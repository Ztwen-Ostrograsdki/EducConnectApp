<div class="w-full overflow-x-hidden bg-slate-950 min-h-screen">

    <div class="mx-auto w-full max-w-[1900px] px-4 sm:px-6 lg:px-8 py-6">

        {{-- ===================== TOP BAR ===================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="h-10 w-1.5 bg-amber-400 rounded-full"></div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-medium">Série</p>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        {{ $serial->name }}
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-xs font-bold uppercase tracking-wide
                    {{ $serial->is_active ? 'bg-emerald-500 text-emerald-950' : 'bg-red-500 text-red-950' }}">
                    {{ $serial->is_active ? 'Active' : 'Inactive' }}
                </span>

                @if ($serial->deleted_at)
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-rose-500/20 text-rose-300 text-xs font-bold uppercase tracking-wide border border-rose-500/40">
                        Corbeille
                    </span>
                @endif
            </div>
        </div>

        {{-- ===================== HERO STRIP ===================== --}}
        <section class="mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 border border-slate-800 rounded-none overflow-hidden">

                {{-- Code block --}}
                <div class="lg:col-span-3 bg-amber-400 text-slate-950 flex items-center justify-center p-8 lg:p-10">
                    <div class="text-center">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] opacity-70 mb-2">Code</p>
                        <p class="text-3xl sm:text-4xl font-black font-mono leading-none uppercase">
                            {{ str()->replace('-', ' ', $serial->code) }}
                        </p>
                    </div>
                </div>

                {{-- Infos --}}
                <div
                    class="lg:col-span-6 bg-slate-900 p-6 sm:p-8 flex flex-col justify-center border-t lg:border-t-0 lg:border-l border-slate-800">
                    <h2 class="text-xl sm:text-2xl font-bold text-white mb-2">{{ $serial->name }}</h2>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-lg">
                        Tableau global des statistiques et performances de la série {{ $serial->name }}.
                    </p>
                </div>

                {{-- Stats rapides --}}
                <div
                    class="lg:col-span-3 bg-slate-900 border-t lg:border-t-0 lg:border-l border-slate-800 grid grid-cols-2 lg:grid-cols-1 divide-x lg:divide-x-0 lg:divide-y divide-slate-800">
                    <div class="p-5 flex flex-col justify-center">
                        <p class="text-3xl font-black text-white">{{ __zero($details['teachers_count']) }}</p>
                        <p class="text-xs uppercase tracking-wider text-slate-500 mt-1">Enseignants</p>
                    </div>
                    <div class="p-5 flex flex-col justify-center">
                        <p class="text-3xl font-black text-white">{{ __zero($details['classes_count']) }}</p>
                        <p class="text-xs uppercase tracking-wider text-slate-500 mt-1">Classes</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== ACTIONS ===================== --}}
        <section class="mb-10">
            <div class="flex flex-wrap gap-2">
                <a wire:navigate href="{{ route('tenant.classes.create') }}"
                    class="h-10 px-4 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold transition-colors">
                    <x-lucide-plus class="w-4 h-4" />
                    Créer une classe
                </a>
                <a wire:navigate href="{{ route('tenant.serial.students', ['serial_slug' => $serial->slug]) }}"
                    class="h-10 px-4 inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-medium border border-slate-700 transition-colors">
                    Apprenants
                </a>
                <a wire:navigate href="{{ route('tenant.serial.teachers', ['serial_slug' => $serial->slug]) }}"
                    class="h-10 px-4 inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-medium border border-slate-700 transition-colors">
                    Enseignants
                </a>
                <button
                    class="h-10 px-4 inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold transition-colors">
                    <x-lucide-file-down class="w-4 h-4" />
                    Export PDF
                </button>
                <a wire:navigate href="{{ route('tenant.serial.edit', ['serial_slug' => $serial->slug]) }}"
                    class="h-10 px-4 inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-medium border border-slate-700 transition-colors">
                    Éditer
                </a>
                <button type="button"
                    title="{{ $serial->deleted_at ? 'Restaurer cette série' : 'Mettre en corbeille' }}"
                    wire:click="{{ $serial->deleted_at ? 'restoreSerial(' . $serial->id . ')' : 'deleteSerial(' . $serial->id . ')' }}"
                    wire:loading.attr="disabled" wire:target="deleteSerial, restoreSerial"
                    class="h-10 px-4 inline-flex items-center gap-2 text-sm font-semibold transition-colors disabled:opacity-50
                               {{ $serial->deleted_at
                                   ? 'bg-emerald-700 hover:bg-emerald-600 text-white'
                                   : 'bg-rose-700 hover:bg-rose-600 text-white' }}">
                    <span wire:loading.remove wire:target="deleteSerial, restoreSerial"
                        class="inline-flex items-center gap-2">
                        @if ($serial->deleted_at)
                            <x-lucide-refresh-ccw class="w-4 h-4" />
                            Restaurer
                        @else
                            <x-lucide-trash class="w-4 h-4" />
                            Corbeille
                        @endif
                    </span>
                    <span wire:loading wire:target="deleteSerial, restoreSerial" class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </section>

        {{-- ===================== PERFORMANCE GRID ===================== --}}
        <section class="mb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-slate-800 border border-slate-800">

                {{-- Best --}}
                <div class="bg-slate-900 p-6 sm:p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400 mb-1">Top</p>
                            <h2 class="text-lg font-bold text-white">Meilleure performance</h2>
                        </div>
                        <span class="text-3xl">🏆</span>
                    </div>

                    <div class="border-l-4 border-emerald-400 pl-5">
                        <h3 class="text-xl font-bold text-white">KOUASSI Sarah</h3>
                        <p class="text-sm text-slate-400 mt-1">Terminale F4-1</p>
                        <div class="mt-4 flex flex-wrap gap-3 text-sm">
                            <span class="font-mono font-bold text-emerald-400">19.75</span>
                            <span class="text-slate-600">•</span>
                            <span class="text-slate-400">Promotion Terminale</span>
                        </div>
                    </div>
                </div>

                {{-- Worst --}}
                <div class="bg-slate-900 p-6 sm:p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-rose-400 mb-1">Alert</p>
                            <h2 class="text-lg font-bold text-white">Plus faible performance</h2>
                        </div>
                        <span class="text-3xl">⚠️</span>
                    </div>

                    <div class="border-l-4 border-rose-400 pl-5">
                        <h3 class="text-xl font-bold text-white">HOUNKPE David</h3>
                        <p class="text-sm text-slate-400 mt-1">Tle F4-2</p>
                        <div class="mt-4 flex flex-wrap gap-3 text-sm">
                            <span class="font-mono font-bold text-rose-400">02.15</span>
                            <span class="text-slate-600">•</span>
                            <span class="text-slate-400">Promotion Terminale F4</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== BEST BOY / GIRL ===================== --}}
        <section class="mb-10">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- Garçon --}}
                <div class="bg-slate-900 border border-slate-800">
                    <div class="bg-sky-500 px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-950 opacity-80">Masculin</p>
                            <h2 class="text-lg font-black text-sky-950">Meilleur garçon</h2>
                        </div>
                        <span class="text-2xl">🏅</span>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row gap-5 items-start">
                            <div class="w-24 h-24 bg-slate-800 border-2 border-slate-700 shrink-0"></div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="text-xl font-bold text-white">HOUNKPE David</h3>
                                    <span
                                        class="px-2 py-0.5 bg-sky-500/20 text-sky-300 text-[10px] font-bold uppercase tracking-wider">#1</span>
                                </div>
                                <p class="text-sm text-slate-400 mb-4">Terminale F4-1 — Promotion Terminale</p>

                                <div class="grid grid-cols-3 gap-2 mb-5 text-center">
                                    <div class="bg-slate-950 border border-slate-800 py-2">
                                        <p class="text-lg font-black text-sky-400">18.92</p>
                                        <p class="text-[10px] uppercase text-slate-500">Moy.</p>
                                    </div>
                                    <div class="bg-slate-950 border border-slate-800 py-2">
                                        <p class="text-lg font-black text-white">4</p>
                                        <p class="text-[10px] uppercase text-slate-500">Coef</p>
                                    </div>
                                    <div class="bg-slate-950 border border-slate-800 py-2">
                                        <p class="text-sm font-bold text-white truncate px-1">AHOLOU</p>
                                        <p class="text-[10px] uppercase text-slate-500">Prof</p>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button
                                        class="flex-1 h-9 bg-sky-600 hover:bg-sky-500 text-white text-sm font-semibold transition-colors">
                                        Profil
                                    </button>
                                    <button
                                        class="flex-1 h-9 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-sm font-medium transition-colors">
                                        Notes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fille --}}
                <div class="bg-slate-900 border border-slate-800">
                    <div class="bg-pink-500 px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-pink-950 opacity-80">Féminin
                            </p>
                            <h2 class="text-lg font-black text-pink-950">Meilleure fille</h2>
                        </div>
                        <span class="text-2xl">👑</span>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row gap-5 items-start">
                            <div class="w-24 h-24 bg-slate-800 border-2 border-slate-700 shrink-0"></div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="text-xl font-bold text-white">KOUASSI Sarah</h3>
                                    <span
                                        class="px-2 py-0.5 bg-pink-500/20 text-pink-300 text-[10px] font-bold uppercase tracking-wider">#1</span>
                                </div>
                                <p class="text-sm text-slate-400 mb-4">Terminale F4-2 — Promotion Terminale</p>

                                <div class="grid grid-cols-3 gap-2 mb-5 text-center">
                                    <div class="bg-slate-950 border border-slate-800 py-2">
                                        <p class="text-lg font-black text-pink-400">19.41</p>
                                        <p class="text-[10px] uppercase text-slate-500">Moy.</p>
                                    </div>
                                    <div class="bg-slate-950 border border-slate-800 py-2">
                                        <p class="text-lg font-black text-white">4</p>
                                        <p class="text-[10px] uppercase text-slate-500">Coef</p>
                                    </div>
                                    <div class="bg-slate-950 border border-slate-800 py-2">
                                        <p class="text-sm font-bold text-white truncate px-1">ADJOVI</p>
                                        <p class="text-[10px] uppercase text-slate-500">Prof</p>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button
                                        class="flex-1 h-9 bg-pink-600 hover:bg-pink-500 text-white text-sm font-semibold transition-colors">
                                        Profil
                                    </button>
                                    <button
                                        class="flex-1 h-9 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-sm font-medium transition-colors">
                                        Notes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== ÉLÈVES EN DIFFICULTÉ ===================== --}}
        <section class="mb-12">
            <div class="border border-rose-500/40">
                <div class="bg-rose-500/10 border-b border-rose-500/30 px-5 py-4 flex items-center gap-3">
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-400"></span>
                    </span>
                    <h2 class="text-sm font-black uppercase tracking-[0.15em] text-rose-400">Élèves en difficulté</h2>
                </div>

                <div class="divide-y divide-slate-800">
                    @foreach (range(1, 5) as $weak)
                        <div
                            class="px-5 py-4 flex items-center justify-between gap-4 hover:bg-slate-900/80 transition-colors">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-white truncate">KOFFI Junior</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Terminale F2-2</p>
                            </div>
                            <span class="font-mono font-black text-rose-400 text-lg shrink-0">08.42</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
</div>
