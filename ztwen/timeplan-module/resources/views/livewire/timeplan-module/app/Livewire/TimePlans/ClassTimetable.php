<?php

namespace App\Livewire\TimePlans;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\TimePlan;
use Livewire\Component;
use Illuminate\Support\Collection;

class ClassTimetable extends Component
{
    public Classe $classe;

    /** @var Collection<int, SchoolYear> */
    public Collection $schoolYears;

    public ?int $schoolYearId = null;

    public function mount(Classe $classe, ?int $schoolYearId = null): void
    {
        $this->classe = $classe;
        $this->schoolYears = SchoolYear::query()
            ->whereHas('classes', fn ($query) => $query->whereKey($classe->getKey()))
            ->orderByDesc('min_year')
            ->get();

        $this->schoolYearId = $schoolYearId
            ?? $classe->school_year_id
            ?? $this->schoolYears->firstWhere('is_active', true)?->id
            ?? $this->schoolYears->first()?->id;
    }

    public function updatedSchoolYearId(): void
    {
        $this->loadTimetable();
    }

    protected function loadTimetable(): ?TimePlan
    {
        if (! $this->schoolYearId) {
            return null;
        }

        // Never expose draft/archived plans on the class profile.
        return TimePlan::query()
            ->where('classe_id', $this->classe->getKey())
            ->where('school_year_id', $this->schoolYearId)
            ->where('status', 'published')
            ->with([
                'schoolYear',
                'slots' => fn ($query) => $query->orderBy('starts_at'),
                'slots.classeSubjectOfSchoolYear.subject',
                'slots.classeSubjectOfSchoolYear.teacher',
            ])
            ->first();
    }

    public function render()
    {
        $timePlan = $this->loadTimetable();
        $days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];
        $slotsByDay = collect($days)->mapWithKeys(fn ($label, $day) => [
            $day => $timePlan?->slots->where('day_of_week', $day)->values() ?? collect(),
        ]);

        $timeRanges = $timePlan?->slots
            ->map(fn ($slot) => [$slot->starts_at, $slot->ends_at])
            ->unique(fn ($range) => $range[0].'|'.$range[1])
            ->sortBy(fn ($range) => $range[0])
            ->values() ?? collect();

        return view('livewire.time-plans.class-timetable', compact('timePlan', 'days', 'slotsByDay', 'timeRanges'));
    }
}
