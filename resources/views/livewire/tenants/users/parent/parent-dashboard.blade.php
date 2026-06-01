<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                ">

        {{-- ===================================================== --}}
        {{-- HERO --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="relative overflow-hidden
                        rounded-[32px]
                        border border-slate-800
                        bg-slate-900">

                {{-- BG --}}
                <div class="absolute inset-0
                            bg-gradient-to-br
                            from-indigo-500/10
                            via-slate-900
                            to-slate-900">
                </div>

                <div class="relative p-5 sm:p-6 lg:p-8">

                    <div class="flex flex-col
                                xl:flex-row
                                xl:items-start
                                xl:justify-between
                                gap-8">

                        {{-- LEFT --}}
                        <div class="flex flex-col
                                    lg:flex-row
                                    gap-6
                                    min-w-0">

                            {{-- AVATAR --}}
                            <div class="flex justify-center lg:block">

                                <div
                                    class="w-32 h-32 sm:w-36 sm:h-36
                                            rounded-[30px]
                                            overflow-hidden
                                            bg-slate-800
                                            border border-slate-700
                                            shrink-0">
                                </div>

                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">

                                <div class="flex flex-wrap
                                            items-center
                                            gap-3">

                                    <h1 class="text-2xl sm:text-3xl font-bold">

                                        KOUASSI Jean Baptiste

                                    </h1>

                                    <span class="px-3 py-1 rounded-full
                                                 bg-emerald-500/10
                                                 text-emerald-400 text-xs">

                                        Parent Vérifié

                                    </span>

                                </div>

                                <p class="mt-2 text-slate-400">

                                    Parent d’élèves —
                                    Accès sécurisé au suivi scolaire.

                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        3 Enfants

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        Cotonou

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        Profession : Ingénieur

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-3">

                            <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                Modifier Profil

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-emerald-500 hover:bg-emerald-600">

                                Notifications

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-sky-500 hover:bg-sky-600">

                                Télécharger Bulletins

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- KPI --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="grid
                        grid-cols-2
                        lg:grid-cols-4
                        2xl:grid-cols-6
                        gap-4">

                @foreach ([['Enfants', '3', 'text-indigo-400'], ['Présence Moyenne', '96%', 'text-emerald-400'], ['Moyenne Générale', '14.22', 'text-sky-400'], ['Notifications', '5', 'text-amber-400'], ['Bulletins', '12', 'text-violet-400'], ['Paiements', 'À jour', 'text-rose-400']] as $kpi)
                    <div class="rounded-3xl
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

        {{-- ===================================================== --}}
        {{-- MAIN GRID --}}
        {{-- ===================================================== --}}
        <section>

            <div class="grid
                        grid-cols-1
                        2xl:grid-cols-[minmax(0,1fr)_420px]
                        gap-6">

                {{-- ===================================================== --}}
                {{-- LEFT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">
                    {{-- ===================================================== --}}
                    {{-- ENFANTS --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-[32px]
                                bg-slate-900
                                border border-slate-800
                                p-6">

                        <div
                            class="flex flex-col
                                    lg:flex-row
                                    lg:items-center
                                    lg:justify-between
                                    gap-4">

                            <div>

                                <h2 class="text-xl font-semibold">

                                    Mes Enfants

                                </h2>

                                <p class="mt-1 text-sm text-slate-400">

                                    Informations scolaires
                                    et suivi global.

                                </p>

                            </div>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                Voir Notes

                            </button>

                        </div>

                        <div class="mt-6 space-y-5">

                            @foreach (range(1, 3) as $child)
                                <div class="rounded-3xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-5">

                                    <div
                                        class="flex flex-col
                                            xl:flex-row
                                            xl:items-center
                                            xl:justify-between
                                            gap-5">

                                        {{-- LEFT --}}
                                        <div class="flex items-center gap-4 min-w-0">

                                            <div
                                                class="w-20 h-20
                                                    rounded-2xl
                                                    bg-slate-800
                                                    shrink-0">
                                            </div>

                                            <div class="min-w-0">

                                                <h3 class="text-lg font-semibold">

                                                    KOUASSI Sarah

                                                </h3>

                                                <p class="mt-1 text-sm text-slate-400">

                                                    Terminale F2-1 —
                                                    Matricule :
                                                    MAT-2026-001

                                                </p>

                                                <div class="mt-3 flex flex-wrap gap-2">

                                                    <span
                                                        class="px-3 py-1 rounded-full
                                                             bg-emerald-500/10
                                                             text-emerald-400 text-xs">

                                                        Moyenne : 14.22

                                                    </span>

                                                    <span
                                                        class="px-3 py-1 rounded-full
                                                             bg-indigo-500/10
                                                             text-indigo-400 text-xs">

                                                        Présence : 96%

                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                        {{-- ACTIONS --}}
                                        <div class="flex flex-wrap gap-3">

                                            <button class="h-11 px-4 rounded-2xl
                                                       bg-sky-500 hover:bg-sky-600">

                                                Emploi du Temps

                                            </button>

                                            <button class="h-11 px-4 rounded-2xl
                                                       bg-emerald-500 hover:bg-emerald-600">

                                                Bulletin

                                            </button>

                                            <button class="h-11 px-4 rounded-2xl
                                                       bg-indigo-500 hover:bg-indigo-600">

                                                Notes

                                            </button>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RIGHT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6">

                    {{-- NOTIFICATIONS --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Notifications

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach (range(1, 5) as $notif)
                                <div class="rounded-2xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-4">

                                    <h3 class="font-medium">

                                        Nouvelle note publiée

                                    </h3>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Mathématiques —
                                        Aujourd’hui

                                    </p>

                                </div>
                            @endforeach

                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div
                        class="rounded-3xl
                                bg-gradient-to-br
                                from-indigo-500/20
                                to-slate-900
                                border border-indigo-500/20
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Actions Rapides

                        </h2>

                        <div class="mt-5 space-y-3">

                            @foreach (['Télécharger Bulletins', 'Voir Présences', 'Contacter École', 'Historique Paiements', 'Notifications'] as $action)
                                <button
                                    class="w-full
                                           h-11
                                           rounded-2xl
                                           bg-slate-900/70
                                           hover:bg-slate-800
                                           border border-slate-800">

                                    {{ $action }}

                                </button>
                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

