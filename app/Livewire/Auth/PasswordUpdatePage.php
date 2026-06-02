<?php

namespace App\Livewire\Auth;

use App\Jobs\JobToNotifyUserAfterPasswordUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class PasswordUpdatePage extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::defaults(),
            ],
        ]);

        try {
            $user = Auth::guard('tenant')->user();

            $done = $user->update([
                'password' => Hash::make($this->password),
            ]);

            if($done){

                $logged_count = Auth::guard('tenant')->user()->logged_count;

                if($logged_count == 0){
                    $done = $user->update([
                        'logged_count' => $logged_count + 1,
                    ]);

                }

                $domain = request()->getSchemeAndHttpHost();

                JobToNotifyUserAfterPasswordUpdated::dispatch($user, $domain);

                Auth::logoutOtherDevices($this->password);

                $this->reset([
                    'current_password',
                    'password',
                    'password_confirmation',
                ]);

                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Mot de passe modifié',
                    'description' => "Votre mot de passe a été modifié avec succès!"
                ]);

                return $this->redirectRoute('tenant.my.profil');

            }
            else{
                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => 'Echec de mise à jour du mot de passe',
                    'description' => "La modification de votre mot de passe a échoué!",
                ]);


            }
        } catch (\Throwable $th) {
            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Echec de mise à jour du mot de passe',
                'description' => "Une erreure est survenue : " . $th->getMessage(),
            ]);
        }
    }

    
    public function render()
    {
        return view('livewire.auth.password-update-page');
    }
}
