<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBadge extends Component
{
    public $guard = 'tenant';

    public $counter = 0;

    public $unreadCount = 0;


    #[On("NewNotificationReceivedLiveEvent")]
    public function newNotificationsReceived()
    {
        $this->relaodNotifications();
    }


    #[On("ReloadNotificationsDataLiveEvent")]
    public function loader()
    {
        $this->relaodNotifications();
    }


    public function relaodNotifications()
    {
        $this->counter++;
    }

    public function render()
    {
        $user = Auth::guard($this->guard)->user();

        $this->unreadCount = count($user->unreadNotifications);

        $notifications = $user
            ->notifications()
            ->latest()
            ->limit(3)
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
        return view('livewire.notification-badge', compact('notifications'));
    }
}
