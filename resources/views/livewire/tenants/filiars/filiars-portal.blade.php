<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- ===================================================== --}}
        {{-- HERO --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="relative overflow-hidden
                        rounded-[32px]
                        border border-slate-800
                        bg-slate-900">

                {{-- BG --}}
                <div class="absolute inset-0
                            bg-gradient-to-br
                            from-indigo-500/10
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
                        <div class="min-w-0">

                            <div class="flex flex-wrap
                                        items-center
                                        gap-3">

                                <h1 class="text-2xl sm:text-3xl font-bold">

                                    Dashboard Filières

                                </h1>

                                <span class="px-3 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400 text-xs">

                                    Gestion Académique

                                </span>

                            </div>

                            <p class="mt-3 text-slate-400 max-w-3xl">

                                Vue globale des filières,
                                performances académiques,
                                statistiques des apprenants
                                et gestion des classes.

                            </p>

                            {{-- BADGES --}}
                            <div class="mt-6 flex flex-wrap gap-3">

                                <div class="px-4 py-2 rounded-2xl
                                            bg-slate-800 border border-slate-700">

                                    7 Filières

                                </div>

                                <div class="px-4 py-2 rounded-2xl
                                            bg-slate-800 border border-slate-700">

                                    124 Classes

                                </div>

                                <div class="px-4 py-2 rounded-2xl
                                            bg-slate-800 border border-slate-700">

                                    4 812 Apprenants

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-3">

                            <button class="h-11 px-5 rounded-2xl
                                           bg-emerald-500 hover:bg-emerald-600
                                           transition">

                                + Nouvelle Classe

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600
                                           transition">

                                Statistiques

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

                @foreach ([['Promotions', '7', 'text-indigo-400'], ['Classes', '124', 'text-sky-400'], ['Apprenants', '4 812', 'text-emerald-400'], ['Moyenne Générale', '12.84', 'text-amber-400'], ['Taux Réussite', '78%', 'text-violet-400']] as $kpi)
                    <div class="rounded-3xl
                            bg-slate-900
                            border border-slate-800
                            p-5">

                        <p class="text-sm text-slate-400">

                            {{ $kpi[0] }}

                        </p>

                        <h2 class="mt-3 text-2xl font-bold {{ $kpi[1] ? $kpi[2] : '' }}">

                            {{ $kpi[1] }}

                        </h2>

                    </div>
                @endforeach

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- PROMOTIONS TABLE --}}
        {{-- ===================================================== --}}
        <section class="">

            <div class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        overflow-hidden p-2">

                {{-- ===================================================== --}}
                {{-- HEADER --}}
                {{-- ===================================================== --}}
                <div class="p-5 sm:p-6 border-b border-slate-800">

                    <div class="flex flex-col
                                2xl:flex-row
                                2xl:items-center
                                2xl:justify-between
                                gap-5">

                        {{-- TITLE --}}
                        <div>

                            <h2 class="text-xl font-semibold">

                                Liste des classes

                            </h2>

                            <p class="mt-1 text-sm text-slate-400">

                                Analyse détaillée des classes,
                                performances et statistiques.

                            </p>

                        </div>

                        {{-- FILTERS --}}
                        <div
                            class="flex flex-col
                                    sm:flex-row
                                    flex-wrap
                                    gap-3
                                    w-full
                                    2xl:w-auto">

                            {{-- SEARCH --}}
                            <div class="relative w-full sm:w-auto">

                                <input type="text" placeholder="Rechercher une filière..."
                                    class="h-12
                                              w-full sm:w-[260px]
                                              rounded-2xl
                                              bg-slate-950
                                              border border-slate-800
                                              pl-4 pr-4
                                              text-sm">

                            </div>

                            {{-- PROMOTIONS --}}
                            <select
                                class="h-12
                                           rounded-2xl
                                           bg-slate-950
                                           border border-slate-800
                                           px-4
                                           text-sm">

                                <option>
                                    Toutes les promotions
                                </option>

                                <option>
                                    Sixième
                                </option>

                                <option>
                                    Troième
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- TABLE --}}
                {{-- ===================================================== --}}
                <div class="overflow-x-auto border rounded-2xl mt-1.5 border-slate-800">

                    <table class="w-full">

                        {{-- HEAD --}}
                        <thead class="bg-slate-950
                                     border-b border-slate-800">

                            <tr class="truncate">

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Classe
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Effectif
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Meilleur Élève
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Plus Faible
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Plus Jeune
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Plus Âgé
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Moyenne
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Taux Réussite
                                </th>

                                <th class="px-6 py-4 text-center text-sm text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        {{-- BODY --}}
                        <tbody class="divide-y divide-slate-800">

                            @foreach (['6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale'] as $classe)
                                <tr class="hover:bg-slate-800/40
                                       transition-colors duration-200">

                                    <td class="px-6 py-5 truncate">

                                        <div>

                                            <h3 class="font-semibold text-lg">

                                                {{ $classe }}

                                            </h3>

                                        </div>

                                    </td>

                                    <td class="px-4 py-5 text-center truncate">

                                        <span class="font-semibold">

                                            18

                                        </span>

                                    </td>

                                    {{-- BEST --}}
                                    <td class="px-6 py-5">

                                        <div class="truncate">

                                            <h3 class="font-medium">

                                                KOUASSI Sarah

                                            </h3>

                                            <p class="text-sm text-emerald-400">

                                                (18.92)
                                            </p>

                                        </div>

                                    </td>

                                    {{-- WORST --}}
                                    <td class="px-6 py-5">

                                        <div class="truncate">

                                            <h3 class="font-medium">

                                                HOUNKPE David

                                            </h3>

                                            <p class="text-sm text-rose-400">

                                                (03.42)

                                            </p>

                                        </div>

                                    </td>

                                    {{-- YOUNGEST --}}
                                    <td class="px-6 py-5">

                                        <div class="truncate">

                                            <h3 class="font-medium">

                                                ADJOVI Esther

                                            </h3>

                                            <p class="text-sm text-slate-400">

                                                10 ans

                                            </p>

                                        </div>

                                    </td>

                                    {{-- OLDEST --}}
                                    <td class="px-6 py-5">

                                        <div class="truncate">

                                            <h3 class="font-medium">

                                                AKAKPO Jonas

                                            </h3>

                                            <p class="text-sm text-slate-400">

                                                19 ans

                                            </p>

                                        </div>

                                    </td>

                                    {{-- AVG --}}
                                    <td class="px-4 py-5 text-center truncate">

                                        <span class="text-lg font-bold
                                                 text-emerald-400">

                                            12.84

                                        </span>

                                    </td>

                                    <td class="px-4 py-5 text-center truncate">

                                        <span
                                            class="px-3 py-1 rounded-full
                                                 bg-emerald-500/10
                                                 text-emerald-400
                                                 text-xs">

                                            78%

                                        </span>

                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-6 py-5">

                                        <div class="flex justify-end
                                                flex-wrap
                                                gap-2 truncate">

                                            {{-- PROFIL --}}
                                            <a href="{{ route('tenant.filiar.profil', ['filiar_slug' => 'f']) }}" class="p-2.5 rounded-2xl bg-blue-500/20 text-blue-400  hover:bg-blue-500/30 transition-all text-sm inline-block text-center">
                                                Profil
                                            </a>

                                            {{-- CLOSE --}}
                                            <button
                                                class="h-10 px-4
                                                       rounded-xl
                                                       bg-amber-500/10
                                                       text-amber-400
                                                       hover:bg-amber-500/20
                                                       transition">

                                                Fermer

                                            </button>

                                            {{-- DELETE --}}
                                            <button
                                                class="h-10 px-4
                                                       rounded-xl
                                                       bg-rose-500/10
                                                       text-rose-400
                                                       hover:bg-rose-500/20
                                                       transition">

                                                Supprimer

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

                    <div class="flex flex-col
                                lg:flex-row
                                lg:items-center
                                lg:justify-between
                                gap-4">

                        {{-- INFO --}}
                        <div class="text-sm text-slate-400">

                            Affichage de
                            <span class="text-slate-200 font-medium">
                                7
                            </span>
                            classes enregistrées.

                        </div>

                        {{-- EXPORTS --}}
                        <div class="flex flex-wrap gap-3">

                            <button class="h-11 px-5 rounded-2xl
                                           bg-emerald-500 hover:bg-emerald-600
                                           transition">

                                Export Excel

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-rose-500 hover:bg-rose-600
                                           transition">

                                Export PDF

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

