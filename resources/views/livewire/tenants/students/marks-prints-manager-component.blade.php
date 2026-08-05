<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="mx-auto max-w-[1100px] px-4 sm:px-6 py-10 relative">

        {{-- Loading overlay --}}
        <div wire:loading wire:target="resetFilters, restoreSelects, initPrintProcess"
            class="fixed inset-0 z-[200] flex items-center justify-center bg-[#070b14]/80 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 rounded-full border-2 border-violet-500/30 border-t-violet-400 animate-spin"></div>
                <span class="text-sm font-mono text-slate-400 tracking-wide">Chargement…</span>
            </div>
        </div>

        {{-- ===================== HEADER ===================== --}}
        <header class="mb-10 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 text-[11px] font-semibold uppercase tracking-[0.2em] mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                Notes & Moyennes
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                Configuration de l’impression
            </h1>
            <p class="mt-3 text-slate-500 text-sm max-w-lg mx-auto">
                Période, matière, classes concernées et format du tableau de notes.
            </p>
        </header>

        <div class="flex flex-wrap justify-center gap-2 mb-8">
            <a wire:navigate href="{{ route('tenant.students.portal') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/15 text-violet-300 border border-violet-500/20 hover:bg-violet-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-users class="w-3.5 h-3.5" />
                Portail des apprenants
            </a>
            <a href="{{ route('tenant.notes.print.preview') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                <x-lucide-eye class="w-3.5 h-3.5" />
                Aperçu du document
            </a>
            <a wire:navigate href="{{ route('tenant.notes.docs') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-amber-500/15 text-amber-300 border border-amber-500/20 hover:bg-amber-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-file class="w-3.5 h-3.5" />
                Fichiers disponibles
            </a>
        </div>

        <div class="space-y-6">

            {{-- ═══ 1. PÉRIODE + MATIÈRE ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/25">
                        <span class="text-lg">📅</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Période et matière</h2>
                        <p class="text-[11px] text-slate-500">Base du calcul des notes et rangs</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                Période <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="period"
                                class="w-full h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                <option value="">Sélectionner le {{ $this->activeYear?->periodLabel() }}</option>
                                @foreach ($this->periods_types as $p)
                                    <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                Matière <span class="text-slate-600 normal-case tracking-normal">(optionnel)</span>
                            </label>
                            <select wire:model.live="subject_id"
                                class="w-full h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30 transition-all">
                                <option value="">Toutes matières (moyenne semestrielle)</option>
                                @foreach ($this->subjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->code ?: $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div wire:key="subject-hint-{{ $subject_id ?: 'none' }}"
                        class="flex items-start gap-3 rounded-xl bg-amber-500/5 border border-amber-500/15 px-4 py-3">
                        <x-lucide-info class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" />
                        <p class="text-xs text-slate-400 leading-relaxed">
                            @if ($subject_id)
                                Une matière est ciblée : le tableau affichera le détail des interrogations, devoirs,
                                moyenne et rang
                                <span class="text-amber-300 font-medium">pour cette matière uniquement</span>.
                            @else
                                Aucune matière ciblée : le tableau affichera la
                                <span class="text-amber-300 font-medium">moyenne semestrielle</span>
                                (toutes matières confondues, pondérée par coefficient) et le rang correspondant.
                            @endif
                        </p>
                    </div>
                </div>
            </section>

            {{-- ═══ 2. CIBLAGE CLASSES ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-cyan-500/15 border border-cyan-500/25">
                        <span class="text-lg">🎯</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Cibler les classes</h2>
                        <p class="text-[11px] text-slate-500">Périmètre des apprenants concernés</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @if (!$filiar_id && !$serial_id && !$promotion_id && !$promotionInGroups)
                            <select wire:model.live="classe_id"
                                class="h-11 rounded-xl bg-[#070b14] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-cyan-500/40 transition-all">
                                <option value="">Toutes les classes</option>
                                @foreach ($this->classes as $cl)
                                    <option value="{{ $cl->id }}">{{ $cl->code ?: $cl->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if (!$classe_id)
                            @if (!$promotion_id)
                                @if (!$serial_id)
                                    <select wire:model.live="filiar_id"
                                        class="h-11 rounded-xl bg-[#070b14] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-cyan-500/40 transition-all">
                                        <option value="">Toutes les filières</option>
                                        @foreach ($this->filiars as $f)
                                            <option value="{{ $f->id }}">{{ $f->code ?: $f->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @if (!$filiar_id)
                                    <select wire:model.live="serial_id"
                                        class="h-11 rounded-xl bg-[#070b14] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-cyan-500/40 transition-all">
                                        <option value="">Toutes les séries</option>
                                        @foreach ($this->serials as $sr)
                                            <option value="{{ $sr->id }}">{{ $sr->code ?: $sr->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            @endif

                            @if (!$filiar_id && !$serial_id && !$promotionInGroups)
                                <select wire:model.live="promotion_id"
                                    class="h-11 rounded-xl bg-[#070b14] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-cyan-500/40 transition-all">
                                    <option value="">Promotions spécifiées</option>
                                    @foreach ($this->promotions as $promo)
                                        <option value="{{ $promo->id }}">{{ $promo->code ?: $promo->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            @if (!$promotion_id)
                                <select wire:model.live="promotionInGroups"
                                    class="h-11 rounded-xl bg-[#070b14] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-cyan-500/40 transition-all">
                                    <option value="">Toutes les promotions</option>
                                    @foreach ($this->promotionsGrouped as $n)
                                        <option value="{{ $n }}">Promotion {{ $n }}</option>
                                    @endforeach
                                </select>
                            @endif
                        @endif

                        <input type="text" wire:model.live.debounce.400ms="level"
                            placeholder="Niveau (ex: 6ème, 2nde…)"
                            class="h-11 rounded-xl bg-[#070b14] border border-white/10 px-3 text-xs text-slate-300 placeholder:text-slate-600 focus:outline-none focus:border-cyan-500/40 transition-all">
                    </div>
                </div>
            </section>

            {{-- ═══ 3. STATUT APPRENANTS ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/25">
                        <span class="text-lg">👤</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Statut des apprenants</h2>
                        <p class="text-[11px] text-slate-500">Filtrer selon le statut</p>
                    </div>
                </div>
                <div class="p-6">
                    <select wire:model.live="leavesStatus"
                        class="w-full sm:w-1/2 h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                        @foreach ($leavesStatuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </section>

            {{-- ═══ 4. COMPTEUR + TITRE ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-xl bg-orange-500/15 border border-orange-500/25">
                            <span class="text-lg">📄</span>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-white">Document généré</h2>
                            <p class="text-[11px] text-slate-500">Classes concernées et titre</p>
                        </div>
                    </div>
                    <button wire:click="resetFilters"
                        class="h-9 px-4 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all inline-flex items-center gap-1.5">
                        <span wire:loading.remove wire:target="resetFilters" class="inline-flex items-center gap-1.5">
                            <x-lucide-refresh-ccw class="w-3.5 h-3.5" />
                            Réinitialiser
                        </span>
                        <span wire:loading wire:target="resetFilters">
                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-amber-500/15 border border-amber-500/25 text-amber-300 text-sm font-semibold tabular-nums">
                            {{ __zero($this->allClassesCounter) }}
                        </span>
                        <span class="text-sm text-slate-400">classe{{ $this->allClassesCounter > 1 ? 's' : '' }}
                            trouvée{{ $this->allClassesCounter > 1 ? 's' : '' }}</span>
                    </div>

                    <div wire:key="doc-title-{{ md5($this->currentDocTitle) }}"
                        class="rounded-xl bg-gradient-to-r from-orange-500/10 via-orange-500/5 to-transparent border border-orange-500/20 px-5 py-4">
                        <p class="text-[10px] uppercase tracking-[0.15em] text-orange-400/70 font-semibold mb-1.5">
                            Titre du document
                        </p>
                        <p class="text-base sm:text-lg font-semibold text-orange-200 leading-snug">
                            {{ $this->currentDocTitle }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- ═══ 5. COLONNES ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-500/15 border border-indigo-500/25">
                            <span class="text-lg">🗂️</span>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-white">Colonnes du tableau</h2>
                            <p class="text-[11px] text-slate-500">Personnaliser l’entête de la liste de notes</p>
                        </div>
                    </div>
                    <button wire:click="restoreSelects" wire:loading.attr="disabled" wire:target="restoreSelects"
                        class="h-9 px-4 rounded-lg bg-orange-500/10 hover:bg-orange-500/20 border border-orange-500/20 text-orange-300 text-xs font-medium transition-all inline-flex items-center gap-1.5 disabled:opacity-50">
                        <span wire:loading.remove wire:target="restoreSelects"
                            class="inline-flex items-center gap-1.5">
                            <x-lucide-trash class="w-3.5 h-3.5" />
                            Nettoyer
                        </span>
                        <span wire:loading wire:target="restoreSelects">
                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach ($columns as $key => $column)
                            @php
                                $order = array_search($key, $selectedColumns, true);
                                $irrelevant = $subject_id
                                    ? in_array($key, ['moy_semestrielle'])
                                    : in_array($key, [
                                        'interro1',
                                        'interro2',
                                        'interro3',
                                        'interro4',
                                        'moy_interro',
                                        'devoir1',
                                        'devoir2',
                                        'compo',
                                        'moy',
                                        'moy_coef',
                                    ]);
                            @endphp
                            <div wire:key="col-{{ $key }}" x-data="{ checked: {{ $order !== false ? 'true' : 'false' }} }"
                                class="relative rounded-xl border p-3.5 transition-all duration-200 cursor-pointer
                                        {{ $irrelevant ? 'opacity-40' : '' }}"
                                :class="checked
                                    ?
                                    'border-violet-500/50 bg-violet-500/10 shadow-lg shadow-violet-900/20' :
                                    'border-white/[0.06] bg-[#070b14] hover:border-white/15'">
                                <label class="flex items-center justify-between gap-2 cursor-pointer">
                                    <span class="flex items-center gap-2 min-w-0">
                                        <template x-if="checked">
                                            <span
                                                class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-violet-600 text-[10px] font-bold text-white">
                                                {{ $order !== false ? $order + 1 : '' }}
                                            </span>
                                        </template>
                                        <span
                                            class="text-xs font-medium text-slate-200 truncate {{ $irrelevant ? 'line-through' : '' }}">
                                            {{ $column['label'] }}
                                        </span>
                                    </span>
                                    <span class="relative flex shrink-0">
                                        <input type="checkbox" wire:click="toggleColumn('{{ $key }}')"
                                            x-on:click="checked = !checked"
                                            wire:key="input-{{ $key }}-{{ $order !== false ? 'on' : 'off' }}"
                                            @checked($order !== false) class="peer sr-only">
                                        <span
                                            class="h-5 w-9 rounded-full bg-slate-700 transition-colors duration-200 peer-checked:bg-violet-600"></span>
                                        <span
                                            class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-slate-400 shadow transition-transform duration-200 peer-checked:translate-x-4 peer-checked:bg-white"></span>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-start gap-2 rounded-xl bg-amber-500/5 border border-amber-500/15 px-4 py-3">
                        <x-lucide-alert-triangle class="w-3.5 h-3.5 text-amber-400 shrink-0 mt-0.5" />
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            @if (!$subject_id)
                                Colonnes barrées : sans matière ciblée, seule la moyenne semestrielle est calculable —
                                même cochées, elles resteront vides.
                            @else
                                Colonne barrée : la moyenne semestrielle n’est calculable que sans matière ciblée — elle
                                restera vide.
                            @endif
                        </p>
                    </div>
                </div>
            </section>

            {{-- ═══ 6. PRÉVISUALISATION ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06]">
                    <h2 class="text-sm font-semibold text-white">
                        @if (count($this->orderedColumns))
                            Aperçu de l’entête
                            <span class="text-violet-400 font-normal text-xs ml-1">(personnalisé)</span>
                        @else
                            Aperçu de l’entête
                            <span class="text-slate-500 font-normal text-xs ml-1">(par défaut)</span>
                        @endif
                    </h2>
                </div>

                <div class="p-4 overflow-x-auto">
                    @if (count($this->orderedColumns))
                        <table class="w-full min-w-[600px]">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th
                                        class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        N°</th>
                                    @foreach ($this->orderedColumns as $th)
                                        <th
                                            class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-violet-300/80 whitespace-nowrap">
                                            {{ $th }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                        </table>
                    @else
                        <table class="w-full min-w-[600px] opacity-60">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th
                                        class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        N°</th>
                                    @foreach ($this->defaultOrderedColumns as $th)
                                        <th
                                            class="px-4 py-3 text-center text-[11px] font-medium uppercase tracking-wider text-slate-500 whitespace-nowrap">
                                            {{ $th }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                        </table>
                        <p class="text-center text-slate-600 text-xs mt-4 animate-pulse">
                            Cochez des colonnes ci-dessus pour personnaliser…
                        </p>
                    @endif
                </div>
            </section>

            {{-- ═══ CTA ═══ --}}
            <div class="pt-2 pb-16">
                <button wire:click="initPrintProcess" wire:loading.attr="disabled" wire:target="initPrintProcess"
                    class="group relative w-full h-14 rounded-2xl overflow-hidden disabled:opacity-50 active:scale-[0.98] transition-all">
                    <span class="absolute inset-0 bg-gradient-to-r from-amber-600 via-orange-600 to-rose-600"></span>
                    <span
                        class="absolute inset-0 bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span
                        class="relative flex items-center justify-center gap-2.5 text-white font-semibold text-sm tracking-wide">
                        <span wire:loading.remove wire:target="initPrintProcess"
                            class="inline-flex items-center gap-2.5 flex-wrap justify-center">
                            <x-lucide-send class="w-5 h-5" />
                            <span>Lancer la procédure d’impression</span>
                            <span class="text-white/70 text-xs font-normal">
                                ({{ __zero($this->allClassesCounter) }}
                                classe{{ $this->allClassesCounter > 1 ? 's' : '' }})
                            </span>
                        </span>
                        <span wire:loading wire:target="initPrintProcess" class="inline-flex items-center gap-2.5">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            Génération en cours…
                        </span>
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

