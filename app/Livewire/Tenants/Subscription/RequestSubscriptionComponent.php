<?php

namespace App\Livewire\Tenants\Subscription;

use App\Events\CentralDataUpdatedEvent;
use App\Events\TenantDirectorDataUpdatedEvent;
use App\Exceptions\SubscriptionRequestActionException;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Services\Subscriptions\SubscriptionService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Title("Page de demande d'abonnement")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class RequestSubscriptionComponent extends Component
{
    use WireUiActions, WithPagination;

    public ?int $selectedPlanId = null;

    public int $counter = 0;

    // Modal "J'ai payé"
    public bool $showClaimModal = false;

    public ?int $claimingRequestId = null;

    public ?string $transactionId = '';

    /**
     * Filtre liste abonnements : all | actifs | expires | desactives
     */
    #[Url]
    public string $subsFilter = 'actifs';

    public function updatingSubsFilter(): void
    {
        $this->resetPage('subsPage');
    }

    #[On('TenantDirectorDataUpdatedLiveEvent')]
    public function relaodData(): void
    {
        $this->counter++;
        unset($this->activeSubscription, $this->demandes, $this->plans, $this->subscriptions);
    }

    public function selectPlan(int $planId): void
    {
        $this->selectedPlanId = $planId;
    }

    public function confirmRequestSubscription(): void
    {
        if (! $this->selectedPlanId) {
            $this->notification()->error('Sélection requise', 'Merci de choisir un plan.');

            return;
        }

        $plan = Plan::findOrFail($this->selectedPlanId);

        $this->dispatch('swal', [
            'title' => 'Confirmer la demande ?',
            'text' => "Vous demandez le plan « {$plan->name} » ({$this->formatPrice($plan->price)}). Le paiement se fera hors ligne et devra être signalé ensuite.",
            'icon' => 'question',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, envoyer la demande',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'submitRequest',
            'onConfirmedParams' => ['planId' => $this->selectedPlanId],
        ]);
    }

    #[On('submitRequest')]
    public function submitRequest(int $planId, SubscriptionService $service): void
    {
        $service->createRequest(tenant('id'), $planId);

        $this->selectedPlanId = null;
        $this->notification()->success(
            'Demande envoyée',
            'Votre demande a été transmise. Vous recevrez une notification dès son traitement.'
        );
    }

    // ─── Signaler un paiement ───────────────────────────────────────

    public function openClaimModal(int $requestId): void
    {
        $request = SubscriptionRequest::findOrFail($requestId);

        $this->claimingRequestId = $requestId;
        $this->transactionId = $request->transaction_id;
        $this->showClaimModal = true;
    }

    public function closeClaimModal(): void
    {
        $this->showClaimModal = false;
        $this->claimingRequestId = null;
        $this->transactionId = '';
        $this->resetErrorBag();
    }

    public function submitClaimPayment(SubscriptionService $service): void
    {
        $this->validate([
            'transactionId' => 'required|string|min:3|max:100',
        ], [
            'transactionId.required' => 'Merci de renseigner l\'ID de la transaction.',
        ]);

        $request = SubscriptionRequest::findOrFail($this->claimingRequestId);

        try {
            $service->claimPayment($request, $this->transactionId);
            $this->notification()->success('Paiement signalé', 'Le central va vérifier votre paiement sous peu.');
            $this->closeClaimModal();
        } catch (SubscriptionRequestActionException $e) {
            $this->notification()->error('Action impossible', cutter($e->getMessage(), 2000));
        }
    }

    public function confirmDelete(int $requestId): void
    {
        $this->dispatch('swal', [
            'title' => 'Supprimer cette demande ?',
            'text' => 'Cette action est irréversible.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, supprimer',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'deleteRequest',
            'onConfirmedParams' => ['requestId' => $requestId],
        ]);
    }

    #[On('deleteRequest')]
    public function deleteRequest(int $requestId, SubscriptionService $service): void
    {
        $request = SubscriptionRequest::findOrFail($requestId);

        $service->deleteRequest($request);

        $this->notification()->success('Demande supprimée', 'Votre demande a bien été supprimée.');

        broadcast(new CentralDataUpdatedEvent());
        broadcast(new TenantDirectorDataUpdatedEvent(tenant('id')));
    }

    public function resetSelectedPlan(): void
    {
        $this->selectedPlanId = null;
    }

    protected function formatPrice(int $price): string
    {
        return number_format($price, 0, ',', ' ') . ' FCFA';
    }

    // ─── Computed ───────────────────────────────────────────────────

    #[Computed]
    public function activeSubscription()
    {
        return tenancy()->tenant?->activeSubscription;
    }

    #[Computed]
    public function plans()
    {
        return Plan::active()->ordered()->get();
    }

    #[Computed]
    public function demandes()
    {
        return SubscriptionRequest::with(['plan', 'subscription'])
            ->forTenant(tenant('id'))
            ->latest()
            ->get();
    }

    /**
     * Liste paginée des abonnements du tenant selon le filtre.
     */
    public function getSubscriptionsProperty()
    {
        $query = Subscription::query()
            ->with(['plan', 'subscriptionRequest'])
            ->forTenant(tenant('id'))
            ->latest('started_at');

        return match ($this->subsFilter) {
            'actifs' => $query
                ->where('status', 'active')
                ->where('expire_at', '>', now())
                ->paginate(8, pageName: 'subsPage'),
            'expires' => $query
                ->where(function ($q) {
                    $q->where('expire_at', '<=', now())
                        ->orWhere('status', 'expired');
                })
                ->paginate(8, pageName: 'subsPage'),
            'desactives' => $query
                ->where('status', 'suspended')
                ->where('expire_at', '>', now())
                ->paginate(8, pageName: 'subsPage'),
            default => $query->paginate(8, pageName: 'subsPage'),
        };
    }

    /**
     * État affiché pour un abonnement.
     *
     * @return array{key: string, label: string, color: string}
     */
    public function subscriptionState(Subscription $subscription): array
    {
        if ($subscription->status === 'suspended' && ! $subscription->isExpired()) {
            return ['key' => 'suspended', 'label' => 'Désactivé', 'color' => 'amber'];
        }

        if ($subscription->isExpired()) {
            return ['key' => 'expired', 'label' => 'Expiré', 'color' => 'rose'];
        }

        $active = $this->activeSubscription;

        if ($active && $active->id === $subscription->id) {
            return ['key' => 'running', 'label' => 'En cours', 'color' => 'emerald'];
        }

        if ($subscription->status === 'active' && $subscription->started_at?->isFuture()) {
            return ['key' => 'queued', 'label' => 'En attente (pas encore utilisé)', 'color' => 'sky'];
        }

        if ($subscription->status === 'active') {
            return ['key' => 'queued', 'label' => 'En attente (pas encore utilisé)', 'color' => 'sky'];
        }

        return ['key' => 'other', 'label' => ucfirst($subscription->status), 'color' => 'slate'];
    }

    public function render()
    {
        return view('livewire.tenants.subscription.request-subscription-component', [
            'subscriptions' => $this->subscriptions,
        ]);
    }
}
