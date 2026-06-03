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
        <form wire:submit.prevent="updatePassword" class="p-6 space-y-6">

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
                        <div class="h-full rounded-full transition-all {{ $password_strength_bg }} {{ $password_strength_with }}">
                        </div>
                    </div>
                    <p class="mt-2 text-xs {{ $password_strength_text_color }}">
                        {{ $password_strength_text }}
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
                    <input type="password" wire:model.live="password_confirmation" class="w-full h-12 rounded-2xl border border-slate-700 bg-slate-800 px-4 pr-12 focus:border-indigo-500 focus:ring-0">
                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row
                       justify-end gap-3">
                <a href="{{ route('tenant.my.profil') }}" type="button" class="py-2 px-6 rounded-2xl border border-slate-700 bg-slate-800 hover:bg-slate-900">
                    Annuler
                </a>
                @if ($current_password && $password && $password_confirmation)
                    <button type="submit" wire:loading.attr="disabled" class="py-2 px-6 rounded-2xl bg-indigo-700 hover:bg-indigo-900 text-white font-semibold flex items-center justify-center gap-2">
                        <span class="flex items-center gap-1.5" wire:target='updatePassword' wire:loading.remove>
                            <x-lucide-save class="w-5 h-5" />
                            Enregistrer le mot de passe
                        </span>
                        <span wire:target='updatePassword' wire:loading.flex class="items-center gap-1.5">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            <span>Enregistrement...</span>
                        </span>
                    </button>
                @endif

            </div>

        </form>

    </section>

</div>

