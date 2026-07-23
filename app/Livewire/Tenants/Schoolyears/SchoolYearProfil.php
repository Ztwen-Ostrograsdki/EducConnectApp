<?php

namespace App\Livewire\Tenants\Schoolyears;

use App\Events\DataUpdatedEvent;
use App\Events\NewSchoolYearActivatedEvent;
use App\Events\SchoolYearUpdatedEvent;
use App\Livewire\Tenants\ActionsTraits\SchoolYearsActions;
use App\Models\SchoolYear;
use App\Services\DashboardCounterService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title('Profil année scolaire')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class SchoolYearProfil extends Component
{
    use WireUiActions, SchoolYearsActions;

    public ?string $school_year_slug;

    public ?string $school_year_uuid;

    public ?SchoolYear $school_year_model;

    public ?int $active_period = null;

    public bool $editing = false;


    public int $counter = 0;


    #[Computed]
    public function periods()
    {
        return $this->school_year_model->getPeriods();
    }

    #[On("NewSchoolYearCreatedLiveEvent")]
    public function newSchoolYearCreated()
    {
        $this->counter++;
    }

    #[On("SchoolYearUpdatedLiveEvent")]
    public function schoolYearUpdated()
    {
        $this->counter++;
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloadData(): void
    {
        $this->counter++;
    }


    public function mount(string $school_year)
    {
        if(!$school_year) return abort(404);

        $schoolYear = SchoolYear::withTrashed()->whereSlug($school_year)->first();

        if(!$schoolYear) return abort(404);

        $this->school_year_model = $schoolYear;

        $this->school_year_slug = $schoolYear->slug;

        $this->school_year_uuid = $schoolYear->uuid;

        $this->active_period = $schoolYear->active_period;
        
    }


    
    #[Computed]
    public function stats(): array
    {
        return app(DashboardCounterService::class)->getMany([
            'students',
            'students_in_classe',
            'teachers_in_classes',
            'teachers',
            'classes_actives',
            'classes_closeds',
            'classes_unactives',
            'promotions_actives',
            'serials_actives',
            'filiars_actives',
        ]);
    }


    public function toggleEdition()
    {
        $this->editing = !$this->editing;

    }

    public function saveActivePediod()
    {
        $this->school_year_model->update(['active_period' => $this->active_period]);

        if($this->active_period) $message = "La période active de l'année scolaire {$this->school_year_model->slug} est désormais " . $this->school_year_model->periodLabel() . ' ' .$this->active_period;

        else $message = "L'année scolaire {$this->school_year_model->slug} n'a désormais aucun " . str()->lower($this->school_year_model->periodLabel()) . " actif ";

        $this->notification()->success(
                title: "Année scolaire {$this->school_year_model->slug} mise à jour",
                description: $message
            );

        $this->editing = false;

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function render()
    {
        return view('livewire.tenants.schoolyears.school-year-profil');
    }
}
