<?php

namespace App\Securities\Rules\Classes;

use App\Models\Classe;
use App\Securities\AuthorizationResponse;

class ClasseIsActive {
	public function handle(
        ?Classe $classe
    ): AuthorizationResponse {

        if (!($classe && $classe->is_acive && !$classe->is_locked)) {

            return AuthorizationResponse::deny(
                "Cette classe est soit inexistante soit inactive soit fermée"
            );
        }

        return AuthorizationResponse::allow();
    }
}