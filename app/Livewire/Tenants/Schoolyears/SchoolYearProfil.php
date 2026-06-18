<?php

namespace App\Livewire\Tenants\Schoolyears;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Profil année scolaire')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class SchoolYearProfil extends Component
{
    public ?string $school_year_slug;

    public ?string $school_year_uuid;


    public function mount($school_year)
    {
        if(!$school_year) return abort(404);

        $this->school_year_slug = $school_year;

        
    }


    public function render()
    {
        return view('livewire.tenants.schoolyears.school-year-profil');
    }
}
