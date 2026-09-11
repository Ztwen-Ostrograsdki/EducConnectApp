<div>
    @if ($show)
        {{-- Backdrop + modal (Livewire only, pas de dépendance Alpine pour l'affichage) --}}
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6" wire:key="testimonial-modal-open"
            x-data x-init="$el.querySelector('textarea')?.focus()">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" wire:click="close"
                wire:loading.class="pointer-events-none" wire:target="save,close">
            </div>

            {{-- Panel --}}
            <div class="relative w-full max-w-lg rounded-2xl bg-[#0f1523] border border-white/10
                        shadow-2xl shadow-black/50 overflow-hidden
                        animate-[modalIn_0.25s_ease-out]"
                role="dialog" aria-modal="true" aria-labelledby="testimonial-modal-title" @click.stop
                @keydown.escape.window="$wire.close()">

                {{-- Header --}}
                <div class="flex items-center justify-between gap-3 px-5 sm:px-6 py-4 border-b border-white/[0.06]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-9 h-9 rounded-xl bg-indigo-500/15 border border-indigo-500/25 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h2 id="testimonial-modal-title" class="text-base sm:text-lg font-bold text-white truncate">
                                Laisser un témoignage
                            </h2>
                            <p class="text-[11px] text-slate-500 truncate">Partagez votre expérience</p>
                        </div>
                    </div>

                    <button type="button" wire:click="close"
                        class="shrink-0 rounded-lg p-2 text-slate-400 hover:text-white hover:bg-white/5 transition-colors duration-200"
                        aria-label="Fermer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <form wire:submit="save" class="px-5 sm:px-6 py-5 space-y-4">
                    <div>
                        <label for="testimonial-content" class="block text-xs font-medium text-slate-400 mb-1.5">
                            Votre témoignage <span class="text-rose-400">*</span>
                        </label>
                        <textarea id="testimonial-content" wire:model="content" rows="5" placeholder="Écrivez votre témoignage ici..."
                            class="w-full rounded-xl bg-slate-900/50 border border-white/10 px-3.5 py-2.5 text-sm text-white
                                         placeholder-slate-600 resize-none
                                         focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 focus:bg-slate-900/80
                                         outline-none transition-all duration-200"></textarea>
                        @error('content')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1.5 text-[11px] text-slate-600">Minimum 10 caractères — Maximum 2000 caractères</p>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5 pt-1">
                        <button type="button" wire:click="close" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10
                                       bg-transparent px-4 py-2.5 text-sm font-medium text-slate-300
                                       hover:bg-white/5 hover:text-white transition-all duration-200">
                            Annuler
                        </button>

                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="relative inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600
                                       px-5 py-2.5 text-sm font-semibold text-white
                                       hover:bg-indigo-500 active:scale-[0.98]
                                       disabled:opacity-70 disabled:cursor-not-allowed
                                       transition-all duration-200
                                       shadow-lg shadow-indigo-600/20 hover:shadow-indigo-500/30">

                            <span wire:loading wire:target="save"
                                class="absolute inset-0 rounded-xl bg-indigo-400/30 blur-md animate-pulse pointer-events-none"></span>

                            <span class="relative flex items-center gap-2">
                                <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span wire:loading.remove wire:target="save">Publier</span>
                                <span wire:loading wire:target="save">Publication...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <style>
            @keyframes modalIn {
                from {
                    opacity: 0;
                    transform: scale(0.95) translateY(12px);
                }

                to {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }
        </style>
    @endif
</div>

