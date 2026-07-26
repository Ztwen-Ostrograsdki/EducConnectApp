<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class InitProcessToUpdateStudentsMarksEvent
{
    use Dispatchable;

    public function __construct(
        public string $tenantId,
        public int $teacherId,
        public int $classeId,
        public int $subjectId,
        public int $period,
        public array $data, // [ ['student_id' => .., 'marks' => ['interro1' => 12.5, 'interro2' => null, 'devoir1' => 14, ...]], ... ]
        public ?int $schoolYearId = null,
    ) {}
}