<div>

    <section class="mb-8">
        <div class="rounded-[28px] bg-slate-900 border border-slate-800 overflow-hidden shadow-xl shadow-black/20">

            {{-- ===================== HEADER ===================== --}}
            <div class="p-5 sm:p-6 border-b border-slate-800 bg-gradient-to-r from-slate-900 to-slate-950">
                <div class="flex flex-col gap-5">

                    {{-- Title --}}
                    <div>
                        <h2 class="text-xl sm:text-2xl font-semibold tracking-tight text-white">
                            Coefficients par Promotion
                            <span class="text-sky-400">· {{ $subject->name }}</span>
                        </h2>
                        <p class="mt-1.5 text-sm text-slate-400">
                            Gestion des coefficients de la matière selon les filières, séries et promotions
                            @if ($this->activeYear)
                                <span class="text-slate-500">· Année {{ $this->activeYear->slug }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- Filters --}}
                    <div class="items-center lg:grid lg:grid-cols-9 justify-end gap-4 w-full">

                        {{-- Recherche --}}
                        <div class="relative col-span-5 my-2">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Rechercher une promotion..."
                                class="h-11 w-full pl-10 pr-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm text-slate-200 placeholder-slate-500 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/30 focus:outline-none transition">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <div class="col-span-4 justify-end flex gap-3 my-2">
                            @if (!$serial_id)
                                <select wire:model.live="filiar_id"
                                    class="h-11 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:border-indigo-500 focus:outline-none transition appearance-none min-w-[160px]">
                                    <option value="">Toutes filières</option>
                                    @foreach ($this->filiars as $f)
                                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                                    @endforeach
                                </select>
                            @endif

                            @if (!$filiar_id)
                                <select wire:model.live="serial_id"
                                    class="h-11 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:border-indigo-500 focus:outline-none transition appearance-none min-w-[160px]">
                                    <option value="">Toutes séries</option>
                                    @foreach ($this->serials as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            @endif

                            @if ($search || $filiar_id || $serial_id)
                                <button wire:click="resetFilters"
                                    class="h-11 px-4 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm transition flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reset
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== TABLE ===================== --}}
            <div class="relative overflow-x-auto">

                {{-- Loading overlay --}}
                <div wire:loading.flex
                    wire:target="search,filiar_id,serial_id,resetFilters,previousPage,nextPage,gotoPage"
                    class="absolute inset-0 z-20 items-center justify-center bg-slate-900/60 backdrop-blur-sm">
                    <div class="flex flex-col items-center gap-3 text-slate-300">
                        <svg class="animate-spin w-9 h-9 text-sky-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        <span class="text-sm font-medium tracking-wide">Chargement...</span>
                    </div>
                </div>

                <table class="w-full z-table-border">
                    <thead class="bg-slate-950/80 border-b border-slate-800">
                        <tr>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Promotion
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Filière / Série
                            </th>
                            <th
                                class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Coefficient
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-800/80">
                        @forelse ($this->promotionsDataGrouped as $promotion => $items)

                            {{-- En-tête de groupe Promotion --}}
                            <tr class="bg-slate-950/40">
                                <td colspan="4" class="px-6 py-3">
                                    <div class="flex items-center justify-center text-center gap-3 uppercase">

                                        <div>
                                            <span class="font-semibold text-white">Les promotions
                                                {{ $promotion }}</span>
                                            <span class="ml-2 text-xs text-slate-500">
                                                <span
                                                    class="inline-flex items-center justify-center p-2 rounded-xl bg-sky-500/15 text-sky-400 text-sm font-bold lowercase">
                                                    {{ $items->count() }}
                                                    promotion{{ $items->count() > 1 ? 's' : '' }}
                                                </span>

                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            {{-- Lignes de la promotion --}}
                            @foreach ($items as $coef)
                                <tr class="hover:bg-slate-800/30 transition-colors duration-150 group text-center">

                                    {{-- Promotion (répétée discrètement) --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-400">
                                            {{ $promotion }}

                                            <span class="font-mono uppercase">
                                                @if ($coef->filiar)
                                                    <span class="text-indigo-400/70">
                                                        {{ $coef->filiar->code }}</span>
                                                @elseif ($coef->serial)
                                                    <span class="text-indigo-400/70">
                                                        {{ $coef->serial->code }}
                                                    </span>
                                                @endif
                                            </span>
                                        </div>

                                    </td>

                                    {{-- Filière OU Série --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap justify-center gap-2 text-center">
                                            @if ($coef->filiar)
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-300 text-xs font-medium border border-indigo-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                                    {{ $coef->filiar->name }}
                                                    @if ($coef->filiar->code)
                                                        <span class="text-indigo-400/70">·
                                                            {{ $coef->filiar->code }}</span>
                                                    @endif
                                                </span>
                                            @elseif ($coef->serial)
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-500/10 text-sky-300 text-xs font-medium border border-sky-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                                    {{ $coef->serial->name }}
                                                    @if ($coef->serial->code)
                                                        <span class="text-sky-400/70">·
                                                            {{ $coef->serial->code }}</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-xs text-slate-500 italic">Non défini</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Coefficient --}}
                                    <td class="px-4 py-4 text-center">
                                        <div
                                            class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 group-hover:bg-emerald-500/15 transition">
                                            <span class="text-lg font-bold text-emerald-400 tabular-nums">
                                                {{ $coef->coef }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4">
                                        <div
                                            class="flex justify-center gap-2 opacity-70 group-hover:opacity-100 transition">
                                            <a wire:navigate
                                                href="{{ route('tenant.subjects.coefs.manage', ['subject_slug' => $this->subject->slug, 'uuid' => $coef->uuid]) }}"
                                                class="p-2.5 rounded-2xl bg-indigo-500/20 text-indigo-400  hover:bg-indigo-500/60 hover:text-black transition-all text-sm flex items-center text-center">
                                                <span class="flex items-center justify-center gap-x-2">
                                                    <span class="flex items-center justify-center gap-x-2">
                                                        <x-lucide-edit class="w-4 h-4" />
                                                        <span> Modifier</span>
                                                    </span>
                                                </span>
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-slate-500">
                                        <svg class="w-12 h-12 opacity-40" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm">Aucun coefficient trouvé pour cette matière.</p>
                                        @if ($search || $filiar_id || $serial_id)
                                            <button wire:click="resetFilters"
                                                class="text-sky-400 text-sm hover:underline">
                                                Réinitialiser les filtres
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($this->promotionsData->hasPages())
                <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/50">
                    {{ $this->promotionsData->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== SECTION STATS (placeholder) ===================== --}}
    <section class="mb-6">
        <div class="rounded-[28px] bg-slate-900 border border-slate-800 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-white">Performances par Promotion</h2>
                    <p class="mt-1 text-sm text-slate-400">
                        Analyse statistique de la matière selon les promotions.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px]">
                    <thead class="bg-slate-950 border-b border-slate-800">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Promotion</th>
                            <th
                                class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Classes</th>
                            <th
                                class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Effectif</th>
                            <th
                                class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Moyenne</th>
                            <th
                                class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Taux Réussite</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Meilleur Élève</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach (['6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale'] as $promo)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4 font-medium text-white">{{ $promo }}</td>
                                <td class="px-4 py-4 text-center text-slate-300">—</td>
                                <td class="px-4 py-4 text-center text-slate-300">—</td>
                                <td class="px-4 py-4 text-center text-emerald-400 font-semibold">—</td>
                                <td class="px-4 py-4 text-center text-slate-300">—</td>
                                <td class="px-6 py-4 text-slate-400">—</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

