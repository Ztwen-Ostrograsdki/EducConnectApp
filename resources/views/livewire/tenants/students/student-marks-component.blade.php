<div class="w-full max-w-full overflow-x-hidden">
    <div wire:loading wire:target='period'
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
                    <span
                        class="block sm:inline text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-cyan-400">
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
                        <svg class="h-3.5 w-3.5 text-violet-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
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

    <section class="my-3">
        <div class="flex gap-2 items-center justify-end">

            <a wire:navigate href="{{ route('tenant.student.profil', ['student_uuid' => $this->student_uuid]) }}"
                class="p-3 rounded-2xl bg-green-500/20 col-span-2 text-green-400 hover:bg-green-500/30 transition-all text-sm inline-block text-center active:scale-95">

                Le profil

            </a>

            <button type="button" wire:click="markStudentAsLeaved({{ $this->student->id }})"
                wire:loading.attr="disabled" wire:target="markStudentAsLeaved({{ $this->student->id }})"
                class="rounded-2xl col-span-2 items-center gap-2 bg-orange-600/60 p-3 text-sm font-medium text-white transition hover:bg-orange-700 disabled:opacity-60 hover:text-black active:scale-95">
                <span wire:loading.remove wire:target="markStudentAsLeaved({{ $this->student->id }})"
                    class="flex justify-center items-center">
                    <span class="flex items-center gap-3">
                        <x-lucide-user-x class="w-4 h-4 " />
                        <span>Marquer comme abandon</span>
                    </span>
                </span>
                <span wire:loading wire:target="markStudentAsLeaved({{ $this->student->id }})"
                    class="flex items-center gap-2">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                            </path>
                        </svg>
                        Traitement en cours...
                    </span>
                </span>
            </button>

            @if ($this->classe)
                <a wire:navigate href="{{ route('tenant.classe.profil', ['classe_slug' => $this->classe->slug]) }}"
                    class="p-3 col-span-2 rounded-2xl bg-sky-500/20 text-sky-400 hover:bg-sky-500/60 transition-all text-sm inline-block text-center hover:text-black active:scale-95">

                    Voir sa classe actuelle
                </a>
            @endif
        </div>
    </section>

    <section class="mb-6">

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">

            <div class="flex flex-col justify-between xl:flex-row gap-4">

                <div class="flex justify-center flex-wrap gap-3 text-gray-950">

                    <button
                        class="px-3 py-2 rounded-2xl
                                    bg-red-500 hover:bg-red-600">

                        Verrouiller notes

                    </button>

                    <button
                        class="px-3 py-2 rounded-2xl
                                    bg-blue-500 hover:bg-blue-600">

                        Imprimer PDF

                    </button>

                    <button
                        class="px-3 py-2 rounded-2xl
                                    bg-emerald-500 hover:bg-emerald-600">

                        Emprimer Excel

                    </button>

                    <button
                        class="px-3 py-2 rounded-2xl
                                    bg-amber-500 hover:bg-amber-600">

                        Imprimer Excel et PDF

                    </button>

                </div>

            </div>

        </div>

    </section>

    @livewire('tenants.components.student-marks-lister-component', ['student' => $this->student])
</div>

