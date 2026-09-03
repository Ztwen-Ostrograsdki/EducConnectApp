<?php

namespace App\Livewire\Central;

use App\Events\TenantAccessWasUpdatedEvent;
use App\Livewire\Central\Actions\ActionsTraits;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Les écoles actives")]
class SchoolsComponent extends Component
{
    use WithPagination, WireUiActions, ActionsTraits;
    
    public $counter = 3;
    
    public string $search = '';

    public string $type_etablissement = '';

    public string $type_enseignement = '';

    public ?string $status = null;

    public int $perPage = 12;

    public ?string $targetedTenantID = null;



    public function mount(?string $status = null)
    {
        if($status) $this->status = $status;
    }

    #[On('LiveReloadDashboardEvent')]
    public function onReloadDashboard()
    {
        $this->counter = randomNumber();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }


    #[Computed]
    public function tenants()
    {
        return Tenant::query()
            ->withTrashed()
            ->when($this->search, function (Builder $query) {
                $query->where(function ($query) {
                    $query
                        ->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('adresse', 'like', "%{$this->search}%")
                        ->orWhere('type_enseignement', 'like', "%{$this->search}%");
                });
            })
            ->when($this->type_enseignement, function (Builder $query) {
                $query->where('school_type', $this->type_enseignement);
            })
            ->when($this->status, function (Builder $query) {
                if ($this->status === 'fermee') {
                    $query->where('domain_blocked', true);
                }
                if ($this->status === 'ouverte') {
                    $query->where('domain_blocked', false);
                }
                if ($this->status === 'ouverte-pour-directeurs') {
                    $query->where('only_for_', true);
                }
                if ($this->status === 'active') {
                    $query->withoutTrashed();
                }
                if ($this->status === 'corbeille') {
                    $query->onlyTrashed();
                }
            })
            ->latest()
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.central.schools-component');
    }
}
