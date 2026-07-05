<?php

namespace App\Livewire\Tenants\Students;

use App\Events\DataUpdatedEvent;
use App\Jobs\JobToMigrateStudentsToClasses;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use App\Models\YearlyClasseStudent;
use App\Notifications\RealTimeNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Gestion des classes d'un apprenant")]
class ManageStudentClassroomComponent extends Component
{
    public ?string $studentUuid = null;

    public ?int $selectedClasseId = null;

    public ?string $student_uuid = null;

    public ?string $classe_slug = null;

    public int $counter = 1;

    public ?string $period_type_selected = null;

    public function mount(string $student_uuid)
    {
        if(!$student_uuid) abort(404);

        $exists = Student::withTrashed()->where('uuid', $student_uuid)->exists();

        if(!$exists) abort(404);

        $this->student_uuid = $student_uuid;
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function student(): Student
    {
        return Student::where('uuid', $this->student_uuid)->first();
    }

    #[Computed]
    public function currentClasse(): ?Classe
    {
        if($this->student && $this->student?->currentClasse()){

            return $this->student?->currentClasse()?->classe;
        }

        return null;
    }

    #[Computed]
    public function availableClasses()
    {
        return Classe::query()
            ->where('school_year_id', $this->activeYear->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function updatedSelectedClasseId(): void
    {
        $this->resetErrorBag('selectedClasseId');
    }

    public function confirmMigration(): void
    {
        $this->validate([
            'selectedClasseId' => ['required', 'exists:classes,id'],
        ], [], ['selectedClasseId' => 'classe']);

        if ($this->currentClasse && $this->selectedClasseId === $this->currentClasse->id) {
            $this->addError('selectedClasseId', "L'apprenant est déjà dans cette classe.");
            return;
        }

        $targetClasse = $this->availableClasses->firstWhere('id', $this->selectedClasseId);

        $this->dispatch('swal', [
            'title' => 'Confirmer la migration',
            'text' => "Migrer {$this->student->full_name} vers la classe {$targetClasse->name} ?",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Migrer',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'migrateStudent',
            'onConfirmedParams' => ['classeId' => $this->selectedClasseId],
        ]);
    }

    

    #[On("migrateStudent")]
    public function OnMigrateStudent(int $classeId)
    {
        $this->student->migrateStudentToClasse($classeId, true);

        unset($this->student, $this->currentClasse);

        $this->selectedClasseId = null;
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


    public function render()
    {
        return view('livewire.tenants.students.manage-student-classroom-component');
    }
}
