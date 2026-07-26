<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] bg-slate-950 p-3">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        <section class="mb-6">
            <div class="rounded-lg bg-slate-900 border border-slate-800 p-5">
                <div class="flex  flex-col gap-4">
                    <div class="md:grid md:grid-cols-6 flex flex-col gap-2">
                        {{-- SEARCH --}}
                        <input type="text" placeholder="Rechercher apprenant..."
                            class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 md:col-span-5">

                        {{-- BUTTON --}}
                        <button class="h-12 rounded-2xl bg-indigo-500 hover:bg-indigo-600 md:col-span-1">
                            Réinitialiser
                        </button>
                    </div>

                    {{-- PERIOD --}}
                    <div class="md:grid md:grid-cols-3 flex w-full">
                        <select wire:model.live="period"
                            class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-2 font-mono uppercase transition-colors duration-200 flex w-full">
                            <option disabled value="">Sélectionner le {{ $this->activeYear->periodLabel() }}
                            </option>
                            @foreach ($this->periods_types as $pv => $p)
                                <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- MAIN --}}
        {{-- ===================================================== --}}
        <section class="">
            <div class="grid grid-cols-1 gap-6 md:text-sm text-xs mb-32">

                <div class="space-y-6 min-w-0 ">

                    <div class="rounded-lg bg-slate-900 border border-slate-800 overflow-hidden p-1">

                        {{-- HEADER --}}
                        <div class="p-5 border-b border-slate-800">
                            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                                <div>
                                    <h2 class="text-xl font-semibold">Les notes de classe</h2>
                                    <p class="mt-1  text-slate-400">Gestion complète des notes des apprenants.
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <button class="h-10 px-4 rounded-xl bg-sky-500 hover:bg-sky-600">
                                        Import Excel
                                    </button>
                                </div>

                            </div>
                        </div>

                        @php
                            // Nombre total de colonnes du tableau, utilisé pour le colspan
                            // du récap final ainsi que de la ligne d'édition inline.
                            $showActionsColumn =
                                $this->activeYear &&
                                $this->activeYear->is_active &&
                                $this->activeYear->active_period === $period;
                            $totalColumns =
                                1 + 4 + 1 + count($this->devoirColumns()) + 1 + 1 + 1 + ($showActionsColumn ? 1 : 0);
                        @endphp

                        <div class="overflow-x-auto font-mono p-2 mt-4 mb-8">

                            <table class="w-full border-collapse z-table-border">

                                <colgroup>
                                    <col class="w-[400px] min-w-[400px]">
                                    <col class="w-[90px] min-w-[90px]">
                                    <col class="w-[90px] min-w-[90px]">
                                    <col class="w-[90px] min-w-[90px]">
                                    <col class="w-[90px] min-w-[90px]">
                                    <col class="w-[100px] min-w-[100px]">
                                    @foreach ($this->devoirColumns() as $type => $label)
                                        <col class="w-[90px] min-w-[90px]">
                                    @endforeach
                                    <col class="w-[100px] min-w-[100px]">
                                    <col class="w-[110px] min-w-[110px]">
                                    <col class="w-[80px] min-w-[80px]">
                                    @if ($showActionsColumn)
                                        <col class="w-[190px] min-w-[190px]">
                                    @endif
                                </colgroup>

                                <thead class="bg-slate-950 border-b border-slate-800">
                                    <tr>
                                        <th
                                            class="sticky left-0 z-10 bg-slate-950 px-2 py-2 text-center  text-slate-400 whitespace-nowrap">
                                            Apprenant
                                        </th>

                                        <th class="px-2 py-2 text-center  text-slate-400 whitespace-nowrap">
                                            Int 1</th>
                                        <th class="px-2 py-2 text-center  text-slate-400 whitespace-nowrap">
                                            Int 2</th>
                                        <th class="px-2 py-2 text-center  text-slate-400 whitespace-nowrap">
                                            Int 3</th>
                                        <th class="px-2 py-2 text-center  text-slate-400 whitespace-nowrap">
                                            Int 4</th>

                                        <th class="px-2 py-2 text-center  text-indigo-400 whitespace-nowrap">
                                            Moy. Int
                                        </th>

                                        @foreach ($this->devoirColumns() as $type => $label)
                                            <th class="px-2 py-2 text-center  text-slate-400 whitespace-nowrap">
                                                {{ $label }}
                                            </th>
                                        @endforeach

                                        <th class="px-2 py-2 text-center  text-emerald-400 whitespace-nowrap">
                                            Moy.
                                        </th>

                                        <th class="px-2 py-2 text-center  text-emerald-400 whitespace-nowrap">
                                            Moy. Coef.
                                        </th>

                                        <th class="px-2 py-2 text-center  text-slate-400 whitespace-nowrap">
                                            Rang
                                        </th>

                                        @if ($showActionsColumn)
                                            <th class="px-2 py-2 text-center  text-slate-400 whitespace-nowrap">
                                                Modifier notes
                                            </th>
                                        @endif
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @forelse ($this->studentsRows as $row)
                                        @php $student = $row['student']; @endphp

                                        <tr wire:key="student-row-{{ $student->id }}"
                                            class="hover:bg-slate-800/40 {{ $editingStudentId === $student->id ? 'bg-indigo-500/10' : '' }}">

                                            {{-- STUDENT (sticky) --}}
                                            <td class="sticky left-0 z-10 bg-slate-900 px-6 py-2 group">
                                                <div class="flex items-center gap-4">
                                                    <div class="p-3 shrink-0 rounded-2xl bg-slate-800">
                                                        {{ $loop->iteration }}
                                                    </div>
                                                    <div class="flex flex-col min-w-0 w-full">
                                                        <div
                                                            class="font-medium w-full transition flex justify-between items-center gap-1 min-w-0">
                                                            <span
                                                                class="group-hover:underline underline-offset-4 group-hover:text-sky-500 font-mono text-slate-300">{{ $student->getFullName() }}</span>
                                                            @if ($student->gender)
                                                                <span
                                                                    class="shrink-0 uppercase text-slate-500 font-mono text-xs py-1 px-2 bg-slate-950 shadow-sm shadow-sky-700 group-hover:shadow-orange-500">{{ str()->initials($student->gender) }}</span>
                                                            @endif
                                                        </div>
                                                        <p class="text-xs text-slate-500 mt-0.5 truncate">
                                                            @if ($student->educMaster)
                                                                <span
                                                                    class="font-mono">{{ $student->educMaster }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- INTERROS --}}
                                            @foreach (['interro1', 'interro2', 'interro3', 'interro4'] as $type)
                                                <td class="px-2 py-2 text-center whitespace-nowrap">
                                                    @if (!is_null($row['marks'][$type]))
                                                        {{ number_format($row['marks'][$type], 2) }}
                                                    @else
                                                        <span class="text-slate-600">—</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            {{-- MOY. INTERRO --}}
                                            <td
                                                class="px-2 py-2 text-center font-medium whitespace-nowrap {{ !is_null($row['moy_interro']) ? 'text-indigo-400' : 'text-slate-600' }}">
                                                {{ !is_null($row['moy_interro']) ? number_format($row['moy_interro'], 2) : '—' }}
                                            </td>

                                            {{-- DEVOIRS --}}
                                            @foreach ($this->devoirColumns() as $type => $label)
                                                <td class="px-2 py-2 text-center whitespace-nowrap">
                                                    @if (!is_null($row['marks'][$type]))
                                                        {{ number_format($row['marks'][$type], 2) }}
                                                    @else
                                                        <span class="text-slate-600">—</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            {{-- MOY --}}
                                            <td
                                                class="px-2 py-2 text-center font-semibold whitespace-nowrap {{ !is_null($row['moy']) ? 'text-emerald-400' : 'text-slate-600' }}">
                                                {{ !is_null($row['moy']) ? number_format($row['moy'], 2) : '—' }}
                                            </td>

                                            {{-- MOY. COEF --}}
                                            <td
                                                class="px-2 py-2 text-center font-semibold whitespace-nowrap {{ !is_null($row['moy_coef']) ? 'text-emerald-400' : 'text-slate-600' }}">
                                                {{ !is_null($row['moy_coef']) ? number_format($row['moy_coef'], 2) : '—' }}
                                            </td>

                                            {{-- RANK --}}
                                            <td class="px-2 py-2 text-center whitespace-nowrap">
                                                {{ $row['rank'] ? '#' . $row['rank'] : '—' }}
                                            </td>

                                            {{-- ACTIONS --}}
                                            @if ($showActionsColumn)
                                                <td class="px-6 py-2">
                                                    <div class="flex justify-center gap-2">
                                                        <button wire:key="edit-btn-{{ $student->id }}"
                                                            title="Editer les notes de {{ $student->getFullName() }}"
                                                            wire:click="editStudentMark({{ $student->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editStudentMark({{ $student->id }})"
                                                            @if (isset($pendingEdits[$student->id])) disabled title="Modification en attente" @endif
                                                            class="relative w-full sm:w-auto px-4 py-3 rounded-2xl {{ isset($pendingEdits[$student->id]) ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-600/30 hover:bg-slate-600/60 text-white' }} font-medium inline-flex items-center justify-center gap-1.5 transition-all duration-300 whitespace-nowrap disabled:opacity-50">
                                                            <span wire:loading.remove
                                                                wire:target="editStudentMark({{ $student->id }})"
                                                                class="inline-flex items-center gap-2">
                                                                <x-lucide-pen class="w-4 h-4" />
                                                            </span>
                                                            <span wire:loading
                                                                wire:target="editStudentMark({{ $student->id }})"
                                                                class="inline-flex items-center gap-2">
                                                                <span class="inline-flex items-center gap-2">
                                                                    <svg class="animate-spin w-4 h-4" fill="none"
                                                                        viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12"
                                                                            cy="12" r="10" stroke="currentColor"
                                                                            stroke-width="4" />
                                                                        <path class="opacity-75" fill="currentColor"
                                                                            d="M4 12a8 8 0 018-8v8z" />
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </button>

                                                    </div>
                                                </td>
                                            @endif

                                        </tr>

                                        @if ($editingStudentId === $student->id)
                                            <tr wire:key="edit-row-{{ $student->id }}" class="bg-indigo-500/5">
                                                <td colspan="{{ $totalColumns }}" class="p-0">
                                                    <div wire:transition.opacity.duration.200ms
                                                        class="sticky left-0 z-20 w-[calc(80vw-1.5rem)] max-w-2xl m-3 rounded-lg border border-indigo-500/30 bg-slate-950 p-5">

                                                        <div class="flex items-center justify-between gap-4 mb-2">
                                                            <h3 class="font-semibold text-indigo-300 flex gap-x-2">
                                                                <span>
                                                                    Modification des notes de
                                                                </span>
                                                                <span class="text-orange-500 ">
                                                                    {{ $student->getFullName() }}
                                                                </span>
                                                            </h3>

                                                            <button type="button"
                                                                wire:click.prevent="cancelEditStudentMark"
                                                                wire:loading.attr="disabled"
                                                                wire:target="cancelEditStudentMark"
                                                                class="h-9 w-9 shrink-0 flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition-colors duration-200 disabled:opacity-50">
                                                                ✕
                                                            </button>
                                                        </div>

                                                        <p
                                                            class="text-xs rounded-2xl p-1 px-3 bg-red-600/20 text-red-500 mb-4 animate-pulse flex flex-col gap-1.5">
                                                            <span>
                                                                Videz un champ pour retirer la note correspondante. Les
                                                                notes 0–20 uniquement.
                                                            </span>
                                                            <span class="text-yellow-500 p-2">
                                                                Toujours cliquer Terminé après modifications
                                                            </span>
                                                        </p>

                                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                                            @foreach ($editInputs as $type => $value)
                                                                <div
                                                                    wire:key="edit-field-{{ $student->id }}-{{ $type }}">
                                                                    <label class="block text-xs text-slate-400 mb-1">
                                                                        {{ $this->markColumns()[$type] ?? $type }}
                                                                    </label>
                                                                    <input type="text"
                                                                        wire:model="editInputs.{{ $type }}"
                                                                        placeholder="—"
                                                                        class="w-full h-8 rounded-2xl bg-slate-900 border border-slate-800 px-3 text-center font-mono text-base transition-colors duration-200">
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <div class="flex flex-col sm:flex-row justify-end gap-2 mt-5">
                                                            <button type="button"
                                                                wire:click.prevent="cancelEditStudentMark"
                                                                wire:loading.attr="disabled"
                                                                wire:target="cancelEditStudentMark"
                                                                class="h-8 px-5 rounded-2xl bg-slate-800 hover:bg-slate-700 disabled:opacity-50 transition-colors duration-200">
                                                                Annuler
                                                            </button>

                                                            <button type="button"
                                                                wire:click.prevent="finishEditStudentMark"
                                                                wire:loading.attr="disabled"
                                                                wire:target="finishEditStudentMark"
                                                                class="relative h-8 px-6 rounded-2xl bg-indigo-500 hover:bg-indigo-600 disabled:opacity-50 transition-all duration-200 flex items-center justify-center overflow-hidden min-w-[110px]">
                                                                <span wire:loading.class="opacity-0 scale-90"
                                                                    wire:target="finishEditStudentMark"
                                                                    class="transition-all duration-200">Terminer</span>
                                                                <svg wire:loading.class="opacity-100 scale-100"
                                                                    wire:loading.class.remove="opacity-0 scale-75"
                                                                    wire:target="finishEditStudentMark"
                                                                    class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                                    viewBox="0 0 24 24" fill="none">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="{{ $totalColumns }}"
                                                class="px-6 py-10 text-center text-slate-500">
                                                Aucun apprenant trouvé pour cette classe.
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        {{-- ================= RÉCAPITULATIF DES MODIFICATIONS EN ATTENTE ================= --}}
                        @if (!empty($pendingEdits))
                            <div wire:transition.opacity.duration.300ms
                                class="mx-2 mb-3 rounded-lg border border-amber-500/30 bg-amber-500/5 overflow-hidden">

                                <div
                                    class="p-4 border-b border-amber-500/20 flex items-center justify-between gap-4 flex-wrap">
                                    <h3 class="font-semibold text-amber-300">
                                        Modifications en attente de confirmation ({{ count($pendingEdits) }})
                                    </h3>

                                    <div class="flex justify-end flex-row-reverse gap-2">
                                        <button wire:click="cancelAllPendingEdits" wire:loading.attr="disabled"
                                            wire:target="cancelAllPendingEdits"
                                            class="relative h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 disabled:opacity-50 transition-all duration-200 flex items-center justify-center overflow-hidden min-w-[130px]">
                                            <span wire:loading.class="opacity-0 scale-90"
                                                wire:target="cancelAllPendingEdits"
                                                class="transition-all duration-200">Tout annuler</span>
                                            <svg wire:loading.class="opacity-100 scale-100"
                                                wire:loading.class.remove="opacity-0 scale-75"
                                                wire:target="cancelAllPendingEdits"
                                                class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </button>

                                        <button wire:click="confirmMarksUpdate" wire:loading.attr="disabled"
                                            wire:target="confirmMarksUpdate"
                                            class="relative h-10 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 transition-all duration-200 flex items-center justify-center overflow-hidden min-w-[170px]">
                                            <span wire:loading.class="opacity-0 scale-90"
                                                wire:target="confirmMarksUpdate"
                                                class="transition-all duration-200">Confirmer la mise à jour</span>
                                            <svg wire:loading.class="opacity-100 scale-100"
                                                wire:loading.class.remove="opacity-0 scale-75"
                                                wire:target="confirmMarksUpdate"
                                                class="animate-spin h-4 w-4 absolute opacity-0 scale-75 transition-all duration-200"
                                                viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <colgroup>
                                            <col class="w-[30%] min-w-[30%]">
                                            <col class="w-[60%] min-w-[60%]">
                                            <col class="w-[10%] min-w-[10%]">
                                        </colgroup>
                                        <thead class="bg-slate-950/40">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-slate-400">Apprenant</th>
                                                <th class="px-4 py-2 text-left text-slate-400">Nouvelles valeurs</th>
                                                <th class="px-4 py-2 text-right text-slate-400">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-amber-500/10">
                                            @foreach ($pendingEdits as $studentId => $marks)
                                                @php $editedStudent = $this->students->firstWhere('id', $studentId); @endphp
                                                <tr wire:key="pending-edit-{{ $studentId }}">
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        {{ $editedStudent?->getFullName() ?? '—' }}
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex gap-2 font-mono">
                                                            @foreach ($marks as $type => $value)
                                                                <span
                                                                    class="px-2 py-1 rounded-lg bg-slate-950 border border-slate-800 text-xs truncate">
                                                                    {{ $this->markColumns()[$type] ?? $type }}
                                                                    :
                                                                    @if (is_null($value))
                                                                        <span class="text-rose-400">retirée</span>
                                                                    @else
                                                                        <span
                                                                            class="text-emerald-400">{{ number_format($value, 2) }}</span>
                                                                    @endif
                                                                </span>
                                                            @endforeach

                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-2 text-right">
                                                        <button wire:click="removePendingEdit({{ $studentId }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="removePendingEdit({{ $studentId }})"
                                                            class="h-9 px-3 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500/20 transition-colors duration-200 disabled:opacity-50">
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

                </div>

            </div>
        </section>

    </div>

</div>

