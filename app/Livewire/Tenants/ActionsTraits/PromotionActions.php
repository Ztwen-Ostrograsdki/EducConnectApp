<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Models\Promotion;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait PromotionActions{

    use WireUiActions;

	public $counter = 0;

	public function resetFilters(): void
    {
        $this->reset(['search', 'filiar_id', 'serial_id']);
        $this->resetPage();
    }


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



	public function closePromotion(int $promotionId): void
    {
        $this->dispatch('swal', [
            'title'              => "Fermer cette promotion ? ",
            'text'               => "Cette action rendra cette promotion indisponible et masquer les classes concernées!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, fermer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ClosePromotion',
            'onConfirmedParams'  => ['promotionId' => $promotionId],
        ]);
    }

    #[On('ClosePromotion')]
    public function OnClosePromotion(int $promotionId): void
    {
        $promotion = Promotion::find($promotionId);

        if (!$promotion) {

            $this->notification()->error(title: 'Promotion introuvable');
            return;
        }

        try {
            
            $done = $promotion->update(['is_active' => false]);

            if($done){

                $this->notification()->success(
                    title: 'Promotion fermée',
                    description: "La promotion {$promotion->name} {$promotion->code} a été fermée!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Promotion non fermée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Promotion non fermée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }


	public function activatePromotion(int $promotionId): void
    {
        $this->dispatch('swal', [
            'title'              => "Réactiver cette promotion ? ",
            'text'               => "Cette action rendra de nouveau cette promotion disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ReactivatePromotion',
            'onConfirmedParams'  => ['promotionId' => $promotionId],
        ]);
    }

    #[On('ReactivatePromotion')]
    public function OnReactivatePromotion(int $promotionId): void
    {
        $promotion = Promotion::find($promotionId);

        if (!$promotion) {

            $this->notification()->error(title: 'Promotion introuvable');
            return;
        }

        try {
            
            $done = $promotion->update(['is_active' => true]);

            if($done){

                $this->notification()->success(
                    title: 'Promotion réactivée',
                    description: "La promotion {$promotion->name} {$promotion->code} a été réactivée!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Promotion non réactivée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Promotion non réactivée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

	public function deletePromotion(int $promotionId): void
    {
        $this->dispatch('swal', [
            'title'              => "Supprimer cette promotion ? ",
            'text'               => "Cette action enverra la promotion dans la corbeille, alors cette promotion ne sera plus disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeletePromotion',
            'onConfirmedParams'  => ['promotionId' => $promotionId],
        ]);
    }

    #[On('ConfirmToDeletePromotion')]
    public function OnConfirmToDeletePromotion(int $promotionId): void
    {
        $promotion = Promotion::find($promotionId);

        if (!$promotion) {

            $this->notification()->error(title: 'Promotion introuvable');
            return;
        }

        try {
            
            $done = $promotion->delete();

            if($done){

                $this->notification()->success(
                    title: 'Promotion supprimée',
                    description: "La promotion {$promotion->name} {$promotion->code} a été envoyée dans la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Promotion non supprimée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Promotion non supprimée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

	public function restorePromotion(int $promotionId): void
    {
        $this->dispatch('swal', [
            'title'              => "Recuperer cette promotion ? ",
            'text'               => "Cette action restaurera la promotion de la corbeille, alors cette promotion sera disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Recuperer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestorePromotion',
            'onConfirmedParams'  => ['promotionId' => $promotionId],
        ]);
    }

    #[On('ConfirmToRestorePromotion')]
    public function OnConfirmToRestorePromotion(int $promotionId): void
    {
        $promotion = Promotion::withoutTrashed()->whereId($promotionId)->first();

        if (!$promotion) {

            $this->notification()->error(title: 'Promotion introuvable');
            return;
        }

        try {
            
            $done = $promotion->restore();

            if($done){

                $this->notification()->success(
                    title: 'Promotion restaurée',
                    description: "La promotion {$promotion->name} {$promotion->code} a été restaurée de la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Promotion non restaurée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Promotion non restaurée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }
}