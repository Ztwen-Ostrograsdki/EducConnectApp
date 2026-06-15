<?php

namespace App\Listeners;

use App\Events\InitProcessToCreateStudentsEvent;
use App\Events\ProcessToCreateStudentsCompletedSuccesfullyEvent;
use App\Events\StudentsCreationTaskStartedEvent;
use App\Jobs\JobToCreateStudent;
use App\Models\ImportTask;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;

class ListenToInitProcessToCreateStudentsEvent
{
    /**
     * Handle the event.
     */
    public function handle(InitProcessToCreateStudentsEvent $event): void
    {
        $tenantId = $event->tenantId;

        $batch = Bus::batch([])
            ->then(function (Batch $batch) use ($tenantId) {
                
            })
            ->finally(function (Batch $batch) use ($tenantId) {

                ProcessToCreateStudentsCompletedSuccesfullyEvent::dispatch(
                    tenantId:   $tenantId,
                    batchId:    $batch->id,
                    totalJobs:  $batch->totalJobs,
                    processed:  $batch->processedJobs(),
                    percentage: $batch->progress(),
                    failed:     $batch->failedJobs,
                );
                
            })
            ->allowFailures()
            ->name('students_creation')
            ->dispatch();

        $jobs = collect($event->students)->map(function ($studentData) use ($batch, $tenantId, $event) {
            $task = ImportTask::create([
                'batch_id'  => $batch->id,
                'payload'   => $studentData,
                'status'    => 'pending',
                'task_name' => 'students-creation',
                'error'     => null,
                'attempts'  => 0,
                'crud'      => 'create',
            ]);

            return new JobToCreateStudent(
                tenantId: $tenantId,
                taskId:   $task->id,
                domain:   $event->domain,
            );
        });

        StudentsCreationTaskStartedEvent::dispatch(
            tenantId:  $tenantId,
            batchId:   $batch->id,
            totalJobs: $jobs->count(),
        );

        $batch->add($jobs);
    }
}
