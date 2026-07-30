<?php

namespace App\Livewire\Tenants\Filiars;

use App\Models\Filiar;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Liste des enseignants d'une filière")]
class FiliarTeachersListComponent extends Component
{
    use WireUiActions;

    public ?Filiar $filiar;

    public string $filiar_slug;

    public int $counterh = 0;

    public function mount(string $filiar_slug)
    {

        if(!$filiar_slug) return abort(404);

        $this->filiar_slug  = $filiar_slug;

        $filiar = Filiar::withTrashed()->whereSlug($filiar_slug)?->first();

        if(!$filiar) return abort(404);

        $this->filiar       = $filiar;


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
        return view('livewire.tenants.filiars.filiar-teachers-list-component');
    }
}
