<?php

namespace App\Livewire\Tenants\Schoolyears;

use App\Models\SchoolYear;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Portail des années scolaires')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class SchoolYearsPortal extends Component
{

    public int $counter = 0;

    #[On("NewSchoolYearCreatedLiveEvent")]
    public function newSchoolYearCreated()
    {
        $this->counter++;
    }

    #[On("SchoolYearUpdatedLiveEvent")]
    public function schoolYearUpdated()
    {
        $this->counter++;
    }

    public function render()
    {
        $schoolYears = SchoolYear::orderBy('min_year', 'desc')->whereNotNull('slug')->get();
        
        return view('livewire.tenants.schoolyears.school-years-portal', compact('schoolYears'));
    }
}
