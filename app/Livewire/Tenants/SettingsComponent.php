<?php

namespace App\Livewire\Tenants;

use App\Events\DataUpdatedEvent;
use App\Livewire\Tenants\ActionsTraits\SchoolYearsActions;
use App\Models\SchoolYear;
use App\Tools\BeninData;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('PAGE DES PARAMETRES')]
class SettingsComponent extends Component
{
    use WireUiActions, SchoolYearsActions;

    public $cities = [];

    public string $activeTab = 'general';

    // ── Général ──
    #[Validate('required|string|max:255')]
    public string $school_name = '';

    #[Validate('nullable|string|max:255')]
    public string $school_devise = '';

    #[Validate('nullable|string|max:50')]
    public string $contacts = '';

    #[Validate('nullable|string|max:255')]
    public string $adresse = '';

    public ?string $city = null;

    public ?string $email = null;

    public ?string $department = null;
    
    
    // ── Notifications ──
    public bool $notify_parents_marks = true;
    public bool $notify_teachers_absences = true;
    public bool $notify_director_payments = true;
    public bool $email_digest = false;

    

    public function mount(): void
    {
        if(session()->has('settings_tab')){

            $this->activeTab = session('settings_tab');

        }

        session()->put('settings_tab', $this->activeTab);

        $this->initAcademic();

        $this->initGeneral();

        $this->initSecurity();
    }

    #[Computed]
    public function activeYear(): ?SchoolYear { return SchoolYear::current()->first(); }


    // ── Académique ──
    #[Validate('nullable|numeric|min:10|max:20')]
    public ?float $min_average_to_pass = 10.0;

    #[Validate('required|in:semestre,trimestre')]
    public ?string $periode_type = 'semestre';

    #[Validate('required|in:devoir1-devoir2,devoir-compo')]
    public ?string $devoirs_type = 'devoir1-devoir2';

    public ?string $activeSchoolYearSlug = null;

    #[Validate('nullable|integer|min:1')]
    public ?int $active_period = null;

    public bool $is_active = false;

    public bool $is_closed = false;

    #[Validate('nullable|array')]
    public ?array $marks_locked_for_periods = [];

    #[Validate('nullable|integer|min:1')]
    public ?string $locked_for_period = null;

    public bool $yearly_average_is_visible = true;

    public bool $is_current_school_year = true;


    /**
     * Liste des périodes disponibles selon periode_type.
     * semestre => [1 => 'Semestre 1', 2 => 'Semestre 2']
     * trimestre => [1 => 'Trimestre 1', 2 => 'Trimestre 2', 3 => 'Trimestre 3']
     */
    #[Computed]
    public function periodOptions(): array
    {
        if ($this->periode_type === 'trimestre') {
            return [
                1 => 'Trimestre 1',
                2 => 'Trimestre 2',
                3 => 'Trimestre 3',
            ];
        }

        return [
            1 => 'Semestre 1',
            2 => 'Semestre 2',
        ];
    }

    public function initAcademic()
    {
        $school_year = $this->activeYear;

        if ($school_year) {
            $this->min_average_to_pass = $school_year->min_average_to_pass;
            $this->periode_type = $school_year->periode_type;
            $this->devoirs_type = $school_year->devoirs_type;
            $this->activeSchoolYearSlug = $school_year->slug;
            $this->active_period = $school_year->active_period;
            $this->is_active = $school_year->is_active;
            $this->is_closed = $school_year->is_closed;
            $this->marks_locked_for_periods = $school_year->marks_locked_for_periods ?? [];
            $this->yearly_average_is_visible = $school_year->yearly_average_is_visible;
            $this->is_current_school_year = $school_year->is_current_school_year;
            $this->locked_for_period = $school_year->locked_for_period;
        }
    }

    /**
     * Si periode_type change, on reset active_period / locked_for_period /
     * marks_locked_for_periods pour éviter des valeurs incohérentes
     * (ex: period 3 sélectionné alors qu'on repasse en semestre).
     */
    public function updatedPeriodeType(): void
    {
        $validPeriods = array_keys($this->periodOptions);

        if ($this->active_period && ! in_array($this->active_period, $validPeriods)) {
            $this->active_period = null;
        }

        if ($this->locked_for_period && ! in_array((int) $this->locked_for_period, $validPeriods)) {
            $this->locked_for_period = null;
        }

        $this->marks_locked_for_periods = array_values(array_intersect(
            $this->marks_locked_for_periods ?? [],
            $validPeriods
        ));
    }

