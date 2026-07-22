<div class="w-full overflow-x-hidden bg-slate-950">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                p-3">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        <section class="mb-6 w-full">

            <div
                class="rounded-lg
                        bg-slate-950
                        border border-slate-800
                        p-2 flex items-center justify-end gap-2 w-full">

                <div class="w-full gap-2 flex justify-end items-center">

                    {{-- TYPE --}}
                    <select
                        class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-6">

                        <option>Interrogation</option>
                        <option>Devoir</option>
                        <option>Composition</option>

                    </select>

                    {{-- PERIOD --}}
                    <select
                        class="h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   px-6">

                        <option>Semestre 1</option>
                        <option>Semestre 2</option>

                    </select>
                </div>

            </div>

        </section>

        <section>

            <div class="grid
                        grid-cols-1
                        ">

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

                                        Saisie des Notes de {{ $this->subject->code }}

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Ajoutez rapidement les notes
                                        des apprenants.

                                    </p>

                                </div>

                                {{-- ACTIONS --}}
                                <div class="flex flex-wrap gap-2">

                                    <button
                                        class="h-10 px-4 rounded-xl
                                                   bg-emerald-500 hover:bg-emerald-600">

                                        Tout Valider

                                    </button>

                                    <button
                                        class="h-10 px-4 rounded-xl
                                                   bg-amber-500 hover:bg-amber-600">

                                        Réinitialiser

                                    </button>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto bg-slate-900">

                            <table class="min-w-[1900px] w-full bg-slate-900">

                                <thead
                                    class="bg-slate-950
                                             border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Apprenant
                                        </th>

                                        <th class="px-6 py-4 text-center text-sm text-slate-400">
                                            Note Interrogation
                                        </th>

                                        <th class="px-6 py-4 text-center text-sm text-slate-400">
                                            Note Devoir
                                        </th>

                                        <th class="px-6 py-4 text-right text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach (range(1, 5) as $student)
                                        <tr class="hover:bg-slate-800/40">

                                            {{-- STUDENT --}}
                                            <td class="px-6 py-5">

                                                <div class="flex items-center gap-4">

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

                                            {{-- INTERRO --}}
                                            <td class="px-6 py-5">

                                                <input type="text" placeholder="12-09-13,5"
                                                    class="w-full min-w-[220px]
                                                          h-12 rounded-2xl
                                                          bg-slate-950
                                                          border border-slate-800
                                                          px-4
                                                          text-left font-mono">
                                            </td>

                                            {{-- DEVOIR --}}
                                            <td class="px-6 py-5">

                                                <input type="text" placeholder="12-09-13,5"
                                                    class="w-full min-w-[220px]
                                                          h-12 rounded-2xl
                                                          bg-slate-950
                                                          border border-slate-800
                                                          px-4
                                                          text-left font-mono">
                                            </td>

                                            {{-- ACTIONS --}}
                                            <td class="px-6 py-5">

                                                <div class="flex justify-end gap-2">

                                                    <button
                                                        class="h-11 px-4 rounded-xl
                                                               bg-emerald-500/10
                                                               text-emerald-400
                                                               border border-emerald-500/20">

                                                        Insérer

                                                    </button>

                                                    <button
                                                        class="h-11 px-4 rounded-xl
                                                               bg-rose-500/10
                                                               text-rose-400
                                                               border border-rose-500/20">

                                                        rafraîchir

                                                    </button>

                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        {{-- FOOTER --}}
                        <div class="p-5 border-t border-slate-800">

                            <div
                                class="flex flex-col
                                        lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                        gap-4">

                                <div class="text-sm text-slate-400">

                                    48 apprenants —
                                    42 notes déjà enregistrées

                                </div>

                                <div class="flex flex-wrap gap-3">

                                    <button
                                        class="h-11 px-6 rounded-2xl
                                                   bg-amber-500 hover:bg-amber-600">

                                        Réinitialiser Toutes les notes en cour

                                    </button>

                                    <button
                                        class="h-11 px-6 rounded-2xl
                                                   bg-emerald-500 hover:bg-emerald-600">

                                        Valider Toutes les Notes

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

