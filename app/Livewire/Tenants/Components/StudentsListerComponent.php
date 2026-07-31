<?php

namespace App\Livewire\Tenants\Components;

use App\Livewire\Tenants\ActionsTraits\StudentsActions;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class StudentsListerComponent extends Component
{
    use WireUiActions, WithPagination, StudentsActions;

    public ?Classe $classe = null;

    public ?Filiar $filiar = null;

    public ?Serial $serial = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public ?int $classe_id = null;

    public ?string $gender = null;

    public ?int $subject_id = null;

    public ?string $promotionInGroups = null;

    public ?string $promotion = null;

    public ?Promotion $promotionModel = null;
    
    public int $perPage = 30;

    public int $counterh = 0;

    public ?string $search = null;

    public ?string $status = null;

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counterh++;
    }

    public function resetFilters()
    {

        if($this->filiar){
            session()->forget(
                [
                    'students_gender_selected', 
                    'students_promotion_selected', 
                    'students_classe_selected',
                    'students_serial_selected', 
                    'students_subject_selected',
                    'students_status_selected',
                ]
            );

            $this->reset('search', 'gender', 'classe_id', 'subject_id', 'promotionInGroups', 'serial_id', 'status');

            $this->resetPage();

            return;
        }

        if($this->promotion || $this->promotionModel){

            session()->forget(
                [
                    'students_gender_selected', 
                    'students_classe_selected',
                    'students_filiar_selected',
                    'students_serial_selected', 
                    'students_subject_selected',
                    'students_status_selected',
                ]
            );

            $this->reset('search', 'gender', 'classe_id', 'subject_id', 'filiar_id', 'serial_id', 'status');

            $this->resetPage();

            return;
        }

        if($this->serial){

            session()->forget(
                [
                    'students_gender_selected', 
                    'students_promotion_selected', 
                    'students_classe_selected',
                    'students_serial_selected', 
                    'students_subject_selected',
                    'students_status_selected',
                ]
            );

            $this->reset('search', 'gender', 'classe_id', 'subject_id', 'promotionInGroups', 'filiar_id', 'status');

            $this->resetPage();

            return;
        }

        if($this->classe){
            session()->forget(
                [
                    'students_gender_selected', 
                    'students_promotion_selected', 
                    'students_filiar_selected',
                    'students_serial_selected', 
                    'students_subject_selected',
                    'students_status_selected',
                ]
            );

            $this->reset('search', 'gender', 'subject_id', 'promotionInGroups', 'filiar_id', 'serial_id', 'status');

            $this->resetPage();
        }
    }

    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function genders() : ?array 
    {
        return config('app.genders');

    }


    public function updatingSearch(): void
    {
        $this->resetPage();
    }


    public function updatingGender(): void
    {
        $this->resetPage();
    }

    public function updatedGender(?string $value): void
    {
        session()->put('students_gender_selected', $value);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(?string $value): void
    {
        session()->put('students_status_selected', $value);
    }

    public function updatingClasseId(): void
    {
        $this->resetPage();
    }

    public function updatedClasseId(?string $value): void
    {
        session()->put('students_classe_selected', $value);
    }

    public function updatingPromotionInGroups(): void
    {
        $this->resetPage();
    }

    public function updatedPromotionInGroups(?string $value): void
    {
        session()->put('students_promotion_selected', $value);
    }
    
    public function updatingFiliarId(): void
    {
        $this->resetPage();
    }

    public function updatedFiliarId(?string $value): void
    {
        session()->put('students_filiar_selected', $value);
    }

    public function updatingSerialId(): void
    {
        $this->resetPage();
    }

    public function updatedSerialId(?string $value): void
    {
        session()->put('students_serial_selected', $value);
    }


    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        if($this->filiar) return $this->filiar->classes()->where('classes.school_year_id', $this->activeYear->id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc')->get();
        
        elseif($this->serial) return $this->serial->classes()->where('classes.school_year_id', $this->activeYear->id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc')->get();
        
        elseif($this->promotion){

            return Classe::where('is_active', true)->where('school_year_id', $this->activeYear->id)
                 ->whereHas('promotion', fn($q) => 
                      $q->where('name', str()->lower($this->promotion))
                      ->orWhere('name', str()->upper($this->promotion))
                      ->where('is_active', true)
                )
                 ->where('classes.is_locked', false)
                 ->orderBy('name', 'desc')->get();

        } 
        elseif($this->promotionModel){

            return Classe::where('is_active', true)
                   ->where('school_year_id', $this->activeYear->id)
                   ->where('promotion_id', $this->promotionModel->id)
                   ->where('is_locked', false)
                   ->orderBy('name', 'desc')->get();

        } 
        else return collect();
        
    }

    #[Computed]
    public function subjects()
    {
        if($this->filiar) return $this->filiar?->getFiliarSubjectsOfSchoolYear()->orderBy('name', 'desc')->get();

        elseif($this->serial) return $this->serial?->getSerialSubjectsOfSchoolYear()->orderBy('name', 'desc')->get();

        else return Subject::where('is_active', true)->orderBy('name', 'desc')->get();
    }
    
    #[Computed]
    public function promotions()
    {
        if($this->filiar) return array_unique($this->filiar?->promotions()->pluck('name')->toArray());

        elseif($this->serial) return array_unique($this->serial?->promotions()->pluck('name')->toArray());

        else return config('app.promotionInGroups');
    }

    #[Computed]
    public function students()
    {
        return $this->getStudentsData()->paginate($this->perPage);
    }

    public function getStudentsData(): Builder
    {
        $query = Student::query()
            ->select('students.*')
            ->withTrashed();

        // ============================================
        // 1. Contexte principal (un seul à la fois)
        // ============================================

        if ($this->classe) {
            // Cas le plus précis : une classe précise
            $query->whereHas('classes', function (Builder $q) {
                $q->where('is_active', true)
                ->where('classe_id', $this->classe->id)
                ->where('school_year_id', $this->activeYear->id);
            });
        }
        elseif ($this->filiar) {
            // Tous les élèves de la filière (année active)
            $query->whereHas('classes', function (Builder $q) {
                $q->where('is_active', true)
                ->where('school_year_id', $this->activeYear->id)
                ->whereHas('classe', function (Builder $qr) {
                    $qr->where('filiar_id', $this->filiar->id)
                        ->where('is_active', true)
                        ->where('school_year_id', $this->activeYear->id);
                });
            });

            // Filtres secondaires possibles quand on est en mode filière
            $this->applySecondaryFiltersWhenFiliar($query);
        }
        elseif ($this->serial) {
            // Tous les élèves de la série
            $query->whereHas('classes', function (Builder $q) {
                $q->where('is_active', true)
                ->where('school_year_id', $this->activeYear->id)
                ->whereHas('classe', function (Builder $qr) {
                    $qr->where('serial_id', $this->serial->id)
                        ->where('is_active', true)
                        ->where('school_year_id', $this->activeYear->id);
                });
            });

            // Filtres secondaires possibles quand on est en mode série
            $this->applySecondaryFiltersWhenSerial($query);
        }
        elseif ($this->promotion || $this->promotionModel) {
            // Tous les élèves d'une promotion (par nom)
            if($this->promotion && !$this->promotionModel){

                $promotionName = is_object($this->promotion) 
                ? $this->promotion->name 
                : $this->promotion;

                $query->whereHas('classes', function (Builder $q) use ($promotionName) {
                    $q->where('is_active', true)
                    ->where('school_year_id', $this->activeYear->id)
                    ->whereHas('classe', function (Builder $qr) use ($promotionName) {
                        $qr->whereHas('promotion', function (Builder $qp) use ($promotionName) {
                            $qp->where(function ($q) use ($promotionName) {
                                $q->where('name', $promotionName)
                                    ->orWhere('name', strtolower($promotionName));
                            })->where('is_active', true);
                        });
                    });
                });
            }
            elseif(!$this->promotion && $this->promotionModel){

                $promotion_id = is_object($this->promotionModel) 
                ? $this->promotionModel->id 
                : $this->promotion;

                $query->whereHas('classes', function (Builder $q) use ($promotion_id) {
                    $q->where('is_active', true)
                    ->where('school_year_id', $this->activeYear->id)
                    ->whereHas('classe', function (Builder $qr) use ($promotion_id) {
                        $qr->whereHas('promotion', function (Builder $qp) use ($promotion_id) {
                            $qp->where(function ($q) use ($promotion_id) {
                                $q->where('id', $promotion_id);
                            })->where('is_active', true);
                        });
                    });
                });
            }

            

            // Filtres secondaires possibles quand on est en mode promotion
            $this->applySecondaryFiltersWhenPromotion($query);
        }

        // ============================================
        // 2. Filtres transversaux (toujours appliqués)
        // ============================================

        // Recherche textuelle (IMPORTANT : regrouper les orWhere)
        $query->when($this->search, function (Builder $query) {
            $search = $this->search;
            $query->where(function (Builder $q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('prenames', 'like', "%{$search}%")
                ->orWhere('contacts', 'like', "%{$search}%")
                ->orWhere('adresse', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('department', 'like', "%{$search}%")
                ->orWhere('country', 'like', "%{$search}%")
                ->orWhere('educMaster', 'like', "%{$search}%")
                ->orWhere('gender', 'like', "%{$search}%")
                ->orWhere('birth_date', 'like', "%{$search}%")
                ->orWhere('birth_place', 'like', "%{$search}%")
                ->orWhere('father_full_name', 'like', "%{$search}%")
                ->orWhere('mother_full_name', 'like', "%{$search}%")
                ->orWhere('matricule', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
            });
        });

        // Genre
        $query->when($this->gender, function (Builder $q) {
            $q->whereIn('gender', [
                $this->gender,
                Str::lower($this->gender),
                Str::upper($this->gender),
            ]);
        });

        // Statut
        $query->when($this->status, function (Builder $qst) {
            match ($this->status) {
                'actifs' => $qst->where('is_active', true),
                'desactives' => $qst->where('is_active', false),
                'de la corbeille' => $qst->whereNotNull('deleted_at'),
                'ayant de classe' => $qst->whereHas('classes', fn ($q) =>
                    $q->where('is_active', true)
                    ->where('school_year_id', $this->activeYear->id)
                    ->whereNull('ended_at')
                ),
                'sans classe' => $qst->whereDoesntHave('classes', fn ($q) =>
                    $q->where('school_year_id', $this->activeYear->id)
                ),
                'ayant abandonés' => $qst->whereHas('yearlyStudentsLeaves', fn ($q) =>
                    $q->where('school_year_id', $this->activeYear->id)
                ),
                default => null,
            };
        });

        return $query
            ->orderBy('students.name')
            ->orderBy('students.prenames');
    }
    
    


    protected function applySecondaryFiltersWhenFiliar(Builder $query): void
    {
        // Filtre promotion (via promotionInGroups)
        $query->when($this->promotionInGroups, function (Builder $query) {
            $query->whereHas('classes', function (Builder $q) {
                $q->where('is_active', true)
                ->where('school_year_id', $this->activeYear->id)
                ->whereHas('classe.promotion', function (Builder $qp) {
                    $qp->where(function ($q) {
                        $q->where('name', $this->promotionInGroups)
                            ->orWhere('name', strtolower($this->promotionInGroups));
                    })->where('is_active', true);
                });
            });
        });

        // Filtre classe
        $query->when($this->classe_id, function (Builder $query) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)
                ->where('classe_id', $this->classe_id)
                ->where('school_year_id', $this->activeYear->id)
            );
        });

        // Filtre série
        $query->when($this->serial_id, function (Builder $query) {
            $query->whereHas('classes', function (Builder $q) {
                $q->where('is_active', true)
                ->where('school_year_id', $this->activeYear->id)
                ->whereHas('classe', fn ($qr) =>
                    $qr->where('serial_id', $this->serial_id)
                        ->where('is_active', true)
                        ->where('school_year_id', $this->activeYear->id)
                );
            });
        });
    }

    protected function applySecondaryFiltersWhenSerial(Builder $query): void
    {
        // Filtre promotion
        $query->when($this->promotionInGroups, function (Builder $query) {
            $query->whereHas('classes', function (Builder $q) {
                $q->where('is_active', true)
                ->where('school_year_id', $this->activeYear->id)
                ->whereHas('classe.promotion', function (Builder $qp) {
                    $qp->where(function ($q) {
                        $q->where('name', $this->promotionInGroups)
                            ->orWhere('name', strtolower($this->promotionInGroups));
                    })->where('is_active', true);
                });
            });
        });

        // Filtre classe
        $query->when($this->classe_id, function (Builder $query) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)
                ->where('classe_id', $this->classe_id)
                ->where('school_year_id', $this->activeYear->id)
            );
        });
    }

    protected function applySecondaryFiltersWhenPromotion(Builder $query): void
    {
        $query->when($this->classe_id, function (Builder $query) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)
                ->where('classe_id', $this->classe_id)
                ->where('school_year_id', $this->activeYear->id)
            );
        });

        $query->when($this->serial_id, function (Builder $query) {
            $query->whereHas('classes', function (Builder $q) {
                $q->where('is_active', true)
                ->where('school_year_id', $this->activeYear->id)
                ->whereHas('classe', fn ($qr) =>
                    $qr->where('serial_id', $this->serial_id)
                        ->where('is_active', true)
                        ->where('school_year_id', $this->activeYear->id)
                );
            });
        });

        $query->when($this->filiar_id, function (Builder $query) {
            $query->whereHas('classes', function (Builder $q) {
                $q->where('is_active', true)
                ->where('school_year_id', $this->activeYear->id)
                ->whereHas('classe', fn ($qr) =>
                    $qr->where('filiar_id', $this->filiar_id)
                        ->where('is_active', true)
                        ->where('school_year_id', $this->activeYear->id)
                );
            });
        });
    }



    public function render()
    {
        return view('livewire.tenants.components.students-lister-component');
    }
}
