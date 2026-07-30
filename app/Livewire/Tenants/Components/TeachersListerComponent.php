<?php

namespace App\Livewire\Tenants\Components;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Models\Filiar;
use App\Models\SchoolYear;
use App\Models\Serial;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class TeachersListerComponent extends Component
{
    use WireUiActions, WithPagination, TeachersActions;

    public ?Filiar $filiar;

    public ?Serial $serial;

    public int $filiar_id;

    public int $serial_id;

    public string $filiar_slug;

    public ?string $school_year_selected;

    public ?string $teachers_gender = null;

    public ?int $teachers_subject_id = null;

    public ?int $teachers_promotion_id = null;

    public ?int $teachers_classe_id = null;

    public int $teachersPerPage = 30;

    public int $counter = 0;


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
    public function teachers()
    {
        if($this->filiar && $this->filiar_id){
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
        elseif($this->serial && $this->serial_id){
            return $this->serial->getSerialTeachersOfSchoolYear(
                                school_year_id: null, 
                                classe_id: $this->teachers_classe_id, 
                                promotion_id: $this->teachers_promotion_id,
                                subject_id : $this->teachers_subject_id, 
                            )
                            ->orderBy('users.name')
                            ->orderBy('users.prenames')
                            ->paginate($this->teachersPerPage);
        }
        
        return [];
    }
    
    public function render()
    {
        return view('livewire.tenants.components.teachers-lister-component');
    }
}
