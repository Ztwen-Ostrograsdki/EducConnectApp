<?php

namespace App\Livewire\Central;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
class NotificationsCenter extends Component
{
    use WireUiActions;

    public int $counter = 0;

    #[On("LiveNewNotificationForCentral")]
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
        $user = Auth::guard('central')->user();

        $this->notifications = $user
            ->notifications()
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
        Auth::guard('central')->user()
            ->notifications()
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
        Auth::guard('central')->user()->unreadNotifications()->markAsRead();

        $this->relaodNotifications();
    }

    /**
     * Supprimer une notification.
     */
    public function supprimer(string $id): void
    {
        Auth::guard('central')->user()
            ->notifications()
            ->where('id', $id)
            ->delete();

        $this->relaodNotifications();
    }

    /**
     * Tout supprimer.
     */
    public function toutSupprimer(): void
    {
        Auth::guard('central')->user()->notifications()->delete();

        $this->relaodNotifications();
    }
    
    public function render()
    {
        return view('livewire.central.notifications-center');
    }
}
