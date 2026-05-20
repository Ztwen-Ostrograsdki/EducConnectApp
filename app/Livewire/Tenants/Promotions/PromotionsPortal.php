<?php

namespace App\Livewire\Tenants\Promotions;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class PromotionsPortal extends Component
{
    public function render()
    {
        return view('livewire.tenants.promotions.promotions-portal');
    }
}
