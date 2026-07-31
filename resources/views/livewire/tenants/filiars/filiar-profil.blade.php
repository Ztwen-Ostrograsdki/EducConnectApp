<div class="w-full overflow-x-hidden">
    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8 mb-28">

        {{-- ===================== STATUS BAR ===================== --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-indigo-500/10 border border-indigo-500/20">
                <span class="text-xs uppercase tracking-wider text-slate-500 font-medium">Filière</span>
                <span class="font-mono text-amber-400 font-semibold tracking-wider">{{ $filiar->name }}</span>
            </div>

            <span
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                {{ $filiar->is_active
                    ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
                    : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $filiar->is_active ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                {{ $filiar->is_active ? 'Active' : 'Inactive' }}
            </span>

            @if ($filiar->deleted_at)
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-rose-500/15 text-rose-400 border border-rose-500/25 animate-pulse">
                    <x-lucide-trash-2 class="w-3.5 h-3.5" />
                    Dans la corbeille
                </span>
            @endif
        </div>

        {{-- ===================== HERO ===================== --}}
        <section class="mb-8">
            <div
                class="relative overflow-hidden rounded-[2rem] border border-white/5 bg-slate-900/80 backdrop-blur-xl shadow-2xl shadow-indigo-950/30">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-transparent to-violet-500/5">
                </div>
                <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-indigo-600/20 blur-3xl"></div>
                <div class="absolute top-0 right-1/4 w-64 h-64 rounded-full bg-violet-500/15 blur-3xl"></div>

                <div class="relative p-6 sm:p-8 lg:p-10">
                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-8">

                        <div class="flex flex-col sm:flex-row gap-6 min-w-0">
                            {{-- Code badge --}}
                            <div class="flex justify-center sm:block shrink-0">
                                <div class="relative group">
                                    <div
                                        class="absolute -inset-1 rounded-[1.75rem] bg-gradient-to-br from-indigo-500 via-violet-500 to-sky-400 opacity-40 blur-md group-hover:opacity-70 transition-opacity">
                                    </div>
                                    <div
                                        class="relative w-32 h-32 sm:w-36 sm:h-36 rounded-[1.5rem] bg-slate-950/80 border border-indigo-500/30 flex items-center justify-center p-3">
                                        <span
                                            class="text-lg sm:text-xl font-bold font-mono text-indigo-300 tracking-wider uppercase text-center leading-tight">
                                            {{ str()->replace('-', ' ', $filiar->code) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Infos --}}
                            <div class="min-w-0 text-center sm:text-left">
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white">
                                    {{ $filiar->name }}
                                </h1>

                                <p class="mt-3 text-slate-400 max-w-xl leading-relaxed">
                                    Tableau global des statistiques et performances de la filière
                                    <span class="text-slate-300">{{ $filiar->name }}</span>.
                                </p>

                                <div class="mt-5 flex flex-wrap justify-center sm:justify-start gap-2.5">
                                    <div
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950/60 border border-white/5 text-sm">
                                        <x-lucide-users class="w-4 h-4 text-indigo-400 opacity-80" />
                                        <span
                                            class="font-semibold text-white">{{ __zero($details['teachers_count']) }}</span>
                                        <span class="text-slate-400">Enseignants</span>
                                    </div>
                                    <div
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950/60 border border-white/5 text-sm">
                                        <x-lucide-school class="w-4 h-4 text-sky-400 opacity-80" />
                                        <span
                                            class="font-semibold text-white">{{ __zero($details['classes_count']) }}</span>
                                        <span class="text-slate-400">Classes</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions rapides desktop --}}
                        <div class="hidden xl:flex flex-col gap-2.5 shrink-0 w-52">
                            <a wire:navigate href="{{ route('tenant.filiar.edit', ['filiar_slug' => $filiar->slug]) }}"
                                class="flex items-center justify-center gap-2 h-11 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-all active:scale-[0.97]">
                                <x-lucide-pencil class="w-4 h-4" />
                                Éditer la filière
                            </a>
                            <a wire:navigate href="{{ route('tenant.classes.create') }}"
                                class="flex items-center justify-center gap-2 h-11 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                                <x-lucide-plus class="w-4 h-4 opacity-70" />
                                Créer une classe
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== CHEFS D'ATELIER ===================== --}}
        @php
            $principalCA = $filiar->currentPrincipalCA();
            $adjointCA = $filiar->currentAjointCA();
        @endphp

        @if ($principalCA || $adjointCA)
            <section class="mb-8">
                <div
                    class="rounded-[1.75rem] border border-white/5 bg-slate-900/70 backdrop-blur-xl p-6 sm:p-8 shadow-xl shadow-black/20">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/5">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-500/15 border border-amber-500/20">
                            <x-lucide-briefcase class="w-5 h-5 text-amber-400" />
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Chefs d’Atelier (CA)</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Année scolaire <span
                                    class="text-orange-400 font-medium">{{ $this->activeYear?->slug }}</span></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- PRINCIPAL --}}
                        @if ($principalCA)
                            <div
                                class="rounded-2xl border border-emerald-500/25 bg-slate-950/50 p-5 hover:border-emerald-500/40 transition-colors">
                                <div
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-xs font-medium mb-4">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    Poste principal
                                </div>

                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-slate-800 overflow-hidden ring-2 ring-emerald-500/30 shrink-0">
                                        <img src="{{ $principalCA?->user->profil_photo_url }}"
                                            class="w-full h-full object-cover" alt="">
                                    </div>
                                    <a wire:navigate
                                        href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $principalCA?->uuid]) }}"
                                        class="min-w-0 flex-1 group">
                                        <h4
                                            class="font-semibold text-white truncate group-hover:text-sky-300 transition-colors">
                                            {{ $principalCA?->getFullName() ?? 'Non encore défini' }}
                                        </h4>
                                        <p class="text-sm text-slate-500 truncate mt-0.5">{{ $principalCA?->email }}
                                        </p>
                                    </a>
                                </div>

                                <div class="mt-5 pt-4 border-t border-white/5">
                                    <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-2.5">Classes tenues
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        @php $teacher_classes1 = $principalCA->getTeacherClassesForThisSchoolYear([]); @endphp
                                        @if (count($teacher_classes1))
                                            @foreach ($teacher_classes1 as $cl)
                                                <span
                                                    class="px-3 py-1.5 rounded-xl bg-slate-900 border border-sky-500/20 text-xs font-mono text-sky-300">
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

                        {{-- ADJOINT --}}
                        @if ($adjointCA)
                            <div
                                class="rounded-2xl border border-violet-500/25 bg-slate-950/50 p-5 hover:border-violet-500/40 transition-colors">
                                <div
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/25 text-violet-400 text-xs font-medium mb-4">
                                    <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                                    Poste adjoint
                                </div>

                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-slate-800 overflow-hidden ring-2 ring-violet-500/30 shrink-0">
                                        <img src="{{ $adjointCA?->user->profil_photo_url }}"
                                            class="w-full h-full object-cover" alt="">
                                    </div>
                                    <a wire:navigate
                                        href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $adjointCA?->uuid]) }}"
                                        class="min-w-0 flex-1 group">
                                        <h4
                                            class="font-semibold text-white truncate group-hover:text-sky-300 transition-colors">
                                            {{ $adjointCA?->getFullName() ?? 'Non encore défini' }}
                                        </h4>
                                        <p class="text-sm text-slate-500 truncate mt-0.5">{{ $adjointCA?->email }}</p>
                                    </a>
                                </div>

                                <div class="mt-5 pt-4 border-t border-white/5">
                                    <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-2.5">Classes tenues
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        @php $teacher_classes2 = $adjointCA->getTeacherClassesForThisSchoolYear([]); @endphp
                                        @if (count($teacher_classes2))
                                            @foreach ($teacher_classes2 as $cl)
                                                <span
                                                    class="px-3 py-1.5 rounded-xl bg-slate-900 border border-sky-500/20 text-xs font-mono text-sky-300">
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
                </div>
            </section>
        @endif

        {{-- ===================== ACTIONS ===================== --}}
        <section class="mb-8">
            <div class="flex flex-wrap gap-2.5">
                <a wire:navigate href="{{ route('tenant.filiar.edit.ca', ['filiar_slug' => $filiar->slug]) }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/25 text-amber-300 text-sm font-medium transition-all active:scale-[0.97]">
                    <x-lucide-user-cog class="w-4 h-4" />
                    Éditer les postes CA
                </a>
                <a wire:navigate href="{{ route('tenant.classes.create') }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 border border-sky-500/25 text-sky-300 text-sm font-medium transition-all active:scale-[0.97] xl:hidden">
                    <x-lucide-plus class="w-4 h-4" />
                    Créer une classe
                </a>
                <a wire:navigate href="{{ route('tenant.filiar.edit', ['filiar_slug' => $filiar->slug]) }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-indigo-500/20 hover:bg-indigo-500/30 border border-indigo-500/25 text-indigo-300 text-sm font-medium transition-all active:scale-[0.97] xl:hidden">
                    <x-lucide-pencil class="w-4 h-4" />
                    Éditer la filière
                </a>
                <a wire:navigate href="{{ route('tenant.filiar.students', ['filiar_slug' => $filiar->slug]) }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                    <x-lucide-graduation-cap class="w-4 h-4 opacity-70" />
                    Les apprenants
                </a>
                <a wire:navigate href="{{ route('tenant.filiar.teachers', ['filiar_slug' => $filiar->slug]) }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                    <x-lucide-users class="w-4 h-4 opacity-70" />
                    Les enseignants
                </a>

                <button type="button"
                    title="{{ $filiar->deleted_at ? 'Restaurer cette filière' : 'Mettre cette filière dans la corbeille' }}"
                    wire:click="{{ $filiar->deleted_at ? 'restoreFiliar(' . $filiar->id . ')' : 'deleteFiliar(' . $filiar->id . ')' }}"
                    wire:loading.attr="disabled" wire:target="deleteFiliar, restoreFiliar"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl text-sm font-medium transition-all active:scale-[0.97] disabled:opacity-50
                               {{ $filiar->deleted_at
                                   ? 'bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/25 text-emerald-300'
                                   : 'bg-rose-500/20 hover:bg-rose-500/30 border border-rose-500/25 text-rose-300' }}">
                    <span wire:loading.remove wire:target="deleteFiliar, restoreFiliar"
                        class="inline-flex items-center gap-2">
                        @if ($filiar->deleted_at)
                            <x-lucide-refresh-ccw class="w-4 h-4" />
                            Restaurer
                        @else
                            <x-lucide-trash class="w-4 h-4" />
                            Corbeille
                        @endif
                    </span>
                    <span wire:loading wire:target="deleteFiliar, restoreFiliar"
                        class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        Traitement…
                    </span>
                </button>
            </div>
        </section>

        {{-- ===================== BEST / WORST ===================== --}}
        <section class="mb-8">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                {{-- Meilleure performance --}}
                <div
                    class="rounded-[1.75rem] border border-emerald-500/20 bg-slate-900/70 backdrop-blur-xl p-6 shadow-xl shadow-emerald-950/10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 text-2xl">
                            🏆</div>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Meilleure performance</h2>
                            <p class="text-sm text-slate-400">Plus forte moyenne enregistrée</p>
                        </div>
                    </div>
                    <div
                        class="rounded-2xl bg-slate-950/60 border border-white/5 p-5 hover:border-emerald-500/30 transition-colors">
                        <h3 class="text-lg font-semibold text-white">KOUASSI Sarah</h3>
                        <p class="mt-1.5 text-sm text-slate-400">Classe : Terminale F4-1</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium">Moyenne
                                : 19.75</span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-medium">Promotion
                                : Terminale</span>
                        </div>
                    </div>
                </div>

                {{-- Plus faible --}}
                <div
                    class="rounded-[1.75rem] border border-rose-500/20 bg-slate-900/70 backdrop-blur-xl p-6 shadow-xl shadow-rose-950/10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="flex items-center justify-center w-14 h-14 rounded-2xl bg-rose-500/15 border border-rose-500/25 text-2xl">
                            ⚠️</div>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Plus faible performance</h2>
                            <p class="text-sm text-slate-400">Plus faible moyenne enregistrée</p>
                        </div>
                    </div>
                    <div
                        class="rounded-2xl bg-slate-950/60 border border-white/5 p-5 hover:border-rose-500/30 transition-colors">
                        <h3 class="text-lg font-semibold text-white">HOUNKPE David</h3>
                        <p class="mt-1.5 text-sm text-slate-400">Classe : Tle F4-2</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-medium">Moyenne
                                : 02.15</span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-medium">Promotion
                                : Terminale F4</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== MEILLEUR GARÇON / FILLE ===================== --}}
        <section class="mb-8">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                {{-- Meilleur garçon --}}
                <div
                    class="rounded-[1.75rem] border border-sky-500/20 bg-slate-900/70 backdrop-blur-xl overflow-hidden shadow-xl shadow-sky-950/10">
                    <div class="p-6 border-b border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="flex items-center justify-center w-14 h-14 rounded-2xl bg-sky-500/15 border border-sky-500/25 text-2xl">
                                🏅</div>
                            <div>
                                <h2 class="text-lg font-semibold text-white">Meilleur garçon</h2>
                                <p class="text-sm text-slate-400">Meilleure performance masculine</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                            <div class="flex justify-center lg:block shrink-0">
                                <div
                                    class="w-28 h-28 rounded-[1.5rem] bg-slate-800/80 border border-white/5 overflow-hidden">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 text-center lg:text-left">
                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5">
                                    <h3 class="text-xl sm:text-2xl font-bold text-white">HOUNKPE David</h3>
                                    <span
                                        class="px-3 py-1 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs font-medium">Rang
                                        #1 Garçon</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-400">Terminale F4-1 — Promotion Terminale</p>
                                <div class="mt-4 flex flex-wrap justify-center lg:justify-start gap-2">
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Moyenne : <span class="font-semibold text-sky-300">18.92</span></div>
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Coef : 4</div>
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Prof : M. AHOLOU</div>
                                </div>
                                <div class="mt-5 flex flex-wrap justify-center lg:justify-start gap-2.5">
                                    <button
                                        class="h-10 px-5 rounded-xl bg-sky-600 hover:bg-sky-500 text-sm font-medium transition-all active:scale-[0.97]">Voir
                                        profil</button>
                                    <button
                                        class="h-10 px-5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">Historique
                                        notes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Meilleure fille --}}
                <div
                    class="rounded-[1.75rem] border border-pink-500/20 bg-slate-900/70 backdrop-blur-xl overflow-hidden shadow-xl shadow-pink-950/10">
                    <div class="p-6 border-b border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="flex items-center justify-center w-14 h-14 rounded-2xl bg-pink-500/15 border border-pink-500/25 text-2xl">
                                👑</div>
                            <div>
                                <h2 class="text-lg font-semibold text-white">Meilleure fille</h2>
                                <p class="text-sm text-slate-400">Meilleure performance féminine</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                            <div class="flex justify-center lg:block shrink-0">
                                <div
                                    class="w-28 h-28 rounded-[1.5rem] bg-slate-800/80 border border-white/5 overflow-hidden">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 text-center lg:text-left">
                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5">
                                    <h3 class="text-xl sm:text-2xl font-bold text-white">KOUASSI Sarah</h3>
                                    <span
                                        class="px-3 py-1 rounded-full bg-pink-500/10 border border-pink-500/20 text-pink-400 text-xs font-medium">Rang
                                        #1 Fille</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-400">Terminale F4-2 — Promotion Terminale</p>
                                <div class="mt-4 flex flex-wrap justify-center lg:justify-start gap-2">
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Moyenne : <span class="font-semibold text-pink-300">19.41</span></div>
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Coef : 4</div>
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Prof : Mme ADJOVI</div>
                                </div>
                                <div class="mt-5 flex flex-wrap justify-center lg:justify-start gap-2.5">
                                    <button
                                        class="h-10 px-5 rounded-xl bg-pink-600 hover:bg-pink-500 text-sm font-medium transition-all active:scale-[0.97]">Voir
                                        profil</button>
                                    <button
                                        class="h-10 px-5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">Historique
                                        notes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== ÉLÈVES EN DIFFICULTÉ ===================== --}}
        <section class="mb-8">
            <div
                class="rounded-[1.75rem] border border-rose-500/20 bg-slate-900/70 backdrop-blur-xl p-6 shadow-xl shadow-rose-950/10">
                <div class="flex items-center gap-3 mb-6">
                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-400"></span>
                    </span>
                    <h2 class="text-lg font-semibold text-rose-400">Élèves en difficulté</h2>
                </div>

                <div class="space-y-3">
                    @foreach (range(1, 5) as $weak)
                        <div
                            class="rounded-2xl bg-slate-950/60 border border-white/5 p-4 hover:border-rose-500/20 transition-colors">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="font-medium text-white truncate">KOFFI Junior</h3>
                                    <p class="mt-0.5 text-sm text-slate-400">Terminale F2-2</p>
                                </div>
                                <span class="text-rose-400 font-bold text-lg shrink-0">08.42</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
</div>
