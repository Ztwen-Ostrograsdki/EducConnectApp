<?php

namespace App\Livewire\Tenants\Students;

use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('livewire.layouts.tenant-auth-layout')]
class StudentProfilPage extends Component
{
    public string $student_uuid, $student;


    public function mount(string $student_uuid)
    {
        $this->student_uuid = $student_uuid;
    }


    public function render()
    {
        return view('livewire.tenants.students.student-profil-page');
    }
}
