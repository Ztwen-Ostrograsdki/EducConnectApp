<?php

namespace App\Livewire\Tenants\Users\Parent;

use App\Livewire\Tenants\ActionsTraits\StudentBulletinActions;
use App\Models\SchoolYear;
use App\Models\Tutor;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title('Mon espace parent - tuteur')]
class ParentDashboard extends Component
{

    use WireUiActions, StudentBulletinActions;

    public string $parent_uuid;

    public ?Tutor $tutor;

    public ?Tutor $parent;

    public ?User $user;

    public function mount()
    {
        $parent = auth('tenant')->user()->tutor;

        if(!$parent) return abort(403);

        $this->parent_uuid = $parent->uuid;

        $this->parent = $parent;

        $this->tutor = $parent;

        $this->user = $parent->user;
    }

    // #[Computed]
    // public function activeYear(): ?SchoolYear
    // {
    //     return SchoolYear::current()->first();
    // }

    #[Computed]
    public function parentInfos()
    {
        if($this->parent && $this->user){

            return [
                ['Téléphone', $this->user->contacts], 
                ['Nationalité', $this->user->country], 
                ['Adresse', $this->user->adresse], 
                ['Date de création de compte', __formatDate($this->parent->affiliated_at)], 
                ['Profession', $this->user->job_name], 
                ['Date naissance', __formatDate($this->user->birth_date)]
            ];
        }
        else{

            return [];
        }
    }

    #[Computed]
    public function children()
    {
        if($this->parent){

            return $this->parent->myChildren;
        }
        else{

            return [];
        }
    }


   
    
    public function render()
    {
        return view('livewire.tenants.users.parent.parent-dashboard');
    }
}
