<div class="space-y-6 p-3">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl border border-slate-800
               bg-slate-900/80 p-5 sm:p-6">

        <div class="flex flex-col
                   xl:flex-row xl:items-center
                   xl:justify-between gap-6">

            {{-- LEFT --}}
            <div class="min-w-0">

                <div
                    class="inline-flex items-center gap-2
                           px-3 py-1 rounded-full
                           bg-indigo-500/10
                           border border-indigo-500/20
                           text-indigo-300 text-xs font-medium">

                    <x-lucide-credit-card class="w-4 h-4" />

                    Gestion des abonnements

                </div>

                <h1 class="mt-4 text-2xl sm:text-4xl
                           font-black tracking-tight">

                    Demandes d’abonnement

                </h1>

                <p class="mt-3 text-sm sm:text-base
                           text-slate-400 max-w-3xl">

                    Gérez les demandes d’abonnement des établissements,
                    validez les packs, approuvez les accès ou notifiez
                    les directeurs concernés.

                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row
                       gap-3 w-full xl:w-auto">

                <button class="h-12 px-5 rounded-2xl
                           bg-emerald-500 hover:bg-emerald-400
                           text-white font-semibold
                           flex items-center justify-center gap-2">

                    <x-lucide-check-check class="w-5 h-5" />

                    Tout approuver

                </button>

                <button
                    class="h-12 px-5 rounded-2xl
                           bg-rose-500/10
                           hover:bg-rose-500/20
                           text-rose-400 font-semibold
                           border border-rose-500/20
                           flex items-center justify-center gap-2">

                    <x-lucide-trash-2 class="w-5 h-5" />

                    Tout supprimer

                </button>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- FILTERS --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl border border-slate-800
               bg-slate-900/80 p-5 sm:p-6">

        <div class="flex flex-col
                   2xl:flex-row
                   2xl:items-center
                   2xl:justify-between
                   gap-4">

            {{-- SEARCH --}}
            <div class="relative w-full 2xl:max-w-md">

                <x-lucide-search class="w-4 h-4 absolute
                           left-4 top-1/2
                           -translate-y-1/2
                           text-slate-500" />

                <input type="text" placeholder="Rechercher une école, un directeur, un email..."
                    class="w-full h-12 rounded-2xl
                           bg-slate-800 border border-slate-700
                           pl-11 pr-4 text-sm
                           focus:outline-none
                           focus:ring-2 focus:ring-indigo-500/40">

            </div>

            {{-- SELECTS --}}
            <div class="grid grid-cols-1
                       sm:grid-cols-2
                       xl:grid-cols-4 gap-3
                       w-full 2xl:w-auto">

                <select class="h-12 px-4 rounded-2xl
                           bg-slate-800 border border-slate-700
                           text-sm">

                    <option>Tous les statuts</option>
                    <option>En attente</option>
                    <option>Approuvées</option>
                    <option>Rejetées</option>

                </select>

                <select class="h-12 px-4 rounded-2xl
                           bg-slate-800 border border-slate-700
                           text-sm">

                    <option>Tous les packs</option>
                    <option>Basic</option>
                    <option>Premium</option>
                    <option>Entreprise</option>

                </select>

                <select class="h-12 px-4 rounded-2xl
                           bg-slate-800 border border-slate-700
                           text-sm">

                    <option>Tous les enseignements</option>
                    <option>Général</option>
                    <option>Technique</option>
                    <option>Professionnel</option>

                </select>

                <button class="h-12 px-5 rounded-2xl
                           bg-indigo-500 hover:bg-indigo-400
                           text-white font-semibold
                           flex items-center justify-center gap-2">

                    <x-lucide-filter class="w-4 h-4" />

                    Filtrer

                </button>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- TABLE --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl border border-slate-800
               bg-slate-900/80 overflow-hidden">

        {{-- HEADER --}}
        <div class="p-5 sm:p-6 border-b border-slate-800
                   flex flex-col xl:flex-row
                   xl:items-center xl:justify-between gap-4">

            <div>

                <h2 class="text-xl font-bold">
                    Liste des demandes
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Validation et supervision des abonnements
                </p>

            </div>

            {{-- EXPORT --}}
            <div class="flex flex-wrap gap-3">

                <button
                    class="h-11 px-4 rounded-xl
                           bg-emerald-500/10
                           hover:bg-emerald-500/20
                           text-emerald-400
                           flex items-center gap-2">

                    <x-lucide-file-spreadsheet class="w-4 h-4" />

                    Excel

                </button>

                <button class="h-11 px-4 rounded-xl
                           bg-rose-500/10
                           hover:bg-rose-500/20
                           text-rose-400
                           flex items-center gap-2">

                    <x-lucide-file-text class="w-4 h-4" />

                    PDF

                </button>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="min-w-[1700px] w-full">

                <thead class="bg-slate-950">

                    <tr>

                        @foreach (['École', 'Directeur', 'Téléphone', 'Email', 'Type', 'Pack', 'Date', 'Statut', 'Actions'] as $head)
                            <th
                                class="px-6 py-4 text-left
                                       text-xs uppercase tracking-wider
                                       font-semibold text-slate-400
                                       border-b border-r border-slate-800">

                                {{ $head }}

                            </th>
                        @endforeach

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-800">

                    @foreach (range(1, 10) as $item)
                        <tr class="hover:bg-slate-800/40
                                   transition-colors duration-200">

                            {{-- SCHOOL --}}
                            <td class="px-6 py-5 border-r border-slate-800">

                                <div class="flex items-center gap-3">

                                    <div class="w-12 h-12 rounded-2xl
                                               bg-indigo-500/10
                                               flex items-center justify-center">

                                        <x-lucide-school class="w-6 h-6 text-indigo-400" />

                                    </div>

                                    <div>

                                        <p class="font-semibold whitespace-nowrap">
                                            Lycée Technique {{ $item }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Cotonou
                                        </p>

                                    </div>

                                </div>

                            </td>

                            {{-- DIRECTOR --}}
                            <td class="px-6 py-5 whitespace-nowrap
                                       border-r border-slate-800">

                                M. HOUNGBEDJI

                            </td>

                            {{-- PHONE --}}
                            <td class="px-6 py-5 whitespace-nowrap
                                       border-r border-slate-800">

                                +229 01 90 00 00 00

                            </td>

                            {{-- EMAIL --}}
                            <td class="px-6 py-5 whitespace-nowrap
                                       border-r border-slate-800">

                                directeur{{ $item }}@mail.com

                            </td>

                            {{-- TYPE --}}
                            <td class="px-6 py-5 whitespace-nowrap
                                       border-r border-slate-800">

                                Technique

                            </td>

                            {{-- PACK --}}
                            <td class="px-6 py-5 whitespace-nowrap
                                       border-r border-slate-800">

                                <span
                                    class="inline-flex items-center
                                           px-3 py-1 rounded-full
                                           bg-indigo-500/10
                                           text-indigo-400 text-xs">

                                    Premium

                                </span>

                            </td>

                            {{-- DATE --}}
                            <td class="px-6 py-5 whitespace-nowrap
                                       border-r border-slate-800">

                                21 Mai 2026

                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-5 whitespace-nowrap
                                       border-r border-slate-800">

                                <span
                                    class="inline-flex items-center
                                           px-3 py-1 rounded-full
                                           bg-amber-500/10
                                           text-amber-400 text-xs font-medium">

                                    En attente

                                </span>

                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-2">

                                    {{-- APPROVE --}}
                                    <button
                                        class="w-10 h-10 rounded-xl
                                               bg-emerald-500/10
                                               hover:bg-emerald-500/20
                                               text-emerald-400
                                               flex items-center justify-center">

                                        <x-lucide-check class="w-5 h-5" />

                                    </button>

                                    {{-- REJECT --}}
                                    <button
                                        class="w-10 h-10 rounded-xl
                                               bg-amber-500/10
                                               hover:bg-amber-500/20
                                               text-amber-400
                                               flex items-center justify-center">

                                        <x-lucide-x class="w-5 h-5" />

                                    </button>

                                    {{-- NOTIFY --}}
                                    <button
                                        class="w-10 h-10 rounded-xl
                                               bg-sky-500/10
                                               hover:bg-sky-500/20
                                               text-sky-400
                                               flex items-center justify-center">

                                        <x-lucide-bell class="w-5 h-5" />

                                    </button>

                                    {{-- DELETE --}}
                                    <button
                                        class="w-10 h-10 rounded-xl
                                               bg-rose-500/10
                                               hover:bg-rose-500/20
                                               text-rose-400
                                               flex items-center justify-center">

                                        <x-lucide-trash-2 class="w-5 h-5" />

                                    </button>

                                </div>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="p-5 sm:p-6 border-t border-slate-800
                   flex flex-col sm:flex-row
                   sm:items-center sm:justify-between gap-4">

            <p class="text-sm text-slate-400">
                Affichage de 1 à 10 sur 128 demandes
            </p>

            <div class="flex items-center gap-2">

                <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700
                           flex items-center justify-center">

                    <x-lucide-chevron-left class="w-4 h-4" />

                </button>

                <button class="w-10 h-10 rounded-xl
                           bg-indigo-500 text-white font-semibold">
                    1
                </button>

                <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700">
                    2
                </button>

                <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700">
                    3
                </button>

                <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700
                           flex items-center justify-center">

                    <x-lucide-chevron-right class="w-4 h-4" />

                </button>

            </div>

        </div>

    </section>

</div>
