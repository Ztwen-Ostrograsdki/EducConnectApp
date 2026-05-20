<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="relative overflow-hidden
                        rounded-[32px]
                        border border-slate-800
                        bg-slate-900">

                {{-- BG --}}
                <div class="absolute inset-0
                            bg-gradient-to-br
                            from-emerald-500/10
                            via-slate-900
                            to-slate-900">
                </div>

                <div class="relative p-5 sm:p-6 lg:p-8">

                    <div class="flex flex-col
                                xl:flex-row
                                xl:items-start
                                xl:justify-between
                                gap-8">

                        {{-- LEFT --}}
                        <div class="flex flex-col
                                    lg:flex-row
                                    gap-6
                                    min-w-0">

                            {{-- PHOTO --}}
                            <div class="flex justify-center lg:block">

                                <div class="w-32 h-32 sm:w-36 sm:h-36
                                            rounded-[30px]
                                            bg-slate-800
                                            border border-slate-700
                                            overflow-hidden
                                            shrink-0">
                                </div>

                            </div>

                            {{-- INFO --}}
                            <div class="min-w-0">

                                <div class="flex flex-wrap
                                            items-center
                                            gap-3">

                                    <h1 class="text-2xl sm:text-3xl font-bold">

                                        Gestion des Présences

                                    </h1>

                                    <span class="px-3 py-1 rounded-full
                                                 bg-emerald-500/10
                                                 text-emerald-400 text-xs">

                                        Session Active

                                    </span>

                                </div>

                                <p class="mt-2 text-slate-400">

                                    Gestion des absences,
                                    retards et présences
                                    des apprenants.

                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800
                                                border border-slate-700">

                                        5 Classes

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800
                                                border border-slate-700">

                                        184 Élèves

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800
                                                border border-slate-700">

                                        96% Présence

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-3">

                            <button class="h-11 px-5 rounded-2xl
                                           bg-emerald-500 hover:bg-emerald-600">

                                Valider Présence

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                Export PDF

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-sky-500 hover:bg-sky-600">

                                Historique

                            </button>

                        </div>

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
                        lg:grid-cols-4
                        2xl:grid-cols-6
                        gap-4">

                @foreach([
                    ['Présents','42','text-emerald-400'],
                    ['Absents','3','text-rose-400'],
                    ['Retards','2','text-amber-400'],
                    ['Permissions','1','text-sky-400'],
                    ['Effectif','48','text-indigo-400'],
                    ['Taux Présence','93%','text-violet-400']
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
                            xl:grid-cols-7
                            gap-4">

                    {{-- CLASSE --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Terminale F2-1</option>
                        <option>1ère F3-2</option>

                    </select>

                    {{-- MATIERE --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Mathématiques</option>
                        <option>Physique</option>

                    </select>

                    {{-- DATE --}}
                    <input type="date"
                           class="h-12 rounded-2xl
                                  bg-slate-950
                                  border border-slate-800
                                  px-4">

                    {{-- HORAIRE --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>07H - 09H</option>
                        <option>09H - 11H</option>
                        <option>11H - 13H</option>

                    </select>

                    {{-- TYPE --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Cours</option>
                        <option>TP</option>
                        <option>Composition</option>

                    </select>

                    {{-- MOTIF --}}
                    <input type="text"
                           placeholder="Motif général..."
                           class="h-12 rounded-2xl
                                  bg-slate-950
                                  border border-slate-800
                                  px-4">

                    {{-- BUTTON --}}
                    <button class="h-12 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-600">

                        Charger

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
                    {{-- TABLE --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-[32px]
                                bg-slate-900
                                border border-slate-800
                                overflow-hidden">

                        {{-- HEADER --}}
                        <div class="p-5 border-b border-slate-800">

                            <div class="flex flex-col
                                        xl:flex-row
                                        xl:items-center
                                        xl:justify-between
                                        gap-4">

                                <div>

                                    <h2 class="text-xl font-semibold">

                                        Feuille de Présence

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Gestion des présences
                                        et retards des apprenants.

                                    </p>

                                </div>

                                {{-- ACTIONS --}}
                                <div class="flex flex-wrap gap-2">

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-emerald-500 hover:bg-emerald-600">

                                        Tout Présent

                                    </button>

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-amber-500 hover:bg-amber-600">

                                        Retards

                                    </button>

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-indigo-500 hover:bg-indigo-600">

                                        Sauvegarder

                                    </button>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto">

                            <table class="min-w-[1800px] w-full">

                                <thead class="bg-slate-950
                                             border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Apprenant
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Sexe
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Présent
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Retard
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Absent
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Permission
                                        </th>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Motif
                                        </th>

                                        <th class="px-6 py-4 text-right text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach(range(1,18) as $student)

                                    <tr class="hover:bg-slate-800/40">

                                        {{-- STUDENT --}}
                                        <td class="px-6 py-5">

                                            <div class="flex items-center gap-4">

                                                <div class="w-14 h-14
                                                            rounded-2xl
                                                            bg-slate-800">
                                                </div>

                                                <div>

                                                    <h3 class="font-medium">

                                                        KOUASSI Sarah

                                                    </h3>

                                                    <p class="text-sm text-slate-400">

                                                        MAT-2026-001

                                                    </p>

                                                </div>

                                            </div>

                                        </td>

                                        {{-- SEX --}}
                                        <td class="px-4 py-5 text-center">

                                            F

                                        </td>

                                        {{-- PRESENT --}}
                                        <td class="px-4 py-5 text-center">

                                            <button class="h-10 px-4 rounded-xl
                                                           bg-emerald-500/20
                                                           text-emerald-400
                                                           border border-emerald-500/20">

                                                Présent

                                            </button>

                                        </td>

                                        {{-- RETARD --}}
                                        <td class="px-4 py-5 text-center">

                                            <button class="h-10 px-4 rounded-xl
                                                           bg-amber-500/20
                                                           text-amber-400
                                                           border border-amber-500/20">

                                                Retard

                                            </button>

                                        </td>

                                        {{-- ABSENT --}}
                                        <td class="px-4 py-5 text-center">

                                            <button class="h-10 px-4 rounded-xl
                                                           bg-rose-500/20
                                                           text-rose-400
                                                           border border-rose-500/20">

                                                Absent

                                            </button>

                                        </td>

                                        {{-- PERMISSION --}}
                                        <td class="px-4 py-5 text-center">

                                            <button class="h-10 px-4 rounded-xl
                                                           bg-sky-500/20
                                                           text-sky-400
                                                           border border-sky-500/20">

                                                Permission

                                            </button>

                                        </td>

                                        {{-- MOTIF --}}
                                        <td class="px-6 py-5">

                                            <input type="text"
                                                   placeholder="Motif..."
                                                   class="w-full min-w-[180px]
                                                          h-10 rounded-xl
                                                          bg-slate-950
                                                          border border-slate-800
                                                          px-4">
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="px-6 py-5">

                                            <div class="flex justify-end gap-2">

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-indigo-500/10
                                                               text-indigo-400">

                                                    Historique

                                                </button>

                                                <button class="h-10 px-4 rounded-xl
                                                               bg-slate-800">

                                                    Détails

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RIGHT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6">

                    {{-- ===================================================== --}}
                    {{-- STATS --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Statistiques Session

                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach([
                                ['Présents','42'],
                                ['Absents','3'],
                                ['Retards','2'],
                                ['Permissions','1'],
                                ['Taux Présence','93%']
                            ] as $item)

                            <div class="flex items-center justify-between">

                                <span class="text-slate-400">

                                    {{ $item[0] }}

                                </span>

                                <span class="font-semibold">

                                    {{ $item[1] }}

                                </span>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- ACTIONS --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-gradient-to-br
                                from-indigo-500/20
                                to-slate-900
                                border border-indigo-500/20
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Actions Rapides

                        </h2>

                        <div class="mt-5 space-y-3">

                            @foreach([
                                'Notifier Parents',
                                'Exporter PDF',
                                'Historique Présences',
                                'Voir Statistiques',
                                'Imprimer Feuille'
                            ] as $action)

                            <button class="w-full
                                           h-11
                                           rounded-2xl
                                           bg-slate-900/70
                                           hover:bg-slate-800
                                           border border-slate-800">

                                {{ $action }}

                            </button>

                            @endforeach

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- RECENT --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Dernières Sessions

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach(range(1,5) as $activity)

                            <div class="rounded-2xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-4">

                                <h3 class="font-medium">

                                    Terminale F2-1 —
                                    Mathématiques

                                </h3>

                                <p class="mt-1 text-sm text-slate-400">

                                    Aujourd’hui — 08H00 à 10H00

                                </p>

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>