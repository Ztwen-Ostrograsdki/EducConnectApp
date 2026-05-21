<?php

namespace App\Livewire\Central;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.central-auth-layout')]
class TenantProfilComponent extends Component
{

    public ?string $tenant_uuid;

    public function mount(?string $tenant_uuid)
    {
        $this->tenant_uuid = $tenant_uuid;

    }
    public function render()
    {
        return view('livewire.central.tenant-profil-component');
    }
}
