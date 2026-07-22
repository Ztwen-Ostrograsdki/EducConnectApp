<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LogoutComponent extends Component
{
    public function mount()
    {
        if(auth('tenant')->check()){

            Auth::guard('tenant')->logout();

            
        }

        session()->invalidate();

        session()->regenerate();

        $this->redirect(route('login'), navigate: false);

    }



    public function render()
    {
        return view('livewire.auth.logout-component');
    }
}
