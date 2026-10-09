<div>
    @if ($classe && $subject)
        {{-- ========== HEADER CLASSE ========== --}}
        <section
            class="rounded-3xl border border-white/5 bg-gradient-to-br from-slate-900/80 to-slate-950/80 
                        backdrop-blur-xl overflow-hidden shadow-2xl shadow-black/30 mb-6">

            <div class="p-5 sm:p-6 lg:p-7">
                <div class="flex flex-col sm:flex-row gap-5 sm:gap-6">

                    {{-- Avatar / Code classe --}}
                    <div class="shrink-0 self-start hidden md:flex">
                        <div
                            class="w-20 h-20 rounded-2xl bg-indigo-500/10 border border-indigo-500/25 
                                    flex items-center justify-center shadow-inner">
                            <span
                                class="text-indigo-400 font-mono font-bold text-lg tracking-wider text-center break-normal">
                                {{ str_replace('-', ' ', $classe->code) }}
                            </span>
                        </div>
                    </div>

                    {{-- Infos principales --}}
                    <div class="min-w-0 flex-1">
                        {{-- Nom + badges --}}
                        <div class="flex flex-wrap items-center gap-2.5 mb-3">
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white leading-tight">
                                {{ $classe->name }}
                            </h1>

                            @if ($classe->is_active)
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                             bg-emerald-500/10 border border-emerald-500/25 text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                             bg-red-500/10 border border-red-500/25 text-red-400">
                                    Fermée
                                </span>
                            @endif

                            @if (!$classe->is_locked)
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                             bg-sky-500/10 border border-sky-500/25 text-sky-400">
                                    Accessible
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                             bg-red-500/10 border border-red-500/25 text-red-400">
                                    Verrouillée
                                </span>
                            @endif

                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                         bg-slate-800 text-slate-400 border border-white/5">
                                <x-lucide-calendar class="w-3.5 h-3.5" />
                                {{ $classe->schoolYear->slug }}
                            </span>
                        </div>

                        {{-- Spécialité + localisation --}}
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-400 mb-5">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-git-branch class="w-3.5 h-3.5 text-indigo-400" />
                                @if ($classe->filiar_id)
                                    Filière · {{ $classe->specialityModel()?->name }}
                                @elseif($classe->serial_id)
                                    Série · {{ $classe->specialityModel()?->name }}
                                @else
                                    {{ $classe->specialityModel()?->name ?? '—' }}
                                @endif
                            </span>

                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-map-pin class="w-3.5 h-3.5 text-orange-400" />
                                {{ $classe->localization ?? 'Non précisée' }}
                            </span>
                        </div>

                        {{-- Effectifs --}}
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                         bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <x-lucide-users class="w-3.5 h-3.5" />
                                {{ $this->effectifs['apprenants'] }} apprenant(s)
                            </span>

                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                         bg-pink-500/10 text-pink-400 border border-pink-500/20">
                                F · {{ $this->effectifs['apprenants_par_sexe']['F'] }}
                            </span>

                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                         bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                G · {{ $this->effectifs['apprenants_par_sexe']['M'] }}
                            </span>

                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                         bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                <x-lucide-user-x class="w-3.5 h-3.5" />
                                {{ $this->effectifs['abandons'] }} abd
                            </span>

                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                         bg-violet-500/10 text-violet-400 border border-violet-500/20">
                                <x-lucide-graduation-cap class="w-3.5 h-3.5" />
                                {{ $this->effectifs['profs'] }} prof(s)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========== PP + RESPONSABLES ========== --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

            {{-- Professeur Principal --}}
            <div class="rounded-2xl border border-white/5 bg-slate-900/60 p-4 sm:p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">
                        Professeur Principal
                    </p>
                </div>

                @if ($this->principal)
                    <div class="space-y-3">
                        <h4 class="text-base font-semibold text-white">
                            {{ $this->principal->getFullName() }}
                        </h4>

                        <div class="flex items-center gap-2 text-sm text-slate-400">
                            <x-lucide-phone class="w-3.5 h-3.5 text-slate-500" />
                            {{ $this->principal->user->contacts }}
                        </div>

                        @if ($subjects = $this->principalSubjects)
                            <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                <span class="text-xs text-slate-500 mr-1">Matières :</span>
                                @foreach ($subjects as $classeSubject)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-lg text-xs font-medium
                                                 bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                        {{ $classeSubject->subject?->code }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-slate-500 italic">
                        Non encore défini
                    </p>
                @endif
            </div>

            {{-- Responsables --}}
            <div class="rounded-2xl border border-white/5 bg-slate-900/60 p-4 sm:p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">
                        Responsables
                    </p>
                </div>

                @if ($classe->respo_1_id || $classe->respo_2_id)
                    <div class="space-y-3">
                        @foreach ($classe->responsables() as $rk => $respo)
                            <div class="flex items-center gap-3"
                                wire:key="respo-{{ $respo?->id ?? $loop->iteration }}">
                                <span
                                    class="shrink-0 text-xs font-mono uppercase tracking-wide text-slate-500 
                                             bg-slate-800 px-2 py-1 rounded-lg">
                                    {{ $rk }}
                                </span>
                                <span class="text-sm font-medium text-indigo-300">
                                    {{ $respo ? $respo->getFullName() : 'Non encore défini' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500 italic">
                        Non encore défini
                    </p>
                @endif
            </div>
        </section>

        {{-- ========== MATIÈRE + ACTIONS ========== --}}
        <section class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">

            {{-- Badge matière --}}
            <div
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl 
                        bg-amber-500/10 border border-amber-500/25 text-amber-400">
                <x-lucide-book-open class="w-4 h-4" />
                <span class="font-medium text-sm sm:text-base">
                    {{ $subject->name }}
                </span>
            </div>

            {{-- Boutons d'action --}}
            <div class="flex flex-wrap gap-2">
                <a wire:navigate
                    href="{{ route('tenant.teacher.classe.students', ['classe_slug' => $classe->slug, 'subject_slug' => $subject->slug]) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-medium
                          bg-sky-500/15 text-sky-400 border border-sky-500/25
                          hover:bg-sky-500 hover:text-white hover:border-sky-500
                          transition-all duration-200">
                    <x-lucide-users class="w-4 h-4" />
                    Voir la classe
                </a>

                <a wire:navigate
                    href="{{ route('tenant.teacher.classe.marks', ['classe_slug' => $classe->slug, 'subject_slug' => $subject->slug]) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-medium
                          bg-emerald-500/15 text-emerald-400 border border-emerald-500/25
                          hover:bg-emerald-500 hover:text-white hover:border-emerald-500
                          transition-all duration-200">
                    <x-lucide-file-text class="w-4 h-4" />
                    Notes de classe
                </a>

                @if (
                    $this->activeYear &&
                        $this->activeYear->active_period &&
                        $classe->is_active &&
                        !$classe->is_locked &&
                        auth('tenant')->user()->teacher->canAccessIntoClasse($classe->id))
                    <a wire:navigate
                        href="{{ route('tenant.teacher.classe.marks.manager', ['classe_slug' => $classe->slug, 'subject_slug' => $subject->slug]) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-medium
                              bg-blue-500/15 text-blue-400 border border-blue-500/25
                              hover:bg-blue-500 hover:text-white hover:border-blue-500
                              transition-all duration-200">
                        <x-lucide-pen-line class="w-4 h-4" />
                        Insertion de notes
                    </a>
                @endif
            </div>
        </section>
    @endif
</div>

