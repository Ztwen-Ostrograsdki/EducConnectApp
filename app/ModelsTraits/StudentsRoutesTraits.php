<?php

namespace App\ModelsTraits;

/**
 * @mixin \App\Models\Student
 * @property string uuid
 */
trait StudentsRoutesTraits{


	public function to_profil_route()
	{
		return route('tenant.student.profil', ['student_uuid' => $this->uuid]);
	}
	
	public function to_manage_student_classe()
	{
		return route('tenant.student.manage.classe', ['student_uuid' => $this->uuid]);
	}
	
	public function to_manage_student_tutors()
	{
		return route('tenant.student.manage.relations', ['student_uuid' => $this->uuid]);
	}



}