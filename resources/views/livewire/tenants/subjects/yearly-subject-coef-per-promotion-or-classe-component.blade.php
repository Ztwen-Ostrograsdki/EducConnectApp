<div>
    <section class="mb-6">

        <div
            class="rounded-[32px]
                bg-slate-900
                border border-slate-800
                overflow-hidden">

            {{-- ===================================================== --}}
            {{-- HEADER --}}
            {{-- ===================================================== --}}
            <div class="p-5 sm:p-6 border-b border-slate-800">

                <div
                    class="flex flex-col
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                        gap-5">

                    {{-- TITLE --}}
                    <div>

                        <h2 class="text-xl font-semibold">

                            Coefficients par Promotion

                        </h2>

                        <p class="mt-1 text-sm text-slate-400">

                            Gestion des coefficients
                            de la matière selon les
                            filières, séries et promotions.

                        </p>

                    </div>

                    {{-- FILTERS --}}
                    <div
                        class="flex flex-col
                            sm:flex-row
                            gap-3
                            w-full
                            xl:w-auto">

                        {{-- FILIERE --}}
                        <select
                            class="h-12
                                   min-w-[220px]
                                   rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4
                                   text-sm">

                            <option>
                                Toutes les Filières
                            </option>

                            <option>
                                F1
                            </option>

                            <option>
                                F2
                            </option>

                            <option>
                                F3
                            </option>

                            <option>
                                F4
                            </option>

                        </select>

                        {{-- SERIE --}}
                        <select
                            class="h-12
                                   min-w-[220px]
                                   rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4
                                   text-sm">

                            <option>
                                Toutes les Séries
                            </option>

                            <option>
                                Série A
                            </option>

                            <option>
                                Série C
                            </option>

                            <option>
                                Série D
                            </option>

                        </select>

                    </div>

                </div>

            </div>

            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}
            <div class="overflow-x-auto">

                <table class="min-w-[1400px] w-full">

                    {{-- HEAD --}}
                    <thead class="bg-slate-950
                             border-b border-slate-800">

                        <tr>

                            <th
                                class="px-6 py-4 text-left
                                   text-sm font-medium
                                   text-slate-400">

                                Promotion

                            </th>

                            <th
                                class="px-6 py-4 text-left
                                   text-sm font-medium
                                   text-slate-400">

                                Filière / Série

                            </th>

                            <th
                                class="px-6 py-4 text-left
                                   text-sm font-medium
                                   text-slate-400">

                                Classes Concernées

                            </th>

                            <th
                                class="px-4 py-4 text-center
                                   text-sm font-medium
                                   text-slate-400">

                                Effectif

                            </th>

                            <th
                                class="px-4 py-4 text-center
                                   text-sm font-medium
                                   text-slate-400">

                                Coefficient

                            </th>

                            <th
                                class="px-4 py-4 text-center
                                   text-sm font-medium
                                   text-slate-400">

                                Moyenne Générale

                            </th>

                            <th
                                class="px-6 py-4 text-right
                                   text-sm font-medium
                                   text-slate-400">

                                Actions

                            </th>

                        </tr>

                    </thead>

                    {{-- BODY --}}
                    <tbody class="divide-y divide-slate-800">

                        @foreach (range(1, 8) as $coef)
                            <tr
                                class="hover:bg-slate-800/40
                               transition-colors duration-200">

                                {{-- PROMOTION --}}
                                <td class="px-6 py-5">

                                    <div>

                                        <h3 class="font-semibold">

                                            Terminale

                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">

                                            Année 2025 - 2026

                                        </p>

                                    </div>

                                </td>

                                {{-- FILIERE --}}
                                <td class="px-6 py-5">

                                    <div class="flex flex-wrap gap-2">

                                        <span
                                            class="px-3 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400
                                             text-xs">

                                            F2

                                        </span>

                                        <span
                                            class="px-3 py-1 rounded-full
                                             bg-sky-500/10
                                             text-sky-400
                                             text-xs">

                                            Série Industrielle

                                        </span>

                                    </div>

                                </td>

                                {{-- CLASSES --}}
                                <td class="px-6 py-5">

                                    <div class="flex flex-wrap gap-2">

                                        @foreach (['Tle F2-1', 'Tle F2-2', 'Tle F2-3'] as $classe)
                                            <span
                                                class="px-3 py-1 rounded-full
                                             bg-slate-800
                                             border border-slate-700
                                             text-xs">

                                                {{ $classe }}

                                            </span>
                                        @endforeach

                                    </div>

                                </td>

                                {{-- EFFECTIF --}}
                                <td class="px-4 py-5 text-center">

                                    <span class="font-semibold">

                                        148

                                    </span>

                                </td>

                                {{-- COEF --}}
                                <td class="px-4 py-5 text-center">

                                    <div
                                        class="inline-flex
                                        items-center
                                        justify-center
                                        w-14 h-14
                                        rounded-2xl
                                        bg-emerald-500/10
                                        border border-emerald-500/20">

                                        <span
                                            class="text-lg font-bold
                                             text-emerald-400">

                                            4

                                        </span>

                                    </div>

                                </td>

                                {{-- MOYENNE --}}
                                <td class="px-4 py-5 text-center">

                                    <div class="flex flex-col items-center">

                                        <span
                                            class="text-lg font-bold
                                             text-indigo-400">

                                            12.84

                                        </span>

                                        <span class="text-xs text-slate-500">

                                            Performance globale

                                        </span>

                                    </div>

                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-5">

                                    <div
                                        class="flex justify-end
                                        flex-wrap
                                        gap-2">

                                        {{-- EDIT --}}
                                        <button
                                            class="h-10 px-4
                                               rounded-xl
                                               bg-indigo-500/10
                                               text-indigo-400
                                               hover:bg-indigo-500/20
                                               transition">

                                            Modifier

                                        </button>

                                        {{-- RESET --}}
                                        <button
                                            class="h-10 px-4
                                               rounded-xl
                                               bg-amber-500/10
                                               text-amber-400
                                               hover:bg-amber-500/20
                                               transition">

                                            Réinitialiser

                                        </button>

                                    </div>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

            {{-- ===================================================== --}}
            {{-- FOOTER --}}
            {{-- ===================================================== --}}
            <div class="p-5 border-t border-slate-800">

                <div
                    class="flex flex-col
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                        gap-4">

                    {{-- INFO --}}
                    <div class="text-sm text-slate-400">

                        Affichage de
                        <span class="text-slate-200 font-medium">
                            8
                        </span>
                        coefficients configurés.

                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-wrap gap-3">

                        <button
                            class="h-11 px-5 rounded-2xl
                                   bg-emerald-500 hover:bg-emerald-600
                                   transition">

                            Ajouter Coefficient

                        </button>

                        <button
                            class="h-11 px-5 rounded-2xl
                                   bg-sky-500 hover:bg-sky-600
                                   transition">

                            Exporter Excel

                        </button>

                        <button
                            class="h-11 px-5 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-600
                                   transition">

                            Historique Modifications

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="mb-6">

        <div
            class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        p-6">

            <div
                class="flex flex-col
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
                            gap-4">

                <div>

                    <h2 class="text-xl font-semibold">

                        Performances par Promotion

                    </h2>

                    <p class="mt-1 text-sm text-slate-400">

                        Analyse statistique de la matière
                        selon les promotions.

                    </p>

                </div>

            </div>

            <div class="mt-6 overflow-x-auto">

                <table class="min-w-[1200px] w-full">

                    <thead class="bg-slate-950
                                     border-b border-slate-800">

                        <tr>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Promotion
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Classes
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Effectif
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Moyenne
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Taux Réussite
                            </th>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Meilleur Élève
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-800">

                        @foreach (['6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale'] as $promo)
                            <tr class="hover:bg-slate-800/40">

                                <td class="px-6 py-5 font-medium">

                                    {{ $promo }}

                                </td>

                                <td class="px-4 py-5 text-center">

                                    8

                                </td>

                                <td class="px-4 py-5 text-center">

                                    312

                                </td>

                                <td
                                    class="px-4 py-5 text-center
                                           text-emerald-400 font-semibold">

                                    12.42

                                </td>

                                <td class="px-4 py-5 text-center">

                                    74%

                                </td>

                                <td class="px-6 py-5">

                                    KOUASSI Sarah

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </section>
</div>

