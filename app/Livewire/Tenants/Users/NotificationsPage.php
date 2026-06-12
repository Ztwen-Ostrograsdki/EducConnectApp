<?php

namespace App\Livewire\Tenants\Users;

use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class NotificationsPage extends Component
{
    use WireUiActions;

    public int $counter = 0;

    #[On("NewNotificationReceivedLiveEvent")]
    public function newNotificationsReceived()
    {
        $this->relaodNotifications();
    }

    /** @var Collection */
    public Collection $notifications;

    /** Nombre de non-lues */
    public int $unreadCount = 0;

    /** Panneau ouvert/fermé */
    public bool $open = false;

    public function mount(): void
    {
        $this->relaodNotifications();
    }

    /**
     * Charge les 30 dernières notifications de l'utilisateur connecté.
     */
    public function relaodNotifications(): void
    {
        $user = auth('tenant')->user();

        $this->notifications = $user
            ->notifications
            ->latest()
            ->limit(30)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'title'      => $n->data['title'],
                'message'    => $n->data['message'],
                'type'       => $n->data['type'] ?? 'info',
                'url'        => $n->data['url'] ?? null,
                'read_at'    => $n->read_at?->toISOString(),
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        $this->unreadCount = $user->unreadNotifications->count();
    }

    /**
     * Marquer une notification comme lue.
     */
    public function marquerLue(string $id): void
    {
        auth('tenant')->user()
            ->notifications
            ->where('id', $id)
            ->first()
            ?->markAsRead();

        $this->relaodNotifications();
    }

    /**
     * Tout marquer comme lu.
     */
    public function toutMarquerLu(): void
    {
        auth('tenant')->user()->unreadNotifications->markAsRead();

        $this->relaodNotifications();
    }

    /**
     * Supprimer une notification.
     */
    public function supprimer(string $id): void
    {
        auth('tenant')->user()
            ->notifications
            ->where('id', $id)
            ->delete();

        $this->relaodNotifications();
    }

    /**
     * Tout supprimer.
     */
    public function toutSupprimer(): void
    {
        auth('tenant')->user()->notifications->delete();

        $this->relaodNotifications();
    }


    public function render()
    {
        return view('livewire.tenants.users.notifications-page');
    }
}
