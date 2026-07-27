<div class="w-full max-w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            {{-- LEFT --}}
            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-2xl sm:text-3xl font-bold break-words">
                        Gestion des Notes
                    </h1>

                    <span
                        class="px-3 py-1 rounded-full
                                 bg-indigo-500/10
                                 border border-indigo-500/20
                                 text-indigo-400 text-xs shrink-0 font-mono">

                        {{ count($this->studentsRows) }} apprenants

                    </span>

                </div>

                <p class="mt-2 text-slate-400 text-sm sm:text-base font-mono">

                    Notes, moyennes et statistiques pédagogiques de la classe.

                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <button
                    class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-indigo-500 hover:bg-indigo-600
                               transition-all duration-300
                               text-sm sm:text-base">

                    Ajouter Notes

                </button>

                <button
                    class="w-full sm:w-auto
                               px-5 py-3 rounded-2xl
                               bg-slate-800
                               border border-slate-700
                               hover:bg-slate-700
                               transition-all duration-300
                               text-sm sm:text-base">

                    Exporter PDF

                </button>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- KPI --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div
            class="grid
                    grid-cols-1
                    sm:grid-cols-2
                    xl:grid-cols-4
                    gap-4 sm:gap-6">

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Moyenne Générale
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    14.52
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Taux de Réussite
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    87%
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Matière
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold uppercase">
                    @if ($this->subject)
                        {{ $this->subject->code }}
                    @else
                        <span class="text-sm text-slate-600">
                            Non sélectionnée
                        </span>
                    @endif
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    @if ($this->activeYear)
                        {{ $this->activeYear->periodLabel() }}
                    @else
                        <span class="text-slate-600 text-sm">Aucune année active</span>
                    @endif
                </p>

                <h2 class="mt-3 text-xl sm:text-xl xl:text-lg font-bold">
                    @if ($this->period)
                        {{ $this->activeYear->periodLabel() }} {{ $this->period }}
                    @else
                        <span class="text-sm text-slate-600">
                            Non sélectionnée
                        </span>
                    @endif
                </h2>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- TOOLBAR --}}
    {{-- ===================================================== --}}
    <section class="mb-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">

            <div class="flex flex-col xl:flex-row gap-4">

                {{-- SEARCH --}}
                <div class="flex-1 min-w-0">

                    <div class="relative">

                        <input type="text" placeholder="Rechercher un apprenant..."
                            class="w-full h-12
                                   rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   outline-none
                                   focus:border-indigo-500
                                   transition-all">

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">

                            🔍

                        </div>

                    </div>

                </div>

                {{-- FILTERS --}}
                <div
                    class="grid
                            grid-cols-1
                            sm:grid-cols-2
                            lg:grid-cols-3
                            gap-3">

                    {{-- SUBJECT --}}
                    <select wire:model.live="subject_slug"
                        class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-2 font-mono transition-colors duration-200">
                        <option value="">Sélectionner une matière</option>
                        @foreach ($this->availableSubjects as $subj)
                            <option value="{{ $subj->slug }}">{{ $subj->name }}</option>
                        @endforeach
                    </select>

                    {{-- PERIOD --}}
                    <select wire:model.live="period"
                        class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-2 font-mono uppercase transition-colors duration-200">
                        <option value="">Sélectionner le {{ $this->activeYear->periodLabel() }}</option>
                        @foreach ($this->periods_types as $pv => $p)
                            <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                        @endforeach
                    </select>

                    {{-- RESET --}}
                    <button
                        class="h-12 px-5 rounded-2xl
                                   bg-slate-800
                                   border border-slate-700
                                   hover:bg-slate-700
                                   transition-all
                                   text-sm">

                        Réinitialiser

                    </button>

                </div>

            </div>

        </div>

    </section>

    <section class="w-full">
        <div class="flex justify-end flex-wrap gap-3 text-gray-950 p-2">

            <button class="px-3 py-2 rounded-2xl
                                    bg-red-500 hover:bg-red-600">

                Verrouiller notes

            </button>

            <button class="px-3 py-2 rounded-2xl
                                    bg-blue-500 hover:bg-blue-600">

                Imprimer PDF

            </button>

            <button
                class="px-3 py-2 rounded-2xl
                                    bg-emerald-500 hover:bg-emerald-600">

                Emprimer Excel

            </button>

            <button class="px-3 py-2 rounded-2xl
                                    bg-amber-500 hover:bg-amber-600">

                Imprimer Excel et PDF

            </button>

        </div>

        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

            <section class="">
                <div class="grid grid-cols-1 gap-6 md:text-sm text-xs mb-32">

                    <div class="space-y-6 min-w-0 ">

                        <div class="rounded-lg bg-slate-900 border border-slate-800 overflow-hidden p-1">

                            {{-- HEADER --}}
                            <div class="p-5 border-b border-slate-800">
                                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                                    <div>
                                        <h2 class="text-xl font-mono font-semibold">
                                            <span>
                                                Les notes de classe
                                            </span>
                                            @if ($this->subject)
                                                <span>
                                                    de
                                                    <span class="text-orange-500 uppercase">
                                                        {{ $this->subject->name }}
                                                    </span>
                                                </span>
                                            @endif
                                            @if ($this->period)
                                                <span>
                                                    du
                                                    <span class="text-orange-500 uppercase">
                                                        {{ $this->activeYear->periodLabel() . ' ' . $this->period }}
                                                    </span>
                                                </span>
                                            @endif

                                        </h2>
                                        <p class="mt-1  text-slate-400 font-mono">Gestion complète des notes des
                                            apprenants.
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
                                    1 +
                                    4 +
                                    1 +
                                    count($this->devoirColumns()) +
                                    1 +
                                    1 +
                                    1 +
                                    ($showActionsColumn ? 1 : 0);
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

                                            <th class="px-2 py-2 text-center  text-slate-400 whitespace-nowrap">
                                                Modifier notes
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-800">

                                        @forelse ($this->studentsRows as $row)
                                            @php $student = $row['student']; @endphp

                                            <tr wire:key="student-row-{{ $student->id }}"
                                                class="hover:bg-slate-800/40">

                                                {{-- STUDENT (sticky) --}}
                                                <td class="sticky left-0 z-10 bg-slate-900 px-6 py-2 ">
                                                    <a wire:navigate
                                                        href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                                        class="flex items-center gap-4 group">
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
                                                    </a>
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

                                                <td class="px-6 py-2">
                                                    <div class="flex justify-center gap-2">

                                                    </div>
                                                </td>

                                            </tr>
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

                        </div>

                    </div>

                </div>
            </section>

        </div>

    </section>

</div>

