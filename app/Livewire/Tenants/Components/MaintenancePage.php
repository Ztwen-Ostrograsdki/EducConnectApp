<?php

namespace App\Livewire\Tenants\Components;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Plateforme en maintenance")]
#[Layout("livewire.layouts.default-app-layout")]
class MaintenancePage extends Component
{
    public function mount()
    {
        $tenant = tenant();

        if(!$tenant->open_only_for_tenant){

            $this->redirectRoute(name: 'tenant.my.profil', navigate:true);
        }
    }

    public function render()
    {
        return view('livewire.tenants.components.maintenance-page');
    }
}
