<?php

namespace App\Livewire\Tenants\Filiars;

use App\Livewire\Tenants\ActionsTraits\FiliarsActions;
use App\Models\Filiar;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des filières")]
class FiliarsPortal extends Component
{
    use WithPagination, FiliarsActions;


    public $is_active = "actives";

    public string $system = "both";

    public ?string $search = null;

    public int $perPage = 15;

    public int $counterh = 0;

    public array $systems = [
        'new_system' => "Les nouveaux métiers",
        'default_system' => "Les filières classiques",
        'both' => "Tous les systèmes",

    ];

    public function mount()
    {
        if(session()->has('filiars_is_active_selected')){

            $this->is_active = session('filiars_is_active_selected');
        }

        if(session()->has('selected_system')){

            $this->system = session('selected_system');
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
        session()->put('filiars_is_active_selected', $this->is_active);
    }
    
    public function updatedSystem(?string $value)
    {
        session()->put('selected_system', $this->system);
    }


    #[Computed]
    public function filiars()
    {
        return Filiar::withTrashed($this->is_active && $this->is_active === 'corbeille')
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
                        ->when($this->system, function ($qs) {
                            match ($this->system) {
                                'new_system' => $qs->whereHas('classes', fn ($q) =>
                                    $q->where('is_new_system', true)
                                )->orWhere('is_new_system', true), 
                                'default_system' => $qs->whereHas('classes', fn ($q) =>
                                    $q->where('is_new_system', false)
                                )->orWhere('is_new_system', false),
                                'both' => $qs->where('is_new_system', true)->orWhere('is_new_system', false),
                                default => $qs->where('is_new_system', true)->orWhere('is_new_system', false),
                            };
                        })
                        ->orderBy('name')
                        ->paginate($this->perPage);
    }

    public function resetFilters()
    {
        session()->forget('filiars_is_active_selected');
        
        $this->reset('is_active', 'search');
    }



    public function render()
    {
        return view('livewire.tenants.filiars.filiars-portal');
    }
}
