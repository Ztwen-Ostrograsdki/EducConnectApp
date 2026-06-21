<div class="min-h-screen bg-slate-950 text-slate-100 overflow-x-hidden">
    <div class="w-full max-w-[100vw] overflow-x-hidden ">
        <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl">
            <div class="px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    {{-- LEFT --}}
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl sm:text-3xl font-bold break-words">
                                Portail des années scolaires
                            </h1>
                            <span
                                class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs shrink-0">
                                24 années scolaires
                            </span>
                        </div>
                        <p class="mt-2 text-sm sm:text-base text-slate-400">
                            Gestion des salles, promotions, séries et enseignants.
                        </p>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <a href="{{ route('tenant.schoolYears.create') }}"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all duration-300 text-sm sm:text-base">
                            Ajouter une année scolaire
                        </a>
                        <button
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300 text-sm sm:text-base">
                            Exporter
                        </button>
                    </div>

                </div>
            </div>
        </section>

        <section class="px-4 sm:px-6 lg:px-8 my-3">
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">
                <div class="flex flex-col xl:flex-row gap-4">

                    {{-- SEARCH --}}
                    <div class="flex-1 min-w-0">
                        <div class="relative">
                            <input type="text" placeholder="Rechercher une année scholaire..."
                                class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm outline-none focus:border-indigo-500 transition-all">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">🔍</div>
                        </div>
                    </div>

                    {{-- FILTERS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:flex gap-3">
                        <select class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                            <option>Années scolaire avec période</option>
                            <option>semestre</option>
                            <option>Trimestre</option>
                            <option>Tout</option>
                        </select>
                        <select class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                            <option>Années scolaire</option>
                            <option>Actives</option>
                            <option>Non actives</option>
                            <option>Toutes</option>
                        </select>
                        <button
                            class="h-12 px-5 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all text-sm">
                            Réinitialiser
                        </button>
                    </div>

                </div>
            </div>
        </section>
        <section class="p-4 sm:p-6 lg:p-8 my-3">
            <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4 sm:gap-6">
                @foreach ($schoolYears as $school_year)
                    <div
                        class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden hover:border-indigo-500/30 transition-all duration-300 opacity-75 hover:opacity-100 hover:-translate-y-0.5">

                        {{-- TOP --}}
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                {{-- LEFT --}}
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="text-lg font-bold truncate">Année scolaire {{ $school_year->slug }}
                                        </h2>
                                        <span
                                            class="px-2 py-1 rounded-full bg-emerald-500/10 {{ $school_year->is_active ? 'text-emerald-400' : 'text-red-400' }} text-xs shrink-0">
                                            {{ $school_year->is_active ? 'Active' : 'Non active' }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-400 break-words">Génie Électrique & Électronique
                                    </p>
                                </div>

                                {{-- ICON --}}
                                <div
                                    class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center shrink-0">
                                    📅
                                </div>
                            </div>
                            {{-- STATS --}}
                            <div class="mt-6 grid grid-cols-2 gap-4">
                                <div class="rounded-2xl bg-slate-950 p-4">
                                    <p class="text-xs text-slate-500">Élèves</p>
                                    <h3 class="mt-2 text-xl font-bold">42</h3>
                                </div>
                                <div class="rounded-2xl bg-slate-950 p-4">
                                    <p class="text-xs text-slate-500">Enseignants</p>
                                    <h3 class="mt-2 text-xl font-bold">8</h3>
                                </div>
                                <div class="rounded-2xl bg-slate-950 p-4">
                                    <p class="text-xs text-slate-500">Classes</p>
                                    <h3 class="mt-2 text-xl font-bold">8</h3>
                                </div>
                                <div class="rounded-2xl bg-slate-950 p-4">
                                    <p class="text-xs text-slate-500">Taux de réussite</p>
                                    <h3 class="mt-2 text-xl font-bold">78 %</h3>
                                </div>
                            </div>

                            {{-- META --}}
                            <div class="mt-6 space-y-3 text-sm text-slate-400">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="truncate">Type de période</span>
                                    <span class="truncate text-slate-300">Semestre</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="truncate">Debut : 15 Oct 2025</span>
                                    <span class="truncate text-slate-300">Fin: 22 Juil 2025</span>
                                </div>
                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="border-t border-slate-800 p-4">
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('tenant.schoolyear.profil', ['school_year' => $school_year->slug]) }}"
                                    class="rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm flex items-center justify-center">
                                    Voir détails
                                </a>
                                <button class="h-11 rounded-2xl bg-slate-800 hover:bg-slate-700 transition-all text-sm">
                                    Modifier
                                </button>
                            </div>

                            {{-- QUICK ACTIONS --}}
                            <div class="mt-3 flex items-center gap-2">
                                <button
                                    class="flex-1 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-xs">
                                    Vérouillez notes
                                </button>
                                <button
                                    class="flex-1 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-xs">
                                    Fermer
                                </button>

                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}
        <section class="px-4 sm:px-6 lg:px-8 pb-10">
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div class="text-sm text-slate-400">
                        Affichage 1 à 9 sur 24 classes
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <button class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm">
                            Précédent
                        </button>
                        <button class="h-10 px-4 rounded-xl bg-indigo-500 text-sm">1</button>
                        <button
                            class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm">2</button>
                        <button class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm">
                            Suivant
                        </button>
                    </div>

                </div>
            </div>
        </section>

    </div>

</div>

