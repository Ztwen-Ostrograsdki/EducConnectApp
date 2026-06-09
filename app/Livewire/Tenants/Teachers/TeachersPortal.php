<?php

namespace App\Livewire\Tenants\Teachers;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
class TeachersPortal extends Component
{
    use WithPagination, WireUiActions;
    
    public $counter = 3;
    
    public string $search = '';

    public string $city = '';

    public string $gender = '';

    public string $department = '';

    public ?string $status = null;

    public int $perPage = 12;

    public $showConfirmDeleteModal = false;

    public $showConfirmRestorationModal = false;

    public $showConfirmForceDeleteModal = false;

    public ?string $targetedTeacherID = null;



    public function mount(?string $status = null)
    {
        if($status) $this->status = $status;
    }

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

        // broadcast(new TenantAccessWasUpdatedEvent($tenantId));
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

        // broadcast(new TenantAccessWasUpdatedEvent($tenantId));
    }

    public function clearFilters()
    {
        $this->reset('search', 'gender', 'city', 'gender', 'department');
    }



    public function render()
    {
        $allTeachersCounter = Teacher::all()->count();

        $activesTeachersCounter = Teacher::whereStatus('active')->count();

        $teachers = Teacher::query()
        ->select('teachers.*')
        ->join('users', 'users.id', '=', 'teachers.user_id')
        ->with(['user'])
        ->withTrashed()
        ->when($this->search, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('email', 'like', "%{$this->search}%");
                $query->orwhere('name', 'like', "%{$this->search}%");
                $query->orwhere('prenames', 'like', "%{$this->search}%");
                $query->orwhere('contacts', 'like', "%{$this->search}%");
                $query->orwhere('adresse', 'like', "%{$this->search}%");
                $query->orwhere('city', 'like', "%{$this->search}%");
                $query->orwhere('department', 'like', "%{$this->search}%");
                $query->orwhere('country', 'like', "%{$this->search}%");
                $query->orwhere('gender', 'like', "%{$this->search}%");
                $query->orwhere('birth_date', 'like', "%{$this->search}%");
                $query->orwhere('birth_place', 'like', "%{$this->search}%");
                $query->orwhere('identifiant', 'like', "%{$this->search}%");
                $query->orwhere('job_name', 'like', "%{$this->search}%");
                $query->orwhere('status', 'like', "%{$this->search}%");
            });
        })
        ->when($this->city, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('city', $this->city);
            });
        })
        ->when($this->department, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('department', $this->department);
            });
        })
        ->when($this->gender, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('gender', $this->gender);
            });
        })
        ->orderBy('users.name')
        ->orderBy('users.prenames')
        ->paginate($this->perPage);

        return view('livewire.tenants.teachers.teachers-portal', compact('teachers', 'allTeachersCounter', 'activesTeachersCounter'));
    }


}
