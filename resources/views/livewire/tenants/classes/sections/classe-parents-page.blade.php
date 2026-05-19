<div class="w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- CONTAINER --}}
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

                            186 Parents

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm sm:text-base">

                        Gestion des parents associés à la classe Terminale F2-1

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
                    ['Parents Actifs', '172', 'text-emerald-400'],
                    ['Accès Bloqués', '14', 'text-rose-400'],
                    ['Notifications', '842', 'text-indigo-400'],
                    ['Enfants liés', '248', 'text-amber-400']
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
        {{-- GLOBAL ACTIONS --}}
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
                            placeholder="Rechercher un parent..."
                            class="w-full h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-500/40"
                        >

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                            🔍
                        </div>

                    </div>

                    {{-- FILTERS --}}
                    <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-6
                                gap-3">

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800 text-sm">

                            <option>Toutes classes</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800 text-sm">

                            <option>Statut accès</option>
                            <option>Actif</option>
                            <option>Bloqué</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800 text-sm">

                            <option>Nombre d'enfants</option>
                            <option>1 enfant</option>
                            <option>2 enfants et +</option>

                        </select>

                        <button class="h-11 rounded-2xl
                                       bg-indigo-500
                                       hover:bg-indigo-600
                                       transition-all text-sm">

                            Filtrer

                        </button>

                        <button class="h-11 rounded-2xl
                                       bg-emerald-500
                                       hover:bg-emerald-600
                                       transition-all text-sm">

                            Envoyer Bulletins

                        </button>

                        <button class="h-11 rounded-2xl
                                       bg-sky-500
                                       hover:bg-sky-600
                                       transition-all text-sm">

                            Envoyer Notes

                        </button>

                    </div>

                    {{-- GLOBAL BUTTONS --}}
                    <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-4
                                gap-3">

                        <button class="h-12 rounded-2xl
                                       bg-slate-800
                                       hover:bg-slate-700
                                       transition-all text-sm">

                            Notifier Tous les Parents

                        </button>

                        <button class="h-12 rounded-2xl
                                       bg-indigo-500/20
                                       text-indigo-400
                                       hover:bg-indigo-500/30
                                       transition-all text-sm">

                            Envoyer Mail Global

                        </button>

                        <button class="h-12 rounded-2xl
                                       bg-amber-500/20
                                       text-amber-400
                                       hover:bg-amber-500/30
                                       transition-all text-sm">

                            Relancer Impayés

                        </button>

                        <button class="h-12 rounded-2xl
                                       bg-rose-500/20
                                       text-rose-400
                                       hover:bg-rose-500/30
                                       transition-all text-sm">

                            Bloquer Accès Groupe

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
                        2xl:grid-cols-[minmax(0,1fr)_380px]
                        gap-6">

                {{-- ===================================================== --}}
                {{-- LEFT --}}
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

                                        Gestion des accès et informations des représentants

                                    </p>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE RESPONSIVE --}}
                        <div class="overflow-x-auto">

                            <table class="min-w-[1650px] w-full">

                                <thead class="bg-slate-950 border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Parent
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Téléphone
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Profession
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Enfant(s)
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Autres Classes
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

                                    @foreach(range(1,10) as $parent)

                                    <tr class="hover:bg-slate-800/40 transition-all">

                                        {{-- PROFILE --}}
                                        <td class="px-6 py-5">

                                            <div class="flex items-center gap-4">

                                                <div class="w-14 h-14 rounded-2xl
                                                            bg-slate-800 shrink-0">
                                                </div>

                                                <div class="min-w-0">

                                                    <h3 class="font-medium truncate">

                                                        Mme Ahouandjinou Claire

                                                    </h3>

                                                    <p class="mt-1 text-sm text-slate-400 truncate">

                                                        parent@email.com

                                                    </p>

                                                </div>

                                            </div>

                                        </td>

                                        {{-- PHONE --}}
                                        <td class="px-4 py-5 text-center whitespace-nowrap">

                                            +229 01 00 00 00

                                        </td>

                                        {{-- JOB --}}
                                        <td class="px-4 py-5 text-center">

                                            Commerçante

                                        </td>

                                        {{-- CHILDREN --}}
                                        <td class="px-4 py-5">

                                            <div class="space-y-2">

                                                <div class="px-3 py-2 rounded-xl
                                                            bg-slate-950 text-sm">

                                                    KOUASSI Marc — Tle F2-1

                                                </div>

                                                <div class="px-3 py-2 rounded-xl
                                                            bg-slate-950 text-sm">

                                                    KOUASSI Sarah — 1ère G2

                                                </div>

                                            </div>

                                        </td>

                                        {{-- OTHER CLASSES --}}
                                        <td class="px-4 py-5 text-center">

                                            <div class="flex flex-wrap
                                                        justify-center gap-2">

                                                <span class="px-3 py-1 rounded-full
                                                             bg-indigo-500/10
                                                             text-indigo-400 text-xs">

                                                    1ère G2

                                                </span>

                                                <span class="px-3 py-1 rounded-full
                                                             bg-sky-500/10
                                                             text-sky-400 text-xs">

                                                    2nde C

                                                </span>

                                            </div>

                                        </td>

                                        {{-- LAST LOGIN --}}
                                        <td class="px-4 py-5 text-center whitespace-nowrap">

                                            Aujourd’hui 08:42

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

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-slate-800
                                                               hover:bg-rose-500
                                                               transition-all text-sm">

                                                    Supprimer

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

                                    Affichage de 1 à 10 sur 186 parents

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

                    {{-- QUICK STATS --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Activité des Parents

                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach([
                                ['Connexions actives', '82%', 'bg-emerald-500'],
                                ['Bulletins ouverts', '71%', 'bg-indigo-500'],
                                ['Notifications lues', '64%', 'bg-sky-500'],
                                ['Accès bloqués', '9%', 'bg-rose-500']
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

                    {{-- RECENT ACTIVITIES --}}
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
                                                shrink-0
                                                flex items-center justify-center
                                                text-indigo-400">

                                        ✓

                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="font-medium text-sm">

                                            Bulletin envoyé

                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">

                                            Bulletin trimestriel transmis au parent

                                        </p>

                                        <p class="mt-2 text-xs text-slate-500">

                                            Il y a 45 min

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
                                'Présences',
                                'Bulletins',
                                'Paiements',
                                'Notes',
                                'Messages',
                                'Archives'
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
