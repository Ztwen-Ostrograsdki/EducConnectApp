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
                        <div class="flex flex-col
                                    lg:flex-row
                                    gap-6
                                    min-w-0">

                            {{-- ICON --}}
                            <div class="flex justify-center lg:block">

                                <div
                                    class="w-32 h-32 sm:w-36 sm:h-36
                                            rounded-[30px]
                                            bg-indigo-500/10
                                            border border-indigo-500/20
                                            flex items-center justify-center
                                            text-5xl
                                            shrink-0">

                                    ∑

                                </div>

                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">

                                <div class="flex flex-wrap
                                            items-center
                                            gap-3">

                                    <h1 class="text-2xl sm:text-3xl font-bold">

                                        Mathématiques

                                    </h1>

                                    <span class="px-3 py-1 rounded-full
                                                 bg-emerald-500/10
                                                 text-emerald-400 text-xs">

                                        Matière Active

                                    </span>

                                </div>

                                <p class="mt-2 text-slate-400">

                                    Tableau global des statistiques,
                                    performances et enseignants
                                    de la matière.

                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        18 Enseignants

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        42 Classes

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        Coef Moyen : 4

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-3">

                            <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                Ajouter Enseignant

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-emerald-500 hover:bg-emerald-600">

                                Export PDF

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-sky-500 hover:bg-sky-600">

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

                @foreach ([['Moyenne Générale', '11.84', 'text-indigo-400'], ['Meilleure Note', '19.75', 'text-emerald-400'], ['Plus Faible', '02.15', 'text-rose-400'], ['Taux Réussite', '72%', 'text-sky-400'], ['Classes', '42', 'text-amber-400'], ['Enseignants', '18', 'text-violet-400']] as $kpi)
                    <div class="rounded-3xl
                            bg-slate-900
                            border border-slate-800
                            p-5">

                        <p class="text-sm text-slate-400">

                            {{ $kpi[0] }}

                        </p>

                        <h2 class="mt-3 text-2xl font-bold {{ $kpi[2] }}">

                            {{ $kpi[1] }}

                        </h2>

                    </div>
                @endforeach

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- BEST / WORST --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="grid
                        grid-cols-1
                        xl:grid-cols-2
                        gap-6">

                {{-- BEST --}}
                <div class="rounded-[32px]
                            bg-slate-900
                            border border-emerald-500/20
                            p-6">

                    <div class="flex items-center gap-4">

                        <div class="w-16 h-16 rounded-2xl
                                    bg-emerald-500/10
                                    flex items-center justify-center
                                    text-2xl">

                            🏆

                        </div>

                        <div>

                            <h2 class="text-xl font-semibold">

                                Meilleure Performance

                            </h2>

                            <p class="text-slate-400">

                                Plus forte moyenne enregistrée.

                            </p>

                        </div>

                    </div>

                    <div class="mt-6 space-y-4">

                        <div class="rounded-2xl
                                    bg-slate-950
                                    border border-slate-800
                                    p-5">

                            <h3 class="text-lg font-semibold">

                                KOUASSI Sarah

                            </h3>

                            <p class="mt-2 text-slate-400">

                                Classe : Terminale F2-1

                            </p>

                            <div class="mt-4 flex flex-wrap gap-3">

                                <span class="px-3 py-1 rounded-full
                                             bg-emerald-500/10
                                             text-emerald-400 text-xs">

                                    Moyenne : 19.75

                                </span>

                                <span class="px-3 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400 text-xs">

                                    Promotion : Terminale

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- WORST --}}
                <div class="rounded-[32px]
                            bg-slate-900
                            border border-rose-500/20
                            p-6">

                    <div class="flex items-center gap-4">

                        <div class="w-16 h-16 rounded-2xl
                                    bg-rose-500/10
                                    flex items-center justify-center
                                    text-2xl">

                            ⚠️

                        </div>

                        <div>

                            <h2 class="text-xl font-semibold">

                                Plus Faible Performance

                            </h2>

                            <p class="text-slate-400">

                                Plus faible moyenne enregistrée.

                            </p>

                        </div>

                    </div>

                    <div class="mt-6 space-y-4">

                        <div class="rounded-2xl
                                    bg-slate-950
                                    border border-slate-800
                                    p-5">

                            <h3 class="text-lg font-semibold">

                                HOUNKPE David

                            </h3>

                            <p class="mt-2 text-slate-400">

                                Classe : 1ère F3-2

                            </p>

                            <div class="mt-4 flex flex-wrap gap-3">

                                <span class="px-3 py-1 rounded-full
                                             bg-rose-500/10
                                             text-rose-400 text-xs">

                                    Moyenne : 02.15

                                </span>

                                <span class="px-3 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400 text-xs">

                                    Promotion : Première

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- BEST BOY / BEST GIRL --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="grid
                grid-cols-1
                xl:grid-cols-2
                gap-6">

                {{-- ===================================================== --}}
                {{-- MEILLEUR GARÇON --}}
                {{-- ===================================================== --}}
                <div class="rounded-[32px]
                    bg-slate-900
                    border border-sky-500/20
                    overflow-hidden">

                    {{-- HEADER --}}
                    <div class="p-6 border-b border-slate-800">

                        <div class="flex items-center gap-4">

                            <div class="w-16 h-16 rounded-2xl
                                bg-sky-500/10
                                flex items-center justify-center
                                text-2xl">

                                🏅

                            </div>

                            <div>

                                <h2 class="text-xl font-semibold">

                                    Meilleur Garçon

                                </h2>

                                <p class="mt-1 text-sm text-slate-400">

                                    Meilleure performance masculine
                                    dans la matière.

                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="p-6">

                        <div class="flex flex-col
                            lg:flex-row
                            lg:items-center
                            gap-5">

                            {{-- PHOTO --}}
                            <div class="flex justify-center lg:block">

                                <div class="w-28 h-28 rounded-[28px]
                                    bg-slate-800
                                    border border-slate-700
                                    shrink-0">
                                </div>

                            </div>

                            {{-- DETAILS --}}
                            <div class="flex-1 min-w-0">

                                <div class="flex flex-wrap
                                    items-center
                                    gap-3">

                                    <h3 class="text-2xl font-bold">

                                        HOUNKPE David

                                    </h3>

                                    <span class="px-3 py-1 rounded-full
                                         bg-sky-500/10
                                         text-sky-400 text-xs">

                                        Rang #1 Garçon

                                    </span>

                                </div>

                                <p class="mt-2 text-slate-400">

                                    Terminale F2-1 —
                                    Promotion Terminale

                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">

                                    <div class="px-4 py-2 rounded-2xl
                                        bg-slate-950
                                        border border-slate-800">

                                        Moyenne : 18.92

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                        bg-slate-950
                                        border border-slate-800">

                                        Coef : 4

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                        bg-slate-950
                                        border border-slate-800">

                                        Prof : M. AHOLOU

                                    </div>

                                </div>

                                {{-- ACTIONS --}}
                                <div class="mt-6 flex flex-wrap gap-3">

                                    <button class="h-11 px-5 rounded-2xl
                                           bg-sky-500 hover:bg-sky-600">

                                        Voir Profil

                                    </button>

                                    <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                        Historique Notes

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- MEILLEURE FILLE --}}
                {{-- ===================================================== --}}
                <div class="rounded-[32px]
                    bg-slate-900
                    border border-pink-500/20
                    overflow-hidden">

                    {{-- HEADER --}}
                    <div class="p-6 border-b border-slate-800">

                        <div class="flex items-center gap-4">

                            <div class="w-16 h-16 rounded-2xl
                                bg-pink-500/10
                                flex items-center justify-center
                                text-2xl">

                                👑

                            </div>

                            <div>

                                <h2 class="text-xl font-semibold">

                                    Meilleure Fille

                                </h2>

                                <p class="mt-1 text-sm text-slate-400">

                                    Meilleure performance féminine
                                    dans la matière.

                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="p-6">

                        <div class="flex flex-col
                            lg:flex-row
                            lg:items-center
                            gap-5">

                            {{-- PHOTO --}}
                            <div class="flex justify-center lg:block">

                                <div class="w-28 h-28 rounded-[28px]
                                    bg-slate-800
                                    border border-slate-700
                                    shrink-0">
                                </div>

                            </div>

                            {{-- DETAILS --}}
                            <div class="flex-1 min-w-0">

                                <div class="flex flex-wrap
                                    items-center
                                    gap-3">

                                    <h3 class="text-2xl font-bold">

                                        KOUASSI Sarah

                                    </h3>

                                    <span class="px-3 py-1 rounded-full
                                         bg-pink-500/10
                                         text-pink-400 text-xs">

                                        Rang #1 Fille

                                    </span>

                                </div>

                                <p class="mt-2 text-slate-400">

                                    1ère F3-2 —
                                    Promotion Première

                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">

                                    <div class="px-4 py-2 rounded-2xl
                                        bg-slate-950
                                        border border-slate-800">

                                        Moyenne : 19.41

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                        bg-slate-950
                                        border border-slate-800">

                                        Coef : 4

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                        bg-slate-950
                                        border border-slate-800">

                                        Prof : Mme ADJOVI

                                    </div>

                                </div>

                                {{-- ACTIONS --}}
                                <div class="mt-6 flex flex-wrap gap-3">

                                    <button class="h-11 px-5 rounded-2xl
                                           bg-pink-500 hover:bg-pink-600">

                                        Voir Profil

                                    </button>

                                    <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                        Historique Notes

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- PROMOTIONS STATS --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        p-6">

                <div class="flex flex-col
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

                                    <td class="px-4 py-5 text-center
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

        {{-- ===================================================== --}}
        {{-- TEACHERS --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

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

                                Enseignants de la Matière

                            </h2>

                            <p class="mt-1 text-sm text-slate-400">

                                Gestion des enseignants
                                et classes concernées.

                            </p>

                        </div>

                        {{-- FILTERS --}}
                        <div class="flex flex-wrap gap-3">

                            <input type="text" placeholder="Rechercher enseignant..."
                                class="h-11 min-w-[220px]
                                          rounded-2xl
                                          bg-slate-950
                                          border border-slate-800
                                          px-4">

                            <select class="h-11 rounded-2xl
                                           bg-slate-950
                                           border border-slate-800
                                           px-4">

                                <option>Toutes les promotions</option>
                                <option>2nde</option>
                                <option>1ère</option>
                                <option>Terminale</option>

                            </select>

                            <select class="h-11 rounded-2xl
                                           bg-slate-950
                                           border border-slate-800
                                           px-4">

                                <option>Toutes les classes</option>
                                <option>Tle F2-1</option>
                                <option>1ère F3-2</option>

                            </select>

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
                                    Enseignant
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Classes
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Nb Classes
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Moyenne
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Rôle
                                </th>

                                <th class="px-6 py-4 text-right text-sm text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @foreach (range(1, 10) as $teacher)
                                <tr class="hover:bg-slate-800/40">

                                    {{-- TEACHER --}}
                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-4">

                                            <div class="w-14 h-14
                                                    rounded-2xl
                                                    bg-slate-800">
                                            </div>

                                            <div>

                                                <h3 class="font-medium">

                                                    M. AHOLOU Pascal

                                                </h3>

                                                <p class="text-sm text-slate-400">

                                                    aholou@email.com

                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- CLASSES --}}
                                    <td class="px-6 py-5">

                                        <div class="flex flex-wrap gap-2">

                                            <span class="px-3 py-1 rounded-full
                                                     bg-indigo-500/10
                                                     text-indigo-400 text-xs">

                                                Tle F2-1

                                            </span>

                                            <span class="px-3 py-1 rounded-full
                                                     bg-indigo-500/10
                                                     text-indigo-400 text-xs">

                                                1ère F3-2

                                            </span>

                                        </div>

                                    </td>

                                    {{-- NB --}}
                                    <td class="px-4 py-5 text-center">

                                        4

                                    </td>

                                    {{-- AVG --}}
                                    <td class="px-4 py-5 text-center
                                           text-emerald-400 font-semibold">

                                        12.84

                                    </td>

                                    {{-- ROLE --}}
                                    <td class="px-6 py-5">

                                        <span class="px-3 py-1 rounded-full
                                                 bg-amber-500/10
                                                 text-amber-400 text-xs">

                                            Chef Atelier

                                        </span>

                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-6 py-5">

                                        <div class="flex justify-end gap-2">

                                            <button class="h-10 px-4 rounded-xl
                                                       bg-indigo-500/10
                                                       text-indigo-400">

                                                Voir Profil

                                            </button>

                                            <button class="h-10 px-4 rounded-xl
                                                       bg-amber-500/10
                                                       text-amber-400">

                                                Bloquer

                                            </button>

                                            <button class="h-10 px-4 rounded-xl
                                                       bg-rose-500/10
                                                       text-rose-400">

                                                Retirer

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
        {{-- COEFFICIENTS PAR PROMOTION / FILIÈRE --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-[32px]
                bg-slate-900
                border border-slate-800
                overflow-hidden">

                {{-- ===================================================== --}}
                {{-- HEADER --}}
                {{-- ===================================================== --}}
                <div class="p-5 sm:p-6 border-b border-slate-800">

                    <div class="flex flex-col
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
                        <div class="flex flex-col
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

                                <th class="px-6 py-4 text-left
                                   text-sm font-medium
                                   text-slate-400">

                                    Promotion

                                </th>

                                <th class="px-6 py-4 text-left
                                   text-sm font-medium
                                   text-slate-400">

                                    Filière / Série

                                </th>

                                <th class="px-6 py-4 text-left
                                   text-sm font-medium
                                   text-slate-400">

                                    Classes Concernées

                                </th>

                                <th class="px-4 py-4 text-center
                                   text-sm font-medium
                                   text-slate-400">

                                    Effectif

                                </th>

                                <th class="px-4 py-4 text-center
                                   text-sm font-medium
                                   text-slate-400">

                                    Coefficient

                                </th>

                                <th class="px-4 py-4 text-center
                                   text-sm font-medium
                                   text-slate-400">

                                    Moyenne Générale

                                </th>

                                <th class="px-6 py-4 text-right
                                   text-sm font-medium
                                   text-slate-400">

                                    Actions

                                </th>

                            </tr>

                        </thead>

                        {{-- BODY --}}
                        <tbody class="divide-y divide-slate-800">

                            @foreach (range(1, 8) as $coef)
                                <tr class="hover:bg-slate-800/40
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

                                            <span class="text-lg font-bold
                                             text-emerald-400">

                                                4

                                            </span>

                                        </div>

                                    </td>

                                    {{-- MOYENNE --}}
                                    <td class="px-4 py-5 text-center">

                                        <div class="flex flex-col items-center">

                                            <span class="text-lg font-bold
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

                                        <div class="flex justify-end
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

                    <div class="flex flex-col
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

                            <button class="h-11 px-5 rounded-2xl
                                   bg-emerald-500 hover:bg-emerald-600
                                   transition">

                                Ajouter Coefficient

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                   bg-sky-500 hover:bg-sky-600
                                   transition">

                                Exporter Excel

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-600
                                   transition">

                                Historique Modifications

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

