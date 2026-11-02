<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Jobs\JobBulkerActionsOnModels;
use App\Jobs\JobToGenerateStudentNotesShareForThePrintViewComponent;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;


/**
 * @property-read LengthAwarePaginator $students
 */
trait StudentsActions{

	use WithPagination, WireUiActions;

    /** @var array<int> IDs des apprenants sélectionnés pour actions en masse */
    public array $selectedStudents = [];

    // public ?Classe $classe;

    // ─── Sélection multiple ───────────────────────────────────────────

    public function toggleSelectStudent(int $studentId): void
    {
        if (in_array($studentId, $this->selectedStudents, true)) {
            $this->selectedStudents = array_values(array_filter(
                $this->selectedStudents,
                fn ($id) => (int) $id !== $studentId
            ));
        } else {
            $this->selectedStudents[] = $studentId;
        }
    }

    public function selectAllOnPage(): void
    {
        $pageIds = $this->students->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->toArray();

        $this->selectedStudents = array_values(array_unique(array_merge(
            $this->selectedStudents,
            $pageIds
        )));
    }

    public function deselectAllOnPage(): void
    {
        $pageIds = $this->students->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->toArray();

        $this->selectedStudents = array_values(array_filter(
            $this->selectedStudents,
            fn ($id) => ! in_array((int) $id, $pageIds, true)
        ));
    }

    public function clearSelection(): void
    {
        $this->selectedStudents = [];
    }

    public function isStudentSelected(int $studentId): bool
    {
        return in_array($studentId, $this->selectedStudents, true);
    }

    public function areAllOnPageSelected(): bool
    {
        $pageIds = $this->students->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->toArray();

        if (empty($pageIds)) {
            return false;
        }

        foreach ($pageIds as $id) {
            if (! in_array($id, $this->selectedStudents, true)) {
                return false;
            }
        }

        return true;
    }

    public function toggleSelectAllOnPage(): void
    {
        if ($this->areAllOnPageSelected()) {
            $this->deselectAllOnPage();
        } else {
            $this->selectAllOnPage();
        }
    }

    protected function ensureSelectionNotEmpty(): bool
    {
        if (empty($this->selectedStudents)) {
            $this->notification()->warning(
                title: 'Aucune sélection',
                description: 'Sélectionnez au moins un apprenant pour exécuter cette action.',
            );

            return false;
        }

        return true;
    }

    // ─── Actions en masse sur la sélection ────────────────────────────

    public function bulkDeleteStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $count = count($this->selectedStudents);

