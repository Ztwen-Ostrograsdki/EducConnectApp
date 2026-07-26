<div class="w-full overflow-x-hidden bg-slate-950">

    <div class="mx-auto w-full max-w-[1900px] p-3">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        @if ($this->activeYear && $this->activeYear->active_period && $this->period)
            <section class="mb-6 w-full">

                <div
                    class="rounded-lg bg-slate-950 border border-slate-800 p-2 flex items-center justify-between gap-2 w-full flex-wrap">

                    <div class="flex items-center gap-2 flex-wrap">

                        {{-- MODE SWITCH --}}
                        <div class="relative flex rounded-2xl border border-slate-800 overflow-hidden bg-slate-950 p-1">

                            {{-- Curseur animé qui glisse derrière le bouton actif --}}
                            <div class="absolute top-1 bottom-1 w-1/2 rounded-xl bg-indigo-500 transition-transform duration-300 ease-out"
                                style="transform: translateX({{ $mode === 'excel' ? '100%' : '0' }});">
                            </div>

                            <button type="button" wire:click="switchMode('manual')"
                                class="relative z-10 h-11 px-5 transition-colors duration-300 {{ $mode === 'manual' ? 'text-white' : 'text-slate-400' }}">
                                Saisie manuelle
                            </button>
                            <button type="button" wire:click="switchMode('excel')"
                                class="relative z-10 h-11 px-5 transition-colors duration-300 {{ $mode === 'excel' ? 'text-white' : 'text-slate-400' }}">
                                Import Excel
                            </button>
                        </div>

                        {{-- PERIOD --}}
                        <select wire:model.live="period"
                            class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-2 font-mono uppercase transition-colors duration-200">
                            <option disabled value="">Sélectionner le {{ $this->activeYear->periodLabel() }}
                            </option>
                            @foreach ($this->periods_types as $pv => $p)
                                @if ($this->activeYear && $this->activeYear->active_period == $p['index'])
                                    <option @disabled(!($this->activeYear && $this->activeYear->active_period == $p['index'])) value="{{ $p['index'] }}">{{ $p['label'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        <svg wire:loading.class="opacity-100 scale-100" wire:loading.class.remove="opacity-0 scale-75"
                            wire:target="period"
                            class="animate-spin h-5 w-5 text-slate-400 opacity-0 scale-75 transition-all duration-200"
                            viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                    </div>

                    {{-- ACTIONS --}}
                    <div x-data class="flex flex-wrap gap-2 font-mono" x-show="@this.mode === 'manual'" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1">

                        <button wire:click="validateAllMarks" wire:loading.attr="disabled"
                            wire:target="validateAllMarks"
                            class="relative h-10 px-4 rounded-xl bg-emerald-500/50 hover:bg-emerald-500 hover:text-black disabled:opacity-50 transition-all duration-200 flex items-center justify-center min-w-[110px] overflow-hidden">
                            <span wire:loading.class="opacity-0 scale-90" wire:target="validateAllMarks"
                                class="transition-all duration-200">Valider toutes les notes saisies</span>
                            <svg wire:loading.class="opacity-100 scale-100"
                                wire:loading.class.remove="opacity-0 scale-75" wire:target="validateAllMarks"
                                class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>
                            </svg>
                        </button>

                        <button wire:click="resetAllInputs" wire:loading.attr="disabled" wire:target="resetAllInputs"
                            class="relative h-10 px-4 rounded-xl bg-amber-500/30 hover:bg-amber-600 hover:text-black disabled:opacity-50 transition-all duration-200 flex items-center justify-center min-w-[110px] overflow-hidden">
                            <span wire:loading.class="opacity-0 scale-90" wire:target="resetAllInputs"
                                class="transition-all duration-200">Réinitialiser</span>
                            <svg wire:loading.class="opacity-100 scale-100"
                                wire:loading.class.remove="opacity-0 scale-75" wire:target="resetAllInputs"
                                class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>
                            </svg>
                        </button>

                    </div>

                </div>

            </section>

            <section class="relative">

                <div wire:loading wire:target='switchMode,resetAllInputs,reloaddata'
                    class="absolute inset-0 flex items-center justify-center bg-slate-800/5 backdrop-blur-xs"
                    style="z-index: 200 !important;">

                    <div
                        class="items-center text-slate-400 relative top-1/2 mx-auto flex justify-center flex-col gap-3">
                        <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        <span class="text-xl font-mono ls-1">Veuillez patienter...</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:text-sm text-xs mb-36">

                    <div class="space-y-6 min-w-0">

                        <div class="rounded-lg bg-slate-900 border border-slate-800 overflow-hidden p-2">

                            {{-- HEADER --}}
                            <div class="p-3 border-b border-slate-800 mb-2">

                                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-2">

                                    <div>
                                        <h2 class="text-xl font-semibold uppercase text-slate-400 font-mono">
                                            <span>
                                                Saisie des Notes de
                                            </span>
                                            <span class="text-yellow-600 ls-1">
                                                {{ $this->subject->code }}
                                            </span>
                                            du
                                            <span class="text-sky-500 text-shadow-2xs text-shadow-black">
                                                {{ $this->activeYear->periodLabel() . ' ' . $this->period }}
                                            </span>
                                        </h2>
                                        <p class="mt-1 text-slate-400 transition-all duration-200 font-mono">
                                            @if ($mode === 'manual')
                                                Ajoutez rapidement les notes des apprenants par saisie.
                                            @else
                                                Chargez les notes depuis un fichier Excel.
                                            @endif
                                        </p>
                                    </div>

                                </div>

                            </div>

                            <div x-data x-show="@this.excelPreviewErrors.length > 0" x-cloak
                                x-transition:enter="transition ease-out duration-250"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="mx-1 mb-3 rounded-lg border border-amber-500/30 bg-amber-500/10 p-4 text-amber-300 max-h-56 overflow-y-auto">
                                <p class="font-medium mb-2">
                                    {{ count($excelPreviewErrors) }} ligne(s)/cellule(s) ignorée(s) lors du dernier
                                    import :
                                </p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($excelPreviewErrors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            @unless ($this->period)
                                <div class="p-6 text-center text-amber-400 transition-opacity duration-300">
                                    Veuillez sélectionner un {{ $this->activeYear?->periodLabel() }} ci-dessus pour
                                    commencer
                                    la saisie des notes.
                                </div>
                            @else
                                <div class="relative">

                                    {{-- ================= PANNEAU IMPORT EXCEL ================= --}}
                                    <div x-data x-show="@this.mode === 'excel'" x-cloak
                                        x-transition:enter="transition ease-out duration-250"
                                        x-transition:enter-start="opacity-0 translate-x-4"
                                        x-transition:enter-end="opacity-100 translate-x-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-x-0"
                                        x-transition:leave-end="opacity-0 -translate-x-4" class="p-6 space-y-4">

                                        <div class="rounded-lg border border-slate-800 bg-slate-950 p-4 text-slate-400">
                                            <p class="font-medium text-slate-200 mb-2">Format attendu du fichier Excel</p>
                                            <p>La première ligne doit contenir les en-têtes suivants (l'ordre des colonnes
                                                n'a
                                                pas d'importance) :</p>
                                            <ul class="list-disc list-inside mt-2 space-y-1">
                                                <li>
                                                    <span class="text-slate-200">Matricule</span>
                                                    — ou à défaut les colonnes
                                                    <span class="text-slate-200">Nom</span> et <span
                                                        class="text-slate-200">Prénoms</span>
                                                    (identification sans tenir compte des accents/majuscules)
                                                </li>
                                                <li>
                                                    <span class="text-slate-200">Interro 1</span>,
                                                    <span class="text-slate-200">Interro 2</span>,
                                                    <span class="text-slate-200">Interro 3</span>,
                                                    <span class="text-slate-200">Interro 4</span>
                                                    — notes sur 20 (facultatives)
                                                </li>
                                                <li>
                                                    <span
                                                        class="text-slate-200">{{ $this->devoirColumnLabels()['devoir1'] }}</span>,
                                                    <span
                                                        class="text-slate-200">{{ $this->devoirColumnLabels()['devoir2'] }}</span>
                                                    — notes sur 20 (facultatives)
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">

                                            <input type="file" wire:model="excelFile" accept=".xlsx,.xls"
                                                class="block w-full text-slate-300 file:h-11 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-500 file:text-white file:mr-4 bg-slate-950 border border-slate-800 rounded-2xl transition-colors duration-200">

                                            <svg wire:loading.class="opacity-100 scale-100"
                                                wire:loading.class.remove="opacity-0 scale-75" wire:target="excelFile"
                                                class="animate-spin h-5 w-5 text-slate-400 shrink-0 opacity-0 scale-75 transition-all duration-200"
                                                viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>

                                            <button wire:click="loadExcelFile" wire:loading.attr="disabled"
                                                wire:target="loadExcelFile,excelFile"
                                                class="relative h-11 px-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 transition-all duration-200 flex items-center justify-center shrink-0  overflow-hidden">
                                                <span wire:loading.class="opacity-0 scale-90" wire:target="loadExcelFile"
                                                    class="transition-all duration-200">Charger les notes</span>
                                                <svg wire:loading.class="opacity-100 scale-100"
                                                    wire:loading.class.remove="opacity-0 scale-75"
                                                    wire:target="loadExcelFile"
                                                    class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                </svg>
                                            </button>

                                        </div>

                                        @error('excelFile')
                                            <p class="text-rose-400">{{ $message }}</p>
                                        @enderror

                                    </div>

                                    {{-- ================= TABLEAU SAISIE MANUELLE ================= --}}
                                    <div x-data x-show="@this.mode === 'manual'" x-cloak
                                        x-transition:enter="transition ease-out duration-250"
                                        x-transition:enter-start="opacity-0 -translate-x-4"
                                        x-transition:enter-end="opacity-100 translate-x-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-x-0"
                                        x-transition:leave-end="opacity-0 translate-x-4">

                                        <div class="overflow-x-auto bg-slate-900 transition-opacity duration-300"
                                            wire:loading.class="opacity-50" wire:target="period">

                                            <table class="table-fixed bg-slate-900 z-table-border mb-8"
                                                style="width: 1200px; min-width: 1200px;">

                                                <colgroup>
                                                    <col style="width: 60px;">
                                                    <col style="width: 400px;">
                                                    <col style="width: 320px;">
                                                    <col style="width: 320px;">
                                                    <col style="width: 200px;">
                                                </colgroup>

                                                <thead class="bg-slate-950 border-b border-slate-800">
                                                    <tr>
                                                        <th class="px-2 py-2 text-center text-slate-400">N°</th>
                                                        <th class="px-2 py-2 text-center text-slate-400">Apprenant</th>
                                                        <th class="px-2 py-2 text-center text-slate-400">
                                                            <span class="inline-flex flex-col gap-1">
                                                                <span>Notes Interrogations</span>
                                                                <span>(max : 4, séparées par un tiret -)</span>
                                                            </span>
                                                        </th>
                                                        <th class="px-2 py-2 text-center text-slate-400">
                                                            <span class="inline-flex flex-col gap-1">
                                                                <span>Note devoirs</span>
                                                                <span>(max : 2, séparées par un tiret -)</span>
                                                            </span>
                                                        </th>
                                                        <th class="px-2 py-2 text-center text-slate-400">Actions</th>
                                                    </tr>
                                                </thead>

                                                <tbody class="divide-y divide-slate-800">

                                                    @foreach ($this->students as $student)
                                                        @php
                                                            $pending = $this->pendingMarks[$student->id] ?? null;
                                                            $existingTypes = (
                                                                $this->existingMarksByStudent->get($student->id) ??
                                                                collect()
                                                            )->pluck('type');
                                                            $existingInterroCount = $existingTypes
                                                                ->intersect([
                                                                    'interro1',
                                                                    'interro2',
                                                                    'interro3',
                                                                    'interro4',
                                                                ])
                                                                ->count();
                                                            $existingDevoirCount = $existingTypes
                                                                ->intersect($this->devoirTypesForTenant())
                                                                ->count();
                                                        @endphp

                                                        <tr class="hover:bg-slate-800/40 transition-colors duration-150"
                                                            wire:key="student-row-{{ $student->id }}">

                                                            {{-- STUDENT --}}
                                                            <td
                                                                class="px-2 py-2 truncate text-center font-mono text-slate-400">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td class="px-2 py-2 align-middle">
                                                                <div>
                                                                    <div
                                                                        class="font-medium w-full transition flex justify-between">
                                                                        <span
                                                                            class="group-hover:underline underline-offset-4 group-hover:text-sky-500 font-mono text-slate-300">{{ $student->getFullName() }}</span>
                                                                        @if ($student->gender)
                                                                            <span
                                                                                class="uppercase float-right text-slate-500 font-mono py-1 px-2 bg-slate-950 shadow-sm shadow-sky-700 group-hover:shadow-orange-500 rounded-lg">{{ str()->initials($student->gender) }}</span>
                                                                        @endif
                                                                    </div>

                                                                    <p class="text-xs text-slate-500 mt-1">
                                                                        {{ $existingInterroCount }}/4 interros ·
                                                                        {{ $existingDevoirCount }}/2 devoirs déjà
                                                                        enregistrés
                                                                    </p>
                                                                </div>
                                                            </td>

                                                            {{-- INTERRO --}}
                                                            <td class="px-2 py-2 align-middle">
                                                                <input type="text" placeholder="12-09-13,5"
                                                                    wire:model="inputs.{{ $student->id }}.interro"
                                                                    @disabled($pending)
                                                                    class="w-full h-11  rounded-2xl bg-slate-950 border border-slate-800 px-4 text-left font-mono text-base tracking-wide transition-all duration-200 {{ $pending ? 'opacity-50 cursor-not-allowed' : '' }}">
                                                            </td>

                                                            {{-- DEVOIR --}}
                                                            <td class="px-2 py-2 align-middle">
                                                                <input type="text" placeholder="14-16"
                                                                    wire:model="inputs.{{ $student->id }}.devoir"
                                                                    @disabled($pending)
                                                                    class="w-full h-11 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-left font-mono text-base tracking-wide transition-all duration-200 {{ $pending ? 'opacity-50 cursor-not-allowed' : '' }}">
                                                            </td>

                                                            {{-- ACTIONS --}}
                                                            <td class="px-2 py-2 align-middle">
                                                                <div class="flex justify-center gap-2">

                                                                    @if ($pending)
                                                                        <button
                                                                            wire:click="editStudentMarks({{ $student->id }})"
                                                                            wire:loading.attr="disabled"
                                                                            wire:target="editStudentMarks({{ $student->id }})"
                                                                            class="relative h-11 px-4 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 disabled:opacity-50 transition-all duration-200 flex items-center justify-center min-w-[90px] overflow-hidden">
                                                                            <span wire:loading.class="opacity-0 scale-90"
                                                                                wire:target="editStudentMarks({{ $student->id }})"
                                                                                class="transition-all duration-200">Modifier</span>
                                                                            <svg wire:loading.class="opacity-100 scale-100"
                                                                                wire:loading.class.remove="opacity-0 scale-75"
                                                                                wire:target="editStudentMarks({{ $student->id }})"
                                                                                class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                                                viewBox="0 0 24 24" fill="none">
                                                                                <circle class="opacity-25" cx="12"
                                                                                    cy="12" r="10"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="4">
                                                                                </circle>
                                                                                <path class="opacity-75"
                                                                                    fill="currentColor"
                                                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                                                                </path>
                                                                            </svg>
                                                                        </button>

                                                                        <button
                                                                            wire:click="removeStudentMarks({{ $student->id }})"
                                                                            wire:loading.attr="disabled"
                                                                            wire:target="removeStudentMarks({{ $student->id }})"
                                                                            class="relative h-11 px-4 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 disabled:opacity-50 transition-all duration-200 flex items-center justify-center min-w-[90px] overflow-hidden">
                                                                            <span wire:loading.class="opacity-0 scale-90"
                                                                                wire:target="removeStudentMarks({{ $student->id }})"
                                                                                class="transition-all duration-200">Effacer</span>
                                                                            <svg wire:loading.class="opacity-100 scale-100"
                                                                                wire:loading.class.remove="opacity-0 scale-75"
                                                                                wire:target="removeStudentMarks({{ $student->id }})"
                                                                                class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                                                viewBox="0 0 24 24" fill="none">
                                                                                <circle class="opacity-25" cx="12"
                                                                                    cy="12" r="10"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="4">
                                                                                </circle>
                                                                                <path class="opacity-75"
                                                                                    fill="currentColor"
                                                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                                                                </path>
                                                                            </svg>
                                                                        </button>
                                                                    @else
                                                                        <button
                                                                            wire:click="addStudentMarks({{ $student->id }})"
                                                                            wire:loading.attr="disabled"
                                                                            wire:target="addStudentMarks({{ $student->id }})"
                                                                            class="relative h-11 px-4 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 disabled:opacity-50 transition-all duration-200 flex items-center justify-center min-w-[90px] overflow-hidden">
                                                                            <span wire:loading.class="opacity-0 scale-90"
                                                                                wire:target="addStudentMarks({{ $student->id }})"
                                                                                class="transition-all duration-200">Insérer</span>
                                                                            <svg wire:loading.class="opacity-100 scale-100"
                                                                                wire:loading.class.remove="opacity-0 scale-75"
                                                                                wire:target="addStudentMarks({{ $student->id }})"
                                                                                class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                                                viewBox="0 0 24 24" fill="none">
                                                                                <circle class="opacity-25" cx="12"
                                                                                    cy="12" r="10"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="4">
                                                                                </circle>
                                                                                <path class="opacity-75"
                                                                                    fill="currentColor"
                                                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                                                                </path>
                                                                            </svg>
                                                                        </button>

                                                                        <button
                                                                            wire:click="resetStudentInputs({{ $student->id }})"
                                                                            wire:loading.attr="disabled"
                                                                            wire:target="resetStudentInputs({{ $student->id }})"
                                                                            class="relative h-11 px-4 rounded-xl bg-orange-500/10 text-orange-400 border border-orange-500/20 disabled:opacity-50 transition-all duration-200 flex items-center justify-center min-w-[90px] overflow-hidden">
                                                                            <span wire:loading.class="opacity-0 scale-90"
                                                                                wire:target="resetStudentInputs({{ $student->id }})"
                                                                                class="transition-all duration-200">Effacer</span>
                                                                            <svg wire:loading.class="opacity-100 scale-100"
                                                                                wire:loading.class.remove="opacity-0 scale-75"
                                                                                wire:target="resetStudentInputs({{ $student->id }})"
                                                                                class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                                                viewBox="0 0 24 24" fill="none">
                                                                                <circle class="opacity-25" cx="12"
                                                                                    cy="12" r="10"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="4">
                                                                                </circle>
                                                                                <path class="opacity-75"
                                                                                    fill="currentColor"
                                                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                                                                </path>
                                                                            </svg>
                                                                        </button>
                                                                    @endif

                                                                </div>
                                                            </td>

                                                        </tr>
                                                    @endforeach

                                                </tbody>

                                            </table>

                                        </div>

                                        {{-- FOOTER --}}
                                        <div class="p-5 border-t border-slate-800">

                                            <div
                                                class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                                <div class="text-slate-400">
                                                    {{ $this->students->count() }} apprenants —
                                                    {{ count($this->pendingMarks) }} en
                                                    attente d'enregistrement
                                                </div>

                                                <div class="flex flex-wrap gap-3 font-mono">

                                                    <button
                                                        title="Toutes les notes que vous avez saisies maintenant seront effacées"
                                                        wire:click="resetAllPendingMarks" wire:loading.attr="disabled"
                                                        wire:target="resetAllPendingMarks"
                                                        class="relative h-11 px-4 rounded-2xl bg-red-500/40 hover:text-red-300 hover:bg-red-800 disabled:opacity-50 transition-all duration-200 flex items-center justify-center overflow-hidden">
                                                        <span wire:loading.class="opacity-0 scale-90"
                                                            wire:target="resetAllPendingMarks"
                                                            class="transition-all duration-200">Effacer
                                                            toutes les notes saisies</span>
                                                        <svg wire:loading.class="opacity-100 scale-100"
                                                            wire:loading.class.remove="opacity-0 scale-75"
                                                            wire:target="resetAllPendingMarks"
                                                            class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                        </svg>
                                                    </button>

                                                    <button wire:click="validateAllMarks" wire:loading.attr="disabled"
                                                        wire:target="validateAllMarks"
                                                        class="relative h-11 px-4 rounded-2xl bg-emerald-500/30 hover:text-green-200 hover:bg-emerald-700 disabled:opacity-50 transition-all duration-200 flex items-center justify-center overflow-hidden">
                                                        <span wire:loading.class="opacity-0 scale-90"
                                                            wire:target="validateAllMarks"
                                                            class="transition-all duration-200">Valider toutes les
                                                            notes saisies</span>
                                                        <svg wire:loading.class="opacity-100 scale-100"
                                                            wire:loading.class.remove="opacity-0 scale-75"
                                                            wire:target="validateAllMarks"
                                                            class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                        </svg>
                                                    </button>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            @endunless

                        </div>

                    </div>

                </div>

            </section>
        @else
            <div
                class="flex-col flex gap-3 items-center justify-center text-sm border-t border-t-slate-600 py-3 my-3 mt-10">
                <h5
                    class="text-orange-500 rounded-2xl p-3 bg-orange-600/25 animate-pulse flex flex-col gap-3 mb-32 mt-14">
                    <span class="flex flex-col gap-1 text-lg">
                        <span>Désolé, l'insertion et la saisie des notes sont temporairement indisponibles</span>
                        <span>
                            Veuillez vous rapprocher de vos administratifs, pour avoir plus de détails !
                        </span>
                    </span>
                    <span>
                        <button wire:click="reloaddata" wire:loading.attr="disabled" wire:target="reloaddata"
                            class="relative text-white h-10 px-4 rounded-xl bg-amber-500/30 hover:bg-amber-600 hover:text-black disabled:opacity-50 transition-all duration-200 flex items-center justify-center min-w-[110px] overflow-hidden">
                            <span wire:loading.class="opacity-0 scale-90" wire:target="reloaddata"
                                class="transition-all duration-200">
                                <span class="flex items-center gap-2">
                                    <x-lucide-recycle class="w-4 h-4" />
                                    <span>Recharger la page</span>
                                </span>
                            </span>
                            <svg wire:loading.class="opacity-100 scale-100"
                                wire:loading.class.remove="opacity-0 scale-75" wire:target="reloaddata"
                                class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>
                            </svg>
                        </button>
                    </span>
                </h5>

            </div>
        @endif

    </div>

</div>

