<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
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

                                Statistiques — Terminale F2-1

                            </h1>

                            <span class="px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         text-indigo-400 text-xs">

                                Classe Spécifique

                            </span>

                        </div>

                        <p class="mt-3 text-slate-400 max-w-3xl">

                            Analyse détaillée des performances
                            académiques, répartition des moyennes,
                            admissibilité et évolution globale
                            de la classe.

                        </p>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-wrap gap-3">

                        <button class="h-11 px-5 rounded-2xl
                                       bg-rose-500 hover:bg-rose-600
                                       transition-all">

                            Export PDF

                        </button>

                        <button class="h-11 px-5 rounded-2xl
                                       bg-emerald-500 hover:bg-emerald-600
                                       transition-all">

                            Export Excel

                        </button>

                        <button class="h-11 px-5 rounded-2xl
                                       bg-indigo-500 hover:bg-indigo-600
                                       transition-all">

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
                            xl:grid-cols-5
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

                    </select>

                    {{-- INCLUDE --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Inclure abandons</option>
                        <option>Exclure abandons</option>

                    </select>

                    {{-- SORT --}}
                    <select class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-4">

                        <option>Tri par moyenne</option>
                        <option>Tri par rang</option>
                        <option>Tri par sexe</option>

                    </select>

                    {{-- SEARCH --}}
                    <input type="text"
                           placeholder="Rechercher un apprenant..."
                           class="h-12 rounded-2xl
                                  bg-slate-950
                                  border border-slate-800
                                  px-4">

                    {{-- BUTTON --}}
                    <button class="h-12 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-600
                                   font-medium">

                        Générer

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
                        lg:grid-cols-3
                        2xl:grid-cols-6
                        gap-4">

                @foreach([
                    ['Effectif','48','text-indigo-400'],
                    ['Filles','18','text-pink-400'],
                    ['Garçons','30','text-sky-400'],
                    ['Moyenne Classe','12.84','text-emerald-400'],
                    ['Admissibilité','84%','text-amber-400'],
                    ['Échec','16%','text-rose-400']
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
                    {{-- DISTRIBUTION TABLE --}}
                    {{-- ===================================================== --}}
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

                                        Répartition des Moyennes

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Distribution statistique
                                        des apprenants selon
                                        les intervalles de moyenne.

                                    </p>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto">

                            <table class="min-w-[1700px] w-full">

                                <thead class="bg-slate-950
                                             border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Intervalle
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Nombre
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            %
                                        </th>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Observation
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach([
                                        ['< 4','2','4%','Situation critique'],
                                        ['4 - 7','4','8%','Très faible'],
                                        ['7 - 9','6','12%','Faible'],
                                        ['9 - 10','5','10%','Insuffisant'],
                                        ['10 - 12','10','21%','Passable'],
                                        ['12 - 14','12','25%','Assez Bien'],
                                        ['14 - 16','7','14%','Bien'],
                                        ['> 16','2','4%','Excellent'],
                                    ] as $range)

                                    <tr class="hover:bg-slate-800/40">

                                        <td class="px-6 py-5 font-medium">

                                            {{ $range[0] }}

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            {{ $range[1] }}

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                   text-indigo-400
                                                   font-semibold">

                                            {{ $range[2] }}

                                        </td>

                                        <td class="px-6 py-5 text-slate-300">

                                            {{ $range[3] }}

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- STUDENTS RANKING --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-[32px]
                                bg-slate-900
                                border border-slate-800
                                overflow-hidden">

                        <div class="p-5 border-b border-slate-800">

                            <h2 class="text-xl font-semibold">

                                Classement Général

                            </h2>

                        </div>

                        <div class="overflow-x-auto">

                            <table class="min-w-[1600px] w-full">

                                <thead class="bg-slate-950
                                             border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Rang
                                        </th>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Apprenant
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Sexe
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moyenne
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Mention
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Admissibilité
                                        </th>

                                        <th class="px-6 py-4 text-right text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach(range(1,20) as $student)

                                    <tr class="hover:bg-slate-800/40">

                                        <td class="px-6 py-5 font-bold
                                                   text-indigo-400">

                                            #{{ $student }}

                                        </td>

                                        <td class="px-6 py-5">

                                            <div class="flex items-center gap-4">

                                                <div class="w-12 h-12
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

                                        <td class="px-4 py-5 text-center">

                                            F

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                   font-semibold
                                                   text-emerald-400">

                                            17.82

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            Très Bien

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            <span class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-xs">

                                                Admis

                                            </span>

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
                {{-- RIGHT SIDEBAR --}}
                {{-- ===================================================== --}}
                <div class="space-y-6">

                    {{-- ===================================================== --}}
                    {{-- TOP STUDENTS --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Meilleurs Élèves

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach([
                                ['Major Classe','Sarah KOUASSI','17.82'],
                                ['Meilleur Garçon','Marc AGBODO','16.44'],
                                ['Meilleure Fille','Sarah KOUASSI','17.82']
                            ] as $best)

                            <div class="rounded-2xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-4">

                                <p class="text-xs text-slate-400">

                                    {{ $best[0] }}

                                </p>

                                <div class="mt-2 flex items-center justify-between">

                                    <h3 class="font-semibold">

                                        {{ $best[1] }}

                                    </h3>

                                    <span class="font-bold text-emerald-400">

                                        {{ $best[2] }}

                                    </span>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- WEAK STUDENTS --}}
                    {{-- ===================================================== --}}
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
                                        border border-slate-800
                                        p-4">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <h3 class="font-medium">

                                            HOUNKPATI Marc

                                        </h3>

                                        <p class="text-sm text-slate-400 mt-1">

                                            Difficultés en Mathématiques

                                        </p>

                                    </div>

                                    <span class="font-bold text-rose-400">

                                        04.11

                                    </span>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- PERFORMANCE --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Performances Globales

                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach([
                                ['Admissibilité','84%','bg-emerald-500'],
                                ['Échec','16%','bg-rose-500'],
                                ['Moyenne > 12','64%','bg-indigo-500'],
                                ['Moyenne > 14','18%','bg-sky-500']
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

                    {{-- ===================================================== --}}
                    {{-- QUICK ACTIONS --}}
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
                                'Générer Bulletin Global',
                                'Exporter Classement',
                                'Imprimer Rapport',
                                'Notifier Parents',
                                'Envoyer Résultats'
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

                </div>

            </div>

        </section>

    </div>

</div>