<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ========== HEADER ========== --}}
        <div class="flex items-start gap-4">
            <a wire:navigate href="{{ route('tenant.teachers.portal') }}"
                class="mt-1 inline-flex items-center justify-center w-9 h-9 rounded-xl
                      border border-white/[0.08] text-slate-400
                      hover:text-white hover:bg-white/[0.06] transition-all shrink-0">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400/80 mb-1">
                    Corps enseignant
                </p>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Gestion des matières
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Associer des matières ·
                    <span class="text-indigo-400 font-mono">{{ $this->activeYear?->slug ?? '—' }}</span>
                </p>
            </div>
        </div>

        {{-- ========== ALERTE ACCÈS ========== --}}
        @php $unaccesses = tenancy()->tenant?->getTeachersWithoutYearlyAccesses(); @endphp
        @if (count($unaccesses))
            <div
                class="rounded-xl border border-rose-500/25 bg-rose-500/10 px-4 py-3
                        flex items-start gap-3 text-sm text-rose-300 animate-pulse">
                <x-lucide-alert-triangle class="w-4 h-4 shrink-0 mt-0.5" />
                <div>
                    <p class="font-medium">
                        {{ __zero(count($unaccesses)) }} enseignant(s) sans accès pour
                        {{ $this->activeYear?->slug ?? '' }}
                    </p>
                    <p class="text-xs text-rose-400/80 mt-0.5">
                        Accordez les accès depuis le portail enseignants avant d’assigner matières ou classes.
                    </p>
                </div>
            </div>
        @endif

        {{-- ========== SÉLECTION ENSEIGNANT ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-3">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Enseignant <span class="text-rose-400">*</span>
                </h2>
            </div>

            @if ($teacherId)
                <div class="flex items-center gap-3 rounded-xl border border-indigo-500/30 bg-indigo-500/10 px-4 py-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-500/30
                                flex items-center justify-center text-sm font-bold text-indigo-300 shrink-0">
                        {{ strtoupper(substr($this->selectedTeacher?->user?->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">
                            {{ $this->selectedTeacher?->user?->name ?? '—' }}
                        </p>
                        <p class="text-xs text-slate-500 truncate">
                            {{ $this->selectedTeacher?->email }}
                            · <span class="font-mono">{{ $this->selectedTeacher?->identifiant }}</span>
                        </p>
                    </div>
                    <button wire:click="clearTeacher"
                        class="w-8 h-8 rounded-lg flex items-center justify-center
                                   text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all shrink-0">
                        <x-lucide-x class="w-4 h-4" />
                    </button>
                </div>
            @else
                <div class="relative">
                    <div class="relative">
                        <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                        <input type="text" wire:model.live.debounce.300ms="teacherSearch"
                            placeholder="Nom, email ou matricule…"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      pl-10 pr-10 text-sm text-white placeholder:text-slate-600
                                      focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                      outline-none transition-all" />
                        <div wire:loading wire:target="teacherSearch"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2">
                            <x-lucide-loader-2 class="w-4 h-4 text-indigo-400 animate-spin" />
                        </div>
                    </div>

                    @if ($showDropdown && $this->teacherResults->isNotEmpty())
                        <div
                            class="absolute z-30 mt-1.5 w-full rounded-xl border border-white/[0.08]
                                    bg-[#0c101c] shadow-2xl overflow-hidden">
                            @foreach ($this->teacherResults as $teacher)
                                <button type="button"
                                    wire:click="selectTeacher({{ $teacher->id }}, '{{ addslashes($teacher->user?->name ?? $teacher->email) }}')"
                                    wire:loading.attr="disabled" wire:target="selectTeacher"
                                    class="flex w-full items-center gap-3 px-4 py-3 text-left
                                               hover:bg-white/[0.04] transition-colors">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white/[0.06] border border-white/[0.08]
                                                flex items-center justify-center text-xs font-bold text-slate-300 shrink-0">
                                        {{ strtoupper(substr($teacher->user?->name ?? $teacher->email, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-white font-medium truncate">
                                            {{ $teacher->user?->name ?? '—' }}
                                        </p>
                                        <p class="text-xs text-slate-500 truncate">
                                            {{ $teacher->email }} ·
                                            <span class="font-mono">{{ $teacher->identifiant }}</span>
                                        </p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @elseif ($showDropdown && strlen($teacherSearch) >= 2)
                        <div
                            class="absolute z-30 mt-1.5 w-full rounded-xl border border-white/[0.08]
                                    bg-[#0c101c] px-4 py-3 text-sm text-slate-500">
                            Aucun enseignant trouvé
                        </div>
                    @endif
                </div>
            @endif
        </section>

        @if ($teacherId)

            {{-- ========== MATIÈRES ASSIGNÉES ========== --}}
            <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
                <div class="px-5 py-4 border-b border-white/[0.05] flex items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                Matières assignées
                            </h2>
                            <p class="text-[11px] text-slate-600 mt-0.5">
                                {{ $this->linkedSubjects->count() }} pour {{ $this->activeYear?->slug }}
                            </p>
                        </div>
                    </div>
                    <span
                        class="px-2.5 py-0.5 rounded-md text-[11px] font-bold
                                 bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                        {{ $this->linkedSubjects->count() }}
                    </span>
                </div>

                @if ($this->linkedSubjects->isEmpty())
                    <div class="px-5 py-12 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/[0.04] mb-3">
                            <x-lucide-book-open class="w-6 h-6 text-slate-600" />
                        </div>
                        <p class="text-sm text-slate-500">Aucune matière assignée</p>
                        <p class="text-xs text-slate-600 mt-1">Ajoutez-en depuis la liste ci-dessous</p>
                    </div>
                @else
                    <div class="divide-y divide-white/[0.04]">
                        @foreach ($this->linkedSubjects as $link)
                            <div wire:key="linked-{{ $link->id }}"
                                class="flex items-center gap-3 px-5 py-3.5 hover:bg-white/[0.02] transition-colors">
                                <div
                                    class="w-11 h-11 rounded-xl bg-emerald-500/15 border border-emerald-500/25
                                            flex items-center justify-center text-[11px] font-bold text-emerald-400
                                            uppercase shrink-0">
                                    {{ $link->subject->code ?? cutter($link->subject->name, 3) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">
                                        {{ $link->subject->name }}
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                        <span
                                            class="px-1.5 py-0.5 rounded text-[10px] capitalize
                                                     bg-white/[0.04] text-slate-400 border border-white/[0.06]">
                                            {{ $link->subject->level }}
                                        </span>
                                        <span
                                            class="px-1.5 py-0.5 rounded text-[10px] capitalize
                                                     bg-white/[0.04] text-slate-400 border border-white/[0.06]">
                                            {{ $link->subject->type }}
                                        </span>
                                    </div>
                                </div>
                                <button wire:click="unlinkSubject({{ $link->subject_id }})"
                                    wire:loading.attr="disabled" wire:target="unlinkSubject({{ $link->subject_id }})"
                                    class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                               bg-rose-500/15 text-rose-400 border border-rose-500/25
                                               hover:bg-rose-500 hover:text-white hover:border-rose-500
                                               transition-all disabled:opacity-40 shrink-0">
                                    <span wire:loading.remove wire:target="unlinkSubject({{ $link->subject_id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-x class="w-3.5 h-3.5" /> Retirer
                                    </span>
                                    <span wire:loading wire:target="unlinkSubject({{ $link->subject_id }})">
                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                    </span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- ========== MATIÈRES DISPONIBLES ========== --}}
            <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
                <div class="px-5 py-4 border-b border-white/[0.05] space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                            <div>
                                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Matières disponibles
                                </h2>
                                <p class="text-[11px] text-slate-600 mt-0.5">Cliquez sur « Lier » pour assigner</p>
                            </div>
                        </div>
                        <span
                            class="px-2.5 py-0.5 rounded-md text-[11px] font-bold
                                     bg-white/[0.04] text-slate-400 border border-white/[0.06]">
                            {{ $this->availableSubjects->total() }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-1">
                            <x-lucide-search
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" />
                            <input type="text" wire:model.live.debounce.300ms="subjectSearch"
                                placeholder="Rechercher une matière…"
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                          pl-9 pr-9 text-sm text-white placeholder:text-slate-600
                                          focus:border-indigo-500/50 focus:outline-none transition-all" />
                            <div wire:loading wire:target="subjectSearch"
                                class="absolute right-3 top-1/2 -translate-y-1/2">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 text-indigo-400 animate-spin" />
                            </div>
                        </div>
                        <select wire:model.live="subjectLevel"
                            class="h-10 px-3 rounded-xl border border-white/[0.08] bg-[#070a12]
                                       text-sm text-slate-300 focus:border-indigo-500/50 focus:outline-none transition-all">
                            <option value="">Tous niveaux</option>
                            <option value="primaire">Primaire</option>
                            <option value="secondaire">Secondaire</option>
                            <option value="superieur">Supérieur</option>
                        </select>
                        <select wire:model.live="subjectType"
                            class="h-10 px-3 rounded-xl border border-white/[0.08] bg-[#070a12]
                                       text-sm text-slate-300 focus:border-indigo-500/50 focus:outline-none transition-all">
                            <option value="">Tous types</option>
                            @foreach (config('app.subject_types') as $value => $label)
                                <option value="{{ $value }}">{{ ucfirst($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="divide-y divide-white/[0.04]" wire:loading.class="opacity-50"
                    wire:target="subjectSearch, subjectLevel, subjectType, previousPage, nextPage, gotoPage">

                    @forelse ($this->availableSubjects as $subject)
                        @php $linked = $this->isLinked($subject->id); @endphp
                        <div wire:key="subject-{{ $subject->id }}"
                            class="flex items-center gap-3 px-5 py-3.5 transition-colors
                                    {{ $linked ? 'bg-emerald-500/[0.04]' : 'hover:bg-white/[0.02]' }}">
                            <div
                                class="py-3 px-1.5 rounded-xl flex items-center justify-center text-[11px] font-bold
                                        uppercase shrink-0 border
                                        {{ $linked
                                            ? 'bg-emerald-500/15 text-emerald-400 border-emerald-600/60'
                                            : 'bg-white/[0.04] text-slate-400 border-white/[0.06]' }}">
                                {{ $subject->code ?? cutter($subject->name, 3) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm font-medium truncate
                                          {{ $linked ? 'text-emerald-300' : 'text-white' }}">
                                    {{ $subject->name }}
                                </p>
                                <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[10px] capitalize
                                                 bg-white/[0.04] text-slate-500 border border-white/[0.06]">
                                        {{ $subject->level }}
                                    </span>
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[10px] capitalize
                                                 bg-white/[0.04] text-slate-500 border border-white/[0.06]">
                                        {{ $subject->type }}
                                    </span>
                                </div>
                            </div>

                            @if ($linked)
                                <button wire:click="unlinkSubject({{ $subject->id }})" wire:loading.attr="disabled"
                                    wire:target="unlinkSubject({{ $subject->id }}), linkSubject({{ $subject->id }})"
                                    class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                               bg-rose-500/15 text-rose-400 border border-rose-500/25
                                               hover:bg-rose-500 hover:text-white hover:border-rose-500
                                               transition-all disabled:opacity-40 shrink-0">
                                    <span wire:loading.remove wire:target="unlinkSubject({{ $subject->id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-x class="w-3.5 h-3.5" /> Retirer
                                    </span>
                                    <span wire:loading wire:target="unlinkSubject({{ $subject->id }})">
                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                    </span>
                                </button>
                            @else
                                <button wire:click="linkSubject({{ $subject->id }})" wire:loading.attr="disabled"
                                    wire:target="linkSubject({{ $subject->id }}), unlinkSubject({{ $subject->id }})"
                                    class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                               bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                                               hover:bg-indigo-500 hover:text-white hover:border-indigo-500
                                               transition-all disabled:opacity-40 shrink-0">
                                    <span wire:loading.remove wire:target="linkSubject({{ $subject->id }})"
                                        class="inline-flex items-center gap-1">
                                        <x-lucide-plus class="w-3.5 h-3.5" /> Lier
                                    </span>
                                    <span wire:loading wire:target="linkSubject({{ $subject->id }})">
                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                    </span>
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <p class="text-sm text-slate-500">Aucune matière trouvée</p>
                            @if ($subjectSearch || $subjectLevel || $subjectType)
                                <button
                                    wire:click="$set('subjectSearch', ''); $set('subjectLevel', ''); $set('subjectType', '')"
                                    class="mt-3 h-8 px-3 rounded-lg text-xs font-medium
                                               bg-white/[0.04] border border-white/[0.08] text-slate-400
                                               hover:bg-white/[0.08] hover:text-white transition-all">
                                    Réinitialiser les filtres
                                </button>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if ($this->availableSubjects->hasPages())
                    <div
                        class="px-5 py-3 border-t border-white/[0.05]
                                flex items-center justify-between gap-4">
                        <span class="text-[11px] text-slate-500">
                            {{ $this->availableSubjects->firstItem() }}–{{ $this->availableSubjects->lastItem() }}
                            sur {{ $this->availableSubjects->total() }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            @if (!$this->availableSubjects->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-8 px-2.5 rounded-lg text-[11px] font-medium
                                               bg-white/[0.04] border border-white/[0.08] text-slate-400
                                               hover:bg-white/[0.08] hover:text-white transition-all disabled:opacity-50">
                                    ← Préc.
                                </button>
                            @endif
                            <span class="text-[11px] text-slate-500 px-2">
                                {{ $this->availableSubjects->currentPage() }} /
                                {{ $this->availableSubjects->lastPage() }}
                            </span>
                            @if ($this->availableSubjects->hasMorePages())
                                <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                    class="h-8 px-2.5 rounded-lg text-[11px] font-medium
                                               bg-white/[0.04] border border-white/[0.08] text-slate-400
                                               hover:bg-white/[0.08] hover:text-white transition-all disabled:opacity-50">
                                    Suiv. →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </section>
        @else
            {{-- Empty state --}}
            <div class="rounded-2xl border border-dashed border-white/[0.08] bg-white/[0.02] py-16 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-500/10 mb-4">
                    <x-lucide-user-search class="w-7 h-7 text-indigo-400" />
                </div>
                <p class="text-sm font-medium text-slate-400">Sélectionnez un enseignant</p>
                <p class="text-xs text-slate-600 mt-1">
                    Les matières assignées et disponibles s’afficheront ici
                </p>
            </div>
        @endif

    </div>
</div>

