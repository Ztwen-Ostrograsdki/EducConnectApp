<div class="space-y-6 p-3">

    {{-- ===================================================== --}}
    {{-- HEADER / HERO --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl overflow-hidden border border-slate-800 bg-slate-900/80">

        {{-- COVER --}}
        <div class="relative h-52 bg-gradient-to-r from-indigo-700 via-sky-600 to-cyan-500">

            <div class="absolute inset-0 bg-black/25"></div>

            {{-- BADGES --}}
            <div class="absolute top-5 right-5 flex flex-wrap gap-3">

                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                             bg-emerald-500/15 border border-emerald-500/20
                             text-emerald-300 text-sm font-semibold">

                    <x-lucide-badge-check class="w-4 h-4" />

                    École active

                </span>

                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                             bg-indigo-500/15 border border-indigo-500/20
                             text-indigo-300 text-sm font-semibold">

                    <x-lucide-crown class="w-4 h-4" />

                    Pack Premium+

                </span>

            </div>

        </div>

        {{-- SCHOOL INFO --}}
        <div class="relative px-5 sm:px-6 pb-6">

            <div class="-mt-16 flex flex-col 2xl:flex-row
                        2xl:items-end 2xl:justify-between gap-6">

                {{-- LEFT --}}
                <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-end">

                    {{-- LOGO --}}
                    <div class="relative shrink-0">

                        <div class="w-32 h-32 rounded-3xl border-4 border-slate-900
                                    bg-slate-800 flex items-center justify-center
                                    shadow-2xl">

                            <x-lucide-school class="w-16 h-16 text-indigo-400" />

                        </div>

                    </div>

                    {{-- DETAILS --}}
                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-3xl sm:text-4xl font-black tracking-tight">

                                CEG Horizon Technique

                            </h1>

                            <span
                                class="inline-flex items-center gap-2
                                         px-3 py-1 rounded-full
                                         bg-sky-500/10 border border-sky-500/20
                                         text-sky-300 text-xs font-semibold">

                                <x-lucide-graduation-cap class="w-4 h-4" />

                                Enseignement Technique

                            </span>

                        </div>

                        <p class="mt-2 text-slate-400 text-lg">

                            Excellence • Discipline • Innovation

                        </p>

                        {{-- INFOS --}}
                        <div class="mt-5 flex flex-wrap gap-3">

                            <div class="inline-flex items-center gap-2
                                        px-4 py-2 rounded-2xl
                                        bg-slate-800 border border-slate-700">

                                <x-lucide-map-pinned class="w-4 h-4 text-amber-400" />

                                <span class="text-sm">
                                    Cotonou, Littoral, Bénin
                                </span>

                            </div>

                            <div class="inline-flex items-center gap-2
                                        px-4 py-2 rounded-2xl
                                        bg-slate-800 border border-slate-700">

                                <x-lucide-phone class="w-4 h-4 text-emerald-400" />

                                <span class="text-sm">
                                    +229 01 90 00 00 00
                                </span>

                            </div>

                            <div class="inline-flex items-center gap-2
                                        px-4 py-2 rounded-2xl
                                        bg-slate-800 border border-slate-700">

                                <x-lucide-mail class="w-4 h-4 text-sky-400" />

                                <span class="text-sm truncate">
                                    administration@horizon-school.com
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-wrap gap-3">

                    <button
                        class="h-12 px-5 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-400
                                   text-white font-semibold
                                   flex items-center gap-2 transition-all">

                        <x-lucide-pencil class="w-5 h-5" />

                        Modifier

                    </button>

                    <button
                        class="h-12 px-5 rounded-2xl
                                   bg-sky-500/10 hover:bg-sky-500/20
                                   border border-sky-500/20
                                   text-sky-400 font-semibold
                                   flex items-center gap-2">

                        <x-lucide-send class="w-5 h-5" />

                        Notifier

                    </button>

                    <button
                        class="h-12 px-5 rounded-2xl
                                   bg-rose-500/10 hover:bg-rose-500/20
                                   border border-rose-500/20
                                   text-rose-400 font-semibold
                                   flex items-center gap-2">

                        <x-lucide-ban class="w-5 h-5" />

                        Suspendre

                    </button>

                </div>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- KPI --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-4">

        @foreach ([['Apprenants', '2 842', 'users', 'sky'], ['Enseignants', '126', 'briefcase', 'emerald'], ['Parents', '1 964', 'users-round', 'amber'], ['Classes', '38', 'layout-grid', 'indigo']] as $card)
            <div class="rounded-3xl border border-slate-800
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

                        <div class="w-14 h-14 rounded-2xl
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

                <button class="h-11 px-4 rounded-xl
                               bg-slate-800 hover:bg-slate-700
                               border border-slate-700
                               text-sm flex items-center gap-2">

                    <x-lucide-file-pen-line class="w-4 h-4" />

                    Modifier infos

                </button>

            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                @foreach ([['Directeur', 'M. Arnaud HOUNGBEDJI', 'user-round'], ['Promotion active', '6 promotions', 'layers-3'], ['Filières', 'F1 • F2 • F3 • F4 • TI', 'network'], ['Séries', 'C • D • G2 • G3', 'git-branch'], ['Statut abonnement', 'Actif jusqu’au 20 Nov 2026', 'badge-check'], ['Base de données', '4.8 GB utilisées', 'database'], ['Nom domaine', 'horizon.educconnect.app', 'globe'], ['Créée le', '12 Janvier 2018', 'calendar-days']] as $info)
                    <div class="rounded-2xl border border-slate-800
                                bg-slate-950/40 p-4">

                        <div class="flex items-center gap-3">

                            <div class="w-11 h-11 rounded-2xl
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

                <button class="w-10 h-10 rounded-xl
                               bg-slate-800 hover:bg-slate-700
                               border border-slate-700
                               flex items-center justify-center">

                    <x-lucide-ellipsis class="w-5 h-5" />

                </button>

            </div>

            <div class="mt-6 text-center">

                <img src="https://i.pravatar.cc/300?img=14" class="w-28 h-28 rounded-3xl mx-auto object-cover">

                <h3 class="mt-4 text-xl font-bold">
                    M. Arnaud HOUNGBEDJI
                </h3>

                <p class="text-slate-400">
                    Directeur Général
                </p>

            </div>

            <div class="mt-6 space-y-4">

                <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-4">

                    <div class="flex items-center gap-3">

                        <x-lucide-mail class="w-5 h-5 text-sky-400" />

                        <span class="text-sm truncate">
                            direction@horizon-school.com
                        </span>

                    </div>

                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-4">

                    <div class="flex items-center gap-3">

                        <x-lucide-phone class="w-5 h-5 text-emerald-400" />

                        <span class="text-sm">
                            +229 01 97 00 00 00
                        </span>

                    </div>

                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-4">

                    <div class="flex items-center gap-3">

                        <x-lucide-clock-3 class="w-5 h-5 text-amber-400" />

                        <span class="text-sm">
                            Dernière connexion : Aujourd’hui 08:12
                        </span>

                    </div>

                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="mt-6 grid grid-cols-2 gap-3">

                <button class="h-11 rounded-xl
                               bg-indigo-500 hover:bg-indigo-400
                               text-white font-medium
                               flex items-center justify-center gap-2">

                    <x-lucide-send class="w-4 h-4" />

                    Notifier

                </button>

                <button
                    class="h-11 rounded-xl
                               bg-rose-500/10 hover:bg-rose-500/20
                               border border-rose-500/20
                               text-rose-400 font-medium
                               flex items-center justify-center gap-2">

                    <x-lucide-ban class="w-4 h-4" />

                    Suspendre

                </button>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- PERFORMANCES --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- BEST --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5">

            <div class="flex items-center gap-3">

                <div class="w-12 h-12 rounded-2xl
                            bg-emerald-500/10
                            flex items-center justify-center">

                    <x-lucide-trophy class="w-6 h-6 text-emerald-400" />

                </div>

                <div>

                    <p class="text-sm text-slate-400">
                        Meilleur apprenant
                    </p>

                    <h3 class="text-xl font-black">
                        Sarah KOUASSI
                    </h3>

                </div>

            </div>

            <div class="mt-5 space-y-3 text-sm">

                <div class="flex justify-between">
                    <span class="text-slate-400">Classe</span>
                    <span>Terminale F2</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-400">Moyenne</span>
                    <span class="text-emerald-400 font-bold">
                        18.72 / 20
                    </span>
                </div>

            </div>

        </div>

        {{-- LOWEST --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5">

            <div class="flex items-center gap-3">

                <div class="w-12 h-12 rounded-2xl
                            bg-rose-500/10
                            flex items-center justify-center">

                    <x-lucide-triangle-alert class="w-6 h-6 text-rose-400" />

                </div>

                <div>

                    <p class="text-sm text-slate-400">
                        Plus faible apprenant
                    </p>

                    <h3 class="text-xl font-black">
                        Marc HOUNKPATI
                    </h3>

                </div>

            </div>

            <div class="mt-5 space-y-3 text-sm">

                <div class="flex justify-between">
                    <span class="text-slate-400">Classe</span>
                    <span>3ème A</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-400">Moyenne</span>
                    <span class="text-rose-400 font-bold">
                        04.18 / 20
                    </span>
                </div>

            </div>

        </div>

        {{-- ADMISSIBILITY --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5">

            <div class="flex items-center gap-3">

                <div class="w-12 h-12 rounded-2xl
                            bg-sky-500/10
                            flex items-center justify-center">

                    <x-lucide-chart-column class="w-6 h-6 text-sky-400" />

                </div>

                <div>

                    <p class="text-sm text-slate-400">
                        Taux d’admissibilité
                    </p>

                    <h3 class="text-3xl font-black">
                        84%
                    </h3>

                </div>

            </div>

            <div class="mt-6 h-3 rounded-full bg-slate-800 overflow-hidden">

                <div class="h-full rounded-full bg-sky-500 w-[84%]"></div>

            </div>

            <p class="mt-3 text-sm text-slate-400">
                Très bonne performance globale cette année.
            </p>

        </div>

    </section>

</div>
