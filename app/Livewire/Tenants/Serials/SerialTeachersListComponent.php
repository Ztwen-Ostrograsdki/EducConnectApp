<?php

namespace App\Livewire\Tenants\Serials;


use App\Models\SchoolYear;
use App\Models\Serial;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Liste des enseignants d'une série")]
class SerialTeachersListComponent extends Component
{
    use WireUiActions;

    public ?Serial $serial;

    public string $serial_slug;

    public int $counterh = 0;

    public function mount(string $serial_slug)
    {

        if(!$serial_slug) return abort(404);

        $this->serial_slug  = $serial_slug;

        $serial = Serial::withTrashed()->whereSlug($serial_slug)?->first();

        if(!$serial) return abort(404);

        $this->serial       = $serial;


    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counterh++;
    }



    public function render()
    {
        return view('livewire.tenants.serials.serial-teachers-list-component');
    }
}
