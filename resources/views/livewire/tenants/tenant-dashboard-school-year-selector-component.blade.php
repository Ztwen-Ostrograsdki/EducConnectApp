<select class="year-select" wire:model.live="selectedYear">
    @foreach($schoolYears as $year)
        <option value="{{ $year }}">{{ $year }}</option>
    @endforeach
</select>


