<div class="space-y-5" wire:key="personnels-page">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold tracking-tight text-slate-100">Le personnel de l'établissement</h2>
            <p class="mt-1 text-sm text-slate-400">Découvrez les membres de l'équipe administrative et technique</p>
        </div>

        <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Nom, prénoms, poste..."
                    class="w-full sm:w-64 rounded-xl border border-slate-700/80 bg-slate-800/50 py-2.5 pl-10 pr-10 text-sm text-slate-200 placeholder-slate-500 transition focus:border-orange-500/60 focus:bg-slate-800/80 focus:outline-none focus:ring-2 focus:ring-orange-500/20" />
                <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="h-4 w-4 animate-spin text-orange-400" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </div>
            </div>

            <select wire:model.live="gradeFilter"
                class="rounded-xl border border-slate-700/80 bg-slate-800/50 px-3.5 py-2.5 text-sm text-slate-200 transition focus:border-orange-500/60 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                <option value="">Tous les grades</option>
                @foreach ($this->grades as $grade)
                    <option value="{{ $grade }}">{{ $grade }}</option>
                @endforeach
            </select>

            <select wire:model.live="genderFilter"
                class="rounded-xl border border-slate-700/80 bg-slate-800/50 px-3.5 py-2.5 text-sm text-slate-200 transition focus:border-orange-500/60 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                <option value="">Tous</option>
                <option value="M">Hommes</option>
                <option value="F">Femmes</option>
            </select>
        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="rounded-xl border border-slate-700/50 bg-slate-900/40 p-4">
            <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">Effectif total</p>
            <p class="mt-1 text-lg font-semibold text-slate-100">{{ $this->stats['total'] }}</p>
        </div>
        <div class="rounded-xl border border-slate-700/50 bg-slate-900/40 p-4">
            <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">Hommes</p>
            <p class="mt-1 text-lg font-semibold text-slate-100">{{ $this->stats['hommes'] }}</p>
        </div>
        <div class="rounded-xl border border-slate-700/50 bg-slate-900/40 p-4">
            <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">Femmes</p>
            <p class="mt-1 text-lg font-semibold text-slate-100">{{ $this->stats['femmes'] }}</p>
        </div>
    </div>

    {{-- GRID --}}
    <div class="relative">
        <div wire:loading.flex wire:target="search, gradeFilter, genderFilter, gotoPage, previousPage, nextPage"
            class="absolute inset-0 z-20 hidden items-center justify-center bg-slate-950/50 backdrop-blur-sm rounded-2xl">
            <div
                class="flex items-center gap-3 rounded-xl bg-slate-800/90 px-5 py-3 shadow-lg ring-1 ring-slate-700/50">
                <svg class="h-5 w-5 animate-spin text-orange-400" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-sm font-medium text-slate-300">Chargement...</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($this->personnels as $personnel)
                <div wire:key="personnel-{{ $personnel->id }}"
                    class="group rounded-2xl border border-slate-700/50 bg-slate-900/40 p-5 transition hover:border-slate-600/60 hover:bg-slate-800/40">
                    <div class="flex flex-col items-center text-center">
                        <div class="h-20 w-20 overflow-hidden rounded-full bg-slate-700/50 ring-2 ring-slate-700/40">
                            @if ($personnel->profil_photo_url)
                                <img src="{{ $personnel->profil_photo_url }}" alt="{{ $personnel->getFullName() }}"
                                    class="h-full w-full object-cover">
                            @else
                                <div
                                    class="flex h-full w-full items-center justify-center text-lg font-semibold text-slate-400">
                                    {{ mb_substr($personnel->name, 0, 1) }}{{ mb_substr($personnel->prenames, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <h3 class="mt-3 font-medium text-slate-100">{{ $personnel->getFullName() }}</h3>

                        @if ($personnel->title)
                            <p class="mt-0.5 text-sm text-slate-400">{{ $personnel->title }}</p>
                        @endif

                        <div class="mt-2 flex flex-wrap items-center justify-center gap-1.5">
                            @if ($personnel->grade)
                                <span
                                    class="inline-flex items-center rounded-full bg-slate-700/50 px-2.5 py-0.5 text-[11px] font-medium text-slate-300 ring-1 ring-inset ring-slate-600/40">
                                    {{ $personnel->grade }}
                                </span>
                            @endif
                            @if ($personnel->since)
                                <span
                                    class="inline-flex items-center rounded-full bg-orange-500/10 px-2.5 py-0.5 text-[11px] font-medium text-orange-400 ring-1 ring-inset ring-orange-500/25">
                                    Depuis {{ $personnel->since->format('Y') }}
                                </span>
                            @endif
                        </div>

                        @if ($personnel->description)
                            <p class="mt-3 text-xs text-slate-500 line-clamp-3">{{ $personnel->description }}</p>
                        @endif

                        @if ($personnel->contacts)
                            <p class="mt-3 text-xs text-slate-400">{{ $personnel->contacts }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <p class="text-sm font-medium text-slate-300">Aucun membre du personnel trouvé</p>
                    <p class="mt-1 text-xs text-slate-500">Essayez de modifier vos filtres ou votre recherche</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="flex items-center justify-between px-1">
        <p class="text-xs text-slate-500">
            <span class="font-medium text-slate-400">{{ $this->personnels->firstItem() ?? 0 }}</span>
            –
            <span class="font-medium text-slate-400">{{ $this->personnels->lastItem() ?? 0 }}</span>
            sur
            <span class="font-medium text-slate-400">{{ $this->personnels->total() }}</span>
            membres
        </p>
        <div>{{ $this->personnels->links() }}</div>
    </div>
</div>
