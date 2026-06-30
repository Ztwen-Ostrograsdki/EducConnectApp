<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Events\DataUpdatedEvent;
use App\Livewire\Tenants\ActionsTraits\StudentsActions;
use App\Models\Classe;
use App\Models\Student;
use App\Models\YearlyClasseStudent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class ClasseStudentsList extends Component
{
    use WireUiActions, WithPagination, StudentsActions;

    public string  $classroom;
    public ?Classe $classe;
    public int     $perpage  = 30;

    // ─── Filtres ──────────────────────────────────────────────────────
    public string $search = '';
    public string $gender = '';



    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedGender(): void { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->reset(['search', 'gender']);
        $this->resetPage();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
    {
        $this->counter++;
        $this->resetPage();
    }

    
    // ─── Render ───────────────────────────────────────────────────────

    public function render()
    {
        $students = Student::whereHas('yearlyClasseStudents', fn($q) =>
            $q->where('classe_id', $this->classe->id)
              ->where('school_year_id', $this->classe->school_year_id)
              ->where('is_active', true)
        )
        ->when($this->search, fn($q) =>
            $q->where('name', 'like', '%'.$this->search.'%')
              ->orWhere('prenames', 'like', '%'.$this->search.'%')
              ->orWhere('matricule', 'like', '%'.$this->search.'%')
        )
        ->when($this->gender, fn($q) => $q->where('gender', $this->gender))
        ->whereDoesntHave('yearlyStudentsLeaves')
        ->orWhereHas('yearlyStudentsLeaves', fn($req) => 
            $req->where('school_year_id', '<>', $this->classe->school_year_id)
                ->orWhere('classe_id', '<>', $this->classe->id)
                ->whereNull('ended_at')
        )
        ->orderBy('name')
        ->orderBy('prenames')
        ->paginate($this->perpage);


        $leave_students = Student::whereHas('yearlyClasseStudents', fn($q) =>
            $q->where('classe_id', $this->classe->id)
              ->where('school_year_id', $this->classe->school_year_id)
              ->where('is_active', true)
        )
        ->when($this->search, fn($q) =>
            $q->where(fn($q) =>
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('prenames', 'like', '%'.$this->search.'%')
                  ->orWhere('matricule', 'like', '%'.$this->search.'%')
            )
        )
        ->when($this->gender, fn($q) => $q->where('gender', $this->gender))
        ->whereHas('yearlyStudentsLeaves', fn($req) => 
            $req->where('school_year_id', $this->classe->school_year_id)
                ->orWhere('classe_id', $this->classe->id)
                ->whereNull('ended_at')
        )
        ->with(['yearlyStudentsLeaves'])
        ->orderBy('name')
        ->orderBy('prenames')
        ->get();

        return view('livewire.tenants.classes.sections.classe-students-list', compact('students', 'leave_students'));
    }
}
