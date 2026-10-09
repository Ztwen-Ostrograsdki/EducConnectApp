<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        {{-- ========== HEADER ========== --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('tenant.classes.portal') }}" wire:navigate
                class="inline-flex items-center justify-center w-9 h-9 rounded-xl
                      border border-white/[0.08] text-slate-400
                      hover:text-white hover:bg-white/[0.06] transition-all">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Édition de la classe
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Année scolaire
                    <span class="text-amber-400 font-mono font-semibold">{{ $school_year }}</span>
                </p>
            </div>
        </div>

        {{-- ========== FORMULAIRE ========== --}}
        <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 sm:p-6 space-y-6">

            {{-- Année + Effectif --}}
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                <div class="sm:col-span-3">
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-calendar class="w-3.5 h-3.5 text-indigo-400" />
                        Année scolaire <span class="text-rose-400">*</span>
                    </label>
                    <select wire:model.live="school_year_id"
                        class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                   px-3 text-sm text-white
                                   focus:border-indigo-500/50 focus:outline-none transition-all">
                        <option value="0" disabled>Sélectionner une année</option>
                        @foreach ($this->schoolYears as $year)
                            <option value="{{ $year->id }}">
                                {{ $year->slug }}{{ $year->is_active ? ' (active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_year_id')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-users class="w-3.5 h-3.5 text-emerald-400" />
                        Effectif max <span class="text-rose-400">*</span>
                    </label>
                    <input type="number" wire:model="effectif_max" min="1" max="200"
                        class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  px-3 text-sm text-white
                                  focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                  outline-none transition-all" />
                    @error('effectif_max')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Promotion + Filière / Série --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-layers class="w-3.5 h-3.5 text-violet-400" />
                        Promotion <span class="text-rose-400">*</span>
                    </label>
                    <select wire:model.live="promotion_id"
                        class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                   px-3 text-sm text-white
                                   focus:border-indigo-500/50 focus:outline-none transition-all">
                        <option value="0" disabled>Sélectionner</option>
                        @foreach ($this->promotions as $promotion)
                            <option value="{{ $promotion->id }}">
                                {{ $promotion->name }}{{ $promotion->code ? ' (' . $promotion->code . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('promotion_id')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div wire:loading wire:target="promotion_id"
                    class="sm:col-span-2 flex items-center gap-2 text-slate-500">
                    <x-lucide-loader-2 class="w-4 h-4 animate-spin text-indigo-400" />
                    <span class="text-sm">Chargement…</span>
                </div>

                @if ($promotion_id)
                    @if (tenancy()->tenant?->promotionCanHasFiliarOrSerial($promotion_id))
                        <div wire:loading.remove wire:target="promotion_id">
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-git-branch class="w-3.5 h-3.5 text-sky-400" />
                                Filière <span class="text-slate-600 normal-case">(optionnel)</span>
                            </label>
                            <select wire:model.live="filiar_id"
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                           px-3 text-sm text-white
                                           focus:border-indigo-500/50 focus:outline-none transition-all">
                                <option value="">— Aucune —</option>
                                @foreach ($this->filiars as $filiar)
                                    <option value="{{ $filiar->id }}">
                                        {{ $filiar->name }}{{ $filiar->code ? ' (' . $filiar->code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('filiar_id')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div wire:loading.remove wire:target="promotion_id">
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-list-tree class="w-3.5 h-3.5 text-cyan-400" />
                                Série <span class="text-slate-600 normal-case">(optionnel)</span>
                            </label>
                            <select wire:model.live="serial_id"
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                           px-3 text-sm text-white
                                           focus:border-indigo-500/50 focus:outline-none transition-all">
                                <option value="">— Aucune —</option>
                                @foreach ($this->serials as $serial)
                                    <option value="{{ $serial->id }}">
                                        {{ $serial->name }}{{ $serial->code ? ' (' . $serial->code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('serial_id')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                @endif
            </div>

            {{-- Nom + Code + Nouveau métier --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-school class="w-3.5 h-3.5 text-amber-400" />
                        Nom de la classe <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" wire:model="name" placeholder="ex: Terminale BTP 2"
                        class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  px-3 text-sm text-white placeholder:text-slate-600
                                  focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                  outline-none transition-all" />
                    @error('name')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-hash class="w-3.5 h-3.5 text-orange-400" />
                        Code <span class="text-slate-600 normal-case">(optionnel)</span>
                    </label>
                    <input type="text" wire:model="code" placeholder="ex: TLE-BTP-2"
                        class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  px-3 text-sm text-white placeholder:text-slate-600
                                  focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                  outline-none transition-all" />
                    @error('code')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="pb-1">
                    <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                        <div class="relative">
                            <input type="checkbox" wire:model="is_new_system" class="sr-only peer" />
                            <div
                                class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-emerald-500 transition-colors">
                            </div>
                            <div
                                class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow
                                        transition-transform peer-checked:translate-x-4">
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-sm text-slate-300">
                            <x-lucide-briefcase class="w-3.5 h-3.5 text-emerald-400" />
                            Nouveau métier
                        </span>
                    </label>
                    @error('is_new_system')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Localisation + toggles --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-map-pin class="w-3.5 h-3.5 text-rose-400" />
                        Localisation <span class="text-slate-600 normal-case">(optionnel)</span>
                    </label>
                    <input type="text" wire:model="localization" placeholder="ex: Bâtiment H salle 1"
                        class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  px-3 text-sm text-white placeholder:text-slate-600
                                  focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                  outline-none transition-all" />
                    @error('localization')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 pt-1 sm:pt-6">
                    <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                        <div class="relative">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                            <div
                                class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-indigo-500 transition-colors">
                            </div>
                            <div
                                class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow
                                        transition-transform peer-checked:translate-x-4">
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-sm text-slate-300">
                            <x-lucide-power class="w-3.5 h-3.5 text-indigo-400" />
                            Classe active
                        </span>
                    </label>
                    <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                        <div class="relative">
                            <input type="checkbox" wire:model="is_locked" class="sr-only peer" />
                            <div class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-amber-500 transition-colors">
                            </div>
                            <div
                                class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow
                                        transition-transform peer-checked:translate-x-4">
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-sm text-slate-300">
                            <x-lucide-lock class="w-3.5 h-3.5 text-amber-400" />
                            Verrouiller l’accès enseignants
                        </span>
                    </label>
                </div>
            </div>
        </div>

        {{-- ========== FOOTER ========== --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('tenant.classes.portal') }}" wire:navigate
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
                    Sauvegarder
                </span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    Sauvegarde…
                </span>
            </button>
        </div>

    </div>
</div>

