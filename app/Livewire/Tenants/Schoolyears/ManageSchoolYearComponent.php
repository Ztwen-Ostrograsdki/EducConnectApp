<?php

namespace App\Livewire\Tenants\Schoolyears;


use App\Livewire\Traits\ManagesSchoolYearPeriods;
use App\Livewire\Traits\ValidatorTrait;
use App\Models\SchoolYear;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title('Modifier une année scolaire')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class ManageSchoolYearComponent extends Component
{
    use WireUiActions, ValidatorTrait, ManagesSchoolYearPeriods;

    public SchoolYear $school_year_model;

    public ?string $schoolYear = null;
    public ?string $year_min = null;
    public ?string $year_max = null;

    public string $periode_type = 'Semestre';
    public array $periods = [];

    public function mount(string $school_year)
    {
        if(!$school_year) return abort(404);

        $school_year_model = SchoolYear::whereSlug($school_year)->first();

        if(!$school_year_model) return abort(404);

        $this->school_year_model = $school_year_model;

        if ($this->school_year_model->is_closed) {
            $this->notification()->error(
                title: 'Modification impossible',
                description: 'Cette année scolaire est clôturée et ne peut plus être modifiée.',
            );
            $this->redirect(route('tenant.schoolyear.profil', ['school_year' => $school_year]), navigate: true);
            return;
        }

        $this->schoolYear   = $school_year_model->slug;
        $this->year_min     = "{$school_year_model->min_year}-09-01";
        $this->year_max     = "{$school_year_model->max_year}-08-31";
        $this->periode_type = Str::ucfirst($school_year_model->periode_type);

        $this->periods = collect($school_year_model->periods ?? [])
            ->values()
            ->map(fn ($p) => ['start' => $p['start'] ?? null, 'end' => $p['end'] ?? null])
            ->toArray();

        if (empty($this->periods)) {
            $this->initPeriods();
        }
    }

    /**
     * Liste déroulante des années — on garde la valeur actuelle même si elle
     * sort de la fenêtre glissante de __defaultSchoolYears(), sinon une
     * vieille année scolaire ne s'afficherait plus correctement dans le select.
     */
    public function getYearOptionsProperty(): array
    {
        $defaults = __defaultSchoolYears();

        if ($this->schoolYear && ! in_array($this->schoolYear, $defaults, true)) {
            array_unshift($defaults, $this->schoolYear);
        }

        return $defaults;
    }

    protected function rules(): array
    {
        return array_merge([
            'schoolYear' => [
                'required', 'string', 'regex:/^\d{4}-\d{4}$/',
                Rule::unique('school_years', 'slug')->ignore($this->school_year_model->id),
            ],
            'periode_type' => ['required', 'string', Rule::in(config('app.periode_types'))],
        ], $this->periodsRules());
    }

    public function render()
    {
        return view('livewire.tenants.schoolyears.manage-school-year-component');
    }

    public function update(): void
    {
        $this->validate();

        try {
            [$startYear, $endYear] = explode('-', $this->schoolYear);

            $this->school_year_model->update([
                'slug' => trim($this->schoolYear),
                'min_year' => trim($startYear),
                'max_year' => trim($endYear),
                'periode_type' => trim($this->periode_type),
                'periods' => $this->buildPeriodsData(),
            ]);

            $this->notification()->success(
                title: 'Année scolaire mise à jour',
                description: "L'année scolaire {$this->schoolYear} a été modifiée avec succès.",
            );

            $this->redirect(route('tenant.schoolyear.profil', ['school_year' => $this->school_year_model->slug]), navigate: true);

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }
    }
}