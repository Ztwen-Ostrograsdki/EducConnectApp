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

                <button
                    class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-indigo-500 hover:bg-indigo-600
                               transition-all duration-300
                               text-sm sm:text-base">

                    Ajouter Cours

                </button>

                <button
                    class="w-full sm:w-auto
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

                        <input type="text" placeholder="Rechercher un cours ou un enseignant..."
                            class="w-full h-12
                                   rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   outline-none
                                   focus:border-indigo-500
                                   transition-all">

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
                    <button
                        class="h-12 px-5 rounded-2xl
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
    <section class="w-full">

        <div class="flex justify-end flex-wrap gap-3 text-gray-950 p-2">

            <button class="px-3 py-2 rounded-2xl
                                    bg-red-500 hover:bg-red-600">

                Vider les emplois

            </button>

            <button class="px-3 py-2 rounded-2xl
                                    bg-blue-500 hover:bg-blue-600">

                Imprimer PDF

            </button>

            <button class="px-3 py-2 rounded-2xl
                                    bg-emerald-500 hover:bg-emerald-600">

                Emprimer Excel

            </button>

            <button class="px-3 py-2 rounded-2xl
                                    bg-amber-500 hover:bg-amber-600">

                Imprimer Excel et PDF

            </button>

        </div>

        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-950 border-b border-slate-800 truncate">

                        <tr>

                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                Horaires
                            </th>

                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                Lundi
                            </th>

                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                Mardi
                            </th>

                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                Mercredi
                            </th>

                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                Jeudi
                            </th>

                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                Vendredi
                            </th>
                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                Samedi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-800">

                        @foreach (['08:00 - 10:00', '10:00 - 12:00', '13:00 - 15:00', '15:00 - 17:00'] as $time)
                            <tr class="hover:bg-slate-800/40 transition-all">

                                {{-- TIME --}}
                                <td class="px-6 py-6 font-medium text-slate-300">
                                    {{ $time }}
                                </td>

                                @foreach (range(1, 6) as $day)
                                    {{-- COURSE --}}
                                    <td class="px-4 py-5">

                                        <div
                                            class="rounded-2xl
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

                                            <div class="mt-4 flex items-center justify-between gap-3 truncate">

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

</div>

