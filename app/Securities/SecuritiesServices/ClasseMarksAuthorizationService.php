<?php

namespace App\Securities\SecuritiesServices;

use App\Models\Classe;
use App\Models\Subject;
use App\Models\Teacher;
use App\Securities\AuthorizationResponse;
use App\Securities\Rules\Classes\ClasseIsActive;
use App\Securities\Rules\Subjects\SubjectExists;
use App\Securities\Rules\Teachers\TeacherCanAccessClasse;
use App\Securities\Rules\Teachers\TeacherHasValidYearAccess;
use App\Securities\Rules\Teachers\TeacherIsNotBlocked;


class ClasseMarksAuthorizationService
{
    public function __construct(

        private TeacherIsNotBlocked $teacherBlocked,

        private TeacherHasValidYearAccess $teacherYear,

        private TeacherCanAccessClasse $teacherClasse,

        private ClasseIsActive $classeActive,

        private SubjectExists $subjectExists,

    ) {
    }

    public function crud(
        ?Teacher $teacher,
        ?Classe $classe,
        ?Subject $subject,
    ): AuthorizationResponse {

        foreach ([

            $this->teacherBlocked->handle($teacher),

            $this->teacherYear->handle($teacher),

            $this->subjectExists->handle($subject),

            $this->classeActive->handle($classe),

            $this->teacherClasse->handle(
                $teacher,
                $classe
            ),

        ] as $response) {

            if (! $response->allowed) {
                return $response;
            }

        }

        return AuthorizationResponse::allow();
    }
}