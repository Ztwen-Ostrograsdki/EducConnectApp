<?php

namespace App\ModelsTraits;

/**
 * @mixin \App\Models\Classe
 * @property string slug
 * @method function(string $role) hasRole
 */
trait UsersRoutesTraits{

	public function to_profil_route()
	{
		return route('tenant.my.profil');
	}

	public function to_space_route()
	{
		if($this->hasRole('enseignant')){

			return route('tenant.teacher.my.dashboard');
		}

		if($this->hasRole('tuteur')){

			return route('tenant.my.parent.space');
		}

		if($this->hasRole('directeur')){

			return route('tenant.dashboard');
		}
		else{

			return route('tenant.my.profil'); 
		}
	}

}