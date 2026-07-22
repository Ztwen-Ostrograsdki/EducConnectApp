<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                bg-slate-950 p-2">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        <section class="mb-6">

            <div
                class="rounded-lg
                        bg-slate-900
                        border border-slate-800
                        p-5">

                <div
                    class="grid
                            grid-cols-1
                            md:grid-cols-2
                            xl:grid-cols-6
                            gap-4">

                    {{-- PERIOD --}}
                    <select
                        class="h-12 rounded-2xl
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
                    <button
                        class="h-12 rounded-2xl
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
                        gap-6">

                {{-- ===================================================== --}}
                {{-- LEFT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">

                    {{-- ===================================================== --}}
                    {{-- TABLE --}}
                    {{-- ===================================================== --}}
                    <div
                        class="rounded-lg
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

                                        Les notes de classe

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Gestion complète des notes
                                        des apprenants.

                                    </p>

                                </div>

                                {{-- ACTIONS --}}
                                <div class="flex flex-wrap gap-2">

                                    <button
                                        class="h-10 px-4 rounded-xl
                                                   bg-emerald-500 hover:bg-emerald-600">

                                        Ajouter Notes

                                    </button>

                                    <button
                                        class="h-10 px-4 rounded-xl
                                                   bg-sky-500 hover:bg-sky-600">

                                        Import Excel

                                    </button>

                                    <button
                                        class="h-10 px-4 rounded-xl
                                                   bg-indigo-500 hover:bg-indigo-600">

                                        Enregistrer

                                    </button>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto">

                            <table class="min-w-[1900px] w-full">

                                <thead
                                    class="bg-slate-950
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

                                                    <div
                                                        class="w-14 h-14
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

                                                    {{ rand(1, 20) }}
                                                </td>
                                            @endforeach

                                            {{-- AVG --}}
                                            <td
                                                class="px-4 py-5 text-center
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

            </div>

        </section>

    </div>

</div>

