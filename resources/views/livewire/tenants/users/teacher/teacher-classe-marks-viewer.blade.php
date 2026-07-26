<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] bg-slate-950 p-3">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        <section class="mb-6">
            <div class="rounded-lg bg-slate-900 border border-slate-800 p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">

                    {{-- PERIOD --}}
                    <select wire:model.live="period"
                        class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-2 font-mono uppercase transition-colors duration-200">
                        <option disabled value="">Sélectionner le {{ $this->activeYear->periodLabel() }}</option>
                        @foreach ($this->periods_types as $pv => $p)
                            <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                        @endforeach
                    </select>

                    {{-- SEARCH --}}
                    <input type="text" placeholder="Rechercher apprenant..."
                        class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4">

                    {{-- BUTTON --}}
                    <button class="h-12 rounded-2xl bg-indigo-500 hover:bg-indigo-600">
                        Charger
                    </button>

                </div>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- MAIN --}}
        {{-- ===================================================== --}}
        <section class="p-3">
            <div class="grid grid-cols-1 gap-6 ">

                <div class="space-y-6 min-w-0 ">

                    <div class="rounded-lg bg-slate-900 border border-slate-800 overflow-hidden p-3">

                        {{-- HEADER --}}
                        <div class="p-5 border-b border-slate-800">
                            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                                <div>
                                    <h2 class="text-xl font-semibold">Les notes de classe</h2>
                                    <p class="mt-1 text-sm text-slate-400">Gestion complète des notes des apprenants.
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <button class="h-10 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-600">
                                        Ajouter Notes
                                    </button>
                                    <button class="h-10 px-4 rounded-xl bg-sky-500 hover:bg-sky-600">
                                        Import Excel
                                    </button>
                                    <button class="h-10 px-4 rounded-xl bg-indigo-500 hover:bg-indigo-600">
                                        Enregistrer
                                    </button>
                                </div>

                            </div>
                        </div>

                        {{--
                            RESPONSIVE : sur petit écran, le tableau défile horizontalement
                            (overflow-x-auto) mais aucune colonne n'est comprimée : chaque
                            cellule a une largeur minimale garantie (min-w) et whitespace-nowrap
                            empêche toute coupure de valeur. La colonne "Apprenant" reste figée
                            (sticky) à gauche pendant le défilement pour garder le contexte.
                        --}}
                        <div class="overflow-x-auto font-mono p-2 mt-4 mb-32">

                            <table class="w-full border-collapse z-table-border">

                                <colgroup>
                                    <col class="w-[400px] min-w-[400px]">
                                    <col class="w-[90px] min-w-[90px]"> {{-- interro1 --}}
                                    <col class="w-[90px] min-w-[90px]"> {{-- interro2 --}}
                                    <col class="w-[90px] min-w-[90px]"> {{-- interro3 --}}
                                    <col class="w-[90px] min-w-[90px]"> {{-- interro4 --}}
                                    <col class="w-[100px] min-w-[100px]"> {{-- moy. interro --}}
                                    @foreach ($this->devoirColumns() as $type => $label)
                                        <col class="w-[90px] min-w-[90px]">
                                    @endforeach
                                    <col class="w-[100px] min-w-[100px]"> {{-- moy --}}
                                    <col class="w-[110px] min-w-[110px]"> {{-- moy. coef --}}
                                    <col class="w-[80px] min-w-[80px]"> {{-- rang --}}
                                    <col class="w-[190px] min-w-[190px]"> {{-- actions --}}
                                </colgroup>

                                <thead class="bg-slate-950 border-b border-slate-800">
                                    <tr>
                                        <th
                                            class="sticky left-0 z-10 bg-slate-950 px-2 py-2 text-center text-sm text-slate-400 whitespace-nowrap">
                                            Apprenant
                                        </th>

                                        <th class="px-2 py-2 text-center text-sm text-slate-400 whitespace-nowrap">
                                            Int 1</th>
                                        <th class="px-2 py-2 text-center text-sm text-slate-400 whitespace-nowrap">
                                            Int 2</th>
                                        <th class="px-2 py-2 text-center text-sm text-slate-400 whitespace-nowrap">
                                            Int 3</th>
                                        <th class="px-2 py-2 text-center text-sm text-slate-400 whitespace-nowrap">
                                            Int 4</th>

                                        <th class="px-2 py-2 text-center text-sm text-indigo-400 whitespace-nowrap">
                                            Moy. Int
                                        </th>

                                        @foreach ($this->devoirColumns() as $type => $label)
                                            <th class="px-2 py-2 text-center text-sm text-slate-400 whitespace-nowrap">
                                                {{ $label }}
                                            </th>
                                        @endforeach

                                        <th class="px-2 py-2 text-center text-sm text-emerald-400 whitespace-nowrap">
                                            Moy.
                                        </th>

                                        <th class="px-2 py-2 text-center text-sm text-emerald-400 whitespace-nowrap">
                                            Moy. Coef.
                                        </th>

                                        <th class="px-2 py-2 text-center text-sm text-slate-400 whitespace-nowrap">
                                            Rang
                                        </th>

                                        <th class="px-2 py-2 text-center text-sm text-slate-400 whitespace-nowrap">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @forelse ($this->studentsRows as $row)
                                        @php $student = $row['student']; @endphp

                                        <tr wire:key="student-row-{{ $student->id }}" class="hover:bg-slate-800/40">

                                            {{-- STUDENT (sticky) --}}
                                            <td class="sticky left-0 z-10 bg-slate-900 px-6 py-2">
                                                <div class="flex items-center gap-4">
                                                    <div class="p-3 shrink-0 rounded-2xl bg-slate-800">
                                                        {{ $loop->iteration }}
                                                    </div>
                                                    <div class="flex flex-col min-w-0 w-full">
                                                        <div
                                                            class="font-medium w-full transition flex justify-between items-center gap-1 min-w-0">
                                                            <span
                                                                class="group-hover:underline underline-offset-4 group-hover:text-sky-500 font-mono text-slate-300 text-xs sm:text-sm break-normal">{{ $student->getFullName() }}</span>
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
                                            <td class="px-6 py-2">
                                                <div class="flex justify-center gap-2">
                                                    <button wire:click="editStudentMark({{ $student->id }})"
                                                        wire:key="edit-btn-{{ $student->id }}"
                                                        class="h-10 px-4 rounded-xl bg-indigo-500/10 text-indigo-400 whitespace-nowrap">
                                                        Modifier
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 8 + count($this->devoirColumns()) }}"
                                                class="px-6 py-10 text-center text-slate-500">
                                                Aucun apprenant trouvé pour cette classe.
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

    </div>

</div>

