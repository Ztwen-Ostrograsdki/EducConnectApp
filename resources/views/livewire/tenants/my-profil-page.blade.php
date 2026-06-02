<div class="mx-auto
                w-full
                max-w-[1900px]
                 flex flex-col gap-2.5">
    <section class="relative overflow-hidden
           rounded-3xl
           border border-slate-800
           bg-slate-900/80">

        <div class="absolute inset-0
               bg-gradient-to-r
               from-indigo-500/10
               via-sky-500/5
               to-transparent">
        </div>

        <div class="relative p-8">

            <div class="flex flex-col
                   xl:flex-row
                   xl:items-center
                   gap-8">

                {{-- PHOTO --}}
                <div class="flex justify-center">

                    <div class="relative">

                        <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}" alt=""
                            class="w-40 h-40
                               rounded-full
                               object-cover
                               border-4
                               border-slate-700">

                        <a href="{{ route('tenant.update.profil.photo') }}"
                            class="absolute bottom-2 right-2
                               w-12 h-12 rounded-full
                               bg-indigo-500
                               flex items-center justify-center">

                            <x-lucide-camera class="w-5 h-5" />

                        </a>

                    </div>

                </div>

                {{-- INFOS --}}
                <div class="flex-1">

                    <div class="inline-flex items-center gap-2
                           px-3 py-1 rounded-full
                           bg-emerald-500/10
                           text-emerald-400">

                        <x-lucide-circle-check class="w-4 h-4" />

                        @if (!$user->blocked)
                            Compte actif
                        @else
                            Compte bloqué
                        @endif

                    </div>

                    <h1 class="mt-4 text-4xl
                           font-black">

                        {{ $user->getFullName(true) }}

                    </h1>

                    <p class="text-slate-400 mt-2 flex flex-wrap gap-x-2">
                        @foreach ($user->roles as $role)
                            <span class="px-2 py-1 rounded bg-indigo-500/10 text-indigo-400">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </p>

                    <div class="mt-6
                           grid md:grid-cols-2
                           xl:grid-cols-4 gap-4">

                        <div>

                            <p class="text-xs text-slate-500">
                                Email
                            </p>

                            <p>
                                {{ $user->email }}
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">
                                Téléphone
                            </p>

                            <p>
                                +229 {{ $user->contacts }}
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">
                                Genre
                            </p>

                            <p>
                                {{ $user->gender }}
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">
                                Adresse
                            </p>

                            <p>
                                {{ $user->adresse }} , {{ $user->country }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
    <section class="grid
           sm:grid-cols-2
           xl:grid-cols-4
           gap-5">

        @foreach ([['Rôles', __zero(count($user->roles)), 'shield'], ['Permissions', __zero(count($user->getAllPermissions())), 'key-round'], ['Connexions', __zero($user->logged_count), 'activity'], ['Notifications', 0, 'bell']] as $card)
            <div class="rounded-3xl
                   border border-slate-800
                   bg-slate-900/80
                   p-5">

                <div class="flex items-center
                       justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            {{ $card[0] }}
                        </p>

                        <h3 class="mt-2
                               text-3xl
                               font-black">

                            {{ $card[1] }}

                        </h3>

                    </div>

                    <div class="w-14 h-14
                           rounded-2xl
                           bg-indigo-500/10
                           flex items-center
                           justify-center">

                        <x-lucide-shield class="w-7 h-7 text-indigo-400" />

                    </div>

                </div>

            </div>
        @endforeach

    </section>
    <section class="rounded-3xl
           border border-slate-800
           bg-slate-900/80">

        <div class="p-6 border-b border-slate-800">

            <h2 class="text-xl font-bold">
                Informations personnelles
            </h2>

        </div>

        <div class="p-6
               grid md:grid-cols-2
               xl:grid-cols-3 gap-6">

            <div>
                <label class="text-slate-500 text-sm">
                    Nom complet
                </label>

                <p class="mt-2 font-medium">
                    {{ $user->getFullName(1) }}
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Date naissance
                </label>

                <p class="mt-2">
                    ---
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Nationalité
                </label>

                <p class="mt-2">
                    {{ $user->country }}
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Adresse
                </label>

                <p class="mt-2">
                    {{ $user->adresse }}
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Téléphone
                </label>

                <p class="mt-2">
                    {{ $user->contacts }}
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Inscription
                </label>

                <p class="mt-2">
                    {{ __formatDateTime($user->created_at) }}
                </p>
            </div>

        </div>

    </section>
    <section class="grid xl:grid-cols-2 gap-6">

        <div class="rounded-3xl
               border border-slate-800
               bg-slate-900/80 p-6">

            <h2 class="text-xl font-bold">
                Rôles
            </h2>

            <div class="mt-5 flex flex-wrap gap-3">

                @foreach ($user->roles as $rl)
                    <span class="badge p-2 bg-purple-400 text-purple-950 rounded-2xl">
                        {{ $rl->name }}
                    </span>
                @endforeach

                @if ($user->hasRole('directeur'))
                    <span class="badge p-2 bg-purple-400 text-purple-950 rounded-2xl">
                        {{ 'Administrateur du domaine' }}
                    </span>
                @endif

            </div>

        </div>

        <div class="rounded-3xl
               border border-slate-800
               bg-slate-900/80 p-6">

            <h2 class="text-xl font-bold">
                Permissions
            </h2>

            <div class="mt-5 gap-2 flex items-center
                           flex-wrap">
                @if ($user->hasRole('directeur'))
                    <span class="flex w-full justify-center items-center p-2 bg-green-400 text-green-950 rounded-2xl">
                        <span>
                            Toutes les Permissions
                        </span>
                    </span>
                @else
                    @foreach ($user->getAllPermissions() as $p)
                        <span class="flex justify-center items-center p-2 bg-green-400 text-green-950 rounded-2xl">
                            <span>
                                {{ $p->name }}
                            </span>
                        </span>
                    @endforeach

                @endif

            </div>

        </div>

    </section>
    <section class="rounded-3xl
           border border-slate-800
           bg-slate-900/80 p-6 mb-32">

        <h2 class="text-xl font-bold">
            Actions rapides
        </h2>

        <div class="mt-6
               flex flex-wrap gap-4">

            <button class="h-12 px-5 rounded-2xl
                   bg-indigo-500
                   text-white
                   flex items-center gap-2 hover:bg-indigo-900">
                <x-lucide-user-pen class="w-5 h-5" />
                Modifier le profil
            </button>

            <a href="{{ route('tenant.update.profil.photo') }}" class="h-12 px-5 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center gap-2 hover:bg-sky-800">
                <x-lucide-camera class="w-5 h-5" />
                Modifier la photo
            </a>

            <a href="{{ route('tenant.update.password') }}" class="h-12 px-5 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center gap-2 hover:bg-amber-800 hover:text-white">
                <x-lucide-key-round class="w-5 h-5" />
                Changer le mot de passe
            </a>

            <button type="button" wire:loading.attr="disabled" class="h-12 px-5 rounded-2xl bg-orange-500/10 text-orange-400 flex items-center gap-2 hover:bg-orange-800 hover:text-white">

                <svg wire:loading wire:target="removePhoto" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25" />
                    <path fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                </svg>

                <span class="flex items-center gap-x-1" wire:loading.remove wire:target="removePhoto">
                    <x-lucide-trash class="w-5 h-5" />
                    Retirer photo de profil
                </span>
                <span wire:loading wire:target="removePhoto">
                    En cours...
                </span>
            </button>
            <button class="h-12 px-5 rounded-2xl bg-rose-500/10 hover:bg-rose-700 text-rose-400 flex items-center gap-2">
                <x-lucide-log-out class="w-5 h-5" />
                Déconnexion
            </button>

        </div>

    </section>
</div>

