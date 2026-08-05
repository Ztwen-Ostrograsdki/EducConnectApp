<?php

namespace App\Services\ClassesServices;

class MoyenneIntervalStatsFormatter
{
    public static function formatCell(array $row, string $key): string
    {
        return match (true) {
            $key === 'label'          => e($row['label']),
            $key === 'total'          => (string) $row['total'],
            $key === 'garcons'        => (string) $row['garcons'],
            $key === 'filles'         => (string) $row['filles'],
            $key === 'abandons'       => (string) $row['abandons'],
            $key === 'bestMoy'        => $row['bestMoy'] !== null ? number_format($row['bestMoy'], 2) : '—',
            $key === 'worstMoy'       => $row['worstMoy'] !== null ? number_format($row['worstMoy'], 2) : '—',
            $key === 'bestStudentName' => $row['bestStudentName'] ? e($row['bestStudentName']) : '—',
            default => '—',
        };
    }

    /**
     * Retourne [effectif, pourcentage_formaté] pour un intervalle donné,
     * plutôt qu'une chaîne combinée — la vue affiche désormais deux <td> séparés.
     */
    public static function intervalCount(array $row, int $index): int
    {
        return $row['intervalCounts'][$index] ?? 0;
    }

    public static function intervalPercentage(array $row, int $index): string
    {
        $count = self::intervalCount($row, $index);
        $total = $row['total'] ?: 0;

        if ($total <= 0) return '—';

        $pct = round(($count / $total) * 100, 1);

        return $pct . '%';
    }


	public static function successCount(array $row): int
	{
		return $row['successCount'] ?? 0;
	}

	public static function successPercentage(array $row): string
	{
		$count = self::successCount($row);
		$total = $row['total'] ?: 0;

		if ($total <= 0) return '—';

		$pct = round(($count / $total) * 100, 1);

		return $pct . '%';
	}
}