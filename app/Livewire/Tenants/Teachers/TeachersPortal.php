<?php

namespace App\Livewire\Tenants\Teachers;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class TeachersPortal extends Component
{
    use TeachersActions;



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


    public function render()
    {
        $allTeachersCounter = Teacher::all()->count();

        $genders = config('app.genders');

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

        return view('livewire.tenants.teachers.teachers-portal', compact('teachers', 'allTeachersCounter', 'activesTeachersCounter', 'genders'));
    }





}
