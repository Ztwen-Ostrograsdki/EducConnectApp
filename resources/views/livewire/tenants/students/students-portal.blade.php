<div class="w-full overflow-x-hidden bg-[#0b0f19] min-h-screen">

    {{-- Global loading --}}
    <div wire:loading
        wire:target="gender,status,department,city,clearFilters,subject_id,classe_id,promotion_id,filiar_id,forceDeleteTeachers,previousPage,nextPage,gotoPage"
        class="fixed inset-0 z-[200] flex items-center justify-center bg-[#0b0f19]/70">
        <div class="flex flex-col items-center gap-3 text-slate-400">
            <svg class="animate-spin w-8 h-8 text-violet-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <span class="text-sm font-mono">Chargement…</span>
        </div>
    </div>

    <div class="mx-auto w-full max-w-[1850px] px-4 sm:px-6 lg:px-8 py-8">

        {{-- ===================== HEADER ===================== --}}
        <header class="mb-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-violet-400/80 mb-2">
                        Administration
                    </p>
                    <h1 class="text-3xl sm:text-4xl font-semibold text-white tracking-tight">
                        Portail des apprenants
                    </h1>
                    <p class="mt-2 text-slate-400 text-sm max-w-lg">
                        Vue globale des apprenants de l’établissement
                    </p>

                    {{-- Stats --}}
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-500/10 text-indigo-300 border border-indigo-500/25 text-xs font-medium">
                            {{ $this->stats['students'] }} apprenants
                        </span>
                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/25 text-xs font-medium">
                            {{ $this->stats['students_in_classe'] }} avec classe
                        </span>
                        @if ($this->stats['students'] - $this->stats['students_in_classe'])
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full bg-rose-500/10 text-rose-300 border border-rose-500/25 text-xs font-medium animate-pulse">
                                {{ $this->stats['students'] - $this->stats['students_in_classe'] }} sans classe
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        {{-- ===================== FILTERS ===================== --}}
        <section class="mb-8">
            <div class="rounded-2xl bg-[#121826] border border-white/5 p-5">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input wire:model.live.debounce.400ms="search" type="text"
                                placeholder="Rechercher un apprenant…"
                                class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500">🔍</span>
                        </div>
                        <button wire:click="clearFilters"
                            class="h-11 px-5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all shrink-0">
                            <span wire:loading.remove wire:target="clearFilters" class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4" />
                                Réinitialiser
                            </span>
                            <span wire:loading wire:target="clearFilters" class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                Rechargement…
                            </span>
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-2.5">
                        <select wire:model.live="classe_id"
                            class="h-10 min-w-[160px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les classes</option>
                            @foreach ($this->classes as $cl)
                                <option value="{{ $cl->id }}">{{ $cl->code ?: $cl->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filiar_id"
                            class="h-10 min-w-[150px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les filières</option>
                            @foreach ($this->filiars as $f)
                                <option value="{{ $f->id }}">{{ $f->code ?: $f->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="promotion_id"
                            class="h-10 min-w-[160px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les promotions</option>
                            @foreach ($this->promotions as $promo)
                                <option value="{{ $promo->id }}">{{ $promo->code ?: $promo->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="department"
                            class="h-10 min-w-[130px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Département</option>
                            @foreach ($this->departments as $dp => $dpv)
                                <option value="{{ $dpv }}">{{ $dpv }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="city"
                            class="h-10 min-w-[120px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Ville</option>
                            @foreach ($this->cities as $ct => $ctv)
                                <option value="{{ $ctv }}">{{ $ctv }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="gender"
                            class="h-10 min-w-[100px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Sexe</option>
                            @foreach ($this->genders as $gk => $gdr)
                                <option value="{{ $gk }}">{{ $gdr }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="status"
                            class="h-10 min-w-[140px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Tout statut</option>
                            <option value="actifs">Actifs</option>
                            <option value="desactives">Désactivés</option>
                            <option value="ayant de classe">Ayant de classe</option>
                            <option value="ayant abandonés">Déclarés abandons</option>
                            <option value="sans classe">Sans classe</option>
                            <option value="de la corbeille">Corbeille</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== ACTIONS BAR ===================== --}}
        <section class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex flex-wrap gap-2">
                    <a wire:navigate href="{{ route('tenant.students.create') }}"
                        class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-600 hover:bg-violet-500 text-white transition-all inline-flex items-center gap-1.5">
                        <x-lucide-user-plus class="w-3.5 h-3.5" />
                        Ajouter apprenants
                    </a>
                    <a wire:navigate href="{{ route('tenant.students.docs') }}"
                        class="h-9 px-3.5 rounded-lg text-xs font-medium bg-sky-500/10 text-sky-300 border border-sky-500/20 hover:bg-sky-500/20 transition-all inline-flex items-center gap-1.5">
                        <x-lucide-file class="w-3.5 h-3.5" />
                        Documents PDF/Excel
                    </a>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($this->hasPrintSessionConfig)
                        <button wire:click="printStudentsList"
                            class="h-9 px-3.5 rounded-lg text-xs font-medium bg-sky-500/10 text-sky-300 border border-sky-500/20 hover:bg-sky-500/20 transition-all">
                            <span wire:loading.remove wire:target="printStudentsList"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-save class="w-3.5 h-3.5" /> Exporter PDF
                            </span>
                            <span wire:loading wire:target="printStudentsList" class="inline-flex items-center gap-1.5">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> …
                            </span>
                        </button>
                    @endif
                    <a href="{{ route('tenant.students.print.list') }}"
                        class="h-9 px-3.5 rounded-lg text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                        <x-lucide-eye class="w-3.5 h-3.5" />
                        Aperçu
                    </a>
                    <a wire:navigate href="{{ route('tenant.students.print.configuration') }}"
                        class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/10 text-violet-300 border border-violet-500/20 hover:bg-violet-500/20 transition-all inline-flex items-center gap-1.5">
                        <x-lucide-printer class="w-3.5 h-3.5" />
                        Génération PDF
                    </a>
                </div>
            </div>
        </section>

        {{-- ===================== BULK ===================== --}}
        <section class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button wire:click="reactivateStudents" wire:loading.attr="disabled" wire:target="reactivateStudents"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/10 text-violet-300 border border-violet-500/20 hover:bg-violet-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="reactivateStudents"
                        class="inline-flex items-center gap-1.5">
                        <x-lucide-lock-keyhole-open class="w-3.5 h-3.5" /> Réactiver les apprenants
                    </span>
                    <span wire:loading wire:target="reactivateStudents" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> …
                    </span>
                </button>
                <button wire:click="restoreStudents" wire:loading.attr="disabled" wire:target="restoreStudents"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="restoreStudents" class="inline-flex items-center gap-1.5">
                        <x-lucide-recycle class="w-3.5 h-3.5" /> Restaurer les apprenants
                    </span>
                    <span wire:loading wire:target="restoreStudents" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> …
                    </span>
                </button>
            </div>
        </section>

        {{-- ===================== LIST ===================== --}}
        <section class="relative mb-16" wire:loading.class="opacity-40" wire:target="search">
            @if (count($this->students))
                <div class="space-y-4">
                    @foreach ($this->students as $student)
                        @php
                            $orderNumber = $this->students->firstItem() + $loop->iteration - 1;
                        @endphp

                        <article wire:key="liste-apprenants-{{ $student->id }}"
                            class="rounded-2xl bg-[#121826] border border-white/5 hover:border-violet-500/20 transition-all overflow-hidden">
                            <div class="p-5 sm:p-6">
                                <div class="flex flex-col xl:flex-row gap-6">

                                    {{-- N° + IDENTITY --}}
                                    <div class="flex items-center gap-4 min-w-0 xl:w-[320px] shrink-0">
                                        <div class="flex items-start pt-1">
                                            <span
                                                class="inline-flex items-center justify-center px-2 h-9 rounded-xl bg-violet-500/15 border border-violet-500/25 text-violet-300 text-xs font-bold tabular-nums">
                                                N° {{ __zero($orderNumber) }}
                                            </span>
                                        </div>

                                        <a href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                            class="flex gap-3 min-w-0 flex-1 group">
                                            <img src="{{ $student->profil_photo_url() }}" alt=""
                                                class="w-14 h-14 rounded-2xl object-cover ring-2 ring-white/10 group-hover:ring-violet-500/40 transition-all shrink-0">
                                            <div class="min-w-0">
                                                <h3
                                                    class="font-semibold text-white group-hover:text-violet-300 transition-colors truncate">
                                                    {{ $student->getFullName() }}
                                                </h3>
                                                @if ($student->email)
                                                    <p
                                                        class="mt-1 text-xs text-slate-400 flex items-center gap-1.5 truncate">
                                                        <x-lucide-mail class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                                        {{ $student->email }}
                                                    </p>
                                                @endif
                                                @if ($student->contacts)
                                                    <p
                                                        class="mt-0.5 text-xs text-slate-400 flex items-center gap-1.5 truncate">
                                                        <x-lucide-phone class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                                        {{ $student->contacts }}
                                                    </p>
                                                @endif
                                            </div>
                                        </a>
                                    </div>

                                    {{-- CLASSE + STATUT --}}
                                    <div
                                        class="flex-1 min-w-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-6 flex flex-wrap items-start gap-4">
                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">
                                                Classe</p>
                                            @if ($student->currentClasse() && $student->currentClasse()->classe)
                                                @php $rel = $student->currentClasse()->classe; @endphp
                                                <a wire:navigate
                                                    href="{{ route('tenant.classe.profil', ['classe_slug' => $rel->slug]) }}"
                                                    class="inline-flex px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 text-xs font-mono hover:bg-emerald-500/20 transition-all">
                                                    {{ $rel->code ?: $rel->name }}
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-600 italic">
                                                    Pas de classe · {{ $this->activeYear?->slug }}
                                                </span>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">
                                                Statut</p>
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[11px] font-medium border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                Actif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- ACTIONS --}}
                                    <div
                                        class="xl:w-[180px] shrink-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-5 flex flex-col gap-2">
                                        <button
                                            wire:click="{{ $student->is_active ? 'desactivateStudent(' . $student->id . ')' : 'activateStudent(' . $student->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="activateStudent({{ $student->id }}), desactivateStudent({{ $student->id }})"
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ $student->is_active
                                                           ? 'bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-300'
                                                           : 'bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-300' }}">
                                            <span wire:loading.remove
                                                wire:target="activateStudent({{ $student->id }}), desactivateStudent({{ $student->id }})"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($student->is_active)
                                                    <x-lucide-user-x class="w-3.5 h-3.5" /> Désactiver
                                                @else
                                                    <x-lucide-user-check class="w-3.5 h-3.5" /> Activer
                                                @endif
                                            </span>
                                            <span wire:loading
                                                wire:target="activateStudent({{ $student->id }}), desactivateStudent({{ $student->id }})">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        <button
                                            wire:click="{{ $student->deleted_at ? 'restoreStudent(' . $student->id . ')' : 'deleteStudent(' . $student->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="deleteStudent({{ $student->id }}), restoreStudent({{ $student->id }})"
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ $student->deleted_at
                                                           ? 'bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-300'
                                                           : 'bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300' }}">
                                            <span wire:loading.remove
                                                wire:target="deleteStudent({{ $student->id }}), restoreStudent({{ $student->id }})"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($student->deleted_at)
                                                    <x-lucide-recycle class="w-3.5 h-3.5" /> Restaurer
                                                @else
                                                    <x-lucide-trash class="w-3.5 h-3.5" /> Corbeille
                                                @endif
                                            </span>
                                            <span wire:loading
                                                wire:target="deleteStudent({{ $student->id }}), restoreStudent({{ $student->id }})">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        @if ($student->deleted_at)
                                            <button wire:click="forceDeleteStudent({{ $student->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="forceDeleteStudent({{ $student->id }})"
                                                class="h-9 px-3 rounded-lg bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                                <span wire:loading.remove
                                                    wire:target="forceDeleteStudent({{ $student->id }})"
                                                    class="inline-flex items-center gap-1.5">
                                                    <x-lucide-trash-2 class="w-3.5 h-3.5" /> Suppr. déf.
                                                </span>
                                                <span wire:loading
                                                    wire:target="forceDeleteStudent({{ $student->id }})">
                                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($this->students->hasPages())
                    <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-xs text-slate-500">
                            Affichage {{ $this->students->firstItem() }} à {{ $this->students->lastItem() }}
                            sur {{ $this->students->total() }} apprenants
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$this->students->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                    ← Précédent
                                </button>
                            @endif
                            @foreach ($this->students->getUrlRange(1, $this->students->lastPage()) as $page => $url)
                                <button @disabled($page === $this->students->currentPage()) wire:click="gotoPage({{ $page }})"
                                    class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                               {{ $page === $this->students->currentPage()
                                                   ? 'bg-violet-600 text-white'
                                                   : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                            @if ($this->students->hasMorePages())
                                <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                    Suivant →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-2xl bg-[#121826] border border-white/5 py-20 text-center">
                    <span class="text-4xl mb-4 block">🎓</span>
                    <p class="text-slate-500 text-sm mb-4">Aucune donnée trouvée</p>
                    @if ($search || $gender || $serial_id || $status || $classe_id || $promotion_id || $filiar_id)
                        <button wire:click="clearFilters"
                            class="h-9 px-4 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all">
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            @endif
        </section>

    </div>
</div>

