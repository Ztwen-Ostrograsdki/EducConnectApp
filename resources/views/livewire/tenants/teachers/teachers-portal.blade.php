<div class="w-full overflow-x-hidden p-2">

    {{-- ===================================================== --}}
    {{-- GLOBAL CONTAINER --}}
    {{-- ===================================================== --}}
    <div
        class="mx-auto
                w-full
                max-w-[1850px]
                px-3
                sm:px-3
                lg:px-6
                xl:px-8">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div
                class="flex flex-col
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                        gap-5">

                {{-- LEFT --}}
                <div class="min-w-0">

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl sm:text-3xl font-bold">

                            Enseignants

                        </h1>

                        <span
                            class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400
                                     text-xs">

                            {{ __zero($allTeachersCounter) }} Enseignants

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm sm:text-base">

                        Vue globale du personnel enseignant de l’établissement

                    </p>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-wrap items-center gap-3">

                    <button wire:click='printTeachersList'
                        class="py-2.5 px-5 rounded-2xl bg-sky-500/50 hover:bg-sky-600/75 transition-all text-sm">
                        <span wire:loading.remove wire:target='printTeachersList'
                            class="inline-flex gap-x-2 items-center">
                            <x-lucide-save class="w-4 h-4" />
                            Exporter la liste en PDF
                        </span>
                        <span wire:loading wire:target='printTeachersList' class="inline-flex items-center gap-x-2">
                            <span class="flex items-center gap-x-2.2">
                                <span>Document en cours...</span>
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            </span>
                        </span>

                    </button>

                    <a href="{{ route('tenant.teachers.create') }}"
                        class="py-2.5 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm">
                        Ajouter Enseignant
                    </a>

                    @if ($doc = \App\Models\GeneratedDocument::ofType('teacher_list')->forUser(auth()->id())->latest()->first())

                        <div class="flex items-center gap-3">
                            <button wire:click="trackDownload({{ $doc->id }})"
                                class="bg-green-600 hover:bg-green-800 text-white rounded-2xl py-2.5 px-5 transition-all text-sm">
                                <span wire:loading.remove wire:target='trackDownload({{ $doc->id }})'
                                    class="inline-flex gap-x-2 items-center">
                                    <x-lucide-save class="w-4 h-4" />
                                    Télécharger liste
                                    @if ($doc->downloaded_count > 0)
                                        <span class="text-xs opacity-60">({{ $doc->downloaded_count }}x)</span>
                                    @endif
                                </span>
                                <span wire:loading wire:target='trackDownload({{ $doc->id }})'
                                    class="inline-flex items-center gap-x-2">
                                    <span class="flex items-center gap-x-2.2">
                                        <span>Document en cours...</span>
                                        <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                    </span>
                                </span>
                            </button>
                            @if (!$doc->downloaded)
                                <span wire:loading.remove wire:target='trackDownload({{ $doc->id }})'
                                    class="text-xs border border-green-600 text-green-600 bg-gray-900 p-0.5 rounded-xl relative right-16 -top-5 px-1.5 animate-pulse">Nouveau</span>
                            @endif
                        </div>
                    @endif

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

                @foreach ([['Total', __zero($allTeachersCounter), 'text-indigo-400'], ['Actifs', __zero($activesTeachersCounter), 'text-emerald-400'], ['Taux Présence', '96%', 'text-amber-400']] as $kpi)
                    <div
                        class="rounded-3xl
                            border border-slate-800
                            bg-slate-900
                            p-4 sm:p-5">

                        <p class="text-xs sm:text-sm text-slate-400">
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
        {{-- FILTER BAR --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div
                class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        p-4 sm:p-5">

                <div class="flex flex-col gap-4">

                    {{-- SEARCH --}}
                    <div class="relative">

                        <input wire:model.live='search' type="text" placeholder="Rechercher un enseignant..."
                            class="w-full h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-500/40">

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">

                            🔍

                        </div>

                    </div>

                    {{-- FILTERS --}}
                    <div
                        class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-5
                                gap-3">

                        <select
                            class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes matières</option>
                            <option>Mathématiques</option>
                            <option>Physique</option>

                        </select>

                        <select
                            class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes classes</option>
                            <option>Terminale F2-1</option>

                        </select>

                        <select wire:model.live='gender'
                            class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Sexe</option>
                            @foreach ($genders as $gk => $gdr)
                                <option value="{{ $gk }}">{{ $gdr }}</option>
                            @endforeach

                        </select>

                        <select
                            class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Statut</option>
                            <option>Actif</option>
                            <option>Suspendu</option>

                        </select>

                        <button wire:click='clearFilters'
                            class="h-11 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm">
                            <span wire:loading.remove wire:target='clearFilters'
                                class="inline-flex gap-x-2 items-center">
                                <x-lucide-brush-cleaning class="w-4 h-4" />
                                Réinitialiser
                            </span>
                            <span wire:loading wire:target='clearFilters' class="inline-flex items-center gap-x-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            </span>

                        </button>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- MAIN GRID --}}
        {{-- ===================================================== --}}
        <section>

            @php
                $unaccesses = tenancy()->tenant?->getTeachersWithoutYearlyAccesses();
            @endphp
            @if (count($unaccesses))
                <div
                    class="rounded-2xl border border-red-800 bg-red-900/30 p-2 font-mono text-sm animate-pulse text-red-400 my-3">
                    <span>{{ __zero(count($unaccesses)) }} enseigant(s) sont sans accès pour cette année scolaire
                        {{ $this->activeYear?->slug ?? '' }}</span>
                    <p>
                        Veuillez leur accorder les accès. Autrement, vous ne
                        pourriez ni définir leurs matières ni leur attribuer de classe!
                    </p>
                </div>
            @endif

            <div class="space-y-6 min-w-0">

                {{-- TEACHERS TABLE --}}
                <div
                    class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                overflow-hidden">

                    {{-- HEADER --}}
                    <div class="border-b border-slate-800
                                    p-4 sm:p-6">

                        <div class="flex flex-col gap-y-3">
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold">

                                    Liste des Enseignants

                                </h2>

                                <p class="mt-1 text-sm text-slate-400">

                                    Gestion et suivi du personnel enseignant

                                </p>

                            </div>

                            <div class="flex w-full justify-end">
                                <div class="flex w-full justify-end">
                                    <div class="flex flex-wrap items-center gap-3 text-sm">

                                        {{-- Débloquer tous --}}
                                        <button wire:click="unlockTeachers" wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex items-center px-2.5 justify-center cursor-pointer bg-lime-500/10 hover:bg-lime-700/50 text-lime-400">
                                            <span wire:loading.remove wire:target="unlockTeachers"
                                                class="flex items-center gap-1.5">
                                                <x-lucide-lock-keyhole-open class="w-4 h-4" />
                                                Débloquer tous
                                            </span>
                                            <span wire:loading wire:target="unlockTeachers"
                                                class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-x-2">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>

                                        {{-- Bloquer tous --}}
                                        <button wire:click="lockTeachers" wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex px-2.5 items-center justify-center cursor-pointer bg-orange-500/10 hover:bg-orange-500/20 text-orange-400">
                                            <span wire:loading.remove wire:target="lockTeachers"
                                                class="flex items-center gap-1.5">
                                                <x-lucide-ban class="w-4 h-4" />
                                                Bloquer tous
                                            </span>
                                            <span wire:loading wire:target="lockTeachers"
                                                class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-x-2">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>

                                        {{-- Restaurer tous --}}
                                        <button wire:click="restoreTeachers" wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex px-2.5 items-center justify-center cursor-pointer bg-purple-500/10 hover:bg-purple-500/20 text-purple-400">
                                            <span wire:loading.remove wire:target="restoreTeachers"
                                                class="flex items-center gap-1.5">
                                                <x-lucide-recycle class="w-4 h-4" />
                                                Restaurer tous
                                            </span>
                                            <span wire:loading wire:target="restoreTeachers"
                                                class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-x-2">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>

                                        {{-- Supprimer déf. tous --}}
                                        <button wire:click="forceDeleteTeachers" wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex px-2.5 items-center justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400">
                                            <span wire:loading.remove wire:target="forceDeleteTeachers"
                                                class="flex items-center gap-1.5">
                                                <x-lucide-trash-2 class="w-4 h-4" />
                                                Supprimer déf. tous
                                            </span>
                                            <span wire:loading wire:target="forceDeleteTeachers"
                                                class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-x-2">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-x-auto">

                        <table class="z-table-border w-full">

                            <thead class="bg-slate-950 border-b border-slate-800">

                                <tr>

                                    <th class="px-3 py-4 text-left text-sm text-slate-400">
                                        N°
                                    </th>
                                    <th class="px-3 py-4 text-left text-sm text-slate-400">
                                        Enseignant
                                    </th>

                                    <th class="px-3 py-4 text-center text-sm text-slate-400">
                                        Matière
                                    </th>

                                    <th class="px-3 py-4 text-center text-sm text-slate-400">
                                        Classes
                                    </th>

                                    <th class="px-3 py-4 text-center text-sm text-slate-400">
                                        Heures/Sem
                                    </th>

                                    <th class="px-6 py-4 text-center text-sm text-slate-400">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-800">

                                @foreach ($teachers as $teacher)
                                    <tr wire:key='liste-enseignants-du-portail-'{{ $teacher->id }}
                                        class="hover:bg-slate-800/40 transition-all">
                                        <td class="px-3 py-5 text-center whitespace-nowrap">

                                            {{ __zero($loop->iteration) }}

                                        </td>

                                        {{-- PROFILE --}}
                                        <td class="px-6 py-5 text-slate-400">

                                            <a title="Charger le profil de l'enseignant {{ $teacher->getFullName() }}"
                                                href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                                class="flex items-center gap-4 underline-offset-4 hover:underline hover:text-amber-600">

                                                <img src="{{ $teacher->profil_photo_url() }}"
                                                    alt="Photo de profil de {{ $teacher->fullName() }}"
                                                    class="w-14 h-14 rounded-full object-cover border-4 border-slate-700">
                                                <div class="min-w-0">

                                                    <h3 class="font-medium ">

                                                        {{ $teacher->getFullName() }}

                                                    </h3>

                                                    <p class="mt-1 text-sm text-slate-400 flex items-center gap-x-1.5">
                                                        <x-lucide-mail class="w-3.5 h-3.5" />
                                                        <span>
                                                            {{ $teacher->user->email }}
                                                        </span>

                                                    </p>
                                                    <p
                                                        class="mt-1 text-sm text-slate-400 font-mono flex items-center gap-x-1.5">

                                                        <x-lucide-phone class="w-3.5 h-3.5" />
                                                        <span>
                                                            {{ $teacher->user->contacts }}
                                                        </span>

                                                    </p>

                                                </div>

                                            </a>
                                            <span
                                                class="px-3 rounded-full @if ($teacher->hasValidAccessForYear()) bg-emerald-500/10 text-emerald-400 @else  bg-red-500/10 text-red-400 animate-pulse @endif border border-slate-600 w-full flex text-xs py-1 mt-2 text-center items-center justify-center gap-x-1">
                                                <span>Accès
                                                    {{ tenancy()->tenant?->getActiveSchoolYear()?->slug }}</span>
                                                @if ($teacher->hasValidAccessForYear())
                                                    <span> accordé</span>
                                                @else
                                                    <span> non accordé</span>
                                                @endif
                                            </span>

                                        </td>

                                        {{-- SUBJECT --}}
                                        <td class="px-3 py-5 text-center whitespace-nowrap">

                                            <div class="mt-1 font-medium flex gap-2 text-sm">
                                                @foreach ($teacher->getYearlySubjects() as $yearly_subject)
                                                    <span
                                                        class="rounded-xl p-1 px-3 font-mono bg-indigo-900/40 text-slate-400 cursor-pointer hover:scale-105 transition-transform">{{ $yearly_subject->subject->code }}</span>
                                                @endforeach
                                            </div>

                                        </td>

                                        {{-- CLASSES --}}
                                        <td class="px-3 py-5 text-center">

                                            @php
                                                $othersClasses = $teacher->getTeacherClassesForThisSchoolYear([]);

                                            @endphp
                                            @if (count($othersClasses))
                                                @foreach ($othersClasses as $cl)
                                                    <span
                                                        class="px-2 py-1 rounded-xl bg-slate-800 text-xs uppercase font-mono border border-sky-700">
                                                        {{ $cl?->code ?? $cl->name }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span
                                                    class="px-2 py-1 rounded-xl text-slate-400 ls-2 italic text-xs flex justify-center flex-col">
                                                    <span>Aucune</span>
                                                    <span>classe assignée</span>
                                                </span>
                                            @endif

                                        </td>

                                        {{-- HOURS --}}
                                        <td class="px-3 py-5 text-center text-gray-500">

                                            -

                                        </td>

                                        <td class="px-3 py-5 truncate">
                                            <div class="flex items-center gap-2 text-xs w-full ">

                                                @if ($teacher->hasValidAccessForYear())
                                                    <a title="Définir les matières spécialités de l'enseigant {{ $teacher->getFullName() }}"
                                                        wire:navigate
                                                        href="{{ route('tenant.teacher.manage.subjects', ['teacher_uuid' => $teacher->uuid]) }}"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-indigo-600/50 hover:bg-indigo-800/50 text-indigo-400 gap-x-2">
                                                        <span>⚙️</span>
                                                        <span>Matières</span>
                                                    </a>
                                                @endif

                                                {{-- Envoyer credentials --}}
                                                @if (!$teacher->user->credentials_sent)
                                                    <button
                                                        title="Envoyer les données de connexion à {{ $teacher->getFullName() }}"
                                                        wire:click="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-sky-600/50 hover:bg-sky-800/50 text-sky-400">
                                                        <span wire:loading.remove
                                                            wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                            class="flex items-center gap-1.5">
                                                            <x-lucide-send class="w-4 h-4" />
                                                            Envoyer
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                            class="flex items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                @endif

                                                {{-- Bloquer / Débloquer --}}
                                                <button
                                                    title="{{ $teacher->is_locked ? 'Débloquer ' : 'Bloquer ' }} l'enseigant {{ $teacher->getFullName() }}"
                                                    wire:click="{{ $teacher->is_locked ? 'unlockTeacher(' . $teacher->id . ')' : 'lockTeacher(' . $teacher->id . ')' }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="lockTeacher, unlockTeacher"
                                                    class="relative py-3 px-4 rounded-xl {{ !$teacher->is_locked ? 'bg-amber-600/80 hover:bg-amber-800/80' : 'bg-purple-500/20 hover:bg-purple-600/60' }} transition-all text-xs font-medium">
                                                    <span wire:loading.remove wire:target="lockTeacher, unlockTeacher"
                                                        class="inline-flex items-center justify-center gap-3">
                                                        <span class="inline-flex items-center justify-center gap-3">
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
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v8z" />
                                                        </svg>
                                                    </span>
                                                </button>

                                                {{-- Accorder accès --}}
                                                @if (!$teacher->deleted_at)
                                                    <button
                                                        title="{{ $teacher->hasValidAccessForYear() ? 'Retirer ' : 'Accorder ' }} l'accès à l'enseigant {{ $teacher->getFullName() }} pour cette année scolaire "
                                                        wire:click="{{ !$teacher->hasValidAccessForYear() ? 'giveAccessForThisSchoolYear(' . $teacher->id . ')' : 'removeAccessForThisSchoolYear(' . $teacher->id . ')' }}"
                                                        wire:loading.attr="disabled"
                                                        wire:target="removeAccessForThisSchoolYear, giveAccessForThisSchoolYear"
                                                        class="relative py-3 px-4 rounded-xl {{ !$teacher->hasValidAccessForYear() ? 'bg-green-800/60 hover:bg-lime-500/80' : 'bg-orange-700/80 hover:bg-orange-600/40' }} transition-all text-xs font-medium">
                                                        <span wire:loading.remove
                                                            wire:target="removeAccessForThisSchoolYear, giveAccessForThisSchoolYear"
                                                            class="inline-flex items-center justify-center gap-3">
                                                            <span
                                                                class="inline-flex items-center justify-center gap-3">
                                                                @if ($teacher->hasValidAccessForYear())
                                                                    <x-lucide-user-lock class="w-4 h-4" />
                                                                    <span>Retirer accès</span>
                                                                @else
                                                                    <x-lucide-key class="w-4 h-4" />
                                                                    <span>Accorder accès</span>
                                                                @endif
                                                            </span>
                                                        </span>

                                                        <span wire:loading
                                                            wire:target="removeAccessForThisSchoolYear, giveAccessForThisSchoolYear"
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
                                                @endif

                                                <button
                                                    title="{{ $teacher->deleted_at ? 'Restaurer ' : 'Envoyer ' }} l'enseigant {{ $teacher->getFullName() }} {{ $teacher->deleted_at ? ' de ' : ' dans ' }} la corbeille"
                                                    wire:click="{{ $teacher->deleted_at ? 'restoreTeacher(' . $teacher->id . ')' : 'deleteTeacher(' . $teacher->id . ')' }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="deleteTeacher, restoreTeacher"
                                                    class="relative py-3 px-4 rounded-xl {{ !$teacher->deleted_at ? 'bg-red-600/50 hover:bg-red-500/80' : 'bg-green-500/20 hover:bg-green-600/60' }} transition-all text-xs font-medium">
                                                    <span wire:loading.remove
                                                        wire:target="deleteTeacher, restoreTeacher"
                                                        class="inline-flex items-center justify-center gap-3">
                                                        <span class="inline-flex items-center justify-center gap-3">
                                                            @if ($teacher->deleted_at)
                                                                <x-lucide-recycle class="w-4 h-4" />
                                                                <span>Restaurer</span>
                                                            @else
                                                                <x-lucide-trash class="w-4 h-4" />
                                                                <span>Corbeille</span>
                                                            @endif
                                                        </span>
                                                    </span>

                                                    <span wire:loading wire:target="deleteTeacher, restoreTeacher"
                                                        class="inline-flex items-center gap-1">
                                                        <svg class="animate-spin w-3 h-3" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v8z" />
                                                        </svg>
                                                    </span>
                                                </button>
                                                @if ($teacher->deleted_at)
                                                    <button
                                                        wire:click="{{ 'forceDeleteTeacher(' . $teacher->id . ')' }}"
                                                        wire:loading.attr="disabled" wire:target="forceDeleteTeacher"
                                                        class="relative py-3 px-4 rounded-xl bg-red-500/20 hover:bg-red-600/60 transition-all text-xs font-medium">
                                                        <span wire:loading.remove wire:target="forceDeleteTeacher"
                                                            class="inline-flex items-center justify-center gap-3">
                                                            <span
                                                                class="inline-flex items-center justify-center gap-3">

                                                                <x-lucide-trash-2 class="w-4 h-4" />
                                                                <span>Suppr. Déf.</span>
                                                            </span>
                                                        </span>

                                                        <span wire:loading wire:target="forceDeleteTeacher"
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
                                                @endif

                                            </div>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    {{-- PAGINATION --}}
                    <div class="border-t border-slate-800
                                    px-3 sm:px-6 py-4">

                        <div
                            class="flex flex-col sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                        gap-4">

                            <p class="text-sm text-slate-400">

                                Affichage de 1 à 10 sur 248 enseignants

                            </p>

                            <div class="flex items-center gap-2">

                                <button
                                    class="h-10 px-3 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                    Précédent

                                </button>

                                <button
                                    class="h-10 px-3 rounded-xl
                                                   bg-indigo-500
                                                   hover:bg-indigo-600
                                                   transition-all text-sm">

                                    1

                                </button>

                                <button
                                    class="h-10 px-3 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                    2

                                </button>

                                <button
                                    class="h-10 px-3 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                    Suivant

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>
        <section class="my-4">
            <div class="space-y-6">

                {{-- QUICK STATS --}}
                <div
                    class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                    <h2 class="text-lg font-semibold">

                        Répartition Matières

                    </h2>

                    <div class="mt-5 space-y-5">

                        @foreach ([['Mathématiques', '82%', 'bg-indigo-500'], ['Physique', '70%', 'bg-emerald-500'], ['Informatique', '65%', 'bg-amber-500'], ['Français', '58%', 'bg-sky-500']] as $item)
                            <div>

                                <div class="flex items-center justify-between">

                                    <span class="text-sm text-slate-300">
                                        {{ $item[0] }}
                                    </span>

                                    <span class="text-sm font-semibold">
                                        {{ $item[1] }}
                                    </span>

                                </div>

                                <div
                                    class="mt-2 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                                    <div class="h-full rounded-full {{ $item[2] }}"
                                        style="width: {{ $item[1] }}">
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- RECENT ACTIVITY --}}
                <div
                    class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                    <h2 class="text-lg font-semibold">

                        Activités Récentes

                    </h2>

                    <div class="mt-5 space-y-4">

                        @foreach (range(1, 5) as $activity)
                            <div
                                class="rounded-2xl
                                        bg-slate-950
                                        p-4">

                                <div class="flex items-start gap-3">

                                    <div
                                        class="w-11 h-11 rounded-2xl
                                                bg-indigo-500/10
                                                shrink-0
                                                flex items-center justify-center
                                                text-indigo-400">

                                        ✓

                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="font-medium text-sm">

                                            Notes publiées

                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">

                                            M. Jean Kouassi a publié les notes de Terminale F2-1

                                        </p>

                                        <p class="mt-2 text-xs text-slate-500">

                                            Il y a 2 heures

                                        </p>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>
        </section>

    </div>

</div>

