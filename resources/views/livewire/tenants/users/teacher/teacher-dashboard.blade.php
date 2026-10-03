<div class="w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- GLOBAL CONTAINER --}}
    {{-- ===================================================== --}}
    <div class="mx-auto
                w-full
                max-w-[1850px]
                mb-28">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div
                class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        overflow-hidden">

                <div class="p-4 sm:p-6 xl:p-8">

                    <div class="flex flex-col xl:flex-row gap-6 xl:gap-8">

                        {{-- LEFT --}}
                        <div class="flex flex-col sm:flex-row gap-5 flex-1 min-w-0">

                            {{-- AVATAR --}}
                            <div class="flex justify-center sm:block shrink-0 relative">

                                <img src="{{ $this->user->profil_photo_url }}" alt=""
                                    class="w-40 h-40
                               rounded-full
                               object-cover
                               border-4
                               border-slate-700">

                                <a title="Editer ma photo de profil" href="{{ route('tenant.update.profil.photo') }}"
                                    class="absolute bottom-2 right-2
                               w-12 h-12 rounded-full
                               bg-indigo-800/75 hover:bg-indigo-500 hover:text-black
                               flex items-center justify-center">
                                    <x-lucide-camera class="w-5 h-5" />
                                </a>

                            </div>

                            {{-- INFOS --}}
                            <div class="flex-1 min-w-0">

                                <div class="flex flex-col gap-4">

                                    {{-- TOP --}}
                                    <div class="min-w-0">

                                        <div class="flex flex-wrap items-center gap-2">

                                            <h1
                                                class="text-2xl sm:text-3xl
                                                       font-bold
                                                       break-words">

                                                {{ $this->teacher->getFullName(true) }}

                                            </h1>

                                            <span
                                                class="px-3 py-1 rounded-full
                                                         bg-indigo-500/10
                                                         text-indigo-400
                                                         text-xs shrink-0">

                                                Enseignant

                                            </span>

                                        </div>

                                        <p class="mt-2 text-slate-400 text-sm">

                                            ID : {{ $this->teacher->identifiant }}

                                        </p>

                                    </div>

                                    {{-- GRID INFOS --}}
                                    <div
                                        class="grid
                                                grid-cols-2
                                                lg:grid-cols-3
                                                gap-3">

                                        <div class="rounded-2xl bg-slate-950 p-3">

                                            <p class="text-xs text-slate-500">
                                                Téléphone
                                            </p>

                                            <h4 class="mt-1 font-medium truncate">
                                                {{ $this->user->contacts }}
                                            </h4>

                                        </div>

                                        <div class="rounded-2xl bg-slate-950 p-3">

                                            <p class="text-xs text-slate-500">
                                                Email
                                            </p>

                                            <h4 class="mt-1 font-medium">
                                                {{ $this->user->email }}
                                            </h4>

                                        </div>

                                        <div class="rounded-2xl bg-slate-950 p-3">

                                            <p class="text-xs text-slate-500">
                                                Statut
                                            </p>

                                            <h4 class="mt-1 font-medium text-emerald-400 text-sm">
                                                <div
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full @if (!$this->teacher->blocked) text-emerald-400 @else  text-red-400 @endif">

                                                    <x-lucide-circle-check class="w-4 h-4" />

                                                    @if (!$this->teacher->blocked)
                                                        Compte actif
                                                    @else
                                                        Compte bloqué
                                                    @endif

                                                </div>
                                            </h4>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>
            <div class="rounded-2xl border border-white/5 bg-slate-900/60 backdrop-blur-sm p-4 sm:p-5 my-4">

                {{-- Header --}}
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">
                        Matière(s) · Spécialité(s)
                    </p>
                </div>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-2">
                    @forelse ($this->teacher->getYearlySubjects() as $yearly_subject)
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-medium
                         bg-indigo-500/10 text-indigo-300 border border-indigo-500/20
                         hover:bg-indigo-500/20 hover:border-indigo-500/40 hover:text-indigo-200
                         cursor-pointer transition-all duration-200 hover:scale-[1.03]">
                            <x-lucide-book-open class="w-3.5 h-3.5 opacity-70" />
                            {{ $yearly_subject->subject->name }}
                        </span>
                    @empty
                        <div class="w-full py-6 text-center">
                            <p class="text-sm text-orange-500/70 italic font-mono">
                                Matières et spécialités non spécifiées
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </section>

        {{-- Bouton d'action en haut --}}
        <section class="mb-6 flex items-center justify-end">
            @if (auth('tenant')->user()->canManageCoef(tenant('id')))
                <a wire:navigate href="{{ route('tenant.subjects.coefs.manage') }}"
                    class="group inline-flex items-center gap-2 px-4 py-2.5 rounded-xl 
                  bg-emerald-500/15 text-emerald-400 border border-emerald-500/30
                  hover:bg-emerald-500 hover:text-white hover:border-emerald-500
                  transition-all duration-300 text-sm font-medium shadow-lg shadow-emerald-500/10">
                    <x-lucide-plus class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" />
                    <span>Ajouter un coéf</span>
                </a>
            @endif
        </section>

        {{-- Conteneur principal --}}
        <section class="space-y-6">
            <div
                class="rounded-3xl border border-white/5 bg-gradient-to-br from-slate-900/80 to-slate-950/80 
                backdrop-blur-xl overflow-hidden shadow-2xl shadow-black/40">

                {{-- HEADER --}}
                <div class="relative px-6 py-5 sm:px-8 sm:py-6 border-b border-white/5">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500/5 via-transparent to-transparent">
                    </div>

                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                                Mes classes
                            </h2>
                            <p class="mt-1 text-sm text-slate-400">
                                Classes assignées pour
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md 
                                     bg-orange-500/15 text-orange-400 font-medium text-xs">
                                    {{ $this->activeYear->slug }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- CONTENU --}}
                <div class="p-4 sm:p-6">
                    @php
                        $classes = $this->teacher?->getTeacherClassesWithSubjectsForThisSchoolYear();
                    @endphp

                    @if (count($classes))
                        {{-- Version Cards (moderne) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach ($classes as $kls)
                                <div
                                    class="group relative rounded-2xl border border-white/5 
                                    bg-slate-900/60 hover:bg-slate-800/80 
                                    hover:border-white/10 transition-all duration-300
                                    hover:shadow-xl hover:shadow-black/30 overflow-hidden">

                                    {{-- Accent bar --}}
                                    <div
                                        class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-orange-500 to-amber-600 
                                        opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>

                                    <div class="p-5">
                                        {{-- Header de la card --}}
                                        <div class="flex items-start justify-between gap-3 mb-4">
                                            <div class="min-w-0">
                                                <a wire:navigate
                                                    href="{{ route('tenant.teacher.classe.students', ['classe_slug' => $kls->classe->slug, 'subject_slug' => $kls->subject->slug]) }}"
                                                    class="block group/link">
                                                    <h3
                                                        class="font-semibold text-white group-hover/link:text-lime-400 
                                                       transition-colors truncate">
                                                        {{ $kls->classe?->name }}
                                                    </h3>
                                                    <p class="text-xs text-amber-500/80 mt-0.5">
                                                        {{ $kls->classe?->speciality() }}
                                                    </p>
                                                </a>
                                            </div>

                                            <span
                                                class="shrink-0 text-xs font-mono text-slate-500 
                                                 bg-slate-800/80 px-2 py-1 rounded-lg">
                                                #{{ $loop->iteration }}
                                            </span>
                                        </div>

                                        {{-- Infos --}}
                                        <div class="flex flex-wrap items-center gap-2 mb-5">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg 
                                                 bg-slate-800 text-slate-300 text-xs font-medium">
                                                <x-lucide-book-open class="w-3.5 h-3.5 text-sky-400" />
                                                {{ $kls->subject?->code ?? $kls->subject?->name }}
                                            </span>

                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg 
                                                 bg-emerald-500/10 text-emerald-400 text-xs font-medium">
                                                <x-lucide-check-circle class="w-3.5 h-3.5" />
                                                86 notes
                                            </span>

                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg 
                                                 bg-violet-500/10 text-violet-400 text-xs font-medium">
                                                <x-lucide-clock class="w-3.5 h-3.5" />
                                                4h / sem
                                            </span>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex flex-wrap gap-2">
                                            <a wire:navigate
                                                href="{{ route('tenant.teacher.classe.students', ['classe_slug' => $kls->classe->slug, 'subject_slug' => $kls->subject->slug]) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                              bg-sky-500/15 text-sky-400 border border-sky-500/25
                                              hover:bg-sky-500 hover:text-white hover:border-sky-500
                                              transition-all duration-200">
                                                <x-lucide-users class="w-3.5 h-3.5" />
                                                Classe
                                            </a>

                                            <a wire:navigate
                                                href="{{ route('tenant.teacher.classe.marks', ['classe_slug' => $kls->classe->slug, 'subject_slug' => $kls->subject->slug]) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                              bg-emerald-500/15 text-emerald-400 border border-emerald-500/25
                                              hover:bg-emerald-500 hover:text-white hover:border-emerald-500
                                              transition-all duration-200">
                                                <x-lucide-file-text class="w-3.5 h-3.5" />
                                                Notes
                                            </a>

                                            @if (
                                                $this->activeYear &&
                                                    $this->activeYear->active_period &&
                                                    $kls->classe->is_active &&
                                                    !$kls->classe->is_locked &&
                                                    auth('tenant')->user()->teacher->canAccessIntoClasse($kls->classe->id))
                                                <a wire:navigate
                                                    href="{{ route('tenant.teacher.classe.marks.manager', ['classe_slug' => $kls->classe->slug, 'subject_slug' => $kls->subject->slug]) }}"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium
                                                  bg-blue-500/15 text-blue-400 border border-blue-500/25
                                                  hover:bg-blue-500 hover:text-white hover:border-blue-500
                                                  transition-all duration-200">
                                                    <x-lucide-pen-line class="w-3.5 h-3.5" />
                                                    Insérer
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty state --}}
                        <div class="py-16 text-center">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl 
                                bg-orange-500/10 mb-4">
                                <x-lucide-school class="w-8 h-8 text-orange-500" />
                            </div>
                            <p class="text-slate-400 text-lg font-medium">Aucune classe assignée</p>
                            <p class="text-slate-500 text-sm mt-1">Les classes apparaîtront ici une fois assignées.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

    </div>

</div>

