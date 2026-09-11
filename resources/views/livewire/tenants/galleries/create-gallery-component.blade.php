<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
    {{-- Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-2">
            <div
                class="w-10 h-10 rounded-xl bg-indigo-500/15 border border-indigo-500/25 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Ajouter des images à la galerie
                </h1>
            </div>
        </div>
        <p class="text-sm text-slate-400 ml-[3.25rem]">
            Ajoutez une ou plusieurs images. Chaque image peut avoir un titre et une description.
        </p>
    </div>

    <form wire:submit="save" class="space-y-5">
        @foreach ($items as $index => $item)
            <div class="group relative rounded-2xl bg-[#0f1523] border border-white/[0.07] p-5 sm:p-6 space-y-5
                        transition-all duration-300 ease-out
                        hover:border-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/5"
                wire:key="item-{{ $index }}">

                {{-- Card header --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span
                            class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-500/15 text-indigo-400 text-xs font-bold">
                            {{ $index + 1 }}
                        </span>
                        <h3 class="text-sm font-semibold text-slate-200">Image #{{ $index + 1 }}</h3>
                    </div>

                    @if (count($items) > 1)
                        <button type="button" wire:click="removeItem({{ $index }})"
                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-medium
                                       text-rose-400/80 hover:text-rose-300 hover:bg-rose-500/10
                                       border border-transparent hover:border-rose-500/20
                                       transition-all duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer
                        </button>
                    @endif
                </div>

                {{-- Image upload zone --}}
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">Image <span
                            class="text-rose-400">*</span></label>

                    <div class="relative">
                        <label
                            class="flex flex-col items-center justify-center w-full min-h-[140px] rounded-xl
                                       border-2 border-dashed border-white/10 bg-slate-900/40
                                       cursor-pointer
                                       transition-all duration-300 ease-out
                                       hover:border-indigo-500/40 hover:bg-indigo-500/5
                                       has-[:focus]:border-indigo-500/50">
                            <input type="file" wire:model="items.{{ $index }}.image" accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                            @if ($item['image'])
                                <div class="relative p-3 w-full">
                                    <img src="{{ $item['image']->temporaryUrl() }}" alt="Aperçu"
                                        class="mx-auto max-h-40 rounded-lg object-cover border border-white/10 shadow-lg
                                                animate-in fade-in zoom-in-95 duration-300">
                                    <p class="mt-2 text-center text-[11px] text-slate-500">Cliquez pour changer l'image
                                    </p>
                                </div>
                            @else
                                <div class="flex flex-col items-center gap-2 py-6 pointer-events-none">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center
                                                group-hover:scale-105 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 16v-8m0 0l-3 3m3-3l3 3M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-400">
                                        <span class="text-indigo-400 font-medium">Cliquez</span> ou glissez une image
                                    </p>
                                    <p class="text-[11px] text-slate-600">PNG, JPG, WEBP — max 5 Mo</p>
                                </div>
                            @endif
                        </label>

                        {{-- Loading glow overlay --}}
                        <div wire:loading wire:target="items.{{ $index }}.image"
                            class="absolute inset-0 rounded-xl flex flex-col items-center justify-center
                                    bg-[#0f1523]/85 backdrop-blur-sm z-10
                                    animate-in fade-in duration-200">
                            <div class="relative">
                                {{-- Glow ring --}}
                                <div
                                    class="absolute inset-0 rounded-full bg-indigo-500/30 blur-xl scale-150 animate-pulse">
                                </div>
                                <div
                                    class="relative w-12 h-12 rounded-full border-2 border-indigo-500/30 border-t-indigo-400 animate-spin">
                                </div>
                            </div>
                            <p class="mt-3 text-xs font-medium text-indigo-300 animate-pulse">Chargement de l'image...
                            </p>
                        </div>
                    </div>

                    @error("items.{$index}.image")
                        <p
                            class="mt-2 text-xs text-rose-400 flex items-center gap-1.5 animate-in fade-in slide-in-from-top-1 duration-200">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Title + Description grid --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Titre <span
                                class="text-slate-600">(optionnel)</span></label>
                        <input type="text" wire:model="items.{{ $index }}.title"
                            placeholder="Ex: Cérémonie de remise des diplômes"
                            class="w-full rounded-xl bg-slate-900/50 border border-white/10 px-3.5 py-2.5 text-sm text-white
                                      placeholder-slate-600
                                      focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 focus:bg-slate-900/80
                                      outline-none transition-all duration-200">
                        @error("items.{$index}.title")
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Description <span
                                class="text-slate-600">(optionnel)</span></label>
                        <textarea wire:model="items.{{ $index }}.description" rows="2" placeholder="Description de l'image..."
                            class="w-full rounded-xl bg-slate-900/50 border border-white/10 px-3.5 py-2.5 text-sm text-white
                                         placeholder-slate-600 resize-none
                                         focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 focus:bg-slate-900/80
                                         outline-none transition-all duration-200"></textarea>
                        @error("items.{$index}.description")
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Action buttons --}}
        <div class="flex flex-wrap items-center gap-3 pt-2">
            {{-- Add another image --}}
            <button type="button" wire:click="addItem"
                class="group/btn inline-flex items-center gap-2 rounded-xl border border-white/10 bg-slate-800/40
                           px-4 py-2.5 text-sm font-medium text-slate-300
                           hover:bg-slate-800/80 hover:border-white/20 hover:text-white
                           active:scale-[0.98]
                           transition-all duration-200">
                <svg class="w-4 h-4 text-slate-400 group-hover/btn:text-indigo-400 transition-colors duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter une autre image
            </button>

            {{-- Submit --}}
            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                class="relative group/save inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5
                           text-sm font-semibold text-white
                           hover:bg-indigo-500
                           active:scale-[0.98]
                           disabled:opacity-70 disabled:cursor-not-allowed
                           transition-all duration-200
                           shadow-lg shadow-indigo-600/20 hover:shadow-indigo-500/30">

                {{-- Glow effect when loading --}}
                <span wire:loading wire:target="save"
                    class="absolute inset-0 rounded-xl bg-indigo-400/30 blur-md animate-pulse pointer-events-none"></span>

                <span class="relative flex items-center gap-2">
                    {{-- Idle icon --}}
                    <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>

                    {{-- Loading spinner --}}
                    <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>

                    <span wire:loading.remove wire:target="save">Enregistrer</span>
                    <span wire:loading wire:target="save">Enregistrement...</span>
                </span>
            </button>
        </div>
    </form>
</div>

