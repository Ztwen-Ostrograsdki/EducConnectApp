<div class="rounded-3xl
            bg-slate-900
            border border-slate-800
            overflow-hidden p-2">
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

                        Dashboard des matières

                    </h1>

                    <span class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400
                                     text-xs">

                        145 Matières

                    </span>

                </div>

                <p class="mt-2 text-slate-400 text-sm sm:text-base">

                    Gestion centralisée des matières de l'établissement

                </p>

            </div>

            {{-- ACTIONS --}}
        </div>

    </section>

    <section class="mb-6">

        <div class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        p-4 sm:p-5">

            <div class="flex flex-col gap-4">

                {{-- SEARCH --}}
                <div class="relative">

                    <input type="text" placeholder="Rechercher un parent, téléphone ou enfant..."
                        class="w-full h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-500/40">

                    <div class="absolute left-4 top-1/2
                                    -translate-y-1/2
                                    text-slate-500">

                        🔍

                    </div>

                </div>

                {{-- FILTER GRID --}}
                <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-7
                                gap-3">

                    <select class="h-11 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        <option>Tous types</option>
                        <option>Littéraires </option>
                        <option>Scientifiques </option>
                        <option>Techniques </option>
                        <option>Professionnelles </option>
                        <option>Sportives </option>
                    </select>
                    <select class="h-11 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        <option>Toutes les promotions</option>
                        <option>Seconde </option>
                        <option>Premières </option>
                        <option>Terminale </option>
                    </select>

                    <select class="h-11 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        <option>Toutes les filières</option>
                        <option>FC</option>
                        <option>MA</option>
                        <option>BTP</option>
                    </select>
                    <select class="h-11 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        <option>Tous les niveaux</option>
                        <option>Primaire</option>
                        <option>Secondaires</option>
                        <option>SUpérieures</option>

                    </select>

                    <button class="h-11 rounded-2xl
                                       bg-indigo-500
                                       hover:bg-indigo-600
                                       transition-all text-sm">

                        Filtrer

                    </button>

                    <button
                        class="h-11 rounded-2xl
                                       bg-rose-500/20
                                       text-rose-400
                                       hover:bg-rose-500/30
                                       transition-all text-sm">

                        Réinitialiser

                    </button>

                </div>

                {{-- GLOBAL ACTIONS --}}
                <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-5
                                gap-3">

                    <button class="h-12 rounded-2xl
                                       bg-indigo-500
                                       hover:bg-indigo-600
                                       transition-all text-sm">

                        Imprimer en PDF

                    </button>

                    <button class="h-12 rounded-2xl
                                       bg-emerald-500
                                       hover:bg-emerald-600
                                       transition-all text-sm">

                        Imprimer en Excel

                    </button>
                </div>

            </div>

        </div>

    </section>

    <div class="overflow-x-auto">

        <table class="min-w-[1500px] w-full">

            <thead class="bg-slate-950 border-b border-slate-800">

                <tr>

                    <th class="px-6 py-4 text-left text-sm text-slate-400">
                        Matière
                    </th>

                    <th class="px-4 py-4 text-center text-sm text-slate-400">
                        Chef Atelier
                    </th>

                    <th class="px-4 py-4 text-center text-sm text-slate-400">
                        Enseignants
                    </th>

                    <th class="px-4 py-4 text-center text-sm text-slate-400">
                        Classes
                    </th>

                    <th class="px-4 py-4 text-center text-sm text-slate-400">
                        Moyenne
                    </th>

                    <th class="px-4 py-4 text-center text-sm text-slate-400">
                        Réussite
                    </th>

                    <th class="px-4 py-4 text-center text-sm text-slate-400">
                        Volume Horaire
                    </th>

                    <th class="px-6 py-4 text-center text-sm text-slate-400">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-800">

                @foreach (range(1, 10) as $subject)
                    <tr class="hover:bg-slate-800/40">

                        <td class="px-6 py-5 font-medium">

                            Mathématiques

                        </td>

                        <td class="px-4 py-5 text-center">

                            M. Tognon

                        </td>

                        <td class="px-4 py-5 text-center">

                            12

                        </td>

                        <td class="px-4 py-5 text-center">

                            28

                        </td>

                        <td class="px-4 py-5 text-center">

                            13.48

                        </td>

                        <td class="px-4 py-5 text-center">

                            <span class="text-emerald-400">

                                84%

                            </span>

                        </td>

                        <td class="px-4 py-5 text-center">

                            18h

                        </td>

                        <td class="px-6 py-5">

                            <div class="flex justify-end gap-2">

                                <a href="{{ route('tenant.subject.profil', ['subject_slug' => 'f']) }}" class="p-2.5 rounded-2xl bg-blue-500/20 text-blue-400  hover:bg-blue-500/30 transition-all text-sm inline-block text-center">
                                    Profil
                                </a>

                                <button class="h-10 px-4 rounded-xl
                                           bg-orange-500/10
                                           text-orange-400">

                                    Désactiver

                                </button>
                                <button class="h-10 px-4 rounded-xl
                                           bg-red-500/10
                                           text-red-400">

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

