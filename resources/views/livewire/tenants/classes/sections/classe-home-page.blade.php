<div class="min-h-screen bg-slate-950 text-slate-100 overflow-x-hidden">

    {{-- PAGE WRAPPER --}}
    <div class="w-full overflow-x-hidden">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}
        <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl rounded-2xl">
            <div class="px-3 py-2">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    {{-- LEFT --}}
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-xl sm:text-2xl font-bold break-words">
                                Détails Généraux
                            </h1>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <button class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-red-500 hover:bg-red-600 transition-all duration-300 text-sm sm:text-base">
                            Fermer cette classe
                        </button>
                        <button class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300 text-sm sm:text-base">
                            Verrouiller les notes
                        </button>
                    </div>

                </div>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- KPI CARDS --}}
        {{-- ===================================================== --}}
        <section class="w-full max-w-full p-4 overflow-hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">

                {{-- CARD --}}
                <div class="min-w-0 overflow-hidden rounded-3xl border border-slate-800 bg-slate-900 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm text-slate-400 truncate">Élèves</p>
                            <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">42</h2>
                        </div>
                        <div class="shrink-0 w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center">
                            👨‍🎓
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-emerald-400 truncate">+12% ce trimestre</div>
                </div>

                {{-- CARD --}}
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">
                    <p class="text-sm text-slate-400 truncate">Enseignants</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">8</h2>
                </div>

                {{-- CARD --}}
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">
                    <p class="text-sm text-slate-400 truncate">Présence</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">96%</h2>
                </div>

                {{-- CARD --}}
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">
                    <p class="text-sm text-slate-400 truncate">Moyenne</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">14.7</h2>
                </div>

            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- CONTENT --}}
        {{-- ===================================================== --}}
        <section class="w-full max-w-full p-2 overflow-hidden">
            <div class="grid grid-cols-1 2xl:grid-cols-3 gap-4 sm:gap-6">

                {{-- LEFT --}}
                <div class="2xl:col-span-2 min-w-0 space-y-6">

                    {{-- STUDENTS --}}
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

                        {{-- HEADER --}}
                        <div class="p-4 sm:p-5 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-base sm:text-lg truncate">Derniers Élèves</h3>
                                <p class="mt-1 text-sm text-slate-400 truncate">Liste récente des inscriptions</p>
                            </div>
                            <button class="text-indigo-400 text-sm shrink-0">Voir tout</button>
                        </div>

                        {{-- LIST --}}
                        <div class="divide-y divide-slate-800">
                            @foreach (range(1, 5) as $i)
                                <div class="p-4 sm:p-5">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-w-0">

                                        {{-- LEFT --}}
                                        <div class="flex items-center gap-4 min-w-0 flex-1">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-800 shrink-0"></div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-medium truncate">Élève {{ $i }}</h4>
                                                <p class="text-sm text-slate-400 truncate">Matricule #458{{ $i }}</p>
                                            </div>
                                        </div>

                                        {{-- RIGHT --}}
                                        <div class="text-sm text-slate-400 shrink-0">Moyenne : 15.2</div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    {{-- TIMETABLE --}}
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5 overflow-hidden">
                        <div class="min-w-0">
                            <h3 class="font-semibold text-base sm:text-lg truncate">Emploi du Temps</h3>
                            <p class="mt-1 text-sm text-slate-400 truncate">Planning hebdomadaire</p>
                        </div>
                        <div class="mt-6 h-64 sm:h-80 rounded-2xl border border-dashed border-slate-700 flex items-center justify-center text-center text-slate-500 p-4 overflow-hidden">
                            Tableau Emploi du Temps
                        </div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="min-w-0 space-y-6">

                    {{-- PROF --}}
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5 overflow-hidden">
                        <h3 class="font-semibold text-base sm:text-lg">Professeur Principal</h3>
                        <div class="mt-5 flex items-center gap-4 min-w-0">
                            <div class="w-16 h-16 rounded-2xl bg-slate-800 shrink-0"></div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-semibold truncate">M. HOUNDEKINDO</h4>
                                <p class="text-sm text-slate-400 truncate">Génie Électrique</p>
                            </div>
                        </div>
                    </div>

                    {{-- STATS --}}
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5 overflow-hidden">
                        <h3 class="font-semibold text-base sm:text-lg">Statistiques</h3>
                        <div class="mt-5 space-y-5">

                            {{-- BAR --}}
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <span class="text-sm truncate">Présence</span>
                                    <span class="text-sm shrink-0">96%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-800 overflow-hidden">
                                    <div class="h-full w-[96%] bg-emerald-500 rounded-full"></div>
                                </div>
                            </div>

                            {{-- BAR --}}
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <span class="text-sm truncate">Réussite</span>
                                    <span class="text-sm shrink-0">82%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-800 overflow-hidden">
                                    <div class="h-full w-[82%] bg-indigo-500 rounded-full"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </section>

    </div>

</div>
