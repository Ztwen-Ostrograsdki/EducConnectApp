<?php

namespace App\Livewire\Tenants\Students;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Profil apprenant")]
class StudentProfilPage extends Component
{
    public string $student_uuid;

    public string $classe_slug;

    public int $counter = 1;

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

        if($this->student_uuid){

            $student = Student::withTrashed()->where('uuid', $this->student_uuid)->first();
        }

        return view('livewire.tenants.students.student-profil-page', compact('student'));
    }

    #[On("StudentDataUpdatedEventLiveEvent")]
    public function studentDataUpdated()
    {
        $this->counter++;
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
