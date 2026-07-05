<?php

namespace App\Livewire\Tenants\Students;

use App\Livewire\Tenants\ActionsTraits\StudentsActions;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Profil apprenant")]
class StudentProfilPage extends Component
{
    use WireUiActions, StudentsActions;
    
    public string $student_uuid;

    public string $classe_slug;

    public int $counter = 1;

    public ?string $period_type_selected;

    public function mount(string $student_uuid)
    {
        if(!$student_uuid) abort(404);

        $exists = Student::withTrashed()->where('uuid', $student_uuid)->exists();

        if(!$exists) abort(404);

        $this->student_uuid = $student_uuid;
    }

    #[Computed]
    public function student()
    {
        return Student::withTrashed()->where('uuid', $this->student_uuid)->first();
    }


    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }
    
    #[Computed]
    public function currentClasse(): ?Classe
    {
        if($this->student && $this->student?->currentClasse()){

            return $this->student?->currentClasse()?->classe;
        }

        return null;
        
    }

    public function render()
    {
        if (session()->has('tenant_student_bulletin_period_type_selected')) {

            $this->period_type_selected = session('tenant_student_bulletin_period_type_selected');
        }

        return view('livewire.tenants.students.student-profil-page');
    }

    #[On("StudentDataUpdatedEventLiveEvent")]
    public function studentDataUpdated()
    {
        $this->counter++;
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
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


    public function removeStudentFromCurrent(): void
    {
        $this->dispatch('swal', [
            'title' => "Retirer l'apprenant " . $this->student->getFullName() . " de sa classe actuelle ?",
            'text' => "L'apprenant " . $this->student->getFullName() . " a été retiré de sa classe actuelle (" . $this->currentClasse->name . ")",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Retirer de la classe',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'RemoveStudentFromCurrentClasse',
        ]);
    }

    

    #[On("RemoveStudentFromCurrentClasse")]
    public function OnRemoveStudentFromCurrentClasse()
    {
        $this->student->removeStudentFromHisCurrentClasse(true);

        unset($this->student, $this->currentClasse);
    }
}
