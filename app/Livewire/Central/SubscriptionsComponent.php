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

    public function render()
    {
        return view('livewire.central.subscriptions-component');
    }
}
