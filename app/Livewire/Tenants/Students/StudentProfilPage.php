<?php

namespace App\Livewire\Tenants\Students;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class StudentProfilPage extends Component
{
    public string $student_uuid;

    public string $student;

    public string $classe_slug;

    public ?string $period_type_selected;

    public function mount(string $student_uuid)
    {
        $this->student_uuid = $student_uuid;

        $this->classe_slug = $student_uuid;
    }

    public function render()
    {
        if (session()->has('tenant_student_bulletin_period_type_selected')) {

            $this->period_type_selected = session('tenant_student_bulletin_period_type_selected');
        }

        return view('livewire.tenants.students.student-profil-page');
    }

    public function updatedPeriodTypeSelected(?string $period_type_selected)
    {
        session()->put('tenant_student_bulletin_period_type_selected', $period_type_selected);

    }

    public function reloadStudentBulletin()
    {
        $this->dispatch('ReloadTheStudentBulletin', $this->period_type_selected, $this->student_uuid);
    }

    public function resetBulletinSelections()
    {
        $this->reset('period_type_selected');

        session()->forget('tenant_student_bulletin_period_type_selected');

        $this->dispatch('ReloadTheStudentBulletin', null, null);
    }
}
