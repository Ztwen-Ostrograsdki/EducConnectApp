<?php

namespace App\Livewire\Tenants;

use App\Helpers\Support\TenantStorage;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Mon profil")]
class MyProfilPage extends Component
{
    use WireUiActions;

    public $counter = 0;

    public function mount()
    {
       
    }

    public function removePhoto(): void
    {

        /** @var \App\Models\User $user */
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

    
    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }
    
    public function render()
    {
        /** @var \App\Models\User $user */
        $user = auth('tenant')->user();
        
        return view('livewire.tenants.my-profil-page', compact('user'))->layout($user->getDashboardLayout());
    }
}
