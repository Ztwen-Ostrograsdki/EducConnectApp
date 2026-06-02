<div x-data class="w-full max-w-7xl mx-auto py-8">

    <form wire:submit="save">

        <div class="overflow-hidden rounded-3xl
                   border border-white/10
                   bg-slate-900/80
                   backdrop-blur-xl
                   shadow-2xl">

            {{-- COVER --}}
            <div class="relative h-72 overflow-hidden">

                {{-- Background animé --}}
                <div
                    class="absolute inset-0
                           bg-gradient-to-r
                           from-cyan-500
                           via-blue-600
                           to-purple-600
                           animate-pulse">
                </div>

                {{-- Image Cover --}}
                @if (auth()->user()->profil_photo)
                    <img src="{{ $this->currentPhoto }}" class="absolute inset-0
                               w-full h-full
                               object-cover
                               opacity-30">
                @endif

                {{-- Overlay --}}
                <div class="absolute inset-0
                           bg-black/40"></div>

                {{-- Loader Upload --}}
                <div wire:loading.flex wire:target="photo"
                    class="absolute inset-0
                           bg-black/80
                           items-center
                           justify-center
                           backdrop-blur-sm
                           z-50">

                    <div class="text-center">

                        <svg class="w-10 h-10 animate-spin text-cyan-400 mx-auto" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />

                            <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>

                        <p class="mt-3 text-white">
                            Téléversement...
                        </p>

                    </div>

                </div>

            </div>

            {{-- PROFILE --}}
            <div class="relative px-8 pb-8">

                <div class="flex flex-col lg:flex-row
                           gap-8 items-center lg:items-end">

                    {{-- Avatar --}}
                    <div class="-mt-20 relative">

                        <div class="w-40 h-40 rounded-full
                                   ring-4 ring-slate-900
                                   overflow-hidden
                                   shadow-2xl">

                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover
                                           transition duration-500">
                            @elseif(auth()->user()->profil_photo)
                                <img src="{{ $this->currentPhoto }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full
                                           flex items-center justify-center
                                           bg-slate-800">

                                    <svg class="w-20 h-20 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6.75a3.75 3.75 0 11-7.5 0
                                            3.75 3.75 0 017.5 0zm4.5
                                            13.5a8.25 8.25 0 10-16.5
                                            0h16.5z" />
                                    </svg>

                                </div>
                            @endif

                        </div>

                        {{-- Badge --}}
                        <div class="absolute bottom-3 right-3
                                   w-5 h-5 rounded-full
                                   bg-green-500
                                   ring-2 ring-slate-900"></div>

                    </div>

                    {{-- Infos --}}
                    <div class="flex-1">

                        <h1 class="text-3xl font-bold
                                   text-white">
                            {{ auth()->user()->name }}
                        </h1>

                        <p class="text-slate-400 mt-1">
                            Personnalisez votre photo de profil.
                        </p>

                    </div>

                </div>

                {{-- Upload Zone --}}
                <div class="mt-10">

                    <label class="group block cursor-pointer">

                        <input type="file" wire:model="photo" accept="image/*" class="hidden">

                        <div
                            class="border-2 border-dashed
                                   border-slate-700
                                   rounded-2xl
                                   p-10
                                   bg-slate-800/50
                                   hover:border-cyan-500
                                   hover:bg-slate-800
                                   transition-all
                                   duration-300">

                            <div class="text-center">

                                <svg class="w-14 h-14 mx-auto
                                           text-slate-500
                                           group-hover:text-cyan-400
                                           transition"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 16V4m0 0l-4 4m4-4l4 4m6
                                        8v1a2 2 0 01-2 2H4a2 2 0
                                        01-2-2v-1" />
                                </svg>

                                <h3 class="mt-4 text-white font-semibold">
                                    Cliquez pour sélectionner une image
                                </h3>

                                <p class="text-slate-400 text-sm mt-2">
                                    PNG, JPG, WEBP • Max 2MB
                                </p>

                            </div>

                        </div>

                    </label>

                    @error('photo')
                        <p class="mt-2 text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Actions --}}
                <div class="flex justify-end mt-8">

                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex
                               items-center
                               gap-3
                               px-6 py-3
                               rounded-xl
                               font-semibold
                               text-white
                               bg-gradient-to-r
                               from-cyan-500
                               to-blue-600
                               hover:scale-105
                               transition-all
                               duration-300
                               disabled:opacity-50">

                        <svg wire:loading wire:target="save" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25" />

                            <path fill="currentColor" d="M4 12a8 8 0 018-8v4a4
                                4 0 00-4 4H4z" />
                        </svg>

                        <span wire:loading.remove wire:target="save">
                            Enregistrer les modifications
                        </span>

                        <span wire:loading wire:target="save">
                            Sauvegarde...
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

