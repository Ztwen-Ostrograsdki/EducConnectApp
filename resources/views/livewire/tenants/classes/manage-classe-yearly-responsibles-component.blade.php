<div class="min-h-screen bg-slate-950 text-slate-100">
    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <div class="flex items-center gap-4">
            <a wire:navigate href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                class="rounded-xl border border-slate-700 p-2 text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Responsables de la classe</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    {{ $classe->name }}
                    @if ($this->activeYear)
                        · <span class="text-indigo-400">{{ $this->activeYear->slug }}</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div
                class="rounded-2xl border {{ $principalId ? 'border-indigo-500/40 bg-indigo-500/5' : 'border-slate-700 bg-slate-900' }} p-4 transition-all">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Prof principal</p>
                @if ($principalId)
                    @php
                        $pt = $this->teachers->firstWhere('id', $principalId);
                    @endphp
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xs font-bold shrink-0">
                            {{ strtoupper(substr($pt?->user?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white truncate">
                                {{ $pt?->getFullName() ?? 'nom non défini' }}</p>
                        </div>
                        <button wire:click="togglePrincipal({{ $principalId }})"
                            class="ml-auto text-slate-500 hover:text-rose-400 transition shrink-0">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>
                @else
                    <p class="text-sm text-slate-600 italic">Non défini</p>
                @endif
            </div>

            <div
                class="rounded-2xl border {{ $respo1Id ? 'border-emerald-500/40 bg-emerald-500/5' : 'border-slate-700 bg-slate-900' }} p-4 transition-all">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Responsable 1</p>
                @if ($respo1Id)
                    @php $r1 = $this->students->firstWhere('id', $respo1Id); @endphp
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold shrink-0">
                            {{ strtoupper(substr($r1?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $r1?->name ?? '—' }}</p>
                            <p class="text-xs text-slate-500 font-mono">{{ $r1?->matricule ?? '' }}</p>
                        </div>
                        <button wire:click="toggleRespo1({{ $respo1Id }})"
                            class="ml-auto text-slate-500 hover:text-rose-400 transition shrink-0">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>
                @else
                    <p class="text-sm text-slate-600 italic">Non défini</p>
                @endif
            </div>

            <div
                class="rounded-2xl border {{ $respo2Id ? 'border-violet-500/40 bg-violet-500/5' : 'border-slate-700 bg-slate-900' }} p-4 transition-all">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Responsable 2</p>
                @if ($respo2Id)
                    @php $r2 = $this->students->firstWhere('id', $respo2Id); @endphp
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-full bg-violet-500/20 text-violet-400 flex items-center justify-center text-xs font-bold shrink-0">
                            {{ strtoupper(substr($r2?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $r2?->name ?? '—' }}</p>
                            <p class="text-xs text-slate-500 font-mono">{{ $r2?->matricule ?? '' }}</p>
                        </div>
                        <button wire:click="toggleRespo2({{ $respo2Id }})"
                            class="ml-auto text-slate-500 hover:text-rose-400 transition shrink-0">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>
                @else
                    <p class="text-sm text-slate-600 italic">Non défini</p>
                @endif
            </div>

        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-white text-sm">Professeur principal</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Enseignants actifs dans cette classe cette année</p>
                </div>
                <div class="relative w-56">
                    <input type="text" wire:model.live.debounce.300ms="teacherSearch" placeholder="Rechercher..."
                        class="w-full h-9 rounded-xl border border-slate-700 bg-slate-800 pl-8 pr-3 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs">🔍</span>
                    <div wire:loading wire:target="teacherSearch" class="absolute right-2.5 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </div>
                </div>
            </div>

            @if ($this->teachers->isEmpty())
                <div class="px-5 py-10 text-center text-sm text-slate-500">
                    Aucun enseignant actif dans cette classe.
                </div>
            @else
                <div class="divide-y divide-slate-800">
                    @foreach ($this->teachers as $teacher)
                        @php $isSelected = $principalId === $teacher->id; @endphp
                        <div wire:key="teacher-{{ $teacher->id }}" wire:click="togglePrincipal({{ $teacher->id }})"
                            class="flex items-center gap-4 px-5 py-3.5 cursor-pointer transition-all
                        {{ $isSelected ? 'bg-indigo-500/10' : 'hover:bg-slate-800/60' }}">

                            <div
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition
                        {{ $isSelected ? 'border-indigo-500 bg-indigo-500' : 'border-slate-600' }}">
                                @if ($isSelected)
                                    <div class="w-2 h-2 rounded-full bg-white"></div>
                                @endif
                            </div>

                            <div
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold shrink-0
                        {{ $isSelected ? 'bg-indigo-500/30 text-indigo-300' : 'bg-slate-700 text-slate-300' }}">
                                {{ strtoupper(substr($teacher->user?->name, 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm font-medium {{ $isSelected ? 'text-white' : 'text-slate-300' }} truncate">
                                    {{ $teacher->getFullName() }}
                                </p>
                                <p class="text-xs text-slate-500 truncate">{{ $teacher->user->email ?? '—' }}</p>
                            </div>

                            @if ($isSelected)
                                <span
                                    class="shrink-0 px-2.5 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs border border-indigo-500/20">
                                    ✓ Sélectionné
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-white text-sm">Responsables apprenants</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Cochez R1 et R2 pour deux apprenants différents</p>
                </div>
                <div class="relative w-56">
                    <input type="text" wire:model.live.debounce.300ms="studentSearch" placeholder="Rechercher..."
                        class="w-full h-9 rounded-xl border border-slate-700 bg-slate-800 pl-8 pr-3 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs">🔍</span>
                    <div wire:loading wire:target="studentSearch" class="absolute right-2.5 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="grid grid-cols-[2rem_2rem_1fr_auto] gap-4 px-5 py-2.5 bg-slate-950 border-b border-slate-800 text-xs font-medium text-slate-500 uppercase tracking-wide">
                <span class="text-center">R1</span>
                <span class="text-center">R2</span>
                <span>Apprenant</span>
                <span>Matricule</span>
            </div>

            <div class="divide-y divide-slate-800 max-h-[500px] overflow-y-auto" wire:loading.class="opacity-50"
                wire:target="studentSearch, previousPage, nextPage, gotoPage">

                @forelse ($this->students as $student)
                    @php
                        $isRespo1 = $respo1Id === $student->id;
                        $isRespo2 = $respo2Id === $student->id;
                    @endphp
                    <div wire:key="student-{{ $student->id }}"
                        class="grid grid-cols-[2rem_2rem_1fr_auto] gap-4 items-center px-5 py-3 transition-all
                        {{ $isRespo1 ? 'bg-emerald-500/5' : ($isRespo2 ? 'bg-violet-500/5' : 'hover:bg-slate-800/40') }}">

                        <div class="flex justify-center">
                            <button wire:click="toggleRespo1({{ $student->id }})" wire:loading.attr="disabled"
                                wire:target="toggleRespo1({{ $student->id }})"
                                class="w-6 h-6 rounded-md border-2 flex items-center justify-center transition shrink-0
                                {{ $isRespo1 ? 'bg-emerald-500 border-emerald-500' : 'border-slate-600 hover:border-emerald-400' }}">
                                <span wire:loading.remove wire:target="toggleRespo1({{ $student->id }})">
                                    @if ($isRespo1)
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </span>
                                <span wire:loading wire:target="toggleRespo1({{ $student->id }})">
                                    <svg class="animate-spin w-3 h-3 text-emerald-400" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                </span>
                            </button>
                        </div>

                        <div class="flex justify-center">
                            <button wire:click="toggleRespo2({{ $student->id }})" wire:loading.attr="disabled"
                                wire:target="toggleRespo2({{ $student->id }})"
                                class="w-6 h-6 rounded-md border-2 flex items-center justify-center transition shrink-0
                                {{ $isRespo2 ? 'bg-violet-500 border-violet-500' : 'border-slate-600 hover:border-violet-400' }}">
                                <span wire:loading.remove wire:target="toggleRespo2({{ $student->id }})">
                                    @if ($isRespo2)
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </span>
                                <span wire:loading wire:target="toggleRespo2({{ $student->id }})">
                                    <svg class="animate-spin w-3 h-3 text-violet-400" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                </span>
                            </button>
                        </div>

                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold shrink-0
                            {{ $isRespo1 ? 'bg-emerald-500/20 text-emerald-400' : ($isRespo2 ? 'bg-violet-500/20 text-violet-400' : 'bg-slate-700 text-slate-300') }}">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-white truncate">
                                    {{ $student->name }} {{ $student->prenames }}
                                </p>
                                @if ($isRespo1)
                                    <span class="text-xs text-emerald-400">Responsable 1</span>
                                @elseif ($isRespo2)
                                    <span class="text-xs text-violet-400">Responsable 2</span>
                                @endif
                            </div>
                        </div>

                        <span class="text-xs text-slate-500 font-mono shrink-0">{{ $student->matricule }}</span>

                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-sm text-slate-500">
                        Aucun apprenant dans cette classe.
                    </div>
                @endforelse
            </div>

            @if ($this->students->hasPages())
                <div class="px-5 py-3 border-t border-slate-800 flex items-center justify-between gap-4">
                    <span class="text-xs text-slate-500">
                        {{ $this->students->firstItem() }}–{{ $this->students->lastItem() }} sur
                        {{ $this->students->total() }}
                    </span>
                    <div class="flex items-center gap-2">
                        @if (!$this->students->onFirstPage())
                            <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                                class="h-8 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs transition disabled:opacity-50">
                                ← Préc.
                            </button>
                        @endif
                        <span class="text-xs text-slate-400">
                            Page {{ $this->students->currentPage() }} / {{ $this->students->lastPage() }}
                        </span>
                        @if ($this->students->hasMorePages())
                            <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                class="h-8 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs transition disabled:opacity-50">
                                Suiv. →
                            </button>
                        @endif
                    </div>
                </div>
            @endif

        </div>
        <div class="flex justify-end gap-3 pb-8">
            <a wire:navigate href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                class="px-5 py-2.5 text-sm rounded-xl border border-slate-700 text-slate-400 hover:bg-slate-800 transition">
                Annuler
            </a>
            <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                class="relative inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold transition disabled:opacity-50 min-w-[160px] justify-center">
                <span wire:loading wire:target="save"
                    class="absolute inset-0 flex items-center justify-center gap-2 rounded-xl bg-indigo-700">
                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>
                    <span class="text-white text-sm">Enregistrement...</span>
                </span>
                <span wire:loading.remove wire:target="save">
                    <x-lucide-check class="w-4 h-4 inline -mt-0.5" /> Enregistrer
                </span>
                <span wire:loading wire:target="save" class="invisible">Enregistrer</span>
            </button>
        </div>

    </div>
</div>

