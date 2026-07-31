<div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-[#0a0a0b]">

    {{-- Background géométrique --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0"
            style="background-image:
                radial-gradient(circle at 20% 30%, rgba(16, 185, 129, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.06) 0%, transparent 45%);">
        </div>
        {{-- Lignes diagonales subtiles --}}
        <div class="absolute inset-0 opacity-[0.025]"
            style="background-image: repeating-linear-gradient(
                -12deg,
                transparent,
                transparent 40px,
                rgba(255,255,255,0.4) 40px,
                rgba(255,255,255,0.4) 41px
             );">
        </div>
    </div>

    <div class="relative w-full max-w-[400px] mx-auto px-4 py-12">

        {{-- Brand header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-6">
                <div
                    class="w-11 h-11 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-lg font-bold text-white tracking-tight leading-none">EducConnect</p>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-emerald-500/80 mt-1">Central Admin</p>
                </div>
            </div>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl bg-[#111113] border border-white/[0.06] shadow-2xl shadow-black/50 overflow-hidden"
            data-login-card>

            {{-- Status bar --}}
            <div class="flex items-center justify-between px-5 py-3 border-b border-white/[0.04] bg-white/[0.02]">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[11px] font-mono text-slate-500">Système opérationnel</span>
                </div>
                <span class="text-[10px] font-mono text-slate-600">v2.0</span>
            </div>

            <div class="p-6 sm:p-8">

                <div class="mb-6">
                    <h1 class="text-xl font-semibold text-white">Authentification</h1>
                    <p class="mt-1 text-sm text-slate-500">Accès réservé à l’administration centrale</p>
                </div>

                @if ($errorMessage)
                    <div
                        class="mb-5 flex items-start gap-3 rounded-lg bg-red-500/10 border border-red-500/20 px-4 py-3">
                        <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z" />
                        </svg>
                        <p class="text-sm text-red-300">{{ $errorMessage }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="login" class="space-y-4">

                    {{-- Email --}}
                    <div>
                        <label for="email"
                            class="block text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                            Email
                        </label>
                        <input id="email" type="email" wire:model="email" autocomplete="email"
                            placeholder="admin@educconnect.com"
                            class="w-full h-11 rounded-lg bg-[#0a0a0b] border border-white/[0.08] px-3.5 text-sm text-white placeholder:text-slate-600
                                      focus:outline-none focus:border-emerald-500/40 focus:ring-1 focus:ring-emerald-500/20 transition-all
                                      @error('email') border-red-500/50 @enderror" />
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password"
                            class="block text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                            Mot de passe
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input id="password" :type="show ? 'text' : 'password'" wire:model="password"
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full h-11 rounded-lg bg-[#0a0a0b] border border-white/[0.08] px-3.5 pr-10 text-sm text-white placeholder:text-slate-600
                                          focus:outline-none focus:border-emerald-500/40 focus:ring-1 focus:ring-emerald-500/20 transition-all
                                          @error('password') border-red-500/50 @enderror" />
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-600 hover:text-slate-400 transition">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full h-11 mt-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold
                                   transition-all duration-200 active:scale-[0.98]
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:ring-offset-2 focus:ring-offset-[#111113]
                                   disabled:opacity-50 disabled:cursor-not-allowed
                                   flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="login" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Accéder à l’administration
                        </span>
                        <span wire:loading wire:target="login" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            Authentification…
                        </span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Footer --}}
        <p class="mt-6 text-center text-[11px] text-slate-600 font-mono">
            EducConnect · Administration centrale · Accès sécurisé
        </p>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
