<?php

namespace App\Services\TimePlanServices;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\SubjectAnimationTime;
use App\Models\Teacher;
use App\Models\TimePlan;
use App\Models\TimePlanSlot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Construit la structure $plans attendue par time-plan-printable.blade.php.
 */
class TimePlanPrintBuilder
{
    public function __construct(
        public readonly int $schoolYearId,
    ) {}

    public static function forYear(int $schoolYearId): self
    {
        return new self($schoolYearId);
    }

    public function weekDays(): array
    {
        return config('timeplan.days', [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
        ]);
    }

    public function scheduleRows(): array
    {
        return [
            ['type' => 'hour', 'start' => '07:00', 'end' => '08:00'],
            ['type' => 'hour', 'start' => '08:00', 'end' => '09:00'],
            ['type' => 'hour', 'start' => '09:00', 'end' => '10:00'],
            ['type' => 'break', 'label' => 'Récréation', 'start' => '10:00', 'end' => '10:00', 'variant' => 'recreation'],
            ['type' => 'hour', 'start' => '10:00', 'end' => '11:00'],
            ['type' => 'hour', 'start' => '11:00', 'end' => '12:00'],
            ['type' => 'hour', 'start' => '12:00', 'end' => '13:00'],
            ['type' => 'break', 'label' => 'Pause déjeuner', 'start' => '13:00', 'end' => '14:00', 'variant' => 'lunch'],
            ['type' => 'hour', 'start' => '14:00', 'end' => '15:00'],
            ['type' => 'hour', 'start' => '15:00', 'end' => '16:00'],
            ['type' => 'hour', 'start' => '16:00', 'end' => '17:00'],
            ['type' => 'hour', 'start' => '17:00', 'end' => '18:00'],
            ['type' => 'hour', 'start' => '18:00', 'end' => '19:00'],
        ];
    }

    public function normalizeTime(string $time): string
    {
        return substr($time, 0, 5);
    }

    protected function scheduleHourIndex(string $rowStart): int
    {
        foreach ($this->scheduleRows() as $i => $row) {
            if (($row['type'] ?? '') === 'hour' && $row['start'] === $rowStart) {
                return $i;
            }
        }

        return -1;
    }

    public function slotContiguousSpan(object $slot, string $fromRowStart): int
    {
        $slotStart = $this->normalizeTime((string) $slot->starts_at);
        $slotEnd = $this->normalizeTime((string) $slot->ends_at);
        $rows = $this->scheduleRows();
        $startIdx = $this->scheduleHourIndex($fromRowStart);

        if ($startIdx < 0) {
            return 1;
        }

        $span = 0;
        for ($i = $startIdx, $n = count($rows); $i < $n; $i++) {
            $row = $rows[$i];

            if (($row['type'] ?? '') !== 'hour') {
                break;
            }

            if ($row['start'] >= $slotEnd || $row['end'] <= $slotStart) {
                break;
            }

            $span++;
        }

        return max(1, $span);
    }

    /**
     * Emplois du temps de classes.
     *
     * @param  array<int>  $classeIds
     * @param  string|null $status  published|draft|null (= published en priorité, sinon tous non archivés)
     */
    public function buildForClasses(array $classeIds, ?string $status = 'published'): array
    {
        $classeIds = array_values(array_filter(array_map('intval', $classeIds)));

        if ($classeIds === []) {
            return [];
        }

        $year = SchoolYear::query()->find($this->schoolYearId);
        $yearLabel = $year?->slug ?? $year?->name ?? '—';

        $query = TimePlan::query()
            ->where('school_year_id', $this->schoolYearId)
            ->whereIn('classe_id', $classeIds)
            ->where('status', '!=', 'archived')
            ->with([
                'classe',
                'slots.classeSubjectOfSchoolYear.subject',
                'slots.classeSubjectOfSchoolYear.teacher.user',
            ]);

        if ($status) {
            $query->where('status', $status);
        }

        $plans = $query->get()->keyBy('classe_id');

        // Si published demandé mais manquant : retomber sur le plan non archivé
        if ($status === 'published') {
            $missing = array_diff($classeIds, $plans->keys()->all());
            if ($missing !== []) {
                $fallback = TimePlan::query()
                    ->where('school_year_id', $this->schoolYearId)
                    ->whereIn('classe_id', $missing)
                    ->where('status', '!=', 'archived')
                    ->with([
                        'classe',
                        'slots.classeSubjectOfSchoolYear.subject',
                        'slots.classeSubjectOfSchoolYear.teacher.user',
                    ])
                    ->get()
                    ->keyBy('classe_id');

                $plans = $plans->union($fallback);
            }
        }

        $result = [];

        foreach ($classeIds as $classeId) {
            $plan = $plans->get($classeId);
            $classe = $plan?->classe ?? Classe::query()->find($classeId);

            if (!$classe) {
                continue;
            }

            $slots = $plan
                ? $plan->slots->sortBy([['day_of_week', 'asc'], ['starts_at', 'asc']])->values()
                : collect();

            $cells = $this->buildCellsFromSlots($slots, forClasse: true);
            $totalHours = $slots->sum(fn ($s) => (int) $s->duration);

            $result[] = [
                'type'        => 'classe',
                'title'       => $classe->name,
                'subtitle'    => $classe->code ? "Code : {$classe->code}" : null,
                'contacts'    => null,
                'school_year' => $yearLabel,
                'total_hours' => $totalHours,
                'meta_line'   => $plan
                    ? ($plan->status === 'published' ? 'Publié' : ucfirst($plan->status)) . ' · ' . $slots->count() . ' créneau(x)'
                    : 'Aucun emploi du temps',
                'days'        => $this->weekDays(),
                'rows'        => $this->scheduleRows(),
                'cells'       => $cells,
            ];
        }

        return $result;
    }

