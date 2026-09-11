<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-white">Ajouter un témoignage</h1>
        <p class="mt-1 text-sm text-slate-400">Partagez votre expérience. Votre témoignage pourra être affiché sur le
            site.</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-xl bg-[#0f1523] border border-white/10 p-5">
            <label class="block text-xs font-medium text-slate-400 mb-1.5">Votre témoignage *</label>
            <textarea wire:model="content" rows="6" placeholder="Écrivez votre témoignage ici..."
                class="w-full rounded-lg bg-slate-900/50 border border-white/10 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition resize-none"></textarea>
            @error('content')
                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
            <p class="mt-1.5 text-[11px] text-slate-600">Minimum 10 caractères – Maximum 2000 caractères</p>
        </div>

        <button type="submit" wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-60 transition">
            <span wire:loading.remove wire:target="save">Publier le témoignage</span>
            <span wire:loading wire:target="save">Enregistrement...</span>
        </button>
    </form>
</div>

