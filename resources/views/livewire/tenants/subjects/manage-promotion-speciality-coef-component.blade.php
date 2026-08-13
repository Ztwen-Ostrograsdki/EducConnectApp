<div class="mx-auto w-full space-y-6 py-5.5">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('tenant.subjects.portal') }}" wire:navigate
            class="rounded-xl border border-slate-700 p-2 text-slate-400 hover:text-white hover:bg-slate-800 transition">
            <x-lucide-arrow-left class="w-4 h-4" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Gestion des coéficients
                @if ($subject_slug)
                    de <span class="uppercase text-yellow-500">{{ $subject_slug }}</span>
                @endif
            </h1>
            <p class="text-sm text-indigo-500 mt-0.5 font-mono">Gérér les coéfiscients par promotion, filière et série
            </p>
        </div>
    </div>

    <div class="flex items-center justify-center flex-col my-3">
        @if ($error)
            <span class="w-full font-mono animate-pulse bg-red-400/35 text-red-300 p-3 text-center rounded-2xl">
                {{ $error }}
            </span>
        @endif
    </div>

    <div class="rounded-2xl border border-slate-700 bg-slate-900 p-6 space-y-5 font-mono">

        <div class="grid grid-cols-1 sm:grid-cols-2  gap-4 w-full">
            <div class="transition-all">
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    La matière 
                    <span
                        class="text-rose-400">*</span>
                </label>
                <select @disabled($subject_slug || $coef_relation || $is_ae) wire:model.live="subject_id"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 disabled:opacity-40 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
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
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Le coéficient <span
                        class="text-rose-400">*</span></label>
                <input type="number" wire:model.live="value" min="1"
                    class="w-52 rounded-xl border border-slate-700 bg-slate-800 disabled:opacity-40 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition" />
                @error('order')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2  gap-4 w-full">
            {{-- Name --}}
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">La promotion 
                    <span
                        class="text-rose-400">*</span>
                    </label>
                <select @disabled($coef_relation || $is_pp) wire:model.live="promotion"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 disabled:opacity-40 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                    <option value="">— Aucune —</option>
                    @foreach ($this->promotionInGroups as $promo)
                        <option value="{{ $promo }}">
                            {{ $promo }}
                        </option>
                    @endforeach
                </select>
                @error('promotion')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2  gap-4 w-full">
            @if (!$serial_id)
                <div class="transition-all">
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">
                        Filière <span class="text-slate-600">(optionnel)</span>
                    </label>
                    <select @disabled($coef_relation || $is_pp || $is_ca) wire:model.live="filiar_id"
                        class="w-full rounded-xl border border-slate-700 bg-slate-800 disabled:opacity-40 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
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
                <div class="transition-all">
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">
                        Série <span class="text-slate-600">(optionnel)</span>
                    </label>
                    <select @disabled($coef_relation || $is_pp) wire:model.live="serial_id"
                        class="w-full rounded-xl border border-slate-700 bg-slate-800 disabled:opacity-40 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                        <option value="">— Aucune —</option>
                        @foreach ($this->serials as $serial)
                            <option value="{{ $serial->id }}">
                                La série {{ $serial->name }}
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

    <div class="flex justify-end gap-3">
        <a wire:navigate href="{{ route('tenant.subjects.portal') }}"
            class="px-5 py-2.5 text-sm rounded-xl border border-slate-700 text-slate-400 hover:bg-slate-800 transition">
            Annuler
        </a>
        <button wire:click="save" wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium transition disabled:opacity-50">
            <span wire:loading.remove wire:target="save">
                <x-lucide-check class="w-4 h-4 inline -mt-0.5" /> Enregistrer
            </span>
            <span wire:loading wire:target="save">Enregistrement...</span>
        </button>
    </div>
</div>

