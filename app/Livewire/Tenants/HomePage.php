<?php

namespace App\Livewire\Tenants;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenants-default-layout')]
#[Title("Page d'acceuil")]
class HomePage extends Component
{
    
    public function render()
    {
        return view('livewire.tenants.home-page');
    }


    public function logout()
    {
        if(auth('tenant')->check()){

            Auth::guard('tenant')->logout();
        }

        session()->invalidate();

        session()->regenerate();

        $this->redirect(route('login'), navigate: false);

    }
}
