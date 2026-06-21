<?php

namespace App\Livewire\Tenants\Schoolyears;

use App\Events\NewSchoolYearCreated;
use App\Livewire\Traits\ValidatorTrait;
use App\Models\SchoolYear;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title('Ajout de nouvelle année scolaire')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class CreateSchoolYear extends Component
{
    use WireUiActions, ValidatorTrait;

    public ?string $schoolYear = null;
    public ?string $year_min = null;
    public ?string $year_max = null;

    public string $periode_type = 'Semestre';
    public array $periods = [];

    public bool $done = false;

    // durée de vie du brouillon avant expiration automatique
    private const DRAFT_TTL_MINUTES = 120;

    public function mount(): void
    {
        if (! $this->restoreDraft()) {
            $this->initPeriods();
        }
    }

    /**
     * Clé de session unique par tenant + utilisateur,
     * pour éviter qu'un brouillon ne fuite entre tenants ou entre comptes.
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

        // expiration : on ignore un brouillon trop vieux
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

    /**
     * S'exécute après CHAQUE requête Livewire (clic, changement, etc.) —
     * un seul point d'entrée, on est sûr de ne rien manquer.
     */
    public function dehydrate(): void
    {
        if(!$this->done === true){
            $this->saveDraft();
        }
    }

    public function discardDraft(): void
    {
        $this->clearDraft();
        $this->reset(['schoolYear', 'year_min', 'year_max']);
        $this->periode_type = 'Semestre';
        $this->initPeriods();

    }

    public function initPeriods(): void
    {
        $count = Str::lower($this->periode_type) === 'trimestre' ? 3 : 2;
        $this->periods = array_fill(0, $count, ['start' => null, 'end' => null]);
    }

    public function updatedPeriodeType(): void
    {
        $this->initPeriods();
    }

    public function updatedPeriods($value, $key): void
    {
        [$index, $field] = explode('.', $key);
        $index = (int) $index;

        if ($field === 'start' && !empty($this->periods[$index]['end']) && $this->periods[$index]['end'] <= $value) {
            $this->periods[$index]['end'] = null;
        }

        if ($field === 'end' && !empty($this->periods[$index + 1]['start'] ?? null) && $this->periods[$index + 1]['start'] <= $value) {
            $this->periods[$index + 1]['start'] = null;
        }
    }

    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    protected function rules(): array
    {
        $rules = [
            'schoolYear'   => ['required', 'string', 'regex:/^\d{4}-\d{4}$/', Rule::in(__defaultSchoolYears()), 'unique:school_years,slug'],
            'periode_type' => ['required', 'string', Rule::in(config('app.periode_types'))],
        ];

        foreach ($this->periods as $i => $period) {
            $startRule = ['required', 'date', "after_or_equal:{$this->year_min}", "before_or_equal:{$this->year_max}"];

            if ($i > 0) {
                $startRule[] = "after:periods." . ($i - 1) . ".end";
            }

            $rules["periods.{$i}.start"] = $startRule;

            $rules["periods.{$i}.end"] = [
                'required', 'date',
                "after:periods.{$i}.start",
                "before_or_equal:{$this->year_max}",
            ];
        }

        return $rules;
    }

    public function render()
    {
        return view('livewire.tenants.schoolyears.create-school-year');
    }

    public function updatedSchoolYear(?string $value): void
    {
        if (! $value) {
            $this->year_min = null;
            $this->year_max = null;
            return;
        }

        [$startYear, $endYear] = explode('-', $value);
        $this->year_min = "{$startYear}-09-01";
        $this->year_max = "{$endYear}-08-31";
    }


    public function formattedDate(?string $date): ?string
    {
        if (! $date) {
            return null;
        }

        $carbon = Carbon::parse($date)->locale('fr');

        // weekday + jour en minuscule, mois avec 1ère lettre en majuscule, année
        return $carbon->translatedFormat('l d') . ' '
            .Str::ucfirst($carbon->translatedFormat('F')) . ' '
            . $carbon->format('Y');
    }

    public function create()
    {
        $this->validate();

        try {
            $periodsData = [];

            foreach ($this->periods as $k => $period) {
                $periodLabel = str()->lower($this->periode_type) . ' ' . ($k + 1);
                $periodsData[$periodLabel] = $period;
            }

            [$startYear, $endYear] = explode('-', $this->schoolYear);

            $data = [
                'slug' => trim($this->schoolYear),
                'min_year' => (int)trim($startYear),
                'max_year' => (int)trim($endYear),
                'periode_type' => trim($this->periode_type),
                'periods' => json_encode($periodsData),
            ];

            $done = SchoolYear::create($data);

            if ($done) {

                $this->done = true;

                $this->clearDraft(); // ✅ brouillon consommé, on le supprime

                broadcast(new NewSchoolYearCreated(tenant('id'), $done->slug));

                // $this->redirect(route('tenant.school-years.index'), navigate: true);
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite',
                description: 'Erreur : ' . cutter($th->getMessage(), 250),
            );
        }
    }
}