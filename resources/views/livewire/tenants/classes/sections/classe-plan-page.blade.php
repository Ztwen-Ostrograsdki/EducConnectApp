<div class="w-full max-w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            {{-- LEFT --}}
            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-2xl sm:text-3xl font-bold break-words">
                        Emploi du Temps
                    </h1>

                    <span class="px-3 py-1 rounded-full
                                 bg-indigo-500/10
                                 border border-indigo-500/20
                                 text-indigo-400 text-xs shrink-0">

                        Terminale F2-1

                    </span>

                </div>

                <p class="mt-2 text-slate-400 text-sm sm:text-base">

                    Gestion des cours, horaires et salles de la classe.

                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <button class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-indigo-500 hover:bg-indigo-600
                               transition-all duration-300
                               text-sm sm:text-base">

                    Ajouter Cours

                </button>

                <button class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-slate-800
                               border border-slate-700
                               hover:bg-slate-700
                               transition-all duration-300
                               text-sm sm:text-base">

                    Exporter PDF

                </button>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- KPI --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div class="grid
                    grid-cols-1
                    sm:grid-cols-2
                    xl:grid-cols-4
                    gap-4 sm:gap-6">

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Heures / Semaine
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    38h
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Matières
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    12
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Enseignants
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    8
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Salles
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    5
                </h2>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- TOOLBAR --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">

            <div class="flex flex-col xl:flex-row gap-4">

                {{-- SEARCH --}}
                <div class="flex-1 min-w-0">

                    <div class="relative">

                        <input
                            type="text"
                            placeholder="Rechercher un cours ou un enseignant..."
                            class="w-full h-12
                                   rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   outline-none
                                   focus:border-indigo-500
                                   transition-all"
                        >

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">

                            🔍

                        </div>

                    </div>

                </div>

                {{-- FILTERS --}}
                <div class="grid
                            grid-cols-1
                            sm:grid-cols-2
                            lg:grid-cols-3
                            gap-3">

                    {{-- SEMESTER --}}
                    <select class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Semestre 1</option>
                        <option>Semestre 2</option>

                    </select>

                    {{-- DAY --}}
                    <select class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Toute la semaine</option>
                        <option>Lundi</option>
                        <option>Mardi</option>
                        <option>Mercredi</option>
                        <option>Jeudi</option>
                        <option>Vendredi</option>

                    </select>

                    {{-- RESET --}}
                    <button class="h-12 px-5 rounded-2xl
                                   bg-slate-800
                                   border border-slate-700
                                   hover:bg-slate-700
                                   transition-all
                                   text-sm">

                        Réinitialiser

                    </button>

                </div>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- DESKTOP TIMETABLE --}}
    {{-- ===================================================== --}}
    <section class="hidden xl:block mb-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-950 border-b border-slate-800">

                        <tr>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Horaires
                            </th>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Lundi
                            </th>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Mardi
                            </th>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Mercredi
                            </th>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Jeudi
                            </th>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Vendredi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-800">

                        @foreach([
                            '08:00 - 10:00',
                            '10:00 - 12:00',
                            '13:00 - 15:00',
                            '15:00 - 17:00'
                        ] as $time)

                        <tr class="hover:bg-slate-800/40 transition-all">

                            {{-- TIME --}}
                            <td class="px-6 py-6 font-medium text-slate-300">
                                {{ $time }}
                            </td>

                            @foreach(range(1,5) as $day)

                            {{-- COURSE --}}
                            <td class="px-4 py-5">

                                <div class="rounded-2xl
                                            border border-indigo-500/20
                                            bg-indigo-500/10
                                            p-4 min-w-[180px]">

                                    <div class="flex items-start justify-between gap-3">

                                        <div class="min-w-0">

                                            <h3 class="font-semibold truncate">
                                                Mathématiques
                                            </h3>

                                            <p class="mt-1 text-sm text-indigo-300 truncate">
                                                M. HOUNDEKINDO
                                            </p>

                                        </div>

                                        <div class="w-3 h-3 rounded-full
                                                    bg-indigo-400 shrink-0">
                                        </div>

                                    </div>

                                    <div class="mt-4 flex items-center justify-between gap-3">

                                        <span class="px-2 py-1 rounded-xl
                                                     bg-slate-950/50
                                                     text-xs text-slate-300">

                                            Salle B12

                                        </span>

                                        <span class="text-xs text-slate-400">

                                            2h

                                        </span>

                                    </div>

                                </div>

                            </td>

                            @endforeach

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- MOBILE / TABLET VIEW --}}
    {{-- ===================================================== --}}
    <section class="xl:hidden">

        <div class="space-y-5">

            @foreach([
                'Lundi',
                'Mardi',
                'Mercredi',
                'Jeudi',
                'Vendredi'
            ] as $day)

            {{-- DAY --}}
            <div class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        overflow-hidden">

                {{-- HEADER --}}
                <div class="border-b border-slate-800
                            px-5 py-4">

                    <div class="flex items-center justify-between gap-4">

                        <h2 class="text-lg font-semibold">
                            {{ $day }}
                        </h2>

                        <span class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400 text-xs">

                            4 cours

                        </span>

                    </div>

                </div>

                {{-- COURSES --}}
                <div class="p-5 space-y-4">

                    @foreach(range(1,4) as $course)

                    <div class="rounded-2xl
                                border border-slate-800
                                bg-slate-950
                                p-4">

                        {{-- TOP --}}
                        <div class="flex items-start justify-between gap-3">

                            <div class="min-w-0">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="font-semibold truncate">
                                        Mathématiques
                                    </h3>

                                    <span class="w-2 h-2 rounded-full bg-indigo-400">
                                    </span>

                                </div>

                                <p class="mt-1 text-sm text-slate-400 truncate">
                                    M. HOUNDEKINDO
                                </p>

                            </div>

                            <span class="px-3 py-1 rounded-xl
                                         bg-indigo-500/10
                                         text-indigo-400
                                         text-xs shrink-0">

                                08:00

                            </span>

                        </div>

                        {{-- DETAILS --}}
                        <div class="mt-5 grid grid-cols-2 gap-3">

                            <div class="rounded-xl bg-slate-900 p-3">

                                <p class="text-xs text-slate-500">
                                    Salle
                                </p>

                                <h4 class="mt-1 font-medium">
                                    B12
                                </h4>

                            </div>

                            <div class="rounded-xl bg-slate-900 p-3">

                                <p class="text-xs text-slate-500">
                                    Durée
                                </p>

                                <h4 class="mt-1 font-medium">
                                    2h
                                </h4>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="mt-5 grid grid-cols-2 gap-3">

                            <button class="h-11 rounded-2xl
                                           bg-slate-800
                                           hover:bg-indigo-500
                                           transition-all
                                           text-sm">

                                Modifier

                            </button>

                            <button class="h-11 rounded-2xl
                                           bg-slate-800
                                           hover:bg-rose-500
                                           transition-all
                                           text-sm">

                                Supprimer

                            </button>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

            @endforeach

        </div>

    </section>

</div>