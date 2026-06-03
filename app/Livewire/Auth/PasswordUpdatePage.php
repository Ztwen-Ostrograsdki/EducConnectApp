<?php

namespace App\Livewire\Auth;

use App\Jobs\JobToNotifyUserAfterPasswordUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class PasswordUpdatePage extends Component
{
    use WireUiActions;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $password_strength_text = '';
    public string $password_strength_bg = 'bg-red-600';
    public string $password_strength_text_color = 'text-red-500';
    public string $password_strength_with = 'w-1/24';

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                'min:5',
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
                    'timeout'     => 0,
                    'description' => "Votre mot de passe a été modifié avec succès!"
                ]);

                return $this->redirectRoute('tenant.my.profil');

            }
            else{
                $this->notification()->send([
                    'icon'        => 'error',
                    'timeout'     => 0,
                    'title'       => 'Echec de mise à jour du mot de passe',
                    'description' => "La modification de votre mot de passe a échoué!",
                ]);


            }
        } catch (\Throwable $th) {
            $this->notification()->send([
                'icon'        => 'error',
                'timeout'     => 0,
                'title'       => 'Echec de mise à jour du mot de passe',
                'description' => "Une erreure est survenue : " . $th->getMessage(),
            ]);
        }
    }

    public function updatedPassword($password)
    {

        $score = 0;

        if (strlen($this->password) >= 5) $score++;
        if (strlen($this->password) >= 8) $score++;
        if (strlen($this->password) >= 10) $score++;
        if (strlen($this->password) >= 12) $score++;

        if (preg_match('/[a-z]/', $this->password)) $score++;
        if (preg_match('/[A-Z]/', $this->password)) $score++;
        if (preg_match('/[0-9]/', $this->password)) $score++;
        if (preg_match('/[\W_]/', $this->password)) $score++;

        if($score == 0){
            $this->password_strength_text = 'Mot de passe trop faible';
            $this->password_strength_bg = 'bg-red-600';
            $this->password_strength_text_color = 'text-red-500';
            $this->password_strength_with = 'w-1/24';

        }
        if($score <= 1){
            $this->password_strength_text = 'Mot de passe trop faible';
            $this->password_strength_bg = 'bg-red-600';
            $this->password_strength_text_color = 'text-red-500';
            $this->password_strength_with = 'w-3/24';

        }
        if($score > 1 && $score <= 2){

            $this->password_strength_text = 'Mot de passe trop faible';
            $this->password_strength_bg = 'bg-red-600';
            $this->password_strength_text_color = 'text-red-500';
            $this->password_strength_with = 'w-2/6';

        }
        if($score > 2 && $score <= 4){
            $this->password_strength_text = 'Mot de passe léger';
            $this->password_strength_bg = 'bg-orange-600';
            $this->password_strength_text_color = 'text-orange-500';
            $this->password_strength_with = 'w-3/6';

        }
        if($score > 4 && $score <= 5){
            $this->password_strength_text = 'Mot de passe moyen';
            $this->password_strength_bg = 'bg-green-300';
            $this->password_strength_text_color = 'text-green-300';
            $this->password_strength_with = 'w-4/6';

        }
        if($score > 6){
            $this->password_strength_text = 'Mot de passe fort';
            $this->password_strength_bg = 'bg-green-600';
            $this->password_strength_text_color = 'text-green-500';
            $this->password_strength_with = 'w-full';

        }

    }

    
    public function render()
    {
        return view('livewire.auth.password-update-page');
    }
}
