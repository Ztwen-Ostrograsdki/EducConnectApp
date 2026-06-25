<?php

namespace App\Jobs;

use App\Models\YearlyClasseStudent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobToMigrateStudentsToClasses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int   $tenantId,
        public readonly int   $classeId,
        public readonly array $studentIds,
        public readonly int   $schoolYearId,
        public readonly int   $authorId,
    ) {}

    public function handle(): void
    {
        $now = now()->toDateString();

        foreach ($this->studentIds as $studentId) {
            $exists = YearlyClasseStudent::where('student_id', $studentId)
                ->where('school_year_id', $this->schoolYearId)
                ->exists();

            if ($exists) {
                continue; // ignoré silencieusement — déjà signalé en preview
            }

            YearlyClasseStudent::create([
                'student_id'     => $studentId,
                'classe_id'      => $this->classeId,
                'school_year_id' => $this->schoolYearId,
                'author_id'      => $this->authorId,
                'is_active'      => true,
                'status'         => 'Approuvé',
                'started_at'     => $now,
            ]);
        }
    }
}
