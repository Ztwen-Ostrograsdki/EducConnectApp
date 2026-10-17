<div class="mx-auto w-full max-w-lg px-4 sm:px-6 pb-24" x-data="{
    showCurrent: false,
    showPassword: false,
    showConfirm: false
}">

    {{-- Illustration --}}
    <div class="mb-6 rounded-2xl border border-slate-950/80 bg-slate-900 overflow-hidden">
        <div class="relative h-20 sm:h-24 flex items-center justify-center bg-slate-950">
            {{-- Soft circles --}}
            <div class="absolute top-6 left-8 h-14 w-14 rounded-full bg-slate-950/80"></div>
            <div class="absolute bottom-5 right-10 h-10 w-10 rounded-full bg-slate-950/60"></div>

            {{-- Lock card --}}
            <div class="relative z-10 flex items-center gap-5 px-6">
                <div
                    class="h-10 w-10 rounded-2xl bg-slate-950 border border-slate-950 flex items-center justify-center shrink-0">
                    <x-lucide-lock-keyhole class="w-8 h-8 text-amber-400" />
                </div>
                <div class="hidden sm:flex items-center gap-2 text-slate-600">
                    <div class="h-px w-8 bg-slate-950"></div>
                    <x-lucide-arrow-right class="w-4 h-4 text-indigo-400" />
                    <div class="h-px w-8 bg-slate-950"></div>
                </div>
                <div class="h-10 w-10 rounded-2xl flex items-center justify-center shrink-0">
                    <x-lucide-key-round class="w-8 h-8 text-indigo-400" />
                </div>
            </div>
        </div>
        <div class="px-5 py-3 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-400">
                Remplacez votre mot de passe actuel par un nouveau mot de passe sécurisé
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-900 bg-slate-950 overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-800 flex items-center gap-3">
            <div
                class="h-11 w-11 rounded-xl bg-slate-950 border border-slate-950 flex items-center justify-center shrink-0">
                <x-lucide-key-round class="w-5 h-5 text-amber-400" />
            </div>
            <div class="min-w-0">
                <h1 class="text-base font-semibold text-white">Mot de passe</h1>
                <p class="text-xs text-slate-500 mt-0.5">Mettez à jour le mot de passe de votre compte</p>
            </div>
        </div>

        <form wire:submit.prevent="updatePassword" class="p-6 space-y-5">

            {{-- Actuel --}}
            <div class="space-y-1.5">
                <label for="current_password" class="block text-xs font-medium text-slate-400">
                    Mot de passe actuel
                </label>
                <div class="relative">
                    <input placeholder="Renseignez l'ancien mot de passe..." id="current_password"
                        :type="showCurrent ? 'text' : 'password'" wire:model="current_password"
                        autocomplete="current-password"
                        class="w-full h-11 rounded-xl border border-sky-900 bg-slate-950 px-3.5 pr-11 text-sm text-slate-100
                               placeholder:text-slate-600 focus:outline-none focus:border-slate-500
                               @error('current_password') border-rose-500 @enderror" />
                    <button type="button" @click="showCurrent = !showCurrent"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-slate-500 hover:text-slate-300"
                        tabindex="-1">
                        <x-lucide-eye x-show="!showCurrent" class="w-4 h-4" />
                        <x-lucide-eye-off x-show="showCurrent" class="w-4 h-4" x-cloak />
                    </button>
                </div>
                @error('current_password')
                    <p class="text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nouveau --}}
            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-medium text-slate-400">
                    Nouveau mot de passe
                </label>
                <div class="relative">
                    <input placeholder="Renseignez le nouveau mot de passe..." id="password"
                        :type="showPassword ? 'text' : 'password'" wire:model.live="password"
                        autocomplete="new-password"
                        class="w-full h-11 rounded-xl border border-sky-900 bg-slate-950 px-3.5 pr-11 text-sm text-slate-100
                               placeholder:text-slate-600 focus:outline-none focus:border-slate-500
                               @error('password') border-rose-500 @enderror" />
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-slate-500 hover:text-slate-300"
                        tabindex="-1">
                        <x-lucide-eye x-show="!showPassword" class="w-4 h-4" />
                        <x-lucide-eye-off x-show="showPassword" class="w-4 h-4" x-cloak />
                    </button>
                </div>

                @if (2 === 3)
                    <div class="pt-1 space-y-1.5 ">
                        <div class="h-1 rounded-full bg-slate-950 overflow-hidden">
                            <div
                                class="h-full rounded-full transition-all duration-300 {{ $password_strength_bg }} {{ $password_strength_with }}">
                            </div>
                        </div>
                        <p class="text-[11px] {{ $password_strength_text_color }}">{{ $password_strength_text }}</p>
                    </div>
                @endif

                @error('password')
                    <p class="text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirmation --}}
            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-xs font-medium text-slate-400">
                    Confirmation
                </label>
                <div class="relative">
                    <input placeholder="Confirmez le mot de passe..." id="password_confirmation"
                        :type="showConfirm ? 'text' : 'password'" wire:model.live="password_confirmation"
                        autocomplete="new-password"
                        class="w-full h-11 rounded-xl border border-sky-900 bg-slate-950 px-3.5 pr-11 text-sm text-slate-100
                               placeholder:text-slate-600 focus:outline-none focus:border-slate-500
                               @if ($password && $password_confirmation && $password === $password_confirmation) border-emerald-600 @endif
                               @error('password_confirmation') border-rose-500 @enderror" />
                    <button type="button" @click="showConfirm = !showConfirm"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-slate-500 hover:text-slate-300"
                        tabindex="-1">
                        <x-lucide-eye x-show="!showConfirm" class="w-4 h-4" />
                        <x-lucide-eye-off x-show="showConfirm" class="w-4 h-4" x-cloak />
                    </button>
                </div>
                @if ($password && $password_confirmation)
                    @if ($password === $password_confirmation)
                        <p class="text-xs text-emerald-400">Les mots de passe correspondent</p>
                    @else
                        <p class="text-xs text-rose-400">Les mots de passe ne correspondent pas</p>
                    @endif
                @endif
                @error('password_confirmation')
                    <p class="text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-800">
                <a wire:navigate href="{{ route('tenant.my.profil') }}"
                    class="h-10 px-4 rounded-xl text-xs font-medium text-slate-400 hover:text-white border border-slate-950 hover:border-slate-600 transition-colors inline-flex items-center bg-amber-500/45 hover:bg-amber-500">
                    Annuler
                </a>

                @if ($current_password && $password && $password_confirmation)
                    <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword"
                        class="h-10 px-5 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white
                               transition-colors inline-flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="updatePassword" class="inline-flex items-center gap-2">
                            <x-lucide-check class="w-3.5 h-3.5" />
                            Enregistrer
                        </span>
                        <span wire:loading wire:target="updatePassword" class="inline-flex items-center gap-2">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            …
                        </span>
                    </button>
                @else
                    <button type="button" disabled
                        class="h-10 px-5 rounded-xl text-xs font-semibold bg-slate-800 text-slate-500 cursor-not-allowed inline-flex items-center gap-2">
                        <x-lucide-check class="w-3.5 h-3.5" />
                        Enregistrer
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

