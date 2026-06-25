<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\Student;
use App\Models\YearlyClasseStudent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class ClasseStudentsList extends Component
{
    use WireUiActions;
    use WithPagination;

    public string  $classroom;
    public ?Classe $classe;
    public int     $counter  = 0;
    public int     $perpage  = 30;

    // ─── Filtres ──────────────────────────────────────────────────────
    public string $search = '';
    public string $gender = '';



    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedGender(): void { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->reset(['search', 'gender']);
        $this->resetPage();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
    {
        $this->counter++;
        $this->resetPage();
    }

    // ─── Suppression (soft delete) ────────────────────────────────────

    public function deleteStudent(?int $studentId = null): void
    {
        $this->dispatch('swal', [
            'title'              => 'Supprimer cet apprenant ?',
            'text'               => 'L\'apprenant sera envoyé à la corbeille.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmStudentDelete',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }

    #[On('ConfirmStudentDelete')]
    public function onConfirmStudentDelete(?int $studentId = null): void
    {
        $student = Student::findOrFail($studentId);
        $student->delete();

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Apprenant supprimé',
            description: "{$student->name} {$student->prenames} a été envoyé à la corbeille.",
        );
    }

    // ─── Bloquer / Débloquer ──────────────────────────────────────────

    public function toggleBlockStudent(?int $studentId = null): void
    {
        $student = Student::findOrFail($studentId);

        if ($student->blocked) {
            $this->dispatch('swal', [
                'title'              => 'Débloquer cet apprenant ?',
                'text'               => "{$student->name} {$student->prenames} retrouvera accès à la plateforme.",
                'icon'               => 'question',
                'showCancelButton'   => true,
                'confirmButtonText'  => 'Oui, débloquer',
                'cancelButtonText'   => 'Annuler',
                'confirmButtonColor' => '#10b981',
                'cancelButtonColor'  => '#475569',
                'onConfirmed'        => 'ConfirmStudentUnblock',
                'onConfirmedParams'  => ['studentId' => $studentId],
            ]);
        } else {
            $this->dispatch('swal', [
                'title'              => 'Bloquer cet apprenant ?',
                'text'               => "{$student->name} {$student->prenames} ne pourra plus accéder à la plateforme.",
                'icon'               => 'warning',
                'showCancelButton'   => true,
                'confirmButtonText'  => 'Oui, bloquer',
                'cancelButtonText'   => 'Annuler',
                'confirmButtonColor' => '#f59e0b',
                'cancelButtonColor'  => '#475569',
                'onConfirmed'        => 'ConfirmStudentBlock',
                'onConfirmedParams'  => ['studentId' => $studentId],
            ]);
        }
    }

    #[On('ConfirmStudentBlock')]
    public function onConfirmStudentBlock(?int $studentId = null): void
    {
        $student = Student::findOrFail($studentId);
        $student->update(['blocked' => true]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->warning(
            title: 'Apprenant bloqué',
            description: "{$student->name} {$student->prenames} a été bloqué.",
        );
    }

    #[On('ConfirmStudentUnblock')]
    public function onConfirmStudentUnblock(?int $studentId = null): void
    {
        $student = Student::findOrFail($studentId);
        $student->update(['blocked' => false]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Apprenant débloqué',
            description: "{$student->name} {$student->prenames} a été débloqué.",
        );
    }

    // ─── Retirer de la classe ─────────────────────────────────────────

    public function removeFromClasse(?int $studentId = null): void
    {
        $this->dispatch('swal', [
            'title'              => 'Retirer de la classe ?',
            'text'               => 'L\'apprenant sera retiré de cette classe pour cette année scolaire.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, retirer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f59e0b',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmRemoveFromClasse',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }

    #[On('ConfirmRemoveFromClasse')]
    public function onConfirmRemoveFromClasse(?int $studentId = null): void
    {
        $link = YearlyClasseStudent::where('student_id', $studentId)
            ->where('classe_id', $this->classe->id)
            ->where('school_year_id', $this->classe->school_year_id)
            ->firstOrFail();

        $link->update([
            'is_active' => false,
            'ended_at'  => now(),
        ]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $student = Student::find($studentId);

        $this->notification()->warning(
            title: 'Apprenant retiré',
            description: "{$student?->name} {$student?->prenames} a été retiré de la classe.",
        );
    }

    // ─── Render ───────────────────────────────────────────────────────

    public function render()
    {
        $students = Student::whereHas('yearlyClasseStudents', fn($q) =>
            $q->where('classe_id', $this->classe->id)
              ->where('school_year_id', $this->classe->school_year_id)
              ->where('is_active', true)
        )
        ->when($this->search, fn($q) =>
            $q->where(fn($q) =>
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('prenames', 'like', '%'.$this->search.'%')
                  ->orWhere('matricule', 'like', '%'.$this->search.'%')
            )
        )
        ->when($this->gender, fn($q) => $q->where('gender', $this->gender))
        ->orderBy('name')
        ->paginate($this->perpage);

        return view('livewire.tenants.classes.sections.classe-students-list', compact('students'));
    }
}
