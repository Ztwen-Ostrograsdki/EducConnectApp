<?php

use App\Models\RequestToCreateNewTenant;
use App\Models\SchoolYear;
use App\Models\Tenant;
use App\Tools\CentralTools;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

if (! function_exists('format_price_fcfa')) {
    function format_price_fcfa(string|float $amount)
    {
        return number_format($amount, 0, ',', ' ').' FCFA';
    }
}

if (!function_exists('normalizeString')) {
    function normalizeString(?string $value): string
    {
        return Str::upper(Str::ascii($value ?? ''));
    }
}
if (!function_exists('getAge')) {
    function getAge(string|null $birthDate): ?int
    {
        if (empty($birthDate)) {
            return null;
        }
        return Carbon::parse($birthDate)->age;
    }
}

if (! function_exists('formatBirthDate')) {

    function formatBirthDate(
        string|null $birthDate,
        ?int $dayLength = 3,
        ?int $monthLength = 5,
        bool $capitalize = true
    ): ?string {
        if (empty($birthDate)) {
            return null;
        }

        $date = Carbon::parse($birthDate)->locale('fr');

        $day = $date->translatedFormat('l');
        $month = $date->translatedFormat('F');

        if ($dayLength !== null) {
            if ($dayLength <= 0) {
                $day = '';
            } else {
                $day = mb_substr($day, 0, $dayLength);
            }
        }

        if ($monthLength !== null) {
            if ($monthLength > 0) {
                $month = mb_substr($month, 0, $monthLength);
            }
        }

        if ($capitalize) {
            $day = mb_convert_case($day, MB_CASE_TITLE, 'UTF-8');
            $month = mb_convert_case($month, MB_CASE_TITLE, 'UTF-8');
        }

        return trim(
            collect([
                $day,
                $date->format('d'),
                $month,
                $date->format('Y'),
            ])->filter()->implode(' ')
        );
    }
}

if (!function_exists('get_tenant_url')) {
    function get_tenant_url(?string $domain_name = null, string $path = '', ?int $tenantId = null): string
    {
        if($domain_name){

            $scheme = request()->getScheme() ?? 'http';

            $port   = request()->getPort() && request()->getPort() != 80 && request()->getPort() != 443 
                    ? ':' . request()->getPort() 
                    : '';

            $baseUrl = $scheme . '://' . $domain_name . $port;

            return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');


        }
        else{

            $tenant = null;

            if($tenantId) $tenant = Tenant::find($tenantId);

            if (!$tenant) return url($path); // fallback central

            $scheme = request()->getScheme() ?? 'http';

            $port   = request()->getPort() && request()->getPort() != 80 && request()->getPort() != 443 
                    ? ':' . request()->getPort() 
                    : '';

            $baseUrl = $scheme . '://' . $tenant->getDomainName() . $port;

            return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
        }
    }
}

if (! function_exists('getRandomValueFromArray')) {

    function getRandomValueFromArray(array $array, $default = null)
    {
        if (empty($array)) {
            return $default;
        }

        return $array[array_rand($array)];
    }
}

if (! function_exists('getTenantLoginUrl')) {

    function getTenantLoginUrl(string $domain_name)
    {
        return 'http://' . $domain_name . '.localhost/login';
    }
}



