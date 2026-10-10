<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-5">

        {{-- ========== HEADER ========== --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-400/80 mb-1">
                    Vie scolaire
                </p>
                <h2 class="text-xl font-bold tracking-tight text-white">
                    Emploi du temps
                    <span class="text-slate-500 font-normal">·</span>
                    {{ $this->classe->name }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Consultez les cours prévus pour cette classe
                </p>
            </div>
            @if ($this->timePlan)

                @if ($this->timePlan->published)
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                             bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Publié
                    </span>
                @elseif($this->timePlan->archived)
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                             bg-red-500/15 text-red-400 border border-red-500/25">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                        Archivé
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                             bg-orange-500/15 text-orange-400 border border-orange-500/25">
                        <span class="h-1.5 w-1.5 rounded-full bg-orange-400"></span>
                        Brouillon
                    </span>
                @endif
            @endif
        </div>

        @if (!$this->activeYear?->id)
            {{-- Pas d'année --}}
            <div class="rounded-2xl border border-dashed border-white/[0.08] bg-white/[0.02] py-14 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/[0.04] mb-3">
                    <x-lucide-calendar-days class="w-6 h-6 text-slate-500" />
                </div>
                <h3 class="font-semibold text-white">Aucune année scolaire disponible</h3>
                <p class="mt-1 text-sm text-slate-500 max-w-md mx-auto">
                    L’emploi du temps apparaîtra ici dès qu’une année scolaire sera associée à la classe.
                </p>
            </div>
        @elseif (!$this->timePlan)
            {{-- Pas de plan publié --}}
            <div class="rounded-2xl border border-dashed border-white/[0.08] bg-white/[0.02] py-14 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-500/10 mb-3">
                    <x-lucide-calendar-clock class="w-6 h-6 text-emerald-400" />
                </div>
                <h3 class="font-semibold text-white">Emploi du temps non disponible</h3>
                <p class="mt-1 text-sm text-slate-500 max-w-md mx-auto">
                    Aucun emploi du temps publié pour
                    <span class="text-slate-300">{{ $this->classe->name }}</span>
                    sur cette année. Il s’affichera ici après publication par la direction.
                </p>
            </div>
        @else
            {{-- ========== KPIs ========== --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4 flex items-center gap-3">
                    <span
                        class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/20
                                 flex items-center justify-center shrink-0">
                        <x-lucide-calendar-check class="w-5 h-5 text-emerald-400" />
                    </span>
                    <div>
                        <p class="text-[11px] text-slate-500">Année scolaire</p>
                        <p class="font-semibold text-white">
                            {{ $this->timePlan->schoolYear?->slug }}
                        </p>
                    </div>
                </div>
                <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4 flex items-center gap-3">
                    <span
                        class="w-10 h-10 rounded-xl bg-sky-500/15 border border-sky-500/20
                                 flex items-center justify-center shrink-0">
                        <x-lucide-book-open class="w-5 h-5 text-sky-400" />
                    </span>
                    <div>
                        <p class="text-[11px] text-slate-500">Séances planifiées</p>
                        <p class="font-semibold text-white">
                            {{ $this->timePlan->slots->count() }} créneau(x)
                        </p>
                    </div>
                </div>
                <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4 flex items-center gap-3">
                    <span
                        class="w-10 h-10 rounded-xl bg-violet-500/15 border border-violet-500/20
                                 flex items-center justify-center shrink-0">
                        <x-lucide-users class="w-5 h-5 text-violet-400" />
                    </span>
                    <div>
                        <p class="text-[11px] text-slate-500">Matières distinctes</p>
                        <p class="font-semibold text-white">
                            {{ $this->timePlan->slots->pluck('classeSubjectOfSchoolYear.subject_id')->filter()->unique()->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ========== GRILLE ========== --}}
            {{-- ========== GRILLE (scroll horizontal fiable) ========== --}}
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02]">
                <div
                    class="flex flex-col gap-1 border-b border-white/[0.05] px-4 py-3
                sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-white">Planning hebdomadaire</h3>
                        <p class="text-[11px] text-slate-500">
                            Enseignants issus des affectations pédagogiques actuelles
                        </p>
                    </div>
                    @if ($this->timePlan->title)
                        <span class="text-xs text-slate-500">{{ $this->timePlan->title }}</span>
                    @endif
                </div>

                {{-- Flex : colonne fixe + zone scroll --}}
                <div class="flex">
                    {{-- Colonne HORAIRE (fixe, ne scroll pas) --}}
                    <div class="shrink-0 w-[5.5rem] sm:w-32 border-r text-center border-white/[0.06] bg-[#0c101c]">
                        <div
                            class="h-11 flex items-center px-2 sm:px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-white/[0.05] text-center">
                            Horaire
                        </div>
                        @foreach ($this->timeRanges as $range)
                            <div
                                class="min-h-[110px] px-2 sm:px-3 py-4 text-sm font-mono font-bold text-slate-400 border-b border-white/[0.05] inline-flex items-center">
                                <span>
                                    {{ \Illuminate\Support\Carbon::parse($range[0])->format('H\hi') }}
                                    <span class="text-slate-600">–</span>
                                    {{ \Illuminate\Support\Carbon::parse($range[1])->format('H\hi') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Jours (scroll horizontal uniquement) --}}
                    <div class="flex-1 min-w-0 overflow-x-auto overscroll-x-contain">
                        <div class="min-w-[720px]">
                            {{-- En-têtes jours --}}
                            <div class="grid border-b border-white/[0.05]"
                                style="grid-template-columns: repeat({{ count($this->days) }}, minmax(7.9rem, 1fr));">
                                @foreach ($this->days as $day => $dayName)
                                    <div
                                        class="h-11 flex items-center justify-center px-2 text-sm font-semibold text-emerald-400 border-r border-white/[0.05] last:border-r-0">
                                        {{ $dayName }}
                                    </div>
                                @endforeach
                            </div>

                            {{-- Lignes créneaux --}}
                            @foreach ($this->timeRanges as $range)
                                <div class="grid border-b border-white/[0.05]"
                                    style="grid-template-columns: repeat({{ count($this->days) }}, minmax(7.5rem, 1fr));">
                                    @foreach ($this->days as $day => $dayName)
                                        @php
                                            $slot = $this->slotsByDay[$day]->first(
                                                fn($item) => $item->starts_at === $range[0] &&
                                                    $item->ends_at === $range[1],
                                            );
                                        @endphp
                                        <div class="min-h-[104px] p-1.5 border-r border-white/[0.05] last:border-r-0">
                                            @if ($slot)
                                                @php
                                                    $subject = $slot->subject;
                                                    $teacher = $slot->teacher;
                                                @endphp
                                                <div
                                                    class="h-full min-h-[92px] rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-2.5 flex flex-col gap-1.5">

                                                    {{-- Matière --}}
                                                    <div class="flex items-start gap-1.5">
                                                        <x-lucide-book-open
                                                            class="w-3.5 h-3.5 text-emerald-400 shrink-0 mt-0.5" />
                                                        <p class="font-semibold leading-5 text-white text-sm">
                                                            {{ $subject?->code ?? ($slot->label ?? 'Cours') }}
                                                        </p>
                                                    </div>

                                                    {{-- Enseignant --}}
                                                    <div class="flex items-center gap-1.5">
                                                        <x-lucide-user class="w-3.5 h-3.5 text-slate-500 shrink-0" />
                                                        <p class="text-xs text-slate-400 truncate">
                                                            {{ $teacher?->getFullName() ?? 'Enseignant non renseigné' }}
                                                        </p>
                                                    </div>

                                                    {{-- Libellé optionnel --}}
                                                    @if ($slot->label && $subject && $slot->label !== $subject->name)
                                                        <div class="flex items-center gap-1.5">
                                                            <x-lucide-tag class="w-3 h-3 text-slate-600 shrink-0" />
                                                            <p class="text-[11px] text-slate-500 truncate">
                                                                {{ $slot->label }}</p>
                                                        </div>
                                                    @endif

                                                    {{-- Notes --}}
                                                    @if ($slot->notes)
                                                        <div class="flex items-start gap-1.5">
                                                            <x-lucide-sticky-note
                                                                class="w-3 h-3 text-slate-600 shrink-0 mt-0.5" />
                                                            <p class="line-clamp-2 text-[11px] text-slate-500">
                                                                {{ $slot->notes }}</p>
                                                        </div>
                                                    @endif

                                                    {{-- Horaires + durée --}}
                                                    <div class="pt-1 mt-3 space-y-0.5">
                                                        <div
                                                            class="flex items-center gap-1.5 font-mono text-xs text-orange-400">
                                                            <x-lucide-clock class="w-3.5 h-3.5 shrink-0" />
                                                            <span>{{ $slot->start }}</span>
                                                            <span class="text-orange-500/50">–</span>
                                                            <span>{{ $slot->end }}</span>
                                                        </div>
                                                        <div
                                                            class="flex items-center gap-1.5 font-mono text-[11px] text-orange-500/80">
                                                            <x-lucide-hourglass class="w-3 h-3 shrink-0" />
                                                            <span>{{ $slot->duration }}H de cours</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="h-full min-h-[92px] rounded-xl bg-white/[0.02]"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Note direction --}}
            @if ($this->timePlan->notes)
                <div class="rounded-xl border border-sky-500/20 bg-sky-500/10 p-4 text-sm text-sky-200">
                    <span class="font-semibold text-sky-300">Note de la direction :</span>
                    {{ $this->timePlan->notes }}
                </div>
            @endif
        @endif

    </div>
</div>

