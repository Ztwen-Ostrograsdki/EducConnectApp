<?php

namespace App\Livewire\Tenants\Classes\Sections;

use Livewire\Attributes\On;
use Livewire\Component;

class ClassePupilBulletinComponent extends Component
{
    public ?string $student_uuid;

    public ?string $classroom;

    public ?string $period_type;

    public ?string $school_year_selected;

    public function render()
    {
        $this->school_year_selected = session('school_year_selected');

        return view('livewire.tenants.classes.sections.classe-pupil-bulletin-component');
    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }


    #[On('ReloadTheStudentBulletin')]
    public function onReloadTheStudentBulletin(?string $period_type_selected, ?string $student_uuid_selected)
    {
        $this->period_type = $period_type_selected;

        $this->student_uuid = $student_uuid_selected;
    }
}
