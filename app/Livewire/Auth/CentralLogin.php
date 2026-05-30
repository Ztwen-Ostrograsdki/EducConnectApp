<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('livewire.layouts.guest-central')]
class CentralLogin extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public string $errorMessage = '';

    /**
     * Attempt to authenticate the super admin user.
     */
    public function login()
    {
        $this->validate();

        // Rate limiting — max 5 tentatives par minute
        $key = 'central.login.'.Str::lower($this->email).'.'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = "Trop de tentatives. Réessayez dans {$seconds} secondes.";

            return;
        }

        if (! Auth::guard('central')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ])) {
            RateLimiter::hit($key);
            $this->errorMessage = 'Identifiants incorrects.';
            $this->reset('password');

            return;
        }

        Auth::shouldUse('central');

        RateLimiter::clear($key);

        session()->regenerate();

        return redirect()->route('central.dashboard');

    }

    /**
     * Reset error message when user types.
     */
    public function updatedEmail(): void
    {
        $this->errorMessage = '';
    }

    /**
     * Reset error message when user types.
     */
    public function updatedPassword(): void
    {
        $this->errorMessage = '';
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.auth.central-login');

    }
}
