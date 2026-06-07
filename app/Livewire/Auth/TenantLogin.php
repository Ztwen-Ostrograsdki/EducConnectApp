<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
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
     */
    public function login()
    {

        $this->validate();

        // Rate limiting — max 5 tentatives par minute
        $key = 'login.'.Str::lower($this->email).'.'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = "Trop de tentatives. Réessayez dans {$seconds} secondes.";

            return;
        }

        if (! Auth::guard('tenant')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            RateLimiter::hit($key);
            $this->errorMessage = 'Identifiants incorrects.';
            $this->reset('password');

            return;
        }

        Auth::shouldUse('tenant');

        if (Auth::guard('tenant')->user()) {

            $tenant = tenant();
            
            if($tenant->domain_blocked){

                $this->errorMessage = "L'accès à votre espace est temporairement bloqué! Veuillez contacter l'administrateur!";

                session('abort-error', "Compte inacessible");

                Auth::guard('tenant')->logout();

                return;

            }

            RateLimiter::clear($key);

            session()->regenerate();

            $logged_count = Auth::guard('tenant')->user()->logged_count;


            if($logged_count < 1){

                return $this->redirectRoute('tenant.update.password');

            }
            else{

                Auth::guard('tenant')->user()->update(['logged_count' => $logged_count + 1]);

                if(Auth::guard('tenant')->user()->hasRole('directeur')){

                    return $this->redirectRoute('tenant.dashboard');

                }
                else{

                    return $this->redirectRoute('tenant.my.profil');
                }
            }
        }

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
        return view('livewire.auth.tenant-login');
    }
}
