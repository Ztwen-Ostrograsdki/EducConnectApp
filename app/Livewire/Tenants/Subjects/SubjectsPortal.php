<?php

namespace App\Livewire\Tenants\Subjects;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class SubjectsPortal extends Component
{
    public ?string $school_year_selected;

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    public function render()
    {
        return view('livewire.tenants.subjects.subjects-portal');
    }
}
