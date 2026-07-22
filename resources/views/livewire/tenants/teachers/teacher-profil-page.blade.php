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

                                        <img src="{{ $this->teacher->user->profil_photo_url }}"
                                            class="w-full h-full object-cover">

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

                                                {{ $this->teacher->user->getFullName(true) }}

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

                                            ID : {{ $this->teacher->identifiant }}

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
                                                {{ $this->teacher->user->contacts }}
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
                    </div>

                </div>
                <div class=" bg-slate-950 p-3">

                    <p class="text-lg text-slate-500 border-b border-b-slate-600">
                        Matière(s) | Spécialité(s)
                    </p>

                    <h4 class="mt-1 font-medium flex flex-wrap gap-2 text-sm">
                        @forelse ($this->teacher->getYearlySubjects() as $yearly_subject)
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

        <section class="my-3 flex justify-end border-y border-y-slate-800 py-3">
            <div class="flex flex-wrap items-center gap-2 text-xs">

                {{-- Matières --}}
                @if ($this->teacher->hasValidAccessForYear())
                    <a title="Définir les matières de {{ $this->teacher->getFullName() }}" wire:navigate
                        href="{{ route('tenant.teacher.manage.subjects', ['teacher_uuid' => $this->teacher->uuid]) }}"
                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-indigo-800/40 hover:bg-indigo-500/80 text-indigo-400 transition-all whitespace-nowrap hover:text-black">
                        <span>⚙️</span>
                        <span>Gérer les matières</span>
                    </a>
                @endif

                {{-- Envoyer credentials --}}
                @if (!$this->teacher->user->blocked)
                    <button title="Envoyer les données de connexion à {{ $this->teacher->getFullName() }}"
                        wire:click="sendCredentialsToTeacher('{{ $this->teacher->user->uuid }}')"
                        wire:loading.attr="disabled"
                        wire:target="sendCredentialsToTeacher('{{ $this->teacher->user->uuid }}')"
                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-sky-800/50 hover:bg-sky-500/80 text-sky-400 transition-all whitespace-nowrap hover:text-black disabled:opacity-50">
                        <span wire:loading.remove
                            wire:target="sendCredentialsToTeacher('{{ $this->teacher->user->uuid }}')"
                            class="inline-flex items-center gap-1.5">
                            <x-lucide-send class="w-3.5 h-3.5 shrink-0" />
                            <span>Envoyer</span>
                        </span>
                        <span wire:loading wire:target="sendCredentialsToTeacher('{{ $this->teacher->user->uuid }}')"
                            class="inline-flex items-center gap-1.5">
                            <span class="flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>
                @endif

                {{-- Bloquer / Débloquer --}}
                <button
                    title="{{ $this->teacher->blocked ? 'Débloquer' : 'Bloquer' }} {{ $this->teacher->getFullName() }}"
                    wire:click="{{ $this->teacher->blocked ? 'unlockTeacher(' . $this->teacher->id . ')' : 'lockTeacher(' . $this->teacher->id . ')' }}"
                    wire:loading.attr="disabled"
                    wire:target="lockTeacher({{ $this->teacher->id }}), unlockTeacher({{ $this->teacher->id }})"
                    class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap hover:text-black disabled:opacity-50 {{ $this->teacher->blocked ? 'bg-lime-600/40 hover:bg-lime-500/80 text-lime-400' : 'bg-amber-800/50 hover:bg-amber-500/80 text-amber-400' }}">
                    <span wire:loading.remove
                        wire:target="lockTeacher({{ $this->teacher->id }}), unlockTeacher({{ $this->teacher->id }})"
                        class="inline-flex items-center gap-1.5">
                        @if ($this->teacher->blocked)
                            <x-lucide-lock-keyhole-open class="w-3.5 h-3.5 shrink-0" />
                            <span>Débloquer prof</span>
                        @else
                            <x-lucide-ban class="w-3.5 h-3.5 shrink-0" />
                            <span>Bloquer prof</span>
                        @endif
                    </span>
                    <span wire:loading
                        wire:target="lockTeacher({{ $this->teacher->id }}), unlockTeacher({{ $this->teacher->id }})"
                        class="inline-flex items-center gap-1.5">
                        <span class="flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                            <span>En cours...</span>
                        </span>
                    </span>
                </button>

                <button
                    title="{{ $this->teacher->user->blocked ? 'Débloquer compte utilisateur de ' : 'Bloquer compte utilisateur de ' }} {{ $this->teacher->user->getFullName() }}"
                    wire:click="{{ $this->teacher->user->blocked ? 'unlockUser(' . $this->teacher->user->id . ')' : 'lockUser(' . $this->teacher->user->id . ')' }}"
                    wire:loading.attr="disabled"
                    wire:target="lockUser({{ $this->teacher->user->id }}), unlockUser({{ $this->teacher->user->id }})"
                    class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap hover:text-black disabled:opacity-50 {{ $this->teacher->user->blocked ? 'bg-indigo-800/50 hover:bg-indigo-500/80 text-indigo-400' : 'bg-red-800/50 hover:bg-red-500/80 text-red-400' }}">
                    <span wire:loading.remove
                        wire:target="lockUser({{ $this->teacher->user->id }}), unlockUser({{ $this->teacher->user->id }})"
                        class="inline-flex items-center gap-1.5">
                        @if ($this->teacher->user->blocked)
                            <x-lucide-unlock class="w-3.5 h-3.5 shrink-0" />
                            <span>Débloquer compte</span>
                        @else
                            <x-lucide-user-lock class="w-3.5 h-3.5 shrink-0" />
                            <span>Bloquer compte</span>
                        @endif
                    </span>
                    <span wire:loading
                        wire:target="lockUser({{ $this->teacher->user->id }}), unlockUser({{ $this->teacher->user->id }})"
                        class="inline-flex items-center gap-1.5">
                        <span class="flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                            <span>En cours...</span>
                        </span>
                    </span>
                </button>

                {{-- Accorder / Retirer accès année --}}
                @if (!$this->teacher->deleted_at)
                    <button
                        title="{{ $this->teacher->hasValidAccessForYear() ? 'Retirer' : 'Accorder' }} l'accès à {{ $this->teacher->getFullName() }}"
                        wire:click="{{ $this->teacher->hasValidAccessForYear() ? 'removeAccessForThisSchoolYear(' . $this->teacher->id . ')' : 'giveAccessForThisSchoolYear(' . $this->teacher->id . ')' }}"
                        wire:loading.attr="disabled"
                        wire:target="giveAccessForThisSchoolYear({{ $this->teacher->id }}), removeAccessForThisSchoolYear({{ $this->teacher->id }})"
                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap hover:text-black disabled:opacity-50 {{ $this->teacher->hasValidAccessForYear() ? 'bg-orange-800/50 hover:bg-orange-500/80 text-orange-400' : 'bg-emerald-800/50 hover:bg-emerald-500/80 text-emerald-400' }}">
                        <span wire:loading.remove
                            wire:target="giveAccessForThisSchoolYear({{ $this->teacher->id }}), removeAccessForThisSchoolYear({{ $this->teacher->id }})"
                            class="inline-flex items-center gap-1.5">
                            @if ($this->teacher->hasValidAccessForYear())
                                <x-lucide-user-key class="w-3.5 h-3.5 shrink-0" />
                                <span>Retirer accès</span>
                            @else
                                <x-lucide-key class="w-3.5 h-3.5 shrink-0" />
                                <span>Accorder accès</span>
                            @endif
                        </span>
                        <span wire:loading
                            wire:target="giveAccessForThisSchoolYear({{ $this->teacher->id }}), removeAccessForThisSchoolYear({{ $this->teacher->id }})"
                            class="inline-flex items-center gap-1.5">
                            <span class="flex items-center gap-x-2">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>
                @endif

                {{-- Corbeille / Restaurer --}}
                <button
                    title="{{ $this->teacher->deleted_at ? 'Restaurer' : 'Mettre en corbeille' }} {{ $this->teacher->getFullName() }}"
                    wire:click="{{ $this->teacher->deleted_at ? 'restoreTeacher(' . $this->teacher->id . ')' : 'deleteTeacher(' . $this->teacher->id . ')' }}"
                    wire:loading.attr="disabled"
                    wire:target="deleteTeacher({{ $this->teacher->id }}), restoreTeacher({{ $this->teacher->id }})"
                    class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap hover:text-black disabled:opacity-50 {{ $this->teacher->deleted_at ? 'bg-violet-800/50 hover:bg-violet-500/80 text-violet-400' : 'bg-rose-800/50 hover:bg-rose-500/80 text-rose-400' }}">
                    <span wire:loading.remove
                        wire:target="deleteTeacher({{ $this->teacher->id }}), restoreTeacher({{ $this->teacher->id }})"
                        class="inline-flex items-center gap-1.5">
                        @if ($this->teacher->deleted_at)
                            <x-lucide-recycle class="w-3.5 h-3.5 shrink-0" />
                            <span>Restaurer</span>
                        @else
                            <x-lucide-trash class="w-3.5 h-3.5 shrink-0" />
                            <span>Corbeille</span>
                        @endif
                    </span>
                    <span wire:loading
                        wire:target="deleteTeacher({{ $this->teacher->id }}), restoreTeacher({{ $this->teacher->id }})"
                        class="inline-flex items-center gap-1.5">
                        <span class="flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                            <span>En cours...</span>
                        </span>
                    </span>
                </button>

                {{-- Supprimer définitivement --}}
                @if ($this->teacher->deleted_at)
                    <button title="Supprimer définitivement {{ $this->teacher->getFullName() }}"
                        wire:click="forceDeleteTeacher({{ $this->teacher->id }})" wire:loading.attr="disabled"
                        wire:target="forceDeleteTeacher({{ $this->teacher->id }})"
                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-red-800/50 hover:bg-red-600/80 text-red-400 transition-all whitespace-nowrap hover:text-black disabled:opacity-50">
                        <span wire:loading.remove wire:target="forceDeleteTeacher({{ $this->teacher->id }})"
                            class="inline-flex items-center gap-1.5">
                            <span class="flex items-center gap-x-2">
                                <x-lucide-trash-2 class="w-3.5 h-3.5 shrink-0" />
                                <span>Suppr. déf.</span>
                            </span>
                        </span>
                        <span wire:loading wire:target="forceDeleteTeacher({{ $this->teacher->id }})"
                            class="inline-flex items-center gap-1.5">
                            <span class="flex items-center gap-x-2">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>
                @endif

            </div>
        </section>

        <section class="mb-6">

            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

                @foreach ([['Classes', __zero($this->teacher?->getTeacherClassesCountForThisSchoolYear()), 'text-indigo-400'], ['Heures/Sem.', '26h', 'text-emerald-400'], ['Notes Publiées', '482', 'text-amber-400'], ['Présence', '98%', 'text-sky-400']] as $kpi)
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

                                <a href="{{ route('tenant.teacher.manage.classes', ['teacher_uuid' => $this->teacher->uuid]) }}"
                                    class="py-3 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm">
                                    Gérer classes
                                </a>

                            </div>

                        </div>

                        <div class="overflow-x-auto p-2">
                            @php
                                $classes = $this->teacher?->getTeacherClassesWithSubjectsForThisSchoolYear();
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
                                                            title="{{ $this->teacher->is_locked ? 'Débloquer ' : 'Bloquer ' }} cet enseigant "
                                                            wire:click="{{ $this->teacher->is_locked ? 'unlockTeacher(' . $this->teacher->id . ')' : 'lockTeacher(' . $this->teacher->id . ')' }}"
                                                            wire:loading.attr="disabled"
                                                            wire:target="lockTeacher, unlockTeacher"
                                                            class="relative py-3 px-4 rounded-xl {{ !$this->teacher->is_locked ? 'bg-amber-600 hover:bg-amber-800' : 'bg-purple-500/20 hover:bg-purple-600/60' }} text-xs font-medium inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 text-black">
                                                            <span wire:loading.remove
                                                                wire:target="lockTeacher, unlockTeacher"
                                                                class="inline-flex items-center justify-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center gap-2">
                                                                    @if ($this->teacher->is_locked)
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
                                                            title="{{ $this->teacher->cannotAccessIntoClasse($kls->classe?->id) ? 'Déverouiller' : 'Vérouiller ' }} l'accès du prof à la classe"
                                                            wire:click="{{ $this->teacher->cannotAccessIntoClasse($kls->classe?->id)
                                                                ? 'unLockAccessToClasse(' . $this->teacher->id . ',' . $kls->classe?->id . ')'
                                                                : 'lockAccessToClasse(' . $this->teacher->id . ',' . $kls->classe?->id . ')' }}"
                                                            wire:loading.attr="disabled"
                                                            wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                            class="relative py-3 px-4 rounded-xl {{ !$this->teacher->cannotAccessIntoClasse($kls->classe?->id) ? 'bg-red-600/50 hover:bg-red-500/80' : 'bg-green-500/20 hover:bg-green-600/60' }}  text-xs font-medium inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 text-black">
                                                            <span wire:loading.remove
                                                                wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                                class="inline-flex items-center justify-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center gap-2">
                                                                    @if ($this->teacher->cannotAccessIntoClasse($kls->classe?->id))
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
                                            <a href="{{ route('tenant.teacher.manage.classes', ['teacher_uuid' => $this->teacher->uuid]) }}"
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
                                $pp_classes = $this->teacher?->getClassesWhereIsPrincipal();
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
                                        responsabilités accordées à {{ $this->teacher->getFullName() }} cette année
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

                            @foreach ([['Email', $this->teacher->user?->email], ['Diplôme', 'Non renseigné'], ['Adresse', $this->teacher->user?->adresse], ['Recrutement', __formatDate($this->teacher->affiliated_at)]] as $info)
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

                            <img class="w-52 h-52" src="{{ $this->teacher->qr_code }}"
                                alt="QR Code de {{ $this->teacher->user->getFullName() }}">

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

