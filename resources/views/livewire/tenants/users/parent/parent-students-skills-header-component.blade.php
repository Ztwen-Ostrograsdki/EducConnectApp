<section class="mb-8 relative overflow-hidden rounded-3xl border border-white/5 bg-slate-950 p-5 sm:p-6">
    {{-- Soft glow décoratif --}}
    <div class="pointer-events-none absolute -top-24 -right-24 h-64 w-64 rounded-full bg-violet-600/10 blur-3xl">
    </div>
    <div class="pointer-events-none absolute -bottom-32 -left-20 h-72 w-72 rounded-full bg-cyan-500/5 blur-3xl">
    </div>

    {{-- Header --}}
    <div class="relative mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div class="min-w-0">
            <div class="inline-flex items-center gap-2 mb-2">
                <span class="h-1.5 w-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                <span class="text-xs font-medium uppercase tracking-wider text-violet-300/80">
                    Performance scolaire
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight">
                Détails des Notes
                <span class="block sm:inline text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-cyan-400">
                    {{ $this->student->getFullName() }}
                </span>
            </h1>

            <p class="mt-1.5 text-sm text-slate-400 max-w-xl">
                Notes, moyennes et statistiques pédagogiques de l’apprenant.
            </p>
        </div>

        {{-- Badge période (optionnel mais joli) --}}
        <div class="shrink-0">
            @if ($this->activeYear)
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3.5 py-1.5 text-xs text-slate-300 backdrop-blur-sm">
                    <svg class="h-3.5 w-3.5 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ $this->activeYear->periodLabel() }}</span>
                    @if ($this->period)
                        <span class="text-slate-500">·</span>
                        <span class="font-medium text-white">{{ $this->period }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Stats grid --}}
    <div class="relative grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Moyenne Générale --}}
        <div
            class="group relative overflow-hidden rounded-2xl border border-white/5 bg-slate-950 p-5 transition-all hover:border-violet-500/30 hover:shadow-[0_0_30px_-10px_rgba(139,92,246,0.25)]">
            <div
                class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>

            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Moyenne
                    </p>
                    <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-white tracking-tight">
                        <span>
                            {{ isset($this->termAverage['moyenne']) ? number_format($this->termAverage['moyenne'], 2) : '--' }}
                        </span>
                    </h2>
                    <p class="mt-1 text-xs text-emerald-400/90 flex items-center gap-1">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        +0.8 vs période préc.
                    </p>
                </div>
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-500/10 border border-violet-500/20 text-violet-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Taux de Réussite --}}
        <div
            class="group relative overflow-hidden rounded-2xl border border-white/5 bg-slate-950 p-5 transition-all hover:border-emerald-500/30 hover:shadow-[0_0_30px_-10px_rgba(16,185,129,0.2)]">
            <div
                class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>

            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Taux de Réussite
                    </p>
                    @php
                        $percentage = min(100, max(0, (float) ($this->termAverage['success_percentage'] ?? 0)));
                    @endphp
                    @if (isset($this->termAverage['success_percentage']) && $this->termAverage['success_percentage'] !== null)
                        <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-white tracking-tight">
                            <span>
                                {{ number_format($this->termAverage['success_percentage'], 2) }}
                            </span>
                            <span class="text-2xl text-slate-400">%</span>
                        </h2>

                        <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-cyan-400 transition-all duration-700 ease-out"
                                style="width: {{ $percentage }}%"></div>
                        </div>
                    @else
                        <span class="text-sm text-slate-800">Indisponible</span>
                    @endif
                </div>
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Classe --}}
        <div
            class="group relative overflow-hidden rounded-2xl border border-white/5 bg-slate-950 p-5 transition-all hover:border-cyan-500/30 hover:shadow-[0_0_30px_-10px_rgba(34,211,238,0.2)]">
            <div
                class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>

            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Classe
                    </p>
                    <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-white tracking-tight font-mono uppercase">
                        {{ $this->classe->code }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        {{ $this->classe->name ?? 'Classe actuelle' }}
                    </p>
                </div>
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Période --}}
        <div
            class="group relative overflow-hidden rounded-2xl border border-white/5 bg-slate-950 p-5 transition-all hover:border-amber-500/30 hover:shadow-[0_0_30px_-10px_rgba(245,158,11,0.15)]">
            <div
                class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>

            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        @if ($this->activeYear)
                            {{ $this->activeYear->periodLabel() }}
                        @else
                            Année scolaire
                        @endif
                    </p>
                    <h2 class="mt-2 text-xl sm:text-2xl font-bold text-white tracking-tight">
                        @if ($this->period)
                            {{ $this->period }}
                        @else
                            <span class="text-base text-slate-500 font-normal">Non sélectionnée</span>
                        @endif
                    </h2>
                    @if ($this->activeYear)
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $this->activeYear->slug ?? '' }}
                        </p>
                    @endif
                </div>
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

    </div>
</section>

