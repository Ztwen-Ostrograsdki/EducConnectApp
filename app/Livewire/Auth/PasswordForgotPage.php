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
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.guest')]
class PasswordForgotPage extends Component
{

    use WireUiActions;
    
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
        session()->forget('reset_email');

        session()->forget('email');

        $this->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            $this->addError('email', "Veuillez vérifier votre adresse mail");
            return;
        }

        $token = Str::random(64);
        $otp = random_int(100000, 999999);

        $password_token_model = PasswordTokenForReset::updateOrCreate(
            ['email' => $this->email],
            [
                'token' => Hash::make($token),
                'otp_code' => Hash::make($otp),
                'attempts' => 0,
                'expires_at' => now()->addMinutes(15),
            ]
        );

        if($password_token_model){
            $domain = request()->getSchemeAndHttpHost();

            session()->put('email', $this->email);

            $this->notification()->send([
                'icon'        => 'success',
                'title'       => 'Un code vous été envoyé sur le ' . cutter($this->email, 9),
                'timeout'     => 0,
                'description' => "Veuillez consulter votre boîte mail"
            ]);

            JobToSendPasswordResetTokenToUser::dispatch($password_token_model, $otp, $token, $domain);

            $this->step = 2;
        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "Une erreure s'est produite",
                'timeout'     => 0,
                'description' => "Veuillez réessayer!"
            ]);


        }
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

        $this->notification()->send([
            'icon'        => 'info',
            'title'       => 'Reinitialisation du mot de passe',
            'delay'       => 0,
            'description' => "Veuillez à présent choisir un nouveau mot de passe"
        ]);


        session(['reset_email' => $this->email]);

        session()->forget('email');

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
            $this->reset('step');

            session()->forget('reset_email');

            session()->forget('email');
            return;
        }

        $done = $user->update([
            'password' => Hash::make($this->password)
        ]);

        if($done){

            PasswordTokenForReset::where('email', $user->email)->delete();

            $domain = request()->getSchemeAndHttpHost();

            $this->notification()->send([
                'icon'        => 'success',
                'title'       => 'Mot de passe mis à jour',
                'delay'       => 0,
                'description' => "Votre mot de passe a été réinitialisé avec succès!"
            ]);

            JobToNotifyUserAfterPasswordUpdated::dispatch($user, $domain);

            session()->forget('reset_email');

            session()->forget('email');

            return redirect()->route('login')
            ->with('success', 'Mot de passe mis à jour.');
        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Echec de mise à jour du mot de passe',
                'description' => "La mise à jour de votre mot de passe a échoué!",
            ]);

        }
    }

    /* =========================
        HANDLE LINK CLICK
    ==========================*/
    public function mount(?string $token = null, ?string $email = null)
    {
        if(session()->has('email')){

            $this->step = 2;
        }
        elseif(session()->has('reset_email')){

            $this->step = 3;
        }
        if ($token && $email) {

            $reset = PasswordTokenForReset::where('email', $email)->first();

            if ($reset && Hash::check($token, $reset->token)) {

                session(['reset_email' => request('email')]);

                $this->email = $email;

                $this->step = 3;

            }
        }
    }

    public function comeBack()
    {
        $this->reset();

        session()->forget('reset_email');

        session()->forget('email');

        $this->resetErrorBag();


    }

    public function render()
    {
        return view('livewire.auth.password-forgot-page');
    }
}