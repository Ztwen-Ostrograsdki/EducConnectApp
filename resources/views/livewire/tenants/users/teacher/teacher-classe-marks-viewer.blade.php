<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                ">

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
                        <div class="flex flex-col
                                    lg:flex-row
                                    gap-6
                                    min-w-0">

                            {{-- PHOTO --}}
                            <div class="flex justify-center lg:block">

                                <div
                                    class="w-32 h-32 sm:w-36 sm:h-36
                                            rounded-[30px]
                                            bg-slate-800
                                            border border-slate-700
                                            overflow-hidden
                                            shrink-0">
                                </div>

                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">

                                <div class="flex flex-wrap
                                            items-center
                                            gap-3">

                                    <h1 class="text-2xl sm:text-3xl font-bold">

                                        M. HOUNKPATI Jean

                                    </h1>

                                    <span class="px-3 py-1 rounded-full
                                                 bg-emerald-500/10
                                                 text-emerald-400 text-xs">

                                        Enseignant Actif

                                    </span>

                                </div>

                                <p class="mt-2 text-slate-400">

                                    Gestion des notes,
                                    évaluations et performances
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

                                        2 Matières

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800
                                                border border-slate-700">

                                        184 Élèves

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-3">

                            <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                Nouvelle Évaluation

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-emerald-500 hover:bg-emerald-600">

                                Export Excel

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-sky-500 hover:bg-sky-600">

                                Imprimer

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

                @foreach ([['Classes', '5', 'text-indigo-400'], ['Évaluations', '38', 'text-sky-400'], ['Notes', '1 482', 'text-emerald-400'], ['Moyenne Générale', '12.44', 'text-amber-400'], ['Retards Notes', '3', 'text-rose-400'], ['Présence', '96%', 'text-violet-400']] as $kpi)
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
                            xl:grid-cols-6
                            gap-4">

                    {{-- CLASS --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Terminale F2-1</option>
                        <option>1ère F2-2</option>
                        <option>2nde F4-1</option>

                    </select>

                    {{-- SUBJECT --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Mathématiques</option>
                        <option>Physique</option>

                    </select>

                    {{-- TYPE --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Interrogation</option>
                        <option>Devoir</option>
                        <option>TP</option>
                        <option>Composition</option>

                    </select>

                    {{-- PERIOD --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Semestre 1</option>
                        <option>Semestre 2</option>
                        <option>Trimestre 1</option>

                    </select>

                    {{-- SEARCH --}}
                    <input type="text" placeholder="Rechercher apprenant..."
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

                            <div
                                class="flex flex-col
                                        xl:flex-row
                                        xl:items-center
                                        xl:justify-between
                                        gap-4">

                                <div>

                                    <h2 class="text-xl font-semibold">

                                        Saisie des Notes

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Gestion complète des notes
                                        des apprenants.

                                    </p>

                                </div>

                                {{-- ACTIONS --}}
                                <div class="flex flex-wrap gap-2">

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-emerald-500 hover:bg-emerald-600">

                                        Ajouter Notes

                                    </button>

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-sky-500 hover:bg-sky-600">

                                        Import Excel

                                    </button>

                                    <button class="h-10 px-4 rounded-xl
                                                   bg-indigo-500 hover:bg-indigo-600">

                                        Enregistrer

                                    </button>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto">

                            <table class="min-w-[1900px] w-full">

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
                                            Interro 1
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Interro 2
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Interro 3
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Devoir 1
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Devoir 2
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moyenne
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Rang
                                        </th>

                                        <th class="px-6 py-4 text-right text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach (range(1, 15) as $student)
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

                                            {{-- NOTES --}}
                                            @foreach (range(1, 5) as $note)
                                                <td class="px-4 py-5">

                                                    <input type="number" step="0.25"
                                                        class="w-20 h-10
                                                          rounded-xl
                                                          bg-slate-950
                                                          border border-slate-800
                                                          text-center">
                                                </td>
                                            @endforeach

                                            {{-- AVG --}}
                                            <td class="px-4 py-5 text-center
                                                   font-semibold
                                                   text-emerald-400">

                                                15.42

                                            </td>

                                            {{-- RANK --}}
                                            <td class="px-4 py-5 text-center">

                                                #2

                                            </td>

                                            {{-- ACTIONS --}}
                                            <td class="px-6 py-5">

                                                <div class="flex justify-end gap-2">

                                                    <button
                                                        class="h-10 px-4 rounded-xl
                                                               bg-indigo-500/10
                                                               text-indigo-400">

                                                        Historique

                                                    </button>

                                                    <button
                                                        class="h-10 px-4 rounded-xl
                                                               bg-emerald-500/10
                                                               text-emerald-400">

                                                        Bulletin

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
                    {{-- CLASS SUMMARY --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Résumé Classe

                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach ([['Effectif', '48'], ['Garçons', '30'], ['Filles', '18'], ['Moyenne Classe', '12.44'], ['Taux Réussite', '84%']] as $item)
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
                    {{-- QUICK ACTIONS --}}
                    {{-- ===================================================== --}}
                    <div
                        class="rounded-3xl
                                bg-gradient-to-br
                                from-indigo-500/20
                                to-slate-900
                                border border-indigo-500/20
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Actions Rapides

                        </h2>

                        <div class="mt-5 space-y-3">

                            @foreach (['Nouvelle Interrogation', 'Ajouter Devoir', 'Exporter Notes', 'Imprimer Tableau', 'Notifier Parents'] as $action)
                                <button
                                    class="w-full
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

                            Dernières Activités

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach (range(1, 5) as $activity)
                                <div class="rounded-2xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-4">

                                    <h3 class="font-medium">

                                        Notes ajoutées —
                                        Terminale F2-1

                                    </h3>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Aujourd’hui — 10:42

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
