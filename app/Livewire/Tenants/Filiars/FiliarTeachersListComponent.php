<?php

namespace App\Livewire\Tenants\Filiars;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Models\Filiar;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class FiliarTeachersListComponent extends Component
{
    use WireUiActions, WithPagination, TeachersActions;

    public ?Filiar $filiar;

    public string $filiar_slug;

    public ?string $school_year_selected;

    public ?string $teachers_gender = null;

    public ?int $teachers_subject_id = null;

    public ?int $teachers_promotion_id = null;

    public ?int $teachers_classe_id = null;

    public int $teachersPerPage = 30;


    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    public function resetTeachersFilters()
    {
        $this->reset('teachers_classe_id', 'teachers_subject_id', 'teachers_promotion_id', 'teachers_gender');
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
    public function teachers()
    {
        return $this->filiar->getFiliarTeachersOfSchoolYear(
                                school_year_id: null, 
                                classe_id: $this->teachers_classe_id, 
                                promotion_id: $this->teachers_promotion_id,
                                subject_id : $this->teachers_subject_id, 
                            )
                            ->orderBy('users.name')
                            ->orderBy('users.prenames')
                            ->paginate($this->teachersPerPage);
    }

    public function render()
    {
        return view('livewire.tenants.filiars.filiar-teachers-list-component');
    }
}
