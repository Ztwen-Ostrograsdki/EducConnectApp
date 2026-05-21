<?php

namespace App\Livewire\Tenants\Filiars;

use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class FiliarProfil extends Component
{
    public string $filiar_slug;

    public string $filiar_name = 'filiar Nom';

    public ?string $school_year_selected;

    public function mount(string $filiar_slug)
    {

        $this->filiar_slug = $filiar_slug;

        $this->filiar_name = Str::random(6);

    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    public function render()
    {
        return view('livewire.tenants.filiars.filiar-profil');
    }
}
