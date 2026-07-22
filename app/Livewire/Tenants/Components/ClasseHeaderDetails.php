<?php

namespace App\Livewire\Tenants\Components;

use App\Models\Classe;
use App\Models\Subject;
use App\Services\ClassesServices\ClasseEffectifsService;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ClasseHeaderDetails extends Component
{

    public Classe $classe;

    public Subject $subject;


    #[Computed]
    public function effectifs()
    {
        $effectifs = app(ClasseEffectifsService::class)->getEffectifs($this->classe->id);

        return $effectifs;
    }

    
    public function render()
    {
        return view('livewire.tenants.components.classe-header-details');
    }
}
