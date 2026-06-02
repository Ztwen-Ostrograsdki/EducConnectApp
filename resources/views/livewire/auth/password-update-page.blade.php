<div class="max-w-3xl mx-auto">

    <section class="rounded-3xl
               border border-slate-800
               bg-slate-900/80
               overflow-hidden">

        {{-- HEADER --}}
        <div class="p-6
                   border-b border-slate-800">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14
                           rounded-2xl
                           bg-amber-500/10
                           flex items-center justify-center">

                    <x-lucide-key-round class="w-7 h-7 text-amber-400" />

                </div>

                <div>

                    <h2 class="text-lg font-sans">

                        Modifier le mot de passe

                    </h2>

                    <p class="text-sm text-slate-400 mt-1">

                        Sécurisez votre compte en définissant
                        un nouveau mot de passe.

                    </p>

                </div>

            </div>

        </div>

        {{-- FORM --}}
        <form wire:submit="updatePassword" class="p-6 space-y-6">

            {{-- PASSWORD ACTUEL --}}
            <div>

                <label class="block mb-2 text-sm font-medium">

                    Mot de passe actuel

                </label>

                <div class="relative">

                    <input type="password" wire:model="current_password"
                        class="w-full h-12
                               rounded-2xl
                               border border-slate-700
                               bg-slate-800
                               px-4 pr-12
                               focus:border-indigo-500
                               focus:ring-0">

                    <button type="button" class="absolute
                               right-4 top-1/2
                               -translate-y-1/2
                               text-slate-400">

                        <x-lucide-eye class="w-5 h-5" />

                    </button>

                </div>

                @error('current_password')
                    <p class="mt-2 text-sm text-rose-400">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- NOUVEAU PASSWORD --}}
            <div>

                <label class="block mb-2 text-sm font-medium">

                    Nouveau mot de passe

                </label>

                <div class="relative">

                    <input type="password" wire:model.live="password"
                        class="w-full h-12
                               rounded-2xl
                               border border-slate-700
                               bg-slate-800
                               px-4 pr-12
                               focus:border-indigo-500
                               focus:ring-0">

                    <button type="button" class="absolute
                               right-4 top-1/2
                               -translate-y-1/2
                               text-slate-400">

                        <x-lucide-eye class="w-5 h-5" />

                    </button>

                </div>

                {{-- FORCE PASSWORD --}}
                <div class="mt-4">

                    <div class="h-2 rounded-full
                               bg-slate-800 overflow-hidden">

                        <div class="h-full rounded-full
                                   bg-emerald-500" style="width: 75%">
                        </div>

                    </div>

                    <p class="mt-2 text-xs text-emerald-400">

                        Mot de passe fort

                    </p>

                </div>

                @error('password')
                    <p class="mt-2 text-sm text-rose-400">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- CONFIRMATION --}}
            <div>

                <label class="block mb-2 text-sm font-medium">

                    Confirmation du mot de passe

                </label>

                <div class="relative">
                    <input type="password" wire:model="password_confirmation" class="w-full h-12 rounded-2xl border border-slate-700 bg-slate-800 px-4 pr-12 focus:border-indigo-500 focus:ring-0">
                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row
                       justify-end gap-3">

                <button type="button" class="h-12 px-6
                           rounded-2xl
                           border border-slate-700
                           bg-slate-800">

                    Annuler

                </button>

                <button type="submit"
                    class="h-12 px-6
                           rounded-2xl
                           bg-indigo-500
                           hover:bg-indigo-400
                           text-white
                           font-semibold
                           flex items-center justify-center gap-2">

                    <x-lucide-save class="w-5 h-5" />

                    Enregistrer le mot de passe

                </button>

            </div>

        </form>

    </section>

</div>

