<div class="w-full overflow-x-hidden">
    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8 mb-28">

        {{-- ===================== BREADCRUMB / STATUS ===================== --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-indigo-500/10 border border-indigo-500/20">
                <span class="text-xs uppercase tracking-wider text-slate-500 font-medium">Promotion</span>
                <span class="font-mono text-amber-400 font-semibold tracking-wider">
                    {{ $promotion->name }} {{ $promotion->specialityModel()?->code }}
                </span>
            </div>

            <span
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                {{ $promotion->is_active
                    ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
                    : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                <span
                    class="w-1.5 h-1.5 rounded-full {{ $promotion->is_active ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                {{ $promotion->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- ===================== HEADER ===================== --}}
        <section class="mb-8">
            <div
                class="relative overflow-hidden rounded-[2rem] border border-white/5 bg-slate-900/80 backdrop-blur-xl shadow-2xl shadow-indigo-950/30">
                {{-- Glow background --}}
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-transparent to-violet-500/5">
                </div>
                <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-indigo-600/20 blur-3xl"></div>
                <div class="absolute top-0 right-1/4 w-64 h-64 rounded-full bg-violet-500/15 blur-3xl"></div>

                <div class="relative p-6 sm:p-8 lg:p-10">
                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-8">

                        {{-- LEFT : Icon + Infos --}}
                        <div class="flex flex-col sm:flex-row gap-6 min-w-0">
                            {{-- Code badge --}}
                            <div class="flex justify-center sm:block shrink-0">
                                <div class="relative group">
                                    <div
                                        class="absolute -inset-1 rounded-[1.75rem] bg-gradient-to-br from-indigo-500 via-violet-500 to-sky-400 opacity-40 blur-md group-hover:opacity-70 transition-opacity">
                                    </div>
                                    <div
                                        class="relative w-32 h-32 sm:w-36 sm:h-36 rounded-[1.5rem] bg-slate-950/80 border border-indigo-500/30 flex items-center justify-center">
                                        <span
                                            class="text-2xl sm:text-3xl font-bold font-mono text-indigo-300 tracking-wider uppercase">
                                            {{ $promotion->code ?? cutter($promotion->name, 1) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Titre + description --}}
                            <div class="min-w-0 text-center sm:text-left">
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white">
                                    {{ $promotion->name }}
                                    <span class="text-indigo-300">{{ $promotion->specialityModel()?->code }}</span>
                                </h1>

                                <p class="mt-3 text-slate-400 max-w-xl leading-relaxed">
                                    Tableau global des statistiques et performances de la promotion
                                    <span class="text-slate-300">{{ $promotion->name }}
                                        {{ $promotion->specialityModel()?->code }}</span>.
                                </p>

                                <div class="mt-5 flex flex-wrap justify-center sm:justify-start gap-3">
                                    <a href="{{ $promotion->toSpecialityProfilRoute() }}"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-medium
                                              bg-indigo-500/15 text-indigo-300 border border-indigo-500/25
                                              hover:bg-indigo-500/25 hover:text-indigo-200 hover:border-indigo-400/40
                                              transition-all active:scale-[0.97]">
                                        <x-lucide-graduation-cap class="w-4 h-4 opacity-70" />
                                        {{ $promotion->specialityModel()?->name }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT : Actions rapides (desktop) --}}
                        <div class="hidden xl:flex flex-col gap-2.5 shrink-0 w-56">
                            <a wire:navigate href="{{ route('tenant.classes.create') }}"
                                class="flex items-center justify-center gap-2 h-11 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-all active:scale-[0.97]">
                                <x-lucide-plus class="w-4 h-4" />
                                Ajouter une classe
                            </a>
                            <a wire:navigate
                                href="{{ route('tenant.promotion.edit', ['promotion_slug' => $promotion->slug]) }}"
                                class="flex items-center justify-center gap-2 h-11 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                                <x-lucide-pencil class="w-4 h-4 opacity-70" />
                                Éditer la promotion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== ACTIONS (mobile + tablet) ===================== --}}
        <section class="mb-8 xl:hidden">
            <div class="flex flex-wrap gap-2.5">
                <a wire:navigate href="{{ route('tenant.classes.create') }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-all active:scale-[0.97]">
                    <x-lucide-plus class="w-4 h-4" />
                    Ajouter une classe
                </a>
                <a wire:navigate href="{{ route('tenant.promotion.create') }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                    Nouvelle promotion
                </a>
                <a wire:navigate
                    href="{{ route('tenant.promotion.teachers', ['promotion_slug' => $promotion->slug]) }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                    Enseignants
                </a>
                <a wire:navigate
                    href="{{ route('tenant.promotion.students', ['promotion_slug' => $promotion->slug]) }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                    Apprenants
                </a>
                <a wire:navigate href="{{ route('tenant.promotion.edit', ['promotion_slug' => $promotion->slug]) }}"
                    class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                    Éditer
                </a>
            </div>
        </section>

        {{-- ===================== ACTIONS (desktop étendu) ===================== --}}
        <section class="mb-8 hidden xl:flex justify-end">
            <div class="flex flex-wrap gap-2.5">
                <a wire:navigate href="{{ route('tenant.promotion.create') }}"
                    class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                    <x-lucide-plus class="w-4 h-4 opacity-70" />
                    Nouvelle promotion
                </a>
                <a wire:navigate
                    href="{{ route('tenant.promotion.teachers', ['promotion_slug' => $promotion->slug]) }}"
                    class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                    <x-lucide-users class="w-4 h-4 opacity-70" />
                    Liste des enseignants
                </a>
                <a wire:navigate
                    href="{{ route('tenant.promotion.students', ['promotion_slug' => $promotion->slug]) }}"
                    class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                    <x-lucide-graduation-cap class="w-4 h-4 opacity-70" />
                    Liste des apprenants
                </a>
            </div>
        </section>

        {{-- ===================== BEST / WORST ===================== --}}
        <section class="mb-8">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- MEILLEURE PERFORMANCE --}}
                <div
                    class="rounded-[1.75rem] border border-emerald-500/20 bg-slate-900/70 backdrop-blur-xl p-6 shadow-xl shadow-emerald-950/10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 text-2xl">
                            🏆
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Meilleure performance</h2>
                            <p class="text-sm text-slate-400">Plus forte moyenne enregistrée</p>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl bg-slate-950/60 border border-white/5 p-5 hover:border-emerald-500/30 transition-colors">
                        <h3 class="text-lg font-semibold text-white">KOUASSI Sarah</h3>
                        <p class="mt-1.5 text-sm text-slate-400">Classe : Terminale F4-1</p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium">
                                Moyenne : 19.75
                            </span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-medium">
                                Promotion : Terminale
                            </span>
                        </div>
                    </div>
                </div>

                {{-- PLUS FAIBLE PERFORMANCE --}}
                <div
                    class="rounded-[1.75rem] border border-rose-500/20 bg-slate-900/70 backdrop-blur-xl p-6 shadow-xl shadow-rose-950/10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="flex items-center justify-center w-14 h-14 rounded-2xl bg-rose-500/15 border border-rose-500/25 text-2xl">
                            ⚠️
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Plus faible performance</h2>
                            <p class="text-sm text-slate-400">Plus faible moyenne enregistrée</p>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl bg-slate-950/60 border border-white/5 p-5 hover:border-rose-500/30 transition-colors">
                        <h3 class="text-lg font-semibold text-white">HOUNKPE David</h3>
                        <p class="mt-1.5 text-sm text-slate-400">Classe : Tle F4-2</p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-medium">
                                Moyenne : 02.15
                            </span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-medium">
                                Promotion : Terminale F4
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== MEILLEUR GARÇON / MEILLEURE FILLE ===================== --}}
        <section class="mb-8">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- MEILLEUR GARÇON --}}
                <div
                    class="rounded-[1.75rem] border border-sky-500/20 bg-slate-900/70 backdrop-blur-xl overflow-hidden shadow-xl shadow-sky-950/10">
                    <div class="p-6 border-b border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="flex items-center justify-center w-14 h-14 rounded-2xl bg-sky-500/15 border border-sky-500/25 text-2xl">
                                🏅
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-white">Meilleur garçon</h2>
                                <p class="text-sm text-slate-400">Meilleure performance masculine</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                            {{-- Avatar placeholder --}}
                            <div class="flex justify-center lg:block shrink-0">
                                <div
                                    class="w-28 h-28 rounded-[1.5rem] bg-slate-800/80 border border-white/5 overflow-hidden">
                                    {{-- <img src="..." class="w-full h-full object-cover" /> --}}
                                </div>
                            </div>

                            <div class="flex-1 min-w-0 text-center lg:text-left">
                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5">
                                    <h3 class="text-xl sm:text-2xl font-bold text-white">HOUNKPE David</h3>
                                    <span
                                        class="px-3 py-1 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs font-medium">
                                        Rang #1 Garçon
                                    </span>
                                </div>

                                <p class="mt-2 text-sm text-slate-400">Terminale F4-1 — Promotion Terminale</p>

                                <div class="mt-4 flex flex-wrap justify-center lg:justify-start gap-2">
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Moyenne : <span class="font-semibold text-sky-300">18.92</span>
                                    </div>
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Coef : 4
                                    </div>
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Prof : M. AHOLOU
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap justify-center lg:justify-start gap-2.5">
                                    <button
                                        class="h-10 px-5 rounded-xl bg-sky-600 hover:bg-sky-500 text-sm font-medium transition-all active:scale-[0.97]">
                                        Voir profil
                                    </button>
                                    <button
                                        class="h-10 px-5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                                        Historique notes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MEILLEURE FILLE --}}
                <div
                    class="rounded-[1.75rem] border border-pink-500/20 bg-slate-900/70 backdrop-blur-xl overflow-hidden shadow-xl shadow-pink-950/10">
                    <div class="p-6 border-b border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="flex items-center justify-center w-14 h-14 rounded-2xl bg-pink-500/15 border border-pink-500/25 text-2xl">
                                👑
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-white">Meilleure fille</h2>
                                <p class="text-sm text-slate-400">Meilleure performance féminine</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                            {{-- Avatar placeholder --}}
                            <div class="flex justify-center lg:block shrink-0">
                                <div
                                    class="w-28 h-28 rounded-[1.5rem] bg-slate-800/80 border border-white/5 overflow-hidden">
                                    {{-- <img src="..." class="w-full h-full object-cover" /> --}}
                                </div>
                            </div>

                            <div class="flex-1 min-w-0 text-center lg:text-left">
                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5">
                                    <h3 class="text-xl sm:text-2xl font-bold text-white">KOUASSI Sarah</h3>
                                    <span
                                        class="px-3 py-1 rounded-full bg-pink-500/10 border border-pink-500/20 text-pink-400 text-xs font-medium">
                                        Rang #1 Fille
                                    </span>
                                </div>

                                <p class="mt-2 text-sm text-slate-400">Terminale F4-2 — Promotion Terminale</p>

                                <div class="mt-4 flex flex-wrap justify-center lg:justify-start gap-2">
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Moyenne : <span class="font-semibold text-pink-300">19.41</span>
                                    </div>
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Coef : 4
                                    </div>
                                    <div
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-950/70 border border-white/5 text-sm">
                                        Prof : Mme ADJOVI
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap justify-center lg:justify-start gap-2.5">
                                    <button
                                        class="h-10 px-5 rounded-xl bg-pink-600 hover:bg-pink-500 text-sm font-medium transition-all active:scale-[0.97]">
                                        Voir profil
                                    </button>
                                    <button
                                        class="h-10 px-5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-medium transition-all active:scale-[0.97]">
                                        Historique notes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>
</div>
