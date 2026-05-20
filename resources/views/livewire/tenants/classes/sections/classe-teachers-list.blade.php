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
                        Enseignants de la Classe
                    </h1>

                    <span class="px-3 py-1 rounded-full
                                 bg-indigo-500/10
                                 border border-indigo-500/20
                                 text-indigo-400 text-xs shrink-0">

                        8 enseignants

                    </span>

                </div>

                <p class="mt-2 text-slate-400 text-sm sm:text-base">

                    Gestion des professeurs, matières et statistiques pédagogiques.

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

                    Ajouter Enseignant

                </button>

                <button
                    class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-slate-800
                               border border-slate-700
                               hover:bg-slate-700
                               transition-all duration-300
                               text-sm sm:text-base">

                    Exporter

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
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">

                <p class="text-sm text-slate-400 truncate">
                    Enseignants
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                    8
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">

                <p class="text-sm text-slate-400 truncate">
                    Présence Moyenne
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                    94%
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">

                <p class="text-sm text-slate-400 truncate">
                    Notes Publiées
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                    1,240
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">

                <p class="text-sm text-slate-400 truncate">
                    Matières
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                    12
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

                        <input type="text" placeholder="Rechercher un enseignant..."
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
                            xl:flex
                            gap-3">

                    <select class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Toutes Matières</option>
                        <option>Mathématiques</option>
                        <option>Physique</option>
                        <option>Électricité</option>

                    </select>

                    <select class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Toutes Présences</option>
                        <option>Excellent</option>
                        <option>Faible</option>

                    </select>

                </div>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- DESKTOP TABLE --}}
    {{-- ===================================================== --}}
    <section class="w-full">

        <div class="flex justify-end flex-wrap gap-3 text-gray-950 p-2">

            <button class="px-3 py-2 rounded-2xl
                                    bg-red-500 hover:bg-red-600">

                Verrouiller notes

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

                <table class="min-w-full">

                    <thead class="bg-slate-950 border-b border-slate-800 truncate">

                        <tr>

                            <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                Enseignant
                            </th>

                            <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                Matière
                            </th>

                            <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                Présence
                            </th>

                            <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                Notes
                            </th>

                            <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                Autres Classes
                            </th>

                            <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                Appréciation
                            </th>

                            <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-800">

                        @foreach (range(1, 8) as $i)
                            <tr class="hover:bg-slate-800/40 transition-all">

                                {{-- PROF --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-4 min-w-0">

                                        <div class="w-14 h-14 rounded-2xl bg-slate-800 shrink-0">
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ route('tenant.teacher.profil', ['teacher_uuid' => rand(272252525, 7727277272772)]) }}">
                                                <h3 class="font-semibold truncate">
                                                    M. HOUNDEKINDO {{ $i }}
                                                </h3>
                                                <p class="text-sm text-slate-400 truncate">
                                                    Enseignant Permanent
                                                </p>
                                            </a>
                                        </div>

                                    </div>

                                </td>

                                {{-- SUBJECT --}}
                                <td class="px-6 py-5 truncate">

                                    <span
                                        class="px-3 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400
                                             text-sm">

                                        Électricité

                                    </span>

                                </td>

                                {{-- PRESENCE --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-3 min-w-0 truncate">

                                        <div class="w-28 h-2 rounded-full bg-slate-800 overflow-hidden">

                                            <div class="h-full w-[94%] bg-emerald-500 rounded-full">
                                            </div>

                                        </div>

                                        <span class="text-sm shrink-0">
                                            94%
                                        </span>

                                    </div>

                                </td>

                                {{-- NOTES --}}
                                <td class="px-6 py-5 text-sm truncate">
                                    148 notes
                                </td>

                                {{-- CLASSES --}}
                                <td class="px-6 py-5">

                                    <div class="flex flex-wrap gap-2 truncate">

                                        <span class="px-2 py-1 rounded-xl bg-slate-800 text-xs">
                                            Tle F2
                                        </span>

                                        <span class="px-2 py-1 rounded-xl bg-slate-800 text-xs">
                                            2nde F3
                                        </span>

                                    </div>

                                </td>

                                {{-- APPRECIATION --}}
                                <td class="px-6 py-5">

                                    <div class="max-w-[220px] ">

                                        <p class="text-sm text-slate-300 line-clamp-2">

                                            Excellent et présence régulière.

                                        </p>

                                    </div>

                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center justify-end gap-2 truncate">

                                        <button
                                            class="py-2 px-2.5 cursor-pointer rounded-xl
                                                               bg-indigo-800
                                                               hover:bg-indigo-500
                                                               transition-all">

                                            Profil

                                        </button>

                                        <button
                                            class="py-2 px-2.5 cursor-pointer rounded-xl
                                                               bg-emerald-800
                                                               hover:bg-emerald-500
                                                               transition-all">

                                            Bloquer

                                        </button>

                                        <button
                                            class="py-2 px-2.5 cursor-pointer rounded-xl
                                                               bg-red-800
                                                               hover:bg-red-500
                                                               transition-all">

                                            Retirer

                                        </button>

                                    </div>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</div>

