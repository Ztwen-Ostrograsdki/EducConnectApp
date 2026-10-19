<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ========== HEADER ENSEIGNANT ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <a href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}" wire:navigate
                    class="flex items-center gap-4 group min-w-0">
                    <div
                        class="w-14 h-14 rounded-2xl overflow-hidden border border-white/[0.08]
                                group-hover:border-indigo-500/40 transition-all shrink-0 shadow-inner">
                        <img src="{{ $teacher?->user?->profil_photo_url }}" alt="{{ $this->teacher->user?->name }}"
                            class="w-full h-full object-cover" />
                    </div>
                    <div class="min-w-0">
                        <h1
                            class="text-lg sm:text-xl font-bold text-white group-hover:text-indigo-300 transition-colors truncate">
                            {{ $this->teacher->user?->name }}
                            @if ($this->teacher->user?->prenames)
                                <span class="font-normal text-slate-400">{{ $this->teacher->user->prenames }}</span>
                            @endif
                        </h1>
                        <p class="text-sm text-slate-500 mt-0.5 truncate">
                            <span class="font-mono">{{ $this->teacher->identifiant }}</span>
                            · {{ $this->teacher->user?->email }}
                            @if ($this->activeYear)
                                · <span class="text-indigo-400">{{ $this->activeYear->name }}</span>
                            @else
                                · <span class="text-amber-400">Aucune année active</span>
                            @endif
                        </p>
                    </div>
                </a>
            </div>
            @if ($this->teacherYearlySubjects->isNotEmpty())
                <div class="flex flex-wrap gap-1.5 w-full items-center justify-start my-2 mt-3.5">
                    <span class="text-slate-400 text-trebuchet">Matières | Spécialités : </span>
                    @foreach ($this->teacherYearlySubjects as $s)
                        <span
                            class="px-2 py-0.5 rounded-md text-[11px] font-medium
                                         bg-indigo-500/15 text-indigo-300 border border-indigo-500/25">
                            {{ $s->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- ========== DEUX COLONNES ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- CLASSES --}}
            <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Classes
                        </h2>
                    </div>
                    <div class="relative flex-1 max-w-[200px]">
                        <x-lucide-search
                            class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" />
                        <input wire:model.live.debounce.300ms="classeSearch" type="text" placeholder="Rechercher…"
                            class="w-full h-9 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      pl-8 pr-8 text-xs text-white placeholder:text-slate-600
                                      focus:border-indigo-500/50 focus:outline-none transition-all" />
                        <div wire:loading wire:target="classeSearch"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 text-indigo-400 animate-spin" />
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-white/[0.04] flex-1 overflow-y-auto max-h-[480px]">
                    @forelse ($this->classes as $classe)
                        <button type="button" wire:click="selectClasse({{ $classe->id }})"
                            wire:loading.attr="disabled" wire:target="selectClasse({{ $classe->id }})"
                            class="w-full text-left px-5 py-3.5 flex items-center gap-3 transition-all
                                       {{ $selectedClasseId === $classe->id
                                           ? 'bg-indigo-500/10 border-l-2 border-l-indigo-500'
                                           : 'hover:bg-white/[0.02] border-l-2 border-l-transparent' }}">
                            <div wire:loading wire:target="selectClasse({{ $classe->id }})"
                                class="shrink-0 w-9 h-9 flex items-center justify-center">
                                <x-lucide-loader-2 class="w-4 h-4 text-indigo-400 animate-spin" />
                            </div>
                            <div wire:loading.remove wire:target="selectClasse({{ $classe->id }})"
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-[11px] font-bold shrink-0 border
                                        {{ $selectedClasseId === $classe->id
                                            ? 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30'
                                            : 'bg-white/[0.04] text-slate-400 border-white/[0.06]' }}">
                                {{ strtoupper(substr($classe->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-white truncate">{{ $classe->name }}</p>
                                @if ($classe->level)
                                    <p class="text-[11px] text-slate-500">Niveau {{ $classe->level }}</p>
                                @endif
                            </div>

                            @php $countInClasse = $this->allAssignedLinks->where('classe_id', $classe->id)->count(); @endphp
                            @if ($countInClasse > 0)
                                <span
                                    class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium
                                             bg-indigo-500/15 text-indigo-300 border border-indigo-500/25">
                                    {{ $countInClasse }} mat.
                                </span>
                            @endif

                            @if ($selectedClasseId === $classe->id)
                                <x-lucide-chevron-right class="w-4 h-4 text-indigo-400 shrink-0" />
                            @endif
                        </button>
                    @empty
                        <div class="px-5 py-12 text-center text-sm text-slate-500">
                            Aucune classe trouvée
                        </div>
                    @endforelse
                </div>

                @if ($this->classes->hasPages())
                    <div class="px-5 py-3 border-t border-white/[0.05]">
                        {{ $this->classes->links() }}
                    </div>
                @endif
            </section>

            {{-- MATIÈRES --}}
            <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden flex flex-col">
                @if (!$selectedClasseId)
                    <div class="flex-1 flex flex-col items-center justify-center py-20 gap-3 text-slate-600">
                        <div
                            class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20
                                    flex items-center justify-center mb-1">
                            <x-lucide-school class="w-7 h-7 text-indigo-400" />
                        </div>
                        <p class="text-sm text-slate-500">Sélectionnez une classe</p>
                        <p class="text-xs text-slate-600">Les matières s’afficheront ici</p>
                    </div>
                @else
                    <div class="px-5 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                Matières
                                <span class="text-indigo-400 normal-case font-medium">
                                    · {{ $this->selectedClasse?->name }}
                                </span>
                            </h2>
                            <p class="text-[11px] text-slate-600 mt-0.5">
                                Cochez les matières à dispenser
                            </p>
                        </div>
                        <div class="relative shrink-0">
                            <x-lucide-search
                                class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" />
                            <input wire:model.live.debounce.200ms="subjectSearch" type="text" placeholder="Filtrer…"
                                class="h-9 w-36 rounded-xl border border-white/[0.08] bg-[#070a12]
                                          pl-8 pr-3 text-xs text-white placeholder:text-slate-600
                                          focus:border-indigo-500/50 focus:outline-none transition-all" />
                        </div>
                    </div>

                    <div class="divide-y divide-white/[0.04] flex-1 overflow-y-auto max-h-[480px]
                                relative"
                        wire:loading.class="opacity-50" wire:target="selectedClasseId,subjectSearch">

                        <div wire:loading wire:target="selectClasse"
                            class="absolute inset-0 z-10 flex items-center justify-center bg-[#070a12]/60">
                            <x-lucide-loader-2 class="w-6 h-6 text-indigo-400 animate-spin" />
                        </div>

                        <div wire:loading.remove wire:target="selectClasse">
                            @forelse ($this->subjectsForSelectedClasse as $item)
                                @php
                                    $subject = $item['subject'];
                                    $assignedSelf = $item['assigned_by_self'];
                                    $takenByOther = $item['taken_by_other'];
                                    $link = $item['link'];
                                    $isDisabled = $takenByOther;
                                @endphp

                                <div wire:key="subject-{{ $subject->id }}"
                                    @if (!$isDisabled) wire:click="toggleSubject({{ $subject->id }})" @endif
                                    class="px-5 py-3.5 flex items-center gap-3 transition-colors
                                            {{ $isDisabled
                                                ? 'opacity-50 cursor-not-allowed'
                                                : ($assignedSelf
                                                    ? 'bg-emerald-500/[0.06] cursor-pointer'
                                                    : 'hover:bg-white/[0.02] cursor-pointer') }}">

                                    {{-- Checkbox --}}
                                    <div class="shrink-0 relative w-5 h-5">
                                        <div wire:loading wire:target="toggleSubject({{ $subject->id }})"
                                            class="absolute inset-0 flex items-center justify-center">
                                            <x-lucide-loader-2 class="w-4 h-4 text-indigo-400 animate-spin" />
                                        </div>
                                        <div wire:loading.remove wire:target="toggleSubject({{ $subject->id }})"
                                            class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all
                                                    {{ $assignedSelf
                                                        ? 'bg-emerald-500 border-emerald-500'
                                                        : ($takenByOther
                                                            ? 'bg-slate-700 border-slate-600'
                                                            : 'border-slate-600 hover:border-indigo-400') }}">
                                            @if ($assignedSelf)
                                                <x-lucide-check class="w-3 h-3 text-white" stroke-width="3" />
                                            @elseif ($takenByOther)
                                                <x-lucide-minus class="w-3 h-3 text-slate-500" />
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span
                                                class="text-sm font-medium
                                                         {{ $assignedSelf ? 'text-emerald-300' : ($takenByOther ? 'text-slate-500' : 'text-white') }}">
                                                {{ $subject->name }}
                                            </span>
                                            @if ($subject->code)
                                                <span
                                                    class="px-1.5 py-0.5 rounded text-[10px] font-mono
                                                             bg-white/[0.04] text-slate-500 border border-white/[0.06]">
                                                    {{ $subject->code }}
                                                </span>
                                            @endif
                                        </div>
                                        @if ($takenByOther && $link?->teacher?->user)
                                            <p class="text-[11px] text-amber-400/80 mt-0.5">
                                                Déjà assignée à {{ $link->teacher->user->name }}
                                            </p>
                                        @endif
                                    </div>

                                    @if ($assignedSelf)
                                        <span
                                            class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium
                                                     bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                                            Assignée
                                        </span>
                                    @elseif ($takenByOther)
                                        <span
                                            class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium
                                                     bg-amber-500/15 text-amber-400 border border-amber-500/25">
                                            Occupée
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <div class="px-5 py-12 text-center text-sm text-slate-500">
                                    @if ($this->teacherYearlySubjects->isEmpty())
                                        Aucune matière assignée à cet enseignant cette année
                                    @else
                                        Aucun résultat pour « {{ $subjectSearch }} »
                                    @endif
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </section>
        </div>

        {{-- ========== RÉCAPITULATIF ========== --}}
        @if ($this->allAssignedLinks->isNotEmpty())
            <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
                <div class="px-5 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Récapitulatif des assignations
                        </h2>
                    </div>
                    <span
                        class="px-2.5 py-0.5 rounded-md text-[11px] font-bold
                                 bg-indigo-500/15 text-indigo-300 border border-indigo-500/25">
                        {{ $this->allAssignedLinks->count() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-white/[0.05]">
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Classe</th>
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Matière</th>
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Depuis</th>
                                <th
                                    class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.04]">
                            @foreach ($this->allAssignedLinks as $link)
                                <tr wire:key="link-{{ $link->id }}"
                                    class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-5 py-3.5 text-white font-medium">
                                        {{ $link->classe?->name ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-300">
                                        {{ $link->subject?->name ?? '—' }}
                                        @if ($link->subject?->code)
                                            <span class="ml-1.5 text-[11px] font-mono text-slate-600">
                                                {{ $link->subject->code }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-xs text-slate-500">
                                        {{ $link->started_at?->diffForHumans() ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <button wire:click="removeLink({{ $link->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="removeLink({{ $link->id }})"
                                            class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                       bg-rose-500/15 text-rose-400 border border-rose-500/25
                                                       hover:bg-rose-500 hover:text-white hover:border-rose-500
                                                       transition-all disabled:opacity-40">
                                            <span wire:loading.remove wire:target="removeLink({{ $link->id }})"
                                                class="inline-flex items-center gap-1">
                                                <x-lucide-x class="w-3.5 h-3.5" /> Retirer
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
            </section>
        @endif

    </div>
</div>

