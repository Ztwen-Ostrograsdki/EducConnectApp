<div>
    <section class="mb-6">

        <div
            class="rounded-[32px]
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

                        <select
                            class="h-11 rounded-2xl
                                           bg-slate-950
                                           border border-slate-800
                                           px-4">

                            <option>Toutes les promotions</option>
                            <option>2nde</option>
                            <option>1ère</option>
                            <option>Terminale</option>

                        </select>

                        <select
                            class="h-11 rounded-2xl
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

                                        <div
                                            class="w-14 h-14
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

                                        <span
                                            class="px-3 py-1 rounded-full
                                                     bg-indigo-500/10
                                                     text-indigo-400 text-xs">

                                            Tle F2-1

                                        </span>

                                        <span
                                            class="px-3 py-1 rounded-full
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
                                <td
                                    class="px-4 py-5 text-center
                                           text-emerald-400 font-semibold">

                                    12.84

                                </td>

                                {{-- ROLE --}}
                                <td class="px-6 py-5">

                                    <span
                                        class="px-3 py-1 rounded-full
                                                 bg-amber-500/10
                                                 text-amber-400 text-xs">

                                        Chef Atelier

                                    </span>

                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-5">

                                    <div class="flex justify-end gap-2">

                                        <button
                                            class="h-10 px-4 rounded-xl
                                                       bg-indigo-500/10
                                                       text-indigo-400">

                                            Voir Profil

                                        </button>

                                        <button
                                            class="h-10 px-4 rounded-xl
                                                       bg-amber-500/10
                                                       text-amber-400">

                                            Bloquer

                                        </button>

                                        <button
                                            class="h-10 px-4 rounded-xl
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
</div>

