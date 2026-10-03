<div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-[#05080f]">

    {{-- ===================== BACKGROUND ===================== --}}
    <div class="absolute inset-0">
        {{-- Gradient de base --}}
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/50 via-[#05080f] to-[#05080f]">
        </div>

        {{-- Orbes de lumière --}}
        <div
            class="absolute top-[-10%] left-[-5%] w-[600px] h-[600px] rounded-full bg-indigo-600/20 blur-[160px] animate-pulse-slow">
        </div>
        <div class="absolute bottom-[-15%] right-[-5%] w-[500px] h-[500px] rounded-full bg-violet-600/15 blur-[140px] animate-pulse-slow"
            style="animation-delay: 1.5s;"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full bg-cyan-500/8 blur-[180px]">
        </div>

        {{-- Grille subtile --}}
        <div class="absolute inset-0 opacity-[0.04]"
            style="background-image: linear-gradient(rgba(255,255,255,.12) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.12) 1px, transparent 1px); background-size: 64px 64px;">
        </div>

        {{-- Icônes éducatives flottantes --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            {{-- Livre --}}
            <div class="floating-icon absolute top-[12%] left-[8%] text-indigo-400/30">
                <x-lucide-book-open class="w-10 h-10" />
            </div>
            {{-- Crayon --}}
            <div class="floating-icon absolute top-[22%] right-[10%] text-violet-400/25" style="animation-delay: 0.8s;">
                <x-lucide-pencil class="w-8 h-8" />
            </div>
            {{-- Graduation --}}
            <div class="floating-icon absolute bottom-[18%] left-[12%] text-cyan-400/25" style="animation-delay: 1.6s;">
                <x-lucide-graduation-cap class="w-11 h-11" />
            </div>
            {{-- Ampoule --}}
            <div class="floating-icon absolute bottom-[28%] right-[8%] text-amber-400/20"
                style="animation-delay: 2.2s;">
                <x-lucide-lightbulb class="w-9 h-9" />
            </div>
            {{-- Calculatrice --}}
            <div class="floating-icon absolute top-[45%] left-[5%] text-emerald-400/20" style="animation-delay: 1.1s;">
                <x-lucide-calculator class="w-7 h-7" />
            </div>
            {{-- Globe --}}
            <div class="floating-icon absolute top-[38%] right-[6%] text-sky-400/20" style="animation-delay: 2.8s;">
                <x-lucide-globe class="w-8 h-8" />
            </div>
            {{-- Users / classe --}}
            <div class="floating-icon absolute bottom-[12%] right-[18%] text-rose-400/15"
                style="animation-delay: 0.4s;">
                <x-lucide-users class="w-9 h-9" />
            </div>
        </div>
    </div>

    {{-- ===================== CONTENU ===================== --}}
    <div class="relative w-full max-w-[440px] mx-auto px-4 py-12 z-10">

        {{-- Flash messages --}}
        @if (session('abort-error') || session('success') || $errorMessage)
            <div class="mb-5 space-y-2">
                @if (session('abort-error'))
                    <div
                        class="flex items-center gap-3 rounded-2xl bg-rose-500/10 border border-rose-500/25 px-4 py-3.5 text-sm text-rose-300 backdrop-blur-sm">
                        <x-lucide-circle-alert class="w-4 h-4 shrink-0" />
                        {{ session('abort-error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div
                        class="flex items-center gap-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 px-4 py-3.5 text-sm text-emerald-300 backdrop-blur-sm">
                        <x-lucide-circle-check class="w-4 h-4 shrink-0" />
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errorMessage)
                    <div
                        class="flex items-center gap-3 rounded-2xl bg-rose-500/10 border border-rose-500/25 px-4 py-3.5 text-sm text-rose-300 backdrop-blur-sm">
                        <x-lucide-circle-alert class="w-4 h-4 shrink-0" />
                        {{ $errorMessage }}
                    </div>
                @endif
            </div>
        @endif

        {{-- Carte de connexion --}}
        <div class="rounded-[2rem] bg-[#0c1220]/75 backdrop-blur-2xl border border-white/[0.09] shadow-2xl shadow-indigo-950/50 overflow-hidden relative"
            data-login-card>

            {{-- Lueur intérieure subtile --}}
            <div
                class="absolute inset-0 rounded-[2rem] bg-gradient-to-b from-white/[0.03] to-transparent pointer-events-none">
            </div>

            {{-- Ligne d'accent animée --}}
            <div
                class="h-[3px] w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-400 relative overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer-line">
                </div>
            </div>

            <div class="p-8 sm:p-10 relative">

                {{-- Brand / Logo --}}
                <div class="text-center mb-9">
                    <div class="relative inline-flex items-center justify-center mb-6">
                        {{-- Halo lumineux derrière le logo --}}
                        <div
                            class="absolute inset-0 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 blur-xl opacity-50 scale-110">
                        </div>
                        <div
                            class="relative w-[72px] h-[72px] rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-xl shadow-indigo-900/60 p-[2px]">
                            <div class="w-full h-full rounded-[14px] overflow-hidden bg-[#0c1220]">
                                <img src="{{ tenancy()->tenant->logo_url }}" alt="Logo de l'école"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <h1 class="text-2xl sm:text-[1.7rem] font-bold text-white tracking-tight">
                        Connexion
                    </h1>
                    <p class="mt-2 text-sm text-slate-400">
                        Accédez à votre espace
                        @if (tenant()?->school_name)
                            <span class="text-indigo-400 font-semibold">{{ tenant()->school_name }}</span>
                        @else
                            école
                        @endif
                    </p>
                </div>

                {{-- Formulaire --}}
                <form wire:submit.prevent="login" class="space-y-5">

                    {{-- Email --}}
                    <div>
                        <label for="email"
                            class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                            Adresse email
                        </label>
                        <div class="relative group">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                                <x-lucide-mail class="w-4 h-4" />
                            </span>
                            <input id="email" type="email" wire:model="email" autocomplete="email"
                                placeholder="votre@email.com"
                                class="w-full h-12 rounded-xl bg-[#070b14]/80 border border-white/10 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600
                                       focus:outline-none focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200
                                       @error('email') border-rose-500/50 bg-rose-500/5 @enderror" />
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1.5">
                                <x-lucide-circle-alert class="w-3 h-3" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div>
                        <label for="password"
                            class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                            Mot de passe
                        </label>
                        <div class="relative group" x-data="{ show: false }">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                                <x-lucide-lock class="w-4 h-4" />
                            </span>
                            <input id="password" :type="show ? 'text' : 'password'" wire:model="password"
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full h-12 rounded-xl bg-[#070b14]/80 border border-white/10 pl-11 pr-12 text-sm text-slate-200 placeholder:text-slate-600
                                       focus:outline-none focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200
                                       @error('password') border-rose-500/50 bg-rose-500/5 @enderror" />
                            <button type="button" @click="show = !show"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition">
                                <x-lucide-eye x-show="!show" class="w-4 h-4" />
                                <x-lucide-eye-off x-show="show" class="w-4 h-4" x-cloak />
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1.5">
                                <x-lucide-circle-alert class="w-3 h-3" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between pt-0.5">
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input id="remember" type="checkbox" wire:model="remember" class="sr-only peer">
                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-md border border-slate-600 bg-[#070b14] transition-all
                                         peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:shadow-[0_0_10px_rgba(99,102,241,0.4)]">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <span class="text-sm text-slate-500 group-hover:text-slate-300 transition">Se souvenir de
                                moi</span>
                        </label>

                        <a href="{{ route('tenant.password.forgot') }}"
                            class="text-sm text-indigo-400 hover:text-indigo-300 transition font-medium">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    {{-- Bouton Submit --}}
                    <button type="submit" wire:loading.attr="disabled"
                        class="group relative w-full h-[52px] rounded-xl overflow-hidden disabled:opacity-60 disabled:cursor-not-allowed active:scale-[0.98] transition-all duration-200 mt-3">

                        {{-- Fond gradient animé --}}
                        <span
                            class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-violet-600 to-indigo-600 bg-[length:200%_100%] group-hover:animate-[shimmer_2.5s_linear_infinite]"></span>

                        {{-- Glow externe --}}
                        <span
                            class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-400 rounded-xl blur-lg opacity-40 group-hover:opacity-70 transition-opacity duration-300 -z-10"></span>

                        <span
                            class="relative flex items-center justify-center gap-2.5 h-full text-white font-semibold text-sm tracking-wide">
                            <span wire:loading.remove wire:target="login" class="inline-flex items-center gap-2.5">
                                Accéder à votre espace
                                <x-lucide-arrow-right
                                    class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" />
                            </span>
                            <span wire:loading wire:target="login" class="inline-flex items-center gap-2.5">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                Connexion…
                            </span>
                        </span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Footer --}}
        <p class="mt-8 text-center text-xs text-slate-600">
            © {{ date('Y') }}
            @if (tenant()?->school_name)
                {{ tenant()->school_name }}
            @endif
            — Accès sécurisé
        </p>
    </div>
</div>

<style>
    /* Animation flottante des icônes */
    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
            opacity: 0.7;
        }

        50% {
            transform: translateY(-18px) rotate(4deg);
            opacity: 1;
        }
    }

    .floating-icon {
        animation: float 6s ease-in-out infinite;
        filter: drop-shadow(0 0 12px currentColor);
    }

    /* Pulse lent des orbes */
    @keyframes pulse-slow {

        0%,
        100% {
            opacity: 0.6;
            transform: scale(1);
        }

        50% {
            opacity: 1;
            transform: scale(1.05);
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow 8s ease-in-out infinite;
    }

    /* Shimmer du bouton */
    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Ligne d'accent qui brille */
    @keyframes shimmer-line {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    .animate-shimmer-line {
        animation: shimmer-line 3s ease-in-out infinite;
    }

    [x-cloak] {
        display: none !important;
    }
</style>
