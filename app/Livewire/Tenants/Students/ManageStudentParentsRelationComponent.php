<?php

namespace App\Livewire\Tenants\Students;

use App\Models\Student;
use App\Models\StudentTutorRelation;
use App\Models\Tutor;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Edition des parents / tuteurs de l'apprenant")]
class ManageStudentParentsRelationComponent extends Component
{
    use WireUiActions;

    public string $student_uuid;

    public ?Student $student = null;

    public string $searchQuery = '';

    public array $searchResults = [];

    /**
     * Sélections en attente de validation, indexées par tutor_id :
     * [
     *   12 => ['data' => [...], 'parentRelation' => 'pere', 'isPrimaryContact' => false],
     *   ...
     * ]
     */
    public array $pendingSelections = [];

    /**
     * Id de la relation actuellement en édition (null = aucune).
     */
    public ?int $editingRelationId = null;

    public string $editParentRelation = '';

    public bool $editIsPrimaryContact = false;

    public bool $editIsActive = true;

    public const RELATION_TYPES = [
        'Père' => 'Père',
        'Mère' => 'Mère',
        'Oncle' => 'Oncle',
        'Tante' => 'Tante',
        'Frère' => 'Frère',
        'Sœur' => 'Sœur',
        'Grand-père' => 'Grand-père',
        'Grand-mère' => 'Grand-mère',
        'Tuteur légal' => 'Tuteur légal',
        'Autre' => 'Autre',
    ];

    public function mount(string $student_uuid)
    {
        if (! $student_uuid) {
            return abort(404);
        }

        $student = Student::withTrashed()->where('uuid', $student_uuid)->firstOrFail();

        if (! $student) {
            return abort(404);
        }

        $this->student_uuid = $student_uuid;
        $this->student = Student::where('uuid', $student_uuid)->firstOrFail();

        $this->restoreDraftFromSession();
    }

    /**
     * Relations existantes de l'apprenant, avec le tuteur chargé.
     */
    #[Computed]
    public function linkedRelations()
    {
        return StudentTutorRelation::query()
            ->where('student_id', $this->student->id)
            ->with('tutor')
            ->latest()
            ->get();
    }

    protected function linkedTutorIds(): array
    {
        return $this->linkedRelations->pluck('tutor_id')->toArray();
    }

    public function updatedSearchQuery(): void
    {
        $this->performSearch();
    }

    /**
     * Capture générique de toute modification de pendingSelections.*
     * pour persister automatiquement en session.
     */
    public function updated($name, $value): void
    {
        if (str_starts_with($name, 'pendingSelections.')) {
            $this->persistDraft();
        }
    }

    /**
     * Recherche simultanée sur nom, prénoms, email et téléphone
     * (aucune sélection de type de recherche requise).
     */
    protected function performSearch(): void
    {
        $query = trim($this->searchQuery);

        if (mb_strlen($query) < 2) {
            $this->searchResults = [];
            return;
        }

        $words = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);

        $tutors = Tutor::query()
            ->where(function ($qi) use ($query, $words) {
                $qi->whereHas('user', function($q) use($query, $words){
                    $q->where('email', 'like', "%{$query}%")
                        ->orWhere('contacts', 'like', "%{$query}%");

                    foreach ($words as $word) {
                        $q->orWhere(function ($sub) use ($word) {
                            $sub->where('name', 'like', "%{$word}%")
                                ->orWhere('prenames', 'like', "%{$word}%");
                        });
                    }

                });
            })
            ->limit(15)
            ->get();

        $linkedIds = $this->linkedTutorIds();
        $pendingIds = array_keys($this->pendingSelections);

