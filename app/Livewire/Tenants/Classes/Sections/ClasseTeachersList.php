<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Models\Classe;
use Livewire\Attributes\On;
use Livewire\Component;

class ClasseTeachersList extends Component
{
    public string $classroom;

    public ?Classe $classe;

    public ?string $classe_slug;

    public $counter = 0;


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-teachers-list');
    }
}
