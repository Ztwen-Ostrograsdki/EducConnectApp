<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="mx-auto max-w-[1100px] px-4 sm:px-6 py-10 relative">

        {{-- Loading overlay --}}
        <div wire:loading wire:target="resetFilters, restoreSelects, initPrintProcess"
            class="fixed inset-0 z-[200] flex items-center justify-center bg-[#070b14]/80 backdrop-blur-xs">
            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 top-1/3 rounded-full border-2 border-rose-500/30 border-t-rose-400 animate-spin">
                </div>
                <span class="text-sm font-mono text-slate-400 tracking-wide">Chargement…</span>
            </div>
        </div>

        {{-- ===================== HEADER ===================== --}}
        <header class="mb-10 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-300 text-[11px] font-semibold uppercase tracking-[0.2em] mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                Diagnostic des notes
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                Suivi de saisie des notes
            </h1>
            <p class="mt-3 text-slate-500 text-sm max-w-lg mx-auto">
                Identifiez les enseignants à jour ou en retard sur la saisie des notes pour une période donnée.
            </p>
        </header>
        <div class="flex flex-wrap justify-center gap-2 mb-8">
            <a wire:navigate href="{{ route('tenant.classes.portal') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/15 text-violet-300 border border-violet-500/20 hover:bg-violet-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-school class="w-3.5 h-3.5" />
                Portail des classes
            </a>
            <a href="{{ route('tenant.marks.reports.print.preview') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                <x-lucide-eye class="w-3.5 h-3.5" />
                Aperçu du document
            </a>
            <a wire:navigate href="{{ route('tenant.marks.reports.docs') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-amber-500/15 text-amber-300 border border-amber-500/20 hover:bg-amber-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-file class="w-3.5 h-3.5" />
                Fichiers disponibles
            </a>
        </div>

        <div class="space-y-6">

            {{-- ═══ 1. PÉRIODE + STATUT ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-rose-500/15 border border-rose-500/25">
                        <span class="text-lg">📅</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Période et statut</h2>
                        <p class="text-[11px] text-slate-500">Période à diagnostiquer et statut recherché</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                Période <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="period"
                                class="w-full h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-rose-500/50 focus:ring-1 focus:ring-rose-500/30 transition-all">
                                <option value="">Sélectionner le {{ $this->activeYear?->periodLabel() }}</option>
                                @foreach ($this->periods_types as $p)
                                    <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                Statut recherché
                            </label>
                            <select wire:model.live="status"
                                class="w-full h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-rose-500/50 focus:ring-1 focus:ring-rose-500/30 transition-all">
                                @foreach ($statusOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ═══ 2. TYPES DE NOTES ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/25">
                        <span class="text-lg">📝</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Types de notes à vérifier</h2>
                        <p class="text-[11px] text-slate-500">Interros, devoirs, compositions…</p>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach ($this->availableMarkTypes as $type => $label)
                            <label
                                class="flex items-center gap-2.5 rounded-xl border px-3.5 py-3 cursor-pointer transition-all duration-200
                                         {{ in_array($type, $checkedMarkTypes)
                                             ? 'border-amber-500/50 bg-amber-500/10 shadow-lg shadow-amber-900/10'
                                             : 'border-white/[0.06] bg-[#070b14] hover:border-white/15' }}">
                                <input type="checkbox" wire:click="toggleMarkType('{{ $type }}')"
                                    @checked(in_array($type, $checkedMarkTypes)) class="sr-only peer">
                                <span
                                    class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border transition-all
                                             {{ in_array($type, $checkedMarkTypes)
                                                 ? 'bg-amber-500 border-amber-500 text-white'
                                                 : 'border-slate-600 bg-transparent' }}">
                                    @if (in_array($type, $checkedMarkTypes))
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </span>
                                <span class="text-xs font-medium text-slate-200">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div
                        class="flex items-start gap-2.5 rounded-xl bg-amber-500/5 border border-amber-500/15 px-4 py-3">
                        <x-lucide-info class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" />
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Cocher une interro inclut automatiquement les précédentes (ex. Interro 3 → Interro 1 et 2).
                            Un enseignant est « à jour » si au moins <span class="text-amber-300 font-medium">95
                                %</span> de ses apprenants ont une valeur pour chaque type coché.
                        </p>
                    </div>
                </div>
            </section>

            {{-- ═══ 3. PÉRIMÈTRE ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-cyan-500/15 border border-cyan-500/25">
                        <span class="text-lg">🎯</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Cibler le périmètre</h2>
                        <p class="text-[11px] text-slate-500">Optionnel — matière, classe, filière…</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <select wire:model.live="subject_id"
                            class="h-11 rounded-xl bg-[#070b14] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-cyan-500/40 transition-all">
                            <option value="">Toutes les matières</option>
                            @foreach ($this->subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->code ?: $sub->name }}</option>
                            @endforeach
                        </select>

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
                    </div>
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
                            <p class="text-[11px] text-slate-500">Affectations et titre dynamique</p>
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
                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-rose-500/15 border border-rose-500/25 text-rose-300 text-sm font-semibold tabular-nums">
                            {{ __zero($this->allAssignmentsCounter) }}
                        </span>
                        <span class="text-sm text-slate-400">
                            affectation{{ $this->allAssignmentsCounter > 1 ? 's' : '' }}
                            trouvée{{ $this->allAssignmentsCounter > 1 ? 's' : '' }}
                        </span>
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
                            <p class="text-[11px] text-slate-500">Personnaliser l’entête de la liste</p>
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

                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach ($columns as $key => $column)
                            @php($order = array_search($key, $selectedColumns, true))
                            <div wire:key="col-{{ $key }}" x-data="{ checked: {{ $order !== false ? 'true' : 'false' }} }"
                                class="relative rounded-xl border p-3.5 transition-all duration-200 cursor-pointer"
                                :class="checked
                                    ?
                                    'border-rose-500/50 bg-rose-500/10 shadow-lg shadow-rose-900/20' :
                                    'border-white/[0.06] bg-[#070b14] hover:border-white/15'">
                                <label class="flex items-center justify-between gap-2 cursor-pointer">
                                    <span class="flex items-center gap-2 min-w-0">
                                        <template x-if="checked">
                                            <span
                                                class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-600 text-[10px] font-bold text-white">
                                                {{ $order !== false ? $order + 1 : '' }}
                                            </span>
                                        </template>
                                        <span
                                            class="text-xs font-medium text-slate-200 truncate">{{ $column['label'] }}</span>
                                    </span>
                                    <span class="relative flex shrink-0">
                                        <input type="checkbox" wire:click="toggleColumn('{{ $key }}')"
                                            x-on:click="checked = !checked"
                                            wire:key="input-{{ $key }}-{{ $order !== false ? 'on' : 'off' }}"
                                            @checked($order !== false) class="peer sr-only">
                                        <span
                                            class="h-5 w-9 rounded-full bg-slate-700 transition-colors duration-200 peer-checked:bg-rose-600"></span>
                                        <span
                                            class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-slate-400 shadow transition-transform duration-200 peer-checked:translate-x-4 peer-checked:bg-white"></span>
                                    </span>
                                </label>
                            </div>
                        @endforeach
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
                            <span class="text-rose-400 font-normal text-xs ml-1">(personnalisé)</span>
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
                                            class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-rose-300/80 whitespace-nowrap">
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

            {{-- ═══ CTA SUPER MODERNE ═══ --}}
            <div class="pt-4 pb-16">
                <button wire:click="initPrintProcess" wire:loading.attr="disabled" wire:target="initPrintProcess"
                    class="group relative w-full h-16 rounded-2xl overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.985] transition-transform duration-200">

                    <span class="absolute inset-0 bg-[#0a1220]"></span>
                    <span
                        class="absolute inset-0 bg-gradient-to-r from-rose-600/90 via-orange-600/80 to-amber-600/80 opacity-90 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <span
                        class="absolute -inset-1 bg-gradient-to-r from-rose-500 via-orange-500 to-amber-500 rounded-2xl blur-xl opacity-30 group-hover:opacity-60 transition-opacity duration-500 -z-10"></span>

                    <span class="absolute inset-0 overflow-hidden rounded-2xl">
                        <span
                            class="absolute top-0 left-0 h-full w-1/3 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-[400%] transition-transform duration-1000 ease-out"></span>
                    </span>

                    <span class="relative flex items-center justify-center gap-3 h-full px-6">
                        <span wire:loading.remove wire:target="initPrintProcess"
                            class="inline-flex items-center gap-3 flex-wrap justify-center">
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-full bg-white/15 border border-white/20 group-hover:scale-110 transition-transform duration-300">
                                <x-lucide-send class="w-5 h-5 text-white" />
                            </span>
                            <span class="flex flex-col items-start sm:items-center text-left sm:text-center">
                                <span class="text-white font-bold text-sm sm:text-base tracking-wide">
                                    Lancer la procédure d’impression
                                </span>
                                <span class="text-white/60 text-[11px] font-medium mt-0.5">
                                    {{ __zero($this->allAssignmentsCounter) }}
                                    affectation{{ $this->allAssignmentsCounter > 1 ? 's' : '' }}
                                    concernée{{ $this->allAssignmentsCounter > 1 ? 's' : '' }}
                                </span>
                            </span>
                        </span>

                        <span wire:loading wire:target="initPrintProcess" class="inline-flex items-center gap-3">
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-full bg-white/15 border border-white/20">
                                <x-lucide-refresh-ccw class="w-5 h-5 text-white animate-spin" />
                            </span>
                            <span class="text-white font-semibold text-sm tracking-wide">
                                Génération en cours…
                            </span>
                        </span>
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

