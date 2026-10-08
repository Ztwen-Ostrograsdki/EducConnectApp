<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-8">

        {{-- ========== HERO ========== --}}
        <div class="relative overflow-hidden rounded-[2rem] border border-white/[0.06]">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/15 via-transparent to-violet-600/10"></div>
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px]"></div>

            <div class="relative p-6 sm:p-8 lg:p-10">
                {{-- Titre + badges --}}
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6 mb-8">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-indigo-400/80 mb-3">
                            Année scolaire
                        </p>
                        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            {{ $school_year_model->slug }}
                        </h1>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium border
                                         {{ $school_year_model->is_active
                                             ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/25'
                                             : 'bg-rose-500/15 text-rose-400 border-rose-500/25' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ $school_year_model->is_active ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                                {{ $school_year_model->is_active ? 'Active' : 'Non active' }}
                            </span>

                            @if ($school_year_model->is_closed)
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium
                                             bg-amber-500/15 text-amber-400 border border-amber-500/25">
                                    <x-lucide-lock class="w-3 h-3" />
                                    Clôturée
                                </span>
                            @endif

                            @if ($school_year_model->trashed())
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium
                                             bg-rose-500/15 text-rose-400 border border-rose-500/25">
                                    <x-lucide-trash-2 class="w-3 h-3" />
                                    Corbeille
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Meta rapide --}}
                    <div class="flex flex-wrap gap-2 sm:justify-end">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs
                                     bg-white/[0.04] border border-white/[0.08] text-slate-400">
                            <x-lucide-calendar-range class="w-3.5 h-3.5 text-indigo-400" />
                            {{ $school_year_model->periode_type }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs
                                     bg-white/[0.04] border border-white/[0.08] text-slate-400">
                            <x-lucide-clock class="w-3.5 h-3.5 text-amber-400" />
                            {{ $school_year_model->getDuration() }}
                        </span>
                        @if ($school_year_model->active_period)
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                         bg-emerald-500/15 border border-emerald-500/25 text-emerald-300">
                                <x-lucide-check-circle class="w-3.5 h-3.5" />
                                {{ $this->school_year_model->periodLabel() }} {{ $this->active_period }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs
                                         bg-rose-500/10 border border-rose-500/20 text-rose-300 animate-pulse">
                                Aucun {{ $this->school_year_model->periodLabel() }} actif
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-2" wire:loading.class="opacity-50 pointer-events-none"
                    wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}'),closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}'),deleteSchoolYear('{{ $school_year_model->slug }}'),restoreSchoolYear('{{ $school_year_model->slug }}'),activateYearlyBulletin('{{ $school_year_model->slug }}'),desactivateYearlyBulletin('{{ $school_year_model->slug }}')">

                    <a href="{{ route('tenant.schoolYears.edit', ['school_year' => $school_year_model->slug]) }}"
                        class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-medium
                              bg-white/[0.05] border border-white/[0.1] text-slate-300
                              hover:bg-white/[0.1] hover:text-white transition-all">
                        <x-lucide-pen class="w-3.5 h-3.5" />
                        Éditer
                    </a>

                    <button
                        wire:click="{{ $school_year_model->is_active ? "deactivateSchoolYear('{$school_year_model->slug}')" : "activateSchoolYear('{$school_year_model->slug}')" }}"
                        wire:loading.attr="disabled"
                        wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}')"
                        class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-medium transition-all disabled:opacity-50
                                   {{ $school_year_model->is_active
                                       ? 'bg-amber-500/10 border border-amber-500/25 text-amber-300 hover:bg-amber-500 hover:text-white hover:border-amber-500'
                                       : 'bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 hover:bg-emerald-500 hover:text-white hover:border-emerald-500' }}">
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
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>

                    <button
                        wire:click="{{ $school_year_model->is_closed ? "reopenSchoolYear('{$school_year_model->slug}')" : "closeSchoolYear('{$school_year_model->slug}')" }}"
                        wire:loading.attr="disabled"
                        wire:target="closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}')"
                        class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-medium transition-all disabled:opacity-50
                                   {{ $school_year_model->is_closed
                                       ? 'bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                                       : 'bg-amber-500/10 border border-amber-500/25 text-amber-300 hover:bg-amber-500 hover:text-white hover:border-amber-500' }}">
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
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>

                    @if ($school_year_model->trashed())
                        <button wire:click="restoreSchoolYear('{{ $school_year_model->slug }}')"
                            wire:loading.attr="disabled"
                            wire:target="restoreSchoolYear('{{ $school_year_model->slug }}')"
                            class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-medium
                                       bg-emerald-500/10 border border-emerald-500/25 text-emerald-300
                                       hover:bg-emerald-500 hover:text-white hover:border-emerald-500
                                       transition-all disabled:opacity-50">
                            <span wire:loading.remove wire:target="restoreSchoolYear('{{ $school_year_model->slug }}')"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-rotate-ccw class="w-3.5 h-3.5" /> Restaurer
                            </span>
                            <span wire:loading wire:target="restoreSchoolYear('{{ $school_year_model->slug }}')">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    @else
                        <button wire:click="deleteSchoolYear('{{ $school_year_model->slug }}')"
                            wire:loading.attr="disabled"
                            wire:target="deleteSchoolYear('{{ $school_year_model->slug }}')"
                            class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-medium
                                       bg-rose-500/10 border border-rose-500/25 text-rose-300
                                       hover:bg-rose-500 hover:text-white hover:border-rose-500
                                       transition-all disabled:opacity-50">
                            <span wire:loading.remove wire:target="deleteSchoolYear('{{ $school_year_model->slug }}')"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" /> Supprimer
                            </span>
                            <span wire:loading wire:target="deleteSchoolYear('{{ $school_year_model->slug }}')">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    @endif

                    @if ($school_year_model->is_active && $school_year_model->active_period)
                        <button wire:click="closePeriods('{{ $school_year_model->slug }}')"
                            wire:loading.attr="disabled" wire:target="closePeriods('{{ $school_year_model->slug }}')"
                            class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-medium
                                       bg-rose-500/10 border border-rose-500/25 text-rose-300
                                       hover:bg-rose-500 hover:text-white hover:border-rose-500
                                       transition-all disabled:opacity-50">
                            <span wire:loading.remove wire:target="closePeriods('{{ $school_year_model->slug }}')"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-x class="w-3.5 h-3.5" />
                                Fermer tous les {{ $school_year_model->periodLabel() }}s
                            </span>
                            <span wire:loading wire:target="closePeriods('{{ $school_year_model->slug }}')">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- ========== TOGGLE BULLETINS ========== --}}
        <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5">
            <label class="group flex items-center justify-between gap-4 cursor-pointer select-none"
                wire:loading.class="opacity-60 pointer-events-none"
                wire:target="activateYearlyBulletin('{{ $school_year_model->slug }}'),desactivateYearlyBulletin('{{ $school_year_model->slug }}')">

                <div class="min-w-0">
                    <p
                        class="text-sm font-medium
                              {{ $school_year_model->yearly_average_is_visible ? 'text-emerald-400' : 'text-slate-300' }}">
                        <span wire:loading.remove
                            wire:target="activateYearlyBulletin('{{ $school_year_model->slug }}'),desactivateYearlyBulletin('{{ $school_year_model->slug }}')">
                            {{ $school_year_model->yearly_average_is_visible ? 'Bulletins annuels visibles' : 'Bulletins annuels masqués' }}
                        </span>
                        <span wire:loading
                            wire:target="activateYearlyBulletin('{{ $school_year_model->slug }}'),desactivateYearlyBulletin('{{ $school_year_model->slug }}')"
                            class="inline-flex items-center gap-1.5 text-slate-400">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            Mise à jour…
                        </span>
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $school_year_model->yearly_average_is_visible
                            ? 'Les élèves peuvent consulter leurs bulletins annuels'
                            : 'Les bulletins annuels ne sont pas accessibles' }}
                    </p>
                </div>

                <input type="checkbox" class="peer sr-only"
                    wire:key="yearly-bulletin-toggle-{{ $school_year_model->yearly_average_is_visible ? 'on' : 'off' }}"
                    @checked($school_year_model->yearly_average_is_visible)
                    wire:click.prevent="{{ $school_year_model->yearly_average_is_visible
                        ? "desactivateYearlyBulletin('{$school_year_model->slug}')"
                        : "activateYearlyBulletin('{$school_year_model->slug}')" }}"
                    wire:loading.attr="disabled"
                    wire:target="activateYearlyBulletin('{{ $school_year_model->slug }}'),desactivateYearlyBulletin('{{ $school_year_model->slug }}')">

                <span
                    class="relative h-7 w-12 shrink-0 rounded-full transition-colors duration-300
                             bg-slate-700 peer-checked:bg-emerald-500
                             peer-checked:[&_.thumb]:translate-x-5">
                    <span
                        class="thumb absolute top-0.5 left-0.5 h-6 w-6 rounded-full bg-white shadow
                                 flex items-center justify-center
                                 transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]">
                        <x-lucide-eye
                            class="w-3 h-3 text-emerald-600 opacity-0 peer-checked:opacity-100 absolute transition-opacity" />
                        <x-lucide-eye-off class="w-3 h-3 text-slate-400 peer-checked:opacity-0 transition-opacity" />
                    </span>
                </span>
            </label>
        </div>

        {{-- ========== PÉRIODE ACTIVE ========== --}}
        <div>
            <button wire:click="toggleEdition" wire:loading.attr="disabled" wire:target="toggleEdition"
                class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-sm font-medium transition-all disabled:opacity-50
                           {{ $editing
                               ? 'bg-white/[0.05] border border-white/[0.1] text-slate-300 hover:bg-white/[0.08]'
                               : 'bg-violet-500/15 border border-violet-500/25 text-violet-300 hover:bg-violet-500 hover:text-white hover:border-violet-500' }}">
                <span wire:loading.remove wire:target="toggleEdition" class="inline-flex items-center gap-2">
                    @if ($editing)
                        <x-lucide-x class="w-4 h-4" /> Annuler
                    @else
                        <x-lucide-pen class="w-4 h-4" />
                        Définir le {{ $school_year_model->periode_type }} actif
                    @endif
                </span>
                <span wire:loading wire:target="toggleEdition">
                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                </span>
            </button>

            @if ($editing)
                <div
                    class="mt-4 rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5
                            flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <select wire:model.live="active_period"
                        class="flex-1 h-11 rounded-xl bg-[#070a12] border border-white/[0.08] px-4
                                   text-sm text-slate-200 focus:outline-none focus:border-violet-500/50
                                   focus:ring-1 focus:ring-violet-500/20 transition-all">
                        <option value="">Choisir le {{ $school_year_model->periode_type }} actif</option>
                        @foreach ($this->periods as $kp => $pv)
                            <option value="{{ $pv['index'] }}">{{ $pv['label'] }}</option>
                        @endforeach
                    </select>

                    <button wire:click="saveActivePediod" wire:loading.attr="disabled" wire:target="saveActivePediod"
                        class="h-11 px-5 rounded-xl bg-violet-500 hover:bg-violet-400 text-white text-sm font-medium
                                   inline-flex items-center justify-center gap-2 transition-all disabled:opacity-50 shrink-0">
                        <span wire:loading.remove wire:target="saveActivePediod"
                            class="inline-flex items-center gap-2">
                            <x-lucide-save class="w-4 h-4" />
                            {{ $this->active_period
                                ? "Activer {$school_year_model->periodLabel()} {$this->active_period}"
                                : "Désactiver tous les {$school_year_model->periodLabel()}s" }}
                        </span>
                        <span wire:loading wire:target="saveActivePediod" class="inline-flex items-center gap-2">
                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                            En cours…
                        </span>
                    </button>
                </div>
            @endif
        </div>

        {{-- ========== TIMELINE ========== --}}
        <section>
            <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 mb-4">
                Timeline des {{ $school_year_model->periode_type }}s
            </h2>

            <div class="relative space-y-0">
                {{-- Ligne verticale --}}
                <div class="absolute left-[15px] top-3 bottom-3 w-px bg-white/[0.06] hidden sm:block"></div>

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

                    <div class="relative flex gap-4 sm:gap-6 pb-6 last:pb-0"
                        wire:key="period-of-school-year-{{ $loop->iteration }}">

                        {{-- Dot --}}
                        <div class="relative z-10 shrink-0 mt-5 hidden sm:flex">
                            <div
                                class="w-[31px] h-[31px] rounded-full flex items-center justify-center border-2
                                        {{ $isActivePeriod
                                            ? 'border-emerald-400 bg-emerald-500/20'
                                            : ($status === 'en_cours'
                                                ? 'border-indigo-400 bg-indigo-500/20'
                                                : ($status === 'passe'
                                                    ? 'border-slate-600 bg-slate-800'
                                                    : 'border-sky-500/40 bg-sky-500/10')) }}">
                                @if ($isActivePeriod || $status === 'en_cours')
                                    <span
                                        class="w-2 h-2 rounded-full {{ $isActivePeriod ? 'bg-emerald-400 animate-pulse' : 'bg-indigo-400' }}"></span>
                                @elseif ($status === 'passe')
                                    <x-lucide-check class="w-3.5 h-3.5 text-slate-500" />
                                @else
                                    <span class="w-2 h-2 rounded-full bg-sky-400/50"></span>
                                @endif
                            </div>
                        </div>

                        {{-- Card --}}
                        <div
                            class="flex-1 min-w-0 rounded-2xl border overflow-hidden transition-all duration-300
                                    {{ $isActivePeriod
                                        ? 'border-emerald-500/30 bg-emerald-500/[0.04]'
                                        : ($status === 'passe'
                                            ? 'border-white/[0.04] bg-white/[0.015] opacity-70 hover:opacity-100'
                                            : 'border-white/[0.06] bg-white/[0.02] hover:border-white/10') }}">

                            <div class="p-5">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                    <div class="flex items-center gap-2.5">
                                        <h3
                                            class="text-sm font-semibold uppercase tracking-wide
                                                   {{ $isActivePeriod ? 'text-emerald-300' : ($status === 'passe' ? 'text-slate-500' : 'text-white') }}">
                                            {{ $position }}
                                        </h3>
                                        @if ($isActivePeriod)
                                            <span
                                                class="px-2 py-0.5 rounded-md text-[10px] font-medium
                                                         bg-emerald-500/15 text-emerald-300 border border-emerald-500/25">
                                                Actif
                                            </span>
                                        @endif
                                    </div>

                                    @if ($status === 'en_cours')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium
                                                     bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                            <span class="relative flex h-1.5 w-1.5">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-400"></span>
                                            </span>
                                            En cours
                                        </span>
                                    @elseif ($status === 'passe')
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium
                                                     bg-slate-800 text-slate-500 border border-white/5">
                                            Terminé
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-md text-[10px] font-medium
                                                     bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                            À venir
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">Début</p>
                                        <p
                                            class="text-sm font-mono tabular-nums {{ $status === 'passe' ? 'text-slate-500' : 'text-slate-200' }}">
                                            {{ $start->locale('fr')->translatedFormat('d M Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">Fin</p>
                                        <p
                                            class="text-sm font-mono tabular-nums {{ $status === 'passe' ? 'text-slate-500' : 'text-slate-200' }}">
                                            {{ $end->locale('fr')->translatedFormat('d M Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">Durée</p>
                                        <p
                                            class="text-sm font-mono tabular-nums {{ $status === 'passe' ? 'text-slate-500' : 'text-slate-200' }}">
                                            {{ $weeks }}
                                            sem{{ $weeks > 1 ? 's' : '' }}{{ $remDays > 0 ? ' ' . $remDays . ' j' : '' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div>
                                    <div class="relative h-1.5 rounded-full bg-[#070a12] overflow-hidden">
                                        <div class="absolute inset-y-0 left-0 rounded-full transition-all duration-700
                                                    {{ $status === 'passe' ? 'bg-slate-600' : ($status === 'en_cours' ? 'bg-gradient-to-r from-violet-500 to-indigo-400' : 'bg-sky-500/30') }}"
                                            style="width: {{ $progress }}%"></div>
                                        @if ($status === 'en_cours')
                                            <div class="absolute top-1/2 -translate-y-1/2 h-3 w-3 rounded-full bg-indigo-400 ring-4 ring-indigo-400/20"
                                                style="left: calc({{ $progress }}% - 6px)"></div>
                                        @endif
                                    </div>
                                    <div
                                        class="flex items-center justify-between mt-1.5 text-[10px] text-slate-600 font-mono">
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
                    </div>
                @endforeach
            </div>
        </section>

    </div>
</div>
