<?php

namespace App\Services\ClassesServices;

class MoyenneIntervalStatsSessionConfig
{
    protected static array $filterSessionKeys = [
        'print_moystats_classe_selected',
        'print_moystats_filiar_selected',
        'print_moystats_serial_selected',
        'print_moystats_promotion_selected',
        'print_moystats_promotions_grouped_selected',
        'print_moystats_period_selected',
        'print_moystats_grouped_by',
        'print_moystats_breakpoints',
    ];

    public static function hasActiveSelection(): bool
    {
        foreach (self::$filterSessionKeys as $key) {
            if (session()->has($key)) return true;
        }
        return false;
    }

    public static function filterConfig(): array
    {
        return [
            "classe_id"         => session('print_moystats_classe_selected'),
            "filiar_id"         => session('print_moystats_filiar_selected'),
            "serial_id"         => session('print_moystats_serial_selected'),
            "promotion_id"      => session('print_moystats_promotion_selected'),
            "promotionInGroups" => session('print_moystats_promotions_grouped_selected'),
        ];
    }

    public static function period(): ?int
    {
        $v = session('print_moystats_period_selected');
        return $v ? (int) $v : null;
    }

    public static function groupedBy(): string
    {
        return session('print_moystats_grouped_by', 'promotionInGroups');
    }

    public static function breakpoints(): array
    {
        return session('print_moystats_breakpoints', [7, 9, 10, 12, 14, 16]);
    }
}