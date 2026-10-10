<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-5">

        {{-- ========== HEADER ========== --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-400/80 mb-1">
                    Vie scolaire
                </p>
                <h2 class="text-xl font-bold tracking-tight text-white">
                    Emploi du temps
                    <span class="text-slate-500 font-normal">·</span>
                    {{ $this->classe->name }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Consultez et gérez les cours prévus pour cette classe
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($this->timePlan)
                    @if ($this->timePlan->published)
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                                   bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            Publié
                        </span>
                    @elseif ($this->timePlan->archived)
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                                   bg-red-500/15 text-red-400 border border-red-500/25">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                            Archivé
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                                   bg-orange-500/15 text-orange-400 border border-orange-500/25">
                            <span class="h-1.5 w-1.5 rounded-full bg-orange-400"></span>
                            Brouillon
                        </span>
                    @endif

                    {{-- Actions plan (même pattern que le dashboard) --}}
                    @if (!$this->timePlan->archived)
                        <button type="button" wire:click="openCreateSlot({{ $this->timePlan->id }})"
                            wire:loading.attr="disabled" wire:target="openCreateSlot({{ $this->timePlan->id }})"
                            class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                   bg-indigo-500 hover:bg-indigo-400 text-white transition-all disabled:opacity-60">
                            <span wire:loading.remove wire:target="openCreateSlot({{ $this->timePlan->id }})"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-plus class="w-3.5 h-3.5" /> Créneau
                            </span>
                            <span wire:loading wire:target="openCreateSlot({{ $this->timePlan->id }})"
                                class="inline-flex items-center gap-1">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                            </span>
                        </button>

                        @if ($this->timePlan->status !== 'published')
                            <button type="button" wire:click="publishPlan({{ $this->timePlan->id }})"
                                wire:loading.attr="disabled" wire:target="publishPlan({{ $this->timePlan->id }})"
                                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                       border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/10 transition-all
                                       disabled:opacity-60">
                                <span wire:loading.remove wire:target="publishPlan({{ $this->timePlan->id }})"
                                    class="inline-flex items-center gap-1.5">
                                    <x-lucide-send class="w-3.5 h-3.5" /> Publier
                                </span>
                                <span wire:loading wire:target="publishPlan({{ $this->timePlan->id }})"
                                    class="inline-flex items-center gap-1">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                                </span>
                            </button>
                        @endif
                        <button type="button" wire:click="archivePlan({{ $this->timePlan->id }})"
                            wire:loading.attr="disabled" wire:target="archivePlan({{ $this->timePlan->id }})"
                            class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                   border border-white/[0.08] text-slate-400
                                   hover:bg-amber-600/40 hover:text-white transition-all disabled:opacity-60">
                            <span wire:loading.remove wire:target="archivePlan({{ $this->timePlan->id }})">
                                Archiver
                            </span>
                            <span wire:loading wire:target="archivePlan({{ $this->timePlan->id }})"
                                class="inline-flex items-center gap-1">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                            </span>
                        </button>
                    @else
                        <button type="button" wire:click="unArchivePlan({{ $this->timePlan->id }})"
                            wire:loading.attr="disabled" wire:target="unArchivePlan({{ $this->timePlan->id }})"
                            class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                   border border-white/[0.08] text-slate-400
                                   hover:bg-slate-800 bg-slate-700/30 hover:text-white transition-all disabled:opacity-60">
                            <span wire:loading.remove wire:target="unArchivePlan({{ $this->timePlan->id }})">
                                Désarchiver
                            </span>
                            <span wire:loading wire:target="unArchivePlan({{ $this->timePlan->id }})"
                                class="inline-flex items-center gap-1">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                            </span>
                        </button>
                    @endif

                    <button type="button" wire:click="deletePlan({{ $this->timePlan->id }})"
                        wire:loading.attr="disabled" wire:target="deletePlan({{ $this->timePlan->id }})"
                        class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                               border border-rose-500/25 text-rose-400 hover:bg-rose-500/15 transition-all
                               disabled:opacity-60">
                        <span wire:loading.remove wire:target="deletePlan({{ $this->timePlan->id }})"
                            class="inline-flex items-center gap-1.5">
                            <x-lucide-trash-2 class="w-3.5 h-3.5" /> Supprimer
                        </span>
                        <span wire:loading wire:target="deletePlan({{ $this->timePlan->id }})"
                            class="inline-flex items-center gap-1">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /> …
                        </span>
                    </button>
                @endif
            </div>
        </div>

        @if (!$this->activeYear?->id)
            <div class="rounded-2xl border border-dashed border-white/[0.08] bg-white/[0.02] py-14 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/[0.04] mb-3">
                    <x-lucide-calendar-days class="w-6 h-6 text-slate-500" />
                </div>
                <h3 class="font-semibold text-white">Aucune année scolaire disponible</h3>
                <p class="mt-1 text-sm text-slate-500 max-w-md mx-auto">
                    L’emploi du temps apparaîtra ici dès qu’une année scolaire sera associée à la classe.
                </p>
            </div>
        @elseif (!$this->timePlan)
            <div class="rounded-2xl border border-dashed border-white/[0.08] bg-white/[0.02] py-14 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-500/10 mb-3">
                    <x-lucide-calendar-clock class="w-6 h-6 text-emerald-400" />
                </div>
                <h3 class="font-semibold text-white">Emploi du temps non disponible</h3>
                <p class="mt-1 text-sm text-slate-500 max-w-md mx-auto">
                    Aucun emploi du temps pour
                    <span class="text-slate-300">{{ $this->classe->name }}</span>
                    sur cette année. Il s’affichera ici après création / publication par la direction.
                </p>
            </div>
        @else
            {{-- ========== KPIs ========== --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4 flex items-center gap-3">
                    <span
                        class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/20
                               flex items-center justify-center shrink-0">
                        <x-lucide-calendar-check class="w-5 h-5 text-emerald-400" />
                    </span>
                    <div>
                        <p class="text-[11px] text-slate-500">Année scolaire</p>
                        <p class="font-semibold text-white">
                            {{ $this->timePlan->schoolYear?->slug }}
                        </p>
                    </div>
                </div>
                <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4 flex items-center gap-3">
                    <span
                        class="w-10 h-10 rounded-xl bg-sky-500/15 border border-sky-500/20
                               flex items-center justify-center shrink-0">
                        <x-lucide-book-open class="w-5 h-5 text-sky-400" />
                    </span>
                    <div>
                        <p class="text-[11px] text-slate-500">Séances planifiées</p>
                        <p class="font-semibold text-white">
                            {{ $this->timePlan->slots->count() }} créneau(x)
                        </p>
                    </div>
                </div>
                <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4 flex items-center gap-3">
                    <span
                        class="w-10 h-10 rounded-xl bg-violet-500/15 border border-violet-500/20
                               flex items-center justify-center shrink-0">
                        <x-lucide-users class="w-5 h-5 text-violet-400" />
                    </span>
                    <div>
                        <p class="text-[11px] text-slate-500">Matières distinctes</p>
                        <p class="font-semibold text-white">
                            {{ $this->timePlan->slots->pluck('classeSubjectOfSchoolYear.subject_id')->filter()->unique()->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ========== GRILLE HORAIRE FIXE 07h–19h ========== --}}
            @php
                $days = $this->days();
                $timeColPx = 112; // largeur colonne Horaire
                $dayColPx = 168; // largeur fixe de chaque jour (à ajuster)
                $tableWidth = $timeColPx + count($days) * $dayColPx;
            @endphp

            <div class="min-w-0 max-w-full rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
                <div
                    class="flex flex-col gap-1 border-b border-white/[0.05] px-4 py-3
               sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-white">Planning hebdomadaire</h3>
                        <p class="text-[11px] text-slate-500">
                            Grille 07h–19h · récréation après 09h–10h · pause déjeuner 13h–14h
                            · le créneau en cours est mis en évidence
                        </p>
                    </div>
                    @if ($this->timePlan->title)
                        <span class="text-xs text-slate-500">{{ $this->timePlan->title }}</span>
                    @endif
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
                                   {{ $isTodayColumn ? 'text-cyan-300 bg-cyan-500/10' : 'text-emerald-400 bg-white/[0.02]' }}">
                                        <span class="inline-flex items-center justify-center gap-1.5">
                                            {{ $dayName }}
                                            @if ($isTodayColumn)
                                                <span
                                                    class="h-1.5 w-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                            @endif
                                        </span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($this->scheduleRows() as $rowIndex => $row)
                                @if ($row['type'] === 'break')
                                    {{-- Ligne de pause (récréation / déjeuner) --}}
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
                                                    class="h-8 rounded-lg border border-dashed
                                               {{ $row['variant'] === 'lunch' ? 'border-amber-500/20 bg-amber-500/5' : 'border-sky-500/20 bg-sky-500/5' }}
                                               flex items-center justify-center text-[10px] font-medium
                                               {{ $row['variant'] === 'lunch' ? 'text-amber-500/70' : 'text-sky-500/70' }}">
                                                    {{ $row['label'] }}
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @else
                                    {{-- Ligne horaire d’1 heure --}}
                                    <tr class="border-b border-white/[0.05]" wire:key="row-{{ $row['start'] }}">
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
                                                $covered = $this->isCellCoveredBySpan(
                                                    $this->timePlan->id,
                                                    $day,
                                                    $row['start'],
                                                );
                                                $slot = $covered
                                                    ? null
                                                    : $this->slotFragmentAt($this->timePlan->id, $day, $row['start']);
                                                $span = $slot ? $this->slotContiguousSpan($slot, $row['start']) : 1;
                                                $isLive = $slot ? $this->isSlotLive($slot) : false;
                                            @endphp

                                            @if ($covered)
                                                {{-- Cellule absorbée par un rowspan précédent : ne rien rendre --}}
                                            @elseif ($slot)
                                                @php
                                                    $subject = $slot->subject;
                                                    $teacher = $slot->teacher;
                                                    $minH = max(72, $span * 72);
                                                @endphp
                                                <td rowspan="{{ $span }}"
                                                    class="p-1.5 border-r border-white/[0.05] last:border-r-0 align-top"
                                                    wire:key="cell-{{ $day }}-{{ $row['start'] }}-{{ $slot->id }}">
                                                    <div @class([
                                                        'relative h-full rounded-xl p-2.5 flex flex-col gap-1.5 transition-all border',
                                                        'border-cyan-400/80 bg-cyan-500/15 shadow-lg shadow-cyan-500/20 ring-2 ring-cyan-400/30' => $isLive,
                                                        'border-emerald-500/20 bg-emerald-500/10' => !$isLive,
                                                    ])
                                                        style="min-height: {{ $minH }}px;">

                                                        @if ($isLive)
                                                            <div class="absolute -top-2 right-2 z-10">
                                                                <span
                                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                                               bg-cyan-500 text-white shadow-md shadow-cyan-500/40 animate-pulse">
                                                                    <span
                                                                        class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                                                    En cours
                                                                </span>
                                                            </div>
                                                        @endif

                                                        @if (!$this->timePlan->archived)
                                                            <div @class([
                                                                'absolute top-1.5 right-1.5 flex items-center gap-0.5 opacity-70 hover:opacity-100 transition-opacity',
                                                                'mt-3' => $isLive,
                                                            ])>
                                                                <button type="button"
                                                                    wire:click="editSlot({{ $slot->id }}, {{ $this->timePlan->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="editSlot({{ $slot->id }}, {{ $this->timePlan->id }})"
                                                                    title="Modifier"
                                                                    class="rounded p-1 text-slate-400 hover:text-cyan-300 hover:bg-white/10 transition-all disabled:opacity-40">
                                                                    <span wire:loading.remove
                                                                        wire:target="editSlot({{ $slot->id }}, {{ $this->timePlan->id }})">
                                                                        <x-lucide-pencil class="w-3.5 h-3.5" />
                                                                    </span>
                                                                    <span wire:loading
                                                                        wire:target="editSlot({{ $slot->id }}, {{ $this->timePlan->id }})">
                                                                        <x-lucide-loader-2
                                                                            class="w-3.5 h-3.5 animate-spin" />
                                                                    </span>
                                                                </button>
                                                                <button type="button"
                                                                    wire:click="deleteSlot({{ $slot->id }}, {{ $this->timePlan->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="deleteSlot({{ $slot->id }}, {{ $this->timePlan->id }})"
                                                                    title="Supprimer"
                                                                    class="rounded p-1 text-slate-400 hover:text-rose-300 hover:bg-white/10 transition-all disabled:opacity-40">
                                                                    <span wire:loading.remove
                                                                        wire:target="deleteSlot({{ $slot->id }}, {{ $this->timePlan->id }})">
                                                                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                                    </span>
                                                                    <span wire:loading
                                                                        wire:target="deleteSlot({{ $slot->id }}, {{ $this->timePlan->id }})">
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
                                                                'text-emerald-400' => !$isLive,
                                                            ]) />
                                                            <p class="font-semibold leading-5 text-white text-sm">
                                                                {{ $subject?->code ?? ($slot->label ?? 'Cours') }}
                                                            </p>
                                                        </div>

                                                        <div class="flex items-center gap-1.5">
                                                            <x-lucide-user
                                                                class="w-3.5 h-3.5 text-slate-500 shrink-0" />
                                                            <p class="text-xs text-slate-400 truncate">
                                                                {{ $teacher?->getFullName() ?? 'Enseignant non renseigné' }}
                                                            </p>
                                                        </div>

                                                        @if ($slot->label && $subject && $slot->label !== $subject->name)
                                                            <div class="flex items-center gap-1.5">
                                                                <x-lucide-tag
                                                                    class="w-3 h-3 text-slate-600 shrink-0" />
                                                                <p class="text-[11px] text-slate-500 truncate">
                                                                    {{ $slot->label }}</p>
                                                            </div>
                                                        @endif

                                                        @if ($slot->notes)
                                                            <div class="flex items-start gap-1.5">
                                                                <x-lucide-sticky-note
                                                                    class="w-3 h-3 text-slate-600 shrink-0 mt-0.5" />
                                                                <p class="line-clamp-2 text-[11px] text-slate-500">
                                                                    {{ $slot->notes }}</p>
                                                            </div>
                                                        @endif

                                                        <div class="pt-1 mt-auto space-y-0.5">
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
                                                            <div @class([
                                                                'flex items-center gap-1.5 font-mono text-[11px]',
                                                                'text-cyan-400/80' => $isLive,
                                                                'text-orange-500/80' => !$isLive,
                                                            ])>
                                                                <x-lucide-hourglass class="w-3 h-3 shrink-0" />
                                                                <span>{{ $slot->duration }}H de cours</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @else
                                                {{-- Case vide : une heure sans cours --}}
                                                <td class="p-1.5 border-r border-white/[0.05] last:border-r-0 align-top"
                                                    wire:key="empty-{{ $day }}-{{ $row['start'] }}">
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

            @if ($this->timePlan->notes)
                <div class="rounded-xl border border-sky-500/20 bg-sky-500/10 p-4 text-sm text-sky-200">
                    <span class="font-semibold text-sky-300">Note de la direction :</span>
                    {{ $this->timePlan->notes }}
                </div>
            @endif
        @endif

        {{-- ========== MODAL CRÉNEAU ========== --}}
        @if ($showSlotForm)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-data
                x-transition>
                <div class="w-full max-w-lg rounded-2xl border border-white/[0.08] bg-[#0c1019] shadow-2xl"
                    @click.outside="$wire.set('showSlotForm', false)">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                        <h3 class="text-base font-semibold text-white">
                            {{ $slotId ? 'Modifier le créneau' : 'Nouveau créneau' }}
                        </h3>
                        <button type="button" wire:click="$set('showSlotForm', false)"
                            class="rounded-lg p-1.5 text-slate-500 hover:text-white hover:bg-white/[0.06] transition-all">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>
                    <form wire:submit="saveSlot" class="p-5 space-y-4">
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-book-open class="w-3.5 h-3.5 text-sky-400" /> Matière / Enseignant
                            </label>
                            <select wire:model="assignment_id" required
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-cyan-500/50 focus:outline-none transition-all">
                                <option value="">Choisir une affectation…</option>
                                @foreach ($this->assignments as $assignment)
                                    <option value="{{ $assignment->id }}">
                                        {{ $assignment->subject?->name }}
                                        — {{ $assignment->teacher?->getFullName() ?? 'Sans enseignant' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assignment_id')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-calendar-days class="w-3.5 h-3.5 text-violet-400" /> Jour
                            </label>
                            <select wire:model="day_of_week" required
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-cyan-500/50 focus:outline-none transition-all">
                                @foreach ($this->days() as $num => $label)
                                    <option value="{{ $num }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                    <x-lucide-clock class="w-3.5 h-3.5 text-emerald-400" /> Début
                                </label>
                                <input wire:model="starts_at" type="time" required
                                    class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                           focus:border-cyan-500/50 focus:outline-none transition-all" />
                                @error('starts_at')
                                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                    <x-lucide-clock class="w-3.5 h-3.5 text-rose-400" /> Fin
                                </label>
                                <input wire:model="ends_at" type="time" required
                                    class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                           focus:border-cyan-500/50 focus:outline-none transition-all" />
                                @error('ends_at')
                                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-type class="w-3.5 h-3.5 text-amber-400" />
                                Libellé <span class="text-slate-600 normal-case">(facultatif)</span>
                            </label>
                            <input wire:model="slot_label" type="text" maxlength="150"
                                placeholder="Ex. Salle B12, TP…"
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       placeholder:text-slate-600 focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20
                                       outline-none transition-all" />
                            @error('slot_label')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-align-left class="w-3.5 h-3.5 text-slate-500" />
                                Notes <span class="text-slate-600 normal-case">(facultatif)</span>
                            </label>
                            <textarea wire:model="slot_notes" rows="2"
                                class="w-full rounded-xl border border-white/[0.08] bg-[#070a12] px-3 py-2.5 text-sm text-white
                                       focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20
                                       outline-none transition-all resize-none"></textarea>
                            @error('slot_notes')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-2 pt-3 border-t border-white/[0.05]">
                            <button type="button" wire:click="$set('showSlotForm', false)"
                                class="h-10 px-4 rounded-xl text-sm font-medium border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.06] hover:text-white transition-all">
                                Annuler
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="saveSlot"
                                class="h-10 px-4 rounded-xl text-sm font-medium bg-cyan-500 hover:bg-cyan-400 text-white
                                       shadow-lg shadow-cyan-500/20 transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="saveSlot">Enregistrer</span>
                                <span wire:loading wire:target="saveSlot" class="inline-flex items-center gap-2">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" /> Enregistrement…
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
</div>

