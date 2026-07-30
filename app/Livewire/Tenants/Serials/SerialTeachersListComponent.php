<?php

namespace App\Livewire\Tenants\Serials;


use App\Models\SchoolYear;
use App\Models\Serial;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class SerialTeachersListComponent extends Component
{
    use WireUiActions;

    public ?Serial $serial;

    public string $serial_slug;

    public ?string $school_year_selected;

    public ?string $teachers_gender = null;

    public ?int $teachers_subject_id = null;

    public ?int $teachers_promotion_id = null;

    public ?int $teachers_classe_id = null;

    public int $teachersPerPage = 30;

    public $counterh = 0;


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
        $this->counterh++;
    }


    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return $this->serial->classes()->where('classes.school_year_id', $this->activeYear->id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc')->get();
    }

    #[Computed]
    public function subjects()
    {
        return $this->serial?->getSerialSubjectsOfSchoolYear()->orderBy('name', 'desc')->get();
    }
    
    #[Computed]
    public function promotions()
    {
        return $this->serial?->promotions;
    }


    public function render()
    {
        return view('livewire.tenants.serials.serial-teachers-list-component');
    }
}
