<div class="min-h-screen bg-[#070a12] text-slate-100">

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

        {{-- ========== HEADER ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-[#0c101c] overflow-hidden">
            <div class="p-5 sm:p-6 lg:p-7">
                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">

                    {{-- Identité --}}
                    <div class="flex gap-4 sm:gap-5 min-w-0 flex-1">
                        <div class="hidden sm:flex shrink-0">
                            <div
                                class="w-16 h-16 rounded-xl border @if (!$classe->is_new_system) bg-indigo-500/10  border-indigo-500/20 text-indigo-400 @else bg-orange-500/10 border-orange-500/20 text-orange-400 @endif
                                        flex items-center justify-center flex-col">
                                <span class=" font-mono font-bold text-sm tracking-wider text-center break-normal">
                                    {{ str_replace('-', ' ', $classe->code) }}
                                </span>

                            </div>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                                    {{ $classe->name }}
                                </h1>

                                @if ($classe->is_new_system)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                 bg-orange-500/15 text-orange-400 border border-orange-500/20">
                                        <span class="w-1 h-1 rounded-full bg-orange-400 animate-pulse"></span>
                                        Nouveau métier
                                    </span>
                                @endif

                                @if ($classe->is_active)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                 bg-emerald-500/15 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1 h-1 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-0.5 rounded-md text-[11px] font-medium
                                                 bg-rose-500/15 text-rose-400 border border-rose-500/20">
                                        Fermée
                                    </span>
                                @endif

                                @if (!$classe->is_locked)
                                    <span
                                        class="px-2 py-0.5 rounded-md text-[11px] font-medium
                                                 bg-sky-500/15 text-sky-400 border border-sky-500/20">
                                        Accessible
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium
                                                 bg-rose-500/15 text-rose-400 border border-rose-500/20">
                                        <x-lucide-lock class="w-3 h-3" />
                                        Verrouillée
                                    </span>
                                @endif

                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium
                                             bg-white/[0.04] text-slate-400 border border-white/[0.06]">
                                    <x-lucide-calendar class="w-3 h-3" />
                                    {{ $classe->schoolYear->slug }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 mb-3">
                                <span class="inline-flex items-center gap-1.5">
                                    <x-lucide-git-branch class="w-3.5 h-3.5 text-indigo-400" />
                                    @if ($classe->filiar_id)
                                        Filière · {{ $classe->specialityModel()?->name }}
                                    @elseif ($classe->serial_id)
                                        Série · {{ $classe->specialityModel()?->name }}
                                    @else
                                        {{ $classe->specialityModel()?->name ?? '—' }}
                                    @endif
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <x-lucide-map-pin class="w-3.5 h-3.5 text-amber-400" />
                                    {{ $classe->localization ?? 'Non précisée' }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <x-lucide-user class="w-3.5 h-3.5 text-violet-400" />
                                    {{ $classe->principal ? 'PP · ' . $classe->principal->getFullName() : 'PP non défini' }}
                                </span>
                            </div>

                            {{-- Effectifs --}}
                            <div class="flex flex-wrap gap-1.5">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                             bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <x-lucide-users class="w-3 h-3" />
                                    {{ $this->effectifs['apprenants'] }}
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium
                                             bg-pink-500/10 text-pink-400 border border-pink-500/20">
                                    F {{ $this->effectifs['apprenants_par_sexe']['F'] }}
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium
                                             bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    G {{ $this->effectifs['apprenants_par_sexe']['M'] }}
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                             bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                    <x-lucide-user-x class="w-3 h-3" />
                                    {{ $this->effectifs['abandons'] }}
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                             bg-violet-500/10 text-violet-400 border border-violet-500/20">
                                    <x-lucide-graduation-cap class="w-3 h-3" />
                                    {{ $this->effectifs['profs'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-5 pt-4 border-t border-white/[0.05] flex flex-wrap gap-1.5">
                    <a wire:navigate
                        href="{{ route('tenant.classe.manage.subjects.teacher', ['classe_slug' => $classe->slug]) }}"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                              bg-emerald-500/15 text-emerald-400 border border-emerald-500/25
                              hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all">
                        <x-lucide-book-user class="w-3.5 h-3.5" />
                        Prof / matière
                    </a>
                    <a wire:navigate
                        href="{{ route('tenant.classe.migrate.students', ['classe_slug' => $classe->slug]) }}"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                              bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                              hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all">
                        <x-lucide-user-plus class="w-3.5 h-3.5" />
                        Ajouter élève
                    </a>
                    <a wire:navigate href="{{ route('tenant.classe.edit', ['classe_slug' => $classe->slug]) }}"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                              bg-white/[0.04] text-slate-400 border border-white/[0.08]
                              hover:bg-white/[0.08] hover:text-white transition-all">
                        <x-lucide-pen class="w-3.5 h-3.5" />
                        Modifier
                    </a>
                    <a wire:navigate href="{{ route('tenant.students.docs', ['classe_slug' => $classe->slug]) }}"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                              bg-sky-500/15 text-sky-400 border border-sky-500/25
                              hover:bg-sky-500 hover:text-white hover:border-sky-500 transition-all">
                        <x-lucide-printer class="w-3.5 h-3.5" />
                        Docs élèves
                    </a>
                    <a wire:navigate href="{{ route('tenant.teachers.docs', ['classe_slug' => $classe->slug]) }}"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                              bg-violet-500/15 text-violet-400 border border-violet-500/25
                              hover:bg-violet-500 hover:text-white hover:border-violet-500 transition-all">
                        <x-lucide-printer class="w-3.5 h-3.5" />
                        Docs profs
                    </a>

                    <div class="flex-1"></div>

                    {{-- Activer / Fermer --}}
                    <button
                        wire:click="{{ $classe->is_active ? 'closeClasse(' . $classe->id . ')' : 'activateClasse(' . $classe->id . ')' }}"
                        wire:loading.attr="disabled" wire:target="activateClasse, closeClasse"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                                   transition-all disabled:opacity-50
                                   {{ $classe->is_active
                                       ? 'bg-rose-500/15 text-rose-400 border border-rose-500/25 hover:bg-rose-500 hover:text-white hover:border-rose-500'
                                       : 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 hover:bg-emerald-500 hover:text-white hover:border-emerald-500' }}">
                        <span wire:loading.remove wire:target="activateClasse, closeClasse"
                            class="inline-flex items-center gap-1.5">
                            @if ($classe->is_active)
                                <x-lucide-power class="w-3.5 h-3.5" /> Fermer
                            @else
                                <x-lucide-power class="w-3.5 h-3.5" /> Activer
                            @endif
                        </span>
                        <span wire:loading wire:target="activateClasse, closeClasse">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>

                    {{-- Lock --}}
                    <button
                        wire:click="{{ $classe->is_locked ? 'unlockClasse(' . $classe->id . ')' : 'lockClasse(' . $classe->id . ')' }}"
                        wire:loading.attr="disabled" wire:target="lockClasse, unlockClasse"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                                   transition-all disabled:opacity-50
                                   {{ $classe->is_locked
                                       ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                                       : 'bg-amber-500/15 text-amber-400 border border-amber-500/25 hover:bg-amber-500 hover:text-white hover:border-amber-500' }}">
                        <span wire:loading.remove wire:target="lockClasse, unlockClasse"
                            class="inline-flex items-center gap-1.5">
                            @if ($classe->is_locked)
                                <x-lucide-lock-open class="w-3.5 h-3.5" /> Déverrouiller
                            @else
                                <x-lucide-lock class="w-3.5 h-3.5" /> Verrouiller
                            @endif
                        </span>
                        <span wire:loading wire:target="lockClasse, unlockClasse">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                </div>
            </div>
        </section>

        {{-- ========== TABS ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-1.5 overflow-x-auto">
            <div class="flex gap-1 w-max min-w-full">
                @foreach ([
        'classe-home-page' => ['Vue générale', 'layout-dashboard'],
        'classe-students-list' => ['Élèves', 'users'],
        'classe-teachers-list' => ['Enseignants', 'graduation-cap'],
        'classe-parents-page' => ['Parents', 'heart'],
        'classe-marks-page' => ['Notes', 'file-text'],
        'classe-presence-page' => ['Présences', 'check-circle'],
        'classe-plan-page' => ['Emploi du temps', 'calendar'],
        'classe-pupil-bulletin-component' => ['Bulletins', 'clipboard-list'],
    ] as $id => [$label, $icon])
                    <button wire:click="setSection('{{ $id }}')" type="button"
                        class="relative shrink-0 h-9 px-3.5 rounded-xl text-xs font-medium transition-all duration-200
                                   {{ $section === $id
                                       ? 'text-white bg-indigo-500 shadow-lg shadow-indigo-500/25'
                                       : 'text-slate-400 hover:text-slate-200 hover:bg-white/[0.04]' }}">
                        <span class="inline-flex items-center gap-1.5">
                            <x-dynamic-component :component="'lucide-' . $icon" class="w-3.5 h-3.5" />
                            {{ $label }}
                        </span>
                    </button>
                @endforeach
            </div>
        </section>

        {{-- ========== CONTENT ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5">
            <div wire:key="section-{{ $section }}" class="animate-[fadeSlide_0.3s_ease-out]">

                @switch($section)
                    @case('classe-home-page')
                        <livewire:tenants.classes.sections.classe-home-page :classroom="$classroom" :classe="$classe" />
                    @break

                    @case('classe-students-list')
                        <livewire:tenants.classes.sections.classe-students-list :classroom="$classroom" :classe="$classe" />
                    @break

                    @case('classe-teachers-list')
                        <livewire:tenants.classes.sections.classe-teachers-list :classroom="$classroom" :classe="$classe" />
                    @break

                    @case('classe-parents-page')
                        @livewire('tenants.classes.sections.classe-parents-page', ['classroom' => $classroom, 'classe' => $classe, 'classe_slug' => $classe->slug])
                    @break

                    @case('classe-marks-page')
                        @livewire('tenants.classes.sections.classe-marks-page', ['classe' => $classe, 'classe_slug' => $classe->slug])
                    @break

                    @case('classe-presence-page')
                        <livewire:tenants.classes.sections.classe-presence-page :classroom="$classroom" />
                    @break

                    @case('classe-plan-page')
                        <livewire:tenants.classes.sections.classe-plan-page :classroom="$classroom" />
                    @break

                    @case('classe-pupil-bulletin-component')
                        <div class="space-y-4">
                            <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                                <select wire:model.live="period"
                                    class="h-10 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm
                                               text-slate-300 font-mono uppercase
                                               focus:border-indigo-500/50 focus:outline-none transition">
                                    <option value="">{{ $this->activeYear->periodLabel() }}</option>
                                    @foreach ($this->periods_types as $pv => $p)
                                        <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                                    @endforeach
                                </select>

                                <select wire:model.live="student_id"
                                    class="h-10 min-w-[220px] rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm
                                               text-slate-300 focus:border-indigo-500/50 focus:outline-none transition">
                                    <option value="">Sélectionner l'apprenant</option>
                                    @foreach ($this->students as $st)
                                        <option value="{{ $st->id }}">{{ $st->getFullName() }}</option>
                                    @endforeach
                                </select>

                                @if ($student_id && $period)
                                    <button wire:click="reloadStudentBulletin"
                                        class="h-10 px-4 rounded-xl text-sm font-medium
                                                   bg-sky-500 hover:bg-sky-400 text-white transition-all">
                                        Charger
                                    </button>
                                    <button wire:click="resetBulletinSelections"
                                        class="h-10 px-4 rounded-xl text-sm font-medium
                                                   bg-white/[0.04] border border-white/[0.08] text-slate-400
                                                   hover:bg-white/[0.08] hover:text-white transition-all">
                                        Réinitialiser
                                    </button>
                                @endif
                            </div>

                            @if ($student)
                                @livewire('tenants.classes.sections.classe-pupil-bulletin-component', [
                                    'student_id' => $student_id,
                                    'student' => $student,
                                    'period' => $period,
                                    'classe' => $classe,
                                ])
                            @else
                                <div class="py-12 text-center">
                                    <div
                                        class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-800/50 mb-3">
                                        <x-lucide-clipboard-list class="w-6 h-6 text-slate-500" />
                                    </div>
                                    <p class="text-sm text-slate-500">
                                        Sélectionnez un apprenant et une période, puis chargez le bulletin
                                    </p>
                                </div>
                            @endif
                        </div>
                    @break

                @endswitch
            </div>
        </section>
    </div>

    <style>
        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>

