<div class="min-h-screen bg-slate-950 text-slate-100 w-full max-w-full px-3 overflow-x-hidden">
    <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl rounded-2xl mt-2.5 ">
        <div class="w-full max-w-full px-4 sm:px-6 lg:px-8 py-5 flex flex-col gap-5">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 min-w-0 flex-1">
                    <div class="shrink-0 self-start font-mono">
                        <div
                            class="w-32 h-32 sm:w-20 sm:h-20 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                            <span class="text-indigo-400">
                                {{ $classe->code }}
                            </span>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold leading-tight break-words">
                                {{ $classe->name }}
                            </h1>
                            @if ($classe->is_active)
                                <span
                                    class="shrink-0 px-3 py-1 rounded-full text-xs bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                    Active
                                </span>
                            @else
                                <span
                                    class="shrink-0 px-3 py-1 rounded-full text-xs bg-red-500/10 border border-red-500/20 text-red-400">
                                    Fermée
                                </span>
                            @endif

                            @if (!$classe->is_locked)
                                <span
                                    class="shrink-0 px-3 py-1 rounded-full text-xs bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                    Accessible
                                </span>
                            @else
                                <span
                                    class="shrink-0 px-3 py-1 rounded-full text-xs bg-red-500/10 border border-red-500/20 text-red-400">
                                    Verrouillée
                                </span>
                            @endif

                            <span class="float-right">
                                📅 {{ $classe->schoolYear->slug }}
                            </span>
                        </div>
                        <p class="mt-3 text-sm sm:text-base text-slate-400 break-words">
                            <span>
                                🔗
                                @if ($classe->filiar_id)
                                    Filière | Spécialité :
                                @elseif($classe->serial_id)
                                    Série :
                                @else
                                @endif
                                {{ $classe->specialityModel()?->name }}
                            </span>

                            <span>
                                📍 {{ $classe->localization ?? 'Non précisée' }}
                            </span>
                        </p>

                        {{-- META --}}
                        <div class="mt-4 flex flex-col sm:flex-wrap gap-2 sm:gap-5 text-sm text-slate-400">
                            <div class="break-words">👨‍🏫
                                {{ $classe->principal ? 'PP : ' . $classe->principal?->getFullName() : 'Non précisée' }}
                            </div>
                            <span class="flex text-xs items-center text-green-300 gap-x-2">
                                <span class="rounded-lg p-1.5 bg-green-800/50 border border-green-700 font-mono">
                                    Apprenant(s) : {{ $this->effectifs['apprenants'] }}</span>
                                <span class="rounded-lg p-1.5 bg-indigo-800/50 border border-indigo-700">
                                    F: {{ $this->effectifs['apprenants_par_sexe']['F'] }}
                                </span>
                                <span class="rounded-lg p-1.5 bg-indigo-800/50 border border-indigo-700">
                                    G: {{ $this->effectifs['apprenants_par_sexe']['M'] }}
                                </span>
                                <span
                                    class="rounded-lg p-1.5 bg-orange-800/50 border border-orange-700 text-orange-400">
                                    Abd: {{ $this->effectifs['abandons'] }}</span>
                                <span class="rounded-lg p-1.5 bg-sky-800/50 border border-sky-700 text-sky-400">
                                    Prof(s) : {{ $this->effectifs['profs'] }} </span>
                            </span>
                        </div>
                    </div>

                </div>

            </div>
            <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto justify-end text-xs ls-1 font-thin font-mono">
                <a wire:navigate
                    href="{{ route('tenant.classe.manage.subjects.teacher', ['classe_slug' => $classe->slug]) }}"
                    class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-green-500/30 hover:bg-green-800/30 transition-all duration-300">
                    Gestion prof par matière
                </a>
                <a wire:navigate href="{{ route('tenant.classe.migrate.students', ['classe_slug' => $classe->slug]) }}"
                    class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all duration-300">
                    Ajouter Élève
                </a>
                <a wire:navigate href="{{ route('tenant.classe.edit', ['classe_slug' => $classe->slug]) }}"
                    class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300">
                    Modifier Classe
                </a>
                <a wire:navigate href="{{ route('tenant.students.docs', ['classe_slug' => $classe->slug]) }}"
                    class="py-2 px-2 bg-indigo-700/40 hover:bg-indigo-800 text-white hover:text-black flex items-center gap-2 active:scale-95 rounded-2xl">
                    <x-lucide-printer class="w-4 h-4" />
                    <span>Docs apprenants disponibles à imprimer</span>
                </a>
                <a wire:navigate href="{{ route('tenant.teachers.docs', ['classe_slug' => $classe->slug]) }}"
                    class="py-2 px-2 bg-purple-700/40 hover:bg-purple-800 text-white hover:text-black flex items-center gap-2 active:scale-95 rounded-2xl">
                    <x-lucide-printer class="w-4 h-4" />
                    <span>Docs Enseignants disponibles à imprimer</span>
                </a>
            </div>
            <div
                class="flex flex-col sm:flex-row gap-3 w-full sm:justify-end lg:w-auto sm:border-y border-y-slate-800 py-2 font-mono">
                <button
                    wire:click="{{ $classe->is_active ? 'closeClasse(' . $classe->id . ')' : 'activateClasse(' . $classe->id . ')' }}"
                    wire:loading.attr="disabled" wire:target="activateClasse, closeClasse"
                    class="relative inline-flex hover:text-black items-center gap-2 px-6 py-3 rounded-xl text-sm transition disabled:opacity-40 disabled:cursor-not-allowed justify-center  {{ $classe->is_active ? 'bg-red-700/60 hover:bg-red-700' : 'bg-green-500/40 hover:bg-green-800/30' }}">

                    <span wire:loading.remove wire:target="activateClasse, closeClasse"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            @if (!$classe->is_active)
                                <x-lucide-check class="w-4 h-4" />
                                <span>Activer</span>
                            @else
                                <x-lucide-x class="w-4 h-4" />
                                <span>Fermer</span>
                            @endif
                        </span>
                    </span>

                    <span wire:loading wire:target="closeClasse, activateClasse"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            <span>En cours...</span>
                            <x-lucide-refresh-cw class="w-4 h-4 animate-spin" />
                        </span>
                    </span>
                </button>
                <button
                    wire:click="{{ $classe->is_locked ? 'unlockClasse(' . $classe->id . ')' : 'lockClasse(' . $classe->id . ')' }}"
                    wire:loading.attr="disabled" wire:target="lockClasse, unlockClasse"
                    class="relative inline-flex hover:text-black items-center gap-2 px-6 py-3 rounded-xl text-sm transition disabled:opacity-40 disabled:cursor-not-allowed justify-center  {{ $classe->is_locked ? 'bg-emerald-600 hover:bg-emerald-500' : 'bg-orange-500/20 hover:bg-orange-600' }}">

                    <span wire:loading.remove wire:target="lockClasse, unlockClasse"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            @if ($classe->is_locked)
                                <x-lucide-lock-open class="w-4 h-4" />
                                <span>Déverrouiller</span>
                            @else
                                <x-lucide-lock class="w-4 h-4" />
                                <span>Verrouiller</span>
                            @endif
                        </span>
                    </span>

                    <span wire:loading wire:target="lockClasse, unlockClasse"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            <span>En cours...</span>
                            <x-lucide-refresh-cw class="w-4 h-4 animate-spin" />
                        </span>
                    </span>
                </button>
            </div>

        </div>
    </section>
    {{-- ===================== TABS ===================== --}}
    <section class="px-1 pt-4" x-data="{
        tabs: [
            { id: 'classe-home-page', label: 'Vue générale', icon: '📊' },
            { id: 'classe-students-list', label: 'Élèves', icon: '🎓' },
            { id: 'classe-teachers-list', label: 'Enseignants', icon: '👨‍🏫' },
            { id: 'classe-parents-page', label: 'Parents', icon: '👪' },
            { id: 'classe-marks-page', label: 'Notes', icon: '📝' },
            { id: 'classe-presence-page', label: 'Présences', icon: '✅' },
            { id: 'classe-plan-page', label: 'Emploi du temps', icon: '📅' },
            { id: 'classe-pupil-bulletin-component', label: 'Bulletins', icon: '📋' },
        ]
    }">
        <div class="rounded-2xl bg-slate-950 border shadow-sm shadow-sky-900 border-white/5 p-1.5 overflow-x-auto">
            <div class="flex gap-3 w-max min-w-full">
                @foreach ([
        'classe-home-page' => ['Vue générale', '📊'],
        'classe-students-list' => ['Élèves', '🎓'],
        'classe-teachers-list' => ['Enseignants', '👨‍🏫'],
        'classe-parents-page' => ['Parents', '👪'],
        'classe-marks-page' => ['Notes', '📝'],
        'classe-presence-page' => ['Présences', '✅'],
        'classe-plan-page' => ['Emploi du temps', '📅'],
        'classe-pupil-bulletin-component' => ['Bulletins', '📋'],
    ] as $id => [$label, $icon])
                    <button wire:click="setSection('{{ $id }}')" type="button"
                        class="relative shrink-0 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 cursor-pointer
                               {{ $section === $id ? 'text-white' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        {{-- Active pill background --}}
                        @if ($section === $id)
                            <span
                                class="absolute inset-0 rounded-xl bg-violet-600 shadow-lg shadow-violet-900/40
                                     animate-[tabIn_0.25s_ease-out]"></span>
                        @endif
                        <span class="relative z-10 inline-flex items-center gap-2">
                            <span class="text-sm opacity-80">{{ $icon }}</span>
                            <span>{{ $label }}</span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== CONTENT ===================== --}}
    <section class="p-2 my-4 border border-slate-900 rounded-2xl">
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
                    @livewire('tenants.classes.sections.classe-marks-page', ['classroom' => $classroom, 'classe' => $classe, 'classe_slug' => $classe->slug])
                @break

                @case('classe-presence-page')
                    <livewire:tenants.classes.sections.classe-presence-page :classroom="$classroom" />
                @break

                @case('classe-plan-page')
                    <livewire:tenants.classes.sections.classe-plan-page :classroom="$classroom" />
                @break

                @case('classe-pupil-bulletin-component')
                    <section class="mb-6">
                        <div class="rounded-2xl bg-[#121826] border border-white/5 p-4 sm:p-5">
                            <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                                <select wire:model.live="period"
                                    class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-2 font-mono uppercase transition-colors duration-200">
                                    <option value="">Sélectionner le {{ $this->activeYear->periodLabel() }}</option>
                                    @foreach ($this->periods_types as $pv => $p)
                                        <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                                    @endforeach
                                </select>

                                <select wire:model.live="student_id"
                                    class="h-11 min-w-[220px] rounded-xl bg-[#0b0f19] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                                    <option value="">Sélectionner l'apprenant</option>
                                    @foreach ($this->students as $st)
                                        <option value="{{ $st->id }}">{{ $st->getFullName() }}
                                        </option>
                                    @endforeach
                                </select>

                                @if ($student_id && $period)
                                    <button wire:click="reloadStudentBulletin"
                                        class="h-11 px-5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white text-sm font-medium transition-all active:scale-[0.97]">
                                        Charger
                                    </button>
                                    <button wire:click="resetBulletinSelections"
                                        class="h-11 px-5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 text-sm font-medium transition-all active:scale-[0.97]">
                                        Réinitialiser
                                    </button>
                                @endif
                            </div>
                        </div>
                    </section>
                    @if ($student)
                        @livewire('tenants.classes.sections.classe-pupil-bulletin-component', ['student_id' => $student_id, 'student' => $student, 'period' => $period, 'classe' => $classe])
                    @else
                        <div
                            class="flex w-full rounded-4xl animate-pulse text-slate-500 text-center font-semibold text-lg items-center justify-center p-5">
                            <h2 class="p-3">Veuillez sélectionner l'apprenant et le semestre|trimestre puis charger pour
                                afficher le
                                bulletin</h2>
                        </div>
                    @endif
                @break

            @endswitch

        </div>
    </section>

    {{-- Keyframes (à mettre dans ton CSS global ou via @layer si Tailwind) --}}
    <style>
        @keyframes tabIn {
            from {
                opacity: 0;
                transform: scale(0.92);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

</div>

