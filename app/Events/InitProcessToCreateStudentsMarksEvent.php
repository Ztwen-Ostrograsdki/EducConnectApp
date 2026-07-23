<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InitProcessToCreateStudentsMarksEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $tenantId,
        public int $teacherId,
        public int $classeId,
        public int $subjectId,
        public int $period,
        public array $data,
        public ?int $schoolYearId = null,
    ) {}
    
    
}
