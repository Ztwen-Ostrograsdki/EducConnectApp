<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        p-5 sm:p-6">

                <div class="flex flex-col
                            xl:flex-row
                            xl:items-center
                            xl:justify-between
                            gap-5">

                    <div>

                        <h1 class="text-2xl sm:text-3xl font-bold">

                            Notes de l’Apprenant

                        </h1>

                        <p class="mt-2 text-slate-400">

                            Consultation des notes,
                            moyennes et performances.

                        </p>

                    </div>

                    <div class="flex flex-wrap gap-3">

                        {{-- SELECT ENFANT --}}
                        <select class="h-12 min-w-[250px]
                                       rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       px-4">

                            <option>KOUASSI Sarah — Tle F2-1</option>
                            <option>KOUASSI David — 1ère F3</option>

                        </select>

                        {{-- SEMESTRE --}}
                        <select class="h-12 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       px-4">

                            <option>Semestre 1</option>
                            <option>Semestre 2</option>

                        </select>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- TABLE NOTES --}}
        {{-- ===================================================== --}}
        <section>

            <div class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-[1400px] w-full">

                        <thead class="bg-slate-950
                                     border-b border-slate-800">

                            <tr>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Matière
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Interro
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Devoir
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Moyenne
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Coef
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Moy. Coef
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Enseignant
                                </th>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Observation
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @foreach(range(1,12) as $note)

                            <tr class="hover:bg-slate-800/40">

                                <td class="px-6 py-5 font-medium">

                                    Mathématiques

                                </td>

                                <td class="px-4 py-5 text-center">

                                    14

                                </td>

                                <td class="px-4 py-5 text-center">

                                    16

                                </td>

                                <td class="px-4 py-5 text-center
                                           text-emerald-400
                                           font-semibold">

                                    15

                                </td>

                                <td class="px-4 py-5 text-center">

                                    4

                                </td>

                                <td class="px-4 py-5 text-center">

                                    60

                                </td>

                                <td class="px-6 py-5">

                                    M. AHOLOU

                                </td>

                                <td class="px-6 py-5">

                                    Très Bien

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- FOOTER --}}
                <div class="p-5 border-t border-slate-800">

                    <div class="grid
                                grid-cols-1
                                md:grid-cols-2
                                xl:grid-cols-4
                                gap-4">

                        @foreach([
                            ['Moyenne Générale','14.22'],
                            ['Rang','5ème'],
                            ['Taux Réussite','87%'],
                            ['Appréciation','Très Bien']
                        ] as $stat)

                        <div class="rounded-2xl
                                    bg-slate-950
                                    border border-slate-800
                                    p-4">

                            <p class="text-sm text-slate-400">

                                {{ $stat[0] }}

                            </p>

                            <h3 class="mt-2 text-xl font-bold">

                                {{ $stat[1] }}

                            </h3>

                        </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>