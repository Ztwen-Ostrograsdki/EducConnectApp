<div>
    <div
        class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-4 sm:p-6">

        <div class="flex items-center justify-between gap-4">

            <div>

                <h2 class="text-lg sm:text-xl font-semibold">
                    Emploi du Temps
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Planning hebdomadaire de l'enseignant
                </p>

            </div>

        </div>

        {{-- TIMETABLE --}}
        <div
            class="mt-6 grid
                                    grid-cols-1
                                    lg:grid-cols-2
                                    xl:grid-cols-3
                                    gap-4">

            @foreach (range(1, 6) as $course)
                <div
                    class="rounded-2xl
                                        border border-indigo-500/20
                                        bg-indigo-500/10
                                        p-4">

                    <div class="flex items-start justify-between gap-3">

                        <div>

                            <h3 class="font-semibold">
                                Terminale F2-1
                            </h3>

                            <p class="mt-1 text-sm text-indigo-300">
                                Mathématiques
                            </p>

                        </div>

                        <span
                            class="px-2 py-1 rounded-xl
                                                 bg-slate-950/40
                                                 text-xs">

                            Lundi

                        </span>

                    </div>

                    <div class="mt-5 space-y-2">

                        <div class="flex items-center justify-between text-sm">

                            <span class="text-slate-400">
                                Heure
                            </span>

                            <span>
                                08h00 - 10h00
                            </span>

                        </div>

                        <div class="flex items-center justify-between text-sm">

                            <span class="text-slate-400">
                                Salle
                            </span>

                            <span>
                                B12
                            </span>

                        </div>

                        <div class="flex items-center justify-between text-sm">

                            <span class="text-slate-400">
                                Durée
                            </span>

                            <span>
                                2h
                            </span>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>
</div>

