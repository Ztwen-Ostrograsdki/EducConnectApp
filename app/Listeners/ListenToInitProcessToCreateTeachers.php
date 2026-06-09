<?php

namespace App\Listeners;

use App\Events\InitProcessToCreateTeachersEvent;
use App\Events\ProcessToCreateTeachersCompletedSuccesfullyEvent;
use App\Events\TeachersCreationProcessProgressEvent;
use App\Events\TeachersCreationTaskStartedEvent;
use App\Jobs\JobToCreateTeacher;
use App\Models\ImportTask;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ListenToInitProcessToCreateTeachers
{
    /**
     * @param InitProcessToCreateTeachersEvent $event
     * @return void
     */
    public function handle(InitProcessToCreateTeachersEvent $event): void
    {
        $tenantId = $event->tenantId;

        $batch = Bus::batch([])
            ->then(function (Batch $batch) use ($tenantId) {
                
            })
            ->finally(function (Batch $batch) use ($tenantId) {

                ProcessToCreateTeachersCompletedSuccesfullyEvent::dispatch(
                    tenantId:   $tenantId,
                    batchId:    $batch->id,
                    totalJobs:  $batch->totalJobs,
                    processed:  $batch->processedJobs(),
                    percentage: $batch->progress(),
                    failed:     $batch->failedJobs,
                );
                
            })
            ->allowFailures()
            ->name('teachers_creation')
            ->dispatch();

        $jobs = collect($event->teachers)->map(function ($teacherData) use ($batch, $tenantId, $event) {
            $task = ImportTask::create([
                'batch_id'  => $batch->id,
                'payload'   => $teacherData,
                'status'    => 'pending',
                'task_name' => 'teachers-creation',
                'error'     => null,
                'attempts'  => 0,
                'crud'      => 'create',
            ]);

            return new JobToCreateTeacher(
                tenantId: $tenantId,
                taskId:   $task->id,
                domain:   $event->domain,
            );
        });

        TeachersCreationTaskStartedEvent::dispatch(
            tenantId:  $tenantId,
            batchId:   $batch->id,
            totalJobs: $jobs->count(),
        );

        $batch->add($jobs);

        
    }
}