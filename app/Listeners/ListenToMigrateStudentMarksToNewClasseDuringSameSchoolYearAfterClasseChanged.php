<?php

namespace App\Listeners;

use App\Events\InitProcessToMigrateStudentMarksToNewClasseEvent;
use App\Jobs\JobToMigrateStudentMarksToHisNewClasseAfterMigrationToNewClasseDuringTheSameSchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ListenToMigrateStudentMarksToNewClasseDuringSameSchoolYearAfterClasseChanged
{

    /**
     * Handle the event.
     */
    public function handle(InitProcessToMigrateStudentMarksToNewClasseEvent $event): void
    {
        Bus::chain(
            [new JobToMigrateStudentMarksToHisNewClasseAfterMigrationToNewClasseDuringTheSameSchoolYear($event->tenantId, $event->student_id, $event->oldClasseId, $event->school_year_id, $event->author_id)]
        )
        ->catch(function (Throwable $e) use ($event) {

            $director = User::firstWhere('tenant_id', $event->tenantId);

            $director?->notify(new RealTimeNotification(
                userEmail: $director?->email,
                tenantId: $event->tenantId,
                title:             "Une erreure est survenue lors de la migration des notes",
                message:           "Details : " . cutter($e->getMessage(), 2000),
                type:              'error',
            ));
        })
        ->dispatch();
    }
}
