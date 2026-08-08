<?php

namespace App\Livewire\Tenants;

use App\Models\Classe;
use App\Models\Filiar;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\DashboardCounterService;
use App\Services\PromotionGroupsCountService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('TABLEAU DE BORD')]
class TenantDashboard extends Component
{
    use WireUiActions, WithPagination;

    public ?string $tenant_dashboard_selected_school_year;

    public $counter = 0;

    public function mount()
    {

    }

    #[Computed]
    public function activeYear(): ?SchoolYear { return SchoolYear::current()->first(); }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->tenant_dashboard_selected_school_year = $schoolYear;
    }


    #[On("NewSchoolYearCreatedLiveEvent")]
    public function schoolYearUpdated()
    {
        $this->counter++;
    }

    #[Computed]
    public function stats(): array
    {
        return app(DashboardCounterService::class)->getMany([
            'students',
            'tutors',
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


    #[Computed]
    public function promotionGroupsCounts(): array
    {
        return app(PromotionGroupsCountService::class)->get();
    }


    #[On("SchoolYearUpdatedLiveEvent")]
    public function newSchoolYearCreated()
    {
        $this->counter++;
    }

    #[Computed]
    public function studentsLeaves()
    {
        $query = Student::query()
            ->select('students.*')
            ->with(['classe'])
            ->whereHas('yearlyClasseStudents', fn($q) => 
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
            )
            ->whereHas('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $this->activeYear->id)
            );
        return $query
            ->orderBy('students.name')
            ->orderBy('students.prenames')->get();
    }

    #[Computed]
    public function principals()
    {
        $query = Classe::query()
            ->select('classes.*')
            ->with(['principal'])
            ->where('school_year_id', $this->activeYear->id)
            ->whereHas('principal')
           ->where('is_active', true);
        return $query
            ->orderBy('name')->limit(10)->get();
    }


    #[Computed]
    public function cas()
    {
        $query = Filiar::query()
            ->select('filiars.*')
            ->with(['currentChiefs'])
           ->where('is_active', true);
        return $query
            ->orderBy('name')->paginate(5);
    }
    

    public function render()
    {
        return view('livewire.tenants.tenant-dashboard');
    }
}
