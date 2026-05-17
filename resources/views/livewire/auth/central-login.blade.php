<div class="min-h-screen flex items-center justify-center bg-gray-900 px-4">

    <div
        class="login-card w-full max-w-md bg-gray-800 rounded-2xl border border-gray-700 p-8 opacity-0"
        data-login-card
    >

        {{-- Logo EducConnect --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-900 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944
                             a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9
                             c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03
                             9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">EducConnect</h1>
            <p class="text-sm text-gray-400 mt-1">Administration centrale</p>
        </div>

        {{-- Message d'erreur global --}}
        @if($errorMessage)
            <div
                class="mb-4 p-3 bg-red-900/40 border border-red-700 rounded-lg text-sm text-red-400"
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
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">
                    Adresse email
                </label>
                <input
                    id="email"
                    type="email"
                    wire:model="email"
                    autocomplete="email"
                    placeholder="admin@educconnect.com"
                    class="w-full px-3 py-2 rounded-lg border border-gray-600
                           bg-gray-700 text-white text-sm placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-primary-500
                           focus:border-primary-500 transition
                           @error('email') border-red-500 @enderror"
                />
                @error('email')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mot de passe --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">
                    Mot de passe
                </label>
                <input
                    id="password"
                    type="password"
                    wire:model="password"
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full px-3 py-2 rounded-lg border border-gray-600
                           bg-gray-700 text-white text-sm placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-primary-500
                           focus:border-primary-500 transition
                           @error('password') border-red-500 @enderror"
                />
                @error('password')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
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
                       focus:ring-offset-gray-800
                       disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
            >
                <span wire:loading.remove>Accéder à l'administration</span>
                <span wire:loading class="flex items-center gap-2 ">
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