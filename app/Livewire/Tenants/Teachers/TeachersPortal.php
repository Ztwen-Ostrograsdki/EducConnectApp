<?php

namespace App\Livewire\Tenants\Teachers;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class TeachersPortal extends Component
{
    public function render()
    {
        return view('livewire.tenants.teachers.teachers-portal');
    }
}
