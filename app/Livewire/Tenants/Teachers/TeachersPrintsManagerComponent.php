<?php

namespace App\Livewire\Tenants\Teachers;

use App\Jobs\JobToGeneratePrintableTeachersDataForThePrintViewComponent;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Services\TeachersServices\TeacherPrintColumns;
use App\Services\TeachersServices\TeacherPrintQuery;
use App\Services\TeachersServices\TeacherPrintSessionConfig;
use App\Tools\BeninData;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Page de gestion des impressions de la liste des enseignants")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class TeachersPrintsManagerComponent extends Component
{
    use WireUiActions;

    public ?string $classe_slug = null;
    public ?string $filiar_slug = null;
    public ?string $serial_slug = null;
    public ?string $promotion_slug = null;

    public ?string $trashedStatus = 'withoutTrashed';

    public ?string $accessStatus = null;

    public ?string $ppStatus = null;

    public ?string $aeStatus = null;

    public ?string $hasClassesStatus = null;

    public ?string $city = null;

    public ?string $gender = null;

    public ?string $department = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public ?int $subject_id = null;

    public ?int $promotion_id = null;

    public ?string $promotionInGroups = null;

    public ?int $classe_id = null;

    public int $counter = 0;

    public array $trashedStatuses = [
        'onlyTrashed'     => "Uniquement les enseignants de la corbeille",
        'withoutTrashed'  => "Tous les enseignants qui ne sont pas dans la corbeille",
        'withTrashed'     => "Tous les enseignants y compris ceux de la corbeille",
    ];

    public array $accessStatuses = [
        ''                  => "Tous les enseignants avec ou sans accès",
        'onlyHasAccess'     => "Seulement enseignants ayant un accès",
        'onlyHasntAccess'   => "Seulement enseignants sans accès",
    ];

    public array $ppStatuses = [
        ''          => "Tous les enseignants PP ou non",
        'onlyPP'    => "Seulement enseignants PP",
        'withoutPP' => "Seulement enseignants qui ne sont pas PP",
    ];

    public array $aeStatuses = [
        ''          => "Tous les enseignants AE ou non",
        'onlyAE'    => "Seulement enseignants AE",
        'withoutAE' => "Seulement enseignants qui ne sont pas AE",
    ];

    public array $hasClassesStatuses = [
        ''                  => "Tous les enseignants ayant ou pas de classes",
        'onlyHasClasses'    => "Seulement enseignants ayant des classes",
        'onlyHasntClasses'  => "Seulement enseignants n'ayant pas de classes",
    ];

    protected string $sessionKey = 'teacher-list-selected-columns';

    public array $columns = [];

    public array $selectedColumns = [];

    public function mount(?string $classe_slug = null)
    {
        if($classe_slug){

            $classe = Classe::firstWhere('slug', $classe_slug);

            if(!$classe) return abort(404);

            $this->classe_id = $classe->id;

            $this->classe_slug = $classe_slug;

            $this->updatedClasseId($classe->id);
        }

        $this->columns = TeacherPrintColumns::$columns;

        $this->selectedColumns = session()->get($this->sessionKey, []);

        $this->selectedColumns = array_values(array_filter(
            $this->selectedColumns,
            fn (string $key) => array_key_exists($key, $this->columns)
        ));

        if (session()->has('print_teachers_trashed_status')) {
            $this->trashedStatus = session('print_teachers_trashed_status');
        }
        if (session()->has('print_teachers_access_status')) {
            $this->accessStatus = session('print_teachers_access_status');
        }
        if (session()->has('print_teachers_pp_status')) {
            $this->ppStatus = session('print_teachers_pp_status');
        }
        if (session()->has('print_teachers_ae_status')) {
            $this->aeStatus = session('print_teachers_ae_status');
        }
        if (session()->has('print_teachers_has_classes_status')) {
            $this->hasClassesStatus = session('print_teachers_has_classes_status');
        }
        if (session()->has('print_teachers_classe_selected')) {
            $this->classe_id = session('print_teachers_classe_selected');
        }
        if (session()->has('print_teachers_filiar_selected')) {
            $this->filiar_id = session('print_teachers_filiar_selected');
        }
        if (session()->has('print_teachers_subject_selected')) {
            $this->subject_id = session('print_teachers_subject_selected');
        }
        if (session()->has('print_teachers_serial_selected')) {
            $this->serial_id = session('print_teachers_serial_selected');
        }
        if (session()->has('print_teachers_promotion_selected')) {
            $this->promotion_id = session('print_teachers_promotion_selected');
        }
        if (session()->has('print_teachers_promotions_grouped_selected')) {
            $this->promotionInGroups = session('print_teachers_promotions_grouped_selected');
        }
        if (session()->has('print_teachers_gender_selected')) {
            $this->gender = session('print_teachers_gender_selected');
        }
        if (session()->has('print_teachers_city_selected')) {
            $this->city = session('print_teachers_city_selected');
        }
        if (session()->has('print_teachers_department_selected')) {
            $this->department = session('print_teachers_department_selected');
        }

    }

    #[Computed]
    public function currentDocTitle(): string
    {
        return TeacherPrintQuery::resolveDocTitle(
            $this->currentFilterConfig(),
        );
    }


    #[Computed]
    public function defaultOrderedColumns(): array
    {
        return collect(TeacherPrintColumns::$defaultOrder)
            ->mapWithKeys(fn (string $key) => [$key => $this->columns[$key]['label'] ?? $key])
            ->toArray();
    }

    
    public function restoreSelects(): void
    {
        $this->selectedColumns = [];
        $this->persistSelection();
    }

    protected function persistSelection(): void
    {
        session()->put($this->sessionKey, $this->selectedColumns);
    }

    public function toggleColumn(string $key): void
    {
        $index = array_search($key, $this->selectedColumns, true);

        if ($index !== false) {
            unset($this->selectedColumns[$index]);
            $this->selectedColumns = array_values($this->selectedColumns);
        } else {
            $this->selectedColumns[] = $key;
        }

        $this->persistSelection();
    }

    #[Computed]
    public function orderedColumns(): array
    {
        return collect($this->selectedColumns)
            ->mapWithKeys(fn (string $key) => [$key => $this->columns[$key]['label'] ?? $key])
            ->toArray();
    }

    protected function buildTableColumns(): array
    {
        return TeacherPrintColumns::build($this->selectedColumns);
    }

    public function resetFilters(): void
    {
        session()->forget([
            'print_teachers_city_selected',
            'print_teachers_department_selected',
            'print_teachers_gender_selected',
            'print_teachers_promotion_selected',
            'print_teachers_promotions_grouped_selected',
            'print_teachers_classe_selected',
            'print_teachers_filiar_selected',
            'print_teachers_serial_selected',
            'print_teachers_subject_selected',
            'print_teachers_access_status',
            'print_teachers_pp_status',
            'print_teachers_ae_status',
            'print_teachers_has_classes_status',
            'print_teachers_trashed_status',
        ]);

        $this->reset(
            'city', 'gender', 'department', 'classe_id', 'promotion_id',
            'promotionInGroups', 'filiar_id', 'serial_id', 'subject_id',
            'accessStatus', 'ppStatus', 'aeStatus', 'hasClassesStatus', 'trashedStatus'
        );

        $this->trashedStatus = 'withoutTrashed';
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function subjects()
    {
        return Subject::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return Classe::where('classes.school_year_id', $this->activeYear->id)
            ->where('classes.is_active', true)
            ->where('classes.is_locked', false)
            ->orderBy('name', 'desc')
            ->get();
    }

    #[Computed]
    public function genders(): ?array
    {
        return config('app.genders');
    }

    #[Computed]
    public function departments(): ?array
    {
        return BeninData::getDepartments();
    }

    #[Computed]
    public function cities(): ?array
    {
        return array_values(array_unique(array_merge(...BeninData::getCities())));
    }

    #[Computed]
    public function promotions()
    {
        return Promotion::where('is_active', true)->orderBy('name', 'desc')->get();
    }

    #[Computed]
    public function promotionsGrouped()
    {
        return array_unique(Promotion::where('is_active', true)->orderBy('name', 'asc')->pluck('name')->toArray());
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    protected function currentFilterConfig(): array
    {
        return [
            "trashedConfig"     => $this->trashedStatus,
            "accessesConfig"    => $this->accessStatus,
            "ppConfig"          => $this->ppStatus,
            "aeConfig"          => $this->aeStatus,
            "hasClassesConfig"  => $this->hasClassesStatus,
            "classe_id"         => $this->classe_id,
            "filiar_id"         => $this->filiar_id,
            "subject_id"        => $this->subject_id,
            "serial_id"         => $this->serial_id,
            "promotion_id"      => $this->promotion_id,
            "promotionInGroups" => $this->promotionInGroups,
            "gender"            => $this->gender,
            "city"              => $this->city,
            "department"        => $this->department,
        ];
    }

    #[Computed]
    public function allTeachersCounter()
    {
        return TeacherPrintQuery::count($this->currentFilterConfig(), $this->activeYear->id);
    }

    public function updatedDepartment(?string $value): void { session()->put('print_teachers_department_selected', $value); }
    public function updatedCity(?string $value): void { session()->put('print_teachers_city_selected', $value); }
    public function updatedGender(?string $value): void { session()->put('print_teachers_gender_selected', $value); }
    public function updatedTrashedStatus(?string $value): void { session()->put('print_teachers_trashed_status', $value); }
    public function updatedAccessStatus(?string $value): void { session()->put('print_teachers_access_status', $value); }
    public function updatedPpStatus(?string $value): void { session()->put('print_teachers_pp_status', $value); }
    public function updatedAeStatus(?string $value): void { session()->put('print_teachers_ae_status', $value); }
    public function updatedHasClassesStatus(?string $value): void { session()->put('print_teachers_has_classes_status', $value); }
    public function updatedSubjectId(?string $value): void { session()->put('print_teachers_subject_selected', $value); }

    public function updatedClasseId(?string $value): void
    {
        if ($value) {
            $this->reset(['serial_id', 'filiar_id', 'promotion_id', 'promotionInGroups']);
            session()->forget([
                'print_teachers_serial_selected',
                'print_teachers_filiar_selected',
                'print_teachers_promotions_grouped_selected',
                'print_teachers_promotion_selected',
            ]);
        }
        session()->put('print_teachers_classe_selected', $value);
    }

    public function updatedPromotionId(?string $value): void
    {
        if ($value) {
            $this->reset(['filiar_id', 'serial_id', 'classe_id', 'promotionInGroups']);
            session()->forget([
                'print_teachers_promotions_grouped_selected',
                'print_teachers_classe_selected',
                'print_teachers_filiar_selected',
                'print_teachers_serial_selected',
            ]);
        }
        session()->put('print_teachers_promotion_selected', $value);
    }

    public function updatedPromotionInGroups(?string $value): void
    {
        if ($value) {
            $this->reset(['classe_id', 'promotion_id']);
            session()->forget([
                'print_teachers_classe_selected',
                'print_teachers_promotion_selected',
            ]);
        }
        session()->put('print_teachers_promotions_grouped_selected', $value);
    }

    public function updatedFiliarId(?string $value): void
    {
        if ($value) {
            $this->reset(['serial_id', 'promotion_id', 'classe_id']);
            session()->forget([
                'print_teachers_classe_selected',
                'print_teachers_serial_selected',
                'print_teachers_promotion_selected',
            ]);
        }
        session()->put('print_teachers_filiar_selected', $value);
    }

    public function updatedSerialId(?string $value): void
    {
        if ($value) {
            $this->reset(['filiar_id', 'promotion_id', 'classe_id']);
            session()->forget([
                'print_teachers_classe_selected',
                'print_teachers_filiar_selected',
                'print_teachers_promotion_selected',
            ]);
        }
        session()->put('print_teachers_serial_selected', $value);
    }

    public function initPrintProcess()
    {
        if (! $this->allTeachersCounter) {
            $this->notification()->info(
                title: "La procédure ne peut être lancée : aucun enregistrement trouvé",
                description: "Pour les conditions que vous avez définies, aucun enregistrement n'a été trouvé dans la base de données!",
            );
            return;
        }

        JobToGeneratePrintableTeachersDataForThePrintViewComponent::dispatch(
            tenantId:       tenant('id'),
            notifiableId:   auth('tenant')->user()->id,
            docTitle:       $this->currentDocTitle,
            school_year_id: $this->activeYear->id,
            config: [
                ...$this->currentFilterConfig(),
                'tableColumns' => $this->buildTableColumns(),
            ],
        );

        $this->notification()->success(title: 'Génération du document lancée');
    }

    public function render()
    {
        return view('livewire.tenants.teachers.teachers-prints-manager-component');
    }
}