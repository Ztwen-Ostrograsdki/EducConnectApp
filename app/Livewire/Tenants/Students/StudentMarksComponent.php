<?php

namespace App\Livewire\Tenants\Students;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;


#[Layout('livewire.layouts.tenant-auth-layout')]
class StudentMarksComponent extends Component
{
    public ?string $student_name, $student_uuid, $classe_slug;

    public ?string $period_type_selected, $school_year_selected; 


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

    public function render()
    {
        return view('livewire.tenants.students.student-marks-component');
    }
}
