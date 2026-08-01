<div
    class="
            bg-slate-950
            text-slate-100
            py-4
            overflow-x-hidden px-1 rounded-4xl">

    <div
        class="mx-auto
                    w-full
                    max-w-[1600px]
                    px-2 sm:px-4 lg:px-6 transition-all">

        {{-- ===================================================== --}}
        {{-- BULLETIN CONTAINER --}}
        {{-- ===================================================== --}}
        <div
            class="rounded-[32px]
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
                    <div
                        class="grid
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
                            <div
                                class="w-24 h-24 sm:w-28 sm:h-28
                                            rounded-3xl
                                            bg-slate-800
                                            border border-slate-700">

                            </div>

                            <h1 class="mt-4 text-2xl sm:text-3xl font-bold text-center">

                                {{ tenant('school_name') }}

                            </h1>

                            <p class="mt-2 text-sm text-slate-400 text-center">

                                {{ tenant('school_devise') }}

                            </p>

                            <div class="mt-4 text-center text-sm text-slate-400">

                                <p>{{ tenant('contacts') }}</p>
                                <p>{{ tenant('email') }}</p>

                            </div>

                        </div>

                        {{-- RIGHT --}}
                        <div class="text-center lg:text-right">

                            <p class="text-sm text-slate-400">

                                Année Scolaire

                            </p>

                            <h2 class="mt-2 text-2xl font-bold text-indigo-400">

                                {{ $this->activeYear->slug }}

                            </h2>

                            <div
                                class="mt-5 inline-flex
                                            px-4 py-2
                                            rounded-2xl
                                            bg-indigo-500/10
                                            text-indigo-400
                                            text-sm">

                                Bulletin du {{ $this->activeYear->periodLabel() }} {{ $period }}

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

                    <div
                        class="grid
                                    grid-cols-1
                                    xl:grid-cols-[240px_minmax(0,1fr)]
                                    gap-8">

                        {{-- PHOTO --}}
                        <div class="flex justify-center xl:justify-start">

                            <div
                                class="w-44 h-52
                                            rounded-3xl
                                            bg-slate-800
                                            border border-slate-700
                                            overflow-hidden">
                                <img src="{{ $student->profil_photo_url }}" alt="Photo de couverture"
                                    class="w-full h-full object-cover object-top scale-105" />
                            </div>

                        </div>

                        {{-- DETAILS --}}
                        <div class="min-w-0">

                            <div class="flex flex-wrap items-center gap-3">

                                <h2 class="text-2xl sm:text-3xl font-bold">

                                    {{ $student->getFullName() }}

                                </h2>

                                <span
                                    class="px-3 py-1 rounded-full
                                                bg-emerald-500/10
                                                text-emerald-400 text-xs">

                                    Élève Régulier

                                </span>

                            </div>

                            {{-- GRID --}}
                            <div
                                class="mt-6 grid
                                            grid-cols-1
                                            sm:grid-cols-2
                                            xl:grid-cols-4
                                            gap-4">

                                @foreach ([['Matricule', $student->matricule], ['Classe', $classe->code], ['Sexe', $student->gender], ['Date Naissance', formatBirthDate($this->student->birth_date)], ['Téléphone', tenant('contacts')], ['Nationalité', $student->country], ['Effectif Classe', $this->effectifs['apprenants']], ['Professeur Principal', $classe->principal?->getFullName() ?? '---']] as $info)
                                    <div
                                        class="rounded-2xl
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

                            @if ($this->termAverage)
                                {{-- CLASS DETAILS --}}
                                <div
                                    class="mt-6 grid
                                            grid-cols-2
                                            sm:grid-cols-4
                                            gap-4">

                                    @foreach ([['Garçons', $this->effectifs['apprenants_par_sexe']['M']], ['Filles', $this->effectifs['apprenants_par_sexe']['F']], ['Rang', $this->termAverage['rank']], ['Moyenne Générale', $this->termAverage['moyenne']]] as $item)
                                        <div
                                            class="rounded-2xl
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
                            @endif

                        </div>

                    </div>

                </div>

            </section>

            @if ($this->termAverage && $this->subjectsDetail)
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
                                            Moy. Int
                                        </th>

                                        @foreach ($this->devoirColumns() as $type => $label)
                                            <th class="px-4 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                {{ $label }}
                                            </th>
                                        @endforeach

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moy
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moy. Coef
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Rang
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Prof
                                        </th>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Mention
                                        </th>

                                    </tr>

                                </thead>

                                {{-- BODY --}}
                                <tbody class="divide-y divide-slate-800">

                                    @foreach ($this->subjectsDetail as $row)
                                        <tr class="hover:bg-slate-800/40 transition-all">

                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ $row['subject']->name }}

                                            </td>
                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ $row['coefficient'] ?? '---' }}

                                            </td>
                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ $row['moy_interro'] ?? '---' }}

                                            </td>
                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ $row['devoirs']['devoir1'] ?? '---' }}

                                            </td>
                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ $row['devoirs']['devoir2'] ?? '---' }}

                                            </td>
                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ $row['moy'] ?? '---' }}

                                            </td>
                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ $row['moy_coef'] ?? '---' }}

                                            </td>
                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ '---' }}

                                            </td>
                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ $row['teacher']->getFullName() }}

                                            </td>
                                            <td class="px-4 py-5 font-medium whitespace-nowrap">

                                                {{ $row['mention'] ?? '---' }}

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                                @if ($this->termAverage)
                                    <tfoot class="bg-slate-950 border border-slate-800">

                                        <tr>

                                            <td colspan="5"
                                                class="px-6 py-5 text-right
                                                font-bold text-lg">

                                                Moyenne Générale

                                            </td>

                                            <td
                                                class="px-4 py-5 text-center
                                                font-bold text-2xl
                                                text-indigo-400">

                                                {{ $this->termAverage['moyenne'] }}

                                            </td>

                                            <td
                                                class="px-4 py-5 text-center
                                                font-bold text-emerald-400">

                                                {{ $this->termAverage['sum_coef'] }}

                                            </td>

                                            <td
                                                class="px-4 py-5 text-center
                                                font-bold">

                                                {{ $this->termAverage['rank'] }} / {{ $this->termAverage['total'] }}

                                            </td>

                                            <td colspan="2" class="px-6 py-5">

                                                <span
                                                    class="px-4 py-2 rounded-2xl
                                                        bg-emerald-500/10
                                                        text-emerald-400
                                                        text-sm">

                                                    {{ $this->termAverage['mention'] }}

                                                </span>

                                            </td>

                                        </tr>

                                    </tfoot>
                                @else
                                    <span
                                        class="animate-pulse text-slate-700 flex justify-center items-center text-center py-3 my-4">
                                        Aucune moyenne calculable pour l'instant
                                    </span>
                                @endif

                            </table>

                        </div>

                    </div>

                </section>

                {{-- ===================================================== --}}
                {{-- OBSERVATIONS --}}
                {{-- ===================================================== --}}
                <section class="border-t border-slate-800">

                    <div class="p-4 sm:p-6 lg:p-10">

                        <div
                            class="grid
                                    grid-cols-1
                                    2xl:grid-cols-3
                                    gap-6">

                            {{-- OBSERVATION --}}
                            <div
                                class="rounded-3xl
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
                            <div
                                class="rounded-3xl
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
                            <div
                                class="rounded-3xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-6">

                                <h2 class="text-lg font-semibold">

                                    Signature & Cachet

                                </h2>

                                <div class="mt-8 flex flex-col items-center">

                                    {{-- STAMP --}}
                                    <div
                                        class="w-36 h-36 rounded-full
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

            @endif

            {{-- ===================================================== --}}
            {{-- FOOTER --}}
            {{-- ===================================================== --}}
            <footer class="border-t border-slate-800
                            bg-slate-950">

                <div class="px-4 sm:px-6 lg:px-10 py-6">

                    <div
                        class="flex flex-col
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

                            <button
                                class="h-11 px-5 rounded-2xl
                                            bg-slate-800 hover:bg-slate-700">

                                Télécharger PDF

                            </button>

                            <button
                                class="h-11 px-5 rounded-2xl
                                            bg-indigo-500 hover:bg-indigo-600">

                                Envoyer au Parent

                            </button>

                        </div>

                    </div>

                </div>

            </footer>

        </div>

    </div>

</div>

