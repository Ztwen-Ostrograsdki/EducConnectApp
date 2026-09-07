<?php

namespace App\Livewire\Central;

use App\Livewire\Central\CentralTraits\CentralReloaderTrait;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Services\Subscriptions\SubscriptionService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;



#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Gestion des abonnements")]
class SubscriptionsComponent extends Component
{
    use WireUiActions, WithPagination, CentralReloaderTrait;

    public string $filter = 'actif'; 
    public string $search = '';

    // Modal de rejet
    public bool $showRejectModal = false;
    public ?int $rejectingRequestId = null;
    public string $reject_reason = '';

    public function mount()
    {
        // Restaure depuis la session au chargement de la page
        $this->filter = session('subscription.filter', 'actiif');
        $this->search = session('subscription.search', '');
    }

    public function updatedFilter(?string $value)
    {
        session(['subscription.filter' => $value]);
        $this->resetPage();
    }

    public function updatedSearch(?string $value)
    {
        session(['subscription.search' => $value]);
        $this->resetPage();
    }


    #[On('CentralDataUpdatedLiveEvent')]
    public function relaodData(): void
    {
        $this->counter++;
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function subscriptions()
    {
        return Subscription::query()
            ->when($this->filter === null, fn ($q) => $q->where('expire_at', '>', now()))
            ->when($this->filter === 'actif', fn ($q) => $q->where('expire_at', '>', now()))
            ->when($this->filter === 'expire', fn ($q) => $q->where('expire_at', '<', now()))
            ->with(['tenant', 'plan', 'subscriptionRequest'])
            ->when($this->search, function ($q) {
                $q->whereHas('tenant', function ($tq) {
                    $tq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->latest()->paginate(10);
    }

    // ─── Approbation ────────────────────────────────────────────────

    

    public function deleteSubscription(int $requestId): void
    {
        $this->dispatch('swal', [
            'title' => 'Supprimer cet abonnement ?',
            'text' => 'Cette action est irréversible.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, supprimer',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'OnConfirmToDeleteSubscription',
            'onConfirmedParams' => ['requestId' => $requestId],
        ]);
    }

    #[On('OnConfirmToDeleteSubscription')]
    public function confirmDeleteSubscription(int $requestId, SubscriptionService $service): void
    {
        $request = SubscriptionRequest::findOrFail($requestId);
        
        $service->deleteSubscription($request);

        $this->notification()->success('Abonnement supprimé', "L'abonnement a été supprimé.");
    }

    

    /**
     * À coller dans le composant Livewire Central qui liste les abonnements
     * (celui qui utilise approved-subscriptions.blade.php).
     *
     * Prérequis :
     *  - use Livewire\Attributes\On;
     *  - use WireUi\Traits\WireUiActions; (déjà présent en général)
     *  - use App\Services\Subscriptions\SubscriptionService;
     *  - use App\Models\Subscription;
     */

    public function toggleSubscriptionStatus(int $subscriptionId): void
    {
        $subscription = Subscription::find($subscriptionId);

        if (! $subscription || $subscription->isExpired()) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Action impossible',
                'description' => 'Abonnement introuvable ou déjà expiré.',
            ]);

            return;
        }

        $willSuspend = $subscription->status === 'active';

        $this->dispatch('swal', [
            'title' => $willSuspend
                ? 'Suspendre cet abonnement ?'
                : 'Réactiver cet abonnement ?',
            'text' => $willSuspend
                ? "L'abonnement #{$subscription->key} sera temporairement désactivé. Les modules liés ne seront plus utilisables tant qu'il reste suspendu."
                : "L'abonnement #{$subscription->key} sera de nouveau actif selon ses dates de validité.",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => $willSuspend ? 'Oui, suspendre' : 'Oui, activer',
            'cancelButtonText' => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor' => '#475569',
            'onConfirmed' => 'ConfirmToggleSubscriptionStatus',
            'onConfirmedParams' => ['subscriptionId' => $subscriptionId],
        ]);
    }

    #[On('ConfirmToggleSubscriptionStatus')]
    public function onConfirmToggleSubscriptionStatus(int $subscriptionId): void
    {
        $subscription = Subscription::find($subscriptionId);

        if (! $subscription) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Introuvable',
                'description' => "L'abonnement n'existe pas.",
            ]);

            return;
        }

        try {
            $updated = app(SubscriptionService::class)->toggleStatus($subscription);

            $this->notification()->send([
                'icon' => 'success',
                'title' => $updated->status === 'active' ? 'Abonnement activé' : 'Abonnement suspendu',
                'description' => $updated->status === 'active'
                    ? "L'abonnement #{$updated->key} est de nouveau actif."
                    : "L'abonnement #{$updated->key} a été suspendu.",
            ]);
        } catch (\Throwable $th) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Échec',
                'description' => cutter($th->getMessage(), 2000),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.central.subscriptions-component');
    }
}
