<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Models\Classe;
use Livewire\Attributes\On;
use Livewire\Component;

class ClasseHomePage extends Component
{
    public string $classroom;

    public ?Classe $classe;

    public $counter = 0;


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-home-page');
    }
}