        $this->searchResults = $tutors->map(fn (Tutor $tutor) => [
            'id' => $tutor->id,
            'fullName' => $tutor->getFullName(),
            'email' => $tutor->user->email,
            'phone' => $tutor->user->contacts,
            'already_linked' => in_array($tutor->id, $linkedIds, true),
            'already_pending' => in_array($tutor->id, $pendingIds, true),
        ])->toArray();
    }

    /**
     * Ajoute un tuteur au tableau des sélections en attente.
     */
    public function selectTutor(int $tutorId): void
    {
        if (in_array($tutorId, $this->linkedTutorIds(), true)) {
            $this->notification()->warning(
                'Parent déjà lié',
                'Ce parent est déjà lié à cet apprenant.'
            );
            return;
        }

        if (array_key_exists($tutorId, $this->pendingSelections)) {
            $this->notification()->info(
                'Déjà en attente',
                'Ce parent est déjà dans votre liste en attente de validation.'
            );
            return;
        }

        $tutor = Tutor::find($tutorId);

        if (! $tutor) {
            $this->notification()->error('Erreur', 'Le parent sélectionné est introuvable.');
            return;
        }

        $this->pendingSelections[$tutorId] = [
            'data' => [
                'id' => $tutor->id,
                'fullName' => $tutor->getFullName(),
                'email' => $tutor->user->email,
                'phone' => $tutor->user->contacts,
            ],
            'parentRelation' => '',
            'isPrimaryContact' => false,
        ];

        $this->persistDraft();
        $this->performSearch();
    }

    public function removePending(int $tutorId): void
    {
        unset($this->pendingSelections[$tutorId]);
        $this->persistDraft();
        $this->performSearch();
    }

    public function validateSingle(int $tutorId): void
    {
        if (! array_key_exists($tutorId, $this->pendingSelections)) {
            return;
        }

        $selection = $this->pendingSelections[$tutorId];

        if (empty($selection['parentRelation']) || ! array_key_exists($selection['parentRelation'], self::RELATION_TYPES)) {
            $this->notification()->error(
                'Lien de parenté requis',
                "Veuillez choisir un lien de parenté pour {$selection['data']['fullName']} avant de valider."
            );
            return;
        }

        $this->createRelation($tutorId, $selection);

        unset($this->pendingSelections[$tutorId]);
        $this->persistDraft();
        unset($this->linkedRelations);

        $this->notification()->success(
            'Liaison effectuée',
            "{$selection['data']['fullName']} a été lié avec succès."
        );
    }

    public function validateAll(): void
    {
        if (empty($this->pendingSelections)) {
            $this->notification()->info('Aucune sélection', "Il n'y a aucun parent en attente de validation.");
            return;
        }

        $missing = collect($this->pendingSelections)
            ->filter(fn ($s) => empty($s['parentRelation']) || ! array_key_exists($s['parentRelation'], self::RELATION_TYPES))
            ->map(fn ($s) => $s['data']['fullName']);

        if ($missing->isNotEmpty()) {
            $this->notification()->error(
                'Liens de parenté manquants',
                'Veuillez renseigner le lien de parenté pour : '.$missing->implode(', ')
            );
            return;
        }

        $count = 0;

        foreach ($this->pendingSelections as $tutorId => $selection) {
            $this->createRelation((int) $tutorId, $selection);
            $count++;
        }

        $this->pendingSelections = [];
        $this->persistDraft();
        unset($this->linkedRelations);

        $this->notification()->success(
            'Liaisons effectuées',
            "{$count} parent(s) ont été liés avec succès."
        );
    }

    protected function createRelation(int $tutorId, array $selection): void
    {
        $exists = StudentTutorRelation::query()
            ->where('student_id', $this->student->id)
            ->where('tutor_id', $tutorId)
            ->exists();

        if ($exists) {
            return;
        }

        StudentTutorRelation::create([
            'student_id' => $this->student->id,
            'tutor_id' => $tutorId,
            'parent_relation' => $selection['parentRelation'],
            'is_primary_contact' => $selection['isPrimaryContact'],
            'is_active' => true,
            'locked' => false,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Edition d'une relation existante
    |--------------------------------------------------------------------------
    */

    public function startEdit(int $relationId): void
    {
        $relation = $this->linkedRelations->firstWhere('id', $relationId);

        if (! $relation) {
            $this->notification()->error('Erreur', 'Relation introuvable.');
            return;
        }

        if ($relation->locked) {
            $this->notification()->warning('Relation verrouillée', 'Cette relation est verrouillée et ne peut pas être modifiée.');
            return;
        }

        $this->editingRelationId = $relation->id;
        $this->editParentRelation = $relation->parent_relation;
        $this->editIsPrimaryContact = $relation->is_primary_contact;
        $this->editIsActive = $relation->is_active;
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingRelationId', 'editParentRelation', 'editIsPrimaryContact', 'editIsActive']);
    }

    protected function editRules(): array
    {
        return [
            'editParentRelation' => 'required|string|in:'.implode(',', array_keys(self::RELATION_TYPES)),
            'editIsPrimaryContact' => 'boolean',
            'editIsActive' => 'boolean',
        ];
    }

    public function updateRelation(): void
    {
        if (! $this->editingRelationId) {
            return;
        }

        $this->validate($this->editRules(), [], [
            'editParentRelation' => 'lien de parenté',
        ]);

        $relation = StudentTutorRelation::find($this->editingRelationId);

        if (! $relation) {
            $this->notification()->error('Erreur', 'Relation introuvable.');
            $this->cancelEdit();
            return;
        }

        if ($relation->locked) {
            $this->notification()->warning('Relation verrouillée', 'Cette relation est verrouillée et ne peut pas être modifiée.');
            $this->cancelEdit();
            return;
        }

        $relation->update([
            'parent_relation' => $this->editParentRelation,
            'is_primary_contact' => $this->editIsPrimaryContact,
            'is_active' => $this->editIsActive,
        ]);

        unset($this->linkedRelations);
        $this->cancelEdit();

        $this->notification()->success('Relation mise à jour', 'Les informations ont été enregistrées avec succès.');
    }

    /*
    |--------------------------------------------------------------------------
    | Suppression / déliaison d'une relation
    |--------------------------------------------------------------------------
    */

    /**
     * Demande de confirmation avant suppression (WireUI dialog).
     */
    public function confirmDeleteRelation(int $relationId): void
    {
        $relation = $this->linkedRelations->firstWhere('id', $relationId);

        if (! $relation) {
            $this->notification()->error('Erreur', 'Relation introuvable.');
            return;
        }

        $this->dialog()->confirm([
            'title' => 'Délier ce parent ?',
            'description' => "Voulez-vous vraiment délier {$relation->tutor->getFullName()} de cet apprenant ?",
            'icon' => 'warning',
            'accept' => [
                'label' => 'Oui, délier',
                'method' => 'deleteRelationConfirmed',
                'params' => $relationId,
            ],
            'reject' => [
                'label' => 'Annuler',
            ],
        ]);
    }

    /**
     * Exécutée après confirmation du dialog WireUI.
     * Règle métier : hard delete si créée il y a moins de 2 semaines,
     * sinon soft-end (is_active = false).
     */
    public function deleteRelationConfirmed(int $relationId): void
    {
        $relation = StudentTutorRelation::find($relationId);

        if (! $relation) {
            $this->notification()->error('Erreur', 'Relation introuvable.');
            return;
        }

        $tutorName = $relation->tutor->getFullName();

        if ($relation->created_at->greaterThanOrEqualTo(now()->subWeeks(2))) {
            $relation->delete();
            $message = "{$tutorName} a été délié définitivement de l'apprenant.";
        } else {
            $relation->update(['is_active' => false]);
            $message = "{$tutorName} a été désactivé (l'historique de la relation est conservé).";
        }

        unset($this->linkedRelations);

        $this->notification()->success('Parent délié', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | Persistance en session
    |--------------------------------------------------------------------------
    */

    protected function sessionKey(): string
    {
        return "student_link_draft_{$this->student_uuid}";
    }

    protected function persistDraft(): void
    {
        session()->put($this->sessionKey(), $this->pendingSelections);
    }

    protected function clearDraft(): void
    {
        session()->forget($this->sessionKey());
    }

    protected function restoreDraftFromSession(): void
    {
        $draft = session()->get($this->sessionKey(), []);

        if (empty($draft) || ! is_array($draft)) {
            $this->clearDraft();
            return;
        }

        $linkedIds = $this->linkedTutorIds();

        $this->pendingSelections = collect($draft)
            // Ne garde que les entrées ayant la structure attendue.
            ->filter(function ($selection, $tutorId) {
                return is_int($tutorId)
                    && is_array($selection)
                    && isset($selection['data'])
                    && is_array($selection['data'])
                    && isset($selection['data']['id'], $selection['data']['fullName']);
            })
            // Retire celles déjà liées entre-temps.
            ->reject(fn ($selection, $tutorId) => in_array((int) $tutorId, $linkedIds, true))
            ->toArray();

        if (empty($this->pendingSelections)) {
            $this->clearDraft();
        } else {
            $this->persistDraft();
        }
    }

    public function render()
    {
        return view('livewire.tenants.students.manage-student-parents-relation-component', [
            'relationTypes' => self::RELATION_TYPES,
        ]);
    }
}