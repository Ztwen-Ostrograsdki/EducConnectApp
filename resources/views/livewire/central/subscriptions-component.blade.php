{{-- ===================================================== --}}
{{-- ABONNEMENTS CARDS --}}
{{-- ===================================================== --}}
<section class="space-y-6 p-2">

    {{-- HEADER --}}
    <div class="rounded-3xl border border-slate-800
               bg-slate-900/80 p-5 sm:p-6">

        <div class="flex flex-col xl:flex-row
                   xl:items-center xl:justify-between
                   gap-5">

            {{-- TITLE --}}
            <div>

                <div
                    class="inline-flex items-center gap-2
                           px-3 py-1 rounded-full
                           bg-indigo-500/10
                           border border-indigo-500/20
                           text-indigo-300 text-xs font-semibold">

                    <x-lucide-credit-card class="w-4 h-4" />

                    Gestion des abonnements

                </div>

                <h2 class="mt-4 text-2xl sm:text-3xl font-black">

                    Abonnements des établissements

                </h2>

                <p class="mt-2 text-sm text-slate-400">

                    Supervision des souscriptions, renouvellements,
                    rappels et statuts des abonnements.

                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-wrap gap-3">

                <button class="h-11 px-5 rounded-2xl
                           bg-indigo-500 hover:bg-indigo-400
                           text-white font-semibold
                           flex items-center gap-2">

                    <x-lucide-plus class="w-4 h-4" />

                    Nouvel abonnement

                </button>

                <button
                    class="h-11 px-5 rounded-2xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700
                           text-slate-200 font-medium
                           flex items-center gap-2">

                    <x-lucide-download class="w-4 h-4" />

                    Exporter

                </button>

            </div>

        </div>

        {{-- FILTERS --}}
        <div class="mt-6 grid grid-cols-1
                   md:grid-cols-2
                   xl:grid-cols-4 gap-4">

            {{-- SEARCH --}}
            <div class="xl:col-span-2 relative">

                <x-lucide-search class="w-4 h-4 absolute left-4 top-1/2
                           -translate-y-1/2 text-slate-500" />

                <input type="text" placeholder="Rechercher une école, directeur..."
                    class="w-full h-12 rounded-2xl
                           bg-slate-800 border border-slate-700
                           pl-11 pr-4 text-sm
                           focus:outline-none
                           focus:border-indigo-500">

            </div>

            {{-- TYPE --}}
            <select class="h-12 rounded-2xl
                       bg-slate-800 border border-slate-700
                       px-4 text-sm">

                <option>Tous les types</option>
                <option>Technique</option>
                <option>Général</option>
                <option>Hybride</option>

            </select>

            {{-- STATUS --}}
            <select class="h-12 rounded-2xl
                       bg-slate-800 border border-slate-700
                       px-4 text-sm">

                <option>Tous les statuts</option>
                <option>Actif</option>
                <option>Expiré</option>
                <option>Suspendu</option>

            </select>

        </div>

    </div>

    {{-- ===================================================== --}}
    {{-- GRID --}}
    {{-- ===================================================== --}}
    <div class="grid grid-cols-1
               lg:grid-cols-2
               2xl:grid-cols-2 gap-6">

        @foreach (range(1, 4) as $item)
            <div
                class="group relative overflow-hidden
                       rounded-3xl border border-slate-800
                       bg-slate-900/80
                       hover:border-indigo-500/30
                       transition-all duration-300">

                {{-- TOP BAR --}}
                <div class="h-1 w-full
                           bg-gradient-to-r
                           from-indigo-500
                           via-sky-500
                           to-cyan-400">

                </div>

                {{-- BG ICON --}}
                <div class="absolute -right-6 -top-6
                           opacity-5 group-hover:opacity-10
                           transition-all duration-300">

                    <x-lucide-school class="w-36 h-36" />

                </div>

                <div class="relative p-5 sm:p-6">

                    {{-- SCHOOL --}}
                    <div class="flex items-start justify-between gap-4">

                        <div class="flex items-center gap-4 min-w-0">

                            {{-- LOGO --}}
                            <div
                                class="w-16 h-16 rounded-2xl
                                       bg-indigo-500/10
                                       border border-indigo-500/20
                                       flex items-center justify-center
                                       shrink-0">

                                <x-lucide-school class="w-8 h-8 text-indigo-400" />

                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">

                                <h3 class="text-lg font-bold truncate">

                                    Lycée Technique {{ $item }}

                                </h3>

                                <p class="text-sm text-slate-400 truncate">

                                    Enseignement Technique

                                </p>

                                <div
                                    class="mt-2 inline-flex items-center
                                           gap-2 px-3 py-1 rounded-full
                                           bg-emerald-500/10
                                           text-emerald-400
                                           text-xs font-semibold">

                                    <span class="w-2 h-2 rounded-full
                                               bg-emerald-400">

                                    </span>

                                    Actif

                                </div>

                            </div>

                        </div>

                        {{-- DAYS --}}
                        <div class="shrink-0 text-right">

                            <p class="text-xs text-slate-500">

                                Restants

                            </p>

                            <p class="mt-1 text-xl font-black
                                       text-emerald-400">

                                98j

                            </p>

                        </div>

                    </div>

                    {{-- DIRECTOR --}}
                    <div class="mt-6 rounded-2xl
                               border border-slate-800
                               bg-slate-950/40 p-4">

                        <div class="flex items-center gap-3">

                            <div class="w-12 h-12 rounded-2xl
                                       bg-slate-800
                                       flex items-center justify-center">

                                <x-lucide-user class="w-5 h-5 text-slate-300" />

                            </div>

                            <div class="min-w-0">

                                <p class="font-semibold truncate">

                                    M. Houngbedji K.

                                </p>

                                <p class="text-xs text-slate-500">

                                    Directeur Général

                                </p>

                            </div>

                        </div>

                        {{-- CONTACTS --}}
                        <div class="mt-4 grid grid-cols-1 gap-3">

                            <div class="flex items-center gap-3
                                       text-sm text-slate-300">

                                <x-lucide-mail class="w-4 h-4 text-slate-500 shrink-0" />

                                <span class="truncate">
                                    directeur@mail.com
                                </span>

                            </div>

                            <div class="flex items-center gap-3
                                       text-sm text-slate-300">

                                <x-lucide-phone class="w-4 h-4 text-slate-500 shrink-0" />

                                +229 97 00 00 00

                            </div>

                            <div class="flex items-center gap-3
                                       text-sm text-slate-300">

                                <x-lucide-map-pin class="w-4 h-4 text-slate-500 shrink-0" />

                                Cotonou, Bénin

                            </div>

                        </div>

                    </div>

                    {{-- STATS --}}
                    <div class="mt-5 grid grid-cols-2 gap-4">

                        <div class="rounded-2xl border border-slate-800
                                   bg-slate-950/40 p-4">

                            <p class="text-xs text-slate-500">

                                Début

                            </p>

                            <p class="mt-2 font-bold">

                                12 Jan 2026

                            </p>

                        </div>

                        <div class="rounded-2xl border border-slate-800
                                   bg-slate-950/40 p-4">

                            <p class="text-xs text-slate-500">

                                Expiration

                            </p>

                            <p class="mt-2 font-bold text-rose-400">

                                12 Jan 2027

                            </p>

                        </div>

                    </div>

                    {{-- PACK --}}
                    <div class="mt-5 rounded-2xl
                               border border-sky-500/20
                               bg-sky-500/5 p-4">

                        <div class="flex items-center justify-between gap-3">

                            <div>

                                <p class="text-xs text-slate-500">

                                    Pack souscrit

                                </p>

                                <p class="mt-1 text-lg font-black
                                           text-sky-400">

                                    Premium Annual

                                </p>

                            </div>

                            <div class="w-12 h-12 rounded-2xl
                                       bg-sky-500/10
                                       flex items-center justify-center">

                                <x-lucide-crown class="w-6 h-6 text-sky-400" />

                            </div>

                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-6 flex flex-wrap gap-3">

                        {{-- MESSAGE --}}
                        <button
                            class="flex-1 min-w-[120px]
                                   h-11 rounded-2xl
                                   bg-slate-800 hover:bg-slate-700
                                   border border-slate-700
                                   text-slate-200
                                   flex items-center justify-center gap-2
                                   transition-all duration-200">

                            <x-lucide-message-square class="w-4 h-4" />

                            Message

                        </button>

                        {{-- REMINDER --}}
                        <button
                            class="flex-1 min-w-[120px]
                                   h-11 rounded-2xl
                                   bg-amber-500/10
                                   hover:bg-amber-500/20
                                   text-amber-400
                                   flex items-center justify-center gap-2
                                   transition-all duration-200">

                            <x-lucide-bell-ring class="w-4 h-4" />

                            Rappel

                        </button>

                    </div>

                    {{-- ACTIONS 2 --}}
                    <div class="mt-3 grid grid-cols-3 gap-3">

                        {{-- EXTEND --}}
                        <button
                            class="h-11 rounded-2xl
                                   bg-indigo-500/10
                                   hover:bg-indigo-500/20
                                   text-indigo-400
                                   flex items-center justify-center">

                            <x-lucide-calendar-plus class="w-5 h-5" />

                        </button>

                        {{-- SUSPEND --}}
                        <button
                            class="h-11 rounded-2xl
                                   bg-orange-500/10
                                   hover:bg-orange-500/20
                                   text-orange-400
                                   flex items-center justify-center">

                            <x-lucide-ban class="w-5 h-5" />

                        </button>

                        {{-- DELETE --}}
                        <button
                            class="h-11 rounded-2xl
                                   bg-rose-500/10
                                   hover:bg-rose-500/20
                                   text-rose-400
                                   flex items-center justify-center">

                            <x-lucide-trash-2 class="w-5 h-5" />

                        </button>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    {{-- ===================================================== --}}
    {{-- PAGINATION --}}
    {{-- ===================================================== --}}
    <div class="rounded-3xl border border-slate-800
               bg-slate-900/80 p-5">

        <div class="flex flex-col sm:flex-row
                   sm:items-center sm:justify-between
                   gap-4">

            <p class="text-sm text-slate-400">

                Affichage de 1 à 9 sur 128 abonnements

            </p>

            <div class="flex items-center gap-2">

                <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700
                           flex items-center justify-center">

                    <x-lucide-chevron-left class="w-5 h-5" />

                </button>

                <button class="h-10 px-4 rounded-xl
                           bg-indigo-500 text-white font-semibold">

                    1

                </button>

                <button class="h-10 px-4 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700">

                    2

                </button>

                <button class="h-10 px-4 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700">

                    3

                </button>

                <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700
                           flex items-center justify-center">

                    <x-lucide-chevron-right class="w-5 h-5" />

                </button>

            </div>

        </div>

    </div>

</section>

