<?php

namespace App\Livewire\Tenants\Parents;

use App\Events\DataUpdatedEvent;
use App\Models\SchoolYear;
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
#[Title("Edition des relations parents - élèves")]
class ManageParentsStudentsRelationComponent extends Component
{
    use WireUiActions;

    public string $parent_uuid;

    public ?Tutor $tutor = null;

    public string $searchQuery = '';

    public array $searchResults = [];

    /**
     * Sélections en attente de validation, indexées par student_id :
     * [
     *   12 => ['data' => [...], 'parentRelation' => 'pere', 'isPrimaryContact' => false],
     *   ...
     * ]
     */
    public array $pendingSelections = [];

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

    public function mount(string $parent_uuid)
    {
        if (! $parent_uuid) {
            return abort(404);
        }

        $parent = Tutor::withTrashed()->where('uuid', $parent_uuid)->firstOrFail();

        if (! $parent) {
            return abort(404);
        }

        $this->parent_uuid = $parent_uuid;
        $this->tutor = Tutor::where('uuid', $parent_uuid)->firstOrFail();

        $this->restoreDraftFromSession();
    }

    /**
     * Liaisons existantes du parent, avec l'apprenant chargé.
     */
    #[Computed]
    public function linkedRelations()
    {
        return StudentTutorRelation::query()
            ->where('tutor_id', $this->tutor->id)
            ->with('student')
            ->latest()
            ->get();
    }

    protected function linkedStudentIds(): array
    {
        return $this->linkedRelations->pluck('student_id')->toArray();
    }

    /**
     * Déclenché automatiquement à chaque frappe (wire:model.live.debounce).
     */
    public function updatedSearchQuery(): void
    {
        $this->performSearch();
    }

    /**
     * Hook générique : capture toute modification de pendingSelections.*
     * (parentRelation, isPrimaryContact) pour persister en session.
     */
    public function updated($name, $value): void
    {
        if (str_starts_with($name, 'pendingSelections.')) {
            $this->persistDraft();
        }
    }

    /**
     * Recherche simultanée sur matricule, EducMaster, nom et prénoms
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

        $students = Student::query()
            ->where(function ($q) use ($query, $words) {
                $q->where('matricule', 'like', "%{$query}%")
                    ->orWhere('educMaster', 'like', "%{$query}%");

                foreach ($words as $word) {
                    $q->orWhere(function ($sub) use ($word) {
                        $sub->where('name', 'like', "%{$word}%")
                            ->orWhere('prenames', 'like', "%{$word}%");
                    });
                }
            })
            ->limit(15)
            ->get();

        $linkedIds = $this->linkedStudentIds();
        $pendingIds = array_keys($this->pendingSelections);

        $this->searchResults = $students->map( function(Student $student) use ($linkedIds, $pendingIds){

            $classe_name = "Aucune classe (" . $this->activeYear?->slug . ")";

            if($student){
                $classe_rel = $student->currentClasse();

                if($classe_rel){

                    $classe = $student->currentClasse()->classe;

                    $classe_name = $classe->code ? $classe->code : $classe->name . "(" . $this->activeYear?->slug . ")";
                    
                }
            }
            
            return [
                'id' => $student->id,
                'matricule' => $student->matricule,
                'educMaster' => $student->educMaster,
                'fullName' => $student->getFullName(),
                'classe' => $classe_name,
                'already_linked' => in_array($student->id, $linkedIds, true),
                'already_pending' => in_array($student->id, $pendingIds, true),
            ];
        })->toArray();
    }

    /**
     * Ajoute un apprenant au tableau des sélections en attente.
     */
    public function selectStudent(int $studentId): void
    {
        if (in_array($studentId, $this->linkedStudentIds(), true)) {
            $this->notification()->warning(
                'Apprenant déjà lié',
                'Cet apprenant est déjà lié à ce parent.'
            );
            return;
        }

        if (array_key_exists($studentId, $this->pendingSelections)) {
            $this->notification()->info(
                'Déjà en attente',
                'Cet apprenant est déjà dans votre liste en attente de validation.'
            );
            return;
        }

        $student = Student::find($studentId);

        $classe_name = "Aucune classe (" . $this->activeYear?->slug . ")";

        if($student){
            $classe_rel = $student->currentClasse();

            if($classe_rel){

                $classe = $student->currentClasse()->classe;

                $classe_name = $classe->code ? $classe->code : $classe->name . "(" . $this->activeYear?->slug . ")";
                
            }
        }

        if (! $student) {
            $this->notification()->error('Erreur', "L'apprenant sélectionné est introuvable.");
            return;
        }

        $this->pendingSelections[$studentId] = [
            'data' => [
                'id' => $student->id,
                'matricule' => $student->matricule,
                'educMaster' => $student->educMaster,
                'classe' => $classe_name,
                'fullName' => trim($student->getFullName()),
            ],
            'parentRelation' => '',
            'isPrimaryContact' => false,
        ];

        $this->persistDraft();
        $this->performSearch(); // rafraîchit les flags already_pending dans les résultats
    }

