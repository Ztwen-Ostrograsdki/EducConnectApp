<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        <div class="flex flex-wrap items-center gap-3 p-3 bg-indigo-500/10 rounded-4xl my-1.5">

            <h1 class="text-lg sm:text-3xl font-bold">

                Profil de la Promotion Tle F2

            </h1>

            <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs">

                Promotion Active

            </span>

        </div>

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

                                    {{ initials($promotion_slug) }}

                                </div>

                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">

                                <div class="flex flex-wrap
                                            items-center
                                            gap-3">

                                    <h1 class="text-2xl sm:text-3xl font-bold">

                                        {{ $promotion_name }}

                                    </h1>

                                </div>

                                <p class="mt-2 text-slate-400">

                                    Tableau global des statistiques,
                                    performances de la promotion.

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
                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-3">

                            <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                Ajouter une classe

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

                @foreach ([['Moyenne Générale', '11.84', 'text-indigo-400'], ['Meilleure classe', 'Terminale F4-3', 'text-emerald-400'], ['Faibel classe', 'Terminale F4-6', 'text-rose-400'], ['Taux Réussite', '72%', 'text-sky-400'], ['Classes', '35', 'text-amber-400'], ['Enseignants', '18', 'text-violet-400']] as $kpi)
                    <div class="rounded-3xl
                            bg-slate-900
                            border border-slate-800
                            p-5">

                        <p class="text-sm text-slate-400">

                            {{ $kpi[0] }}

                        </p>

                        <h2 class="mt-3 text-xl font-bold {{ $kpi[2] }}">

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

                                Classe : Terminale F4-1

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

                                Classe : Tle F4-2

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

                                    Promotion : Terminale F4

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

                                    Terminale F4-1 —
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

                                    Terminale F4-2 —
                                    Promotion Terminale

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
        {{-- TEACHERS --}}
        {{-- ===================================================== --}}
        <section class="mb-6 p-2">

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

                                Enseignants de la promotion

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

                                <option>Toutes les séries</option>
                                <option>AB</option>
                                <option>C</option>

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

                                <th class="px-6 py-4 text-center text-sm text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @foreach (range(1, 10) as $teacher)
                                <tr class="hover:bg-slate-800/40">

                                    {{-- TEACHER --}}
                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-4 truncate">

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

                                        <div class="flex flex-wrap gap-2 truncate">

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

                                    {{-- ACTIONS --}}
                                    <td class="px-6 py-5">

                                        <div class="flex justify-end gap-2 truncate">

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
        {{-- LISTES DES APPRENANTS DE LA PROMOTIONS  --}}
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

                                Apprenants de la Promotion

                            </h2>

                            <p class="mt-1 text-sm text-slate-400">

                                Gestion des apprenants
                                de la promotion selon les
                                filières, séries.

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
                                    Toutes les classes
                                </option>

                                <option>
                                    Terminales F4-1
                                </option>
                                <option>
                                    Terminales F4-2
                                </option>
                                <option>
                                    Terminales F4-3
                                </option>
                                <option>
                                    Terminales F4-4
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
                <section class="w-full p-2">

                    <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

                        <div class="overflow-x-auto">

                            <table class="w-full p-1">

                                <thead class="bg-slate-950 border-b border-slate-800 ">

                                    <tr>

                                        <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                            Apprenant
                                        </th>

                                        <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                            Matricule
                                        </th>

                                        <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                            Classe
                                        </th>

                                        <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                            Présence
                                        </th>

                                        <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                            Parent
                                        </th>

                                        <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach (range(1, 10) as $i)
                                        <tr class="hover:bg-slate-800/40 transition-all">

                                            {{-- STUDENT --}}
                                            <td class="px-6 py-5">

                                                <div class="flex items-center gap-4 min-w-0">

                                                    <div class="w-12 h-12 rounded-2xl bg-slate-800 shrink-0">
                                                    </div>

                                                    <div class="min-w-0">

                                                        <a href="{{ route('tenant.student.profil', ['student_uuid' => rand(272252525, 7727277272772)]) }}">
                                                            <h3 class="font-medium truncate">
                                                                Kouassi Vincent {{ $i }}
                                                            </h3>

                                                            <p class="text-sm text-slate-400 truncate">
                                                                Génie Électrique
                                                            </p>
                                                        </a>

                                                    </div>

                                                </div>

                                            </td>

                                            {{-- MATRICULE --}}
                                            <td class="px-6 py-5 text-sm text-slate-300">
                                                <div
                                                    class="inline-flex
                                                items-center
                                                px-3 py-1
                                                rounded-full
                                                bg-amber-500/10
                                                text-amber-400
                                                text-sm truncate">

                                                    Matricule GGGG

                                                </div>

                                            </td>

                                            {{-- MOYENNE --}}
                                            <td class="px-6 py-5">

                                                <div
                                                    class="inline-flex
                                                items-center
                                                px-3 py-1
                                                rounded-full
                                                bg-emerald-500/10
                                                text-emerald-400
                                                text-sm truncate">

                                                    Tle F4-5

                                                </div>

                                            </td>

                                            {{-- PRESENCE --}}
                                            <td class="px-6 py-5 text-sm">
                                                96%
                                            </td>

                                            {{-- PARENT --}}
                                            <td class="px-6 py-5 truncate text-sm text-slate-300">
                                                M. HOUNDEKINDO
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

                    </div>

                </section>

            </div>

        </section>

        <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

            <h2 class="text-lg font-semibold">

                Élèves en Difficulté

            </h2>

            <div class="mt-5 space-y-4">

                @foreach (range(1, 5) as $weak)
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

    </div>

</div>

