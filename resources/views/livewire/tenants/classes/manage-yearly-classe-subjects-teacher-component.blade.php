<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ========== HEADER ========== --}}
        <div class="flex items-start gap-4">
            <a wire:navigate href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                class="mt-1 inline-flex items-center justify-center w-9 h-9 rounded-xl
                      border border-white/[0.08] text-slate-400
                      hover:text-white hover:bg-white/[0.06] transition-all shrink-0">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400/80 mb-1">
                    Organisation pédagogique
                </p>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Gestion des matières & Enseignants de classe
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $classe->name }}
                    · <span class="capitalize">{{ $classe->level }}</span>
                    · <span class="text-indigo-400 font-mono">{{ $this->activeYear?->slug ?? '—' }}</span>
                </p>
            </div>
        </div>

        {{-- ========== ALERTE ========== --}}
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

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- ========== COLONNE GAUCHE ========== --}}
            <div class="space-y-5">

                {{-- Matières disponibles --}}
                <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
                    <div class="px-5 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></div>
                            <div class="min-w-0">
                                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Matières disponibles
                                </h2>
                                <p class="text-[11px] text-slate-600 mt-0.5 truncate">
                                    Niveau <span class="capitalize">{{ $classe->level }}</span>
                                    · {{ $this->subjects->total() }}
                                </p>
                            </div>
                        </div>
                        <div class="relative w-40 shrink-0">
                            <x-lucide-search
                                class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" />
                            <input type="text" wire:model.live.debounce.300ms="subjectSearch"
                                placeholder="Rechercher…"
                                class="w-full h-9 rounded-xl border border-white/[0.08] bg-[#070a12]
                                          pl-8 pr-8 text-xs text-white placeholder:text-slate-600
                                          focus:border-indigo-500/50 focus:outline-none transition-all" />
                            <div wire:loading wire:target="subjectSearch"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 text-indigo-400 animate-spin" />
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-white/[0.04]" wire:loading.class="opacity-50"
                        wire:target="subjectSearch, previousPage, nextPage, gotoPage">

                        @forelse ($this->subjects as $subject)
                            @php
                                $isSelected = $selectedSubjectId === $subject->id;
                                $isAssigned = $this->assignedLinks->contains('subject_id', $subject->id);
                            @endphp
                            <div wire:key="subject-{{ $subject->id }}" wire:click="selectSubject({{ $subject->id }})"
                                wire:loading.class="pointer-events-none"
                                wire:target="selectSubject({{ $subject->id }})"
                                class="flex items-center gap-3 px-5 py-3.5 cursor-pointer transition-all select-none
                                        {{ $isSelected
                                            ? 'bg-indigo-500/10 border-l-2 border-l-indigo-500'
                                            : 'hover:bg-white/[0.02] border-l-2 border-l-transparent' }}">

                                <div wire:loading wire:target="selectSubject({{ $subject->id }})"
                                    class="w-9 h-9 flex items-center justify-center shrink-0">
                                    <x-lucide-loader-2 class="w-4 h-4 text-indigo-400 animate-spin" />
                                </div>

                                <div wire:loading.remove wire:target="selectSubject({{ $subject->id }})"
                                    class="px-1 py-2.5 rounded-xl flex items-center justify-center text-[11px] font-bold
                                            uppercase shrink-0 border
                                            {{ $isSelected
                                                ? 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30'
                                                : ($isAssigned
                                                    ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/25'
                                                    : 'bg-white/[0.04] text-slate-400 border-white/[0.06]') }}">
                                    {{ $subject->code }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-medium truncate
                                              {{ $isSelected ? 'text-white' : 'text-slate-300' }}">
                                        {{ $subject->name }}
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        @if ($subject->code)
                                            <span
                                                class="text-[11px] text-slate-600 font-mono">{{ $subject->code }}</span>
                                        @endif
                                        <span class="text-[11px] text-slate-600 capitalize">{{ $subject->type }}</span>
                                    </div>
                                </div>

                                @if ($isAssigned)
                                    <span
                                        class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium
                                                 bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                                        Assignée
                                    </span>
                                @elseif ($isSelected)
                                    <span
                                        class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium
                                                 bg-indigo-500/15 text-indigo-400 border border-indigo-500/25">
                                        Sélectionnée
                                    </span>
                                @else
                                    <x-lucide-chevron-right class="w-4 h-4 text-slate-600 shrink-0" />
                                @endif
                            </div>
                        @empty
                            <div class="px-5 py-12 text-center text-sm text-slate-500">
                                Aucune matière pour ce niveau
                            </div>
                        @endforelse
                    </div>

                    @if ($this->subjects->hasPages())
                        <div
                            class="px-5 py-3 border-t border-white/[0.05]
                                    flex items-center justify-between gap-3">
                            <span class="text-[11px] text-slate-500">
                                {{ $this->subjects->firstItem() }}–{{ $this->subjects->lastItem() }}
                                / {{ $this->subjects->total() }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                @if (!$this->subjects->onFirstPage())
                                    <button wire:click="previousPage" wire:loading.attr="disabled"
                                        wire:target="previousPage"
                                        class="h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                   bg-white/[0.04] border border-white/[0.08] text-slate-400
                                                   hover:bg-white/[0.08] hover:text-white transition-all disabled:opacity-50">
                                        ← Préc.
                                    </button>
                                @endif
                                <span class="text-[11px] text-slate-500 px-1">
                                    {{ $this->subjects->currentPage() }}/{{ $this->subjects->lastPage() }}
                                </span>
                                @if ($this->subjects->hasMorePages())
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

                {{-- Choix enseignant --}}
                @if ($selectedSubjectId)
                    <section class="rounded-2xl border border-indigo-500/25 bg-indigo-500/[0.03] overflow-hidden">
                        <div class="px-5 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Choisir un enseignant
                                </h2>
                                <p class="text-[11px] text-slate-600 mt-0.5 truncate">
                                    Pour « {{ $this->selectedSubject?->name }} »
                                </p>
                            </div>
                            <div class="relative w-40 shrink-0">
                                <x-lucide-search
                                    class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" />
                                <input type="text" wire:model.live.debounce.300ms="teacherSearch"
                                    placeholder="Rechercher…"
                                    class="w-full h-9 rounded-xl border border-white/[0.08] bg-[#070a12]
                                              pl-8 pr-8 text-xs text-white placeholder:text-slate-600
                                              focus:border-indigo-500/50 focus:outline-none transition-all" />
                                <div wire:loading wire:target="teacherSearch"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 text-indigo-400 animate-spin" />
                                </div>
                            </div>
                        </div>

                        @if ($this->assignedTeacherForSubject)
                            <div
                                class="px-5 py-3 bg-amber-500/10 border-b border-amber-500/20
                                        flex items-start gap-2.5">
                                <x-lucide-alert-triangle class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" />
                                <p class="text-xs text-amber-300">
                                    Déjà assignée à
                                    <strong>{{ $this->assignedTeacherForSubject->teacher?->user?->name }}</strong>.
                                    Retirez-le à droite avant d’en choisir un autre.
                                </p>
                            </div>
                        @endif

                        <div class="divide-y divide-white/[0.04] max-h-64 overflow-y-auto"
                            wire:loading.class="opacity-50" wire:target="teacherSearch">
                            @forelse ($this->availableTeachers as $teacher)
                                @php $alreadyAssigned = (bool) $this->assignedTeacherForSubject; @endphp
                                <div wire:key="teacher-{{ $teacher->id }}"
                                    class="flex items-center gap-3 px-5 py-3
                                            {{ $alreadyAssigned ? 'opacity-40' : '' }}">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white/[0.04] border border-white/[0.06]
                                                flex items-center justify-center text-xs font-bold text-slate-300 shrink-0">
                                        {{ strtoupper(substr($teacher->user?->name ?? $teacher->email, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-white truncate font-medium">
                                            {{ $teacher->user?->name ?? '—' }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 font-mono truncate">
                                            {{ $teacher->identifiant }}
                                        </p>
                                    </div>
                                    <button wire:click="assignTeacher({{ $teacher->id }})"
                                        wire:loading.attr="disabled" wire:target="assignTeacher({{ $teacher->id }})"
                                        @disabled($alreadyAssigned)
                                        class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                   bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                                                   hover:bg-indigo-500 hover:text-white hover:border-indigo-500
                                                   transition-all disabled:opacity-30 disabled:cursor-not-allowed shrink-0">
                                        <span wire:loading.remove wire:target="assignTeacher({{ $teacher->id }})"
                                            class="inline-flex items-center gap-1">
                                            <x-lucide-plus class="w-3.5 h-3.5" /> Assigner
                                        </span>
                                        <span wire:loading wire:target="assignTeacher({{ $teacher->id }})">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                </div>
                            @empty
                                <div class="px-5 py-10 text-center text-sm text-slate-500">
                                    Aucun enseignant avec accès valide cette année
                                </div>
                            @endforelse
                        </div>
                    </section>
                @else
                    <div
                        class="rounded-2xl border border-dashed border-white/[0.08] bg-white/[0.02] py-12 text-center">
                        <div
                            class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-500/10 mb-3">
                            <x-lucide-book-open class="w-6 h-6 text-indigo-400" />
                        </div>
                        <p class="text-sm text-slate-500">Sélectionnez une matière</p>
                        <p class="text-xs text-slate-600 mt-1">Les enseignants s’afficheront ici</p>
                    </div>
                @endif
            </div>

            {{-- ========== COLONNE DROITE : ASSIGNATIONS ========== --}}
            <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden h-fit sticky top-6">
                <div class="px-5 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                Assignations
                                <span class="text-amber-400 font-mono ml-1">{{ $classe?->code }}</span>
                            </h2>
                        </div>
                        <p class="text-[11px] text-slate-600 mt-0.5">
                            {{ $this->assignedLinks->count() }} · {{ $this->activeYear?->slug }}
                        </p>
                    </div>
                    <span
                        class="px-2.5 py-0.5 rounded-md text-[11px] font-bold
                                 bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                        {{ $this->assignedLinks->count() }}
                    </span>
                </div>

                @if ($this->assignedLinks->isEmpty())
                    <div class="px-5 py-14 text-center">
                        <div
                            class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/[0.04] mb-3">
                            <x-lucide-users class="w-6 h-6 text-slate-600" />
                        </div>
                        <p class="text-sm text-slate-500">Aucune assignation</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-white/[0.05]">
                                    <th
                                        class="px-3 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 w-12">
                                        N°</th>
                                    <th
                                        class="px-3 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Matière</th>
                                    <th
                                        class="px-3 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Enseignant</th>
                                    <th
                                        class="px-3 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 w-24">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/[0.04]">
                                @foreach ($this->assignedLinks as $link)
                                    <tr wire:key="link-{{ $link->id }}"
                                        class="hover:bg-white/[0.02] transition-colors">
                                        <td class="px-3 py-3.5 text-center text-xs font-mono text-slate-500">
                                            {{ __zero($loop->iteration) }}
                                        </td>
                                        <td class="px-3 py-3.5">
                                            <p class="text-sm font-medium text-white truncate max-w-[140px]">
                                                {{ $link->subject?->name ?? '—' }}
                                            </p>
                                            @if ($link->coefficient)
                                                <p class="text-[11px] text-slate-600 mt-0.5">
                                                    Coeff. {{ $link->coefficient }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3.5">
                                            <p class="text-sm text-slate-300 truncate max-w-[140px]">
                                                {{ $link->teacher?->getFullName() ?? 'Sans prof' }}
                                            </p>
                                        </td>
                                        <td class="px-3 py-3.5 text-center">
                                            <button wire:click="removeLink({{ $link->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="removeLink({{ $link->id }})"
                                                class="inline-flex items-center gap-1 h-8 px-2 rounded-lg text-[11px] font-medium
                                                           bg-rose-500/15 text-rose-400 border border-rose-500/25
                                                           hover:bg-rose-500 hover:text-white hover:border-rose-500
                                                           transition-all disabled:opacity-40">
                                                <span wire:loading.remove
                                                    wire:target="removeLink({{ $link->id }})"
                                                    class="inline-flex items-center gap-1">
                                                    <x-lucide-x class="w-3.5 h-3.5" />
                                                </span>
                                                <span wire:loading wire:target="removeLink({{ $link->id }})">
                                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

        </div>
    </div>
</div>

