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
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ClasseTeachersList extends Component
{
    use TeachersActions;

    public string $classroom;

    public $counterh = 25;

    public ?Classe $classe;

    public ?SchoolYear $schoolYear;

    public ?string $classe_slug;
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

   

    public function resetFilters()
    {
        return $this->reset('search', 'gender', 'subjectType');
    }

    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-teachers-list');
    }
}