    /**
     * Emplois du temps d’enseignants (ids explicites).
     *
     * @param  array<int>  $teacherIds
     */
    public function buildForTeachers(array $teacherIds): array
    {
        $teacherIds = array_values(array_filter(array_map('intval', $teacherIds)));

        if ($teacherIds === []) {
            return [];
        }

        $year = SchoolYear::query()->find($this->schoolYearId);
        $yearLabel = $year?->slug ?? $year?->name ?? '—';

        $teachers = Teacher::query()
            ->whereIn('id', $teacherIds)
            ->with(['user'])
            ->get()
            ->keyBy('id');

        $result = [];

        foreach ($teacherIds as $teacherId) {
            $teacher = $teachers->get($teacherId);
            if (!$teacher) {
                continue;
            }

            $slots = $this->teacherSlotsWithAp($teacherId);
            $cells = $this->buildCellsFromSlots($slots, forClasse: false);

            // Heures de cours uniquement (hors AP)
            $courseHours = $slots
                ->reject(fn ($s) => (bool) ($s->is_ap ?? false))
                ->sum(function ($s) {
                    if (isset($s->duration)) {
                        return (int) $s->duration;
                    }
                    $start = Carbon::parse($this->normalizeTime((string) $s->starts_at));
                    $end = Carbon::parse($this->normalizeTime((string) $s->ends_at));

                    return (int) $start->diffInHours($end, true);
                });

            // AP : on ne compte que la première APTime renseignée (pas la somme de toutes)
            $firstAp = $slots->first(fn ($s) => (bool) ($s->is_ap ?? false));
            $apHours = 0;
            if ($firstAp) {
                if (isset($firstAp->duration)) {
                    $apHours = (int) $firstAp->duration;
                } else {
                    $start = Carbon::parse($this->normalizeTime((string) $firstAp->starts_at));
                    $end = Carbon::parse($this->normalizeTime((string) $firstAp->ends_at));
                    $apHours = (int) $start->diffInHours($end, true);
                }
            }

            $totalHours = (int) $courseHours + (int) $apHours;

            $subjects = $slots
                ->map(fn ($s) => $s->subject?->code ?? $s->subject?->name)
                ->filter()
                ->unique()
                ->values();

            $contacts = collect([
                $teacher->user?->contacts ?? $teacher->contacts ?? null,
                $teacher->user?->email ?? $teacher->email ?? null,
            ])->filter()->implode(' · ');

            $name = $this->getTeacherName($teacher);

            $result[] = [
                'type'        => 'teacher',
                'title'       => $name ?: 'Enseignant',
                'subtitle'    => $subjects->isNotEmpty() ? $subjects->implode(' · ') : null,
                'contacts'    => $contacts ?: null,
                'school_year' => $yearLabel,
                'total_hours' => $totalHours,
                'meta_line'   => $slots->count() . ' créneau(x)' . ($subjects->isNotEmpty() ? ' · ' . $subjects->count() . ' matière(s)' : ''),
                'days'        => $this->weekDays(),
                'rows'        => $this->scheduleRows(),
                'cells'       => $cells,
            ];
        }

        return $result;
    }

    /**
     * Tous les enseignants d’une matière (affectations actives de l’année).
     */
    public function buildForSubjectTeachers(int $subjectId): array
    {
        $teacherIds = ClasseSubjectOfSchoolYear::query()
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $this->schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->pluck('teacher_id')
            ->unique()
            ->filter()
            ->values()
            ->all();

        return $this->buildForTeachers($teacherIds);
    }

