<div class="min-h-screen bg-slate-950 text-slate-100 w-full max-w-full px-3 overflow-x-hidden">
    <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl rounded-2xl mt-2.5">
        <div class="w-full max-w-full px-4 sm:px-6 lg:px-8 py-5">
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
                        </div>
                        <p class="mt-3 text-sm sm:text-base text-slate-400 break-words">
                            @if ($classe->filiar_id)
                                Filière|Spécialité :
                            @elseif($classe->serial_id)
                                Série :
                            @else
                            @endif
                            {{ $classe->specialityModel()?->name }}
                        </p>

                        {{-- META --}}
                        <div class="mt-4 flex flex-col sm:flex-row sm:flex-wrap gap-2 sm:gap-5 text-sm text-slate-400">
                            <div class="break-words">👨‍🏫
                                {{ $classe->principal ? 'PP : ' . $classe->principal?->getFullName() : 'Non précisée' }}
                            </div>
                            <div class="break-words">📍 {{ $classe->localization ?? 'Non précisée' }}</div>
                            <div class="break-words">📅 {{ $classe->schoolYear->slug }}</div>
                            <span class="flex font-mono text-xs items-center text-indigo-600 gap-x-2">
                                <span class="rounded-2xl p-1.5 bg-indigo-800/50 border border-indigo-700">
                                    {{ __zero($classe->getStudentsCountOnGender('Féminin')) }}
                                    filles</span>
                                <span class="rounded-2xl p-1.5 bg-indigo-800/50 border border-indigo-700">
                                    {{ __zero($classe->getStudentsCountOnGender('Masculin')) }}
                                    Garçons</span>
                                <span
                                    class="rounded-2xl p-1.5 bg-orange-800/50 border border-orange-700 text-orange-400">
                                    {{ __zero($classe->getClasseStudentsLeavesCount()) }} Abandon</span>
                            </span>
                        </div>
                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                    <a wire:navigate
                        href="{{ route('tenant.classe.manage.subjects.teacher', ['classe_slug' => $classe->slug]) }}"
                        class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-green-500/30 hover:bg-green-800/30 transition-all duration-300 text-sm sm:text-base">
                        Gestion prof par matière
                    </a>
                    <a wire:navigate
                        href="{{ route('tenant.classe.migrate.students', ['classe_slug' => $classe->slug]) }}"
                        class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all duration-300 text-sm sm:text-base">
                        Ajouter Élève
                    </a>
                    <a wire:navigate href="{{ route('tenant.classe.edit', ['classe_slug' => $classe->slug]) }}"
                        class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300 text-sm sm:text-base">
                        Modifier Classe
                    </a>
                </div>

            </div>
        </div>
    </section>
    <section class="px-1 pt-6">
        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">
            <div class="overflow-x-auto">
                <div class="flex gap-2 p-3 w-max min-w-full">

                    <button wire:click="setSection('classe-home-page')" @class([
                        'shrink-0 px-5 py-3 rounded-2xl cursor-pointer transition-all text-sm',
                        'bg-indigo-500 text-white' => $section === 'classe-home-page',
                        'hover:bg-slate-800' => $section !== 'classe-home-page',
                    ])>
                        Vue Générale
                    </button>

                    <button wire:click="setSection('classe-students-list')" @class([
                        'shrink-0 px-5 py-3 rounded-2xl cursor-pointer transition-all text-sm',
                        'bg-indigo-500 text-white' => $section === 'classe-students-list',
                        'hover:bg-slate-800' => $section !== 'classe-students-list',
                    ])>
                        Élèves
                    </button>

                    <button wire:click="setSection('classe-teachers-list')" @class([
                        'shrink-0 px-5 py-3 rounded-2xl cursor-pointer transition-all text-sm',
                        'bg-indigo-500 text-white' => $section === 'classe-teachers-list',
                        'hover:bg-slate-800' => $section !== 'classe-teachers-list',
                    ])>
                        Enseignants
                    </button>

                    <button wire:click="setSection('classe-parents-page')" @class([
                        'shrink-0 px-5 py-3 rounded-2xl cursor-pointer transition-all text-sm',
                        'bg-indigo-500 text-white' => $section === 'classe-parents-page',
                        'hover:bg-slate-800' => $section !== 'classe-parents-page',
                    ])>
                        Parents
                    </button>

                    <button wire:click="setSection('classe-marks-page')" @class([
                        'shrink-0 px-5 py-3 rounded-2xl cursor-pointer transition-all text-sm',
                        'bg-indigo-500 text-white' => $section === 'classe-marks-page',
                        'hover:bg-slate-800' => $section !== 'classe-marks-page',
                    ])>
                        Notes
                    </button>

                    <button wire:click="setSection('classe-presence-page')" @class([
                        'shrink-0 px-5 py-3 rounded-2xl cursor-pointer transition-all text-sm',
                        'bg-indigo-500 text-white' => $section === 'classe-presence-page',
                        'hover:bg-slate-800' => $section !== 'classe-presence-page',
                    ])>
                        Présences
                    </button>

                    <button wire:click="setSection('classe-plan-page')" @class([
                        'shrink-0 px-5 py-3 rounded-2xl cursor-pointer transition-all text-sm',
                        'bg-indigo-500 text-white' => $section === 'classe-plan-page',
                        'hover:bg-slate-800' => $section !== 'classe-plan-page',
                    ])>
                        Emploi du temps
                    </button>

                    <button wire:click="setSection('classe-pupil-bulletin-component')" @class([
                        'shrink-0 px-5 py-3 rounded-2xl cursor-pointer transition-all text-sm',
                        'bg-indigo-500 text-white' =>
                            $section === 'classe-pupil-bulletin-component',
                        'hover:bg-slate-800' => $section !== 'classe-pupil-bulletin-component',
                    ])>
                        Bulletins
                    </button>

                </div>
            </div>
        </div>
    </section>

    <section class="p-2 my-2.5 shadow-sm shadow-cyan-600 rounded-2xl">
        <div wire:key="section-{{ $section }}">

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
                    <livewire:tenants.classes.sections.classe-parents-page :classroom="$classroom" />
                @break

                @case('classe-marks-page')
                    <livewire:tenants.classes.sections.classe-marks-page :classroom="$classroom" />
                @break

                @case('classe-presence-page')
                    <livewire:tenants.classes.sections.classe-presence-page :classroom="$classroom" />
                @break

                @case('classe-plan-page')
                    <livewire:tenants.classes.sections.classe-plan-page :classroom="$classroom" />
                @break

                @case('classe-pupil-bulletin-component')
                    <section class="mb-6">
                        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">
                            <div class="flex flex-col xl:flex-row gap-4">

                                {{-- FILTERS --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                                    {{-- SEMESTER --}}
                                    <select wire:model.live="period_type_selected"
                                        class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                                        <option value="">Sélectionner le semestre|trimestre</option>
                                        @foreach (range(1, 2) as $i)
                                            <option value="Semestre {{ $i }}">Semestre {{ $i }}</option>
                                        @endforeach
                                        @foreach (range(1, 3) as $i)
                                            <option value="Trimestre {{ $i }}">Trimestre {{ $i }}
                                            </option>
                                        @endforeach
                                    </select>

                                    {{-- PUPILS --}}
                                    <select wire:model.live="student_uuid_selected"
                                        class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                                        <option value="">Sélectionner l'apprenant</option>
                                        @foreach (range(1, 10) as $i)
                                            <option value="HOUNGNITO Marc {{ $i }}">HOUNGNITO Marc
                                                {{ $i }}</option>
                                        @endforeach
                                    </select>

                                    {{-- ACTIONS --}}
                                    @if ($student_uuid_selected && $period_type_selected)
                                        <button wire:click='reloadStudentBulletin'
                                            class="h-12 px-5 rounded-2xl bg-sky-800 border border-sky-700 hover:bg-sky-700 transition-all text-sm cursor-pointer">
                                            Charger
                                        </button>
                                        <button wire:click='resetBulletinSelections'
                                            class="h-12 px-5 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all text-sm cursor-pointer">
                                            Réinitialiser
                                        </button>
                                    @endif

                                </div>

                            </div>
                        </div>
                    </section>
                    @livewire('tenants.classes.sections.classe-pupil-bulletin-component')
                @break

            @endswitch

        </div>
    </section>

</div>

