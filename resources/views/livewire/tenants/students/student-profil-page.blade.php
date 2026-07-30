<div class="w-full max-w-full overflow-x-hidden p-3 sm:p-4 lg:p-6">

    {{-- ===================== HEADER PROFIL ===================== --}}
    <section class="mb-8">
        <div
            class="relative rounded-[2rem] border border-white/5 bg-slate-900/80 backdrop-blur-xl overflow-hidden shadow-2xl shadow-indigo-950/40">

            {{-- COVER --}}
            <div class="relative h-40 sm:h-52 lg:h-60 w-full overflow-hidden">
                @if ($this->currentClasse)
                    <div
                        class="absolute top-4 left-4 z-30 hidden sm:flex items-center gap-2 px-4 py-2 rounded-2xl bg-black/40 backdrop-blur-md border border-white/10">
                        <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
                        <span class="text-sky-300/90 text-sm font-semibold tracking-widest uppercase font-mono">
                            {{ $this->currentClasse?->name }}
                        </span>
                    </div>
                @endif

                <img src="{{ $this->student->profil_photo_url }}" alt="Photo de couverture"
                    class="w-full h-full object-cover object-top scale-105" />

                {{-- Mesh gradient overlay --}}
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/80 via-slate-900/40 to-violet-950/70">
                </div>
                <div
                    class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-sky-500/20 via-transparent to-transparent">
                </div>
                <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-indigo-600/25 blur-3xl"></div>
                <div class="absolute top-10 right-1/4 w-64 h-64 rounded-full bg-violet-500/20 blur-3xl"></div>
                <div class="absolute -bottom-16 right-0 w-72 h-72 rounded-full bg-sky-500/15 blur-3xl"></div>

                {{-- Soft bottom fade --}}
                <div
                    class="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent">
                </div>
            </div>

            {{-- BODY --}}
            <div class="relative px-5 sm:px-8 pb-8 -mt-20">
                <div class="flex flex-col xl:flex-row gap-8 xl:gap-10">

                    {{-- AVATAR + BADGES --}}
                    <div class="flex flex-col items-center relative z-20 shrink-0">
                        <div class="relative group">
                            {{-- Glow ring --}}
                            <div
                                class="absolute -inset-1.5 rounded-[1.75rem] bg-gradient-to-br from-indigo-500 via-violet-500 to-sky-400 opacity-60 blur-md group-hover:opacity-90 transition-opacity duration-500">
                            </div>
                            <div
                                class="absolute -inset-0.5 rounded-[1.6rem] bg-gradient-to-br from-indigo-400 via-violet-400 to-sky-300 opacity-80">
                            </div>

                            <div
                                class="relative w-36 h-36 rounded-[1.5rem] bg-slate-900 ring-4 ring-slate-900 overflow-hidden shadow-2xl">
                                <img src="{{ $this->student->profil_photo_url }}" alt="Photo de profil"
                                    class="w-full h-full object-cover" />
                            </div>

                            {{-- Online status --}}
                            <span class="absolute bottom-2 right-2 flex h-5 w-5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-60"></span>
                                <span
                                    class="relative inline-flex rounded-full h-5 w-5 bg-emerald-500 ring-4 ring-slate-900"></span>
                            </span>
                        </div>
                    </div>

                    {{-- INFOS PRINCIPALES --}}
                    <div class="flex-1 min-w-0 pt-2 xl:pt-6">
                        <div class="flex flex-col 2xl:flex-row 2xl:items-start 2xl:justify-between gap-8">

                            <div class="min-w-0 space-y-5">
                                {{-- Nom + Identifiants --}}
                                <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                                    <div class="min-w-0">
                                        <h1
                                            class="text-3xl sm:text-4xl lg:text-[2.6rem] font-bold tracking-tight text-white leading-tight">
                                            {{ $this->student->prenames }}
                                            <span class="text-slate-300">{{ $this->student->name }}</span>
                                        </h1>

                                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-sm">
                                            <span class="inline-flex items-center gap-1.5 text-slate-400">
                                                <span class="text-slate-500">Matricule</span>
                                                <span
                                                    class="font-mono text-slate-200">{{ $this->student->matricule }}</span>
                                            </span>
                                            <span class="hidden sm:inline text-slate-700">•</span>
                                            <span class="inline-flex items-center gap-1.5 text-slate-400">
                                                <span class="text-slate-500">EducMaster</span>
                                                <span
                                                    class="font-medium text-slate-300">{{ $this->student->educMaster }}</span>
                                            </span>
                                        </div>

                                        @if ($this->student->hasResponsibleInThisYear())
                                            <p class="mt-2 text-sm text-slate-400">
                                                {{ $this->student->hasResponsibleInThisYear() }}
                                                de la classe de
                                                <span
                                                    class="text-sky-400 font-medium">{{ $this->student->currentClasse()?->name }}</span>
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Classe actuelle (badge) --}}
                                    <div
                                        class="shrink-0 rounded-2xl border border-white/5 bg-slate-950/60 backdrop-blur-sm px-5 py-3.5 min-w-[140px]">
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">
                                            Classe • {{ $this->activeYear->slug }}
                                        </p>
                                        @if ($this->currentClasse)
                                            <a wire:navigate
                                                href="{{ route('tenant.classe.profil', ['classe_slug' => $this->currentClasse->slug]) }}"
                                                class="block text-2xl font-bold font-mono text-sky-400 hover:text-sky-300 transition-colors">
                                                {{ $this->currentClasse->code ?: $this->currentClasse->name }}
                                            </a>
                                        @else
                                            <p class="text-xs text-slate-600 leading-snug">
                                                Pas encore de classe<br>en {{ $this->activeYear?->slug }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Mini stats --}}
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div
                                        class="rounded-2xl bg-slate-950/70 border border-white/5 p-4 hover:border-indigo-500/30 transition-colors">
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500">Âge</p>
                                        <p class="mt-1.5 text-xl font-semibold text-white">
                                            {{ getAge($this->student->birth_date) }} <span
                                                class="text-sm font-normal text-slate-400">ans</span></p>
                                    </div>
                                    <div
                                        class="rounded-2xl bg-slate-950/70 border border-white/5 p-4 hover:border-indigo-500/30 transition-colors">
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500">Sexe</p>
                                        <p class="mt-1.5 text-xl font-semibold text-white">{{ $this->student->gender }}
                                        </p>
                                    </div>
                                    <div
                                        class="rounded-2xl bg-slate-950/70 border border-white/5 p-4 hover:border-indigo-500/30 transition-colors">
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500">Nationalité</p>
                                        <p class="mt-1.5 text-xl font-semibold text-white truncate">
                                            {{ $this->student->country }}</p>
                                    </div>
                                    <div
                                        class="rounded-2xl bg-slate-950/70 border border-white/5 p-4 hover:border-indigo-500/30 transition-colors">
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500">Naissance</p>
                                        <p class="mt-1.5 text-base font-semibold text-white leading-tight">
                                            {{ formatBirthDate($this->student->birth_date) }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- ACTIONS --}}
                            <div class="w-full xl:w-72 shrink-0 space-y-3">
                                <div class="grid grid-cols-2 gap-2.5">
                                    <a title="Changer la photo de profil"
                                        href="{{ route('tenant.director.manage.profil.photo', ['target' => 'apprenant', 'modelUuid' => $this->student->uuid]) }}"
                                        class="flex items-center justify-center gap-2 h-11 rounded-xl bg-slate-800/80 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                                        <x-lucide-image-upscale class="w-4 h-4 opacity-70" />
                                        <span>Photo</span>
                                    </a>
                                    <a title="Mettre à jour les informations"
                                        href="{{ route('tenant.director.manage.student.data', ['studentUuid' => $this->student->uuid]) }}"
                                        class="flex items-center justify-center gap-2 h-11 rounded-xl bg-blue-600/90 hover:bg-blue-500 border border-blue-500/30 text-sm font-medium transition-all active:scale-[0.97]">
                                        <x-lucide-user-pen class="w-4 h-4 opacity-80" />
                                        <span>Infos</span>
                                    </a>
                                </div>

                                <a wire:navigate
                                    href="{{ route('tenant.student.marks', ['student_uuid' => $this->student_uuid]) }}"
                                    class="flex items-center justify-center gap-2 h-11 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/20 text-emerald-400 text-sm font-medium transition-all active:scale-[0.97]">
                                    <x-lucide-file-bar-chart class="w-4 h-4" />
                                    Les notes
                                </a>

                                <button type="button" wire:click="markStudentAsLeaved({{ $this->student->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="markStudentAsLeaved({{ $this->student->id }})"
                                    class="w-full flex items-center justify-center gap-2 h-11 rounded-xl bg-orange-600/20 hover:bg-orange-600/30 border border-orange-500/25 text-orange-300 text-sm font-medium transition-all disabled:opacity-50 active:scale-[0.97]">
                                    <span wire:loading.remove
                                        wire:target="markStudentAsLeaved({{ $this->student->id }})"
                                        class="flex items-center gap-2">
                                        <x-lucide-user-x class="w-4 h-4" />
                                        Marquer comme abandon
                                    </span>
                                    <span wire:loading wire:target="markStudentAsLeaved({{ $this->student->id }})"
                                        class="flex items-center gap-2">
                                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        Traitement...
                                    </span>
                                </button>

                                @if ($this->currentClasse)
                                    <a wire:navigate
                                        href="{{ route('tenant.classe.profil', ['classe_slug' => $this->currentClasse->slug]) }}"
                                        class="flex items-center justify-center gap-2 h-11 rounded-xl bg-sky-500/15 hover:bg-sky-500/25 border border-sky-500/20 text-sky-400 text-sm font-medium transition-all active:scale-[0.97]">
                                        Voir sa classe
                                    </a>

                                    <button type="button" wire:click="removeStudentFromCurrent"
                                        wire:loading.attr="disabled" wire:target="removeStudentFromCurrent"
                                        class="w-full flex items-center justify-center gap-2 h-11 rounded-xl bg-red-600/20 hover:bg-red-600/30 border border-red-500/25 text-red-300 text-sm font-medium transition-all disabled:opacity-50 active:scale-[0.97]">
                                        <span wire:loading.remove wire:target="removeStudentFromCurrent"
                                            class="flex items-center gap-2">
                                            <x-lucide-user-x class="w-4 h-4" />
                                            Retirer de la classe
                                        </span>
                                        <span wire:loading wire:target="removeStudentFromCurrent"
                                            class="flex items-center gap-2">
                                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Traitement...
                                        </span>
                                    </button>
                                @endif

                                <a href="{{ route('tenant.student.manage.classe', ['student_uuid' => $student_uuid]) }}"
                                    class="flex items-center justify-center gap-2 h-11 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/20 text-amber-400 text-sm font-medium transition-all active:scale-[0.97]">
                                    {{ $this->currentClasse ? 'Changer de classe' : 'Définir une classe' }}
                                </a>

                                <a wire:navigate
                                    href="{{ route('tenant.student.manage.relations', ['student_uuid' => $this->student->uuid]) }}"
                                    class="flex items-center justify-center gap-2 h-11 rounded-xl bg-indigo-500/20 hover:bg-indigo-500/30 border border-indigo-500/25 text-indigo-300 text-sm font-medium transition-all active:scale-[0.97]">
                                    <x-lucide-users class="w-4 h-4" />
                                    Éditer les parents
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CONTENU PRINCIPAL ===================== --}}
    <section class="grid grid-cols-1 2xl:grid-cols-12 gap-6 mb-8">

        {{-- COLONNE DROITE (STATS + PARENTS + PRÉSENCE) --}}
        <div class="2xl:col-span-4 space-y-6 min-w-0 order-2 2xl:order-1">

            {{-- STATISTIQUES --}}
            <div
                class="rounded-[1.75rem] border border-white/5 bg-slate-900/70 backdrop-blur-xl p-6 shadow-xl shadow-black/20">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-white">Statistiques</h2>
                    <span class="text-xs text-slate-500 font-mono">Moyennes</span>
                </div>

                <div class="space-y-6">
                    {{-- Scientifique --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-slate-300">Scientifiques</span>
                            <span class="text-sm font-bold text-indigo-400">16.2</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-800/80 overflow-hidden">
                            <div
                                class="h-full w-[82%] rounded-full bg-gradient-to-r from-indigo-600 to-indigo-400 shadow-[0_0_12px_rgba(99,102,241,0.4)]">
                            </div>
                        </div>
                    </div>

                    {{-- Littéraire --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-slate-300">Littéraires</span>
                            <span class="text-sm font-bold text-emerald-400">13.4</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-800/80 overflow-hidden">
                            <div
                                class="h-full w-[68%] rounded-full bg-gradient-to-r from-emerald-600 to-emerald-400 shadow-[0_0_12px_rgba(16,185,129,0.35)]">
                            </div>
                        </div>
                    </div>

                    {{-- Informatique --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-slate-300">Informatiques</span>
                            <span class="text-sm font-bold text-amber-400">17.5</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-800/80 overflow-hidden">
                            <div
                                class="h-full w-[92%] rounded-full bg-gradient-to-r from-amber-500 to-amber-300 shadow-[0_0_12px_rgba(245,158,11,0.4)]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PARENTS / TUTEURS --}}
            <div
                class="rounded-[1.75rem] border border-white/5 bg-slate-900/70 backdrop-blur-xl p-6 shadow-xl shadow-black/20">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-semibold text-white">Parents / Tuteurs</h2>
                    <a wire:navigate
                        href="{{ route('tenant.student.manage.relations', ['student_uuid' => $this->student->uuid]) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-500/20 hover:bg-indigo-500/30 border border-indigo-500/20 text-indigo-300 text-xs font-medium transition-all">
                        <x-lucide-edit class="w-3.5 h-3.5" />
                        Éditer
                    </a>
                </div>

                <div class="space-y-3">
                    @if ($this->parents)
                        <p class="text-right text-xs text-slate-500 mb-1">
                            {{ __zero(count($this->parents)) }} parent{{ count($this->parents) > 1 ? 's' : '' }} ou
                            tuteur{{ count($this->parents) > 1 ? 's' : '' }}
                        </p>

                        @foreach ($this->parents as $parent_rel)
                            <a wire:navigate
                                href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent_rel->parent->uuid]) }}"
                                class="block rounded-2xl bg-slate-950/60 border border-white/5 p-4 hover:border-sky-500/30 hover:bg-slate-950/80 transition-all group active:scale-[0.99]">
                                <div class="flex gap-4">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-slate-800 shrink-0 overflow-hidden ring-2 ring-slate-700 group-hover:ring-sky-500/50 transition-all">
                                        <img src="{{ $parent_rel->parent->user->profil_photo_url }}"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3
                                            class="font-medium text-white truncate group-hover:text-sky-300 transition-colors">
                                            {{ $parent_rel->parent->getFullName() }}
                                        </h3>
                                        <p class="mt-0.5 text-sm text-slate-400 truncate">
                                            {{ $parent_rel->parent->user->contacts }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500 truncate">
                                            {{ $parent_rel->parent->user->email }}</p>
                                        <p
                                            class="mt-2 text-xs font-medium text-lime-400/90 border-t border-lime-500/20 pt-1.5 text-right">
                                            {{ $parent_rel->parent_relation }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="py-8 text-center">
                            <p class="text-slate-600 font-mono text-sm animate-pulse">Aucun parent lié</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- PRÉSENCE HEBDOMADAIRE --}}
            <div
                class="rounded-[1.75rem] border border-white/5 bg-slate-900/70 backdrop-blur-xl p-6 shadow-xl shadow-black/20">
                <h2 class="text-lg font-semibold text-white mb-6">Présence hebdomadaire</h2>

                <div class="space-y-5">
                    @foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'] as $day)
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm text-slate-300">{{ $day }}</span>
                                <span class="text-sm font-semibold text-emerald-400">100%</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-800/80 overflow-hidden">
                                <div
                                    class="h-full w-full rounded-full bg-gradient-to-r from-emerald-600 to-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.35)]">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- COLONNE PRINCIPALE (peut accueillir d'autres contenus plus tard) --}}
        <div class="2xl:col-span-8 order-1 2xl:order-2">
            {{-- Tu peux ajouter ici d'autres sections (timeline, dernières notes, etc.) --}}
        </div>
    </section>

    {{-- ===================== BULLETIN ===================== --}}
    <section class="pb-10">
        <div
            class="rounded-[1.75rem] border border-white/5 bg-slate-900/70 backdrop-blur-xl p-6 sm:p-8 shadow-xl shadow-black/20">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-white">
                    Bulletin de notes
                    <span class="text-sky-400">{{ session('school_year_selected') }}</span>
                </h2>
                <p class="mt-1.5 text-sm text-slate-400">
                    Détails des notes par semestre / trimestre de l’apprenant
                </p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
                <select wire:model.live="period_type_selected"
                    class="h-12 px-4 rounded-2xl bg-slate-950 border border-white/10 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500/50 transition-all min-w-[220px]">
                    <option value="">Sélectionner le semestre / trimestre</option>
                    @foreach (range(1, 2) as $i)
                        <option value="Semestre {{ $i }}">Semestre {{ $i }}</option>
                    @endforeach
                    @foreach (range(1, 3) as $i)
                        <option value="Trimestre {{ $i }}">Trimestre {{ $i }}</option>
                    @endforeach
                </select>

                @if ($period_type_selected)
                    <div class="flex gap-2.5">
                        <button wire:click="reloadStudentBulletin"
                            class="h-12 px-6 rounded-2xl bg-sky-600 hover:bg-sky-500 border border-sky-500/40 text-sm font-medium transition-all active:scale-[0.97]">
                            Charger
                        </button>
                        <button wire:click="resetBulletinSelections"
                            class="h-12 px-6 rounded-2xl bg-slate-800 hover:bg-slate-700 border border-white/10 text-sm font-medium transition-all active:scale-[0.97]">
                            Réinitialiser
                        </button>
                    </div>
                @endif
            </div>

            @livewire('tenants.classes.sections.classe-pupil-bulletin-component')
        </div>
    </section>
</div>
