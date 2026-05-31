<?php

namespace App\Livewire\Central;

use App\Events\TenantAccessWasUpdatedEvent;
use App\Jobs\JobToNotifyUserOfNewTenantRequest;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
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

    public ?string $status = null;

    public int $perPage = 12;

    public $showConfirmDeleteModal = false;

    public $showConfirmRestorationModal = false;

    public $showConfirmForceDeleteModal = false;

    public ?string $targetedTenantID = null;



    protected $queryString = [
        'search',
        'adresse',
        'type_enseignement',
        'status',
    ];

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

    // public function notifyUserThatRequestHasReceived(string $domain)
    // {
    //     $req = RequestToCreateNewTenant::firstWhere('domain_name', $domain);

    //     if($req){

    //         JobToNotifyUserOfNewTenantRequest::dispatch($req);

    //         $this->notification()->send([
    //             'icon'        => 'success',
    //             'title'       => 'Accusé de reception envoyé',
    //             'description' => "Les détails de la demande ont été envoyés à " . $req->getUserNamePrefix(true, false),
    //         ]);
    //     }
    //     else{

    //         $this->notification()->send([
    //             'icon'        => 'error',
    //             'title'       => 'Requête introuvable',
    //             'description' => "La reqûete n'existe pas dnas la base de données",
    //         ]);
            
    //     }
    // }



    public function deleteTenant(string $tenantId): void
    {
        $this->showConfirmDeleteModal = true;

        $this->targetedTenantID = $tenantId;

    }

    public function ConfirmSchoolDeletion(): void
    {
        $tenant = Tenant::find($this->targetedTenantID);

        if($tenant){

            $del = $tenant->delete();

            if($del){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Suppression terminée',
                    'description' => "Le tenant a été supprimé avec succès!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la suppression',
                    'description' => "Le tenant n'a pas été supprimé!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'tenant introuvable',
                'description' => "Le tenant n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }

    public function restoreTenant(string $tenantId): void
    {
        $this->showConfirmRestorationModal = true;

        $this->targetedTenantID = $tenantId;

    }

    public function ConfirmSchoolRestoration(): void
    {
        $tenant = Tenant::withTrashed()->whereId($this->targetedTenantID)->first();

        if($tenant){

            $restored = $tenant->restore();

            if($restored){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Restauration terminée',
                    'description' => "Le tenant a été restauré avec succès!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la restauration',
                    'description' => "Le tenant n'a pas été restauré!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'tenant introuvable',
                'description' => "Le tenant n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }

    public function forceDelete(string $tenantId): void
    {
        $this->showConfirmForceDeleteModal = true;

        $this->targetedTenantID = $tenantId;

    }

    public function ConfirmSchoolForceDelete(): void
    {
        $tenant = Tenant::withTrashed()->whereId($this->targetedTenantID)->first();

        if($tenant){

            $force_del = $tenant->forceDelete();

            if($force_del){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Suppresion terminée',
                    'description' => "Le tenant a été définitivement supprimé avec succès!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la suppresion définitive',
                    'description' => "Le tenant n'a pas été supprimé!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'tenant introuvable',
                'description' => "Le tenant n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }

    public function closeModal()
    {
        $this->showConfirmDeleteModal = false;

        $this->showConfirmRestorationModal = false;

        $this->showConfirmForceDeleteModal = false;

    }




    public function render()
    {
        $tenants = Tenant::query()
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
            ->when($this->adresse, function (Builder $query) {
                $query->where('adresse', $this->adresse);
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

        return view('livewire.central.schools-component', compact('tenants'));
    }
}
