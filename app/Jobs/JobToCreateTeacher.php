<?php

namespace App\Jobs;

use App\Events\ATeacherCreationFailedEvent;
use App\Events\DefaultForAnyEvent;
use App\Events\TeacherCreatedEvent;
use App\Events\TeachersCreationStatusUpdatedEvent;
use App\Helpers\Robot;
use App\Models\ImportTask;
use App\Models\Teacher;
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
    public int $tries = 2;

    /**
     * @param string $tenantId
     * @param int    $taskId
     */
    public function __construct(
        public string $tenantId,
        public int    $taskId,
        public ?string $domain,
    ) {}

    /**
     * @return void
     */
    public function handle(): void
    {

        try {
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
            if (User::where('email', $payload['email'])->exists()) {

                $task->update(['status' => 'success']);

                ATeacherCreationFailedEvent::dispatch(
                    $this->tenantId,
                    $this->taskId,
                    "Ce compte est déjà existant dans la base de données!",
                );
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

            $teacher = Teacher::create([
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
                'identifiant'            => Robot::makeIdentifier(),
                'qr_code'                => Robot::makeQrcode(),
                'affiliated_at'          => now(),
                'status'                 => 'active',
            ]);

            // 5. Assignation via objet directement
            if($user && $teacher){

                $user->assignRole($role);

                $task->update(['status' => 'success']);

                TeacherCreatedEvent::dispatch($this->tenantId, $task->id, null);
            }

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