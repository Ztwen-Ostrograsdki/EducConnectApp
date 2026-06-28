<div class="w-full max-w-full overflow-x-hidden">

    <section class="mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            {{-- LEFT --}}
            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-2xl sm:text-3xl font-bold break-words">
                        Enseignants de la Classe
                    </h1>

                    <span
                        class="px-3 py-1 rounded-full
                                 bg-indigo-500/10
                                 border border-indigo-500/20
                                 text-indigo-400 text-xs shrink-0">

                        8 enseignants

                    </span>

                </div>

                <p class="mt-2 text-slate-400 text-sm sm:text-base">

                    Gestion des professeurs, matières et statistiques pédagogiques.

                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <button
                    class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-indigo-500 hover:bg-indigo-600
                               transition-all duration-300
                               text-sm sm:text-base">

                    Ajouter Enseignant

                </button>

                <button
                    class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-slate-800
                               border border-slate-700
                               hover:bg-slate-700
                               transition-all duration-300
                               text-sm sm:text-base">

                    Exporter

                </button>

            </div>

        </div>

    </section>
    <section class="mb-6">

        <div
            class="grid
                    grid-cols-1
                    sm:grid-cols-2
                    xl:grid-cols-4
                    gap-4 sm:gap-6">

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">

                <p class="text-sm text-slate-400 truncate">
                    Enseignants
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                    8
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">

                <p class="text-sm text-slate-400 truncate">
                    Présence Moyenne
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                    94%
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">

                <p class="text-sm text-slate-400 truncate">
                    Notes Publiées
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                    1,240
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">

                <p class="text-sm text-slate-400 truncate">
                    Matières
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                    12
                </h2>

            </div>

        </div>

    </section>

    <section class="mb-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">

            <div class="flex flex-col xl:flex-row gap-4">

                {{-- SEARCH --}}
                <div class="flex-1 min-w-0">

                    <div class="relative">

                        <input wire:model.live='search' type="text" placeholder="Rechercher un enseignant..."
                            class="w-full h-12
                                   rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   outline-none
                                   focus:border-indigo-500
                                   transition-all">

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">

                            🔍

                        </div>

                    </div>

                </div>

                {{-- FILTERS --}}
                <div
                    class="grid
                            sm:grid-cols-3
                            xl:flex
                            gap-3">

                    <select wire:model.live='subjectType'
                        class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Toutes Matières</option>
                        @foreach (config('app.subject_types') as $sub)
                            <option value="{{ $sub }}">{{ $sub }}</option>
                        @endforeach

                    </select>

                    <select wire:model.live='gender'
                        class="h-12 px-4 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   text-sm">

                        <option>Tout genre</option>
                        @foreach (config('app.genders') as $g => $gend)
                            <option value="{{ $gend }}">{{ $gend }}</option>
                        @endforeach

                    </select>
                    <button wire:click="resetFilters"
                        class="px-5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                        <span wire:loading.remove wire:target='resetFilters'>Réinitialiser les filtres</span>
                        <span wire:loading wire:target='resetFilters'
                            class="inline-flex justify-center gap-3.5 items-center">
                            <span class="inline-flex justify-center gap-3.5 items-center">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>

                </div>

            </div>

        </div>

    </section>
    <section class="w-full">

        <div class="flex justify-end flex-wrap gap-3 text-gray-950 p-2">

            <button class="px-3 py-2 rounded-2xl
                                    bg-red-500 hover:bg-red-600">

                Verrouiller notes

            </button>

            <button class="px-3 py-2 rounded-2xl
                                    bg-blue-500 hover:bg-blue-600">

                Imprimer PDF

            </button>

            <button
                class="px-3 py-2 rounded-2xl
                                    bg-emerald-500 hover:bg-emerald-600">

                Emprimer Excel

            </button>

            <button class="px-3 py-2 rounded-2xl
                                    bg-amber-500 hover:bg-amber-600">

                Imprimer Excel et PDF

            </button>

        </div>

        <div class="border border-slate-800 bg-slate-900 overflow-hidden text-slate-300 h-screen">

            <div class="overflow-x-auto">

                @if ($teachers->isEmpty())
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-16 text-center">
                        <div class="text-4xl mb-4">🏫</div>
                        <p class="text-slate-400 text-sm">Aucune classe trouvée pour ces filtres.</p>
                        <button wire:click="resetFilters"
                            class="mt-4 px-5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                            <span wire:loading.remove wire:target='resetFilters'>Réinitialiser les filtres</span>
                            <span wire:loading wire:target='resetFilters'
                                class="inline-flex justify-center gap-3.5 items-center">
                                <span class="inline-flex justify-center gap-3.5 items-center">
                                    <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                    <span>En cours...</span>
                                </span>
                            </span>
                        </button>
                    </div>
                @else
                    <table class="w-full z-table z-table-border">

                        <thead class="bg-slate-950 border-b border-slate-800 truncate">

                            <tr>

                                <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                    N°
                                </th>
                                <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                    Enseignant
                                </th>

                                <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                    <span class="inline-flex flex-col">
                                        <span> Matière enseignées</span>
                                        <span>dans cette classe</span>
                                    </span>
                                </th>

                                <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                    Autres Classes
                                </th>

                                <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800 text-center">

                            @foreach ($teachers as $teacher)
                                <tr class="hover:bg-slate-800/40 transition-all">
                                    <td class="px-3 py-5 text-center whitespace-nowrap">

                                        {{ __zero($loop->iteration) }}

                                    </td>
                                    <td class="px-6 py-5 truncate">
                                        <a href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                            class="flex items-center gap-4 underline-offset-4 hover:underline hover:text-amber-600">
                                            <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4">
                                                <img src="{{ $teacher->user->profil_photo_url }}"
                                                    class="w-full h-full object-cover rounded-full">
                                            </div>
                                            <div class="text-left">
                                                <span class="flex flex-col">
                                                    <h3 class="font-semibold truncate">
                                                        {{ $teacher->getFullName() }}
                                                    </h3>
                                                    <span class="text-slate-400 font-mono text-xs">
                                                        ID: {{ $teacher->identifiant }}
                                                    </span>
                                                    <span
                                                        class="text-slate-400 font-mono text-xs flex gap-x-2 items-center">
                                                        <x-lucide-phone class="w-3 h-3" />
                                                        <span class="ls-1">{{ $teacher->user?->contacts }}</span>
                                                    </span>
                                                    <span
                                                        class="text-slate-400 font-mono text-xs flex gap-x-2 items-center">
                                                        <x-lucide-mail class="w-3 h-3" />
                                                        <span>{{ $teacher->user?->email }}</span>
                                                    </span>
                                                </span>
                                            </div>

                                        </a>
                                        <small class="font-mono text-xs flex justify-center w-full text-yellow-500">
                                            Tient la classe depuis
                                            {{ __formatDate($teacher->classeSubjects->first()->started_at) }}
                                        </small>
                                        @if ($teacher->cannotAccessIntoClasse($classe->id))
                                            <span
                                                class="bg-red-400/30 text-red-500 mt-2 rounded-2xl border border-red-500 animate-pulse p-1 font-mono text-xs flex w-full px-2.5 justify-center">
                                                Accès à la classe bloqué
                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-6 py-5 truncate">
                                        @foreach ($teacher->getSubjectsForThisClasse($classe->id) as $subjectRelation)
                                            <span
                                                class="px-3 py-1 rounded-full
                                             bg-indigo-500/10
                                             text-indigo-400
                                             text-sm">
                                                {{ $subjectRelation->subject->code }}
                                            </span>
                                        @endforeach
                                    </td>

                                    <td class="px-6 py-5 truncate">

                                        <div class="flex flex-wrap justify-center gap-2 ">
                                            @php
                                                $othersClasses = $teacher->getTeacherClassesForThisSchoolYear([
                                                    $classe->id,
                                                ]);

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
                                                    <span>Aucune autre</span>
                                                    <span>classe assignée</span>
                                                </span>
                                            @endif

                                        </div>

                                    </td>
                                    <td class="px-6 py-5">

                                        <div class="flex items-center justify-end gap-2 truncate">

                                            <a wire:navigate
                                                href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                                class="py-3 px-4  rounded-2xl cursor-pointer bg-indigo-800 hover:bg-indigo-500 transition-all flex gap-x-2 items-center">
                                                <x-lucide-user class="w-4 h-4" />
                                                <span>Profil</span>
                                            </a>

                                            @if ($teacher->user)
                                                <button
                                                    title="Envoyer les données de connexion à {{ $teacher->getFullName() }}"
                                                    wire:click="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                    class=" rounded-2xl flex py-3 px-4 items-center flex-1 justify-center cursor-pointer bg-sky-600/50 hover:bg-sky-800/50 text-sky-400">
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

                                            <button
                                                title="{{ $teacher->is_locked ? 'Débloquer ' : 'Bloquer ' }} cet enseigant "
                                                wire:click="{{ $teacher->is_locked ? 'unlockTeacher(' . $teacher->id . ')' : 'lockTeacher(' . $teacher->id . ')' }}"
                                                wire:loading.attr="disabled" wire:target="lockTeacher, unlockTeacher"
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

                                            <button
                                                title="{{ $teacher->cannotAccessIntoClasse($classe->id) ? 'Déverouiller' : 'Vérouiller ' }} l'accès du prof à la classe"
                                                wire:click="{{ $teacher->cannotAccessIntoClasse($classe->id)
                                                    ? 'unLockAccessToClasse(' . $teacher->id . ',' . $classe->id . ')'
                                                    : 'lockAccessToClasse(' . $teacher->id . ',' . $classe->id . ')' }}"
                                                wire:loading.attr="disabled"
                                                wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                class="relative py-3 px-4 rounded-xl {{ !$teacher->cannotAccessIntoClasse($classe->id) ? 'bg-red-600/50 hover:bg-red-500/80' : 'bg-green-500/20 hover:bg-green-600/60' }} transition-all text-xs font-medium">
                                                <span wire:loading.remove
                                                    wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                    class="inline-flex items-center justify-center gap-3">
                                                    <span class="inline-flex items-center justify-center gap-3">
                                                        @if ($teacher->cannotAccessIntoClasse($classe->id))
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
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4" />
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

                @endif

            </div>

        </div>

    </section>

</div>

