<?php

namespace App\Livewire\Auth;

use App\Jobs\JobToNotifyUserAfterPasswordUpdated;
use App\Jobs\JobToSendPasswordResetTokenToUser;
use App\Models\PasswordReset;
use App\Models\PasswordTokenForReset;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.guest')]
class PasswordForgotPage extends Component
{
    public $step = 1;

    public string $email;
    public int $otp;
    public string $password;
    public string $password_confirmation;

    public string $token;

    /* =========================
        STEP 1 : SEND EMAIL
    ==========================*/
    public function sendReset()
    {

        $this->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            $this->addError('email', 'Aucun compte trouvé.');
            return;
        }

        $token = Str::random(64);
        $otp = random_int(100000, 999999);

        $password = PasswordTokenForReset::updateOrCreate(
            ['email' => $this->email],
            [
                'token' => Hash::make($token),
                'otp_code' => Hash::make($otp),
                'attempts' => 0,
                'expires_at' => now()->addMinutes(15),
            ]
        );

        $domain = request()->getSchemeAndHttpHost();

        JobToSendPasswordResetTokenToUser::dispatch($password, $otp, $domain);

        $this->step = 2;
    }

    /* =========================
        STEP 2 : VERIFY OTP
    ==========================*/
    public function verifyOtp()
    {
        $this->validate([
            'otp' => ['required']
        ]);

        $reset = PasswordTokenForReset::where('email', $this->email)->first();

        if (! $reset) {
            $this->addError('otp', 'Session invalide.');
            return;
        }

        if (Carbon::parse($reset->expires_at)->isPast()) {
            $this->addError('otp', 'Code expiré.');
            return;
        }

        if ($reset->attempts >= 5) {
            $this->addError('otp', 'Trop de tentatives.');
            return;
        }

        if (! Hash::check($this->otp, $reset->otp_code)) {
            $reset->increment('attempts');

            $this->addError('otp', 'Code incorrect.');
            return;
        }

        session(['reset_email' => $this->email]);

        $this->step = 3;
    }

    /* =========================
        STEP 3 : RESET PASSWORD
    ==========================*/
    public function resetPassword()
    {
        $this->validate([
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (! $user) {
            return;
        }

        $user->update([
            'password' => Hash::make($this->password)
        ]);

        PasswordTokenForReset::where('email', $user->email)->delete();

        $domain = request()->getSchemeAndHttpHost();

        JobToNotifyUserAfterPasswordUpdated::dispatch($user, $domain);

        session()->forget('reset_email');

        return redirect()->route('login')
            ->with('success', 'Mot de passe mis à jour.');
    }

    /* =========================
        HANDLE LINK CLICK
    ==========================*/
    public function mount()
    {
        if (request()->has('token') && request()->has('email')) {

            $reset = PasswordTokenForReset::where('email', request('email'))->first();

            if ($reset && Hash::check(request('token'), $reset->token)) {

                session(['reset_email' => request('email')]);

                $this->email = request('email');

                $this->step = 3;
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.password-forgot-page');
    }
}