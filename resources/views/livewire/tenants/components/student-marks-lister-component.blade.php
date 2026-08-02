<section class=" mb-6">

    <div class="w-full overflow-x-hidden">

        <div class="mx-auto w-full max-w-[1900px] bg-slate-950 p-2">

            <section class="mb-6">
                <div class="rounded-lg bg-slate-900 border border-slate-800 p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">

                        {{-- PERIOD (seul select ici) --}}
                        <select wire:model.live="period"
                            class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-2 font-mono uppercase transition-colors duration-200">
                            <option disabled value="">Sélectionner le {{ $this->activeYear->periodLabel() }}
                            </option>
                            @foreach ($this->periods_types as $pv => $p)
                                <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>

            <section>
                <div class="grid grid-cols-1 gap-6">

                    <div class="space-y-6 min-w-0">

                        <div class="rounded-lg bg-slate-900 border border-slate-800 overflow-hidden">

                            <div class="p-5 border-b border-slate-800 font-mono">
                                <h2 class="text-xl font-semibold">
                                    <span>
                                        Notes de l'apprenant
                                    </span>
                                    <span class="text-yellow-500">
                                        {{ $student->getFullName() }}
                                    </span>
                                </h2>
                                <p class="mt-1 text-sm text-slate-400">
                                    Détail des notes par matière pour la période sélectionnée.
                                </p>
                            </div>

                            {{-- Même structure d'en-tête que la vue enseignant : Interro 1-4,
                             Moy. Interro, Devoirs, Moy., Moy. Coef., Rang — seule la
                             première colonne change (Matière au lieu d'Apprenant). --}}
                            <div class="overflow-x-auto p-3 font-mono">

                                <table class="w-full border-collapse z-table-border">

                                    <colgroup>
                                        <col class="w-[220px] min-w-[220px]"> {{-- matière --}}
                                        <col class="w-[70px] min-w-[70px]"> {{-- coef --}}
                                        <col class="w-[90px] min-w-[90px]">
                                        <col class="w-[90px] min-w-[90px]">
                                        <col class="w-[90px] min-w-[90px]">
                                        <col class="w-[90px] min-w-[90px]">
                                        <col class="w-[100px] min-w-[100px]"> {{-- moy interro --}}
                                        @foreach ($this->devoirColumns() as $type => $label)
                                            <col class="w-[90px] min-w-[90px]">
                                        @endforeach
                                        <col class="w-[100px] min-w-[100px]"> {{-- moy --}}
                                        <col class="w-[110px] min-w-[110px]"> {{-- moy coef --}}
                                        <col class="w-[110px] min-w-[110px]"> {{-- rang --}}
                                        <col class="w-[110px] min-w-[110px]"> {{-- prof --}}
                                    </colgroup>

                                    <thead class="bg-slate-950 border-b border-slate-800">
                                        <tr>
                                            <th
                                                class="sticky left-0 z-10 bg-slate-950 px-6 py-4 text-left text-sm text-slate-400 whitespace-nowrap">
                                                Matière
                                            </th>

                                            <th class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                Coef.
                                            </th>

                                            <th class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                Int 1</th>
                                            <th class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                Int 2</th>
                                            <th class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                Int 3</th>
                                            <th class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                Int 4</th>

                                            <th class="px-2 py-4 text-center text-sm text-indigo-400 whitespace-nowrap">
                                                Moy. Int
                                            </th>

                                            @foreach ($this->devoirColumns() as $type => $label)
                                                <th
                                                    class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                    {{ $label }}
                                                </th>
                                            @endforeach

                                            <th
                                                class="px-2 py-4 text-center text-sm text-emerald-400 whitespace-nowrap">
                                                Moy.
                                            </th>

                                            <th
                                                class="px-2 py-4 text-center text-sm text-emerald-400 whitespace-nowrap">
                                                Moy. Coef.
                                            </th>

                                            <th class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                Rang
                                            </th>
                                            <th class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                Prof
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-800">

                                        @forelse ($this->subjectRows as $row)

                                            <tr wire:key="subject-row-{{ $row['subject']->id }}"
                                                class="hover:bg-slate-800/40">

                                                {{-- MATIERE (sticky) --}}
                                                <td class="sticky left-0 z-10 bg-slate-900 px-6 py-2">
                                                    <h3 class="font-medium truncate uppercase">
                                                        {{ $row['subject']->code }}
                                                    </h3>
                                                </td>

                                                {{-- COEF --}}
                                                <td class="px-2 py-2 text-center whitespace-nowrap">
                                                    {{ number_format($row['coefficient'], 2) }}
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
                                                    {{ $row['rank'] ? '#' . $row['rank'] . ' / ' . $row['total'] : '—' }}
                                                </td>

                                                <td class="px-2 py-2 text-center whitespace-nowrap text-slate-500">
                                                    {{ $row['teacher']->getFullName() }}
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 8 + count($this->devoirColumns()) }}"
                                                    class="px-6 py-10 text-center text-slate-500">
                                                    Aucune matière trouvée pour cette classe.
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>
            </section>

            <section class="my-4 mt-10">
                <div class="grid grid-cols-1 gap-6">

                    <div class="space-y-6 min-w-0">

                        <div
                            class="rounded-lg bg-slate-950 shadow-xs shadow-sky-700 border border-slate-800 overflow-hidden">

                            <div class="p-5 border-b border-slate-800 font-mono">
                                <h2 class="text-xl font-semibold">
                                    <span>
                                        Moyennes de l'apprenant
                                    </span>
                                    <span class="text-yellow-500">
                                        {{ $student->getFullName() }}
                                    </span>
                                </h2>
                                <p class="mt-1 text-sm text-slate-400">
                                    Détail des moyenne par matière pour la période sélectionnée.
                                </p>
                            </div>

                            @if ($this->termAverage)
                                <div class="overflow-x-auto p-3 font-mono">

                                    <table class="w-full border-collapse z-table-border">

                                        <colgroup>
                                            <col class="w-[220px] min-w-[220px]"> {{-- matière --}}
                                            <col class="w-[70px] min-w-[70px]"> {{-- coef --}}
                                            <col class="w-[100px] min-w-[100px]"> {{-- moy interro --}}
                                            @foreach ($this->devoirColumns() as $type => $label)
                                                <col class="w-[90px] min-w-[90px]">
                                            @endforeach
                                            <col class="w-[100px] min-w-[100px]"> {{-- moy --}}
                                            <col class="w-[110px] min-w-[110px]"> {{-- moy coef --}}
                                            <col class="w-[110px] min-w-[110px]"> {{-- rang --}}
                                            <col class="w-[110px] min-w-[110px]"> {{-- obs --}}
                                            <col class="w-[110px] min-w-[110px]"> {{-- teacher --}}
                                        </colgroup>

                                        <thead class="bg-slate-950 border-b border-slate-800">
                                            <tr>
                                                <th
                                                    class="sticky left-0 z-10 bg-slate-950 px-6 py-4 text-left text-sm text-slate-400 whitespace-nowrap">
                                                    Matière
                                                </th>

                                                <th
                                                    class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                    Coef.
                                                </th>
                                                <th
                                                    class="px-2 py-4 text-center text-sm text-indigo-400 whitespace-nowrap">
                                                    Moy. Int
                                                </th>

                                                @foreach ($this->devoirColumns() as $type => $label)
                                                    <th
                                                        class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                        {{ $label }}
                                                    </th>
                                                @endforeach

                                                <th
                                                    class="px-2 py-4 text-center text-sm text-emerald-400 whitespace-nowrap">
                                                    Moy.
                                                </th>

                                                <th
                                                    class="px-2 py-4 text-center text-sm text-emerald-400 whitespace-nowrap">
                                                    Moy. Coef.
                                                </th>

                                                <th
                                                    class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                    Rang
                                                </th>
                                                <th
                                                    class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                    Observation
                                                </th>
                                                <th
                                                    class="px-2 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                                    Prof
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-800">

                                            @forelse ($this->subjectRows as $row)
                                                <tr wire:key="subject-row-{{ $row['subject']->id }}"
                                                    class="hover:bg-slate-800/40">

                                                    {{-- MATIERE (sticky) --}}
                                                    <td class="sticky left-0 z-10 bg-slate-900 px-6 py-2">
                                                        <h3 class="font-medium truncate uppercase">
                                                            {{ $row['subject']->code }}
                                                        </h3>
                                                    </td>

                                                    {{-- COEF --}}
                                                    <td @if (is_null($row['moy'])) title="Ce coef n'est pas pris en compte en raison de l'absence des notes ou de l'impossiblité de calculer la moyenne de {{ $row['subject'] ? $row['subject']->name : ' cette matière' }} " @endif
                                                        class="px-2 py-2 text-center whitespace-nowrap">
                                                        <span
                                                            class="@if (is_null($row['moy'])) line-through decoration-red-500 text-slate-500 @endif">
                                                            {{ number_format($row['coefficient'], 2) }}
                                                        </span>

                                                    </td>

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
                                                        {{ $row['rank'] ? '#' . $row['rank'] . ' / ' . $row['total'] : '—' }}
                                                    </td>
                                                    <td class="px-2 py-2 text-center whitespace-nowrap">
                                                        {{ $row['mention'] ? $row['mention'] : '—' }}
                                                    </td>
                                                    <td class="px-2 py-2 text-center whitespace-nowrap text-slate-400">
                                                        {{ $row['teacher']->getFullName() }}
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ 8 + count($this->devoirColumns()) }}"
                                                        class="px-6 py-10 text-center text-slate-500">
                                                        Aucune matière trouvée pour cette classe.
                                                    </td>
                                                </tr>
                                            @endforelse
                                            <tr class="shadow-xs text-center bg-blue-900/20 text-sky-500 text-lg">
                                                <td class="sticky left-0 z-10  px-6 py-2">
                                                    <h3 class="font-medium truncate uppercase">
                                                        Total
                                                    </h3>
                                                </td>
                                                <td class="  px-6 py-2">
                                                    <h3 class="font-medium truncate uppercase">
                                                        {{ isset($this->termAverage['sum_coef']) ? number_format($this->termAverage['sum_coef'], 2) : '---' }}
                                                    </h3>
                                                </td>
                                                <td colspan="4" class="  px-6 py-2">
                                                    <h3 class="font-medium truncate uppercase">

                                                    </h3>
                                                </td>
                                                <td class="  px-6 py-2">
                                                    <h3 class="font-medium truncate uppercase">
                                                        {{ isset($this->termAverage['sum_moy_coef']) ? number_format($this->termAverage['sum_moy_coef'], 2) : '---' }}
                                                    </h3>
                                                </td>
                                                <td class="  px-6 py-2">
                                                    <h3 class="font-medium truncate uppercase">

                                                    </h3>
                                                </td>
                                                <td class="  px-6 py-2">
                                                    <h3 class="font-medium truncate uppercase">

                                                    </h3>
                                                </td>
                                                <td class="  px-6 py-2">
                                                    <h3 class="font-medium truncate uppercase">

                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr class="shadow-xs text-center bg-blue-900/20 text-orange-400 text-lg">
                                                <td class="  px-6 py-2">
                                                    <h3 class="font-medium truncate uppercase">
                                                        BILAN
                                                    </h3>
                                                </td>
                                                <td colspan="4" class="  px-6 py-2">
                                                    <div
                                                        class="font-medium truncate uppercase flex justify-center text-2xl items-center gap-3">
                                                        <span>MOYENNE : </span>
                                                        <span>
                                                            {{ isset($this->termAverage['moyenne']) ? number_format($this->termAverage['moyenne'], 2) : '--' }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td colspan="5" class="  px-6 py-2">
                                                    <h3
                                                        class="font-medium truncate uppercase flex justify-center text-2xl items-center gap-3">
                                                        <span>RANG : </span>
                                                        <span>
                                                            {{ isset($this->termAverage['rank']) ? $this->termAverage['rank'] : '---' }}
                                                        </span>
                                                    </h3>
                                                </td>
                                            </tr>

                                        </tbody>

                                    </table>

                                </div>
                            @else
                                <h4 class="flex items-center justify-center text-center ">
                                    Aucune moyenne générale disponible, certaines notes ne sont pas encore
                                    disponible
                                </h4>
                            @endif

                        </div>

                    </div>

                </div>
            </section>

        </div>

    </div>

</section>

