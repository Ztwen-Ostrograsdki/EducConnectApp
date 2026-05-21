<?php

namespace App\Livewire\Central;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.central-auth-layout')]
class TenantsComponent extends Component
{
    public function render()
    {
        return view('livewire.central.tenants-component');
    }
}
