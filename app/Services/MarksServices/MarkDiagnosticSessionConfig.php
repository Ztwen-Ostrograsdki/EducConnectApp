<?php

namespace App\Services\MarksServices;

class MarkDiagnosticSessionConfig
{
    protected static array $filterSessionKeys = [
        'print_diag_classe_selected', 'print_diag_filiar_selected', 'print_diag_serial_selected',
        'print_diag_promotion_selected', 'print_diag_promotions_grouped_selected',
        'print_diag_subject_selected', 'print_diag_period_selected',
        'print_diag_status', 'print_diag_mark_types',
    ];

    protected static string $columnsSessionKey = 'mark-diagnostic-selected-columns';

    public static function hasActiveSelection(): bool
    {
        foreach (self::$filterSessionKeys as $key) {
            if (session()->has($key)) return true;
        }
        return session()->has(self::$columnsSessionKey);
    }

    public static function filterConfig(): array
    {
        return [
            "classe_id"         => session('print_diag_classe_selected'),
            "filiar_id"         => session('print_diag_filiar_selected'),
            "serial_id"         => session('print_diag_serial_selected'),
            "promotion_id"      => session('print_diag_promotion_selected'),
            "promotionInGroups" => session('print_diag_promotions_grouped_selected'),
            "subject_id"        => session('print_diag_subject_selected'),
        ];
    }

    public static function period(): ?int
    {
        $v = session('print_diag_period_selected');
        return $v ? (int) $v : null;
    }

    public static function status(): string
    {
        return session('print_diag_status', 'both');
    }

    public static function markTypes(): array
    {
        return session('print_diag_mark_types', ['interro1', 'interro2', 'interro3', 'interro4', 'devoir1', 'devoir2']);
    }
}