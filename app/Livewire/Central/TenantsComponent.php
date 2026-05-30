<?php

namespace App\Livewire\Central;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('livewire.layouts.central-auth-layout')]
class TenantsComponent extends Component
{
    public $counter = 3;
    
    public function render()
    {
        return view('livewire.central.tenants-component');
    }

    #[On('LiveReloadDashboardEvent')]
    public function onReloadDashboard()
    {
        $this->counter = randomNumber();
    }
}
