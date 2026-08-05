<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="mx-auto max-w-[1100px] px-4 sm:px-6 py-10 relative">

        {{-- Loading --}}
        <div wire:loading wire:target="resetFilters, initPrintProcess"
            class="fixed inset-0 z-[200] flex items-center justify-center bg-[#070b14]/80 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 rounded-full border-2 border-indigo-500/30 border-t-indigo-400 animate-spin"></div>
                <span class="text-sm font-mono text-slate-400 tracking-wide">Chargement…</span>
            </div>
        </div>

        {{-- Header --}}
        <header class="mb-10 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[11px] font-semibold uppercase tracking-[0.2em] mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                Impression des bulletins
            </div>
            <h1 class="text-lg sm:text-2xl font-bold tracking-tight text-white">
                Système de génération des bulletins en masse
            </h1>
            <p class="mt-3 text-slate-500 text-sm max-w-lg mx-auto">
                Sélectionnez la période, le périmètre et le statut pour générer les bulletins scolaires.
            </p>
        </header>

        <div class="flex flex-wrap justify-center gap-2 mb-8">
            <a wire:navigate href="{{ route('tenant.classes.portal') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/15 text-violet-300 border border-violet-500/20 hover:bg-violet-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-school class="w-3.5 h-3.5" />
                Portail des classes
            </a>
            <a href="{{ route('tenant.bulletins.print.preview') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                <x-lucide-eye class="w-3.5 h-3.5" />
                Aperçu du document
            </a>
            <a wire:navigate href="{{ route('tenant.bulletins.docs') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-amber-500/15 text-amber-300 border border-amber-500/20 hover:bg-amber-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-file class="w-3.5 h-3.5" />
                Fichiers disponibles
            </a>
        </div>

        <div class="space-y-6">

            {{-- ═══ 1. PÉRIODE ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-500/15 border border-indigo-500/25">
                        <span class="text-lg">📅</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Période</h2>
                        <p class="text-[11px] text-slate-500">{{ $this->activeYear?->periodLabel() }} à imprimer</p>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <select wire:model.live="period"
                        class="w-full sm:w-1/2 h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                        <option disabled value="">Sélectionner le {{ $this->activeYear?->periodLabel() }}</option>
                        @foreach ($this->periods_types as $p)
                            <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                        @endforeach
                    </select>

                    @if ($this->isLastPeriod)
                        <div
                            class="flex items-start gap-2.5 rounded-xl bg-emerald-500/5 border border-emerald-500/20 px-4 py-3">
                            <x-lucide-check-circle class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" />
                            <p class="text-[11px] text-emerald-300/90 leading-relaxed">
                                Dernière période : les bulletins incluront le <span
                                    class="font-medium text-emerald-200">récapitulatif annuel</span> et la <span
                                    class="font-medium text-emerald-200">décision du jury</span>.
                            </p>
                        </div>
                    @endif
                </div>
            </section>

            {{-- ═══ 2. PÉRIMÈTRE ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-cyan-500/15 border border-cyan-500/25">
                        <span class="text-lg">🎯</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Cibler le périmètre</h2>
                        <p class="text-[11px] text-slate-500">Classe, filière, série, promotion, niveau…</p>
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

            {{-- ═══ 3. STATUT ═══ --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/25">
                        <span class="text-lg">📊</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Statut des apprenants</h2>
                        <p class="text-[11px] text-slate-500">Actifs, abandon, etc.</p>
                    </div>
                </div>

                <div class="p-6">
                    <select wire:model.live="leavesStatus"
                        class="w-full sm:w-1/2 h-12 rounded-xl bg-[#070b14] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30 transition-all">
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
                            <p class="text-[11px] text-slate-500">Volume et titre de l’impression</p>
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
                            class="inline-flex items-center px-3 py-1.5 rounded-full gap-2 font-mono bg-indigo-500/15 border border-indigo-500/25 text-indigo-300 text-sm font-semibold tabular-nums">
                            <span>{{ __zero($this->allStudentsCounter) }}</span>
                            <span class="text-sm text-slate-400">
                                bulletin{{ $this->allStudentsCounter > 1 ? 's' : '' }} à générer
                            </span>
                        </span>

                    </div>

                    <div wire:key="doc-title-{{ md5($this->currentDocTitle) }}"
                        class="rounded-xl bg-gradient-to-r from-orange-500/10 via-orange-500/5 to-transparent border border-orange-500/20 px-5 py-4">
                        <p class="text-[10px] uppercase tracking-[0.15em] text-orange-400/70 font-semibold mb-1.5">
                            Titre du document
                        </p>
                        <p class="text-base font-semibold text-orange-200 leading-snug">
                            {{ $this->currentDocTitle }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- ═══ CTA ═══ --}}
            <div class="pt-4 pb-16">
                <div wire:click="initPrintProcess" wire:loading.attr="disabled" wire:target="initPrintProcess"
                    class="group relative w-full py-3 rounded-2xl overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.985] transition-transform duration-200 cursor-pointer">

                    <span class="absolute inset-0 bg-[#0a1220]"></span>
                    <span
                        class="absolute inset-0 bg-gradient-to-r from-indigo-600/90 via-violet-600/80 to-sky-600/80 opacity-90 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <span
                        class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-sky-500 rounded-2xl blur-xl opacity-30 group-hover:opacity-60 transition-opacity duration-500 -z-10"></span>

                    <span class="absolute inset-0 overflow-hidden rounded-2xl">
                        <span
                            class="absolute top-0 left-0 h-full w-1/3 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-[400%] transition-transform duration-1000 ease-out"></span>
                    </span>

                    <span class="relative flex items-center justify-center gap-3 h-full px-6">
                        <span wire:loading.remove wire:target="initPrintProcess"
                            class="inline-flex items-center gap-3 flex-wrap justify-center">

                            <span class="flex flex-col items-start sm:items-center text-left sm:text-center">
                                <span class="text-white font-bold text-sm sm:text-base tracking-wide">
                                    <span class="inline-flex items-center gap-x-2">
                                        <x-lucide-arrow-left class="w-5 h-5 text-white group-hover:-translate-x-2" />
                                        <span>
                                            Lancer la procédure d’impression
                                        </span>
                                        <x-lucide-arrow-right class="w-5 h-5 text-white group-hover:translate-x-2" />
                                    </span>
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
                </div>
            </div>

        </div>
    </div>
</div>

