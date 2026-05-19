<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
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
                            xl:flex-row
                            xl:items-center
                            xl:justify-between
                            gap-6">

                    <div>

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-2xl sm:text-3xl font-bold">

                                Tableau Statistique

                            </h1>

                            <span class="px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         text-indigo-400 text-xs">

                                Semestriel / Trimestriel

                            </span>

                        </div>

                        <p class="mt-3 text-slate-400 max-w-3xl">

                            Analyse statistique des performances
                            académiques selon les promotions,
                            filières, séries et classes.

                        </p>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-wrap gap-3">

                        <button class="h-11 px-5 rounded-2xl
                                       bg-rose-500 hover:bg-rose-600">

                            Export PDF

                        </button>

                        <button class="h-11 px-5 rounded-2xl
                                       bg-emerald-500 hover:bg-emerald-600">

                            Export Excel

                        </button>

                        <button class="h-11 px-5 rounded-2xl
                                       bg-indigo-500 hover:bg-indigo-600">

                            Imprimer

                        </button>

                    </div>

                </div>

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
                            xl:grid-cols-6
                            gap-4">

                    {{-- PERIOD --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Semestre 1</option>
                        <option>Semestre 2</option>
                        <option>Trimestre 1</option>
                        <option>Trimestre 2</option>
                        <option>Trimestre 3</option>
                        <option>Annuelle</option>

                    </select>

                    {{-- PROMOTION --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Toutes Promotions</option>
                        <option>6ème</option>
                        <option>5ème</option>
                        <option>4ème</option>
                        <option>3ème</option>
                        <option>2nde</option>
                        <option>1ère</option>
                        <option>Terminale</option>

                    </select>

                    {{-- FILIERE --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Toutes Filières</option>
                        <option>F1</option>
                        <option>F2</option>
                        <option>F3</option>
                        <option>F4</option>

                    </select>

                    {{-- SERIE --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Toutes Séries</option>
                        <option>F2-1</option>
                        <option>F2-2</option>

                    </select>

                    {{-- OPTIONS --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Inclure abandons</option>
                        <option>Exclure abandons</option>

                    </select>

                    {{-- BUTTON --}}
                    <button class="h-12 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-600
                                   font-medium">

                        Calculer

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
                        xl:grid-cols-5
                        gap-4">

                @foreach([
                    ['Classes','18','text-indigo-400'],
                    ['Effectif','1248','text-emerald-400'],
                    ['Admissibilité','84%','text-sky-400'],
                    ['Meilleure Moy.','17.82','text-amber-400'],
                    ['Plus Faible','03.11','text-rose-400'],
                ] as $card)

                <div class="rounded-3xl
                            bg-slate-900
                            border border-slate-800
                            p-5">

                    <p class="text-sm text-slate-400">

                        {{ $card[0] }}

                    </p>

                    <h2 class="mt-3 text-3xl font-bold {{ $card[2] }}">

                        {{ $card[1] }}

                    </h2>

                </div>

                @endforeach

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- MAIN TABLE --}}
        {{-- ===================================================== --}}
        <section>

            <div class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        overflow-hidden">

                {{-- HEADER --}}
                <div class="p-5 border-b border-slate-800">

                    <div class="flex flex-col
                                lg:flex-row
                                lg:items-center
                                lg:justify-between
                                gap-4">

                        <div>

                            <h2 class="text-xl font-semibold">

                                Statistiques des Classes

                            </h2>

                            <p class="mt-1 text-sm text-slate-400">

                                Répartition des apprenants
                                selon les intervalles de moyenne.

                            </p>

                        </div>

                        {{-- SEARCH --}}
                        <div class="w-full lg:w-[320px]">

                            <input type="text"
                                   placeholder="Rechercher une classe..."
                                   class="w-full
                                          h-11
                                          rounded-2xl
                                          bg-slate-950
                                          border border-slate-800
                                          px-4">

                        </div>

                    </div>

                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">

                    <table class="min-w-[2400px] w-full">

                        <thead class="bg-slate-950 border-b border-slate-800">

                            <tr>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">

                                    Classe

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    Effectif

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    < 4

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    4 - 7

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    7 - 9

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    9 - 10

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    10 - 12

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    12 - 14

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    14 - 16

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    > 16

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    Meilleure Moy.

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    Plus Faible

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    Major

                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">

                                    Dernier

                                </th>

                                <th class="px-6 py-4 text-center text-sm text-slate-400">

                                    Admissibilité

                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @foreach(range(1,12) as $class)

                            <tr class="hover:bg-slate-800/40">

                                <td class="px-6 py-5 font-medium">

                                    Terminale F2-{{ $class }}

                                </td>

                                <td class="px-4 py-5 text-center">

                                    48

                                </td>

                                <td class="px-4 py-5 text-center text-rose-400">

                                    2

                                </td>

                                <td class="px-4 py-5 text-center text-orange-400">

                                    4

                                </td>

                                <td class="px-4 py-5 text-center text-amber-400">

                                    6

                                </td>

                                <td class="px-4 py-5 text-center text-yellow-400">

                                    5

                                </td>

                                <td class="px-4 py-5 text-center text-sky-400">

                                    10

                                </td>

                                <td class="px-4 py-5 text-center text-indigo-400">

                                    12

                                </td>

                                <td class="px-4 py-5 text-center text-emerald-400">

                                    7

                                </td>

                                <td class="px-4 py-5 text-center text-green-400">

                                    2

                                </td>

                                <td class="px-4 py-5 text-center font-semibold text-emerald-400">

                                    17.82

                                </td>

                                <td class="px-4 py-5 text-center font-semibold text-rose-400">

                                    03.21

                                </td>

                                <td class="px-4 py-5 text-center">

                                    Sarah KOUASSI

                                </td>

                                <td class="px-4 py-5 text-center">

                                    Marc HOUNKPATI

                                </td>

                                <td class="px-6 py-5 text-center">

                                    <div class="inline-flex items-center gap-2">

                                        <span class="text-emerald-400 font-bold">

                                            84%

                                        </span>

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

</div>