if (! function_exists('initials')) {

    function initials(string $strings): string
    {
        return Str::of($strings)
            ->explode(' ')
            ->filter()
            ->map(fn ($word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');
    }
}

if (! function_exists('getSchoolYearForTenants')) {

    function getSchoolYearForTenants(?string $school_year = null): string
    {
        if($school_year){

            if(is_numeric($school_year)){
                
                $school_year_model = SchoolYear::where('id', $school_year)->first();
            }
            else{
                $school_year_model = SchoolYear::where('slug', $school_year)->first();
            }

            return $school_year_model;
        }
        else{

            $school_year = null;

            $current_month_index = intval(date('m'));

            if($current_month_index >= 9){

                $school_year = date('Y') . '-' . intval(date('Y') + 1);
            }
            else{

                $school_year = intval(date('Y') - 1) . '-' . intval(date('Y'));
            }
            if(Session::has('school_year_selected') && session('school_year_selected')){

                $school_year = session('school_year_selected');

                Session::put('school_year_selected', $school_year);
            }
            else{

                Session::put('school_year_selected', $school_year);
            }

            $model = SchoolYear::where('slug', $school_year)->first();

            if($school_year && $model){

                // $this->__setSemestreIndex($school_year);

                // $school_year_model = $model;

            }
            else{

                // $school_year_model = $this->getLastYear();

                // $this->__setSemestreIndex($school_year_model->school_year);

            }

            Session::put('school_year_selected', $school_year);

            return $school_year;

        }
    }
}

if (! function_exists('getSchoolYearForCentral')) {

    function getSchoolYearForCentral(?string $school_year = null): string
    {
        $school_years = CentralTools::getSchoolYears();

        $school_year = null;

        $current_month_index = intval(date('m'));

        if($current_month_index >= 9){

            $school_year = date('Y') . '-' . intval(date('Y') + 1);
        }
        else{

            $school_year = intval(date('Y') - 1) . '-' . intval(date('Y'));
        }
        if(Session::has('school_year_selected') && session('school_year_selected')){

            $school_year = session('school_year_selected');

            Session::put('school_year_selected', $school_year);
        }
        else{

            Session::put('school_year_selected', $school_year);
        }

        $school_year = $school_years[$school_year];


        Session::put('school_year_selected', $school_year);

        return $school_year;

    }
}if (! function_exists('getSchoolYearForTenants')) {

    function getSchoolYearForTenants(?string $school_year = null): string
    {
        if($school_year){

            if(is_numeric($school_year)){
                
                $school_year_model = SchoolYear::where('id', $school_year)->first();
            }
            else{
                $school_year_model = SchoolYear::where('slug', $school_year)->first();
            }

            return $school_year_model;
        }
        else{

            $school_year = null;

            $current_month_index = intval(date('m'));

            if($current_month_index >= 9){

                $school_year = date('Y') . '-' . intval(date('Y') + 1);
            }
            else{

                $school_year = intval(date('Y') - 1) . '-' . intval(date('Y'));
            }
            if(Session::has('school_year_selected') && session('school_year_selected')){

                $school_year = session('school_year_selected');

                Session::put('school_year_selected', $school_year);
            }
            else{

                Session::put('school_year_selected', $school_year);
            }

            $model = SchoolYear::where('slug', $school_year)->first();

            if($school_year && $model){

                // $this->__setSemestreIndex($school_year);

                // $school_year_model = $model;

            }
            else{

                // $school_year_model = $this->getLastYear();

                // $this->__setSemestreIndex($school_year_model->school_year);

            }

            Session::put('school_year_selected', $school_year);

            return $school_year;

        }
    }
}

if (! function_exists('__defaultSchoolYears')) {

    function __defaultSchoolYears(): array
    {
        $school_years = [];

        $current_year = date('Y');

        for ($i=$current_year - 20; $i <= $current_year; $i++) { 
            
            $sy = $i . '-' . $i+1;

            $school_years[] = $sy;
        }
        
        return $school_years;

    }
}

if(!function_exists('getMonths')){

    function getMonths($index = null)
    {
        $months = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return $index ? $months[$index] : $months;
    }

}

if(!function_exists('getFulldurationBetween')){

    function getFulldurationBetween2Dates($start_date, $end_date)
    {
        $start = Carbon::parse($start_date);

        $end = Carbon::parse($end_date);

        $diff = $start->diff($end);

        $years = $diff->y;

        $months = $diff->m;

        $totalDays = $diff->d;

        $weeks = intval($totalDays / 7);

        $days = $totalDays % 7;

        $result = [];

        if($years > 0){

            $result[] = $years . ' an(s)';
        }

        if($months > 0){
            
            $result[] = $months . ' mois(s)';
        }

        if($weeks > 0){
            
            $result[] = $weeks . ' semaine(s)';
        }

        if($days > 0){
            
            $result[] = $days . ' jour(s)';
        }

        if($result !== []){

            return implode(', ', $result);
        }
        else{

            return "0 jours";
        }

    }

    

}

if(!function_exists('generateRandomNumber')){

    function generateRandomNumber($length = 10)
    {
        $min = (int)str_pad("1", $length, "0");

        $max = (int)str_pad("", $length, "9");

        return random_int($min, $max);
    }

}

if(!function_exists('getCurrentMonth')){

    function getCurrentMonth()
    {
        $index = date('n');

        return getMonths($index);
    }

}

if(!function_exists('__isConnectedToInternet')){

    function __isConnectedToInternet()
    {
        try {

           return @fsockopen("www.google.com", 80) !== false;

        } catch (\Exception $e) {

            return false;
        }
    }

}

if(!function_exists('getSpace_requests')){

    function getSpace_requests(?string $column = null, mixed $value = null)
    {
        if($column){

            return RequestToCreateNewTenant::where($column, $value)->get();

        }
        else{

            return RequestToCreateNewTenant::all();
        }
    }

}


if(!function_exists('__greatingMessager')){

    function __greatingMessager($name)
    {
        $hour = date('G');
        
        if($hour >= 0 && $hour <= 12){

            $greating = "Bonjour ";
        }
        else{

            $greating = "Bonsoir ";
        }

        return $name  ? $greating . ' ' . $name : $greating;
    }

}

if(!function_exists('is_image')){

    function is_image($extension)
    {
        $extension = str_replace('.', '', $extension);
        
        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            
            return true;
        }
        
        return false;
    }

}
if(!function_exists('numberZeroFormattor')){

    function numberZeroFormattor($number, $string = false)
    {
        if(is_array($number)) $number = count($number);

        if($string && $number == 0) return "Aucune donnée"; 
        
        return $number >= 10 ? $number : '0' . $number;
    }

}

if(!function_exists('__zero')){

    function __zero($number, $string = false)
    {
        if(is_array($number)) $number = count($number);

        if($string && $number == 0) return "Aucune donnée"; 
        
        return $number >= 10 ? $number : '0' . $number;
    }

}

if(!function_exists('zero')){

    function zero($number, $string = false)
    {
        if(is_array($number)) $number = count($number);

        if($string && $number == 0) return "Aucune donnée"; 
        
        return $number >= 10 ? $number : '0' . $number;
    }

}

if(!function_exists('__formatDecimal')){

    function __formatDecimal($number, $string = false)
    {
        return strpos($number, '.') !== false ? rtrim(rtrim($number, '0'), '.') : $number;
        
    }

}

if(!function_exists('__formatNumber3')){

    function __formatNumber3(int $number)
    {
        return $nombre_formate = number_format($number, 0, '', ' ');
    }

}

if(!function_exists('initials')){

    function initials(string $string)
    {

       if (!$string) return '';

        if (mb_strlen($string) <= 3) return $string;

        return str()->initials($string);
    }

}

if(!function_exists('substringer')){

    function substringer($string, $length = 8)
    {

       if (!$string) return '';

        if (mb_strlen($string) <= $length) return $string;

        $cut = mb_substr($string, 0, $length);
        $lastSpace = mb_strrpos($cut, ' ');

        $result = $lastSpace !== false
            ? mb_substr($cut, 0, $lastSpace)
            : $cut;

        return $result . '...';
    }

}

if(!function_exists('string_cutter')){

    function string_cutter($string, $length = 8)
    {

        if (!$string) return '';

        if (mb_strlen($string) <= $length) return $string;

        $cut = mb_substr($string, 0, $length);
        $lastSpace = mb_strrpos($cut, ' ');

        $result = $lastSpace !== false
            ? mb_substr($cut, 0, $lastSpace)
            : $cut;

        return $result . '...';
    }

}

if(!function_exists('cutter')){

    function cutter($string, $length = 8)
    {

        if (!$string) return '';

        if (mb_strlen($string) <= $length) return $string;

        $cut = mb_substr($string, 0, $length);
        $lastSpace = mb_strrpos($cut, ' ');

        $result = $lastSpace !== false
            ? mb_substr($cut, 0, $lastSpace)
            : $cut;

        return $result . '...';
    }

}



if(!function_exists('getYears')){

    function getYears($big_to_small = true, $start = null, $end = null)
    {
        $data = [];

        $first = 1990;

        $last = date('Y');

        if($start) $first = $start;

        if($end && $end > $start) $last = $end;

        for ($i = $first; $i <= $last; $i++) { 
            
            $data[$i] = $i;
        } 

        return $big_to_small ? array_reverse($data) : $data;
    }

}

if(!function_exists('__getSchoolFormattedName')){

    function __getClasseFormattedName($name)
    {
        if ($name) {

            $card = [];

            $card['name'] = $name;

            $card['idc'] = "";

            if(preg_match_all('/ /', $name)){

                $card['idc'] = explode(' ', $name)[1];
            }

            if (preg_match_all('/Sixi/', $name)) { 

                $card['sup'] = "ème";

                $card['root'] = "6";
            }
            elseif (preg_match_all('/Cinqui/', $name)) {

                $card['sup'] = "ème";

                $card['root'] = "5";
            }
            elseif (preg_match_all('/Quatriem/', $name)) {
                $card['sup'] = "ème";
                $card['root'] = "4";
            }
            elseif (preg_match_all('/Troisie/', $name)) {
                $card['sup'] = "ère";
                $card['root'] = "3";
            }
            elseif (preg_match_all('/Seconde/', $name)) {
                $card['sup'] = "nde";
                $card['root'] = "2";
            }
            elseif (preg_match_all('/Premi/', $name)) {

                $card['sup'] = "ère";

                $card['root'] = "1";
            }
            elseif (preg_match_all('/Terminale/', $name)) {

                $card['sup'] = "le";

                $card['root'] = "T";
                
            }
            else{

                return ['sup' => "", 'idc' => "", 'root' => $name];
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

            return ['sup' => "", 'idc' => "", 'root' => $name];
        }
    }

}

if(!function_exists('getRand')){

    function getRand($min = 2, $max = 234)
    {
        return rand($min, $max);
    }

}

if(!function_exists('getRandom')){

    function getRandom($min = 2, $max = 234)
    {
        return rand($min, $max);
    }

}

if(!function_exists('randomNumber')){

    function randomNumber($min = 2, $max = 234)
    {
        return rand($min, $max);
    }

}

if(!function_exists('randNumber')){

    function randNumber($min = 2, $max = 234)
    {
        return rand($min, $max);
    }

}

if(!function_exists('randColor')){

    function randColor($text = 'bg-', $except = ['indigo', 'blue'])
    {
        $colors = ['sky', 'amber', 'green', 'red', 'orange', 'gray', 'yellow', 'indigo', 'blue', 'emerald', 'fuchsia', 'cyan', 'lime', 'pink', 'purple', 'teal', 'violet', 'rose'];

        $filters = array_values(array_diff($colors, $except));

        $color = collect($filters)->random();

        $weight = collect(['200', '300', '400', '500', '600', '700', '800', '900'])->random();

        return $text . '' . $color . '-' . $weight;
    }

}


if(!function_exists('user_profil_photo')){

    function user_profil_photo($user = null)
    {
        if($user->profil_photo) 

            return url('storage', $user->profil_photo);

        else

            return asset("/images/image1.jpg");

    }

}


if(!function_exists('school_images')){

    function school_images($school = null)
    {
		$defaults = [
			asset("/images/school1.jpg"),
			asset("/images/school2.jpg"),
			asset("/images/school3.jpg"),
			asset("/images/school6.jpg"),
			asset("/images/school17.jpg"),
			asset("/images/school10.jpg"),
			asset("/images/school20.jpg"),
			asset("/images/school13.jpg"),

		];

        if(!$school) return $defaults;
        
        if($school->images) {

			$images = [];

			foreach($school->images as $image_path){

				$images[] = url('storage', $image_path);

			}

			return $images;
		}
		else{

			return $defaults;
		}
    }

}





if(!function_exists('getTenant')){

    function getTenant($value, $column = "id")
    {
        return Tenant::where($column, $value)->first();
    }

}

if(!function_exists('getTenants')){

    function getTenants($value, $column = "id")
    {
        return Tenant::where($column, $value)->get();
    }

}

if(!function_exists('getNotDeletedTenants')){

    function getNotDeletedTenants()
    {
        return Tenant::withoutTrashed()->get();
    }

}

if(!function_exists('getDeletedTenants')){

    function getDeletedTenants()
    {
        return Tenant::onlyTrashed()->get();
    }

}


if(!function_exists('__moneyFormat')){

    function __moneyFormat($amount, $currency = "FCFA")
    {
        if($currency) $value = number_format($amount, 0, ',', ' ') . " " . $currency;

        else $value = number_format($amount, 0, ',', ' ');

        return $value;
    }

}



if(!function_exists('__formatDateAgo')){

    function __formatDateAgo($start, $end = null)
    {
        Carbon::setLocale('fr');

        $start = Carbon::parse($start);

        if(!$end) $end = now();

        $end = Carbon::parse($end);
        
        return $start->diffForHumans($end);
    }

}

if(!function_exists('__ago')){

    function __ago($start, $end = null)
    {
        Carbon::setLocale('fr');

        $start = Carbon::parse($start);

        if(!$end) $end = now();

        $end = Carbon::parse($end);
        
        return $start->diffForHumans($end);
    }

}

if(!function_exists('__asAgo')){

    function __asAgo($start, $end = null)
    {
        Carbon::setLocale('fr');

        $start = Carbon::parse($start);

        if(!$end) $end = now();

        $end = Carbon::parse($end);
        
        return $start->diffForHumans($end);
    }

}

if(!function_exists('__agoFormat')){

    function __agoFormat($start, $end = null)
    {
        Carbon::setLocale('fr');

        $start = Carbon::parse($start);

        if(!$end) $end = now();

        $end = Carbon::parse($end);
        
        return $start->diffForHumans($end);
    }

}

if(!function_exists('__formatDate')){

    function __formatDate($date)
    {
        Carbon::setLocale('fr');

        $formatted = ucfirst(Carbon::parse($date)->translatedFormat('d F Y'));
        
        return $formatted;
    }



}
if(!function_exists('__formatDateDiff')){

    function __formatDateDiff($from, $to = null)
    {
        Carbon::setLocale('fr');

        if(!$to) $date1 = Carbon::today();

        else $date1 = Carbon::parse($to);

        $target = Carbon::parse($from);

        $joursRestants = $date1->diffInDays($target, false);

        if($joursRestants >= 1) :

            return floor($date1->diffInDays($target, true)) . " jours restants";

        else :

            return $date1->diff($target)->format('%d jours, %h h, %i min');
            
        endif;
    }

}

if(!function_exists('__formatDateTime')){

    function __formatDateTime($datetime)
    {
        Carbon::setLocale('fr');

        if(!$datetime) $datetime = Carbon::now();

        $formatted = ucwords(Carbon::parse($datetime)->translatedFormat('l j F Y \à H\h i\m s\s'));

        return $formatted;
    }

}

if(!function_exists('__moneyFormat')){

    function __moneyFormat($amount)
    {
        return number_format($amount, 0, ',', ' ');
    }

}

if(!function_exists('deleteFileIfExists')){

    function deleteFileIfExists($path)
    {
        if(File::exists($path)){

            File::delete($path);
        }
    }

}



if(!function_exists('rankFormat')){

    function rankFormat($rank)
    {
        if($rank == 1){

            return ['rank'=> 1, 'sup' => 'er'];
        }
        elseif($rank > 1){

            return ['rank'=> $rank, 'sup' => 'è'];
            
        }
        else{

            return ['rank'=> $rank, 'sup' => 'null'];
        }

    }

}
