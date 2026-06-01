<?php

namespace App\Livewire\Tenants;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Mon profil")]
class MyProfilPage extends Component
{
    public function render()
    {
        return view('livewire.tenants.my-profil-page');
    }
}
