<div class="min-h-screen bg-slate-950 text-slate-100 overflow-x-hidden mb-36 shadow-sm shadow-sky-500">

    <div wire:loading wire:target="previousPage,nextPage,resetFilters,gotoPage"
        class="fixed inset-0 flex items-center justify-center bg-slate-800/30 backdrop-blur-xs rounded-3xl"
        style="z-index: 200 !important;">
        <div class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-row">
            <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <span class="text-sm font-mono ls-1">Chargement en cours...</span>
        </div>
    </div>

    <div class="w-full max-w-[100vw] overflow-x-hidden">

        <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl">
            <div class="px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-lg sm:text-xl text-slate-300 font-bold break-words">Portail des années
                                scolaires</h1>
                            @if (tenancy()->tenant?->getActiveSchoolYear())
                                <span
                                    class="px-3 py-1 rounded-full bg-green-500/10 border border-green-500/20 text-green-400 text-xs shrink-0 font-mono ls-1">
                                    Année active : {{ tenancy()->tenant?->getActiveSchoolYear()->slug }}
                                </span>
                            @else
                                <span
                                    class="px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-xs shrink-0">
                                    Aucune année active
                                </span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm sm:text-base text-slate-400 font-mono">Gestion des ressources scolaires
                            par année</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <a href="{{ route('tenant.schoolYears.create') }}"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all duration-300 text-sm sm:text-base text-center">
                            Ajouter une année scolaire
                        </a>
                    </div>

                </div>
            </div>
        </section>

        <section class="px-4 sm:px-6 lg:px-8 my-3">
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">
                <div class="flex flex-col xl:flex-row gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="relative">
                            <input wire:model.live.debounce.500ms='search' type="text"
                                placeholder="Rechercher une année scolaire..."
                                class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm outline-none focus:border-indigo-500 transition-all">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">🔍</div>
                        </div>
                    </div>
                    <div wire:click='resetFilters' class="grid grid-cols-1 sm:grid-cols-2 xl:flex gap-3">
                        <button
                            class="h-12 px-5 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all text-sm">
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </section>

        @if ($this->schoolYears->total())
            <section class="p-4 sm:p-6 lg:p-8 my-3">
                <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-2 gap-4 sm:gap-6">
                    @foreach ($this->schoolYears as $school_year)
                        @php

                            $cardTargets = "closeSchoolYear('{$school_year->slug}'),reopenSchoolYear('{$school_year->slug}'),activateSchoolYear('{$school_year->slug}'),deactivateSchoolYear('{$school_year->slug}'),deleteSchoolYear('{$school_year->slug}'),restoreSchoolYear('{$school_year->slug}'), search";
                        @endphp
                        <div wire:key="school_year-{{ $school_year->id }}"
                            class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden hover:border-indigo-500/30 transition-all duration-300 opacity-75 hover:opacity-100 hover:-translate-y-0.5 relative">

                            <div wire:loading wire:target="{{ $cardTargets }}"
                                class="absolute inset-0 flex items-center justify-center bg-slate-800/30 backdrop-blur-xs rounded-3xl"
                                style="z-index: 200 !important;">
                                <div
                                    class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-row">
                                    <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                    <span class="text-sm font-mono ls-1">Chargement en cours...</span>
                                </div>
                            </div>

                            <div class="p-5">
                                <a href="{{ route('tenant.schoolyear.profil', ['school_year' => $school_year->slug]) }}"
                                    class="flex items-start justify-between gap-4 group">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 ">
                                            <h2
                                                class="text-lg font-bold truncate group-hover:underline-offset-4 group-hover:underline group-hover:text-sky-400">
                                                Année
                                                scolaire
                                                {{ $school_year->slug }}</h2>
                                            <span
                                                class="px-2 py-1 rounded-full bg-emerald-500/10 {{ $school_year->is_active ? 'text-emerald-400' : 'text-red-400' }} text-xs shrink-0">
                                                {{ $school_year->is_active ? 'Active' : 'Non active' }}
                                            </span>
                                            @if ($school_year->is_closed)
                                                <span
                                                    class="px-2 py-1 rounded-full bg-orange-500/10 text-orange-400 text-xs shrink-0">
                                                    Clôturée
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-2 text-sm group-hover:text-sky-800 text-slate-400 break-words">
                                            Génie Électrique &
                                            Électronique</p>
                                    </div>
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center shrink-0 border-4 border-slate-900 group-hover:border-sky-600 group-hover:animate-bounce">
                                        📅
                                    </div>
                                </a>

                                @if ($school_year->is_active)
                                    <div class="mt-6 grid grid-cols-2 gap-4">
                                        <div class="rounded-2xl bg-slate-950 p-4">
                                            <p class="text-xs text-slate-500">Élèves</p>
                                            <h3 class="mt-2 text-xl font-bold">
                                                {{ $this->stats['students_in_classe'] }}
                                            </h3>
                                        </div>
                                        <div class="rounded-2xl bg-slate-950 p-4">
                                            <p class="text-xs text-slate-500">Enseignants</p>
                                            <h3 class="mt-2 text-xl font-bold">
                                                {{ $this->stats['teachers_in_classes'] }}
                                            </h3>
                                        </div>
                                        <div class="rounded-2xl bg-slate-950 p-4">
                                            <p class="text-xs text-slate-500">Classes</p>
                                            <h3 class="mt-2 text-xl font-bold">
                                                {{ $this->stats['classes_actives'] + $this->stats['classes_unactives'] }}
                                            </h3>
                                        </div>
                                        <div class="rounded-2xl bg-slate-950 p-4">
                                            <p class="text-xs text-slate-500">Taux de réussite</p>
                                            <h3 class="mt-2 animate-pulse text-xs font-bold text-yellow-500">
                                                indisponible pour l'instant
                                            </h3>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-6 grid grid-cols-2 gap-4">
                                        <div class="rounded-2xl bg-slate-950 p-4">
                                            <p class="text-xs text-slate-500">Élèves</p>
                                            <h3
                                                class="mt-2 font-bold text-xs text-orange-600/80 animate-pulse font-mono">
                                                en Données
                                                disponibles seulement lorsque {{ $school_year->slug }} est active</h3>
                                        </div>
                                        <div class="rounded-2xl bg-slate-950 p-4">
                                            <p class="text-xs text-slate-500">Enseignants</p>
                                            <h3
                                                class="mt-2 font-bold text-xs text-orange-600/80 animate-pulse font-mono">
                                                en Données
                                                disponibles seulement lorsque {{ $school_year->slug }} est active</h3>
                                        </div>
                                        <div class="rounded-2xl bg-slate-950 p-4">
                                            <p class="text-xs text-slate-500">Classes</p>
                                            <h3
                                                class="mt-2 font-bold text-xs text-orange-600/80 animate-pulse font-mono">
                                                en Données
                                                disponibles seulement lorsque {{ $school_year->slug }} est active</h3>
                                        </div>
                                        <div class="rounded-2xl bg-slate-950 p-4">
                                            <p class="text-xs text-slate-500">Taux de réussite</p>
                                            <h3
                                                class="mt-2 font-bold text-xs text-orange-600/80 animate-pulse font-mono">
                                                en Données
                                                disponibles seulement lorsque {{ $school_year->slug }} est active</h3>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-6 space-y-3 text-sm text-slate-400">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="truncate">Type de période</span>
                                        <span
                                            class="truncate text-slate-300">{{ ucwords($school_year->periode_type) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="truncate">Début : {{ $school_year->getStartDate() }}</span>
                                        <span class="truncate text-slate-300">Fin :
                                            {{ $school_year->getEndDate() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-slate-800 p-4 font-mono">

                                <div class="grid grid-cols-2 gap-3">
                                    <a href="{{ route('tenant.schoolyear.profil', ['school_year' => $school_year->slug]) }}"
                                        class="rounded-2xl border border-slate-700 bg-slate-800/90 hover:bg-slate-500 justify-center hover:text-black transition-all text-sm flex items-center py-2.5 px-2">
                                        <span class="inline-flex items-center gap-2">
                                            <x-lucide-eye class="w-4 h-4" />
                                            <span>
                                                Voir détails
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('tenant.schoolYears.edit', ['school_year' => $school_year->slug]) }}"
                                        class="flex text-center items-center py-2.5 px-2 rounded-2xl bg-indigo-800/50 hover:bg-indigo-500 justify-center hover:text-black transition-all text-sm">

                                        <span class="inline-flex items-center gap-2">
                                            <x-lucide-pen class="w-4 h-4" />
                                            <span>
                                                Modifier
                                            </span>
                                        </span>
                                    </a>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-2">

                                    <button
                                        title="{{ $school_year->is_active ? 'Désactiver' : 'Activer' }} l'année scolaire {{ $school_year->slug }}"
                                        wire:click="{{ $school_year->is_active ? "deactivateSchoolYear('{$school_year->slug}')" : "activateSchoolYear('{$school_year->slug}')" }}"
                                        wire:loading.attr="disabled"
                                        wire:target="activateSchoolYear('{{ $school_year->slug }}'),deactivateSchoolYear('{{ $school_year->slug }}')"
                                        class="relative py-3 px-4 rounded-2xl text-white text-xs font-medium inline-flex items-center justify-center gap-1.5 transition-all whitespace-nowrap disabled:opacity-50 {{ $school_year->is_active ? 'bg-emerald-600/30 hover:bg-red-600/40' : 'bg-lime-600/40 hover:bg-lime-500 hover:text-black' }} ">
                                        <span wire:loading.remove
                                            wire:target="activateSchoolYear('{{ $school_year->slug }}'),deactivateSchoolYear('{{ $school_year->slug }}')"
                                            class="inline-flex items-center gap-2">
                                            @if ($school_year->is_active)
                                                <x-lucide-star-off class="w-4 h-4" />
                                                <span>Désactiver</span>
                                            @else
                                                <x-lucide-star class="w-4 h-4" />
                                                <span>Activer</span>
                                            @endif
                                        </span>
                                        <span wire:loading
                                            wire:target="activateSchoolYear('{{ $school_year->slug }}'),deactivateSchoolYear('{{ $school_year->slug }}')"
                                            class="inline-flex items-center gap-1">
                                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v8z" />
                                            </svg>
                                        </span>
                                    </button>

                                    <button
                                        title="{{ $school_year->is_closed ? 'Réouvrir' : 'Clôturer' }} l'année scolaire {{ $school_year->slug }}"
                                        wire:click="{{ $school_year->is_closed ? "reopenSchoolYear('{$school_year->slug}')" : "closeSchoolYear('{$school_year->slug}')" }}"
                                        wire:loading.attr="disabled"
                                        wire:target="closeSchoolYear('{{ $school_year->slug }}'),reopenSchoolYear('{{ $school_year->slug }}')"
                                        class="relative py-3 px-4 rounded-2xl text-white text-xs font-medium inline-flex items-center justify-center gap-1.5 transition-all whitespace-nowrap disabled:opacity-50 {{ $school_year->is_closed ? 'bg-lime-600/60 hover:bg-lime-500 hover:text-black' : 'bg-orange-500/20 hover:bg-orange-600/60' }}">
                                        <span wire:loading.remove
                                            wire:target="closeSchoolYear('{{ $school_year->slug }}'),reopenSchoolYear('{{ $school_year->slug }}')"
                                            class="inline-flex items-center gap-2">
                                            @if ($school_year->is_closed)
                                                <x-lucide-unlock class="w-4 h-4" />
                                                <span>Réouvrir</span>
                                            @else
                                                <x-lucide-lock class="w-4 h-4" />
                                                <span>Clôturer</span>
                                            @endif
                                        </span>
                                        <span wire:loading
                                            wire:target="closeSchoolYear('{{ $school_year->slug }}'),reopenSchoolYear('{{ $school_year->slug }}')"
                                            class="inline-flex items-center gap-1">
                                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v8z" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <div class="mt-2 grid grid-cols-1 gap-2">
                                    @if ($school_year->trashed())
                                        <button title="Restaurer l'année scolaire {{ $school_year->slug }}"
                                            wire:click="restoreSchoolYear('{{ $school_year->slug }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="restoreSchoolYear('{{ $school_year->slug }}')"
                                            class="relative py-3 px-4 rounded-2xl bg-emerald-600/30 hover:bg-emerald-600/60 text-white text-xs font-medium inline-flex items-center justify-center gap-1.5 transition-all whitespace-nowrap disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="restoreSchoolYear('{{ $school_year->slug }}')"
                                                class="inline-flex items-center gap-2">
                                                <x-lucide-rotate-ccw class="w-4 h-4" />
                                                <span>Restaurer</span>
                                            </span>
                                            <span wire:loading
                                                wire:target="restoreSchoolYear('{{ $school_year->slug }}')"
                                                class="inline-flex items-center gap-1">
                                                <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8v8z" />
                                                </svg>
                                            </span>
                                        </button>
                                    @else
                                        <button
                                            title="Mettre l'année scolaire {{ $school_year->slug }} à la corbeille"
                                            wire:click="deleteSchoolYear('{{ $school_year->slug }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="deleteSchoolYear('{{ $school_year->slug }}')"
                                            class="relative py-3 px-4 rounded-2xl bg-red-500/10 hover:bg-red-600/40 text-red-300 hover:text-white text-xs font-medium inline-flex items-center justify-center gap-1.5 transition-all whitespace-nowrap disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="deleteSchoolYear('{{ $school_year->slug }}')"
                                                class="inline-flex items-center gap-2">
                                                <x-lucide-trash-2 class="w-4 h-4" />
                                                <span>Supprimer</span>
                                            </span>
                                            <span wire:loading
                                                wire:target="deleteSchoolYear('{{ $school_year->slug }}')"
                                                class="inline-flex items-center gap-1">
                                                <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8v8z" />
                                                </svg>
                                            </span>
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            @if ($this->schoolYears->hasPages())
                <section class="px-4 sm:px-6 lg:px-8 pb-10">
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-sm text-slate-400">
                                Affichage {{ $this->schoolYears->firstItem() }} à {{ $this->schoolYears->lastItem() }}
                                sur {{ $this->schoolYears->total() }} années scolaires
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                @if ($this->schoolYears->onFirstPage())
                                    <span
                                        class="h-10 px-4 rounded-xl bg-slate-800/50 text-slate-600 text-sm flex items-center">Précédent</span>
                                @else
                                    <button wire:click="previousPage"
                                        class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm">Précédent</button>
                                @endif

                                @foreach ($this->schoolYears->getUrlRange(1, $this->schoolYears->lastPage()) as $page => $url)
                                    <button @disabled($page === $this->schoolYears->currentPage()) wire:click="gotoPage({{ $page }})"
                                        class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->schoolYears->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                        {{ $page }}
                                    </button>
                                @endforeach

                                @if ($this->schoolYears->hasMorePages())
                                    <button wire:click="nextPage"
                                        class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm">Suivant</button>
                                @else
                                    <span
                                        class="h-10 px-4 rounded-xl bg-slate-800/50 text-slate-600 text-sm flex items-center">Suivant</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @else
            <div class="w-full justify-center p-3">
                <div class="p-5 flex justify-center w-full text-center">
                    <div class="flex flex-col items-center gap-3">
                        <p class="text-slate-500 text-sm">Aucune année scolaire trouvée.</p>
                        @if ($search)
                            <button wire:click="resetFilters"
                                class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                Réinitialiser les filtres
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

