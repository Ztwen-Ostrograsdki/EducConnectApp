<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class Robot {

	public static function makeIdentifier(string $school_name) : ?string
	{

		return Str::upper(Str::initials($school_name)) . '-' . randomNumber(1000000, 9999999);
		
	}



	public static function makeMatricule(string $school_name) : ?string
	{
		return Str::upper(Str::initials($school_name)) . '-' . randomNumber(100000000, 999999999);
	}



	public static function makeQrCode(array $payload) : ?string
	{
		$png = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate(json_encode($payload));

        return 'data:image/png;base64,' . base64_encode($png);
	}
}