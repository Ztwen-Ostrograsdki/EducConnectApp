<?php

namespace App\Livewire\Tenants\Filiars;

use App\Events\DataUpdatedEvent;
use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Models\Filiar;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
class FiliarProfil extends Component
{
    use WireUiActions, WithPagination;

    public ?Filiar $filiar;

    public string $filiar_slug;

    public string $filiar_name = 'filiar Nom';

    public ?string $school_year_selected;

    public $counter = 0;

    public string $grid_cols = '2xl:grid-cols-7';


    public function mount(string $filiar_slug)
    {

        if(!$filiar_slug) return abort(404);

        $this->filiar_slug  = $filiar_slug;

        $filiar = Filiar::withTrashed()->whereSlug($filiar_slug)?->first();

        if(!$filiar) return abort(404);

        $this->filiar       = $filiar;
        $this->filiar_name       = $filiar->name;

    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function kpis()
    {
        $data = [
            ['Promotions', __zero($this->filiar->promotions->count()), 'text-amber-400'], 
            ['Classes', __zero($this->filiar->getFiliarClassesOfSchoolYearCount()), 'text-amber-400'], 
            ['Enseignants', __zero($this->filiar->getFiliarTeachersOfSchoolYearCount()), 'text-violet-400'],
            ['Apprenants', __zero($this->filiar->getFiliarStudentsOfSchoolYearCount()), 'text-indigo-400'], 
            ['Meilleure classe', '-', 'text-emerald-400'], 
            ['Faible classe', '-', 'text-rose-400'], 
            ['Meilleur élève', '-', 'text-sky-400'], 
            ['Meilleur moyenne', '-', 'text-sky-400'],  
        ];

        return $data;
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }


    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return $this->filiar->classes()->where('classes.school_year_id', $this->activeYear->id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc')->get();
    }

    #[Computed]
    public function subjects()
    {
        return $this->filiar?->getFiliarSubjectsOfSchoolYear()->orderBy('name', 'desc')->get();
    }
    
    #[Computed]
    public function promotions()
    {
        return $this->filiar?->promotions;
    }

    #[Computed]
    public function teachers()
    {
        return $this->filiar->getFiliarTeachersOfSchoolYear()
                            ->orderBy('users.name')
                            ->orderBy('users.prenames')
                            ->get();
    }

    
    #[Computed]
    public function students()
    {
        return $this->filiar->getFiliarStudentsOfSchoolYear()
                            ->orderBy('students.name')
                            ->orderBy('students.prenames')
                            ->get();
    }

    public function deleteFiliar(int $filiarId): void
    {
        $this->dispatch('swal', [
            'title'              => "Supprimer cette filière ? ",
            'text'               => "Cette action enverra la filière dans la corbeille, alors cette filière ne sera plus disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteFiliar',
            'onConfirmedParams'  => ['filiarId' => $filiarId],
        ]);
    }

    #[On('ConfirmToDeleteFiliar')]
    public function OnConfirmToDeleteFiliar(int $filiarId): void
    {
        $filiar = Filiar::find($filiarId);

        if (!$filiar) {

            $this->notification()->error(title: 'Filière introuvable');
            return;
        }

        try {
            
            $done = $filiar->delete();

            if($done){

                $this->notification()->success(
                    title: 'Filière supprimée',
                    description: "La filière {$filiar->name} {$filiar->code} a été envoyée dans la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Filière non supprimée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Filière non supprimée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

	public function restoreFiliar(int $filiarId): void
    {
        $this->dispatch('swal', [
            'title'              => "Recuperer cette filière ? ",
            'text'               => "Cette action restaurera la filière de la corbeille, alors cette filière sera disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Recuperer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestoreFiliar',
            'onConfirmedParams'  => ['filiarId' => $filiarId],
        ]);
    }

    #[On('ConfirmToRestoreFiliar')]
    public function OnConfirmToRestoreFiliar(int $filiarId): void
    {
        $filiar = Filiar::withoutTrashed()->whereId($filiarId)->first();

        if (!$filiar) {

            $this->notification()->error(title: 'Filière introuvable');
            return;
        }

        try {
            
            $done = $filiar->restore();

            if($done){

                $this->notification()->success(
                    title: 'Filière restaurée',
                    description: "La filière {$filiar->name} {$filiar->code} a été restaurée de la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Filière non restaurée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Filière non restaurée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

    public function render()
    {
        return view('livewire.tenants.filiars.filiar-profil');
    }
}
