<?php

namespace App\Livewire\Tenants;

use App\Events\StudentDataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

#[Title("Mise à jour de la photo de profil")]
class ProfilPhotoManagerByDirectorComponent extends Component
{
    use WithFileUploads, WireUiActions;

    public $photo;

    public ?string $classMapping = '';

    public string $modelUuid = '';

    public string $target = '';

    public Model $model;


    public function mount(string $target, string $modelUuid)
    {

        if(!$modelUuid || !$target) return abort(404);

        $this->modelUuid = $modelUuid;

        $this->target = $target;

        if($target == 'enseignant'){

            $user = Teacher::withTrashed()->where('uuid', $modelUuid)->first()?->user;

            if($user) $this->model = $user;

        }
        elseif($target == 'apprenant'){

            $student = Student::withTrashed()->where('uuid', $modelUuid)->first();

            if($student) $this->model = $student;

        }
        elseif($target == 'parent'){

            $user = Tutor::withTrashed()->where('uuid', $modelUuid)->first()?->user;

            if($user) $this->model = $user;

        }
        else{

            return abort(404);

        }

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

        TenantStorage::delete(
            $model->profile_photo
        );

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
        $model = $this->model;

        TenantStorage::delete(
            $model->profile_photo
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

            $this->reset('photo');

            $domain = request()->getSchemeAndHttpHost();

            StudentDataUpdatedEvent::dispatch(
                tenantId: tenant('id'),
                studentId: $this->model->id,
                domain: $domain
            );


        }else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => "La suppression de La photo a échoué",
                'timeout' => 0,
                'description' => "La photo de profil n'a pas été mise à jour",
            ]);
        }

    }

    public function render()
    {
        /** @var \App\Models\User $user **/
        $user = auth('tenant')->user();

        
        
        return view('livewire.tenants.profil-photo-manager-by-director-component')->layout($user->getDashboardLayout());
    }
}
