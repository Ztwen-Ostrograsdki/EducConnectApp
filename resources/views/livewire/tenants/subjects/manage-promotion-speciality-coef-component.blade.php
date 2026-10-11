<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 space-y-8">

        {{-- ========== HEADER ========== --}}
        <div class="flex items-start gap-4">
            <a href="{{ route('tenant.subjects.portal') }}" wire:navigate
                class="mt-1 inline-flex items-center justify-center w-9 h-9 rounded-xl
                      border border-white/[0.08] text-slate-400
                      hover:text-white hover:bg-white/[0.06] transition-all shrink-0">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div class="min-w-0">
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-400/80 mb-1">
                    Référentiel pédagogique
                </p>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Gestion des coefficients
                    @if ($subject_slug)
                        <span class="text-amber-400 font-mono uppercase text-lg">· {{ $subject_slug }}</span>
                    @endif
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Coefficients par promotion, filière et série
                </p>
            </div>
        </div>

        {{-- ========== ERREUR ========== --}}
        @if ($error)
            <div
                class="rounded-xl border border-rose-500/25 bg-rose-500/10 px-4 py-3
                        flex items-center gap-2.5 text-sm text-rose-300 animate-pulse">
                <x-lucide-alert-circle class="w-4 h-4 shrink-0" />
                {{ $error }}
            </div>
        @endif

        {{-- ========== MATIÈRE & COEF ========== --}}
        <section class="space-y-4">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Matière & coefficient
                </h2>
            </div>

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                            <x-lucide-book-open class="w-3.5 h-3.5 text-indigo-400" />
                            Matière <span class="text-rose-400">*</span>
                        </label>
                        <select @disabled($subject_slug || $coef_relation || $is_ae) wire:model.live="subject_id"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                       px-3 text-sm text-white disabled:opacity-40
                                       focus:border-indigo-500/50 focus:outline-none transition-all">
                            <option value="">— Aucune —</option>
                            @foreach ($this->subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }}{{ $subject->code ? ' (' . $subject->code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                            <x-lucide-sigma class="w-3.5 h-3.5 text-amber-400" />
                            Coefficient <span class="text-rose-400">*</span>
                        </label>
                        <input type="number" wire:model.live="value" min="1"
                            class="w-full sm:w-32 h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      px-3 text-sm text-white font-mono
                                      focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20
                                      outline-none transition-all" />
                        @error('value')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- ========== RATTACHEMENT ========== --}}
        <section class="space-y-4">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-violet-400"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Rattachement
                </h2>
            </div>

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-4">
                <div>
                    <label
                        class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                        <x-lucide-graduation-cap class="w-3.5 h-3.5 text-violet-400" />
                        Promotion <span class="text-rose-400">*</span>
                    </label>
                    <select @disabled($coef_relation || $is_pp) wire:model.live="promotion"
                        class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                   px-3 text-sm text-white disabled:opacity-40
                                   focus:border-indigo-500/50 focus:outline-none transition-all">
                        <option value="">— Aucune —</option>
                        @foreach ($this->promotionInGroups as $promo)
                            <option value="{{ $promo }}">{{ $promo }}</option>
                        @endforeach
                    </select>
                    @error('promotion')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if (!$serial_id)
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-git-branch class="w-3.5 h-3.5 text-sky-400" />
                                Filière
                                <span class="text-slate-600 normal-case font-normal">(optionnel)</span>
                            </label>
                            <select @disabled($coef_relation || $is_pp || $is_ca) wire:model.live="filiar_id"
                                class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                           px-3 text-sm text-white disabled:opacity-40
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
                    @endif

                    @if (!$filiar_id)
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-list-tree class="w-3.5 h-3.5 text-cyan-400" />
                                Série
                                <span class="text-slate-600 normal-case font-normal">(optionnel)</span>
                            </label>
                            <select @disabled($coef_relation || $is_pp) wire:model.live="serial_id"
                                class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                           px-3 text-sm text-white disabled:opacity-40
                                           focus:border-indigo-500/50 focus:outline-none transition-all">
                                <option value="">— Aucune —</option>
                                @foreach ($this->serials as $serial)
                                    <option value="{{ $serial->id }}">
                                        Série {{ $serial->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('serial_id')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- ========== FOOTER ========== --}}
        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('tenant.subjects.portal') }}" wire:navigate
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
                    Enregistrer
                </span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    Enregistrement…
                </span>
            </button>
        </div>

    </div>
</div>
