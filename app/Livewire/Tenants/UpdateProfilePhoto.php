<?php

namespace App\Livewire\Tenants;


use App\Events\DataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

#[Title("Edition de ma photo de profil")]
class UpdateProfilePhoto extends Component
{
    use WithFileUploads, WireUiActions;

    public $photo;

    public function save(): void
    {
        $this->validate([
            'photo' => [
                'required',
                'image',
                'max:2048',
            ],
        ]);

        $user = Auth::guard('tenant')->user();

        TenantStorage::delete(
            $user->profile_photo
        );

        $path = TenantStorage::store(
            $this->photo,
            'profiles'
        );

        $done = $user->update([
            'profil_photo' => $path,
        ]);

        if($done){
            $this->notification()->send([
                'icon'        => 'success',
                'title'       => "Mise à jour de la photo réussie",
                'timeout' => 0,
                'description' => "Votre photo de profil a bien été mise à jour",
            ]);

            $this->reset('photo');

            broadcast(new DataUpdatedEvent(tenant('id')));

            $this->redirectRoute('tenant.my.profil');
        }else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "La mise à jour échouée",
                'timeout' => 0,
                'description' => "Votre photo de profil n'a pas été mise à jour",
            ]);
        }
        

    }

    #[Computed]
    public function currentPhoto(): ?string
    {
        return auth()->guard('tenant')->user()->profil_photo_url;
    }

    public function removePhoto(): void
    {
        /**@var \App\Models\User */
        $user = auth('tenant')->user();

        TenantStorage::delete(
            $user->profile_photo
        );

        $done = $user->update([
            'profil_photo' => null,
        ]);

        if($done){
            $this->notification()->send([
                'icon'        => 'success',
                'title'       => "Mise à jour de la photo réussie",
                'timeout' => 0,
                'description' => "Votre photo de profil a bien été retirée",
            ]);

            $this->reset('photo');

            broadcast(new DataUpdatedEvent(tenant('id')));

            $this->redirectRoute('tenant.my.profil');
        }else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "La suppression de votre photo a échoué",
                'timeout' => 0,
                'description' => "Votre photo de profil n'a pas été mise à jour",
            ]);
        }

    }

    public function render()
    {
        /**@var \App\Models\User */
        $user = auth('tenant')->user();

        return view('livewire.tenants.update-profile-photo')->layout($user->getDashboardLayout());
    }
}