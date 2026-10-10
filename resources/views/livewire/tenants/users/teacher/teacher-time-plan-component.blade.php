<div>
    <div
        class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-4 sm:p-6">

        <div class="flex items-center justify-between gap-4">

            <div>

                <h2 class="text-lg sm:text-xl font-semibold">
                    Emploi du Temps
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Planning hebdomadaire de l'enseignant
                </p>

            </div>

        </div>

        {{-- Emploi du temps enseignant --}}
        @php
            $days = $this->weekDays();
            $timeColPx = 112; // largeur colonne Horaire
            $dayColPx = 168; // largeur fixe de chaque jour (à ajuster)
            $tableWidth = $timeColPx + count($days) * $dayColPx;
        @endphp

        <div
            class="min-w-0 max-w-full rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-b border-white/[0.05]">
                <div>
                    <h2 class="text-sm font-semibold text-white">Emploi du temps</h2>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        Grille 07h–19h · année {{ $this->activeYear?->slug ?? '—' }}
                        · {{ $this->teacherSlots->count() }} créneau(x)
                    </p>
                </div>
                <button type="button" wire:click="openCreateSlot" wire:loading.attr="disabled"
                    wire:target="openCreateSlot"
                    class="h-9 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white transition-all inline-flex items-center gap-1.5 shrink-0 disabled:opacity-60">
                    <span wire:loading.remove wire:target="openCreateSlot" class="inline-flex items-center gap-1.5">
                        <x-lucide-plus class="w-3.5 h-3.5" /> Créneau
                    </span>
                    <span wire:loading wire:target="openCreateSlot">
                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                    </span>
                </button>
            </div>

            <div class="overflow-x-auto overscroll-x-contain">
                <table class="table-fixed border-collapse text-left text-xs"
                    style="width: {{ $tableWidth }}px; min-width: {{ $tableWidth }}px;">
                    <colgroup>
                        <col style="width: {{ $timeColPx }}px">
                        @foreach ($days as $day => $dayName)
                            <col style="width: {{ $dayColPx }}px">
                        @endforeach
                    </colgroup>

                    <thead>
                        <tr class="border-b border-white/[0.06]">
                            <th
                                class="sticky left-0 z-20 bg-[#0c101c] px-2 py-3 text-center
                               text-[11px] font-bold uppercase tracking-wider text-slate-500 border-r border-white/[0.06]">
                                Horaire
                            </th>
                            @foreach ($days as $day => $dayName)
                                @php $isTodayColumn = (int) $day === (int) now()->dayOfWeekIso; @endphp
                                <th
                                    class="px-2 py-3 text-center text-sm font-semibold border-r border-white/[0.05] last:border-r-0
                                   {{ $isTodayColumn ? 'text-cyan-300 bg-cyan-500/10' : 'text-indigo-300 bg-white/[0.02]' }}">
                                    <span class="inline-flex items-center justify-center gap-1.5">
                                        {{ $dayName }}
                                        @if ($isTodayColumn)
                                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                        @endif
                                    </span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($this->scheduleRows() as $row)
                            @if ($row['type'] === 'break')
                                <tr class="border-b border-white/[0.05]">
                                    {{-- Fond opaque sur le td, teinte dans le div interne --}}
                                    <td class="sticky left-0 z-10 bg-[#0c101c] p-0 border-r border-white/[0.06]">
                                        <div
                                            class="px-2 py-2 text-center {{ $row['variant'] === 'lunch' ? 'bg-amber-500/5' : 'bg-sky-500/5' }}">
                                            <span
                                                class="inline-flex flex-col items-center gap-0.5 text-[10px] font-semibold uppercase tracking-wide
                                               {{ $row['variant'] === 'lunch' ? 'text-amber-400/90' : 'text-sky-400/90' }}">
                                                <x-lucide-coffee class="w-3.5 h-3.5" />
                                                {{ $row['label'] }}
                                            </span>
                                        </div>
                                    </td>

                                    @foreach ($days as $day => $dayName)
                                        <td
                                            class="px-1 py-1.5 border-r border-white/[0.05] last:border-r-0
                                           {{ $row['variant'] === 'lunch' ? 'bg-amber-500/5' : 'bg-sky-500/5' }}">
                                            <div
                                                class="h-8 rounded-lg border border-dashed flex items-center justify-center text-[10px] font-medium
                                               {{ $row['variant'] === 'lunch'
                                                   ? 'border-amber-500/20 bg-amber-500/5 text-amber-500/70'
                                                   : 'border-sky-500/20 bg-sky-500/5 text-sky-500/70' }}">
                                                {{ $row['label'] }}
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @else
                                <tr class="border-b border-white/[0.05]" wire:key="teacher-row-{{ $row['start'] }}">
                                    <td
                                        class="sticky left-0 z-10 bg-[#0c101c] px-2 py-2 text-center border-r border-white/[0.06] align-middle">
                                        <span class="font-mono text-xs font-bold text-slate-400 whitespace-nowrap">
                                            {{ \Illuminate\Support\Carbon::parse($row['start'])->format('H\hi') }}
                                            <span class="text-slate-600">–</span>
                                            {{ \Illuminate\Support\Carbon::parse($row['end'])->format('H\hi') }}
                                        </span>
                                    </td>

                                    @foreach ($days as $day => $dayName)
                                        @php
                                            $covered = $this->isCellCoveredBySpanTeacher($day, $row['start']);
                                            $slot = $covered ? null : $this->slotFragmentAtTeacher($day, $row['start']);
                                            $span = $slot ? $this->slotContiguousSpan($slot, $row['start']) : 1;
                                            $isLive = $slot ? $this->isSlotLive($slot) : false;
                                            $planId = $slot?->time_plan_id;
                                        @endphp

                                        @if ($covered)
                                            {{-- absorbé par rowspan --}}
                                        @elseif ($slot)
                                            @php $minH = max(72, $span * 72); @endphp
                                            <td rowspan="{{ $span }}"
                                                class="p-1.5 border-r border-white/[0.05] last:border-r-0 align-top"
                                                wire:key="teacher-slot-{{ $slot->id }}">
                                                <div @class([
                                                    'relative h-full rounded-xl p-2.5 flex flex-col gap-1.5 transition-all border',
                                                    'border-cyan-400/80 bg-cyan-500/15 shadow-lg shadow-cyan-500/20 ring-2 ring-cyan-400/30' => $isLive,
                                                    'border-indigo-500/25 bg-indigo-500/10' => !$isLive,
                                                ])
                                                    style="min-height: {{ $minH }}px;">

                                                    @if ($isLive)
                                                        <div class="absolute -top-2 right-2 z-10">
                                                            <span
                                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-cyan-500 text-white shadow-md animate-pulse">
                                                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                                                En cours
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if ($planId && !$slot->timePlan?->archived)
                                                        <div @class([
                                                            'absolute top-1.5 right-1.5 flex items-center gap-0.5 opacity-70 hover:opacity-100',
                                                            'mt-3' => $isLive,
                                                        ])>
                                                            <button type="button"
                                                                wire:click="editSlot({{ $slot->id }}, {{ $planId }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="editSlot({{ $slot->id }}, {{ $planId }})"
                                                                title="Modifier"
                                                                class="rounded p-1 text-slate-400 hover:text-cyan-300 hover:bg-white/10 transition-all">
                                                                <span wire:loading.remove
                                                                    wire:target="editSlot({{ $slot->id }}, {{ $planId }})">
                                                                    <x-lucide-pencil class="w-3.5 h-3.5" />
                                                                </span>
                                                                <span wire:loading
                                                                    wire:target="editSlot({{ $slot->id }}, {{ $planId }})">
                                                                    <x-lucide-loader-2
                                                                        class="w-3.5 h-3.5 animate-spin" />
                                                                </span>
                                                            </button>
                                                            <button type="button"
                                                                wire:click="deleteSlot({{ $slot->id }}, {{ $planId }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="deleteSlot({{ $slot->id }}, {{ $planId }})"
                                                                title="Supprimer"
                                                                class="rounded p-1 text-slate-400 hover:text-rose-300 hover:bg-white/10 transition-all">
                                                                <span wire:loading.remove
                                                                    wire:target="deleteSlot({{ $slot->id }}, {{ $planId }})">
                                                                    <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                                </span>
                                                                <span wire:loading
                                                                    wire:target="deleteSlot({{ $slot->id }}, {{ $planId }})">
                                                                    <x-lucide-loader-2
                                                                        class="w-3.5 h-3.5 animate-spin" />
                                                                </span>
                                                            </button>
                                                        </div>
                                                    @endif

                                                    <div class="flex items-start gap-1.5 pr-6">
                                                        <x-lucide-book-open @class([
                                                            'w-3.5 h-3.5 shrink-0 mt-0.5',
                                                            'text-cyan-300' => $isLive,
                                                            'text-indigo-300' => !$isLive,
                                                        ]) />
                                                        <p class="font-semibold leading-5 text-white text-sm">
                                                            {{ $slot->subject?->code ?? ($slot->subject?->name ?? ($slot->label ?? 'Cours')) }}
                                                        </p>
                                                    </div>

                                                    <p class="text-[11px] text-slate-400 truncate">
                                                        {{ $slot->timePlan?->classe?->name ?? ($slot->classeSubjectOfSchoolYear?->classe?->name ?? 'Classe') }}
                                                    </p>

                                                    <div class="pt-1 mt-auto">
                                                        <div @class([
                                                            'flex items-center gap-1.5 font-mono text-xs',
                                                            'text-cyan-300' => $isLive,
                                                            'text-orange-400' => !$isLive,
                                                        ])>
                                                            <x-lucide-clock class="w-3.5 h-3.5 shrink-0" />
                                                            <span>{{ $slot->start }}</span>
                                                            <span class="opacity-50">–</span>
                                                            <span>{{ $slot->end }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @else
                                            <td class="p-1.5 border-r border-white/[0.05] last:border-r-0 align-top"
                                                wire:key="teacher-empty-{{ $day }}-{{ $row['start'] }}">
                                                <div
                                                    class="h-[72px] rounded-xl bg-white/[0.015] border border-white/[0.03]">
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

