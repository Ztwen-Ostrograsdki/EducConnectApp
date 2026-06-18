<?php

namespace App\Livewire\Tenants\Schoolyears;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Portail des années scolaires')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class SchoolYearsPortal extends Component
{
    public function render()
    {
        return view('livewire.tenants.schoolyears.school-years-portal');
    }
}