    /**
     * Construit la grille cells[day][rowStart] pour une collection de slots (cours ou AP).
     *
     * Un créneau qui traverse une pause (récréation / déjeuner) est découpé en
     * plusieurs fragments : un avant la pause, un à la reprise juste après —
     * comme sur l’affichage Livewire (slotFragmentAt).
     *
     * @param  Collection<int, object>  $slots
     */
    public function buildCellsFromSlots(Collection $slots, bool $forClasse = true): array
    {
        $days = array_keys($this->weekDays());
        $rows = $this->scheduleRows();
        $cells = [];

        foreach ($days as $day) {
            $cells[$day] = [];
            $daySlots = $slots->where('day_of_week', $day)->values();

            foreach ($rows as $row) {
                if (($row['type'] ?? '') !== 'hour') {
                    continue;
                }
                $cells[$day][$row['start']] = null;
            }

            foreach ($daySlots as $slot) {
                $slotStart = $this->normalizeTime((string) $slot->starts_at);
                $slotEnd = $this->normalizeTime((string) $slot->ends_at);

                $isAp = (bool) ($slot->is_ap ?? false);

                if ($forClasse) {
                    $label = $slot->subject?->code
                        ?? $slot->subject?->name
                        ?? ($slot->label ?: 'Cours');

                    $detail = $this->getTeacherName($slot->classeSubjectOfSchoolYear?->teacher);
                } else {
                    $label = $slot->subject?->code
                        ?? $slot->subject?->name
                        ?? ($slot->label ?: ($isAp ? 'AP' : 'Cours'));
                    $detail = $isAp
                        ? ($slot->label ?: 'Animation pédagogique')
                        : ($slot->timePlan?->classe?->name
                            ?? $slot->classeSubjectOfSchoolYear?->classe?->name
                            ?? null);
                }

                $cellPayload = [
                    'label'   => $label,
                    'detail'  => $detail,
                    'start'   => Carbon::parse($slotStart)->format('H\hi'),
                    'end'     => Carbon::parse($slotEnd)->format('H\hi'),
                    'is_ap'   => $isAp,
                    'covered' => false,
                ];

                // Fragments : départ exact + reprises juste après chaque pause
                $fragmentStarts = $this->slotFragmentStarts($slotStart, $slotEnd, $rows);

                foreach ($fragmentStarts as $fragmentStart) {
                    // Déjà occupé par un autre créneau → on ignore ce fragment
                    if (!empty($cells[$day][$fragmentStart]['label'])) {
                        continue;
                    }
                    // Déjà absorbé par un rowspan précédent du même créneau
                    if (!empty($cells[$day][$fragmentStart]['covered'])) {
                        continue;
                    }

                    $span = $this->slotContiguousSpan($slot, $fragmentStart);

                    $cells[$day][$fragmentStart] = array_merge($cellPayload, [
                        'span' => $span,
                    ]);

                    // Marquer les lignes horaires suivantes du fragment comme covered
                    $startIdx = $this->scheduleHourIndex($fragmentStart);
                    $marked = 0;
                    for ($i = $startIdx, $n = count($rows); $i < $n && $marked < $span; $i++) {
                        $row = $rows[$i];
                        if (($row['type'] ?? '') !== 'hour') {
                            break;
                        }
                        if ($row['start'] === $fragmentStart) {
                            $marked++;
                            continue;
                        }
                        $cells[$day][$row['start']] = ['covered' => true];
                        $marked++;
                    }
                }
            }
        }

        return $cells;
    }

    /**
     * Lignes horaires où un fragment de créneau doit commencer :
     * - la ligne de début exacte du créneau
     * - chaque ligne horaire qui suit immédiatement une pause, tant que le créneau la couvre
     *
     * Ex. 08h00–12h00 → fragments à 08:00 (avant récré) et 10:00 (après récré).
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return list<string>
     */
    public function slotFragmentStarts(string $slotStart, string $slotEnd, array $rows): array
    {
        $starts = [];

        foreach ($rows as $i => $row) {
            if (($row['type'] ?? '') !== 'hour') {
                continue;
            }

            $rowStart = $row['start'];
            $rowEnd = $row['end'];

            // La ligne n’intersecte pas le créneau
            if ($rowStart >= $slotEnd || $rowEnd <= $slotStart) {
                continue;
            }

            // Départ exact du créneau
            if ($rowStart === $slotStart) {
                $starts[] = $rowStart;
                continue;
            }

            // Reprise après une pause : la ligne précédente est un break
            // et le créneau a commencé avant cette ligne
            if ($i > 0 && ($rows[$i - 1]['type'] ?? '') === 'break' && $slotStart < $rowStart && $slotEnd > $rowStart) {
                $starts[] = $rowStart;
            }
        }

        // Fallback : première heure couverte si aucun départ trouvé
        if ($starts === []) {
            foreach ($rows as $row) {
                if (($row['type'] ?? '') === 'hour' && $row['start'] < $slotEnd && $row['end'] > $slotStart) {
                    $starts[] = $row['start'];
                    break;
                }
            }
        }

        return $starts;
    }

