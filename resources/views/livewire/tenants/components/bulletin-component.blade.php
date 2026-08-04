<div class="min-h-screen bg-slate-950 text-slate-100 overflow-x-hidden p-2">

    <section class="mb-4">
        <div
            class="rounded-[1.75rem] border border-white/5 bg-slate-900/70 backdrop-blur-xl p-6 shadow-xl shadow-black/20">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-white">
                    Bulletin de notes de <span class="text-sky-500">{{ $student->getFullName() }}</span>
                    - <span class="text-orange-600">{{ session('school_year_selected') }}</span>
                </h2>
                <p class="mt-1.5 text-sm text-slate-400">
                    Détails des notes par {{ $this->activeYear->periodLabel() }} de l’apprenant
                </p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-2">
                <select wire:model.live="period"
                    class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-2 font-mono uppercase transition-colors duration-200">
                    <option value="">Sélectionner le {{ $this->activeYear->periodLabel() }}</option>
                    @foreach ($this->periods_types as $pv => $p)
                        <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </section>

    <div class="mx-auto w-full max-w-[1400px] mb-40">

        <div class="border border-white/[0.06] bg-[#0f1523] shadow-xs shadow-purple-700 overflow-hidden">

            {{-- ════════════════ HEADER OFFICIEL ════════════════ --}}
            <header class="relative border-b border-white/[0.06]">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/5 via-transparent to-emerald-500/5">
                </div>

                <div class="relative p-5 sm:p-8 lg:p-10">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">

                        <div class="text-center lg:text-left order-2 lg:order-1">
                            <p class="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-slate-500 font-semibold">
                                République du Bénin
                            </p>
                            <h2 class="mt-2 text-sm sm:text-base font-semibold text-slate-200 leading-snug">
                                Ministère des Enseignements Secondaire,<br class="hidden sm:block">
                                Technique et de la Formation Professionnelle
                            </h2>
                            <p class="mt-2 text-xs text-slate-500">
                                Direction Départementale de l’Enseignement
                            </p>
                        </div>

                        <div class="flex flex-col items-center order-1 lg:order-2">
                            <div
                                class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-[#070b14] border border-white/10 flex items-center justify-center overflow-hidden">
                                <span class="text-3xl">🎓</span>
                            </div>
                            <h1 class="mt-3 text-xl sm:text-2xl font-bold text-white text-center tracking-tight">
                                {{ tenant('school_name') }}
                            </h1>
                            <p class="mt-1 text-xs text-slate-500 text-center italic">
                                {{ tenant('school_devise') }}
                            </p>
                            <div class="mt-2 text-center text-[11px] text-slate-600 space-y-0.5">
                                <p>{{ tenant('contacts') }}</p>
                                <p>{{ tenant('email') }}</p>
                            </div>
                        </div>

                        <div class="text-center lg:text-right order-3">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500">Année scolaire</p>
                            <p class="mt-1 text-xl sm:text-2xl font-bold text-indigo-400 font-mono">
                                {{ $this->activeYear->slug }}
                            </p>
                            <div
                                class="mt-3 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                Bulletin — {{ $this->activeYear->periodLabel() }} {{ $period }}
                                @if ($this->isLastPeriod)
                                    <span class="ml-1 text-emerald-300">· Résultats annuels inclus</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- ════════════════ ÉLÈVE ════════════════ --}}
            <section class="border-b border-white/[0.06]">
                <div class="p-5 sm:p-8 lg:p-10">
                    <div class="grid grid-cols-1 xl:grid-cols-[200px_minmax(0,1fr)] gap-6 lg:gap-8">

                        <div class="flex justify-center xl:justify-start">
                            <div
                                class="w-40 h-48 sm:w-44 sm:h-52 rounded-2xl bg-[#070b14] border border-white/10 overflow-hidden shadow-lg">
                                <img src="{{ $student->profil_photo_url }}" alt="{{ $student->getFullName() }}"
                                    class="w-full h-full object-cover object-top">
                            </div>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white tracking-tight">
                                    {{ $student->getFullName() }}
                                </h2>
                                <span
                                    class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[11px] font-medium">
                                    Élève régulier
                                </span>
                            </div>

                            <div class="mt-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2.5">
                                @foreach ([['Matricule', $student->matricule], ['Classe', $classe->code], ['Sexe', $student->gender], ['Né(e) le', formatBirthDate($this->student->birth_date)], ['Nationalité', $student->country], ['Effectif', $this->effectifs['apprenants']], ['Prof. Principal', $classe->principal?->getFullName() ?? '—'], ['Contact école', tenant('contacts')]] as $info)
                                    <div class="rounded-xl bg-[#070b14] border border-white/[0.05] px-3.5 py-3">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-600 font-semibold">
                                            {{ $info[0] }}</p>
                                        <p class="mt-1 text-sm font-medium text-slate-200 truncate">
                                            {{ $info[1] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            @if ($this->termAverage)
                                {{-- Stats de la PÉRIODE : rang, moyenne, premier/dernier de la classe,
                                     taux de réussite de la période (apprenant + classe). --}}
                                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    @foreach ([['Garçons', $this->effectifs['apprenants_par_sexe']['M'] ?? 0, 'text-sky-400'], ['Filles', $this->effectifs['apprenants_par_sexe']['F'] ?? 0, 'text-pink-400'], ['Rang', $this->termAverage['rank'] ?? '—', 'text-amber-400'], ['Moyenne', $this->termAverage['moyenne'] ?? '—', 'text-indigo-400']] as $item)
                                        <div
                                            class="rounded-xl bg-white/[0.02] border border-white/[0.05] px-3.5 py-3 text-center">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-600">
                                                {{ $item[0] }}</p>
                                            <p class="mt-1 text-lg font-bold {{ $item[2] }}">
                                                {{ $item[1] }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-2.5 grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    @foreach ([['1er de la classe', $this->termAverage['premier']['moyenne'] ?? '—', 'text-emerald-400'], ['Dernier de la classe', $this->termAverage['dernier']['moyenne'] ?? '—', 'text-red-400'], ['Réussite (apprenant)', ($this->termAverage['success_percentage'] ?? null) !== null ? $this->termAverage['success_percentage'] . '%' : '—', 'text-sky-400'], ['Réussite de la classe', ($this->termAverage['class_success_rate'] ?? null) !== null ? $this->termAverage['class_success_rate'] . '%' : '—', 'text-indigo-400']] as $item)
                                        <div
                                            class="rounded-xl bg-white/[0.02] border border-white/[0.05] px-3.5 py-3 text-center">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-600">
                                                {{ $item[0] }}</p>
                                            <p class="mt-1 text-base font-bold {{ $item[2] }}">
                                                {{ $item[1] }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            @if ($this->termAverage && $this->subjectsDetail)
                {{-- ════════════════ NOTES DE LA PÉRIODE ════════════════ --}}
                <section>
                    <div class="p-3 sm:p-5 lg:p-6">
                        <div class="overflow-x-auto rounded-2xl border border-white/[0.06]">
                            <table class="min-w-[1100px] w-full text-sm">
                                <thead>
                                    <tr class="bg-[#070b14] border-b border-white/[0.06]">
                                        <th
                                            class="px-4 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500 sticky left-0 bg-[#070b14]">
                                            Matière</th>
                                        <th
                                            class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                            Coef</th>
                                        <th
                                            class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                            Moy. Int</th>
                                        @foreach ($this->devoirColumns() as $type => $label)
                                            <th
                                                class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
                                                {{ $label }}</th>
                                        @endforeach
                                        <th
                                            class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                            Moy</th>
                                        <th
                                            class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                            Moy×Coef</th>
                                        <th
                                            class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                            Rang</th>
                                        <th
                                            class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                            Professeur</th>
                                        <th
                                            class="px-4 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                            Mention</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-white/[0.03]">
                                    @foreach ($this->subjectsDetail as $row)
                                        <tr class="hover:bg-white/[0.02] transition-colors">
                                            <td
                                                class="px-4 py-3.5 font-medium text-slate-200 whitespace-nowrap sticky left-0 bg-[#0f1523]">
                                                {{ $row['subject']->name }}
                                            </td>
                                            <td class="px-3 py-3.5 text-center font-mono text-slate-400">
                                                {{ $row['coefficient'] ?? '—' }}</td>
                                            <td class="px-3 py-3.5 text-center font-mono text-slate-300">
                                                {{ $row['moy_interro'] ?? '—' }}</td>
                                            @foreach ($this->devoirColumns() as $type => $label)
                                                <td class="px-3 py-3.5 text-center font-mono text-slate-300">
                                                    {{ $row['devoirs'][$type] ?? '—' }}</td>
                                            @endforeach
                                            <td class="px-3 py-3.5 text-center font-mono font-semibold text-white">
                                                {{ $row['moy'] ?? '—' }}</td>
                                            <td class="px-3 py-3.5 text-center font-mono text-indigo-300">
                                                {{ $row['moy_coef'] ?? '—' }}</td>
                                            <td class="px-3 py-3.5 text-center font-mono text-slate-500">
                                                @if ($row['rank'])
                                                    {{ $row['rank'] . ($row['rank'] === 1 ? 'er' : 'e') }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="px-3 py-3.5 text-slate-400 text-xs whitespace-nowrap">
                                                {{ $row['teacher']->getFullName() }}</td>
                                            <td class="px-4 py-3.5">
                                                @if (!empty($row['mention']))
                                                    <span
                                                        class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-medium
                                                        {{ str_contains(strtolower($row['mention']), 'bien') || str_contains(strtolower($row['mention']), 'très')
                                                            ? 'bg-emerald-500/10 text-emerald-400'
                                                            : (str_contains(strtolower($row['mention']), 'passable')
                                                                ? 'bg-amber-500/10 text-amber-400'
                                                                : 'bg-slate-500/10 text-slate-400') }}">
                                                        {{ $row['mention'] }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-600">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr class="bg-[#070b14] border-t border-white/[0.08]">
                                        <td colspan="2"
                                            class="px-4 py-4 text-right text-sm font-semibold text-slate-400">
                                            Moyenne générale :
                                        </td>
                                        <td class="px-3 py-4 text-center text-xl font-bold text-indigo-400 font-mono">
                                            {{ $this->termAverage['moyenne'] }}
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm font-semibold text-slate-400">
                                            Total :
                                        </td>
                                        <td class="px-3 py-4 text-center font-bold text-emerald-400 font-mono text-xl">
                                            {{ $this->termAverage['sum_moy_coef'] }}
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm font-semibold text-slate-400">
                                            Rang :
                                        </td>
                                        <td class="px-3 py-4 text-center text-sm font-bold text-white font-mono">
                                            {{ $this->termAverage['rank'] }}
                                            <span class="text-slate-500 font-normal">/
                                                {{ $this->termAverage['total'] }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-right font-semibold text-slate-400">
                                            Mention :
                                        </td>
                                        <td colspan="2" class="px-4 py-4">
                                            <span
                                                class="inline-flex px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-lg font-semibold">
                                                {{ $this->termAverage['mention'] }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </section>

                @if ($this->isLastPeriod && $this->yearlyAverage)
                    {{-- ════════════════ RÉSULTATS ANNUELS (dernière période uniquement) ════════════════ --}}
                    <section class="border-t border-white/[0.06]">
                        <div class="p-5 sm:p-8 lg:p-10">
                            <div class="flex items-center gap-2 mb-5">
                                <span
                                    class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-sm">📊</span>
                                <h3 class="text-lg font-semibold text-white">Résultats annuels</h3>
                            </div>

                            {{-- Stats de l'apprenant sur l'année --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                @foreach ([['Moy. annuelle', $this->yearlyAverage['moy_general'] ?? '—', 'text-indigo-400'], ['Rang annuel', $this->yearlyAverage['rang_general'] ?? null ? $this->yearlyAverage['rang_general'] . ' / ' . $this->yearlyClasseData['total'] : '—', 'text-amber-400'], ['Mention annuelle', $this->yearlyAverage['mention_generale'] ?? '—', 'text-emerald-400'], ['Réussite annuelle (apprenant)', ($this->yearlyAverage['success_percentage_annuel'] ?? null) !== null ? $this->yearlyAverage['success_percentage_annuel'] . '%' : '—', 'text-sky-400']] as $item)
                                    <div
                                        class="rounded-xl bg-[#070b14] border border-white/[0.05] px-3.5 py-3 text-center">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-600">
                                            {{ $item[0] }}</p>
                                        <p class="mt-1 text-lg font-bold {{ $item[2] }}">{{ $item[1] }}</p>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Stats de la classe sur l'année --}}
                            <div class="mt-2.5 grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                @foreach ([['1er annuel de la classe', $this->yearlyClasseData['premier']['moyenne'] ?? '—', 'text-emerald-400'], ['Dernier annuel de la classe', $this->yearlyClasseData['dernier']['moyenne'] ?? '—', 'text-red-400'], ['Réussite annuelle (classe)', ($this->yearlyClasseData['success_percentage_annuelle'] ?? null) !== null ? $this->yearlyClasseData['success_percentage_annuelle'] . '%' : '—', 'text-indigo-400'], ['Abandons sur l’année', $this->yearlyClasseData['effectifs']['abandons'] ?? 0, 'text-slate-300']] as $item)
                                    <div
                                        class="rounded-xl bg-white/[0.02] border border-white/[0.05] px-3.5 py-3 text-center">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-600">
                                            {{ $item[0] }}</p>
                                        <p class="mt-1 text-base font-bold {{ $item[2] }}">{{ $item[1] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Récapitulatif par période --}}
                            <div class="mt-5 overflow-x-auto rounded-2xl border border-white/[0.06]">
                                <table class="min-w-[500px] w-full text-sm">
                                    <thead>
                                        <tr class="bg-[#070b14] border-b border-white/[0.06]">
                                            <th
                                                class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                                {{ $this->activeYear->periodLabel() }}</th>
                                            <th
                                                class="px-4 py-3 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                                Moyenne</th>
                                            <th
                                                class="px-4 py-3 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                                Rang</th>
                                            <th
                                                class="px-4 py-3 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                                Mention</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/[0.03]">
                                        @foreach ($this->yearlyAverage['periods'] as $p => $entry)
                                            <tr>
                                                <td class="px-4 py-3 font-medium text-slate-200">
                                                    {{ $this->activeYear->periodLabel() }} {{ $p }}</td>
                                                <td class="px-4 py-3 text-center font-mono text-white">
                                                    {{ $entry['moyenne'] ?? '—' }}</td>
                                                <td class="px-4 py-3 text-center font-mono text-slate-400">
                                                    {{ $entry['rank'] ?? '—' }}
                                                    @if ($entry)
                                                        <span class="text-slate-600">/
                                                            {{ $entry['total'] ?? '—' }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center text-slate-300">
                                                    {{ $entry['mention'] ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- ════════════════ OBSERVATIONS ════════════════ --}}
                <section class="border-t border-white/[0.06]">
                    <div class="p-5 sm:p-8 lg:p-10">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                            <div class="rounded-2xl bg-[#070b14] border border-white/[0.05] p-5 sm:p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span
                                        class="w-8 h-8 rounded-lg bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sm">📋</span>
                                    <h3 class="text-sm font-semibold text-white">Observation générale</h3>
                                </div>
                                <p class="text-sm text-slate-400 leading-relaxed">
                                    Élève sérieux et discipliné. Les résultats sont satisfaisants dans l’ensemble.
                                    Quelques efforts supplémentaires sont attendus en Français afin d’améliorer
                                    davantage les performances globales.
                                </p>
                            </div>

                            <div class="rounded-2xl bg-[#070b14] border border-white/[0.05] p-5 sm:p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span
                                        class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-sm">⚖️</span>
                                    <h3 class="text-sm font-semibold text-white">Décision du jury</h3>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-slate-500">Décision</span>
                                        @if ($this->termAverage['moyenne'] >= 10)
                                            <span class="text-sm font-semibold text-emerald-400">Admis</span>
                                        @else
                                            <span class="text-sm font-semibold text-red-500">Réfusé</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-slate-500">Mention</span>
                                        <span
                                            class="text-sm font-semibold text-indigo-400">{{ $this->termAverage['mention'] }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-slate-500">Discipline</span>
                                        <span class="text-sm font-semibold text-slate-200">Très bonne</span>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl bg-[#070b14] border border-white/[0.05] p-5 sm:p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <span
                                        class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-sm">🔏</span>
                                    <h3 class="text-sm font-semibold text-white">Signature & cachet</h3>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-28 h-28 rounded-full border-2 border-dashed border-indigo-500/30 flex items-center justify-center text-center">
                                        <span
                                            class="text-[11px] text-indigo-400/70 leading-tight">Cachet<br>officiel</span>
                                    </div>
                                    <p class="mt-4 text-sm font-semibold text-slate-200">Le Directeur</p>
                                    <p class="text-[11px] text-slate-600 mt-0.5">Signature</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

        </div>
    </div>

</div>
