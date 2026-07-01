<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Jobs\JobBulkerActionsOnModels;
use App\Models\Filiar;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait FiliarsActions{

	use WireUiActions;

	public $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }


	#[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function unActivesFiliars() : int
    {
        return Filiar::withTrashed(false)
                        ->where('is_active', false)
                        ->count();
    }
    
    #[Computed]
    public function trashedsFiliars() : int
    {
        return Filiar::onlyTrashed()->count();
    }


    #[Computed]
    public function activesFiliars() : int
    {
        return  Filiar::withTrashed(false)
                        ->where('is_active', true)
                        ->count();
    }

	public function closeFiliar(int $filiarId): void
    {
        $this->dispatch('swal', [
            'title'              => "Fermer cette filière ? ",
            'text'               => "Cette action rendra cette filière indisponible et masquer les classes concernées!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, fermer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToCloseFiliar',
            'onConfirmedParams'  => ['filiarId' => $filiarId],
        ]);
    }

    #[On('ConfirmToCloseFiliar')]
    public function OnCloseFiliar(int $filiarId): void
    {
        $filiar = Filiar::find($filiarId);

        if (!$filiar) {

            $this->notification()->error(title: 'Filière introuvable');
            return;
        }

        try {
            
            $done = $filiar->update(['is_active' => false]);

            if($done){

                $this->notification()->success(
                    title: 'Filière fermée',
                    description: "La filière {$filiar->name} {$filiar->code} a été fermée!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Filière non fermée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Filière non fermée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }


	public function activateFiliar(int $filiarId): void
    {
        $this->dispatch('swal', [
            'title'              => "Réactiver cette filière ? ",
            'text'               => "Cette action rendra de nouveau cette filière disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ReactivateFiliar',
            'onConfirmedParams'  => ['filiarId' => $filiarId],
        ]);
    }

    #[On('ReactivateFiliar')]
    public function OnReactivateFiliar(int $filiarId): void
    {
        $filiar = Filiar::find($filiarId);

        if (!$filiar) {

            $this->notification()->error(title: 'Filière introuvable');
            return;
        }

        try {
            
            $done = $filiar->update(['is_active' => true]);

            if($done){

                $this->notification()->success(
                    title: 'Filière réactivée',
                    description: "La filière {$filiar->name} {$filiar->code} a été réactivée!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Filière non réactivée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Filière non réactivée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

	public function deleteFiliar(int $filiarId): void
    {
        $this->dispatch('swal', [
            'title'              => "Supprimer cette filière ? ",
            'text'               => "Cette action enverra la filière dans la corbeille, alors cette filière ne sera plus disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteFiliar',
            'onConfirmedParams'  => ['filiarId' => $filiarId],
        ]);
    }

    #[On('ConfirmToDeleteFiliar')]
    public function OnConfirmToDeleteFiliar(int $filiarId): void
    {
        $filiar = Filiar::find($filiarId);

        if (!$filiar) {

            $this->notification()->error(title: 'Filière introuvable');
            return;
        }

        try {
            
            $done = $filiar->delete();

            if($done){

                $this->notification()->success(
                    title: 'Filière supprimée',
                    description: "La filière {$filiar->name} {$filiar->code} a été envoyée dans la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Filière non supprimée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Filière non supprimée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

	public function restoreFiliar(int $filiarId): void
    {
        $this->dispatch('swal', [
            'title'              => "Recuperer cette filière ? ",
            'text'               => "Cette action restaurera la filière de la corbeille, alors cette filière sera disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Recuperer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestoreFiliar',
            'onConfirmedParams'  => ['filiarId' => $filiarId],
        ]);
    }

    #[On('ConfirmToRestoreFiliar')]
    public function OnConfirmToRestoreFiliar(int $filiarId): void
    {
        $filiar = Filiar::withoutTrashed()->whereId($filiarId)->first();

        if (!$filiar) {

            $this->notification()->error(title: 'Filière introuvable');
            return;
        }

        try {
            
            $done = $filiar->restore();

            if($done){

                $this->notification()->success(
                    title: 'Filière restaurée',
                    description: "La filière {$filiar->name} {$filiar->code} a été restaurée de la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Filière non restaurée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Filière non restaurée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }


    public function activateUnactivesFiliars(): void
    {
        $this->dispatch('swal', [
            'title'              => "Réactiver les filière désactivées? ",
            'text'               => "Cette action rendra de nouveau cette ces filières disponibles et actives",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ReactivateUnactivesFiliars',
        ]);
    }

    #[On('ReactivateUnactivesFiliars')]
    public function OnReactivateUnactivesFiliars(): void
    {

        try {

            $ids = Filiar::withTrashed(false)
                        ->where('is_active', false)
                        ->pluck('id')->toArray();
            
            JobBulkerActionsOnModels::dispatch(
                tenantId: tenant('id'),
                model: Filiar::class,
                ids: $ids,
                method: 'update',
                options: ['is_active' => true],
                withTrashedDeleted: false,
                taskTitle: "REACTIVATION EN MASSE DES FILIERES"
            );

            $this->notification()->success(
                    title: 'PROCESSUS DE REACTIVATION DES FILIERES LANCE',
                    description: "La tâche a été lancée en arrière plan...",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'ECHEC DU PROCESSUS DE REACTIVATION DES FILIERES',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }


    public function restoreTrashedsFiliars(): void
    {
        $this->dispatch('swal', [
            'title'              => "Restorer les filière de la corbeille? ",
            'text'               => "Cette action restorera toutes les filières de la corbeille",
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
    public function OnRestoreTrashedsFiliars(): void
    {

        try {

            $ids = Filiar::onlyTrashed()->pluck('id')->toArray();
            
            JobBulkerActionsOnModels::dispatch(
                tenantId: tenant('id'),
                model: Filiar::class,
                ids: $ids,
                method: 'restore',
                options: null,
                withTrashedDeleted: true,
                taskTitle: "RESTORATION EN MASSE DES FILIERES"
            );

            $this->notification()->success(
                    title: 'PROCESSUS DE RESTORATION DES FILIERES LANCE',
                    description: "La tâche a été lancée en arrière plan...",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'ECHEC DU PROCESSUS DE RESTORATION DES FILIERES',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }
}