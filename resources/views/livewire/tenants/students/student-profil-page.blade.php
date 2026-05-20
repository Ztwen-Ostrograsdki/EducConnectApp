<div class="w-full max-w-full overflow-x-hidden p-2">

    {{-- ===================================================== --}}
    {{-- PROFILE HEADER --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div class="rounded-3xl
                    border border-slate-800
                    bg-slate-900
                    overflow-hidden">

            <div class="p-5 sm:p-8">

                <div class="flex flex-col xl:flex-row gap-8">

                    {{-- AVATAR --}}
                    <div class="flex flex-col items-center xl:items-start">

                        <div class="w-36 h-36 rounded-3xl
                                    bg-slate-800
                                    shrink-0">
                        </div>

                        <div class="mt-5 flex flex-wrap gap-3 justify-center xl:justify-start">

                            <span class="px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         text-indigo-400 text-xs">

                                Terminale F2-1

                            </span>

                            <span class="px-3 py-1 rounded-full
                                         bg-emerald-500/10
                                         text-emerald-400 text-xs">

                                Excellent

                            </span>

                        </div>

                    </div>

                    {{-- INFOS --}}
                    <div class="flex-1 min-w-0">

                        <div class="flex flex-col 2xl:flex-row
                                    2xl:items-start
                                    2xl:justify-between
                                    gap-6">

                            <div class="min-w-0">

                                <h1 class="text-3xl sm:text-4xl font-bold break-words">

                                    Kouassi Vincent HOUNDEKINDO

                                </h1>

                                <p class="mt-2 text-slate-400">

                                    Matricule : MAT-2026-00124

                                </p>

                                <div class="mt-6 grid
                                            grid-cols-1
                                            sm:grid-cols-2
                                            xl:grid-cols-4
                                            gap-4">

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Âge
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            17 ans
                                        </h4>

                                    </div>

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Sexe
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            Masculin
                                        </h4>

                                    </div>

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Nationalité
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            Béninoise
                                        </h4>

                                    </div>

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Naissance
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            12/08/2008
                                        </h4>

                                    </div>

                                </div>

                            </div>

                            {{-- ACTIONS --}}
                            <div class="grid
                                        grid-cols-2
                                        sm:grid-cols-4
                                        xl:grid-cols-2
                                        gap-3 w-full xl:w-[260px]">

                                <button class="p-3 rounded-2xl
                                               bg-indigo-500
                                               hover:bg-indigo-600
                                               transition-all text-sm">

                                    Editer

                                </button>

                                <a href="{{route('tenant.student.marks', ['student_uuid' => $student_uuid])}}" class="p-3 rounded-2xl
                                               bg-green-500/20
                                               text-green-400
                                               hover:bg-green-500/30
                                               transition-all text-sm inline-block text-center">

                                    Les notes 

                                </a>

                                <button class="p-3 rounded-2xl
                                               bg-slate-800
                                               hover:bg-slate-700
                                               transition-all text-sm">

                                    Présence

                                </button>

                                <button class="p-3 rounded-2xl
                                               bg-rose-500/20
                                               text-rose-400
                                               hover:bg-rose-500/30
                                               transition-all text-sm">

                                    Suspendre

                                </button>

                                <a href="{{route('tenant.classe.profil', ['classe_slug' => $classe_slug])}}" class="p-3 col-span-2 rounded-2xl
                                               bg-sky-500/20
                                               text-sky-400
                                               hover:bg-sky-500/30
                                               transition-all text-sm inline-block text-center">

                                    Acceder à la classe 

                                </a>

                            </div>

                        </div>

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
                    grid-cols-1
                    sm:grid-cols-2
                    2xl:grid-cols-4
                    gap-4 sm:gap-6">

            {{-- MOYENNE --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Moyenne Générale
                </p>

                <h2 class="mt-3 text-4xl font-bold text-indigo-400">
                    15.24
                </h2>

            </div>

            {{-- RANK --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Rang
                </p>

                <h2 class="mt-3 text-4xl font-bold">
                    3e
                </h2>

            </div>

            {{-- PRESENCE --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Présence
                </p>

                <h2 class="mt-3 text-4xl font-bold text-emerald-400">
                    96%
                </h2>

            </div>

            {{-- PROBA --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Probabilité Réussite
                </p>

                <h2 class="mt-3 text-4xl font-bold text-amber-400">
                    92%
                </h2>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- MAIN GRID --}}
    {{-- ===================================================== --}}
    <section class="grid
                    grid-cols-1
                    2xl:grid-cols-12
                    gap-6">

        {{-- LEFT --}}
        <div class="2xl:col-span-8 space-y-6 min-w-0">

            {{-- NOTES PAR MATIERES --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

                <div class="border-b border-slate-800 px-6 py-5">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                        <div>

                            <h2 class="text-xl font-semibold">
                                Notes par Matières
                            </h2>

                            <p class="mt-1 text-sm text-slate-400">
                                Détails académiques de l'apprenant
                            </p>

                        </div>

                        <select class="h-11 px-4 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Semestre 1</option>
                            <option>Semestre 2</option>

                        </select>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-slate-950 border-b border-slate-800">

                            <tr>

                                <th class="px-6 py-4 text-left text-sm text-slate-400">
                                    Matière
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Interros
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Devoirs
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Moyenne
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Coef
                                </th>

                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                    Moy Coef
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @foreach([
                                'Mathématiques',
                                'Physique',
                                'Électricité',
                                'Informatique',
                                'Français'
                            ] as $subject)

                            <tr class="hover:bg-slate-800/40 transition-all">

                                <td class="px-6 py-5 font-medium">
                                    {{ $subject }}
                                </td>

                                <td class="px-4 py-5 text-center">
                                    14.5
                                </td>

                                <td class="px-4 py-5 text-center">
                                    15
                                </td>

                                <td class="px-4 py-5 text-center">

                                    <span class="px-3 py-1 rounded-full
                                                 bg-emerald-500/10
                                                 text-emerald-400 text-sm">

                                        15.2

                                    </span>

                                </td>

                                <td class="px-4 py-5 text-center">
                                    2
                                </td>

                                <td class="px-4 py-5 text-center font-semibold">
                                    30.4
                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- EVOLUTION CHART --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-semibold">
                            Évolution des Notes
                        </h2>

                        <p class="mt-1 text-sm text-slate-400">
                            Progression globale de l'apprenant
                        </p>

                    </div>

                    <span class="px-3 py-1 rounded-full
                                 bg-indigo-500/10
                                 text-indigo-400 text-xs">

                        +12%

                    </span>

                </div>

                {{-- CHART --}}
                <div class="mt-8 h-[320px]
                            rounded-3xl
                            border border-dashed border-slate-700
                            bg-slate-950
                            flex items-center justify-center">

                    <p class="text-slate-500">
                        Courbe d'évolution des notes
                    </p>

                </div>

            </div>

            {{-- EMPLOI DU TEMPS --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-semibold">
                            Emploi du Temps
                        </h2>

                        <p class="mt-1 text-sm text-slate-400">
                            Planning hebdomadaire
                        </p>

                    </div>

                </div>

                <div class="mt-6 grid
                            grid-cols-1
                            sm:grid-cols-2
                            xl:grid-cols-3
                            gap-4">

                    @foreach(range(1,6) as $course)

                    <div class="rounded-2xl
                                border border-indigo-500/20
                                bg-indigo-500/10
                                p-4">

                        <div class="flex items-center justify-between gap-3">

                            <h3 class="font-semibold">
                                Mathématiques
                            </h3>

                            <span class="text-xs text-indigo-300">
                                08:00
                            </span>

                        </div>

                        <p class="mt-2 text-sm text-slate-300">
                            M. HOUNDEKINDO
                        </p>

                        <div class="mt-4 flex items-center justify-between">

                            <span class="px-2 py-1 rounded-xl
                                         bg-slate-950/50
                                         text-xs">

                                Salle B12

                            </span>

                            <span class="text-xs text-slate-400">
                                Lundi
                            </span>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="2xl:col-span-4 space-y-6 min-w-0">

            {{-- STATS MATIERES --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">

                <h2 class="text-xl font-semibold">
                    Statistiques
                </h2>

                <div class="mt-6 space-y-5">

                    {{-- SCIENTIFIQUE --}}
                    <div>

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-slate-300">
                                Scientifiques
                            </span>

                            <span class="text-sm font-semibold">
                                16.2
                            </span>

                        </div>

                        <div class="mt-2 h-2 rounded-full bg-slate-800 overflow-hidden">

                            <div class="h-full w-[82%]
                                        bg-indigo-500 rounded-full">
                            </div>

                        </div>

                    </div>

                    {{-- LETTER --}}
                    <div>

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-slate-300">
                                Littéraires
                            </span>

                            <span class="text-sm font-semibold">
                                13.4
                            </span>

                        </div>

                        <div class="mt-2 h-2 rounded-full bg-slate-800 overflow-hidden">

                            <div class="h-full w-[68%]
                                        bg-emerald-500 rounded-full">
                            </div>

                        </div>

                    </div>

                    {{-- INFO --}}
                    <div>

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-slate-300">
                                Informatiques
                            </span>

                            <span class="text-sm font-semibold">
                                17.5
                            </span>

                        </div>

                        <div class="mt-2 h-2 rounded-full bg-slate-800 overflow-hidden">

                            <div class="h-full w-[92%]
                                        bg-amber-500 rounded-full">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- PARENTS --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">

                <h2 class="text-xl font-semibold">
                    Parents / Tuteurs
                </h2>

                <div class="mt-6 space-y-5">

                    @foreach(range(1,2) as $parent)

                    <div class="rounded-2xl bg-slate-950 p-4">

                        <div class="flex items-start gap-4">

                            <div class="w-14 h-14 rounded-2xl bg-slate-800 shrink-0">
                            </div>

                            <div class="min-w-0">

                                <h3 class="font-medium truncate">
                                    Parent {{$parent}}
                                </h3>

                                <p class="mt-1 text-sm text-slate-400 truncate">
                                    +229 01 00 00 00 00
                                </p>

                                <p class="mt-1 text-sm text-slate-500 truncate">
                                    parent@email.com
                                </p>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

            {{-- PRESENCE --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">

                <h2 class="text-xl font-semibold">
                    Présence Hebdomadaire
                </h2>

                <div class="mt-6 space-y-4">

                    @foreach([
                        'Lundi',
                        'Mardi',
                        'Mercredi',
                        'Jeudi',
                        'Vendredi'
                    ] as $day)

                    <div>

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-slate-300">
                                {{ $day }}
                            </span>

                            <span class="text-sm font-semibold">
                                100%
                            </span>

                        </div>

                        <div class="mt-2 h-2 rounded-full bg-slate-800 overflow-hidden">

                            <div class="h-full w-full
                                        bg-emerald-500 rounded-full">
                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>
        </div>
    </section>

    <section class="grid my-3 pb-9">
        {{-- Bulletins --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <div>
                <h2 class="text-xl font-semibold">
                    Bulletin de notes de l'année scolaire <span class="text-sky-600">{{ session('school_year_selected') }}</span>
                </h2>
                <p class="mt-1 text-sm text-slate-400">
                    Détails sur les notes par semestre|trimestre de l'apprenant
                </p>
            </div>
            <div class="flex flex-col xl:flex-row gap-4">
                        {{-- FILTERS --}}
                <div class="grid
                            grid-cols-1
                            sm:grid-cols-2
                            lg:grid-cols-4
                            gap-3 p-1 py-2.5">

                    {{-- SEMESTER --}}
                    <select wire:model.live="period_type_selected" class="h-12 px-4 rounded-2xl
                                bg-slate-950
                                border border-slate-800
                                text-sm">
                            <option value="">Sélectionner le semestre|trimestre</option>
                        @foreach(range(1,2) as $i)
                            <option value="Semestre {{ $i }}">Semestre {{ $i }}</option>
                        @endforeach

                        @foreach(range(1,3) as $i)
                            <option value="Trimestre {{ $i }}">Trimestre {{ $i }}</option>
                        @endforeach

                    </select>

                    {{-- ACTIONS --}}
                    @if($period_type_selected)
                        <button wire:click='reloadStudentBulletin' class="h-12 px-5 rounded-2xl
                                    bg-sky-800
                                    border border-sky-700
                                    hover:bg-sky-700
                                    transition-all
                                    text-sm cursor-pointer">

                            Charger
                        </button>

                        <button wire:click='resetBulletinSelections' class="h-12 px-5 rounded-2xl
                                    bg-slate-800
                                    border border-slate-700
                                    hover:bg-slate-700
                                    transition-all
                                    text-sm cursor-pointer">

                            Réinitialiser

                        </button>
                    @endif
                </div>

            </div>
            @livewire('tenants.classes.sections.classe-pupil-bulletin-component')
        </div>
    </section>
</div>