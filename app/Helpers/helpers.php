<?php

use Illuminate\Support\Str;

if (! function_exists('format_price_fcfa')) {
    function format_price_fcfa(string|float $amount)
    {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
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