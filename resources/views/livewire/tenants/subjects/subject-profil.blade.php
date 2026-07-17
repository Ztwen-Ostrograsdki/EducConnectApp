<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8">

        <section class="mb-6">

            <div
                class="relative overflow-hidden
                        rounded-[32px]
                        border border-slate-800
                        bg-slate-900">

                {{-- BG --}}
                <div
                    class="absolute inset-0
                            bg-gradient-to-br
                            from-indigo-500/10
                            via-slate-900
                            to-slate-900">
                </div>

                <div class="relative p-5 sm:p-6 lg:p-8">

                    <div
                        class="flex flex-col
                                xl:flex-row
                                xl:items-start
                                xl:justify-between
                                gap-8">

                        {{-- LEFT --}}
                        <div
                            class="flex flex-col
                                    lg:flex-row
                                    gap-6
                                    min-w-0">

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
                                        {{ str()->replace('-', ' ', $subject->code) }}
                                    </span>

                                </div>

                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">

                                <div
                                    class="flex flex-wrap
                                            items-center
                                            gap-3">

                                    <h1 class="text-2xl sm:text-3xl font-bold">

                                        {{ $subject->name }}

                                    </h1>

                                    @if ($subject->is_active)
                                        <span
                                            class="px-3 py-1 rounded-full
                                                 bg-emerald-500/10
                                                 text-emerald-400 text-xs">

                                            Matière Active

                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full
                                                 bg-red-500/10
                                                 text-red-400 text-xs">

                                            Matière désactivée

                                        </span>
                                    @endif

                                </div>

                                <p class="mt-2 text-slate-400">

                                    Tableau global des statistiques,
                                    performances et enseignants
                                    de la matière.

                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">

                                    <div
                                        class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        {{ __zero($details['teachers_count'] ?? 0) }} Enseignant(s)

                                    </div>

                                    <div
                                        class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        {{ __zero($details['classes_count'] ?? 0) }} Classe(s)

                                    </div>

                                    <div
                                        class="px-4 py-2 rounded-2xl
                                                bg-slate-800 border border-slate-700">

                                        Coef Moyen : 4

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        @php
            $principalAE = $subject->currentPrincipalAE();

            $adjointAE = $subject->currentAdjointAE();

        @endphp

        @if ($principalAE || $adjointAE)
            <section class="my-5 border rounded-2xl p-4 border-slate-700 flex flex-col gap-3 text-sm">
                <div class="flex justify-between border-b border-slate-500 py-2 items-center">
                    <h5 class=" uppercase text-slate-400 font-mono text-sm">
                        Les Animateurs d'Etablissement (AE) <span
                            class="text-orange-600">{{ $this->activeYear?->slug }}</span>
                    </h5>
                    <a wire:navigate href="{{ route('tenant.subject.edit.ae', ['subject_slug' => $subject->slug]) }}"
                        class="py-3 px-5 rounded-2xl bg-yellow-500/30 hover:bg-yellow-600 hover:text-black">
                        Editer les postes AE
                    </a>
                </div>

                <div class=" grid md:grid-cols-2 grid-cols-1 gap-2 p-2">
                    @if ($principalAE)

                        <div
                            class="mt-5 flex flex-col p-2 gap-4 min-w-0 justify-start border rounded-2xl border-green-700">
                            <h5 class="rounded-2xl p-2 text-center bg-green-600/40 text-green-400">
                                POSTE PRINCIPALE
                            </h5>
                            <a wire:navigate
                                href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $principalAE?->uuid]) }}"
                                class="flex gap-4 items-center justify-start hover:underline underline-offset-4 hover:text-sky-500">
                                <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4 group">
                                    <img src="{{ $principalAE?->user->profil_photo_url }}"
                                        class="w-full h-full object-cover rounded-full">
                                </div>
                                <div class="min-w-0 flex-1 flex-col hover:text-sky-500 group-hover:text-sky-400">
                                    <h4 class="font-semibold truncate">
                                        {{ $principalAE?->getFullName() ?? 'Non encore défini' }}
                                    </h4>
                                    <h4 class="font-semibold text-sm text-slate-600">
                                        {{ $principalAE?->email }}
                                    </h4>
                                </div>

                            </a>
                            <div class="flex flex-col gap-2 border rounded-3xl border-slate-700 p-2">
                                <h6 class="p-2 border-b border-slate-700 text-center uppercase text-slate-500">Classes
                                    tenues
                                </h6>
                                <div class="flex gap-2 p-2">
                                    @php
                                        $teacher_classes1 = $principalAE->getTeacherClassesForThisSchoolYear([]);

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

                    @if ($adjointAE)

                        <div
                            class="mt-5 flex flex-col p-2 gap-4 min-w-0 justify-start border rounded-2xl border-purple-700">
                            <h5 class="rounded-2xl p-2 text-center bg-purple-600/40 text-purple-400">
                                POSTE ADJOINT
                            </h5>
                            <div class="flex gap-4 items-center justify-start">
                                <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4">
                                    <img src="{{ $adjointAE?->user->profil_photo_url }}"
                                        class="w-full h-full object-cover rounded-full">
                                </div>
                                <a wire:navigate
                                    href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $adjointAE?->uuid]) }}"
                                    class="min-w-0 flex-1 flex-col hover:text-sky-500 underline-offset-4 hover:underline">
                                    <h4 class="font-semibold truncate">
                                        {{ $adjointAE?->getFullName() ?? 'Non encore défini' }}
                                    </h4>
                                    <h4 class="font-semibold text-sm text-slate-600">
                                        {{ $adjointAE?->email }}
                                    </h4>
                                </a>

                            </div>
                            <div class="flex flex-col gap-2 border rounded-3xl border-slate-700 p-2">
                                <h6 class="p-2 border-b border-slate-700 text-center uppercase text-slate-500">Classes
                                    tenues
                                </h6>
                                <div class="flex gap-2 p-2">
                                    @php
                                        $teacher_classes2 = $adjointAE->getTeacherClassesForThisSchoolYear([]);

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

        <section class="border-y border-y-slate-800 my-3 py-4">
            <div class="flex gap-2 truncate justify-end">
                <a wire:navigate href="{{ route('tenant.subject.edit.ae', ['subject_slug' => $subject->slug]) }}"
                    class="py-3 px-5 rounded-2xl bg-yellow-500/30 hover:bg-yellow-600 hover:text-black">
                    Editer les postes AE
                </a>
                <a wire:navigate href="{{ route('tenant.subject.create') }}"
                    class="p-2.5 rounded-2xl bg-indigo-500/20 text-indigo-400  hover:bg-indigo-500/60 hover:text-black transition-all text-sm flex items-center text-center">
                    <span class="flex items-center justify-center gap-x-2">
                        <span class="flex items-center justify-center gap-x-2">
                            <x-lucide-plus class="w-4 h-4" />
                            <span> Ajouter nouvelle matière</span>
                        </span>
                    </span>
                </a>
                <a wire:navigate href="{{ route('tenant.teacher.manage.subjects') }}"
                    class="p-2.5 rounded-2xl bg-purple-500/20 text-purple-400  hover:bg-purple-500/60 hover:text-black transition-all text-sm flex items-centertext-center">
                    <span class="flex items-center justify-center gap-x-2">
                        <span class="flex items-center justify-center gap-x-2">
                            <x-lucide-user-plus class="w-4 h-4" />
                            <span> Ajouter enseignant</span>
                        </span>
                    </span>
                </a>
                <a wire:navigate href="{{ route('tenant.subject.edit', ['subject_slug' => $subject->slug]) }}"
                    class="p-2.5 rounded-2xl bg-blue-500/20 text-blue-400  hover:bg-blue-500/60 hover:text-black transition-all text-sm flex items-centertext-center">
                    <span class="flex items-center justify-center gap-x-2">
                        <span class="flex items-center justify-center gap-x-2">
                            <x-lucide-edit class="w-4 h-4" />
                            <span>Editer</span>
                        </span>
                    </span>
                </a>

                <button title="{{ $subject->is_active ? 'Fermer ' : 'Activer ' }} cette matière "
                    wire:click="{{ $subject->is_active ? 'desactivateSubject(' . $subject->id . ')' : 'activateSubject(' . $subject->id . ')' }}"
                    wire:loading.attr="disabled" wire:target="activateSubject, desactivateSubject"
                    class="relative py-2 px-4 rounded-2xl text-white {{ !$subject->is_active ? 'bg-lime-600/60 hover:bg-lime-500 hover:text-black' : 'bg-orange-500/60 hover:bg-orange-600/90' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-2xl transition-all whitespace-nowrap disabled:opacity-50 hover:text-black">
                    <span wire:loading.remove wire:target="activateSubject, desactivateSubject"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            @if ($subject->is_active)
                                <x-lucide-lock class="w-4 h-4" />
                                <span>Fermer</span>
                            @else
                                <x-lucide-unlock class="w-4 h-4" />
                                <span>Activer</span>
                            @endif
                        </span>
                    </span>

                    <span wire:loading wire:target="activateSubject, desactivateSubject"
                        class="inline-flex items-center gap-1">
                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </span>
                </button>

                <button
                    title="{{ $subject->deleted_at ? 'Restaurer cette matière de la corbeille ' : 'Mettre cette matière dans la corbeille ' }} "
                    wire:click="{{ $subject->deleted_at ? 'restoreSubject(' . $subject->id . ')' : 'deleteSubject(' . $subject->id . ')' }}"
                    wire:loading.attr="disabled" wire:target="deleteSubject, restoreSubject"
                    class="relative py-2 px-4 rounded-2xl text-white {{ $subject->deleted_at ? 'bg-green-600/50 hover:bg-green-800/80' : 'bg-red-500/60 hover:bg-red-600/80' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-2xl transition-all whitespace-nowrap disabled:opacity-50 hover:text-black">
                    <span wire:loading.remove wire:target="deleteSubject, restoreSubject"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            @if ($subject->deleted_at)
                                <x-lucide-refresh-ccw class="w-4 h-4" />
                                <span>Restaurer</span>
                            @else
                                <x-lucide-trash class="w-4 h-4" />
                                <span>Corbeille</span>
                            @endif
                        </span>
                    </span>

                    <span wire:loading wire:target="restoreSubject, deleteSubject"
                        class="inline-flex items-center gap-1">
                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </span>
                </button>

            </div>
        </section>

        <div x-data="{
            activeTab: localStorage.getItem('subject-tabs-{{ $subject->id }}') || 'best-students',
            setTab(tab) {
                this.activeTab = tab;
                localStorage.setItem('subject-tabs-{{ $subject->id }}', tab);
            }
        }" x-cloak class="mb-6">

            <div
                class="flex flex-wrap gap-2
                p-2 mb-6
                rounded-2xl
                bg-slate-900
                border border-slate-800">

                <button @click="setTab('best-students')"
                    :class="activeTab === 'best-students'
                        ?
                        'bg-indigo-500/20 text-indigo-400' :
                        'text-slate-400 hover:bg-slate-800 hover:text-slate-200'"
                    class="flex items-center gap-2
                    px-4 py-2.5
                    rounded-xl
                    text-sm font-medium
                    transition-all duration-200">
                    <x-lucide-award class="w-4 h-4" />
                    <span>Meilleurs Élèves</span>
                </button>

                <button @click="setTab('teachers')"
                    :class="activeTab === 'teachers'
                        ?
                        'bg-purple-500/20 text-purple-400' :
                        'text-slate-400 hover:bg-slate-800 hover:text-slate-200'"
                    class="flex items-center gap-2
                    px-4 py-2.5
                    rounded-xl
                    text-sm font-medium
                    transition-all duration-200">
                    <x-lucide-users class="w-4 h-4" />
                    <span>Enseignants</span>
                </button>

                <button @click="setTab('coefficients')"
                    :class="activeTab === 'coefficients'
                        ?
                        'bg-sky-500/20 text-sky-400' :
                        'text-slate-400 hover:bg-slate-800 hover:text-slate-200'"
                    class="flex items-center gap-2
                    px-4 py-2.5
                    rounded-xl
                    text-sm font-medium
                    transition-all duration-200">
                    <x-lucide-calculator class="w-4 h-4" />
                    <span>Coefficients</span>
                </button>

            </div>

            {{-- PANELS --}}
            <div class="relative">

                <div x-show="activeTab === 'best-students'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-3">
                    @livewire('tenants.subjects.yearly-subject-bests-students-component')
                </div>

                <div x-show="activeTab === 'teachers'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-3">
                    @livewire('tenants.subjects.yearly-subject-teachers-list-component', ['subject' => $subject])
                </div>

                <div x-show="activeTab === 'coefficients'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-3">
                    @livewire('tenants.subjects.yearly-subject-coef-per-promotion-or-classe-component')
                </div>

            </div>

        </div>
    </div>

</div>

