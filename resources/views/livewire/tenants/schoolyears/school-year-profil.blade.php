<div class="min-h-screen bg-[#070b14] text-slate-100 overflow-x-hidden">
    <div class="mx-auto max-w-[1200px] px-4 sm:px-6 py-8">

        {{-- ===================== HEADER ===================== --}}
        <section
            class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20 mb-6">
            <div class="p-5 sm:p-7">
                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">

                    {{-- Identity --}}
                    <div class="flex gap-4 items-center sm:gap-5 min-w-0">
                        <div class="min-w-0 flex-1">
                            <div
                                class="flex flex-wrap gap-2 items-center justify-center shadow-xs shadow-green-400 rounded-2xl p-3 bg-green-600/25">
                                <h1
                                    class="text-lg flex flex-col items-center sm:text-xl lg:text-xl font-bold tracking-tight text-white">
                                    <span>Année scolaire</span>
                                    <span class="text-amber-400/80">{{ $school_year_model->slug }}</span>
                                </h1>
                            </div>

                            <div class="mt-2.5 flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium border
                                    {{ $school_year_model->is_active
                                        ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                                        : 'bg-rose-500/10 text-rose-400 border-rose-500/20' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $school_year_model->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                    {{ $school_year_model->is_active ? 'Active' : 'Non active' }}
                                </span>

                                @if ($school_year_model->is_closed)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                        Clôturée
                                    </span>
                                @endif

                                @if ($school_year_model->trashed())
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        À la corbeille
                                    </span>
                                @endif
                            </div>

                            <p class="mt-3 text-sm text-slate-500">
                                Détails généraux de l’année scolaire {{ $school_year_model->slug }}
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#070b14] border border-white/5 text-xs text-slate-400">
                                    📑 {{ $school_year_model->periode_type }}
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#070b14] border border-white/5 text-xs text-slate-400">
                                    🕒 {{ $school_year_model->getDuration() }}
                                </span>
                                @if ($school_year_model->active_period)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-xs text-emerald-300 font-medium">
                                        ✅ {{ $this->school_year_model->periodLabel() }} {{ $this->active_period }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-xs text-rose-300 animate-pulse">
                                        Aucun {{ $this->school_year_model->periodLabel() }} actif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap gap-2 shrink-0" wire:loading.class="opacity-50 pointer-events-none"
                        wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}'),closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}'),deleteSchoolYear('{{ $school_year_model->slug }}'),restoreSchoolYear('{{ $school_year_model->slug }}'), activateYearlyBulletin('{{ $school_year_model->slug }}'), desactivateYearlyBulletin('{{ $school_year_model->slug }}')">

                        <a href="{{ route('tenant.schoolYears.edit', ['school_year' => $school_year_model->slug]) }}"
                            class="h-10 px-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all inline-flex items-center gap-1.5">
                            <x-lucide-pen class="w-3.5 h-3.5" />
                            Éditer
                        </a>

                        <button
                            wire:click="{{ $school_year_model->is_active ? "deactivateSchoolYear('{$school_year_model->slug}')" : "activateSchoolYear('{$school_year_model->slug}')" }}"
                            wire:loading.attr="disabled"
                            wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}')"
                            class="h-10 px-4 rounded-xl text-sm font-medium inline-flex items-center gap-1.5 transition-all disabled:opacity-50
                                       {{ $school_year_model->is_active
                                           ? 'bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-300'
                                           : 'bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/25 text-emerald-300' }}">
                            <span wire:loading.remove
                                wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}')"
                                class="inline-flex items-center gap-1.5">
                                @if ($school_year_model->is_active)
                                    <x-lucide-star-off class="w-3.5 h-3.5" /> Désactiver
                                @else
                                    <x-lucide-star class="w-3.5 h-3.5" /> Activer
                                @endif
                            </span>
                            <span wire:loading
                                wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}')">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>

                        <button
                            wire:click="{{ $school_year_model->is_closed ? "reopenSchoolYear('{$school_year_model->slug}')" : "closeSchoolYear('{$school_year_model->slug}')" }}"
                            wire:loading.attr="disabled"
                            wire:target="closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}')"
                            class="h-10 px-4 rounded-xl text-sm font-medium inline-flex items-center gap-1.5 transition-all disabled:opacity-50
                                       {{ $school_year_model->is_closed
                                           ? 'bg-lime-500/15 hover:bg-lime-500/25 border border-lime-500/25 text-lime-300'
                                           : 'bg-orange-500/10 hover:bg-orange-500/20 border border-orange-500/20 text-orange-300' }}">
                            <span wire:loading.remove
                                wire:target="closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}')"
                                class="inline-flex items-center gap-1.5">
                                @if ($school_year_model->is_closed)
                                    <x-lucide-unlock class="w-3.5 h-3.5" /> Réouvrir
                                @else
                                    <x-lucide-lock class="w-3.5 h-3.5" /> Clôturer
                                @endif
                            </span>
                            <span wire:loading
                                wire:target="closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}')">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>

                        @if ($school_year_model->trashed())
                            <button wire:click="restoreSchoolYear('{{ $school_year_model->slug }}')"
                                wire:loading.attr="disabled"
                                wire:target="restoreSchoolYear('{{ $school_year_model->slug }}')"
                                class="h-10 px-4 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/25 text-emerald-300 text-sm font-medium inline-flex items-center gap-1.5 transition-all disabled:opacity-50">
                                <span wire:loading.remove
                                    wire:target="restoreSchoolYear('{{ $school_year_model->slug }}')"
                                    class="inline-flex items-center gap-1.5">
                                    <x-lucide-rotate-ccw class="w-3.5 h-3.5" /> Restaurer
                                </span>
                                <span wire:loading wire:target="restoreSchoolYear('{{ $school_year_model->slug }}')">
                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                </span>
                            </button>
                        @else
                            <button wire:click="deleteSchoolYear('{{ $school_year_model->slug }}')"
                                wire:loading.attr="disabled"
                                wire:target="deleteSchoolYear('{{ $school_year_model->slug }}')"
                                class="h-10 px-4 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300 text-sm font-medium inline-flex items-center gap-1.5 transition-all disabled:opacity-50">
                                <span wire:loading.remove
                                    wire:target="deleteSchoolYear('{{ $school_year_model->slug }}')"
                                    class="inline-flex items-center gap-1.5">
                                    <x-lucide-trash-2 class="w-3.5 h-3.5" /> Supprimer
                                </span>
                                <span wire:loading wire:target="deleteSchoolYear('{{ $school_year_model->slug }}')">
                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                </span>
                            </button>
                        @endif

                        @if ($school_year_model->is_active && $school_year_model->active_period)
                            <button wire:click="closePeriods('{{ $school_year_model->slug }}')"
                                wire:loading.attr="disabled"
                                wire:target="closePeriods('{{ $school_year_model->slug }}')"
                                class="h-10 px-4 rounded-xl bg-rose-500/15 hover:bg-rose-500/25 border border-rose-500/25 text-rose-300 text-sm font-medium inline-flex items-center gap-1.5 transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="closePeriods('{{ $school_year_model->slug }}')"
                                    class="inline-flex items-center gap-1.5">
                                    <x-lucide-x class="w-3.5 h-3.5" />
                                    Fermer tous les {{ $school_year_model->periodLabel() }}s
                                </span>
                                <span wire:loading wire:target="closePeriods('{{ $school_year_model->slug }}')">
                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex justify-start p-5 sm:p-7">
                <label class="group relative inline-flex flex-row-reverse items-center gap-3 cursor-pointer select-none"
                    wire:loading.class="opacity-60 pointer-events-none"
                    wire:target="activateYearlyBulletin('{{ $school_year_model->slug }}'),desactivateYearlyBulletin('{{ $school_year_model->slug }}')">

                    {{-- Label --}}
                    <span
                        class="text-sm font-medium transition-colors duration-300
                     {{ $school_year_model->yearly_average_is_visible ? 'text-emerald-400' : 'text-slate-400' }}">
                        <span wire:loading.remove
                            wire:target="activateYearlyBulletin('{{ $school_year_model->slug }}'),desactivateYearlyBulletin('{{ $school_year_model->slug }}')">
                            {{ $school_year_model->yearly_average_is_visible
                                ? 'Les bulletins annuels sont visibles et accessibles'
                                : 'Les bulletins annuels sont masqués et ne sont pas accessibles' }}
                        </span>
                        <span wire:loading
                            wire:target="activateYearlyBulletin('{{ $school_year_model->slug }}'),desactivateYearlyBulletin('{{ $school_year_model->slug }}')"
                            class="inline-flex items-center gap-1.5 text-slate-400">
                            <span class="inline-flex items-center gap-1.5 text-slate-400">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                <span>Mise à jour…</span>
                            </span>
                        </span>
                    </span>

                    <input type="checkbox" class="peer sr-only"
                        wire:key="yearly-bulletin-toggle-{{ $school_year_model->yearly_average_is_visible ? 'on' : 'off' }}"
                        @checked($school_year_model->yearly_average_is_visible)
                        wire:click.prevent="{{ $school_year_model->yearly_average_is_visible
                            ? "desactivateYearlyBulletin('{$school_year_model->slug}')"
                            : "activateYearlyBulletin('{$school_year_model->slug}')" }}"
                        wire:loading.attr="disabled"
                        wire:target="activateYearlyBulletin('{{ $school_year_model->slug }}'),desactivateYearlyBulletin('{{ $school_year_model->slug }}')">

                    {{-- Track --}}
                    <span
                        class="relative h-8 w-14 shrink-0 rounded-full shadow-inner transition-colors duration-300 ease-out
                     bg-slate-700
                     peer-checked:bg-emerald-500
                     peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-500/40 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-[#070b14]
                     peer-checked:[&_.thumb]:translate-x-6
                     peer-checked:[&_.icon-off]:opacity-0 peer-checked:[&_.icon-off]:scale-50
                     peer-checked:[&_.icon-on]:opacity-100 peer-checked:[&_.icon-on]:scale-100">

                        <span
                            class="pointer-events-none absolute inset-0 rounded-full bg-emerald-400/25 opacity-0 blur-md transition-opacity duration-300 peer-checked:opacity-100"></span>

                        {{-- Thumb --}}
                        <span
                            class="thumb absolute top-1 left-1 h-6 w-6 rounded-full bg-white shadow-md
                         flex items-center justify-center
                         transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]
                         translate-x-0
                         group-active:scale-90">

                            <span
                                class="icon-off absolute inset-0 flex items-center justify-center transition-all duration-200 ease-out opacity-100 scale-100">
                                <x-lucide-eye-off class="w-3.5 h-3.5 text-slate-500" />
                            </span>

                            <span
                                class="icon-on absolute inset-0 flex items-center justify-center transition-all duration-200 ease-out opacity-0 scale-50">
                                <x-lucide-eye class="w-3.5 h-3.5 text-emerald-600" />
                            </span>
                        </span>
                    </span>
                </label>
            </div>
        </section>

        {{-- ===================== PÉRIODE ACTIVE ===================== --}}
        <section class="mb-6">
            <button wire:click="toggleEdition" wire:loading.attr="disabled" wire:target="toggleEdition"
                class="h-11 px-5 rounded-xl text-sm font-medium inline-flex items-center gap-2 transition-all disabled:opacity-50
                           {{ $editing
                               ? 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300'
                               : 'bg-violet-500/15 hover:bg-violet-500/25 border border-violet-500/25 text-violet-300' }}">
                <span wire:loading.remove wire:target="toggleEdition" class="inline-flex items-center gap-2">
                    @if ($editing)
                        <x-lucide-x class="w-4 h-4" /> Annuler
                    @else
                        <x-lucide-pen class="w-4 h-4" />
                        Définir le {{ $school_year_model->periode_type }} actif
                    @endif
                </span>
                <span wire:loading wire:target="toggleEdition">
                    <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                </span>
            </button>

            @if ($editing)
                <div
                    class="mt-4 rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <select wire:model.live="active_period"
                        class="flex-1 h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                        <option value="">Choisir le {{ $school_year_model->periode_type }} actif</option>
                        @foreach ($this->periods as $kp => $pv)
                            <option value="{{ $pv['index'] }}">{{ $pv['label'] }}</option>
                        @endforeach
                    </select>

                    <button wire:click="saveActivePediod" wire:loading.attr="disabled" wire:target="saveActivePediod"
                        class="h-12 px-6 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium inline-flex items-center justify-center gap-2 transition-all disabled:opacity-50 shrink-0">
                        <span wire:loading.remove wire:target="saveActivePediod"
                            class="inline-flex items-center gap-2">
                            <x-lucide-save class="w-4 h-4" />
                            {{ $this->active_period
                                ? "Activer {$school_year_model->periodLabel()} {$this->active_period}"
                                : "Désactiver tous les {$school_year_model->periodLabel()}s" }}
                        </span>
                        <span wire:loading wire:target="saveActivePediod" class="inline-flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            En cours…
                        </span>
                    </button>
                </div>
            @endif
        </section>

        {{-- ===================== TIMELINE DES PÉRIODES ===================== --}}
        <section class="space-y-4 pb-16">
            <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">
                Timeline des {{ $school_year_model->periode_type }}s
            </h2>

            @foreach ($school_year_model->periods as $position => $period)
                @php
                    $start = \Carbon\Carbon::parse($period['start']);
                    $end = \Carbon\Carbon::parse($period['end']);
                    $today = now()->startOfDay();

                    $totalDays = $start->diffInDays($end) + 1;
                    $weeks = intdiv($totalDays, 7);
                    $remDays = $totalDays % 7;

                    $status = $today->lt($start) ? 'a_venir' : ($today->gt($end) ? 'passe' : 'en_cours');

                    $elapsed = max(0, min($totalDays, $start->diffInDays($today) + 1));
                    $progress = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 0;

                    $dayCount = $today->between($start, $end) ? $start->diffInDays($today) + 1 : null;

                    $isActivePeriod =
                        str()->lower(
                            $this->school_year_model->periodLabel() . ' ' . $school_year_model->active_period,
                        ) == str()->lower($position);
                @endphp

                <div wire:key="period-of-school-year-{{ $loop->iteration }}"
                    class="rounded-2xl border overflow-hidden transition-all duration-300
                            {{ $isActivePeriod
                                ? 'border-emerald-500/30 bg-emerald-500/5 shadow-lg shadow-emerald-900/10'
                                : ($status === 'passe'
                                    ? 'border-white/[0.04] bg-[#0f1523]/60 opacity-70 hover:opacity-100'
                                    : 'border-white/[0.06] bg-[#0f1523] hover:border-white/10') }}">

                    <div class="p-5 sm:p-6">
                        {{-- Header période --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                            <div class="flex items-center gap-3">
                                @if ($isActivePeriod)
                                    <span class="flex gap-1">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"
                                            style="animation-delay: 0.15s"></span>
                                        <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"
                                            style="animation-delay: 0.3s"></span>
                                    </span>
                                @endif

                                <h3
                                    class="font-mono uppercase text-sm font-semibold tracking-wide
                                           {{ $isActivePeriod ? 'text-emerald-300' : ($status === 'passe' ? 'text-slate-500' : 'text-slate-200') }}">
                                    {{ $position }}
                                </h3>

                                @if ($isActivePeriod)
                                    <span
                                        class="px-2.5 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-500/25 text-emerald-300 text-[11px] font-medium">
                                        {{ $this->school_year_model->periodLabel() }} actif
                                    </span>
                                @endif
                            </div>

                            @if ($status === 'en_cours')
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[11px] font-medium border border-emerald-500/20">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span
                                            class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-400"></span>
                                    </span>
                                    En cours
                                </span>
                            @elseif ($status === 'passe')
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-800 text-slate-500 text-[11px] font-medium border border-white/5">
                                    <x-lucide-check class="w-3 h-3" />
                                    Terminé
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full bg-sky-500/10 text-sky-400 text-[11px] font-medium border border-sky-500/20">
                                    À venir
                                </span>
                            @endif
                        </div>

                        {{-- Dates --}}
                        <div class="grid grid-cols-3 gap-4 mb-5">
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-1">Début</p>
                                <p
                                    class="font-mono text-sm tabular-nums {{ $status === 'passe' ? 'text-slate-500' : 'text-slate-200' }}">
                                    {{ $start->locale('fr')->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-1">Fin</p>
                                <p
                                    class="font-mono text-sm tabular-nums {{ $status === 'passe' ? 'text-slate-500' : 'text-slate-200' }}">
                                    {{ $end->locale('fr')->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-1">Durée</p>
                                <p
                                    class="font-mono text-sm tabular-nums {{ $status === 'passe' ? 'text-slate-500' : 'text-slate-200' }}">
                                    {{ $weeks }}
                                    sem{{ $weeks > 1 ? 's' : '' }}{{ $remDays > 0 ? ' ' . $remDays . ' j' : '' }}
                                </p>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div>
                            <div class="relative h-1.5 rounded-full bg-[#070b14] overflow-hidden">
                                <div class="absolute inset-y-0 left-0 rounded-full transition-all duration-700
                                            {{ $status === 'passe' ? 'bg-slate-600' : ($status === 'en_cours' ? 'bg-gradient-to-r from-violet-600 to-indigo-400' : 'bg-sky-500/40') }}"
                                    style="width: {{ $progress }}%"></div>

                                @if ($status === 'en_cours')
                                    <div class="absolute top-1/2 -translate-y-1/2 h-3 w-3 rounded-full bg-indigo-400 ring-4 ring-indigo-400/20 shadow-lg shadow-indigo-500/30"
                                        style="left: calc({{ $progress }}% - 6px)"></div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between mt-2 text-[11px] text-slate-600 font-mono">
                                <span>{{ $start->locale('fr')->translatedFormat('d M') }}</span>
                                @if ($status === 'en_cours' && $dayCount)
                                    <span class="text-indigo-400 font-medium">Jour {{ $dayCount }} /
                                        {{ $totalDays }}</span>
                                @endif
                                <span>{{ $end->locale('fr')->translatedFormat('d M') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

    </div>
</div>

