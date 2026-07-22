<?php

namespace App\Livewire\Tenants\Users\Teacher;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ClassesServices\ClasseEffectifsService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;


class TeacherClasseStudentsViewer extends Component
{
    use WireUiActions;

    public $counter = 0;

    public string $classe_slug;

    public string $subject_slug;

    public int $classe_subject_id;

    public function mount(string $classe_slug, string $subject_slug)
    {
        if(!$classe_slug && !$subject_slug) return abort(404);

        $this->classe_slug  = $classe_slug;

        $this->subject_slug  = $subject_slug;

    }


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
    public function classe_subject()
    {
        $classe_subject = ClasseSubjectOfSchoolYear::with(['teacher', 'classe', 'subject'])
                   ->whereHas('teacher', fn($qt) => 
                        $qt->where('id', $this->teacher->id)
                   )
                   ->whereHas('classe', fn($qc) => 
                        $qc->where('slug', $this->classe_slug)
                   )
                   ->whereHas('subject', fn($qs) => 
                        $qs->where('slug', $this->subject_slug)
                )->where('school_year_id', $this->activeYear->id)->whereNull('ended_at')->where('is_active', true)->first();

        if(!$classe_subject) return abort(404);

        return $classe_subject;
    }
    
    #[Computed]
    public function classe()
    {
        if(!$this->classe_slug) return abort(404);

        $classe = Classe::firstWhere('slug', $this->classe_slug);

        if(!$classe) return abort(404);

        return $classe;
    }

    #[Computed]
    public function effectifs()
    {
        $effectifs = app(ClasseEffectifsService::class)->getEffectifs($this->classe->id);

        return $effectifs;
    }
    
    #[Computed]
    public function students()
    {
        return Student::whereHas('yearlyClasseStudents', fn($q) =>
            $q->where('classe_id', $this->classe->id)
              ->where('school_year_id', $this->classe->school_year_id)
              ->where('is_active', true)
        )
        ->orderBy('name')
        ->orderBy('prenames')
        ->get();
    } 
    
    
    #[Computed]
    public function subject()
    {
        if(!$this->subject_slug) return abort(404);

        $subject = Subject::firstWhere('slug', $this->subject_slug);

        if(!$subject) return abort(404);

        return $subject;
    } 
    
    
    #[Computed]
    public function user()
    {
        return auth('tenant')->user();
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    public function render()
    {
        
        return view('livewire.tenants.users.teacher.teacher-classe-students-viewer');
    }
}