    /**
     * Créneaux cours + AP d’un enseignant (même logique que TimePlanActions::teacherSlotsWithApFor).
     */
    public function teacherSlotsWithAp(int $teacherId): Collection
    {
        $courseSlots = TimePlanSlot::query()
            ->whereHas(
                'timePlan',
                fn (Builder $q) => $q
                    ->where('school_year_id', $this->schoolYearId)
                    ->where('status', 'published')
            )
            ->whereHas(
                'classeSubjectOfSchoolYear',
                fn (Builder $q) => $q
                    ->where('teacher_id', $teacherId)
                    ->whereNull('ended_at')
                    ->where('is_active', true)
            )
            ->with([
                'timePlan.classe',
                'classeSubjectOfSchoolYear.subject',
                'classeSubjectOfSchoolYear.teacher.user',
                'classeSubjectOfSchoolYear.classe',
            ])
            ->orderBy('day_of_week')
            ->orderBy('starts_at')
            ->get();

        $subjectIds = ClasseSubjectOfSchoolYear::query()
            ->where('teacher_id', $teacherId)
            ->where('school_year_id', $this->schoolYearId)
            ->whereNull('ended_at')
            ->where('is_active', true)
            ->whereDoesntHave('subject', fn ($q) => $q->whereIn('name', ['conduite', 'Conduite']))
            ->pluck('subject_id')
            ->unique()
            ->filter()
            ->values();

        $apDisplay = collect();

        if ($subjectIds->isNotEmpty()) {
            $apTimes = SubjectAnimationTime::query()
                ->where('school_year_id', $this->schoolYearId)
                ->where('is_active', true)
                ->whereIn('subject_id', $subjectIds)
                ->with(['subject'])
                ->orderBy('day_of_week')
                ->orderBy('starts_at')
                ->get();

            $kept = collect();
            foreach ($apTimes as $ap) {
                $start = $this->normalizeTime((string) $ap->starts_at);
                $end = $this->normalizeTime((string) $ap->ends_at);

                $overlapsAp = $kept->contains(function ($existing) use ($ap, $start, $end) {
                    if ((int) $existing->day_of_week !== (int) $ap->day_of_week) {
                        return false;
                    }
                    $eStart = $this->normalizeTime((string) $existing->starts_at);
                    $eEnd = $this->normalizeTime((string) $existing->ends_at);

                    return $start < $eEnd && $end > $eStart;
                });

                if ($overlapsAp) {
                    continue;
                }

                $conflictsCourse = $courseSlots->contains(function ($slot) use ($ap, $start, $end) {
                    if ((int) $slot->day_of_week !== (int) $ap->day_of_week) {
                        return false;
                    }
                    $sStart = $this->normalizeTime((string) $slot->starts_at);
                    $sEnd = $this->normalizeTime((string) $slot->ends_at);

                    return $start < $sEnd && $end > $sStart;
                });

                if ($conflictsCourse) {
                    continue;
                }

                $kept->push($ap);
                $apDisplay->push((object) [
                    'id' => 'ap-' . $ap->id,
                    'day_of_week' => (int) $ap->day_of_week,
                    'starts_at' => $start,
                    'ends_at' => $end,
                    'label' => $ap->label ?: 'AP',
                    'subject' => $ap->subject,
                    'is_ap' => true,
                    'timePlan' => null,
                    'classeSubjectOfSchoolYear' => null,
                    'duration' => (int) Carbon::parse($start)->diffInHours(Carbon::parse($end), true),
                ]);
            }
        }

        return $courseSlots
            ->concat($apDisplay)
            ->sortBy([
                fn ($s) => (int) $s->day_of_week,
                fn ($s) => $this->normalizeTime((string) $s->starts_at),
            ])
            ->values();
    }

    public function resolveDocTitle(string $mode, array $config = []): string
    {
        $year = SchoolYear::query()->find($this->schoolYearId);
        $yearLabel = $year?->slug ?? '';

        return match ($mode) {
            'classes' => 'Emplois du temps — Classes' . ($yearLabel ? " ({$yearLabel})" : ''),
            'teachers' => 'Emplois du temps — Enseignants' . ($yearLabel ? " ({$yearLabel})" : ''),
            'subject' => 'Emplois du temps — Atelier/Spécialités'
                . (isset($config['subject_name']) ? ' ' . $config['subject_name'] : '')
                . ($yearLabel ? " ({$yearLabel})" : ''),
            default => 'Emplois du temps' . ($yearLabel ? " ({$yearLabel})" : ''),
        };
    }


    public function getTeacherName(?Teacher $teacher)
    {
        if ($teacher) return "Prof : " . $teacher->user->name . ' ' . Str::upper(str()->substr($teacher->user->prenames, 1, 1)) . '.';

        else return "Enseignant";
    }
}
