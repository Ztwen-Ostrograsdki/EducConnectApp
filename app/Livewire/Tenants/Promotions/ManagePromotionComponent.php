<?php

namespace App\Livewire\Tenants\Promotions;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Mise à jour de promotion")]
class ManagePromotionComponent extends Component
{
    public function render()
    {
        return view('livewire.tenants.promotions.manage-promotion-component');
    }
}
