<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.guest')]
class ResetPasswordPage extends Component
{
    
    

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
