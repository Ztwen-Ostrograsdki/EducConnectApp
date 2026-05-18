<?php

namespace App\Livewire\Tenants;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.tenants.dashboard');
    }
}
