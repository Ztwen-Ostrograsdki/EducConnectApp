<?php

namespace App\Services\MarksServices;

class MarksPrintFormatter
{
    public static array $columnWidths = [
        'student'          => 20,
        'classe'           => 8,
        'interro1'         => 6,
        'interro2'         => 6,
        'interro3'         => 6,
        'interro4'         => 6,
        'moy_interro'      => 8,
        'devoir1'          => 8,
        'devoir2'          => 8,
        'compo'            => 9,
        'moy'              => 8,
        'moy_coef'         => 9,
        'moy_semestrielle' => 10,
        'rang'             => 6,
        'observations'     => 10,
    ];

    public static function getData($student, array $column, array $context = []): string
    {
        $value = static::resolveValue($student, $column['key'], $context);

        return static::formatValue($value, $column['type'] ?? 'text');
    }

    protected static function resolveValue($student, string $key, array $context = []): mixed
    {
        return match ($key) {
            'student'      => $student->getFullName(),
            'classe'       => $context['classeLabel'] ?? null,
            'average'      => $context['average'] ?? null,
            'observations' => null,
            default        => $context[$key] ?? null,
        };
    }

    protected static function formatValue(mixed $value, string $type): string
    {
        return match ($type) {
            'mark' => is_numeric($value) ? number_format($value, 2) : '—',

            'rank' => $value ? '#' . e($value) : '—',

            'blank' => '&nbsp;',

            default => ($value !== null && $value !== '') ? e((string) $value) : '—',
        };
    }

    public static function normalizedWidths(array $tableColumns): array
    {
        $raw = collect($tableColumns)
            ->mapWithKeys(fn (array $col) => [$col['key'] => static::$columnWidths[$col['key']] ?? 10]);

        $sum = $raw->sum();

        if ($sum <= 0) return [];

        return $raw->map(fn ($w) => round(($w / $sum) * 96, 2) . '%')->toArray();
    }
}