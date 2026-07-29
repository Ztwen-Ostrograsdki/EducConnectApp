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

    #[Computed]
    public function classes()
    {
        $yearId = $this->activeYear?->id;

        return Classe::query()
                    ->where('school_year_id', $yearId)
                    ->with(['promotion', 'filiar', 'serial', 'principal', 'students', 'teachers'])
                    ->withCount(['students'])
                    ->withCount([
                        'teachers as teachers_count' => function ($q) use ($yearId) {
                            $q->select(DB::raw('count(distinct classe_subject_of_school_years.teacher_id)'))
                            ->where('classe_subject_of_school_years.school_year_id', $yearId)
                            ->where('classe_subject_of_school_years.is_active', true)
                            ->whereNull('classe_subject_of_school_years.ended_at');
                        },
                    ])
                    ->when($this->search, fn($q) =>
                        $q->where(function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%');
                        })
                    )
                    ->when($this->promotion, function($q){

                        $q->whereHas('promotion', function($qq){

                            $qq->whereAny(['name', 'slug', 'code'], $this->promotion);
                            
                        });

                    })
                    ->when($this->status, function ($qs) {
                        match ($this->status) {
                            'closed' => $qs->where('is_locked', true),
                            'open' => $qs->where('is_locked', false),
                            'active' => $qs->where('is_active', true),
                            'with_students' => $qs->whereHas('students', fn ($q) =>
                                $q->where('is_active', true)->whereNull('ended_at')
                            ),
                            'with_leaves_students' => $qs->whereHas('leavesStudents', fn ($q) =>
                                $q->whereNull('ended_at')
                            ),
                            'without_students' => $qs->whereDoesntHave('students', fn ($q) =>
                                $q->where('is_active', true)->whereNull('ended_at')
                            ),
                            'with_teachers' => $qs->whereHas('teachers', fn ($q) =>
                                $q->where('classe_subject_of_school_years.is_active', true)
                                ->whereNull('classe_subject_of_school_years.ended_at')
                            ),
                            'without_teachers' => $qs->whereDoesntHave('teachers', fn ($q) =>
                                $q->where('classe_subject_of_school_years.is_active', true)
                                ->whereNull('classe_subject_of_school_years.ended_at')
                            ),
                            default => null,
                        };
                    })
                    ->when($this->filiar,    fn($q) => $q->where('filiar_id', $this->filiar))
                    ->when($this->serial,    fn($q) => $q->where('serial_id', $this->serial))
                    ->orderBy('updated_at')
                    ->paginate($this->perPage);
    }


    // ─── Render ───────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.tenants.classes.classes-portal');
    }
}