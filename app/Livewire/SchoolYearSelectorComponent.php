<?php

namespace App\Livewire;

use App\Events\DataUpdatedEvent;
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
        $activeSchoolYear = SchoolYear::where('is_active', true)->where('is_closed', false)->first();

        if($activeSchoolYear) $this->selectedYear = $activeSchoolYear->slug;

        Session::put('school_year_selected', $this->selectedYear);
    }

    public function updatedSelectedYear(?string $school_year): void
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->where('is_closed', false)->first();

        $selectedSchoolYear = SchoolYear::firstWhere('slug', $school_year);

        $done = false;

        if($selectedSchoolYear){

            $done = $selectedSchoolYear->update(['is_active' => true, 'is_closed' => false]);

        }

        if($activeSchoolYear && $done){

            $activeSchoolYear->update(['is_active' => false]);
        }

        $this->dispatch('yearChanged', $this->selectedYear);

        broadcast(new DataUpdatedEvent(tenant('id')));

        Session::put('school_year_selected', $this->selectedYear);
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
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

            $schoolYears = SchoolYear::orderBy('min_year')->whereNotNull('slug')->get();
            
        }

        return view('livewire.school-year-selector-component', compact('schoolYears'));
    }
}
