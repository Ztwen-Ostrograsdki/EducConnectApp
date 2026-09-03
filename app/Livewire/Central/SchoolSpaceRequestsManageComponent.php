<?php

namespace App\Livewire\Central;

use App\Livewire\Central\Actions\ActionsTraits;
use App\Models\RequestToCreateNewTenant;
use App\Tools\BeninData;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Gestion des demandes d'espace école")]
class SchoolSpaceRequestsManageComponent extends Component
{
    
    use WithPagination, ActionsTraits;
    
    public $counter = 3;
    
    public string $search = '';

    public string $etablissement_type = '';

    public string $enseignement_type = '';

    public string $school_type = '';

    public ?string $status = null;

    public int $perPage = 6;


    public ?string $targetRequest;

    public function mount(?string $status = 'tout')
    {
        if($status){

            $this->status = $status;

            if($status == 'tout'){
                $this->status = null;
            }

        }
        else{

            $this->status = null;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }


    #[On('LiveNewTenantRequestCreatedEvent')]
    public function onLiveNewTenantRequestCreatedEvent($email)
    {
        $this->counter = randomNumber();
    }
    
    #[Computed]
    public function enseignement_types()
    {
       return BeninData::getSytems();
    }
    
    #[Computed]
    public function demandes_requests()
    {
       return RequestToCreateNewTenant::query()
            ->when($this->status, function (Builder $query) {
                $query->where('status', $this->status);
            })
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
            ->when($this->enseignement_type, function (Builder $query) {
                $query->where('school_type', $this->enseignement_type);
            })
            ->latest()
            ->paginate($this->perPage);
    }

    public function render()
    {
        $school_types = config('app.school_types');

        $tenant_request_statuses = config('app.tenant_request_statuses');

        return view('livewire.central.school-space-requests-manage-component', compact('school_types', 'tenant_request_statuses'));
    }

    public function clearFilters()
    {
        $this->reset();
    }

}
