<div class="min-h-screen bg-[#070a12] text-slate-100">

    <div class="max-w-[1600px] mx-auto px-4 py-6 sm:py-8 space-y-6">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        {{-- ========== FILTRES ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input type="text" placeholder="Rechercher un apprenant…"
                        class="w-full h-10 rounded-xl bg-[#070a12] border border-white/[0.08]
                                  pl-10 pr-4 text-sm text-white placeholder:text-slate-600
                                  outline-none focus:border-indigo-500/50 transition-all" />
                </div>

                <select wire:model.live="period"
                    class="h-10 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm
                               text-slate-300 font-mono uppercase
                               focus:border-indigo-500/50 focus:outline-none transition min-w-[180px]">
                    <option disabled value="">{{ $this->activeYear->periodLabel() }}</option>
                    @foreach ($this->periods_types as $pv => $p)
                        <option @disabled($p['index'] !== $this->activeYear->active_period) value="{{ $p['index'] }}">
                            {{ $p['label'] }}
                        </option>
                    @endforeach
                </select>

                <button
                    class="h-10 px-4 rounded-xl text-sm font-medium
                               bg-white/[0.04] border border-white/[0.08] text-slate-400
                               hover:bg-white/[0.08] hover:text-white transition-all shrink-0">
                    Réinitialiser
                </button>
            </div>
        </section>

        {{-- ========== TABLEAU ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">

            {{-- Header --}}
            <div
                class="px-5 sm:px-6 py-4 border-b border-white/[0.05]
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-white">Notes de classe</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Gestion et modification des notes des apprenants</p>
                </div>
            </div>

            @php
                $showActionsColumn =
                    $this->activeYear && $this->activeYear->is_active && $this->activeYear->active_period === $period;
                $totalColumns = 1 + 4 + 1 + count($this->devoirColumns()) + 1 + 1 + 1 + ($showActionsColumn ? 1 : 0);
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full text-sm" style="min-width: 1100px;">
                    <thead>
                        <tr class="border-b border-white/[0.05]">
                            <th
                                class="sticky left-0 z-10 bg-[#0c101c] px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Apprenant
                            </th>
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Int 1</th>
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Int 2</th>
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Int 3</th>
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Int 4</th>
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-indigo-400">
                                Moy. Int</th>
                            @foreach ($this->devoirColumns() as $type => $label)
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    {{ $label }}
                                </th>
                            @endforeach
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-emerald-400">
                                Moy.</th>
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-emerald-400">
                                Moy. Coef.</th>
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Rang</th>
                            @if ($showActionsColumn)
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 w-16">
                                </th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/[0.04]">
                        @forelse ($this->studentsRows as $row)
                            @php $student = $row['student']; @endphp

                            <tr class="group hover:bg-white/[0.02] transition-colors
                                       {{ $editingStudentId === $student->id ? 'bg-indigo-500/[0.06]' : '' }}"
                                wire:key="student-row-{{ $student->id }}">

                                {{-- Apprenant sticky --}}
                                <td
                                    class="sticky left-0 z-10 bg-[#0c101c] group-hover:bg-[#0e1320] px-5 py-3 transition-colors
                                           {{ $editingStudentId === $student->id ? 'bg-[#0e1220]' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="shrink-0 w-7 h-7 rounded-lg bg-white/[0.04] border border-white/[0.06]
                                                     flex items-center justify-center text-[11px] font-mono text-slate-500">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-slate-200 truncate text-sm">
                                                    {{ $student->getFullName() }}
                                                </span>
                                                @if ($student->gender)
                                                    <span
                                                        class="shrink-0 text-[10px] font-mono uppercase px-1.5 py-0.5 rounded
                                                                 bg-white/[0.04] text-slate-500 border border-white/[0.06]">
                                                        {{ str()->initials($student->gender) }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($student->educMaster)
                                                <p class="text-[11px] text-slate-600 truncate mt-0.5">
                                                    {{ $student->educMaster }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                @foreach (['interro1', 'interro2', 'interro3', 'interro4'] as $type)
                                    <td class="px-3 py-3 text-center font-mono text-xs">
                                        @if (!is_null($row['marks'][$type]))
                                            <span
                                                class="text-slate-300">{{ number_format($row['marks'][$type], 2) }}</span>
                                        @else
                                            <span class="text-slate-700">—</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td
                                    class="px-3 py-3 text-center font-mono text-xs font-medium
                                           {{ !is_null($row['moy_interro']) ? 'text-indigo-400' : 'text-slate-700' }}">
                                    {{ !is_null($row['moy_interro']) ? number_format($row['moy_interro'], 2) : '—' }}
                                </td>

                                @foreach ($this->devoirColumns() as $type => $label)
                                    <td class="px-3 py-3 text-center font-mono text-xs">
                                        @if (!is_null($row['marks'][$type]))
                                            <span
                                                class="text-slate-300">{{ number_format($row['marks'][$type], 2) }}</span>
                                        @else
                                            <span class="text-slate-700">—</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td
                                    class="px-3 py-3 text-center font-mono text-xs font-semibold
                                           {{ !is_null($row['moy']) ? 'text-emerald-400' : 'text-slate-700' }}">
                                    {{ !is_null($row['moy']) ? number_format($row['moy'], 2) : '—' }}
                                </td>

                                <td
                                    class="px-3 py-3 text-center font-mono text-xs font-semibold
                                           {{ !is_null($row['moy_coef']) ? 'text-emerald-400' : 'text-slate-700' }}">
                                    {{ !is_null($row['moy_coef']) ? number_format($row['moy_coef'], 2) : '—' }}
                                </td>

                                <td class="px-3 py-3 text-center">
                                    @if ($row['rank'])
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono
                                                     bg-white/[0.04] text-slate-300 border border-white/[0.06]">
                                            #{{ $row['rank'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-700">—</span>
                                    @endif
                                </td>

                                @if ($showActionsColumn)
                                    <td class="px-3 py-3 text-center">
                                        <button wire:click="editStudentMark({{ $student->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="editStudentMark({{ $student->id }})"
                                            @if (isset($pendingEdits[$student->id])) disabled @endif
                                            title="{{ isset($pendingEdits[$student->id]) ? 'Modification en attente' : 'Éditer' }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-all disabled:opacity-50
                                                       {{ isset($pendingEdits[$student->id])
                                                           ? 'bg-amber-500/15 text-amber-400 border border-amber-500/25'
                                                           : 'bg-white/[0.04] text-slate-400 border border-white/[0.08] hover:bg-indigo-500 hover:text-white hover:border-indigo-500' }}">
                                            <span wire:loading.remove
                                                wire:target="editStudentMark({{ $student->id }})">
                                                <x-lucide-pen class="w-3.5 h-3.5" />
                                            </span>
                                            <span wire:loading wire:target="editStudentMark({{ $student->id }})">
                                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>
                                    </td>
                                @endif
                            </tr>

                            {{-- ===== ÉDITION INLINE ===== --}}
                            @if ($editingStudentId === $student->id)
                                <tr wire:key="edit-row-{{ $student->id }}" class="bg-indigo-500/[0.04]">
                                    <td colspan="{{ $totalColumns }}" class="p-0">
                                        <div
                                            class="sticky left-0 z-20 w-[calc(100vw-3rem)] max-w-2xl m-4
                                                    rounded-2xl border border-indigo-500/25 bg-[#0c101c] p-5
                                                    shadow-xl shadow-indigo-500/5">

                                            <div class="flex items-center justify-between gap-3 mb-3">
                                                <h3 class="text-sm font-semibold text-indigo-300">
                                                    Modifier les notes de
                                                    <span class="text-amber-400">{{ $student->getFullName() }}</span>
                                                </h3>
                                                <button type="button" wire:click.prevent="cancelEditStudentMark"
                                                    class="w-7 h-7 rounded-lg bg-white/[0.04] border border-white/[0.08]
                                                               flex items-center justify-center text-slate-400
                                                               hover:bg-white/[0.08] hover:text-white transition-all">
                                                    <x-lucide-x class="w-3.5 h-3.5" />
                                                </button>
                                            </div>

                                            <div
                                                class="rounded-xl bg-rose-500/10 border border-rose-500/20 px-3.5 py-2.5 mb-4 text-xs space-y-1">
                                                <p class="text-rose-300">Videz un champ pour retirer la note. Notes 0–20
                                                    uniquement.</p>
                                                <p class="text-amber-400 font-medium">Cliquez sur « Terminer » après vos
                                                    modifications.</p>
                                            </div>

                                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                                @foreach ($editInputs as $type => $value)
                                                    <div
                                                        wire:key="edit-field-{{ $student->id }}-{{ $type }}">
                                                        <label
                                                            class="block text-[11px] text-slate-500 mb-1 uppercase tracking-wider">
                                                            {{ $this->markColumns()[$type] ?? $type }}
                                                        </label>
                                                        <input type="text"
                                                            wire:model="editInputs.{{ $type }}" placeholder="—"
                                                            class="w-full h-9 rounded-xl bg-[#070a12] border border-white/[0.08]
                                                                      px-3 text-center font-mono text-sm text-slate-200
                                                                      placeholder:text-slate-600
                                                                      focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                                                      outline-none transition-all" />
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="flex justify-end gap-2 mt-5">
                                                <button type="button" wire:click.prevent="cancelEditStudentMark"
                                                    wire:loading.attr="disabled" wire:target="cancelEditStudentMark"
                                                    class="h-9 px-4 rounded-xl text-xs font-medium
                                                               bg-white/[0.04] border border-white/[0.08] text-slate-400
                                                               hover:bg-white/[0.08] hover:text-white
                                                               transition-all disabled:opacity-50">
                                                    Annuler
                                                </button>
                                                <button type="button" wire:click.prevent="finishEditStudentMark"
                                                    wire:loading.attr="disabled" wire:target="finishEditStudentMark"
                                                    class="inline-flex items-center gap-2 h-9 px-5 rounded-xl text-xs font-medium
                                                               bg-indigo-500 hover:bg-indigo-400 text-white
                                                               shadow-lg shadow-indigo-500/20
                                                               transition-all disabled:opacity-50">
                                                    <span wire:loading.remove wire:target="finishEditStudentMark">
                                                        Terminer
                                                    </span>
                                                    <span wire:loading wire:target="finishEditStudentMark">
                                                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                        @empty
                            <tr>
                                <td colspan="{{ $totalColumns }}" class="px-6 py-14 text-center">
                                    <div
                                        class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-800/50 mb-3">
                                        <x-lucide-users class="w-6 h-6 text-slate-500" />
                                    </div>
                                    <p class="text-sm text-slate-500">Aucun apprenant trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ========== PENDING EDITS ========== --}}
            @if (!empty($pendingEdits))
                <div class="mx-4 mb-4 mt-2 rounded-2xl border border-amber-500/25 bg-amber-500/[0.04] overflow-hidden">

                    <div
                        class="px-5 py-3.5 border-b border-amber-500/15
                                flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <h3 class="text-sm font-semibold text-amber-300">
                            {{ count($pendingEdits) }} modification{{ count($pendingEdits) > 1 ? 's' : '' }} en
                            attente
                        </h3>
                        <div class="flex gap-2">
                            <button wire:click="cancelAllPendingEdits" wire:loading.attr="disabled"
                                wire:target="cancelAllPendingEdits"
                                class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium
                                           bg-white/[0.04] border border-white/[0.08] text-slate-400
                                           hover:bg-white/[0.08] hover:text-white transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="cancelAllPendingEdits">Tout annuler</span>
                                <span wire:loading wire:target="cancelAllPendingEdits">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                </span>
                            </button>
                            <button wire:click="confirmMarksUpdate" wire:loading.attr="disabled"
                                wire:target="confirmMarksUpdate"
                                class="inline-flex items-center gap-1.5 h-8 px-3.5 rounded-lg text-xs font-medium
                                           bg-emerald-500 hover:bg-emerald-400 text-white
                                           shadow-lg shadow-emerald-500/20 transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="confirmMarksUpdate"
                                    class="inline-flex items-center gap-1.5">
                                    <x-lucide-check class="w-3.5 h-3.5" />
                                    Confirmer
                                </span>
                                <span wire:loading wire:target="confirmMarksUpdate">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="divide-y divide-amber-500/10">
                        @foreach ($pendingEdits as $studentId => $marks)
                            @php $editedStudent = $this->students->firstWhere('id', $studentId); @endphp
                            <div class="px-5 py-3 flex flex-col sm:flex-row sm:items-center gap-3"
                                wire:key="pending-edit-{{ $studentId }}">
                                <span class="text-sm font-medium text-slate-200 shrink-0 min-w-[160px]">
                                    {{ $editedStudent?->getFullName() ?? '—' }}
                                </span>
                                <div class="flex flex-wrap gap-1.5 flex-1">
                                    @foreach ($marks as $type => $value)
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-mono
                                                     bg-[#070a12] border border-white/[0.06]">
                                            <span
                                                class="text-slate-500">{{ $this->markColumns()[$type] ?? $type }}</span>
                                            @if (is_null($value))
                                                <span class="text-rose-400">retirée</span>
                                            @else
                                                <span class="text-emerald-400">{{ number_format($value, 2) }}</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                                <button wire:click="removePendingEdit({{ $studentId }})"
                                    wire:loading.attr="disabled" wire:target="removePendingEdit({{ $studentId }})"
                                    class="inline-flex items-center gap-1 h-7 px-2.5 rounded-lg text-[11px] font-medium
                                               bg-rose-500/10 text-rose-400 border border-rose-500/20
                                               hover:bg-rose-500 hover:text-white hover:border-rose-500
                                               transition-all disabled:opacity-50 shrink-0">
                                    <x-lucide-x class="w-3 h-3" />
                                    Annuler
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </section>
    </div>
</div>

