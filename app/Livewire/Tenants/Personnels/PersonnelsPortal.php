<?php

namespace App\Livewire\Tenants\Personnels;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class PersonnelsPortal extends Component
{
    public function render()
    {
        return view('livewire.tenants.personnels.personnels-portal');
    }
}
