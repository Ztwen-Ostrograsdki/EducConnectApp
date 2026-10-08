<div class="min-h-screen bg-[#070a12] text-slate-100">

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
                            Gestion académique · {{ $this->activeYear?->slug }}
                        </p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                            Portail des séries
                        </h1>
                        <p class="mt-1.5 text-sm text-slate-500 max-w-md">
                            Vue globale des séries, performances et gestion des classes
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                         bg-white/[0.04] border border-white/[0.08] text-slate-300">
                                <x-lucide-layers class="w-3.5 h-3.5 text-indigo-400" />
                                {{ __zero($this->serials->total()) }} séries
                            </span>

                            @if ($this->unActivesSerials)
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                             bg-rose-500/10 border border-rose-500/25 text-rose-400 animate-pulse">
                                    <x-lucide-power-off class="w-3.5 h-3.5" />
                                    {{ __zero($this->unActivesSerials) }} désactivées
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

            <a wire:navigate href="{{ route('tenant.serial.create') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                      bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                      hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all duration-200">
                <x-lucide-plus class="w-3.5 h-3.5" />
                Créer une série
            </a>

            <button
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                           bg-emerald-500/15 text-emerald-400 border border-emerald-500/25
                           hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all duration-200">
                <x-lucide-file-down class="w-3.5 h-3.5" />
                Export PDF
            </button>

            @if ($this->unActivesSerials)
                <button wire:click="activateUnactivesserials" wire:loading.attr="disabled"
                    wire:target="activateUnactivesserials"
                    title="Réactiver les {{ $this->unActivesSerials }} séries désactivées"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                               bg-amber-500/15 text-amber-400 border border-amber-500/25
                               hover:bg-amber-500 hover:text-white hover:border-amber-500
                               transition-all duration-200 disabled:opacity-50">
                    <span wire:loading.remove wire:target="activateUnactivesserials"
                        class="inline-flex items-center gap-2">
                        <x-lucide-power class="w-3.5 h-3.5" />
                        Réactiver ({{ __zero($this->unActivesSerials) }})
                    </span>
                    <span wire:loading wire:target="activateUnactivesserials" class="inline-flex items-center gap-2">
                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        Activation...
                    </span>
                </button>
            @endif

            @if ($this->trashedsSerials)
                <button wire:click="restoreTrashedsserials" wire:loading.attr="disabled"
                    wire:target="restoreTrashedsserials"
                    title="Restaurer les {{ $this->trashedsSerials }} séries de la corbeille"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                               bg-rose-500/15 text-rose-400 border border-rose-500/25
                               hover:bg-rose-500 hover:text-white hover:border-rose-500
                               transition-all duration-200 disabled:opacity-50">
                    <span wire:loading.remove wire:target="restoreTrashedsserials"
                        class="inline-flex items-center gap-2">
                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                        Restaurer ({{ __zero($this->trashedsSerials) }})
                    </span>
                    <span wire:loading wire:target="restoreTrashedsserials" class="inline-flex items-center gap-2">
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
                            Liste des séries
                            @if ($is_active)
                                <span class="ml-2 text-xs font-mono uppercase tracking-wider text-orange-400/70">
                                    {{ $is_active }}
                                </span>
                            @endif
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Analyse détaillée des séries
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                        <div class="relative sm:col-span-3">
                            <x-lucide-search
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                            <input wire:model.live.debounce.600ms="search" type="text"
                                placeholder="Rechercher une série..."
                                class="w-full h-11 rounded-xl bg-[#070a12] border border-white/[0.08]
                                          pl-10 pr-4 text-sm text-white placeholder:text-slate-600
                                          outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                          transition-all" />
                        </div>

                        <select wire:model.live="is_active"
                            class="h-11 sm:col-span-2 rounded-xl bg-[#070a12] border border-white/[0.08]
                                       px-3 text-sm text-slate-300 focus:border-indigo-500/50 focus:outline-none transition">
                            <option value="">
                                Toutes ({{ __zero($this->activesSerials + $this->unActivesSerials) }})
                            </option>
                            <option value="actives">
                                Actives ({{ __zero($this->activesSerials) }})
                            </option>
                            <option value="desactives">
                                Désactivées ({{ __zero($this->unActivesSerials) }})
                            </option>
                            <option value="corbeille">
                                Corbeille ({{ __zero($this->trashedsSerials) }})
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="relative p-4 sm:p-5">

                <div wire:loading wire:target="is_active,search,previousPage,nextPage,resetFilters,gotoPage"
                    class="absolute inset-0 z-20 flex items-center justify-center bg-[#070a12]/60 backdrop-blur-sm rounded-b-2xl">
                    <div class="flex items-center gap-3 text-slate-400">
                        <x-lucide-loader-2 class="w-6 h-6 text-indigo-400 animate-spin" />
                        <span class="text-sm font-medium">Chargement...</span>
                    </div>
                </div>

                @if (count($this->serials))
                    <div class="space-y-3">
                        @foreach ($this->serials as $serial)
                            @php
                                $details = app(\App\Services\SerialsServices\SerialDetailsCacheService::class)->get(
                                    $serial->id,
                                );
                            @endphp

                            <article
                                class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02]
                                            hover:border-indigo-500/25 hover:bg-white/[0.03]
                                            transition-all duration-300 overflow-hidden
                                            @if ($serial->deleted_at) opacity-60 @endif"
                                wire:key="serial-{{ $serial->id }}">

                                <div
                                    class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-indigo-500 to-violet-600
                                            opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>

                                <div class="p-4 sm:p-5">
                                    <div class="flex flex-col xl:flex-row xl:items-center gap-4">

                                        {{-- Identité --}}
                                        <div class="flex items-start gap-3 min-w-0 xl:w-[200px] shrink-0">
                                            <span class="text-xs font-mono text-slate-600 mt-1 shrink-0">
                                                {{ __zero($this->serials->firstItem() + $loop->iteration - 1) }}
                                            </span>
                                            <div class="min-w-0">
                                                <a wire:navigate
                                                    href="{{ route('tenant.serial.profil', ['serial_slug' => $serial->slug]) }}"
                                                    class="block group/link">
                                                    <h3
                                                        class="font-semibold text-white truncate
                                                               group-hover/link:text-indigo-300 transition-colors">
                                                        {{ $serial->name }}
                                                    </h3>
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
                                            <div class="min-w-0">
                                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">
                                                    Meilleur</p>
                                                <p class="text-xs font-medium text-slate-300 truncate">KOUASSI Sarah</p>
                                                <p class="text-xs text-emerald-400 font-mono">(18.92)</p>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">
                                                    Plus faible</p>
                                                <p class="text-xs font-medium text-slate-300 truncate">HOUNKPE David</p>
                                                <p class="text-xs text-rose-400 font-mono">(03.42)</p>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-0.5">
                                                    Plus jeune</p>
                                                <p class="text-xs font-medium text-slate-300 truncate">ADJOVI Esther</p>
                                                <p class="text-xs text-slate-500">10 ans</p>
                                            </div>
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
                                                href="{{ route('tenant.serial.profil', ['serial_slug' => $serial->slug]) }}"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                      bg-sky-500/15 text-sky-400 border border-sky-500/25
                                                      hover:bg-sky-500 hover:text-white hover:border-sky-500
                                                      transition-all duration-200">
                                                <x-lucide-eye class="w-3.5 h-3.5" />
                                                Voir
                                            </a>

                                            <button
                                                wire:click="{{ $serial->is_active ? 'closeSerial(' . $serial->id . ')' : 'activateSerial(' . $serial->id . ')' }}"
                                                wire:loading.attr="disabled" wire:target="activateSerial, closeSerial"
                                                title="{{ $serial->is_active ? 'Fermer' : 'Activer' }} cette série"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                           {{ $serial->is_active
                                                               ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500 hover:text-white hover:border-amber-500'
                                                               : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500' }}
                                                           transition-all duration-200 disabled:opacity-50">
                                                <span wire:loading.remove wire:target="activateSerial, closeSerial">
                                                    <x-lucide-power class="w-3.5 h-3.5" />
                                                </span>
                                                <span wire:loading wire:target="activateSerial, closeSerial">
                                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>

                                            <button
                                                wire:click="{{ $serial->deleted_at ? 'restoreSerial(' . $serial->id . ')' : 'deleteSerial(' . $serial->id . ')' }}"
                                                wire:loading.attr="disabled" wire:target="deleteSerial, restoreSerial"
                                                title="{{ $serial->deleted_at ? 'Restaurer' : 'Mettre en corbeille' }}"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                           {{ $serial->deleted_at
                                                               ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                                                               : 'bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white hover:border-rose-500' }}
                                                           transition-all duration-200 disabled:opacity-50">
                                                <span wire:loading.remove wire:target="deleteSerial, restoreSerial">
                                                    @if ($serial->deleted_at)
                                                        <x-lucide-refresh-ccw class="w-3.5 h-3.5" />
                                                    @else
                                                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                    @endif
                                                </span>
                                                <span wire:loading wire:target="deleteSerial, restoreSerial">
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
                    @if ($this->serials->hasPages())
                        <div class="mt-6 pt-5 border-t border-white/[0.05]">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <p class="text-sm text-slate-500">
                                    {{ $this->serials->firstItem() }}–{{ $this->serials->lastItem() }}
                                    sur <span class="text-slate-300 font-medium">{{ $this->serials->total() }}</span>
                                </p>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if (!$this->serials->onFirstPage())
                                        <button wire:click="previousPage" wire:loading.attr="disabled"
                                            wire:target="previousPage"
                                            class="h-9 px-3.5 rounded-lg text-sm text-slate-300
                                                       bg-white/[0.04] border border-white/[0.06]
                                                       hover:bg-white/[0.08] hover:text-white
                                                       transition-all disabled:opacity-50">
                                            Précédent
                                        </button>
                                    @endif

                                    @foreach ($this->serials->getUrlRange(1, $this->serials->lastPage()) as $page => $url)
                                        <button wire:click="gotoPage({{ $page }})"
                                            @disabled($page === $this->serials->currentPage())
                                            class="h-9 w-9 rounded-lg text-sm font-medium transition-all
                                                       {{ $page === $this->serials->currentPage()
                                                           ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/25'
                                                           : 'bg-white/[0.04] text-slate-400 border border-white/[0.06] hover:bg-white/[0.08] hover:text-white' }}">
                                            {{ $page }}
                                        </button>
                                    @endforeach

                                    @if ($this->serials->hasMorePages())
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
                    <div class="py-16 text-center">
                        <div
                            class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-500/10 mb-4">
                            <x-lucide-layers class="w-7 h-7 text-indigo-400" />
                        </div>
                        <p class="text-slate-400 text-sm">Aucune série trouvée</p>
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
