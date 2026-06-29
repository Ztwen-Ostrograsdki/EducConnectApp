<?php

namespace App\Listeners;

use App\Events\InitProcessToGrantYearlyAccessToTeachersEvent;
use App\Jobs\JobToCreateYearlyAccessForTeacher;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;

class ListenToGrantYearlyAccessToTeachers
{
    /**
     * Handle the event.
     */
    public function handle(InitProcessToGrantYearlyAccessToTeachersEvent $event): void
    {
        $tenantId = $event->tenantId;

        $query = Teacher::withoutTrashed()->where('status', 'active');

        if(!$event->school_year_id) $schoolYearId = SchoolYear::current()?->first()?->id;

        else $schoolYearId = $event->school_year_id;

        if($event->onlyForSubjects){

            $query->whereHas('yearlySubjects', fn($q) => 
                $q->where('teacher_yearly_subjects.school_year_id', $schoolYearId)
                  ->whereIn('teacher_yearly_subjects.subject_id', $event->onlyForSubjects)
                  ->where('teacher_yearly_subjects.is_active', true)
            );
        }

        $query->whereDoesntHave('yearlyAccesses', fn($q) => 
                $q->where('teacher_yearly_accesses.school_year_id', $schoolYearId)
                  ->whereNull('teacher_yearly_accesses.suspended_at')
            );

        if($event->excepts) $query->whereNotIn('teachers.id', $event->excepts);

        $jobs = collect($query->pluck('teachers.id')->toArray())->map(function ($teacherId) use ($tenantId, $event) {
            
            return new JobToCreateYearlyAccessForTeacher(
                tenantId: $tenantId,
                teacherId:   $teacherId,
                school_year_id: $event->school_year_id,
                domain:   $event->domain,
            );
        });

        if(count($jobs) < 1){

            $director = User::firstWhere('tenant_id', $event->tenantId);

            $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $event->tenantId,
                    title:             "Aucun enseignant sans accès trouvé!",
                    message:           "Tous les enseignants ont déjà un accès pour cette année scolaire!",
                    type:              'error',
                ));

            return;
        }

        Bus::batch($jobs)
        
            ->then(function (Batch $batch) use ($tenantId) {
                
            })
            ->finally(function (Batch $batch) use ($tenantId) {

               
                
            })
            ->allowFailures()
            ->name('create_teachers_accesses')
            ->dispatch();

    }
}
