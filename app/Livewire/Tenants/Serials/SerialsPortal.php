<?php

namespace App\Livewire\Tenants\Serials;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class SerialsPortal extends Component
{
    public function render()
    {
        return view('livewire.tenants.serials.serials-portal');
    }
}
