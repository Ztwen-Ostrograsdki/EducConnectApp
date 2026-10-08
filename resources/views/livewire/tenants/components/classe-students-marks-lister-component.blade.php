<div class="min-h-screen bg-[#070a12] text-slate-100">

    {{-- Loading overlay --}}
    <div wire:loading wire:target="period,subject_slug"
        class="fixed inset-0 z-[200] flex items-center justify-center bg-[#070a12]/70 backdrop-blur-sm">
        <div class="flex flex-col items-center gap-3 text-slate-400">
            <x-lucide-loader-2 class="w-8 h-8 text-indigo-400 animate-spin" />
            <span class="text-sm font-medium">Chargement…</span>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

        {{-- ========== HEADER ========== --}}
        <header>
            <div class="flex flex-wrap items-center gap-3 mb-2">
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Notes de
                    <span class="text-sky-400 font-mono uppercase">{{ $classe->code }}</span>
                </h1>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                             bg-indigo-500/15 text-indigo-400 border border-indigo-500/25">
                    {{ count($this->studentsRows) }} apprenant{{ count($this->studentsRows) > 1 ? 's' : '' }}
                </span>
            </div>
            <p class="text-sm text-slate-500">
                Notes, moyennes et statistiques pédagogiques de la classe
            </p>
        </header>

        {{-- ========== KPI ========== --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5">
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-medium">Moyenne générale</p>
                <p class="mt-2 text-2xl sm:text-3xl font-bold text-slate-600">—</p>
            </div>

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5">
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-medium">Taux de réussite</p>
                <p class="mt-2 text-2xl sm:text-3xl font-bold text-slate-600">—</p>
            </div>

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5">
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-medium">Matière</p>
                <p
                    class="mt-2 text-lg sm:text-xl font-bold uppercase tracking-tight
                           {{ $this->subject ? 'text-white' : 'text-slate-600' }}">
                    {{ $this->subject?->code ?? 'Non sélectionnée' }}
                </p>
            </div>

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5">
                <p class="text-[11px] uppercase tracking-wider text-slate-500 font-medium">
                    {{ $this->activeYear?->periodLabel() ?? 'Période' }}
                </p>
                <p
                    class="mt-2 text-lg sm:text-xl font-bold tracking-tight
                           {{ $this->period ? 'text-white' : 'text-slate-600' }}">
                    @if ($this->period)
                        {{ $this->activeYear->periodLabel() }} {{ $this->period }}
                    @else
                        Non sélectionnée
                    @endif
                </p>
            </div>
        </div>

        {{-- ========== FILTRES ========== --}}
        <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <select wire:model.live="subject_slug"
                    class="h-11 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm
                               text-slate-300 focus:border-indigo-500/50 focus:outline-none transition">
                    <option value="">Sélectionner une matière</option>
                    @foreach ($this->availableSubjects as $subj)
                        <option value="{{ $subj->slug }}">{{ $subj->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="period"
                    class="h-11 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm
                               text-slate-300 font-mono uppercase
                               focus:border-indigo-500/50 focus:outline-none transition">
                    <option value="">{{ $this->activeYear->periodLabel() }}</option>
                    @foreach ($this->periods_types as $pv => $p)
                        <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ========== TABLEAU ========== --}}
        <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">

            {{-- Header table --}}
            <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05]">
                <h2 class="text-base sm:text-lg font-semibold text-white">
                    Notes de classe
                    @if ($this->subject)
                        <span class="text-amber-400 font-mono uppercase">
                            {{ $this->subject->code ?? $this->subject->name }}
                        </span>
                    @endif
                    @if ($this->period)
                        <span class="text-slate-500 font-normal">·</span>
                        <span class="text-sky-400 font-mono text-sm">
                            {{ $this->activeYear->periodLabel() }} {{ $this->period }}
                        </span>
                    @endif

                    @if ($this->subject)
                        @php
                            $coef_rel = $this->coef_relation;
                            if ($coef_rel) {
                                $url = route('tenant.subjects.coefs.manage', [
                                    'subject_slug' => $this->subject->slug,
                                    'uuid' => $coef_rel->uuid,
                                ]);
                            } else {
                                if ($classe->filiar_id) {
                                    $url = route('tenant.subjects.coefs.manage', [
                                        'subject_slug' => $this->subject->slug,
                                        'promotion' => $classe->promotion->name,
                                        'filiar_id' => $classe->filiar_id,
                                    ]);
                                } elseif ($classe->serial_id) {
                                    $url = route('tenant.subjects.coefs.manage', [
                                        'subject_slug' => $this->subject->slug,
                                        'promotion' => $classe->promotion->name,
                                        'serial_id' => $classe->serial_id,
                                    ]);
                                } else {
                                    $url = route('tenant.subjects.coefs.manage', [
                                        'subject_slug' => $this->subject->slug,
                                        'promotion' => $classe->promotion->name,
                                    ]);
                                }
                            }
                        @endphp
                        <a wire:navigate href="{{ auth('tenant')->user()->hasRole('directeur') ? $url : '#' }}"
                            title="Définir ou éditer le coefficient"
                            class="inline-flex items-center gap-1 ml-2 text-sm font-normal
                                  text-sky-500 hover:text-sky-300 transition-colors">
                            <span class="text-slate-600">|</span>
                            Coef:
                            @if ($coef_rel)
                                <span class="font-mono font-semibold">{{ $coef_rel->coef }}</span>
                            @else
                                <span class="text-rose-400 text-xs">non défini</span>
                            @endif
                        </a>
                    @endif
                </h2>

                @if ($this->classe_subject && $this->classe_subject->teacher)
                    <div
                        class="mt-3 inline-flex flex-wrap items-center gap-x-4 gap-y-1
                                px-3.5 py-2 rounded-xl bg-sky-500/10 border border-sky-500/20 text-xs">
                        <span class="inline-flex items-center gap-1.5 text-slate-400">
                            <x-lucide-user class="w-3.5 h-3.5 text-sky-400" />
                            <span class="text-amber-400 font-medium">
                                {{ $this->classe_subject->teacher->getFullName() }}
                            </span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-slate-500">
                            <x-lucide-phone class="w-3.5 h-3.5" />
                            {{ $this->classe_subject->teacher->user->contacts }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                @if (count($this->studentsRows))
                    <table class="w-full text-sm" style="min-width: 1000px;">
                        <thead>
                            <tr class="border-b border-white/[0.05]">
                                <th
                                    class="sticky left-0 z-10 bg-[#0c101c] px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Apprenant
                                </th>
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Int 1</th>
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Int 2</th>
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Int 3</th>
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Int 4</th>
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-indigo-400">
                                    Moy. Int</th>
                                @foreach ($this->devoirColumns() as $type => $label)
                                    <th
                                        class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        {{ $label }}
                                    </th>
                                @endforeach
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-emerald-400">
                                    Moy.</th>
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-emerald-400">
                                    Moy. Coef.</th>
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Rang</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/[0.04]">
                            @foreach ($this->studentsRows as $row)
                                @php $student = $row['student']; @endphp

                                <tr class="group hover:bg-white/[0.02] transition-colors"
                                    wire:key="student-row-{{ $student->id }}">

                                    {{-- Apprenant sticky --}}
                                    <td
                                        class="sticky left-0 z-10 bg-[#0c101c] group-hover:bg-[#0e1320] px-5 py-3 transition-colors">
                                        <a wire:navigate
                                            href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                            class="flex items-center gap-3 group/link">
                                            <span
                                                class="shrink-0 w-8 h-8 rounded-lg bg-white/[0.04] border border-white/[0.06]
                                                         flex items-center justify-center text-xs font-mono text-slate-500">
                                                {{ $loop->iteration }}
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="font-medium text-slate-200 truncate
                                                               group-hover/link:text-sky-400 transition-colors">
                                                        {{ $student->getFullName() }}
                                                    </span>
                                                    @if ($student->gender)
                                                        <span
                                                            class="shrink-0 text-[10px] font-mono uppercase px-1.5 py-0.5 rounded
                                                                     bg-white/[0.04] text-slate-500 border border-white/[0.06]">
                                                            {{ str()->initials($student->gender) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if ($student->educMaster)
                                                    <p class="text-[11px] text-slate-600 truncate mt-0.5">
                                                        {{ $student->educMaster }}
                                                    </p>
                                                @endif
                                            </div>
                                        </a>
                                    </td>

                                    {{-- Interros --}}
                                    @foreach (['interro1', 'interro2', 'interro3', 'interro4'] as $type)
                                        <td class="px-3 py-3 text-center font-mono text-xs">
                                            @if (!is_null($row['marks'][$type]))
                                                <span
                                                    class="text-slate-300">{{ number_format($row['marks'][$type], 2) }}</span>
                                            @else
                                                <span class="text-slate-700">—</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    {{-- Moy Int --}}
                                    <td
                                        class="px-3 py-3 text-center font-mono text-xs font-medium
                                               {{ !is_null($row['moy_interro']) ? 'text-indigo-400' : 'text-slate-700' }}">
                                        {{ !is_null($row['moy_interro']) ? number_format($row['moy_interro'], 2) : '—' }}
                                    </td>

                                    {{-- Devoirs --}}
                                    @foreach ($this->devoirColumns() as $type => $label)
                                        <td class="px-3 py-3 text-center font-mono text-xs">
                                            @if (!is_null($row['marks'][$type]))
                                                <span
                                                    class="text-slate-300">{{ number_format($row['marks'][$type], 2) }}</span>
                                            @else
                                                <span class="text-slate-700">—</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    {{-- Moy --}}
                                    <td
                                        class="px-3 py-3 text-center font-mono text-xs font-semibold
                                               {{ !is_null($row['moy']) ? 'text-emerald-400' : 'text-slate-700' }}">
                                        {{ !is_null($row['moy']) ? number_format($row['moy'], 2) : '—' }}
                                    </td>

                                    {{-- Moy Coef --}}
                                    <td
                                        class="px-3 py-3 text-center font-mono text-xs font-semibold
                                               {{ !is_null($row['moy_coef']) ? 'text-emerald-400' : 'text-slate-700' }}">
                                        {{ !is_null($row['moy_coef']) ? number_format($row['moy_coef'], 2) : '—' }}
                                    </td>

                                    {{-- Rang --}}
                                    <td class="px-3 py-3 text-center">
                                        @if ($row['rank'])
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono
                                                         bg-white/[0.04] text-slate-300 border border-white/[0.06]">
                                                #{{ $row['rank'] }}
                                            </span>
                                        @else
                                            <span class="text-slate-700">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="py-16 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-800/50 mb-3">
                            <x-lucide-users class="w-6 h-6 text-slate-500" />
                        </div>
                        <p class="text-sm text-slate-500">Cette classe est vide</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
