<?php

namespace App\Jobs;

use App\Events\AStudentCreationFailedEvent;
use App\Events\StudentCreatedEvent;
use App\Events\StudentsCreationStatusUpdatedEvent;
use App\Models\ImportTask;
use App\Models\Student;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class JobToCreateStudent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

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
            if (Student::where('name', $payload['name'])->where('prenames', $payload['prenames'])->exists()) {

                $task->update(['status' => 'success']);

                AStudentCreationFailedEvent::dispatch(
                    $this->tenantId,
                    $this->taskId,
                    "Cet apprenant est déjà existant dans la base de données!",
                );
                return;
            }

           
            $adresse = ($payload['city'] ?? null) && ($payload['department'] ?? null)
                ? $payload['city'] . ' (' . $payload['department'] . ')'
                : null;

            Student::create([
                'name'              => $payload['name'],
                'prenames'          => $payload['prenames'],
                'country'           => $payload['country'] ?? null,
                'city'              => $payload['city'] ?? null,
                'contacts'          => $payload['contacts'] ?? null,
                'gender'            => $payload['gender'] ?? null,
                'birth_date'        => $payload['birth_date'] ?? null,
                'birth_place'       => $payload['birth_place'] ?? null,
                'adresse'           => $adresse,
            ]);


            $task->update(['status' => 'success']);

            StudentCreatedEvent::dispatch($this->tenantId, $task->id, null);

        } finally {

            $batch = $this->batch();

            if (! $batch) {
                return;
            }

            StudentsCreationStatusUpdatedEvent::dispatch(
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

        if (Student::where('name', $$task->payload['name'])->where('prenames', $$task->payload['prenames'])->exists()) {

            Student::where('name', $$task->payload['name'])->where('prenames', $$task->payload['prenames'])->forceDelete();
        }

        AStudentCreationFailedEvent::dispatch(
            $this->tenantId,
            $this->taskId,
            $exception->getMessage(),
        );
    }

    
}
