<div class="mx-auto w-full space-y-6 py-5.5">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('tenant.promotions.portal') }}" wire:navigate
            class="rounded-xl border border-slate-700 p-2 text-slate-400 hover:text-white hover:bg-slate-800 transition">
            <x-lucide-arrow-left class="w-4 h-4" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Nouvelle promotion</h1>
            <p class="text-sm text-slate-500 mt-0.5">Ajouter une promotion à l'établissement
                <span class="text-amber-600 opacity-75">{{ tenant('school_name') }}</span>
            </p>
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="rounded-2xl border border-slate-700 bg-slate-900 p-6 space-y-5">

        {{-- Name --}}
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Nom de la promotion <span
                    class="text-rose-400">*</span></label>
            <input type="text" wire:model.live="name" placeholder="ex: Terminale, Troisième, Sixième"
                class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
            @error('name')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
            @if ($previewSlug)
                <p class="mt-1 text-xs text-slate-500">Slug : <span
                        class="text-slate-400 font-mono">{{ $previewSlug }}</span></p>
            @endif
        </div>

        {{-- Code --}}
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Code <span
                    class="text-slate-600">(optionnel)</span></label>
            <input type="text" wire:model="code" placeholder="ex: TLE, 3EME, 6EME"
                class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
            @error('code')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Level --}}
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Niveau <span
                    class="text-rose-400">*</span></label>
            <div class="flex gap-3">
                @foreach (['primaire' => 'Primaire', 'secondaire' => 'Secondaire', 'superieur' => 'Supérieur'] as $val => $label)
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" wire:model="level" value="{{ $val }}" class="sr-only peer" />
                        <div
                            class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-3 text-center text-sm text-slate-400
                        peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10 peer-checked:text-indigo-400 transition">
                            {{ $label }}
                        </div>
                    </label>
                @endforeach
            </div>
            @error('level')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Order --}}
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Ordre d'affichage <span
                    class="text-rose-400">*</span></label>
            <input type="number" wire:model="order" min="1"
                class="w-32 rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition" />
            <p class="mt-1 text-xs text-slate-500">Détermine l'ordre dans les listes (1 = en tête).</p>
            @error('order')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- is_active --}}
        <label class="flex items-center gap-3 cursor-pointer">
            <div class="relative">
                <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                <div class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-indigo-600 transition"></div>
                <div
                    class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white transition peer-checked:translate-x-4">
                </div>
            </div>
            <span class="text-sm text-slate-300">Promotion active</span>
        </label>
    </div>

    {{-- Footer actions --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('tenant.promotions.portal') }}" wire:navigate
            class="px-5 py-2.5 text-sm rounded-xl border border-slate-700 text-slate-400 hover:bg-slate-800 transition">
            Annuler
        </a>
        <button wire:click="save" wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium transition disabled:opacity-50">
            <span wire:loading.remove wire:target="save">
                <x-lucide-check class="w-4 h-4 inline -mt-0.5" /> Créer la promotion
            </span>
            <span wire:loading wire:target="save">Enregistrement...</span>
        </button>
    </div>
</div>

