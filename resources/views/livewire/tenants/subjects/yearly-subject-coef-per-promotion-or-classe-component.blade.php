<div class="space-y-6">

    {{-- ========== COEFFICIENTS ========== --}}
    <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">

        {{-- Header + filtres --}}
        <div class="p-5 sm:p-6 border-b border-white/[0.05] space-y-4">
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-white tracking-tight">
                    Coefficients par promotion
                    <span class="text-sky-400">· {{ $subject->name }}</span>
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Coefficients selon filières, séries et promotions
                    @if ($this->activeYear)
                        <span class="text-slate-600">· {{ $this->activeYear->slug }}</span>
                    @endif
                </p>
            </div>

            <div class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1">
                    <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Rechercher une promotion…"
                        class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  pl-10 pr-4 text-sm text-white placeholder:text-slate-600
                                  focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/20
                                  outline-none transition-all" />
                </div>

                <div class="flex flex-wrap gap-2">
                    @if (!$serial_id)
                        <select wire:model.live="filiar_id"
                            class="h-10 px-3 rounded-xl border border-white/[0.08] bg-[#070a12]
                                       text-sm text-slate-300 focus:border-indigo-500/50 focus:outline-none
                                       transition min-w-[150px]">
                            <option value="">Toutes filières</option>
                            @foreach ($this->filiars as $f)
                                <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if (!$filiar_id)
                        <select wire:model.live="serial_id"
                            class="h-10 px-3 rounded-xl border border-white/[0.08] bg-[#070a12]
                                       text-sm text-slate-300 focus:border-indigo-500/50 focus:outline-none
                                       transition min-w-[150px]">
                            <option value="">Toutes séries</option>
                            @foreach ($this->serials as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if ($search || $filiar_id || $serial_id)
                        <button wire:click="resetFilters"
                            class="h-10 px-3 rounded-xl text-xs font-medium
                                       bg-white/[0.04] border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.08] hover:text-white transition-all
                                       inline-flex items-center gap-1.5">
                            <x-lucide-x class="w-3.5 h-3.5" />
                            Reset
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="relative overflow-x-auto">
            <div wire:loading.flex wire:target="search,filiar_id,serial_id,resetFilters,previousPage,nextPage,gotoPage"
                class="absolute inset-0 z-20 items-center justify-center bg-[#070a12]/70 backdrop-blur-sm">
                <div class="flex flex-col items-center gap-2 text-slate-400">
                    <x-lucide-loader-2 class="w-8 h-8 text-sky-400 animate-spin" />
                    <span class="text-xs">Chargement…</span>
                </div>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/[0.05]">
                        <th
                            class="px-5 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Promotion
                        </th>
                        <th
                            class="px-5 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Filière / Série
                        </th>
                        <th
                            class="px-4 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Coefficient
                        </th>
                        <th
                            class="px-5 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse ($this->promotionsDataGrouped as $promotion => $items)
                        {{-- Groupe --}}
                        <tr class="bg-white/[0.02]">
                            <td colspan="4" class="px-5 py-2.5">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-sm font-semibold text-white uppercase">
                                        Promotions {{ $promotion }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold
                                                 bg-sky-500/15 text-sky-400 border border-sky-500/20">
                                        {{ $items->count() }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                        @foreach ($items as $coef)
                            <tr class="hover:bg-white/[0.02] transition-colors group text-center">
                                <td class="px-5 py-3.5">
                                    <span class="text-sm text-slate-400">
                                        {{ $promotion }}
                                        @if ($coef->filiar)
                                            <span class="font-mono text-indigo-400/70 uppercase text-xs ml-1">
                                                {{ $coef->filiar->code }}
                                            </span>
                                        @elseif ($coef->serial)
                                            <span class="font-mono text-cyan-400/70 uppercase text-xs ml-1">
                                                {{ $coef->serial->code }}
                                            </span>
                                        @endif
                                    </span>
                                </td>

                                <td class="px-5 py-3.5">
                                    @if ($coef->filiar)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                                     bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                            {{ $coef->filiar->name }}
                                            @if ($coef->filiar->code)
                                                <span class="text-indigo-400/60">· {{ $coef->filiar->code }}</span>
                                            @endif
                                        </span>
                                    @elseif ($coef->serial)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                                     bg-cyan-500/10 text-cyan-300 border border-cyan-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                                            {{ $coef->serial->name }}
                                            @if ($coef->serial->code)
                                                <span class="text-cyan-400/60">· {{ $coef->serial->code }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-600 italic">Non défini</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3.5">
                                    <div
                                        class="inline-flex items-center justify-center w-11 h-11 rounded-xl
                                                bg-emerald-500/10 border border-emerald-500/20
                                                group-hover:bg-emerald-500/15 transition">
                                        <span class="text-base font-bold text-emerald-400 tabular-nums">
                                            {{ $coef->coef }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-3.5">
                                    <a wire:navigate
                                        href="{{ route('tenant.subjects.coefs.manage', ['subject_slug' => $this->subject->slug, 'uuid' => $coef->uuid]) }}"
                                        class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                              bg-indigo-500/10 text-indigo-400 border border-indigo-500/20
                                              hover:bg-indigo-500 hover:text-white hover:border-indigo-500
                                              transition-all opacity-70 group-hover:opacity-100">
                                        <x-lucide-pencil class="w-3.5 h-3.5" />
                                        Modifier
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-white/[0.04] flex items-center justify-center">
                                        <x-lucide-file-x class="w-6 h-6 text-slate-600" />
                                    </div>
                                    <p class="text-sm text-slate-500">Aucun coefficient pour cette matière</p>
                                    @if ($search || $filiar_id || $serial_id)
                                        <button wire:click="resetFilters" class="text-xs text-sky-400 hover:underline">
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

        @if ($this->promotionsData->hasPages())
            <div class="px-5 py-4 border-t border-white/[0.05]">
                {{ $this->promotionsData->links() }}
            </div>
        @endif
    </section>

</div>

