<?php

namespace App\Livewire\Tenants\Classes;

use App\Livewire\Tenants\ActionsTraits\ClassesActions;
use App\Models\Classe;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des classes")]
class ClassesPortal extends Component
{

    use ClassesActions;

    public function mount(): void
    {
        if ($this->filiar && ! $this->filiars->contains('id', (int) $this->filiar)) {
            $this->reset('filiar');
        }

        if ($this->serial && ! $this->serials->contains('id', (int) $this->serial)) {
            $this->reset('serial');
        }

        if ($this->promotion && ! in_array($this->promotion, $this->promotions, true)) {
            $this->reset('promotion');
        }
    }
    
    #[Computed]
    public function classes()
    {
        $yearId = $this->activeYear?->id;

        // Pas d'année active => aucun résultat (évite "school_year_id IS NULL")
        if (! $yearId) {
            return Classe::query()->whereRaw('1 = 0')->paginate($this->perPage);
        }

        $activeTeacherPivot = fn ($q) => $q
            ->where('classe_subject_of_school_years.school_year_id', $yearId)
            ->where('classe_subject_of_school_years.is_active', true)
            ->whereNull('classe_subject_of_school_years.ended_at');

        $activeStudent = fn ($q) => $q->where('is_active', true)->whereNull('ended_at');

        return Classe::query()
            ->where('classes.school_year_id', $yearId)

            ->with(['promotion', 'filiar', 'serial', 'principal'])
            ->withCount('students')
            ->withCount([
                'teachers as teachers_count' => function ($q) use ($yearId) {
                    $q->select(DB::raw('count(distinct classe_subject_of_school_years.teacher_id)'))
                    ->where('classe_subject_of_school_years.school_year_id', $yearId)
                    ->where('classe_subject_of_school_years.is_active', true)
                    ->whereNull('classe_subject_of_school_years.ended_at');
                },
            ])

            // 3) Recherche groupée
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $term = '%' . $this->search . '%';
                $q->where('name', 'like', $term)
                ->orWhere('code', 'like', $term);
            }))

            ->when($this->promotion, fn ($q) => $q->whereHas('promotion', fn ($qq) =>
                $qq->whereAny(['name', 'slug', 'code'], $this->promotion)
            ))

            ->when($this->status, fn ($q) => match ($this->status) {
                'closed'               => $q->where('is_locked', true),
                'open'                 => $q->where('is_locked', false),
                'active'               => $q->where('is_active', true),
                'with_students'        => $q->whereHas('students', $activeStudent),
                'without_students'     => $q->whereDoesntHave('students', $activeStudent),
                'with_leaves_students' => $q->whereHas('leavesStudents', fn ($s) => $s->whereNull('ended_at')),
                'with_teachers'        => $q->whereHas('teachers', $activeTeacherPivot),
                'without_teachers'     => $q->whereDoesntHave('teachers', $activeTeacherPivot),
                default                => $q,
            })

            // 4) CORRECTION PRINCIPALE : les orWhere sont isolés dans une closure
            ->when($this->system, fn ($q) => match ($this->system) {
                'new_system' => $q->where(fn ($g) => $g
                    ->where('is_new_system', true)
                    ->orWhereHas('promotion', fn ($p) => $p->where('is_new_system', true))
                ),
                'old_system' => $q->where(fn ($g) => $g
                    ->where('is_new_system', false)
                    ->orWhereHas('promotion', fn ($p) => $p->where('is_new_system', false))
                ),
                default => $q,
            })

            ->when($this->filiar, fn ($q) => $q->where('filiar_id', $this->filiar))
            ->when($this->serial, fn ($q) => $q->where('serial_id', $this->serial))

            ->orderBy('updated_at', 'desc')
            ->orderBy('id') // ordre stable pour la pagination
            ->paginate($this->perPage);
    }


    // ─── Render ───────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.tenants.classes.classes-portal');
    }
}