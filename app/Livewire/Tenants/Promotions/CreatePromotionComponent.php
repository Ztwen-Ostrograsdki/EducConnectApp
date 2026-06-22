<?php

namespace App\Livewire\Tenants\Promotions;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Creation de promotion")]
class CreatePromotionComponent extends Component
{
    public function render()
    {
        return view('livewire.tenants.promotions.create-promotion-component');
    }
}
