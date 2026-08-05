<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="mx-auto max-w-[1100px] px-4 sm:px-6 py-10 relative">

        {{-- Loading --}}
        <div wire:loading wire:target="resetFilters, initPrintProcess"
            class="fixed inset-0 z-[200] flex items-center justify-center bg-[#070b14]/80 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 rounded-full border-2 border-violet-500/30 border-t-violet-400 animate-spin"></div>
                <span class="text-sm font-mono text-slate-400 tracking-wide">Chargement…</span>
            </div>
        </div>

        {{-- Header --}}
        <header class="mb-10 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-300 text-[11px] font-semibold uppercase tracking-[0.2em] mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                Statistiques par intervalles
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                Configuration du document
            </h1>
            <p class="mt-3 text-slate-500 text-sm max-w-lg mx-auto">
                Définissez la période, les seuils de moyenne et le périmètre pour générer les statistiques.
            </p>
        </header>

        <div class="flex flex-wrap justify-center gap-2 mb-8">
            <a wire:navigate href="{{ route('tenant.classes.portal') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/15 text-violet-300 border border-violet-500/20 hover:bg-violet-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-school class="w-3.5 h-3.5" />
                Portail des classes
            </a>
            <a href="{{ route('tenant.stats.print.preview') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                <x-lucide-eye class="w-3.5 h-3.5" />
                Aperçu du document
            </a>
            <a wire:navigate href="{{ route('tenant.stats.docs') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-amber-500/15 text-amber-300 border border-amber-500/20 hover:bg-amber-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-file class="w-3.5 h-3.5" />
                Fichiers disponibles
            </a>
        </div>

        <div class="space-y-6">

            {{-- ═══ 1. PÉRIODE + REGROUPEMENT ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-violet-500/15 border border-violet-500/25">
                        <span class="text-lg">📅</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Période et regroupement</h2>
                        <p class="text-[11px] text-slate-500">Période et mode de regroupement des stats</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                Période <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="period"
                                class="w-full h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                                <option value="">Sélectionner le {{ $this->activeYear?->periodLabel() }}</option>
                                @foreach ($this->periods_types as $p)
                                    <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                Statistiques regroupées
                            </label>
                            <select wire:model.live="groupedBy"
                                class="w-full h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                                @foreach ($groupedByOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ═══ 2. INTERVALLES ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/25">
                        <span class="text-lg">📊</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Seuils des intervalles</h2>
                        <p class="text-[11px] text-slate-500">Moyennes entre 0 et 20, séparées par des virgules</p>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                            Seuils
                        </label>
                        <input type="text" wire:model.live.debounce.500ms="breakpointsInput"
                            placeholder="7, 9, 10, 12, 14, 16"
                            class="w-full h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm font-mono text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30 transition-all">
                    </div>

                    @if (count($this->intervalLabelsPreview))
                        <div wire:key="intervals-preview-{{ implode('-', $this->parsedBreakpoints) }}"
                            class="flex flex-wrap gap-2">
                            @foreach ($this->intervalLabelsPreview as $label)
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs font-mono">
                                    {{ $label }}
                                </span>
                            @endforeach
                        </div>
                    @endif
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
                        <h2 class="text-sm font-semibold text-white">Restreindre le périmètre</h2>
                        <p class="text-[11px] text-slate-500">Optionnel — classe, filière, série, promotion</p>
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
                    </div>
                </div>
            </section>

            {{-- ═══ 4. DOCUMENT ═══ --}}
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
                            <p class="text-[11px] text-slate-500">Classes et titre dynamique</p>
                        </div>
                    </div>
                    <button wire:click="resetFilters"
                        class="h-9 px-4 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5" />
                        Réinitialiser
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-violet-500/15 border border-violet-500/25 text-violet-300 text-sm font-semibold tabular-nums">
                            {{ __zero($this->allClassesCounter) }}
                        </span>
                        <span class="text-sm text-slate-400">
                            classe{{ $this->allClassesCounter > 1 ? 's' : '' }}
                            trouvée{{ $this->allClassesCounter > 1 ? 's' : '' }}
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

            {{-- ═══ CTA ═══ --}}
            <div class="pt-4 pb-16">
                <button wire:click="initPrintProcess" wire:loading.attr="disabled" wire:target="initPrintProcess"
                    class="group relative w-full h-16 rounded-2xl overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.985] transition-transform duration-200">

                    <span class="absolute inset-0 bg-[#0a1220]"></span>
                    <span
                        class="absolute inset-0 bg-gradient-to-r from-violet-600/90 via-indigo-600/80 to-fuchsia-600/80 opacity-90 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <span
                        class="absolute -inset-1 bg-gradient-to-r from-violet-500 via-indigo-500 to-fuchsia-500 rounded-2xl blur-xl opacity-30 group-hover:opacity-60 transition-opacity duration-500 -z-10"></span>

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
                                    {{ __zero($this->allClassesCounter) }}
                                    classe{{ $this->allClassesCounter > 1 ? 's' : '' }}
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

