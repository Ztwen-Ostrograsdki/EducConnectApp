<div class="min-h-screen bg-[#070a12] text-slate-100 shadow-sm shadow-sky-600">
    <div wire:loading wire:target="switchMode,resetAllInputs,reloaddata"
        class="fixed inset-0 z-[200] flex items-center justify-center bg-[#0b0f19]/70 backdrop-blur-xs">
        <div class="flex flex-col items-center gap-3 text-slate-400">
            <svg class="animate-spin w-8 h-8 text-violet-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <span class="text-sm font-mono">Chargement…</span>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-4 py-6 sm:py-8 space-y-6 mb-24">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        @if ($this->activeYear && $this->activeYear->active_period && $this->period)

            <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-3 sm:p-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <div class="flex flex-wrap items-center gap-3">

                        {{-- Mode switch --}}
                        @if (!$this->subject->isConduite())
                            <div class="relative flex rounded-xl border border-white/[0.08] bg-[#070a12] p-1">
                                <div class="absolute top-1 bottom-1 w-[calc(50%-2px)] rounded-lg bg-indigo-500
                                        shadow-lg shadow-indigo-500/30 transition-transform duration-300 ease-out"
                                    style="transform: translateX({{ $mode === 'excel' ? '100%' : '0' }}); left: 2px;">
                                </div>
                                <button type="button" wire:click="switchMode('manual')"
                                    class="relative z-10 h-9 px-4 text-sm font-medium transition-colors duration-300
                                           {{ $mode === 'manual' ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">
                                    Saisie manuelle
                                </button>

                                <button type="button" wire:click="switchMode('excel')"
                                    class="relative z-10 h-9 px-4 text-sm font-medium transition-colors duration-300
                                           {{ $mode === 'excel' ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">
                                    Import Excel
                                </button>

                            </div>
                        @endif

                        {{-- Période --}}
                        <select wire:model.live="period"
                            class="h-10 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm
                                       text-slate-300 font-mono uppercase
                                       focus:border-indigo-500/50 focus:outline-none transition">
                            <option disabled value="">
                                {{ $this->activeYear->periodLabel() }}
                            </option>
                            @foreach ($this->periods_types as $pv => $p)
                                @if ($this->activeYear && $this->activeYear->active_period == $p['index'])
                                    <option @disabled(!($this->activeYear && $this->activeYear->active_period == $p['index'])) value="{{ $p['index'] }}">
                                        {{ $p['label'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        <x-lucide-loader-2 wire:loading.class="opacity-100" wire:loading.class.remove="opacity-0"
                            wire:target="period"
                            class="w-4 h-4 text-indigo-400 animate-spin opacity-0 transition-opacity" />
                    </div>

                    {{-- Actions manuelles --}}
                    <div x-data x-show="@this.mode === 'manual'" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-wrap gap-2">

                        <button wire:click="validateAllMarks" wire:loading.attr="disabled"
                            wire:target="validateAllMarks"
                            class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                                       bg-emerald-500/15 text-emerald-400 border border-emerald-500/25
                                       hover:bg-emerald-500 hover:text-white hover:border-emerald-500
                                       transition-all duration-200 disabled:opacity-50">
                            <span wire:loading.remove wire:target="validateAllMarks"
                                class="inline-flex items-center gap-2">
                                <x-lucide-check-check class="w-3.5 h-3.5" />
                                Valider tout
                            </span>
                            <span wire:loading wire:target="validateAllMarks">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>

                        <button wire:click="resetAllInputs" wire:loading.attr="disabled" wire:target="resetAllInputs"
                            class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                                       bg-amber-500/10 text-amber-400 border border-amber-500/20
                                       hover:bg-amber-500 hover:text-white hover:border-amber-500
                                       transition-all duration-200 disabled:opacity-50">
                            <span wire:loading.remove wire:target="resetAllInputs"
                                class="inline-flex items-center gap-2">
                                <x-lucide-rotate-ccw class="w-3.5 h-3.5" />
                                Réinitialiser
                            </span>
                            <span wire:loading wire:target="resetAllInputs">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    </div>
                </div>
            </section>

            <section>

                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">

                    {{-- Header --}}
                    <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05]">
                        <h2 class="text-base sm:text-lg font-semibold text-white">
                            Saisie des notes
                            <span class="text-amber-400 font-mono">{{ $this->subject->code }}</span>
                            <span class="text-slate-500 font-normal">·</span>
                            <span class="text-sky-400 font-mono text-sm">
                                {{ $this->activeYear->periodLabel() }} {{ $this->period }}
                            </span>
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            @if ($mode === 'manual')
                                Ajoutez rapidement les notes des apprenants par saisie.
                            @else
                                Chargez les notes depuis un fichier Excel.
                            @endif
                        </p>
                    </div>

                    {{-- Erreurs Excel --}}
                    <div x-data x-show="@this.excelPreviewErrors.length > 0" x-cloak x-transition
                        class="mx-4 mt-4 rounded-xl border border-amber-500/25 bg-amber-500/10 p-4 text-amber-300 max-h-48 overflow-y-auto">
                        <p class="text-sm font-medium mb-2">
                            {{ count($excelPreviewErrors) }} ligne(s)/cellule(s) ignorée(s) :
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-xs text-amber-300/80">
                            @foreach ($excelPreviewErrors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                    @unless ($this->period)
                        <div class="p-10 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 mb-3">
                                <x-lucide-calendar class="w-6 h-6 text-amber-400" />
                            </div>
                            <p class="text-sm text-amber-400">
                                Sélectionnez un {{ $this->activeYear?->periodLabel() }} pour commencer.
                            </p>
                        </div>
                    @else
                        {{-- ===== IMPORT EXCEL ===== --}}
                        @if (!$this->subject->isConduite())
                            <div x-data x-show="@this.mode === 'excel'" x-cloak
                                x-transition:enter="transition ease-out duration-250"
                                x-transition:enter-start="opacity-0 translate-x-3"
                                x-transition:enter-end="opacity-100 translate-x-0" class="p-5 sm:p-6 space-y-5">

                                <div class="rounded-xl border border-white/[0.06] bg-[#070a12]/80 p-5">
                                    <p class="text-sm font-medium text-white mb-2">Format attendu</p>
                                    <p class="text-xs text-slate-500 mb-3">
                                        La première ligne doit contenir les en-têtes (ordre libre) :
                                    </p>
                                    <ul class="space-y-1.5 text-xs text-slate-400">
                                        <li class="flex items-start gap-2">
                                            <span class="w-1 h-1 rounded-full bg-indigo-400 mt-1.5 shrink-0"></span>
                                            <span>
                                                <span class="text-slate-200 font-medium">Matricule</span>
                                                — ou <span class="text-slate-200">Nom</span> + <span
                                                    class="text-slate-200">Prénoms</span>
                                            </span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="w-1 h-1 rounded-full bg-indigo-400 mt-1.5 shrink-0"></span>
                                            <span>
                                                <span class="text-slate-200 font-medium">Interro 1–4</span>
                                                — notes /20 (facultatives)
                                            </span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="w-1 h-1 rounded-full bg-indigo-400 mt-1.5 shrink-0"></span>
                                            <span>
                                                <span
                                                    class="text-slate-200 font-medium">{{ $this->devoirColumnLabels()['devoir1'] }}</span>,
                                                <span
                                                    class="text-slate-200 font-medium">{{ $this->devoirColumnLabels()['devoir2'] }}</span>
                                                — notes /20 (facultatives)
                                            </span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                    <input type="file" wire:model="excelFile" accept=".xlsx,.xls"
                                        class="block w-full text-sm text-slate-400
                                              file:h-10 file:px-4 file:rounded-xl file:border-0
                                              file:bg-indigo-500 file:text-white file:text-sm file:font-medium
                                              file:mr-3 file:cursor-pointer
                                              bg-[#070a12] border border-white/[0.08] rounded-xl
                                              transition-colors" />

                                    <x-lucide-loader-2 wire:loading.class="opacity-100"
                                        wire:loading.class.remove="opacity-0" wire:target="excelFile"
                                        class="w-5 h-5 text-indigo-400 animate-spin opacity-0 shrink-0" />

                                    <button wire:click="loadExcelFile" wire:loading.attr="disabled"
                                        wire:target="loadExcelFile,excelFile"
                                        class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-xl text-sm font-medium
                                               bg-emerald-500 hover:bg-emerald-400 text-white
                                               shadow-lg shadow-emerald-500/20
                                               transition-all disabled:opacity-50 shrink-0">
                                        <span wire:loading.remove wire:target="loadExcelFile"
                                            class="inline-flex items-center gap-2">
                                            <x-lucide-upload class="w-4 h-4" />
                                            Charger
                                        </span>
                                        <span wire:loading wire:target="loadExcelFile">
                                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                        </span>
                                    </button>
                                </div>

                                @error('excelFile')
                                    <p class="text-sm text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    @endunless
            </section>

            {{-- ===== SAISIE MANUELLE ===== --}}
            <div x-data x-show="@this.mode === 'manual'" x-cloak x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 -translate-x-3"
                x-transition:enter-end="opacity-100 translate-x-0">

                <div class="overflow-x-auto border border-slate-600" wire:loading.class="opacity-50"
                    wire:target="period">
                    <table class="w-full text-sm border-collapse " style="min-width: 700px;">
                        <thead>
                            <tr class="border-b border-white/[0.05]">
                                {{-- N° figé --}}
                                <th
                                    class="sticky left-0 z-20 bg-[#0c101c] px-2 py-3 text-center
                               text-[11px] font-bold uppercase tracking-wider text-slate-500
                               w-10 min-w-[40px]">
                                    N°
                                </th>
                                {{-- Apprenant figé — largeur réduite --}}
                                <th
                                    class="sticky left-10 z-20 bg-[#0c101c] px-3 py-3 text-left
                               text-[11px] font-bold uppercase tracking-wider text-slate-500
                               w-[140px] min-w-[140px] max-w-[140px]
                               sm:w-[180px] sm:min-w-[180px] sm:max-w-[180px]
                               shadow-[3px_0_6px_-3px_rgba(0,0,0,0.5)]">
                                    Apprenant
                                </th>

                                @if (!$this->subject->isConduite())
                                    <th
                                        class="px-3 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[150px]">
                                        Interros
                                        <span class="block font-normal normal-case text-slate-600 mt-0.5">max 4 ·
                                            séparées par -</span>
                                    </th>
                                @endif

                                @if (!$this->subject->isConduite())
                                    <th
                                        class="px-3 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[130px]">
                                        Devoirs
                                        <span class="block font-normal normal-case text-slate-600 mt-0.5">max 2 ·
                                            séparées par -</span>
                                    </th>
                                @else
                                    <th
                                        class="px-3 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[160px]">
                                        Conduite
                                        <span class="block font-normal normal-case text-slate-600 mt-0.5">Une seule
                                            note</span>
                                    </th>
                                @endif

                                <th
                                    class="px-3 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[100px]">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/[0.04]">
                            @foreach ($this->students as $student)
                                @php
                                    $pending = $this->pendingMarks[$student->id] ?? null;
                                    $existingTypes = (
                                        $this->existingMarksByStudent->get($student->id) ?? collect()
                                    )->pluck('type');
                                    $existingInterroCount = $existingTypes
                                        ->intersect(['interro1', 'interro2', 'interro3', 'interro4'])
                                        ->count();
                                    $existingDevoirCount = $existingTypes
                                        ->intersect($this->devoirTypesForTenant())
                                        ->count();
                                @endphp

                                <tr class="group hover:bg-white/[0.02] transition-colors duration-150"
                                    wire:key="student-row-{{ $student->id }}">

                                    {{-- N° figé --}}
                                    <td
                                        class="sticky left-0 z-10 bg-[#0c101c] group-hover:bg-[#0e1320]
                                   px-2 py-2.5 text-center font-mono text-xs text-slate-600
                                   transition-colors">
                                        {{ $loop->iteration }}
                                    </td>

                                    {{-- Apprenant figé — largeur réduite --}}
                                    <td
                                        class="sticky left-10 z-10 bg-[#0c101c] group-hover:bg-[#0e1320]
                                   px-3 py-2.5 transition-colors
                                   w-[140px] min-w-[140px] max-w-[140px]
                                   sm:w-[180px] sm:min-w-[180px] sm:max-w-[180px]
                                   shadow-[3px_0_6px_-3px_rgba(0,0,0,0.5)]">
                                        <div class="min-w-0">
                                            <p class="font-medium text-slate-200 truncate text-xs sm:text-sm leading-snug"
                                                title="{{ $student->getFullName() }}">
                                                {{ $student->getFullName() }}
                                            </p>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                @if ($student->gender)
                                                    <span class="text-[12px] font-mono uppercase text-amber-600">
                                                        {{ str()->initials($student->gender) }}/
                                                    </span>
                                                @endif
                                                @if (!$this->subject->isConduite())
                                                    <span class="text-[12px] text-slate-400 truncate">
                                                        Int : {{ $existingInterroCount }}/4 · Dev :
                                                        {{ $existingDevoirCount }}/2
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-slate-600">
                                                        {{ $existingDevoirCount }} note
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    @if (!$this->subject->isConduite())
                                        <td class="px-3 py-2.5">
                                            <input type="text" placeholder="12-09-13,5"
                                                wire:model="inputs.{{ $student->id }}.interro"
                                                @disabled($pending)
                                                class="w-full h-9 rounded-lg bg-[#070a12] border border-white/[0.08]
                                           px-2.5 text-sm font-mono text-slate-200 tracking-wide
                                           placeholder:text-slate-600
                                           focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                           outline-none transition-all
                                           {{ $pending ? 'opacity-40 cursor-not-allowed' : '' }}" />
                                        </td>
                                    @endif

                                    <td class="px-3 py-2.5">
                                        @if (!$this->subject->isConduite())
                                            <input type="text" placeholder="14-16"
                                                wire:model="inputs.{{ $student->id }}.devoir"
                                                @disabled($pending)
                                                class="w-full h-9 rounded-lg bg-[#070a12] border border-white/[0.08]
                                           px-2.5 text-sm font-mono text-slate-200 tracking-wide
                                           placeholder:text-slate-600
                                           focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                           outline-none transition-all
                                           {{ $pending ? 'opacity-40 cursor-not-allowed' : '' }}" />
                                        @else
                                            @if (!($pending || $existingDevoirCount > 0))
                                                <input type="text" placeholder="Conduite…"
                                                    wire:model="inputs.{{ $student->id }}.devoir"
                                                    @disabled($pending || $existingDevoirCount > 0)
                                                    class="w-full h-9 rounded-lg bg-[#070a12] border border-white/[0.08]
                                               px-2.5 text-sm font-mono text-slate-200 tracking-wide
                                               placeholder:text-slate-600
                                               focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                               outline-none transition-all
                                               {{ $pending ? 'opacity-40 cursor-not-allowed' : '' }}" />
                                            @else
                                                <input type="text" placeholder="Déjà renseignée"
                                                    wire:model="inputs.{{ $student->id }}.devoir"
                                                    @disabled(true)
                                                    class="w-full h-9 rounded-lg bg-[#070a12] border border-white/[0.08]
                                               px-2.5 text-sm font-mono text-slate-200 tracking-wide
                                               placeholder:text-slate-600 outline-none
                                               opacity-60 cursor-not-allowed" />
                                            @endif
                                        @endif
                                    </td>

                                    @if (!$this->subject->isConduite() || ($this->subject->isConduite() && !($existingDevoirCount > 0)))
                                        <td class="px-3 py-2.5">
                                            <div class="flex items-center justify-center gap-1">
                                                @if ($pending)
                                                    <button wire:click="editStudentMarks({{ $student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="editStudentMarks({{ $student->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs
                                                   bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                                                   hover:bg-indigo-500 hover:text-white hover:border-indigo-500
                                                   transition-all disabled:opacity-50">
                                                        <span wire:loading.remove
                                                            wire:target="editStudentMarks({{ $student->id }})">
                                                            <x-lucide-pen class="w-3.5 h-3.5" />
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="editStudentMarks({{ $student->id }})">
                                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                        </span>
                                                    </button>
                                                    <button wire:click="removeStudentMarks({{ $student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="removeStudentMarks({{ $student->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs
                                                   bg-rose-500/10 text-rose-400 border border-rose-500/20
                                                   hover:bg-rose-500 hover:text-white hover:border-rose-500
                                                   transition-all disabled:opacity-50">
                                                        <span wire:loading.remove
                                                            wire:target="removeStudentMarks({{ $student->id }})">
                                                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="removeStudentMarks({{ $student->id }})">
                                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                        </span>
                                                    </button>
                                                @else
                                                    <button wire:click="addStudentMarks({{ $student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="addStudentMarks({{ $student->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs
                                                   bg-emerald-500/15 text-emerald-400 border border-emerald-500/25
                                                   hover:bg-emerald-500 hover:text-white hover:border-emerald-500
                                                   transition-all disabled:opacity-50">
                                                        <span wire:loading.remove
                                                            wire:target="addStudentMarks({{ $student->id }})">
                                                            <x-lucide-plus class="w-3.5 h-3.5" />
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="addStudentMarks({{ $student->id }})">
                                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                        </span>
                                                    </button>
                                                    <button wire:click="resetStudentInputs({{ $student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="resetStudentInputs({{ $student->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs
                                                   bg-amber-500/10 text-amber-400 border border-amber-500/20
                                                   hover:bg-amber-500 hover:text-white hover:border-amber-500
                                                   transition-all disabled:opacity-50">
                                                        <span wire:loading.remove
                                                            wire:target="resetStudentInputs({{ $student->id }})">
                                                            <x-lucide-eraser class="w-3.5 h-3.5" />
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="resetStudentInputs({{ $student->id }})">
                                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                        </span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer --}}
                <div
                    class="px-5 sm:px-6 py-4 border-t border-white/[0.05]
                flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-sm text-slate-500">
                        <span class="text-slate-300 font-medium">{{ $this->students->count() }}</span> apprenants
                        ·
                        <span class="text-amber-400 font-medium">{{ count($this->pendingMarks) }}</span> en attente
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="resetAllPendingMarks" wire:loading.attr="disabled"
                            wire:target="resetAllPendingMarks" title="Effacer toutes les notes saisies"
                            class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                       bg-rose-500/10 text-rose-400 border border-rose-500/20
                       hover:bg-rose-500 hover:text-white hover:border-rose-500
                       transition-all disabled:opacity-50">
                            <span wire:loading.remove wire:target="resetAllPendingMarks"
                                class="inline-flex items-center gap-2">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                Tout effacer
                            </span>
                            <span wire:loading wire:target="resetAllPendingMarks">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                        <button wire:click="validateAllMarks" wire:loading.attr="disabled"
                            wire:target="validateAllMarks"
                            class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                       bg-emerald-500/15 text-emerald-400 border border-emerald-500/25
                       hover:bg-emerald-500 hover:text-white hover:border-emerald-500
                       transition-all disabled:opacity-50">
                            <span wire:loading.remove wire:target="validateAllMarks"
                                class="inline-flex items-center gap-2">
                                <x-lucide-check-check class="w-3.5 h-3.5" />
                                Valider tout
                            </span>
                            <span wire:loading wire:target="validateAllMarks">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @else
            {{-- Indisponible --}}
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] py-16 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-500/10 mb-4">
                    <x-lucide-lock class="w-7 h-7 text-amber-400" />
                </div>
                <p class="text-amber-400 font-medium mb-1">Saisie temporairement indisponible</p>
                <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">
                    Veuillez vous rapprocher de l’administration pour plus de détails.
                </p>
                <button wire:click="reloaddata" wire:loading.attr="disabled" wire:target="reloaddata"
                    class="inline-flex items-center gap-2 h-10 px-5 rounded-xl text-sm font-medium
                               bg-amber-500/15 text-amber-400 border border-amber-500/25
                               hover:bg-amber-500 hover:text-white hover:border-amber-500
                               transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="reloaddata" class="inline-flex items-center gap-2">
                        <x-lucide-refresh-cw class="w-4 h-4" />
                        Recharger
                    </span>
                    <span wire:loading wire:target="reloaddata">
                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    </span>
                </button>
            </div>
        @endif

    </div>
</div>

