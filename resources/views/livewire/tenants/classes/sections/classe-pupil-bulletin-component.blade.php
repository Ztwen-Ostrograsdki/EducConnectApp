<div class="
            bg-slate-950
            text-slate-100
            py-4
            overflow-x-hidden px-1 rounded-4xl">

    @if($student_uuid && $period_type)
        <div class="mx-auto
                    w-full
                    max-w-[1600px]
                    px-2 sm:px-4 lg:px-6 transition-all">

            {{-- ===================================================== --}}
            {{-- BULLETIN CONTAINER --}}
            {{-- ===================================================== --}}
            <div class="rounded-[32px]
                        border border-slate-800
                        bg-slate-900
                        shadow-2xl
                        overflow-hidden">

                {{-- ===================================================== --}}
                {{-- HEADER --}}
                {{-- ===================================================== --}}
                <section class="border-b border-slate-800">

                    <div class="p-4 sm:p-6 lg:p-10">

                        {{-- TOP --}}
                        <div class="grid
                                    grid-cols-1
                                    lg:grid-cols-3
                                    gap-8
                                    items-center">

                            {{-- LEFT --}}
                            <div class="text-center lg:text-left">

                                <p class="text-xs sm:text-sm text-slate-400 uppercase tracking-widest">

                                    République du Bénin

                                </p>

                                <h2 class="mt-2 text-lg sm:text-xl font-bold">

                                    Ministère des Enseignements
                                    Secondaire, Technique
                                    et de la Formation Professionnelle

                                </h2>

                                <p class="mt-3 text-sm text-slate-400">

                                    Direction Départementale de l'Enseignement

                                </p>

                            </div>

                            {{-- CENTER --}}
                            <div class="flex flex-col items-center">

                                {{-- LOGO --}}
                                <div class="w-24 h-24 sm:w-28 sm:h-28
                                            rounded-3xl
                                            bg-slate-800
                                            border border-slate-700">
                                </div>

                                <h1 class="mt-4 text-2xl sm:text-3xl font-bold text-center">

                                    CEG TECHNIQUE DE COTONOU

                                </h1>

                                <p class="mt-2 text-sm text-slate-400 text-center">

                                    Excellence • Discipline • Travail

                                </p>

                                <div class="mt-4 text-center text-sm text-slate-400">

                                    <p>+229 01 00 00 00</p>
                                    <p>contact@cet-cotonou.bj</p>

                                </div>

                            </div>

                            {{-- RIGHT --}}
                            <div class="text-center lg:text-right">

                                <p class="text-sm text-slate-400">

                                    Année Scolaire

                                </p>

                                <h2 class="mt-2 text-2xl font-bold text-indigo-400">

                                    {{ $school_year_selected }}

                                </h2>

                                <div class="mt-5 inline-flex
                                            px-4 py-2
                                            rounded-2xl
                                            bg-indigo-500/10
                                            text-indigo-400
                                            text-sm">

                                    Bulletin du {{ $period_type }}

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                {{-- ===================================================== --}}
                {{-- STUDENT INFOS --}}
                {{-- ===================================================== --}}
                <section class="border-b border-slate-800">

                    <div class="p-4 sm:p-6 lg:p-10">

                        <div class="grid
                                    grid-cols-1
                                    xl:grid-cols-[240px_minmax(0,1fr)]
                                    gap-8">

                            {{-- PHOTO --}}
                            <div class="flex justify-center xl:justify-start">

                                <div class="w-44 h-52
                                            rounded-3xl
                                            bg-slate-800
                                            border border-slate-700
                                            overflow-hidden">
                                </div>

                            </div>

                            {{-- DETAILS --}}
                            <div class="min-w-0">

                                <div class="flex flex-wrap items-center gap-3">

                                    <h2 class="text-2xl sm:text-3xl font-bold">

                                        {{ $student_uuid }}

                                    </h2>

                                    <span class="px-3 py-1 rounded-full
                                                bg-emerald-500/10
                                                text-emerald-400 text-xs">

                                        Élève Régulier

                                    </span>

                                </div>

                                {{-- GRID --}}
                                <div class="mt-6 grid
                                            grid-cols-1
                                            sm:grid-cols-2
                                            xl:grid-cols-4
                                            gap-4">

                                    @foreach([
                                        ['Matricule', 'MAT-2025-001'],
                                        ['Classe', $period_type],
                                        ['Sexe', 'Masculin'],
                                        ['Date Naissance', '12/08/2008'],
                                        ['Téléphone', '+229 01 00 00 00'],
                                        ['Nationalité', 'Béninoise'],
                                        ['Effectif Classe', '58'],
                                        ['Professeur Principal', 'M. AGBODJI']
                                    ] as $info)

                                    <div class="rounded-2xl
                                                bg-slate-950
                                                border border-slate-800
                                                p-4">

                                        <p class="text-xs text-slate-500 uppercase">

                                            {{ $info[0] }}

                                        </p>

                                        <h3 class="mt-2 text-sm sm:text-base font-semibold">

                                            {{ $info[1] }}

                                        </h3>

                                    </div>

                                    @endforeach

                                </div>

                                {{-- CLASS DETAILS --}}
                                <div class="mt-6 grid
                                            grid-cols-2
                                            sm:grid-cols-4
                                            gap-4">

                                    @foreach([
                                        ['Garçons', '34'],
                                        ['Filles', '24'],
                                        ['Rang', '5e'],
                                        ['Moyenne Générale', '14.72']
                                    ] as $item)

                                    <div class="rounded-2xl
                                                bg-indigo-500/5
                                                border border-indigo-500/10
                                                p-4 text-center">

                                        <p class="text-xs text-slate-400">

                                            {{ $item[0] }}

                                        </p>

                                        <h3 class="mt-2 text-xl font-bold text-indigo-400">

                                            {{ $item[1] }}

                                        </h3>

                                    </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                {{-- ===================================================== --}}
                {{-- NOTES TABLE --}}
                {{-- ===================================================== --}}
                <section>

                    <div class="p-2 sm:p-4 lg:p-6">

                        <div class="overflow-x-auto">

                            <table class="min-w-[1900px] w-full">

                                {{-- HEADER --}}
                                <thead class="bg-slate-950 border border-slate-800">

                                    <tr>

                                        <th class="px-4 py-4 text-left text-sm text-slate-400">
                                            Matières
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Coef
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moy. Interro
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Devoir 1
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Devoir 2
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moyenne
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moy. Coef
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Rang
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Enseignant
                                        </th>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Observation
                                        </th>

                                    </tr>

                                </thead>

                                {{-- BODY --}}
                                <tbody class="divide-y divide-slate-800">

                                    @foreach([
                                        ['Mathématiques', 5, 15, 14, 16, 15, 75, '4e', 'M. Tognon', 'Très bon travail'],
                                        ['Physique', 4, 13, 14, 12, 13, 52, '8e', 'Mme Ahouandjinou', 'Bon ensemble'],
                                        ['Français', 3, 12, 10, 11, 11, 33, '11e', 'M. Gandonou', 'Peut mieux faire'],
                                        ['Anglais', 2, 16, 15, 17, 16, 32, '2e', 'Mme Assogba', 'Excellent'],
                                        ['Informatique', 5, 18, 17, 19, 18, 90, '1er', 'M. Houngbédji', 'Excellent niveau'],
                                        ['EPS', 1, 15, 14, 16, 15, 15, '5e', 'M. Sossou', 'Très dynamique'],
                                    ] as $subject)

                                    <tr class="hover:bg-slate-800/40 transition-all">

                                        <td class="px-4 py-5 font-medium whitespace-nowrap">

                                            {{ $subject[0] }}

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            {{ $subject[1] }}

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            {{ $subject[2] }}

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            {{ $subject[3] }}

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            {{ $subject[4] }}

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                font-semibold text-indigo-400">

                                            {{ $subject[5] }}

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                font-semibold text-emerald-400">

                                            {{ $subject[6] }}

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            {{ $subject[7] }}

                                        </td>

                                        <td class="px-4 py-5 whitespace-nowrap">

                                            {{ $subject[8] }}

                                        </td>

                                        <td class="px-6 py-5 min-w-[280px]">

                                            <span class="text-sm text-slate-300">

                                                {{ $subject[9] }}

                                            </span>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                                {{-- FOOT TOTAL --}}
                                <tfoot class="bg-slate-950 border border-slate-800">

                                    <tr>

                                        <td colspan="5"
                                            class="px-6 py-5 text-right
                                                font-bold text-lg">

                                            Moyenne Générale

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                font-bold text-2xl
                                                text-indigo-400">

                                            14.72

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                font-bold text-emerald-400">

                                            297

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                font-bold">

                                            5e / 58

                                        </td>

                                        <td colspan="2"
                                            class="px-6 py-5">

                                            <span class="px-4 py-2 rounded-2xl
                                                        bg-emerald-500/10
                                                        text-emerald-400
                                                        text-sm">

                                                Très Bon {{ $period_type }}

                                            </span>

                                        </td>

                                    </tr>

                                </tfoot>

                            </table>

                        </div>

                    </div>

                </section>

                {{-- ===================================================== --}}
                {{-- OBSERVATIONS --}}
                {{-- ===================================================== --}}
                <section class="border-t border-slate-800">

                    <div class="p-4 sm:p-6 lg:p-10">

                        <div class="grid
                                    grid-cols-1
                                    2xl:grid-cols-3
                                    gap-6">

                            {{-- OBSERVATION --}}
                            <div class="rounded-3xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-6">

                                <h2 class="text-lg font-semibold">

                                    Observation Générale

                                </h2>

                                <p class="mt-4 text-slate-300 leading-relaxed">

                                    Élève sérieux et discipliné.
                                    Les résultats sont satisfaisants dans l’ensemble.
                                    Quelques efforts supplémentaires sont attendus
                                    en Français afin d’améliorer davantage
                                    les performances globales.

                                </p>

                            </div>

                            {{-- JURY --}}
                            <div class="rounded-3xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-6">

                                <h2 class="text-lg font-semibold">

                                    Décision du Jury

                                </h2>

                                <div class="mt-5 space-y-4">

                                    <div class="flex items-center justify-between">

                                        <span class="text-slate-400">

                                            Décision

                                        </span>

                                        <span class="text-emerald-400 font-semibold">

                                            Admis

                                        </span>

                                    </div>

                                    <div class="flex items-center justify-between">

                                        <span class="text-slate-400">

                                            Mention

                                        </span>

                                        <span class="text-indigo-400 font-semibold">

                                            Bien

                                        </span>

                                    </div>

                                    <div class="flex items-center justify-between">

                                        <span class="text-slate-400">

                                            Discipline

                                        </span>

                                        <span class="font-semibold">

                                            Très Bonne

                                        </span>

                                    </div>

                                </div>

                            </div>

                            {{-- SIGNATURE --}}
                            <div class="rounded-3xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-6">

                                <h2 class="text-lg font-semibold">

                                    Signature & Cachet

                                </h2>

                                <div class="mt-8 flex flex-col items-center">

                                    {{-- STAMP --}}
                                    <div class="w-36 h-36 rounded-full
                                                border-4 border-dashed
                                                border-indigo-500/40
                                                flex items-center
                                                justify-center
                                                text-indigo-400
                                                text-center
                                                text-sm">

                                        Cachet
                                        Officiel

                                    </div>

                                    <div class="mt-8 text-center">

                                        <p class="font-semibold">

                                            Le Directeur

                                        </p>

                                        <p class="mt-2 text-sm text-slate-400">

                                            Signature

                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                {{-- ===================================================== --}}
                {{-- FOOTER --}}
                {{-- ===================================================== --}}
                <footer class="border-t border-slate-800
                            bg-slate-950">

                    <div class="px-4 sm:px-6 lg:px-10 py-6">

                        <div class="flex flex-col
                                    lg:flex-row
                                    lg:items-center
                                    lg:justify-between
                                    gap-4">

                            <div>

                                <p class="text-sm text-slate-400">

                                    Bulletin généré automatiquement par
                                    la plateforme de gestion scolaire

                                </p>

                            </div>

                            <div class="flex flex-wrap gap-3">

                                <button class="h-11 px-5 rounded-2xl
                                            bg-slate-800 hover:bg-slate-700">

                                    Télécharger PDF

                                </button>

                                <button class="h-11 px-5 rounded-2xl
                                            bg-indigo-500 hover:bg-indigo-600">

                                    Envoyer au Parent

                                </button>

                            </div>

                        </div>

                    </div>

                </footer>

            </div>

        </div>
    @else
        <div class="flex w-full rounded-4xl animate-pulse text-slate-500 text-center font-semibold text-lg items-center justify-center p-5">
            <h2 class="p-3">Veuillez sélectionner l'apprenant et le semestre|trimestre puis charger pour afficher le bulletin</h2>
        </div>
    @endif
</div>