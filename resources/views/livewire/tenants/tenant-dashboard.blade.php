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
                            Compte administrateur — Directeur
                        </span>
                        <span class="text-[12px] font-mono text-slate-600">
                            {{ $this->activeYear?->slug }}
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
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#0f1523] border border-white/5 text-[11px] text-slate-400">
                            <span class="text-slate-600">📚</span>
                            <span class="text-slate-300">Enseignement {{ tenant('level') }}</span>
                        </span>
                    </div>
                </div>
                <div>
                    <span class="text-lg font-mono text-slate-600 animate-pulse">
                        Année - scolaire : {{ $this->activeYear?->slug ?? 'Aucune année active' }}
                    </span>
                </div>
            </div>
        </header>
        {{-- ════════════════ KPI ════════════════ --}}
        <section class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4 mb-8">

            @foreach ([
        ['label' => 'Apprenants', 'value' => $this->stats['students'], 'delta' => '+12', 'deltaLabel' => 'vs année passée', 'icon' => '👨‍🎓', 'color' => 'indigo', 'positive' => true],
        ['label' => 'Enseignants', 'value' => $this->stats['teachers'], 'delta' => '+3', 'deltaLabel' => 'actifs', 'icon' => '👩‍🏫', 'color' => 'emerald', 'positive' => true],
        ['label' => 'Parents/Tuteurs', 'value' => $this->stats['tutors'], 'delta' => '+3', 'deltaLabel' => 'actifs', 'icon' => '👥', 'color' => 'emerald', 'positive' => true],
        ['label' => 'Classes', 'value' => $this->stats['classes_actives'], 'delta' => '0', 'deltaLabel' => 'inchangé', 'icon' => '🏫', 'color' => 'amber', 'positive' => null],
        ['label' => 'Promotions', 'value' => $this->stats['promotions_actives'], 'delta' => '+28', 'deltaLabel' => 'inscrits', 'icon' => '🗃️', 'color' => 'violet', 'positive' => true],
        ['label' => 'Filières', 'value' => $this->stats['filiars_actives'], 'delta' => '+28', 'deltaLabel' => 'inscrits', 'icon' => '📚', 'color' => 'violet', 'positive' => true],
        ['label' => 'Séries', 'value' => $this->stats['serials_actives'], 'delta' => '+28', 'deltaLabel' => 'inscrits', 'icon' => '🎯', 'color' => 'violet', 'positive' => true],
    ] as $kpi)
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

            {{-- Abandons --}}
            <div wire:loading.class='opacity-20'
                wire:target="nextPage('leavesPage'), previousPage('leavesPage'), gotoPage"
                class="lg:col-span-3 rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-6 shadow-xl shadow-black/10">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-white">
                            <span>Abandons</span>
                            <span
                                class="font-mono uppercase text-2xs rounded-lg p-1 px-3 bg-indigo-600/20 text-indigo-600">
                                <span>{{ $this->studentsLeaves->total() }}</span>
                                <span>apprenants</span>
                            </span>
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Les apprenants ayant abandonnés</p>
                    </div>
                </div>

                <div class="space-y-2">
                    @forelse ($this->studentsLeaves as $student)
                        <div wire:key="leave-{{ $student->id }}"
                            class="group flex items-center justify-between gap-3 rounded-xl bg-[#0f1523] border border-white/[0.05] hover:border-amber-500/25 px-3.5 py-2 transition-all duration-200">

                            <a wire:navigate
                                href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                class="inline-flex items-center gap-x-1.5 group">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold tabular-nums shrink-0">
                                    {{ __zero($loop->iteration) }}
                                </span>
                                <div
                                    class="w-9 h-9 rounded-xl bg-slate-800 border border-white/5 flex items-center justify-center text-xs font-bold text-slate-400 shrink-0 group-hover:text-sky-500">
                                    {{ strtoupper(str()->substr($student->getFullName(), 0, 1)) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-medium text-slate-200 truncate transition-colors group-hover:text-sky-500">
                                        {{ $student->getFullName() }}
                                    </p>
                                    <p class="text-[11px] text-orange-400/50 mt-0.5 group-hover:text-orange-500">
                                        Abandon
                                    </p>
                                </div>
                            </a>

                            <span
                                class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-lg bg-[#070b14] border border-white/5 text-[11px] font-mono text-amber-400/90 uppercase group-hover:text-sky-500">
                                {{ $student->classe->code ? $student->classe->code : $student->classe->name }}
                            </span>
                        </div>
                    @empty
                        <div class="rounded-xl bg-[#0f1523] border border-white/[0.05] py-12 text-center">
                            <p class="text-sm text-slate-600">Aucun abandon enregistré</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($this->studentsLeaves->hasPages())
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 mt-2 border-t border-white/[0.05]">
                        <p class="text-xs text-slate-600">
                            {{ $this->studentsLeaves->firstItem() }}–{{ $this->studentsLeaves->lastItem() }}
                            sur {{ $this->studentsLeaves->total() }} apprenants
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$this->studentsLeaves->onFirstPage())
                                <button wire:click="previousPage('leavesPage')" wire:loading.attr="disabled"
                                    wire:target="previousPage('leavesPage')"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                    ← Préc.
                                </button>
                            @endif
                            @foreach ($this->studentsLeaves->getUrlRange(1, $this->studentsLeaves->lastPage()) as $page => $url)
                                <button wire:click="gotoPage({{ $page }}, 'leavesPage')"
                                    wire:target="gotoPage({{ $page }}, 'leavesPage')"
                                    class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                            {{ $page === $this->studentsLeaves->currentPage()
                                ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/30'
                                : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                            @if ($this->studentsLeaves->hasMorePages())
                                <button wire:click="nextPage('leavesPage')" wire:loading.attr="disabled"
                                    wire:target="nextPage('leavesPage')"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                    Suiv. →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Répartition --}}
            <div class="lg:col-span-2 rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-6 shadow-xl shadow-black/10"
                x-data="{ show: false }" x-init="setTimeout(() => show = true, 150)">
                <h3 class="text-sm font-semibold text-white">Répartition des apprenants par promotions</h3>
                <p class="text-[11px] text-slate-500 mt-0.5 mb-5">Effectifs par promotion</p>

                @php
                    $gradients = [
                        'from-emerald-600 to-emerald-400',
                        'from-indigo-600 to-indigo-400',
                        'from-amber-600 to-amber-400',
                        'from-sky-600 to-sky-400',
                        'from-pink-600 to-pink-400',
                        'from-violet-600 to-violet-400',
                    ];
                @endphp

                @forelse ($this->promotionGroupsCounts['promotions'] as $name => $data)
                    <div class="mb-4 transition-all duration-700"
                        :style="show ? 'opacity:1; transform:translateY(0)' : 'opacity:0; transform:translateY(10px)'"
                        style="transition-delay: {{ $loop->index * 120 }}ms">
                        <div class="flex justify-between mb-1.5">
                            <span class="text-xs font-medium text-slate-300">{{ $name }}</span>
                            <span class="text-[11px] font-mono text-slate-500">{{ $data['count'] }} ·
                                {{ $data['percentage'] }}%</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-[#070b14] overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $gradients[$loop->index % count($gradients)] }} transition-all duration-1000"
                                :style="show ? 'width: {{ $data['percentage'] }}%' : 'width: 0%'"
                                style="transition-delay: {{ 150 + $loop->index * 120 }}ms"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">Aucune donnée disponible pour cette année scolaire.</p>
                @endforelse

                <div class="mt-5 pt-4 border-t border-white/5" :class="show ? 'opacity-100' : 'opacity-0'"
                    style="transition: opacity 0.6s ease 0.5s">
                    <p class="text-[10px] uppercase tracking-wider text-slate-600 mb-1">Total</p>
                    <p class="text-2xl font-bold text-white">
                        {{ $this->promotionGroupsCounts['total'] }}
                        <span class="text-sm font-normal text-slate-500">apprenants</span>
                    </p>
                </div>
            </div>
        </section>

        {{-- ════════════════ LISTS ════════════════ --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-4 pb-12">

            {{-- LES CA --}}
            <div wire:loading.class='opacity-20' wire:target="nextPage('casPage'), previousPage('casPage'), gotoPage"
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                    <h3 class="text-sm font-semibold text-white"> Les Chefs ateliers</h3>
                </div>

                <div>
                    <div class="space-y-3 p-3 sm:p-4">
                        @forelse ($this->cas as $filiar)
                            @php
                                $chiefs = $filiar->currentChiefs;
                                $principal = $chiefs[0] ?? null;
                                $adjoint = $chiefs[1] ?? null;
                            @endphp

                            <article wire:key="filiar-ca-{{ $filiar->id }}"
                                class="rounded-xl bg-[#070b14] border border-white/[0.05] hover:border-violet-500/20 transition-all duration-200 overflow-hidden">

                                <div class="flex items-center gap-3 px-4 py-3 border-b border-white/[0.04]">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-violet-500/15 border border-violet-500/20 flex items-center justify-center shrink-0">
                                        <x-lucide-wrench class="w-4 h-4 text-violet-400" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-semibold text-white truncate">{{ $filiar->name }}</h3>
                                        <p class="text-[10px] text-slate-600 uppercase tracking-wider">Filière</p>
                                    </div>
                                    @if ($filiar->code ?? null)
                                        <span
                                            class="shrink-0 px-2 py-0.5 rounded-md bg-white/5 border border-white/5 text-[10px] font-mono text-slate-400">
                                            {{ $filiar->code }}
                                        </span>
                                    @endif
                                </div>

                                <div class="grid sm:grid-cols-2 gap-px bg-white/[0.03]">
                                    <div class="bg-[#070b14] px-4 py-3.5">
                                        <p
                                            class="text-[10px] uppercase tracking-wider text-slate-600 font-semibold mb-2">
                                            CA Principal
                                        </p>
                                        @if ($principal)
                                            <div class="flex items-center gap-2.5">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-[10px] font-bold text-white shrink-0">
                                                    {{ strtoupper(str()->substr($principal->getFullName(), 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-slate-200 truncate">
                                                        {{ $principal->getFullName() }}
                                                    </p>
                                                    @if ($principal->contacts ?? null)
                                                        <p class="text-[11px] text-slate-500 truncate">
                                                            {{ $principal->contacts }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-600 italic">Non désigné</p>
                                        @endif
                                    </div>

                                    <div class="bg-[#070b14] px-4 py-3.5">
                                        <p
                                            class="text-[10px] uppercase tracking-wider text-slate-600 font-semibold mb-2">
                                            CA Adjoint
                                        </p>
                                        @if ($adjoint)
                                            <div class="flex items-center gap-2.5">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center text-[10px] font-bold text-white shrink-0">
                                                    {{ strtoupper(str()->substr($adjoint->getFullName(), 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-slate-200 truncate">
                                                        {{ $adjoint->getFullName() }}
                                                    </p>
                                                    @if ($adjoint->contacts ?? null)
                                                        <p class="text-[11px] text-slate-500 truncate">
                                                            {{ $adjoint->contacts }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-600 italic">Non désigné</p>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="py-14 text-center">
                                <div
                                    class="w-12 h-12 mx-auto rounded-xl bg-white/5 border border-white/5 flex items-center justify-center mb-3">
                                    <x-lucide-wrench class="w-5 h-5 text-slate-600" />
                                </div>
                                <p class="text-sm text-slate-600">Aucune filière avec chefs d'atelier</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if ($this->cas->hasPages())
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 sm:px-5 py-3.5 border-t border-white/[0.05]">
                            <p class="text-xs text-slate-600">
                                {{ $this->cas->firstItem() }}–{{ $this->cas->lastItem() }}
                                sur {{ $this->cas->total() }} filières
                            </p>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @if (!$this->cas->onFirstPage())
                                    <button wire:click="previousPage('casPage')" wire:loading.attr="disabled"
                                        wire:target="previousPage('casPage')"
                                        class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                        ← Préc.
                                    </button>
                                @endif
                                @foreach ($this->cas->getUrlRange(1, $this->cas->lastPage()) as $page => $url)
                                    <button wire:click="gotoPage({{ $page }}, 'casPage')"
                                        wire:target="gotoPage({{ $page }}, 'casPage')"
                                        class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                {{ $page === $this->cas->currentPage()
                                    ? 'bg-violet-600 text-white shadow-lg shadow-violet-900/30'
                                    : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400' }}">
                                        {{ $page }}
                                    </button>
                                @endforeach
                                @if ($this->cas->hasMorePages())
                                    <button wire:click="nextPage('casPage')" wire:loading.attr="disabled"
                                        wire:target="nextPage('casPage')"
                                        class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                        Suiv. →
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- LES AE  --}}
            <div wire:loading.class='opacity-20' wire:target="nextPage('aesPage'), previousPage('aesPage'), gotoPage"
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                    <h3 class="text-sm font-semibold text-white"> Les animateurs d'établissement (AE)</h3>
                </div>

                <div>
                    <div class="space-y-3 p-3 sm:p-4">
                        @forelse ($this->aes as $subject)
                            @php
                                $chiefs = $subject->currentChiefs;
                                $principal = $chiefs[0] ?? null;
                                $adjoint = $chiefs[1] ?? null;
                            @endphp

                            <article wire:key="subject-ae-{{ $subject->id }}"
                                class="rounded-xl bg-[#070b14] border border-white/[0.05] hover:border-violet-500/20 transition-all duration-200 overflow-hidden">

                                <div class="flex items-center gap-3 px-4 py-3 border-b border-white/[0.04]">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-violet-500/15 border border-violet-500/20 flex items-center justify-center shrink-0">
                                        <x-lucide-notebook-pen class="w-4 h-4 text-violet-400" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-semibold text-white truncate">{{ $subject->name }}
                                        </h3>
                                        <p class="text-[10px] text-slate-600 uppercase tracking-wider">Matière</p>
                                    </div>
                                    @if ($subject->code ?? null)
                                        <span
                                            class="shrink-0 px-2 py-0.5 rounded-md bg-white/5 border border-white/5 text-[10px] font-mono text-slate-400 uppercase">
                                            {{ $subject->code }}
                                        </span>
                                    @endif
                                </div>

                                <div class="grid sm:grid-cols-2 gap-px bg-white/[0.03]">
                                    <div class="bg-[#070b14] px-4 py-3.5">
                                        <p
                                            class="text-[10px] uppercase tracking-wider text-slate-600 font-semibold mb-2">
                                            AE Principal
                                        </p>
                                        @if ($principal)
                                            <div class="flex items-center gap-2.5">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-[10px] font-bold text-white shrink-0">
                                                    {{ strtoupper(str()->substr($principal->getFullName(), 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-slate-200 truncate">
                                                        {{ $principal->getFullName() }}
                                                    </p>
                                                    @if ($principal->contacts ?? null)
                                                        <p class="text-[11px] text-slate-500 truncate">
                                                            {{ $principal->contacts }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-600 italic">Non désigné</p>
                                        @endif
                                    </div>

                                    <div class="bg-[#070b14] px-4 py-3.5">
                                        <p
                                            class="text-[10px] uppercase tracking-wider text-slate-600 font-semibold mb-2">
                                            AE Adjoint
                                        </p>
                                        @if ($adjoint)
                                            <div class="flex items-center gap-2.5">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center text-[10px] font-bold text-white shrink-0">
                                                    {{ strtoupper(str()->substr($adjoint->getFullName(), 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-slate-200 truncate">
                                                        {{ $adjoint->getFullName() }}
                                                    </p>
                                                    @if ($adjoint->contacts ?? null)
                                                        <p class="text-[11px] text-slate-500 truncate">
                                                            {{ $adjoint->contacts }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-600 italic">Non désigné</p>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="py-14 text-center">
                                <div
                                    class="w-12 h-12 mx-auto rounded-xl bg-white/5 border border-white/5 flex items-center justify-center mb-3">
                                    <x-lucide-wrench class="w-5 h-5 text-slate-600" />
                                </div>
                                <p class="text-sm text-slate-600">Aucune matière avec animateurs</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if ($this->aes->hasPages())
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 sm:px-5 py-3.5 border-t border-white/[0.05]">
                            <p class="text-xs text-slate-600">
                                {{ $this->aes->firstItem() }}–{{ $this->aes->lastItem() }}
                                sur {{ $this->aes->total() }} matières
                            </p>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @if (!$this->aes->onFirstPage())
                                    <button wire:click="previousPage('aesPage')" wire:loading.attr="disabled"
                                        wire:target="previousPage('aesPage')"
                                        class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                        ← Préc.
                                    </button>
                                @endif
                                @foreach ($this->aes->getUrlRange(1, $this->aes->lastPage()) as $page => $url)
                                    <button wire:click="gotoPage({{ $page }}, 'aesPage')"
                                        wire:target="gotoPage({{ $page }}, 'aesPage')"
                                        class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                {{ $page === $this->aes->currentPage()
                                    ? 'bg-violet-600 text-white shadow-lg shadow-violet-900/30'
                                    : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400' }}">
                                        {{ $page }}
                                    </button>
                                @endforeach
                                @if ($this->aes->hasMorePages())
                                    <button wire:click="nextPage('aesPage')" wire:loading.attr="disabled"
                                        wire:target="nextPage('aesPage')"
                                        class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                        Suiv. →
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </section>

        {{-- PP --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-4 pb-12">
            <div wire:loading.class='opacity-20'
                wire:target="nextPage('principalsPage'), previousPage('principalsPage'), gotoPage"
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10 font-mono">
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                    <h3 class="text-sm font-semibold text-white">Professeurs principaux (PP)</h3>
                </div>

                <div class="space-y-2 p-2 sm:p-2">
                    @forelse ($this->principals as $classe)
                        @php
                            $pp = $classe->principal;
                            $initials = collect(explode(' ', $pp->getFullName()))
                                ->filter()
                                ->take(2)
                                ->map(fn($w) => strtoupper(str()->substr($w, 0, 1)))
                                ->implode('');
                        @endphp

                        <div wire:key="pp-{{ $classe->id }}"
                            class="group flex items-center gap-3.5 rounded-xl bg-[#070b14] border border-white/[0.04] hover:border-indigo-500/25 hover:bg-[#0a0f1a] px-3.5 py-2 transition-all duration-200">

                            <div class="relative shrink-0">
                                <div
                                    class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-emerald-500 flex items-center justify-center text-xs font-bold text-white shadow-lg shadow-indigo-900/20">
                                    {{ $initials }}
                                </div>
                                <span
                                    class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-[#070b14]"></span>
                            </div>

                            <a wire:navigate
                                href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $pp->uuid]) }}"
                                class="flex-1 min-w-0 group">
                                <p
                                    class="text-sm font-semibold text-slate-100 truncate transition-colors group-hover:text-sky-500">
                                    {{ $pp->getFullName() }}
                                </p>
                                <div
                                    class="mt-0.5 flex flex-wrap items-center gap-x-2.5 gap-y-0.5 text-[11px] text-slate-500 group-hover:text-amber-500">
                                    @if ($pp->contacts)
                                        <span class="inline-flex items-center gap-1 truncate">
                                            <x-lucide-phone class="w-3 h-3 shrink-0 opacity-60" />
                                            {{ $pp->contacts }}
                                        </span>
                                    @endif
                                    @if ($pp->email)
                                        <span class="inline-flex items-center gap-1 truncate max-w-[160px]">
                                            <x-lucide-mail class="w-3 h-3 shrink-0 opacity-60" />
                                            {{ $pp->email }}
                                        </span>
                                    @endif
                                </div>
                            </a>

                            <a wire:navigate
                                href="{{ route('tenant.classe.profil', ['classe_slug' => $classe->slug]) }}"
                                class="shrink-0 text-right group">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-500/10 border border-amber-500/20 text-[11px] font-mono font-semibold text-amber-400 group-hover:bg-sky-500/10 group-hover:border-sky-500/20 group-hover:text-sky-400">
                                    {{ $classe->code }}
                                </span>
                                <p class="mt-1 text-[10px] text-slate-600">Prof. principal</p>
                            </a>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <div
                                class="w-12 h-12 mx-auto rounded-xl bg-white/5 border border-white/5 flex items-center justify-center mb-3">
                                <x-lucide-user-cog class="w-5 h-5 text-slate-600" />
                            </div>
                            <p class="text-sm text-slate-600">Aucun professeur principal</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($this->principals->hasPages())
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 sm:px-5 py-3.5 border-t border-white/[0.05]">
                        <p class="text-xs text-slate-600">
                            {{ $this->principals->firstItem() }}–{{ $this->principals->lastItem() }}
                            sur {{ $this->principals->total() }} classes
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$this->principals->onFirstPage())
                                <button wire:click="previousPage('principalsPage')" wire:loading.attr="disabled"
                                    wire:target="previousPage('principalsPage')"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                    ← Préc.
                                </button>
                            @endif
                            @foreach ($this->principals->getUrlRange(1, $this->principals->lastPage()) as $page => $url)
                                <button wire:click="gotoPage({{ $page }}, 'principalsPage')"
                                    wire:target="gotoPage({{ $page }}, 'principalsPage')"
                                    class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                {{ $page === $this->principals->currentPage()
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/30'
                                    : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                            @if ($this->principals->hasMorePages())
                                <button wire:click="nextPage('principalsPage')" wire:loading.attr="disabled"
                                    wire:target="nextPage('principalsPage')"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                    Suiv. →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </section>

    </div>
</div>

