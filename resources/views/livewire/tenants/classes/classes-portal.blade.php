<div class="min-h-screen bg-slate-950 text-slate-100 overflow-x-hidden">
    <div class="w-full max-w-[100vw] overflow-x-hidden">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl">
            <div class="px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl sm:text-3xl font-bold break-words">Portail des classes</h1>
                            <span
                                class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs shrink-0">
                                {{ $classes->total() }} classe{{ $classes->total() > 1 ? 's' : '' }}
                            </span>
                            @if ($this->activeYear)
                                <span
                                    class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs shrink-0">
                                    {{ $this->activeYear->slug }}
                                </span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm sm:text-base text-slate-400">
                            Gestion des classes, promotions, séries et enseignants.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <a wire:navigate href="{{ route('tenant.classes.create') }}"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all duration-300 text-sm sm:text-base text-center font-medium">
                            + Ajouter une classe
                        </a>
                        <button
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300 text-sm sm:text-base">
                            Exporter
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- KPI --}}
        {{-- ===================================================== --}}
        <section class="p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">Total classes</p>
                            <h2 class="mt-3 text-3xl xl:text-4xl font-bold">{{ $this->stats['classes'] }}</h2>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center shrink-0">🏫
                        </div>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">Apprenants</p>
                            <h2 class="mt-3 text-3xl xl:text-4xl font-bold">
                                {{ number_format($this->stats['students']) }}</h2>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-sky-500/10 flex items-center justify-center shrink-0">👨‍🎓
                        </div>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">Enseignants</p>
                            <h2 class="mt-3 text-3xl xl:text-4xl font-bold">{{ $this->stats['teachers'] }}</h2>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center shrink-0">
                            👩‍🏫</div>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">Promotions</p>
                            <h2 class="mt-3 text-3xl xl:text-4xl font-bold">{{ $this->stats['promotions'] }}</h2>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center shrink-0">🎯
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- FILTERS --}}
        {{-- ===================================================== --}}
        <section class="px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">
                <div class="flex flex-col xl:flex-row gap-4">

                    {{-- Search --}}
                    <div class="flex-1 min-w-0">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Rechercher une classe..."
                                class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm outline-none focus:border-indigo-500 transition-all" />
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">🔍</div>
                        </div>
                    </div>

                    {{-- Selects --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:flex gap-3">

                        <select wire:model.live="promotion"
                            class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm focus:border-indigo-500 focus:outline-none transition">
                            <option value="">Toutes promotions</option>
                            @foreach ($this->promotions as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filiar"
                            class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm focus:border-indigo-500 focus:outline-none transition">
                            <option value="">Toutes filières</option>
                            @foreach ($this->filiars as $f)
                                <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="serial"
                            class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm focus:border-indigo-500 focus:outline-none transition">
                            <option value="">Toutes séries</option>
                            @foreach ($this->serials as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="level"
                            class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm focus:border-indigo-500 focus:outline-none transition">
                            <option value="">Tous niveaux</option>
                            <option value="primaire">Primaire</option>
                            <option value="secondaire">Secondaire</option>
                            <option value="superieur">Supérieur</option>
                        </select>

                        <button wire:click="resetFilters"
                            class="h-12 px-5 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all text-sm whitespace-nowrap">
                            Réinitialiser
                        </button>
                    </div>

                </div>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- CLASSES GRID --}}
        {{-- ===================================================== --}}
        <section class="p-4 sm:p-6 lg:p-8">

            {{-- Loading overlay --}}
            <div wire:loading.flex class="mb-4 items-center gap-2 text-sm text-slate-400">
                <svg class="animate-spin w-4 h-4 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Chargement...
            </div>

            @if ($classes->isEmpty())
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-16 text-center">
                    <div class="text-4xl mb-4">🏫</div>
                    <p class="text-slate-400 text-sm">Aucune classe trouvée pour ces filtres.</p>
                    <button wire:click="resetFilters"
                        class="mt-4 px-5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                        Réinitialiser les filtres
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-2 gap-4 sm:gap-6"
                    wire:loading.class="opacity-50">
                    @foreach ($classes as $classe)
                        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden hover:border-indigo-500/30 transition-all duration-300 hover:-translate-y-0.5"
                            wire:key="classe-{{ $classe->id }}">

                            {{-- TOP --}}
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <a wire:navigate
                                            href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                                            class="flex flex-wrap items-center gap-2">
                                            <h2 class="text-xl font-bold truncate">{{ $classe->name }}</h2>
                                            @if ($classe->is_active)
                                                <span
                                                    class="px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs shrink-0">Active</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 rounded-full bg-slate-700 text-slate-400 text-xs shrink-0">Inactive</span>
                                            @endif
                                            @if ($classe->is_locked)
                                                <span
                                                    class="px-2 py-1 rounded-full bg-amber-500/10 text-amber-400 text-xs shrink-0">🔒
                                                    Verrouillée</span>
                                            @endif
                                        </a>
                                        <div class="mt-1.5 flex flex-wrap gap-2">
                                            @if ($classe->promotion)
                                                <span
                                                    class="text-xs text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded-full">{{ $classe->promotion->name }}</span>
                                            @endif
                                            @if ($classe->filiar)
                                                <span
                                                    class="text-xs text-violet-400 bg-violet-500/10 px-2 py-0.5 rounded-full">{{ $classe->filiar->name }}</span>
                                            @endif
                                            @if ($classe->serial)
                                                <span
                                                    class="text-xs text-sky-400 bg-sky-500/10 px-2 py-0.5 rounded-full">{{ $classe->serial->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center shrink-0 text-2xl">
                                        🏫
                                    </div>
                                </div>

                                {{-- STATS --}}
                                <div class="mt-5 grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl bg-slate-950 p-4">
                                        <p class="text-xs text-slate-500">Élèves</p>
                                        <h3 class="mt-1.5 text-xl font-bold">
                                            {{ $classe->students_count }}
                                            <span class="text-xs text-slate-500 font-normal">/
                                                {{ $classe->effectif_max }}</span>
                                        </h3>
                                        {{-- Barre de remplissage --}}
                                        <div class="mt-2 h-1.5 rounded-full bg-slate-800 overflow-hidden">
                                            @php $pct = $classe->effectif_max > 0 ? min(100, round($classe->students_count / $classe->effectif_max * 100)) : 0; @endphp
                                            <div class="h-full rounded-full {{ $pct >= 90 ? 'bg-rose-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                                style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                    <div class="rounded-2xl bg-slate-950 p-4">
                                        <p class="text-xs text-slate-500">Niveau</p>
                                        <h3 class="mt-1.5 text-sm font-semibold capitalize">{{ $classe->level }}</h3>
                                        @if ($classe->code)
                                            <p class="mt-1 text-xs text-slate-500 font-mono">{{ $classe->code }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- META --}}
                                <div class="mt-5 space-y-2.5 text-sm text-slate-400">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="truncate text-xs">Prof principal</span>
                                        <span class="truncate text-slate-300 text-xs font-medium">
                                            {{ $classe->principal?->name ?? '—' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- ACTIONS --}}
                            <div class="border-t border-slate-800 p-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <a wire:navigate
                                        href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                                        class="h-11 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm flex items-center justify-center font-medium">
                                        Voir
                                    </a>
                                    <a wire:navigate
                                        href="{{ route('tenant.classe.edit', ['classe_slug' => $classe->slug]) }}"
                                        class="h-11 rounded-2xl bg-slate-800 hover:bg-slate-700 transition-all text-sm flex items-center justify-center">
                                        Modifier
                                    </a>
                                </div>
                                <div class="mt-3 grid grid-cols-3 gap-3">
                                    <button wire:click="moveClasseToTrash({{ $classe->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="moveClasseToTrash({{ $classe->id }})"
                                        class="relative py-3 px-4 rounded-xl bg-rose-500/20 hover:bg-rose-500/45 text-rose-400 hover:text-white transition-all text-xs font-medium">
                                        <span wire:loading.remove
                                            wire:target="moveClasseToTrash({{ $classe->id }})">
                                            <span class="inline-flex items-center gap-x-3">
                                                <x-lucide-trash class="w-4 h-4" />
                                                <span>Corbeille</span>
                                            </span>
                                        </span>
                                        <span wire:loading wire:target="moveClasseToTrash({{ $classe->id }})"
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
                                        wire:click="{{ $classe->is_locked ? 'unlockClasse(' . $classe->id . ')' : 'lockClasse(' . $classe->id . ')' }}"
                                        wire:loading.attr="disabled" wire:target="lockClasse, unlockClasse"
                                        class="relative py-3 px-4 rounded-xl {{ $classe->is_locked ? 'bg-emerald-600/20 hover:bg-emerald-500/50' : 'bg-amber-500/0 hover:bg-amber-600/50' }} transition-all text-xs font-medium">
                                        <span wire:loading.remove wire:target="lockClasse, unlockClasse"
                                            class="inline-flex items-center justify-center gap-3">
                                            <span class="inline-flex items-center justify-center gap-3">
                                                @if ($classe->is_locked)
                                                    <x-lucide-lock-open class="w-4 h-4" />
                                                    <span>Déverrouiller</span>
                                                @else
                                                    <x-lucide-lock class="w-4 h-4" />
                                                    <span>Verrouiller</span>
                                                @endif
                                            </span>
                                        </span>
                                        <span wire:loading wire:loading wire:target="lockClasse, unlockClasse"
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
                                        wire:click="{{ $classe->is_active ? 'closeClasse(' . $classe->id . ')' : 'activateClasse(' . $classe->id . ')' }}"
                                        wire:loading.attr="disabled" wire:target="activateClasse, closeClasse"
                                        class="relative py-3 px-4 rounded-xl {{ !$classe->is_active ? 'bg-green-600/20 hover:bg-green-500/60' : 'bg-red-500/20 hover:bg-red-600/60' }} transition-all text-xs font-medium">
                                        <span wire:loading.remove wire:target="activateClasse, closeClasse"
                                            class="inline-flex items-center justify-center gap-3">
                                            <span class="inline-flex items-center justify-center gap-3">
                                                @if ($classe->is_active)
                                                    <x-lucide-power class="w-4 h-4" />
                                                    <span>Fermer</span>
                                                @else
                                                    <x-lucide-book-open-check class="w-4 h-4" />
                                                    <span>Activer</span>
                                                @endif
                                            </span>
                                        </span>
                                        <span wire:loading wire:loading wire:target="activateClasse, closeClasse"
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
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </section>

        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}
        @if ($classes->hasPages())
            <section class="px-4 sm:px-6 lg:px-8 pb-10">
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-slate-400">
                            Affichage {{ $classes->firstItem() }} à {{ $classes->lastItem() }} sur
                            {{ $classes->total() }} classes
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            {{-- Précédent --}}
                            @if ($classes->onFirstPage())
                                <span
                                    class="h-10 px-4 rounded-xl bg-slate-800/50 text-slate-600 text-sm flex items-center">Précédent</span>
                            @else
                                <button wire:click="previousPage"
                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm">Précédent</button>
                            @endif

                            {{-- Pages --}}
                            @foreach ($classes->getUrlRange(1, $classes->lastPage()) as $page => $url)
                                <button wire:click="gotoPage({{ $page }})"
                                    class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $classes->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                    {{ $page }}
                                </button>
                            @endforeach

                            {{-- Suivant --}}
                            @if ($classes->hasMorePages())
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

    </div>
</div>

