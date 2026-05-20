<?php

namespace App\Livewire\Tenants\Parents;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class ParentProfil extends Component
{
    public string $parent_uuid;

    public function mount(string $parent_uuid)
    {
        $this->parent_uuid = $parent_uuid;
    }


    public function render()
    {
        return view('livewire.tenants.parents.parent-profil');
    }
}
