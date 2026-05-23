<?php

namespace App\Livewire\Central;

use App\Events\TenantAccessWasUpdatedEvent;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
class SchoolsComponent extends Component
{
    use WithPagination, WireUiActions;
    
    public $counter = 3;
    
    public string $search = '';

    public string $adresse = '';

    public string $type_etablissement = '';

    public string $type_enseignement = '';

    public string $status = '';

    public int $perPage = 12;

    protected $queryString = [
        'search',
        'adresse',
        'type_enseignement',
        'status',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function suspend(string $tenantId): void
    {
        $tenant = getTenant($tenantId);

        $tenant->update([
            'domain_blocked' => true,
        ]);

        session()->flash('success', 'Domaine ténant suspendu avec succès.');
    }

    public function unsuspend(string $tenantId): void
    {
        $tenant = getTenant($tenantId);

        $tenant->update([
            'domain_blocked' => false,
        ]);

        session()->flash('success', 'Domaine Tenant réactivé avec succès.');
    }

    public function blockDomain(string $tenantId): void
    {
        $tenant = getTenant($tenantId);

        $tenant->update([
            'domain_blocked' => true,
        ]);

        $this->notification()->success(
            'Succès',
            'Domaine du tenant bloqué!'
        );

        broadcast(new TenantAccessWasUpdatedEvent($tenantId));
    }

    public function unblockDomain(string $tenantId): void
    {
        $tenant = getTenant($tenantId);

        $tenant->update([
            'domain_blocked' => false,
        ]);

        $this->notification()->success(
            'Succès',
            'Domaine du tenant débloqué!'
        );

        broadcast(new TenantAccessWasUpdatedEvent($tenantId));
    }

    public function blockAll(): void
    {
        Tenant::query()->update([
            'domain_blocked' => true,
        ]);

        session()->flash('success', 'Tous les tenants ont été bloqués.');
    }

    public function unblockAll(): void
    {
        Tenant::query()->update([
            'domain_blocked' => false,
        ]);

        session()->flash('success', 'Tous les tenants ont été débloqués.');
    }

    public function delete(string $tenantId): void
    {
        Tenant::findOrFail($tenantId)->delete();

        session()->flash('success', 'Tenant supprimé avec succès.');
    }




    public function render()
    {
        $tenants = Tenant::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function ($query) {
                    $query
                        ->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('adresse', 'like', "%{$this->search}%")
                        ->orWhere('type_enseignement', 'like', "%{$this->search}%");
                });
            })
            ->when($this->adresse, function (Builder $query) {
                $query->where('adresse', $this->adresse);
            })
            ->when($this->type_enseignement, function (Builder $query) {
                $query->where('school_type', $this->type_enseignement);
            })
            ->when($this->status, function (Builder $query) {
                if ($this->status === 'blocked') {
                    $query->where('domain_blocked', true);
                }

                if ($this->status === 'active') {
                    $query->where('domain_blocked', false);
                }
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.central.schools-component', compact('tenants'));
    }
}
