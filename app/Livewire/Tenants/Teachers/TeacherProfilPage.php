<?php

namespace App\Livewire\Tenants\Teachers;

use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Page profil Enseignant")]
class TeacherProfilPage extends Component
{
    public string $teacher_uuid;

    public function mount(string $teacher_uuid)
    {
        $this->teacher_uuid = $teacher_uuid;
    }

    public function render()
    {
        $teacher = Teacher::withTrashed()->where('uuid', $this->teacher_uuid)->firstOrFail();

        $user = $teacher->user;


        return view('livewire.tenants.teachers.teacher-profil-page', compact('teacher', 'user'));
    }
}
