<?php

namespace App\Livewire;

use App\Models\SchoolYear;
use App\Tools\CentralTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class SchoolYearSelectorComponent extends Component
{
    public string|int|null $selectedYear = null;

    public int $counter = 0;

    public function mount()
    {
        $this->selectedYear = session('school_year_selected');
    }

    public function updatedSelectedYear(): void
    {
        $this->dispatch('yearChanged', $this->selectedYear);

        Session::put('school_year_selected', $this->selectedYear);
    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        
        $this->selectedYear = $schoolYear;
    }


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
        $schoolYears = [];

        if(Auth::guard('central')->user()){

            $schoolYears = CentralTools::getSchoolYears();
        }
        if(Auth::guard('tenant')->user()){

            $schoolYears = SchoolYear::orderBy('min_year')->whereNotNull('slug')->pluck('slug')->toArray();
            
        }

        return view('livewire.school-year-selector-component', compact('schoolYears'));
    }
}
