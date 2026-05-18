<div class="min-h-screen flex items-center justify-center bg-sky-950 px-4">

    <div
        class="login-card w-full max-w-md bg-gray-900 rounded-2xl border border-sky-600 p-8"
        data-login-card
    >

        {{-- Logo école --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-300 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083
                             12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055
                             a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0
                             01.665-6.479L12 14z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-blue-500">Connexion</h1>
            <p class="text-sm text-amber-600 font-semibold tracking-wide mt-1">Accédez à votre espace école</p>
        </div>

        {{-- Message d'erreur global --}}
        @if($errorMessage)
            <div
                class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700"
            >
                {{ $errorMessage }}
            </div>
        @endif

        {{-- Formulaire --}}
        <form wire:submit.prevent="login" class="space-y-5">

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-sky-500 mb-1">
                    Adresse email
                </label>
                <input
                    id="email"
                    type="email"
                    wire:model="email"
                    autocomplete="email"
                    placeholder="votre@email.com"
                    class="w-full px-3 py-2 rounded-lg border border-gray-300 text-gray-300 text-sm
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
                    <label for="password" class="block text-sm font-medium text-sky-500">
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
                    class="w-full px-3 py-2 rounded-lg border border-gray-300 text-gray-400 text-sm
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
                       focus:ring-offset-gray-800
                       disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
            >
                <span wire:loading.remove>Accéder à votre espace</span>
                <span wire:loading class="flex items-center gap-1 justify-center">
                    <div class="flex items-center gap-1 justify-center" role="status">
                        <svg aria-hidden="true" class="w-4 h-4 text-sky-900 animate-spin fill-blue-200" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                        <span>Connexion...</span>
                    </div>

                </span>
            </button>
        </form>
    </div>
</div>