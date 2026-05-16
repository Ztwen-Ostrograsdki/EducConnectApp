<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClasseSubjectOfSchoolYear extends Model
{
    public function replaceTeacher(int $newTeacherId, string $reason, int $replacedBy): void
    {
        // 1. Clôturer l'ancien
        $this->update([
            'ended_at'    => now(),
            'replaced_by' => $replacedBy,
        ]);

        // 2. Créer le nouveau
        self::create([
            'classe_id'          => $this->classe_id,
            'subject_id'         => $this->subject_id,
            'school_year_id'     => $this->school_year_id,
            'teacher_id'         => $newTeacherId,
            'coefficient'        => $this->coefficient,
            'replacement_reason' => $reason,
            'started_at'         => now(),
            'ended_at'           => null,
        ]);
    }


    
}
