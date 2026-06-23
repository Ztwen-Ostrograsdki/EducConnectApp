<?php

namespace App\Livewire\Tenants\Classes;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Créer nouvelle classe")]
class CreateClasseComponent extends Component
{
    public int    $school_year_id = 0;
    public int    $promotion_id   = 0;
    public ?int   $filiar_id      = null;
    public ?int   $serial_id      = null;
    public string $name           = '';
    public string $code           = '';
    public string $level          = 'secondaire';
    public int    $effectif_max   = 50;
    public ?int   $principal_id   = null;
    public bool   $is_active      = true;
    public bool   $is_locked      = false;

    // Pour le select searchable du prof principal
    public string $teacherSearch = '';

    public function mount(): void
    {
        // Année active par défaut
        $active = SchoolYear::current()->first();
        if ($active) {
            $this->school_year_id = $active->id;
        }
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
        return Promotion::where('is_active', true)->orderBy('order')->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function teachers()
    {
        if (strlen($this->teacherSearch) < 2) {
            return collect();
        }

        $yearId = $this->school_year_id;

        return Teacher::query()
                ->select('teachers.*')
                ->join('users', 'users.id', '=', 'teachers.user_id')
                ->with(['user'])
                ->withoutTrashed()
                ->when($this->teacherSearch, function (Builder $query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('email', 'like', "%{$this->teacherSearch}%");
                        $query->orwhere('name', 'like', "%{$this->teacherSearch}%");
                        $query->orwhere('prenames', 'like', "%{$this->teacherSearch}%");
                        $query->orwhere('contacts', 'like', "%{$this->teacherSearch}%");
                        $query->orwhere('birth_date', 'like', "%{$this->teacherSearch}%");
                        $query->orwhere('birth_place', 'like', "%{$this->teacherSearch}%");
                    });
                })
                ->orwhere('teachers.identifiant', 'like', "%{$this->teacherSearch}%")
                ->get()
                ->filter(fn(Teacher $t) => $t->hasValidAccessForYear(
                    $yearId
                ))
                ->take(10)
                ->values();
    }

    // ─── Actions ──────────────────────────────────────────────────────

    public function selectTeacher(int $id, string $name): void
    {
        $this->principal_id  = $id;
        $this->teacherSearch = $name;
    }

    public function clearTeacher(): void
    {
        $this->principal_id  = null;
        $this->teacherSearch = '';
    }

    public function save(): void
    {
        try {
            $this->validate([
                'school_year_id' => 'required|exists:school_years,id',
                'promotion_id'   => 'required|exists:promotions,id',
                'filiar_id'      => 'nullable|exists:filiars,id',
                'serial_id'      => 'nullable|exists:serials,id',
                'name'           => [
                    'required', 'string', 'max:100',
                    Rule::unique('classes', 'name')
                        ->where('school_year_id', $this->school_year_id)
                        ->whereNull('deleted_at'),
                ],
                'code'         => 'nullable|string|max:30',
                'level'        => 'required|in:primaire,secondaire,superieur',
                'effectif_max' => 'required|integer|min:1|max:200',
                'principal_id' => 'nullable|exists:teachers,id',
                'is_active'    => 'boolean',
                'is_locked'    => 'boolean',
            ]);

            $classe = Classe::create([
                'uuid'           => (string) Str::uuid(),
                'school_year_id' => $this->school_year_id,
                'promotion_id'   => $this->promotion_id,
                'filiar_id'      => $this->filiar_id,
                'serial_id'      => $this->serial_id,
                'name'           => $this->name,
                'code'           => $this->code ?: null,
                'level'          => $this->level,
                'effectif_max'   => $this->effectif_max,
                'principal_id'   => $this->principal_id,
                'is_active'      => $this->is_active,
                'is_locked'      => $this->is_locked,
            ]);

            if($classe){

                $this->notification()->success(
                    title: 'Nouvelle classe créée',
                    description: "La classe {$this->name} a été créée avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->reset();
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Une erreur s\'est produite lors de la création de la classe',
                description: 'Erreur : ' . cutter($th->getMessage(), 150),
            );
        }

    }


    public function render()
    {
        return view('livewire.tenants.classes.create-classe-component');
    }
}
