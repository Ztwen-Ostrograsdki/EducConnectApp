<?php

namespace App\Livewire\Tenants\Filiars;

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

        $filiar = Filiar::whereSlug($filiar_slug)?->first();

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

    public function render()
    {
        return view('livewire.tenants.filiars.filiar-profil');
    }
}
