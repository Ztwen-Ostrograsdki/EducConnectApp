<div class="min-h-screen bg-slate-950 text-slate-100">
    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <div class="flex items-center gap-4">
            <a wire:navigate href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                class="rounded-xl border border-slate-700 p-2 text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Gestion des matières</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    {{ $classe->name }}
                    · <span class="capitalize">{{ $classe->level }}</span>
                    · <span class="text-indigo-400 font-medium">{{ $this->activeYear?->slug ?? '—' }}</span>
                </p>
            </div>
        </div>
        @php
            $unaccesses = tenancy()->tenant?->getTeachersWithoutYearlyAccesses();
        @endphp
        @if (count($unaccesses))
            <div
                class="rounded-2xl border border-red-800 bg-red-900/30 p-2 font-mono text-sm animate-pulse text-red-400">
                <span>{{ __zero(count($unaccesses)) }} enseigant(s) sont sans accès pour cette année scolaire
                    {{ $this->activeYear?->slug ?? '' }}</span>
                <p>
                    Veuillez vous rendre sur le portail des enseignants pour accorder les accès. Autrement, vous ne
                    pourriez ni définir leurs matières ni leur attribuer de classe!
                </p>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

            <div class="space-y-5">

                <div class="rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden">

                    <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="font-semibold text-white text-sm">Matières disponibles</h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Niveau <span class="capitalize text-slate-400">{{ $classe->level }}</span>
                                · {{ $this->subjects->total() }} matière(s)
                            </p>
                        </div>
                        <div class="relative w-44">
                            <input type="text" wire:model.live.debounce.300ms="subjectSearch"
                                placeholder="Rechercher..."
                                class="w-full h-9 rounded-xl border border-slate-700 bg-slate-800 pl-8 pr-3 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs">🔍</span>
                            <div wire:loading wire:target="subjectSearch"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2">
                                <svg class="animate-spin w-3.5 h-3.5 text-indigo-400" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-800" wire:loading.class="opacity-50"
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
                                {{ $isSelected ? 'bg-indigo-500/10 border-l-2 border-l-indigo-500' : 'hover:bg-slate-800/60' }}">

                                <div wire:loading wire:target="selectSubject({{ $subject->id }})"
                                    class="w-8 h-8 flex items-center justify-center shrink-0">
                                    <svg class="animate-spin w-4 h-4 text-indigo-400" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                </div>

                                <div wire:loading.remove wire:target="selectSubject({{ $subject->id }})"
                                    class="p-2 rounded-xl uppercase flex items-center justify-center text-xs font-bold shrink-0
                                    {{ $isSelected ? 'bg-indigo-500/30 text-indigo-300' : ($isAssigned ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-700 text-slate-300') }}">
                                    {{ $subject->code }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-medium truncate
                                    {{ $isSelected ? 'text-white' : 'text-slate-300' }}">
                                        {{ $subject->name }}
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                        @if ($subject->code)
                                            <span class="text-xs text-slate-500 font-mono">{{ $subject->code }}</span>
                                        @endif
                                        <span class="text-xs text-slate-600 capitalize">{{ $subject->type }}</span>
                                    </div>
                                </div>

                                @if ($isAssigned)
                                    <span
                                        class="shrink-0 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-xs border border-emerald-500/20">
                                        ✓ Assignée
                                    </span>
                                @elseif ($isSelected)
                                    <span
                                        class="shrink-0 px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 text-xs border border-indigo-500/20">
                                        Sélectionnée
                                    </span>
                                @else
                                    <x-lucide-chevron-right class="w-4 h-4 text-slate-600 shrink-0" />
                                @endif

                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-slate-500">
                                Aucune matière disponible pour ce niveau.
                            </div>
                        @endforelse
                    </div>

                    @if ($this->subjects->hasPages())
                        <div class="px-5 py-3 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-xs text-slate-500">
                                {{ $this->subjects->firstItem() }}–{{ $this->subjects->lastItem() }}
                                sur {{ $this->subjects->total() }}
                            </span>
                            <div class="flex items-center gap-2">
                                @if (!$this->subjects->onFirstPage())
                                    <button wire:click="previousPage" wire:loading.attr="disabled"
                                        wire:target="previousPage"
                                        class="h-8 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs transition disabled:opacity-50">
                                        ← Préc.
                                    </button>
                                @endif
                                <span class="text-xs text-slate-400">
                                    {{ $this->subjects->currentPage() }} / {{ $this->subjects->lastPage() }}
                                </span>
                                @if ($this->subjects->hasMorePages())
                                    <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                        class="h-8 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs transition disabled:opacity-50">
                                        Suiv. →
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                @if ($selectedSubjectId)
                    <div class="rounded-2xl border border-indigo-500/30 bg-slate-900 overflow-hidden">

                        <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="font-semibold text-white text-sm">
                                    Choisir un enseignant
                                </h2>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Pour « {{ $this->selectedSubject?->name }} »
                                </p>
                            </div>
                            <div class="relative w-44">
                                <input type="text" wire:model.live.debounce.300ms="teacherSearch"
                                    placeholder="Rechercher..."
                                    class="w-full h-9 rounded-xl border border-slate-700 bg-slate-800 pl-8 pr-3 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                                <span
                                    class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs">🔍</span>
                                <div wire:loading wire:target="teacherSearch"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin w-3.5 h-3.5 text-indigo-400" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        @if ($this->assignedTeacherForSubject)
                            <div class="px-5 py-3 bg-amber-500/5 border-b border-amber-500/20 flex items-center gap-3">
                                <x-lucide-alert-triangle class="w-4 h-4 text-amber-400 shrink-0" />
                                <p class="text-xs text-amber-400">
                                    Cette matière est déjà assignée à
                                    <strong>{{ $this->assignedTeacherForSubject->teacher?->user?->name }}</strong>.
                                    Retirez-le depuis la section droite avant d'en choisir un autre.
                                </p>
                            </div>
                        @endif

                        <div class="divide-y divide-slate-800 max-h-64 overflow-y-auto"
                            wire:loading.class="opacity-50" wire:target="teacherSearch">

                            @forelse ($this->availableTeachers as $teacher)
                                @php $alreadyAssigned = (bool) $this->assignedTeacherForSubject; @endphp
                                <div wire:key="teacher-{{ $teacher->id }}"
                                    class="flex items-center gap-3 px-5 py-3 {{ $alreadyAssigned ? 'opacity-40' : '' }}">

                                    <div
                                        class="w-8 h-8 rounded-xl bg-slate-700 text-slate-300 flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($teacher->user?->name ?? $teacher->email, 0, 1)) }}
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-white truncate font-medium">
                                            {{ $teacher->user?->name ?? '—' }}
                                        </p>
                                        <p class="text-xs text-slate-500 truncate font-mono">
                                            {{ $teacher->identifiant }}
                                        </p>
                                    </div>

                                    <button wire:click="assignTeacher({{ $teacher->id }})"
                                        wire:loading.attr="disabled" wire:target="assignTeacher({{ $teacher->id }})"
                                        @disabled($alreadyAssigned)
                                        class="relative inline-flex items-center gap-1.5 py-2 px-3 rounded-xl bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white text-xs font-medium transition disabled:opacity-30 disabled:cursor-not-allowed shrink-0">
                                        <span wire:loading.remove wire:target="assignTeacher({{ $teacher->id }})">
                                            <x-lucide-plus class="w-3.5 h-3.5 inline -mt-0.5" /> Assigner
                                        </span>
                                        <span wire:loading wire:target="assignTeacher({{ $teacher->id }})"
                                            class="inline-flex items-center gap-1.5">
                                            <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v8z" />
                                            </svg>
                                            En cours...
                                        </span>
                                    </button>
                                </div>
                            @empty
                                <div class="px-5 py-8 text-center text-sm text-slate-500">
                                    Aucun enseignant disponible avec un accès valide cette année.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @else
                    <div
                        class="rounded-2xl border border-dashed border-slate-700 bg-slate-900/40 px-6 py-10 text-center">
                        <div class="text-3xl mb-3">📚</div>
                        <p class="text-sm text-slate-500">Sélectionnez une matière</p>
                        <p class="text-xs text-slate-600 mt-1">Les enseignants disponibles s'afficheront ici</p>
                    </div>
                @endif

            </div>

            <div
                class="rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden h-fit sticky top-6 font-semibold space-x-3">

                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between gap-4">
                    <div>
                        <h2 class=" text-slate-400 text-base font-semibold">Profs choisis par matière de la
                            classe
                            <span class="text-orange-500 font-semibold">
                                {{ $classe?->code }}</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $this->assignedLinks->count() }} assignation(s) · {{ $this->activeYear?->slug }}
                        </p>
                    </div>
                    <span
                        class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs">
                        {{ $this->assignedLinks->count() }}
                    </span>
                </div>

                @if ($this->assignedLinks->isEmpty())
                    <div class="px-5 py-12 text-center">
                        <div class="text-3xl mb-3">👩‍🏫</div>
                        <p class="text-sm text-slate-500">Aucune matière assignée pour l'instant.</p>
                    </div>
                @else
                    <table class="divide-y divide-slate-800 w-full z-table-border ">
                        <thead class="bg-slate-950 ">

                            <tr>

                                <th class="px-3 py-4 text-center text-sm text-slate-400">
                                    N°
                                </th>
                                <th class="px-3 py-4 text-center text-sm text-slate-400">
                                    Matière
                                </th>

                                <th class="px-3 py-4 text-center text-sm text-slate-400">
                                    Enseignant
                                </th>

                                <th class="px-6 py-4 text-center text-sm text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>
                        @foreach ($this->assignedLinks as $link)
                            <tr wire:key="link-{{ $link->id }}"
                                class=" px-3 py-2 hover:bg-slate-800/30 transition w-full text-slate-500">

                                <td class="px-3 py-5 whitespace-nowrap text-center">

                                    {{ __zero($loop->iteration) }}

                                </td>

                                <td class=" truncate">

                                    <div class="flex flex-col items-center text-xs font-bold ">
                                        <p class="text-sm font-semibold text-orange-600">
                                            {{ $link->subject?->name ?? '—' }}
                                        </p>
                                        <div class="flex flex-col text-left w-full justify-start px-3 gap-x-2">
                                            @if ($link->coefficient)
                                                <p class="text-xs text-slate-600 mt-1">Coeff. {{ $link->coefficient }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                </td>

                                <td class=" min-w-0 truncate text-center text-sm">

                                    {{ $link->teacher?->getFullName() ?? 'Sans prof' }}

                                </td>

                                <td class="truncate p-2">
                                    <button wire:click="removeLink({{ $link->id }})" wire:loading.attr="disabled"
                                        wire:target="removeLink({{ $link->id }})"
                                        class="relative inline-flex items-center gap-1.5 py-1.5 px-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white text-xs font-medium transition disabled:opacity-40 shrink-0 justify-center w-full border border-red-700">
                                        <span wire:loading.remove wire:target="removeLink({{ $link->id }})">
                                            <x-lucide-x class="w-3.5 h-3.5 inline -mt-0.5" /> Retirer
                                        </span>
                                        <span wire:loading wire:target="removeLink({{ $link->id }})"
                                            class="inline-flex items-center gap-1.5">
                                            <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v8z" />
                                            </svg>
                                            En cours...
                                        </span>
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </table>
                @endif

            </div>

        </div>

    </div>
</div>

