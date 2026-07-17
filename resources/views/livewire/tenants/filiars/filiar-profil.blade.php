<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8">

        <div class="flex flex-wrap items-center gap-3 p-3 bg-indigo-500/10 rounded-4xl my-1.5">
            <h1 class="text-lg sm:text-xl font-bold text-slate-400 px-3 py-2.5">
                Profil de la filière <span class="font-mono text-amber-400 font-semibold">{{ $filiar->name }}</span>
            </h1>

            <span
                class="px-3 py-1 rounded-full @if ($filiar->is_active) bg-emerald-500/10 text-emerald-400 @else bg-red-500/10 text-red-400 @endif text-xs">
                filière {{ $filiar->is_active ? 'active' : 'non active' }}
            </span>
        </div>

        {{-- ===================================================== --}}
        {{-- HERO --}}
        {{-- ===================================================== --}}
        <section class="mb-6">
            <div class="relative overflow-hidden rounded-[32px] border border-slate-800 bg-slate-900">

                {{-- BG --}}
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-slate-900 to-slate-900"></div>

                <div class="relative p-5 sm:p-6 lg:p-8">
                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-8">

                        {{-- LEFT --}}
                        <div class="flex flex-col lg:flex-row gap-6 min-w-0">

                            {{-- ICON --}}
                            <div class="flex justify-center lg:block">
                                <div
                                    class="w-32 h-32 sm:w-36 sm:h-36
                                            rounded-[30px]
                                            bg-indigo-500/10
                                            border border-indigo-500/20
                                            flex items-center justify-center
                                            text-2xl uppercase text-center">

                                    <span>
                                        {{ str()->replace('-', ' ', $filiar->code) }}
                                    </span>

                                </div>
                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $filiar->name }}</h1>
                                </div>

                                <p class="mt-2 text-slate-400">
                                    Tableau global des statistiques, performances de la filière {{ $filiar->name }}.
                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">
                                        {{ __zero($details['teachers_count']) }}
                                        Enseignants
                                    </div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">
                                        {{ __zero($details['classes_count']) }} Classes
                                    </div>
                                    @if ($filiar->deleted_at)
                                        <div
                                            class="px-4 py-2 flex items-center justify-center rounded-2xl animate-pulse font-mono bg-rose-800/40 text-rose-400 text-xs">
                                            Cette filière est supprimée et se trouve dans la corbeille
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>
        @php
            $principalCA = $filiar->currentPrincipalCA();

            $adjointCA = $filiar->currentAjointCA();
        @endphp

        @if ($principalCA || $adjointCA)
            <section class="my-5 border rounded-2xl p-4 border-slate-700 flex flex-col gap-3">
                <h5 class="border-b border-slate-500 py-2 uppercase text-slate-400 font-mono text-lg">
                    Les Chefs d'Atelier (CA) <span class="text-orange-600">{{ $this->activeYear?->slug }}</span>
                </h5>

                <div class=" grid md:grid-cols-2 grid-cols-1 gap-2 p-2">
                    @if ($principalCA)
                        <div
                            class="mt-5 flex flex-col p-2 gap-4 min-w-0 justify-start border rounded-2xl border-green-700">
                            <h5 class="rounded-2xl p-2 text-center bg-green-600/40 text-green-400">
                                POSTE PRINCIPALE
                            </h5>
                            <div class="flex gap-4 items-center justify-start">
                                <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4">
                                    <img src="{{ $principalCA?->user->profil_photo_url }}"
                                        class="w-full h-full object-cover rounded-full">
                                </div>
                                <a wire:navigate
                                    href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $principalCA?->uuid]) }}"
                                    class="min-w-0 flex-1 flex-col hover:text-sky-500 underline-offset-4 hover:underline">
                                    <h4 class="font-semibold truncate">
                                        {{ $principalCA?->getFullName() ?? 'Non encore défini' }}
                                    </h4>
                                    <h4 class="font-semibold text-sm text-slate-600">
                                        {{ $principalCA?->email }}
                                    </h4>
                                </a>

                            </div>
                            <div class="flex flex-col gap-2 border rounded-3xl border-slate-700 p-2">
                                <h6 class="p-2 border-b border-slate-700 text-center uppercase text-slate-500">Classes
                                    tenues
                                </h6>
                                <div class="flex gap-2 p-2">
                                    @php
                                        $teacher_classes1 = $principalCA->getTeacherClassesForThisSchoolYear([]);

                                    @endphp
                                    @if (count($teacher_classes1))
                                        @foreach ($teacher_classes1 as $cl)
                                            <span
                                                class="px-6 py-3 rounded-3xl bg-slate-800 text-xs uppercase font-mono border border-sky-700">
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
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($adjointCA)
                        <div
                            class="mt-5 flex flex-col p-2 gap-4 min-w-0 justify-start border rounded-2xl border-purple-700">
                            <h5 class="rounded-2xl p-2 text-center bg-purple-600/40 text-purple-400">
                                POSTE ADJOINT
                            </h5>
                            <div class="flex gap-4 items-center justify-start">
                                <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4">
                                    <img src="{{ $adjointCA?->user->profil_photo_url }}"
                                        class="w-full h-full object-cover rounded-full">
                                </div>
                                <a wire:navigate
                                    href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $adjointCA?->uuid]) }}"
                                    class="min-w-0 flex-1 flex-col hover:text-sky-500 underline-offset-4 hover:underline">
                                    <h4 class="font-semibold truncate">
                                        {{ $adjointCA?->getFullName() ?? 'Non encore défini' }}
                                    </h4>
                                    <h4 class="font-semibold text-sm text-slate-600">
                                        {{ $adjointCA?->email }}
                                    </h4>
                                </a>

                            </div>
                            <div class="flex flex-col gap-2 border rounded-3xl border-slate-700 p-2">
                                <h6 class="p-2 border-b border-slate-700 text-center uppercase text-slate-500">Classes
                                    tenues
                                </h6>
                                <div class="flex gap-2 p-2">
                                    @php
                                        $teacher_classes2 = $adjointCA->getTeacherClassesForThisSchoolYear([]);
                                    @endphp
                                    @if (count($teacher_classes2))
                                        @foreach ($teacher_classes2 as $cl)
                                            <span
                                                class="px-6 py-3 rounded-3xl bg-slate-800 text-xs uppercase font-mono border border-sky-700">
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
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </section>
        @endif

        <section class="my-4 mb-5 flex justify-end border-y border-y-slate-800 py-4">
            <div class="flex gap-3">
                <a wire:navigate href="{{ route('tenant.filiar.edit.ca', ['filiar_slug' => $filiar->slug]) }}"
                    class="py-3 px-5 rounded-2xl bg-yellow-500/30 hover:bg-yellow-600 hover:text-black">
                    Editer les postes CA
                </a>
                <a wire:navigate href="{{ route('tenant.classes.create') }}"
                    class="py-3 px-5 rounded-2xl bg-blue-500/30 hover:bg-blue-500 hover:text-black">
                    Créer une classe
                </a>
                <button class="py-3 px-5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 hover:text-black">
                    Export PDF
                </button>
                <a wire:navigate href="{{ route('tenant.filiar.edit', ['filiar_slug' => $filiar->slug]) }}"
                    class="py-3 px-5 rounded-2xl bg-indigo-500/40 hover:bg-indigo-400 hover:text-black">
                    Editer cette filière
                </a>
                <button
                    title="{{ $filiar->deleted_at ? 'Restaurer cette filière de la corbeille ' : 'Mettre cette filière dans la corbeille ' }} "
                    wire:click="{{ $filiar->deleted_at ? 'restoreFiliar(' . $filiar->id . ')' : 'deleteFiliar(' . $filiar->id . ')' }}"
                    wire:loading.attr="disabled" wire:target="deleteFiliar, restoreFiliar"
                    class="relative py-2 px-4 rounded-2xl text-white {{ $filiar->deleted_at ? 'bg-green-600/50 hover:bg-green-800/80' : 'bg-red-500/60 hover:bg-red-600/80' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-2xl transition-all whitespace-nowrap disabled:opacity-50 hover:text-black">
                    <span wire:loading.remove wire:target="deleteFiliar, restoreFiliar"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            @if ($filiar->deleted_at)
                                <x-lucide-refresh-ccw class="w-4 h-4" />
                                <span>Restaurer</span>
                            @else
                                <x-lucide-trash class="w-4 h-4" />
                                <span>Corbeille</span>
                            @endif
                        </span>
                    </span>

                    <span wire:loading wire:target="restoreFiliar, deleteFiliar" class="inline-flex items-center gap-1">
                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </span>
                </button>
            </div>
        </section>

        <section class="mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- BEST --}}
                <div class="rounded-[32px] bg-slate-900 border border-emerald-500/20 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-2xl">
                            🏆</div>
                        <div>
                            <h2 class="text-xl font-semibold">Meilleure Performance</h2>
                            <p class="text-slate-400">Plus forte moyenne enregistrée.</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                            <h3 class="text-lg font-semibold">KOUASSI Sarah</h3>
                            <p class="mt-2 text-slate-400">Classe : Terminale F4-1</p>

                            <div class="mt-4 flex flex-wrap gap-3">
                                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs">Moyenne
                                    : 19.75</span>
                                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">Promotion
                                    : Terminale</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- WORST --}}
                <div class="rounded-[32px] bg-slate-900 border border-rose-500/20 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-rose-500/10 flex items-center justify-center text-2xl">⚠️
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold">Plus Faible Performance</h2>
                            <p class="text-slate-400">Plus faible moyenne enregistrée.</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                            <h3 class="text-lg font-semibold">HOUNKPE David</h3>
                            <p class="mt-2 text-slate-400">Classe : Tle F4-2</p>

                            <div class="mt-4 flex flex-wrap gap-3">
                                <span class="px-3 py-1 rounded-full bg-rose-500/10 text-rose-400 text-xs">Moyenne :
                                    02.15</span>
                                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">Promotion
                                    : Terminale F4</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- BEST BOY / BEST GIRL --}}
        {{-- ===================================================== --}}
        <section class="mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- ===================================================== --}}
                {{-- MEILLEUR GARÇON --}}
                {{-- ===================================================== --}}
                <div class="rounded-[32px] bg-slate-900 border border-sky-500/20 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="p-6 border-b border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-sky-500/10 flex items-center justify-center text-2xl">
                                🏅</div>
                            <div>
                                <h2 class="text-xl font-semibold">Meilleur Garçon</h2>
                                <p class="mt-1 text-sm text-slate-400">Meilleure performance masculine dans la matière.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">

                            {{-- PHOTO --}}
                            <div class="flex justify-center lg:block">
                                <div class="w-28 h-28 rounded-[28px] bg-slate-800 border border-slate-700 shrink-0">
                                </div>
                            </div>

                            {{-- DETAILS --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-2xl font-bold">HOUNKPE David</h3>
                                    <span class="px-3 py-1 rounded-full bg-sky-500/10 text-sky-400 text-xs">Rang #1
                                        Garçon</span>
                                </div>

                                <p class="mt-2 text-slate-400">Terminale F4-1 — Promotion Terminale</p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Moyenne :
                                        18.92</div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Coef : 4
                                    </div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Prof : M.
                                        AHOLOU</div>
                                </div>

                                {{-- ACTIONS --}}
                                <div class="mt-6 flex flex-wrap gap-3">
                                    <button class="h-11 px-5 rounded-2xl bg-sky-500 hover:bg-sky-600">Voir
                                        Profil</button>
                                    <button class="h-11 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600">Historique
                                        Notes</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ===================================================== --}}
                {{-- MEILLEURE FILLE --}}
                {{-- ===================================================== --}}
                <div class="rounded-[32px] bg-slate-900 border border-pink-500/20 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="p-6 border-b border-slate-800">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-2xl bg-pink-500/10 flex items-center justify-center text-2xl">
                                👑</div>
                            <div>
                                <h2 class="text-xl font-semibold">Meilleure Fille</h2>
                                <p class="mt-1 text-sm text-slate-400">Meilleure performance féminine dans la matière.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">

                            {{-- PHOTO --}}
                            <div class="flex justify-center lg:block">
                                <div class="w-28 h-28 rounded-[28px] bg-slate-800 border border-slate-700 shrink-0">
                                </div>
                            </div>

                            {{-- DETAILS --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-2xl font-bold">KOUASSI Sarah</h3>
                                    <span class="px-3 py-1 rounded-full bg-pink-500/10 text-pink-400 text-xs">Rang #1
                                        Fille</span>
                                </div>

                                <p class="mt-2 text-slate-400">Terminale F4-2 — Promotion Terminale</p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Moyenne :
                                        19.41</div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Coef : 4
                                    </div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Prof : Mme
                                        ADJOVI</div>
                                </div>

                                {{-- ACTIONS --}}
                                <div class="mt-6 flex flex-wrap gap-3">
                                    <button class="h-11 px-5 rounded-2xl bg-pink-500 hover:bg-pink-600">Voir
                                        Profil</button>
                                    <button class="h-11 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600">Historique
                                        Notes</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- Remplacer les 2 @livewire(...) + la section "Élèves en Difficulté" par ce bloc --}}

        <section class="mb-6" x-data="{ activeTab: 'teachers' }">

            {{-- NAV TABS --}}
            <div
                class="flex flex-wrap gap-2
                p-2 mb-6
                rounded-2xl
                bg-slate-900
                border border-slate-800">

                <button @click="activeTab = 'teachers'"
                    class="relative flex items-center gap-2
                    px-4 py-2.5
                    rounded-xl
                    text-sm font-medium
                    transition-all duration-300"
                    :class="activeTab === 'teachers'
                        ?
                        'bg-indigo-500/15 text-indigo-400 shadow-[0_0_5px_-3px_rgba(99,102,241,0.4)]' :
                        'text-slate-400 hover:bg-slate-800 hover:text-slate-200'">
                    <span class="relative flex h-2 w-2 shrink-0">
                        <span x-show="activeTab === 'teachers'"
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span :class="activeTab === 'teachers' ? 'bg-indigo-400' : 'bg-slate-600'"
                            class="relative inline-flex rounded-full h-2 w-2 transition-colors duration-300"></span>
                    </span>
                    <x-lucide-users class="w-4 h-4" />
                    <span>Enseignants</span>
                </button>

                <button @click="activeTab = 'students'"
                    class="relative flex items-center gap-2
                    px-4 py-2.5
                    rounded-xl
                    text-sm font-medium
                    transition-all duration-300"
                    :class="activeTab === 'students'
                        ?
                        'bg-purple-500/15 text-purple-400 shadow-[0_0_5px_-3px_rgba(16,185,129,0.4)]' :
                        'text-slate-400 hover:bg-slate-800 hover:text-slate-200'">
                    <span class="relative flex h-2 w-2 shrink-0">
                        <span x-show="activeTab === 'students'"
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                        <span :class="activeTab === 'students' ? 'bg-purple-400' : 'bg-slate-600'"
                            class="relative inline-flex rounded-full h-2 w-2 transition-colors duration-300"></span>
                    </span>
                    <x-lucide-graduation-cap class="w-4 h-4" />
                    <span>Élèves</span>
                </button>

                <button @click="activeTab = 'weak-students'"
                    class="relative flex items-center gap-2
                    px-4 py-2.5
                    rounded-xl
                    text-sm font-medium
                    transition-all duration-300"
                    :class="activeTab === 'weak-students'
                        ?
                        'bg-rose-500/15 text-rose-400 shadow-[0_0_5px_-3px_rgba(244,63,94,0.4)]' :
                        'text-slate-400 hover:bg-slate-800 hover:text-slate-200'">
                    <span class="relative flex h-2 w-2 shrink-0">
                        <span x-show="activeTab === 'weak-students'"
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span :class="activeTab === 'weak-students' ? 'bg-rose-400' : 'bg-slate-600'"
                            class="relative inline-flex rounded-full h-2 w-2 transition-colors duration-300"></span>
                    </span>
                    <x-lucide-triangle-alert class="w-4 h-4" />
                    <span>Élèves en Difficulté</span>
                </button>

            </div>

            {{-- PANELS --}}
            <div class="relative">

                {{-- ENSEIGNANTS --}}
                <div x-show="activeTab === 'teachers'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-3">
                    @livewire('tenants.filiars.filiar-teachers-list-component', ['filiar' => $filiar, 'activeYear' => $this->activeYear])
                </div>

                {{-- ÉLÈVES --}}
                <div x-show="activeTab === 'students'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-3">
                    @livewire('tenants.filiars.filiar-students-list-component', ['filiar' => $filiar, 'activeYear' => $this->activeYear])
                </div>

                {{-- ÉLÈVES EN DIFFICULTÉ (statique) --}}
                <div x-show="activeTab === 'weak-students'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-3">

                    <div class="rounded-3xl bg-slate-900 border border-slate-800 p-5">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-2.5 w-2.5 shrink-0">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-400"></span>
                            </span>
                            <h2 class="text-lg font-semibold text-rose-400">Élèves en Difficulté</h2>
                        </div>

                        <div class="mt-5 space-y-4">
                            @foreach (range(1, 5) as $weak)
                                <div class="rounded-2xl bg-slate-950 p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-medium">KOFFI Junior</h3>
                                            <p class="mt-1 text-sm text-slate-400">Terminale F2-2</p>
                                        </div>
                                        <span class="text-rose-400 font-bold">08.42</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

