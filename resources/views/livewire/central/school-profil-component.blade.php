<div class="space-y-6 p-3 mb-24">

    {{-- ===================================================== --}}
    {{-- HEADER / HERO --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl overflow-hidden border border-slate-800 bg-slate-900/80">

        {{-- COVER --}}
        <div class="relative h-52 bg-gradient-to-r from-indigo-700 via-sky-600 to-cyan-500">

            <div class="absolute inset-0 bg-black/25"></div>

            {{-- BADGES --}}
            <div class="absolute top-5 right-5 flex flex-wrap gap-3">

                @if ($this->tenant->isActive())
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                             bg-emerald-500/15 border border-emerald-500/20
                             text-emerald-300 text-sm font-semibold">

                        <x-lucide-badge-check class="w-4 h-4" />
                        École active
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                             bg-red-500/50 border border-red-500/20
                             text-red-300 text-sm font-semibold">

                        <x-lucide-lock class="w-4 h-4" />
                        École non active
                    </span>
                @endif

                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                             bg-indigo-500/80
                             text-black text-sm font-semibold">

                    <x-lucide-crown class="w-4 h-4" />

                    Pack Premium+

                </span>

            </div>

        </div>

        {{-- SCHOOL INFO --}}
        <div class="relative px-5 sm:px-6 pb-6">

            <div
                class="-mt-16 flex flex-col 2xl:flex-row
                        2xl:items-end 2xl:justify-between gap-6">

                {{-- LEFT --}}
                <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-end">

                    {{-- LOGO --}}
                    <div class="relative shrink-0">

                        <div
                            class="w-32 h-32 rounded-3xl border-4 border-slate-900
                                    bg-slate-800 flex items-center justify-center
                                    shadow-2xl">

                            <x-lucide-school class="w-16 h-16 text-indigo-400" />

                        </div>

                    </div>

                    {{-- DETAILS --}}
                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-3xl sm:text-4xl font-black tracking-tight">

                                <span>
                                    {{ $this->tenant->school_name }}
                                </span>

                                <span>
                                    {{ $this->tenant->simple_name }}
                                </span>

                            </h1>

                            <span
                                class="inline-flex items-center gap-2
                                         px-3 py-1 rounded-full
                                         bg-sky-500/10 border border-sky-500/20
                                         text-black text-xs font-semibold">

                                <x-lucide-graduation-cap class="w-4 h-4" />

                                {{ $this->tenant->enseignement_type }}

                            </span>

                        </div>

                        <p class="mt-2 text-slate-400 text-lg">

                            {{ $this->tenant->devise }}

                        </p>

                        {{-- INFOS --}}
                        <div class="mt-5 flex flex-wrap gap-3">

                            <div
                                class="inline-flex items-center gap-2
                                        px-4 py-2 rounded-2xl
                                        bg-slate-800 border border-slate-700">

                                <x-lucide-map-pinned class="w-4 h-4 text-amber-400" />

                                <span class="text-sm">
                                    {{ $this->tenant->adresse }}, {{ $this->tenant->country }}
                                </span>

                            </div>

                            <div
                                class="inline-flex items-center gap-2
                                        px-4 py-2 rounded-2xl
                                        bg-slate-800 border border-slate-700">

                                <x-lucide-phone class="w-4 h-4 text-emerald-400" />

                                <span class="text-sm">
                                    {{ $this->tenant->contacts }}
                                </span>

                            </div>

                            <div
                                class="inline-flex items-center gap-2
                                        px-4 py-2 rounded-2xl
                                        bg-slate-800 border border-slate-700">

                                <x-lucide-mail class="w-4 h-4 text-sky-400" />

                                <span class="text-sm truncate">
                                    {{ $this->tenant->email }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- KPI --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-4 hidden">

        @foreach ([['Apprenants', '2 842', 'users', 'sky'], ['Enseignants', '126', 'briefcase', 'emerald'], ['Parents', '1 964', 'users-round', 'amber'], ['Classes', '38', 'layout-grid', 'indigo']] as $card)
            <div
                class="rounded-3xl border border-slate-800
                        bg-slate-900/80 p-5 overflow-hidden relative">

                <div class="absolute -right-5 -top-5 opacity-10">

                    @switch($card[2])
                        @case('users')
                            <x-lucide-users class="w-28 h-28" />
                        @break

                        @case('briefcase')
                            <x-lucide-briefcase class="w-28 h-28" />
                        @break

                        @case('users-round')
                            <x-lucide-users-round class="w-28 h-28" />
                        @break

                        @default
                            <x-lucide-layout-grid class="w-28 h-28" />
                    @endswitch

                </div>

                <div class="relative">

                    <div class="flex items-center justify-between gap-4">

                        <div>

                            <p class="text-sm text-slate-400">
                                {{ $card[0] }}
                            </p>

                            <h2 class="mt-3 text-4xl font-black">
                                {{ $card[1] }}
                            </h2>

                        </div>

                        <div
                            class="w-14 h-14 rounded-2xl
                                    bg-{{ $card[3] }}-500/10
                                    flex items-center justify-center">

                            @switch($card[2])
                                @case('users')
                                    <x-lucide-users class="w-7 h-7 text-sky-400" />
                                @break

                                @case('briefcase')
                                    <x-lucide-briefcase class="w-7 h-7 text-emerald-400" />
                                @break

                                @case('users-round')
                                    <x-lucide-users-round class="w-7 h-7 text-amber-400" />
                                @break

                                @default
                                    <x-lucide-layout-grid class="w-7 h-7 text-indigo-400" />
                            @endswitch

                        </div>

                    </div>

                </div>

            </div>
        @endforeach

    </section>

    <section class="my-3 flex items-center mb-6 justify-end">
        <div class="flex flex-wrap gap-3">

            <button
                class="h-11 px-5 rounded-2xl
                                   bg-green-300/30 hover:bg-green-600
                                   text-black
                                   flex items-center gap-2 transition-all">

                <x-lucide-plus class="w-5 h-5" />

                Accorder un bonus

            </button>

            <button
                class="h-11 px-5 rounded-2xl
                                   bg-sky-500/10 hover:bg-sky-500/20
                                   border border-sky-500/20
                                   text-sky-400
                                   flex items-center gap-2">

                <x-lucide-send class="w-5 h-5" />

                Notifier

            </button>

            @if (!$this->tenant->domain_blocked)
                <button title="Bloquer l'accès au domaine de l'école {{ $this->tenant->school_name }}"
                    wire:click="blockDomain('{{ $this->tenant->id }}')" wire:loading.attr="disabled"
                    class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 px-5 border border-red-500/30">
                    <span wire:loading.remove class="flex items-center gap-1.5" wire:target="blockDomain">
                        <x-lucide-ban class="w-4 h-4" />
                        Bloquer accès
                    </span>
                    <span wire:loading.flex wire:target="blockDomain" class="items-center gap-1.5">
                        <span class="inline-flex items-center gap-1">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </span>
                </button>
            @else
                <button title="Débloquer et re-accorder l'accès au domaine de l'école {{ $this->tenant->school_name }}"
                    wire:click="unblockDomain('{{ $this->tenant->id }}')" wire:loading.attr="disabled"
                    class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 px-5 border border-red-500/30">
                    <span wire:loading.remove class="flex items-center gap-1.5" wire:target="unblockDomain">
                        <x-lucide-unlock class="w-4 h-4" />
                        Accorder accès
                    </span>
                    <span wire:loading.flex wire:target="unblockDomain" class="items-center gap-1.5">
                        <span class="inline-flex items-center gap-1">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </span>
                </button>
            @endif

        </div>
    </section>

    {{-- ===================================================== --}}
    {{-- DETAILS + DIRECTOR --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 2xl:grid-cols-3 gap-6">

        {{-- DETAILS --}}
        <div class="2xl:col-span-2 rounded-3xl border border-slate-800 bg-slate-900/80 p-5 sm:p-6">

            <div class="flex items-center justify-between gap-4">

                <div>

                    <h2 class="text-xl font-bold">
                        Informations générales
                    </h2>

                    <p class="mt-1 text-sm text-slate-400">
                        Détails administratifs de l’établissement
                    </p>

                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                @foreach ($this->infos as $info)
                    <div
                        class="rounded-2xl border border-slate-800
                                bg-slate-950/40 p-4">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-11 h-11 rounded-2xl
                                        bg-indigo-500/10
                                        flex items-center justify-center">

                                @switch($info[2])
                                    @case('user-round')
                                        <x-lucide-user-round class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('layers-3')
                                        <x-lucide-layers-3 class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('network')
                                        <x-lucide-network class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('git-branch')
                                        <x-lucide-git-branch class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('badge-check')
                                        <x-lucide-badge-check class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('database')
                                        <x-lucide-database class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @case('globe')
                                        <x-lucide-globe class="w-5 h-5 text-indigo-400" />
                                    @break

                                    @default
                                        <x-lucide-calendar-days class="w-5 h-5 text-indigo-400" />
                                @endswitch

                            </div>

                            <div>

                                <p class="text-xs text-slate-500">
                                    {{ $info[0] }}
                                </p>

                                <p class="mt-1 font-semibold">
                                    {{ $info[1] }}
                                </p>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>
        {{-- DIRECTOR CARD --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 sm:p-6">

            <div class="flex items-center justify-between">

                <h2 class="text-xl font-bold">
                    Directeur
                </h2>
            </div>

            <div class="mt-6 text-center">

                <img src="{{ $this->profil_photo_url }}" class="w-28 h-28 rounded-3xl mx-auto object-cover">

                <h3 class="mt-4 text-xl font-bold">
                    {{ $this->tenant->getFullName() }}
                </h3>

                <p class="text-slate-400 uppercase inline-flex gap-2 items-center mt-4">
                    <span class="rounded-2xl py-1.5 bg-green-600/50 text-green-400 px-6">{{ 'Directeur' }}</span>
                </p>

            </div>

            <div class="mt-6 space-y-4">

                <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-4">

                    <div class="flex items-center gap-3">

                        <x-lucide-mail class="w-5 h-5 text-sky-400" />

                        <span class="text-sm truncate">
                            {{ $this->tenant->email }}
                        </span>

                    </div>

                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-4">

                    <div class="flex items-center gap-3">

                        <x-lucide-phone class="w-5 h-5 text-emerald-400" />

                        <span class="text-sm">
                            {{ $this->tenant->contacts }}
                        </span>

                    </div>

                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-4">

                    <div class="flex items-center gap-3">

                        <x-lucide-clock-3 class="w-5 h-5 text-amber-400" />

                        <span class="text-sm">
                            Dernière connexion : ---
                        </span>

                    </div>

                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="mt-6 grid grid-cols-1 gap-3">

                <button
                    class="h-11 rounded-xl
                               bg-indigo-500 hover:bg-indigo-400
                               text-white font-medium
                               flex items-center justify-center gap-2">

                    <x-lucide-send class="w-4 h-4" />

                    Notifier

                </button>

            </div>

        </div>

    </section>

</div>

