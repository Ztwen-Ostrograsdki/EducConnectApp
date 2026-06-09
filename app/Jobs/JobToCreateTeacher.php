<?php

namespace App\Jobs;

use App\Events\ATeacherCreationFailedEvent;
use App\Events\DefaultForAnyEvent;
use App\Events\TeacherCreatedEvent;
use App\Events\TeachersCreationStatusUpdatedEvent;
use App\Helpers\Robot;
use App\Models\ImportTask;
use App\Models\Teacher;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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

            // Vide le cache Spatie immédiatement après init de la tenancy
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            // Variable locale — pas de propriété publique
            $task = ImportTask::findOrFail($this->taskId);

            if ($this->batch()?->cancelled()) {
                return;
            }

            $task->update([
                'status'   => 'pending',
                'attempts' => $task->attempts + 1,
            ]);

            $payload = $task->payload;


            // Anti-doublon
            if (User::where('email', $payload['email'])->first()) {

                $task->update(['status' => 'failed']);

                $this->fail("Compte est déjà existant dans la base de données!");
                return;
            }

            // 1. Rôle en premier
            $role = Role::firstOrCreate([
                'name'       => 'enseignant',
                'guard_name' => 'tenant',
            ]);

            // 2. Vide le cache après firstOrCreate
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            // 3. Recharge depuis la DB pour être sûr
            $role = $role->fresh();

            // 4. Création du user
            $adresse = ($payload['city'] ?? null) && ($payload['department'] ?? null)
                ? $payload['city'] . ' (' . $payload['department'] . ')'
                : null;

            $user = User::create([
                'name'                   => $payload['name'],
                'prenames'               => $payload['prenames'],
                'job_name'               => $payload['job_name'],
                'country'                => $payload['country'] ?? null,
                'city'                   => $payload['city'] ?? null,
                'email'                  => $payload['email'],
                'contacts'               => $payload['contacts'] ?? null,
                'gender'                 => $payload['gender'] ?? null,
                'birth_date'             => $payload['birth_date'] ?? null,
                'adresse'                => $adresse,
                'email_verified_at'      => now(),
                'password'               => Hash::make(Str::random(10)),
            ]);

            $user->assignRole($role);

            try {

                $tenant = tenancy()->tenant;

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
                ATeacherCreationFailedEvent::dispatch(
                    $this->tenantId,
                    $this->taskId,
                    $th->getMessage(),
                );

                $this->fail($th->getMessage());
                return;
            }

            // 5. Assignation via objet directement
            
            $task->update(['status' => 'success']);

            TeacherCreatedEvent::dispatch($this->tenantId, $task->id, null);

        } finally {

            tenancy()->end();

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

        }
    }

    /**
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception): void
    {

        $task = ImportTask::find($this->taskId);

        if ($task) {
            $task->update([
                'status' => 'failed',
                'error'  => $exception->getMessage(),
            ]);

        }

        User::where('email', $task->payload['email'])?->forceDelete();

        Teacher::where('email', $task->payload['email'])?->forceDelete();

        ATeacherCreationFailedEvent::dispatch(
            $this->tenantId,
            $this->taskId,
            $exception->getMessage(),
        );
    }

}