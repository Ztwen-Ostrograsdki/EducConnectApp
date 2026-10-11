<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\SubjectAnimationTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait SubjectAnimationTimesActions
{
    use WireUiActions;

    public $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
    {
        $this->counter++;
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    /**
     * Règles de validation communes pour un créneau AP.
     */
    protected function apTimeValidationRules(?int $ignoreId = null): array
    {
        return [
            'subject_id'   => ['required', 'integer', 'exists:tenant.subjects,id'],
            'day_of_week'  => ['required', 'integer', 'min:1', 'max:6'],
            'starts_at'    => ['required', 'date_format:H:i', 'after_or_equal:07:00', 'before_or_equal:19:00'],
            'ends_at'      => ['required', 'date_format:H:i', 'after:starts_at', 'before_or_equal:19:00'],
            'label'        => ['nullable', 'string', 'max:150'],
            'notes'        => ['nullable', 'string', 'max:5000'],
            'is_active'    => ['nullable', 'boolean'],
        ];
    }

    protected function apTimeValidationMessages(): array
    {
        return [
            'subject_id.required'        => 'La matière est obligatoire.',
            'subject_id.exists'          => 'La matière sélectionnée est invalide.',
            'day_of_week.required'       => 'Le jour est obligatoire.',
            'day_of_week.min'            => 'Le jour doit être un jour ouvré (lundi à samedi).',
            'day_of_week.max'            => 'Le jour doit être un jour ouvré (lundi à samedi).',
            'starts_at.required'         => "L'heure de début est obligatoire.",
            'starts_at.date_format'      => "L'heure de début doit être au format HH:MM.",
            'starts_at.after_or_equal'   => "L'heure de début doit être au plus tôt à 07h00.",
            'starts_at.before_or_equal'  => "L'heure de début doit être au plus tard à 19h00.",
            'ends_at.required'           => "L'heure de fin est obligatoire.",
            'ends_at.date_format'        => "L'heure de fin doit être au format HH:MM.",
            'ends_at.after'              => "L'heure de fin doit être postérieure à l'heure de début.",
            'ends_at.before_or_equal'    => "L'heure de fin doit être au plus tard à 19h00.",
            'label.max'                  => 'Le libellé ne peut pas dépasser 150 caractères.',
            'notes.max'                  => 'Les notes ne peuvent pas dépasser 5000 caractères.',
        ];
    }

    /**
     * Initialise les propriétés du formulaire à partir d'un enregistrement existant.
     */
    public function initAPTimeData(int $id): void
    {
        $apTime = SubjectAnimationTime::query()->find($id);

        if (!$apTime) {
            $this->notification()->error(
                title: 'Créneau introuvable',
                description: 'Ce créneau AP n’existe pas ou a été supprimé.',
            );
            return;
        }

        $this->ap_time_id  = $apTime->id;
        $this->subject_id  = $apTime->subject_id;
        $this->day_of_week = $apTime->day_of_week;
        $this->starts_at   = substr((string) $apTime->starts_at, 0, 5); // HH:MM
        $this->ends_at     = substr((string) $apTime->ends_at, 0, 5);
        $this->label       = $apTime->label ?? 'AP';
        $this->notes       = $apTime->notes ?? '';
        $this->is_active   = (bool) $apTime->is_active;

        $this->showAPTimeForm = true;
    }

    /**
     * Réinitialise les champs du formulaire (sans toucher à la visibilité).
     */
    protected function clearAPTimeFormFields(): void
    {
        $this->ap_time_id  = null;
        $this->day_of_week = 1;
        $this->starts_at   = '08:00';
        $this->ends_at     = '10:00';
        $this->label       = 'AP';
        $this->notes       = 'Animation Pédagogique Hebdomadaire (AP-H)';
        $this->is_active   = true;

        // Conserve subject_id / subject si on est dans le contexte d'une matière
        if (!isset($this->subject) || !$this->subject) {
            $this->subject_id = null;
        }

        $this->resetValidation();
    }

    /**
     * Ferme et réinitialise le formulaire de créneau AP.
     */
    public function resetAPTimeForm(): void
    {
        $this->clearAPTimeFormFields();
        $this->showAPTimeForm = false;
    }

    /**
     * Ouvre le formulaire en mode création.
     */
    public function openCreateAPTimeForm(): void
    {
        $this->clearAPTimeFormFields();
        $this->showAPTimeForm = true;
    }

    /**
     * Enregistre (création ou mise à jour) un créneau AP.
     */
    public function saveAPTime(): void
    {
        $data = $this->validate(
            $this->apTimeValidationRules($this->ap_time_id),
            $this->apTimeValidationMessages()
        );

        $year = $this->activeYear;

        if (!$year) {
            $this->notification()->error(
                title: 'Année scolaire absente',
                description: "Aucune année scolaire active n'a été trouvée.",
            );
            return;
        }

        if ($year->is_closed) {
            $this->notification()->error(
                title: 'Année clôturée',
                description: "L'année scolaire {$year->slug} est clôturée. Aucune modification n'est possible.",
            );
            throw ValidationException::withMessages([
                'day_of_week' => 'Cette année scolaire est clôturée.',
            ]);
        }

        $subject = Subject::query()->find($data['subject_id']);

        if (!$subject || !$subject->is_active) {
            $this->notification()->error(
                title: 'Matière invalide',
                description: 'La matière sélectionnée est introuvable ou inactive.',
            );
            throw ValidationException::withMessages([
                'subject_id' => 'Matière invalide ou inactive.',
            ]);
        }

        // Contrôle de chevauchement sur le même jour / même matière / même année
        $overlap = SubjectAnimationTime::query()
            ->where('subject_id', $subject->id)
            ->where('school_year_id', $year->id)
            ->where('day_of_week', $data['day_of_week'])
            ->when($this->ap_time_id, fn ($q) => $q->where('id', '!=', $this->ap_time_id))
            ->where(function ($q) use ($data) {
                $q->where(function ($q2) use ($data) {
                    // Nouveau créneau commence pendant un existant
                    $q2->where('starts_at', '<=', $data['starts_at'])
                       ->where('ends_at', '>', $data['starts_at']);
                })->orWhere(function ($q2) use ($data) {
                    // Nouveau créneau finit pendant un existant
                    $q2->where('starts_at', '<', $data['ends_at'])
                       ->where('ends_at', '>=', $data['ends_at']);
                })->orWhere(function ($q2) use ($data) {
                    // Nouveau créneau englobe un existant
                    $q2->where('starts_at', '>=', $data['starts_at'])
                       ->where('ends_at', '<=', $data['ends_at']);
                });
            })
            ->exists();

        if ($overlap) {
            $this->notification()->error(
                title: 'Chevauchement détecté',
                description: 'Un créneau existe déjà pour cette matière sur ce jour et ce créneau horaire.',
            );
            throw ValidationException::withMessages([
                'starts_at' => 'Ce créneau chevauche un créneau existant.',
                'ends_at'   => 'Ce créneau chevauche un créneau existant.',
            ]);
        }

        try {
            $apTime = DB::connection('tenant')->transaction(function () use ($data, $year, $subject) {
                $payload = [
                    'subject_id'     => $subject->id,
                    'school_year_id' => $year->id,
                    'day_of_week'    => (int) $data['day_of_week'],
                    'starts_at'      => $data['starts_at'],
                    'ends_at'        => $data['ends_at'],
                    'label'          => $data['label'] ?: 'AP',
                    'notes'          => $data['notes'] ?? null,
                    'is_active'      => $data['is_active'] ?? true,
                ];

                if ($this->ap_time_id) {
                    $apTime = SubjectAnimationTime::query()
                        ->lockForUpdate()
                        ->findOrFail($this->ap_time_id);

                    $apTime->update($payload);

                    return $apTime->fresh();
                }

                return SubjectAnimationTime::query()->create($payload);
            });

            $wasUpdate = (bool) $this->ap_time_id;
            $this->ap_time_id = $apTime->id;

            $this->notification()->success(
                title: $wasUpdate ? 'Créneau mis à jour' : 'Créneau créé',
                description: "Le créneau AP ({$apTime->day_name} {$apTime->start} – {$apTime->end}) a été enregistré avec succès.",
            );

            broadcast(new DataUpdatedEvent(tenant('id')));

            $this->resetAPTimeForm();
            $this->showAPTimeForm = false;

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Erreur d’enregistrement',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    /**
     * Demande de confirmation avant suppression d'un créneau.
     */
    public function deleteAPTime(int $apTimeId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Supprimer ce créneau AP ?',
            'text'               => 'Cette action est définitive. Le créneau sera définitivement supprimé.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteAPTime',
            'onConfirmedParams'  => ['apTimeId' => $apTimeId],
        ]);
    }

    #[On('ConfirmToDeleteAPTime')]
    public function onConfirmToDeleteAPTime(int $apTimeId): void
    {
        $apTime = SubjectAnimationTime::query()->find($apTimeId);

        if (!$apTime) {
            $this->notification()->error(title: 'Créneau introuvable');
            return;
        }

        $year = $this->activeYear;
        if ($year && $year->is_closed) {
            $this->notification()->error(
                title: 'Année clôturée',
                description: 'Impossible de supprimer un créneau sur une année scolaire clôturée.',
            );
            return;
        }

        try {
            $label = "{$apTime->day_name} {$apTime->start} – {$apTime->end}";
            $done  = $apTime->delete();

            if ($done) {
                $this->notification()->success(
                    title: 'Créneau supprimé',
                    description: "Le créneau {$label} a été supprimé.",
                );
                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: 'Suppression échouée',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Suppression échouée',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    /**
     * Demande de confirmation avant désactivation.
     */
    public function deactivateAPTime(int $apTimeId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Désactiver ce créneau AP ?',
            'text'               => 'Le créneau ne sera plus pris en compte dans les emplois du temps.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, désactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeactivateAPTime',
            'onConfirmedParams'  => ['apTimeId' => $apTimeId],
        ]);
    }

    #[On('ConfirmToDeactivateAPTime')]
    public function onConfirmToDeactivateAPTime(int $apTimeId): void
    {
        $apTime = SubjectAnimationTime::query()->find($apTimeId);

        if (!$apTime) {
            $this->notification()->error(title: 'Créneau introuvable');
            return;
        }

        try {
            $done = $apTime->update(['is_active' => false]);

            if ($done) {
                $this->notification()->success(
                    title: 'Créneau désactivé',
                    description: "Le créneau {$apTime->day_name} {$apTime->start} – {$apTime->end} a été désactivé.",
                );
                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: 'Désactivation échouée',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Désactivation échouée',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    /**
     * Demande de confirmation avant réactivation.
     */
    public function activateAPTime(int $apTimeId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Réactiver ce créneau AP ?',
            'text'               => 'Le créneau sera de nouveau pris en compte dans les emplois du temps.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToActivateAPTime',
            'onConfirmedParams'  => ['apTimeId' => $apTimeId],
        ]);
    }

    #[On('ConfirmToActivateAPTime')]
    public function onConfirmToActivateAPTime(int $apTimeId): void
    {
        $apTime = SubjectAnimationTime::query()->find($apTimeId);

        if (!$apTime) {
            $this->notification()->error(title: 'Créneau introuvable');
            return;
        }

        try {
            $done = $apTime->update(['is_active' => true]);

            if ($done) {
                $this->notification()->success(
                    title: 'Créneau réactivé',
                    description: "Le créneau {$apTime->day_name} {$apTime->start} – {$apTime->end} a été réactivé.",
                );
                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: 'Réactivation échouée',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Réactivation échouée',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    /**
     * Liste des créneaux AP filtrés (pour affichage dans le composant).
     * Le composant peut surcharger ou utiliser directement cette query.
     */
    protected function apTimesQuery()
    {
        $year = $this->activeYear;

        return SubjectAnimationTime::query()
            ->with(['subject'])
            ->when($year, fn ($q) => $q->where('school_year_id', $year->id))
            ->when(
                isset($this->subject_id) && $this->subject_id,
                fn ($q) => $q->where('subject_id', $this->subject_id)
            )
            ->when(
                isset($this->is_active_filter) && $this->is_active_filter === 'actives',
                fn ($q) => $q->where('is_active', true)
            )
            ->when(
                isset($this->is_active_filter) && $this->is_active_filter === 'inactives',
                fn ($q) => $q->where('is_active', false)
            )
            ->when(
                isset($this->search) && filled($this->search),
                function ($q) {
                    $term = '%' . $this->search . '%';
                    $q->where(function ($q2) use ($term) {
                        $q2->where('label', 'like', $term)
                           ->orWhere('notes', 'like', $term)
                           ->orWhereHas('subject', fn ($sq) => $sq->where('name', 'like', $term)->orWhere('code', 'like', $term));
                    });
                }
            )
            ->orderBy('day_of_week')
            ->orderBy('starts_at');
    }
}
