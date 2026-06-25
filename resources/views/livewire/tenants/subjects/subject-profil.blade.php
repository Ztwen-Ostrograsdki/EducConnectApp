<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8">

        <section class="mb-6">

            <div
                class="relative overflow-hidden
                        rounded-[32px]
                        border border-slate-800
                        bg-slate-900">

                {{-- BG --}}
                <div
                    class="absolute inset-0
                            bg-gradient-to-br
                            from-indigo-500/10
                            via-slate-900
                            to-slate-900">
                </div>

                <div class="relative p-5 sm:p-6 lg:p-8">

                    <div
                        class="flex flex-col
                                xl:flex-row
                                xl:items-start
                                xl:justify-between
                                gap-8">

                        {{-- LEFT --}}
                        <div
                            class="flex flex-col
                                    lg:flex-row
                                    gap-6
                                    min-w-0">

                            {{-- ICON --}}
                            <div class="flex justify-center lg:block">

                                <div
                                    class="w-32 h-32 sm:w-36 sm:h-36
                                            rounded-[30px]
                                            bg-indigo-500/10
                                            border border-indigo-500/20
                                            flex items-center justify-center
                                            text-5xl
                                            shrink-0">

                                    {{ $subject->code }}

                                </div>

                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">

                                <div
                                    class="flex flex-wrap
                                            items-center
                                            gap-3">

                                    <h1 class="text-2xl sm:text-3xl font-bold">

                                        {{ $subject->name }}

                                    </h1>

                                    <span
                                        class="px-3 py-1 rounded-full
                                                 bg-emerald-500/10
                                                 text-emerald-400 text-xs">

                                        Matière Active

                                    </span>

                                </div>

                                <p class="mt-2 text-slate-400">

                                    Tableau global des statistiques,
                                    performances et enseignants
                                    de la matière.

                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">

                                    <div
                                        class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        18 Enseignants

                                    </div>

                                    <div
                                        class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        42 Classes

                                    </div>

                                    <div
                                        class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        Coef Moyen : 4

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-3">

                            <button
                                class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                Ajouter Enseignant

                            </button>

                            <button
                                class="h-11 px-5 rounded-2xl
                                           bg-emerald-500 hover:bg-emerald-600">

                                Activer / Desactiver

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <section class="mb-6">

            <div
                class="grid
                        grid-cols-2
                        lg:grid-cols-4
                        2xl:grid-cols-6
                        gap-4">

                @foreach ([['Moyenne Générale', '11.84', 'text-indigo-400'], ['Meilleure Note', '19.75', 'text-emerald-400'], ['Plus Faible', '02.15', 'text-rose-400'], ['Taux Réussite', '72%', 'text-sky-400'], ['Classes', '42', 'text-amber-400'], ['Enseignants', '18', 'text-violet-400']] as $kpi)
                    <div
                        class="rounded-3xl
                            bg-slate-900
                            border border-slate-800
                            p-5">

                        <p class="text-sm text-slate-400">

                            {{ $kpi[0] }}

                        </p>

                        <h2 class="mt-3 text-2xl font-bold {{ $kpi[2] }}">

                            {{ $kpi[1] }}

                        </h2>

                    </div>
                @endforeach

            </div>

        </section>

        @livewire('tenants.subjects.yearly-subject-bests-students-component')

        @livewire('tenants.subjects.yearly-subject-teachers-list-component')

        @livewire('tenants.subjects.yearly-subject-coef-per-promotion-or-classe-component')

    </div>

</div>

