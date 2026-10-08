<section class="min-h-screen bg-[#070a12] text-slate-100">

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

        {{-- ========== FILTRE PÉRIODE ========== --}}
        <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4">
            <div class="flex flex-wrap items-center gap-3">
                <label class="text-xs font-medium uppercase tracking-wider text-slate-500">
                    Période
                </label>
                <select wire:model.live="period"
                    class="h-10 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm
                               text-slate-300 font-mono uppercase
                               focus:border-indigo-500/50 focus:outline-none transition min-w-[180px]">
                    <option disabled value="">
                        {{ $this->activeYear->periodLabel() }}
                    </option>
                    @foreach ($this->periods_types as $pv => $p)
                        <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ========== NOTES DÉTAILLÉES ========== --}}
        <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">

            <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05]">
                <h2 class="text-lg font-semibold text-white">
                    Notes de
                    <span class="text-amber-400">{{ $student->getFullName() }}</span>
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Détail des notes par matière pour la période sélectionnée
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm" style="min-width: 1100px;">
                    <thead>
                        <tr class="border-b border-white/[0.05]">
                            <th
                                class="sticky left-0 z-10 bg-[#0c101c] px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Matière
                            </th>
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Coef.</th>
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
                            <th
                                class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Prof</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/[0.04]">
                        @forelse ($this->subjectRows as $row)
                            <tr class="group hover:bg-white/[0.02] transition-colors"
                                wire:key="subject-row-{{ $row['subject']->id }}">

                                <td
                                    class="sticky left-0 z-10 bg-[#0c101c] group-hover:bg-[#0e1320] px-5 py-3 transition-colors">
                                    <span class="font-medium text-slate-200 uppercase tracking-wide">
                                        {{ $row['subject']->code }}
                                    </span>
                                </td>

                                <td class="px-3 py-3 text-center text-slate-400 font-mono text-xs">
                                    {{ number_format($row['coefficient'], 2) }}
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
                                            #{{ $row['rank'] }}/{{ $row['total'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-700">—</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-center text-xs text-slate-500 truncate max-w-[120px]">
                                    {{ $row['teacher']->getFullName() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 10 + count($this->devoirColumns()) }}"
                                    class="px-6 py-12 text-center text-slate-500 text-sm">
                                    Aucune matière trouvée pour cette classe.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========== MOYENNES + BILAN ========== --}}
        <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">

            <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05]">
                <h2 class="text-lg font-semibold text-white">
                    Moyennes de
                    <span class="text-amber-400">{{ $student->getFullName() }}</span>
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Synthèse des moyennes par matière et bilan de période
                </p>
            </div>

            @if ($this->termAverage)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" style="min-width: 1000px;">
                        <thead>
                            <tr class="border-b border-white/[0.05]">
                                <th
                                    class="sticky left-0 z-10 bg-[#0c101c] px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Matière
                                </th>
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Coef.</th>
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
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Observation</th>
                                <th
                                    class="px-3 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Prof</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/[0.04]">
                            @forelse ($this->subjectRows as $row)
                                <tr class="group hover:bg-white/[0.02] transition-colors"
                                    wire:key="avg-row-{{ $row['subject']->id }}">

                                    <td
                                        class="sticky left-0 z-10 bg-[#0c101c] group-hover:bg-[#0e1320] px-5 py-3 transition-colors">
                                        <span class="font-medium text-slate-200 uppercase tracking-wide">
                                            {{ $row['subject']->code }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-3 text-center font-mono text-xs"
                                        @if (is_null($row['moy'])) title="Coef non pris en compte (moyenne absente)" @endif>
                                        <span
                                            class="{{ is_null($row['moy']) ? 'line-through decoration-rose-500/60 text-slate-600' : 'text-slate-400' }}">
                                            {{ number_format($row['coefficient'], 2) }}
                                        </span>
                                    </td>

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
                                                #{{ $row['rank'] }}/{{ $row['total'] }}
                                            </span>
                                        @else
                                            <span class="text-slate-700">—</span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-3 text-center text-xs text-slate-400">
                                        {{ $row['mention'] ?: '—' }}
                                    </td>

                                    <td class="px-3 py-3 text-center text-xs text-slate-500 truncate max-w-[120px]">
                                        {{ $row['teacher']->getFullName() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 8 + count($this->devoirColumns()) }}"
                                        class="px-6 py-12 text-center text-slate-500 text-sm">
                                        Aucune matière trouvée pour cette classe.
                                    </td>
                                </tr>
                            @endforelse

                            {{-- TOTAL --}}
                            <tr class="bg-sky-500/5 border-t border-sky-500/20">
                                <td class="sticky left-0 z-10 bg-[#0a1220] px-5 py-3.5">
                                    <span class="text-xs font-bold uppercase tracking-wider text-sky-400">Total</span>
                                </td>
                                <td class="px-3 py-3.5 text-center font-mono text-sm font-semibold text-sky-300">
                                    {{ isset($this->termAverage['sum_coef']) ? number_format($this->termAverage['sum_coef'], 2) : '—' }}
                                </td>
                                <td colspan="{{ 1 + count($this->devoirColumns()) }}"></td>
                                <td class="px-3 py-3.5 text-center font-mono text-sm font-semibold text-sky-300">
                                    {{ isset($this->termAverage['sum_moy_coef']) ? number_format($this->termAverage['sum_moy_coef'], 2) : '—' }}
                                </td>
                                <td colspan="3"></td>
                            </tr>

                            {{-- BILAN --}}
                            <tr class="bg-amber-500/5 border-t border-amber-500/20">
                                <td class="sticky left-0 z-10 bg-[#12100a] px-5 py-4">
                                    <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Bilan</span>
                                </td>
                                <td colspan="{{ 2 + count($this->devoirColumns()) }}" class="px-3 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <span class="text-xs uppercase tracking-wider text-slate-500">Moyenne</span>
                                        <span class="text-2xl font-black text-amber-400 font-mono tabular-nums">
                                            {{ isset($this->termAverage['moyenne']) ? number_format($this->termAverage['moyenne'], 2) : '—' }}
                                        </span>
                                    </div>
                                </td>
                                <td colspan="{{ 3 }}" class="px-3 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <span class="text-xs uppercase tracking-wider text-slate-500">Rang</span>
                                        <span class="text-2xl font-black text-amber-400 font-mono tabular-nums">
                                            {{ $this->termAverage['rank'] ?? '—' }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-14 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-800/50 mb-3">
                        <x-lucide-file-x class="w-6 h-6 text-slate-500" />
                    </div>
                    <p class="text-sm text-slate-500">
                        Aucune moyenne générale disponible — certaines notes manquent encore.
                    </p>
                </div>
            @endif
        </div>

    </div>
</section>
