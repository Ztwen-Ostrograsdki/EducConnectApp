<?php

namespace App\Services;

class MentionService
{
    /**
     * Retourne la mention correspondant à une moyenne (0-20).
     * Retourne null si $value est null (apprenant sans moyenne calculable).
     */
    public function forValue(?float $value): ?string
    {
        if (!($value))  return null;

        foreach (config('mentions.scale') as $range) {
            if ($value >= $range['min'] && $value <= $range['max']) {
                return $range['label'];
            }
        }

        return null;
    }
}