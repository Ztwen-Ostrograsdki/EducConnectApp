<?php

namespace App\Livewire\Tenants\Classes;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Créer nouvelle classe")]
class CreateClasseComponent extends Component
{
    use WireUiActions;


    public int    $counter        = 0;
    public int    $school_year_id = 0;
    public string    $school_year ='';
    public int    $promotion_id   = 0;
    public ?int   $filiar_id      = null;
    public ?int   $serial_id      = null;
    public string $name           = '';
    public string $code           = '';
    public string $localization   = 'Bâtiment H - Salle 1';
    public int    $effectif_max   = 40;
    public bool   $is_active      = true;
    public bool   $is_locked      = false;
    public bool   $is_new_system  = false;

    // Pour le select searchable du prof principal
    public string $teacherSearch = '';

    public function mount(): void
    {
        // Année active par défaut
        $active = SchoolYear::current()->first();
        if ($active) {
            $this->school_year_id = $active->id;

            $this->school_year = $active->slug;
        }
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    // ─── Computed ─────────────────────────────────────────────────────

    #[Computed]
    public function schoolYears()
    {
        return SchoolYear::orderByDesc('min_year')->get(['id', 'slug', 'is_active']);
    }

    #[Computed]
    public function promotions()
    {
        return Promotion::where('is_active', true)
        ->when($this->is_new_system, fn($q) => $q->where('is_new_system', $this->is_new_system))
        ->orderBy('order')->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)
        ->when($this->is_new_system, fn($q) => $q->where('is_new_system', $this->is_new_system))->orderBy('name')->get(['id', 'name', 'code']);
    }

    public function updatedIsNewSystem($value)
    {
        unset($this->existingClasses);

        $this->reset('filiar_id', 'serial_id', 'promotion_id');

        self::getClasseName();
    }

    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
    }

    /**
     * Classes déjà créées pour l'année + la promotion (+ filière / série) sélectionnées.
     */
    #[Computed]
    public function existingClasses()
    {
        if (! $this->school_year_id || ! $this->promotion_id) {
            return collect();
        }

        return Classe::query()
            ->withTrashed() // une classe en corbeille bloque aussi l'unicité du nom
            ->select(['id', 'name', 'code', 'slug', 'effectif_max', 'is_active', 'is_locked', 'deleted_at'])
            ->withCount('students')
            ->where('school_year_id', $this->school_year_id)
            ->where('promotion_id', $this->promotion_id)
            ->when($this->filiar_id, fn ($q) => $q->where('filiar_id', $this->filiar_id))
            ->when($this->serial_id, fn ($q) => $q->where('serial_id', $this->serial_id))
            ->orderBy('name')
            ->get();
    }

    /**
     * Le nom saisi existe-t-il déjà dans la sélection courante ?
     */
    #[Computed]
    public function nameAlreadyTaken(): bool
    {
        return $this->name !== ''
            && $this->existingClasses->contains(
                fn ($c) => mb_strtolower($c->name) === mb_strtolower($this->name)
            );
    }

    // ─── Actions ──────────────────────────────────────────────────────

    public function save(): void
    {
        try {
            $this->validate([
                'school_year_id' => 'required|exists:school_years,id',
                'localization'   => 'string|nullable',
                'promotion_id'   => 'required|exists:promotions,id',
                'filiar_id'      => 'nullable|exists:filiars,id',
                'serial_id'      => 'nullable|exists:serials,id',
                'name'           => [
                    'required', 'string', 'max:100',
                    Rule::unique('classes', 'name')
                        ->where('school_year_id', $this->school_year_id),
                ],
                'code'         => 'nullable|string|max:30',
                'effectif_max' => 'required|integer|min:1|max:200',
                'is_active'    => 'boolean',
                'is_new_system'=> 'boolean',
                'is_locked'    => 'boolean',
            ]);


            $classe = Classe::create([
                'uuid'           => (string) Str::uuid(),
                'school_year_id' => $this->school_year_id,
                'promotion_id'   => $this->promotion_id,
                'slug'           => Str::slug($this->name),
                'filiar_id'      => $this->filiar_id,
                'serial_id'      => $this->serial_id,
                'name'           => $this->name,
                'code'           => $this->code ?: null,
                'level'          => tenant('level'),
                'effectif_max'   => $this->effectif_max,
                'is_active'      => $this->is_active,
                'is_new_system'  => $this->is_new_system,
                'is_locked'      => $this->is_locked,
                'localization'   => $this->localization,
            ]);

            if($classe){

                $this->notification()->success(
                    title: 'Nouvelle classe créée',
                    description: "La classe {$this->name} a été créée avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->resetExcept('school_year_id', 'school_year', 'promotion_id', 'is_new_system', 'effectif_max');

                $this->updatedPromotionId($this->promotion_id);

                // Rafraîchit la liste des classes existantes (la nouvelle y apparaît)
                unset($this->existingClasses, $this->nameAlreadyTaken);
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite lors de la création de la classe',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }

    }


    public function updatedPromotionId($promotion_id)
    {
        $promotion = Promotion::find($promotion_id);

        if (! $promotion) {
            return;
        }

        $this->filiar_id = $promotion->filiar_id;

        $this->serial_id = $promotion->serial_id;

        $this->getClasseName();
    }

    public function updatedFiliarId(): void
    {
        if ($this->promotion_id) {
            $this->getClasseName();
        }
    }

    public function updatedSerialId(): void
    {
        if ($this->promotion_id) {
            $this->getClasseName();
        }
    }



    public function getClasseName()
    {
        $name = '';
        $serial = '';
        $filiar = '';
        $suffix = null;

        $tenant = tenancy()->tenant;


        if($this->promotion_id){

            $promotion_name = Promotion::find($this->promotion_id)?->name;

            $name =  $promotion_name . '-';

            if(!$tenant->promotionCanHasFiliarOrSerial($this->promotion_id)){

                $name = $promotion_name;
            }

        }

        if($tenant->promotionCanHasFiliarOrSerial($this->promotion_id)){

            if($this->promotion_id && $this->serial_id){

                $serial = Serial::find($this->serial_id)?->code;

                $name .= '' . Str::upper($serial);

            }
            elseif($this->promotion_id && $this->filiar_id){

                $filiar = Filiar::find($this->filiar_id)?->code;

                $name .=  Str::upper($filiar);

            }

            $suffix = $serial ? $serial : $filiar;
        }

        

        $this->code = $tenant->classeCodeGenerator($this->school_year_id, $this->promotion_id, $name, $suffix);

        $this->name = $tenant->classeNameGenerator($this->school_year_id, $this->promotion_id, $name);
    }

    public function updatedSchoolYearId(?int $schoolYearId)
    {
        if($schoolYearId){

            $this->school_year = SchoolYear::find($schoolYearId)?->slug;
        }
    }

    public function render()
    {
        return view('livewire.tenants.classes.create-classe-component');
    }
}