<?php

namespace App\Livewire\Tenants\Teachers;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class TeacherProfilPage extends Component
{
    public string $teacher_uuid;

    public string $teacher;

    public function mount(string $teacher_uuid)
    {
        $this->teacher_uuid = $teacher_uuid;
    }

    public function render()
    {
        return view('livewire.tenants.teachers.teacher-profil-page');
    }
}
