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

                        <img src="https://i.pravatar.cc/250?img=15" alt=""
                            class="w-40 h-40
                               rounded-full
                               object-cover
                               border-4
                               border-slate-700">

                        <button class="absolute bottom-2 right-2
                               w-12 h-12 rounded-full
                               bg-indigo-500
                               flex items-center justify-center">

                            <x-lucide-camera class="w-5 h-5" />

                        </button>

                    </div>

                </div>

                {{-- INFOS --}}
                <div class="flex-1">

                    <div class="inline-flex items-center gap-2
                           px-3 py-1 rounded-full
                           bg-emerald-500/10
                           text-emerald-400">

                        <x-lucide-circle-check class="w-4 h-4" />

                        Compte Actif

                    </div>

                    <h1 class="mt-4 text-4xl
                           font-black">

                        Vincent HOUNDEKINDO

                    </h1>

                    <p class="text-slate-400 mt-2">
                        Administrateur • Enseignant • Parent
                    </p>

                    <div class="mt-6
                           grid md:grid-cols-2
                           xl:grid-cols-4 gap-4">

                        <div>

                            <p class="text-xs text-slate-500">
                                Email
                            </p>

                            <p>
                                vincent@mail.com
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">
                                Téléphone
                            </p>

                            <p>
                                +229 01 97 00 00 00
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">
                                Genre
                            </p>

                            <p>
                                Masculin
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">
                                Adresse
                            </p>

                            <p>
                                Cotonou
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

        @foreach ([['Rôles', '4', 'shield'], ['Permissions', '38', 'key-round'], ['Connexions', '124', 'activity'], ['Notifications', '12', 'bell']] as $card)
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
                    Vincent HOUNDEKINDO
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Date naissance
                </label>

                <p class="mt-2">
                    15/08/1998
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Nationalité
                </label>

                <p class="mt-2">
                    Béninoise
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Adresse
                </label>

                <p class="mt-2">
                    Cotonou
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Téléphone
                </label>

                <p class="mt-2">
                    +229 XX XX XX XX
                </p>
            </div>

            <div>
                <label class="text-slate-500 text-sm">
                    Inscription
                </label>

                <p class="mt-2">
                    12 Mars 2026
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

                <span class="badge p-2 bg-purple-400 text-purple-950 rounded-2xl">
                    Directeur
                </span>

                <span class="badge p-2 bg-purple-400 text-purple-950 rounded-2xl">
                    Enseignant
                </span>

                <span class="badge p-2 bg-purple-400 text-purple-950 rounded-2xl">
                    Parent
                </span>

                <span class="badge p-2 bg-purple-400 text-purple-950 rounded-2xl">
                    Administrateur
                </span>

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

                @foreach (range(1, 8) as $i)
                    <span class="flex justify-center items-center p-2 bg-green-400 text-green-950 rounded-2xl">
                        <span>
                            Permission {{ $i }}
                        </span>
                    </span>
                @endforeach

            </div>

        </div>

    </section>
    <section class="rounded-3xl
           border border-slate-800
           bg-slate-900/80 p-6">

        <h2 class="text-xl font-bold">
            Actions rapides
        </h2>

        <div class="mt-6
               flex flex-wrap gap-4">

            <button class="h-12 px-5 rounded-2xl
                   bg-indigo-500
                   text-white
                   flex items-center gap-2">

                <x-lucide-user-pen class="w-5 h-5" />

                Modifier le profil

            </button>

            <button class="h-12 px-5 rounded-2xl
                   bg-sky-500/10
                   text-sky-400
                   flex items-center gap-2">

                <x-lucide-camera class="w-5 h-5" />

                Modifier la photo

            </button>

            <button class="h-12 px-5 rounded-2xl
                   bg-amber-500/10
                   text-amber-400
                   flex items-center gap-2">

                <x-lucide-key-round class="w-5 h-5" />

                Changer le mot de passe

            </button>

            <button class="h-12 px-5 rounded-2xl
                   bg-rose-500/10
                   text-rose-400
                   flex items-center gap-2">

                <x-lucide-log-out class="w-5 h-5" />

                Déconnexion

            </button>

        </div>

    </section>
</div>

