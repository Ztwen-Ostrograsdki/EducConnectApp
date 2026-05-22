<?php

namespace App\Livewire\Central;

use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('livewire.layouts.central-auth-layout')]
class SchoolProfilComponent extends Component
{
    public ?string $school_uuid;

    public function mount(?string $school_uuid)
    {
        $this->school_uuid = $school_uuid;

    }

    
    public function render()
    {
        return view('livewire.central.school-profil-component');
    }
}
