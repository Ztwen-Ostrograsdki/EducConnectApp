<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('livewire.layouts.guest')]
class TenantLogin extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public bool $remember = false;

    public string $errorMessage = '';



    /**
     * Attempt to authenticate the tenant user.
     *
     * 
     */
    public function login()
    {
        // dd(
        //     request()->getHost(),
        //     request()->getHttpHost(),
        //     request()->url(),
        // );
        
        $this->validate();

        // Rate limiting — max 5 tentatives par minute
        $key = 'login.' . Str::lower($this->email) . '.' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = "Trop de tentatives. Réessayez dans {$seconds} secondes.";
            return;
        }


        if (!Auth::guard('tenant')->attempt([
            'email'    => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            RateLimiter::hit($key);
            $this->errorMessage = 'Identifiants incorrects.';
            $this->reset('password');
            return;
        }

        Auth::shouldUse('tenant');

        // dd('Auth réussi !', Auth::user(), Auth::user()->name);

        RateLimiter::clear($key);

        session()->regenerate();

        return $this->redirectRoute('dashboard');
    }

    /**
     * Reset error message when user types.
     *
     * @return void
     */
    public function updatedEmail(): void
    {
        $this->errorMessage = '';
    }

    /**
     * Reset error message when user types.
     *
     * @return void
     */
    public function updatedPassword(): void
    {
        $this->errorMessage = '';
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.tenant-login');
    }
}