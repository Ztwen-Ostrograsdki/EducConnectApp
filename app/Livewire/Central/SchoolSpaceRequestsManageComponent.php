<?php

namespace App\Livewire\Central;

use App\Events\InitProcessToCreateTenantSpaceEvent;
use App\Jobs\JobToSendCredentialsToCreatedTenant;
use App\Models\RequestToCreateNewTenant;
use App\Models\Tenant;
use App\Models\User;
use App\Tools\BeninData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
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


    public function mailBuilder(string $tenant_id)
    {
        $tenant = Tenant::find($tenant_id);

        if($tenant){

            tenancy()->initialize($tenant);

            $user = User::first();

            if($user){

                $default_password = Str::random(8);

                $user->update(['password'  => Hash::make($default_password)]);

                JobToSendCredentialsToCreatedTenant::dispatch($tenant, $user, $default_password)->delay(0);

            }

            tenancy()->end();

            
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

    public function unblockAll(): void
    {
         $this->notification()->success(
            'Succès',
            'Domaine du tenant bloqué!'
        );

    }

    public function delete(string $requestId): void
    {
         $this->notification()->success(
            'Succès',
            'Domaine du tenant bloqué!'
        );
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
