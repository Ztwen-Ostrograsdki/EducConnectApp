<?php

namespace App\Livewire\Tenants\Classes\Sections;

use Livewire\Component;

class ClasseTeachersList extends Component
{
    public string $classroom;
    
    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-teachers-list');
    }
}
