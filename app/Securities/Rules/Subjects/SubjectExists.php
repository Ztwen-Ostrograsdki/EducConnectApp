<?php

namespace App\Securities\Rules\Subjects;

use App\Models\Subject;
use App\Securities\AuthorizationResponse;

class SubjectExists 
{

	public function handle(
        ?Subject $subject
    ): AuthorizationResponse {

        if (! $subject || !$subject->is_active) {

            return AuthorizationResponse::deny(
                "Cette matière n'existe pas ou a été désactivée"
            );
        }

        return AuthorizationResponse::allow();
    }
}