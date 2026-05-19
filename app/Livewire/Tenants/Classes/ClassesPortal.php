<?php

namespace App\Livewire\Tenants\Classes;

use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('livewire.layouts.tenant-auth-layout')]
class ClassesPortal extends Component
{
    public function render()
    {
        return view('livewire.tenants.classes.classes-portal');
    }
}
