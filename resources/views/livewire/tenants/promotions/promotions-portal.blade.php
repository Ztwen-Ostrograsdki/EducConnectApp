<div class="w-full overflow-x-hidden bg-slate-950 min-h-screen">

    <div class="mx-auto w-full max-w-[1900px] px-4 sm:px-6 lg:px-8 py-6">

        {{-- ===================== HEADER ===================== --}}
        <header class="mb-8 border-b border-slate-800 pb-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="h-2 w-2 rounded-full bg-indigo-400"></span>
                        <span class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Gestion
                            académique</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        Dashboard Promotions
                    </h1>
                    <p class="mt-2 text-slate-400 max-w-2xl text-sm leading-relaxed">
                        Vue globale des promotions, performances académiques, statistiques des apprenants et gestion des
                        classes.
                    </p>
                </div>

                <a wire:navigate href="{{ route('tenant.promotion.create') }}"
                    class="inline-flex items-center gap-2 h-11 px-5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold transition-colors shrink-0">
                    <x-lucide-plus class="w-4 h-4" />
                    Nouvelle promotion
                </a>
            </div>

            {{-- KPI strip --}}
            <div class="mt-6 grid grid-cols-3 gap-px bg-slate-800 border border-slate-800">
                <div class="bg-slate-900 px-5 py-4">
                    <p class="text-2xl sm:text-3xl font-black text-white">{{ __zero($this->promotions->total()) }}</p>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 mt-1">Promotions</p>
                </div>
                <div class="bg-slate-900 px-5 py-4">
                    <p class="text-2xl sm:text-3xl font-black text-white">{{ __zero($this->classes) }}</p>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 mt-1">Classes</p>
                </div>
                <div class="bg-slate-900 px-5 py-4">
                    <p class="text-2xl sm:text-3xl font-black text-white">{{ __zero($this->students) }}</p>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 mt-1">Apprenants</p>
                </div>
            </div>
        </header>

        {{-- ===================== FILTERS ===================== --}}
        <section class="mb-6">
            <div class="flex flex-col xl:flex-row xl:items-center gap-3">
                <div class="relative flex-1 min-w-0">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Rechercher une promotion…"
                        class="w-full h-11 bg-slate-900 border border-slate-700 pl-10 pr-10 text-sm text-slate-200 placeholder:text-slate-600 outline-none focus:border-indigo-500 transition-colors" />
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">🔍</span>
                    <div wire:loading wire:target="search" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </div>
                </div>

                <select wire:model.live="filiar_id"
                    class="h-11 bg-slate-900 border border-slate-700 px-3 text-sm text-slate-300 outline-none focus:border-indigo-500 min-w-[180px]">
                    <option value="">Toutes les filières</option>
                    @foreach ($this->filiars as $filiar)
                        <option value="{{ $filiar->id }}">{{ $filiar->name }} ({{ $filiar->code }})</option>
                    @endforeach
                </select>

                <select wire:model.live="serial_id"
                    class="h-11 bg-slate-900 border border-slate-700 px-3 text-sm text-slate-300 outline-none focus:border-indigo-500 min-w-[160px]">
                    <option value="">Toutes les séries</option>
                    @foreach ($this->serials as $serial)
                        <option value="{{ $serial->id }}">{{ $serial->name }} ({{ $serial->code }})</option>
                    @endforeach
                </select>

                <button wire:click="resetFilters"
                    class="h-11 px-4 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-sm text-slate-300 transition-colors">
                    Réinitialiser
                </button>
            </div>
        </section>

        {{-- ===================== TABLE ===================== --}}
        <section class="mb-16 relative">
            {{-- Loading overlay --}}
            <div wire:loading wire:target="serial_id,filiar_id,previousPage,nextPage,resetFilters,gotoPage"
                class="absolute inset-0 z-20 flex items-center justify-center bg-slate-950/60 backdrop-blur-[2px]">
                <div class="flex items-center gap-3 text-slate-300">
                    <svg class="animate-spin w-7 h-7 text-indigo-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>
                    <span class="font-mono text-sm">Chargement…</span>
                </div>
            </div>

            @if (count($this->promotions))
                <div class=" overflow-x-auto">
                    <table class="w-full text-sm z-table-border">
                        <thead>
                            <tr class="bg-slate-900 border-b border-slate-800 text-left">
                                <th
                                    class="px-4 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 w-14">
                                    N°</th>
                                <th class="px-4 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Promotion</th>
                                <th
                                    class="px-4 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">
                                    Classes</th>
                                <th
                                    class="px-4 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">
                                    Apprenants</th>
                                <th
                                    class="px-4 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">
                                    Enseignants</th>
                                <th class="px-4 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Meilleur élève</th>
                                <th class="px-4 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Plus faible</th>
                                <th
                                    class="px-4 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">
                                    Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800/80">
                            @foreach ($this->promotions as $promo)
                                @php
                                    $details = app(
                                        \App\Services\PromotionsServices\PromotionDetailsCacheService::class,
                                    )->get($promo->id);
                                @endphp
                                <tr class="hover:bg-slate-900/80 transition-colors group">
                                    <td class="px-4 py-4 text-slate-500 font-mono text-xs">
                                        {{ __zero($this->promotions->firstItem() + $loop->iteration - 1) }}
                                    </td>

                                    <td class="px-4 py-4">
                                        <a wire:navigate
                                            href="{{ route('tenant.promotion.profil', ['promotion_slug' => $promo->slug]) }}"
                                            class="block group/link">
                                            <span
                                                class="font-semibold text-white group-hover/link:text-amber-400 transition-colors">
                                                {{ $promo->name }} {{ $promo->specialityModel()?->code }}
                                            </span>
                                            <span class="block mt-0.5 text-xs font-mono text-slate-500 uppercase">
                                                @if ($promo->code)
                                                    {{ $promo->code }}
                                                @else
                                                    {{ $promo->name }}-{{ $promo->specialityModel()?->code }}
                                                @endif
                                            </span>
                                        </a>
                                    </td>

                                    <td class="px-4 py-4 text-center font-semibold text-slate-200">
                                        {{ __zero($details['classes_count']) }}
                                    </td>
                                    <td class="px-4 py-4 text-center font-semibold text-indigo-400">
                                        {{ __zero($details['students_count']) }}
                                    </td>
                                    <td class="px-4 py-4 text-center font-semibold text-indigo-400">
                                        {{ __zero($details['teachers_count']) }}
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="text-xs text-slate-500 italic">En cours…</span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="text-xs text-slate-500 italic">En cours…</span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            <a wire:navigate
                                                href="{{ route('tenant.promotion.edit', ['promotion_slug' => $promo->slug]) }}"
                                                class="h-8 px-2.5 inline-flex items-center gap-1.5 bg-slate-800 hover:bg-blue-600 border border-slate-700 hover:border-blue-500 text-slate-300 hover:text-white text-xs font-medium transition-colors">
                                                <x-lucide-pen class="w-3.5 h-3.5" />
                                                Éditer
                                            </a>

                                            <button type="button"
                                                title="{{ $promo->is_active ? 'Fermer' : 'Activer' }} cette promotion"
                                                wire:click="{{ $promo->is_active ? 'closePromotion(' . $promo->id . ')' : 'activatePromotion(' . $promo->id . ')' }}"
                                                wire:loading.attr="disabled"
                                                wire:target="activatePromotion, closePromotion"
                                                class="h-8 px-2.5 inline-flex items-center gap-1.5 text-xs font-medium transition-colors disabled:opacity-50
                                                           {{ $promo->is_active
                                                               ? 'bg-orange-500/15 hover:bg-orange-500/30 text-orange-300 border border-orange-500/30'
                                                               : 'bg-lime-500/15 hover:bg-lime-500/30 text-lime-300 border border-lime-500/30' }}">
                                                <span wire:loading.remove
                                                    wire:target="activatePromotion, closePromotion"
                                                    class="inline-flex items-center gap-1.5">
                                                    @if ($promo->is_active)
                                                        <x-lucide-lock class="w-3.5 h-3.5" />
                                                        Fermer
                                                    @else
                                                        <x-lucide-unlock class="w-3.5 h-3.5" />
                                                        Activer
                                                    @endif
                                                </span>
                                                <span wire:loading wire:target="activatePromotion, closePromotion">
                                                    <svg class="animate-spin w-3.5 h-3.5" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4" />
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8v8z" />
                                                    </svg>
                                                </span>
                                            </button>

                                            <button type="button"
                                                title="{{ $promo->deleted_at ? 'Restaurer' : 'Mettre en corbeille' }}"
                                                wire:click="{{ $promo->deleted_at ? 'restorePromotion(' . $promo->id . ')' : 'deletePromotion(' . $promo->id . ')' }}"
                                                wire:loading.attr="disabled"
                                                wire:target="deletePromotion, restorePromotion"
                                                class="h-8 px-2.5 inline-flex items-center gap-1.5 text-xs font-medium transition-colors disabled:opacity-50
                                                           {{ $promo->deleted_at
                                                               ? 'bg-emerald-500/15 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30'
                                                               : 'bg-rose-500/15 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30' }}">
                                                <span wire:loading.remove
                                                    wire:target="deletePromotion, restorePromotion"
                                                    class="inline-flex items-center gap-1.5">
                                                    @if ($promo->deleted_at)
                                                        <x-lucide-refresh-ccw class="w-3.5 h-3.5" />
                                                        Restaurer
                                                    @else
                                                        <x-lucide-trash class="w-3.5 h-3.5" />
                                                        Corbeille
                                                    @endif
                                                </span>
                                                <span wire:loading wire:target="deletePromotion, restorePromotion">
                                                    <svg class="animate-spin w-3.5 h-3.5" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4" />
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8v8z" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($this->promotions->hasPages())
                    <div
                        class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border border-slate-800 bg-slate-900 px-5 py-4">
                        <p class="text-xs text-slate-500">
                            Affichage
                            <span class="text-slate-300 font-medium">{{ $this->promotions->firstItem() }}</span>
                            –
                            <span class="text-slate-300 font-medium">{{ $this->promotions->lastItem() }}</span>
                            sur
                            <span class="text-slate-300 font-medium">{{ $this->promotions->total() }}</span>
                            promotions
                        </p>

                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$this->promotions->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-9 px-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs text-slate-300 transition-colors disabled:opacity-50">
                                    ← Précédent
                                </button>
                            @endif

                            @foreach ($this->promotions->getUrlRange(1, $this->promotions->lastPage()) as $page => $url)
                                <button @disabled($page === $this->promotions->currentPage()) wire:click="gotoPage({{ $page }})"
                                    class="h-9 min-w-[36px] px-2 text-xs font-medium transition-colors
                                               {{ $page === $this->promotions->currentPage()
                                                   ? 'bg-indigo-600 text-white'
                                                   : 'bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300' }}">
                                    {{ $page }}
                                </button>
                            @endforeach

                            @if ($this->promotions->hasMorePages())
                                <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                    class="h-9 px-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs text-slate-300 transition-colors disabled:opacity-50">
                                    Suivant →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="border border-slate-800 bg-slate-900 px-6 py-16 text-center">
                    <p class="text-slate-500 text-sm mb-4">Aucune promotion trouvée.</p>
                    @if ($search || $filiar_id || $serial_id)
                        <button wire:click="resetFilters"
                            class="h-9 px-4 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-sm text-slate-300 transition-colors">
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            @endif
        </section>

    </div>
</div>

