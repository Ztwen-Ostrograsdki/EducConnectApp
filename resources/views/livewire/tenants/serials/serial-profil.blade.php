<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1850px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        p-5 sm:p-6 lg:p-8">

                <div class="flex flex-col
                            2xl:flex-row
                            2xl:items-center
                            2xl:justify-between
                            gap-6">

                    {{-- LEFT --}}
                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-2xl sm:text-3xl font-bold">

                                Série F2

                            </h1>

                            <span class="px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         text-indigo-400 text-xs">

                                Construction Mécanique

                            </span>

                        </div>

                        <p class="mt-3 text-slate-400 max-w-3xl">

                            Vue globale de la série F2 avec
                            statistiques, performances,
                            classes, enseignants et matières.

                        </p>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-wrap gap-3">

                        <button class="h-11 px-5 rounded-2xl
                                       bg-indigo-500 hover:bg-indigo-600">

                            Ajouter Classe

                        </button>

                        <button class="h-11 px-5 rounded-2xl
                                       bg-slate-800 hover:bg-slate-700">

                            Exporter

                        </button>

                    </div>

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
                    ['Classes', '8', 'text-indigo-400'],
                    ['Élèves', '386', 'text-emerald-400'],
                    ['Réussite', '87%', 'text-sky-400'],
                    ['Moyenne Générale', '13.84', 'text-amber-400']
                ] as $kpi)

                <div class="rounded-3xl
                            bg-slate-900
                            border border-slate-800
                            p-5">

                    <p class="text-sm text-slate-400">
                        {{ $kpi[0] }}
                    </p>

                    <h2 class="mt-3 text-3xl font-bold {{ $kpi[2] }}">
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
                        bg-slate-900
                        border border-slate-800
                        p-5">

                <div class="grid
                            grid-cols-1
                            md:grid-cols-2
                            xl:grid-cols-5
                            gap-3">

                    <select class="h-11 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800 px-4">

                        <option>Toutes les classes</option>
                        <option>Terminale F2-1</option>
                        <option>Terminale F2-2</option>

                    </select>

                    <select class="h-11 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800 px-4">

                        <option>Toutes les matières</option>

                    </select>

                    <select class="h-11 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800 px-4">

                        <option>Performance</option>

                    </select>

                    <button class="h-11 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-600">

                        Filtrer

                    </button>

                    <button class="h-11 rounded-2xl
                                   bg-slate-800 hover:bg-slate-700">

                        Réinitialiser

                    </button>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- MAIN GRID --}}
        {{-- ===================================================== --}}
        <section>

            <div class="grid
                        grid-cols-1
                        2xl:grid-cols-[minmax(0,1fr)_420px]
                        gap-6">

                {{-- ===================================================== --}}
                {{-- LEFT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">

                    {{-- ===================================================== --}}
                    {{-- CLASSES --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                overflow-hidden">

                        <div class="p-5 border-b border-slate-800">

                            <h2 class="text-xl font-semibold">

                                Classes de la Série

                            </h2>

                        </div>

                        <div class="overflow-x-auto">

                            <table class="min-w-[1600px] w-full">

                                <thead class="bg-slate-950 border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Classe
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Prof Principal
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Élèves
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Garçons
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Filles
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moyenne
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Réussite
                                        </th>

                                        <th class="px-6 py-4 text-right text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach(range(1,8) as $class)

                                    <tr class="hover:bg-slate-800/40">

                                        <td class="px-6 py-5 font-medium">

                                            Terminale F2-{{ $class }}

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            M. AGOSSOU

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            48

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            35

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            13

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                   text-indigo-400 font-semibold">

                                            13.84

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                   text-emerald-400 font-semibold">

                                            88%

                                        </td>

                                        <td class="px-6 py-5">

                                            <div class="flex justify-end gap-2">

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-indigo-500/10
                                                               text-indigo-400">

                                                    Profil

                                                </button>

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-emerald-500/10
                                                               text-emerald-400">

                                                    Notes

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- SUBJECTS --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                overflow-hidden">

                        <div class="p-5 border-b border-slate-800">

                            <h2 class="text-xl font-semibold">

                                Matières & Coefficients

                            </h2>

                        </div>

                        <div class="overflow-x-auto">

                            <table class="min-w-[1400px] w-full">

                                <thead class="bg-slate-950 border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Matière
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Coef
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Enseignants
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moyenne
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Réussite
                                        </th>

                                        <th class="px-6 py-4 text-right text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach([
                                        ['Mathématiques',5],
                                        ['Physique',4],
                                        ['Construction',6],
                                        ['Informatique',3],
                                        ['Français',2],
                                    ] as $subject)

                                    <tr class="hover:bg-slate-800/40">

                                        <td class="px-6 py-5 font-medium">

                                            {{ $subject[0] }}

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            {{ $subject[1] }}

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            6

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                   text-indigo-400">

                                            13.74

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                   text-emerald-400">

                                            86%

                                        </td>

                                        <td class="px-6 py-5">

                                            <div class="flex justify-end gap-2">

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-indigo-500/10
                                                               text-indigo-400">

                                                    Profil

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- TEACHERS --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <div class="flex items-center justify-between">

                            <h2 class="text-xl font-semibold">

                                Enseignants de la Série

                            </h2>

                            <span class="text-sm text-slate-400">

                                24 Enseignants

                            </span>

                        </div>

                        <div class="mt-5 grid
                                    grid-cols-1
                                    sm:grid-cols-2
                                    xl:grid-cols-3
                                    gap-4">

                            @foreach(range(1,9) as $teacher)

                            <div class="rounded-2xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-4">

                                <div class="flex items-center gap-4">

                                    <div class="w-14 h-14 rounded-2xl
                                                bg-slate-800">
                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="font-medium truncate">

                                            M. AGBODJI

                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">

                                            Construction Mécanique

                                        </p>

                                    </div>

                                </div>

                                <div class="mt-4 flex items-center justify-between">

                                    <span class="text-sm text-slate-400">

                                        Classes

                                    </span>

                                    <span class="font-semibold">

                                        4

                                    </span>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RIGHT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6">

                    {{-- BEST STUDENTS --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Meilleurs Élèves

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach([
                                ['Meilleure Fille','Sarah KOUASSI','17.82'],
                                ['Meilleur Garçon','Marc AGBODO','17.11']
                            ] as $student)

                            <div class="rounded-2xl
                                        bg-slate-950
                                        p-4">

                                <p class="text-xs text-slate-400">

                                    {{ $student[0] }}

                                </p>

                                <div class="mt-2 flex items-center justify-between">

                                    <h3 class="font-semibold">

                                        {{ $student[1] }}

                                    </h3>

                                    <span class="text-emerald-400 font-bold">

                                        {{ $student[2] }}

                                    </span>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- WEAK STUDENTS --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Élèves en Difficulté

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach(range(1,5) as $weak)

                            <div class="rounded-2xl
                                        bg-slate-950
                                        p-4">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <h3 class="font-medium">

                                            KOFFI Junior

                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">

                                            Terminale F2-2

                                        </p>

                                    </div>

                                    <span class="text-rose-400 font-bold">

                                        08.42

                                    </span>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- GLOBAL PERFORMANCE --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Performances Globales

                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach([
                                ['Mathématiques','84%','bg-indigo-500'],
                                ['Construction','91%','bg-emerald-500'],
                                ['Physique','76%','bg-sky-500'],
                                ['Informatique','95%','bg-amber-500']
                            ] as $perf)

                            <div>

                                <div class="flex justify-between">

                                    <span class="text-sm text-slate-300">

                                        {{ $perf[0] }}

                                    </span>

                                    <span class="text-sm font-semibold">

                                        {{ $perf[1] }}

                                    </span>

                                </div>

                                <div class="mt-2 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                                    <div class="h-full rounded-full {{ $perf[2] }}"
                                         style="width: {{ $perf[1] }}">
                                    </div>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>