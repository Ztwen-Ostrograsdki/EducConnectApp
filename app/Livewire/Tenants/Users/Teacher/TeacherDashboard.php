<?php

namespace App\Livewire\Tenants\Users\Teacher;

use App\Models\Mark;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;


class TeacherDashboard extends Component
{
    use WireUiActions;

    public $counter = 0;


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }
    
    #[On('TeacherWasBlockedLiveEvent')]
    public function teacherBlocked()
    {
        $this->notification()->send([
            'icon'        => 'warning',
            'title'       => "Votre compte enseignant a été bloqué",
            'timeout' => 0,
        ]);
        
        $this->counter++;

        return $this->redirect(route('tenant.my.profil'));
    }


    #[Computed]
    public function teacher()
    {
        return auth('tenant')->user()->teacher;
    } 
    
    
    #[Computed]
    public function user()
    {
        return auth('tenant')->user();
    }

    #[Computed]
    public function classes()
    {
        return $this->teacher?->getTeacherClassesWithSubjectsForThisSchoolYear();
    }
    
    
    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }
    
    
    #[Computed]
    public function teachersMarksCount(): ?array
    {
        $marks = [];

        foreach($this->classes as $rel){

            $counts = Mark::query()
                ->where('school_year_id', $this->activeYear->id)
                ->where('period', $this->activeYear->active_period)
                ->where('classe_id', $rel->classe_id)
                ->where('teacher_id', $this->teacher->id)
                ->select('subject_id', DB::raw('COUNT(*) as marks_count'))
                ->groupBy('subject_id')
                ->pluck('marks_count', 'subject_id')->toArray();

            $marks[$rel->classe_id] = $counts;
        }

        return $marks;
    }









    public function render()
    {
        return view('livewire.tenants.users.teacher.teacher-dashboard');
    }
}
