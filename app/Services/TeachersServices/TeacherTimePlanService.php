<?php

namespace App\Services\TeachersServices;

use Illuminate\Support\Collection;

/**
 * Logique partagée d’affichage / résolution de grille d’emploi du temps enseignant.
 *
 * Utilisé par TeacherDashboard, TeacherProfilPage, etc.
 * Les données brutes (slots, affectations, scheduleRows) restent fournies
 * par TimePlanActions ou le modèle ; ce service centralise le calcul de grille.
 */
class TeacherTimePlanService
{
    /**
     * Groupe une collection de créneaux par day_of_week.
     *
     * @param  Collection<int, object>  $slots
     * @return Collection<int, Collection<int, object>>
     */
    public function groupSlotsByDay(Collection $slots): Collection
    {
        return $slots
            ->groupBy(fn (object $slot) => (int) ($slot->day_of_week ?? 0))
            ->map(fn (Collection $daySlots) => $daySlots->values());
    }

    /**
     * Normalise une heure "H:i" ou "H:i:s" → "H:i".
     */
    public function normalizeTime(string $time): string
    {
        $time = trim($time);

        if (preg_match('/^(\d{1,2}):(\d{2})/', $time, $m)) {
            return sprintf('%02d:%02d', (int) $m[1], (int) $m[2]);
        }

        return $time;
    }

    /**
     * Index de la ligne horaire dans scheduleRows pour un début donné, ou -1.
     *
     * @param  array<int, array{type?: string, start?: string, end?: string}>  $scheduleRows
     */
    public function scheduleHourIndex(string $rowStart, array $scheduleRows): int
    {
        $rowStart = $this->normalizeTime($rowStart);

        foreach ($scheduleRows as $i => $row) {
            if (($row['type'] ?? '') === 'hour' && $this->normalizeTime((string) ($row['start'] ?? '')) === $rowStart) {
                return (int) $i;
            }
        }

        return -1;
    }

    /**
     * Créneau qui démarre sur la ligne, ou qui chevauche après une pause.
     *
     * @param  Collection<int, Collection<int, object>>  $slotsByDay
     * @param  array<int, array{type?: string, start?: string, end?: string}>  $scheduleRows
     */
    public function slotFragmentAt(
        Collection $slotsByDay,
        int $day,
        string $rowStart,
        array $scheduleRows,
    ): ?object {
        $rowStart = $this->normalizeTime($rowStart);
        $daySlots = $slotsByDay[$day] ?? collect();

        $starting = $daySlots->first(
            fn (object $slot) => $this->normalizeTime((string) $slot->starts_at) === $rowStart
        );

        if ($starting) {
            return $starting;
        }

        $idx = $this->scheduleHourIndex($rowStart, $scheduleRows);
        if ($idx <= 0) {
            return null;
        }

        $prev = $scheduleRows[$idx - 1] ?? null;
        if (! $prev || ($prev['type'] ?? '') !== 'break') {
            return null;
        }

        return $daySlots->first(function (object $slot) use ($rowStart) {
            $slotStart = $this->normalizeTime((string) $slot->starts_at);
            $slotEnd   = $this->normalizeTime((string) $slot->ends_at);

            return $slotStart < $rowStart && $slotEnd > $rowStart;
        });
    }

    /**
     * La cellule est-elle couverte par un créneau commencé plus haut (rowspan) ?
     *
     * @param  Collection<int, Collection<int, object>>  $slotsByDay
     * @param  array<int, array{type?: string, start?: string, end?: string}>  $scheduleRows
     */
    public function isCellCoveredBySpan(
        Collection $slotsByDay,
        int $day,
        string $rowStart,
        array $scheduleRows,
    ): bool {
        $rowStart = $this->normalizeTime($rowStart);
        $idx      = $this->scheduleHourIndex($rowStart, $scheduleRows);

        if ($idx < 0) {
            return false;
        }

        $daySlots = $slotsByDay[$day] ?? collect();

        foreach ($daySlots as $slot) {
            $slotStart = $this->normalizeTime((string) $slot->starts_at);
            $slotEnd   = $this->normalizeTime((string) $slot->ends_at);

            if (! ($slotStart < $rowStart && $slotEnd > $rowStart)) {
                continue;
            }

            $fragmentStartIdx = $this->scheduleHourIndex($slotStart, $scheduleRows);

            if ($fragmentStartIdx < 0) {
                foreach ($scheduleRows as $i => $row) {
                    if (
                        ($row['type'] ?? '') === 'hour'
                        && ($row['start'] ?? '') < $slotEnd
                        && ($row['end'] ?? '') > $slotStart
                    ) {
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
                if (($scheduleRows[$i]['type'] ?? '') === 'break') {
                    $blockedByBreak   = true;
                    $fragmentStartIdx = $i + 1;
                }
            }

            if ($blockedByBreak) {
                if (
                    $fragmentStartIdx < $idx
                    && ($scheduleRows[$fragmentStartIdx]['type'] ?? '') === 'hour'
                    && ($scheduleRows[$fragmentStartIdx]['start'] ?? '') < $rowStart
                ) {
                    return true;
                }
                continue;
            }

            return true;
        }

        return false;
    }
}
