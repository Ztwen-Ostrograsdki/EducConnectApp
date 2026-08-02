<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ClassePupilBulletinComponent extends Component
{
    public ?int $student_id = null;

    public ?int $period = null;

    public ?string $school_year_selected;

    public ?Classe $classe = null;

    public ?Student $student = null;

    public int $counter = 0;


    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function currentClasse(): ?Classe
    {
        if($this->student && $this->student?->currentClasse()){

            return $this->student?->currentClasse()?->classe;
        }

        return null;
        
    }

    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-pupil-bulletin-component');
    }


}
