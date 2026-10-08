<div class="min-h-screen bg-[#070a12] text-slate-100">

    {{-- ========== HEADER ========== --}}
    <header class="sticky top-0 z-30 border-b border-white/[0.06] bg-[#070a12]/80 backdrop-blur-xl">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                            Portail des classes
                        </h1>
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                     bg-indigo-500/15 text-indigo-400 border border-indigo-500/25">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                            {{ $this->stats['classes_actives'] }}
                            active{{ $this->stats['classes_actives'] > 1 ? 's' : '' }}
                        </span>
                        @if ($this->activeYear)
                            <span
                                class="px-2.5 py-1 rounded-lg text-xs font-medium
                                         bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                {{ $this->activeYear->slug }}
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-slate-500">
                        Gestion des classes, promotions, séries et enseignants
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a wire:navigate href="{{ route('tenant.classes.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium
                              bg-indigo-500 hover:bg-indigo-400 text-white
                              shadow-lg shadow-indigo-500/20 transition-all duration-200">
                        <x-lucide-plus class="w-4 h-4" />
                        Ajouter une classe
                    </a>
                    <button
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium
                                   bg-white/[0.04] border border-white/[0.08] text-slate-300
                                   hover:bg-white/[0.08] hover:text-white transition-all duration-200">
                        <x-lucide-download class="w-4 h-4" />
                        Exporter
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

        {{-- ========== STATS ========== --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">

            {{-- Classes --}}
            <div
                class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5
                        hover:border-indigo-500/30 hover:bg-indigo-500/[0.03] transition-all duration-300 cursor-pointer">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-500/15 flex items-center justify-center
                                group-hover:scale-110 transition-transform duration-300">
                        <x-lucide-school class="w-5 h-5 text-indigo-400" />
                    </div>
                    <div class="text-right text-[11px] space-y-0.5">
                        <p class="text-orange-400/70">Inactives · {{ $this->stats['classes_unactives'] }}</p>
                        <p class="text-red-400/70">Fermées · {{ $this->stats['classes_closeds'] }}</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Classes actives</p>
                <p class="mt-1 text-3xl font-bold text-white tracking-tight">{{ $this->stats['classes_actives'] }}</p>
            </div>

            {{-- Apprenants --}}
            <div
                class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5
                        hover:border-sky-500/30 hover:bg-sky-500/[0.03] transition-all duration-300 cursor-pointer">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-sky-500/15 flex items-center justify-center
                                group-hover:scale-110 transition-transform duration-300">
                        <x-lucide-users class="w-5 h-5 text-sky-400" />
                    </div>
                    <div class="text-right text-[11px] space-y-0.5">
                        <p class="text-emerald-400/70">En classe ·
                            {{ number_format($this->stats['students_in_classe']) }}</p>
                        <p class="text-red-400/70">Sans ·
                            {{ number_format($this->stats['students'] - $this->stats['students_in_classe']) }}</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Apprenants</p>
                <p class="mt-1 text-3xl font-bold text-white tracking-tight">
                    {{ number_format($this->stats['students']) }}</p>
            </div>

            {{-- Enseignants --}}
            <div
                class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5
                        hover:border-violet-500/30 hover:bg-violet-500/[0.03] transition-all duration-300 cursor-pointer">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-violet-500/15 flex items-center justify-center
                                group-hover:scale-110 transition-transform duration-300">
                        <x-lucide-graduation-cap class="w-5 h-5 text-violet-400" />
                    </div>
                    <div class="text-right text-[11px] space-y-0.5">
                        <p class="text-emerald-400/70">En classe · {{ $this->stats['teachers_in_classes'] }}</p>
                        <p class="text-red-400/70">Sans ·
                            {{ $this->stats['teachers'] - $this->stats['teachers_in_classes'] }}</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Enseignants</p>
                <p class="mt-1 text-3xl font-bold text-white tracking-tight">{{ $this->stats['teachers'] }}</p>
            </div>

            {{-- Promotions --}}
            <div
                class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5
                        hover:border-amber-500/30 hover:bg-amber-500/[0.03] transition-all duration-300 cursor-pointer">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-500/15 flex items-center justify-center
                                group-hover:scale-110 transition-transform duration-300">
                        <x-lucide-target class="w-5 h-5 text-amber-400" />
                    </div>
                    <div class="text-right text-[11px] space-y-0.5">
                        <p class="text-slate-400">Filières · {{ $this->stats['filiars_actives'] }}</p>
                        <p class="text-slate-400">Séries · {{ $this->stats['serials_actives'] }}</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Promotions</p>
                <p class="mt-1 text-3xl font-bold text-white tracking-tight">{{ $this->stats['promotions_actives'] }}
                </p>
            </div>
        </div>

        {{-- ========== ACTIONS DOCUMENTS ========== --}}
        <div class="flex flex-wrap items-center gap-2">
            <button wire:click="generateClassesListAsPDF"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                           bg-sky-500/15 text-sky-400 border border-sky-500/25
                           hover:bg-sky-500 hover:text-white hover:border-sky-500 transition-all duration-200">
                <span wire:loading.remove wire:target="generateClassesListAsPDF" class="inline-flex items-center gap-2">
                    <x-lucide-file-down class="w-3.5 h-3.5" />
                    Liste PDF
                </span>
                <span wire:loading wire:target="generateClassesListAsPDF" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                    Génération...
                </span>
            </button>

            <a href="{{ route('tenant.classes.print.list') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                      bg-white/[0.04] text-slate-400 border border-white/[0.08]
                      hover:bg-white/[0.08] hover:text-white transition-all duration-200">
                <x-lucide-eye class="w-3.5 h-3.5" />
                Aperçu
            </a>

            <a wire:navigate href="{{ route('tenant.classes.print.configuration') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                      bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                      hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all duration-200">
                <x-lucide-printer class="w-3.5 h-3.5" />
                Config. PDF
            </a>

            <a wire:navigate href="{{ route('tenant.classes.docs') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium
                      bg-amber-500/15 text-amber-400 border border-amber-500/25
                      hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200">
                <x-lucide-folder class="w-3.5 h-3.5" />
                Fichiers
            </a>
        </div>

        {{-- ========== FILTRES ========== --}}
        <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5">
            <div class="flex flex-col gap-3">
                {{-- Search --}}
                <div class="relative">
                    <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher une classe..."
                        class="w-full h-11 rounded-xl bg-[#070a12] border border-white/[0.08] 
                                  pl-10 pr-4 text-sm text-white placeholder:text-slate-600
                                  outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                  transition-all" />
                </div>

                {{-- Selects --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2.5">
                    <select wire:model.live="promotion"
                        class="h-11 px-3 rounded-xl bg-[#070a12] border border-white/[0.08] text-sm text-slate-300
                                   focus:border-indigo-500/50 focus:outline-none transition">
                        <option value="">Toutes promotions</option>
                        @foreach ($this->promotions as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="filiar"
                        class="h-11 px-3 rounded-xl bg-[#070a12] border border-white/[0.08] text-sm text-slate-300
                                   focus:border-indigo-500/50 focus:outline-none transition">
                        <option value="">Toutes filières</option>
                        @foreach ($this->filiars as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="serial"
                        class="h-11 px-3 rounded-xl bg-[#070a12] border border-white/[0.08] text-sm text-slate-300
                                   focus:border-indigo-500/50 focus:outline-none transition">
                        <option value="">Toutes séries</option>
                        @foreach ($this->serials as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="status"
                        class="h-11 px-3 rounded-xl bg-[#070a12] border border-white/[0.08] text-sm text-slate-300
                                   focus:border-indigo-500/50 focus:outline-none transition">
                        <option value="">Les classes actives</option>
                        <option value="closed">Fermées</option>
                        <option value="open">Ouvertes</option>
                        <option value="active">Actives</option>
                        <option value="with_students">Ayant des apprenants</option>
                        <option value="with_leaves_students">Ayant des abandons</option>
                        <option value="without_students">Sans apprenants</option>
                        <option value="with_teachers">Ayant des enseignants</option>
                        <option value="without_teachers">Sans enseignants</option>
                    </select>

                    <button wire:click="resetFilters"
                        class="h-11 px-4 rounded-xl text-sm font-medium
                                   bg-white/[0.04] border border-white/[0.08] text-slate-400
                                   hover:bg-white/[0.08] hover:text-white transition-all">
                        Réinitialiser
                    </button>
                </div>
            </div>
        </div>

        {{-- ========== LISTE DES CLASSES ========== --}}
        <div>
            <div wire:loading.flex class="mb-4 items-center gap-2 text-sm text-slate-500">
                <x-lucide-loader-2 class="w-4 h-4 text-indigo-400 animate-spin" />
                Chargement...
            </div>

            @if ($this->classes->isEmpty())
                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-16 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-500/10 mb-4">
                        <x-lucide-school class="w-7 h-7 text-indigo-400" />
                    </div>
                    <p class="text-slate-400 text-sm">Aucune classe trouvée pour ces filtres.</p>
                    <button wire:click="resetFilters"
                        class="mt-4 px-4 py-2 rounded-xl text-sm
                                   bg-white/[0.04] border border-white/[0.08] text-slate-400
                                   hover:bg-white/[0.08] hover:text-white transition-all">
                        Réinitialiser les filtres
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" wire:loading.class="opacity-50">

                    @foreach ($this->classes as $classe)
                        <article
                            class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02]
                                        overflow-hidden
                                        hover:border-indigo-500/25 hover:bg-white/[0.03]
                                        transition-all duration-300"
                            wire:key="classe-{{ $classe->id }}">

                            {{-- Accent bar --}}
                            <div
                                class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-indigo-500 to-violet-600
                                        opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <div class="p-5">
                                {{-- Header card --}}
                                <a wire:navigate
                                    href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                                    class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <h2
                                                class="text-lg font-semibold text-white truncate
                                                       group-hover:text-indigo-300 transition-colors">
                                                {{ str()->replace(['-', '_'], ' ', $classe->name) }}
                                            </h2>

                                            @if ($classe->is_active)
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

                                            @if ($classe->is_locked)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                             bg-amber-500/15 text-amber-400 border border-amber-500/20">
                                                    <x-lucide-lock class="w-3 h-3" />
                                                    Verrouillée
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap gap-1.5">
                                            @if ($classe->promotion)
                                                <span
                                                    class="px-2 py-0.5 rounded-md text-[11px] font-medium
                                                             bg-indigo-500/10 text-indigo-400">
                                                    {{ $classe->promotion->name }}
                                                </span>
                                            @endif
                                            @if ($classe->filiar)
                                                <span
                                                    class="px-2 py-0.5 rounded-md text-[11px] font-medium
                                                             bg-violet-500/10 text-violet-400">
                                                    {{ $classe->filiar->name }}
                                                </span>
                                            @endif
                                            @if ($classe->serial)
                                                <span
                                                    class="px-2 py-0.5 rounded-md text-[11px] font-medium
                                                             bg-sky-500/10 text-sky-400">
                                                    {{ $classe->serial->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                {{-- Stats --}}
                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl bg-[#070a12]/80 border border-white/[0.04] p-3.5">
                                        <p class="text-[11px] text-slate-500 uppercase tracking-wider">Élèves</p>
                                        <div class="mt-1.5 flex items-baseline gap-1">
                                            <span
                                                class="text-xl font-bold text-white">{{ $classe->students_count }}</span>
                                            <span class="text-xs text-slate-500">/ {{ $classe->effectif_max }}</span>
                                        </div>
                                        @php $pct = $classe->effectif_max > 0 ? min(100, round($classe->students_count / $classe->effectif_max * 100)) : 0; @endphp
                                        <div class="mt-2 h-1 rounded-full bg-white/[0.06] overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500
                                                        {{ $pct >= 90 ? 'bg-rose-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                                style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                    <div class="rounded-xl bg-[#070a12]/80 border border-white/[0.04] p-3.5">
                                        <p class="text-[11px] text-slate-500 uppercase tracking-wider">Enseignants</p>
                                        <p class="mt-1.5 text-xl font-bold text-white">
                                            {{ __zero($classe->teachers_count) }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Meta --}}
                                <div class="mt-4 space-y-2 text-xs">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-slate-500 uppercase tracking-wider">Prof principal</span>
                                        <span class="text-slate-300 font-medium truncate">
                                            {{ $classe->principal?->getFullName() ?? 'Non défini' }}
                                        </span>
                                    </div>
                                    @foreach ($classe->responsables() as $key => $respo)
                                        <div class="flex items-center justify-between gap-2">
                                            <span
                                                class="text-slate-500 uppercase tracking-wider">{{ $key }}</span>
                                            <span class="text-slate-300 font-medium truncate">
                                                {{ $respo?->getFullName() ?? 'Non défini' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="border-t border-white/[0.05] px-5 py-3.5">
                                <div class="flex flex-wrap gap-2">
                                    <a wire:navigate
                                        href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium
                                              bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                                              hover:bg-indigo-500 hover:text-white hover:border-indigo-500
                                              transition-all duration-200">
                                        <x-lucide-eye class="w-3.5 h-3.5" />
                                        Voir
                                    </a>
                                    <a wire:navigate
                                        href="{{ route('tenant.classe.edit', ['classe_slug' => $classe->slug]) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium
                                              bg-white/[0.04] text-slate-400 border border-white/[0.08]
                                              hover:bg-white/[0.08] hover:text-white transition-all duration-200">
                                        <x-lucide-pen class="w-3.5 h-3.5" />
                                        Modifier
                                    </a>

                                    <div class="flex-1"></div>

                                    {{-- Corbeille --}}
                                    <button wire:click="moveClasseToTrash({{ $classe->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="moveClasseToTrash({{ $classe->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                   bg-rose-500/10 text-rose-400 border border-rose-500/20
                                                   hover:bg-rose-500 hover:text-white hover:border-rose-500
                                                   transition-all duration-200 disabled:opacity-50">
                                        <span wire:loading.remove
                                            wire:target="moveClasseToTrash({{ $classe->id }})">
                                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                        </span>
                                        <span wire:loading wire:target="moveClasseToTrash({{ $classe->id }})">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>

                                    {{-- Lock --}}
                                    <button
                                        wire:click="{{ $classe->is_locked ? 'unlockClasse(' . $classe->id . ')' : 'lockClasse(' . $classe->id . ')' }}"
                                        wire:loading.attr="disabled" wire:target="lockClasse, unlockClasse"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                   {{ $classe->is_locked
                                                       ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                                                       : 'bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500 hover:text-white hover:border-amber-500' }}
                                                   transition-all duration-200 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="lockClasse, unlockClasse">
                                            @if ($classe->is_locked)
                                                <x-lucide-lock-open class="w-3.5 h-3.5" />
                                            @else
                                                <x-lucide-lock class="w-3.5 h-3.5" />
                                            @endif
                                        </span>
                                        <span wire:loading wire:target="lockClasse, unlockClasse">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>

                                    {{-- Activate / Close --}}
                                    <button
                                        wire:click="{{ $classe->is_active ? 'closeClasse(' . $classe->id . ')' : 'activateClasse(' . $classe->id . ')' }}"
                                        wire:loading.attr="disabled" wire:target="activateClasse, closeClasse"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                   {{ $classe->is_active
                                                       ? 'bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500 hover:text-white hover:border-red-500'
                                                       : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500' }}
                                                   transition-all duration-200 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="activateClasse, closeClasse">
                                            @if ($classe->is_active)
                                                <x-lucide-power class="w-3.5 h-3.5" />
                                            @else
                                                <x-lucide-power class="w-3.5 h-3.5" />
                                            @endif
                                        </span>
                                        <span wire:loading wire:target="activateClasse, closeClasse">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ========== PAGINATION ========== --}}
        @if ($this->classes->hasPages())
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] px-5 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-sm text-slate-500">
                        {{ $this->classes->firstItem() }}–{{ $this->classes->lastItem() }}
                        sur <span class="text-slate-300 font-medium">{{ $this->classes->total() }}</span>
                    </p>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if ($this->classes->onFirstPage())
                            <span
                                class="h-9 px-3.5 rounded-lg text-sm text-slate-600 bg-white/[0.02] flex items-center">
                                Précédent
                            </span>
                        @else
                            <button wire:click="previousPage"
                                class="h-9 px-3.5 rounded-lg text-sm text-slate-300 bg-white/[0.04] border border-white/[0.06]
                                           hover:bg-white/[0.08] hover:text-white transition-all">
                                Précédent
                            </button>
                        @endif

                        @foreach ($this->classes->getUrlRange(1, $this->classes->lastPage()) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})"
                                class="h-9 w-9 rounded-lg text-sm font-medium transition-all
                                           {{ $page === $this->classes->currentPage()
                                               ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/25'
                                               : 'bg-white/[0.04] text-slate-400 border border-white/[0.06] hover:bg-white/[0.08] hover:text-white' }}">
                                {{ $page }}
                            </button>
                        @endforeach

                        @if ($this->classes->hasMorePages())
                            <button wire:click="nextPage"
                                class="h-9 px-3.5 rounded-lg text-sm text-slate-300 bg-white/[0.04] border border-white/[0.06]
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

    </div>
</div>
