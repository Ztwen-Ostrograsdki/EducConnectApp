<div class="min-h-screen bg-[#070a12] text-slate-100">

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

        {{-- ========== HEADER ========== --}}
        <header class="relative overflow-hidden rounded-3xl border border-white/[0.06] bg-white/[0.02]">
            <div
                class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-transparent to-violet-500/5 pointer-events-none">
            </div>

            <div class="relative p-6 sm:p-8">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                                Dashboard Filières
                            </h1>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                         bg-indigo-500/15 text-indigo-400 border border-indigo-500/25">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                {{ $this->activeYear?->slug }}
                            </span>
                        </div>

                        <p class="text-sm text-slate-400 max-w-2xl leading-relaxed">
                            Vue globale des filières, performances académiques,
                            statistiques des apprenants et gestion des classes.
                        </p>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                         bg-white/[0.04] border border-white/[0.08] text-slate-300">
                                <x-lucide-layers class="w-3.5 h-3.5 text-indigo-400" />
                                {{ __zero($this->filiars->total()) }} filières
                            </span>

                            @if ($this->unActivesFiliars)
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                             bg-rose-500/10 border border-rose-500/25 text-rose-400 animate-pulse">
                                    <x-lucide-power-off class="w-3.5 h-3.5" />
                                    {{ __zero($this->unActivesFiliars) }} désactivées
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- ========== ACTIONS ========== --}}
        <div class="flex flex-wrap items-center gap-2">
            <a wire:navigate href="{{ route('tenant.classes.create') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                      bg-violet-500/15 text-violet-400 border border-violet-500/25
                      hover:bg-violet-500 hover:text-white hover:border-violet-500 transition-all duration-200">
                <x-lucide-plus class="w-3.5 h-3.5" />
                Créer une classe
            </a>

            <a wire:navigate href="{{ route('tenant.filiar.create') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                      bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                      hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all duration-200">
                <x-lucide-plus class="w-3.5 h-3.5" />
                Créer une filière
            </a>

            <button
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                           bg-emerald-500/15 text-emerald-400 border border-emerald-500/25
                           hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all duration-200">
                <x-lucide-file-down class="w-3.5 h-3.5" />
                Export PDF
            </button>

            @if ($this->unActivesFiliars)
                <button wire:click="activateUnactivesFiliars" wire:loading.attr="disabled"
                    wire:target="activateUnactivesFiliars"
                    title="Réactiver les {{ $this->unActivesFiliars }} filières désactivées"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                               bg-amber-500/15 text-amber-400 border border-amber-500/25
                               hover:bg-amber-500 hover:text-white hover:border-amber-500
                               transition-all duration-200 disabled:opacity-50">
                    <span wire:loading.remove wire:target="activateUnactivesFiliars"
                        class="inline-flex items-center gap-2">
                        <x-lucide-power class="w-3.5 h-3.5" />
                        Réactiver ({{ __zero($this->unActivesFiliars) }})
                    </span>
                    <span wire:loading wire:target="activateUnactivesFiliars" class="inline-flex items-center gap-2">
                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        Activation...
                    </span>
                </button>
            @endif

            @if ($this->trashedsFiliars)
                <button wire:click="restoreTrashedsFiliars" wire:loading.attr="disabled"
                    wire:target="restoreTrashedsFiliars"
                    title="Restaurer les {{ $this->trashedsFiliars }} filières de la corbeille"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                               bg-rose-500/15 text-rose-400 border border-rose-500/25
                               hover:bg-rose-500 hover:text-white hover:border-rose-500
                               transition-all duration-200 disabled:opacity-50">
                    <span wire:loading.remove wire:target="restoreTrashedsFiliars"
                        class="inline-flex items-center gap-2">
                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                        Restaurer ({{ __zero($this->trashedsFiliars) }})
                    </span>
                    <span wire:loading wire:target="restoreTrashedsFiliars" class="inline-flex items-center gap-2">
                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        Restauration...
                    </span>
                </button>
            @endif
        </div>

        {{-- ========== LISTE ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">

            {{-- Header + filtres --}}
            <div class="p-5 sm:p-6 border-b border-white/[0.05]">
                <div class="flex flex-col gap-5">
                    <div>
                        <h2 class="text-lg font-semibold text-white">
                            Liste des filières
                            @if ($is_active)
                                <span class="ml-2 text-xs font-mono uppercase tracking-wider text-orange-400/70">
                                    {{ $is_active }}
                                </span>
                            @endif
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Analyse détaillée des filières
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                        <div class="relative sm:col-span-3">
                            <x-lucide-search
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Rechercher une filière..."
                                class="w-full h-11 rounded-xl bg-[#070a12] border border-white/[0.08]
                                          pl-10 pr-4 text-sm text-white placeholder:text-slate-600
                                          outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                          transition-all" />
                        </div>

                        <select wire:model.live="is_active"
                            class="h-11 sm:col-span-2 rounded-xl bg-[#070a12] border border-white/[0.08]
                                       px-3 text-sm text-slate-300 focus:border-indigo-500/50 focus:outline-none transition">
                            <option value="">
                                Toutes ({{ __zero($this->activesFiliars + $this->unActivesFiliars) }})
                            </option>
                            <option value="actives">
                                Actives ({{ __zero($this->activesFiliars) }})
                            </option>
                            <option value="desactives">
                                Désactivées ({{ __zero($this->unActivesFiliars) }})
                            </option>
                            <option value="corbeille">
                                Corbeille ({{ __zero($this->trashedsFiliars) }})
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="relative p-4 sm:p-5">

                {{-- Loading overlay --}}
                <div wire:loading wire:target="is_active,search,previousPage,nextPage,resetFilters,gotoPage"
                    class="absolute inset-0 z-20 flex items-center justify-center bg-[#070a12]/60 backdrop-blur-sm rounded-b-2xl">
                    <div class="flex items-center gap-3 text-slate-400">
                        <x-lucide-loader-2 class="w-6 h-6 text-indigo-400 animate-spin" />
                        <span class="text-sm font-medium">Chargement...</span>
                    </div>
                </div>

                @if (count($this->filiars))
                    {{-- Cards au lieu de table --}}
                    <div class="space-y-3">
                        @foreach ($this->filiars as $filiar)
                            @php
                                $details = app(\App\Services\FiliarsServices\FiliarDetailsCacheService::class)->get(
                                    $filiar->id,
                                );
                            @endphp

                            <article
                                class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02]
                                            hover:border-indigo-500/25 hover:bg-white/[0.03]
                                            transition-all duration-300 overflow-hidden
                                            @if ($filiar->deleted_at) opacity-60 @endif"
                                wire:key="filiar-{{ $filiar->id }}">

                                {{-- Accent bar --}}
                                <div
                                    class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-indigo-500 to-violet-600
                                            opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>

                                <div class="p-4 sm:p-5">
                                    <div class="flex flex-col xl:flex-row xl:items-center gap-4">

                                        {{-- Identité --}}
                                        <div class="flex items-start gap-3 min-w-0 xl:w-[220px] shrink-0">
                                            <span class="text-xs font-mono text-slate-600 mt-1 shrink-0">
                                                {{ __zero($this->filiars->firstItem() + $loop->iteration - 1) }}
                                            </span>
                                            <div class="min-w-0">
                                                <a wire:navigate
                                                    href="{{ route('tenant.filiar.profil', ['filiar_slug' => $filiar->slug]) }}"
                                                    class="block group/link">
                                                    <h3
                                                        class="font-semibold text-white truncate
                                                               group-hover/link:text-indigo-300 transition-colors">
                                                        {{ $filiar->name }}
                                                    </h3>
                                                    <p class="text-xs font-mono text-slate-500 mt-0.5">
                                                        {{ $filiar->code }}
                                                    </p>
                                                </a>
                                            </div>
                                        </div>

                                        {{-- Effectifs --}}
                                        <div class="flex items-center gap-3 xl:w-[140px] shrink-0">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                                         bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                <x-lucide-school class="w-3 h-3" />
                                                {{ __zero($details['classes_count']) }}
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                                         bg-violet-500/10 text-violet-400 border border-violet-500/20">
                                                <x-lucide-users class="w-3 h-3" />
                                                {{ __zero($details['students_count']) }}
                                            </span>
                                        </div>

                                        {{-- Stats élèves --}}
                                        <div class="flex-1 grid grid-cols-2 sm:grid-cols-4 gap-3 min-w-0">
                                            {{-- Best --}}
                                            <div class="min-w-0">
                                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">
                                                    Meilleur</p>
                                                <p class="text-xs font-medium text-slate-300 truncate">KOUASSI Sarah</p>
                                                <p class="text-xs text-emerald-400 font-mono">(18.92)</p>
                                            </div>
                                            {{-- Worst --}}
                                            <div class="min-w-0">
                                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">
                                                    Plus faible</p>
                                                <p class="text-xs font-medium text-slate-300 truncate">HOUNKPE David</p>
                                                <p class="text-xs text-rose-400 font-mono">(03.42)</p>
                                            </div>
                                            {{-- Youngest --}}
                                            <div class="min-w-0">
                                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">
                                                    Plus jeune</p>
                                                <p class="text-xs font-medium text-slate-300 truncate">ADJOVI Esther
                                                </p>
                                                <p class="text-xs text-slate-500">10 ans</p>
                                            </div>
                                            {{-- Oldest --}}
                                            <div class="min-w-0">
                                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">
                                                    Plus âgé</p>
                                                <p class="text-xs font-medium text-slate-300 truncate">AKAKPO Jonas</p>
                                                <p class="text-xs text-slate-500">19 ans</p>
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex flex-wrap items-center gap-1.5 xl:justify-end shrink-0">
                                            <a wire:navigate
                                                href="{{ route('tenant.filiar.profil', ['filiar_slug' => $filiar->slug]) }}"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                      bg-sky-500/15 text-sky-400 border border-sky-500/25
                                                      hover:bg-sky-500 hover:text-white hover:border-sky-500
                                                      transition-all duration-200">
                                                <x-lucide-eye class="w-3.5 h-3.5" />
                                                Voir
                                            </a>

                                            <button
                                                wire:click="{{ $filiar->is_active ? 'closeFiliar(' . $filiar->id . ')' : 'activateFiliar(' . $filiar->id . ')' }}"
                                                wire:loading.attr="disabled" wire:target="activateFiliar, closeFiliar"
                                                title="{{ $filiar->is_active ? 'Fermer' : 'Activer' }} cette filière"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                           {{ $filiar->is_active
                                                               ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500 hover:text-white hover:border-amber-500'
                                                               : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500' }}
                                                           transition-all duration-200 disabled:opacity-50">
                                                <span wire:loading.remove wire:target="activateFiliar, closeFiliar">
                                                    @if ($filiar->is_active)
                                                        <x-lucide-power class="w-3.5 h-3.5" />
                                                    @else
                                                        <x-lucide-power class="w-3.5 h-3.5" />
                                                    @endif
                                                </span>
                                                <span wire:loading wire:target="activateFiliar, closeFiliar">
                                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>

                                            <button
                                                wire:click="{{ $filiar->deleted_at ? 'restoreFiliar(' . $filiar->id . ')' : 'deleteFiliar(' . $filiar->id . ')' }}"
                                                wire:loading.attr="disabled" wire:target="deleteFiliar, restoreFiliar"
                                                title="{{ $filiar->deleted_at ? 'Restaurer' : 'Mettre en corbeille' }}"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                           {{ $filiar->deleted_at
                                                               ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                                                               : 'bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white hover:border-rose-500' }}
                                                           transition-all duration-200 disabled:opacity-50">
                                                <span wire:loading.remove wire:target="deleteFiliar, restoreFiliar">
                                                    @if ($filiar->deleted_at)
                                                        <x-lucide-refresh-ccw class="w-3.5 h-3.5" />
                                                    @else
                                                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                    @endif
                                                </span>
                                                <span wire:loading wire:target="deleteFiliar, restoreFiliar">
                                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($this->filiars->hasPages())
                        <div class="mt-6 pt-5 border-t border-white/[0.05]">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <p class="text-sm text-slate-500">
                                    {{ $this->filiars->firstItem() }}–{{ $this->filiars->lastItem() }}
                                    sur <span class="text-slate-300 font-medium">{{ $this->filiars->total() }}</span>
                                </p>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if (!$this->filiars->onFirstPage())
                                        <button wire:click="previousPage" wire:loading.attr="disabled"
                                            wire:target="previousPage"
                                            class="h-9 px-3.5 rounded-lg text-sm text-slate-300
                                                       bg-white/[0.04] border border-white/[0.06]
                                                       hover:bg-white/[0.08] hover:text-white
                                                       transition-all disabled:opacity-50">
                                            Précédent
                                        </button>
                                    @endif

                                    @foreach ($this->filiars->getUrlRange(1, $this->filiars->lastPage()) as $page => $url)
                                        <button wire:click="gotoPage({{ $page }})"
                                            @disabled($page === $this->filiars->currentPage())
                                            class="h-9 w-9 rounded-lg text-sm font-medium transition-all
                                                       {{ $page === $this->filiars->currentPage()
                                                           ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/25'
                                                           : 'bg-white/[0.04] text-slate-400 border border-white/[0.06] hover:bg-white/[0.08] hover:text-white' }}">
                                            {{ $page }}
                                        </button>
                                    @endforeach

                                    @if ($this->filiars->hasMorePages())
                                        <button wire:click="nextPage" wire:loading.attr="disabled"
                                            wire:target="nextPage"
                                            class="h-9 px-3.5 rounded-lg text-sm text-slate-300
                                                       bg-white/[0.04] border border-white/[0.06]
                                                       hover:bg-white/[0.08] hover:text-white
                                                       transition-all disabled:opacity-50">
                                            Suivant
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    {{-- Empty state --}}
                    <div class="py-16 text-center">
                        <div
                            class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-500/10 mb-4">
                            <x-lucide-layers class="w-7 h-7 text-indigo-400" />
                        </div>
                        <p class="text-slate-400 text-sm">Aucune filière trouvée</p>
                        @if ($search || $is_active)
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
        </section>

    </div>
</div>
