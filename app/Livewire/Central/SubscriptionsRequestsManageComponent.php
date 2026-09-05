<?php

namespace App\Livewire\Central;

use App\Exceptions\SubscriptionRequestActionException;
use App\Livewire\Central\CentralTraits\CentralReloaderTrait;
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
#[Title("Gestion des demandes d'abonnement")]
class SubscriptionsRequestsManageComponent extends Component
{
    use WireUiActions, WithPagination, CentralReloaderTrait;

    public string $filter = 'awaiting'; // awaiting | approved | rejected | all
    public string $search = '';

    // Modal de rejet
    public bool $showRejectModal = false;
    public ?int $rejectingRequestId = null;
    public string $reject_reason = '';

    public function mount()
    {
        // Restaure depuis la session au chargement de la page
        $this->filter = session('subscription_requests.filter', 'awaiting');
        $this->search = session('subscription_requests.search', '');
    }

    public function updatedFilter(?string $value)
    {
        session(['subscription_requests.filter' => $value]);
        $this->resetPage();
    }

    public function updatedSearch(?string $value)
    {
        session(['subscription_requests.search' => $value]);
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
    public function requests()
    {
        return SubscriptionRequest::query()
            ->with(['tenant', 'plan', 'treatedBy'])
            ->when($this->filter === 'awaiting', fn ($q) => $q->awaitingAction())
            ->when($this->filter === 'approved', fn ($q) => $q->where('status', 'approved'))
            ->when($this->filter === 'rejected', fn ($q) => $q->where('status', 'rejected'))
            ->when($this->search, function ($q) {
                $q->whereHas('tenant', function ($tq) {
                    $tq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->latest()->paginate(20);
    }

    // ─── Approbation ────────────────────────────────────────────────

    public function confirmApprove(int $requestId): void
    {
        $request = SubscriptionRequest::with('plan', 'tenant')->findOrFail($requestId);

        $this->dispatch('swal', [
            'title' => 'Approuver cette demande ?',
            'text' => "Le plan « {$request->plan->name} » sera activé pour « {$request->tenant->getFullName()} ». Les modules correspondants seront appliqués immédiatement.",
            'icon' => 'question',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, approuver',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'approveRequest',
            'onConfirmedParams' => ['requestId' => $requestId],
        ]);
    }

    #[On('approveRequest')]
    public function approveRequest(int $requestId, SubscriptionService $service): void
    {
        $request = SubscriptionRequest::findOrFail($requestId);

        try {
            $service->approve($request, auth('central')->user());
            $this->notification()->success('Demande approuvée', "L'abonnement a été activé pour {$request->tenant->getFullName()}.");
        } catch (SubscriptionRequestActionException $e) {
            $this->notification()->error('Action impossible', cutter($e->getMessage(), 2000));
        }
    }

    // ─── Rejet ──────────────────────────────────────────────────────

    public function openRejectModal(int $requestId): void
    {
        $this->rejectingRequestId = $requestId;
        $this->reject_reason = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->rejectingRequestId = null;
        $this->reject_reason = '';
    }

    public function confirmReject(): void
    {
        $this->validate([
            'reject_reason' => 'required|string|min:5|max:500',
        ], [
            'reject_reason.required' => 'Merci d\'indiquer un motif de rejet.',
            'reject_reason.min' => 'Le motif doit contenir au moins 5 caractères.',
        ]);

        $this->dispatch('swal', [
            'title' => 'Confirmer le rejet ?',
            'text' => 'Le tenant sera notifié du rejet avec le motif indiqué.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, rejeter',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'rejectRequest',
            'onConfirmedParams' => ['requestId' => $this->rejectingRequestId, 'motif' => $this->reject_reason],
        ]);
    }

    #[On('rejectRequest')]
    public function rejectRequest(int $requestId, string $motif, SubscriptionService $service): void
    {
        $request = SubscriptionRequest::findOrFail($requestId);

        try {
            $service->reject($request, auth('central')->user(), $motif);
            $this->notification()->success('Demande rejetée', "La demande de {$request->tenant->getFullName()} a été rejetée.");
        } catch (SubscriptionRequestActionException $e) {
            $this->notification()->error('Action impossible', $e->getMessage());
        }

        $this->closeRejectModal();
    }

    // ─── Réclamer paiement ──────────────────────────────────────────

    public function confirmRemindPayment(int $requestId): void
    {
        $request = SubscriptionRequest::with('tenant')->findOrFail($requestId);

        $this->dispatch('swal', [
            'title' => 'Réclamer le paiement ?',
            'text' => "Un rappel sera envoyé à « {$request->tenant->getFullName()} » pour finaliser le paiement.",
            'icon' => 'info',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, envoyer le rappel',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'remindPayment',
            'onConfirmedParams' => ['requestId' => $requestId],
        ]);
    }

    #[On('remindPayment')]
    public function remindPayment(int $requestId, SubscriptionService $service): void
    {
        $request = SubscriptionRequest::findOrFail($requestId);

        try {
            $service->remindPayment($request);
            $this->notification()->success('Rappel envoyé', "Un rappel de paiement a été envoyé à {$request->tenant->getFullName()}.");
        } catch (SubscriptionRequestActionException $e) {
            $this->notification()->error('Action impossible', $e->getMessage());
        }
    }

    // ─── Suppression ────────────────────────────────────────────────

    public function deleteRequest(int $requestId): void
    {
        $this->dispatch('swal', [
            'title' => 'Supprimer cette demande ?',
            'text' => 'Cette action est irréversible.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, supprimer',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'OnConfirmToDeleteRequest',
            'onConfirmedParams' => ['requestId' => $requestId],
        ]);
    }

    #[On('OnConfirmToDeleteRequest')]
    public function confirmDeleteRequest(int $requestId, SubscriptionService $service): void
    {
        $request = SubscriptionRequest::findOrFail($requestId);

        $service->deleteRequest($request);

        $this->notification()->success('Demande supprimée', 'La demande a été supprimée.');
    }


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

    public function render()
    {
        return view('livewire.central.subscriptions-requests-manage-component');
    }
}
