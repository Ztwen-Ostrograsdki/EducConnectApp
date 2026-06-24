<div class="mx-auto w-full space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('tenant.classes.portal') }}" wire:navigate
            class="rounded-xl border border-slate-700 p-2 text-slate-400 hover:text-white hover:bg-slate-800 transition">
            <x-lucide-arrow-left class="w-4 h-4" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Nouvelle classe</h1>
            <p class="text-sm text-slate-500 mt-0.5">Créer une classe pour une cette année scolaire
                <span class="text-orange-500 font-mono font-semibold">{{ $school_year }}</span>
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-700 bg-slate-900 p-6 space-y-5 font-mono text-slate-300">
        <div class="grid grid-cols-5 gap-4">
            <div class=" col-span-3">
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Année scolaire <span class="text-rose-400">*</span>
                </label>
                <select wire:model.live="school_year_id"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-slate-300 focus:border-indigo-500 focus:outline-none transition">
                    <option value="0" disabled>Sélectionner une année</option>
                    @foreach ($this->schoolYears as $year)
                        <option class="font-mono text-slate-300" value="{{ $year->id }}">
                            {{ $year->slug }}{{ $year->is_active ? ' (active)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('school_year_id')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Effectif maximal <span class="text-rose-400">*</span>
                </label>
                <input type="number" wire:model="effectif_max" min="1" max="200"
                    class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition w-full" />
                @error('effectif_max')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3  gap-4 w-full">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Promotion <span class="text-rose-400">*</span>
                </label>
                <select wire:model.live="promotion_id"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
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
            <div wire:loading wire:target='promotion_id'>
                <div class="py-3 mt-3 flex justify-center items-center gap-x-3 text-gray-600 transition-all">
                    <x-lucide-loader class="w-5 h-5 animate-spin" />
                    <h5>Chargement en cours ...</h5>
                </div>
            </div>
            @if ($promotion_id)

                <div wire:loading.remove wire:target='promotion_id' class="transition-all">
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">
                        Filière <span class="text-slate-600">(optionnel)</span>
                    </label>
                    <select wire:model.live="filiar_id"
                        class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
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

                <div wire:loading.remove wire:target='promotion_id' class="transition-all">
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">
                        Série <span class="text-slate-600">(optionnel)</span>
                    </label>
                    <select wire:model.live="serial_id"
                        class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
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
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Nom de la classe <span class="text-rose-400">*</span>
                </label>
                <input type="text" wire:model="name" placeholder="ex: Terminale BTP 2"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                @error('name')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Code <span class="text-slate-600">(optionnel)</span>
                </label>
                <input type="text" wire:model="code" placeholder="ex: TLE-BTP-2"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                @error('code')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Niveau <span class="text-rose-400">*</span>
                </label>
                <select wire:model="level"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                    <option value="primaire">Primaire</option>
                    <option value="secondaire">Secondaire</option>
                    <option value="superieur">Supérieur</option>
                </select>
                @error('level')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Toggles --}}
        <div class="flex flex-col gap-3 pt-1">
            <label class="flex items-center gap-3 cursor-pointer">
                <div class="relative">
                    <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                    <div class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-indigo-600 transition"></div>
                    <div
                        class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white transition peer-checked:translate-x-4">
                    </div>
                </div>
                <span class="text-sm text-slate-300">Classe active</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <div class="relative">
                    <input type="checkbox" wire:model="is_locked" class="sr-only peer" />
                    <div class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-amber-600 transition"></div>
                    <div
                        class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white transition peer-checked:translate-x-4">
                    </div>
                </div>
                <span class="text-sm text-slate-300">Verrouiller l'accès enseignants</span>
            </label>
        </div>

    </div>

    {{-- Footer --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('tenant.classes.portal') }}" wire:navigate
            class="px-5 py-2.5 text-sm rounded-xl border border-slate-700 text-slate-400 hover:bg-slate-800 transition">
            Annuler
        </a>
        <button wire:click="save" wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm rounded-xl bg-blue-600 hover:bg-blue-800 text-white font-medium transition disabled:opacity-50">
            <span wire:loading.remove wire:target="save">
                <x-lucide-check class="w-4 h-4 inline -mt-0.5" /> Créer la classe
            </span>
            <span wire:loading wire:target="save">Enregistrement...</span>
        </button>
    </div>
</div>

