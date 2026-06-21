<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Str;

/**
 * Nécessite sur la classe consommatrice :
 * - public ?string $schoolYear
 * - public ?string $year_min
 * - public ?string $year_max
 * - public string  $periode_type
 * - public array   $periods
 */
trait ManagesSchoolYearPeriods
{
    public function initPeriods(): void
    {
        $count = Str::lower($this->periode_type) === 'trimestre' ? 3 : 2;
        $current = $this->periods;

        $this->periods = array_fill(0, $count, ['start' => null, 'end' => null]);

        // préserve les dates déjà saisies si le nombre de périodes ne change pas
        foreach ($current as $i => $p) {
            if (isset($this->periods[$i])) {
                $this->periods[$i] = $p;
            }
        }
    }

    public function updatedPeriodeType(): void
    {
        $this->initPeriods();
    }

    public function updatedPeriods($value, $key): void
    {
        [$index, $field] = explode('.', $key);
        $index = (int) $index;

        if ($field === 'start' && !empty($this->periods[$index]['end']) && $this->periods[$index]['end'] <= $value) {
            $this->periods[$index]['end'] = null;
        }

        if ($field === 'end' && !empty($this->periods[$index + 1]['start'] ?? null) && $this->periods[$index + 1]['start'] <= $value) {
            $this->periods[$index + 1]['start'] = null;
        }
    }

    public function updatedSchoolYear(?string $value): void
    {
        if (! $value) {
            $this->year_min = null;
            $this->year_max = null;
            return;
        }

        [$startYear, $endYear] = explode('-', $value);
        $this->year_min = "{$startYear}-09-01";
        $this->year_max = "{$endYear}-08-31";
    }

    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    protected function periodsRules(): array
    {
        $rules = [];

        foreach ($this->periods as $i => $period) {
            $startRule = ['required', 'date', "after_or_equal:{$this->year_min}", "before_or_equal:{$this->year_max}"];

            if ($i > 0) {
                $startRule[] = "after:periods." . ($i - 1) . ".end";
            }

            $rules["periods.{$i}.start"] = $startRule;

            $rules["periods.{$i}.end"] = [
                'required', 'date',
                "after:periods.{$i}.start",
                "before_or_equal:{$this->year_max}",
            ];
        }

        return $rules;
    }

    public function formattedDate(?string $date): ?string
    {
        if (! $date) {
            return null;
        }

        $carbon = \Carbon\Carbon::parse($date)->locale('fr');

        return $carbon->translatedFormat('l d') . ' '
            . Str::ucfirst($carbon->translatedFormat('F')) . ' '
            . $carbon->format('Y');
    }

    protected function buildPeriodsData(): array
    {
        $periodsData = [];

        foreach ($this->periods as $k => $period) {
            $label = Str::lower($this->periode_type) . ' ' . ($k + 1);
            $periodsData[$label] = $period;
        }

        return $periodsData;
    }
}