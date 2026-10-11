<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ========== HEADER ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
            <div class="relative p-5 sm:p-6 lg:p-7">
                <div
                    class="absolute top-0 right-0 w-72 h-72 bg-indigo-500/10 rounded-full blur-[80px]
                            -translate-y-1/2 translate-x-1/3 pointer-events-none">
                </div>

                <div class="relative flex flex-col lg:flex-row gap-6 lg:items-center">
                    {{-- Code badge --}}
                    <div class="shrink-0 self-start">
                        <div
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-indigo-500/10 border border-indigo-500/25
                                    flex items-center justify-center shadow-inner">
                            <span class="text-indigo-400 font-mono font-bold text-lg tracking-wider text-center px-2">
                                {{ str()->replace('-', ' ', $subject->code) }}
                            </span>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                                {{ $subject->name }}
                            </h1>
                            @if ($subject->is_active)
                                <span
                                    class="px-2.5 py-0.5 rounded-md text-[11px] font-medium
                                             bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                                    Active
                                </span>
                            @else
                                <span
                                    class="px-2.5 py-0.5 rounded-md text-[11px] font-medium
                                             bg-rose-500/15 text-rose-400 border border-rose-500/25">
                                    Désactivée
                                </span>
                            @endif
                        </div>
                        <p class="mt-1.5 text-sm text-slate-500 max-w-xl">
                            Statistiques, performances et enseignants de la matière
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs
                                         bg-white/[0.04] border border-white/[0.06] text-slate-300">
                                <x-lucide-users class="w-3.5 h-3.5 text-slate-500" />
                                {{ __zero($details['teachers_count'] ?? 0) }} enseignant(s)
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs
                                         bg-white/[0.04] border border-white/[0.06] text-slate-300">
                                <x-lucide-school class="w-3.5 h-3.5 text-slate-500" />
                                {{ __zero($details['classes_count'] ?? 0) }} classe(s)
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs
                                         bg-white/[0.04] border border-white/[0.06] text-slate-300">
                                <x-lucide-sigma class="w-3.5 h-3.5 text-amber-400" />
                                Coef moyen · 4
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========== AE ========== --}}
        @php
            $principalAE = $subject->currentPrincipalAE();
            $adjointAE = $subject->currentAdjointAE();
        @endphp

        @if ($principalAE || $adjointAE)
            <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Animateurs d’établissement
                            <span class="text-amber-400 font-mono ml-1">{{ $this->activeYear?->slug }}</span>
                        </h2>
                    </div>
                    <a wire:navigate href="{{ route('tenant.subject.edit.ae', ['subject_slug' => $subject->slug]) }}"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-[11px] font-medium
                              bg-amber-500/15 text-amber-400 border border-amber-500/25
                              hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all">
                        <x-lucide-pencil class="w-3.5 h-3.5" />
                        Éditer les postes AE
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if ($principalAE)
                        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/[0.04] p-4 space-y-3">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-medium
                                         bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                                <x-lucide-star class="w-3 h-3" />
                                Poste principal
                            </span>
                            <a wire:navigate
                                href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $principalAE?->uuid]) }}"
                                class="flex items-center gap-3 group">
                                <div class="w-12 h-12 rounded-xl overflow-hidden border border-white/[0.08] shrink-0">
                                    <img src="{{ $principalAE?->user->profil_photo_url }}"
                                        class="w-full h-full object-cover" alt="">
                                </div>
                                <div class="min-w-0">
                                    <p
                                        class="font-semibold text-white text-sm truncate group-hover:text-sky-400 transition-colors">
                                        {{ $principalAE?->getFullName() ?? 'Non défini' }}
                                    </p>
                                    <p class="text-xs text-slate-500 truncate">{{ $principalAE?->email }}</p>
                                </div>
                            </a>
                            <div class="rounded-lg border border-white/[0.05] bg-[#070a12]/50 p-3">
                                <p class="text-[11px] uppercase tracking-wider text-slate-600 mb-2 text-center">
                                    Classes tenues
                                </p>
                                <div class="flex flex-wrap gap-1.5 justify-center">
                                    @php $teacher_classes1 = $principalAE->getTeacherClassesForThisSchoolYear([]); @endphp
                                    @if (count($teacher_classes1))
                                        @foreach ($teacher_classes1 as $cl)
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[11px] font-mono uppercase
                                                         bg-white/[0.04] border border-sky-500/20 text-sky-400">
                                                {{ $cl?->code ?? $cl->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-slate-600 italic">Aucune classe assignée</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($adjointAE)
                        <div class="rounded-xl border border-violet-500/20 bg-violet-500/[0.04] p-4 space-y-3">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-medium
                                         bg-violet-500/15 text-violet-400 border border-violet-500/25">
                                <x-lucide-user-check class="w-3 h-3" />
                                Poste adjoint
                            </span>
                            <a wire:navigate
                                href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $adjointAE?->uuid]) }}"
                                class="flex items-center gap-3 group">
                                <div class="w-12 h-12 rounded-xl overflow-hidden border border-white/[0.08] shrink-0">
                                    <img src="{{ $adjointAE?->user->profil_photo_url }}"
                                        class="w-full h-full object-cover" alt="">
                                </div>
                                <div class="min-w-0">
                                    <p
                                        class="font-semibold text-white text-sm truncate group-hover:text-sky-400 transition-colors">
                                        {{ $adjointAE?->getFullName() ?? 'Non défini' }}
                                    </p>
                                    <p class="text-xs text-slate-500 truncate">{{ $adjointAE?->email }}</p>
                                </div>
                            </a>
                            <div class="rounded-lg border border-white/[0.05] bg-[#070a12]/50 p-3">
                                <p class="text-[11px] uppercase tracking-wider text-slate-600 mb-2 text-center">
                                    Classes tenues
                                </p>
                                <div class="flex flex-wrap gap-1.5 justify-center">
                                    @php $teacher_classes2 = $adjointAE->getTeacherClassesForThisSchoolYear([]); @endphp
                                    @if (count($teacher_classes2))
                                        @foreach ($teacher_classes2 as $cl)
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[11px] font-mono uppercase
                                                         bg-white/[0.04] border border-sky-500/20 text-sky-400">
                                                {{ $cl?->code ?? $cl->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-slate-600 italic">Aucune classe assignée</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- ========== ACTIONS ========== --}}
        <section class="flex flex-wrap gap-1.5 justify-end">
            <a wire:navigate href="{{ route('tenant.subject.edit.ae', ['subject_slug' => $subject->slug]) }}"
                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                      bg-amber-500/15 text-amber-400 border border-amber-500/25
                      hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all">
                <x-lucide-user-cog class="w-3.5 h-3.5" />
                Postes AE
            </a>
            <a wire:navigate href="{{ route('tenant.subjects.coefs.manage') }}"
                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                      bg-emerald-500/15 text-emerald-400 border border-emerald-500/25
                      hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all">
                <x-lucide-plus class="w-3.5 h-3.5" />
                Coefficient
            </a>
            <a wire:navigate href="{{ route('tenant.subject.create') }}"
                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                      bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                      hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all">
                <x-lucide-plus class="w-3.5 h-3.5" />
                Nouvelle matière
            </a>
            <a wire:navigate href="{{ route('tenant.teacher.manage.subjects') }}"
                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                      bg-violet-500/15 text-violet-400 border border-violet-500/25
                      hover:bg-violet-500 hover:text-white hover:border-violet-500 transition-all">
                <x-lucide-user-plus class="w-3.5 h-3.5" />
                Enseignant
            </a>
            <a wire:navigate href="{{ route('tenant.subject.edit', ['subject_slug' => $subject->slug]) }}"
                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                      bg-sky-500/15 text-sky-400 border border-sky-500/25
                      hover:bg-sky-500 hover:text-white hover:border-sky-500 transition-all">
                <x-lucide-pencil class="w-3.5 h-3.5" />
                Éditer
            </a>
            <button title="{{ $subject->is_active ? 'Fermer' : 'Activer' }} cette matière"
                wire:click="{{ $subject->is_active ? 'desactivateSubject(' . $subject->id . ')' : 'activateSubject(' . $subject->id . ')' }}"
                wire:loading.attr="disabled" wire:target="activateSubject, desactivateSubject"
                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium transition-all disabled:opacity-50
                           {{ $subject->is_active
                               ? 'bg-orange-500/15 text-orange-400 border border-orange-500/25 hover:bg-orange-500 hover:text-white hover:border-orange-500'
                               : 'bg-lime-500/15 text-lime-400 border border-lime-500/25 hover:bg-lime-500 hover:text-white hover:border-lime-500' }}">
                <span wire:loading.remove wire:target="activateSubject, desactivateSubject"
                    class="inline-flex items-center gap-1.5">
                    @if ($subject->is_active)
                        <x-lucide-lock class="w-3.5 h-3.5" /> Fermer
                    @else
                        <x-lucide-unlock class="w-3.5 h-3.5" /> Activer
                    @endif
                </span>
                <span wire:loading wire:target="activateSubject, desactivateSubject">
                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                </span>
            </button>
            <button title="{{ $subject->deleted_at ? 'Restaurer' : 'Mettre en corbeille' }}"
                wire:click="{{ $subject->deleted_at ? 'restoreSubject(' . $subject->id . ')' : 'deleteSubject(' . $subject->id . ')' }}"
                wire:loading.attr="disabled" wire:target="deleteSubject, restoreSubject"
                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium transition-all disabled:opacity-50
                           {{ $subject->deleted_at
                               ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                               : 'bg-rose-500/15 text-rose-400 border border-rose-500/25 hover:bg-rose-500 hover:text-white hover:border-rose-500' }}">
                <span wire:loading.remove wire:target="deleteSubject, restoreSubject"
                    class="inline-flex items-center gap-1.5">
                    @if ($subject->deleted_at)
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5" /> Restaurer
                    @else
                        <x-lucide-trash-2 class="w-3.5 h-3.5" /> Corbeille
                    @endif
                </span>
                <span wire:loading wire:target="deleteSubject, restoreSubject">
                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                </span>
            </button>
        </section>

        {{-- ========== TABS ========== --}}
        <div x-data="{
            activeTab: localStorage.getItem('subject-tabs-{{ $subject->id }}') || 'best-students',
            setTab(tab) {
                this.activeTab = tab;
                localStorage.setItem('subject-tabs-{{ $subject->id }}', tab);
            }
        }" x-cloak class="space-y-5">

            <div class="flex flex-wrap gap-1.5 p-1.5 rounded-xl border border-white/[0.06] bg-white/[0.02]">
                <button @click="setTab('best-students')"
                    :class="activeTab === 'best-students'
                        ?
                        'bg-indigo-500/20 text-indigo-400 border-indigo-500/25' :
                        'text-slate-400 border-transparent hover:bg-white/[0.04] hover:text-white'"
                    class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg text-xs font-medium
                               border transition-all">
                    <x-lucide-award class="w-3.5 h-3.5" />
                    Meilleurs élèves
                </button>
                <button @click="setTab('teachers')"
                    :class="activeTab === 'teachers'
                        ?
                        'bg-violet-500/20 text-violet-400 border-violet-500/25' :
                        'text-slate-400 border-transparent hover:bg-white/[0.04] hover:text-white'"
                    class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg text-xs font-medium
                               border transition-all">
                    <x-lucide-users class="w-3.5 h-3.5" />
                    Enseignants
                </button>
                <button @click="setTab('coefficients')"
                    :class="activeTab === 'coefficients'
                        ?
                        'bg-sky-500/20 text-sky-400 border-sky-500/25' :
                        'text-slate-400 border-transparent hover:bg-white/[0.04] hover:text-white'"
                    class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg text-xs font-medium
                               border transition-all">
                    <x-lucide-calculator class="w-3.5 h-3.5" />
                    Coefficients
                </button>
            </div>

            <div class="relative">
                <div x-show="activeTab === 'best-students'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    @livewire('tenants.subjects.yearly-subject-bests-students-component')
                </div>
                <div x-show="activeTab === 'teachers'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    @livewire('tenants.subjects.yearly-subject-teachers-list-component', ['subject' => $subject])
                </div>
                <div x-show="activeTab === 'coefficients'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    @livewire('tenants.subjects.yearly-subject-coef-per-promotion-or-classe-component', ['subject' => $subject])
                </div>
            </div>
        </div>

    </div>
</div>
