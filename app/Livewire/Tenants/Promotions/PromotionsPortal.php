<?php

namespace App\Livewire\Tenants\Promotions;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des promotions")]
class PromotionsPortal extends Component
{

    public $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }


    
    public function render()
    {
        return view('livewire.tenants.promotions.promotions-portal');
    }
}
