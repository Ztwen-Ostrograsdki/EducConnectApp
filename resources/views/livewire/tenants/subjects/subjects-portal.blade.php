<div class="w-full overflow-x-hidden bg-[#0b0f19] min-h-screen">

    <div class="mx-auto w-full max-w-[1850px] px-4 sm:px-6 lg:px-8 py-8">

        {{-- ===================== HEADER ===================== --}}
        <header class="mb-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-violet-400/80 mb-2">
                        Administration
                    </p>
                    <h1 class="text-3xl sm:text-4xl font-semibold text-white tracking-tight">
                        Dashboard des matières
                    </h1>
                    <p class="mt-2 text-slate-400 text-sm max-w-lg">
                        Gestion centralisée des matières de l’établissement
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end">
                        <span
                            class="text-2xl font-semibold text-white tabular-nums">{{ __zero($this->subjects->total()) }}</span>
                        <span class="text-xs text-slate-500 uppercase tracking-wider">Matières</span>
                    </div>
                    <a wire:navigate href="{{ route('tenant.subject.create') }}"
                        class="inline-flex items-center gap-2 h-11 px-5 rounded-full bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium shadow-lg shadow-violet-900/30 transition-all active:scale-[0.97]">
                        + Créer une matière
                    </a>
                </div>
            </div>
        </header>

        {{-- ===================== FILTERS ===================== --}}
        <section class="mb-8">
            <div class="rounded-2xl bg-[#121826] border border-white/5 p-5">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Rechercher une matière…"
                                class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500">🔍</span>
                        </div>
                        <button wire:click="clearFilters"
                            class="h-11 px-5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all shrink-0">
                            <span wire:loading.remove wire:target="clearFilters" class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4" />
                                Réinitialiser
                            </span>
                            <span wire:loading wire:target="clearFilters" class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                Rechargement…
                            </span>
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-2.5">
                        <select wire:model.live="is_active"
                            class="h-10 min-w-[200px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">
                                Toutes ({{ __zero($this->activesSubjects + $this->unActivesSubjects) }})
                            </option>
                            <option value="actives">Actives ({{ __zero($this->activesSubjects) }})</option>
                            <option value="desactives">Désactivées ({{ __zero($this->unActivesSubjects) }})</option>
                            <option value="corbeille">Corbeille ({{ __zero($this->trashedsSubjects) }})</option>
                        </select>

                        <select wire:model.live="type"
                            class="h-10 min-w-[180px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Tous types de matières</option>
                            @foreach (config('app.subject_types') as $subk => $sub)
                                <option value="{{ $sub }}">{{ $sub }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== ACTIONS ===================== --}}
        <section class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 hover:bg-indigo-500/20 transition-all">
                    Imprimer PDF
                </button>
                <button
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all">
                    Exporter Excel
                </button>

                @if ($this->unActivesSubjects)
                    <button title="Réactiver les {{ $this->unActivesSubjects }} matières désactivées"
                        wire:click="activateUnactivesSubjects" wire:loading.attr="disabled"
                        wire:target="activateUnactivesSubjects"
                        class="h-9 px-3.5 rounded-lg text-xs font-medium bg-amber-500/10 text-amber-300 border border-amber-500/20 hover:bg-amber-500/20 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="activateUnactivesSubjects"
                            class="inline-flex items-center gap-1.5">
                            <x-lucide-unlock class="w-3.5 h-3.5" />
                            Réactiver ({{ __zero($this->unActivesSubjects) }})
                        </span>
                        <span wire:loading wire:target="activateUnactivesSubjects">
                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                @endif

                @if ($this->trashedsSubjects)
                    <button title="Restaurer les {{ $this->trashedsSubjects }} matières de la corbeille"
                        wire:click="restoreTrashedsSubjects" wire:loading.attr="disabled"
                        wire:target="restoreTrashedsSubjects"
                        class="h-9 px-3.5 rounded-lg text-xs font-medium bg-rose-500/10 text-rose-300 border border-rose-500/20 hover:bg-rose-500/20 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="restoreTrashedsSubjects"
                            class="inline-flex items-center gap-1.5">
                            <x-lucide-recycle class="w-3.5 h-3.5" />
                            Restaurer ({{ __zero($this->trashedsSubjects) }})
                        </span>
                        <span wire:loading wire:target="restoreTrashedsSubjects">
                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                @endif
            </div>
        </section>

        {{-- ===================== LIST ===================== --}}
        <section class="relative mb-16">
            <div wire:loading
                wire:target="clearFilters,is_active,type,activateSubject,activateUnactivesSubjects,restoreTrashedsSubjects,desactivateSubject,deleteSubject,forceDeleteSubject,search,previousPage,nextPage,gotoPage"
                class="absolute inset-0 z-20 flex items-center justify-center bg-[#0b0f19]/70 rounded-2xl">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                    <svg class="animate-spin w-8 h-8 text-violet-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>
                    <span class="text-sm font-mono">Chargement…</span>
                </div>
            </div>

            @if (count($this->subjects))
                <div class="space-y-4">
                    @foreach ($this->subjects as $subject)
                        @php
                            $details = app(\App\Services\SubjectsServices\SubjectDetailsCacheService::class)->get(
                                $subject->id,
                            );
                            $orderNumber = $this->subjects->firstItem() + $loop->iteration - 1;
                        @endphp

                        <article
                            class="rounded-2xl bg-[#121826] border border-white/5 hover:border-violet-500/20 transition-all overflow-hidden">
                            <div class="p-5 sm:p-6">
                                <div class="flex flex-col xl:flex-row gap-6">

                                    {{-- N° + IDENTITY --}}
                                    <div class="flex gap-4 min-w-0 xl:w-[280px] shrink-0">
                                        {{-- Numéro d'ordre --}}
                                        <div class="flex items-start pt-1">
                                            <span
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-violet-500/15 border border-violet-500/25 text-violet-300 text-sm font-bold tabular-nums">
                                                {{ __zero($orderNumber) }}
                                            </span>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <a wire:navigate
                                                href="{{ route('tenant.subject.profil', ['subject_slug' => $subject->slug]) }}"
                                                class="block group">
                                                <h3
                                                    class="font-semibold text-white group-hover:text-violet-300 transition-colors truncate">
                                                    {{ $subject->name }}
                                                </h3>
                                                <p
                                                    class="mt-0.5 text-xs font-mono text-slate-500 uppercase group-hover:text-violet-400/70">
                                                    {{ $subject->code }}
                                                </p>
                                            </a>

                                            <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-400">
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="text-slate-500">Ens.</span>
                                                    <span
                                                        class="font-semibold text-slate-200">{{ __zero($details['teachers_count']) }}</span>
                                                </span>
                                                <span class="text-slate-700">·</span>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="text-slate-500">Classes</span>
                                                    <span
                                                        class="font-semibold text-slate-200">{{ __zero($details['classes_count']) }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- CHEFS + STATS --}}
                                    <div
                                        class="flex-1 min-w-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-6 space-y-4">
                                        {{-- Chefs d'atelier --}}
                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-2">Chefs
                                                d’atelier</p>
                                            @if ($details['chief'])
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($details['chief'] as $ck => $chief)
                                                        @if ($ck === 'principal')
                                                            <div
                                                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-xs">
                                                                <span
                                                                    class="text-emerald-400 font-medium">Principal</span>
                                                                @if ($chief)
                                                                    <span
                                                                        class="text-slate-300">{{ $chief['full_name'] ?? '' }}</span>
                                                                @else
                                                                    <span class="text-amber-400/80 animate-pulse">Non
                                                                        renseigné</span>
                                                                @endif
                                                            </div>
                                                        @elseif ($ck === 'adjoint')
                                                            <div
                                                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-sky-500/10 border border-sky-500/20 text-xs">
                                                                <span class="text-sky-400 font-medium">Adjoint</span>
                                                                @if ($chief)
                                                                    <span
                                                                        class="text-slate-300">{{ $chief['full_name'] ?? '' }}</span>
                                                                @else
                                                                    <span class="text-amber-400/80 animate-pulse">Non
                                                                        renseigné</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <span
                                                    class="text-xs text-slate-600 italic animate-pulse">Chargement…</span>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap gap-4 text-xs text-slate-500">
                                            <span>
                                                Meilleure classe :
                                                <span
                                                    class="text-slate-300">{{ $details['best_classe'] ?? '—' }}</span>
                                            </span>
                                            <span>
                                                Volume horaire :
                                                <span class="text-slate-400">indisponible</span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- ACTIONS --}}
                                    <div
                                        class="xl:w-[200px] shrink-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-5 flex flex-col gap-2">
                                        <a wire:navigate
                                            href="{{ route('tenant.subject.edit', ['subject_slug' => $subject->slug]) }}"
                                            class="h-9 px-3 rounded-lg bg-indigo-500/15 hover:bg-indigo-500/25 border border-indigo-500/20 text-indigo-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all">
                                            <x-lucide-pen class="w-3.5 h-3.5" />
                                            Éditer
                                        </a>

                                        <a wire:navigate
                                            href="{{ route('tenant.subject.profil', ['subject_slug' => $subject->slug]) }}"
                                            class="h-9 px-3 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/20 text-sky-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all">
                                            <x-lucide-eye class="w-3.5 h-3.5" />
                                            Voir détails
                                        </a>

                                        <button
                                            wire:click="{{ $subject->is_active ? 'desactivateSubject(' . $subject->id . ')' : 'activateSubject(' . $subject->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="activateSubject, desactivateSubject"
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ $subject->is_active
                                                           ? 'bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-300'
                                                           : 'bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-300' }}">
                                            <span wire:loading.remove wire:target="activateSubject, desactivateSubject"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($subject->is_active)
                                                    <x-lucide-lock class="w-3.5 h-3.5" /> Fermer
                                                @else
                                                    <x-lucide-unlock class="w-3.5 h-3.5" /> Activer
                                                @endif
                                            </span>
                                            <span wire:loading wire:target="activateSubject, desactivateSubject">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        <button
                                            wire:click="{{ $subject->deleted_at ? 'restoreSubject(' . $subject->id . ')' : 'deleteSubject(' . $subject->id . ')' }}"
                                            wire:loading.attr="disabled" wire:target="deleteSubject, restoreSubject"
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ $subject->deleted_at
                                                           ? 'bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-300'
                                                           : 'bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300' }}">
                                            <span wire:loading.remove wire:target="deleteSubject, restoreSubject"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($subject->deleted_at)
                                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5" /> Restaurer
                                                @else
                                                    <x-lucide-trash class="w-3.5 h-3.5" /> Corbeille
                                                @endif
                                            </span>
                                            <span wire:loading wire:target="deleteSubject, restoreSubject">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($this->subjects->hasPages())
                    <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-xs text-slate-500">
                            Affichage {{ $this->subjects->firstItem() }} à {{ $this->subjects->lastItem() }}
                            sur {{ $this->subjects->total() }} matières
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$this->subjects->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                    ← Précédent
                                </button>
                            @endif
                            @foreach ($this->subjects->getUrlRange(1, $this->subjects->lastPage()) as $page => $url)
                                <button @disabled($page === $this->subjects->currentPage()) wire:click="gotoPage({{ $page }})"
                                    class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                               {{ $page === $this->subjects->currentPage()
                                                   ? 'bg-violet-600 text-white'
                                                   : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                            @if ($this->subjects->hasMorePages())
                                <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                    Suivant →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-2xl bg-[#121826] border border-white/5 py-20 text-center">
                    <span class="text-4xl mb-4 block">📚</span>
                    <p class="text-slate-500 text-sm mb-4">Aucune matière trouvée</p>
                    @if ($search || $type || $is_active)
                        <button wire:click="clearFilters"
                            class="h-9 px-4 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all">
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            @endif
        </section>

    </div>
</div>
