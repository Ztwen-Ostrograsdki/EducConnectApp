<?php

namespace App\Helpers;

use Illuminate\Support\Str;


class Robot {

	public static function makeIdentifier() : ?string
	{

		$identify = Str::random(10);
		
		return $identify;
	}



	public static function makeMatricule() : ?string
	{
		$matricule = Str::random(10);
		
		return $matricule;
	}



	public static function makeQrcode() : ?string
	{
		$qr_code = Str::random(10);
		
		return $qr_code;
	}
}