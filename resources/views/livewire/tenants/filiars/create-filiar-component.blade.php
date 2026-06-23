<div class="mx-auto w-full space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('tenant.serials.portal') }}" wire:navigate
            class="rounded-xl border border-slate-700 p-2 text-slate-400 hover:text-white hover:bg-slate-800 transition">
            <x-lucide-arrow-left class="w-4 h-4" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Nouvelle filière</h1>
            <p class="text-sm text-slate-500 mt-0.5">Ajouter une filière à l'établissement</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-700 bg-slate-900 p-6 space-y-5">
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Nom <span
                    class="text-rose-400">*</span></label>
            <input type="text" wire:model.live="name" placeholder="ex: Informatique, BTP"
                class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
            @error('name')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
            @if ($previewSlug)
                <p class="mt-1 text-xs text-slate-500">Slug : <span
                        class="text-slate-400 font-mono">{{ $previewSlug }}</span></p>
            @endif
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Code <span
                    class="text-slate-600">(optionnel)</span></label>
            <input type="text" wire:model="code" placeholder="ex: INFO, BTP"
                class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
            @error('code')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Description <span
                    class="text-slate-600">(optionnel)</span></label>
            <textarea wire:model="description" rows="3" placeholder="Description de la filière..."
                class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition resize-none"></textarea>
            @error('description')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-3 cursor-pointer">
            <div class="relative">
                <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                <div class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-indigo-600 transition"></div>
                <div
                    class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white transition peer-checked:translate-x-4">
                </div>
            </div>
            <span class="text-sm text-slate-300">Filière active</span>
        </label>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('tenant.filiars.portal') }}" wire:navigate
            class="px-5 py-2.5 text-sm rounded-xl border border-slate-700 text-slate-400 hover:bg-slate-800 transition">
            Annuler
        </a>
        <button wire:click="save" wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium transition disabled:opacity-50">
            <span wire:loading.remove wire:target="save"><x-lucide-check class="w-4 h-4 inline -mt-0.5" /> Créer la
                filière</span>
            <span wire:loading wire:target="save">Enregistrement...</span>
        </button>
    </div>
</div>

