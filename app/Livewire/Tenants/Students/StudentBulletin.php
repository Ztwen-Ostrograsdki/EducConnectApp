<?php

namespace App\Livewire\Tenants\Students;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('livewire.layouts.tenant-auth-layout')]
class StudentBulletin extends Component
{
    public function render()
    {
        return view('livewire.tenants.students.student-bulletin');
    }
}
