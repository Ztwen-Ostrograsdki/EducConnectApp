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

    public $counterh = 25;

    public $perPage = 25;

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
        $this->counterh++;
    }

    #[Computed]
    public function teachers()
    {
        
        return Teacher::query()
        ->select('teachers.*')
        ->join('users', 'users.id', '=', 'teachers.user_id')
        ->whereHas('classeSubjects', fn ($q) =>
            $q->where('school_year_id', $this->school_year_id)
              ->where('classe_id', $this->classe->id)
              ->where('is_active', true)
              ->whereNull('ended_at')
        )
        ->when($this->search, function (Builder $query) {
            $query->where(function (Builder $q) {
                $q->whereHas('user', function ($q2) {
                    $q2->where('email', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%")
                        ->orWhere('prenames', 'like', "%{$this->search}%")
                        ->orWhere('contacts', 'like', "%{$this->search}%")
                        ->orWhere('adresse', 'like', "%{$this->search}%")
                        ->orWhere('city', 'like', "%{$this->search}%")
                        ->orWhere('department', 'like', "%{$this->search}%")
                        ->orWhere('country', 'like', "%{$this->search}%")
                        ->orWhere('gender', 'like', "%{$this->search}%")
                        ->orWhere('birth_date', 'like', "%{$this->search}%")
                        ->orWhere('birth_place', 'like', "%{$this->search}%")
                        ->orWhere('job_name', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%");
                })
                ->orWhere('identifiant', 'like', "%{$this->search}%");
            });
        })
        ->when($this->gender, function (Builder $qq) {
            $qq->whereHas('user', function ($qq) {
                $qq->where('gender', $this->gender);
            });
        })
        ->orderBy('users.name')
        ->orderBy('users.prenames')
        ->paginate($this->perPage);


        
    }

    public function resetFilters()
    {
        return $this->reset('search', 'gender', 'subjectType');
    }

    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-teachers-list');
    }
}
