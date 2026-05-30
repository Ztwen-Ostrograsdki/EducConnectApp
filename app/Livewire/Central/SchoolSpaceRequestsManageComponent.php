<?php

namespace App\Livewire\Central;

use App\Events\InitProcessToCreateTenantSpaceEvent;
use App\Jobs\JobToNotifyUserOfNewTenantRequest;
use App\Models\RequestToCreateNewTenant;
use App\Tools\BeninData;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Gestion des demandes d'espace école")]
class SchoolSpaceRequestsManageComponent extends Component
{
    
    use WithPagination, WireUiActions;
    
    public $counter = 3;
    
    public string $search = '';

    public string $adresse = '';

    public string $etablissement_type = '';

    public string $enseignement_type = '';

    public string $school_devise = '';

    public string $school_name = '';

    public string $school_type = '';

    public string $name = '';

    public string $prenames = '';

    public string $status = '';

    public int $perPage = 6;


    public $showModal = false;

    public ?string $targetRequest;

    protected $queryString = [
        'search',
        'adresse',
        'school_devise',
        'school_name',
        'school_type',
        'enseignement_type',
        'periode_type',
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

    public function suspend(string $requestId): void
    {
        $tenant = getTenant($requestId);

        $tenant->update([
            'domain_blocked' => true,
        ]);

    }

    public function unsuspend(string $requestId): void
    {

        $this->notification()->success(
            'Succès',
            'Domaine du tenant bloqué!'
        );

    }


    public function notifyUserThatRequestHasReceived(string $domain)
    {
        $req = RequestToCreateNewTenant::firstWhere('domain_name', $domain);

        if($req){

            JobToNotifyUserOfNewTenantRequest::dispatch($req);
        }
        else{

            $this->notification()->send([
                'icon'        => 'negative',
                'title'       => 'Requête introuvable',
                'description' => "La reqûete n'existe pas dnas la base de données",
            ]);
            
        }
    }


    public function validateRequest(string $requestId): void
    {
        $req = RequestToCreateNewTenant::where('id', $requestId)->firstOrfail();

        if($req){

            broadcast(new InitProcessToCreateTenantSpaceEvent($req));

            $this->notification()->success(
                'Validation lancée avec succès!',
                "Le processus de creation d'espace a été lancée avec succès. Une fois terminée vous aurez le rapport!"
            );

        }

    }


    public function deleteRequest(string $requestId): void
    {
        $this->showModal = true;

        $this->targetRequest = $requestId;

    }

    public function ConfirmRequestDeletion(): void
    {
        $req = RequestToCreateNewTenant::find($this->targetRequest);

        if($req){

            $del = $req->delete();

            if($del){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Suppression terminée',
                    'description' => "La reqûete a été supprimée avec succès!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la suppression',
                    'description' => "La reqûete n'a pas été supprimer!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Requête introuvable',
                'description' => "La reqûete n'existe pas dnas la base de données",
            ]);
            
        }
        $this->showModal = false;
        
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
    
    
    public function rejectRequest(string $requestId): void
    {
        $this->showModal = true;

        $this->targetRequest = $requestId;
    }

    public function ConfirmRequestReject(): void
    {
        $req = RequestToCreateNewTenant::find($this->targetRequest);

        if($req){

            $del = $req->update(['status' => 'rejected']);

            if($del){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Revocation terminée',
                    'description' => "La reqûete a été révoquée avec succès!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la Revocation',
                    'description' => "La reqûete n'a pas été Revoquée!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Requête introuvable',
                'description' => "La reqûete n'existe pas dnas la base de données",
            ]);
            
        }
        $this->showModal = false;
    }

    #[On('LiveNewTenantRequestCreatedEvent')]
    public function onLiveNewTenantRequestCreatedEvent($email)
    {
        $this->counter = randomNumber();
    }

    public function render()
    {
        $enseignement_types = BeninData::getSytems();

        $periode_types = config('app.periode_types');

        $school_types = config('app.school_types');

        $devoirs_types = config('app.devoirs_types');

        $tenant_request_statuses = config('app.tenant_request_statuses');

        $departments = BeninData::getDepartments();

        $countries = ['Bénin' => 'Bénin'];

        
        $demandes_requests = RequestToCreateNewTenant::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function ($query) {
                    $query
                        ->where('name', 'like', "%{$this->search}%")
                        ->orWhere('prenames', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('school_name', 'like', "%{$this->search}%")
                        ->orWhere('school_devise', 'like', "%{$this->search}%")
                        ->orWhere('adresse', 'like', "%{$this->search}%")
                        ->orWhere('school_type', 'like', "%{$this->search}%")
                        ->orWhere('enseignement_type', 'like', "%{$this->search}%");
                });
            })
            ->when($this->adresse, function (Builder $query) {
                $query->where('adresse', $this->adresse);
            })
            ->when($this->enseignement_type, function (Builder $query) {
                $query->where('school_type', $this->enseignement_type);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.central.school-space-requests-manage-component', compact('demandes_requests', 'enseignement_types', 'periode_types', 'school_types', 'devoirs_types', 'departments', 'countries', 'tenant_request_statuses'));
    }

    public function resetFilters()
    {
        $this->reset();
    }

}
