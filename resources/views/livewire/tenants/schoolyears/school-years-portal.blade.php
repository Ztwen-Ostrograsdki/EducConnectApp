<div class="min-h-screen bg-[#070a12] text-slate-100">

    {{-- Loading global --}}
    <div wire:loading wire:target="previousPage,nextPage,resetFilters,gotoPage"
        class="fixed inset-0 z-[200] flex items-center justify-center bg-[#070a12]/70 backdrop-blur-sm">
        <div class="flex items-center gap-3 text-slate-400">
            <x-lucide-loader-2 class="w-6 h-6 text-indigo-400 animate-spin" />
            <span class="text-sm font-medium">Chargement...</span>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

        {{-- ========== HEADER ========== --}}
        <header class="relative overflow-hidden rounded-3xl border border-white/[0.06] bg-[#0c101c]">
            <div
                class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent">
            </div>

            <div class="relative p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400/80 mb-2">
                            Gestion académique
                        </p>
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                                Années scolaires
                            </h1>
                            @if (tenancy()->tenant?->getActiveSchoolYear())
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                             bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    {{ tenancy()->tenant?->getActiveSchoolYear()->slug }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                             bg-rose-500/15 text-rose-400 border border-rose-500/25">
                                    Aucune année active
                                </span>
                            @endif
                        </div>
                        <p class="mt-1.5 text-sm text-slate-500">
                            Gestion des ressources scolaires par année
                        </p>
                    </div>

                    <a href="{{ route('tenant.schoolYears.create') }}"
                        class="inline-flex items-center gap-2 h-11 px-5 rounded-xl
                              bg-indigo-500 hover:bg-indigo-400 text-white text-sm font-medium
                              shadow-lg shadow-indigo-500/20 transition-all duration-200 shrink-0">
                        <x-lucide-plus class="w-4 h-4" />
                        Ajouter une année
                    </a>
                </div>
            </div>
        </header>

        {{-- ========== FILTRES ========== --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                <input wire:model.live.debounce.500ms="search" type="text"
                    placeholder="Rechercher une année scolaire..."
                    class="w-full h-11 rounded-xl bg-white/[0.03] border border-white/[0.08]
                              pl-10 pr-4 text-sm text-white placeholder:text-slate-600
                              outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                              transition-all" />
            </div>
            <button wire:click="resetFilters"
                class="h-11 px-4 rounded-xl text-sm font-medium
                           bg-white/[0.04] border border-white/[0.08] text-slate-400
                           hover:bg-white/[0.08] hover:text-white transition-all shrink-0">
                Réinitialiser
            </button>
        </div>

        {{-- ========== LISTE ========== --}}
        @if ($this->schoolYears->total())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                @foreach ($this->schoolYears as $school_year)
                    @php
                        $cardTargets = "closeSchoolYear('{$school_year->slug}'),reopenSchoolYear('{$school_year->slug}'),activateSchoolYear('{$school_year->slug}'),deactivateSchoolYear('{$school_year->slug}'),deleteSchoolYear('{$school_year->slug}'),restoreSchoolYear('{$school_year->slug}'),search";
                    @endphp

                    <article
                        class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02]
                                    overflow-hidden
                                    hover:border-indigo-500/25 hover:bg-white/[0.03]
                                    transition-all duration-300"
                        wire:key="school_year-{{ $school_year->id }}">

                        {{-- Loading card --}}
                        <div wire:loading wire:target="{{ $cardTargets }}"
                            class="absolute inset-0 z-20 flex items-center justify-center bg-[#070a12]/70 backdrop-blur-sm rounded-2xl">
                            <div class="flex items-center gap-2 text-slate-400">
                                <x-lucide-loader-2 class="w-5 h-5 text-indigo-400 animate-spin" />
                                <span class="text-xs font-medium">Chargement...</span>
                            </div>
                        </div>

                        {{-- Accent --}}
                        <div
                            class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-indigo-500 to-violet-600
                                    opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>

                        <div class="p-5">
                            {{-- Header card --}}
                            <a href="{{ route('tenant.schoolyear.profil', ['school_year' => $school_year->slug]) }}"
                                class="flex items-start justify-between gap-3 mb-5">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                        <h2
                                            class="text-lg font-semibold text-white
                                                   group-hover:text-indigo-300 transition-colors">
                                            {{ $school_year->slug }}
                                        </h2>

                                        @if ($school_year->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                         bg-emerald-500/15 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1 h-1 rounded-full bg-emerald-400"></span>
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded-md text-[11px] font-medium
                                                         bg-slate-700/50 text-slate-400">
                                                Inactive
                                            </span>
                                        @endif

                                        @if ($school_year->is_closed)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                         bg-amber-500/15 text-amber-400 border border-amber-500/20">
                                                <x-lucide-lock class="w-3 h-3" />
                                                Clôturée
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500">
                                        {{ ucwords($school_year->periode_type) }}
                                        · {{ $school_year->getStartDate() }} → {{ $school_year->getEndDate() }}
                                    </p>
                                </div>

                                <div
                                    class="w-11 h-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20
                                            flex items-center justify-center shrink-0
                                            group-hover:scale-105 transition-transform duration-300">
                                    <x-lucide-calendar class="w-5 h-5 text-indigo-400" />
                                </div>
                            </a>

                            {{-- Stats --}}
                            @if ($school_year->is_active)
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    <div class="rounded-xl bg-[#070a12]/80 border border-white/[0.04] p-3">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-500">Élèves</p>
                                        <p class="mt-1 text-lg font-bold text-white">
                                            {{ $this->stats['students_in_classe'] }}
                                        </p>
                                    </div>
                                    <div class="rounded-xl bg-[#070a12]/80 border border-white/[0.04] p-3">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-500">Profs</p>
                                        <p class="mt-1 text-lg font-bold text-white">
                                            {{ $this->stats['teachers_in_classes'] }}
                                        </p>
                                    </div>
                                    <div class="rounded-xl bg-[#070a12]/80 border border-white/[0.04] p-3">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-500">Classes</p>
                                        <p class="mt-1 text-lg font-bold text-white">
                                            {{ $this->stats['classes_actives'] + $this->stats['classes_unactives'] }}
                                        </p>
                                    </div>
                                    <div class="rounded-xl bg-[#070a12]/80 border border-white/[0.04] p-3">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-500">Réussite</p>
                                        <p class="mt-1 text-xs font-medium text-amber-400/70">
                                            Bientôt
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-xl bg-[#070a12]/60 border border-white/[0.04] px-4 py-3">
                                    <p class="text-xs text-slate-500 italic">
                                        Stats disponibles uniquement lorsque l’année est active
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="border-t border-white/[0.05] px-5 py-3.5">
                            <div class="flex flex-wrap gap-1.5">
                                <a href="{{ route('tenant.schoolyear.profil', ['school_year' => $school_year->slug]) }}"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                          bg-sky-500/15 text-sky-400 border border-sky-500/25
                                          hover:bg-sky-500 hover:text-white hover:border-sky-500
                                          transition-all duration-200">
                                    <x-lucide-eye class="w-3.5 h-3.5" />
                                    Voir
                                </a>

                                <a href="{{ route('tenant.schoolYears.edit', ['school_year' => $school_year->slug]) }}"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                          bg-white/[0.04] text-slate-400 border border-white/[0.08]
                                          hover:bg-white/[0.08] hover:text-white transition-all duration-200">
                                    <x-lucide-pen class="w-3.5 h-3.5" />
                                    Modifier
                                </a>

                                <div class="flex-1"></div>

                                {{-- Activer / Désactiver --}}
                                <button
                                    title="{{ $school_year->is_active ? 'Désactiver' : 'Activer' }} {{ $school_year->slug }}"
                                    wire:click="{{ $school_year->is_active ? "deactivateSchoolYear('{$school_year->slug}')" : "activateSchoolYear('{$school_year->slug}')" }}"
                                    wire:loading.attr="disabled"
                                    wire:target="activateSchoolYear('{{ $school_year->slug }}'),deactivateSchoolYear('{{ $school_year->slug }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                               transition-all duration-200 disabled:opacity-50
                                               {{ $school_year->is_active
                                                   ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white hover:border-rose-500'
                                                   : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500' }}">
                                    <span wire:loading.remove
                                        wire:target="activateSchoolYear('{{ $school_year->slug }}'),deactivateSchoolYear('{{ $school_year->slug }}')">
                                        @if ($school_year->is_active)
                                            <x-lucide-star-off class="w-3.5 h-3.5" />
                                        @else
                                            <x-lucide-star class="w-3.5 h-3.5" />
                                        @endif
                                    </span>
                                    <span wire:loading
                                        wire:target="activateSchoolYear('{{ $school_year->slug }}'),deactivateSchoolYear('{{ $school_year->slug }}')">
                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                    </span>
                                </button>

                                {{-- Clôturer / Réouvrir --}}
                                <button
                                    title="{{ $school_year->is_closed ? 'Réouvrir' : 'Clôturer' }} {{ $school_year->slug }}"
                                    wire:click="{{ $school_year->is_closed ? "reopenSchoolYear('{$school_year->slug}')" : "closeSchoolYear('{$school_year->slug}')" }}"
                                    wire:loading.attr="disabled"
                                    wire:target="closeSchoolYear('{{ $school_year->slug }}'),reopenSchoolYear('{{ $school_year->slug }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                               transition-all duration-200 disabled:opacity-50
                                               {{ $school_year->is_closed
                                                   ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                                                   : 'bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500 hover:text-white hover:border-amber-500' }}">
                                    <span wire:loading.remove
                                        wire:target="closeSchoolYear('{{ $school_year->slug }}'),reopenSchoolYear('{{ $school_year->slug }}')">
                                        @if ($school_year->is_closed)
                                            <x-lucide-unlock class="w-3.5 h-3.5" />
                                        @else
                                            <x-lucide-lock class="w-3.5 h-3.5" />
                                        @endif
                                    </span>
                                    <span wire:loading
                                        wire:target="closeSchoolYear('{{ $school_year->slug }}'),reopenSchoolYear('{{ $school_year->slug }}')">
                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                    </span>
                                </button>

                                {{-- Corbeille / Restaurer --}}
                                @if ($school_year->trashed())
                                    <button title="Restaurer {{ $school_year->slug }}"
                                        wire:click="restoreSchoolYear('{{ $school_year->slug }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="restoreSchoolYear('{{ $school_year->slug }}')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                   bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                                   hover:bg-emerald-500 hover:text-white hover:border-emerald-500
                                                   transition-all duration-200 disabled:opacity-50">
                                        <span wire:loading.remove
                                            wire:target="restoreSchoolYear('{{ $school_year->slug }}')">
                                            <x-lucide-rotate-ccw class="w-3.5 h-3.5" />
                                        </span>
                                        <span wire:loading
                                            wire:target="restoreSchoolYear('{{ $school_year->slug }}')">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                @else
                                    <button title="Supprimer {{ $school_year->slug }}"
                                        wire:click="deleteSchoolYear('{{ $school_year->slug }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="deleteSchoolYear('{{ $school_year->slug }}')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                   bg-rose-500/10 text-rose-400 border border-rose-500/20
                                                   hover:bg-rose-500 hover:text-white hover:border-rose-500
                                                   transition-all duration-200 disabled:opacity-50">
                                        <span wire:loading.remove
                                            wire:target="deleteSchoolYear('{{ $school_year->slug }}')">
                                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                        </span>
                                        <span wire:loading wire:target="deleteSchoolYear('{{ $school_year->slug }}')">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($this->schoolYears->hasPages())
                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] px-5 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-sm text-slate-500">
                            {{ $this->schoolYears->firstItem() }}–{{ $this->schoolYears->lastItem() }}
                            sur <span class="text-slate-300 font-medium">{{ $this->schoolYears->total() }}</span>
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if ($this->schoolYears->onFirstPage())
                                <span
                                    class="h-9 px-3.5 rounded-lg text-sm text-slate-600 bg-white/[0.02] flex items-center">
                                    Précédent
                                </span>
                            @else
                                <button wire:click="previousPage"
                                    class="h-9 px-3.5 rounded-lg text-sm text-slate-300
                                               bg-white/[0.04] border border-white/[0.06]
                                               hover:bg-white/[0.08] hover:text-white transition-all">
                                    Précédent
                                </button>
                            @endif

                            @foreach ($this->schoolYears->getUrlRange(1, $this->schoolYears->lastPage()) as $page => $url)
                                <button wire:click="gotoPage({{ $page }})" @disabled($page === $this->schoolYears->currentPage())
                                    class="h-9 w-9 rounded-lg text-sm font-medium transition-all
                                               {{ $page === $this->schoolYears->currentPage()
                                                   ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/25'
                                                   : 'bg-white/[0.04] text-slate-400 border border-white/[0.06] hover:bg-white/[0.08] hover:text-white' }}">
                                    {{ $page }}
                                </button>
                            @endforeach

                            @if ($this->schoolYears->hasMorePages())
                                <button wire:click="nextPage"
                                    class="h-9 px-3.5 rounded-lg text-sm text-slate-300
                                               bg-white/[0.04] border border-white/[0.06]
                                               hover:bg-white/[0.08] hover:text-white transition-all">
                                    Suivant
                                </button>
                            @else
                                <span
                                    class="h-9 px-3.5 rounded-lg text-sm text-slate-600 bg-white/[0.02] flex items-center">
                                    Suivant
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] py-16 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-500/10 mb-4">
                    <x-lucide-calendar class="w-7 h-7 text-indigo-400" />
                </div>
                <p class="text-slate-400 text-sm">Aucune année scolaire trouvée</p>
                @if ($search)
                    <button wire:click="resetFilters"
                        class="mt-4 px-4 py-2 rounded-xl text-sm
                                   bg-white/[0.04] border border-white/[0.08] text-slate-400
                                   hover:bg-white/[0.08] hover:text-white transition-all">
                        Réinitialiser les filtres
                    </button>
                @endif
            </div>
        @endif

    </div>
</div>
