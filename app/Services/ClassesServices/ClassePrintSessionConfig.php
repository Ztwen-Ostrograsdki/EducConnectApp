<?php

namespace App\Services\ClassesServices;

class ClassePrintSessionConfig
{
    protected static array $filterSessionKeys = [
        'print_classes_active_status',
        'print_classes_locked_status',
        'print_classes_pp_status',
        'print_classes_has_students_status',
        'print_classes_has_teachers_status',
        'print_classes_filiar_selected',
        'print_classes_serial_selected',
        'print_classes_promotion_selected',
        'print_classes_promotions_grouped_selected',
        'print_classes_level_selected',
    ];

    protected static string $columnsSessionKey = 'classe-list-selected-columns';

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
            "activeConfig"       => session('print_classes_active_status'),
            "lockedConfig"       => session('print_classes_locked_status'),
            "ppConfig"           => session('print_classes_pp_status'),
            "hasStudentsConfig"  => session('print_classes_has_students_status'),
            "hasTeachersConfig"  => session('print_classes_has_teachers_status'),
            "filiar_id"          => session('print_classes_filiar_selected'),
            "serial_id"          => session('print_classes_serial_selected'),
            "promotion_id"       => session('print_classes_promotion_selected'),
            "promotionInGroups"  => session('print_classes_promotions_grouped_selected'),
            "level"              => session('print_classes_level_selected'),
        ];
    }

    public static function tableColumns(): array
    {
        return ClassePrintColumns::resolve(null);
    }
}