<?php

namespace App\Jobs;

use App\Events\ATutorCreationFailedEvent;
use App\Events\TutorsCreationStatusUpdatedEvent;
use App\Helpers\Robot;
use App\Jobs\JobToSendCredentialsToUser;
use App\Models\ImportTask;
use App\Models\Tutor;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

class JobToCreateTutor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 1;

    public function __construct(
        public string $tenantId,
        public int $taskId,
        public ?string $domain = null,
    ) {}

    public function handle(): void
    {
        try {
            tenancy()->initialize($this->tenantId);

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            $task = ImportTask::findOrFail($this->taskId);

            if ($this->batch()?->cancelled()) {
                return;
            }

            $task->update([
                'status'   => 'pending',
                'attempts' => $task->attempts + 1,
            ]);

            $payload = $task->payload;

            $tenant = tenancy()->tenant;

            $director = User::first();

            if (! $director) {
                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE DE CREER UN UTILISATEUR AVANT LE COMPTE DIRECTEUR");
                return;
            }

            if (empty($payload['email'])) {
                $full_name = $payload['name'] . ' ' . $payload['prenames'];
                $error_message = "Echec de création de l'espace du tuteur " . $full_name . " . Car son adresse mail est manquante!";

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

            if (User::where('email', $payload['email'])->first()) {
                $task->update(['status' => 'failed']);

                $this->fail("Compte est déjà existant dans la base de données!");

                $director?->notify(new RealTimeNotification(
                    userEmail: $tenant->email,
                    tenantId:  $this->tenantId,
                    title:     "Le Compte " . $payload['email'] . " déjà existant dans la base de données!",
                    message:   "Compte est déjà existant dans la base de données!",
                    type:      'error',
                ));

                return;
            }

            $role = Role::firstOrCreate([
                'name'       => 'tuteur',
                'guard_name' => 'tenant',
            ]);

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            $role = $role->fresh();

            $adresse = ($payload['city'] ?? null) && ($payload['department'] ?? null)
                ? $payload['city'] . ' (' . $payload['department'] . ')'
                : null;

            $birth_date = $payload['birth_date'] ?? null;

            if ($birth_date) {
                try {
                    $birth_date = Carbon::createFromFormat('d/m/Y', $birth_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    $birth_date = Carbon::parse($birth_date)->format('Y-m-d');
                }
            }

            $user = User::create([
                'name'              => $payload['name'],
                'prenames'          => $payload['prenames'],
                'job_name'          => $payload['job_name'] ?? null,
                'country'           => $payload['country'] ?? null,
                'city'              => $payload['city'] ?? null,
                'email'             => $payload['email'],
                'contacts'          => $payload['contacts'] ?? null,
                'gender'            => $payload['gender'] ?? null,
                'birth_date'        => $birth_date,
                'adresse'           => $adresse,
                'email_verified_at' => now(),
                'password'          => Hash::make(Str::random(10)),
            ]);

            $user->assignRole($role);

            try {
                $qr_code_payload = [
                    'nom'      => $payload['name'],
                    'prenoms'  => $payload['prenames'],
                    'pays'     => $payload['country'] ?? null,
                    'email'    => $payload['email'],
                    'contacts' => $payload['contacts'] ?? null,
                    'addresse' => $adresse,
                    'ecole'    => $tenant->school_name,
                    'domaine'  => $tenant->domain_name,
                ];

                Tutor::create([
                    'email'         => $user->email,
                    'qr_code'       => Robot::makeQrCode($qr_code_payload),
                    'affiliated_at' => now(),
                    'is_active'     => true,
                    'user_id'       => $user->id,
                ]);
            } catch (\Throwable $th) {
                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId:  $this->tenantId,
                    title:     "Erreur création du compte " . $payload['email'],
                    message:   cutter($th->getMessage(), 200),
                    type:      'error',
                ));

                $this->fail($th->getMessage());
                return;
            }

            $task->update(['status' => 'success']);

            $can_sent = randomNumber(1, 10);

            if (in_array($can_sent, [1, 3, 7])) {
                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId:  $this->tenantId,
                    title:     "COMPTE TUTEUR CREE AVEC SUCCES",
                    message:   "Le compte du tuteur " . $user->getUserNamePrefix(true, true) . " a été créé avec succès!",
                    type:      'success',
                ));
            }

            $space_url = get_tenant_url($this->domain, 'login');

            JobToSendCredentialsToUser::dispatch(
                tenantId: tenant('id'), 
                userEmail: $user->email, 
                default_password: null, 
                space_url : $space_url,
                type_of_space: 'Parent/Tuteur' 
            )->delay(now()->addMinutes(2));

        } finally {
            $batch = $this->batch();

            if (! $batch) {
                return;
            }

            TutorsCreationStatusUpdatedEvent::dispatch(
                tenantId:   $this->tenantId,
                batchId:    $batch->id,
                totalJobs:  $batch->totalJobs,
                processed:  $batch->processedJobs(),
                percentage: $batch->progress(),
                failed:     $batch->failedJobs,
            );

            tenancy()->end();
        }
    }

    public function failed(Throwable $exception): void
    {
        tenancy()->initialize($this->tenantId);

        try {
            $task = ImportTask::find($this->taskId);

            if ($task) {
                $payload = $task->payload;

                $task->update([
                    'status' => 'failed',
                    'error'  => $exception->getMessage(),
                ]);

                $user = User::firstWhere('email', $task->payload['email']);
                $tutor = Tutor::firstWhere('email', $task->payload['email']);

                if ($user && $tutor) {
                    if ($tutor->is_active !== true && $user->logged_accout < 1) {
                        $tutor?->forceDelete();
                        $user?->forceDelete();
                    }
                }

                $full_name = $payload['name'] . ' ' . $payload['prenames'];


                $director = User::firstWhere('tenant_id', $this->tenantId);

                $director?->notify(new RealTimeNotification(
                    userEmail: $director->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC CRÉATION DU COMPTE TUTEUR : " . $full_name,
                    message:   cutter($exception->getMessage(), 200),
                    type:      'error',
                ));
            }
        } finally {
            tenancy()->end();
        }
    }
}