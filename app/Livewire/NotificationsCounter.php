<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('')]
class NotificationsCounter extends Component
{
    public $guard = 'tenant';

    public $counter = 0;


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

        $unreadCount = count($user->unreadNotifications);

        return view('livewire.notifications-counter', compact('unreadCount'));
    }
}
