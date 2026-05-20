<div class="w-full overflow-x-hidden p-2">

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

                            Élèves

                        </h1>

                        <span class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400
                                     text-xs">

                            3 482 Apprenants

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm sm:text-base">

                        Gestion complète des apprenants et performances académiques

                    </p>

                </div>

                {{-- ACTIONS --}}
                <div class="flex justify-center flex-wrap gap-3 text-gray-950">

                    <button class="p-2 px-3 rounded-2xl
                                    bg-gray-500 hover:bg-gray-600">

                        Ajouter un appr.

                    </button>
                    
                    <button class="p-2 px-3 rounded-2xl
                                    bg-blue-500 hover:bg-blue-600">

                        Imprimer PDF

                    </button>

                    <button class="p-2 px-3 rounded-2xl
                                    bg-emerald-500 hover:bg-emerald-600">

                        Emprimer Excel

                    </button>

                    <button class="p-2 px-3 rounded-2xl
                                    bg-amber-500 hover:bg-amber-600">

                        Imprimer Excel et PDF

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
                    ['Total Élèves', '3482', 'text-indigo-400'],
                    ['Présents', '3310', 'text-emerald-400'],
                    ['Filles', '1624', 'text-pink-400'],
                    ['Moyenne Générale', '13.8', 'text-amber-400']
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
                            placeholder="Rechercher un élève..."
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

                    {{-- FILTERS GRID --}}
                    <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-6
                                gap-3">

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes classes</option>
                            <option>Terminale F2-1</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes séries</option>
                            <option>F2</option>
                            <option>G1</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Sexe</option>
                            <option>Masculin</option>
                            <option>Féminin</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Statut</option>
                            <option>Actif</option>
                            <option>Suspendu</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Semestre</option>
                            <option>Semestre 1</option>
                            <option>Semestre 2</option>

                        </select>

                        <button class="h-11 rounded-2xl
                                       bg-indigo-500
                                       hover:bg-indigo-600
                                       transition-all text-sm">

                            Filtrer

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

                    {{-- STUDENTS TABLE --}}
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

                                        Liste des Élèves

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Gestion académique et administrative

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

                            <table class="min-w-[1400px] w-full">

                                <thead class="bg-slate-950 border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Élève
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Classe
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Série
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moyenne
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Rang
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Présence
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Probabilité
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

                                    @foreach(range(1,12) as $student)

                                    <tr class="hover:bg-slate-800/40 transition-all">

                                        {{-- PROFILE --}}
                                        <td class="px-6 py-5">

                                            <div class="flex items-center gap-4">

                                                <div class="w-14 h-14 rounded-2xl
                                                            bg-slate-800
                                                            shrink-0">
                                                </div>

                                                <div class="min-w-0">

                                                    <h3 class="font-medium truncate">

                                                        Kouassi Vincent

                                                    </h3>

                                                    <p class="mt-1 text-sm text-slate-400 truncate">

                                                        MAT-2026-00124

                                                    </p>

                                                </div>

                                            </div>

                                        </td>

                                        {{-- CLASS --}}
                                        <td class="px-4 py-5 text-center whitespace-nowrap">

                                            Terminale F2-1

                                        </td>

                                        {{-- SERIES --}}
                                        <td class="px-4 py-5 text-center">

                                            F2

                                        </td>

                                        {{-- AVERAGE --}}
                                        <td class="px-4 py-5 text-center">

                                            <span class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                15.24

                                            </span>

                                        </td>

                                        {{-- RANK --}}
                                        <td class="px-4 py-5 text-center font-semibold">

                                            3e

                                        </td>

                                        {{-- PRESENCE --}}
                                        <td class="px-4 py-5 text-center">

                                            <span class="text-emerald-400">
                                                96%
                                            </span>

                                        </td>

                                        {{-- SUCCESS --}}
                                        <td class="px-4 py-5 text-center">

                                            <span class="text-indigo-400">
                                                92%
                                            </span>

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

                                            <div class="flex items-center justify-end gap-2">

                                                <button class="w-10 h-10 rounded-xl
                                                               bg-slate-800
                                                               hover:bg-indigo-500
                                                               transition-all">

                                                    👁

                                                </button>

                                                <button class="w-10 h-10 rounded-xl
                                                               bg-slate-800
                                                               hover:bg-emerald-500
                                                               transition-all">

                                                    ✏

                                                </button>

                                                <button class="w-10 h-10 rounded-xl
                                                               bg-slate-800
                                                               hover:bg-amber-500
                                                               transition-all">

                                                    📊

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

                                    Affichage de 1 à 12 sur 3482 élèves

                                </p>

                                <div class="flex items-center gap-2">

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

                    {{-- SUCCESS STATS --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Répartition Performances

                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach([
                                ['Excellent', '18%', 'bg-emerald-500'],
                                ['Bien', '34%', 'bg-indigo-500'],
                                ['Assez Bien', '27%', 'bg-amber-500'],
                                ['Faible', '21%', 'bg-rose-500']
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

                                            Nouvelle note publiée

                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">

                                            Notes ajoutées pour Terminale F2-1

                                        </p>

                                        <p class="mt-2 text-xs text-slate-500">

                                            Il y a 1 heure

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
                                'Notes',
                                'Classes',
                                'Parents',
                                'Statistiques',
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