<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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
     *
     * 
     */
    public function login()
    {
        $this->validate();

        // Rate limiting — max 5 tentatives par minute
        $key = 'central.login.' . Str::lower($this->email) . '.' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = "Trop de tentatives. Réessayez dans {$seconds} secondes.";
            return;
        }

        if (!Auth::attempt([
            'email'    => $this->email,
            'password' => $this->password,
        ])) {
            RateLimiter::hit($key);
            $this->errorMessage = 'Identifiants incorrects.';
            $this->reset('password');
            return;
        }

        // Vérifier que c'est bien le super admin
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isSuperAdmin()) {
            Auth::logout();
            $this->errorMessage = 'Accès non autorisé.';
            return;
        }

        RateLimiter::clear($key);

        session()->regenerate();

        $this->redirect('/admin/dashboard');

        // $this->redirect(route('central.dashboard'), navigate: true);
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
        return view('livewire.auth.central-login');
        
    }
}