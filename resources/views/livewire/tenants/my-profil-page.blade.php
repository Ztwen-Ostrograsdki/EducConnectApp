<div class="mx-auto w-full max-w-[1900px] flex flex-col gap-6 pb-24">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden rounded-[2rem] border border-white/5">
        {{-- Fond dégradé principal --}}
        <div class="absolute inset-0 bg-gradient-to-br from-[#0f172a] via-[#0b1120] to-[#020617]"></div>

        {{-- Orbes de lumière --}}
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-violet-600/20 blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-[400px] h-[400px] rounded-full bg-cyan-500/15 blur-[100px]"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-indigo-500/10 blur-[140px]">
        </div>

        <div class="relative p-7 sm:p-10">
            <div class="flex flex-col xl:flex-row items-center gap-10">

                {{-- PHOTO --}}
                <div class="relative shrink-0">
                    {{-- Anneau dégradé --}}
                    <div
                        class="absolute -inset-[3px] rounded-full bg-gradient-to-tr from-violet-500 via-fuchsia-500 to-cyan-400 opacity-80">
                    </div>
                    <div
                        class="absolute -inset-[6px] rounded-full bg-gradient-to-tr from-violet-500/30 via-transparent to-cyan-400/30 blur-md">
                    </div>

                    <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}" alt="Photo de profil"
                        class="relative w-40 h-40 sm:w-44 sm:h-44 rounded-full object-cover border-[6px] border-[#0b1120]">

                    <a href="{{ route('tenant.update.profil.photo') }}"
                        class="absolute bottom-2 right-2 w-12 h-12 rounded-full bg-gradient-to-br from-violet-600 to-indigo-700 hover:from-violet-500 hover:to-indigo-600 text-white flex items-center justify-center shadow-xl shadow-violet-900/50 transition-all hover:scale-110">
                        <x-lucide-camera class="w-5 h-5" />
                    </a>
                </div>

                {{-- INFOS --}}
                <div class="flex-1 text-center xl:text-left min-w-0">
                    {{-- Badge --}}
                    <div
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-medium border
                        @if (!$user->blocked) bg-emerald-500/10 text-emerald-300 border-emerald-500/25
                        @else
                            bg-rose-500/10 text-rose-300 border-rose-500/25 @endif">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-60
                                @if (!$user->blocked) bg-emerald-400 @else bg-rose-400 @endif"></span>
                            <span
                                class="relative inline-flex h-2 w-2 rounded-full
                                @if (!$user->blocked) bg-emerald-400 @else bg-rose-400 @endif"></span>
                        </span>
                        {{ !$user->blocked ? 'Compte actif' : 'Compte bloqué' }}
                    </div>

                    <h1 class="mt-5 text-4xl sm:text-5xl font-bold tracking-tight">
                        <span class="bg-gradient-to-r from-white via-white to-slate-300 bg-clip-text text-transparent">
                            {{ $user->getFullName(true) }}
                        </span>
                    </h1>

                    {{-- Rôles --}}
                    <div class="mt-4 flex flex-wrap justify-center xl:justify-start gap-2">
                        @foreach ($user->roles as $role)
                            <span
                                class="px-3 py-1 rounded-lg text-xs font-medium
                                       bg-gradient-to-r from-violet-500/15 to-indigo-500/15 
                                       text-violet-200 border border-violet-500/20">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>

                    {{-- Mini cards --}}
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div
                            class="rounded-2xl bg-white/[0.03] border border-white/5 backdrop-blur-sm px-4 py-3.5 hover:bg-white/[0.05] transition-colors">
                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1">Email</p>
                            <p class="text-sm text-slate-100 font-medium truncate">{{ $user->email }}</p>
                        </div>
                        <div
                            class="rounded-2xl bg-white/[0.03] border border-white/5 backdrop-blur-sm px-4 py-3.5 hover:bg-white/[0.05] transition-colors">
                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1">Téléphone</p>
                            <p class="text-sm text-slate-100 font-medium">+229 {{ $user->contacts }}</p>
                        </div>
                        <div
                            class="rounded-2xl bg-white/[0.03] border border-white/5 backdrop-blur-sm px-4 py-3.5 hover:bg-white/[0.05] transition-colors">
                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1">Genre</p>
                            <p class="text-sm text-slate-100 font-medium">{{ $user->gender }}</p>
                        </div>
                        <div
                            class="rounded-2xl bg-white/[0.03] border border-white/5 backdrop-blur-sm px-4 py-3.5 hover:bg-white/[0.05] transition-colors">
                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1">Adresse</p>
                            <p class="text-sm text-slate-100 font-medium truncate">
                                {{ $user->adresse }}@if ($user->country)
                                    , {{ $user->country }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== INFORMATIONS PERSONNELLES ===================== --}}
    <section class="relative overflow-hidden rounded-[2rem] border border-white/5">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0f172a]/80 to-[#020617]/90"></div>
        <div class="absolute top-0 right-0 w-80 h-80 bg-violet-600/10 blur-[100px] rounded-full"></div>

        <div class="relative">
            <div class="px-7 sm:px-9 py-5 border-b border-white/5 flex items-center gap-3">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-600/30 to-indigo-600/20 border border-violet-500/20 text-violet-300">
                    <x-lucide-user class="w-5 h-5" />
                </div>
                <h2 class="text-lg font-semibold text-white">Informations personnelles</h2>
            </div>

            <div class="p-7 sm:p-9 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-x-10 gap-y-8">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1.5">Nom complet</p>
                    <p class="text-base font-medium text-white">{{ $user->getFullName(1) }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1.5">Date de naissance</p>
                    <p class="text-base font-medium text-white">{{ ucwords(__formatDate($user->birth_date)) }}</p>
                    <p class="text-sm text-slate-400 mt-0.5">{{ getAge($user->birth_date) }} ans</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1.5">Nationalité</p>
                    <p class="text-base font-medium text-white">{{ $user->country }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1.5">Adresse</p>
                    <p class="text-base font-medium text-white">{{ $user->adresse }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1.5">Téléphone</p>
                    <p class="text-base font-medium text-white">{{ $user->contacts }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1.5">Date d’inscription</p>
                    <p class="text-base font-medium text-white">{{ __formatDateTime($user->created_at) }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== ACTIONS RAPIDES ===================== --}}
    <section class="relative overflow-hidden rounded-[2rem] border border-white/5">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0f172a] to-[#020617]"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-cyan-500/10 blur-[90px] rounded-full"></div>

        <div class="relative p-7 sm:p-9">
            <div class="flex items-center gap-3 mb-7">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500/25 to-sky-600/15 border border-cyan-500/20 text-cyan-300">
                    <x-lucide-zap class="w-5 h-5" />
                </div>
                <h2 class="text-lg font-semibold text-white">Actions rapides</h2>
            </div>

            <div class="flex flex-wrap gap-3">
                {{-- Modifier profil --}}
                <button
                    class="h-12 px-6 rounded-xl text-sm font-medium text-white
                               bg-gradient-to-r from-violet-600 to-indigo-600 
                               hover:from-violet-500 hover:to-indigo-500
                               shadow-lg shadow-violet-900/40 hover:shadow-violet-800/50
                               flex items-center gap-2.5 transition-all">
                    <x-lucide-user-pen class="w-4.5 h-4.5" />
                    Modifier le profil
                </button>

                {{-- Photo --}}
                <a href="{{ route('tenant.update.profil.photo') }}"
                    class="h-12 px-5 rounded-xl text-sm font-medium
                          bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/20 text-sky-300
                          flex items-center gap-2.5 transition-all">
                    <x-lucide-camera class="w-4.5 h-4.5" />
                    Modifier la photo
                </a>

                {{-- Mot de passe --}}
                <a href="{{ route('tenant.update.password') }}"
                    class="h-12 px-5 rounded-xl text-sm font-medium
                          bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-300
                          flex items-center gap-2.5 transition-all">
                    <x-lucide-key-round class="w-4.5 h-4.5" />
                    Changer le mot de passe
                </a>

                {{-- Retirer photo --}}
                <button type="button" wire:loading.attr="disabled"
                    class="h-12 px-5 rounded-xl text-sm font-medium
                               bg-orange-500/10 hover:bg-orange-500/20 border border-orange-500/20 text-orange-300
                               flex items-center gap-2.5 transition-all disabled:opacity-50">
                    <svg wire:loading wire:target="removePhoto" class="w-4.5 h-4.5 animate-spin" fill="none"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                            class="opacity-25" />
                        <path fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                    </svg>
                    <span class="flex items-center gap-2" wire:loading.remove wire:target="removePhoto">
                        <x-lucide-trash class="w-4.5 h-4.5" />
                        Retirer la photo
                    </span>
                    <span wire:loading wire:target="removePhoto">En cours...</span>
                </button>

                {{-- Déconnexion --}}
                <button
                    class="h-12 px-5 rounded-xl text-sm font-medium
                               bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300
                               flex items-center gap-2.5 transition-all">
                    <x-lucide-log-out class="w-4.5 h-4.5" />
                    Déconnexion
                </button>
            </div>
        </div>
    </section>
</div>
