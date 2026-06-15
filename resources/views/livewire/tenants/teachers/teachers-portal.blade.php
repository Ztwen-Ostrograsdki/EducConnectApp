<div class="w-full overflow-x-hidden p-2">

    {{-- ===================================================== --}}
    {{-- GLOBAL CONTAINER --}}
    {{-- ===================================================== --}}
    <div class="mx-auto
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

            <div class="flex flex-col
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

                        <span class="px-3 py-1 rounded-full
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

                    <button wire:click='printTeachersList' class="py-2.5 px-5 rounded-2xl bg-sky-500/50 hover:bg-sky-600/75 transition-all text-sm">
                        <span wire:loading.remove wire:target='printTeachersList' class="inline-flex gap-x-2 items-center">
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

                    <a href="{{ route('tenant.teachers.create') }}" class="py-2.5 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm">
                        Ajouter Enseignant
                    </a>

                    @if ($doc = \App\Models\GeneratedDocument::ofType('teacher_list')->forUser(auth()->id())->latest()->first())

                        <div class="flex items-center gap-3">
                            <button wire:click="trackDownload({{ $doc->id }})" class="bg-green-600 hover:bg-green-800 text-white rounded-2xl py-2.5 px-5 transition-all text-sm">
                                <span wire:loading.remove wire:target='trackDownload({{ $doc->id }})' class="inline-flex gap-x-2 items-center">
                                    <x-lucide-save class="w-4 h-4" />
                                    Télécharger liste
                                    @if ($doc->downloaded_count > 0)
                                        <span class="text-xs opacity-60">({{ $doc->downloaded_count }}x)</span>
                                    @endif
                                </span>
                                <span wire:loading wire:target='trackDownload({{ $doc->id }})' class="inline-flex items-center gap-x-2">
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

            <div class="grid
                        grid-cols-2
                        xl:grid-cols-4
                        gap-4">

                @foreach ([['Total', __zero($allTeachersCounter), 'text-indigo-400'], ['Actifs', __zero($activesTeachersCounter), 'text-emerald-400'], ['Taux Présence', '96%', 'text-amber-400']] as $kpi)
                    <div class="rounded-3xl
                            border border-slate-800
                            bg-slate-900
                            p-4 sm:p-5">

                        <p class="text-xs sm:text-sm text-slate-400">
                            {{ $kpi[0] }}
                        </p>

                        <h2 class="mt-3
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

            <div class="rounded-3xl
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
                    <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-5
                                gap-3">

                        <select class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes matières</option>
                            <option>Mathématiques</option>
                            <option>Physique</option>

                        </select>

                        <select class="h-11 px-3 rounded-2xl
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

                        <select class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Statut</option>
                            <option>Actif</option>
                            <option>Suspendu</option>

                        </select>

                        <button wire:click='clearFilters' class="h-11 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm">
                            <span wire:loading.remove wire:target='clearFilters' class="inline-flex gap-x-2 items-center">
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

            <div class="space-y-6 min-w-0">

                {{-- TEACHERS TABLE --}}
                <div class="rounded-3xl
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
                                <div class="flex flex-wrap items-center gap-3 text-sm ">
                                    <button wire:key="unlock-teacher-all" wire:click="unlockTeachers" wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl flex items-center px-2.5 justify-center cursor-pointer bg-lime-500/10 hover:bg-lime-700/50 text-lime-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="unlockTeachers">
                                            <x-lucide-lock-keyhole-open class="w-4 h-4" />
                                            Débloquer tous
                                        </span>
                                        <span wire:loading.flex wire:target="unlockTeachers" class="items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>
                                    <x-confirm-modal wire:key="confirm-unlock-teacher-all" :show="$showConfirmTeachersUnLock" title="Débloquer tous les enseignants bloqués " confirm-text="Oui, déboqué" cancel-text="Annuler"
                                        confirm-action="ConfirmTeachersUnLocking" close-action="closeModal">
                                        <p>Cette action entrainera : </p>
                                        <ul class="text-green-500 text-xs">
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5" />
                                                <span>
                                                    Le déblocage de tous les enseignants bloqués
                                                </span>
                                            </li>
                                        </ul>
                                    </x-confirm-modal>
                                    <button wire:key="lock-teacher-all" wire:click="lockTeachers" wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl flex px-2.5 items-center justify-center cursor-pointer bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="lockTeachers">
                                            <x-lucide-ban class="w-4 h-4" />
                                            Bloquer tous
                                        </span>
                                        <span wire:loading.flex wire:target="lockTeachers" class="items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>
                                    <x-confirm-modal wire:key="confirm-lock-teacher-all" :show="$showConfirmTeachersLock" title="Bloquer l'accès à tous les enseignants " confirm-text="Oui, placer tous dans la corbeille" cancel-text="Annuler"
                                        confirm-action="ConfirmTeacherLocking" close-action="closeModal">
                                        <p>Cette action entrainera : </p>
                                        <ul class="text-orange-500 text-xs">
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5" />
                                                <span>
                                                    Les enseignants n'ouront plus accès à leur espace
                                                </span>
                                            </li>
                                        </ul>
                                    </x-confirm-modal>

                                    <button wire:key="restore-teacher-all" wire:click="restoreTeachers" wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl flex px-2.5 items-center justify-center cursor-pointer bg-purple-500/10 hover:bg-purple-500/20 text-purple-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="restoreTeachers">
                                            <x-lucide-recycle class="w-4 h-4" />
                                            Restaurer tous
                                        </span>
                                        <span wire:loading.flex wire:target="restoreTeachers" class="items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>
                                    <x-confirm-modal wire:key="confirm-restore-teacher-all" :show="$showConfirmTeachersRestorationModal" title="Restauration de tout enseignant" confirm-text="Oui, restaurer tout enseignant" cancel-text="Annuler"
                                        confirm-action="ConfirmTeacherRestoration" close-action="closeModal">
                                        <p>Cette action entrainera : </p>
                                        <ul class="text-green-500 text-xs">
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La restauration de tous les enseignants</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>Chaque enseignant aura de nouveau accès à son espace en tant qu'enseignant</span>
                                            </li>
                                        </ul>
                                    </x-confirm-modal>

                                    <button wire:key="force-del-teacher-all" wire:click="forceDeleteTeachers" wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl flex px-2.5 items-center justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="forceDeleteTeachers">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                            Supprimer déf. tous
                                        </span>
                                        <span wire:loading.flex wire:target="forceDeleteTeachers" class="items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>
                                    <x-confirm-modal wire:key="confirm-force-del-teacher-all" :show="$showConfirmForceDeleteTeachers" title="Suppression définitive de tout enseignant" confirm-text="Oui, Supprimer Déf." cancel-text="Annuler"
                                        confirm-action="ConfirmTeachersForceDelete" close-action="closeModal">
                                        <p>Cette action entrainera : </p>
                                        <ul class="text-orange-500 text-xs">
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-orange-800" />
                                                <span>La suppresion définitive de tous les enseignants de la corbeille</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-amber-600" />
                                                <span>Cette action est irrevsersible et ne sera effective que dans 30 jours!</span>
                                            </li>
                                        </ul>
                                    </x-confirm-modal>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-x-auto">

                        <table class="min-w-[1200px] w-full">

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

                                    <th class="px-3 py-4 text-center text-sm text-slate-400">
                                        Statut
                                    </th>

                                    <th class="px-6 py-4 text-center text-sm text-slate-400">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-800">

                                @foreach ($teachers as $teacher)
                                    <tr wire:key='liste-enseignants-du-portail-'{{ $teacher->id }} class="hover:bg-slate-800/40 transition-all">
                                        <td class="px-3 py-5 text-center whitespace-nowrap">

                                            {{ __zero($loop->iteration) }}

                                        </td>

                                        {{-- PROFILE --}}
                                        <td class="px-6 py-5">

                                            <a title="Charger le profil de l'enseignant {{ $teacher->getFullName() }}" href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                                class="flex items-center gap-4 hover:text-sky-500 hover:underline">

                                                <img src="{{ $teacher->profil_photo_url() }}" alt="" class="w-14 h-14 rounded-full object-cover border-4 border-slate-700">
                                                <div class="min-w-0">

                                                    <h3 class="font-medium truncate">

                                                        {{ $teacher->getFullName() }}

                                                    </h3>

                                                    <p class="mt-1 text-sm text-slate-400 truncate flex items-center gap-x-1.5">
                                                        <x-lucide-mail class="w-3.5 h-3.5" />
                                                        <span>
                                                            {{ $teacher->user->email }}
                                                        </span>

                                                    </p>
                                                    <p class="mt-1 text-sm text-slate-400 truncate font-mono flex items-center gap-x-1.5">

                                                        <x-lucide-phone class="w-3.5 h-3.5" />
                                                        <span>
                                                            {{ $teacher->user->contacts }}
                                                        </span>

                                                    </p>

                                                </div>

                                            </a>

                                        </td>

                                        {{-- SUBJECT --}}
                                        <td class="px-3 py-5 text-center whitespace-nowrap">

                                            {{ $teacher->specialties ? implode($teacher->specialties, '-') : '-' }}

                                        </td>

                                        {{-- CLASSES --}}
                                        <td class="px-3 py-5 text-center">

                                            -

                                        </td>

                                        {{-- HOURS --}}
                                        <td class="px-3 py-5 text-center text-gray-500">

                                            26h

                                        </td>

                                        {{-- STATUS --}}
                                        <td class="px-3 py-5 text-center">

                                            <span class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                Actif

                                            </span>

                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="px-3 py-5 truncate">
                                            <div class="flex items-center gap-2 text-sm w-full">
                                                @if (!$teacher->user->credentials_sent)
                                                    <button wire:key="send-credentials-teacher-{{ $teacher->id }}" wire:click="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-sky-600/50 hover:bg-sky-800/50 text-sky-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')">
                                                            <x-lucide-send class="w-4 h-4" />
                                                            Envoyer
                                                        </span>
                                                        <span wire:loading.flex wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                @endif
                                                @if ($teacher->blocked)
                                                    <button wire:key="unlock-teacher-{{ $teacher->id }}" wire:click="unlockTeacher('{{ $teacher->user->uuid }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-lime-600/50 hover:bg-lime-800/50 text-lime-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="unlockTeacher('{{ $teacher->user->uuid }}')">
                                                            <x-lucide-lock-keyhole-open class="w-4 h-4" />
                                                            Débloquer
                                                        </span>
                                                        <span wire:loading.flex wire:target="unlockTeacher('{{ $teacher->user->uuid }}')" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-unlock-teacher-{{ $teacher->id }}" :show="$showConfirmTeacherUnLock" title="Débloquer l'enseignant {{ $teacher->user->getFullName(true) }}"
                                                        confirm-text="Oui, déboqué" cancel-text="Annuler" confirm-action="ConfirmTeacherUnLocking" close-action="closeModal">
                                                        <p>Cette action entrainera : </p>
                                                        <ul class="text-green-500 text-xs">
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5" />
                                                                <span>
                                                                    {{ $teacher->user->getFullName(true) }} aura de nouveau accès à son espace
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </x-confirm-modal>
                                                @else
                                                    <button wire:key="lock-teacher-{{ $teacher->id }}" wire:click="lockTeacher('{{ $teacher->user->uuid }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="lockTeacher('{{ $teacher->user->uuid }}')">
                                                            <x-lucide-ban class="w-4 h-4" />
                                                            Bloquer
                                                        </span>
                                                        <span wire:loading.flex wire:target="lockTeacher('{{ $teacher->user->uuid }}')" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-lock-teacher-{{ $teacher->id }}" :show="$showConfirmTeacherLock" title="Bloquer l'accès à l'enseignant {{ $teacher->user->getFullName(true) }}"
                                                        confirm-text="Oui, placer dans la corbeille" cancel-text="Annuler" confirm-action="ConfirmTeacherLocking" close-action="closeModal">
                                                        <p>Cette action entrainera : </p>
                                                        <ul class="text-orange-500 text-xs">
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5" />
                                                                <span>
                                                                    {{ $teacher->user->getFullName(true) }} n'aura plus accès à la son espace enseignant
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </x-confirm-modal>
                                                @endif

                                                {{-- DELETE --}}
                                                @if (!$teacher->deleted_at)
                                                    <button wire:key="del-teacher-{{ $teacher->id }}" wire:click="deleteTeacher('{{ $teacher->user->uuid }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="deleteTeacher('{{ $teacher->user->uuid }}')">
                                                            <x-lucide-trash class="w-4 h-4" />
                                                            Corbeille
                                                        </span>
                                                        <span wire:loading.flex wire:target="deleteTeacher('{{ $teacher->user->uuid }}')" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-del-teacher-{{ $teacher->id }}" :show="$showConfirmDeleteTeacher" title="Placer l'enseignant {{ $teacher->getFullName() }} dans la Corbeille"
                                                        confirm-text="Oui, placer dans la corbeille" cancel-text="Annuler" confirm-action="ConfirmTeacherDeletion" close-action="closeModal">
                                                        <p>Cette action entrainera : </p>
                                                        <ul class="text-orange-600 text-xs">
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 " />
                                                                <span>La mise en corbeille de l'enseignant</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 " />
                                                                <span>L'enseignant n'aura plus accès à son espace enseignant</span>
                                                            </li>

                                                        </ul>
                                                    </x-confirm-modal>
                                                @else
                                                    <button wire:key="restore-teacher-{{ $teacher->id }}" wire:click="restoreTeacher('{{ $teacher->user->uuid }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-purple-500/10 hover:bg-purple-500/20 text-purple-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="restoreTeacher('{{ $teacher->user->uuid }}')">
                                                            <x-lucide-recycle class="w-4 h-4" />
                                                            Restaurer
                                                        </span>
                                                        <span wire:loading.flex wire:target="restoreTeacher('{{ $teacher->user->uuid }}')" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-restore-teacher-{{ $teacher->id }}" :show="$showConfirmTeacherRestorationModal" title="restauration de l'enseignant {{ $teacher->user->getFullName(true) }}"
                                                        confirm-text="Oui, restaurer l'enseignant {{ $teacher->user->getFullName(true) }}" cancel-text="Annuler" confirm-action="ConfirmTeacherRestoration" close-action="closeModal">
                                                        <p>Cette action entrainera : </p>
                                                        <ul class="text-green-500 text-xs">
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La restauration de l'enseignant</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>L'enseignant aura de nouveau accès à son espace en temps que enseignant</span>
                                                            </li>
                                                        </ul>
                                                    </x-confirm-modal>
                                                    <button wire:key="force-del-teacher-{{ $teacher->id }}" wire:click="forceDeleteTeacher('{{ $teacher->user->uuid }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 px-1.5 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="forceDeleteTeacher('{{ $teacher->user->uuid }}')">
                                                            <x-lucide-trash-2 class="w-4 h-4" />
                                                            Suppr. déf.
                                                        </span>
                                                        <span wire:loading.flex wire:target="forceDeleteTeacher('{{ $teacher->user->uuid }}')" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-force-del-teacher-{{ $teacher->id }}" :show="$showConfirmForceDeleteTeacher" title="Suppression définitive de l'enseignant {{ $teacher->user->getFullName() }}"
                                                        confirm-text="Oui, Supprimer Déf." cancel-text="Annuler" confirm-action="ConfirmTeacherForceDelete" close-action="closeModal">
                                                        <p>Cette action entrainera : </p>
                                                        <ul class="text-orange-500 text-xs">
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-orange-800" />
                                                                <span>La suppresion définitive de l'utilisateur {{ $teacher->getFullName() }}</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-orange-800" />
                                                                <span>La suppresion définitive de l'enseignant {{ $teacher->getFullName() }}</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-amber-600" />
                                                                <span>Cette action est irrevsersible et ne sera effective que dans 30 jours!</span>
                                                            </li>
                                                        </ul>
                                                    </x-confirm-modal>
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

                        <div class="flex flex-col sm:flex-row
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
                <div class="rounded-3xl
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

                                <div class="mt-2 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                                    <div class="h-full rounded-full {{ $item[2] }}" style="width: {{ $item[1] }}">
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- RECENT ACTIVITY --}}
                <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                    <h2 class="text-lg font-semibold">

                        Activités Récentes

                    </h2>

                    <div class="mt-5 space-y-4">

                        @foreach (range(1, 5) as $activity)
                            <div class="rounded-2xl
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

