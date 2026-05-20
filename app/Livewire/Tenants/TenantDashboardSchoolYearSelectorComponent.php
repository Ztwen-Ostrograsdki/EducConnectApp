<?php

namespace App\Livewire\Tenants;

use Livewire\Component;

class TenantDashboardSchoolYearSelectorComponent extends Component
{
    public string|int|null $selectedYear;


    public function updatedSelectedYear(): void
    {
        $this->dispatch('yearChanged', $this->selectedYear);

        session()->put('school_year_selected', $this->selectedYear);
    }

    public function render()
    {
        $schoolYears = [
            '2022-2023' => '2022-2023',
            '2023-2024' => '2023-2024',
            '2024-2025' => '2024-2025',
            '2025-2026' => '2025-2026',
        ];

        if(session()->has('school_year_selected')){

            $this->selectedYear = session('school_year_selected');

        }

        return view('livewire.tenants.tenant-dashboard-school-year-selector-component', compact('schoolYears'));
    }
    
}
