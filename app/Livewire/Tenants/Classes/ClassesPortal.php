<?php

namespace App\Livewire\Tenants\Classes;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Portails des classes ou groupes pédagogiques')]
class ClassesPortal extends Component
{
    public $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }
    
    public function render()
    {
        return view('livewire.tenants.classes.classes-portal');
    }
}
