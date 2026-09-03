<?php

namespace App\Livewire\Central;

use App\Livewire\Central\CentralTraits\CentralReloaderTrait;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Tableau de bord central")]
class CentralDashboard extends Component
{

    use CentralReloaderTrait;

    public function render()
    {
        return view('livewire.central.central-dashboard');
    }
}
