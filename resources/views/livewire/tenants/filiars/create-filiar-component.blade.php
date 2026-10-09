<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-8">

        {{-- ========== HEADER ========== --}}
        <div class="flex items-start gap-4">
            <a href="{{ route('tenant.filiars.portal') }}" wire:navigate
                class="mt-1 inline-flex items-center justify-center w-9 h-9 rounded-xl
                      border border-white/[0.08] text-slate-400
                      hover:text-white hover:bg-white/[0.06] transition-all shrink-0">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400/80 mb-1">
                    Gestion académique
                </p>
                <h1 class="text-2xl font-bold text-white tracking-tight">
                    Nouvelle filière
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Ajouter une filière à l’établissement
                </p>
            </div>
        </div>

        {{-- ========== IDENTITÉ ========== --}}
        <section class="space-y-4">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Identité
                </h2>
            </div>

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-4">
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-git-branch class="w-3.5 h-3.5 text-sky-400" />
                        Nom <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" wire:model.live="name" placeholder="ex: Informatique, BTP"
                        class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  px-3 text-sm text-white placeholder:text-slate-600
                                  focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                  outline-none transition-all" />
                    @error('name')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                    @if ($previewSlug)
                        <p class="mt-1.5 text-[11px] text-slate-600">
                            Slug · <span class="font-mono text-slate-400">{{ $previewSlug }}</span>
                        </p>
                    @endif
                </div>

                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-hash class="w-3.5 h-3.5 text-orange-400" />
                        Code
                        <span class="text-slate-600 normal-case font-normal">(optionnel)</span>
                    </label>
                    <input type="text" wire:model="code" placeholder="ex: INFO, BTP"
                        class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  px-3 text-sm text-white placeholder:text-slate-600
                                  focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                  outline-none transition-all" />
                    @error('code')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-align-left class="w-3.5 h-3.5 text-violet-400" />
                        Description
                        <span class="text-slate-600 normal-case font-normal">(optionnel)</span>
                    </label>
                    <textarea wire:model="description" rows="3" placeholder="Description de la filière…"
                        class="w-full rounded-xl border border-white/[0.08] bg-[#070a12]
                                     px-3 py-2.5 text-sm text-white placeholder:text-slate-600
                                     focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                     outline-none transition-all resize-none"></textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        {{-- ========== OPTIONS ========== --}}
        <section class="space-y-4">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Options
                </h2>
            </div>

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-4">
                <label class="flex items-center justify-between gap-4 cursor-pointer select-none">
                    <span class="inline-flex items-center gap-2.5 text-sm text-slate-300">
                        <span
                            class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20
                                     flex items-center justify-center shrink-0">
                            <x-lucide-power class="w-4 h-4 text-indigo-400" />
                        </span>
                        Filière active
                    </span>
                    <div class="relative shrink-0">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                        <div class="h-6 w-11 rounded-full bg-slate-700 peer-checked:bg-indigo-500 transition-colors">
                        </div>
                        <div
                            class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow
                                    transition-transform peer-checked:translate-x-5">
                        </div>
                    </div>
                </label>

                <div class="h-px bg-white/[0.04]"></div>

                <label class="flex items-center justify-between gap-4 cursor-pointer select-none">
                    <span class="inline-flex items-center gap-2.5 text-sm text-slate-300">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20
                                     flex items-center justify-center shrink-0">
                            <x-lucide-briefcase class="w-4 h-4 text-emerald-400" />
                        </span>
                        Nouveau métier
                    </span>
                    <div class="relative shrink-0">
                        <input type="checkbox" wire:model="is_new_system" class="sr-only peer" />
                        <div class="h-6 w-11 rounded-full bg-slate-700 peer-checked:bg-emerald-500 transition-colors">
                        </div>
                        <div
                            class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow
                                    transition-transform peer-checked:translate-x-5">
                        </div>
                    </div>
                </label>
            </div>
        </section>

        {{-- ========== FOOTER ========== --}}
        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('tenant.filiars.portal') }}" wire:navigate
                class="inline-flex items-center h-10 px-4 rounded-xl text-sm font-medium
                      border border-white/[0.08] text-slate-400
                      hover:bg-white/[0.06] hover:text-white transition-all">
                Annuler
            </a>
            <button wire:click="save" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 h-10 px-5 rounded-xl text-sm font-medium
                           bg-indigo-500 hover:bg-indigo-400 text-white
                           shadow-lg shadow-indigo-500/20
                           transition-all disabled:opacity-50">
                <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                    <x-lucide-check class="w-4 h-4" />
                    Créer la filière
                </span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    Enregistrement…
                </span>
            </button>
        </div>

    </div>
</div>

