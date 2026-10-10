<div class="space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700 dark:text-emerald-400">Vie scolaire</p>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Emploi du temps — {{ $classe->name }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Consultez les cours prévus pour cette classe.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($schoolYears->isNotEmpty())
                <label class="sr-only" for="class-timetable-year">Année scolaire</label>
                <select id="class-timetable-year" wire:model.live="schoolYearId"
                    class="rounded-xl border-slate-200 bg-white py-2 pl-3 pr-8 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}">{{ $year->min_year }}–{{ $year->max_year }}</option>
                    @endforeach
                </select>
            @endif
            @if($timePlan)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-950/40 dark:text-emerald-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Publié
                </span>
            @endif
        </div>
    </div>

    @if(!$schoolYearId)
        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center dark:border-slate-700 dark:bg-slate-900/50">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm dark:bg-slate-800"><x-lucide-calendar-days class="h-6 w-6" /></div>
            <h3 class="mt-3 font-semibold text-slate-900 dark:text-white">Aucune année scolaire disponible</h3>
            <p class="mt-1 text-sm text-slate-500">L’emploi du temps apparaîtra ici dès qu’une année scolaire sera associée à la classe.</p>
        </div>
    @elseif(!$timePlan)
        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900/50">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-emerald-600 shadow-sm dark:bg-slate-800"><x-lucide-calendar-clock class="h-6 w-6" /></div>
            <h3 class="mt-3 font-semibold text-slate-900 dark:text-white">Emploi du temps non disponible</h3>
            <p class="mx-auto mt-1 max-w-md text-sm text-slate-500 dark:text-slate-400">Aucun emploi du temps publié n’a été trouvé pour {{ $classe->name }} sur cette année scolaire. Il sera affiché ici après sa publication par la direction.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300"><x-lucide-calendar-check class="h-5 w-5" /></span><div><p class="text-xs text-slate-500">Année scolaire</p><p class="font-semibold text-slate-900 dark:text-white">{{ $timePlan->schoolYear?->min_year }}–{{ $timePlan->schoolYear?->max_year }}</p></div></div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700 dark:bg-sky-950/50 dark:text-sky-300"><x-lucide-book-open class="h-5 w-5" /></span><div><p class="text-xs text-slate-500">Séances planifiées</p><p class="font-semibold text-slate-900 dark:text-white">{{ $timePlan->slots->count() }} créneau(x)</p></div></div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300"><x-lucide-users class="h-5 w-5" /></span><div><p class="text-xs text-slate-500">Matières distinctes</p><p class="font-semibold text-slate-900 dark:text-white">{{ $timePlan->slots->pluck('classeSubjectOfSchoolYear.subject_id')->filter()->unique()->count() }}</p></div></div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-2 border-b border-slate-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
                <div><h3 class="font-semibold text-slate-900 dark:text-white">Planning hebdomadaire</h3><p class="text-xs text-slate-500">Les enseignants affichés proviennent des affectations pédagogiques actuelles.</p></div>
                <span class="text-xs text-slate-500">{{ $timePlan->title }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full table-fixed border-collapse text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-950/60">
                        <tr>
                            <th class="w-32 border-b border-r border-slate-200 px-3 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:border-slate-800">Horaire</th>
                            @foreach($days as $day => $dayName)
                                <th class="border-b border-r border-slate-200 px-3 py-3 text-center font-semibold text-emerald-800 last:border-r-0 dark:border-slate-800 dark:text-emerald-300">{{ $dayName }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeRanges as $range)
                            <tr class="align-top">
                                <th class="border-b border-r border-slate-200 bg-slate-50/70 px-3 py-4 text-xs font-medium text-slate-600 dark:border-slate-800 dark:bg-slate-950/30 dark:text-slate-300">
                                    {{ \Illuminate\Support\Carbon::parse($range[0])->format('H:i') }}<span class="mx-1 text-slate-400">–</span>{{ \Illuminate\Support\Carbon::parse($range[1])->format('H:i') }}
                                </th>
                                @foreach($days as $day => $dayName)
                                    @php $slot = $slotsByDay[$day]->first(fn ($item) => $item->starts_at === $range[0] && $item->ends_at === $range[1]); @endphp
                                    <td class="border-b border-r border-slate-200 p-1.5 last:border-r-0 dark:border-slate-800">
                                        @if($slot)
                                            @php $subject = $slot->subject; $teacher = $slot->teacher; @endphp
                                            <div class="min-h-[92px] rounded-xl border border-emerald-100 bg-emerald-50/80 p-2.5 dark:border-emerald-900/70 dark:bg-emerald-950/30">
                                                <p class="font-semibold leading-5 text-slate-900 dark:text-white">{{ $subject?->name ?? $slot->label ?? 'Cours' }}</p>
                                                <p class="mt-1 text-xs text-slate-600 dark:text-slate-300">{{ $teacher?->name ?? 'Enseignant non renseigné' }}</p>
                                                @if($slot->label && $subject && $slot->label !== $subject->name)
                                                    <p class="mt-1 text-xs text-slate-500">{{ $slot->label }}</p>
                                                @endif
                                                @if($slot->notes)<p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $slot->notes }}</p>@endif
                                            </div>
                                        @else
                                            <div class="min-h-[92px] rounded-xl bg-slate-50/70 dark:bg-slate-950/20"></div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($timePlan->notes)
            <div class="rounded-xl border border-sky-100 bg-sky-50 p-4 text-sm text-sky-900 dark:border-sky-900/60 dark:bg-sky-950/30 dark:text-sky-200"><span class="font-semibold">Note de la direction :</span> {{ $timePlan->notes }}</div>
        @endif
    @endif
</div>
