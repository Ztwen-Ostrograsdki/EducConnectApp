<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 py-8">

        {{-- ════════════════ HEADER ════════════════ --}}
        <header class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                <div class="min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-semibold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                            Directeur
                        </span>
                        <span class="text-[10px] font-mono text-slate-600">
                            {{ $tenant_dashboard_selected_school_year }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                        Bonjour,
                        <span class="bg-gradient-to-r from-indigo-400 to-emerald-400 bg-clip-text text-transparent">
                            {{ Auth::guard('tenant')->user()?->getFullName() ?? 'Directeur' }}
                        </span>
                    </h1>
                    <p class="mt-1.5 text-sm text-slate-500">
                        Tableau de bord —
                        <span class="text-slate-300 font-medium">{{ tenant('school_name') }}</span>
                    </p>

                    {{-- School meta chips --}}
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#0f1523] border border-white/5 text-[11px] text-slate-400">
                            <span class="text-slate-600">Type</span>
                            <span class="text-slate-300">{{ tenant('school_type') }}</span>
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#0f1523] border border-white/5 text-[11px] text-slate-400">
                            <span class="text-slate-600">Enseignement</span>
                            <span class="text-slate-300">{{ tenant('enseignement_type') }}</span>
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#0f1523] border border-white/5 text-[11px] text-slate-400">
                            <span class="text-slate-600">📍</span>
                            <span class="text-slate-300">{{ tenant('adresse') }}, {{ tenant('country') }}</span>
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#0f1523] border border-white/5 text-[11px] text-slate-400">
                            <span class="text-slate-600">📞</span>
                            <span class="text-slate-300">{{ tenant('contacts') }}</span>
                        </span>
                    </div>
                </div>

                <div class="flex gap-2 shrink-0">
                    <button
                        class="h-10 px-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-medium text-slate-300 transition-all inline-flex items-center gap-1.5">
                        <x-lucide-download class="w-3.5 h-3.5" />
                        Exporter
                    </button>
                    <button
                        class="h-10 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white transition-all inline-flex items-center gap-1.5 shadow-lg shadow-indigo-900/30">
                        <x-lucide-plus class="w-3.5 h-3.5" />
                        Nouvelle action
                    </button>
                </div>
            </div>
        </header>

        {{-- ════════════════ KPI ════════════════ --}}
        <section class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4 mb-8">

            @foreach ([['label' => 'Apprenants', 'value' => '847', 'delta' => '+12', 'deltaLabel' => 'vs année passée', 'icon' => '👨‍🎓', 'color' => 'indigo', 'positive' => true], ['label' => 'Enseignants', 'value' => '42', 'delta' => '+3', 'deltaLabel' => 'actifs', 'icon' => '👩‍🏫', 'color' => 'emerald', 'positive' => true], ['label' => 'Classes', 'value' => '18', 'delta' => '0', 'deltaLabel' => 'inchangé', 'icon' => '🏫', 'color' => 'amber', 'positive' => null], ['label' => 'Parents', 'value' => '634', 'delta' => '+28', 'deltaLabel' => 'inscrits', 'icon' => '👨‍👩‍👧', 'color' => 'violet', 'positive' => true], ['label' => 'Présence', 'value' => '94%', 'delta' => '+2%', 'deltaLabel' => 'cette semaine', 'icon' => '✅', 'color' => 'emerald', 'positive' => true], ['label' => 'Paiements dus', 'value' => '47', 'delta' => 'Urgent', 'deltaLabel' => 'à régulariser', 'icon' => '💳', 'color' => 'rose', 'positive' => false]] as $kpi)
                <div
                    class="group relative rounded-2xl bg-[#0f1523] border border-white/[0.06] p-4 sm:p-5 hover:border-{{ $kpi['color'] }}-500/30 hover:-translate-y-0.5 transition-all duration-200 shadow-lg shadow-black/10">
                    <div class="absolute top-3 right-3 text-xl opacity-30 group-hover:opacity-50 transition-opacity">
                        {{ $kpi['icon'] }}</div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                        {{ $kpi['label'] }}</p>
                    <p
                        class="text-2xl sm:text-3xl font-bold text-white tracking-tight leading-none
                              {{ $kpi['color'] === 'emerald' && $kpi['label'] === 'Présence' ? 'text-emerald-400' : '' }}
                              {{ $kpi['color'] === 'rose' ? 'text-rose-400' : '' }}">
                        {{ $kpi['value'] }}
                    </p>
                    <div class="flex items-center gap-1.5 mt-2.5 flex-wrap">
                        <span
                            class="text-[10px] font-medium px-1.5 py-0.5 rounded-md border
                            {{ $kpi['positive'] === true
                                ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                                : ($kpi['positive'] === false
                                    ? 'bg-rose-500/10 text-rose-400 border-rose-500/20'
                                    : 'bg-amber-500/10 text-amber-400 border-amber-500/20') }}">
                            {{ $kpi['delta'] }}
                        </span>
                        <span class="text-[10px] text-slate-600">{{ $kpi['deltaLabel'] }}</span>
                    </div>
                </div>
            @endforeach
        </section>

        {{-- ════════════════ CHARTS ════════════════ --}}
        <section class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-8">

            {{-- Présences --}}
            <div
                class="lg:col-span-3 rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-6 shadow-xl shadow-black/10">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-white">Présences</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">7 derniers jours</p>
                    </div>
                    <select
                        class="h-8 rounded-lg bg-[#070b14] border border-white/10 text-[11px] text-slate-400 px-2.5 focus:outline-none focus:border-indigo-500/40">
                        <option>7 jours</option>
                        <option>30 jours</option>
                        <option>Semestre</option>
                    </select>
                </div>

                <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)" class="flex items-end gap-2 h-32 pb-1">
                    @foreach ([['L', 92], ['M', 88], ['Me', 95], ['J', 90], ['V', 87], ['S', 82], ['D', null]] as $d)
                        <div class="flex-1 flex flex-col items-center gap-1.5">
                            @if ($d[1] !== null)
                                <div class="w-full max-w-[32px] mx-auto rounded-t-md bg-gradient-to-t from-indigo-600 to-indigo-400 transition-all duration-1000 ease-out"
                                    :style="loaded ?
                                        'height: {{ $d[1] }}px; transition-delay: {{ $loop->index * 80 }}ms' :
                                        'height: 0px'">
                                </div>
                            @else
                                <div class="flex-1"></div>
                            @endif
                            <span class="text-[10px] font-mono text-slate-600">{{ $d[0] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex gap-4 mt-4 pt-3 border-t border-white/5">
                    <div class="flex items-center gap-1.5 text-[11px] text-slate-500">
                        <span class="w-2 h-2 rounded-sm bg-indigo-500"></span> Présents
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] text-slate-500">
                        <span class="w-2 h-2 rounded-sm bg-rose-500"></span> Absents
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] text-slate-500">
                        <span class="w-2 h-2 rounded-sm bg-amber-500"></span> Retards
                    </div>
                </div>
            </div>

            {{-- Répartition --}}
            <div class="lg:col-span-2 rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-6 shadow-xl shadow-black/10"
                x-data="{ show: false }" x-init="setTimeout(() => show = true, 150)">
                <h3 class="text-sm font-semibold text-white">Répartition niveaux</h3>
                <p class="text-[11px] text-slate-500 mt-0.5 mb-5">Effectifs par cycle</p>

                @foreach ([['Primaire', '312', 37, 'from-emerald-600 to-emerald-400'], ['Secondaire', '428', 51, 'from-indigo-600 to-indigo-400'], ['Supérieur', '107', 12, 'from-amber-600 to-amber-400']] as $n)
                    <div class="mb-4 transition-all duration-700"
                        :style="show ? 'opacity:1; transform:translateY(0)' : 'opacity:0; transform:translateY(10px)'"
                        style="transition-delay: {{ $loop->index * 120 }}ms">
                        <div class="flex justify-between mb-1.5">
                            <span class="text-xs font-medium text-slate-300">{{ $n[0] }}</span>
                            <span class="text-[11px] font-mono text-slate-500">{{ $n[1] }} ·
                                {{ $n[2] }}%</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-[#070b14] overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $n[3] }} transition-all duration-1000"
                                :style="show ? 'width: {{ $n[2] }}%' : 'width: 0%'"
                                style="transition-delay: {{ 150 + $loop->index * 120 }}ms"></div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-5 pt-4 border-t border-white/5" :class="show ? 'opacity-100' : 'opacity-0'"
                    style="transition: opacity 0.6s ease 0.5s">
                    <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-1">Total</p>
                    <p class="text-2xl font-bold text-white">
                        847
                        <span class="text-sm font-normal text-slate-500">apprenants</span>
                    </p>
                </div>
            </div>
        </section>

        {{-- ════════════════ LISTS ════════════════ --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-4 pb-12">

            {{-- Classes actives --}}
            <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                    <h3 class="text-sm font-semibold text-white">Classes actives</h3>
                    <a href="#" class="text-[11px] text-indigo-400 hover:text-indigo-300 transition">Voir tout
                        →</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th
                                    class="px-5 py-2.5 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-600">
                                    Classe</th>
                                <th
                                    class="px-3 py-2.5 text-center text-[10px] font-semibold uppercase tracking-wider text-slate-600">
                                    Effectif</th>
                                <th
                                    class="px-3 py-2.5 text-center text-[10px] font-semibold uppercase tracking-wider text-slate-600">
                                    Présence</th>
                                <th
                                    class="px-5 py-2.5 text-center text-[10px] font-semibold uppercase tracking-wider text-slate-600">
                                    Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([['Terminale C', '38', '96%'], ['3ème 1', '42', '91%'], ['6ème A', '45', '88%'], ['Tle BTP 2', '35', '94%'], ['2nde 3', '40', '85%']] as $c)
                                <tr class="border-b border-white/[0.03] hover:bg-white/[0.02] transition-colors">
                                    <td class="px-5 py-3 text-sm font-medium text-slate-200">{{ $c[0] }}</td>
                                    <td class="px-3 py-3 text-center text-xs font-mono text-slate-400">
                                        {{ $c[1] }}</td>
                                    <td class="px-3 py-3 text-center text-xs font-mono text-emerald-400">
                                        {{ $c[2] }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Actif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between px-5 py-3 border-t border-white/5">
                    <span class="text-[11px] text-slate-600">1–5 sur 18</span>
                    <div class="flex gap-1.5">
                        <button
                            class="h-7 px-2.5 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-[11px] text-slate-400 transition">‹</button>
                        <button
                            class="h-7 px-2.5 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-[11px] text-slate-400 transition">›</button>
                    </div>
                </div>
            </div>

            {{-- Enseignants --}}
            <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                    <h3 class="text-sm font-semibold text-white">Enseignants actifs</h3>
                    <a href="#" class="text-[11px] text-indigo-400 hover:text-indigo-300 transition">Gérer →</a>
                </div>

                <div class="divide-y divide-white/[0.03]">
                    @foreach ([['Sylvie Amoussou', 'Maths — Tle C, 3ème 1', 'SA', 'actif'], ['Fabrice Bossou', 'Physique — 3ème, 2nde', 'FB', 'actif'], ['Jean Koudé', 'Histoire — 6ème, 5ème', 'JK', 'actif'], ['Marie Adjovi', 'Français — Tle, 1ère', 'MA', 'actif'], ['Kofi Mensah', 'SVT — 2nde, 3ème', 'KM', 'en attente']] as $t)
                        <div class="flex items-center gap-3 px-5 py-3 hover:bg-white/[0.02] transition-colors">
                            <div
                                class="w-9 h-9 shrink-0 rounded-xl bg-gradient-to-br from-indigo-500 to-emerald-500 flex items-center justify-center text-[11px] font-bold text-white">
                                {{ $t[2] }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-200 truncate">{{ $t[0] }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ $t[1] }}</p>
                            </div>
                            @if ($t[3] === 'actif')
                                <span
                                    class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">actif</span>
                            @else
                                <span
                                    class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">en
                                    attente</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
</div>
