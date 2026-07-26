<?php

namespace App\Listeners;

use App\Events\InitProcessToUpdateStudentsMarksEvent;
use App\Jobs\JobToUpdateStudentsMarksIntoDB;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListenToUpdateStudentsMarks implements ShouldQueue
{
    public function handle(InitProcessToUpdateStudentsMarksEvent $event): void
    {
        JobToUpdateStudentsMarksIntoDB::dispatch(
            tenantId: $event->tenantId,
            teacherId: $event->teacherId,
            classeId: $event->classeId,
            subjectId: $event->subjectId,
            period: $event->period,
            data: $event->data,
            schoolYearId: $event->schoolYearId,
        );
    }
}