<?php

namespace App\Livewire\Tenants\Schoolyears;

use App\Events\NewSchoolYearCreated;
use App\Livewire\Traits\ManagesSchoolYearPeriods;
use App\Livewire\Traits\ValidatorTrait;
use App\Models\SchoolYear;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title('Ajout de nouvelle année scolaire')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class CreateSchoolYear extends Component
{
    use WireUiActions, ValidatorTrait, ManagesSchoolYearPeriods;

    public ?string $schoolYear = null;
    public ?string $year_min = null;
    public ?string $year_max = null;

    public string $periode_type = 'Semestre';
    public array $periods = [];

    private const DRAFT_TTL_MINUTES = 120;

    public function mount(): void
    {
        if (! $this->restoreDraft()) {
            $this->initPeriods();
        }
    }

    /*
    |--------------------------------------------------------------------
    | Brouillon de session
    |--------------------------------------------------------------------
    */

    private function draftSessionKey(): string
    {
        return 'school_year_draft:' . tenant('id') . ':' . auth('tenant')->id();
    }

    private function restoreDraft(): bool
    {
        $draft = session()->get($this->draftSessionKey());

        if (! $draft) {
            return false;
        }

        if (now()->diffInMinutes($draft['saved_at'] ?? now()->subDay()) > self::DRAFT_TTL_MINUTES) {
            $this->clearDraft();
            return false;
        }

        $this->schoolYear   = $draft['schoolYear']   ?? null;
        $this->periode_type = $draft['periode_type'] ?? $this->periode_type;
        $this->periods       = $draft['periods']      ?? [];
        $this->year_min      = $draft['year_min']     ?? null;
        $this->year_max      = $draft['year_max']     ?? null;

        if (empty($this->periods)) {
            $this->initPeriods();
        }

        $this->notification()->info(
            title: 'Brouillon restauré',
            description: 'Vos saisies précédentes ont été récupérées.',
        );

        return true;
    }

    private function saveDraft(): void
    {
        session()->put($this->draftSessionKey(), [
            'schoolYear'   => $this->schoolYear,
            'periode_type' => $this->periode_type,
            'periods'      => $this->periods,
            'year_min'     => $this->year_min,
            'year_max'     => $this->year_max,
            'saved_at'     => now(),
        ]);
    }

    private function clearDraft(): void
    {
        session()->forget($this->draftSessionKey());
    }

    public function dehydrate(): void
    {
        $this->saveDraft();
    }

    public function discardDraft(): void
    {
        $this->clearDraft();
        $this->reset(['schoolYear', 'year_min', 'year_max']);
        $this->periode_type = 'Semestre';
        $this->initPeriods();

        $this->notification()->success(title: 'Formulaire réinitialisé');
    }

    /*
    |--------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------
    | Logique des dates (cascade, bornes, periodsRules()) héritée du trait
    | ManagesSchoolYearPeriods — ne pas dupliquer ici.
    */

    protected function rules(): array
    {
        return array_merge([
            'schoolYear' => [
                'required', 'string', 'regex:/^\d{4}-\d{4}$/',
                Rule::in(__defaultSchoolYears()),
                'unique:school_years,slug',
            ],
            'periode_type' => ['required', 'string', Rule::in(config('app.periode_types'))],
        ], $this->periodsRules());
    }

    public function render()
    {
        return view('livewire.tenants.schoolyears.create-school-year');
    }

    /*
    |--------------------------------------------------------------------
    | Création
    |--------------------------------------------------------------------
    */

    public function create(): void
    {
        $this->validate();

        try {
            [$startYear, $endYear] = explode('-', $this->schoolYear);

            $done = SchoolYear::create([
                'slug' => trim($this->schoolYear),
                'min_year' => trim($startYear),
                'max_year' => trim($endYear),
                'periode_type' => trim($this->periode_type),
                'periods' => $this->buildPeriodsData(), // cast 'array' requis sur le modèle
                'locked_for_period' => null,
                'marks_locked_for_periods' => null,
            ]);

            if ($done) {
                $this->clearDraft();

                broadcast(new NewSchoolYearCreated(tenant('id'), $done->slug));

                $this->redirect(route('tenant.school-years.index'), navigate: true);
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }
    }
}