<div>

    {{-- ── Page Header ── --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between flex-wrap mb-7">
        <div>
            <h1 class="font-serif text-3xl italic font-normal leading-tight text-slate-100">
                Bonjour,
                <span class="bg-gradient-to-br from-indigo-500 to-emerald-500 bg-clip-text text-transparent">
                    {{ Auth::guard('tenant')->user()?->getFullName() ?? 'Directeur' }}
                </span>
                👋
            </h1>
            <p class="text-xs text-slate-400 mt-1">
                Le tableau de bord de votre établissement <span class="text-amber-500 font-semibold opacity-80"> {{ tenant('school_name') }}</span> — Année scolaire
                <span class="text-emerald-400 font-mono">{{ $tenant_dashboard_selected_school_year }}</span>
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button class="flex items-center gap-1.5 px-3.5 py-2 bg-indigo-500/10 border border-indigo-500/25 rounded-lg text-indigo-400 text-xs font-semibold hover:bg-indigo-500/20 transition-colors cursor-pointer">
                📤 Exporter
            </button>
            <button class="flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-br from-indigo-500 to-violet-500 border-0 rounded-lg text-white text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer">
                ➕ Nouvelle action
            </button>
        </div>
    </div>

    {{-- ── KPI Cards ── --}}
    <div class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-4 mb-7">

        {{-- Apprenants --}}
        <div data-animate="card" class=" relative overflow-hidden bg-slate-800 border border-slate-700 rounded-2xl p-5 transition-[border-color,transform] duration-200 hover:border-indigo-500/40 hover:-translate-y-0.5">
            <div class="absolute top-4 right-4 text-2xl opacity-40">👨‍🎓</div>
            <div class="font-mono text-[0.62rem] text-slate-400 uppercase tracking-widest mb-2">Apprenants</div>
            <div class="font-serif text-[2.2rem] font-normal text-slate-100 leading-none">847</div>
            <div class="flex items-center gap-1.5 mt-2.5">
                <span class="text-[0.7rem] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20">↑ +12</span>
                <span class="text-[0.7rem] text-slate-400">vs année passée</span>
            </div>
        </div>

        {{-- Enseignants --}}
        <div data-animate="card" class=" relative overflow-hidden bg-slate-800 border border-slate-700 rounded-2xl p-5 transition-[border-color,transform] duration-200 hover:border-emerald-500/40 hover:-translate-y-0.5">
            <div class="absolute top-4 right-4 text-2xl opacity-40">👩‍🏫</div>
            <div class="font-mono text-[0.62rem] text-slate-400 uppercase tracking-widest mb-2">Enseignants</div>
            <div class="font-serif text-[2.2rem] font-normal text-slate-100 leading-none">42</div>
            <div class="flex items-center gap-1.5 mt-2.5">
                <span class="text-[0.7rem] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20">↑ +3</span>
                <span class="text-[0.7rem] text-slate-400">actifs cette année</span>
            </div>
        </div>

        {{-- Classes --}}
        <div data-animate="card" class=" relative overflow-hidden bg-slate-800 border border-slate-700 rounded-2xl p-5 transition-[border-color,transform] duration-200 hover:border-amber-500/40 hover:-translate-y-0.5">
            <div class="absolute top-4 right-4 text-2xl opacity-40">🏫</div>
            <div class="font-mono text-[0.62rem] text-slate-400 uppercase tracking-widest mb-2">Classes</div>
            <div class="font-serif text-[2.2rem] font-normal text-slate-100 leading-none">18</div>
            <div class="flex items-center gap-1.5 mt-2.5">
                <span class="text-[0.7rem] text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-500/20">→ 0</span>
                <span class="text-[0.7rem] text-slate-400">inchangé</span>
            </div>
        </div>

        {{-- Parents --}}
        <div data-animate="card" class=" relative overflow-hidden bg-slate-800 border border-slate-700 rounded-2xl p-5 transition-[border-color,transform] duration-200 hover:border-violet-500/40 hover:-translate-y-0.5">
            <div class="absolute top-4 right-4 text-2xl opacity-40">👨‍👩‍👧</div>
            <div class="font-mono text-[0.62rem] text-slate-400 uppercase tracking-widest mb-2">Parents / Tuteurs</div>
            <div class="font-serif text-[2.2rem] font-normal text-slate-100 leading-none">634</div>
            <div class="flex items-center gap-1.5 mt-2.5">
                <span class="text-[0.7rem] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20">↑ +28</span>
                <span class="text-[0.7rem] text-slate-400">inscrits cette année</span>
            </div>
        </div>

        {{-- Taux présence --}}
        <div data-animate="card" class=" relative overflow-hidden bg-slate-800 border border-slate-700 rounded-2xl p-5 transition-[border-color,transform] duration-200 hover:border-emerald-500/40 hover:-translate-y-0.5">
            <div class="absolute top-4 right-4 text-2xl opacity-40">✅</div>
            <div class="font-mono text-[0.62rem] text-slate-400 uppercase tracking-widest mb-2">Taux présence</div>
            <div class="font-serif text-[2.2rem] font-normal text-emerald-400 leading-none">94%</div>
            <div class="flex items-center gap-1.5 mt-2.5">
                <span class="text-[0.7rem] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20">↑ +2%</span>
                <span class="text-[0.7rem] text-slate-400">cette semaine</span>
            </div>
        </div>

        {{-- Paiements --}}
        <div data-animate="card" class=" relative overflow-hidden bg-slate-800 border border-slate-700 rounded-2xl p-5 transition-[border-color,transform] duration-200 hover:border-rose-500/40 hover:-translate-y-0.5">
            <div class="absolute top-4 right-4 text-2xl opacity-40">💳</div>
            <div class="font-mono text-[0.62rem] text-slate-400 uppercase tracking-widest mb-2">Paiements en attente</div>
            <div class="font-serif text-[2.2rem] font-normal text-rose-400 leading-none">47</div>
            <div class="flex items-center gap-1.5 mt-2.5">
                <span class="text-[0.7rem] text-rose-400 bg-rose-500/10 px-2 py-0.5 rounded-full border border-rose-500/20">⚠ Urgent</span>
                <span class="text-[0.7rem] text-slate-400">à régulariser</span>
            </div>
        </div>

    </div>

    {{-- ── Charts + Répartition ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-4 mb-7">

        {{-- Graphique présences --}}
        <div data-z-card class="bg-slate-800 border transition-[transform] duration-200 hover:-translate-y-0.5 border-slate-700 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-sm font-bold text-slate-100">Présences — 7 derniers jours</div>
                    <div class="font-mono text-[0.62rem] text-slate-500">Présents / Absents / Retards</div>
                </div>
                <select class="font-mono text-[0.65rem] bg-slate-700 border border-slate-600 text-slate-400 px-2 py-1.5 rounded-md cursor-pointer">
                    <option>7 jours</option>
                    <option>30 jours</option>
                    <option>Semestre</option>
                </select>
            </div>

            <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)" class="flex items-end gap-2 h-28 pb-2">
                @foreach ([['L', 92], ['M', 88], ['Me', 95], ['J', 90], ['V', 87], ['S', 82], ['D', null]] as $d)
                    <div class="flex-1 flex flex-col items-center gap-1">

                        @if ($d[1] !== null)
                            <div class="w-full rounded-t bg-indigo-500/70 transition-all duration-1000 ease-out"
                                :style="loaded ? 'height: {{ $d[1] }}px; transition-delay: {{ $loop->index * 120 }}ms' :
                                    'height:0px'"></div>
                        @else
                            <div class="flex-1"></div>
                        @endif

                        <div class="font-mono text-[0.55rem] text-slate-500">
                            {{ $d[0] }}
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="flex gap-4 mt-3">
                <div class="flex items-center gap-1.5 text-[0.68rem] text-slate-400">
                    <span class="w-2 h-2 rounded-sm bg-indigo-500/70 inline-block"></span>Présents
                </div>
                <div class="flex items-center gap-1.5 text-[0.68rem] text-slate-400">
                    <span class="w-2 h-2 rounded-sm bg-rose-500/70 inline-block"></span>Absents
                </div>
                <div class="flex items-center gap-1.5 text-[0.68rem] text-slate-400">
                    <span class="w-2 h-2 rounded-sm bg-amber-500/70 inline-block"></span>Retards
                </div>
            </div>
        </div>

        {{-- Répartition par niveau --}}
        <div data-z-card x-data="{ show: false }" x-init="setTimeout(() => show = true, 150)" class="bg-slate-800 border border-slate-700 transition-[transform] duration-200 hover:-translate-y-0.5 rounded-2xl p-5">
            <div class="text-sm font-bold text-slate-100 mb-0.5">
                Répartition niveaux
            </div>

            <div class="font-mono text-[0.62rem] text-slate-500 mb-4">
                Effectifs par cycle
            </div>

            @foreach ([['Primaire', '312', 37, 'bg-emerald-500/70'], ['Secondaire', '428', 51, 'bg-indigo-500/70'], ['Supérieur', '107', 12, 'bg-amber-500/70']] as $n)
                <div class="mb-3.5 transition-all duration-700 ease-out"
                    :style="show
                        ?
                        'opacity:1; transform:translateY(0px); transition-delay: {{ $loop->index * 180 }}ms' :
                        'opacity:0; transform:translateY(14px)'">

                    <div class="flex justify-between mb-1">
                        <span class="text-[0.78rem] font-medium text-slate-200">
                            {{ $n[0] }}
                        </span>

                        <span class="font-mono text-[0.65rem] text-slate-400">
                            {{ $n[1] }} — {{ $n[2] }}%
                        </span>
                    </div>

                    <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">

                        <div class="h-full {{ $n[3] }}
                        rounded-full
                        origin-left
                        transition-all duration-1000 ease-out"
                            :style="show
                                ?
                                'width: {{ $n[2] }}%; transition-delay: {{ 200 + $loop->index * 180 }}ms' :
                                'width:0%'">
                        </div>

                    </div>

                </div>
            @endforeach

            <div class="mt-4 pt-3.5 border-t border-slate-700
                transition-all duration-700 ease-out"
                :style="show
                    ?
                    'opacity:1; transform:translateY(0px); transition-delay:700ms' :
                    'opacity:0; transform:translateY(12px)'">
                <div class="font-mono text-[0.6rem] text-slate-500 mb-1.5">
                    TOTAL
                </div>

                <div class="font-serif text-3xl text-slate-100">
                    847
                    <span class="text-sm text-slate-400 font-sans">
                        apprenants
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Classes actives + Activité récente ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-7">

        {{-- Classes actives --}}
        <div data-z-card class="bg-slate-800 border border-slate-700 transition-[transform] duration-200 hover:-translate-y-0.5 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
                <div class="text-sm font-bold text-slate-100">Classes actives</div>
                <a href="#" class="font-mono text-[0.65rem] text-indigo-400 no-underline hover:text-indigo-300 transition-colors">Voir tout →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-700/50">
                            <th class="font-mono text-[0.6rem] text-slate-500 uppercase tracking-widest px-5 py-2.5 text-left">Classe</th>
                            <th class="font-mono text-[0.6rem] text-slate-500 uppercase tracking-widest px-3 py-2.5 text-center">Effectif</th>
                            <th class="font-mono text-[0.6rem] text-slate-500 uppercase tracking-widest px-3 py-2.5 text-center">Présence</th>
                            <th class="font-mono text-[0.6rem] text-slate-500 uppercase tracking-widest px-5 py-2.5 text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([['Terminale C', '38', '96%'], ['3ème 1', '42', '91%'], ['6ème A', '45', '88%'], ['Tle BTP 2', '35', '94%'], ['2nde 3', '40', '85%']] as $c)
                            <tr class="border-b border-slate-700 hover:bg-white/[.02] transition-colors">
                                <td class="px-5 py-2.5 text-[0.8rem] font-semibold text-slate-100">{{ $c[0] }}</td>
                                <td class="px-3 py-2.5 text-center font-mono text-[0.72rem] text-slate-400">{{ $c[1] }}</td>
                                <td class="px-3 py-2.5 text-center font-mono text-[0.7rem] text-emerald-400">{{ $c[2] }}</td>
                                <td class="px-5 py-2.5 text-center">
                                    <span class="font-mono text-[0.62rem] px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Actif</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center px-5 py-3 border-t border-slate-700">
                <span class="text-[0.72rem] text-slate-500">Affichage 1–5 sur 18</span>
                <div class="flex gap-1.5">
                    <button class="px-2.5 py-1 bg-slate-700 border border-slate-600 rounded-md text-slate-400 text-[0.7rem] hover:bg-slate-600 transition-colors cursor-pointer">‹ Préc</button>
                    <button class="px-2.5 py-1 bg-slate-700 border border-slate-600 rounded-md text-slate-400 text-[0.7rem] hover:bg-slate-600 transition-colors cursor-pointer">Suiv ›</button>
                </div>
            </div>
        </div>

        {{-- Activité récente --}}
        <div data-z-card class="bg-slate-800 border border-slate-700 transition-[transform] duration-200 hover:-translate-y-0.5 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
                <div class="text-sm font-bold text-slate-100">Activité récente</div>
                <span class="font-mono text-[0.62rem] px-2 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full">En direct</span>
            </div>
            <div class="py-1">
                @foreach ([['📝', 'Notes saisies — Maths 3ème 1', 'Prof. Amoussou', '5 min'], ['👤', 'Nouvel apprenant inscrit', 'Kofi Danso', '12 min'], ['💳', 'Paiement reçu — 25 000 FCFA', 'Famille Mensah', '1h'], ['🔔', 'Absence signalée — Terminale C', 'Auto', '2h'], ['📄', 'Bulletin généré — Tle C', 'Directeur', '3h'], ['👩‍🏫', 'Enseignant affilié — Prof. Bossou', 'Directeur', '5h'], ['⚠️', 'Note modifiée avec justification', 'Prof. Koudé', '7h']] as $a)
                    <div class="flex items-start gap-3 px-5 py-2.5 border-b border-slate-700/50 hover:bg-white/[.02] transition-colors">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-slate-700 flex items-center justify-center text-sm mt-0.5">{{ $a[0] }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[0.78rem] font-medium text-slate-200 truncate">{{ $a[1] }}</div>
                            <div class="font-mono text-[0.6rem] text-slate-500">{{ $a[2] }}</div>
                        </div>
                        <div class="font-mono text-[0.6rem] text-slate-500 shrink-0">{{ $a[3] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── Enseignants + Paiements ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Enseignants actifs --}}
        <div data-z-card class="bg-slate-800 border border-slate-700 transition-[transform] duration-200 hover:-translate-y-0.5 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
                <div class="text-sm font-bold text-slate-100">Enseignants actifs</div>
                <a href="#" class="font-mono text-[0.65rem] text-indigo-400 no-underline hover:text-indigo-300 transition-colors">Gérer →</a>
            </div>
            <div class="py-1">
                @foreach ([['Sylvie Amoussou', 'Maths — Tle C, 3ème 1', 'SA', 'actif'], ['Fabrice Bossou', 'Physique — 3ème, 2nde', 'FB', 'actif'], ['Jean Koudé', 'Histoire — 6ème, 5ème', 'JK', 'actif'], ['Marie Adjovi', 'Français — Tle, 1ère', 'MA', 'actif'], ['Kofi Mensah', 'SVT — 2nde, 3ème', 'KM', 'en attente']] as $t)
                    <div class="flex items-center gap-3 px-5 py-2.5 border-b border-slate-700/50 hover:bg-white/[.02] transition-colors">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-gradient-to-br from-indigo-500 to-emerald-500 flex items-center justify-center text-[0.65rem] font-extrabold text-white">{{ $t[2] }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[0.8rem] font-semibold text-slate-100">{{ $t[0] }}</div>
                            <div class="font-mono text-[0.6rem] text-slate-500 truncate">{{ $t[1] }}</div>
                        </div>
                        @if ($t[3] === 'actif')
                            <span class="font-mono text-[0.62rem] px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shrink-0">actif</span>
                        @else
                            <span class="font-mono text-[0.62rem] px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 shrink-0">en attente</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Paiements en attente --}}
        <div data-z-card class="bg-slate-800 border border-slate-700 transition-[transform] duration-200 hover:-translate-y-0.5 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
                <div class="text-sm font-bold text-slate-100">Paiements en attente</div>
                <span class="font-mono text-[0.62rem] px-2 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-full">47 urgents</span>
            </div>
            <div class="py-1">
                @foreach ([['Aminata Mensah', 'Terminale C', 'Scolarité S1', '50 000', 'en attente'], ['Kofi Danso', '3ème 1', 'Inscription', '25 000', 'partiel'], ['Fatoumata Bah', '6ème A', 'Scolarité S1', '45 000', 'en attente'], ['Jean-Marc Glo', 'Tle BTP 2', 'Scolarité S1', '55 000', 'en attente'], ['Abigaël Yovo', '2nde 3', 'Cantine', '15 000', 'partiel']] as $p)
                    <div class="flex items-center gap-3 px-5 py-2.5 border-b border-slate-700/50 hover:bg-white/[.02] transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="text-[0.78rem] font-semibold text-slate-100">{{ $p[0] }}</div>
                            <div class="font-mono text-[0.6rem] text-slate-500">{{ $p[1] }} — {{ $p[2] }}</div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-mono text-[0.72rem] text-slate-100 mb-0.5">{{ $p[3] }} F</div>
                            @if ($p[4] === 'en attente')
                                <span class="font-mono text-[0.58rem] px-1.5 py-0.5 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20">en attente</span>
                            @else
                                <span class="font-mono text-[0.58rem] px-1.5 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">partiel</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-5 py-3 border-t border-slate-700">
                <button class="w-full py-2 bg-rose-500/[.08] border border-rose-500/20 rounded-lg text-rose-400 text-[0.78rem] font-semibold hover:bg-rose-500/15 transition-colors cursor-pointer">
                    Gérer tous les paiements en attente →
                </button>
            </div>
        </div>

    </div>

</div>

