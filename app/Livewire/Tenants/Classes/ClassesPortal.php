<?php

namespace App\Livewire\Tenants\Classes;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Portails des classes ou groupes pédagogiques')]
class ClassesPortal extends Component
{
    public function render()
    {
        return view('livewire.tenants.classes.classes-portal');
    }
}
