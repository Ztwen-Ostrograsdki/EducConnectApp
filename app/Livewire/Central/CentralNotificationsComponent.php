<?php

namespace App\Livewire\Central;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;


#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Mes notifications")]
class CentralNotificationsComponent extends Component
{
    use WireUiActions, WithPagination;

    /**
     * 'all' | 'unread' | 'read'
     */
    public string $filterType = 'all';

    #[On('NewCentralNotificationReceivedLiveEvent')]
    public function newNotificationsReceived(): void
    {
        unset($this->notifications, $this->unreadCount);
    }

    /**
     * IMPORTANT : on utilise through() et NON map() sur le paginator.
     * map() délègue à la Collection sous-jacente et retourne une simple
     * Collection (perte de hasPages/links/total...). through() transforme
     * les items tout en conservant l'instance LengthAwarePaginator.
     */
    #[Computed]
    public function notifications(): LengthAwarePaginator
    {
         /** @var \App\Models\User $user */
        $user = Auth::guard('central')->user();

        $query = $user->notifications()->latest();

        match ($this->filterType) {
            'unread' => $query->whereNull('read_at'),
            'read'   => $query->whereNotNull('read_at'),
            default  => null,
        };

        return $query->paginate(9)->through(fn ($n) => [
            'id'         => $n->id,
            'title'      => $n->data['title'] ?? '',
            'message'    => $n->data['message'] ?? '',
            'type'       => $n->data['type'] ?? 'info',
            'url'        => $n->data['url'] ?? null,
            'read_at'    => $n->read_at?->toISOString(),
            'created_at' => $n->created_at->diffForHumans(),
        ]);
    }

    #[Computed]
    public function unreadCount(): int
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('central')->user();

        return $user->unreadNotifications()->count();
    }

    /**
     * Reset la pagination quand on change de filtre (sinon on peut se
     * retrouver sur une page qui n'existe plus dans le sous-ensemble filtré).
     */
    public function updatedFilterType(): void
    {
        $this->resetPage();
        unset($this->notifications);
    }

    /**
     * Marquer une notification comme lue.
     */
    public function markAsRead(string $id): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('central')->user();

        $user
            ->notifications()
            ->where('id', $id)
            ->first()
            ?->markAsRead();

        unset($this->notifications, $this->unreadCount);
    }

    /**
     * Tout marquer comme lu (toutes, pas seulement 20 — ajuste si tu
     * voulais vraiment limiter, mais une limite silencieuse sur une action
     * "Tout lire" est trompeuse pour l'utilisateur).
     */
    public function markAllAsRead(): void
    {
        Auth::guard('central')->user()->unreadNotifications->markAsRead();

        unset($this->notifications, $this->unreadCount);
    }

    /**
     * Demande de confirmation SweetAlert2 avant suppression unitaire.
     */
    public function deleteNotification(string $id): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('central')->user();

        $user
            ->notifications()
            ->where('id', $id)
            ->delete();

        unset($this->notifications, $this->unreadCount);
    }

    /**
     * Demande de confirmation SweetAlert2 avant suppression totale.
     */
    public function confirmDeleteAll(): void
    {
        $this->dispatch('swal', [
            'title'             => 'Tout supprimer ?',
            'text'              => 'Toutes vos notifications seront définitivement supprimées.',
            'icon'              => 'warning',
            'showCancelButton'  => true,
            'confirmButtonText' => 'Oui, tout supprimer',
            'cancelButtonText'  => 'Annuler',
            'onConfirmed'       => 'ConfirmToDelete',
        ]);
    }

    #[On('ConfirmToDelete')]
    public function deleteAll(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('central')->user();
        

        $user->notifications()->limit(50)->delete();

        $this->resetPage();
        unset($this->notifications, $this->unreadCount);

    }

    public function render()
    {
        return view('livewire.central.central-notifications-component');
    }
}
