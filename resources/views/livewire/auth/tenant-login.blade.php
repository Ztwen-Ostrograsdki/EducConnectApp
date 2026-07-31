<div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-[#05080f]">

    {{-- Background --}}
    <div class="absolute inset-0">
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/40 via-[#05080f] to-[#05080f]">
        </div>
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] rounded-full bg-indigo-600/15 blur-[140px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] rounded-full bg-violet-600/10 blur-[120px]"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-cyan-600/5 blur-[160px]">
        </div>
        {{-- Subtle grid --}}
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px); background-size: 60px 60px;">
        </div>
    </div>

    <div class="relative w-full max-w-[420px] mx-auto px-4 py-12">

        {{-- Flash messages --}}
        @if (session('abort-error') || session('success') || $errorMessage)
            <div class="mb-5 space-y-2">
                @if (session('abort-error'))
                    <div
                        class="flex items-center gap-3 rounded-xl bg-rose-500/10 border border-rose-500/25 px-4 py-3 text-sm text-rose-300">
                        <x-lucide-circle-alert class="w-4 h-4 shrink-0" />
                        {{ session('abort-error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div
                        class="flex items-center gap-3 rounded-xl bg-emerald-500/10 border border-emerald-500/25 px-4 py-3 text-sm text-emerald-300">
                        <x-lucide-circle-check class="w-4 h-4 shrink-0" />
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errorMessage)
                    <div
                        class="flex items-center gap-3 rounded-xl bg-rose-500/10 border border-rose-500/25 px-4 py-3 text-sm text-rose-300">
                        <x-lucide-circle-alert class="w-4 h-4 shrink-0" />
                        {{ $errorMessage }}
                    </div>
                @endif
            </div>
        @endif

        {{-- Card --}}
        <div class="rounded-3xl bg-[#0f1523]/80 backdrop-blur-2xl border border-white/[0.08] shadow-2xl shadow-black/40 overflow-hidden"
            data-login-card>

            {{-- Top accent line --}}
            <div class="h-1 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-500"></div>

            <div class="p-8 sm:p-10">

                {{-- Brand --}}
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-xl shadow-indigo-900/50 mb-5">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Connexion</h1>
                    <p class="mt-1.5 text-sm text-slate-500">
                        Accédez à votre espace
                        @if (tenant()?->school_name)
                            <span class="text-indigo-400 font-medium">{{ tenant()->school_name }}</span>
                        @else
                            école
                        @endif
                    </p>
                </div>

                {{-- Form --}}
                <form wire:submit.prevent="login" class="space-y-5">

                    {{-- Email --}}
                    <div>
                        <label for="email"
                            class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                            Adresse email
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-600">
                                <x-lucide-mail class="w-4 h-4" />
                            </span>
                            <input id="email" type="email" wire:model="email" autocomplete="email"
                                placeholder="votre@email.com"
                                class="w-full h-12 rounded-xl bg-[#070b14] border border-white/10 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600
                                          focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/30 transition-all
                                          @error('email') border-rose-500/50 bg-rose-500/5 @enderror" />
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                <x-lucide-circle-alert class="w-3 h-3" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password"
                            class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                            Mot de passe
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-600">
                                <x-lucide-lock class="w-4 h-4" />
                            </span>
                            <input id="password" :type="show ? 'text' : 'password'" wire:model="password"
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full h-12 rounded-xl bg-[#070b14] border border-white/10 pl-11 pr-12 text-sm text-slate-200 placeholder:text-slate-600
                                          focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/30 transition-all
                                          @error('password') border-rose-500/50 bg-rose-500/5 @enderror" />
                            <button type="button" @click="show = !show"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-600 hover:text-slate-400 transition">
                                <x-lucide-eye x-show="!show" class="w-4 h-4" />
                                <x-lucide-eye-off x-show="show" class="w-4 h-4" x-cloak />
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                <x-lucide-circle-alert class="w-3 h-3" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input id="remember" type="checkbox" wire:model="remember" class="sr-only peer">
                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-md border border-slate-600 bg-[#070b14] transition-all
                                         peer-checked:bg-indigo-600 peer-checked:border-indigo-600">
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

                    {{-- Submit --}}
                    <button type="submit" wire:loading.attr="disabled"
                        class="group relative w-full h-13 rounded-xl overflow-hidden disabled:opacity-60 disabled:cursor-not-allowed active:scale-[0.98] transition-transform duration-200 mt-2">
                        <span
                            class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-violet-600 to-indigo-600 bg-[length:200%_100%] group-hover:animate-[shimmer_2s_linear_infinite]"></span>
                        <span
                            class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-500 rounded-xl blur-lg opacity-30 group-hover:opacity-50 transition-opacity -z-10"></span>
                        <span
                            class="relative flex items-center justify-center gap-2 h-12 text-white font-semibold text-sm tracking-wide">
                            <span wire:loading.remove wire:target="login" class="inline-flex items-center gap-2">
                                Accéder à votre espace
                                <x-lucide-arrow-right
                                    class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" />
                            </span>
                            <span wire:loading wire:target="login" class="inline-flex items-center gap-2">
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
    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    [x-cloak] {
        display: none !important;
    }
</style>
