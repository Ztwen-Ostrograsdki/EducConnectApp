<?php

namespace App\Livewire\Tenants;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('TABLEAU DE BORD')]
class TenantDashboard extends Component
{
    public ?string $tenant_dashboard_selected_school_year;

    public function mount()
    {
        $year = now()->year;

        $this->tenant_dashboard_selected_school_year = $year - 1 .'-'.$year;
    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->tenant_dashboard_selected_school_year = $schoolYear;
    }

    public function render()
    {
        return view('livewire.tenants.tenant-dashboard');
    }
}
