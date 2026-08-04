<?php

namespace App\Livewire\Tenants\Classes;

use App\Jobs\JobToGeneratePrintableClassesDataForThePrintViewComponent;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Services\ClassesServices\ClassePrintColumns;
use App\Services\ClassesServices\ClassePrintQuery;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Page de gestion des impressions de la liste des classes")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class ClassesPrintsManagerComponent extends Component
{
    use WireUiActions;

    public ?string $activeStatus = null;

    public ?string $lockedStatus = null;

    public ?string $ppStatus = null;

    public ?string $hasStudentsStatus = null;

    public ?string $hasTeachersStatus = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public ?int $promotion_id = null;

    public ?string $promotionInGroups = null;

    public int $counter = 0;

    public array $activeStatuses = [
        ''             => "Toutes les classes actives ou non",
        'onlyActive'   => "Seulement classes actives",
        'onlyInactive' => "Seulement classes non actives",
    ];

    public array $lockedStatuses = [
        ''               => "Toutes les classes verrouillées ou non",
        'onlyLocked'     => "Seulement classes verrouillées",
        'onlyUnlocked'   => "Seulement classes non verrouillées",
    ];

    public array $ppStatuses = [
        ''             => "Toutes les classes, PP défini ou non",
        'onlyHasPP'    => "Seulement classes ayant un PP",
        'onlyHasntPP'  => "Seulement classes sans PP",
    ];

    public array $hasStudentsStatuses = [
        ''                    => "Toutes les classes, apprenants ou non",
        'onlyHasStudents'     => "Seulement classes ayant des apprenants",
        'onlyHasntStudents'   => "Seulement classes sans apprenant",
    ];

    public array $hasTeachersStatuses = [
        ''                    => "Toutes les classes, enseignants ou non",
        'onlyHasTeachers'     => "Seulement classes ayant des enseignants",
        'onlyHasntTeachers'   => "Seulement classes sans enseignant",
    ];

    protected string $sessionKey = 'classe-list-selected-columns';

    public array $columns = [];

    public array $selectedColumns = [];

    public function mount(): void
    {
        $this->columns = ClassePrintColumns::$columns;

        $this->selectedColumns = session()->get($this->sessionKey, []);

        $this->selectedColumns = array_values(array_filter(
            $this->selectedColumns,
            fn (string $key) => array_key_exists($key, $this->columns)
        ));

        if (session()->has('print_classes_active_status')) {
            $this->activeStatus = session('print_classes_active_status');
        }
        if (session()->has('print_classes_locked_status')) {
            $this->lockedStatus = session('print_classes_locked_status');
        }
        if (session()->has('print_classes_pp_status')) {
            $this->ppStatus = session('print_classes_pp_status');
        }
        if (session()->has('print_classes_has_students_status')) {
            $this->hasStudentsStatus = session('print_classes_has_students_status');
        }
        if (session()->has('print_classes_has_teachers_status')) {
            $this->hasTeachersStatus = session('print_classes_has_teachers_status');
        }
        if (session()->has('print_classes_filiar_selected')) {
            $this->filiar_id = session('print_classes_filiar_selected');
        }
        if (session()->has('print_classes_serial_selected')) {
            $this->serial_id = session('print_classes_serial_selected');
        }
        if (session()->has('print_classes_promotion_selected')) {
            $this->promotion_id = session('print_classes_promotion_selected');
        }
        if (session()->has('print_classes_promotions_grouped_selected')) {
            $this->promotionInGroups = session('print_classes_promotions_grouped_selected');
        }
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

    #[Computed]
    public function defaultOrderedColumns(): array
    {
        return collect(ClassePrintColumns::$defaultOrder)
            ->mapWithKeys(fn (string $key) => [$key => $this->columns[$key]['label'] ?? $key])
            ->toArray();
    }

    protected function buildTableColumns(): array
    {
        return ClassePrintColumns::build($this->selectedColumns);
    }

    public function resetFilters(): void
    {
        session()->forget([
            'print_classes_active_status',
            'print_classes_locked_status',
            'print_classes_pp_status',
            'print_classes_has_students_status',
            'print_classes_has_teachers_status',
            'print_classes_filiar_selected',
            'print_classes_serial_selected',
            'print_classes_promotion_selected',
            'print_classes_promotions_grouped_selected',
        ]);

        $this->reset(
            'activeStatus', 'lockedStatus', 'ppStatus', 'hasStudentsStatus',
            'hasTeachersStatus', 'filiar_id', 'serial_id', 'promotion_id',
            'promotionInGroups'
        );
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
            "activeConfig"       => $this->activeStatus,
            "lockedConfig"       => $this->lockedStatus,
            "ppConfig"           => $this->ppStatus,
            "hasStudentsConfig"  => $this->hasStudentsStatus,
            "hasTeachersConfig"  => $this->hasTeachersStatus,
            "filiar_id"          => $this->filiar_id,
            "serial_id"          => $this->serial_id,
            "promotion_id"       => $this->promotion_id,
            "promotionInGroups"  => $this->promotionInGroups,
        ];
    }

    #[Computed]
    public function allClassesCounter()
    {
        return ClassePrintQuery::count($this->currentFilterConfig(), $this->activeYear->id);
    }

    #[Computed]
    public function currentDocTitle(): string
    {
        return ClassePrintQuery::resolveDocTitle(
            $this->currentFilterConfig(),
            $this->activeYear?->id
        );
    }

    public function updatedActiveStatus(?string $value): void { session()->put('print_classes_active_status', $value); }
    public function updatedLockedStatus(?string $value): void { session()->put('print_classes_locked_status', $value); }
    public function updatedPpStatus(?string $value): void { session()->put('print_classes_pp_status', $value); }
    public function updatedHasStudentsStatus(?string $value): void { session()->put('print_classes_has_students_status', $value); }
    public function updatedHasTeachersStatus(?string $value): void { session()->put('print_classes_has_teachers_status', $value); }

    public function updatedPromotionId(?string $value): void
    {
        if ($value) {
            $this->reset(['filiar_id', 'serial_id', 'promotionInGroups']);
            session()->forget([
                'print_classes_promotions_grouped_selected',
                'print_classes_filiar_selected',
                'print_classes_serial_selected',
            ]);
        }
        session()->put('print_classes_promotion_selected', $value);
    }

    public function updatedPromotionInGroups(?string $value): void
    {
        if ($value) {
            $this->reset(['promotion_id']);
            session()->forget(['print_classes_promotion_selected']);
        }
        session()->put('print_classes_promotions_grouped_selected', $value);
    }

    public function updatedFiliarId(?string $value): void
    {
        if ($value) {
            $this->reset(['serial_id', 'promotion_id']);
            session()->forget([
                'print_classes_serial_selected',
                'print_classes_promotion_selected',
            ]);
        }
        session()->put('print_classes_filiar_selected', $value);
    }

    public function updatedSerialId(?string $value): void
    {
        if ($value) {
            $this->reset(['filiar_id', 'promotion_id']);
            session()->forget([
                'print_classes_filiar_selected',
                'print_classes_promotion_selected',
            ]);
        }
        session()->put('print_classes_serial_selected', $value);
    }

    public function initPrintProcess()
    {
        if (! $this->allClassesCounter) {
            $this->notification()->info(
                title: "La procédure ne peut être lancée : aucun enregistrement trouvé",
                description: "Pour les conditions que vous avez définies, aucun enregistrement n'a été trouvé dans la base de données!",
            );
            return;
        }

        JobToGeneratePrintableClassesDataForThePrintViewComponent::dispatch(
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
        return view('livewire.tenants.classes.classes-prints-manager-component');
    }
}