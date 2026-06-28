<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Events\DataUpdatedEvent;
use App\Jobs\JobToSendCredentialsToUser;
use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ClasseTeachersList extends Component
{
    use TeachersActions;

    public string $classroom;

    public ?Classe $classe;
    public ?SchoolYear $schoolYear;

    public ?string $classe_slug;
    public ?string $search = '';
    public ?string $gender = null;
    public ?string $subjectType;
    public ?int $school_year_id;
    public ?string $school_year;

    public function mount()
    {
        $active = SchoolYear::current()->first();

        if ($active) {

            $this->school_year_id = $active->id;

            $this->schoolYear = $active;

            $this->school_year = $active->slug;
        }
    }


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    #[Computed]
    public function getTeachers()
    {
        return Teacher::query()
        ->select('teachers.*')
        ->join('users', 'users.id', '=', 'teachers.user_id')
        ->with(['user', 'classeSubjects'])
        ->whereHas('classeSubjects', fn($q) => 
              $q->where('school_year_id', $this->school_year_id)
              ->where('classe_id', $this->classe->id)
              ->where('is_active', true)
              ->whereNull('ended_at')
        )
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
                $query->orwhere('job_name', 'like', "%{$this->search}%");
                $query->orwhere('status', 'like', "%{$this->search}%");
            })
            ->orwhere('identifiant', 'like', "%{$this->search}%");
        })
        
        ->when($this->gender, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('gender', $this->gender);
            });
        })
        ->orderBy('users.name')
        ->orderBy('users.prenames');
    }

    public function resetFilters()
    {
        return $this->reset('search', 'gender', 'subjectType');
    }

    public function render()
    {
        $teachers = $this->getTeachers()->get();
        return view('livewire.tenants.classes.sections.classe-teachers-list', compact('teachers'));
    }
}
