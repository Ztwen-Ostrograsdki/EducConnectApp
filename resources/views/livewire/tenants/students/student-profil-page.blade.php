<div class="w-full max-w-full overflow-x-hidden p-2">
    <section class="mb-6">
        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">
            {{-- COVER PHOTO --}}
            <div class="relative h-32 sm:h-44 w-full flex justify-center overflow-hidden">
                @if ($this->currentClasse)
                    <span
                        class="px-3 py-4 absolute z-70 sm:flex hidden  text-sky-500/70  text-center rounded-2xl m-3 text-4xl font-bold uppercase font-mono">

                        <span>{{ $this->currentClasse?->name }}</span>
                    </span>
                @endif
                <img src="{{ $this->student->profil_photo_url }}" alt="Photo de couverture"
                    class="w-full h-full object-cover object-top scale-110" />

                {{-- Overlay sombre + lueur indigo pour le style --}}
                <div class="absolute inset-0 bg-linear-to-br from-indigo-950/70 via-slate-900/50 to-slate-950/80"></div>

                {{-- Lueurs colorées par-dessus la photo --}}
                <div class="absolute -top-10 -left-10 w-64 h-64 rounded-full bg-indigo-600/30 blur-3xl"></div>
                <div class="absolute top-0 right-1/3 w-48 h-48 rounded-full bg-violet-600/20 blur-3xl"></div>
                <div class="absolute -bottom-8 right-10 w-56 h-56 rounded-full bg-sky-500/20 blur-3xl"></div>

                {{-- Overlay bas pour transition douce vers le card body --}}
                <div class="absolute bottom-0 left-0 right-0 h-24 bg-linear-to-t from-slate-900 to-transparent"></div>
            </div>
            {{-- AVATAR flottant sur la cover --}}
            <div class="px-5 sm:px-8 pb-6 sm:pb-8">
                <div class="flex flex-col xl:flex-row gap-8">
                    {{-- AVATAR --}}
                    <div class="flex flex-col items-center  -mt-16 relative z-20">
                        {{-- Anneau lumineux autour de l'avatar --}}
                        <div class="relative shrink-0">
                            <div
                                class="absolute -inset-1 rounded-3xl bg-gradient-to-br from-indigo-500 via-violet-500 to-sky-500 opacity-70 blur-sm">
                            </div>
                            <div
                                class="relative w-32 h-32 rounded-3xl bg-slate-800 ring-4 ring-slate-900 shrink-0 overflow-hidden">
                                <img src="{{ $this->student->profil_photo_url }}" alt="Photo de profil"
                                    class="w-full h-full object-cover" />
                            </div>
                            {{-- Badge statut en ligne --}}
                            <span
                                class="absolute -bottom-1.5 -right-1.5 w-5 h-5 rounded-full bg-emerald-500 ring-2 ring-slate-900 block"></span>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-3 justify-center">

                        </div>
                    </div>
                    {{-- INFOS --}}
                    <div class="flex-1 min-w-0 pt-4">

                        <div class="flex flex-col 2xl:flex-row 2xl:items-start 2xl:justify-between gap-6">

                            <div class="min-w-0">

                                <div class="flex gap-3">
                                    <div class="">
                                        <h1 class="text-3xl sm:text-4xl font-bold break-words">
                                            <span>
                                                {{ $this->student->prenames }}
                                            </span>
                                            <span>
                                                {{ $this->student->name }}
                                            </span>
                                        </h1>

                                        <p class="mt-2 text-slate-400 inline-flex flex-col items-center gap-y-1">
                                            <span class="">
                                                Matricule : {{ $this->student->matricule }}
                                            </span>
                                            <span class="text-slate-600">
                                                EducMaster : {{ $this->student->educMaster }}
                                            </span>
                                            @if ($this->student->hasResponsibleInThisYear())
                                                <span class="flex items-center gap-2">
                                                    <span>{{ $this->student->hasResponsibleInThisYear() }}</span>
                                                    <span>de la classe de
                                                        {{ $this->student->currentClasse()?->name }}</span>
                                                </span>
                                            @endif
                                        </p>
                                    </div>

                                    <div
                                        class="flex-col items-center justify-center rounded-2xl shadow-sm shadow-sky-600 p-2">

                                        <h5 class="text-sm text-slate-500 border-b p-2">
                                            Classe actuelle <span>{{ $this->activeYear->slug }}</span>
                                        </h5>

                                        @if ($this->currentClasse)
                                            <a wire:navigate
                                                href="{{ route('tenant.classe.profil', ['classe_slug' => $this->currentClasse->slug]) }}"
                                                class="flex justify-center items-center hover:text-orange-500 hover:bg-gray-900 mt-2.5 uppercase font-mono text-2xl text-sky-500">
                                                <span>{{ $this->currentClasse->code ? $this->currentClasse->code : $this->currentClasse->name }}</span>
                                            </a>
                                        @else
                                            <span class="flex-col flex gap-1 justify-center text-xs text-slate-600">
                                                <span>Pas encore de</span>
                                                <span>classe en {{ $this->activeYear?->slug }}</span>
                                            </span>
                                        @endif

                                    </div>
                                </div>

                                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Âge
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            {{ getAge($this->student->birth_date) }} ans
                                        </h4>

                                    </div>

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Sexe
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            {{ $this->student->gender }}
                                        </h4>

                                    </div>

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Nationalité
                                        </p>

                                        <h4 class="mt-2 font-semibold">
                                            {{ $this->student->country }}
                                        </h4>

                                    </div>

                                    <div class="rounded-2xl bg-slate-950 p-4">

                                        <p class="text-xs text-slate-500">
                                            Naissance
                                        </p>

                                        <h4 class="mt-2 font-semibold text-sm">
                                            {{ formatBirthDate($this->student->birth_date) }}
                                        </h4>

                                    </div>

                                </div>

                            </div>

                            {{-- ACTIONS --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-2 gap-3 w-full xl:w-75">

                                <a title="Changer la photo de profil de {{ $this->student->getFullName() }}"
                                    href="{{ route('tenant.director.manage.profil.photo', ['target' => 'apprenant', 'modelUuid' => $this->student->uuid]) }}"
                                    class="p-3 rounded-2xl bg-slate-500 hover:bg-slate-600 transition-all text-sm flex items-center justify-center text-center active:scale-95">

                                    <span class="inline-flex items-center gap-x-2">
                                        <x-lucide-image-upscale class="w-4 h-4" />
                                        <span>Editer photo</span>
                                    </span>

                                </a>
                                <a title="Mettre à jour les informations de l'apprenant {{ $this->student->getFullName() }}"
                                    href="{{ route('tenant.director.manage.student.data', ['studentUuid' => $this->student->uuid]) }}"
                                    class="p-3 rounded-2xl bg-blue-500 hover:bg-blue-600 transition-all text-sm flex items-center justify-center text-center active:scale-95">

                                    <span class="inline-flex items-center gap-x-2">
                                        <x-lucide-user-pen class="w-4 h-4" />
                                        <span>Editer infos</span>
                                    </span>

                                </a>
                                <a wire:navigate
                                    href="{{ route('tenant.student.marks', ['student_uuid' => $this->student_uuid]) }}"
                                    class="p-3 rounded-2xl bg-green-500/20 col-span-2 text-green-400 hover:bg-green-500/30 transition-all text-sm inline-block text-center active:scale-95">

                                    Les notes

                                </a>

                                <button type="button" wire:click="markStudentAsLeaved({{ $this->student->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="markStudentAsLeaved({{ $this->student->id }})"
                                    class="rounded-2xl col-span-2 items-center gap-2 bg-orange-600/60 p-3 text-sm font-medium text-white transition hover:bg-orange-700 disabled:opacity-60 hover:text-black active:scale-95">
                                    <span wire:loading.remove
                                        wire:target="markStudentAsLeaved({{ $this->student->id }})"
                                        class="flex justify-center items-center">
                                        <span class="flex items-center gap-3">
                                            <x-lucide-user-x class="w-4 h-4 " />
                                            <span>Marquer comme abandon</span>
                                        </span>
                                    </span>
                                    <span wire:loading wire:target="markStudentAsLeaved({{ $this->student->id }})"
                                        class="flex items-center gap-2">
                                        <span class="flex items-center gap-2">
                                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                                                </path>
                                            </svg>
                                            Traitement en cours...
                                        </span>
                                    </span>
                                </button>

                                @if ($this->currentClasse)
                                    <a wire:navigate
                                        href="{{ route('tenant.classe.profil', ['classe_slug' => $this->currentClasse->slug]) }}"
                                        class="p-3 col-span-2 rounded-2xl bg-sky-500/20 text-sky-400 hover:bg-sky-500/60 transition-all text-sm inline-block text-center hover:text-black active:scale-95">

                                        Voir sa classe actuelle
                                    </a>
                                    <button type="button" wire:click="removeStudentFromCurrent"
                                        wire:loading.attr="disabled" wire:target="removeStudentFromCurrent"
                                        class="p-3 col-span-2 rounded-2xl items-center gap-2 bg-red-600/40 text-sm font-medium text-white transition hover:bg-red-700 disabled:opacity-60 active:scale-95">
                                        <span wire:loading.remove wire:target="removeStudentFromCurrent"
                                            class="flex justify-center items-center">
                                            <span class="flex items-center gap-3">
                                                <x-lucide-user-x class="w-4 h-4 " />
                                                <span>Retirer la classe actuelle</span>
                                            </span>
                                        </span>
                                        <span wire:loading wire:target="removeStudentFromCurrent"
                                            class="flex items-center gap-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                                                    </path>
                                                </svg>
                                                Traitement en cours...
                                            </span>
                                        </span>
                                    </button>
                                @endif
                                <a href="{{ route('tenant.student.manage.classe', ['student_uuid' => $student_uuid]) }}"
                                    class="p-3 col-span-2 rounded-2xl bg-orange-500/20 text-orange-400 hover:bg-orange-500/60 transition-all text-sm inline-block text-center hover:text-black active:scale-95">

                                    {{ $this->currentClasse ? 'Changer de classe ' : 'Définir nouvelle classe' }}

                                </a>
                                <a wire:navigate
                                    href="{{ route('tenant.student.manage.relations', ['student_uuid' => $this->student->uuid]) }}"
                                    class="rounded-2xl col-span-2 justify-center p-3 bg-indigo-500/40 text-white hover:text-black hover:bg-indigo-700 active:scale-95 flex items-center gap-2 text-sm">
                                    <x-lucide-edit class="w-4 h-4" />
                                    <span>Editer les parents</span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>{{-- fin px-5 sm:px-8 --}}

        </div>

    </section>

    <section class="grid grid-cols-1 2xl:grid-cols-12 gap-6">

        {{-- RIGHT --}}
        <div class="2xl:col-span-4 space-y-6 min-w-0">

            {{-- STATS MATIERES --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">

                <h2 class="text-xl font-semibold">
                    Statistiques
                </h2>

                <div class="mt-6 space-y-5">

                    {{-- SCIENTIFIQUE --}}
                    <div>

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-slate-300">
                                Scientifiques
                            </span>

                            <span class="text-sm font-semibold">
                                16.2
                            </span>

                        </div>

                        <div class="mt-2 h-2 rounded-full bg-slate-800 overflow-hidden">

                            <div class="h-full w-[82%] bg-indigo-500 rounded-full">
                            </div>

                        </div>

                    </div>

                    {{-- LETTER --}}
                    <div>

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-slate-300">
                                Littéraires
                            </span>

                            <span class="text-sm font-semibold">
                                13.4
                            </span>

                        </div>

                        <div class="mt-2 h-2 rounded-full bg-slate-800 overflow-hidden">

                            <div class="h-full w-[68%] bg-emerald-500 rounded-full">
                            </div>

                        </div>

                    </div>

                    {{-- INFO --}}
                    <div>

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-slate-300">
                                Informatiques
                            </span>

                            <span class="text-sm font-semibold">
                                17.5
                            </span>

                        </div>

                        <div class="mt-2 h-2 rounded-full bg-slate-800 overflow-hidden">

                            <div class="h-full w-[92%] bg-amber-500 rounded-full">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- PARENTS --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">

                <h2 class="text-sm font-semibold flex justify-between items-center">
                    <span>Parents / Tuteurs</span>

                    <a wire:navigate
                        href="{{ route('tenant.student.manage.relations', ['student_uuid' => $this->student->uuid]) }}"
                        class="rounded-2xl p-3 bg-indigo-500/40 text-white hover:text-black hover:bg-indigo-700 font-mono active:scale-95 flex items-center gap-2">
                        <x-lucide-edit class="w-4 h-4" />
                        <span>Editer les parents</span>
                    </a>
                </h2>

                <div class="mt-6 flex flex-col gap-3">
                    @if ($this->parents)
                        <h4 class="border-b border-b-slate-800 py-1 text-right text-slate-500">
                            {{ __zero(count($this->parents)) }}
                            parents ou tuteurs</h4>
                        @foreach ($this->parents as $parent_rel)
                            <a wire:navigate
                                href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent_rel->parent->uuid]) }}"
                                class="rounded-2xl bg-slate-950 p-4 group active:scale-95 w-full">

                                <div class="flex w-full gap-4">

                                    <div class="w-14 h-14 rounded-2xl bg-slate-800 shrink-0">
                                        <img src="{{ $parent_rel->parent->user->profil_photo_url }}"
                                            class="w-full h-full rounded-2xl border-4 border-slate-500 object-cover group-hover:border-sky-600">
                                    </div>

                                    <div class="w-full">

                                        <h3 class="font-medium truncate group-hover:text-sky-700">
                                            {{ $parent_rel->parent->getFullName() }}
                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400 truncate group-hover:text-orange-700">
                                            {{ $parent_rel->parent->user->contacts }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500 truncate group-hover:text-orange-700">
                                            {{ $parent_rel->parent->user->email }}
                                        </p>
                                        <p
                                            class="mt-1 text-sm text-lime-500 truncate group-hover:text-orange-700 text-right border-t border-t-lime-400">
                                            {{ $parent_rel->parent_relation }}
                                        </p>

                                    </div>

                                </div>

                            </a>
                        @endforeach
                    @else
                        <div class="flex justify-center items-center text-slate-700 animate-pulse font-mono">
                            <h4>Aucun parent lié</h4>
                        </div>
                    @endif

                </div>

            </div>

            {{-- PRESENCE --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">

                <h2 class="text-xl font-semibold">
                    Présence Hebdomadaire
                </h2>

                <div class="mt-6 space-y-4">

                    @foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'] as $day)
                        <div>

                            <div class="flex items-center justify-between">

                                <span class="text-sm text-slate-300">
                                    {{ $day }}
                                </span>

                                <span class="text-sm font-semibold">
                                    100%
                                </span>

                            </div>

                            <div class="mt-2 h-2 rounded-full bg-slate-800 overflow-hidden">

                                <div class="h-full w-full bg-emerald-500 rounded-full">
                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </section>

    <section class="grid my-3 pb-9">
        {{-- Bulletins --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <div>
                <h2 class="text-xl font-semibold">
                    Bulletin de notes de l'année scolaire <span
                        class="text-sky-600">{{ session('school_year_selected') }}</span>
                </h2>
                <p class="mt-1 text-sm text-slate-400">
                    Détails sur les notes par semestre|trimestre de l'apprenant
                </p>
            </div>
            <div class="flex flex-col xl:flex-row gap-4">
                {{-- FILTERS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 p-1 py-2.5">

                    {{-- SEMESTER --}}
                    <select wire:model.live="period_type_selected"
                        class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        <option value="">Sélectionner le semestre|trimestre</option>
                        @foreach (range(1, 2) as $i)
                            <option value="Semestre {{ $i }}">Semestre {{ $i }}</option>
                        @endforeach

                        @foreach (range(1, 3) as $i)
                            <option value="Trimestre {{ $i }}">Trimestre {{ $i }}</option>
                        @endforeach

                    </select>

                    {{-- ACTIONS --}}
                    @if ($period_type_selected)
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
            @livewire('tenants.classes.sections.classe-pupil-bulletin-component')
        </div>
    </section>
</div>

