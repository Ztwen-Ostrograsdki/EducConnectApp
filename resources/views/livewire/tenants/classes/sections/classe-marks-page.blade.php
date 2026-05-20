<div class="w-full max-w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            {{-- LEFT --}}
            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-2xl sm:text-3xl font-bold break-words">
                        Gestion des Notes
                    </h1>

                    <span class="px-3 py-1 rounded-full
                                 bg-indigo-500/10
                                 border border-indigo-500/20
                                 text-indigo-400 text-xs shrink-0">

                        42 apprenants

                    </span>

                </div>

                <p class="mt-2 text-slate-400 text-sm sm:text-base">

                    Notes, moyennes et statistiques pédagogiques de la classe.

                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <button
                    class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-indigo-500 hover:bg-indigo-600
                               transition-all duration-300
                               text-sm sm:text-base">

                    Ajouter Notes

                </button>

                <button
                    class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-slate-800
                               border border-slate-700
                               hover:bg-slate-700
                               transition-all duration-300
                               text-sm sm:text-base">

                    Exporter PDF

                </button>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- KPI --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

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
                    Matière
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    Maths
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

            <div class="flex flex-col xl:flex-row gap-4">

                {{-- SEARCH --}}
                <div class="flex-1 min-w-0">

                    <div class="relative">

                        <input type="text" placeholder="Rechercher un apprenant..."
                            class="w-full h-12
                                   rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   outline-none
                                   focus:border-indigo-500
                                   transition-all">

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">

                            🔍

                        </div>

                    </div>

                </div>

                {{-- FILTERS --}}
                <div class="grid
                            grid-cols-1
                            sm:grid-cols-2
                            lg:grid-cols-3
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

                    {{-- SUBJECT --}}
                    <select class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Mathématiques</option>
                        <option>Physique</option>
                        <option>Électricité</option>

                    </select>

                    {{-- RESET --}}
                    <button
                        class="h-12 px-5 rounded-2xl
                                   bg-slate-800
                                   border border-slate-700
                                   hover:bg-slate-700
                                   transition-all
                                   text-sm">

                        Réinitialiser

                    </button>

                </div>

            </div>

        </div>

    </section>

    <section class="w-full">
        <div class="flex justify-end flex-wrap gap-3 text-gray-950 p-2">

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

        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-950 border-b border-slate-800 truncate">

                        <tr>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Apprenants
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

                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-800">

                        @foreach (range(1, 15) as $i)
                            <tr class="hover:bg-slate-800/40 transition-all">

                                {{-- STUDENT --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-4 min-w-0">

                                        <div class="w-12 h-12 rounded-2xl bg-slate-800 shrink-0">
                                        </div>

                                        <div class="min-w-0">

                                            <a href="{{ route('tenant.student.profil', ['student_uuid' => 'f2-' . $i]) }}">
                                                <h3 class="font-medium truncate">
                                                    Kouassi Vincent {{ $i }}
                                                </h3>

                                                <p class="text-sm text-slate-400 truncate">
                                                    MAT-2025-{{ $i }}
                                                </p>
                                            </a>
                                        </div>

                                    </div>

                                </td>

                                {{-- NOTES --}}
                                <td class="px-4 py-5 text-center truncate">14</td>
                                <td class="px-4 py-5 text-center truncate">16</td>
                                <td class="px-4 py-5 text-center truncate">15</td>
                                <td class="px-4 py-5 text-center truncate">13</td>

                                {{-- MOY INTERRO --}}
                                <td class="px-4 py-5 text-center truncate">

                                    <span class="px-3 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400 text-sm">

                                        14.5

                                    </span>

                                </td>

                                {{-- DEV --}}
                                <td class="px-4 py-5 text-center truncate">15</td>
                                <td class="px-4 py-5 text-center truncate">17</td>

                                {{-- MOY --}}
                                <td class="px-4 py-5 text-center truncate">

                                    <span class="px-3 py-1 rounded-full
                                             bg-emerald-500/10
                                             text-emerald-400 text-sm">

                                        15.2

                                    </span>

                                </td>

                                {{-- MOY COEF --}}
                                <td class="px-4 py-5 text-center truncate font-semibold">
                                    30.4
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center justify-end gap-2 truncate">

                                        <button
                                            class="py-2 px-2.5 cursor-pointer rounded-xl
                                                               bg-indigo-800
                                                               hover:bg-indigo-500
                                                               transition-all">

                                            Profil

                                        </button>

                                        <button
                                            class="py-2 px-2.5 cursor-pointer rounded-xl
                                                               bg-emerald-800
                                                               hover:bg-emerald-500
                                                               transition-all">

                                            Bloquer

                                        </button>

                                        <button
                                            class="py-2 px-2.5 cursor-pointer rounded-xl
                                                               bg-red-800
                                                               hover:bg-red-500
                                                               transition-all">

                                            Supprimer

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

