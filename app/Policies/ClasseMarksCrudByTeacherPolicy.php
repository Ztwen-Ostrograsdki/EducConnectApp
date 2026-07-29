<?php

namespace App\Policies;

use App\Models\Classe;
use App\Models\Subject;
use App\Models\Teacher;
use App\Securities\SecuritiesServices\ClasseMarksAuthorizationService;

class ClasseMarksCrudByTeacherPolicy
{
    public function crud(
        ?Teacher $teacher,
        ?Classe $classe,
        ?Subject $subject,
    ): bool {

        return app(ClasseMarksAuthorizationService::class)
            ->crud(
                $teacher,
                $classe,
                $subject
            )
            ->allowed;
    }
}