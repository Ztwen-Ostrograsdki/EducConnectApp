<?php

namespace App\Livewire;

use App\Tools\CentralTools;
use Livewire\Attributes\On;
use Livewire\Component;

class SchoolYearSelectorComponent extends Component
{
    public string|int|null $selectedYear = null;

    public function mount()
    {
        $this->selectedYear = session('school_year_selected');
    }

    public function updatedSelectedYear(): void
    {
        $this->dispatch('yearChanged', $this->selectedYear);

        session()->put('school_year_selected', $this->selectedYear);
    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        
        $this->selectedYear = $schoolYear;
    }

    public function render()
    {
        if(auth()->guard('central')->user()){

            $schoolYears = CentralTools::getSchoolYears();
        }
        if(auth()->guard('tenant')->user()){
            $schoolYears = [
                '2022 - 2023' => '2022 - 2023',
                '2023 - 2024' => '2023 - 2024',
                '2024 - 2025' => '2024 - 2025',
                '2025 - 2026' => '2025 - 2026',
            ];
        }

        return view('livewire.school-year-selector-component', compact('schoolYears'));
    }
}
