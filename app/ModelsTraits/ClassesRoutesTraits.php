<?php

namespace App\ModelsTraits;

/**
 * @mixin \App\Models\Classe
 * @property string slug
 */
trait ClassesRoutesTraits{


	public function to_profil_route()
	{
		return route('tenant.classe.profil', ['classe_slug' => $this->slug]);
	}

	public function to_update_route()
	{
		return route('tenant.classe.edit', ['classe_slug' => $this->slug]);
	}

	public function to_migrate_students_route()
	{
		return route('tenant.classe.migrate.students', ['classe_slug' => $this->slug]);
	}


	public function to_manage_responsibles_route()
	{
		return route('tenant.classe.respos', ['classe_slug' => $this->slug]);
	}


	public function to_manage_classe_teachers_route()
	{
		return route('tenant.classe.manage.subjects.teacher', ['classe_slug' => $this->slug]);
	}
	
	public function to_classe_printable_docs_route()
	{
		return route('tenant.students.docs', ['classe_slug' => $this->slug]);
	}

	public function to_generate_classe_students_docs_route()
	{
		return route('tenant.students.print.configuration', ['classe_slug' => $this->slug]);
	}



}