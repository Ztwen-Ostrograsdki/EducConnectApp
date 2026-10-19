<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

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
                    Vie de classe
                </p>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Responsables de la classe
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $classe->name }}
                    @if ($this->activeYear)
                        · <span class="text-indigo-400 font-mono">{{ $this->activeYear->slug }}</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- ========== RÉSUMÉ CHOIX ========== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

            {{-- Prof principal --}}
            <div
                class="rounded-2xl border p-4 transition-all
                        {{ $principalId ? 'border-indigo-500/30 bg-indigo-500/[0.06]' : 'border-white/[0.06] bg-white/[0.02]' }}">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2.5">
                    Prof principal
                </p>
                @if ($principalId)
                    @php $pt = $this->teachers->firstWhere('id', $principalId); @endphp
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-9 h-9 rounded-xl bg-indigo-500/20 border border-indigo-500/30
                                    flex items-center justify-center text-xs font-bold text-indigo-300 shrink-0">
                            {{ strtoupper(substr($pt?->user?->name ?? '?', 0, 1)) }}
                        </div>
                        <p class="text-sm font-medium text-white truncate flex-1 min-w-0">
                            {{ $pt?->getFullName() ?? '—' }}
                        </p>
                        <button wire:click="togglePrincipal({{ $principalId }})"
                            class="w-7 h-7 rounded-lg flex items-center justify-center
                                       text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all shrink-0">
                            <x-lucide-x class="w-3.5 h-3.5" />
                        </button>
                    </div>
                @else
                    <p class="text-sm text-slate-600 italic">Non défini</p>
                @endif
            </div>

            {{-- Respo 1 --}}
            <div
                class="rounded-2xl border p-4 transition-all
                        {{ $respo1Id ? 'border-emerald-500/30 bg-emerald-500/[0.06]' : 'border-white/[0.06] bg-white/[0.02]' }}">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2.5">
                    Responsable 1
                </p>
                @if ($respo1Id)
                    @php $r1 = $this->students->firstWhere('id', $respo1Id); @endphp
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-9 h-9 rounded-xl bg-emerald-500/20 border border-emerald-500/30
                                    flex items-center justify-center text-xs font-bold text-emerald-300 shrink-0">
                            {{ strtoupper(substr($r1?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-white truncate">{{ $r1?->name ?? '—' }}</p>
                            <p class="text-[11px] text-slate-500 font-mono">{{ $r1?->matricule ?? '' }}</p>
                        </div>
                        <button wire:click="toggleRespo1({{ $respo1Id }})"
                            class="w-7 h-7 rounded-lg flex items-center justify-center
                                       text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all shrink-0">
                            <x-lucide-x class="w-3.5 h-3.5" />
                        </button>
                    </div>
                @else
                    <p class="text-sm text-slate-600 italic">Non défini</p>
                @endif
            </div>

            {{-- Respo 2 --}}
            <div
                class="rounded-2xl border p-4 transition-all
                        {{ $respo2Id ? 'border-violet-500/30 bg-violet-500/[0.06]' : 'border-white/[0.06] bg-white/[0.02]' }}">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2.5">
                    Responsable 2
                </p>
                @if ($respo2Id)
                    @php $r2 = $this->students->firstWhere('id', $respo2Id); @endphp
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-9 h-9 rounded-xl bg-violet-500/20 border border-violet-500/30
                                    flex items-center justify-center text-xs font-bold text-violet-300 shrink-0">
                            {{ strtoupper(substr($r2?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-white truncate">{{ $r2?->name ?? '—' }}</p>
                            <p class="text-[11px] text-slate-500 font-mono">{{ $r2?->matricule ?? '' }}</p>
                        </div>
                        <button wire:click="toggleRespo2({{ $respo2Id }})"
                            class="w-7 h-7 rounded-lg flex items-center justify-center
                                       text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all shrink-0">
                            <x-lucide-x class="w-3.5 h-3.5" />
                        </button>
                    </div>
                @else
                    <p class="text-sm text-slate-600 italic">Non défini</p>
                @endif
            </div>
        </div>

        {{-- ========== PROF PRINCIPAL ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
            <div class="px-5 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></div>
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Professeur principal
                        </h2>
                        <p class="text-[11px] text-slate-600 mt-0.5">
                            Enseignants actifs dans cette classe
                        </p>
                    </div>
                </div>
                <div class="relative w-48 shrink-0">
                    <x-lucide-search class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" />
                    <input type="text" wire:model.live.debounce.300ms="teacherSearch" placeholder="Rechercher…"
                        class="w-full h-9 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  pl-8 pr-8 text-xs text-white placeholder:text-slate-600
                                  focus:border-indigo-500/50 focus:outline-none transition-all" />
                    <div wire:loading wire:target="teacherSearch" class="absolute right-2.5 top-1/2 -translate-y-1/2">
                        <x-lucide-loader-2 class="w-3.5 h-3.5 text-indigo-400 animate-spin" />
                    </div>
                </div>
            </div>

            @if ($this->teachers->isEmpty())
                <div class="px-5 py-12 text-center text-sm text-slate-500">
                    Aucun enseignant actif dans cette classe
                </div>
            @else
                <div class="divide-y divide-white/[0.04]">
                    @foreach ($this->teachers as $teacher)
                        @php
                            $isSelected = $principalId === $teacher->id;
                            $blocked = $teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyCARole();
                        @endphp
                        <div @if ($blocked) title="{{ $teacher->getFullName() }} a déjà un rôle AE ou CA"
                             @else
                                 wire:key="teacher-{{ $teacher->id }}"
                                 wire:click="togglePrincipal({{ $teacher->id }})" @endif
                            class="flex items-center gap-3 px-5 py-3.5 transition-all
                                    {{ $blocked ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}
                                    {{ $isSelected ? 'bg-indigo-500/10' : ($blocked ? '' : 'hover:bg-white/[0.02]') }}">

                            {{-- Radio --}}
                            <div
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition
                                        {{ $isSelected ? 'border-indigo-500 bg-indigo-500' : 'border-slate-600' }}">
                                @if ($isSelected)
                                    <div class="w-2 h-2 rounded-full bg-white"></div>
                                @endif
                            </div>

                            <div
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold shrink-0 border
                                        {{ $isSelected
                                            ? 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30'
                                            : 'bg-white/[0.04] text-slate-400 border-white/[0.06]' }}">
                                {{ strtoupper(substr($teacher->user?->name ?? '?', 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm font-medium truncate
                                          {{ $isSelected ? 'text-white' : 'text-slate-300' }}">
                                    {{ $teacher->getFullName() }}
                                </p>
                                <p class="text-[11px] text-slate-500 truncate">
                                    {{ $teacher->user->email ?? '—' }}
                                </p>
                            </div>

                            @if ($isSelected)
                                <span
                                    class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium
                                             bg-indigo-500/15 text-indigo-400 border border-indigo-500/25">
                                    Sélectionné
                                </span>
                            @endif
                            @if ($teacher->hasCurrentlyAERole())
                                <span
                                    class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium
                                             bg-rose-500/15 text-rose-400 border border-rose-500/25">
                                    Rôle AE
                                </span>
                            @endif
                            @if ($teacher->hasCurrentlyCARole())
                                <span
                                    class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium
                                             bg-rose-500/15 text-rose-400 border border-rose-500/25">
                                    Rôle CA
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- ========== RESPONSABLES APPRENANTS ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
            <div class="px-5 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></div>
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Responsables apprenants
                        </h2>
                        <p class="text-[11px] text-slate-600 mt-0.5">
                            Cochez R1 et R2 pour deux apprenants distincts
                        </p>
                    </div>
                </div>
                <div class="relative w-48 shrink-0">
                    <x-lucide-search class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" />
                    <input type="text" wire:model.live.debounce.300ms="studentSearch" placeholder="Rechercher…"
                        class="w-full h-9 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  pl-8 pr-8 text-xs text-white placeholder:text-slate-600
                                  focus:border-indigo-500/50 focus:outline-none transition-all" />
                    <div wire:loading wire:target="studentSearch" class="absolute right-2.5 top-1/2 -translate-y-1/2">
                        <x-lucide-loader-2 class="w-3.5 h-3.5 text-indigo-400 animate-spin" />
                    </div>
                </div>
            </div>

            {{-- Header colonnes --}}
            <div
                class="grid grid-cols-[2.5rem_2.5rem_1fr_auto] gap-3 px-5 py-2.5
                        bg-white/[0.02] border-b border-white/[0.05]
                        text-[11px] font-bold uppercase tracking-wider text-slate-500">
                <span class="text-center text-emerald-400/80">R1</span>
                <span class="text-center text-violet-400/80">R2</span>
                <span>Apprenant</span>
                <span>Matricule</span>
            </div>

            <div class="divide-y divide-white/[0.04] max-h-[500px] overflow-y-auto" wire:loading.class="opacity-50"
                wire:target="studentSearch, previousPage, nextPage, gotoPage">

                @forelse ($this->students as $student)
                    @php
                        $isRespo1 = $respo1Id === $student->id;
                        $isRespo2 = $respo2Id === $student->id;
                    @endphp
                    <div wire:key="student-{{ $student->id }}"
                        class="grid grid-cols-[2.5rem_2.5rem_1fr_auto] gap-3 items-center px-5 py-3 transition-all
                                {{ $isRespo1 ? 'bg-emerald-500/[0.05]' : ($isRespo2 ? 'bg-violet-500/[0.05]' : 'hover:bg-white/[0.02]') }}">

                        {{-- R1 --}}
                        <div class="flex justify-center">
                            <button wire:click="toggleRespo1({{ $student->id }})" wire:loading.attr="disabled"
                                wire:target="toggleRespo1({{ $student->id }})"
                                class="w-6 h-6 rounded-md border-2 flex items-center justify-center transition shrink-0
                                           {{ $isRespo1 ? 'bg-emerald-500 border-emerald-500' : 'border-slate-600 hover:border-emerald-400' }}">
                                <span wire:loading.remove wire:target="toggleRespo1({{ $student->id }})">
                                    @if ($isRespo1)
                                        <x-lucide-check class="w-3.5 h-3.5 text-white" stroke-width="3" />
                                    @endif
                                </span>
                                <span wire:loading wire:target="toggleRespo1({{ $student->id }})">
                                    <x-lucide-loader-2 class="w-3 h-3 text-emerald-400 animate-spin" />
                                </span>
                            </button>
                        </div>

                        {{-- R2 --}}
                        <div class="flex justify-center">
                            <button wire:click="toggleRespo2({{ $student->id }})" wire:loading.attr="disabled"
                                wire:target="toggleRespo2({{ $student->id }})"
                                class="w-6 h-6 rounded-md border-2 flex items-center justify-center transition shrink-0
                                           {{ $isRespo2 ? 'bg-violet-500 border-violet-500' : 'border-slate-600 hover:border-violet-400' }}">
                                <span wire:loading.remove wire:target="toggleRespo2({{ $student->id }})">
                                    @if ($isRespo2)
                                        <x-lucide-check class="w-3.5 h-3.5 text-white" stroke-width="3" />
                                    @endif
                                </span>
                                <span wire:loading wire:target="toggleRespo2({{ $student->id }})">
                                    <x-lucide-loader-2 class="w-3 h-3 text-violet-400 animate-spin" />
                                </span>
                            </button>
                        </div>

                        {{-- Nom --}}
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div
                                class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold shrink-0 border
                                        {{ $isRespo1
                                            ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30'
                                            : ($isRespo2
                                                ? 'bg-violet-500/20 text-violet-400 border-violet-500/30'
                                                : 'bg-white/[0.04] text-slate-400 border-white/[0.06]') }}">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-white truncate">
                                    {{ $student->name }} {{ $student->prenames }}
                                </p>
                                @if ($isRespo1)
                                    <span class="text-[11px] text-emerald-400">Responsable 1</span>
                                @elseif ($isRespo2)
                                    <span class="text-[11px] text-violet-400">Responsable 2</span>
                                @endif
                            </div>
                        </div>

                        <span class="text-xs text-slate-500 font-mono shrink-0">
                            {{ $student->matricule }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-sm text-slate-500">
                        Aucun apprenant dans cette classe
                    </div>
                @endforelse
            </div>

            @if ($this->students->hasPages())
                <div
                    class="px-5 py-3 border-t border-white/[0.05]
                            flex items-center justify-between gap-3">
                    <span class="text-[11px] text-slate-500">
                        {{ $this->students->firstItem() }}–{{ $this->students->lastItem() }}
                        / {{ $this->students->total() }}
                    </span>
                    <div class="flex items-center gap-1.5">
                        @if (!$this->students->onFirstPage())
                            <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                                class="h-8 px-2.5 rounded-lg text-[11px] font-medium
                                           bg-white/[0.04] border border-white/[0.08] text-slate-400
                                           hover:bg-white/[0.08] hover:text-white transition-all disabled:opacity-50">
                                ← Préc.
                            </button>
                        @endif
                        <span class="text-[11px] text-slate-500 px-1">
                            {{ $this->students->currentPage() }}/{{ $this->students->lastPage() }}
                        </span>
                        @if ($this->students->hasMorePages())
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

        {{-- ========== FOOTER ========== --}}
        <div class="flex justify-end gap-2 pb-4">
            <a wire:navigate href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                class="inline-flex items-center h-10 px-4 rounded-xl text-sm font-medium
                      border border-white/[0.08] text-slate-400
                      hover:bg-white/[0.06] hover:text-white transition-all">
                Annuler
            </a>
            <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                class="inline-flex items-center gap-2 h-10 px-5 rounded-xl text-sm font-medium
                           bg-indigo-500 hover:bg-indigo-400 text-white
                           shadow-lg shadow-indigo-500/20
                           transition-all disabled:opacity-50 min-w-[140px] justify-center">
                <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                    <x-lucide-check class="w-4 h-4" />
                    Enregistrer
                </span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    Enregistrement…
                </span>
            </button>
        </div>

    </div>
</div>

