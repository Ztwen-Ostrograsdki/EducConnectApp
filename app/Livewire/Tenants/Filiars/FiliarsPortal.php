<?php

namespace App\Livewire\Tenants\Filiars;

use App\Livewire\Tenants\ActionsTraits\FiliarsActions;
use App\Models\Filiar;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des filières")]
class FiliarsPortal extends Component
{
    use WithPagination, FiliarsActions;


    public $is_active = "actives";

    public ?string $search = null;

    public int $perPage = 3;

    #[Computed]
    public function kpis()
    {
        $tenant = tenancy()->tenant;

        $data = [
            ['Promotions', __zero($tenant->promotionsCount()), 'text-amber-400'], 
            ['Meilleure classe', '-', 'text-emerald-400'], 
            ['Faible classe', '-', 'text-rose-400'], 
            ['Meilleur élève', '-', 'text-sky-400'], 
            ['Meilleur moyenne', '-', 'text-sky-400'],  
        ];

        return $data;
    }

    public function mount()
    {
        if(session()->has('filiars_is_active_selected')){

            $this->is_active = session('filiars_is_active_selected');
        }
    }


    public function updatedIsActive(string $value)
    {
        session()->put('filiars_is_active_selected', $this->is_active);
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
