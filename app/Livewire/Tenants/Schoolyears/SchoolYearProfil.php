<?php

namespace App\Livewire\Tenants\Schoolyears;

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


    public int $counter = 0;

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


    

    public function render()
    {
        return view('livewire.tenants.schoolyears.school-year-profil');
    }
}
