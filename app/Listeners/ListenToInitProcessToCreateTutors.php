<?php

namespace App\Listeners;

use App\Events\InitProcessToCreateTutorsEvent;
use App\Events\ProcessToCreateTutorsCompletedSuccesfullyEvent;
use App\Events\TutorsCreationTaskStartedEvent;
use App\Jobs\JobToCreateTutor;
use App\Models\ImportTask;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class ListenToInitProcessToCreateTutors
{
    public function handle(InitProcessToCreateTutorsEvent $event): void
    {
        $tenantId = $event->tenantId;

        $batch = Bus::batch([])
            ->then(function (Batch $batch) use ($tenantId) {
                //
            })
            ->finally(function (Batch $batch) use ($tenantId) {
                ProcessToCreateTutorsCompletedSuccesfullyEvent::dispatch(
                    tenantId:   $tenantId,
                    batchId:    $batch->id,
                    totalJobs:  $batch->totalJobs,
                    processed:  $batch->processedJobs(),
                    percentage: $batch->progress(),
                    failed:     $batch->failedJobs,
                );
            })
            ->allowFailures()
            ->name('tutors_creation')
            ->dispatch();

        $jobs = collect($event->tutors)->map(function ($tutorData) use ($batch, $tenantId, $event) {
            $task = ImportTask::create([
                'batch_id'  => $batch->id,
                'payload'   => $tutorData,
                'status'    => 'pending',
                'task_name' => 'tutors-creation',
                'error'     => null,
                'attempts'  => 0,
                'crud'      => 'create',
            ]);

            return new JobToCreateTutor(
                tenantId: $tenantId,
                taskId:   $task->id,
                domain:   $event->domain,
            );
        });

        TutorsCreationTaskStartedEvent::dispatch(
            tenantId:  $tenantId,
            batchId:   $batch->id,
            totalJobs: $jobs->count(),
        );

        $batch->add($jobs);
    }
}