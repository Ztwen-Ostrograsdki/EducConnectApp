<div class="w-full overflow-x-hidden bg-slate-950">

    <div class="mx-auto w-full max-w-[1900px] p-3 mb-16">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        <section class="mb-6 w-full">

            <div
                class="rounded-lg bg-slate-950 border border-slate-800 p-2 flex items-center justify-between gap-2 w-full flex-wrap">

                <div class="flex items-center gap-2">

                    {{-- PERIOD --}}
                    <select wire:model.live="period"
                        class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-6 font-mono uppercase">
                        <option value="">Sélectionner la période</option>
                        @foreach ($this->periods_types as $pv => $p)
                            <option value="{{ $pv }}">{{ $p }}</option>
                        @endforeach
                    </select>

                    <svg wire:loading wire:target="period" class="animate-spin h-5 w-5 text-slate-400"
                        viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-wrap gap-2">

                    <button wire:click="validateAllMarks" wire:loading.attr="disabled" wire:target="validateAllMarks"
                        class="h-10 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 flex items-center justify-center gap-2 min-w-[110px]">
                        <span wire:loading.remove wire:target="validateAllMarks">Tout Valider</span>
                        <svg wire:loading wire:target="validateAllMarks" class="animate-spin h-4 w-4"
                            viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>

                    <button wire:click="resetAllInputs" wire:loading.attr="disabled" wire:target="resetAllInputs"
                        class="h-10 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 disabled:opacity-50 flex items-center justify-center gap-2 min-w-[110px]">
                        <span wire:loading.remove wire:target="resetAllInputs">Réinitialiser</span>
                        <svg wire:loading wire:target="resetAllInputs" class="animate-spin h-4 w-4" viewBox="0 0 24 24"
                            fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>

                </div>

            </div>

        </section>

        <section>

            <div class="grid grid-cols-1 md:text-sm text-xs text-slate-300">

                <div class="space-y-6 min-w-0">

                    <div class="rounded-lg bg-slate-900 border border-slate-800 overflow-hidden p-2">

                        {{-- HEADER --}}
                        <div class="p-3 border-b border-slate-800 mb-2">

                            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-2">

                                <div>
                                    <h2 class="text-xl font-semibold">Saisie des Notes de {{ $this->subject->code }}
                                    </h2>
                                    <p class="mt-1 text-slate-400">Ajoutez rapidement les notes des apprenants.
                                    </p>
                                </div>

                            </div>

                        </div>

                        @unless ($this->period)

                            <div class="p-6 text-center text-amber-400">
                                Veuillez sélectionner une période ci-dessus pour commencer la saisie des notes.
                            </div>
                        @else
                            <div class="overflow-x-auto bg-slate-900" wire:loading.class="opacity-50" wire:target="period">

                                <table class="table-fixed bg-slate-900 z-table-border mb-10"
                                    style="width: 1400px; min-width: 1400px;">

                                    <colgroup>
                                        <col style="width: 80px;">
                                        <col style="width: 400px;">
                                        <col style="width: 320px;">
                                        <col style="width: 320px;">
                                        <col style="width: 280px;">
                                    </colgroup>

                                    <thead class="bg-slate-950 border-b border-slate-800">
                                        <tr>
                                            <th class="px-6 py-2 text-left text-slate-400">N°</th>
                                            <th class="px-6 py-2 text-left text-slate-400">Apprenant</th>
                                            <th class="px-6 py-2 text-center text-slate-400">
                                                <span class="inline-flex flex-col gap-1">
                                                    <span>Notes Interrogations</span>
                                                    <span>(max : 4, séparées par un tiret -)</span>
                                                </span>
                                            </th>
                                            <th class="px-6 py-2 text-center text-slate-400">
                                                <span class="inline-flex flex-col gap-1">
                                                    <span>Note devoirs</span>
                                                    <span>(max : 2, séparées par un tiret -)</span>
                                                </span>
                                            </th>
                                            <th class="px-6 py-2 text-center text-slate-400">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-800">

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

                                            <tr class="hover:bg-slate-800/40" wire:key="student-row-{{ $student->id }}">

                                                {{-- STUDENT --}}
                                                <td class="px-6 py-2 text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-2 align-middle">
                                                    <div>
                                                        <div class="font-medium w-full transition flex justify-between">
                                                            <span
                                                                class="group-hover:underline underline-offset-4 group-hover:text-sky-500 font-mono text-slate-300">{{ $student->getFullName() }}</span>
                                                            @if ($student->gender)
                                                                <span
                                                                    class="uppercase float-right text-slate-500 font-mono py-1 px-2 bg-slate-950 shadow-sm shadow-sky-700 group-hover:shadow-orange-500 rounded-lg">{{ str()->initials($student->gender) }}</span>
                                                            @endif
                                                        </div>

                                                        <p class="text-xs text-slate-500 mt-1">
                                                            {{ $existingInterroCount }}/4 interros ·
                                                            {{ $existingDevoirCount }}/2 devoirs déjà enregistrés
                                                        </p>
                                                    </div>
                                                </td>

                                                {{-- INTERRO --}}
                                                <td class="px-6 py-2 align-middle">
                                                    <input type="text" placeholder="12-09-13,5"
                                                        wire:model="inputs.{{ $student->id }}.interro"
                                                        @disabled($pending)
                                                        class="w-full h-11 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-left font-mono  tracking-wide {{ $pending ? 'opacity-50 cursor-not-allowed' : '' }}">
                                                </td>

                                                {{-- DEVOIR --}}
                                                <td class="px-6 py-2 align-middle">
                                                    <input type="text" placeholder="14-16"
                                                        wire:model="inputs.{{ $student->id }}.devoir"
                                                        @disabled($pending)
                                                        class="w-full h-11 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-left font-mono  tracking-wide {{ $pending ? 'opacity-50 cursor-not-allowed' : '' }}">
                                                </td>

                                                {{-- ACTIONS --}}
                                                <td class="px-6 py-2 align-middle">
                                                    <div class="flex justify-center gap-2">

                                                        @if ($pending)
                                                            <button wire:click="editStudentMarks({{ $student->id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="editStudentMarks({{ $student->id }})"
                                                                class="h-11 px-4 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 disabled:opacity-50 flex items-center justify-center min-w-[90px]">
                                                                <span wire:loading.remove
                                                                    wire:target="editStudentMarks({{ $student->id }})">Modifier</span>
                                                                <svg wire:loading
                                                                    wire:target="editStudentMarks({{ $student->id }})"
                                                                    class="animate-spin h-4 w-4" viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4">
                                                                    </circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                                </svg>
                                                            </button>

                                                            <button wire:click="removeStudentMarks({{ $student->id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="removeStudentMarks({{ $student->id }})"
                                                                class="h-11 px-4 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 disabled:opacity-50 flex items-center justify-center min-w-[90px]">
                                                                <span wire:loading.remove
                                                                    wire:target="removeStudentMarks({{ $student->id }})">Retirer</span>
                                                                <svg wire:loading
                                                                    wire:target="removeStudentMarks({{ $student->id }})"
                                                                    class="animate-spin h-4 w-4" viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                                </svg>
                                                            </button>
                                                        @else
                                                            <button wire:click="addStudentMarks({{ $student->id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="addStudentMarks({{ $student->id }})"
                                                                class="h-11 px-4 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 disabled:opacity-50 flex items-center justify-center min-w-[90px]">
                                                                <span wire:loading.remove
                                                                    wire:target="addStudentMarks({{ $student->id }})">Insérer</span>
                                                                <svg wire:loading
                                                                    wire:target="addStudentMarks({{ $student->id }})"
                                                                    class="animate-spin h-4 w-4" viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                                </svg>
                                                            </button>

                                                            <button wire:click="resetStudentInputs({{ $student->id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="resetStudentInputs({{ $student->id }})"
                                                                class="h-11 px-4 rounded-xl bg-orange-500/10 text-orange-400 border border-orange-500/20 disabled:opacity-50 flex items-center justify-center min-w-[90px]">
                                                                <span wire:loading.remove
                                                                    wire:target="resetStudentInputs({{ $student->id }})">Effacer</span>
                                                                <svg wire:loading
                                                                    wire:target="resetStudentInputs({{ $student->id }})"
                                                                    class="animate-spin h-4 w-4" viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
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

                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                    <div class="text-slate-400">
                                        {{ $this->students->count() }} apprenants — {{ count($this->pendingMarks) }} en
                                        attente d'enregistrement
                                    </div>

                                    <div class="flex flex-wrap gap-3">

                                        <button wire:click="resetAllInputs" wire:loading.attr="disabled"
                                            wire:target="resetAllInputs"
                                            class="h-11 px-6 rounded-2xl bg-amber-500 hover:bg-amber-600 disabled:opacity-50 flex items-center justify-center gap-2">
                                            <span wire:loading.remove wire:target="resetAllInputs">Réinitialiser
                                                Toutes les notes en cours</span>
                                            <svg wire:loading wire:target="resetAllInputs" class="animate-spin h-4 w-4"
                                                viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </button>

                                        <button wire:click="validateAllMarks" wire:loading.attr="disabled"
                                            wire:target="validateAllMarks"
                                            class="h-11 px-6 rounded-2xl bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 flex items-center justify-center gap-2">
                                            <span wire:loading.remove wire:target="validateAllMarks">Valider Toutes les
                                                Notes</span>
                                            <svg wire:loading wire:target="validateAllMarks" class="animate-spin h-4 w-4"
                                                viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </button>

                                    </div>

                                </div>

                            </div>

                        @endunless

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

