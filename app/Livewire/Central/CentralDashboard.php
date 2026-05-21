<?php

namespace App\Livewire\Central;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('livewire.layouts.central-auth-layout')]
class CentralDashboard extends Component
{

    public ?string $central_dashboard_selected_school_year;

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        
        $this->central_dashboard_selected_school_year = $schoolYear;
    }

    
    public function render()
    {
        return view('livewire.central.central-dashboard');
    }
}
