<?php

namespace App\Livewire\Tenants\Teachers;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Livewire\Tenants\ActionsTraits\UsersActions;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Page profil Enseignant")]
class TeacherProfilPage extends Component
{
    use WireUiActions, TeachersActions, UsersActions;
    
    public string $teacher_uuid;

    public $counter = 0;

    public function mount(string $teacher_uuid)
    {
        if(!$teacher_uuid) return abort(404);

        $teacher = Teacher::withTrashed()->where('uuid', $teacher_uuid)->first();

        if(!$teacher) return abort(404);

        $this->teacher_uuid = $teacher_uuid;

    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }


    #[Computed]
    public function teacher()
    {
        if(!$this->teacher_uuid) return abort(404);

        $teacher = Teacher::withTrashed()->where('uuid', $this->teacher_uuid)->first();

        if(!$teacher) return abort(404);

        return $teacher;

    }


    #[Computed]
    public function user()
    {
        if(!$this->teacher_uuid) return abort(404);


        if(!$this->teacher) return abort(404);

        return $this->teacher->user;

    }


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    

    

    public function render()
    {
        return view('livewire.tenants.teachers.teacher-profil-page');
    }
}
