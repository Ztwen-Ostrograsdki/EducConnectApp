<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

        {{-- ========== HEADER ========== --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-cyan-400/80 mb-1">
                    Organisation pédagogique
                </p>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Emplois du temps
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Un emploi du temps par classe et par année scolaire
                </p>
            </div>
            <button type="button" wire:click="openCreatePlan" wire:loading.attr="disabled" wire:target="openCreatePlan"
                class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-sm font-medium
                       bg-cyan-500 hover:bg-cyan-400 text-white
                       shadow-lg shadow-cyan-500/20 transition-all shrink-0 disabled:opacity-60">
                <span wire:loading.remove wire:target="openCreatePlan" class="inline-flex items-center gap-2">
                    <x-lucide-plus class="w-4 h-4" />
                    Nouvel emploi du temps
                </span>
                <span wire:loading wire:target="openCreatePlan" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    Chargement…
                </span>
            </button>
        </div>

        {{-- ========== FILTRES ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 space-y-3">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Année scolaire --}}
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-calendar class="w-3.5 h-3.5 text-cyan-400" />
                        Année scolaire
                    </label>
                    <select id="school_year_id" wire:model.live="school_year_id"
                        class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                               px-3 text-sm text-white focus:border-cyan-500/50 focus:outline-none transition-all">
                        <option value="">Choisir une année</option>
                        @foreach ($this->schoolYears as $year)
                            <option value="{{ $year->id }}">
                                {{ $year->min_year }}–{{ $year->max_year }}{{ $year->is_closed ? ' (clôturée)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Type de filtre --}}
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-filter class="w-3.5 h-3.5 text-violet-400" />
                        Filtrer par
                    </label>
                    <select wire:model.live="filterBy"
                        class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                               px-3 text-sm text-white focus:border-cyan-500/50 focus:outline-none transition-all">
                        <option value="all">Tous les emplois du temps</option>
                        <option value="classe">Classe</option>
                        <option value="filiar">Filière</option>
                        <option value="promotion">Promotion</option>
                        <option value="serial">Série</option>
                        <option value="is_new_system">Système (nouveau / ancien)</option>
                    </select>
                </div>

                {{-- Valeur du filtre --}}
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-list-filter class="w-3.5 h-3.5 text-amber-400" />
                        Valeur
                    </label>
                    @if ($filterBy === 'all')
                        <select disabled
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]/50
                                   px-3 text-sm text-slate-600 cursor-not-allowed">
                            <option>—</option>
                        </select>
                    @elseif ($filterBy === 'classe')
                        <select wire:model.live="filterValue"
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                   px-3 text-sm text-white focus:border-cyan-500/50 focus:outline-none transition-all">
                            <option value="">Toutes les classes</option>
                            @foreach ($this->classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->name }}</option>
                            @endforeach
                        </select>
                    @elseif ($filterBy === 'filiar')
                        <select wire:model.live="filterValue"
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                   px-3 text-sm text-white focus:border-cyan-500/50 focus:outline-none transition-all">
                            <option value="">Toutes les filières</option>
                            @foreach ($this->filiars as $filiar)
                                <option value="{{ $filiar->id }}">{{ $filiar->name }} ({{ $filiar->code }})</option>
                            @endforeach
                        </select>
                    @elseif ($filterBy === 'promotion')
                        <select wire:model.live="filterValue"
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                   px-3 text-sm text-white focus:border-cyan-500/50 focus:outline-none transition-all">
                            <option value="">Toutes les promotions</option>
                            @foreach ($this->promotions as $promo)
                                <option value="{{ $promo->id }}">{{ $promo->name }} ({{ $promo->code }})</option>
                            @endforeach
                        </select>
                    @elseif ($filterBy === 'serial')
                        <select wire:model.live="filterValue"
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                   px-3 text-sm text-white focus:border-cyan-500/50 focus:outline-none transition-all">
                            <option value="">Toutes les séries</option>
                            @foreach ($this->serials as $serial)
                                <option value="{{ $serial->id }}">{{ $serial->name }} ({{ $serial->code }})
                                </option>
                            @endforeach
                        </select>
                    @elseif ($filterBy === 'is_new_system')
                        <select wire:model.live="filterValue"
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                   px-3 text-sm text-white focus:border-cyan-500/50 focus:outline-none transition-all">
                            <option value="">Tous les systèmes</option>
                            <option value="1">Nouveau système</option>
                            <option value="0">Ancien système</option>
                        </select>
                    @endif
                </div>

                {{-- Recherche --}}
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-search class="w-3.5 h-3.5 text-slate-500" />
                        Rechercher
                    </label>
                    <div class="relative">
                        <x-lucide-search
                            class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                        <input id="search" type="search" wire:model.live.debounce.300ms="search"
                            placeholder="Classe ou titre…"
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                   pl-10 pr-4 text-sm text-white placeholder:text-slate-600
                                   focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20
                                   outline-none transition-all" />
                    </div>
                </div>
            </div>
        </section>

        {{-- ========== LISTE DES PLANS (paginée) ========== --}}
        <div wire:loading.flex wire:target="school_year_id,filterBy,filterValue,search,gotoPage,previousPage,nextPage"
            class="hidden items-center justify-center py-16">
            <div class="flex flex-col items-center gap-3">
                <x-lucide-loader-2 class="w-8 h-8 text-cyan-400 animate-spin" />
                <p class="text-sm text-slate-500">Chargement des emplois du temps…</p>
            </div>
        </div>

        <div wire:loading.remove
            wire:target="school_year_id,filterBy,filterValue,search,gotoPage,previousPage,nextPage">
            @if ($this->plans->isEmpty())
                <div class="rounded-2xl border border-dashed border-white/[0.08] bg-white/[0.02] py-16 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-cyan-500/10 mb-4">
                        <x-lucide-calendar-clock class="w-7 h-7 text-cyan-400" />
                    </div>
                    <h2 class="font-semibold text-white">Aucun emploi du temps</h2>
                    <p class="mt-1 text-sm text-slate-500 mb-5">
                        Aucun résultat pour les filtres sélectionnés
                    </p>
                    <button wire:click="openCreatePlan" wire:loading.attr="disabled" wire:target="openCreatePlan"
                        class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-sm font-medium
                               bg-cyan-500 hover:bg-cyan-400 text-white transition-all disabled:opacity-60">
                        <x-lucide-plus class="w-4 h-4" /> Créer
                    </button>
                </div>
            @else
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($this->plans as $plan)
                        <article wire:key="plan-{{ $plan->id }}"
                            class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5
                                   hover:border-cyan-500/30 hover:bg-white/[0.03] transition-all">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 cursor-pointer" wire:click="openPlan({{ $plan->id }})">
                                    <h2
                                        class="truncate text-base font-bold text-white group-hover:text-cyan-300 transition-colors">
                                        {{ $plan->classe?->name ?? 'Classe supprimée' }}
                                    </h2>
                                    <p class="mt-0.5 text-sm text-slate-500 truncate">
                                        {{ $plan->title ?: 'Emploi du temps de la classe' }}
                                    </p>
                                    @if ($plan->classe)
                                        <p class="mt-1 text-[11px] text-slate-600 truncate">
                                            {{ $plan->classe->filiar?->name }}
                                            @if ($plan->classe->promotion)
                                                · {{ $plan->classe->promotion->name }}
                                            @endif
                                            @if ($plan->classe->serial)
                                                · {{ $plan->classe->serial->name }}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                @php
                                    $statusStyles = [
                                        'draft' => 'bg-amber-500/15 text-amber-400 border-amber-500/25',
                                        'published' => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/25',
                                        'archived' => 'bg-slate-500/15 text-slate-400 border-slate-500/25',
                                    ];
                                    $statusLabels = [
                                        'draft' => 'Brouillon',
                                        'published' => 'Publié',
                                        'archived' => 'Archivé',
                                    ];
                                @endphp
                                <span
                                    class="shrink-0 px-2 py-0.5 rounded-md text-[11px] font-medium border
                                             {{ $statusStyles[$plan->status] ?? $statusStyles['draft'] }}">
                                    {{ $statusLabels[$plan->status] ?? $plan->status }}
                                </span>
                            </div>

                            <div class="mt-4 flex items-center gap-3 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1.5">
                                    <x-lucide-calendar-days class="w-3.5 h-3.5" />
                                    {{ $plan->schoolYear?->min_year }}–{{ $plan->schoolYear?->max_year }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <x-lucide-clock class="w-3.5 h-3.5" />
                                    {{ $plan->slots_count }} créneau(x)
                                </span>
                                @if ($plan->classe?->is_new_system)
                                    <span
                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 text-[10px] font-medium">
                                        NS
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4 flex flex-wrap gap-1.5">
                                <button wire:click="openPlan({{ $plan->id }})" wire:loading.attr="disabled"
                                    wire:target="openPlan({{ $plan->id }})"
                                    class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                           bg-cyan-500/15 text-cyan-400 border border-cyan-500/25
                                           hover:bg-cyan-700 hover:text-white hover:border-cyan-500 transition-all
                                           disabled:opacity-50">
                                    <span wire:loading.remove wire:target="openPlan({{ $plan->id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-eye class="w-3.5 h-3.5" /> Ouvrir
                                    </span>
                                    <span wire:loading wire:target="openPlan({{ $plan->id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                                    </span>
                                </button>

                                <button wire:click="editPlan({{ $plan->id }})" wire:loading.attr="disabled"
                                    wire:target="editPlan({{ $plan->id }})"
                                    class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                           bg-white/[0.04] text-slate-400 border border-white/[0.08]
                                           hover:bg-white/[0.08] hover:text-white transition-all disabled:opacity-50">
                                    <span wire:loading.remove wire:target="editPlan({{ $plan->id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-pencil class="w-3.5 h-3.5" /> Modifier
                                    </span>
                                    <span wire:loading wire:target="editPlan({{ $plan->id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                                    </span>
                                </button>

                                <button wire:click="deletePlan({{ $plan->id }})" wire:loading.attr="disabled"
                                    wire:target="deletePlan({{ $plan->id }})"
                                    class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                           bg-rose-500/10 text-rose-400 border border-rose-500/20
                                           hover:bg-rose-500/20 hover:text-rose-300 transition-all disabled:opacity-50">
                                    <span wire:loading.remove wire:target="deletePlan({{ $plan->id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-trash-2 class="w-3.5 h-3.5" /> Supprimer
                                    </span>
                                    <span wire:loading wire:target="deletePlan({{ $plan->id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                                    </span>
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($this->plans->hasPages())
                    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs text-slate-500">
                            Affichage de
                            <span class="text-slate-300 font-medium">{{ $this->plans->firstItem() }}</span>
                            à
                            <span class="text-slate-300 font-medium">{{ $this->plans->lastItem() }}</span>
                            sur
                            <span class="text-slate-300 font-medium">{{ $this->plans->total() }}</span>
                            emploi(s) du temps
                        </p>
                        <div>
                            {{ $this->plans->links() }}
                        </div>
                    </div>
                @endif
            @endif
        </div>

        {{-- ========== PLAN OUVERT + GRILLE HEBDO ========== --}}
        @if ($this->currentPlan)
            @php
                $weekDays = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];
                $planSlots = $this->currentPlan->slots->sortBy(
                    fn($slot) => sprintf('%d-%s', $slot->day_of_week, $slot->starts_at),
                );
                $periods = $planSlots
                    ->groupBy(
                        fn($slot) => substr((string) $slot->starts_at, 0, 5) .
                            '|' .
                            substr((string) $slot->ends_at, 0, 5),
                    )
                    ->sortKeys();
                $slotColors = [
                    'bg-emerald-500/15 border-emerald-500/25 text-emerald-200',
                    'bg-sky-500/15 border-sky-500/25 text-sky-200',
                    'bg-violet-500/15 border-violet-500/25 text-violet-200',
                    'bg-amber-500/15 border-amber-500/25 text-amber-200',
                    'bg-rose-500/15 border-rose-500/25 text-rose-200',
                    'bg-cyan-500/15 border-cyan-500/25 text-cyan-200',
                ];
            @endphp

            <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 sm:p-6 space-y-5"
                wire:key="current-plan-{{ $this->currentPlan->id }}">
                {{-- Header plan --}}
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <p class="text-xs text-cyan-400 font-mono">
                            {{ $this->currentPlan->schoolYear?->slug }}
                        </p>
                        <h2 class="mt-1 text-lg font-bold text-white">
                            {{ $this->currentPlan->classe?->name }}
                            <span class="text-slate-500 font-normal">·</span>
                            {{ $this->currentPlan->title ?: 'Emploi du temps' }}
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Enseignants résolus depuis l’affectation active
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button title="Ajouter un créneau" wire:click="openCreateSlot" wire:loading.attr="disabled"
                            wire:target="openCreateSlot"
                            class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                   bg-indigo-500 hover:bg-indigo-400 text-white transition-all disabled:opacity-60">
                            <span wire:loading.remove wire:target="openCreateSlot"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-plus class="w-3.5 h-3.5" /> Créneau
                            </span>
                            <span wire:loading wire:target="openCreateSlot" class="inline-flex items-center gap-1.5">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                            </span>
                        </button>

                        @if (!$this->currentPlan->archived)
                            @if ($this->currentPlan->status !== 'published')
                                <button wire:click="publishPlan({{ $this->currentPlan->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="publishPlan({{ $this->currentPlan->id }})"
                                    class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                           border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/10 transition-all
                                           disabled:opacity-60">
                                    <span wire:loading.remove
                                        wire:target="publishPlan({{ $this->currentPlan->id }})">Publier</span>
                                    <span wire:loading wire:target="publishPlan({{ $this->currentPlan->id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                                    </span>
                                </button>
                            @endif
                            <button wire:click="archivePlan({{ $this->currentPlan->id }})"
                                wire:loading.attr="disabled" wire:target="archivePlan({{ $this->currentPlan->id }})"
                                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                       border border-white/[0.08] text-slate-400
                                       hover:bg-amber-600/40 hover:text-white transition-all disabled:opacity-60">
                                <span wire:loading.remove
                                    wire:target="archivePlan({{ $this->currentPlan->id }})">Archiver</span>
                                <span wire:loading wire:target="archivePlan({{ $this->currentPlan->id }})"
                                    class="inline-flex items-center gap-1">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                                </span>
                            </button>
                        @else
                            <button wire:click="unArchivePlan({{ $this->currentPlan->id }})"
                                wire:loading.attr="disabled"
                                wire:target="unArchivePlan({{ $this->currentPlan->id }})"
                                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                       border border-white/[0.08] text-slate-400
                                       hover:bg-slate-800 bg-slate-700/30 hover:text-white transition-all disabled:opacity-60">
                                <span wire:loading.remove
                                    wire:target="unArchivePlan({{ $this->currentPlan->id }})">Désarchiver</span>
                                <span wire:loading wire:target="unArchivePlan({{ $this->currentPlan->id }})"
                                    class="inline-flex items-center gap-1">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                                </span>
                            </button>
                        @endif

                        <button wire:click="deletePlan({{ $this->currentPlan->id }})" wire:loading.attr="disabled"
                            wire:target="deletePlan({{ $this->currentPlan->id }})"
                            class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                   border border-rose-500/25 text-rose-400 hover:bg-rose-500/15 transition-all
                                   disabled:opacity-60">
                            <span wire:loading.remove wire:target="deletePlan({{ $this->currentPlan->id }})"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" /> Supprimer
                            </span>
                            <span wire:loading wire:target="deletePlan({{ $this->currentPlan->id }})"
                                class="inline-flex items-center gap-1">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                            </span>
                        </button>
                    </div>
                </div>

                {{-- KPIs --}}
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
                    <div class="rounded-xl border border-white/[0.06] bg-[#070a12]/60 p-4 flex items-center gap-3">
                        <span
                            class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/20
                                     flex items-center justify-center shrink-0">
                            <x-lucide-calendar-clock class="w-5 h-5 text-emerald-400" />
                        </span>
                        <div>
                            <p class="text-[11px] text-slate-500">Créneaux / sem.</p>
                            <p class="text-xl font-bold text-white">{{ $planSlots->count() }}</p>
                        </div>
                    </div>
                    <div class="rounded-xl border border-white/[0.06] bg-[#070a12]/60 p-4 flex items-center gap-3">
                        <span
                            class="w-10 h-10 rounded-xl bg-violet-500/15 border border-violet-500/20
                                     flex items-center justify-center shrink-0">
                            <x-lucide-users class="w-5 h-5 text-violet-400" />
                        </span>
                        <div>
                            <p class="text-[11px] text-slate-500">Enseignants</p>
                            <p class="text-xl font-bold text-white">
                                {{ $planSlots->pluck('classe_subject_of_school_year_id')->unique()->count() }}
                            </p>
                        </div>
                    </div>
                    <div class="rounded-xl border border-white/[0.06] bg-[#070a12]/60 p-4 flex items-center gap-3">
                        <span
                            class="w-10 h-10 rounded-xl bg-sky-500/15 border border-sky-500/20
                                     flex items-center justify-center shrink-0">
                            <x-lucide-book-open class="w-5 h-5 text-sky-400" />
                        </span>
                        <div>
                            <p class="text-[11px] text-slate-500">Matières</p>
                            <p class="text-xl font-bold text-white">
                                {{ $planSlots->pluck('classe_subject_of_school_year_id')->unique()->count() }}
                            </p>
                        </div>
                    </div>
                    <div class="rounded-xl border border-white/[0.06] bg-[#070a12]/60 p-4 flex items-center gap-3">
                        <span
                            class="w-10 h-10 rounded-xl bg-cyan-500/15 border border-cyan-500/20
                                     flex items-center justify-center shrink-0">
                            <x-lucide-circle-check class="w-5 h-5 text-cyan-400" />
                        </span>
                        <div>
                            <p class="text-[11px] text-slate-500">Statut</p>
                            <p class="text-base font-bold text-white">
                                {{ ['draft' => 'Brouillon', 'published' => 'Publié', 'archived' => 'Archivé'][$this->currentPlan->status] ?? $this->currentPlan->status }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Grille hebdo --}}
                <div class="rounded-2xl border border-white/[0.06] overflow-hidden">
                    <div
                        class="flex flex-col gap-2 border-b border-white/[0.05] px-4 py-3
                                sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-white">Vue hebdomadaire</h3>
                            <p class="text-[11px] text-slate-500">Modifier / supprimer sur chaque créneau</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-400">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span> Cours
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full bg-sky-400"></span> Sciences
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full bg-violet-400"></span> Autres
                            </span>
                        </div>
                    </div>

                    @if ($planSlots->isEmpty())
                        <div class="py-14 text-center">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-cyan-500/10 mb-3">
                                <x-lucide-calendar-plus class="w-6 h-6 text-cyan-400" />
                            </div>
                            <p class="font-semibold text-white">Aucun créneau</p>
                            <p class="mt-1 text-sm text-slate-500 mb-4">Ajoutez les premières séances</p>
                            <button wire:click="openCreateSlot" wire:loading.attr="disabled"
                                wire:target="openCreateSlot"
                                class="inline-flex items-center gap-2 h-9 px-4 rounded-xl text-xs font-medium
                                       bg-cyan-500 hover:bg-cyan-400 text-white transition-all disabled:opacity-60">
                                <x-lucide-plus class="w-3.5 h-3.5" /> Ajouter un créneau
                            </button>
                        </div>
                    @else
                        <div class="overflow-x-auto p-2 sm:p-3">
                            <table
                                class="w-full min-w-[1050px] table-fixed border-separate border-spacing-1 text-left text-xs">
                                <thead>
                                    <tr>
                                        <th
                                            class="w-28 rounded-lg bg-white/[0.03] px-3 py-3 font-semibold text-cyan-400">
                                            Horaire
                                        </th>
                                        @foreach ($weekDays as $dayName)
                                            <th
                                                class="rounded-lg bg-white/[0.03] px-3 py-3 text-center font-semibold text-cyan-400">
                                                {{ $dayName }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($periods as $period => $slotsInPeriod)
                                        @php([$periodStart, $periodEnd] = explode('|', $period))
                                        <tr>
                                            <th
                                                class="rounded-lg border border-white/[0.04] bg-white/[0.02] px-3 py-3 text-center">
                                                <span
                                                    class="whitespace-nowrap font-mono font-normal text-slate-500 text-center flex items-center justify-center gap-1">
                                                    {{ \Illuminate\Support\Carbon::parse($periodStart)->format('H\hi') }}
                                                    <span class="text-slate-600">–</span>
                                                    {{ \Illuminate\Support\Carbon::parse($periodEnd)->format('H\hi') }}
                                                </span>
                                            </th>
                                            @foreach ($weekDays as $dayNumber => $dayName)
                                                @php($slot = $slotsInPeriod->firstWhere('day_of_week', $dayNumber))
                                                <td class="h-28 rounded-lg border border-white/[0.04] p-1 align-top">
                                                    @if ($slot)
                                                        @php($colorClass = $slotColors[abs(crc32((string) ($slot->subject?->name ?? $slot->id))) % count($slotColors)])
                                                        <div wire:key="weekly-slot-{{ $slot->id }}"
                                                            class="flex h-full min-h-24 flex-col rounded-lg border p-2 {{ $colorClass }}">
                                                            <div class="flex items-start justify-between gap-1">
                                                                <p class="line-clamp-2 font-bold leading-4">
                                                                    {{ $slot->subject?->name ?? 'Affectation indisponible' }}
                                                                </p>
                                                                @if (!$this->currentPlan->archived)
                                                                    <div class="flex shrink-0 items-center gap-0.5">
                                                                        <button type="button"
                                                                            wire:click="editSlot({{ $slot->id }})"
                                                                            wire:loading.attr="disabled"
                                                                            wire:target="editSlot({{ $slot->id }})"
                                                                            title="Modifier"
                                                                            class="rounded p-1 opacity-60 hover:opacity-100 hover:bg-white/10 transition-all disabled:opacity-40">
                                                                            <span wire:loading.remove
                                                                                wire:target="editSlot({{ $slot->id }})">
                                                                                <x-lucide-pencil class="w-3.5 h-3.5" />
                                                                            </span>
                                                                            <span wire:loading
                                                                                wire:target="editSlot({{ $slot->id }})">
                                                                                <x-lucide-loader-2
                                                                                    class="w-3.5 h-3.5 animate-spin" />
                                                                            </span>
                                                                        </button>
                                                                        <button type="button"
                                                                            wire:click="deleteSlot({{ $slot->id }}, {{ $this->currentPlan->id }})"
                                                                            wire:loading.attr="disabled"
                                                                            wire:target="deleteSlot({{ $slot->id }}, {{ $this->currentPlan->id }})"
                                                                            title="Supprimer"
                                                                            class="rounded p-1 opacity-60 hover:opacity-100 hover:bg-white/10 hover:text-rose-300 transition-all disabled:opacity-40">
                                                                            <span wire:loading.remove
                                                                                wire:target="deleteSlot({{ $slot->id }}, {{ $this->currentPlan->id }})">
                                                                                <x-lucide-trash-2
                                                                                    class="w-3.5 h-3.5" />
                                                                            </span>
                                                                            <span wire:loading
                                                                                wire:target="deleteSlot({{ $slot->id }}, {{ $this->currentPlan->id }})">
                                                                                <x-lucide-loader-2
                                                                                    class="w-3.5 h-3.5 animate-spin" />
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <p class="mt-1 truncate text-[11px] opacity-80">
                                                                {{ trim($slot->teacher?->getFullName() ?? '') ?: 'Enseignant non disponible' }}
                                                            </p>
                                                            @if ($slot->label)
                                                                <p class="mt-1 line-clamp-2 text-[11px] opacity-70">
                                                                    {{ $slot->label }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- ========== MODAL PLAN ========== --}}
        @if ($showPlanForm)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-data
                x-transition>
                <div class="w-full max-w-lg rounded-2xl border border-white/[0.08] bg-[#0c1019] shadow-2xl"
                    @click.outside="$wire.set('showPlanForm', false)">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                        <h3 class="text-base font-semibold text-white">
                            {{ $timePlanId ? 'Modifier l’emploi du temps' : 'Nouvel emploi du temps' }}
                        </h3>
                        <button wire:click="$set('showPlanForm', false)"
                            class="rounded-lg p-1.5 text-slate-500 hover:text-white hover:bg-white/[0.06] transition-all">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>
                    <form wire:submit="savePlan" class="p-5 space-y-4">
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-calendar class="w-3.5 h-3.5 text-cyan-400" /> Année scolaire
                            </label>
                            <select wire:model="school_year_id" required
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-cyan-500/50 focus:outline-none transition-all">
                                <option value="">Choisir…</option>
                                @foreach ($this->schoolYears as $year)
                                    <option value="{{ $year->id }}">
                                        {{ $year->min_year }}–{{ $year->max_year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_year_id')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-pencil class="w-3.5 h-3.5 text-cyan-400" /> Statut
                            </label>
                            <select wire:model="status" required
                                class="w-full h-10 rounded-xl uppercase border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-cyan-500/50 focus:outline-none transition-all">
                                @foreach (config('timeplan.statuses') as $sk => $sl)
                                    <option value="{{ $sk }}">{{ $sl }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-school class="w-3.5 h-3.5 text-amber-400" /> Classe
                            </label>
                            <select wire:model="classe_id" required
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-cyan-500/50 focus:outline-none transition-all">
                                <option value="">Choisir une classe…</option>
                                @foreach ($this->classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->name }}</option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-type class="w-3.5 h-3.5 text-violet-400" />
                                Titre <span class="text-slate-600 normal-case">(facultatif)</span>
                            </label>
                            <input wire:model="title" type="text" maxlength="150"
                                placeholder="Ex. Emploi du temps général"
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       placeholder:text-slate-600 focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20
                                       outline-none transition-all" />
                            @error('title')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-align-left class="w-3.5 h-3.5 text-slate-500" />
                                Notes <span class="text-slate-600 normal-case">(facultatif)</span>
                            </label>
                            <textarea wire:model="notes" rows="3"
                                class="w-full rounded-xl border border-white/[0.08] bg-[#070a12] px-3 py-2.5 text-sm text-white
                                       focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20
                                       outline-none transition-all resize-none"></textarea>
                            @error('notes')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-2 pt-3 border-t border-white/[0.05]">
                            <button type="button" wire:click="$set('showPlanForm', false)"
                                class="h-10 px-4 rounded-xl text-sm font-medium border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.06] hover:text-white transition-all">
                                Annuler
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="savePlan"
                                class="h-10 px-4 rounded-xl text-sm font-medium bg-cyan-500 hover:bg-cyan-400 text-white
                                       shadow-lg shadow-cyan-500/20 transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="savePlan">Enregistrer</span>
                                <span wire:loading wire:target="savePlan" class="inline-flex items-center gap-2">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" /> Enregistrement…
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ========== MODAL SLOT ========== --}}
        @if ($showSlotForm)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-data
                x-transition>
                <div class="w-full max-w-lg rounded-2xl border border-white/[0.08] bg-[#0c1019] shadow-2xl"
                    @click.outside="$wire.set('showSlotForm', false)">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                        <h3 class="text-base font-semibold text-white">
                            {{ $slotId ? 'Modifier le créneau' : 'Nouveau créneau' }}
                        </h3>
                        <button wire:click="$set('showSlotForm', false)"
                            class="rounded-lg p-1.5 text-slate-500 hover:text-white hover:bg-white/[0.06] transition-all">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>
                    <form wire:submit="saveSlot" class="p-5 space-y-4">
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-book-open class="w-3.5 h-3.5 text-sky-400" /> Matière / Enseignant
                            </label>
                            <select wire:model="assignment_id" required
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-cyan-500/50 focus:outline-none transition-all">
                                <option value="">Choisir une affectation…</option>
                                @foreach ($this->assignments as $assignment)
                                    <option value="{{ $assignment->id }}">
                                        {{ $assignment->subject?->name }}
                                        — {{ $assignment->teacher?->getFullName() ?? 'Sans enseignant' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assignment_id')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-calendar-days class="w-3.5 h-3.5 text-violet-400" /> Jour
                            </label>
                            <select wire:model="day_of_week" required
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-cyan-500/50 focus:outline-none transition-all">
                                @foreach ([1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'] as $num => $label)
                                    <option value="{{ $num }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                    <x-lucide-clock class="w-3.5 h-3.5 text-emerald-400" /> Début
                                </label>
                                <input wire:model="starts_at" type="time" required
                                    class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                           focus:border-cyan-500/50 focus:outline-none transition-all" />
                                @error('starts_at')
                                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                    <x-lucide-clock class="w-3.5 h-3.5 text-rose-400" /> Fin
                                </label>
                                <input wire:model="ends_at" type="time" required
                                    class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                           focus:border-cyan-500/50 focus:outline-none transition-all" />
                                @error('ends_at')
                                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-type class="w-3.5 h-3.5 text-amber-400" />
                                Libellé <span class="text-slate-600 normal-case">(facultatif)</span>
                            </label>
                            <input wire:model="slot_label" type="text" maxlength="150"
                                placeholder="Ex. Salle B12, TP…"
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       placeholder:text-slate-600 focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20
                                       outline-none transition-all" />
                            @error('slot_label')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-align-left class="w-3.5 h-3.5 text-slate-500" />
                                Notes <span class="text-slate-600 normal-case">(facultatif)</span>
                            </label>
                            <textarea wire:model="slot_notes" rows="2"
                                class="w-full rounded-xl border border-white/[0.08] bg-[#070a12] px-3 py-2.5 text-sm text-white
                                       focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20
                                       outline-none transition-all resize-none"></textarea>
                            @error('slot_notes')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-2 pt-3 border-t border-white/[0.05]">
                            <button type="button" wire:click="$set('showSlotForm', false)"
                                class="h-10 px-4 rounded-xl text-sm font-medium border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.06] hover:text-white transition-all">
                                Annuler
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="saveSlot"
                                class="h-10 px-4 rounded-xl text-sm font-medium bg-cyan-500 hover:bg-cyan-400 text-white
                                       shadow-lg shadow-cyan-500/20 transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="saveSlot">Enregistrer</span>
                                <span wire:loading wire:target="saveSlot" class="inline-flex items-center gap-2">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" /> Enregistrement…
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

