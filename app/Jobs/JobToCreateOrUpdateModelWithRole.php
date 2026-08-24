<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Helpers\Robot;
use App\Models\Teacher;
use App\Models\Tutor;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

#[Timeout(300)]
class JobToCreateOrUpdateModelWithRole implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $tenantId,
        public string $role,
        public int $userId,
        public ?array $data = [],
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            tenancy()->initialize($this->tenantId);

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            if ($this->batch()?->cancelled()) {
                return;
            }

            $done = false;

            $payload = $this->data;

            $tenant = tenancy()->tenant;

            $director = User::first();

            if (! $director) {

                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE DE FAIRE UNE QUELCONQUE ACTION SUR LA BASE DE DONNEES AVANT LE COMPTE DIRECTEUR");

                return;
            }

            $user = User::find($this->userId);

            if (!$user) {

                $full_name = $payload['full_name'];
                
                $error_message = "Echec de création de l'espace du {$this->role} " . $full_name . " . Compte introuvable";

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId:  $this->tenantId,
                    title:     "Erreur création du compte " . ($payload['email'] ?? ''),
                    message:   $error_message,
                    type:      'error',
                ));

                $this->fail($error_message);

                return;
            }

            $role = Role::firstOrCreate([
                'name'       => str()->lower($this->role),
                'guard_name' => 'tenant',
            ]);

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            $role = $role->fresh();

            $birth_date = $user->birth_date;


            if ($birth_date) {
                try {
                    $birth_date = Carbon::createFromFormat('d/m/Y', $birth_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    $birth_date = Carbon::parse($birth_date)->format('Y-m-d');
                }
            }

            try {
                $qr_code_payload = [
                    'nom'      => $user->name,
                    'prenoms'  => $user->prenames,
                    'pays'     => $user->country ?? null,
                    'email'    => $user->email,
                    'contacts' => $user->contacts ?? null,
                    'addresse' => $user->addresse,
                    'ecole'    => $tenant->school_name,
                    'domaine'  => $tenant->domain_name,
                ];

                if(str()->lower($this->role) === 'tuteur'){

                    if($user->hasRole('tuteur')){


                        return;
                    }

                    $done = Tutor::create([
                        'email'         => $user->email,
                        'qr_code'       => Robot::makeQrCode($qr_code_payload),
                        'affiliated_at' => now(),
                        'is_active'     => true,
                        'user_id'       => $user->id,
                    ]);
                }
                elseif(str()->lower($this->role) === 'enseignant'){

                    if($user->hasRole('enseignant')){


                        return;
                    }

                    $done = Teacher::create([
                        'email'                  => $user->email,
                        'identifiant'            => Robot::makeIdentifier($tenant->school_name),
                        'qr_code'                => Robot::makeQrCode($qr_code_payload),
                        'affiliated_at'          => now(),
                        'status'                 => 'active',
                        'user_id'                => $user->id,
                    ]);

                }

            } catch (\Throwable $th) {

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId:  $this->tenantId,
                    title:     "Erreur création du compte " . $payload['email'],
                    message:   cutter($th->getMessage(), 200),
                    type:      'error',
                ));

                $this->fail($th->getMessage());

                throw $th;

            }

            if ($done) {

                $user->assignRole($role);

                $role = str()->upper($this->role);

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId:  $this->tenantId,
                    title:     "COMPTE {$role} CREE AVEC SUCCES",
                    message:   "Le compte du " . $user->getUserNamePrefix(true, true) . " a été créé avec succès!",
                    type:      'success',
                ));
            }


        }

        catch (Throwable $th){

            $this->fail(cutter($th->getMessage(), 2000));
        }
        
        finally {
            
            broadcast(new DataUpdatedEvent(($this->tenantId)));

            tenancy()->end();
        }
    }

    public function failed(Throwable $exception): void
    {
        tenancy()->initialize($this->tenantId);

        try {
            $director = User::first();

            $role = str()->upper($this->role);

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "ECHEC CRÉATION DU COMPTE {$role} : " . $this->data['full_name'],
                message:   cutter($exception->getMessage(), 200),
                type:      'error',
            ));
            
        } finally {

            tenancy()->end();
        }
    }
}
