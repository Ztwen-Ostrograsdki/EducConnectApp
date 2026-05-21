<?php

namespace App\Tools;


trait CentralTools{


	public static function getSchoolYears()
	{
		$data = [];

		$current_year = now()->year;

		for($i = 20; $i > 0; $i--){

			$next_year = $current_year - $i;

			$last_year = $current_year - ($i - 1);

			$school_year = $next_year. ' - ' . $last_year;

			$data[$school_year] = $school_year;

		}

		return $data;


	}





}