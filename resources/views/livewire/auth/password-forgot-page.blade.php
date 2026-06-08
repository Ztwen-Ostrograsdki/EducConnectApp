<div class="min-h-screen flex flex-col items-center justify-center bg-sky-950  px-4">

    <div class="w-full max-w-md bg-slate-900 rounded-2xl space-y-4 shadow-lg p-8">

        {{-- STEP 1 --}}
        @if ($step === 1)
            <div class="login-card w-full rounded-2xl space-y-4 p-3">

                <h2 class="text-xl font-bold">Mot de passe oublié</h2>

                <input type="email" wire:model="email" class="w-full h-12 bg-slate-800 border border-slate-700 rounded-xl px-4" placeholder="Email">

                @error('email')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror

                <button wire:click="sendReset" type="submit" wire:loading.attr="disabled" class="p-3 rounded-2xl w-full flex items-center justify-center cursor-pointer bg-sky-600 hover:bg-sky-800">
                    <span class="flex items-center gap-1.5" wire:target='sendReset' wire:loading.remove>
                        <span>Recevoir code</span>
                        <x-lucide-send class="w-5 h-5" />
                    </span>
                    <span wire:target='sendReset' wire:loading.flex class="items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                        <span>En cours...</span>
                    </span>
                </button>
            </div>
        @endif

        {{-- STEP 2 --}}
        @if ($step === 2)
            <div class="login-card w-full rounded-2xl space-y-4 p-3">

                <h2 class="text-xl font-bold">Vérification du code</h2>

                <input type="text" wire:model="otp" class="w-full h-12 bg-slate-800 border border-slate-700 rounded-xl px-4" placeholder="Code OTP">

                @error('otp')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror

                <button wire:click="verifyOtp" type="submit" wire:loading.attr="disabled" class="p-3 rounded-2xl w-full flex items-center justify-center cursor-pointer bg-sky-600 hover:bg-sky-800">
                    <span class="flex items-center gap-1.5" wire:target='verifyOtp' wire:loading.remove>
                        <span> Vérifier code OTP</span>
                        <x-lucide-send class="w-5 h-5" />
                    </span>
                    <span wire:target='verifyOtp' wire:loading.flex class="items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                        <span>Vérification...</span>
                    </span>
                </button>
            </div>
        @endif

        {{-- STEP 3 --}}
        @if ($step === 3)
            <div class="login-card w-full rounded-2xl space-y-4 p-3">

                <h2 class="text-xl font-bold">Nouveau mot de passe</h2>

                <input type="password" wire:model="password" class="w-full h-12 bg-slate-800 border border-slate-700 rounded-xl px-4" placeholder="Nouveau mot de passe">

                <input type="password" wire:model="password_confirmation" class="w-full h-12 bg-slate-800 border border-slate-700 rounded-xl px-4" placeholder="Confirmer mot de passe">

                @error('password')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror

                <button wire:click="resetPassword" type="submit" wire:loading.attr="disabled" class="p-3 rounded-2xl w-full flex items-center justify-center cursor-pointer bg-sky-600 hover:bg-sky-800">
                    <span class="flex items-center gap-1.5" wire:target='resetPassword' wire:loading.remove>
                        <span> Modifier le mot de passe</span>
                        <x-lucide-send class="w-5 h-5" />
                    </span>
                    <span wire:target='resetPassword' wire:loading.flex class="items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                        <span>En cours...</span>
                    </span>
                </button>
            </div>
        @endif
        <div class="flex justify-center my-2.5 p-2 items-center">
            <a class="text-amber-500 hover:text-amber-700 hover:underline" href="{{ route('login') }}">Je me souviens de mon mot de passe</a>
        </div>
    </div>

</div>

