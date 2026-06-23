<?php

namespace App\Livewire\Tenants\Filiars;

use App\Models\Filiar;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class FiliarProfil extends Component
{
    public ?Filiar $filiar;

    public string $filiar_slug;

    public string $filiar_name = 'filiar Nom';

    public ?string $school_year_selected;

    public function mount(string $filiar_slug)
    {

        if(!$filiar_slug) return abort(404);

        $this->filiar_slug  = $filiar_slug;

        $filiar = Filiar::whereSlug($filiar_slug)?->first();

        if(!$filiar) return abort(404);

        $this->filiar       = $filiar;
        $this->filiar_name       = $filiar->name;

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
