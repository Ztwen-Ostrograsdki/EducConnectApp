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
                        Gestion des Présences
                    </h1>

                    <span class="px-3 py-1 rounded-full
                                 bg-emerald-500/10
                                 border border-emerald-500/20
                                 text-emerald-400 text-xs shrink-0">

                        42 apprenants

                    </span>

                </div>

                <p class="mt-2 text-slate-400 text-sm sm:text-base">

                    Suivi des présences, absences et retards des apprenants.

                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <button class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-emerald-500 hover:bg-emerald-600
                               transition-all duration-300
                               text-sm sm:text-base">

                    Marquer Présences

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

            {{-- PRESENT --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Présents
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold text-emerald-400">
                    36
                </h2>

            </div>

            {{-- ABSENT --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Absents
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold text-rose-400">
                    4
                </h2>

            </div>

            {{-- RETARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Retards
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold text-amber-400">
                    2
                </h2>

            </div>

            {{-- RATE --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Taux de Présence
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    92%
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
                            placeholder="Rechercher un apprenant..."
                            class="w-full h-12
                                   rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   outline-none
                                   focus:border-emerald-500
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
                            lg:grid-cols-4
                            gap-3">

                    {{-- DATE --}}
                    <input
                        type="date"
                        class="h-12 px-4 rounded-2xl
                               bg-slate-950
                               border border-slate-800
                               text-sm"
                    >

                    {{-- SEMESTER --}}
                    <select class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Semestre 1</option>
                        <option>Semestre 2</option>

                    </select>

                    {{-- STATUS --}}
                    <select class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Tous Statuts</option>
                        <option>Présent</option>
                        <option>Absent</option>
                        <option>Retard</option>

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
    {{-- DESKTOP TABLE --}}
    {{-- ===================================================== --}}
    <section class="hidden 2xl:block mb-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-950 border-b border-slate-800">

                        <tr>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Apprenant
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Date
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Heure
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Statut
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Présence
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Retards
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Absences
                            </th>

                            <th class="px-6 py-4 text-right text-sm text-slate-400">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-800">

                        @foreach(range(1,15) as $i)

                        <tr class="hover:bg-slate-800/40 transition-all">

                            {{-- STUDENT --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-4 min-w-0">

                                    <div class="w-12 h-12 rounded-2xl bg-slate-800 shrink-0">
                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="font-medium truncate">
                                            Kouassi Vincent {{$i}}
                                        </h3>

                                        <p class="text-sm text-slate-400 truncate">
                                            MAT-2025-{{$i}}
                                        </p>

                                    </div>

                                </div>

                            </td>

                            {{-- DATE --}}
                            <td class="px-4 py-5 text-center">
                                12/05/2026
                            </td>

                            {{-- TIME --}}
                            <td class="px-4 py-5 text-center">
                                08:00
                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-5 text-center">

                                <span class="px-3 py-1 rounded-full
                                             bg-emerald-500/10
                                             text-emerald-400 text-sm">

                                    Présent

                                </span>

                            </td>

                            {{-- RATE --}}
                            <td class="px-4 py-5 text-center font-semibold">
                                96%
                            </td>

                            {{-- LATE --}}
                            <td class="px-4 py-5 text-center">
                                1
                            </td>

                            {{-- ABSENCES --}}
                            <td class="px-4 py-5 text-center">
                                0
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
    {{-- MOBILE / TABLET CARDS --}}
    {{-- ===================================================== --}}
    <section class="2xl:hidden">

        <div class="grid
                    grid-cols-1
                    lg:grid-cols-2
                    gap-4 sm:gap-6">

            @foreach(range(1,15) as $i)

            <div class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        overflow-hidden">

                <div class="p-5">

                    {{-- TOP --}}
                    <div class="flex items-start gap-4 min-w-0">

                        <div class="w-14 h-14 rounded-2xl bg-slate-800 shrink-0">
                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex flex-wrap items-center gap-2">

                                <h3 class="font-semibold truncate">
                                    Kouassi Vincent {{$i}}
                                </h3>

                                <span class="px-2 py-1 rounded-full
                                             bg-emerald-500/10
                                             text-emerald-400
                                             text-xs">

                                    Présent

                                </span>

                            </div>

                            <p class="mt-2 text-sm text-slate-400 truncate">
                                MAT-2025-{{$i}}
                            </p>

                        </div>

                    </div>

                    {{-- STATS --}}
                    <div class="mt-6 grid grid-cols-3 gap-3">

                        <div class="rounded-2xl bg-slate-950 p-4 text-center">

                            <p class="text-xs text-slate-500">
                                Présence
                            </p>

                            <h4 class="mt-2 text-lg font-bold text-emerald-400">
                                96%
                            </h4>

                        </div>

                        <div class="rounded-2xl bg-slate-950 p-4 text-center">

                            <p class="text-xs text-slate-500">
                                Retards
                            </p>

                            <h4 class="mt-2 text-lg font-bold text-amber-400">
                                1
                            </h4>

                        </div>

                        <div class="rounded-2xl bg-slate-950 p-4 text-center">

                            <p class="text-xs text-slate-500">
                                Absences
                            </p>

                            <h4 class="mt-2 text-lg font-bold text-rose-400">
                                0
                            </h4>

                        </div>

                    </div>

                    {{-- DETAILS --}}
                    <div class="mt-5 grid grid-cols-2 gap-3">

                        <div class="rounded-xl bg-slate-950 p-3">

                            <p class="text-xs text-slate-500">
                                Date
                            </p>

                            <h4 class="mt-1 font-medium">
                                12/05/2026
                            </h4>

                        </div>

                        <div class="rounded-xl bg-slate-950 p-3">

                            <p class="text-xs text-slate-500">
                                Heure
                            </p>

                            <h4 class="mt-1 font-medium">
                                08:00
                            </h4>

                        </div>

                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="border-t border-slate-800 p-4">

                    <div class="grid grid-cols-3 gap-3">

                        <button class="h-11 rounded-2xl
                                       bg-slate-800
                                       hover:bg-indigo-500
                                       transition-all
                                       text-sm">

                            Voir

                        </button>

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