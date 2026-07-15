<?php

namespace App\Services\TeachersServices;

class TeacherPrintSessionConfig
{
    protected static array $filterSessionKeys = [
        'print_teachers_trashed_status',
        'print_teachers_access_status',
        'print_teachers_pp_status',
        'print_teachers_ae_status',
        'print_teachers_has_classes_status',
        'print_teachers_classe_selected',
        'print_teachers_filiar_selected',
        'print_teachers_subject_selected',
        'print_teachers_serial_selected',
        'print_teachers_promotion_selected',
        'print_teachers_promotions_grouped_selected',
        'print_teachers_gender_selected',
        'print_teachers_city_selected',
        'print_teachers_department_selected',
    ];

    protected static string $columnsSessionKey = 'teacher-list-selected-columns';

    /**
     * Indique si une configuration d'impression (filtres et/ou colonnes)
     * a déjà été définie en session, peu importe où (TeachersPrintsManagerComponent).
     * Sert à conditionner l'affichage du bouton d'impression ailleurs (ex: TeachersPortail).
     */
    public static function hasActiveSelection(): bool
    {
        foreach (self::$filterSessionKeys as $sessionKey) {
            if (session()->has($sessionKey)) {
                return true;
            }
        }

        return session()->has(self::$columnsSessionKey);
    }

    public static function filterConfig(): array
    {
        return [
            "trashedConfig"     => session('print_teachers_trashed_status', 'withoutTrashed'),
            "accessesConfig"    => session('print_teachers_access_status'),
            "ppConfig"          => session('print_teachers_pp_status'),
            "aeConfig"          => session('print_teachers_ae_status'),
            "hasClassesConfig"  => session('print_teachers_has_classes_status'),
            "classe_id"         => session('print_teachers_classe_selected'),
            "filiar_id"         => session('print_teachers_filiar_selected'),
            "subject_id"        => session('print_teachers_subject_selected'),
            "serial_id"         => session('print_teachers_serial_selected'),
            "promotion_id"      => session('print_teachers_promotion_selected'),
            "promotionInGroups" => session('print_teachers_promotions_grouped_selected'),
            "gender"            => session('print_teachers_gender_selected'),
            "city"              => session('print_teachers_city_selected'),
            "department"        => session('print_teachers_department_selected'),
        ];
    }

    public static function tableColumns(): array
    {
        return TeacherPrintColumns::resolve(null);
    }

    public static function columnsSessionKey(): string
    {
        return self::$columnsSessionKey;
    }
}