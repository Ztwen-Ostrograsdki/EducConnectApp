<div class="rounded-3xl
            bg-slate-900
            border border-slate-800
            overflow-hidden">

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

                    <th class="px-6 py-4 text-right text-sm text-slate-400">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-800">

                @foreach(range(1,10) as $subject)

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

                            <button class="h-10 px-4 rounded-xl
                                           bg-indigo-500/10
                                           text-indigo-400">

                                Profil

                            </button>

                            <button class="h-10 px-4 rounded-xl
                                           bg-emerald-500/10
                                           text-emerald-400">

                                Statistiques

                            </button>

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>