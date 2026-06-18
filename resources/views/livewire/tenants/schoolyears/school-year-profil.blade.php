<div class="min-h-screen bg-slate-950 text-slate-100 w-full max-w-full px-3 overflow-x-hidden">

    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}
    <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl rounded-2xl mt-2.5">
        <div class="w-full max-w-full px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                {{-- LEFT --}}
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 min-w-0 flex-1">

                    {{-- ICON --}}
                    <div class="shrink-0 self-start">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                            {{ $school_year_slug }}
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold leading-tight break-words">
                                Année scolaire - <span class="text-amber-500 opacity-70">{{ $school_year_slug }}</span>
                            </h1>
                            <span class="shrink-0 px-3 py-1 rounded-full text-xs bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                Active
                            </span>
                        </div>
                        <p class="mt-3 text-sm sm:text-base text-slate-400 break-words">
                            Les détails génraux de l'année scolaire {{ $school_year_slug }}
                        </p>

                        {{-- META --}}
                        <div class="mt-4 flex flex-col sm:flex-row sm:flex-wrap gap-2 sm:gap-5 text-sm text-slate-400">
                            <div class="break-words">👨‍🏫 M. HOUNDEKINDO</div>
                            <div class="break-words">📍 Bloc B — Salle 14</div>
                            <div class="break-words">🕒 2025-2026</div>
                        </div>
                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                    <button class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all duration-300 text-sm sm:text-base">
                        Ajouter Élève
                    </button>
                    <button class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300 text-sm sm:text-base">
                        Modifier Classe
                    </button>
                </div>

            </div>
        </div>
    </section>

</div>

