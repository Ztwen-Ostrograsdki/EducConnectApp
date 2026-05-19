<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1850px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- HEADER --}}
        <section class="mb-6">

            <div class="flex flex-col
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                        gap-5">

                <div>

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl sm:text-3xl font-bold">

                            Promotions

                        </h1>

                        <span class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400 text-xs">

                            12 Promotions

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm">

                        Gestion des promotions académiques

                    </p>

                </div>

                <div class="flex flex-wrap gap-3">

                    <button class="h-11 px-5 rounded-2xl
                                   bg-slate-800 hover:bg-slate-700">

                        Exporter

                    </button>

                    <button class="h-11 px-5 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-600">

                        Ajouter Promotion

                    </button>

                </div>

            </div>

        </section>

        {{-- KPI --}}
        <section class="mb-6">

            <div class="grid
                        grid-cols-2
                        xl:grid-cols-4
                        gap-4">

                @foreach([
                    ['Promotions', '12', 'text-indigo-400'],
                    ['Classes', '84', 'text-emerald-400'],
                    ['Élèves', '4821', 'text-sky-400'],
                    ['Réussite', '91%', 'text-amber-400']
                ] as $item)

                <div class="rounded-3xl
                            bg-slate-900
                            border border-slate-800
                            p-5">

                    <p class="text-sm text-slate-400">
                        {{ $item[0] }}
                    </p>

                    <h2 class="mt-3 text-3xl font-bold {{ $item[2] }}">
                        {{ $item[1] }}
                    </h2>

                </div>

                @endforeach

            </div>

        </section>

        {{-- FILTERS --}}
        <section class="mb-6">

            <div class="rounded-3xl
                        bg-slate-900
                        border border-slate-800
                        p-5">

                <div class="grid
                            grid-cols-1
                            md:grid-cols-2
                            xl:grid-cols-5
                            gap-3">

                    <input
                        type="text"
                        placeholder="Rechercher une promotion..."
                        class="h-11 rounded-2xl
                               bg-slate-950
                               border border-slate-800
                               px-4"
                    >

                    <select class="h-11 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800 px-4">

                        <option>Taux réussite</option>

                    </select>

                    <select class="h-11 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800 px-4">

                        <option>Effectifs</option>

                    </select>

                    <button class="h-11 rounded-2xl
                                   bg-indigo-500 hover:bg-indigo-600">

                        Filtrer

                    </button>

                    <button class="h-11 rounded-2xl
                                   bg-slate-800 hover:bg-slate-700">

                        Réinitialiser

                    </button>

                </div>

            </div>

        </section>

        {{-- TABLE --}}
        <section>

            <div class="rounded-3xl
                        bg-slate-900
                        border border-slate-800
                        overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-[1400px] w-full">

                        <thead class="bg-slate-950 border-b border-slate-800">

                            <tr>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Promotion
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Classes
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Élèves
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Réussite
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Responsable
                                </th>

                                <th class="px-6 py-4 text-right text-sm text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @foreach(range(1,8) as $promotion)

                            <tr class="hover:bg-slate-800/40">

                                {{-- PROMOTION --}}
                                <td class="px-6 py-5">

                                    <a href="#"
                                       class="font-semibold text-indigo-400 hover:underline">

                                        Sixième

                                    </a>

                                </td>

                                {{-- CLASSES --}}
                                <td class="px-4 py-5 text-center">

                                    12

                                </td>

                                {{-- STUDENTS --}}
                                <td class="px-4 py-5 text-center">

                                    684

                                </td>

                                {{-- SUCCESS --}}
                                <td class="px-4 py-5 text-center">

                                    <span class="text-emerald-400">

                                        92%

                                    </span>

                                </td>

                                {{-- MANAGER --}}
                                <td class="px-4 py-5 text-center">

                                    M. Kouassi

                                </td>

                                {{-- ACTIONS --}}
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

                                            Classes

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

</div>
