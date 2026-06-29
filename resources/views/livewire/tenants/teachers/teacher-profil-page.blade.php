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

                                                Enseignant

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

                                Editer

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
                        Matière(s) | Spécialité(s)
                    </p>

                    <h4 class="mt-1 font-medium flex flex-wrap gap-2 text-sm">
                        @forelse ($teacher->getYearlySubjects() as $yearly_subject)
                            <span
                                class="rounded-2xl p-2 font-mono bg-indigo-900/40 text-slate-400 cursor-pointer hover:scale-105 transition-transform">{{ $yearly_subject->subject->name }}</span>
                        @empty
                            <span class="text-orange-600/50 italic ls-1 font-mono py-4">Matières et spacialités non
                                spécifiées</span>
                        @endforelse
                    </h4>

                </div>

            </div>

        </section>

        <section class="mb-6">

            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

                @foreach ([['Classes', __zero($teacher?->getTeacherClassesCountForThisSchoolYear()), 'text-indigo-400'], ['Heures/Sem.', '26h', 'text-emerald-400'], ['Notes Publiées', '482', 'text-amber-400'], ['Présence', '98%', 'text-sky-400']] as $kpi)
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
        <section>

            <div class="grid grid-cols-1 2xl:grid-cols-[minmax(0,1fr)_400px] gap-6">
                <div class="space-y-6 min-w-0">
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">
                        <div class="border-b border-slate-800 p-4 sm:p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-semibold">
                                        Classes assignées
                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Gestion des classes dirigées
                                    </p>

                                </div>

                                <button
                                    class="h-11 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm">
                                    Ajouter Classe
                                </button>

                            </div>

                        </div>

                        <div class="overflow-x-auto p-2">
                            @php
                                $classes = $teacher?->getTeacherClassesWithSubjectsForThisSchoolYear();
                            @endphp

                            @if (count($classes))
                                <table class="w-full z-table-border text-slate-400 text-sm">

                                    <thead class="bg-slate-950 border-b border-slate-800">

                                        <tr>

                                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                Classe
                                            </th>

                                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                Matière
                                            </th>

                                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                Notes faites
                                            </th>

                                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                Heures/Sem
                                            </th>

                                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                Actions
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody class="divide-y divide-slate-800">

                                        @foreach ($classes as $kls)
                                            <tr class="hover:bg-slate-800/40 transition-all">

                                                <td class="px-6 py-5">

                                                    <div class="flex items-center gap-3">

                                                        <a href="{{ route('tenant.classe.profil', ['classe_slug' => $kls->classe?->slug]) }}"
                                                            class="hover:underline underline-offset-4 hover:text-lime-500">

                                                            <h3 class="font-medium">
                                                                {{ $kls->classe?->name }}
                                                            </h3>

                                                            <p class="text-xs text-amber-700">
                                                                {{ $kls->classe?->speciality() }}
                                                            </p>

                                                        </a>

                                                    </div>

                                                </td>

                                                <td class="px-4 py-5 text-center">
                                                    {{ $kls->subject?->code ?? $kls->subject?->name }}
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

                                                <td class="px-6 py-5">

                                                    <div class="flex items-center justify-end gap-2">

                                                        <button
                                                            title="{{ $teacher->is_locked ? 'Débloquer ' : 'Bloquer ' }} cet enseigant "
                                                            wire:click="{{ $teacher->is_locked ? 'unlockTeacher(' . $teacher->id . ')' : 'lockTeacher(' . $teacher->id . ')' }}"
                                                            wire:loading.attr="disabled"
                                                            wire:target="lockTeacher, unlockTeacher"
                                                            class="relative py-3 px-4 rounded-xl {{ !$teacher->is_locked ? 'bg-amber-600 hover:bg-amber-800' : 'bg-purple-500/20 hover:bg-purple-600/60' }} text-xs font-medium inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 text-black">
                                                            <span wire:loading.remove
                                                                wire:target="lockTeacher, unlockTeacher"
                                                                class="inline-flex items-center justify-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center gap-2">
                                                                    @if ($teacher->is_locked)
                                                                        <x-lucide-unlock class="w-4 h-4" />
                                                                        <span>Débloquer</span>
                                                                    @else
                                                                        <x-lucide-user-lock class="w-4 h-4" />
                                                                        <span>Bloquer</span>
                                                                    @endif
                                                                </span>
                                                            </span>

                                                            <span wire:loading wire:target="lockTeacher, unlockTeacher"
                                                                class="inline-flex items-center gap-1">
                                                                <svg class="animate-spin w-3 h-3" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4" />
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8v8z" />
                                                                </svg>
                                                            </span>
                                                        </button>

                                                        <button
                                                            title="{{ $teacher->cannotAccessIntoClasse($kls->classe?->id) ? 'Déverouiller' : 'Vérouiller ' }} l'accès du prof à la classe"
                                                            wire:click="{{ $teacher->cannotAccessIntoClasse($kls->classe?->id)
                                                                ? 'unLockAccessToClasse(' . $teacher->id . ',' . $kls->classe?->id . ')'
                                                                : 'lockAccessToClasse(' . $teacher->id . ',' . $kls->classe?->id . ')' }}"
                                                            wire:loading.attr="disabled"
                                                            wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                            class="relative py-3 px-4 rounded-xl {{ !$teacher->cannotAccessIntoClasse($kls->classe?->id) ? 'bg-red-600/50 hover:bg-red-500/80' : 'bg-green-500/20 hover:bg-green-600/60' }}  text-xs font-medium inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 text-black">
                                                            <span wire:loading.remove
                                                                wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                                class="inline-flex items-center justify-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center gap-2">
                                                                    @if ($teacher->cannotAccessIntoClasse($kls->classe?->id))
                                                                        <x-lucide-check class="w-4 h-4" />
                                                                        <span>Déverouiller accès</span>
                                                                    @else
                                                                        <x-lucide-user-lock class="w-4 h-4" />
                                                                        <span>Verouiller accès</span>
                                                                    @endif
                                                                </span>
                                                            </span>

                                                            <span wire:loading
                                                                wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                                class="inline-flex items-center gap-1">
                                                                <svg class="animate-spin w-3 h-3" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4" />
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8v8z" />
                                                                </svg>
                                                            </span>
                                                        </button>

                                                    </div>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            @else
                                <div>
                                    <div class="p-6 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <x-lucide-school class="w-10 h-10 text-orange-600" />
                                            <p class="text-slate-500 text-lg animate-pulse">Aucune classe assignée</p>
                                            <a href="#"
                                                class="mt-2 px-4 w-full py-2 rounded-xl bg-slate-800 hover:bg-orange-700/25 text-sm transition hover:underline underline-offset-4 hover:text-orange-500">
                                                Attribuer des classes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-6">

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

                        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">

                            @foreach (range(1, 6) as $course)
                                <div class="rounded-2xl border border-indigo-500/20 bg-indigo-500/10 p-4">

                                    <div class="flex items-start justify-between gap-3">

                                        <div>

                                            <h3 class="font-semibold">
                                                Terminale F2-1
                                            </h3>

                                            <p class="mt-1 text-sm text-indigo-300">
                                                Mathématiques
                                            </p>

                                        </div>

                                        <span class="px-2 py-1 rounded-xl bg-slate-950/40 text-xs">

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

                <div class="space-y-6 min-w-0">

                    <div class="rounded-3xl border border-slate-800  bg-slate-900 p-5">

                        <h2 class="text-base font-semibold text-slate-400 w-full border-b border-b-slate-700">
                            Titres et responsabilités <span
                                class="text-amber-700">{{ session('school_year_selected') }}</span>
                        </h2>

                        <div class="mt-5 space-y-5">

                            @php
                                $pp_classes = $teacher?->getClassesWhereIsPrincipal();
                            @endphp

                            <div class="flex flex-col gap-2 text-slate-500 text-sm">
                                @if (count($pp_classes))
                                    @foreach ($pp_classes as $cl)
                                        <div class="flex items-center gap-x-3">
                                            <x-lucide-user class="w-4 h-4" />
                                            <span class="text-amber-600">Professeur principal (PP)</span>
                                            <span>de la classe de {{ $cl->code ?? $cl->name }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-yellow-600/80 italic ls-1 font-mono py-4">Aucune
                                        responsabilités accordées à {{ $teacher->getFullName() }} cette année
                                        scolaire</span>
                                @endif
                            </div>

                        </div>

                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                        <h2 class="text-lg font-semibold">
                            Informations
                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach ([['Email', $teacher->user?->email], ['Diplôme', 'Non renseigné'], ['Adresse', $teacher->user?->adresse], ['Recrutement', __formatDate($teacher->affiliated_at)]] as $info)
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

