<?php

namespace App\Livewire\Tenants;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenants-default-layout')]
#[Title("Page d'acceuil")]
class HomePage extends Component
{
    
    public function render()
    {
        return view('livewire.tenants.home-page');
    }
}
