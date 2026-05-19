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

                <button class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-indigo-500 hover:bg-indigo-600
                               transition-all duration-300
                               text-sm sm:text-base">

                    Ajouter Enseignant

                </button>

                <button class="w-full sm:w-auto
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

                        <input
                            type="text"
                            placeholder="Rechercher un enseignant..."
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
    <section class="hidden 2xl:block mb-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-slate-950 border-b border-slate-800">

                        <tr>

                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                Enseignant
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                Matière
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                Présence
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                Notes
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                Autres Classes
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                Appréciation
                            </th>

                            <th class="text-right px-6 py-4 text-sm font-medium text-slate-400">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-800">

                        @foreach(range(1,8) as $i)

                        <tr class="hover:bg-slate-800/40 transition-all">

                            {{-- PROF --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-4 min-w-0">

                                    <div class="w-14 h-14 rounded-2xl bg-slate-800 shrink-0">
                                    </div>

                                    <div class="min-w-0">
                                        <a href="{{route('tenant.teacher.profil', ['teacher_uuid' => rand(272252525, 7727277272772)])}}">
                                            <h3 class="font-semibold truncate">
                                                M. HOUNDEKINDO {{$i}}
                                            </h3>
                                            <p class="text-sm text-slate-400 truncate">
                                                Enseignant Permanent
                                            </p>
                                        </a>
                                    </div>

                                </div>

                            </td>

                            {{-- SUBJECT --}}
                            <td class="px-6 py-5">

                                <span class="px-3 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400
                                             text-sm">

                                    Électricité

                                </span>

                            </td>

                            {{-- PRESENCE --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-3 min-w-0">

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
                            <td class="px-6 py-5 text-sm">
                                148 notes
                            </td>

                            {{-- CLASSES --}}
                            <td class="px-6 py-5">

                                <div class="flex flex-wrap gap-2">

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

                                <div class="max-w-[220px]">

                                    <p class="text-sm text-slate-300 line-clamp-2">

                                        Excellent suivi pédagogique et présence régulière.

                                    </p>

                                </div>

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
                                                   hover:bg-rose-500
                                                   transition-all">

                                        🗑

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

    {{-- ===================================================== --}}
    {{-- MOBILE/TABLET CARDS --}}
    {{-- ===================================================== --}}
    <section class="2xl:hidden">

        <div class="grid
                    grid-cols-1
                    lg:grid-cols-2
                    gap-4 sm:gap-6">

            @foreach(range(1,8) as $i)

            <div class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        overflow-hidden">

                <div class="p-5">

                    {{-- TOP --}}
                    <div class="flex items-start gap-4 min-w-0">

                        <div class="w-16 h-16 rounded-2xl bg-slate-800 shrink-0">
                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex flex-wrap items-center gap-2">

                                <h3 class="font-semibold truncate">
                                    M. HOUNDEKINDO {{$i}}
                                </h3>

                                <span class="px-2 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400
                                             text-xs">

                                    Électricité

                                </span>

                            </div>

                            <p class="mt-2 text-sm text-slate-400 truncate">
                                Enseignant Permanent
                            </p>

                        </div>

                    </div>

                    {{-- STATS --}}
                    <div class="mt-6 grid grid-cols-2 gap-4">

                        <div class="rounded-2xl bg-slate-950 p-4">

                            <p class="text-xs text-slate-500">
                                Présence
                            </p>

                            <h4 class="mt-2 text-lg font-bold">
                                94%
                            </h4>

                        </div>

                        <div class="rounded-2xl bg-slate-950 p-4">

                            <p class="text-xs text-slate-500">
                                Notes
                            </p>

                            <h4 class="mt-2 text-lg font-bold">
                                148
                            </h4>

                        </div>

                    </div>

                    {{-- CLASSES --}}
                    <div class="mt-5">

                        <p class="text-xs text-slate-500 mb-3">
                            Autres Classes
                        </p>

                        <div class="flex flex-wrap gap-2">

                            <span class="px-2 py-1 rounded-xl bg-slate-800 text-xs">
                                Tle F2
                            </span>

                            <span class="px-2 py-1 rounded-xl bg-slate-800 text-xs">
                                2nde F3
                            </span>

                            <span class="px-2 py-1 rounded-xl bg-slate-800 text-xs">
                                1ère G1
                            </span>

                        </div>

                    </div>

                    {{-- APPRECIATION --}}
                    <div class="mt-5">

                        <p class="text-xs text-slate-500 mb-2">
                            Appréciation
                        </p>

                        <p class="text-sm text-slate-300 leading-relaxed">

                            Excellent suivi pédagogique et présence régulière.

                        </p>

                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="border-t border-slate-800 p-4">

                    <div class="grid grid-cols-3 gap-3">

                        <a href="{{route('tenant.teacher.profil', ['teacher_uuid' => 'f2-' . $i])}}" class="rounded-2xl
                                           bg-indigo-500
                                           hover:bg-indigo-600
                                           transition-all
                                           text-sm flex items-center justify-center">

                            Voir profil

                        </a>

                        <button class="h-11 rounded-2xl
                                       bg-slate-800
                                       hover:bg-emerald-500
                                       transition-all
                                       text-sm">

                            Modifier

                        </button>

                        <button class="h-11 rounded-2xl
                                       bg-slate-800
                                       hover:bg-rose-500
                                       transition-all
                                       text-sm">

                            Suppr.

                        </button>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </section>

</div>