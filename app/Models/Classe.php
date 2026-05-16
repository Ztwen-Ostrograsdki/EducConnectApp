<?php

namespace App\Models;

use App\Models\ClasseSubjectOfSchoolYear;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    // Enseignant actuel d'une matière dans une classe
    public function getCurrentTeacherOfSubject(int $subjectId, int $yearId)
    {
       return ClasseSubjectOfSchoolYear::where('classe_id', $this->id)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $yearId)
            ->whereNull('ended_at')
            ->first();
    }

    /**
     * Historique complet des enseignants d'une matière
     */
    public function getSubjectTeachersHistories(int $subjectId, int $yearId)
    {
        return ClasseSubjectOfSchoolYear::where('classe_id', $this->id)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $yearId)
            ->orderBy('started_at')
            ->get();

    }

    // Tous les remplacements de l'année
    public function getSubjectReplacements(int $subjectId, int $yearId)
    {
        return ClasseSubjectOfSchoolYear::where('classe_id', $this->id)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $yearId)
            ->whereNotNull('ended_at')
            ->orderBy('ended_at', 'desc')
            ->get();
    }
}
