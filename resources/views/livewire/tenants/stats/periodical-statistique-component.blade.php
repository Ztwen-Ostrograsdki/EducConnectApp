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

                @foreach ([['Classes', '18', 'text-indigo-400'], ['Effectif', '1248', 'text-emerald-400'], ['Admissibilité', '84%', 'text-sky-400'], ['Meilleure Moy.', '17.82', 'text-amber-400'], ['Plus Faible', '03.11', 'text-rose-400']] as $card)
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

                            <input type="text" placeholder="Rechercher une classe..."
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

                    <table class="min-w-[4200px] w-full border-collapse">

                        {{-- ===================================================== --}}
                        {{-- HEADER --}}
                        {{-- ===================================================== --}}
                        <thead class="bg-slate-950">

                            {{-- ===================================================== --}}
                            {{-- ROW 1 --}}
                            {{-- ===================================================== --}}
                            <tr>

                                {{-- CLASSE --}}
                                <th rowspan="2" class="px-6 py-5
                       text-left
                       text-sm font-semibold
                       text-slate-300
                       border border-slate-800">

                                    Classe

                                </th>

                                {{-- EFFECTIF --}}
                                <th colspan="3" class="px-4 py-4
                       text-center
                       text-sm font-semibold
                       text-slate-300
                       border border-slate-800">

                                    Effectif

                                </th>

                                {{-- ABANDONS --}}
                                <th colspan="2" class="px-4 py-4
                       text-center
                       text-sm font-semibold
                       text-rose-300
                       border border-slate-800">

                                    Abandons

                                </th>

                                {{-- INTERVALS --}}
                                @foreach (['< 4', '4 - 7', '7 - 9', '9 - 10', '10 - 12', '12 - 14', '14 - 16', '> 16'] as $interval)
                                    <th colspan="2" class="px-4 py-4
                       text-center
                       text-sm font-semibold
                       text-slate-300
                       border border-slate-800">

                                        {{ $interval }}

                                    </th>
                                @endforeach

                                {{-- OTHER --}}
                                <th rowspan="2" class="px-4 py-4
                       text-center
                       text-sm font-semibold
                       text-slate-300
                       border border-slate-800">

                                    Meilleure Moy.

                                </th>

                                <th rowspan="2" class="px-4 py-4
                       text-center
                       text-sm font-semibold
                       text-slate-300
                       border border-slate-800">

                                    Plus Faible

                                </th>

                                <th rowspan="2" class="px-4 py-4
                       text-center
                       text-sm font-semibold
                       text-slate-300
                       border border-slate-800">

                                    Major

                                </th>

                                <th rowspan="2" class="px-4 py-4
                       text-center
                       text-sm font-semibold
                       text-slate-300
                       border border-slate-800">

                                    Dernier

                                </th>

                                <th rowspan="2" class="px-6 py-4
                       text-center
                       text-sm font-semibold
                       text-slate-300
                       border border-slate-800">

                                    Admissibilité

                                </th>

                            </tr>

                            {{-- ===================================================== --}}
                            {{-- ROW 2 --}}
                            {{-- ===================================================== --}}
                            <tr>

                                {{-- EFFECTIF --}}
                                <th class="px-4 py-3
                       text-center
                       text-xs text-slate-400
                       border border-slate-800">

                                    F

                                </th>

                                <th class="px-4 py-3
                       text-center
                       text-xs text-slate-400
                       border border-slate-800">

                                    G

                                </th>

                                <th class="px-4 py-3
                       text-center
                       text-xs text-slate-400
                       border border-slate-800">

                                    T

                                </th>

                                {{-- ABANDONS --}}
                                <th class="px-4 py-3
                       text-center
                       text-xs text-rose-400
                       border border-slate-800">

                                    Eff.

                                </th>

                                <th class="px-4 py-3
                       text-center
                       text-xs text-rose-400
                       border border-slate-800">

                                    %

                                </th>

                                {{-- INTERVALS --}}
                                @foreach (range(1, 8) as $i)
                                    <th class="px-4 py-3
                       text-center
                       text-xs text-slate-400
                       border border-slate-800">

                                        Eff.

                                    </th>

                                    <th class="px-4 py-3
                       text-center
                       text-xs text-slate-400
                       border border-slate-800">

                                        %

                                    </th>
                                @endforeach

                            </tr>

                        </thead>

                        {{-- ===================================================== --}}
                        {{-- BODY --}}
                        {{-- ===================================================== --}}
                        <tbody class="bg-slate-900">

                            @foreach (range(1, 12) as $class)
                                <tr class="hover:bg-slate-800/40 transition-colors duration-200">

                                    {{-- CLASSE --}}
                                    <td class="px-6 py-5
                       font-semibold
                       whitespace-nowrap
                       border border-slate-800">

                                        Terminale F2-{{ $class }}

                                    </td>

                                    {{-- EFFECTIF --}}
                                    <td class="px-4 py-5
                       text-center
                       text-pink-400
                       font-medium
                       border border-slate-800">

                                        21

                                    </td>

                                    <td class="px-4 py-5
                       text-center
                       text-sky-400
                       font-medium
                       border border-slate-800">

                                        27

                                    </td>

                                    <td class="px-4 py-5
                       text-center
                       font-bold
                       border border-slate-800">

                                        48

                                    </td>

                                    {{-- ABANDONS --}}
                                    <td class="px-4 py-5
                       text-center
                       text-rose-400
                       border border-slate-800">

                                        2

                                    </td>

                                    <td class="px-4 py-5
                       text-center
                       text-rose-400
                       border border-slate-800">

                                        4%

                                    </td>

                                    {{-- INTERVALS --}}
                                    @foreach ([['2', '4%', 'text-rose-400'], ['4', '8%', 'text-orange-400'], ['6', '12%', 'text-amber-400'], ['5', '10%', 'text-yellow-400'], ['10', '20%', 'text-sky-400'], ['12', '25%', 'text-indigo-400'], ['7', '15%', 'text-emerald-400'], ['2', '4%', 'text-green-400']] as $range)
                                        <td class="px-4 py-5
                       text-center
                       {{ $range[2] }}
                       border border-slate-800">

                                            {{ $range[0] }}

                                        </td>

                                        <td class="px-4 py-5
                       text-center
                       {{ $range[2] }}
                       border border-slate-800">

                                            {{ $range[1] }}

                                        </td>
                                    @endforeach

                                    {{-- BEST --}}
                                    <td class="px-4 py-5
                       text-center
                       font-bold text-emerald-400
                       border border-slate-800">

                                        17.82

                                    </td>

                                    {{-- LOW --}}
                                    <td class="px-4 py-5
                       text-center
                       font-bold text-rose-400
                       border border-slate-800">

                                        03.21

                                    </td>

                                    {{-- MAJOR --}}
                                    <td class="px-4 py-5
                       text-center
                       whitespace-nowrap
                       border border-slate-800">

                                        Sarah KOUASSI

                                    </td>

                                    {{-- LAST --}}
                                    <td class="px-4 py-5
                       text-center
                       whitespace-nowrap
                       border border-slate-800">

                                        Marc HOUNKPATI

                                    </td>

                                    {{-- SUCCESS --}}
                                    <td class="px-6 py-5
                       text-center
                       border border-slate-800">

                                        <div class="inline-flex
                            items-center
                            gap-2
                            px-3 py-1 rounded-full
                            bg-emerald-500/10">

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

