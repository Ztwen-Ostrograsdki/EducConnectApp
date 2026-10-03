<?php

namespace App\Services\MarksServices;

use App\Models\Mark;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Models\YearlyClasseStudent;

class NotesShareQuery
{
    public static function resolveClasseIdForStudent(int $studentId, int $schoolYearId): ?int
    {
		return YearlyClasseStudent::where('student_id', $studentId)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->value('classe_id');
    }

    protected static function markTypeLabels(string $devoirsType): array
    {
        return array_merge(
            ['interro1' => 'Interro 1', 'interro2' => 'Interro 2', 'interro3' => 'Interro 3', 'interro4' => 'Interro 4'],
            MarkPrintQuery::devoirColumns($devoirsType)
        );
    }

    /**
     * Retourne les notes BRUTES (pas de calcul) d'un élève, groupées par matière.
     * Si $subjectId est null, inclut toutes les matières ayant au moins une note
     * pour cette période — les matières sans aucune note sont omises.
     */
    public static function getMarksData(int $studentId, ?int $subjectId, int $period, int $schoolYearId): array
    {
        $classeId = self::resolveClasseIdForStudent($studentId, $schoolYearId);

        if (! $classeId) return [];

		if ($period && $schoolYearId) {

            if(!$schoolYearId){

                $schoolYear = SchoolYear::current()->first();
            }
            else{

                $schoolYear = SchoolYear::find($schoolYearId);
            }

           
        }

        $devoirsType = $schoolYear->devoirs_type ?? 'devoir1-devoir2';
        
		$typeLabels = self::markTypeLabels($devoirsType);

        $query = Mark::query()
            ->select(['id', 'student_id', 'subject_id', 'type', 'value'])
            ->where('classe_id', $classeId)
            ->where('student_id', $studentId)
            ->where('period', $period)
            ->where('school_year_id', $schoolYearId)
            ->whereIn('type', array_keys($typeLabels));

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $marksBySubject = $query->get()->groupBy('subject_id');

        if ($marksBySubject->isEmpty()) return [];

        $subjectNames = Subject::whereIn('id', $marksBySubject->keys())->pluck('name', 'id');

        $result = [];

        foreach ($marksBySubject as $subjId => $group) {
            $rows = [];

            foreach ($typeLabels as $type => $label) {
                $mark = $group->firstWhere('type', $type);

                if ($mark && $mark->value !== null) {
                    $rows[] = ['label' => $label, 'value' => (float) $mark->value];
                }
            }

            if (empty($rows)) continue;

            $result[] = [
                'subjectName' => $subjectNames[$subjId] ?? '—',
                'rows'        => $rows,
            ];
        }

        usort($result, fn ($a, $b) => strcmp($a['subjectName'], $b['subjectName']));

        return $result;
    }

    public static function resolveDocTitle(Student $student, ?Subject $subject, int $period, ?SchoolYear $schoolYear): string
    {
        $title = "Details des notes de {$student->getFullName()}";

        if ($subject) $title .= " en {$subject->name}";

        if ($schoolYear) $title .= " - {$schoolYear->periodLabel()} {$period}";

        return $title;
    }
}