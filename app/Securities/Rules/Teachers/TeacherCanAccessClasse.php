<?php

namespace App\Securities\Rules\Teachers;

use App\Models\Classe;
use App\Models\Teacher;
use App\Securities\AuthorizationResponse;


class TeacherCanAccessClasse{

	public function handle(
        ?Teacher $teacher,
        ?Classe $classe
    ): AuthorizationResponse {

        if (! ($teacher && $classe && $teacher->canAccessIntoClasse($classe->id))) {

            return AuthorizationResponse::deny(
                "Vous n'avez pas accès à cette classe, votre accès a peut-être été désactivé!"
            );
        }

        return AuthorizationResponse::allow();
    }



}