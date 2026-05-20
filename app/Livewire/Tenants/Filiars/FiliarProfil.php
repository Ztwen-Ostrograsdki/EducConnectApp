<?php

namespace App\Livewire\Tenants\Filiars;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class FiliarProfil extends Component
{
    public function render()
    {
        return view('livewire.tenants.filiars.filiar-profil');
    }
}
