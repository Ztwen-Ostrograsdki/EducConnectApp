<?php

namespace App\Helpers;

use Illuminate\Support\Str;


class ClasseHelpers{



	public static function getClasseNameFormatted(string $name, ?int $occurence = null) : array
    {

        if ($name) {

            $card = [];

            $card['name'] = $name;

            $card['idc'] = "";

			$card['code'] = "";

            if(preg_match_all('/ /', $name)){

                $card['idc'] = explode(' ', $name)[1];
            }

            if (preg_match_all('/Sixi/', $name)) { 

                $card['code'] = "6ème";

                $card['sup'] = "ème";

                $card['root'] = "6";
            }
            elseif (preg_match_all('/Cinqui/', $name)) {

				$card['code'] = "5ème";

                $card['sup'] = "ème";

                $card['root'] = "5";
            }
            elseif (preg_match_all('/Quatriem/', $name)) {

				$card['code'] = "4ème";

                $card['sup'] = "ème";

                $card['root'] = "4";
            }
            elseif (preg_match_all('/Troisie/', $name)) {

				$card['code'] = "3ème";

                $card['sup'] = "ème";

                $card['root'] = "3";
            }
            elseif (preg_match_all('/Seconde/', $name)) {

				$card['code'] = "2nde";

                $card['sup'] = "nde";

                $card['root'] = "2";
            }
            elseif (preg_match_all('/Premi/', $name)) {

				$card['code'] = "1ère";

                $card['sup'] = "ère";

                $card['root'] = "1";
            }
            elseif (preg_match_all('/Terminale/', $name)) {

				$card['code'] = "Tle";

                $card['sup'] = "le";

                $card['root'] = "T";
                
            }
			elseif (preg_match_all('/premiere annee/', Str::lower(Str::ascii($name)))) {

				$card['code'] = "1AI";

                $card['sup'] = "";

                $card['root'] = "1AI";
                
            }
			elseif (preg_match_all('/deuxieme annee/', Str::lower(Str::ascii($name)))) {

				$card['code'] = "2AI";

                $card['sup'] = "";

                $card['root'] = "2AI";
                
            }
			elseif (preg_match_all('/troisieme annee/', Str::lower(Str::ascii($name)))) {

				$card['code'] = "3AI";

                $card['sup'] = "";

                $card['root'] = "3AI";
                
            }
            else{

                return ['sup' => "", 'idc' => "", 'root' => $name, 'code' => Str::lower(Str::ascii($name))];
            }

            $parts = explode(' ', $name);

            if(count($parts) > 1){

                $idcs = explode('-', $parts[1]);

                if(count($idcs) > 1){

                    $idc = $idcs[0] . '-' . $idcs[1];

                    $card['idc'] = $idc;
                }
                else{

                    $idc = $parts[1];

                    $card['idc'] = $idc;
                }
            }

            return $card;

        }
        else{

            return ['sup' => "", 'idc' => "", 'root' => $name, 'code' => Str::lower(Str::ascii($name))];
        }
    }
}