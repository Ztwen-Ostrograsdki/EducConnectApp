<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Jobs\JobBulkerActionsOnModels;
use App\Models\SchoolYear;
use App\Models\Serial;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait SerialsActions{

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
    public function unActivesSerials() : int
    {
        return Serial::withTrashed(false)
                        ->where('is_active', false)
                        ->count();
    }
    
    #[Computed]
    public function trashedsSerials() : int
    {
        return Serial::onlyTrashed()->count();
    }


    #[Computed]
    public function activesSerials() : int
    {
        return  Serial::withTrashed(false)
                        ->where('is_active', true)
                        ->count();
    }

	public function closeSerial(int $serialId): void
    {
        $this->dispatch('swal', [
            'title'              => "Fermer cette série ? ",
            'text'               => "Cette action rendra cette série indisponible et masquer les classes concernées!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, fermer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToCloseSerial',
            'onConfirmedParams'  => ['serialId' => $serialId],
        ]);
    }

    #[On('ConfirmToCloseSerial')]
    public function OnCloseSerial(int $serialId): void
    {
        $serial = Serial::find($serialId);

        if (!$serial) {

            $this->notification()->error(title: 'Série introuvable');
            return;
        }

        try {
            
            $done = $serial->update(['is_active' => false]);

            if($done){

                $this->notification()->success(
                    title: 'Série fermée',
                    description: "La Série {$serial->name} {$serial->code} a été fermée!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Série non fermée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Série non fermée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }


	public function activateSerial(int $serialId): void
    {
        $this->dispatch('swal', [
            'title'              => "Réactiver cette série ? ",
            'text'               => "Cette action rendra de nouveau cette série disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ReactivateSerial',
            'onConfirmedParams'  => ['serialId' => $serialId],
        ]);
    }

    #[On('ReactivateSerial')]
    public function OnReactivateSerial(int $serialId): void
    {
        $serial = Serial::find($serialId);

        if (!$serial) {

            $this->notification()->error(title: 'Série introuvable');
            return;
        }

        try {
            
            $done = $serial->update(['is_active' => true]);

            if($done){

                $this->notification()->success(
                    title: 'Série réactivée',
                    description: "La Série {$serial->name} {$serial->code} a été réactivée!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Série non réactivée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Série non réactivée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }

	public function deleteSerial(int $serialId): void
    {
        $this->dispatch('swal', [
            'title'              => "Supprimer cette série ? ",
            'text'               => "Cette action enverra la Série dans la corbeille, alors cette série ne sera plus disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteSerial',
            'onConfirmedParams'  => ['serialId' => $serialId],
        ]);
    }

    #[On('ConfirmToDeleteSerial')]
    public function OnConfirmToDeleteSerial(int $serialId): void
    {
        $serial = Serial::find($serialId);

        if (!$serial) {

            $this->notification()->error(title: 'Série introuvable');
            return;
        }

        try {
            
            $done = $serial->delete();

            if($done){

                $this->notification()->success(
                    title: 'Série supprimée',
                    description: "La Série {$serial->name} {$serial->code} a été envoyée dans la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Série non supprimée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Série non supprimée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }

	public function restoreSerial(int $serialId): void
    {
        $this->dispatch('swal', [
            'title'              => "Recuperer cette série ? ",
            'text'               => "Cette action restaurera la Série de la corbeille, alors cette série sera disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Recuperer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestoreSerial',
            'onConfirmedParams'  => ['serialId' => $serialId],
        ]);
    }

    #[On('ConfirmToRestoreSerial')]
    public function OnConfirmToRestoreSerial(int $serialId): void
    {
        $serial = Serial::withoutTrashed()->whereId($serialId)->first();

        if (!$serial) {

            $this->notification()->error(title: 'Série introuvable');
            return;
        }

        try {
            
            $done = $serial->restore();

            if($done){

                $this->notification()->success(
                    title: 'Série restaurée',
                    description: "La Série {$serial->name} {$serial->code} a été restaurée de la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Série non restaurée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Série non restaurée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }


    public function activateUnactivesSerials(): void
    {
        $this->dispatch('swal', [
            'title'              => "Réactiver les Série désactivées? ",
            'text'               => "Cette action rendra de nouveau cette ces Séries disponibles et actives",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ReactivateUnactivesSerials',
        ]);
    }

    #[On('ReactivateUnactivesSerials')]
    public function OnReactivateUnactivesSerials(): void
    {

        try {

            $ids = Serial::withTrashed(false)
                        ->where('is_active', false)
                        ->pluck('id')->toArray();
            
            JobBulkerActionsOnModels::dispatch(
                tenantId: tenant('id'),
                model: Serial::class,
                ids: $ids,
                method: 'update',
                options: ['is_active' => true],
                withTrashedDeleted: false,
                taskTitle: "REACTIVATION EN MASSE DES SERIES"
            );

            $this->notification()->success(
                    title: 'PROCESSUS DE REACTIVATION DES SERIES LANCE',
                    description: "La tâche a été lancée en arrière plan...",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'ECHEC DU PROCESSUS DE REACTIVATION DES SERIES',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }


    public function restoreTrashedsSerials(): void
    {
        $this->dispatch('swal', [
            'title'              => "Restorer les Série de la corbeille? ",
            'text'               => "Cette action restorera toutes les Séries de la corbeille",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Restorer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'RestoreTrashedsSerials',
        ]);
    }

    #[On('RestoreTrashedsSerials')]
    public function OnRestoreTrashedsSerials(): void
    {

        try {

            $ids = Serial::onlyTrashed()->pluck('id')->toArray();
            
            JobBulkerActionsOnModels::dispatch(
                tenantId: tenant('id'),
                model: Serial::class,
                ids: $ids,
                method: 'restore',
                options: null,
                withTrashedDeleted: true,
                taskTitle: "RESTORATION EN MASSE DES SERIES"
            );

            $this->notification()->success(
                    title: 'PROCESSUS DE RESTORATION DES SERIES LANCE',
                    description: "La tâche a été lancée en arrière plan...",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'ECHEC DU PROCESSUS DE RESTORATION DES SERIES',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }
}