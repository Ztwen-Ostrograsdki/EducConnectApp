<?php

namespace App\Livewire\Tenants\Filiars;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class FiliarsPortal extends Component
{
    public function render()
    {
        return view('livewire.tenants.filiars.filiars-portal');
    }
}
