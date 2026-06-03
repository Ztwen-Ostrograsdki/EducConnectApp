<?php

namespace App\Livewire\Auth;

use App\Models\PasswordTokenForReset;
use Illuminate\Foundation\Http\Attributes\RedirectToRoute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.guest')]
class ResetPasswordPage extends Component
{

    use WireUiActions;

    public function mount(string $token, string $email)
    {
        if ($token && $email) {

            $reset = PasswordTokenForReset::where('email', $email)->first();

            if ($reset && Hash::check(request('token'), $reset->token)) {

                return $this->redirectRoute('tenant.password.forgot', ['email' => $email, 'token' => $token]);
            }
        }
    }
    
    

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
