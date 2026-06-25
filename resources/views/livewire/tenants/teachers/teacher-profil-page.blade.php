<div class="w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- GLOBAL CONTAINER --}}
    {{-- ===================================================== --}}
    <div
        class="mx-auto
                w-full
                max-w-[1850px]
                px-3
                sm:px-4
                lg:px-6
                xl:px-8">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div
                class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        overflow-hidden">

                <div class="p-4 sm:p-6 xl:p-8">

                    <div class="flex flex-col xl:flex-row gap-6 xl:gap-8">

                        {{-- LEFT --}}
                        <div class="flex flex-col sm:flex-row gap-5 flex-1 min-w-0">

                            {{-- AVATAR --}}
                            <div class="flex justify-center sm:block shrink-0">

                                <div class="relative">

                                    <div
                                        class="w-40 h-40 rounded-full
                                   ring-4 ring-slate-900
                                   overflow-hidden
                                   shadow-2xl">

                                        <img src="{{ $user->profil_photo_url }}" class="w-full h-full object-cover">

                                    </div>

                                    {{-- Badge --}}
                                    <div
                                        class="absolute bottom-3 right-3
                                   w-5 h-5 rounded-full
                                   bg-green-500
                                   ring-2 ring-slate-900">
                                    </div>

                                </div>

                            </div>

                            {{-- INFOS --}}
                            <div class="flex-1 min-w-0">

                                <div class="flex flex-col gap-4">

                                    {{-- TOP --}}
                                    <div class="min-w-0">

                                        <div class="flex flex-wrap items-center gap-2">

                                            <h1
                                                class="text-2xl sm:text-3xl
                                                       font-bold
                                                       break-words">

                                                {{ $user->getFullName(true) }}

                                            </h1>

                                            <span
                                                class="px-3 py-1 rounded-full
                                                         bg-indigo-500/10
                                                         text-indigo-400
                                                         text-xs shrink-0">

                                                Enseignant Permanent

                                            </span>

                                        </div>

                                        <p class="mt-2 text-slate-400 text-sm">

                                            ID : {{ $teacher->identifiant }}

                                        </p>

                                    </div>

                                    {{-- GRID INFOS --}}
                                    <div
                                        class="grid
                                                grid-cols-2
                                                lg:grid-cols-4
                                                gap-3">

                                        <div class="rounded-2xl bg-slate-950 p-3">

                                            <p class="text-xs text-slate-500">
                                                Téléphone
                                            </p>

                                            <h4 class="mt-1 font-medium truncate">
                                                {{ $user->contacts }}
                                            </h4>

                                        </div>

                                        <div class="rounded-2xl bg-slate-950 p-3">

                                            <p class="text-xs text-slate-500">
                                                Expérience
                                            </p>

                                            <h4 class="mt-1 font-medium">
                                                12 ans
                                            </h4>

                                        </div>

                                        <div class="rounded-2xl bg-slate-950 p-3">

                                            <p class="text-xs text-slate-500">
                                                Statut
                                            </p>

                                            <h4 class="mt-1 font-medium text-emerald-400">
                                                Actif
                                            </h4>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div
                            class="grid
                                    grid-cols-2
                                    sm:grid-cols-4
                                    xl:grid-cols-2
                                    gap-3
                                    xl:w-[260px]
                                    shrink-0">

                            <button
                                class="h-12 rounded-2xl
                                           bg-indigo-500
                                           hover:bg-indigo-600
                                           transition-all
                                           text-sm">

                                Modifier

                            </button>

                            <button
                                class="h-12 rounded-2xl
                                           bg-slate-800
                                           hover:bg-slate-700
                                           transition-all
                                           text-sm">

                                Emploi du temps

                            </button>

                            <button
                                class="h-12 rounded-2xl
                                           bg-slate-800
                                           hover:bg-slate-700
                                           transition-all
                                           text-sm">

                                Notes

                            </button>

                            <button
                                class="h-12 rounded-2xl
                                           bg-rose-500/20
                                           text-rose-400
                                           hover:bg-rose-500/30
                                           transition-all
                                           text-sm">

                                Désactiver

                            </button>
                            <a wire:navigate
                                href="{{ route('tenant.teacher.manage.subjects', ['teacher_uuid' => $teacher->uuid]) }}"
                                class="flex justify-center gap-x-3 items-center w-full rounded-2xl py-3 px-2  bg-indigo-500/20 text-indigo-400 hover:bg-indigo-500/30 transition-all col-span-2 text-sm"
                                style="">
                                <span class="" style="">
                                    ⚙️
                                </span>
                                <span class="text-center">
                                    Gérer les matières
                                </span>
                            </a>

                        </div>

                    </div>

                </div>
                <div class=" bg-slate-950 p-3">

                    <p class="text-lg text-slate-500 border-b border-b-slate-600">
                        Matière(s) | Spécilaité(s)
                    </p>

                    <h4 class="mt-1 font-medium flex flex-wrap gap-2 text-sm">
                        @foreach ($teacher->getYearlySubjects() as $yearly_subject)
                            <span
                                class="rounded-2xl p-2 font-mono bg-indigo-900/40 text-slate-400 cursor-pointer hover:scale-105 transition-transform">{{ $yearly_subject->subject->name }}</span>
                        @endforeach
                    </h4>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- KPI --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div
                class="grid
                        grid-cols-2
                        xl:grid-cols-4
                        gap-4">

                @foreach ([['Classes', '8', 'text-indigo-400'], ['Heures/Sem.', '26h', 'text-emerald-400'], ['Notes Publiées', '482', 'text-amber-400'], ['Présence', '98%', 'text-sky-400']] as $kpi)
                    <div
                        class="rounded-3xl
                            border border-slate-800
                            bg-slate-900
                            p-4 sm:p-5">

                        <p class="text-xs sm:text-sm text-slate-400 truncate">
                            {{ $kpi[0] }}
                        </p>

                        <h2
                            class="mt-3
                               text-2xl sm:text-3xl xl:text-4xl
                               font-bold {{ $kpi[2] }}">

                            {{ $kpi[1] }}

                        </h2>

                    </div>
                @endforeach

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- MAIN --}}
        {{-- ===================================================== --}}
        <section>

            <div
                class="grid
                        grid-cols-1
                        2xl:grid-cols-[minmax(0,1fr)_400px]
                        gap-6">

                {{-- ===================================================== --}}
                {{-- LEFT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">

                    {{-- CLASSES --}}
                    <div
                        class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                overflow-hidden">

                        {{-- HEADER --}}
                        <div class="border-b border-slate-800
                                    p-4 sm:p-6">

                            <div
                                class="flex flex-col lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                        gap-4">

                                <div>

                                    <h2 class="text-lg sm:text-xl font-semibold">
                                        Classes Enseignées
                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Gestion des performances par classe
                                    </p>

                                </div>

                                <button
                                    class="h-11 px-5 rounded-2xl
                                               bg-indigo-500
                                               hover:bg-indigo-600
                                               transition-all
                                               text-sm">

                                    Ajouter Classe

                                </button>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto">

                            <table class="min-w-[950px] w-full">

                                <thead class="bg-slate-950 border-b border-slate-800">

                                    <tr>

                                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                                            Classe
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Matière
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Élèves
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Notes faites
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Heures/Sem
                                        </th>

                                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                                            Moyenne classe
                                        </th>

                                        <th class="px-6 py-4 text-right text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach (range(1, 4) as $i)
                                        <tr class="hover:bg-slate-800/40 transition-all">

                                            <td class="px-6 py-5">

                                                <div class="flex items-center gap-3">

                                                    <div
                                                        class="w-11 h-11 rounded-2xl
                                                            bg-indigo-500/10
                                                            flex items-center justify-center
                                                            text-indigo-400 font-semibold">

                                                        F2

                                                    </div>

                                                    <div>

                                                        <h3 class="font-medium">
                                                            Terminale F2-{{ $i }}
                                                        </h3>

                                                        <p class="text-sm text-slate-400">
                                                            Série Technique
                                                        </p>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="px-4 py-5 text-center">
                                                Mathématiques
                                            </td>

                                            <td class="px-4 py-5 text-center">
                                                42
                                            </td>

                                            <td class="px-4 py-5 text-center">

                                                <span
                                                    class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                    86

                                                </span>

                                            </td>

                                            <td class="px-4 py-5 text-center">
                                                4h
                                            </td>

                                            <td class="px-4 py-5 text-center font-semibold">
                                                13.8
                                            </td>

                                            <td class="px-6 py-5">

                                                <div class="flex items-center justify-end gap-2">

                                                    <button
                                                        class="w-10 h-10 rounded-xl
                                                               bg-slate-800
                                                               hover:bg-indigo-500
                                                               transition-all">

                                                        👁

                                                    </button>

                                                    <button
                                                        class="w-10 h-10 rounded-xl
                                                               bg-slate-800
                                                               hover:bg-emerald-500
                                                               transition-all">

                                                        ✏

                                                    </button>

                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- EMPLOI DU TEMPS --}}
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

                {{-- ===================================================== --}}
                {{-- RIGHT --}}
                {{-- ===================================================== --}}
                <div class="space-y-6 min-w-0">

                    {{-- STATS --}}
                    <div
                        class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">
                            Statistiques
                        </h2>

                        <div class="mt-5 space-y-5">

                            @foreach ([['Mathématiques', '92%', 'bg-indigo-500'], ['Physique', '81%', 'bg-emerald-500'], ['Informatique', '95%', 'bg-amber-500'], ['Électricité', '76%', 'bg-sky-500']] as $stat)
                                <div>

                                    <div class="flex items-center justify-between">

                                        <span class="text-sm text-slate-300">
                                            {{ $stat[0] }}
                                        </span>

                                        <span class="text-sm font-semibold">
                                            {{ $stat[1] }}
                                        </span>

                                    </div>

                                    <div class="mt-2 h-2 rounded-full bg-slate-800 overflow-hidden">

                                        <div class="h-full rounded-full {{ $stat[2] }}"
                                            style="width: {{ $stat[1] }}">
                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>

                    {{-- INFOS --}}
                    <div
                        class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">
                            Informations
                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach ([['Email', 'enseignant@email.com'], ['Diplôme', 'Master en Mathématiques'], ['Adresse', 'Cotonou, Bénin'], ['Recrutement', '12 Septembre 2015']] as $info)
                                <div class="rounded-2xl bg-slate-950 p-4">

                                    <p class="text-xs text-slate-500">
                                        {{ $info[0] }}
                                    </p>

                                    <h4 class="mt-2 text-sm font-medium break-words">
                                        {{ $info[1] }}
                                    </h4>

                                </div>
                            @endforeach

                        </div>

                    </div>

                    {{-- PERFORMANCE --}}
                    <div
                        class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                        <h2 class="text-lg font-semibold">
                            Qr Code
                        </h2>

                        <div class="mt-6 flex justify-center items-center">

                            <img class="w-52 h-52" src="{{ $teacher->qr_code }}"
                                alt="QR Code de {{ $teacher->user->getFullName() }}">

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

