<div>
    <div x-data x-show="$wire.show" x-cloak x-transition.opacity @keydown.escape.window="$wire.close()"
        class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-[#070b14]/80 backdrop-blur-md" @click="$wire.close()"></div>

        {{-- Panel --}}
        <div x-show="$wire.show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95 translate-y-1" @click.stop
            class="relative z-10 w-full max-w-lg rounded-2xl bg-[#0f1523] border border-white/[0.08] shadow-2xl shadow-black/50 overflow-hidden">

            {{-- Lueur décorative --}}
            <div
                class="pointer-events-none absolute -top-20 -right-20 w-40 h-40 rounded-full bg-indigo-500/15 blur-3xl">
            </div>

            {{-- Header --}}
            <div
                class="relative flex items-center justify-between gap-3 px-5 sm:px-6 py-4 border-b border-white/[0.06]">
                <div class="flex items-center gap-3 min-w-0">
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-500/15 border border-indigo-500/25 flex items-center justify-center shrink-0">
                        <x-lucide-message-square-quote class="w-4 h-4 text-indigo-400" />
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-semibold text-white">Ajouter un témoignage</h3>
                        <p class="text-[11px] text-slate-500">Partagez votre expérience</p>
                    </div>
                </div>
                <button type="button" wire:click="close"
                    class="w-8 h-8 rounded-lg hover:bg-white/5 text-slate-500 hover:text-slate-300 transition-all flex items-center justify-center shrink-0">
                    <x-lucide-x class="w-4 h-4" />
                </button>
            </div>

            {{-- Body --}}
            <div class="relative px-5 sm:px-6 py-5 space-y-3">
                <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                    Votre message
                </label>

                <div class="relative">
                    <textarea wire:model="content" rows="6" placeholder="Ce que vous avez apprécié, vécu, recommandé…"
                        class="w-full rounded-xl bg-[#070b14] border border-white/10 px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600
                                     focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/30 transition-all resize-none leading-relaxed
                                     @error('content') border-rose-500/50 @enderror"></textarea>

                    {{-- Compteur discret --}}
                    <div class="absolute bottom-2.5 right-3 text-[10px] font-mono text-slate-600 pointer-events-none">
                        10 – 2000
                    </div>
                </div>

                @error('content')
                    <p class="text-xs text-rose-400 flex items-center gap-1.5">
                        <x-lucide-circle-alert class="w-3.5 h-3.5 shrink-0" />
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Footer --}}
            <div
                class="relative flex items-center justify-end gap-2.5 px-5 sm:px-6 py-4 border-t border-white/[0.06] bg-[#0a0e18]/50">
                <button type="button" wire:click="close"
                    class="h-10 px-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-400 hover:text-slate-200 transition-all">
                    Annuler
                </button>

                <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save"
                    class="h-10 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition-all disabled:opacity-50 inline-flex items-center gap-2">
                    <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                        <x-lucide-send class="w-4 h-4" />
                        Publier
                    </span>
                    <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                        <span class="relative flex h-4 w-4">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white/40"></span>
                            <x-lucide-loader-2 class="relative w-4 h-4 animate-spin" />
                        </span>
                        Envoi…
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
