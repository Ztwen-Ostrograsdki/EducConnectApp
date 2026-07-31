<div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-[#0c0f14]">

    {{-- Background --}}
    <div class="absolute inset-0">
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-sky-900/20 via-[#0c0f14] to-[#0c0f14]">
        </div>
        <div
            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[500px] h-[300px] rounded-full bg-sky-600/10 blur-[100px]">
        </div>
    </div>

    <div class="relative w-full max-w-[420px] mx-auto px-4 py-12">

        {{-- Progress steps --}}
        <div class="flex items-center justify-center gap-2 mb-8">
            @foreach ([1 => 'Email', 2 => 'Code', 3 => 'Mot de passe'] as $n => $label)
                <div class="flex items-center gap-2">
                    <div
                        class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold transition-all
                                {{ $step === $n
                                    ? 'bg-sky-500 text-white shadow-lg shadow-sky-900/40'
                                    : ($step > $n
                                        ? 'bg-sky-500/20 text-sky-400 border border-sky-500/30'
                                        : 'bg-white/5 text-slate-600 border border-white/10') }}">
                        @if ($step > $n)
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            {{ $n }}
                        @endif
                    </div>
                    @if ($n < 3)
                        <div class="w-8 h-px {{ $step > $n ? 'bg-sky-500/50' : 'bg-white/10' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Card --}}
        <div class="rounded-2xl bg-[#141a24] border border-white/[0.06] shadow-2xl shadow-black/30 overflow-hidden">
            <div class="p-7 sm:p-8">

                {{-- ═══ STEP 1 : Email ═══ --}}
                @if ($step === 1)
                    <div class="text-center mb-7">
                        <div
                            class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-sky-500/10 border border-sky-500/20 mb-4">
                            <svg class="w-6 h-6 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Mot de passe oublié</h2>
                        <p class="mt-1.5 text-sm text-slate-500">
                            Entrez votre email pour recevoir un code de vérification
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Adresse email
                            </label>
                            <input type="email" wire:model="email" placeholder="votre@email.com"
                                class="w-full h-12 rounded-xl bg-[#0c0f14] border border-white/10 px-4 text-sm text-slate-200 placeholder:text-slate-600
                                          focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30 transition-all
                                          @error('email') border-rose-500/50 @enderror">
                            @error('email')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button wire:click="sendReset" wire:loading.attr="disabled"
                            class="w-full h-12 rounded-xl bg-sky-600 hover:bg-sky-500 text-white text-sm font-semibold
                                       transition-all active:scale-[0.98] disabled:opacity-50
                                       flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="sendReset" class="inline-flex items-center gap-2">
                                Recevoir le code
                                <x-lucide-send class="w-4 h-4" />
                            </span>
                            <span wire:loading wire:target="sendReset" class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                Envoi…
                            </span>
                        </button>
                    </div>
                @endif

                {{-- ═══ STEP 2 : OTP ═══ --}}
                @if ($step === 2)
                    <div class="text-center mb-7">
                        <div
                            class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-violet-500/10 border border-violet-500/20 mb-4">
                            <svg class="w-6 h-6 text-violet-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Vérification</h2>
                        <p class="mt-1.5 text-sm text-slate-500">
                            Saisissez le code reçu par email
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Code OTP
                            </label>
                            <input type="text" wire:model="otp" placeholder="• • • • • •" maxlength="8"
                                class="w-full h-14 rounded-xl bg-[#0c0f14] border border-white/10 px-4 text-center text-xl font-mono tracking-[0.4em] text-slate-200 placeholder:text-slate-700 placeholder:tracking-[0.3em]
                                          focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all
                                          @error('otp') border-rose-500/50 @enderror">
                            @error('otp')
                                <p class="mt-1.5 text-xs text-rose-400 text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        <button wire:click="verifyOtp" wire:loading.attr="disabled"
                            class="w-full h-12 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold
                                       transition-all active:scale-[0.98] disabled:opacity-50
                                       flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="verifyOtp" class="inline-flex items-center gap-2">
                                Vérifier le code
                                <x-lucide-shield-check class="w-4 h-4" />
                            </span>
                            <span wire:loading wire:target="verifyOtp" class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                Vérification…
                            </span>
                        </button>
                    </div>
                @endif

                {{-- ═══ STEP 3 : Nouveau mot de passe ═══ --}}
                @if ($step === 3)
                    <div class="text-center mb-7">
                        <div
                            class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 mb-4">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Nouveau mot de passe</h2>
                        <p class="mt-1.5 text-sm text-slate-500">
                            Choisissez un mot de passe sécurisé
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Nouveau mot de passe
                            </label>
                            <input type="password" wire:model="password" placeholder="••••••••"
                                class="w-full h-12 rounded-xl bg-[#0c0f14] border border-white/10 px-4 text-sm text-slate-200 placeholder:text-slate-600
                                          focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30 transition-all
                                          @error('password') border-rose-500/50 @enderror">
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Confirmer
                            </label>
                            <input type="password" wire:model="password_confirmation" placeholder="••••••••"
                                class="w-full h-12 rounded-xl bg-[#0c0f14] border border-white/10 px-4 text-sm text-slate-200 placeholder:text-slate-600
                                          focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                        </div>

                        @error('password')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror

                        <button wire:click="resetPassword" wire:loading.attr="disabled"
                            class="w-full h-12 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold
                                       transition-all active:scale-[0.98] disabled:opacity-50
                                       flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="resetPassword"
                                class="inline-flex items-center gap-2">
                                Modifier le mot de passe
                                <x-lucide-check class="w-4 h-4" />
                            </span>
                            <span wire:loading wire:target="resetPassword" class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                En cours…
                            </span>
                        </button>
                    </div>
                @endif

            </div>
        </div>

        {{-- Back to login --}}
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-sky-400 transition-all hover:-translate-y-1 group">
                <x-lucide-arrow-left class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-all" />
                Je me souviens de mon mot de passe
            </a>
        </div>
    </div>
</div>

