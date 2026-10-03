<?php

namespace App\Listeners;

use App\Events\InitProcessToShareClasseStudentsNotesEvent;
use App\Jobs\JobToGenerateStudentNotesShareForThePrintViewComponent;
use App\Models\Student;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;

class ListenToShareClasseStudentsNotes
{
    /**
     * Handle the event.
     */
    public function handle(InitProcessToShareClasseStudentsNotesEvent $event): void
    {
        $tenantId = $event->tenantId;

        $jobs = [];

        $studentsIds = Student::whereHas('yearlyClasseStudents', fn($q) =>
            $q->where('classe_id', $event->classeId)
              ->where('school_year_id', $event->schoolYearId)
              ->where('is_active', true)
        )
        ->whereDoesntHave('yearlyStudentsLeaves')
        ->orWhereHas('yearlyStudentsLeaves', fn($req) => 
            $req->where('school_year_id', '<>', $event->schoolYearId)
                ->orWhere('classe_id', '<>', $event->classeId)
                ->whereNull('ended_at')
        )
        ->whereHas('tutors')
        ->pluck('students.id')->toArray();

        if(count($studentsIds) < 1){

            $director = User::firstWhere('tenant_id', $tenantId);

            $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $event->tenantId,
                    title:             "Aucun parent trouvé!",
                    message:           "Les notes n'ont pas été envoyées, car aucun parent|tuteur n'a été trouvé!",
                    type:              'error',
                ));

            return;
        }

        foreach($studentsIds as $student_id){

            $jobs[] = new JobToGenerateStudentNotesShareForThePrintViewComponent (
                tenantId:       $tenantId,
                notifiableId:   $event->notifiableId,
                student_id:     $student_id,
                period:         $event->period,
                subject_id:     $event->subjectId,
                school_year_id: $event->schoolYearId,
                space_url: $event->space_url,

            );
        }


        $batch = Bus::batch([$jobs])
            ->then(function (Batch $batch) use ($tenantId) {
                
            })
            ->finally(function (Batch $batch) use ($tenantId, $studentsIds) {

                if(count($studentsIds)){

                    $director = User::firstWhere('tenant_id', $tenantId);

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director?->email,
                        tenantId: $tenantId,
                        title:             "NOTES ENVOYEES!",
                        message:           "Les notes ont été envoyées à " . count($studentsIds) . " parents!",
                        type:              'success',
                    ));
                }

            })
            ->allowFailures()
            ->name('sharing_classe_students_notes')
            ->dispatch();
    }
}
