<div class="min-h-screen bg-slate-950 text-slate-100">
    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <div class="flex items-center gap-4">
            <a wire:navigate href="{{ route('tenant.teachers.portal') }}"
                class="rounded-xl border border-slate-700 p-2 text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Gestion des matières</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Associer des matières à un enseignant ·
                    <span class="text-indigo-400 font-medium">{{ $this->activeYear?->slug ?? '—' }}</span>
                </p>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
            <label class="block text-xs font-medium text-slate-400 mb-2">
                Enseignant <span class="text-rose-400">*</span>
            </label>

            @if ($teacherId)
                <div class="flex items-center gap-3 rounded-xl border border-indigo-500/40 bg-indigo-500/10 px-4 py-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-sm font-bold shrink-0">
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
                    <button wire:click="clearTeacher" class="shrink-0 text-slate-500 hover:text-rose-400 transition">
                        <x-lucide-x class="w-4 h-4" />
                    </button>
                </div>
            @else
                <div class="relative">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="teacherSearch"
                            placeholder="Rechercher par nom, email ou matricule..."
                            class="w-full h-11 rounded-xl border border-slate-700 bg-slate-800 pl-10 pr-4 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs">🔍</span>
                        <div wire:loading wire:target="teacherSearch"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                        </div>
                    </div>

                    @if ($showDropdown && $this->teacherResults->isNotEmpty())
                        <div
                            class="absolute z-30 mt-1 w-full rounded-xl border border-slate-700 bg-slate-800 shadow-2xl overflow-hidden">
                            @foreach ($this->teacherResults as $teacher)
                                <button
                                    wire:click="selectTeacher({{ $teacher->id }}, '{{ addslashes($teacher->user?->name ?? $teacher->email) }}')"
                                    wire:loading.attr="disabled" wire:target="selectTeacher"
                                    class="flex w-full items-center gap-3 px-4 py-3 text-left hover:bg-slate-700 transition">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-slate-600 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                        {{ strtoupper(substr($teacher->user?->name ?? $teacher->email, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-white font-medium truncate">
                                            {{ $teacher->user?->name ?? '—' }}</p>
                                        <p class="text-xs text-slate-500 truncate">
                                            {{ $teacher->email }} · <span
                                                class="font-mono">{{ $teacher->identifiant }}</span>
                                        </p>
                                    </div>
                                    <span wire:loading wire:target="selectTeacher" class="shrink-0">
                                        <svg class="animate-spin w-4 h-4 text-indigo-400" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                        </svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    @elseif ($showDropdown && strlen($teacherSearch) >= 2)
                        <div
                            class="absolute z-30 mt-1 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-slate-500">
                            Aucun enseignant trouvé.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        @if ($teacherId)
            <div class="rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="font-semibold text-white text-sm">Matières assignées</h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $this->linkedSubjects->count() }} matière(s) pour {{ $this->activeYear?->slug }}
                        </p>
                    </div>
                    <span
                        class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs">
                        {{ $this->linkedSubjects->count() }}
                    </span>
                </div>

                @if ($this->linkedSubjects->isEmpty())
                    <div class="px-5 py-10 text-center">
                        <p class="text-sm text-slate-500">Aucune matière assignée à cet enseignant.</p>
                        <p class="text-xs text-slate-600 mt-1">Utilisez la liste ci-dessous pour en ajouter.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-800">
                        @foreach ($this->linkedSubjects as $link)
                            <div wire:key="linked-{{ $link->id }}"
                                class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-800/40 transition">

                                <div
                                    class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($link->subject->name, 0, 2)) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $link->subject->name }}</p>
                                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                        @if ($link->subject->code)
                                            <span
                                                class="text-xs text-slate-500 font-mono">{{ $link->subject->code }}</span>
                                        @endif
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full bg-slate-700 text-slate-400 capitalize">
                                            {{ $link->subject->level }}
                                        </span>
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full bg-slate-700 text-slate-400 capitalize">
                                            {{ $link->subject->type }}
                                        </span>
                                    </div>
                                </div>

                                <button wire:click="unlinkSubject({{ $link->subject_id }})"
                                    wire:loading.attr="disabled" wire:target="unlinkSubject({{ $link->subject_id }})"
                                    class="relative inline-flex items-center gap-2 py-2 px-3 rounded-xl bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white text-xs font-medium transition disabled:opacity-40">
                                    <span wire:loading.remove wire:target="unlinkSubject({{ $link->subject_id }})">
                                        <x-lucide-x class="w-3.5 h-3.5 inline -mt-0.5" /> Retirer
                                    </span>
                                    <span wire:loading wire:target="unlinkSubject({{ $link->subject_id }})"
                                        class="inline-flex items-center gap-1.5">
                                        <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                        </svg>
                                        En cours...
                                    </span>
                                </button>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-800 space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="font-semibold text-white text-sm">Matières disponibles</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Cliquez sur « Lier » pour assigner une matière</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-slate-700 text-slate-400 text-xs">
                            {{ $this->availableSubjects->total() }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input type="text" wire:model.live.debounce.300ms="subjectSearch"
                                placeholder="Rechercher une matière..."
                                class="w-full h-10 rounded-xl border border-slate-700 bg-slate-800 pl-9 pr-4 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs">🔍</span>
                            <div wire:loading wire:target="subjectSearch"
                                class="absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="animate-spin w-3.5 h-3.5 text-indigo-400" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                            </div>
                        </div>

                        <select wire:model.live="subjectLevel"
                            class="h-10 px-3 rounded-xl border border-slate-700 bg-slate-800 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                            <option value="">Tous niveaux</option>
                            <option value="primaire">Primaire</option>
                            <option value="secondaire">Secondaire</option>
                            <option value="superieur">Supérieur</option>
                        </select>

                        <select wire:model.live="subjectType"
                            class="h-10 px-3 rounded-xl border border-slate-700 bg-slate-800 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                            <option value="">Tous types</option>
                            @foreach (config('app.subject_types') as $value => $label)
                                <option value="{{ $value }}">{{ ucfirst($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="divide-y divide-slate-800" wire:loading.class="opacity-50"
                    wire:target="subjectSearch, subjectLevel, subjectType, previousPage, nextPage, gotoPage">

                    @forelse ($this->availableSubjects as $subject)
                        @php $linked = $this->isLinked($subject->id); @endphp
                        <div wire:key="subject-{{ $subject->id }}"
                            class="flex items-center gap-4 px-5 py-3.5 transition
                        {{ $linked ? 'bg-emerald-500/5' : 'hover:bg-slate-800/40' }}">
                            <div
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold shrink-0
                        {{ $linked ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-700 text-slate-300' }}">
                                {{ strtoupper(substr($subject->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $subject->name }}</p>
                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                    @if ($subject->code)
                                        <span class="text-xs text-slate-500 font-mono">{{ $subject->code }}</span>
                                    @endif
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-full bg-slate-700/80 text-slate-400 capitalize">
                                        {{ $subject->level }}
                                    </span>
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-full bg-slate-700/80 text-slate-400 capitalize">
                                        {{ $subject->type }}
                                    </span>
                                </div>
                            </div>
                            @if ($linked)
                                <button wire:click="unlinkSubject({{ $subject->id }})" wire:loading.attr="disabled"
                                    wire:target="unlinkSubject({{ $subject->id }}), linkSubject({{ $subject->id }})"
                                    class="relative inline-flex items-center gap-2 py-2 px-3 rounded-xl bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white text-xs font-medium transition disabled:opacity-40 shrink-0">
                                    <span wire:loading.remove wire:target="unlinkSubject({{ $subject->id }})">
                                        <x-lucide-x class="w-3.5 h-3.5 inline -mt-0.5" /> Retirer
                                    </span>
                                    <span wire:loading wire:target="unlinkSubject({{ $subject->id }})"
                                        class="inline-flex items-center gap-1.5">
                                        <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                        </svg>
                                        En cours...
                                    </span>
                                </button>
                            @else
                                <button wire:click="linkSubject({{ $subject->id }})" wire:loading.attr="disabled"
                                    wire:target="linkSubject({{ $subject->id }}), unlinkSubject({{ $subject->id }})"
                                    class="relative inline-flex items-center gap-2 py-2 px-3 rounded-xl bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white text-xs font-medium transition disabled:opacity-40 shrink-0">
                                    <span wire:loading.remove wire:target="linkSubject({{ $subject->id }})">
                                        <x-lucide-plus class="w-3.5 h-3.5 inline -mt-0.5" /> Lier
                                    </span>
                                    <span wire:loading wire:target="linkSubject({{ $subject->id }})"
                                        class="inline-flex items-center gap-1.5">
                                        <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                        </svg>
                                        En cours...
                                    </span>
                                </button>
                            @endif

                        </div>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <p class="text-sm text-slate-500">Aucune matière trouvée.</p>
                            @if ($subjectSearch || $subjectLevel || $subjectType)
                                <button
                                    wire:click="$set('subjectSearch', ''); $set('subjectLevel', ''); $set('subjectType', '')"
                                    class="mt-3 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs transition">
                                    Réinitialiser les filtres
                                </button>
                            @endif
                        </div>
                    @endforelse
                </div>
                @if ($this->availableSubjects->hasPages())
                    <div class="px-5 py-3 border-t border-slate-800 flex items-center justify-between gap-4">
                        <span class="text-xs text-slate-500">
                            {{ $this->availableSubjects->firstItem() }}–{{ $this->availableSubjects->lastItem() }}
                            sur {{ $this->availableSubjects->total() }}
                        </span>
                        <div class="flex items-center gap-2">
                            @if (!$this->availableSubjects->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-8 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs transition disabled:opacity-50">
                                    ← Préc.
                                </button>
                            @endif
                            <span class="text-xs text-slate-400 px-2">
                                Page {{ $this->availableSubjects->currentPage() }} /
                                {{ $this->availableSubjects->lastPage() }}
                            </span>
                            @if ($this->availableSubjects->hasMorePages())
                                <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                    class="h-8 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs transition disabled:opacity-50">
                                    Suiv. →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-900/40 px-8 py-16 text-center">
                <div class="text-4xl mb-4">👨‍🏫</div>
                <p class="text-slate-400 text-sm font-medium">Sélectionnez un enseignant</p>
                <p class="text-slate-600 text-xs mt-1">Les matières assignées et disponibles s'afficheront ici.</p>
            </div>

        @endif

    </div>
</div>

