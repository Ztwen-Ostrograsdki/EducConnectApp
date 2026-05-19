<div class="grid
            grid-cols-1
            2xl:grid-cols-[minmax(0,1fr)_400px]
            gap-6">

    {{-- LEFT --}}
    <div class="space-y-6 min-w-0">

        {{-- HEADER --}}
        <div class="rounded-3xl
                    bg-slate-900
                    border border-slate-800
                    p-6">

            <div class="flex flex-col
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                        gap-5">

                <div>

                    <h1 class="text-3xl font-bold">

                        Promotion Sixième

                    </h1>

                    <p class="mt-2 text-slate-400">

                        Vue globale des classes de sixième

                    </p>

                </div>

                <div class="flex flex-wrap gap-3">

                    <button class="h-11 px-5 rounded-2xl
                                   bg-indigo-500">

                        Ajouter Classe

                    </button>

                    <button class="h-11 px-5 rounded-2xl
                                   bg-slate-800">

                        Exporter

                    </button>

                </div>

            </div>

        </div>

        {{-- CLASSES --}}
        <div class="rounded-3xl
                    bg-slate-900
                    border border-slate-800
                    overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-[1500px] w-full">

                    <thead class="bg-slate-950 border-b border-slate-800">

                        <tr>

                            <th class="px-6 py-4 text-left text-sm text-slate-400">
                                Classe
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Prof Principal
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Élèves
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Présence
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Réussite
                            </th>

                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                Moyenne
                            </th>

                            <th class="px-6 py-4 text-right text-sm text-slate-400">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-800">

                        @foreach(range(1,10) as $class)

                        <tr class="hover:bg-slate-800/40">

                            <td class="px-6 py-5 font-medium">

                                Sixième {{ $class }}

                            </td>

                            <td class="px-4 py-5 text-center">

                                Mme Ahouandjinou

                            </td>

                            <td class="px-4 py-5 text-center">

                                58

                            </td>

                            <td class="px-4 py-5 text-center">

                                <span class="text-emerald-400">

                                    96%

                                </span>

                            </td>

                            <td class="px-4 py-5 text-center">

                                <span class="text-indigo-400">

                                    89%

                                </span>

                            </td>

                            <td class="px-4 py-5 text-center">

                                13.42

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

                                        Notes

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

    {{-- RIGHT --}}
    <div class="space-y-6">

        {{-- STATS --}}
        <div class="rounded-3xl
                    bg-slate-900
                    border border-slate-800
                    p-5">

            <h2 class="text-lg font-semibold">

                Statistiques

            </h2>

            <div class="mt-5 space-y-5">

                @foreach([
                    ['Présence', '96%', 'bg-emerald-500'],
                    ['Réussite', '89%', 'bg-indigo-500'],
                    ['Discipline', '92%', 'bg-sky-500'],
                    ['Participation', '84%', 'bg-amber-500']
                ] as $item)

                <div>

                    <div class="flex justify-between">

                        <span class="text-sm text-slate-300">
                            {{ $item[0] }}
                        </span>

                        <span class="text-sm font-semibold">
                            {{ $item[1] }}
                        </span>

                    </div>

                    <div class="mt-2 h-2 rounded-full
                                bg-slate-800 overflow-hidden">

                        <div class="h-full rounded-full {{ $item[2] }}"
                             style="width: {{ $item[1] }}">
                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>

</div>