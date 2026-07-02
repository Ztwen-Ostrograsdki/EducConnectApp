<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Jobs\JobBulkerActionsOnModels;
use App\Models\Subject;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait SubjectsActions{

	use WireUiActions;

	public $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

	#[Computed]
    public function unActivesSubjects() : int
    {
        return Subject::withTrashed(false)
                        ->where('is_active', false)
                        ->count();
    }
    
    #[Computed]
    public function trashedsSubjects() : int
    {
        return Subject::onlyTrashed()->count();
    }


    #[Computed]
    public function activesSubjects() : int
    {
        return  Subject::withTrashed(false)
                        ->where('is_active', true)
                        ->count();
    }

	public function desactivateSubject(int $subjectId): void
    {
        $this->dispatch('swal', [
            'title'              => "Désactiver cette matière ? ",
            'text'               => "Cette action rendra cette matière indisponible et masquer les classes concernées!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, désactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDesactivateSubject',
            'onConfirmedParams'  => ['subjectId' => $subjectId],
        ]);
    }

    #[On('ConfirmToDesactivateSubject')]
    public function OnConfirmToDesactivateSubject(int $subjectId): void
    {
        $filiar = Subject::find($subjectId);

        if (!$filiar) {

            $this->notification()->error(title: 'Matière introuvable');
            return;
        }

        try {
            
            $done = $filiar->update(['is_active' => false]);

            if($done){

                $this->notification()->success(
                    title: 'Matière fermée',
                    description: "La Matière {$filiar->name} {$filiar->code} a été fermée!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Matière non fermée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Matière non fermée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }


	public function activateSubject(int $subjectId): void
    {
        $this->dispatch('swal', [
            'title'              => "Réactiver cette matière ? ",
            'text'               => "Cette action rendra de nouveau cette matière disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToReactivateSubject',
            'onConfirmedParams'  => ['subjectId' => $subjectId],
        ]);
    }

    #[On('ConfirmToReactivateSubject')]
    public function OnReactivateSubject(int $subjectId): void
    {
        $filiar = Subject::find($subjectId);

        if (!$filiar) {

            $this->notification()->error(title: 'Matière introuvable');
            return;
        }

        try {
            
            $done = $filiar->update(['is_active' => true]);

            if($done){

                $this->notification()->success(
                    title: 'Matière réactivée',
                    description: "La Matière {$filiar->name} {$filiar->code} a été réactivée!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Matière non réactivée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Matière non réactivée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

    public function deleteSubject(int $subjectId): void
    {
        $this->dispatch('swal', [
            'title'              => "Supprimer cette matière ? ",
            'text'               => "Cette action enverra la matière dans la corbeille, alors cette matière ne sera plus disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteSubject',
            'onConfirmedParams'  => ['subjectId' => $subjectId],
        ]);
    }

    #[On('ConfirmToDeleteSubject')]
    public function OnConfirmToDeleteSubject(int $subjectId): void
    {
        $subject = Subject::find($subjectId);

        if (!$subject) {

            $this->notification()->error(title: 'Matière introuvable');
            return;
        }

        try {
			
            $done = $subject->delete();

            if($done){

                $this->notification()->success(
                    title: 'Matière supprimée',
                    description: "La matière {$subject->name} {$subject->code} a été envoyée dans la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Matière non supprimée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Matière non supprimée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

	public function restoreSubject(int $subjectId): void
    {
        $this->dispatch('swal', [
            'title'              => "Recuperer cette matière ? ",
            'text'               => "Cette action restaurera la matière de la corbeille, alors cette matière sera disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Recuperer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestoreFiliar',
            'onConfirmedParams'  => ['subjectId' => $subjectId],
        ]);
    }

    #[On('ConfirmToRestoreFiliar')]
    public function OnConfirmToRestoreSubject(int $subjectId): void
    {
        $subject = Subject::withoutTrashed()->whereId($subjectId)->first();

        if (!$subject) {

            $this->notification()->error(title: 'Matière introuvable');
            return;
        }

        try {
            
            $done = $subject->restore();

            if($done){

                $this->notification()->success(
                    title: 'Matière restaurée',
                    description: "La matière {$subject->name} {$subject->code} a été restaurée de la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Matière non restaurée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Matière non restaurée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }


    public function activateUnactivesSubjects(): void
    {
        $this->dispatch('swal', [
            'title'              => "Réactiver les matières désactivées? ",
            'text'               => "Cette action rendra de nouveau cette ces matières disponibles et actives",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ReactivateUnactivesSubjects',
        ]);
    }

    #[On('ReactivateUnactivesSubjects')]
    public function OnReactivateUnactivesSubjects(): void
    {

        try {

            $ids = Subject::withTrashed(false)
                        ->where('is_active', false)
                        ->pluck('id')->toArray();
            
            JobBulkerActionsOnModels::dispatch(
                tenantId: tenant('id'),
                model: Subject::class,
                ids: $ids,
                method: 'update',
                options: ['is_active' => true],
                withTrashedDeleted: false,
                taskTitle: "REACTIVATION EN MASSE DES MATIERES"
            );

            $this->notification()->success(
                    title: 'PROCESSUS DE REACTIVATION DES MATIERES LANCE',
                    description: "La tâche a été lancée en arrière plan...",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'ECHEC DU PROCESSUS DE REACTIVATION DES MATIERES',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }


    public function restoreTrashedsSubjects(): void
    {
        $this->dispatch('swal', [
            'title'              => "Restorer les matières de la corbeille? ",
            'text'               => "Cette action restorera toutes les matièress de la corbeille",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Restorer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'RestoreTrashedsFiliars',
        ]);
    }

    #[On('RestoreTrashedsFiliars')]
    public function OnRestoreTrashedsSubjects(): void
    {

        try {

            $ids = Subject::onlyTrashed()->pluck('id')->toArray();
            
            JobBulkerActionsOnModels::dispatch(
                tenantId: tenant('id'),
                model: Subject::class,
                ids: $ids,
                method: 'restore',
                options: null,
                withTrashedDeleted: true,
                taskTitle: "RESTORATION EN MASSE DES MATIERES"
            );

            $this->notification()->success(
                    title: 'PROCESSUS DE RESTORATION DES MATIERES LANCE',
                    description: "La tâche a été lancée en arrière plan...",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'ECHEC DU PROCESSUS DE RESTORATION DES MATIERES',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }
}