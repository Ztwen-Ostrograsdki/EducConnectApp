<?php

namespace App\Livewire\Tenants\Parents;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class ParentProfil extends Component
{
    public function render()
    {
        return view('livewire.tenants.parents.parent-profil');
    }
}
