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
        if (is_null($value)) {
            return null;
        }

        foreach (config('mentions.scale') as $tier) {
            if ($value >= $tier['min'] && $value <= $tier['max']) {
                return $tier['label'];
            }
        }

        return null;
    }
}