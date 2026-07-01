<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8">

        <div class="flex flex-wrap items-center gap-3 p-3 bg-indigo-500/10 rounded-4xl my-1.5">
            <h1 class="text-lg sm:text-xl font-bold text-slate-400 px-3 py-2.5">
                Profil de la filière <span class="font-mono text-amber-400 font-semibold">{{ $filiar->name }}</span>
            </h1>

            <span
                class="px-3 py-1 rounded-full @if ($filiar->is_active) bg-emerald-500/10 text-emerald-400 @else bg-red-500/10 text-red-400 @endif text-xs">
                filière {{ $filiar->is_active ? 'active' : 'non active' }}
            </span>
        </div>

        {{-- ===================================================== --}}
        {{-- HERO --}}
        {{-- ===================================================== --}}
        <section class="mb-6">
            <div class="relative overflow-hidden rounded-[32px] border border-slate-800 bg-slate-900">

                {{-- BG --}}
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-slate-900 to-slate-900"></div>

                <div class="relative p-5 sm:p-6 lg:p-8">
                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-8">

                        {{-- LEFT --}}
                        <div class="flex flex-col lg:flex-row gap-6 min-w-0">

                            {{-- ICON --}}
                            <div class="flex justify-center lg:block">
                                <div
                                    class="w-32 h-32 sm:w-36 sm:h-36 rounded-[30px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-5xl shrink-0">
                                    {{ $filiar->code }}
                                </div>
                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $filiar->name }}</h1>
                                </div>

                                <p class="mt-2 text-slate-400">
                                    Tableau global des statistiques, performances de la filière {{ $filiar->name }}.
                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">
                                        {{ __zero($this->filiar->getFiliarTeachersOfSchoolYearCount()) }}
                                        Enseignants
                                    </div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">
                                        {{ __zero($filiar->getFiliarClassesOfSchoolYearCount()) }} Classes
                                    </div>
                                    @if ($filiar->deleted_at)
                                        <div
                                            class="px-4 py-2 flex items-center justify-center rounded-2xl animate-pulse font-mono bg-rose-800/40 text-rose-400 text-xs">
                                            Cette filière est supprimée et se trouve dans la corbeille
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>

        {{-- KPI --}}
        <section class="mb-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 {{ $grid_cols }} gap-4">
                @foreach ($this->kpis as $kpi)
                    <div class="rounded-3xl bg-slate-900 border border-slate-800 p-5">
                        <p class="text-sm text-slate-400">{{ $kpi[0] }}</p>
                        <h2 class="mt-3 text-xl font-bold {{ $kpi[2] }}">{{ $kpi[1] }}</h2>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="my-4 mb-5 flex justify-end border-y border-y-slate-800 py-4">
            <div class="flex gap-3">
                <a wire:navigate href="{{ route('tenant.classes.create') }}"
                    class="py-3 px-5 rounded-2xl bg-blue-500 hover:bg-blue-800">
                    Créer une classe
                </a>
                <button class="py-3 px-5 rounded-2xl bg-emerald-500 hover:bg-emerald-600">
                    Export PDF
                </button>
                <a wire:navigate href="{{ route('tenant.filiar.edit', ['filiar_slug' => $filiar->slug]) }}"
                    class="py-3 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600">
                    Editer cette filière
                </a>
                <button
                    title="{{ $filiar->deleted_at ? 'Restaurer cette filière de la corbeille ' : 'Mettre cette filière dans la corbeille ' }} "
                    wire:click="{{ $filiar->deleted_at ? 'restoreFiliar(' . $filiar->id . ')' : 'deleteFiliar(' . $filiar->id . ')' }}"
                    wire:loading.attr="disabled" wire:target="deleteFiliar, restoreFiliar"
                    class="relative py-3 px-4 rounded-xl text-white {{ $filiar->deleted_at ? 'bg-green-600/50 hover:bg-green-800/80' : 'bg-red-500/60 hover:bg-red-600/80' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50 hover:text-yellow-400">
                    <span wire:loading.remove wire:target="deleteFiliar, restoreFiliar"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            @if ($filiar->deleted_at)
                                <x-lucide-refresh-ccw class="w-4 h-4" />
                                <span>Restaurer</span>
                            @else
                                <x-lucide-trash class="w-4 h-4" />
                                <span>Corbeille</span>
                            @endif
                        </span>
                    </span>

                    <span wire:loading wire:target="restoreFiliar, deleteFiliar" class="inline-flex items-center gap-1">
                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </span>
                </button>
            </div>
        </section>

        <section class="mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- BEST --}}
                <div class="rounded-[32px] bg-slate-900 border border-emerald-500/20 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-2xl">
                            🏆</div>
                        <div>
                            <h2 class="text-xl font-semibold">Meilleure Performance</h2>
                            <p class="text-slate-400">Plus forte moyenne enregistrée.</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                            <h3 class="text-lg font-semibold">KOUASSI Sarah</h3>
                            <p class="mt-2 text-slate-400">Classe : Terminale F4-1</p>

                            <div class="mt-4 flex flex-wrap gap-3">
                                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs">Moyenne
                                    : 19.75</span>
                                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">Promotion
                                    : Terminale</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- WORST --}}
                <div class="rounded-[32px] bg-slate-900 border border-rose-500/20 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-rose-500/10 flex items-center justify-center text-2xl">⚠️
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold">Plus Faible Performance</h2>
                            <p class="text-slate-400">Plus faible moyenne enregistrée.</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                            <h3 class="text-lg font-semibold">HOUNKPE David</h3>
                            <p class="mt-2 text-slate-400">Classe : Tle F4-2</p>

                            <div class="mt-4 flex flex-wrap gap-3">
                                <span class="px-3 py-1 rounded-full bg-rose-500/10 text-rose-400 text-xs">Moyenne :
                                    02.15</span>
                                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">Promotion
                                    : Terminale F4</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- BEST BOY / BEST GIRL --}}
        {{-- ===================================================== --}}
        <section class="mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- ===================================================== --}}
                {{-- MEILLEUR GARÇON --}}
                {{-- ===================================================== --}}
                <div class="rounded-[32px] bg-slate-900 border border-sky-500/20 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="p-6 border-b border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-sky-500/10 flex items-center justify-center text-2xl">
                                🏅</div>
                            <div>
                                <h2 class="text-xl font-semibold">Meilleur Garçon</h2>
                                <p class="mt-1 text-sm text-slate-400">Meilleure performance masculine dans la matière.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">

                            {{-- PHOTO --}}
                            <div class="flex justify-center lg:block">
                                <div class="w-28 h-28 rounded-[28px] bg-slate-800 border border-slate-700 shrink-0">
                                </div>
                            </div>

                            {{-- DETAILS --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-2xl font-bold">HOUNKPE David</h3>
                                    <span class="px-3 py-1 rounded-full bg-sky-500/10 text-sky-400 text-xs">Rang #1
                                        Garçon</span>
                                </div>

                                <p class="mt-2 text-slate-400">Terminale F4-1 — Promotion Terminale</p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Moyenne :
                                        18.92</div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Coef : 4
                                    </div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Prof : M.
                                        AHOLOU</div>
                                </div>

                                {{-- ACTIONS --}}
                                <div class="mt-6 flex flex-wrap gap-3">
                                    <button class="h-11 px-5 rounded-2xl bg-sky-500 hover:bg-sky-600">Voir
                                        Profil</button>
                                    <button class="h-11 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600">Historique
                                        Notes</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ===================================================== --}}
                {{-- MEILLEURE FILLE --}}
                {{-- ===================================================== --}}
                <div class="rounded-[32px] bg-slate-900 border border-pink-500/20 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="p-6 border-b border-slate-800">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-2xl bg-pink-500/10 flex items-center justify-center text-2xl">
                                👑</div>
                            <div>
                                <h2 class="text-xl font-semibold">Meilleure Fille</h2>
                                <p class="mt-1 text-sm text-slate-400">Meilleure performance féminine dans la matière.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">

                            {{-- PHOTO --}}
                            <div class="flex justify-center lg:block">
                                <div class="w-28 h-28 rounded-[28px] bg-slate-800 border border-slate-700 shrink-0">
                                </div>
                            </div>

                            {{-- DETAILS --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-2xl font-bold">KOUASSI Sarah</h3>
                                    <span class="px-3 py-1 rounded-full bg-pink-500/10 text-pink-400 text-xs">Rang #1
                                        Fille</span>
                                </div>

                                <p class="mt-2 text-slate-400">Terminale F4-2 — Promotion Terminale</p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Moyenne :
                                        19.41</div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Coef : 4
                                    </div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Prof : Mme
                                        ADJOVI</div>
                                </div>

                                {{-- ACTIONS --}}
                                <div class="mt-6 flex flex-wrap gap-3">
                                    <button class="h-11 px-5 rounded-2xl bg-pink-500 hover:bg-pink-600">Voir
                                        Profil</button>
                                    <button class="h-11 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600">Historique
                                        Notes</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>

        @livewire('tenants.filiars.filiar-teachers-list-component', ['filiar' => $filiar, 'activeYear' => $this->activeYear])

        @livewire('tenants.filiars.filiar-students-list-component', ['filiar' => $filiar, 'activeYear' => $this->activeYear])

        <section class="rounded-3xl bg-slate-900 border border-slate-800 p-5" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left">
                <h2 class="text-lg font-semibold">Élèves en Difficulté</h2>
                <svg :class="open ? 'rotate-180' : 'rotate-0'" class="w-5 h-5 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" x-collapse x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="mt-5 space-y-4">
                @foreach (range(1, 5) as $weak)
                    <div class="rounded-2xl bg-slate-950 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium">KOFFI Junior</h3>
                                <p class="mt-1 text-sm text-slate-400">Terminale F2-2</p>
                            </div>
                            <span class="text-rose-400 font-bold">08.42</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

</div>

