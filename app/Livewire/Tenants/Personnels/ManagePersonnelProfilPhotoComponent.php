<?php

namespace App\Livewire\Tenants\Personnels;

use App\Events\DataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

#[Title("Gestion du photo de profil de personnel")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class ManagePersonnelProfilPhotoComponent extends Component
{

    use WithFileUploads, WireUiActions;

    public $photo;

    public Model $model;

    public Personnel $personnel;


    public function mount(Personnel $personnel): void
    {
        $this->personnel = $personnel;

        $this->model = $personnel;

    }

    public function save(): void
    {
        $this->validate([
            'photo' => [
                'required',
                'image',
                'max:2048',
            ],
        ]);

        $model = $this->model;

        if($model->profil_photo){

            try {
                TenantStorage::delete(
                    $model->profile_photo
                );
            } catch (\Throwable $th) {
                
                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => "ERREUR MISE A JOUR DE PHOTO",
                    'timeout' => 0,
                    'description' => cutter($th->getMessage(), 2000),
                ]);

                return;
            }
        }

        $path = TenantStorage::store(
            $this->photo,
            'profiles'
        );

        $done = $model->update([
            'profil_photo' => $path,
        ]);

        if($done){
            $this->notification()->send([
                'icon'        => 'success',
                'title'       => "Mise à jour de la photo réussie",
                'timeout' => 0,
                'description' => "La photo de profil a bien été mise à jour",
            ]);

            $this->reset('photo');

            broadcast(new DataUpdatedEvent(tenant('id')));

        }else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "La mise à jour échouée",
                'timeout' => 0,
                'description' => "La photo de profil n'a pas été mise à jour",
            ]);
        }

        $this->dispatch("UserDataUpdatedLiveEvent");
    }

    #[Computed]
    public function currentPhoto(): ?string
    {
        return $this->model->profil_photo_url;
    }

    public function removePhoto(): void
    {
        $personnel = $this->personnel;

        if (! $personnel || ! $personnel->profil_photo) {
            return;
        }

        try {
            $model = $personnel;

            TenantStorage::delete(
                $model->profil_photo
            );

            $done = $model->update([
                'profil_photo' => null,
            ]);

            if($done){

                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => "Mise à jour de la photo réussie",
                    'timeout' => 0,
                    'description' => "La photo de profil a bien été retirée",
                ]);
            }
            else{

                $this->notification()->send([
                    'icon'        => 'error',
                    'title'       => "La suppression de La photo a échoué",
                    'timeout' => 0,
                    'description' => "La photo de profil n'a pas été mise à jour",
                ]);
            }
        } catch (\Throwable $e) {
            $this->notification()->error(
                title: 'ECHEC DE LA MISE A JOUR DE LA PHOTO',
                description: "Une erreur s'est produite : " . cutter($e->getMessage(), 2000),
            );

            return;
        }
        finally{

            broadcast(new DataUpdatedEvent(tenant('id')));
        }

        $personnel->update(['profil_photo' => null]);

        $this->notification()->success(
            title: 'Photo retirée',
            description: "La photo de « {$personnel->full_name} » a été supprimée."
        );

    }


    public function render()
    {
        return view('livewire.tenants.personnels.manage-personnel-profil-photo-component');
    }
}
