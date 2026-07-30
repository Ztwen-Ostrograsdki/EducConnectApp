<?php

namespace App\Livewire\Tenants\Components;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Livewire\Tenants\ActionsTraits\UsersActions;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Models\Teacher;
use App\Tools\BeninData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class TeachersListerComponent extends Component
{
    use WireUiActions, WithPagination, TeachersActions, UsersActions;

    // ============================================
    // Contexte principal (un seul non null à la fois)
    // ============================================
    public ?Filiar $filiar = null;
    public ?Classe $classe = null;
    public ?Subject $subject = null;
    public ?Serial $serial = null;
    public ?Promotion $promotionModel = null;   // objet Promotion
    public ?string $promotion = null;           // ou string (nom)

    // ============================================
    // Filtres secondaires
    // ============================================
    public ?int $filiar_id = null;
    public ?int $serial_id = null;
    public ?int $classe_id = null;
    public ?int $subject_id = null;
    public ?string $promotionInGroups = null;

    public ?string $subject_type = null;
    public ?string $search = null;
    public ?string $city = null;
    public ?string $gender = null;
    public ?string $department = null;
    public ?string $status = null;

    public int $counterh = 0;
    public int $perPage = 35;

    public function mount(?string $status = null)
    {
        if ($status) {
            $this->status = $status;
        }

        $this->status            = session('teachers_status_selected', $this->status);
        $this->classe_id         = session('teachers_classe_selected');
        $this->filiar_id         = session('teachers_filiar_selected');
        $this->subject_id        = session('teachers_subject_selected');
        $this->gender            = session('teachers_gender_selected');
        $this->city              = session('teachers_city_selected');
        $this->department        = session('teachers_department_selected');
        $this->serial_id         = session('teachers_serial_selected');
        $this->promotionInGroups = session('teachers_promotion_selected');
        $this->subject_type = session('teachers_subject_type_selected');
    }

    public function clearFilters()
    {
        // ============================================
        // Contexte : Filiar
        // ============================================
        if ($this->filiar) {
            session()->forget([
                'teachers_gender_selected',
                'teachers_city_selected',
                'teachers_department_selected',
                'teachers_promotion_selected',
                'teachers_classe_selected',
                'teachers_serial_selected',
                'teachers_subject_selected',
                'teachers_status_selected',
                'teachers_subject_type_selected',
            ]);

            $this->reset(
                'search', 'gender', 'city', 'department',
                'classe_id', 'subject_id', 'serial_id',
                'promotionInGroups', 'status', 'subject_type'
            );

            $this->resetPage();
            return;
        }

        // ============================================
        // Contexte : Subject
        // ============================================
        if ($this->subject) {
            session()->forget([
                'teachers_gender_selected',
                'teachers_city_selected',
                'teachers_department_selected',
                'teachers_promotion_selected',
                'teachers_classe_selected',
                'teachers_filiar_selected',
                'teachers_serial_selected',
                'teachers_status_selected',
                
            ]);

            $this->reset(
                'search', 'gender', 'city', 'department',
                'classe_id', 'filiar_id', 'serial_id',
                'promotionInGroups', 'status'
            );

            $this->resetPage();
            return;
        }

        // ============================================
        // Contexte : Serial
        // ============================================
        if ($this->serial) {
            session()->forget([
                'teachers_gender_selected',
                'teachers_city_selected',
                'teachers_department_selected',
                'teachers_promotion_selected',
                'teachers_classe_selected',
                'teachers_filiar_selected',
                'teachers_subject_selected',
                'teachers_status_selected',
                'teachers_subject_type_selected'
            ]);

            $this->reset(
                'search', 'gender', 'city', 'department',
                'classe_id', 'filiar_id', 'subject_id',
                'promotionInGroups', 'status', 'subject_type'
            );

            $this->resetPage();
            return;
        }

        // ============================================
        // Contexte : Classe
        // ============================================
        if ($this->classe) {
            session()->forget([
                'teachers_gender_selected',
                'teachers_city_selected',
                'teachers_department_selected',
                'teachers_promotion_selected',
                'teachers_filiar_selected',
                'teachers_serial_selected',
                'teachers_subject_selected',
                'teachers_subject_type_selected',
                'teachers_status_selected',
            ]);

            $this->reset(
                'search', 'gender', 'city', 'department',
                'filiar_id', 'serial_id', 'subject_id',
                'promotionInGroups', 'status', 'subject_type'
            );

            $this->resetPage();
            return;
        }

        // ============================================
        // Contexte : Promotion (objet ou string)
        // ============================================
        if ($this->promotionModel || $this->promotion) {
            session()->forget([
                'teachers_gender_selected',
                'teachers_city_selected',
                'teachers_department_selected',
                'teachers_classe_selected',
                'teachers_filiar_selected',
                'teachers_serial_selected',
                'teachers_subject_selected',
                'teachers_status_selected',
                'teachers_subject_type_selected',
            ]);

            $this->reset(
                'search', 'gender', 'city', 'department',
                'classe_id', 'filiar_id', 'serial_id',
                'subject_id', 'status', 'subject_type'
            );

            $this->resetPage();
            return;
        }

        // ============================================
        // Aucun contexte principal → on reset tout
        // ============================================
        session()->forget([
            'teachers_gender_selected',
            'teachers_city_selected',
            'teachers_department_selected',
            'teachers_promotion_selected',
            'teachers_classe_selected',
            'teachers_filiar_selected',
            'teachers_serial_selected',
            'teachers_subject_selected',
            'teachers_status_selected',
            'teachers_subject_type_selected',
        ]);

        $this->reset(
            'search', 'gender', 'city', 'department',
            'classe_id', 'filiar_id', 'serial_id',
            'subject_id', 'promotionInGroups', 'promotion', 'status', 'subject_type'
        );

        $this->resetPage();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counterh++;
    }

    // ============================================
    // Computed helpers
    // ============================================

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
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
    public function departments(): ?array
    {
        return BeninData::getDepartments();
    }

    #[Computed]
    public function cities(): ?array
    {
        return array_values(array_unique(array_merge(...BeninData::getCities())));
    }

    #[Computed]
    public function genders()
    {
        return config('app.genders');
    }
    
    
    #[Computed]
    public function subject_types()
    {
        return config('app.subject_types');
    }

    #[Computed]
    public function classes()
    {
        if ($this->filiar) {
            return $this->filiar->classes()
                ->where('classes.school_year_id', $this->activeYear->id)
                ->where('classes.is_active', true)
                ->where('classes.is_locked', false)
                ->orderBy('name', 'desc')
                ->get();
        }

        if ($this->serial) {
            return $this->serial->classes()
                ->where('classes.school_year_id', $this->activeYear->id)
                ->where('classes.is_active', true)
                ->where('classes.is_locked', false)
                ->orderBy('name', 'desc')
                ->get();
        }

        if ($this->promotion || $this->promotionModel) {
            $name = is_object($this->promotionModel)
                ? $this->promotionModel->name
                : $this->promotion;

            return Classe::where('is_active', true)
                ->where('school_year_id', $this->activeYear->id)
                ->where('is_locked', false)
                ->whereHas('promotion', function ($q) use ($name) {
                    $q->where(function ($q) use ($name) {
                        $q->where('name', $name)
                          ->orWhere('name', strtolower($name))
                          ->orWhere('name', strtoupper($name));
                    })->where('is_active', true);
                })
                ->orderBy('name', 'desc')
                ->get();
        }

        return collect();
    }

    #[Computed]
    public function subjects()
    {
        if ($this->filiar) {
            return $this->filiar->getFiliarSubjectsOfSchoolYear()
                ->orderBy('name', 'desc')
                ->get();
        }

        if ($this->serial) {
            return $this->serial->getSerialSubjectsOfSchoolYear()
                ->orderBy('name', 'desc')
                ->get();
        }

        return Subject::where('is_active', true)->orderBy('name', 'desc')->get();
    }

    #[Computed]
    public function promotions()
    {
        if ($this->filiar) {
            return array_unique($this->filiar->promotions()->pluck('name')->toArray());
        }

        if ($this->serial) {
            return array_unique($this->serial->promotions()->pluck('name')->toArray());
        }

        return config('app.promotionInGroups');
    }

    // ============================================
    // Updating / Updated hooks
    // ============================================

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingDepartment(): void { $this->resetPage(); }
    public function updatingCity(): void { $this->resetPage(); }
    public function updatingGender(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }
    public function updatingClasseId(): void { $this->resetPage(); }
    public function updatingSubjectId(): void { $this->resetPage(); }
    public function updatingFiliarId(): void { $this->resetPage(); }
    public function updatingSerialId(): void { $this->resetPage(); }
    public function updatingSubjectType(): void { $this->resetPage(); }
    public function updatingPromotionInGroups(): void { $this->resetPage(); }

    public function updatedDepartment(?string $value): void
    {
        session()->put('teachers_department_selected', $value);
    }

    public function updatedCity(?string $value): void
    {
        session()->put('teachers_city_selected', $value);
    }

    public function updatedGender(?string $value): void
    {
        session()->put('teachers_gender_selected', $value);
    }

    public function updatedStatus(?string $value): void
    {
        session()->put('teachers_status_selected', $value);
    }

    public function updatedClasseId(?string $value): void
    {
        session()->put('teachers_classe_selected', $value);
    }

    public function updatedSubjectId(?string $value): void
    {
        session()->put('teachers_subject_selected', $value);
    }

    public function updatedFiliarId(?string $value): void
    {
        session()->put('teachers_filiar_selected', $value);
    }

    public function updatedSerialId(?string $value): void
    {
        session()->put('teachers_serial_selected', $value);
    }

    public function updatedPromotionInGroups(?string $value): void
    {
        session()->put('teachers_promotion_selected', $value);
    }
    public function updatedPromotionSubjectType(?string $value): void
    {
        session()->put('teachers_subject_type_selected', $value);
    }

    // ============================================
    // Récupération des enseignants
    // ============================================

    #[Computed]
    public function teachers()
    {
        return $this->getTeachersData()->paginate($this->perPage);
    }

    public function getTeachersData(): Builder
    {
        $query = Teacher::query()
            ->select('teachers.*')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->with(['user'])
            ->withTrashed();

        // ============================================
        // 1. Contexte principal
        // ============================================

        if ($this->classe) {
            // Enseignants d'une classe précise
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('classe_id', $this->classe->id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at');
            });

            $this->applySecondaryFiltersWhenClasse($query);
        }
        elseif ($this->subject) {
            // Enseignants d'une matière précise
            $query->whereHas('yearlySubjects', function (Builder $q) {
                $q->where('subject_id', $this->subject->id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id);
            });

            // Filtres secondaires possibles
            $this->applySecondaryFiltersWhenSubject($query);
        }
        elseif ($this->filiar) {
            // Enseignants de la filière
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe', function (Builder $qc) {
                      $qc->where('filiar_id', $this->filiar->id)
                         ->where('is_active', true)
                         ->where('school_year_id', $this->activeYear->id);
                  });
            });

            $this->applySecondaryFiltersWhenFiliar($query);
        }
        elseif ($this->serial) {
            // Enseignants de la série
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe', function (Builder $qc) {
                      $qc->where('serial_id', $this->serial->id)
                         ->where('is_active', true)
                         ->where('school_year_id', $this->activeYear->id);
                  });
            });

            $this->applySecondaryFiltersWhenSerial($query);
        }
        elseif ($this->promotionModel || $this->promotion) {
            $promotionName = is_object($this->promotionModel)
                ? $this->promotionModel->name
                : $this->promotion;

            $query->whereHas('classeSubjects', function (Builder $q) use ($promotionName) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe.promotion', function (Builder $qp) use ($promotionName) {
                      $qp->where(function ($q) use ($promotionName) {
                          $q->where('name', $promotionName)
                            ->orWhere('name', strtolower($promotionName))
                            ->orWhere('name', strtoupper($promotionName));
                      })->where('is_active', true);
                  });
            });

            $this->applySecondaryFiltersWhenPromotion($query);
        }

        // ============================================
        // 2. Filtres transversaux
        // ============================================

        // Recherche (IMPORTANT : regrouper les orWhere)
        $query->when($this->search, function (Builder $query) {
            $search = $this->search;

            $query->where(function (Builder $q) use ($search) {
                $q->where('teachers.identifiant', 'like', "%{$search}%")
                  ->orWhereHas('user', function (Builder $qu) use ($search) {
                      $qu->where('email', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%")
                         ->orWhere('prenames', 'like', "%{$search}%")
                         ->orWhere('contacts', 'like', "%{$search}%")
                         ->orWhere('adresse', 'like', "%{$search}%")
                         ->orWhere('city', 'like', "%{$search}%")
                         ->orWhere('department', 'like', "%{$search}%")
                         ->orWhere('country', 'like', "%{$search}%")
                         ->orWhere('gender', 'like', "%{$search}%")
                         ->orWhere('birth_date', 'like', "%{$search}%")
                         ->orWhere('birth_place', 'like', "%{$search}%")
                         ->orWhere('job_name', 'like', "%{$search}%")
                         ->orWhere('status', 'like', "%{$search}%");
                  });
            });
        });

        // Ville
        $query->when($this->city, function (Builder $query) {
            $query->whereHas('user', fn ($q) => $q->where('city', $this->city));
        });

        // Département
        $query->when($this->department, function (Builder $query) {
            $query->whereHas('user', fn ($q) => $q->where('department', $this->department));
        });

        // Genre
        $query->when($this->gender, function (Builder $query) {
            $query->whereHas('user', function (Builder $q) {
                $q->whereIn('gender', [
                    $this->gender,
                    Str::lower($this->gender),
                    Str::upper($this->gender),
                ]);
            });
        });

        // Statut (à adapter selon tes valeurs exactes)
        $query->when($this->status, function (Builder $q) {
            match ($this->status) {
                'actifs'     => $q->where('teachers.blocked', false),
                'desactives' => $q->where('teachers.blocked', true),
                'corbeille'  => $q->whereNotNull('teachers.deleted_at'),
                default      => null,
            };
        });

        return $query
            ->orderBy('users.name')
            ->orderBy('users.prenames');
    }

    // ============================================
    // Filtres secondaires selon le contexte
    // ============================================

    protected function applySecondaryFiltersWhenClasse(Builder $query): void
    {
        // Matière
        $query->when($this->subject_id, function (Builder $query) {
            $query->whereHas('yearlySubjects', fn ($q) =>
                $q->where('subject_id', $this->subject_id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
            );
        }); 
        
       $query->when($this->subject_type, function (Builder $query) {
            $query->whereHas('yearlySubjects', fn ($q) =>
                $q->whereHas('subject', fn($qs) => 
                    $qs->where('type', $this->subject_type)
                        ->where('is_active', true)
                )
                ->where('school_year_id', $this->activeYear->id)
                ->where('is_active', true)
            );
        });
    } 
    
    
    protected function applySecondaryFiltersWhenSubject(Builder $query): void
    {
        // Classe
        $query->when($this->classe_id, function (Builder $query) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('classe_id', $this->classe_id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
            );
        });

        // Filière
        $query->when($this->filiar_id, function (Builder $query) {
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe', fn ($qc) =>
                      $qc->where('filiar_id', $this->filiar_id)
                         ->where('is_active', true)
                         ->where('school_year_id', $this->activeYear->id)
                  );
            });
        });

        // Série
        $query->when($this->serial_id, function (Builder $query) {
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe', fn ($qc) =>
                      $qc->where('serial_id', $this->serial_id)
                         ->where('is_active', true)
                         ->where('school_year_id', $this->activeYear->id)
                  );
            });
        });

        // Promotion
        $query->when($this->promotionInGroups, function (Builder $query) {
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe.promotion', function (Builder $qp) {
                      $qp->where(function ($q) {
                          $q->where('name', $this->promotionInGroups)
                            ->orWhere('name', strtolower($this->promotionInGroups));
                      })->where('is_active', true);
                  });
            });
        });
    }

    protected function applySecondaryFiltersWhenFiliar(Builder $query): void
    {
        // Matière
        $query->when($this->subject_id, function (Builder $query) {
            $query->whereHas('yearlySubjects', fn ($q) =>
                $q->where('subject_id', $this->subject_id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
            );
        }); 
        
       $query->when($this->subject_type, function (Builder $query) {
            $query->whereHas('yearlySubjects', fn ($q) =>
                $q->whereHas('subject', fn($qs) => 
                    $qs->where('type', $this->subject_type)
                        ->where('is_active', true)
                )
                ->where('school_year_id', $this->activeYear->id)
                ->where('is_active', true)
            );
        });
        // Classe
        $query->when($this->classe_id, function (Builder $query) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('classe_id', $this->classe_id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
            );
        });

        // Série
        $query->when($this->serial_id, function (Builder $query) {
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe', fn ($qc) =>
                      $qc->where('serial_id', $this->serial_id)
                         ->where('is_active', true)
                         ->where('school_year_id', $this->activeYear->id)
                  );
            });
        });

        // Promotion
        $query->when($this->promotionInGroups, function (Builder $query) {
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe.promotion', function (Builder $qp) {
                      $qp->where(function ($q) {
                          $q->where('name', $this->promotionInGroups)
                            ->orWhere('name', strtolower($this->promotionInGroups));
                      })->where('is_active', true);
                  });
            });
        });
    }

    protected function applySecondaryFiltersWhenSerial(Builder $query): void
    {
        // Matière
        $query->when($this->subject_id, function (Builder $query) {
            $query->whereHas('yearlySubjects', fn ($q) =>
                $q->where('subject_id', $this->subject_id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
            );
        });

        $query->when($this->subject_type, function (Builder $query) {
            $query->whereHas('yearlySubjects', fn ($q) =>
                $q->whereHas('subject', fn($qs) => 
                    $qs->where('type', $this->subject_type)
                        ->where('is_active', true)
                )
                ->where('school_year_id', $this->activeYear->id)
                ->where('is_active', true)
            );
        });

        // Classe
        $query->when($this->classe_id, function (Builder $query) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('classe_id', $this->classe_id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
            );
        });

        // Promotion
        $query->when($this->promotionInGroups, function (Builder $query) {
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe.promotion', function (Builder $qp) {
                      $qp->where(function ($q) {
                          $q->where('name', $this->promotionInGroups)
                            ->orWhere('name', strtolower($this->promotionInGroups));
                      })->where('is_active', true);
                  });
            });
        });
    }

    protected function applySecondaryFiltersWhenPromotion(Builder $query): void
    {
        // Matière
        $query->when($this->subject_id, function (Builder $query) {
            $query->whereHas('yearlySubjects', fn ($q) =>
                $q->where('subject_id', $this->subject_id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
            );
        });

       $query->when($this->subject_type, function (Builder $query) {
            $query->whereHas('yearlySubjects', fn ($q) =>
                $q->whereHas('subject', fn($qs) => 
                    $qs->where('type', $this->subject_type)
                        ->where('is_active', true)
                )
                ->where('school_year_id', $this->activeYear->id)
                ->where('is_active', true)
            );
        });
        // Classe
        $query->when($this->classe_id, function (Builder $query) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('classe_id', $this->classe_id)
                  ->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
            );
        });

        // Filière
        $query->when($this->filiar_id, function (Builder $query) {
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe', fn ($qc) =>
                      $qc->where('filiar_id', $this->filiar_id)
                         ->where('is_active', true)
                         ->where('school_year_id', $this->activeYear->id)
                  );
            });
        });

        // Série
        $query->when($this->serial_id, function (Builder $query) {
            $query->whereHas('classeSubjects', function (Builder $q) {
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereNull('ended_at')
                  ->whereHas('classe', fn ($qc) =>
                      $qc->where('serial_id', $this->serial_id)
                         ->where('is_active', true)
                         ->where('school_year_id', $this->activeYear->id)
                  );
            });
        });
    }

    public function render()
    {
        return view('livewire.tenants.components.teachers-lister-component');
    }
}