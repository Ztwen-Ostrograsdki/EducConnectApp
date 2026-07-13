<?php

namespace App\Services\StudentsServices;


class StudentPrintSessionConfig
{
    protected static array $filterSessionKeys = [
        'print_students_trashed_status',
        'print_students_leaves_status',
        'print_students_has_classe_status',
        'print_students_classe_selected',
        'print_students_filiar_selected',
        'print_students_serial_selected',
        'print_students_promotion_selected',
        'print_students_promotions_grouped_selected',
        'print_students_gender_selected',
        'print_students_city_selected',
        'print_students_department_selected',
    ];

    protected static string $columnsSessionKey = 'student-list-selected-columns';

    /**
     * Indique si une configuration d'impression (filtres et/ou colonnes)
     * a déjà été définie en session, peu importe où (StudentsPrintsManagerComponent).
     * Sert à conditionner l'affichage du bouton d'impression ailleurs (ex: StudentsPortail).
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
            "trashedConfig"     => session('print_students_trashed_status', 'withoutTrashed'),
            "leavesConfig"      => session('print_students_leaves_status', 'onlyActives'),
            "hasClasseConfig"   => session('print_students_has_classe_status', 'onlyHasClasse'),
            "classe_id"         => session('print_students_classe_selected'),
            "filiar_id"         => session('print_students_filiar_selected'),
            "serial_id"         => session('print_students_serial_selected'),
            "promotion_id"      => session('print_students_promotion_selected'),
            "promotionInGroups" => session('print_students_promotions_grouped_selected'),
            "gender"            => session('print_students_gender_selected'),
            "city"              => session('print_students_city_selected'),
            "department"        => session('print_students_department_selected'),
        ];
    }

    public static function tableColumns(): array
    {
        return StudentPrintColumns::resolve(null);
    }
}