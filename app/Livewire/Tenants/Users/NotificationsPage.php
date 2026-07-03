<?php

namespace App\Livewire\Tenants\Users;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Title("Mes notifications")]
class NotificationsPage extends Component
{
    use WireUiActions, WithPagination;

    /**
     * 'all' | 'unread' | 'read'
     */
    public string $filterType = 'all';

    #[On('NewNotificationReceivedLiveEvent')]
    public function newNotificationsReceived(): void
    {
        $this->dispatch('ReloadNotificationsDataLiveEvent');
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
        $user = Auth::guard('tenant')->user();

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
        $user = Auth::guard('tenant')->user();

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
        $user = Auth::guard('tenant')->user();

        $user
            ->notifications()
            ->where('id', $id)
            ->first()
            ?->markAsRead();

        unset($this->notifications, $this->unreadCount);
        $this->dispatch('ReloadNotificationsDataLiveEvent');
    }

    /**
     * Tout marquer comme lu (toutes, pas seulement 20 — ajuste si tu
     * voulais vraiment limiter, mais une limite silencieuse sur une action
     * "Tout lire" est trompeuse pour l'utilisateur).
     */
    public function markAllAsRead(): void
    {
        Auth::guard('tenant')->user()->unreadNotifications->markAsRead();

        unset($this->notifications, $this->unreadCount);
        $this->dispatch('ReloadNotificationsDataLiveEvent');
    }

    /**
     * Demande de confirmation SweetAlert2 avant suppression unitaire.
     */
    public function confirmDeleteNotification(string $id): void
    {
        $this->dispatch('swal', [
            'title'             => 'Supprimer cette notification ?',
            'text'              => 'Cette action est irréversible.',
            'icon'              => 'warning',
            'showCancelButton'  => true,
            'confirmButtonText' => 'Supprimer',
            'cancelButtonText'  => 'Annuler',
            'onConfirmed'       => 'deleteNotification',
            'onConfirmedParams' => [$id],
        ]);
    }

    public function deleteNotification(string $id): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('tenant')->user();

        $user
            ->notifications()
            ->where('id', $id)
            ->delete();

        unset($this->notifications, $this->unreadCount);
        $this->dispatch('ReloadNotificationsDataLiveEvent');
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
            'onConfirmed'       => 'deleteAll',
        ]);
    }

    public function deleteAll(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('tenant')->user();

        $user->notifications()->limit(50)->delete();

        $this->resetPage();
        unset($this->notifications, $this->unreadCount);
        $this->dispatch('ReloadNotificationsDataLiveEvent');
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = auth('tenant')->user();

        return view('livewire.tenants.users.notifications-page')
        ->layout($user->getDashboardLayout());
    }
}