        $this->dispatch('swal', [
            'title'              => "Supprimer {$count} apprenant(s) ?",
            'text'               => 'Les apprenants sélectionnés seront envoyés à la corbeille.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmBulkDeleteStudents',
        ]);
    }

    #[On('ConfirmBulkDeleteStudents')]
    public function onConfirmBulkDeleteStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $ids = array_map('intval', $this->selectedStudents);

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Student::class,
            ids: $ids,
            method: 'delete',
            options: [],
            withTrashedDeleted: false,
            taskTitle: 'SUPPRESSION EN MASSE (CORBEILLE) DES APPRENANTS SÉLECTIONNÉS'
        );

        $this->notification()->success(
            title: 'Suppression lancée',
            description: count($ids) . ' apprenant(s) envoyé(s) à la corbeille.',
        );

        $this->clearSelection();
        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function bulkForceDeleteStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $count = count($this->selectedStudents);

        $this->dispatch('swal', [
            'title'              => "Supprimer définitivement {$count} apprenant(s) ?",
            'text'               => 'Cette action est irréversible. Les apprenants seront définitivement supprimés.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer définitivement',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmBulkForceDeleteStudents',
        ]);
    }

    #[On('ConfirmBulkForceDeleteStudents')]
    public function onConfirmBulkForceDeleteStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $ids = array_map('intval', $this->selectedStudents);

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Student::class,
            ids: $ids,
            method: 'forceDelete',
            options: [],
            withTrashedDeleted: true,
            taskTitle: 'SUPPRESSION DÉFINITIVE EN MASSE DES APPRENANTS SÉLECTIONNÉS'
        );

        $this->notification()->success(
            title: 'Suppression définitive lancée',
            description: count($ids) . ' apprenant(s) seront définitivement supprimé(s).',
        );

        $this->clearSelection();
        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function bulkRestoreStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $count = count($this->selectedStudents);

        $this->dispatch('swal', [
            'title'              => "Restaurer {$count} apprenant(s) ?",
            'text'               => 'Les apprenants sélectionnés seront retirés de la corbeille.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, restaurer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#10b981',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmBulkRestoreStudents',
        ]);
    }

    #[On('ConfirmBulkRestoreStudents')]
    public function onConfirmBulkRestoreStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $ids = array_map('intval', $this->selectedStudents);

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Student::class,
            ids: $ids,
            method: 'restore',
            options: [],
            withTrashedDeleted: true,
            taskTitle: 'RESTAURATION EN MASSE DES APPRENANTS SÉLECTIONNÉS'
        );

        $this->notification()->success(
            title: 'Restauration lancée',
            description: count($ids) . ' apprenant(s) seront restauré(s).',
        );

        $this->clearSelection();
        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function bulkActivateStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $count = count($this->selectedStudents);

        $this->dispatch('swal', [
            'title'              => "Réactiver {$count} apprenant(s) ?",
            'text'               => 'Les apprenants sélectionnés seront à nouveau actifs.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#10b981',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmBulkActivateStudents',
        ]);
    }

    #[On('ConfirmBulkActivateStudents')]
    public function onConfirmBulkActivateStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $ids = array_map('intval', $this->selectedStudents);

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Student::class,
            ids: $ids,
            method: 'update',
            options: ['is_active' => true],
            withTrashedDeleted: false,
            taskTitle: 'RÉACTIVATION EN MASSE DES APPRENANTS SÉLECTIONNÉS'
        );

        $this->notification()->success(
            title: 'Réactivation lancée',
            description: count($ids) . ' apprenant(s) seront réactivé(s).',
        );

        $this->clearSelection();
        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function bulkDeactivateStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $count = count($this->selectedStudents);

        $this->dispatch('swal', [
            'title'              => "Désactiver {$count} apprenant(s) ?",
            'text'               => 'Les apprenants sélectionnés ne seront plus accessibles dans les classes.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, désactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f59e0b',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmBulkDeactivateStudents',
        ]);
    }

    #[On('ConfirmBulkDeactivateStudents')]
    public function onConfirmBulkDeactivateStudents(): void
    {
        if (! $this->ensureSelectionNotEmpty()) {
            return;
        }

        $ids = array_map('intval', $this->selectedStudents);

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Student::class,
            ids: $ids,
            method: 'update',
            options: ['is_active' => false],
            withTrashedDeleted: false,
            taskTitle: 'DÉSACTIVATION EN MASSE DES APPRENANTS SÉLECTIONNÉS'
        );

        $this->notification()->success(
            title: 'Désactivation lancée',
            description: count($ids) . ' apprenant(s) seront désactivé(s).',
        );

        $this->clearSelection();
        broadcast(new DataUpdatedEvent(tenant('id')));
    }
    
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
            'text'               => "Cet apprenant figurera désormais dans la liste de la classe en cours d'année scolaire et ne sera plus considéré comme abandon",
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
        $student = Student::find($studentId);

        if(!$student) return;

        $student->delete();

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Apprenant supprimé',
            description: "{$student->name} {$student->prenames} a été envoyé à la corbeille.",
        );
    }
    
    
    public function activateStudent(?int $studentId = null): void
    {
        $this->dispatch('swal', [
            'title'              => 'Réactiver cet apprenant ?',
            'text'               => 'L\'apprenant sera à nouveau actif.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToActivateStudent',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }

    #[On('ConfirmToActivateStudent')]
    public function onConfirmToActivateStudent(?int $studentId = null): void
    {
        $student = Student::find($studentId);

        if(!$student) return;

        $student->update(['is_active' => true]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Apprenant réactivé',
            description: "L'apprenant {$student->name} {$student->prenames} a été réactivé.",
        );
    }


    public function desactivateStudent(?int $studentId = null): void
    {
        $this->dispatch('swal', [
            'title'              => 'Désactiver cet apprenant ?',
            'text'               => 'L\'apprenant ne sera plus accessible dans les classes.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Désactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDesactivateStudent',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }

    #[On('ConfirmToDesactivateStudent')]
    public function onConfirmToDesactivateStudent(?int $studentId = null): void
    {
        $student = Student::findOrFail($studentId);

        $student->update(['is_active' => false]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Apprenant réactivé',
            description: "L'apprenant {$student->name} {$student->prenames} a été réactivé.",
        );
    }
    
    
    public function restoreStudent(?int $studentId = null): void
    {
        $this->dispatch('swal', [
            'title'              => 'Restorer cet apprenant ?',
            'text'               => 'L\'apprenant sera retiré de la corbeille.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Restorer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestoreStudent',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }

    #[On('ConfirmToRestoreStudent')]
    public function onConfirmToRestoreStudent(?int $studentId = null): void
    {
        $student = Student::withTrashed()->find($studentId);

        if(!$student) return;

        $student->restore();

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Apprenant restoré',
            description: "L'apprenant {$student->getFullName()} a été restoré de la corbeille.",
        );
    }
    
    
    public function forceDeleteStudent(?int $studentId = null): void
    {
        $this->dispatch('swal', [
            'title'              => 'Supprimer définitivement cet apprenant ?',
            'text'               => 'L\'apprenant sera définitivement supprimé.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToForceDeleteStudent',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }

    #[On('ConfirmToForceDeleteStudent')]
    public function onConfirmToForceDeleteStudent(?int $studentId = null): void
    {
        $student = Student::withTrashed()->firstWhere('id', $studentId);

        if(!$student) return;

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Student::class,
            ids: [$studentId],
            method: 'forceDelete',
            options: [],
            withTrashedDeleted: true,
            taskTitle: "SUPPRESSION DEFINITIVE EN MASSE DES APPRENANTS DE LA CORBEILLE"
        );

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Apprenant supprimé',
            description: "{$student->name} {$student->prenames} a été supprimé définitivement.",
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
    public function onConfirmRemoveFromClasse(?int $studentId = null)
    {
        $student = Student::find($studentId);

        if(!$student) return $this->notification()->warning(
            title: 'ECHEC DU RETRAIT : Apprenant non trouvé',
        );

        $currentClasse = $student->currentClasse();

        if(!$currentClasse || !$currentClasse->classe) return $this->notification()->warning(
            title: "ECHEC DU RETRAIT",
            description: "L'Apprenant {$student->getFullName()} n'a pas de classe",
        );

        $currentClasse->update([
            'is_active' => false,
            'ended_at'  => now(),
        ]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->warning(
            title: 'Apprenant retiré',
            description: "{$student->getFullName()} a été retiré de la classe.",
        );
    }


    public function restoreStudents(): void
    {
        $this->dispatch('swal', [
            'title'              => 'Restorer tous les apprenants ?',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, restaurer tous',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#a855f7',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmStudentsRestoration',
        ]);
    }

    #[On('ConfirmStudentsRestoration')]
    public function OnConfirmStudentsRestoration(): void
    {
        $ids = Student::onlyTrashed()->take(50)->pluck('id')->toArray();

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Student::class,
            ids: $ids,
            method: 'restore',
            options: [],
            withTrashedDeleted: true,
            taskTitle: "RESTORATION EN MASSE DES APPRENANTS DE LA CORBEILLE"
        );

        $this->notification()->success(
            title: 'Apprenants restorés',
            description: 'Tous les Apprenants ont été restorés.',
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function reactivateStudents(): void
    {
        $this->dispatch('swal', [
            'title'              => 'Réactiver tous les apprenants ?',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, réactiver tous',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#a855f7',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmStudentsReactivation',
        ]);
    }

    #[On('ConfirmStudentsReactivation')]
    public function OnConfirmStudentsReactivation(): void
    {
        $ids = Student::withoutTrashed()->where('is_active', false)->take(50)->pluck('id')->toArray();

        JobBulkerActionsOnModels::dispatch(
            tenantId: tenant('id'),
            model: Student::class,
            ids: $ids,
            method: 'update',
            options: ['is_active' => true],
            withTrashedDeleted: false,
            taskTitle: "REACTIVATION EN MASSE DES APPRENANTS"
        );

        $this->notification()->success(
            title: 'Apprenants réactivés',
            description: 'Tous les Apprenants ont été réactivés.',
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
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


    public function shareStudentNotes(int $studentId, ?int $subjectId = null)
    {
        $period = $this->period ?? SchoolYear::current()->first()?->active_period;

        if (! $period) {
            $this->notification()->error(title: "Aucune période active définie.");
            return;
        }

        $domain = request()->getSchemeAndHttpHost();

        $space_url = get_tenant_url($domain);

        JobToGenerateStudentNotesShareForThePrintViewComponent::dispatch(
            tenantId:       tenant('id'),
            notifiableId:   auth('tenant')->user()->id,
            student_id:     $studentId,
            period:         $period,
            subject_id:     $subjectId,
            school_year_id: SchoolYear::current()->first()?->id,
            space_url: $space_url,

        );

        $this->notification()->success(title: 'Chargement des notes... Génération du document...');
    }
    
}