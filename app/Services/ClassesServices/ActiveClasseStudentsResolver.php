<?php

namespace App\Services\ClassesServices;

use App\Models\YearlyClasseStudent;
use Illuminate\Support\Collection;

class ActiveClasseStudentsResolver
{
    /**
     * Apprenants n'ayant pas abandonné, pour une classe/année données.
     * Même critère que ClasseEffectifsService::countActiveStudents().
     */
    public function ids(int $classeId, ?int $schoolYearId): Collection
    {
        return YearlyClasseStudent::query()
            ->where('classe_id', $classeId)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
			->whereHas('student', fn($q) => 
				$q->whereDoesntHave('yearlyStudentsLeaves', fn ($qs) => 
					$qs->where('school_year_id', $schoolYearId)
				)
			)
            ->pluck('student_id');
    }
}