<?php

namespace App\Securities\Rules\Teachers;

use App\Models\Teacher;
use App\Securities\AuthorizationResponse;


class TeacherHasValidYearAccess
{
    public function handle(
        Teacher $teacher
    ): AuthorizationResponse {

        if (! $teacher->hasValidAccessForYear()) {

            return AuthorizationResponse::deny(
                "Votre clé d'accès aux classes n'est pas active."
            );
        }

        return AuthorizationResponse::allow();
    }
}