<?php

namespace App\Livewire\Tenants\Classes;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\YearlyClasseStudent;
use App\Services\ClassesServices\ClasseEffectifsService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Profil de classe ou groupe pédagogique')]
class ClasseProfil extends Component
{
    use WireUiActions;
    
    public $section = 'classe-home-page';

    public $classroom = '';

    public $counter = 0;

    public ?int $student_id = null;

    public ?Classe $classe;

    public ?Student $student;

    public string $classe_slug;

    public ?int $period = null;

    public function mount(string $classe_slug)
    {
        if(!$classe_slug) return abort(404);

        $this->classe_slug  = $classe_slug;

        $classe = Classe::withTrashed()->whereSlug($classe_slug)?->first();

        if(!$classe) return abort(404);

        $this->classe       = $classe;

        $this->classroom       = $classe->name;

        if (session()->has('tenant_classe_section_selected')) {

            $this->section = session('tenant_classe_section_selected');
        }

        if (session()->has('tenant_classe_bulletin_period')) {

            $this->period = session('tenant_classe_bulletin_period');
        }

        if (session()->has('tenant_classe_bulletin_student_id')) {

            $this->student_id = session('tenant_classe_bulletin_student_id');

            if($this->student_id) $this->student = Student::find($this->student_id);
        }

    }

    #[Computed]
    public function periods_types()
    {
        return $this->activeYear->getPeriods();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
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
        ->whereDoesntHave('yearlyStudentsLeaves')
        ->orWhereHas('yearlyStudentsLeaves', fn($req) => 
            $req->where('school_year_id', '<>', $this->classe->school_year_id)
                ->orWhere('classe_id', '<>', $this->classe->id)
                ->whereNull('ended_at')
        )
        ->orderBy('name')
        ->orderBy('prenames')
        ->get();
    }

    public function setSection(string $section)
    {
        session()->put('tenant_classe_section_selected', $section);

        $this->section = $section;
    }

    public function updatedStudentId(?int $student_id)
    {
        $this->student = null;

        session()->put('tenant_classe_bulletin_student_id', $student_id);

        if($student_id) $this->student = Student::find($student_id);

        else $this->student = null;

        $this->dispatch("ReloadForNewStudent", $this->period, $this->student_id, $this->classe->id);
    }

    public function updatedPeriod(?int $period)
    {
        session()->put('tenant_classe_bulletin_period', $period);

        $this->dispatch("ReloadForNewStudent", $this->period, $this->student_id, $this->classe->id);

    }

    public function reloadStudentBulletin()
    {
        $this->dispatch('ReloadTheStudentBulletin', $this->period, $this->student_id);
    }

    public function resetBulletinSelections()
    {
        $this->reset('student_id', 'period');

        session()->forget('tenant_classe_bulletin_period');

        session()->forget('tenant_classe_bulletin_student_id');

        $this->dispatch('ReloadTheStudentBulletin', null, null);
    }

    public function render()
    {
        return view('livewire.tenants.classes.classe-profil');
    }
}
