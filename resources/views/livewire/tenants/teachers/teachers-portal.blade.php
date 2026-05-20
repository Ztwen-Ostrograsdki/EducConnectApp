<div class="w-full overflow-x-hidden p-2">

    {{-- ===================================================== --}}
    {{-- GLOBAL CONTAINER --}}
    {{-- ===================================================== --}}
    <div class="mx-auto
                w-full
                max-w-[1850px]
                px-3
                sm:px-4
                lg:px-6
                xl:px-8">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="flex flex-col
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                        gap-5">

                {{-- LEFT --}}
                <div class="min-w-0">

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl sm:text-3xl font-bold">

                            Enseignants

                        </h1>

                        <span class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400
                                     text-xs">

                            248 Enseignants

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm sm:text-base">

                        Vue globale du personnel enseignant de l’établissement

                    </p>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-wrap gap-3">

                    <button class="h-11 px-5 rounded-2xl
                                   bg-slate-800
                                   hover:bg-slate-700
                                   transition-all text-sm">

                        Exporter

                    </button>

                    <button class="h-11 px-5 rounded-2xl
                                   bg-indigo-500
                                   hover:bg-indigo-600
                                   transition-all text-sm">

                        Ajouter Enseignant

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
                        xl:grid-cols-4
                        gap-4">

                @foreach ([['Total', '248', 'text-indigo-400'], ['Actifs', '232', 'text-emerald-400'], ['Temps Plein', '180', 'text-sky-400'], ['Taux Présence', '96%', 'text-amber-400']] as $kpi)
                    <div class="rounded-3xl
                            border border-slate-800
                            bg-slate-900
                            p-4 sm:p-5">

                        <p class="text-xs sm:text-sm text-slate-400">
                            {{ $kpi[0] }}
                        </p>

                        <h2 class="mt-3
                               text-2xl sm:text-3xl xl:text-4xl
                               font-bold {{ $kpi[2] }}">

                            {{ $kpi[1] }}

                        </h2>

                    </div>
                @endforeach

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- FILTER BAR --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        p-4 sm:p-5">

                <div class="flex flex-col gap-4">

                    {{-- SEARCH --}}
                    <div class="relative">

                        <input type="text" placeholder="Rechercher un enseignant..."
                            class="w-full h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-500/40">

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">

                            🔍

                        </div>

                    </div>

                    {{-- FILTERS --}}
                    <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-5
                                gap-3">

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes matières</option>
                            <option>Mathématiques</option>
                            <option>Physique</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes classes</option>
                            <option>Terminale F2-1</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Sexe</option>
                            <option>Masculin</option>
                            <option>Féminin</option>

                        </select>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Statut</option>
                            <option>Actif</option>
                            <option>Suspendu</option>

                        </select>

                        <button class="h-11 rounded-2xl
                                       bg-indigo-500
                                       hover:bg-indigo-600
                                       transition-all text-sm">

                            Filtrer

                        </button>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- MAIN GRID --}}
        {{-- ===================================================== --}}
        <section>

            <div class="grid
                        grid-cols-1
                        2xl:grid-cols-[minmax(0,1fr)_380px]
                        gap-6">

                {{-- ===================================================== --}}
                {{-- LEFT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">

                    {{-- TEACHERS TABLE --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                overflow-hidden">

                        {{-- HEADER --}}
                        <div class="border-b border-slate-800
                                    p-4 sm:p-6">

                            <div
                                class="flex flex-col
                                        lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                        gap-4">

                                <div>

                                    <h2 class="text-lg sm:text-xl font-semibold">

                                        Liste des Enseignants

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Gestion et suivi du personnel

                                    </p>

                                </div>

                                <div class="flex flex-wrap gap-3">

                                    <button
                                        class="h-11 px-4 rounded-2xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        Trier

                                    </button>

                                    <button
                                        class="h-11 px-4 rounded-2xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        Colonnes

                                    </button>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto">

                            <table class="min-w-[1200px] w-full">

                                <thead class="bg-slate-950 border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Enseignant
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Matière
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Classes
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Notes
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Heures/Sem
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Présence
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Statut
                                        </th>

                                        <th class="px-6 py-4 text-right text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach (range(1, 10) as $teacher)
                                        <tr class="hover:bg-slate-800/40 transition-all">

                                            {{-- PROFILE --}}
                                            <td class="px-6 py-5">

                                                <div class="flex items-center gap-4">

                                                    <div class="w-14 h-14 rounded-2xl
                                                            bg-slate-800
                                                            shrink-0">
                                                    </div>

                                                    <div class="min-w-0">

                                                        <h3 class="font-medium truncate">

                                                            M. Jean Kouassi

                                                        </h3>

                                                        <p class="mt-1 text-sm text-slate-400 truncate">

                                                            enseignant@email.com

                                                        </p>

                                                    </div>

                                                </div>

                                            </td>

                                            {{-- SUBJECT --}}
                                            <td class="px-4 py-5 text-center whitespace-nowrap">

                                                Mathématiques

                                            </td>

                                            {{-- CLASSES --}}
                                            <td class="px-4 py-5 text-center">

                                                8

                                            </td>

                                            {{-- NOTES --}}
                                            <td class="px-4 py-5 text-center">

                                                <span class="px-3 py-1 rounded-full
                                                         bg-indigo-500/10
                                                         text-indigo-400 text-sm">

                                                    482

                                                </span>

                                            </td>

                                            {{-- HOURS --}}
                                            <td class="px-4 py-5 text-center">

                                                26h

                                            </td>

                                            {{-- PRESENCE --}}
                                            <td class="px-4 py-5 text-center">

                                                <span class="text-emerald-400">
                                                    98%
                                                </span>

                                            </td>

                                            {{-- STATUS --}}
                                            <td class="px-4 py-5 text-center">

                                                <span
                                                    class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                    Actif

                                                </span>

                                            </td>

                                            {{-- ACTIONS --}}
                                            <td class="px-6 py-5">

                                                <div class="flex items-center justify-end gap-2">

                                                    <button
                                                        class="w-10 h-10 rounded-xl
                                                               bg-slate-800
                                                               hover:bg-indigo-500
                                                               transition-all">

                                                        👁

                                                    </button>

                                                    <button
                                                        class="w-10 h-10 rounded-xl
                                                               bg-slate-800
                                                               hover:bg-emerald-500
                                                               transition-all">

                                                        ✏

                                                    </button>

                                                    <button
                                                        class="w-10 h-10 rounded-xl
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

                        {{-- PAGINATION --}}
                        <div class="border-t border-slate-800
                                    px-4 sm:px-6 py-4">

                            <div class="flex flex-col sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                        gap-4">

                                <p class="text-sm text-slate-400">

                                    Affichage de 1 à 10 sur 248 enseignants

                                </p>

                                <div class="flex items-center gap-2">

                                    <button
                                        class="h-10 px-4 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        Précédent

                                    </button>

                                    <button
                                        class="h-10 px-4 rounded-xl
                                                   bg-indigo-500
                                                   hover:bg-indigo-600
                                                   transition-all text-sm">

                                        1

                                    </button>

                                    <button
                                        class="h-10 px-4 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        2

                                    </button>

                                    <button
                                        class="h-10 px-4 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                        Suivant

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RIGHT SIDEBAR --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">

                    {{-- QUICK STATS --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Répartition Matières

                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach ([['Mathématiques', '82%', 'bg-indigo-500'], ['Physique', '70%', 'bg-emerald-500'], ['Informatique', '65%', 'bg-amber-500'], ['Français', '58%', 'bg-sky-500']] as $item)
                                <div>

                                    <div class="flex items-center justify-between">

                                        <span class="text-sm text-slate-300">
                                            {{ $item[0] }}
                                        </span>

                                        <span class="text-sm font-semibold">
                                            {{ $item[1] }}
                                        </span>

                                    </div>

                                    <div class="mt-2 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                                        <div class="h-full rounded-full {{ $item[2] }}" style="width: {{ $item[1] }}">
                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>

                    {{-- RECENT ACTIVITY --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Activités Récentes

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach (range(1, 5) as $activity)
                                <div class="rounded-2xl
                                        bg-slate-950
                                        p-4">

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="w-11 h-11 rounded-2xl
                                                bg-indigo-500/10
                                                shrink-0
                                                flex items-center justify-center
                                                text-indigo-400">

                                            ✓

                                        </div>

                                        <div class="min-w-0">

                                            <h3 class="font-medium text-sm">

                                                Notes publiées

                                            </h3>

                                            <p class="mt-1 text-sm text-slate-400">

                                                M. Jean Kouassi a publié les notes de Terminale F2-1

                                            </p>

                                            <p class="mt-2 text-xs text-slate-500">

                                                Il y a 2 heures

                                            </p>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>

                    {{-- QUICK MENU --}}
                    <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Accès Rapides

                        </h2>

                        <div class="mt-5 grid grid-cols-2 gap-3">

                            @foreach (['Présences', 'Emplois', 'Notes', 'Rapports', 'Statistiques', 'Archives'] as $menu)
                                <button
                                    class="h-24 rounded-2xl
                                           bg-slate-950
                                           hover:bg-indigo-500/10
                                           border border-slate-800
                                           transition-all
                                           text-sm">

                                    {{ $menu }}

                                </button>
                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

