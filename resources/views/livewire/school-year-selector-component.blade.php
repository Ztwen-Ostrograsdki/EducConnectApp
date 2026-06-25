<span>
    @if ($schoolYears)
        <select class="year-select" wire:model.live="selectedYear">
            @foreach ($schoolYears as $year)
                <option value="{{ $year->slug }}">{{ $year->slug }}</option>
            @endforeach
        </select>
    @else
        <option class="text-xs text-gray-400 font-mono" value="{{ null }}">Année scolaire indisponible</option>
    @endif

</span>

