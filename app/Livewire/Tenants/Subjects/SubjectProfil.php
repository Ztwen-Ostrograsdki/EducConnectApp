<?php

namespace App\Livewire\Tenants\Subjects;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class SubjectProfil extends Component
{
    public function render()
    {
        return view('livewire.tenants.subjects.subject-profil');
    }
}
