<?php

namespace App\Services\MarksServices;

class MarkRankingPrintSessionConfig
{
    protected static array $filterSessionKeys = [
        'print_ranking_classe_selected',
        'print_ranking_filiar_selected',
        'print_ranking_serial_selected',
        'print_ranking_promotion_selected',
        'print_ranking_promotions_grouped_selected',
        'print_ranking_level_selected',
        'print_ranking_subject_selected',
        'print_ranking_period_selected',
        'print_ranking_leaves_status',
        'print_ranking_gender_selected',
        'print_ranking_targeted',
        'print_ranking_limit',
        'print_ranking_grouped_by',
    ];

    protected static string $columnsSessionKey = 'mark-ranking-selected-columns';

    public static function hasActiveSelection(): bool
    {
        foreach (self::$filterSessionKeys as $sessionKey) {
            if (session()->has($sessionKey)) return true;
        }

        return session()->has(self::$columnsSessionKey);
    }

    public static function filterConfig(): array
    {
        return [
            "classe_id"         => session('print_ranking_classe_selected'),
            "filiar_id"         => session('print_ranking_filiar_selected'),
            "serial_id"         => session('print_ranking_serial_selected'),
            "promotion_id"      => session('print_ranking_promotion_selected'),
            "promotionInGroups" => session('print_ranking_promotions_grouped_selected'),
            "level"             => session('print_ranking_level_selected'),
            "leavesConfig"      => session('print_ranking_leaves_status', 'onlyActives'),
            "gender"            => session('print_ranking_gender_selected'),
        ];
    }

    public static function subjectId(): ?int
    {
        $v = session('print_ranking_subject_selected');
        return $v ? (int) $v : null;
    }

    public static function period(): ?int
    {
        $v = session('print_ranking_period_selected');
        return $v ? (int) $v : null;
    }

    public static function targeted(): string
    {
        return session('print_ranking_targeted', 'best');
    }

    public static function limit(): int
    {
        return (int) session('print_ranking_limit', 10);
    }

    public static function groupedBy(): string
    {
        return session('print_ranking_grouped_by', 'classe_id');
    }
}