<div class="min-h-screen bg-[#070b14] text-slate-100 overflow-x-hidden">
    <div class="mx-auto w-full max-w-[1400px] px-3 sm:px-4 lg:px-6 py-6">

        {{-- ════════════════ HEADER PROFIL ════════════════ --}}
        <section class="mb-6">
            <div
                class="relative rounded-3xl border border-white/[0.06] bg-[#0f1523] overflow-hidden shadow-2xl shadow-black/30">

                {{-- Cover --}}
                <div class="relative h-36 sm:h-44 lg:h-52 w-full overflow-hidden">
                    @if ($this->currentClasse)
                        <div
                            class="absolute top-3 left-3 z-20 hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-black/50 backdrop-blur-md border border-white/10">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                            <span class="text-sky-300 text-xs font-semibold tracking-wider uppercase font-mono">
                                {{ $this->currentClasse?->name }}
                            </span>
                        </div>
                    @endif

                    <img src="{{ $this->student->profil_photo_url }}" alt="Cover"
                        class="w-full h-full object-cover object-top scale-105">

                    <div
                        class="absolute inset-0 bg-gradient-to-br from-indigo-950/80 via-slate-900/50 to-violet-950/70">
                    </div>
                    <div class="absolute -top-16 -left-16 w-64 h-64 rounded-full bg-indigo-600/20 blur-3xl"></div>
                    <div class="absolute top-0 right-1/3 w-48 h-48 rounded-full bg-violet-500/15 blur-3xl"></div>
                    <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-[#0f1523] to-transparent"></div>
                </div>

                {{-- Identity --}}
                <div class="relative px-5 sm:px-8 pb-6 -mt-16">
                    <div class="flex flex-col sm:flex-row gap-5 sm:gap-6 items-center sm:items-end">

                        {{-- Avatar --}}
                        <div class="relative shrink-0 z-10">
                            <div
                                class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-sky-400 opacity-50 blur-sm">
                            </div>
                            <div
                                class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-[#070b14] ring-4 ring-[#0f1523] overflow-hidden shadow-xl">
                                <img src="{{ $this->student->profil_photo_url }}"
                                    alt="{{ $this->student->getFullName() }}" class="w-full h-full object-cover">
                            </div>
                            <span class="absolute bottom-1.5 right-1.5 flex h-4 w-4">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-50"></span>
                                <span
                                    class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 ring-2 ring-[#0f1523]"></span>
                            </span>
                        </div>

                        {{-- Name + meta --}}
                        <div class="flex-1 min-w-0 text-center sm:text-left pb-1">
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                                {{ $this->student->prenames }}
                                <span class="text-slate-400">{{ $this->student->name }}</span>
                            </h1>

                            <div
                                class="mt-2 flex flex-wrap items-center justify-center sm:justify-start gap-x-3 gap-y-1 text-xs text-slate-500">
                                <span>
                                    Matricule
                                    <span class="font-mono text-slate-300 ml-1">{{ $this->student->matricule }}</span>
                                </span>
                                <span class="text-slate-700">·</span>
                                <span>
                                    EducMaster
                                    <span class="text-slate-300 ml-1">{{ $this->student->educMaster }}</span>
                                </span>
                            </div>

                            @if ($this->student->hasResponsibleInThisYear())
                                <p class="mt-1.5 text-sm text-slate-500">
                                    {{ $this->student->hasResponsibleInThisYear() }}
                                    de
                                    <span
                                        class="text-sky-400 font-medium">{{ $this->student->currentClasse()?->name }}</span>
                                </p>
                            @endif
                        </div>

                        {{-- Classe badge --}}
                        <div
                            class="shrink-0 rounded-xl border border-white/5 bg-[#070b14] px-4 py-3 text-center sm:text-right">
                            <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-1">
                                Classe · {{ $this->activeYear->slug }}
                            </p>
                            @if ($this->currentClasse)
                                <a wire:navigate
                                    href="{{ route('tenant.classe.profil', ['classe_slug' => $this->currentClasse->slug]) }}"
                                    class="text-xl font-bold font-mono text-sky-400 hover:text-sky-300 transition-colors">
                                    {{ $this->currentClasse->code ?: $this->currentClasse->name }}
                                </a>
                            @else
                                <p class="text-xs text-slate-600">Aucune classe</p>
                            @endif
                        </div>
                    </div>

                    {{-- Mini stats --}}
                    <div class="mt-5 grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        @foreach ([['Âge', getAge($this->student->birth_date) . ' ans'], ['Sexe', $this->student->gender], ['Nationalité', $this->student->country], ['Naissance', formatBirthDate($this->student->birth_date)]] as $stat)
                            <div class="rounded-xl bg-[#070b14] border border-white/[0.04] px-3.5 py-3">
                                <p class="text-[10px] uppercase tracking-wider text-slate-600">{{ $stat[0] }}</p>
                                <p class="mt-1 text-sm font-semibold text-slate-200 truncate">{{ $stat[1] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ════════════════ ACTIONS (section dédiée) ════════════════ --}}
        <section class="mb-6">
            <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-4 sm:p-5 shadow-xl shadow-black/10">
                <div class="flex items-center gap-2 mb-4">
                    <span
                        class="w-8 h-8 rounded-lg bg-indigo-500/15 border border-indigo-500/20 flex items-center justify-center text-sm">⚡</span>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Actions</h2>
                        <p class="text-[11px] text-slate-500">Gestion du profil et de la scolarité</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2.5">

                    {{-- Photo --}}
                    <a href="{{ route('tenant.director.manage.profil.photo', ['target' => 'apprenant', 'modelUuid' => $this->student->uuid]) }}"
                        title="Changer la photo de profil"
                        class="flex items-center gap-2.5 h-11 px-3.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-medium transition-all active:scale-[0.97]">
                        <x-lucide-image class="w-4 h-4 text-slate-400 shrink-0" />
                        <span class="truncate">Photo</span>
                    </a>

                    {{-- Infos --}}
                    <a href="{{ route('tenant.director.manage.student.data', ['studentUuid' => $this->student->uuid]) }}"
                        title="Mettre à jour les informations"
                        class="flex items-center gap-2.5 h-11 px-3.5 rounded-xl bg-blue-500/15 hover:bg-blue-500/25 border border-blue-500/20 text-blue-300 text-xs font-medium transition-all active:scale-[0.97]">
                        <x-lucide-user-pen class="w-4 h-4 shrink-0" />
                        <span class="truncate">Infos</span>
                    </a>

                    {{-- Notes --}}
                    <a wire:navigate
                        href="{{ route('tenant.student.marks', ['student_uuid' => $this->student_uuid]) }}"
                        class="flex items-center gap-2.5 h-11 px-3.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/20 text-emerald-400 text-xs font-medium transition-all active:scale-[0.97]">
                        <x-lucide-file-bar-chart class="w-4 h-4 shrink-0" />
                        <span class="truncate">Notes</span>
                    </a>

                    {{-- Parents --}}
                    <a wire:navigate
                        href="{{ route('tenant.student.manage.relations', ['student_uuid' => $this->student->uuid]) }}"
                        class="flex items-center gap-2.5 h-11 px-3.5 rounded-xl bg-indigo-500/15 hover:bg-indigo-500/25 border border-indigo-500/20 text-indigo-300 text-xs font-medium transition-all active:scale-[0.97]">
                        <x-lucide-users class="w-4 h-4 shrink-0" />
                        <span class="truncate">Parents</span>
                    </a>

                    {{-- Classe --}}
                    <a href="{{ route('tenant.student.manage.classe', ['student_uuid' => $student_uuid]) }}"
                        class="flex items-center gap-2.5 h-11 px-3.5 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/20 text-amber-400 text-xs font-medium transition-all active:scale-[0.97]">
                        <x-lucide-school class="w-4 h-4 shrink-0" />
                        <span class="truncate">{{ $this->currentClasse ? 'Changer classe' : 'Définir classe' }}</span>
                    </a>

                    @if ($this->currentClasse)
                        <a wire:navigate
                            href="{{ route('tenant.classe.profil', ['classe_slug' => $this->currentClasse->slug]) }}"
                            class="flex items-center gap-2.5 h-11 px-3.5 rounded-xl bg-sky-500/15 hover:bg-sky-500/25 border border-sky-500/20 text-sky-400 text-xs font-medium transition-all active:scale-[0.97]">
                            <x-lucide-door-open class="w-4 h-4 shrink-0" />
                            <span class="truncate">Voir la classe</span>
                        </a>

                        <button type="button" wire:click="removeStudentFromCurrent" wire:loading.attr="disabled"
                            wire:target="removeStudentFromCurrent"
                            class="flex items-center gap-2.5 h-11 px-3.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300 text-xs font-medium transition-all disabled:opacity-50 active:scale-[0.97]">
                            <span wire:loading.remove wire:target="removeStudentFromCurrent"
                                class="inline-flex items-center gap-2.5 truncate">
                                <x-lucide-user-minus class="w-4 h-4 shrink-0" />
                                Retirer classe
                            </span>
                            <span wire:loading wire:target="removeStudentFromCurrent"
                                class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            </span>
                        </button>
                    @endif

                    {{-- Abandon --}}
                    <button type="button" wire:click="markStudentAsLeaved({{ $this->student->id }})"
                        wire:loading.attr="disabled" wire:target="markStudentAsLeaved({{ $this->student->id }})"
                        class="flex items-center gap-2.5 h-11 px-3.5 rounded-xl bg-orange-500/10 hover:bg-orange-500/20 border border-orange-500/20 text-orange-300 text-xs font-medium transition-all disabled:opacity-50 active:scale-[0.97]">
                        <span wire:loading.remove wire:target="markStudentAsLeaved({{ $this->student->id }})"
                            class="inline-flex items-center gap-2.5 truncate">
                            <x-lucide-user-x class="w-4 h-4 shrink-0" />
                            Abandon
                        </span>
                        <span wire:loading wire:target="markStudentAsLeaved({{ $this->student->id }})"
                            class="inline-flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                        </span>
                    </button>
                </div>
            </div>
        </section>

        {{-- ════════════════ STATS + PARENTS ════════════════ --}}
        <section class="mb-6">
            <div class="grid md:grid-cols-2 gap-4">

                {{-- Statistiques --}}
                <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-xl shadow-black/10">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-sm font-semibold text-white">Statistiques</h2>
                        <span class="text-[10px] font-mono text-slate-600 uppercase tracking-wider">Moyennes</span>
                    </div>

                    <div class="space-y-5">
                        @foreach ([['Scientifiques', '16.2', 82, 'from-indigo-600 to-indigo-400', 'text-indigo-400'], ['Littéraires', '13.4', 68, 'from-emerald-600 to-emerald-400', 'text-emerald-400'], ['Informatiques', '17.5', 92, 'from-amber-500 to-amber-300', 'text-amber-400']] as $s)
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs text-slate-400">{{ $s[0] }}</span>
                                    <span class="text-sm font-bold {{ $s[4] }}">{{ $s[1] }}</span>
                                </div>
                                <div class="h-1.5 rounded-full bg-[#070b14] overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r {{ $s[3] }}"
                                        style="width: {{ $s[2] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Parents --}}
                <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-xl shadow-black/10">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-white">Parents / Tuteurs</h2>
                        <a wire:navigate
                            href="{{ route('tenant.student.manage.relations', ['student_uuid' => $this->student->uuid]) }}"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-500/15 hover:bg-indigo-500/25 border border-indigo-500/20 text-indigo-300 text-[11px] font-medium transition-all">
                            <x-lucide-edit class="w-3 h-3" />
                            Éditer
                        </a>
                    </div>

                    <div class="space-y-2.5">
                        @if ($this->parents)
                            <p class="text-right text-[10px] text-slate-600 mb-1">
                                {{ __zero(count($this->parents)) }} parent{{ count($this->parents) > 1 ? 's' : '' }}
                            </p>

                            @foreach ($this->parents as $parent_rel)
                                <a wire:navigate
                                    href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent_rel->parent->uuid]) }}"
                                    class="flex gap-3 rounded-xl bg-[#070b14] border border-white/[0.04] p-3 hover:border-sky-500/25 transition-all group active:scale-[0.99]">
                                    <div
                                        class="w-11 h-11 rounded-xl bg-slate-800 shrink-0 overflow-hidden ring-2 ring-white/5 group-hover:ring-sky-500/30 transition-all">
                                        <img src="{{ $parent_rel->parent->user->profil_photo_url }}"
                                            class="w-full h-full object-cover" alt="">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3
                                            class="text-sm font-medium text-white truncate group-hover:text-sky-300 transition-colors">
                                            {{ $parent_rel->parent->getFullName() }}
                                        </h3>
                                        <p class="text-[11px] text-slate-500 truncate">
                                            {{ $parent_rel->parent->user->contacts }}</p>
                                        <p class="text-[11px] text-slate-600 truncate">
                                            {{ $parent_rel->parent->user->email }}</p>
                                        <p class="mt-1 text-[10px] font-medium text-lime-400/80 text-right">
                                            {{ $parent_rel->parent_relation }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="py-10 text-center">
                                <p class="text-slate-600 text-xs font-mono">Aucun parent lié</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- ════════════════ BULLETIN ════════════════ --}}
        <section class="pb-12">
            @livewire('tenants.components.bulletin-component', [
                'student' => $this->student,
                'classe' => $this->currentClasse,
            ])
        </section>
    </div>
</div>
