<?php

namespace App\Livewire\Tenants\Parents;

use App\Events\DataUpdatedEvent;
use App\Models\SchoolYear;
use App\Models\StudentTutorRelation;
use App\Models\Tutor;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Profil Parent/Tuteur d'élève")]
class ParentProfil extends Component
{
    use WireUiActions;

    public string $parent_uuid;

    public ?Tutor $tutor;

    public ?Tutor $parent;

    public ?User $user;

    public function mount(string $parent_uuid)
    {
        if(!$parent_uuid) return abort(404);

        $parent = Tutor::withTrashed()->where('uuid', $parent_uuid)->firstOrFail();

        if(!$parent) return abort(404);

        $this->parent_uuid = $parent_uuid;

        $this->parent = $parent;

        $this->tutor = $parent;

        $this->user = $parent->user;
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

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

    public function removeRelation(int $studentId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Dissocier cet apprenant de cet parent ?',
            'text'               => "Le parent " . $this->tutor->getFullName() . " n'aura plus accès aux informations de cet apprenant",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, dissocier',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToUnlinkedStudentToTutor',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }


    #[On("ConfirmToUnlinkedStudentToTutor")]
    public function onConfirmToUnlinkedStudentToTutor(int $studentId)
    {
        $exists = StudentTutorRelation::query()
            ->where('tutor_id', $this->tutor->id)
            ->where('student_id', $studentId)
            ->exists();

        if ($exists) {
             $this->notification()->error(
                'RELATION INTROUVABLE',
                "Aucune relation trouvée correspondant"
            );
            return;
        }

        $del = $exists->delete();

        if($del){

            $this->notification()->success(
                'RELATION SUPPRIMEE',
            );
        }


        broadcast(new DataUpdatedEvent(tenant('id')));
    }



    public function render()
    {
        return view('livewire.tenants.parents.parent-profil');
    }
}
