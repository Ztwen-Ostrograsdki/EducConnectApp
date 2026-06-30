<div class="min-h-screen bg-slate-950 text-slate-100 p-6">

    {{-- ══════════════════════════════════════════════════════════════════
         EN-TÊTE ENSEIGNANT
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
            class="flex items-center justify-center text-2xl font-bold text-indigo-300 hover:underline underline-offset-4 hover:border-indigo-800 gap-x-3">
            <div
                class="flex-shrink-0 w-14 h-14 rounded-full bg-indigo-900/60 border border-indigo-700/50 flex items-center justify-center text-2xl font-bold text-indigo-300">
                <img src="{{ $teacher?->user?->profil_photo_url }}"
                    class="w-full h-full border-4 rounded-full object-cover">
            </div>
            <div>
                <h1 class="text-xl font-semibold text-slate-300">
                    {{ $this->teacher->user?->name }}
                    @if ($this->teacher->user?->prenames)
                        <span class="font-normal text-slate-400">{{ $this->teacher->user->prenames }}</span>
                    @endif
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    {{ $this->teacher->identifiant . ' - ' . $this->teacher->user?->email }}
                    @if ($this->activeYear)
                        &mdash;
                        <span class="text-indigo-400">{{ $this->activeYear->name }}</span>
                    @else
                        &mdash; <span class="text-amber-500">Aucune année scolaire active</span>
                    @endif
                </p>
            </div>
        </a>

        {{-- Matières du prof (badges) --}}
        @if ($this->teacherYearlySubjects->isNotEmpty())
            <div class="ml-auto flex flex-wrap gap-2 max-w-lg justify-end">
                @foreach ($this->teacherYearlySubjects as $s)
                    <span
                        class="px-2.5 py-0.5 text-xs rounded-full bg-indigo-900/50 border border-indigo-700/40 text-indigo-300">
                        {{ $s->name }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden flex flex-col">

            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between gap-3">
                <h2 class="text-sm font-semibold text-slate-300 uppercase tracking-widest">Classes</h2>
                <div class="relative flex-1 max-w-xs">
                    <input wire:model.live.debounce.300ms="classeSearch" type="text" placeholder="Rechercher…"
                        class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-1.5 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
                    <div wire:loading wire:target="classeSearch" class="absolute right-2 top-2">
                        <svg class="w-4 h-4 animate-spin text-indigo-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-slate-800 flex-1 overflow-y-auto">
                @forelse($this->classes as $classe)
                    <button wire:click="selectClasse({{ $classe->id }})" wire:loading.attr="disabled"
                        wire:target="selectClasse({{ $classe->id }})"
                        class="w-full text-left px-5 py-3.5 flex items-center justify-between gap-3 transition-colors
                            @if ($selectedClasseId === $classe->id) bg-indigo-900/40 border-l-2 border-indigo-500
                            @else
                                hover:bg-slate-800/60 border-l-2 border-transparent @endif">
                        <div class="flex items-center gap-3 min-w-0">
                            <div wire:loading wire:target="selectClasse({{ $classe->id }})" class="flex-shrink-0">
                                <svg class="w-4 h-4 animate-spin text-indigo-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                            </div>
                            <div wire:loading.remove wire:target="selectClasse({{ $classe->id }})"
                                class="flex-shrink-0 w-8 h-8 rounded-lg
                                @if ($selectedClasseId === $classe->id) bg-indigo-600 @else bg-slate-800 @endif
                                flex items-center justify-center text-xs font-bold
                                @if ($selectedClasseId === $classe->id) text-white @else text-slate-400 @endif">
                                {{ strtoupper(substr($classe->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-medium text-slate-200 truncate">{{ $classe->name }}</div>
                                @if ($classe->level)
                                    <div class="text-xs text-slate-500">Niveau {{ $classe->level }}</div>
                                @endif
                            </div>
                        </div>

                        @php
                            $countInClasse = $this->allAssignedLinks->where('classe_id', $classe->id)->count();
                        @endphp
                        @if ($countInClasse > 0)
                            <span
                                class="flex-shrink-0 text-xs px-2 py-0.5 rounded-full bg-indigo-900/60 border border-indigo-700/50 text-indigo-300">
                                {{ $countInClasse }} mat.
                            </span>
                        @endif

                        @if ($selectedClasseId === $classe->id)
                            <svg class="flex-shrink-0 w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        @endif
                    </button>
                @empty
                    <div class="px-5 py-10 text-center text-slate-500 text-sm">
                        Aucune classe trouvée
                    </div>
                @endforelse
            </div>

            @if ($this->classes->hasPages())
                <div class="px-5 py-3 border-t border-slate-800">
                    {{ $this->classes->links() }}
                </div>
            @endif
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden flex flex-col">

            @if (!$selectedClasseId)
                <div class="flex-1 flex flex-col items-center justify-center py-20 gap-4 text-slate-600">
                    <svg class="w-12 h-12 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                    <p class="text-sm">Sélectionnez une classe pour gérer les matières</p>
                </div>
            @else
                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-300 uppercase tracking-widest">
                            Matières — <span
                                class="text-indigo-400 normal-case">{{ $this->selectedClasse?->name }}</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Cochez les matières à dispenser dans cette classe</p>
                    </div>
                    <div class="relative">
                        <input wire:model.live.debounce.200ms="subjectSearch" type="text" placeholder="Filtrer…"
                            class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-1.5 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition w-40">
                    </div>
                </div>

                <div class="divide-y divide-slate-800 flex-1 overflow-y-auto" wire:loading.class="opacity-50"
                    wire:target="selectedClasseId,subjectSearch">

                    <div wire:loading wire:target="selectClasse" class="px-5 py-10 flex justify-center">
                        <svg class="w-6 h-6 animate-spin text-indigo-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </div>

                    <div wire:loading.remove wire:target="selectClasse">
                        @forelse($this->subjectsForSelectedClasse as $item)
                            @php
                                $subject = $item['subject'];
                                $assignedSelf = $item['assigned_by_self'];
                                $takenByOther = $item['taken_by_other'];
                                $link = $item['link'];
                                $isDisabled = $takenByOther;
                            @endphp

                            <div wire:key="subject-{{ $subject->id }}"
                                class="px-5 py-3.5 flex items-center gap-4 transition-colors
                                    @if ($isDisabled) opacity-50 cursor-not-allowed
                                    @elseif($assignedSelf) bg-emerald-950/30
                                    @else hover:bg-slate-800/50 cursor-pointer @endif"
                                @if (!$isDisabled) wire:click="toggleSubject({{ $subject->id }})" @endif>
                                <div class="flex-shrink-0 relative">
                                    <div wire:loading wire:target="toggleSubject({{ $subject->id }})"
                                        class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-5 h-5 animate-spin text-indigo-400" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                        </svg>
                                    </div>

                                    <div wire:loading.remove wire:target="toggleSubject({{ $subject->id }})"
                                        class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all
                                            @if ($assignedSelf) bg-emerald-500 border-emerald-500
                                            @elseif($takenByOther)
                                                bg-slate-700 border-slate-600
                                            @else
                                                border-slate-600 hover:border-indigo-400 @endif">
                                        @if ($assignedSelf)
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($takenByOther)
                                            <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18 12H6" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-sm font-medium
                                            @if ($assignedSelf) text-emerald-300
                                            @elseif($takenByOther) text-slate-500
                                            @else text-slate-200 @endif">
                                            {{ $subject->name }}
                                        </span>
                                        @if ($subject->code)
                                            <span class="text-xs px-1.5 py-0.5 rounded bg-slate-800 text-slate-500">
                                                {{ $subject->code }}
                                            </span>
                                        @endif
                                    </div>
                                    @if ($takenByOther && $link?->teacher?->user)
                                        <p class="text-xs text-amber-600/80 mt-0.5">
                                            Déjà assignée à {{ $link->teacher->user->name }}
                                        </p>
                                    @endif
                                </div>

                                @if ($assignedSelf)
                                    <span
                                        class="flex-shrink-0 text-xs px-2 py-0.5 rounded-full bg-emerald-900/50 border border-emerald-700/40 text-emerald-400">
                                        Assignée
                                    </span>
                                @elseif($takenByOther)
                                    <span
                                        class="flex-shrink-0 text-xs px-2 py-0.5 rounded-full bg-amber-900/30 border border-amber-700/30 text-amber-600">
                                        Occupée
                                    </span>
                                @endif
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-slate-500 text-sm">
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
        </div>
    </div>

    @if ($this->allAssignedLinks->isNotEmpty())
        <div
            class="mt-6 bg-slate-900 border border-slate-800 overflow-hidden rounded-l-3xl rounded-r-3xl rounded-br-none rounded-bl-none">

            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-300 uppercase tracking-widest">
                    Récapitulatif des assignations
                </h2>
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-900/50 border border-indigo-700/40 text-indigo-300">
                    {{ $this->allAssignedLinks->count() }} lien(s)
                </span>
            </div>

            <div class="overflow-x-auto pb-10 px-0.5">
                <table class="w-full text-sm z-table-border">
                    <thead>
                        <tr
                            class="text-xs text-indigo-500 bg-gray-900 uppercase tracking-wider border-b border-slate-800 text-center">
                            <th class="px-5 py-3 font-medium">Classe</th>
                            <th class="px-5 py-3 font-medium">Matière</th>
                            <th class="px-5 py-3 font-medium">Depuis</th>
                            <th class="px-5 py-3 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach ($this->allAssignedLinks as $link)
                            <tr class="hover:bg-slate-800/40 transition-colors" wire:key="link-{{ $link->id }}">
                                <td class="px-5 py-3 text-slate-200">
                                    {{ $link->classe?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-slate-300">
                                    {{ $link->subject?->name ?? '—' }}
                                    @if ($link->subject?->code)
                                        <span class="ml-1.5 text-xs text-slate-600">{{ $link->subject->code }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-500 text-xs">
                                    {{ $link->started_at?->diffForHumans() ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <button wire:click="removeLink({{ $link->id }})" wire:loading.attr="disabled"
                                        wire:target="removeLink({{ $link->id }})"
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-center rounded-lg text-xs
                                               bg-red-900/30 border border-red-800/40 text-red-400
                                               hover:bg-red-900/60 hover:border-red-700 transition-colors
                                               disabled:opacity-40 disabled:cursor-not-allowed w-full">
                                        <div wire:loading wire:target="removeLink({{ $link->id }})">
                                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v8z" />
                                            </svg>
                                        </div>
                                        <div wire:loading.remove wire:target="removeLink({{ $link->id }})">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                        Retirer
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