    /**
     * Retire une ligne du tableau en attente sans la valider.
     */
    public function removePending(int $studentId): void
    {
        unset($this->pendingSelections[$studentId]);
        $this->persistDraft();
        $this->performSearch();
    }

    /**
     * Valide une seule ligne du tableau.
     */
    public function validateSingle(int $studentId): void
    {
        if (! array_key_exists($studentId, $this->pendingSelections)) {
            return;
        }

        $selection = $this->pendingSelections[$studentId];

        if (empty($selection['parentRelation']) || ! array_key_exists($selection['parentRelation'], self::RELATION_TYPES)) {
            $this->notification()->error(
                'Lien de parenté requis',
                "Veuillez choisir un lien de parenté pour {$selection['data']['fullName']} avant de valider."
            );
            return;
        }

        $this->createRelation($studentId, $selection);

        unset($this->pendingSelections[$studentId]);
        $this->persistDraft();
        unset($this->linkedRelations);

        $this->notification()->success(
            'Liaison effectuée',
            "{$selection['data']['fullName']} a été lié avec succès."
        );
    }

    /**
     * Valide toutes les lignes en attente d'un coup.
     */
    public function validateAll(): void
    {
        if (empty($this->pendingSelections)) {
            $this->notification()->info('Aucune sélection', "Il n'y a aucun apprenant en attente de validation.");
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

        foreach ($this->pendingSelections as $studentId => $selection) {
            $this->createRelation((int) $studentId, $selection);
            $count++;
        }

        $this->pendingSelections = [];
        $this->persistDraft();
        unset($this->linkedRelations);

        $this->notification()->success(
            'Liaisons effectuées',
            "{$count} apprenant(s) ont été liés avec succès."
        );
    }

    /**
     * Création effective de la relation, avec garde anti-doublon.
     */
    protected function createRelation(int $studentId, array $selection): void
    {
        $exists = StudentTutorRelation::query()
            ->where('tutor_id', $this->tutor->id)
            ->where('student_id', $studentId)
            ->exists();

        if ($exists) {
            return;
        }

        StudentTutorRelation::create([
            'student_id' => $studentId,
            'tutor_id' => $this->tutor->id,
            'parent_relation' => $selection['parentRelation'],
            'is_primary_contact' => $selection['isPrimaryContact'],
            'is_active' => true,
            'locked' => false,
        ]);

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    /**
     * Clé de session unique par parent, pour ne pas mélanger les drafts
     * de différents parents.
     */
    protected function sessionKey(): string
    {
        return "parent_link_draft_{$this->parent_uuid}";
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

        $linkedIds = $this->linkedStudentIds();

        $this->pendingSelections = collect($draft)
            // Ne garde que les entrées ayant la structure attendue (nouveau format).
            ->filter(function ($selection, $studentId) {
                return is_int($studentId)
                    && is_array($selection)
                    && isset($selection['data'])
                    && is_array($selection['data'])
                    && isset($selection['data']['id'], $selection['data']['fullName']);
            })
            // Retire celles déjà liées entre-temps.
            ->reject(fn ($selection, $studentId) => in_array((int) $studentId, $linkedIds, true))
            ->toArray();

        if (empty($this->pendingSelections)) {
            $this->clearDraft();
        } else {
            $this->persistDraft();
        }
    }

    public function removeRelation(int $studentId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Dissocier cet apprenant de cet parent ?',
            'text'               => "Le parent " . $this->tutor->getFullName() . " n'aura plus accès aux informations de cet apprenant",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, dissocier',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToUnlinkedStudentToTutor',
            'onConfirmedParams'  => ['studentId' => $studentId],
        ]);
    }


    #[On("ConfirmToUnlinkedStudentToTutor")]
    public function onConfirmToUnlinkedStudentToTutor(int $studentId)
    {
        $exists = StudentTutorRelation::query()
            ->where('tutor_id', $this->tutor->id)
            ->where('student_id', $studentId)
            ->first();

        if (!$exists) {
             $this->notification()->error(
                'RELATION INTROUVABLE',
                "Aucune relation trouvée correspondant"
            );
            return;
        }

        $del = $exists->delete();

        if($del){

            $this->notification()->success(
                'RELATION SUPPRIMEE',
            );
        }


        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function render()
    {
        return view('livewire.tenants.parents.manage-parents-students-relation-component', [
            'relationTypes' => self::RELATION_TYPES,
        ]);
    }
}