<?php

namespace App\Livewire\Tenants\Filiars;

use App\Livewire\Tenants\ActionsTraits\StudentsActions;
use App\Models\Filiar;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class FiliarStudentsListComponent extends Component
{
    use WireUiActions, WithPagination, StudentsActions;

    public ?Filiar $filiar;

    public string $filiar_slug;

    public ?string $students_gender = null;

    public ?int $students_subject_id = null;

    public ?int $students_promotion_id = null;
    
    public ?int $students_classe_id = null;

    public int $studentsPerPage = 50;

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    public function resetStudentsFilters()
    {
        $this->reset('students_classe_id', 'students_subject_id', 'students_promotion_id', 'students_gender');
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }


    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return $this->filiar->classes()->where('classes.school_year_id', $this->activeYear->id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc')->get();
    }

    #[Computed]
    public function subjects()
    {
        return $this->filiar?->getFiliarSubjectsOfSchoolYear()->orderBy('name', 'desc')->get();
    }
    
    #[Computed]
    public function promotions()
    {
        return $this->filiar?->promotions;
    }

    #[Computed]
    public function students()
    {
        return $this->filiar->getFiliarStudentsOfSchoolYear(
                                school_year_id: null, 
                                classe_id: $this->students_classe_id, 
                                promotion_id: $this->students_promotion_id,
                                gender : $this->students_gender, 
                            )
                            ->orderBy('students.name')
                            ->orderBy('students.prenames')
                            ->paginate($this->studentsPerPage);
    }
    public function render()
    {
        return view('livewire.tenants.filiars.filiar-students-list-component');
    }
}
