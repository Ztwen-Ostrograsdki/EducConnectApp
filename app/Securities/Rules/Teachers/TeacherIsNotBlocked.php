<?php

namespace App\Securities\Rules\Teachers;

use App\Models\Teacher;
use App\Securities\AuthorizationResponse;

class TeacherIsNotBlocked
{
    public function handle(
        Teacher $teacher
    ): AuthorizationResponse {

        if (
            $teacher->blocked
            || $teacher->user?->blocked
        ) {

            return AuthorizationResponse::deny(
                "Votre compte est bloqué."
            );
        }

        return AuthorizationResponse::allow();
    }
}