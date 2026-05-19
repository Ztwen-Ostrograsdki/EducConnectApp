<div class="w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- GLOBAL CONTAINER --}}
    {{-- ===================================================== --}}
    <div class="mx-auto
                w-full
                max-w-[1850px]
                px-3
                sm:px-4
                lg:px-6
                xl:px-8">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="flex flex-col
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                        gap-5">

                {{-- LEFT --}}
                <div class="min-w-0">

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl sm:text-3xl font-bold">

                            Parents d'Élèves

                        </h1>

                        <span class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400
                                     text-xs">

                            1 248 Parents

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm sm:text-base">

                        Gestion centralisée des représentants légaux des apprenants

                    </p>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-wrap gap-3">

                    <button class="h-11 px-5 rounded-2xl
                                   bg-slate-800
                                   hover:bg-slate-700
                                   transition-all text-sm">

                        Exporter

                    </button>

                    <button class="h-11 px-5 rounded-2xl
                                   bg-indigo-500
                                   hover:bg-indigo-600
                                   transition-all text-sm">

                        Ajouter Parent

                    </button>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- KPI --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="grid
                        grid-cols-2
                        xl:grid-cols-4
                        gap-4">

                @foreach([
                    ['Parents Actifs', '1180', 'text-emerald-400'],
                    ['Accès Bloqués', '32', 'text-rose-400'],
                    ['Notifications Envoyées', '8420', 'text-indigo-400'],
                    ['Parents Connectés', '864', 'text-sky-400']
                ] as $kpi)

                <div class="rounded-3xl
                            border border-slate-800
                            bg-slate-900
                            p-4 sm:p-5">

                    <p class="text-xs sm:text-sm text-slate-400">
                        {{ $kpi[0] }}
                    </p>

                    <h2 class="mt-3
                               text-2xl sm:text-3xl xl:text-4xl
                               font-bold {{ $kpi[2] }}">

                        {{ $kpi[1] }}

                    </h2>

                </div>

                @endforeach

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- FILTERS --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        p-4 sm:p-5">

                <div class="flex flex-col gap-4">

                    {{-- SEARCH --}}
                    <div class="relative">

                        <input
                            type="text"
                            placeholder="Rechercher un parent, téléphone ou enfant..."
                            class="w-full h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-500/40"
                        >

                        <div class="absolute left-4 top-1/2
                                    -translate-y-1/2
                                    text-slate-500">

                            🔍

                        </div>

                    </div>

                    {{-- FILTER GRID --}}
                    <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-7
                                gap-3">

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800 text-sm">

                            <option>Toutes les classes</option>
                            <option>Terminale F2-1</option>
                            <option>1ère G2</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800 text-sm">

                            <option>Sexe</option>
                            <option>Masculin</option>
                            <option>Féminin</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800 text-sm">

                            <option>Ville</option>
                            <option>Cotonou</option>
                            <option>Porto-Novo</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800 text-sm">

                            <option>Profession</option>
                            <option>Commerçant</option>
                            <option>Fonctionnaire</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800 text-sm">

                            <option>Statut Accès</option>
                            <option>Actif</option>
                            <option>Bloqué</option>

                        </select>

                        <button class="h-11 rounded-2xl
                                       bg-indigo-500
                                       hover:bg-indigo-600
                                       transition-all text-sm">

                            Filtrer

                        </button>

                        <button class="h-11 rounded-2xl
                                       bg-rose-500/20
                                       text-rose-400
                                       hover:bg-rose-500/30
                                       transition-all text-sm">

                            Réinitialiser

                        </button>

                    </div>

                    {{-- GLOBAL ACTIONS --}}
                    <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-5
                                gap-3">

                        <button class="h-12 rounded-2xl
                                       bg-indigo-500
                                       hover:bg-indigo-600
                                       transition-all text-sm">

                            Notifier Tous

                        </button>

                        <button class="h-12 rounded-2xl
                                       bg-emerald-500
                                       hover:bg-emerald-600
                                       transition-all text-sm">

                            Envoyer Bulletins

                        </button>

                        <button class="h-12 rounded-2xl
                                       bg-sky-500
                                       hover:bg-sky-600
                                       transition-all text-sm">

                            Envoyer Notes

                        </button>

                        <button class="h-12 rounded-2xl
                                       bg-amber-500
                                       hover:bg-amber-600
                                       transition-all text-sm">

                            Mail Global

                        </button>

                        <button class="h-12 rounded-2xl
                                       bg-rose-500
                                       hover:bg-rose-600
                                       transition-all text-sm">

                            Bloquer Accès

                        </button>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- MAIN GRID --}}
        {{-- ===================================================== --}}
        <section>

            <div class="grid
                        grid-cols-1
                        2xl:grid-cols-[minmax(0,1fr)_390px]
                        gap-6">

                {{-- ===================================================== --}}
                {{-- LEFT CONTENT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">

                    {{-- TABLE --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                overflow-hidden">

                        {{-- HEADER --}}
                        <div class="border-b border-slate-800
                                    p-4 sm:p-6">

                            <div class="flex flex-col
                                        lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                        gap-4">

                                <div>

                                    <h2 class="text-lg sm:text-xl font-semibold">

                                        Liste des Parents

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Gestion des accès et suivi des représentants

                                    </p>

                                </div>

                                <div class="flex flex-wrap gap-3">

                                    <button class="h-11 px-4 rounded-2xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        Trier

                                    </button>

                                    <button class="h-11 px-4 rounded-2xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        Colonnes

                                    </button>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto">

                            <table class="min-w-[1900px] w-full">

                                <thead class="bg-slate-950 border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Parent
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Sexe
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Profession
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Ville
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Téléphone
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Enfant(s)
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Classes
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Dernière Connexion
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Statut
                                        </th>

                                        <th class="px-6 py-4 text-right text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach(range(1,12) as $parent)

                                    <tr class="hover:bg-slate-800/40 transition-all">

                                        {{-- PROFILE --}}
                                        <td class="px-6 py-5">

                                            <div class="flex items-center gap-4">

                                                <div class="w-14 h-14 rounded-2xl
                                                            bg-slate-800 shrink-0">
                                                </div>

                                                <div class="min-w-0">

                                                    <h3 class="font-medium truncate">

                                                        Mme AGBODJI Clarisse

                                                    </h3>

                                                    <p class="mt-1 text-sm text-slate-400 truncate">

                                                        clarisse@email.com

                                                    </p>

                                                </div>

                                            </div>

                                        </td>

                                        {{-- SEX --}}
                                        <td class="px-4 py-5 text-center">

                                            Féminin

                                        </td>

                                        {{-- JOB --}}
                                        <td class="px-4 py-5 text-center whitespace-nowrap">

                                            Fonctionnaire

                                        </td>

                                        {{-- CITY --}}
                                        <td class="px-4 py-5 text-center">

                                            Cotonou

                                        </td>

                                        {{-- PHONE --}}
                                        <td class="px-4 py-5 text-center whitespace-nowrap">

                                            +229 01 00 00 00

                                        </td>

                                        {{-- CHILDREN --}}
                                        <td class="px-4 py-5">

                                            <div class="space-y-2">

                                                <div class="px-3 py-2 rounded-xl
                                                            bg-slate-950 text-sm">

                                                    KOUASSI Marc

                                                </div>

                                                <div class="px-3 py-2 rounded-xl
                                                            bg-slate-950 text-sm">

                                                    KOUASSI Sarah

                                                </div>

                                            </div>

                                        </td>

                                        {{-- CLASSES --}}
                                        <td class="px-4 py-5">

                                            <div class="flex flex-wrap
                                                        justify-center gap-2">

                                                <span class="px-3 py-1 rounded-full
                                                             bg-indigo-500/10
                                                             text-indigo-400 text-xs">

                                                    Tle F2-1

                                                </span>

                                                <span class="px-3 py-1 rounded-full
                                                             bg-sky-500/10
                                                             text-sky-400 text-xs">

                                                    1ère G2

                                                </span>

                                            </div>

                                        </td>

                                        {{-- LOGIN --}}
                                        <td class="px-4 py-5 text-center whitespace-nowrap">

                                            Aujourd’hui 07:32

                                        </td>

                                        {{-- STATUS --}}
                                        <td class="px-4 py-5 text-center">

                                            <span class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                Actif

                                            </span>

                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="px-6 py-5">

                                            <div class="flex flex-wrap
                                                        justify-end gap-2">

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-indigo-500/10
                                                               text-indigo-400
                                                               hover:bg-indigo-500/20
                                                               transition-all text-sm">

                                                    Bulletin

                                                </button>

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-sky-500/10
                                                               text-sky-400
                                                               hover:bg-sky-500/20
                                                               transition-all text-sm">

                                                    Notes

                                                </button>

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-emerald-500/10
                                                               text-emerald-400
                                                               hover:bg-emerald-500/20
                                                               transition-all text-sm">

                                                    Notifier

                                                </button>

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-amber-500/10
                                                               text-amber-400
                                                               hover:bg-amber-500/20
                                                               transition-all text-sm">

                                                    Modifier

                                                </button>

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-rose-500/10
                                                               text-rose-400
                                                               hover:bg-rose-500/20
                                                               transition-all text-sm">

                                                    Bloquer

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        {{-- PAGINATION --}}
                        <div class="border-t border-slate-800
                                    px-4 sm:px-6 py-4">

                            <div class="flex flex-col sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                        gap-4">

                                <p class="text-sm text-slate-400">

                                    Affichage de 1 à 12 sur 1248 parents

                                </p>

                                <div class="flex items-center gap-2 flex-wrap">

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        Précédent

                                    </button>

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-indigo-500
                                                   hover:bg-indigo-600
                                                   transition-all text-sm">

                                        1

                                    </button>

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        2

                                    </button>

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        Suivant

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RIGHT SIDEBAR --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">

                    {{-- STATS --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Activité des Parents

                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach([
                                ['Bulletins consultés', '78%', 'bg-indigo-500'],
                                ['Notifications lues', '69%', 'bg-emerald-500'],
                                ['Parents connectés', '57%', 'bg-sky-500'],
                                ['Accès bloqués', '8%', 'bg-rose-500']
                            ] as $item)

                            <div>

                                <div class="flex items-center justify-between">

                                    <span class="text-sm text-slate-300">
                                        {{ $item[0] }}
                                    </span>

                                    <span class="text-sm font-semibold">
                                        {{ $item[1] }}
                                    </span>

                                </div>

                                <div class="mt-2 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                                    <div class="h-full rounded-full {{ $item[2] }}"
                                         style="width: {{ $item[1] }}">
                                    </div>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- RECENT --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Activités Récentes

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach(range(1,5) as $activity)

                            <div class="rounded-2xl
                                        bg-slate-950
                                        p-4">

                                <div class="flex items-start gap-3">

                                    <div class="w-11 h-11 rounded-2xl
                                                bg-indigo-500/10
                                                flex items-center
                                                justify-center
                                                text-indigo-400">

                                        ✓

                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="font-medium text-sm">

                                            Bulletin envoyé

                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">

                                            Bulletin transmis au parent

                                        </p>

                                        <p class="mt-2 text-xs text-slate-500">

                                            Il y a 35 min

                                        </p>

                                    </div>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- QUICK MENU --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Accès Rapides

                        </h2>

                        <div class="mt-5 grid grid-cols-2 gap-3">

                            @foreach([
                                'Bulletins',
                                'Notes',
                                'Messages',
                                'Présences',
                                'Paiements',
                                'Archivage'
                            ] as $menu)

                            <button class="h-24 rounded-2xl
                                           bg-slate-950
                                           hover:bg-indigo-500/10
                                           border border-slate-800
                                           transition-all
                                           text-sm">

                                {{ $menu }}

                            </button>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>
