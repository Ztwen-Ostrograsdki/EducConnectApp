<?php

namespace App\Livewire\Tenants\Classes\Sections;

use Livewire\Component;

class ClasseHomePage extends Component
{
    public string $classroom;

    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-home-page');
    }
}
