<div class="w-full max-w-full overflow-x-hidden">
    <div wire:loading wire:target='period,subject_slug'
        class="fixed inset-0 flex items-center justify-center bg-slate-800/10 backdrop-blur-xs"
        style="z-index: 200 !important;">

        <div class="items-center text-slate-400 relative top-1/2 mx-auto flex justify-center flex-col gap-3">
            <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <span class="text-xl font-mono ls-1">Chargement en cours...</span>
        </div>
    </div>

    <section class="mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            {{-- LEFT --}}
            <div class="min-w-0 w-full px-3">

                <div class="flex flex-wrap items-center gap-3 border-b border-b-slate-800 w-full">

                    <h1 class="md:text-xl text-base font-bold break-words py-3 ">
                        Gestion des Notes de <span class="text-sky-600 uppercase font-mono">{{ $classe->code }}</span>
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
                    ---
                </h2>

            </div>

            {{-- CARD --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">

                <p class="text-sm text-slate-400">
                    Taux de Réussite
                </p>

                <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold">
                    ---
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

                </div>

            </div>

        </div>

    </section>

    <section class="w-full">
        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

            <section class="">
                <div class="grid grid-cols-1 gap-6 md:text-sm text-xs mb-32">

                    <div class="space-y-6 min-w-0 ">

                        <div class="rounded-lg bg-slate-900 border border-slate-800 overflow-hidden p-1">

                            {{-- HEADER --}}
                            <div class="p-5 border-b border-slate-800">
                                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                                    <div>
                                        <h2 class="md:text-xl text-base font-mono font-semibold">
                                            <span>
                                                Les notes de classe
                                            </span>
                                            @if ($this->subject)
                                                <span>
                                                    de
                                                    <span class="text-orange-500 uppercase">
                                                        {{ $this->subject->code ? $this->subject->code : $this->subject->name }}
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

                                            @if ($this->subject)
                                                @php
                                                    $coef_rel = $this->coef_relation;

                                                    if ($coef_rel) {
                                                        $url = route('tenant.subjects.coefs.manage', [
                                                            'subject_slug' => $this->subject->slug,
                                                            'uuid' => $coef_rel->uuid,
                                                        ]);
                                                    } else {
                                                        if ($classe->filiar_id) {
                                                            $url = route('tenant.subjects.coefs.manage', [
                                                                'subject_slug' => $this->subject->slug,
                                                                'promotion' => $classe->promotion->name,
                                                                'filiar_id' => $classe->filiar_id,
                                                            ]);
                                                        } elseif ($classe->serial_id) {
                                                            $url = route('tenant.subjects.coefs.manage', [
                                                                'subject_slug' => $this->subject->slug,
                                                                'promotion' => $classe->promotion->name,
                                                                'serial_id' => $classe->serial_id,
                                                            ]);
                                                        } else {
                                                            $url = route('tenant.subjects.coefs.manage', [
                                                                'subject_slug' => $this->subject->slug,
                                                                'promotion' => $classe->promotion->name,
                                                            ]);
                                                        }
                                                    }
                                                @endphp
                                                <a class="group hover:text-slate-500 hover:underline underline-offset-4"
                                                    title="Cliquez pour définir ou éditer le coéficient de {{ $this->subject->name }} "
                                                    wire:navigate
                                                    href="{{ auth('tenant')->user()->hasRole('directeur') ? $url : '#' }}">
                                                    <span class="text-slate-500 group-hover:hidden">|</span>
                                                    <span class="text-sky-700 group-hover:text-slate-500">Coef:</span>
                                                    @if ($coef_rel)
                                                        <span class="text-sky-600 group-hover:text-slate-500">
                                                            {{ $coef_rel->coef }}
                                                        </span>
                                                    @else
                                                        <span class="text-red-400 group-hover:text-slate-500">
                                                            Le coéf n'est pas défini
                                                        </span>
                                                    @endif
                                                </a>
                                            @endif

                                        </h2>
                                        <p class="mt-1  text-slate-400 font-mono">Gestion complète des notes des
                                            apprenants.
                                        </p>
                                        @if ($this->classe_subject && $this->classe_subject->teacher)
                                            <p
                                                class="mt-2 flex-col md:flex-row text-slate-400 rounded-2xl p-2 bg-sky-600/20 text-sm sm:text-base font-mono inline-flex items-center gap-2 px-5">
                                                <span class="inline-flex gap-2 items-center ">
                                                    <span>Prof : </span>
                                                </span>
                                                <span class="text-amber-500 inline-flex gap-2 items-center ">
                                                    <span class=" inline-flex items-center gap-x-1.5 ml-2">
                                                        <x-lucide-user class="w-4 h-4" />
                                                        <span>
                                                            {{ $this->classe_subject->teacher->getFullName() }}
                                                        </span>
                                                    </span>
                                                    <span
                                                        class=" inline-flex items-center gap-x-1.5 ml-2 text-amber-700">
                                                        <x-lucide-phone class="w-4 h-4" />
                                                        <span>
                                                            {{ $this->classe_subject->teacher->user->contacts }}
                                                        </span>
                                                    </span>
                                                </span>
                                            </p>
                                        @endif

                                    </div>

                                </div>
                            </div>

                            <div class="overflow-x-auto font-mono p-2 mt-4 mb-8">
                                @if (count($this->studentsRows))
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

                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-slate-800">

                                            @foreach ($this->studentsRows as $row)
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

                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                @else
                                    <div>
                                        <h4 class="px-6 py-10 text-center text-slate-500">
                                            Il semble que cette classe est vide
                                        </h4>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>
            </section>

        </div>

    </section>

</div>

