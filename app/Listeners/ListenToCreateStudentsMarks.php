<?php

namespace App\Listeners;

use App\Events\InitProcessToCreateStudentsMarksEvent;
use App\Jobs\JobToCreateStudentsMarksIntoDB;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListenToCreateStudentsMarks implements ShouldQueue
{
    public function handle(InitProcessToCreateStudentsMarksEvent $event): void
    {
        JobToCreateStudentsMarksIntoDB::dispatch(
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