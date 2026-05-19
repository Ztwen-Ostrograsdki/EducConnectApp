<?php

namespace App\Livewire\Tenants\Classes;

use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('livewire.layouts.tenant-auth-layout')]
class ClasseProfil extends Component
{

    public $section = 'classe-home-page';

    public $classroom = "TERMINALE";

    public string $classe_slug; 

    public function mount(string $classe_slug)
    {
        $this->classe_slug = $classe_slug;
    }

    public function setSection(string $section)
    {
        session()->put('tenant_classe_section_selected', $section);

        $this->section = $section;
    }



    public function render()
    {
        if(session()->has('tenant_classe_section_selected')){

            $this->section = session('tenant_classe_section_selected');
        }
        return view('livewire.tenants.classes.classe-profil');
    }
}
