<?php

namespace App\Livewire\Tenants\Students;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Notes de classe pour apprenant")]
class StudentMarksComponent extends Component
{

    public int $counter = 1;

    public ?string $student_name;

    public ?string $student_uuid;

    public ?string $classe_slug;

    public ?string $period_type_selected;

    public ?string $school_year_selected;

    public function mount(string $student_uuid)
    {
        $this->student_uuid = $student_uuid;

        $this->classe_slug = $student_uuid;
    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    #[On("StudentDataUpdatedEventLiveEvent")]
    public function studentDataUpdated()
    {
        $this->counter++;
    }

    public function render()
    {
        return view('livewire.tenants.students.student-marks-component');
    }
}
