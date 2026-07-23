<?php

namespace App\Livewire\Tenants\Components;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Services\ClassesServices\ClasseEffectifsService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ClasseHeaderDetails extends Component
{

    public Classe $classe;

    public Subject $subject;

    public $counter = 0;


    #[Computed]
    public function effectifs()
    {
        $effectifs = app(ClasseEffectifsService::class)->getEffectifs($this->classe->id);

        return $effectifs;
    }

    #[Computed]
    public function principal()
    {
        return $this->classe ? $this->classe->principal : null;
    }


    #[Computed]
    public function principalSubjects()
    {
        if($this->principal){

            return $this->principal->getSubjectsForThisClasse($this->classe->id);
        }

        return [];
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[On("NewSchoolYearCreatedLiveEvent")]
    public function newSchoolYearCreated()
    {
        $this->counter++;
    }

    #[On("SchoolYearUpdatedLiveEvent")]
    public function schoolYearUpdated()
    {
        $this->counter++;
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloadData(): void
    {
        $this->counter++;
    }

    public function render()
    {
        return view('livewire.tenants.components.classe-header-details');
    }
}
