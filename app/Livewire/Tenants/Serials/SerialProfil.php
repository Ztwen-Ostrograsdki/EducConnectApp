<?php

namespace App\Livewire\Tenants\Serials;

use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class SerialProfil extends Component
{
    public string $serial_slug;

    public string $serial_name = 'serial Nom';

    public ?string $school_year_selected;

    public function mount(string $serial_slug)
    {

        $this->serial_slug = $serial_slug;

        $this->serial_name = Str::random(6);

    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    public function render()
    {
        return view('livewire.tenants.serials.serial-profil');
    }
}
