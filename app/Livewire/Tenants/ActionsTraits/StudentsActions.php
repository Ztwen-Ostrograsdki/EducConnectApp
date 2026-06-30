<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\Student;
use App\Models\YearlyClasseStudent;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

trait StudentsActions{


	use WithPagination, WireUiActions;
    
    public string $search = '';

    public string $city = '';

    public $counter = 3;

    public string $gender = '';

    public string $department = '';

    public ?string $status = null;

    public int $perPage = 12;

    public ?Classe $classe;

    public function markStudentAsLeaved(int $studentId): void
    {
        $this->dispatch('swal', [
            'title'              => "Marquer comme abondonné ?",
            'text'               => "Cet apprenant ne figurera plus dans la liste de la classe en cours d'année scolaire",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, valider',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'MarkAsLeaved',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }

    #[On('MarkAsLeaved')]
    public function OnMarkAsLeaved(int $studentId): void
    {
        $student = Student::find($studentId);

        if (!$student) {

            $this->notification()->error(title: 'Apprenant introuvable');
            return;
        }

        try {
            
            $done = $student->markStudentAsLeaved();

            if($done){

                $this->notification()->success(
                    title: 'Apprenant marqué abandonné',
                    description: "L'apprenant {$student->getFullName()} a été marqué comme un apprenant ayant abondonné.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

            }
            else{

                $this->notification()->error(
                    title: 'Apprenant non marqué abandonné',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Apprenant non marqué abandonné',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }
    
    public function reinsertIntoClasseAsActive(int $studentId): void
    {
        $this->dispatch('swal', [
            'title'              => "Réinséré cet apprenant dans la classe",
            'text'               => "Cet apprenant figurera désormais dans la liste de la classe en cours d'année scolaire",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, réinséré',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ReinsertIntoClasse',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }

    #[On('ReinsertIntoClasse')]
    public function OnReinsertIntoClasse(int $studentId): void
    {
        $student = Student::find($studentId);

        if (!$student) {

            $this->notification()->error(title: 'Apprenant introuvable');
            return;
        }

        try {
            
            $done = $student->reinsertStudentIntoClasse();

            if($done){

                $this->notification()->success(
                    title: 'Apprenant réinséré dans sa classe',
                    description: "L'apprenant {$student->getFullName()} a été réinséré dans sa classe de départ au cours de l'année scolaire!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Apprenant non réinséré',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Apprenant non réinséré',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
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


    public function createStudentsFromExcelFile()
    {
        session()->put('showImportMode', true);
    }


    public function exportToExcelFile()
    {

    }
    
    public function clearFilters()
    {
        $this->reset('search', 'gender', 'city', 'gender', 'department');
    }
}