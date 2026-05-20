<div class="w-full overflow-x-hidden">

    <div class="mx-auto
                w-full
                max-w-[1900px]
                px-3 sm:px-4 lg:px-6 xl:px-8">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
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

                <div class="relative p-2 py-3">

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

                            {{-- PHOTO --}}
                            <div class="flex justify-center items-center lg:block p-2">

                                <div class="w-32 h-32 sm:w-36 sm:h-36
                                            rounded-[30px]
                                            bg-slate-800
                                            border border-slate-700
                                            overflow-hidden
                                            shrink-0">
                                </div>

                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">

                                <div class="flex flex-wrap
                                            items-center
                                            gap-3">

                                    <h1 class="text-2xl sm:text-3xl font-bold">

                                        KOUASSI Marceline

                                    </h1>

                                    <span class="px-3 py-1 rounded-full
                                                 bg-emerald-500/10
                                                 text-emerald-400 text-xs">

                                        Parent Actif

                                    </span>

                                </div>

                                <p class="mt-2 text-amber-400">

                                    Email :
                                    marceline@gmail.com

                                </p>
                                <p class="mt-2 text-slate-400">

                                    Parent principal —
                                    3 enfants inscrits dans l’établissement.

                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800
                                                border border-slate-700">

                                        Profession : Commerçante

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800
                                                border border-slate-700">

                                        Ville : Cotonou

                                    </div>

                                    <div class="px-4 py-2 rounded-2xl
                                                bg-slate-800
                                                border border-slate-700">

                                        Sexe : Féminin

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap gap-3">

                            <button class="h-11 px-5 rounded-2xl
                                           bg-indigo-500 hover:bg-indigo-600">

                                Modifier

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-emerald-500 hover:bg-emerald-600">

                                Envoyer Bulletin

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-sky-500 hover:bg-sky-600">

                                Envoyer Notes

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-amber-500 hover:bg-amber-600">

                                Notifier

                            </button>

                            <button class="h-11 px-5 rounded-2xl
                                           bg-rose-500 hover:bg-rose-600">

                                Bloquer Accès

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

                @foreach([
                    ['Enfants','3','text-indigo-400'],
                    ['Présences','94%','text-emerald-400'],
                    ['Moyenne Globale','13.42','text-sky-400'],
                    ['Retards','6','text-amber-400'],
                    ['Absences','3','text-rose-400'],
                    ['Notifications','18','text-violet-400']
                ] as $kpi)

                <div class="rounded-3xl
                            bg-slate-900
                            border border-slate-800
                            p-5">

                    <p class="text-sm text-slate-400">

                        {{ $kpi[0] }}

                    </p>

                    <h2 class="mt-3 text-3xl font-bold {{ $kpi[2] }}">

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
                    {{-- ETAT CIVIL --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-[32px]
                                bg-slate-900
                                border border-slate-800
                                p-5 sm:p-6">

                        <div class="flex items-center justify-between">

                            <div>

                                <h2 class="text-xl font-semibold">

                                    État Civil & Identité

                                </h2>

                                <p class="mt-1 text-sm text-slate-400">

                                    Informations administratives du parent.

                                </p>

                            </div>

                        </div>

                        <div class="mt-6 grid
                                    grid-cols-1
                                    md:grid-cols-2
                                    xl:grid-cols-3
                                    gap-4">

                            @foreach([
                                ['Téléphone','+229 01 97 00 00 00'],
                                ['Nationalité','Béninoise'],
                                ['Adresse','Akpakpa — Cotonou'],
                                ['N° Identité','BJ-998721'],
                                ['Situation','Mariée'],
                                ['Date naissance','12 Juin 1982'],
                            ] as $info)

                            <div class="rounded-2xl
                                        bg-slate-950
                                        border border-slate-800
                                        p-4">

                                <p class="text-xs text-slate-500">

                                    {{ $info[0] }}

                                </p>

                                <h3 class="mt-2 font-medium">

                                    {{ $info[1] }}

                                </h3>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- ENFANTS --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-[32px]
                                bg-slate-900
                                border border-slate-800
                                overflow-hidden">

                        <div class="p-5 border-b border-slate-800">

                            <div class="flex flex-col
                                        lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                        gap-4">

                                <div>

                                    <h2 class="text-xl font-semibold">

                                        Enfants Associés

                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">

                                        Liste des apprenants liés au parent.

                                    </p>

                                </div>

                                <button class="h-10 px-4 rounded-xl
                                               bg-indigo-500 hover:bg-indigo-600">

                                    Ajouter Enfant

                                </button>

                            </div>

                        </div>

                        <div class="overflow-x-auto">

                            <table class="min-w-[1700px] w-full">

                                <thead class="bg-slate-950 text-center border-b border-slate-800">

                                    <tr class="text-center">

                                        <th class="px-6 py-4  text-sm text-slate-400">
                                            Apprenant
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Sexe
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Âge
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Présence
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Statut
                                        </th>

                                        <th class="px-6 py-4 text-center text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach(range(1,3) as $child)

                                    <tr class="hover:bg-slate-800/40">

                                        <td class="px-6 py-5">

                                            <a href="{{route('tenant.student.profil', ['student_uuid' => 'f'])}}" class="px-3 py-2 flex rounded-xl bg-slate-950 text-sm hover:bg-gray-800 border border-slate-950 hover:border-sky-600">

                                                KOUASSI Marc

                                                <span class="text-xs text-amber-500 rounded-2xl bg-slate-800 p-1 text-center">
                                                    2nde F2-2
                                                </span>

                                            </a>

                                        </td>


                                        <td class="px-4 py-5 text-center">

                                            F

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            17 ans

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            96%

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            <span class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-xs">

                                                Régulier

                                            </span>

                                        </td>

                                        <td class="px-6 py-5">

                                            <div class="flex items-center justify-end gap-2">

                                                <a href="{{route('tenant.student.profil', ['student_uuid' => 'f'])}}" class="p-2.5 rounded-2xl
                                               bg-blue-500/20
                                               text-blue-400
                                               hover:bg-blue-500/30
                                               transition-all text-sm inline-block text-center">

                                                    Profil 

                                                </a>


                                                <a href="{{route('tenant.student.marks', ['student_uuid' => 'dddd'])}}" class="p-2.5 rounded-2xl
                                               bg-green-500/20
                                               text-green-400
                                               hover:bg-green-500/30
                                               transition-all text-sm inline-block text-center">

                                                    Notes 

                                                </a>
                                                <button class="p-2.5 rounded-2xl
                                               bg-red-500/20
                                               text-red-400
                                               hover:bg-red-500/30
                                               transition-all text-sm inline-block text-center">

                                                    Dissocier

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- NOTES RECENTES --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-[32px]
                                bg-slate-900
                                border border-slate-800
                                overflow-hidden">

                        <div class="p-5 border-b border-slate-800">

                            <h2 class="text-xl font-semibold">

                                Dernières Notes des Enfants

                            </h2>

                        </div>

                        <div class="overflow-x-auto">

                            <table class="min-w-[1700px] w-full">

                                <thead class="bg-slate-950
                                             border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4  text-sm text-slate-400">
                                            Enfant
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Matière
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Type
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Note
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Coef
                                        </th>

                                        <th class="px-6 py-4  text-sm text-slate-400">
                                            Enseignant
                                        </th>

                                        <th class="px-6 py-4  text-sm text-slate-400">
                                            Observation
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach(range(1,3) as $note)

                                    <tr class="hover:bg-slate-800/40">

                                        <td class="px-6 py-5 font-medium">

                                            KOUASSI Sarah

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            Mathématiques

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            Interro 2

                                        </td>

                                        <td class="px-4 py-5 text-center
                                                   font-semibold
                                                   text-emerald-400">

                                            17.5

                                        </td>

                                        <td class="px-4 py-5 text-center">

                                            4

                                        </td>

                                        <td class="px-6 py-5">

                                            M. HOUNKPATI

                                        </td>

                                        <td class="px-6 py-5 text-slate-300">

                                            Très bon travail.

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RIGHT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6">

                    {{-- ===================================================== --}}
                    {{-- CONTACT --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Contact Rapide

                        </h2>

                        <div class="mt-5 space-y-3">

                            @foreach([
                                'Envoyer Notification',
                                'Envoyer Email',
                                'Envoyer SMS',
                                'Envoyer WhatsApp',
                                'Partager Bulletin'
                            ] as $action)

                            <button class="w-full h-11 rounded-2xl
                                           bg-slate-950
                                           hover:bg-slate-800
                                           border border-slate-800">

                                {{ $action }}

                            </button>

                            @endforeach

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- GLOBAL PERFORMANCE --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-slate-900
                                border border-slate-800
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Performance Globale

                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach([
                                ['Moyenne Générale','13.42','bg-emerald-500'],
                                ['Présence','94%','bg-indigo-500'],
                                ['Retards','12%','bg-amber-500'],
                                ['Admissibilité','100%','bg-sky-500']
                            ] as $perf)

                            <div>

                                <div class="flex justify-between">

                                    <span class="text-sm text-slate-300">

                                        {{ $perf[0] }}

                                    </span>

                                    <span class="font-semibold">

                                        {{ $perf[1] }}

                                    </span>

                                </div>

                                <div class="mt-2 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                                    <div class="h-full rounded-full {{ $perf[2] }}"
                                         style="width: {{ $perf[1] }}">
                                    </div>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- ACTIVITE --}}
                    {{-- ===================================================== --}}
                    <div class="rounded-3xl
                                bg-gradient-to-br
                                from-indigo-500/20
                                to-slate-900
                                border border-indigo-500/20
                                p-5">

                        <h2 class="text-lg font-semibold">

                            Activité Récente

                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach(range(1,5) as $activity)

                            <div class="rounded-2xl
                                        bg-slate-900/60
                                        border border-slate-800
                                        p-4">

                                <h3 class="font-medium">

                                    Bulletin téléchargé

                                </h3>

                                <p class="mt-1 text-sm text-slate-400">

                                    19 Mai 2026 — 18:32

                                </p>

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>