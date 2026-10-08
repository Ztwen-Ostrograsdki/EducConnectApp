<div class="min-h-screen bg-[#070a12] text-slate-100">

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-10">

        {{-- ========== HERO ========== --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/[0.06] bg-[#0c101c]">

            {{-- Ligne décorative en haut --}}
            <div
                class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent">
            </div>

            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">

                    {{-- Titre --}}
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400/80 mb-2">
                            Gestion académique
                        </p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                            Dashboard Promotions
                        </h1>
                        <p class="mt-1.5 text-sm text-slate-500 max-w-md">
                            Performances, effectifs et gestion des promotions
                        </p>
                    </div>

                    {{-- CTA --}}
                    <a wire:navigate href="{{ route('tenant.promotion.create') }}"
                        class="inline-flex items-center gap-2 h-11 px-5 rounded-xl
                      bg-indigo-500 hover:bg-indigo-400 text-white text-sm font-medium
                      shadow-lg shadow-indigo-500/20 transition-all duration-200 shrink-0">
                        <x-lucide-plus class="w-4 h-4" />
                        Nouvelle promotion
                    </a>
                </div>

                {{-- KPI en ligne fine --}}
                <div class="mt-6 pt-6 border-t border-white/[0.05]">
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4">

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-500/15 flex items-center justify-center">
                                <x-lucide-layers class="w-4 h-4 text-indigo-400" />
                            </div>
                            <div>
                                <p class="text-xl font-bold text-white leading-none">
                                    {{ __zero($this->promotions->total()) }}
                                </p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Promotions</p>
                            </div>
                        </div>

                        <div class="w-px h-8 bg-white/[0.06] hidden sm:block"></div>

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-500/15 flex items-center justify-center">
                                <x-lucide-school class="w-4 h-4 text-amber-400" />
                            </div>
                            <div>
                                <p class="text-xl font-bold text-white leading-none">
                                    {{ __zero($this->classes) }}
                                </p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Classes</p>
                            </div>
                        </div>

                        <div class="w-px h-8 bg-white/[0.06] hidden sm:block"></div>

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-500/15 flex items-center justify-center">
                                <x-lucide-users class="w-4 h-4 text-emerald-400" />
                            </div>
                            <div>
                                <p class="text-xl font-bold text-white leading-none">
                                    {{ __zero($this->students) }}
                                </p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Apprenants</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== FILTRES ========== --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher une promotion…"
                    class="w-full h-11 rounded-xl bg-white/[0.03] border border-white/[0.08]
                              pl-10 pr-10 text-sm text-white placeholder:text-slate-600
                              outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                              transition-all" />
                <div wire:loading wire:target="search" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                    <x-lucide-loader-2 class="w-4 h-4 text-indigo-400 animate-spin" />
                </div>
            </div>

            <select wire:model.live="filiar_id"
                class="h-11 rounded-xl bg-white/[0.03] border border-white/[0.08] px-3
                           text-sm text-slate-300 outline-none focus:border-indigo-500/50
                           min-w-[180px] transition">
                <option class="bg-slate-950" value="">Toutes les filières</option>
                @foreach ($this->filiars as $filiar)
                    <option class="bg-slate-950" value="{{ $filiar->id }}">{{ $filiar->name }} ({{ $filiar->code }})
                    </option>
                @endforeach
            </select>

            <select wire:model.live="serial_id"
                class="h-11 rounded-xl bg-white/[0.03] border border-white/[0.08] px-3
                           text-sm text-slate-300 outline-none focus:border-indigo-500/50
                           min-w-[160px] transition">
                <option class="bg-slate-950" value="">Toutes les séries</option>
                @foreach ($this->serials as $serial)
                    <option class="bg-slate-950" value="{{ $serial->id }}">{{ $serial->name }} ({{ $serial->code }})
                    </option>
                @endforeach
            </select>

            <button wire:click="resetFilters"
                class="h-11 px-4 rounded-xl text-sm font-medium
                           bg-white/[0.04] border border-white/[0.08] text-slate-400
                           hover:bg-white/[0.08] hover:text-white transition-all shrink-0">
                Réinitialiser
            </button>
        </div>

        {{-- ========== LISTE ========== --}}
        <section class="relative">
            {{-- Loading --}}
            <div wire:loading wire:target="serial_id,filiar_id,previousPage,nextPage,resetFilters,gotoPage"
                class="absolute inset-0 z-20 flex items-center justify-center bg-[#070a12]/70 backdrop-blur-sm rounded-2xl">
                <div class="flex items-center gap-3 text-slate-400">
                    <x-lucide-loader-2 class="w-6 h-6 text-indigo-400 animate-spin" />
                    <span class="text-sm font-medium">Chargement…</span>
                </div>
            </div>

            @if (count($this->promotions))
                <div class="space-y-3">
                    @foreach ($this->promotions as $promo)
                        @php
                            $details = app(\App\Services\PromotionsServices\PromotionDetailsCacheService::class)->get(
                                $promo->id,
                            );
                        @endphp

                        <article
                            class="group relative rounded-2xl border border-white/[0.06] bg-white/[0.02]
                                        hover:border-indigo-500/25 hover:bg-white/[0.035]
                                        transition-all duration-300 overflow-hidden"
                            wire:key="promo-{{ $promo->id }}">

                            {{-- Accent --}}
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1
                                        bg-gradient-to-b from-indigo-500 via-violet-500 to-purple-600
                                        scale-y-0 group-hover:scale-y-100
                                        transition-transform duration-400 origin-center">
                            </div>

                            <div class="p-5 sm:p-6">
                                <div class="flex flex-col lg:flex-row lg:items-center gap-5">

                                    {{-- Identité --}}
                                    <div class="flex items-start gap-3 min-w-0 lg:w-[260px] shrink-0">
                                        <span class="text-xs font-mono text-slate-600 mt-1.5 shrink-0 w-6 text-right">
                                            {{ __zero($this->promotions->firstItem() + $loop->iteration - 1) }}
                                        </span>
                                        <div class="min-w-0">
                                            <a wire:navigate
                                                href="{{ route('tenant.promotion.profil', ['promotion_slug' => $promo->slug]) }}"
                                                class="block group/link">
                                                <h3
                                                    class="font-semibold text-white text-base leading-snug
                                                           group-hover/link:text-indigo-300 transition-colors">
                                                    {{ $promo->name }}
                                                    <span class="text-indigo-400/80 font-normal">
                                                        {{ $promo->specialityModel()?->code }}
                                                    </span>
                                                </h3>
                                                <p
                                                    class="mt-0.5 text-xs font-mono text-slate-500 uppercase tracking-wide">
                                                    @if ($promo->code)
                                                        {{ $promo->code }}
                                                    @else
                                                        {{ $promo->name }}-{{ $promo->specialityModel()?->code }}
                                                    @endif
                                                </p>
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Stats --}}
                                    <div class="flex items-center gap-2.5 flex-wrap lg:flex-1">
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl
                                                    bg-amber-500/10 border border-amber-500/20">
                                            <x-lucide-school class="w-3.5 h-3.5 text-amber-400" />
                                            <div>
                                                <p class="text-sm font-bold text-amber-300 leading-none">
                                                    {{ __zero($details['classes_count']) }}
                                                </p>
                                                <p class="text-[10px] text-amber-400/60 mt-0.5">classes</p>
                                            </div>
                                        </div>

                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl
                                                    bg-indigo-500/10 border border-indigo-500/20">
                                            <x-lucide-users class="w-3.5 h-3.5 text-indigo-400" />
                                            <div>
                                                <p class="text-sm font-bold text-indigo-300 leading-none">
                                                    {{ __zero($details['students_count']) }}
                                                </p>
                                                <p class="text-[10px] text-indigo-400/60 mt-0.5">élèves</p>
                                            </div>
                                        </div>

                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl
                                                    bg-violet-500/10 border border-violet-500/20">
                                            <x-lucide-graduation-cap class="w-3.5 h-3.5 text-violet-400" />
                                            <div>
                                                <p class="text-sm font-bold text-violet-300 leading-none">
                                                    {{ __zero($details['teachers_count']) }}
                                                </p>
                                                <p class="text-[10px] text-violet-400/60 mt-0.5">profs</p>
                                            </div>
                                        </div>

                                        {{-- Placeholders perf --}}
                                        <div
                                            class="hidden sm:flex items-center gap-3 ml-2 text-xs text-slate-600 italic">
                                            <span>Meilleur · en cours…</span>
                                            <span>Faible · en cours…</span>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-1.5 flex-wrap lg:justify-end shrink-0">
                                        <a wire:navigate
                                            href="{{ route('tenant.promotion.edit', ['promotion_slug' => $promo->slug]) }}"
                                            class="inline-flex items-center gap-1.5 h-8 px-2.5 rounded-lg text-xs font-medium
                                                  bg-white/[0.04] text-slate-400 border border-white/[0.08]
                                                  hover:bg-sky-500 hover:text-white hover:border-sky-500
                                                  transition-all duration-200">
                                            <x-lucide-pen class="w-3.5 h-3.5" />
                                            Éditer
                                        </a>

                                        <button type="button"
                                            title="{{ $promo->is_active ? 'Fermer' : 'Activer' }} cette promotion"
                                            wire:click="{{ $promo->is_active ? 'closePromotion(' . $promo->id . ')' : 'activatePromotion(' . $promo->id . ')' }}"
                                            wire:loading.attr="disabled" wire:target="activatePromotion, closePromotion"
                                            class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-xs font-medium
                                                       transition-all duration-200 disabled:opacity-50
                                                       {{ $promo->is_active
                                                           ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500 hover:text-white hover:border-amber-500'
                                                           : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500' }}">
                                            <span wire:loading.remove wire:target="activatePromotion, closePromotion">
                                                @if ($promo->is_active)
                                                    <x-lucide-power class="w-3.5 h-3.5" />
                                                @else
                                                    <x-lucide-power class="w-3.5 h-3.5" />
                                                @endif
                                            </span>
                                            <span wire:loading wire:target="activatePromotion, closePromotion">
                                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        <button type="button"
                                            title="{{ $promo->deleted_at ? 'Restaurer' : 'Mettre en corbeille' }}"
                                            wire:click="{{ $promo->deleted_at ? 'restorePromotion(' . $promo->id . ')' : 'deletePromotion(' . $promo->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="deletePromotion, restorePromotion"
                                            class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-xs font-medium
                                                       transition-all duration-200 disabled:opacity-50
                                                       {{ $promo->deleted_at
                                                           ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                                                           : 'bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white hover:border-rose-500' }}">
                                            <span wire:loading.remove wire:target="deletePromotion, restorePromotion">
                                                @if ($promo->deleted_at)
                                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5" />
                                                @else
                                                    <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                @endif
                                            </span>
                                            <span wire:loading wire:target="deletePromotion, restorePromotion">
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
                @if ($this->promotions->hasPages())
                    <div class="mt-8 rounded-2xl border border-white/[0.06] bg-white/[0.02] px-5 py-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <p class="text-sm text-slate-500">
                                {{ $this->promotions->firstItem() }}–{{ $this->promotions->lastItem() }}
                                sur <span class="text-slate-300 font-medium">{{ $this->promotions->total() }}</span>
                            </p>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @if (!$this->promotions->onFirstPage())
                                    <button wire:click="previousPage" wire:loading.attr="disabled"
                                        wire:target="previousPage"
                                        class="h-9 px-3.5 rounded-lg text-sm text-slate-300
                                                   bg-white/[0.04] border border-white/[0.06]
                                                   hover:bg-white/[0.08] hover:text-white
                                                   transition-all disabled:opacity-50">
                                        Précédent
                                    </button>
                                @endif

                                @foreach ($this->promotions->getUrlRange(1, $this->promotions->lastPage()) as $page => $url)
                                    <button wire:click="gotoPage({{ $page }})" @disabled($page === $this->promotions->currentPage())
                                        class="h-9 w-9 rounded-lg text-sm font-medium transition-all
                                                   {{ $page === $this->promotions->currentPage()
                                                       ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/25'
                                                       : 'bg-white/[0.04] text-slate-400 border border-white/[0.06] hover:bg-white/[0.08] hover:text-white' }}">
                                        {{ $page }}
                                    </button>
                                @endforeach

                                @if ($this->promotions->hasMorePages())
                                    <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
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
                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] py-16 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-500/10 mb-4">
                        <x-lucide-layers class="w-7 h-7 text-indigo-400" />
                    </div>
                    <p class="text-slate-400 text-sm">Aucune promotion trouvée</p>
                    @if ($search || $filiar_id || $serial_id)
                        <button wire:click="resetFilters"
                            class="mt-4 px-4 py-2 rounded-xl text-sm
                                       bg-white/[0.04] border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.08] hover:text-white transition-all">
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            @endif
        </section>

    </div>
</div>

