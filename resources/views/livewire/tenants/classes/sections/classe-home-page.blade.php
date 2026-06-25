<div class="min-h-screen bg-slate-950 text-slate-100 overflow-x-hidden">
    <div class="w-full overflow-x-hidden">
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
                        <button
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-red-500 hover:bg-red-600 transition-all duration-300 text-sm sm:text-base">
                            Fermer cette classe
                        </button>
                        <button
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300 text-sm sm:text-base">
                            Verrouiller les notes
                        </button>
                    </div>

                </div>
            </div>
        </section>

        <section class="w-full max-w-full my-3 overflow-hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">

                <div class="min-w-0 overflow-hidden rounded-3xl border border-slate-800 bg-slate-900 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm text-slate-400 truncate">Élèves</p>
                            <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                                {{ __zero($classe->effectif()) }}
                            </h2>
                        </div>
                        <div class="shrink-0 w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center">
                            👨‍🎓
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-emerald-400 truncate">+12% ce trimestre</div>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">
                    <p class="text-sm text-slate-400 truncate">Enseignants</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                        {{ __zero($classe->teachersCount()) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">
                    <p class="text-sm text-slate-400 truncate">Présence</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate text-slate-600">
                        En cours...
                    </h2>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">
                    <p class="text-sm text-slate-400 truncate">Moyenne</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate text-slate-700">
                        En cours...
                    </h2>
                </div>

            </div>
        </section>

        <section class="w-full max-w-full my-3 overflow-hidden">
            <div class="grid grid-cols-1 2xl:grid-cols-3 gap-4 sm:gap-6">

                <div class="2xl:col-span-2 min-w-0 space-y-6">

                    <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

                        <div
                            class="p-4 sm:p-5 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-base sm:text-lg truncate">Récents Élèves ajoutés</h3>
                                <p class="mt-1 text-sm text-slate-400 truncate">Liste récente des ajouts
                                    <span class="text-gray-700 italic">Il y a deux semaines</span>
                                </p>
                            </div>
                            <button class="text-indigo-400 text-sm shrink-0">Voir tout</button>
                        </div>

                        <div class="divide-y divide-slate-800">
                            @foreach ($classe->recentStudentsMigratedsIntoClasse(2) as $student)
                                <div class="p-4 sm:p-5">
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-w-0">

                                        <div class="flex items-center gap-4 min-w-0 flex-1">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-800 shrink-0"></div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-medium truncate">Élève {{ $student->getFullName() }}
                                                </h4>
                                                <p class="text-sm text-slate-400 truncate">Matricule
                                                    #458{{ $student->matricule }}</p>
                                            </div>
                                        </div>

                                        <div class="text-xs font-mono text-slate-400 shrink-0">Ajouté à la classe le
                                            {{ __formatDate($student->currentYearlyAccess($classe->id)?->started_at) }}
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="min-w-0 space-y-6 text-slate-400 font-semibold">

                    {{-- PROF --}}
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5 overflow-hidden">
                        <h3 class="font-semibold text-base sm:text-lg">Professeur Principal</h3>
                        <div class="mt-5 flex items-center gap-4 min-w-0">
                            <div class="w-16 h-16 rounded-2xl bg-slate-800 shrink-0"></div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-semibold truncate">
                                    {{ $classe->principal ? $classe->principal?->getFullName() : 'Non encore défini' }}
                                </h4>
                                @if ($classe->principal?->getSubjectsForThisClasse($classe->id))
                                    <p class="text-sm text-slate-400 truncate flex flex-wrap gap-2">
                                        @foreach ($classe->principal?->getSubjectsForThisClasse($classe->id) as $classeSubject)
                                            <span>{{ $classeSubject->subject?->name }}</span>
                                        @endforeach
                                    </p>
                                @endif
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
                                    <span class="text-sm shrink-0 text-slate-600">En cours...</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-800 overflow-hidden">
                                    <div class="h-full w-[96%] bg-emerald-500 rounded-full"></div>
                                </div>
                            </div>

                            {{-- BAR --}}
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <span class="text-sm truncate">Réussite</span>
                                    <span class="text-sm shrink-0 text-slate-600">En cours...</span>
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

