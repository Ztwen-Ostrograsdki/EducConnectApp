<?php

use App\Models\SchoolYear;
use App\Tools\CentralTools;
use Illuminate\Support\Str;

if (! function_exists('format_price_fcfa')) {
    function format_price_fcfa(string|float $amount)
    {
        return number_format($amount, 0, ',', ' ').' FCFA';
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

                $school_year = date('Y') . ' - ' . intval(date('Y') + 1);
            }
            else{

                $school_year = intval(date('Y') - 1) . ' - ' . intval(date('Y'));
            }
            if(session()->has('school_year_selected') && session('school_year_selected')){

                $school_year = session('school_year_selected');

                session()->put('school_year_selected', $school_year);
            }
            else{

                session()->put('school_year_selected', $school_year);
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

            session()->put('school_year_selected', $school_year);

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

            $school_year = date('Y') . ' - ' . intval(date('Y') + 1);
        }
        else{

            $school_year = intval(date('Y') - 1) . ' - ' . intval(date('Y'));
        }
        if(session()->has('school_year_selected') && session('school_year_selected')){

            $school_year = session('school_year_selected');

            session()->put('school_year_selected', $school_year);
        }
        else{

            session()->put('school_year_selected', $school_year);
        }

        $school_year = $school_years[$school_year];


        session()->put('school_year_selected', $school_year);

        return $school_year;

    }
}
