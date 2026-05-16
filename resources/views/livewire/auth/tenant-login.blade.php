<div
    class="min-h-screen flex items-center justify-center bg-gray-50 px-4"
    x-data
    x-init="
        $nextTick(() => {
            window.Motion.animate($el.querySelector('.login-card'),
                { opacity: [0, 1], y: [24, 0] },
                { duration: 0.4, easing: 'ease-out' }
            )
        })
    "
>
    <div class="login-card w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        {{-- Logo école --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083
                             12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055
                             a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0
                             01.665-6.479L12 14z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Connexion</h1>
            <p class="text-sm text-gray-500 mt-1">Accédez à votre espace école</p>
        </div>

        {{-- Message d'erreur global --}}
        @if($errorMessage)
            <div
                class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700"
                x-data
                x-init="window.Motion.animate($el, { opacity: [0, 1], x: [-8, 0] }, { duration: 0.3 })"
            >
                {{ $errorMessage }}
            </div>
        @endif

        {{-- Formulaire --}}
        <form wire:submit="login" class="space-y-5">

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Adresse email
                </label>
                <input
                    id="email"
                    type="email"
                    wire:model="email"
                    autocomplete="email"
                    placeholder="votre@email.com"
                    class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm
                           focus:outline-none focus:ring-2 focus:ring-primary-200
                           focus:border-primary-500 transition
                           @error('email') border-red-400 bg-red-50 @enderror"
                />
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mot de passe --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <a href="#" class="text-xs text-primary-600 hover:text-primary-700 transition">
                        Mot de passe oublié ?
                    </a>
                </div>
                <input
                    id="password"
                    type="password"
                    wire:model="password"
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm
                           focus:outline-none focus:ring-2 focus:ring-primary-200
                           focus:border-primary-500 transition
                           @error('password') border-red-400 bg-red-50 @enderror"
                />
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Se souvenir de moi --}}
            <div class="flex items-center gap-2">
                <input
                    id="remember"
                    type="checkbox"
                    wire:model="remember"
                    class="w-4 h-4 rounded border-gray-300 text-primary-600
                           focus:ring-primary-500 cursor-pointer"
                />
                <label for="remember" class="text-sm text-gray-600 cursor-pointer">
                    Se souvenir de moi
                </label>
            </div>

            {{-- Bouton connexion --}}
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5
                       bg-primary-600 hover:bg-primary-700 active:scale-95
                       text-white text-sm font-medium rounded-lg transition-all
                       duration-200 focus:outline-none focus:ring-2
                       focus:ring-primary-500 focus:ring-offset-2
                       disabled:opacity-60 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove>Se connecter</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Connexion...
                </span>
            </button>

        </form>
    </div>
</div>