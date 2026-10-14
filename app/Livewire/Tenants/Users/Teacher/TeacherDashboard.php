<?php

namespace App\Livewire\Tenants\Users\Teacher;

use App\Helpers\Support\TenantStorage;
use App\Jobs\JobToGeneratePrintableTimePlans;
use App\Livewire\Tenants\ActionsTraits\TimePlanActions;
use App\Models\GeneratedDocument;
use App\Models\Mark;
use App\Models\SchoolYear;
use App\Models\TimePlanSlot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;


class TeacherDashboard extends Component
{
    use WireUiActions, TimePlanActions;

    public $counter = 0;


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }
    
    #[On('TeacherWasBlockedLiveEvent')]
    public function teacherBlocked()
    {
        $this->notification()->send([
            'icon'        => 'warning',
            'title'       => "Votre compte enseignant a été bloqué",
            'timeout' => 0,
        ]);
        
        $this->counter++;

        return $this->redirect(route('tenant.my.profil'));
    }


    #[Computed]
    public function teacher()
    {
        return auth('tenant')->user()->teacher;
    } 
    
    
    #[Computed]
    public function user()
    {
        return auth('tenant')->user();
    }

    #[Computed]
    public function classes()
    {
        return $this->teacher?->getTeacherClassesWithSubjectsForThisSchoolYear();
    }
    
    
    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }
    
    
    #[Computed]
    public function teachersMarksCount(): ?array
    {
        $marks = [];

        foreach($this->classes as $rel){

            $counts = Mark::query()
                ->where('school_year_id', $this->activeYear->id)
                ->where('period', $this->activeYear->active_period)
                ->where('classe_id', $rel->classe_id)
                ->where('teacher_id', $this->teacher->id)
                ->select('subject_id', DB::raw('COUNT(*) as marks_count'))
                ->groupBy('subject_id')
                ->pluck('marks_count', 'subject_id')->toArray();

            $marks[$rel->classe_id] = $counts;
        }

        return $marks;
    }


    /**
     * Créneaux de l’enseignant pour l’année active (cours + heures d’AP des matières).
     */
    #[Computed]
    public function teacherSlots(): Collection
    {
        if (!$this->teacher || !$this->activeYear) {
            return collect();
        }

        return $this->teacherSlotsWithApFor($this->teacher->id, $this->activeYear->id);
    }

    /** Créneaux groupés par jour. */
    #[Computed]
    public function teacherSlotsByDay(): Collection
    {
        return $this->slotsByDayFromSlots($this->teacherSlots);
    }

    /**
     * Affectations pour le formulaire : uniquement les classes / matières
     * où cet enseignant intervient (ClasseSubjectOfSchoolYear).
     */
    #[Computed]
    public function assignments(): Collection
    {
        if (!$this->teacher || !$this->activeYear) {
            return collect();
        }

        return $this->assignmentsForTeacher($this->teacher->id, $this->activeYear->id);
    }

    public function slotFragmentAtTeacher(int $day, string $rowStart): ?object
    {
        $daySlots = $this->teacherSlotsByDay[$day] ?? collect();

        $starting = $daySlots->first(
            fn (object $slot) => $this->normalizeTime((string) $slot->starts_at) === $rowStart
        );

        if ($starting) {
            return $starting;
        }

        $idx = $this->scheduleHourIndex($rowStart);
        if ($idx <= 0) {
            return null;
        }

        $rows = $this->scheduleRows();
        $prev = $rows[$idx - 1] ?? null;
        if (!$prev || ($prev['type'] ?? '') !== 'break') {
            return null;
        }

        return $daySlots->first(function (object $slot) use ($rowStart) {
            $slotStart = $this->normalizeTime((string) $slot->starts_at);
            $slotEnd = $this->normalizeTime((string) $slot->ends_at);

            return $slotStart < $rowStart && $slotEnd > $rowStart;
        });
    }

    public function isCellCoveredBySpanTeacher(int $day, string $rowStart): bool
    {
        $idx = $this->scheduleHourIndex($rowStart);
        if ($idx < 0) {
            return false;
        }

        $rows = $this->scheduleRows();
        $daySlots = $this->teacherSlotsByDay[$day] ?? collect();

        foreach ($daySlots as $slot) {
            $slotStart = $this->normalizeTime((string) $slot->starts_at);
            $slotEnd = $this->normalizeTime((string) $slot->ends_at);

            if (!($slotStart < $rowStart && $slotEnd > $rowStart)) {
                continue;
            }

            $fragmentStartIdx = $this->scheduleHourIndex($slotStart);
            if ($fragmentStartIdx < 0) {
                foreach ($rows as $i => $row) {
                    if (($row['type'] ?? '') === 'hour' && $row['start'] < $slotEnd && $row['end'] > $slotStart) {
                        $fragmentStartIdx = $i;
                        break;
                    }
                }
            }

            if ($fragmentStartIdx < 0) {
                continue;
            }

            $blockedByBreak = false;
            for ($i = $fragmentStartIdx; $i < $idx; $i++) {
                if (($rows[$i]['type'] ?? '') === 'break') {
                    $blockedByBreak = true;
                    $fragmentStartIdx = $i + 1;
                }
            }

            if ($blockedByBreak) {
                if (
                    $fragmentStartIdx < $idx
                    && ($rows[$fragmentStartIdx]['type'] ?? '') === 'hour'
                    && $rows[$fragmentStartIdx]['start'] < $rowStart
                ) {
                    return true;
                }
                continue;
            }

            return true;
        }

        return false;
    }


    /**
     * Génère le PDF de l’emploi du temps de l’enseignant connecté
     * (sans passer par le manager d’impression).
     */
    public function downloadTimePlan(): void
    {
        $teacher = $this->teacher;
        $year = $this->activeYear;

        if (!$teacher) {
            $this->notification()->error(
                title: 'Profil enseignant introuvable',
                description: 'Impossible de générer l’emploi du temps.',
            );
            return;
        }

        if (!$year) {
            $this->notification()->error(
                title: 'Année scolaire absente',
                description: 'Aucune année scolaire active n’a été trouvée.',
            );
            return;
        }

        if ($this->teacherSlots->isEmpty()) {
            $this->notification()->info(
                title: 'Aucun créneau',
                description: 'Votre emploi du temps ne contient aucun créneau pour l’année en cours.',
            );
            return;
        }

        $fullName = method_exists($teacher, 'getFullName')
            ? $teacher->getFullName()
            : ($teacher->user?->getFullName() ?? 'enseignant');

        $docTitle = 'EDT-' . Str::slug($fullName) . '-' . ($year->slug ?? $year->id);

        try {
            if($this->myTimePlanIsDownloadable){

                $this->deleteDocument($this->myTimePlanIsDownloadable);
            }

            JobToGeneratePrintableTimePlans::dispatch(
                tenantId: tenant('id'),
                notifiableId: (int) auth('tenant')->id(),
                mode: 'teachers',
                school_year_id: (int) $year->id,
                docTitle: $docTitle,
                config: [
                    'teacher_ids' => [(int) $teacher->id],
                ],
            );

            $this->notification()->success(
                title: 'Génération lancée',
                description: 'Votre emploi du temps est en cours de génération. Vous serez notifié dès qu’il sera prêt.',
            );
        } catch (\Throwable $th) {
            $this->notification()->error(
            title: 'ERREUR',
                description: "Une erreur s'est produitre lors de la génération de votre emploi du temps!",
            );
        }

        
    }


    #[Computed]
    public function myTimePlanIsDownloadable() : ?int
    {
        $doc =  GeneratedDocument::ofType('time_plan')
            ->forUser(auth('tenant')->id())
            ->where('school_year_id', $this->activeYear->id)
            ->where('for_teacher_id', $this->teacher->id)->first();

        return $doc ? $doc->id : null;
    }


     /**
     * Retourne une réponse de téléchargement de fichier directement depuis
     * l'action Livewire — supporté nativement depuis Livewire 3.
     */
    public function trackDownload(int $documentId)
    {
        $doc = GeneratedDocument::where('id', $documentId)
            ->where('user_id', auth('tenant')->user()->id)
            ->first();

        if (! $doc || ! File::exists($doc->path)) {
            $this->notification()->error(
                title: 'Document introuvable',
                description: 'Le fichier a peut-être déjà été supprimé du serveur.',
            );
            return;
        }

        $doc->recordDownload();

        $this->notification()->info(title: 'Téléchargement de votre emploi du temps en cours ...');

        return response()->download($doc->path, $doc->filename);


    }


    public function deleteDocument(int $documentId): void
    {
        $doc = GeneratedDocument::where('id', $documentId)
            ->where('user_id', auth('tenant')->user()->id)
            ->first();

        if (! $doc) {
            return;
        }

        TenantStorage::delete($this->relativePathFromAbsolute($doc->path));

        $doc->delete();

        unset($this->myTimePlanIsDownloadable);

    }


    /**
     * TenantStorage::delete() attend un chemin relatif au disque 'public',
     * alors que GeneratedDocument::path stocke un chemin absolu (issu de
     * PDFFactory::outputPath()). On reconvertit ici.
     */
    protected function relativePathFromAbsolute(string $absolutePath): string
    {
        $absolutePath = str_replace('\\', '/', $absolutePath);
        $storageRoot  = str_replace('\\', '/', Storage::disk('public')->path(''));

        return ltrim(Str::after($absolutePath, $storageRoot), '/');
    }










    public function render()
    {
        return view('livewire.tenants.users.teacher.teacher-dashboard');
    }
}
