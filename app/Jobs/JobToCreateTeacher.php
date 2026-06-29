<?php

namespace App\Jobs;

use App\Events\ATeacherCreationFailedEvent;
use App\Events\DefaultForAnyEvent;
use App\Events\TeacherCreatedEvent;
use App\Events\TeachersCreationStatusUpdatedEvent;
use App\Helpers\Robot;
use App\Jobs\JobToSendCredentialsToUser;
use App\Models\ImportTask;
use App\Models\Teacher;
use App\Models\Tenant;
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

class JobToCreateTeacher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Nombre de tentatives max (retry manuel via bouton).
     */
    public int $tries = 1;

    /**
     * @param string $tenantId
     * @param int    $taskId
     */
    public function __construct(
        public string $tenantId,
        public int    $taskId,
        public ?string $domain = null,
    ) {}

    /**
     * @return void
     */
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

            if(!$director){

                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE DE CREER UN UTILISATEUR AVANT LE COMPTE DIRECTEUR");

                return;

            }


            if(empty($payload['email'])){

                $full_name = $payload['name'] . ' ' .  $payload['prenames'];

                $error_message = "Echec de création de l'espace de l'enseignant " . $full_name . " . Car son adresse mail est manquante!";

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "Erreur création du compte " . $payload['email'],
                    message:           $error_message,
                    type:              'error',
                ));

                $this->fail($error_message);
                return;

            }

            
            if (User::where('email', $payload['email'])->first()) {

                $task->update(['status' => 'failed']);

                $this->fail("Compte est déjà existant dans la base de données!");

                User::first()->notify(new RealTimeNotification(
                    userEmail: $tenant->email,
                    tenantId: $this->tenantId,
                    title:             "Le Compte " . $payload['email'] . " déjà existant dans la base de données!",
                    message:           "Compte est déjà existant dans la base de données!",
                    type:              'error',
                ));

                return;
            }

            $role = Role::firstOrCreate([
                'name'       => 'enseignant',
                'guard_name' => 'tenant',
            ]);

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            $role = $role->fresh();

            $adresse = ($payload['city'] ?? null) && ($payload['department'] ?? null)
                ? $payload['city'] . ' (' . $payload['department'] . ')'
                : null;

            $birth_date = $payload['birth_date'];

            try {

                $birth_date = Carbon::createFromFormat('d/m/Y', $birth_date)->format('Y-m-d');

            } catch (\Exception $e) {

                $birth_date = Carbon::parse($birth_date)->format('Y-m-d');
            }

            $user = User::create([
                'name'                   => $payload['name'],
                'prenames'               => $payload['prenames'],
                'job_name'               => $payload['job_name'],
                'country'                => $payload['country'] ?? null,
                'city'                   => $payload['city'] ?? null,
                'email'                  => $payload['email'],
                'contacts'               => $payload['contacts'] ?? null,
                'gender'                 => $payload['gender'] ?? null,
                'birth_date'             => $birth_date ?? null,
                'adresse'                => $adresse,
                'email_verified_at'      => now(),
                'password'               => Hash::make(Str::random(10)),
            ]);

            $user->assignRole($role);

            try {

                $qr_code_payload =
                [
                    'nom'                    => $payload['name'],
                    'prenoms'                => $payload['prenames'],
                    'pays'                   => $payload['country'] ?? null,
                    'email'                  => $payload['email'],
                    'contacts'               => $payload['contacts'] ?? null,
                    'addresse'               => $adresse,
                    'ecole'                  => $tenant->school_name,
                    'domaine'                => $tenant->domain_name,
                ];

                Teacher::create([
                    'email'                  => $user->email,
                    'identifiant'            => Robot::makeIdentifier($tenant->school_name),
                    'qr_code'                => Robot::makeQrCode($qr_code_payload),
                    'affiliated_at'          => now(),
                    'status'                 => 'active',
                    'user_id'                => $user->id,
                ]);
            } catch (\Throwable $th) {

                $full_name = $payload['name'] . ' ' .  $payload['prenames'];

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "Erreur création du compte " . $payload['email'],
                    message:           cutter($th->getMessage(), 200),
                    type:              'error',
                ));

                $this->fail($th->getMessage());
                return;
            }

            $task->update(['status' => 'success']);

            $can_sent = randomNumber(1, 10);

            if(in_array($can_sent, [1, 3, 7])){

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "COMPTE ENSEIGNANT CREE AVEC SUCCES",
                    message:           "Le compte de l'enseignant " . $user->getUserNamePrefix(true, true) . " a été créé avec succès!",
                    type:              'success',
                ));
            }

            $space_url = get_tenant_url($this->domain, 'login');

            JobToSendCredentialsToUser::dispatch(tenant('id'), $user->email, null, $space_url)->delay(now()->addMinutes(2));

        } finally {

            $batch = $this->batch();

            if (! $batch) {
                return;
            }

            TeachersCreationStatusUpdatedEvent::dispatch(
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

    /**
     * @param Throwable $exception
     * @return void
     */
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

                $teacher = Teacher::firstWhere('email', $task->payload['email']);

                if($user && $teacher){

                    if($teacher && $teacher->status !== 'active' && $user && $user->logged_accout < 1){

                        $teacher?->forceDelete();

                        $user?->forceDelete();
                        
                    }
                }

                $full_name = $payload['name'] . ' ' .  $payload['prenames'];

                ATeacherCreationFailedEvent::dispatch(
                    tenantId: $this->tenantId,
                    taskId: $this->taskId,
                    error: cutter($exception->getMessage(), 200),
                    teacherName: $full_name,
                );

                $director = User::firstWhere('tenant_id', $this->tenantId);

                $director?->notify(new RealTimeNotification(
                    userEmail: $director->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC CRÉATION DU COMPTE ENSEIGNANT ",
                    message:   cutter($exception->getMessage(), 200),
                    type:      'error',
                ));
            }

        } finally {
            tenancy()->end();
        }
    }

}