    public function saveAcademic(): void
    {
        $validated = $this->validate([
            'min_average_to_pass'         => 'nullable|numeric|min:10|max:20',
            'periode_type'                => 'required|in:semestre,trimestre',
            'devoirs_type'                => 'required|in:devoir1-devoir2,devoir-compo',
            'active_period'               => 'nullable|integer|min:1|max:' . count($this->periodOptions),
            'locked_for_period'           => 'nullable|integer|min:1|max:' . count($this->periodOptions),
            'marks_locked_for_periods'    => 'nullable|array',
            'marks_locked_for_periods.*'  => 'integer|min:1|max:' . count($this->periodOptions),
            'yearly_average_is_visible'   => 'boolean',
        ]);

        $schoolYear = $this->activeYear;

        if (! $schoolYear) {
            $this->addError('periode_type', "Aucune année scolaire active n'a été trouvée.");
            return;
        }

        $schoolYear->update($validated);

        unset($this->activeYear, $this->periodOptions);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Paramètres académiques mis à jour',
            description: 'Les modifications ont été enregistrées avec succès.',
        );
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;

        session()->put('settings_tab', $tab);
    }

    public function saveGeneral(): void
    {
        $this->validate(
            [
                'school_name' => 'required|string|max:255',
                'school_devise' => 'nullable|string|max:255',
                'contacts' => 'nullable|string|max:50',
                'adresse' => 'nullable|string|max:255',
            ]
        );

    }

    

    public function saveNotifications(): void
    {
    }



    // ── Sécurité ──
    public bool $force_2fa = false;
    public int  $session_lifetime = 120;
    public bool $tutors_can_see_bulletin = false;
    public bool $tutors_can_download_bulletin = false;
    public bool $pp_can_edit_coef = false;
    public bool $ae_can_edit_coef = false;
    public bool $ca_can_edit_coef = false;
    public bool $open_only_for_tenant = false;

    public function initSecurity()
    {
        $tenant = tenancy()->tenant;

        if ($tenant) {
            $this->force_2fa = (bool) $tenant->force_2fa;
            // $this->session_lifetime = $tenant->session_lifetime ?? 120;
            $this->tutors_can_see_bulletin = (bool) $tenant->tutors_can_see_bulletin;
            $this->tutors_can_download_bulletin = (bool) $tenant->tutors_can_download_bulletin;
            $this->pp_can_edit_coef = (bool) $tenant->pp_can_edit_coef;
            $this->ae_can_edit_coef = (bool) $tenant->ae_can_edit_coef;
            $this->ca_can_edit_coef = (bool) $tenant->ca_can_edit_coef;
            $this->open_only_for_tenant = (bool) $tenant->open_only_for_tenant;
            
        }
    }

    public function saveSecurity(): void
    {
        $validated = $this->validate([
            'force_2fa'                     => 'boolean',
            // Laravel gère nativement le lifetime en minutes ; on borne à 24h max
            // pour éviter des sessions ouvertes indéfiniment sur un système scolaire multi-rôles.
            // 'session_lifetime'               => 'required|integer|min:15|max:1440',
            'tutors_can_see_bulletin'        => 'boolean',
            'tutors_can_download_bulletin'   => 'boolean',
            'pp_can_edit_coef'                => 'boolean',
            'ae_can_edit_coef'                => 'boolean',
            'ca_can_edit_coef'                => 'boolean',
            'open_only_for_tenant'            => 'boolean',
        ]);

        $tenant = tenancy()->tenant;

        if (! $tenant) {
            $this->addError('force_2fa', "Le tenant courant est introuvable.");
            return;
        }

        $tenant->update($validated);

        // Le lifetime doit être appliqué immédiatement à la session en cours,
        // sinon l'utilisateur connecté verrait sa session expirer selon l'ancienne valeur
        // jusqu'à son prochain login.
        
        
        // config(['session.lifetime' => $validated['session_lifetime']]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Paramètres de sécurité mis à jour',
            description: 'Les modifications ont été enregistrées avec succès.',
        );
    }


    #[Computed]
    public function enseignement_types() { return BeninData::getSytems();}

    #[Computed]
    public function school_types() { return config('app.school_types');}

    #[Computed]
    public function periode_types() { return config('app.periode_types');}
  
  
    #[Computed]
    public function devoirs_types() { return config('app.devoirs_types');}


    #[Computed]
    public function departments() { return BeninData::getDepartments();}
 
    #[Computed]
    public function countries() { return ['BENIN' => 'BENIN'];}

    public function updatedDepartment(?string $department)
    {

        if($department){

            $this->cities = [];

            $this->city = null;

            $departments = BeninData::getDepartments();

            $department_key = array_keys($departments, $department)[0];

            $this->cities = BeninData::getCities($department_key);

        }

    }


    public function initGeneral()
    {
        $tenant = tenancy()->tenant;

        if ($tenant) {

            $this->school_name = $tenant?->school_name ?? '';

            $this->school_devise = $tenant?->school_devise ?? '';

            $this->contacts = $tenant?->contacts ?? '';

            $this->email = $tenant?->email ?? '';

            $this->city = $tenant?->city ?? '';

            $this->department = $tenant?->department ?? '';

            $this->adresse = $tenant?->adresse ?? '';

            if($this->department){

                $departments = BeninData::getDepartments();

                $department_key = array_keys($departments, $this->department)[0];

                $this->cities = BeninData::getCities($department_key);


            }
        }
    }


    

    public function render()
    {
        return view('livewire.tenants.settings-component');
    }
}
