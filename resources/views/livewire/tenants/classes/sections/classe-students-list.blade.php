<div class="min-h-screen bg-slate-950 text-slate-100 w-full
                max-w-full
                overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- PAGE WRAPPER --}}
    {{-- ===================================================== --}}
    <div class="w-full max-w-[100vw] overflow-x-hidden">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl">

            <div class="px-2 sm:px-3 lg:px-5 py-5">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    {{-- LEFT --}}
                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-xl sm:text-2xl font-bold break-words">
                                Liste des apprenants
                            </h1>

                            <span class="px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         border border-indigo-500/20
                                         text-indigo-400 text-xs shrink-0">

                                42 élèves

                            </span>

                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                        <button class="w-full sm:w-auto
                                       px-5 py-3 rounded-2xl
                                       bg-slate-800
                                       border border-slate-700
                                       hover:bg-slate-700
                                       transition-all duration-300
                                       text-sm sm:text-base">

                            Exporter liste PDF

                        </button>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- TOOLBAR --}}
        {{-- ===================================================== --}}
        <section class="py-4">

            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <div class="flex flex-col xl:flex-row gap-4">

                    {{-- SEARCH --}}
                    <div class="flex-1 min-w-0">

                        <div class="relative">

                            <input
                                type="text"
                                placeholder="Rechercher un apprenant..."
                                class="w-full
                                       h-12
                                       rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       pl-12 pr-4
                                       text-sm
                                       outline-none
                                       focus:border-indigo-500
                                       transition-all"
                            >

                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">

                                🔍

                            </div>

                        </div>

                    </div>

                    {{-- FILTERS --}}
                    <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:flex
                                gap-3">

                        <select class="h-12 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Tous les genres</option>
                            <option>Masculin</option>
                            <option>Féminin</option>

                        </select>

                        <select class="h-12 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes les moyennes</option>
                            <option>Excellent</option>
                            <option>Faible</option>

                        </select>

                        <button class="h-12 px-5 rounded-2xl
                                       bg-slate-800
                                       border border-slate-700
                                       hover:bg-slate-700
                                       transition-all
                                       text-sm">

                            Réinitialiser

                        </button>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- TABLE DESKTOP --}}
        {{-- ===================================================== --}}
        <section class="hidden xl:block">

            <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full min-w-full">

                        <thead class="bg-slate-950 border-b border-slate-800">

                            <tr>

                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                    Apprenant
                                </th>

                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                    Matricule
                                </th>

                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                    Moyenne
                                </th>

                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                    Présence
                                </th>

                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                    Parent
                                </th>

                                <th class="text-right px-6 py-4 text-sm font-medium text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @foreach(range(1,10) as $i)

                            <tr  class="hover:bg-slate-800/40 transition-all">

                                {{-- STUDENT --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-4 min-w-0">

                                        <div class="w-12 h-12 rounded-2xl bg-slate-800 shrink-0">
                                        </div>

                                        <div class="min-w-0">

                                            <a href="{{route('tenant.student.profil', ['student_uuid' => rand(272252525, 7727277272772)])}}">
                                                <h3 class="font-medium truncate">
                                                    Kouassi Vincent {{$i}}
                                                </h3>

                                                <p class="text-sm text-slate-400 truncate">
                                                    Génie Électrique
                                                </p>
                                            </a>

                                        </div>

                                    </div>

                                </td>

                                {{-- MATRICULE --}}
                                <td class="px-6 py-5 text-sm text-slate-300">
                                    MAT-2025-{{$i}}
                                </td>

                                {{-- MOYENNE --}}
                                <td class="px-6 py-5">

                                    <div class="inline-flex
                                                items-center
                                                px-3 py-1
                                                rounded-full
                                                bg-emerald-500/10
                                                text-emerald-400
                                                text-sm">

                                        15.4

                                    </div>

                                </td>

                                {{-- PRESENCE --}}
                                <td class="px-6 py-5 text-sm">
                                    96%
                                </td>

                                {{-- PARENT --}}
                                <td class="px-6 py-5 text-sm text-slate-300">
                                    M. HOUNDEKINDO
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center justify-end gap-2">

                                        <button class="w-10 h-10 rounded-xl
                                                       bg-slate-800
                                                       hover:bg-indigo-500
                                                       transition-all">

                                            👁

                                        </button>

                                        <button class="w-10 h-10 rounded-xl
                                                       bg-slate-800
                                                       hover:bg-emerald-500
                                                       transition-all">

                                            ✏

                                        </button>

                                        <button class="w-10 h-10 rounded-xl
                                                       bg-slate-800
                                                       hover:bg-rose-500
                                                       transition-all">

                                            🗑

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

        {{-- ===================================================== --}}
        {{-- MOBILE / TABLET CARDS --}}
        {{-- ===================================================== --}}
        <section class="xl:hidden px-1 pb-8">

            <div class="space-y-4">

                @foreach(range(1,10) as $i)

                <div class="rounded-3xl
                            border border-slate-800
                            bg-slate-900
                            p-5
                            overflow-hidden">

                    {{-- TOP --}}
                    <div class="flex items-start gap-4 min-w-0">

                        <div class="w-14 h-14 rounded-2xl bg-slate-800 shrink-0">
                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex flex-wrap items-center gap-2">

                                <h3 class="font-semibold truncate">
                                    Kouassi Vincent {{$i}}
                                </h3>

                                <span class="px-2 py-1 rounded-full
                                             bg-emerald-500/10
                                             text-emerald-400
                                             text-xs shrink-0">

                                    15.4

                                </span>

                            </div>

                            <p class="mt-1 text-sm text-slate-400 truncate">
                                MAT-2025-{{$i}}
                            </p>

                        </div>

                    </div>

                    {{-- INFOS --}}
                    <div class="mt-5 grid grid-cols-2 gap-4">

                        <div>

                            <p class="text-xs text-slate-500">
                                Présence
                            </p>

                            <p class="mt-1 text-sm">
                                96%
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">
                                Parent
                            </p>

                            <p class="mt-1 text-sm truncate">
                                HOUNDEKINDO
                            </p>

                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-5 flex items-center gap-2">

                        <button class="flex-1 h-11 rounded-2xl
                                       bg-slate-800
                                       hover:bg-indigo-500
                                       transition-all
                                       text-sm">

                            <a href="{{route('tenant.student.profil', ['student_uuid' => 'f2-' . $i])}}" class="">
                                Voir profil
                            </a>

                        </button>

                        <button class="flex-1 h-11 rounded-2xl
                                       bg-slate-800
                                       hover:bg-emerald-500
                                       transition-all
                                       text-sm">

                            Modifier

                        </button>

                        <button class="w-11 h-11 rounded-2xl
                                       bg-slate-800
                                       hover:bg-rose-500
                                       transition-all
                                       shrink-0">

                            🗑

                        </button>

                    </div>

                </div>

                @endforeach

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}
        <section class="py-10">

            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4">

                <div class="flex flex-col sm:flex-row
                            sm:items-center
                            sm:justify-between
                            gap-4">

                    <div class="text-sm text-slate-400">

                        Affichage 1 à 10 sur 42 apprenants

                    </div>

                    <div class="flex items-center gap-2">

                        <button class="h-10 px-4 rounded-xl
                                       bg-slate-800
                                       hover:bg-slate-700
                                       transition-all
                                       text-sm">

                            Précédent

                        </button>

                        <button class="h-10 px-4 rounded-xl
                                       bg-indigo-500
                                       text-sm">

                            1

                        </button>

                        <button class="h-10 px-4 rounded-xl
                                       bg-slate-800
                                       hover:bg-slate-700
                                       transition-all
                                       text-sm">

                            2

                        </button>

                        <button class="h-10 px-4 rounded-xl
                                       bg-slate-800
                                       hover:bg-slate-700
                                       transition-all
                                       text-sm">

                            Suivant

                        </button>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>