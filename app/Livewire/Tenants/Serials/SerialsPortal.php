<?php

namespace App\Livewire\Tenants\Serials;

use App\Livewire\Tenants\ActionsTraits\SerialsActions;
use App\Models\Serial;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des séries")]
class SerialsPortal extends Component
{
    use WithPagination, SerialsActions;


    public $is_active = "actives";

    public ?string $search = null;

    public int $perPage = 15;

    public int $counterh = 0;

    public function mount()
    {
        if(session()->has('serials_is_active_selected')){

            $this->is_active = session('serials_is_active_selected');
        }
    }


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counterh++;
    }


    public function updatingSearch()
    {
        $this->resetPage();
    } 
    
    public function updatingIsActive()
    {
        $this->resetPage();
    }


    public function updatedIsActive(?string $value)
    {
        session()->put('serials_is_active_selected', $this->is_active);
    }


    #[Computed]
    public function serials()
    {
        return Serial::withTrashed($this->is_active && $this->is_active === 'corbeille')
                        ->when($this->search, fn($qs) =>
                            $qs->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%')
                        )
                        ->when($this->is_active && $this->is_active === 'actives', fn($qa) =>
                            $qa->where('is_active', true)
                        )
                        ->when($this->is_active && $this->is_active === 'desactives', fn($qa) =>
                            $qa->where('is_active', false)
                        )
                        ->when($this->is_active && $this->is_active === 'corbeille', fn($qa) =>
                            $qa->whereNotNull('deleted_at')
                        )
                        ->orderBy('name')
                        ->paginate($this->perPage);
    }

    public function resetFilters()
    {
        session()->forget('serials_is_active_selected');
        
        $this->reset('is_active', 'search');
    }



    public function render()
    {
        return view('livewire.tenants.serials.serials-portal');
    }
}
