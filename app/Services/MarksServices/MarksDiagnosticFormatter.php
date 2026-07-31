<?php

namespace App\Services\MarksServices;

class MarksDiagnosticFormatter
{
    public static array $columnWidths = [
        'teacher'                 => 18,
        'classe'                  => 10,
        'subject'                 => 12,
        'checked_types'           => 16,
        'status'                  => 10,
        'students_without_count'  => 8,
        'students_without_list'   => 26,
    ];

    public static function getData($teacher, array $column, array $context = []): string
    {
        $value = static::resolveValue($teacher, $column['key'], $context);

        return static::formatValue($value, $column['type'] ?? 'text');
    }

    protected static function resolveValue($teacher, string $key, array $context = []): mixed
    {
        return match ($key) {
            'teacher'                 => $teacher->getFullName(),
            'classe'                  => $context['classeLabel'] ?? null,
            'subject'                 => $context['subjectLabel'] ?? null,
            'checked_types'           => $context['checkedTypes'] ?? null,
            'status'                  => $context['hasMarks'] ?? null,
            'students_without_count'  => $context['missingCount'],
            'students_without_list'   => $context['missingList'],
            default                   => null,
        };
    }

    protected static function formatValue(mixed $value, string $type): string
    {
        return match ($type) {
            'badge' => static::statusBadge($value),
            default => ($value !== null && $value !== '') ? e((string) $value) : '—',
        };
    }

    protected static function statusBadge(mixed $hasMarks): string
    {
        return $hasMarks
            ? '<span class="statut-badge statut-badge--actif">Notes saisies</span>'
            : '<span class="statut-badge statut-badge--inactif">Sans notes</span>';
    }

    public static function normalizedWidths(array $tableColumns): array
    {
        $raw = collect($tableColumns)->mapWithKeys(fn (array $col) => [$col['key'] => static::$columnWidths[$col['key']] ?? 10]);
        $sum = $raw->sum();
        if ($sum <= 0) return [];
        return $raw->map(fn ($w) => round(($w / $sum) * 96, 2) . '%')->toArray();
    }
}