<div class="w-full max-w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div class="rounded-3xl
                    border border-slate-800
                    bg-slate-900
                    overflow-hidden">

            <div class="p-5 sm:p-8">

                <div class="flex flex-col xl:flex-row gap-8">

                    {{-- AVATAR --}}
                    <div class="flex flex-col items-center xl:items-start">

                        <div class="w-36 h-36 rounded-3xl
                                    bg-slate-800
                                    shrink-0">
                        </div>

                        <div class="mt-5 flex flex-wrap gap-3 justify-center xl:justify-start">

                            <span class="px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         text-indigo-400 text-xs">

                                Terminale F2-1

                            </span>

                            <span class="px-3 py-1 rounded-full
                                         bg-emerald-500/10
                                         text-emerald-400 text-xs">

                                Excellent

                            </span>

                        </div>

                    </div>

                    {{-- INFOS --}}
                    <div class="flex-1 min-w-0">

                        <div class="flex flex-col 2xl:flex-row
                                    2xl:items-start
                                    2xl:justify-between
                                    gap-6">

                            <div class="min-w-0">

                                <h1 class="text-3xl sm:text-4xl font-bold break-words">

                                    Kouassi Vincent HOUNDEKINDO

                                </h1>

                                <p class="mt-2 text-slate-400">

                                    Matricule : MAT-2026-00124

                                </p>

                                <div class="mt-6 grid
                                            grid-cols-1
                                            sm:grid-cols-2
                                            xl:grid-cols-4
                                            gap-4">

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Âge
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            17 ans
                                        </h4>

                                    </div>

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Sexe
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            Masculin
                                        </h4>

                                    </div>

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Nationalité
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            Béninoise
                                        </h4>

                                    </div>

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Naissance
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            12/08/2008
                                        </h4>

                                    </div>

                                </div>

                            </div>

                            {{-- ACTIONS --}}
                            <div class="grid
                                        grid-cols-2
                                        sm:grid-cols-4
                                        xl:grid-cols-2
                                        gap-3 w-full xl:w-[260px]">

                                <a href="{{route('tenant.student.profil', ['student_uuid' => $student_uuid])}}" class="p-3 rounded-2xl col-span-2
                                               bg-primary-500/70
                                               text-gray-200
                                               hover:bg-primary-800/70
                                               transition-all text-sm inline-block text-center">

                                    Retour à l'acceuil profil 

                                </a>

                                <button class="p-3 rounded-2xl
                                               bg-slate-800
                                               hover:bg-slate-700
                                               transition-all text-sm">

                                    Présence

                                </button>

                                <button class="p-3 rounded-2xl
                                               bg-rose-500/20
                                               text-rose-400
                                               hover:bg-rose-500/30
                                               transition-all text-sm">

                                    Suspendre

                                </button>

                                <a href="{{route('tenant.classe.profil', ['classe_slug' => $classe_slug])}}" class="p-3 col-span-2 rounded-2xl
                                               bg-sky-500/20
                                               text-sky-400
                                               hover:bg-sky-500/30
                                               transition-all text-sm inline-block text-center">

                                    Acceder à la classe 

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- KPI --}}
    {{-- ===================================================== --}}
    <section class="mb-6 rounded-3xl border border-slate-800 bg-slate-900 p-4">

        <div class="min-w-0 mb-2">

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-2xl sm:text-3xl font-bold break-words">
                    Détails des Notes
                </h1>
            </div>

            <p class="mt-0 text-gray-400 text-sm sm:text-base">

                Notes, moyennes et statistiques pédagogiques apprenant.

            </p>

        </div>

        <div class="grid
                    grid-cols-1
                    sm:grid-cols-2
                    xl:grid-cols-4
                    gap-4 sm:gap-6">

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Moyenne Générale
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    14.52
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Taux de Réussite
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    87%
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Classe
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    Terminale
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Semestre
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    S1
                </h2>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- TOOLBAR --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">

            <div class="flex flex-col justify-between xl:flex-row gap-4">

                {{-- FILTERS --}}
                <div class="grid
                            grid-cols-1
                            sm:grid-cols-2
                            lg:grid-cols-2
                            gap-3">

                    {{-- SEMESTER --}}
                    <select class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Semestre 1</option>
                        <option>Semestre 2</option>
                        <option>Trimestre 1</option>
                        <option>Trimestre 2</option>

                    </select>

                    {{-- RESET --}}
                    <button class="h-12 px-5 rounded-2xl
                                   bg-slate-800
                                   border border-slate-700
                                   hover:bg-slate-700
                                   transition-all
                                   text-sm">

                        Réinitialiser

                    </button>

                </div>
                <div class="flex justify-center flex-wrap gap-3 text-gray-950">

                    <button class="px-3 py-2 rounded-2xl
                                    bg-red-500 hover:bg-red-600">

                        Verrouiller notes

                    </button>
                    
                    <button class="px-3 py-2 rounded-2xl
                                    bg-blue-500 hover:bg-blue-600">

                        Imprimer PDF

                    </button>

                    <button class="px-3 py-2 rounded-2xl
                                    bg-emerald-500 hover:bg-emerald-600">

                        Emprimer Excel

                    </button>

                    <button class="px-3 py-2 rounded-2xl
                                    bg-amber-500 hover:bg-amber-600">

                        Imprimer Excel et PDF

                    </button>

                </div>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- DESKTOP TABLE --}}
    {{-- ===================================================== --}}
    <section class="hidden 2xl:block mb-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-950 border-b border-slate-800">

                        <tr>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Matières
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
                                Interro 4
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Moy Interro
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Dev 1
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Dev 2
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Moyenne
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Moy Coef
                            </th>

                            <th class="px-6 py-4 text-right text-sm text-slate-400">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-800">

                        @foreach(range(1,15) as $i)

                        <tr class="hover:bg-slate-800/40 transition-all">

                            {{-- STUDENT --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-4 min-w-0">

                                    <div class="w-12 h-12 rounded-2xl bg-slate-800 shrink-0">
                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="font-medium truncate">
                                            Matière {{$i}}
                                        </h3>

                                        <p class="text-sm text-slate-400 truncate">
                                            Coef {{rand(1, 5)}}
                                        </p>

                                    </div>

                                </div>

                            </td>

                            {{-- NOTES --}}
                            <td class="px-4 py-5 text-center">14</td>
                            <td class="px-4 py-5 text-center">16</td>
                            <td class="px-4 py-5 text-center">15</td>
                            <td class="px-4 py-5 text-center">13</td>

                            {{-- MOY INTERRO --}}
                            <td class="px-4 py-5 text-center">

                                <span class="px-3 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400 text-sm">

                                    14.5

                                </span>

                            </td>

                            {{-- DEV --}}
                            <td class="px-4 py-5 text-center">15</td>
                            <td class="px-4 py-5 text-center">17</td>

                            {{-- MOY --}}
                            <td class="px-4 py-5 text-center">

                                <span class="px-3 py-1 rounded-full
                                             bg-emerald-500/10
                                             text-emerald-400 text-sm">

                                    15.2

                                </span>

                            </td>

                            {{-- MOY COEF --}}
                            <td class="px-4 py-5 text-center font-semibold">
                                30.4
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center justify-end gap-2">

                                    <button class="w-10 h-10 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-indigo-500
                                                   transition-all">

                                        👁

                                    </button>

                                    <button class="w-10 h-10 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-emerald-500
                                                   transition-all">

                                        ✏

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
    {{-- MOBILE/TABLET CARDS --}}
    {{-- ===================================================== --}}
    <section class="2xl:hidden">

        <div class="grid
                    grid-cols-1
                    lg:grid-cols-2
                    gap-4 sm:gap-6">

            @foreach(range(1,15) as $i)

            <div class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        overflow-hidden">

                <div class="p-5">

                    {{-- TOP --}}
                    <div class="flex items-start gap-4 min-w-0">

                        <div class="w-14 h-14 rounded-2xl bg-slate-800 shrink-0">
                        </div>

                        <div class="min-w-0 flex-1">

                            <h3 class="font-semibold truncate">
                                Matière {{$i}}
                            </h3>

                            <p class="mt-1 text-sm text-slate-400 truncate">
                                Coef {{rand(1, 5)}}
                            </p>

                        </div>

                    </div>

                    {{-- INTERROS --}}
                    <div class="mt-6 grid grid-cols-2 gap-4">

                        <div class="rounded-2xl bg-slate-950 p-4">
                            <p class="text-xs text-slate-500">Interro 1</p>
                            <h4 class="mt-2 text-lg font-bold">14</h4>
                        </div>

                        <div class="rounded-2xl bg-slate-950 p-4">
                            <p class="text-xs text-slate-500">Interro 2</p>
                            <h4 class="mt-2 text-lg font-bold">16</h4>
                        </div>

                        <div class="rounded-2xl bg-slate-950 p-4">
                            <p class="text-xs text-slate-500">Interro 3</p>
                            <h4 class="mt-2 text-lg font-bold">15</h4>
                        </div>

                        <div class="rounded-2xl bg-slate-950 p-4">
                            <p class="text-xs text-slate-500">Interro 4</p>
                            <h4 class="mt-2 text-lg font-bold">13</h4>
                        </div>

                    </div>

                    {{-- MOYENNES --}}
                    <div class="mt-5 grid grid-cols-3 gap-3">

                        <div class="rounded-2xl bg-indigo-500/10 p-4 text-center">
                            <p class="text-xs text-indigo-300">
                                Moy Interro
                            </p>

                            <h4 class="mt-2 font-bold">
                                14.5
                            </h4>
                        </div>

                        <div class="rounded-2xl bg-emerald-500/10 p-4 text-center">
                            <p class="text-xs text-emerald-300">
                                Moyenne
                            </p>

                            <h4 class="mt-2 font-bold">
                                15.2
                            </h4>
                        </div>

                        <div class="rounded-2xl bg-slate-800 p-4 text-center">
                            <p class="text-xs text-slate-400">
                                Coef
                            </p>

                            <h4 class="mt-2 font-bold">
                                30.4
                            </h4>
                        </div>

                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="border-t border-slate-800 p-4">

                    <div class="grid grid-cols-2 gap-3">

                        <button class="h-11 rounded-2xl
                                       bg-slate-800
                                       hover:bg-indigo-500
                                       transition-all
                                       text-sm">

                            Voir

                        </button>

                        <button class="h-11 rounded-2xl
                                       bg-slate-800
                                       hover:bg-emerald-500
                                       transition-all
                                       text-sm">

                            Modifier

                        </button>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </section>

</div>