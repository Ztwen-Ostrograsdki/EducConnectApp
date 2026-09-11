<div class="max-w-6xl mx-auto py-12 px-4 sm:px-6">
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-b from-[#141b2d] to-[#0b0f17] border border-white/10 p-8 sm:p-10 shadow-2xl shadow-black/40">
        <!-- Accent Glow -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none">
        </div>

        <div class="relative z-10">
            <div class="mb-8 pb-6 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Partagez votre avis</h1>
                    <p class="mt-1 text-sm text-slate-400">Votre expérience compte et aide notre communauté à grandir.
                    </p>
                </div>
                <div
                    class="hidden sm:flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                </div>
            </div>

            <form wire:submit="save" class="space-y-6">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Votre
                            témoignage <span class="text-indigo-400">*</span></label>
                        <span class="text-[11px] text-slate-500">10 - 2000 caractères</span>
                    </div>

                    <div class="relative group">
                        <textarea wire:model="content" rows="5" placeholder="Décrivez votre expérience en quelques lignes..."
                            style="font-family: cursive, monospace"
                            class="w-full rounded-2xl bg-slate-900/80 border border-white/10 p-4 text-sm text-slate-400 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all resize-none shadow-inner"></textarea>
                    </div>

                    @error('content')
                        <p class="mt-1.5 text-xs font-medium text-rose-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="submit" wire:loading.attr="disabled"
                        class="relative inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/25 hover:from-indigo-500 hover:to-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 disabled:opacity-60 transition-all duration-200">
                        <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                            Publier le témoignage
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Enregistrement en cours...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

