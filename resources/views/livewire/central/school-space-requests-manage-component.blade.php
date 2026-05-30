<div class="space-y-6 p-3">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl border border-slate-800
               bg-slate-900/80 p-5 sm:p-6">

        <div class="flex flex-col
                   xl:flex-row xl:items-center
                   xl:justify-between gap-6">

            {{-- LEFT --}}
            <div class="min-w-0">

                <div
                    class="inline-flex items-center gap-2
                           px-3 py-1 rounded-full
                           bg-indigo-500/10
                           border border-indigo-500/20
                           text-indigo-300 text-xs font-medium">

                    <x-lucide-credit-card class="w-4 h-4" />

                    Gestion des demandes d'espace école

                </div>

                <h1 class="mt-4 text-2xl sm:text-4xl
                           font-sans tracking-tight">

                    Demandes d’espace école

                </h1>

                <p class="mt-3 text-sm sm:text-base
                           text-slate-400 max-w-3xl">
                    Gérez les demandes d'espace école
                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row
                       gap-3 w-full xl:w-auto">

                <button class="h-12 px-5 rounded-2xl
                           bg-emerald-500 hover:bg-emerald-400
                           text-white font-semibold
                           flex items-center justify-center gap-2">

                    <x-lucide-check-check class="w-5 h-5" />

                    Tout approuver

                </button>

                <button
                    class="h-12 px-5 rounded-2xl
                           bg-rose-500/10
                           hover:bg-rose-500/20
                           text-rose-400 font-semibold
                           border border-rose-500/20
                           flex items-center justify-center gap-2">

                    <x-lucide-trash-2 class="w-5 h-5" />

                    Tout supprimer

                </button>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- FILTERS --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl border border-slate-800
               bg-slate-900/80 p-5 sm:p-6">

        <div class="flex flex-col
                   2xl:items-center
                   gap-4">

            {{-- SEARCH --}}
            <div class="relative w-full">
                <x-lucide-search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500" />
                <input wire:model.lazy='search' type="text" placeholder="Filtrer les demandes..."
                    class="w-full h-12 rounded-2xl bg-slate-800 border border-slate-700 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
            </div>

            {{-- SELECTS --}}
            <div class="grid grid-cols-1
                       sm:grid-cols-2
                       xl:grid-cols-5 gap-3
                       w-full">

                <select class="h-12 px-4 rounded-2xl
                           bg-slate-800 border border-slate-700
                           text-sm">
                    <option value="">Tous les statuts</option>
                    @foreach ($tenant_request_statuses as $stat)
                        <option value="{{ $stat }}">{{ $stat }}</option>
                    @endforeach
                </select>
                <select class="h-12 px-4 rounded-2xl bg-slate-800 border border-slate-700 text-sm">
                    <option>Tous les types d'écoles</option>
                    @foreach ($school_types as $sch)
                        <option value="{{ $sch }}">{{ $sch }}</option>
                    @endforeach
                </select>
                <select class="h-12 px-4 rounded-2xl bg-slate-800 border border-slate-700 text-sm">
                    <option>Tous les enseignements</option>
                    @foreach ($enseignement_types as $ens)
                        <option value="{{ $ens }}">{{ $ens }}</option>
                    @endforeach
                </select>
                <select class="h-12 px-4 rounded-2xl bg-slate-800 border border-slate-700 text-sm">
                    <option>Tous les types de période</option>
                    @foreach ($periode_types as $pert)
                        <option value="{{ $pert }}">{{ $pert }}</option>
                    @endforeach
                </select>
                <button class="h-12 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-400 text-white font-semibold flex items-center justify-center gap-2">
                    <x-lucide-filter class="w-4 h-4" />
                    Filtrer
                </button>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-800
               bg-slate-900/80 overflow-hidden p-2">
        {{-- HEADER --}}
        <div class="p-5 sm:p-6 border-b border-slate-800 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">
                    Liste des demandes
                </h2>
                <p class="mt-1 text-sm text-slate-400">
                    Validation et supervision des abonnements
                </p>
            </div>

            {{-- EXPORT --}}
            <div class="flex flex-wrap gap-3">
                <button class="h-11 px-4 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 flex items-center gap-2">

                    <x-lucide-file-spreadsheet class="w-4 h-4" />

                    Excel
                </button>
                <button class="h-11 px-4 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 flex items-center gap-2">
                    <x-lucide-file-text class="w-4 h-4" />
                    PDF
                </button>
            </div>
        </div>
        {{-- TABLE --}}
        @if (count($demandes_requests))
            <div class="overflow-x-auto p-2">

                <div class="grid grid-cols-1
               lg:grid-cols-2
               2xl:grid-cols-2 gap-6">

                    @foreach ($demandes_requests as $item)
                        <div
                            class="group relative overflow-hidden
                       rounded-3xl border border-slate-800
                       bg-slate-900/80
                       hover:border-indigo-500/30
                       transition-all duration-300">

                            {{-- TOP BAR --}}
                            <div class="h-1 w-full
                           bg-gradient-to-r
                           from-indigo-500
                           via-sky-500
                           to-cyan-400">

                            </div>

                            {{-- BG ICON --}}
                            <div class="absolute -right-6 -top-6
                           opacity-5 group-hover:opacity-10
                           transition-all duration-300">

                                <x-lucide-school class="w-36 h-36" />

                            </div>

                            <div class="relative p-5 sm:p-6">

                                {{-- SCHOOL --}}
                                <div class="flex flex-row-reverse items-center justify-between gap-4">

                                    <div class="flex items-center flex-row-reverse gap-4 min-w-0">

                                        {{-- LOGO --}}
                                        <div
                                            class="w-16 h-16 rounded-2xl
                                       bg-indigo-500/10
                                       border border-indigo-500/20
                                       flex items-center justify-center
                                       shrink-0">

                                            <x-lucide-school class="w-8 h-8 text-indigo-400" />

                                        </div>

                                        {{-- INFOS --}}
                                        <div class="min-w-0">

                                            <h3 class="text-lg font-bold truncate">

                                                {{ $item->school_name }}

                                            </h3>

                                            <p class="text-sm text-slate-400 truncate">

                                                {{ $item->enseignement_type }}

                                            </p>

                                            <div
                                                class="mt-2 inline-flex items-center
                                           gap-2 px-3 py-1 rounded-full
                                           bg-emerald-500/10
                                           text-emerald-400
                                           text-xs font-semibold w-full text-center">

                                                <span class="w-2 h-2 rounded-full
                                               bg-emerald-400">

                                                </span>

                                                {{ $item->school_type }}

                                            </div>

                                        </div>

                                    </div>

                                    {{-- ITERATION --}}
                                    <div class="shrink-0 text-right">
                                        <p class="mt-1 text-sm font-black
                                       text-emerald-400 flex items-center gap-2 bg-slate-800 shadow-md shadow-green-600 rounded-2xl p-2">
                                            N°
                                            {{ __zero($loop->iteration) }}
                                        </p>
                                    </div>

                                </div>

                                {{-- DIRECTOR --}}
                                <div class="mt-6 rounded-2xl
                               border border-slate-800
                               bg-slate-950/40 p-4">

                                    <div class="flex items-center gap-3">

                                        <div class="w-12 h-12 rounded-2xl
                                       bg-slate-800
                                       flex items-center justify-center">

                                            <x-lucide-user class="w-5 h-5 text-slate-300" />

                                        </div>

                                        <div class="min-w-0">

                                            <p class="font-semibold truncate">

                                                {{ $item->name }} {{ $item->prenames }}

                                            </p>

                                            <p class="text-xs text-slate-500">

                                                Directeur Général

                                            </p>

                                        </div>

                                    </div>

                                    {{-- CONTACTS --}}
                                    <div class="mt-4 grid grid-cols-1 gap-3">

                                        <div class="flex items-center gap-3
                                       text-sm text-slate-300">

                                            <x-lucide-mail class="w-4 h-4 text-slate-500 shrink-0" />

                                            <span class="truncate">
                                                {{ $item->email }}
                                            </span>

                                        </div>

                                        <div class="flex items-center gap-3
                                       text-sm text-slate-300">

                                            <x-lucide-phone class="w-4 h-4 text-slate-500 shrink-0" />

                                            {{ $item->contacts }}

                                        </div>

                                        <div class="flex items-center gap-3
                                       text-sm text-slate-300">

                                            <x-lucide-map-pin class="w-4 h-4 text-slate-500 shrink-0" />

                                            {{ $item->adresse }}, {{ $item->country }}

                                        </div>

                                    </div>

                                </div>

                                <div class="mt-5 rounded-2xl
                               border border-sky-500/20
                               bg-sky-500/5 p-4">

                                    <div class="flex items-center justify-between gap-3">
                                        <div class="felx flex-col justify-center w-full">
                                            <p class="text-lg flex items-center gap-2 text-slate-400 border-b border-b-slate-700 w-full">
                                                <x-lucide-pen class="w-5 h-5" />
                                                <span>Détails école</span>
                                            </p>
                                            <ul class="flex flex-col gap-2 text-slate-500 mt-2.5">
                                                <li class="flex items-center gap-1.5">
                                                    <x-lucide-circle-check class="w-5 h-5 text-green-700" />
                                                    <span>
                                                        <span class="text-green-700">Nom de l'école : </span>
                                                        <span>{{ $item->school_name }}</span>
                                                    </span>
                                                </li>
                                                <li class="flex items-center gap-1.5">
                                                    <x-lucide-circle-check class="w-5 h-5 text-green-700" />
                                                    <span>
                                                        <span class="text-green-700">Nom abr. : </span>
                                                        <span>{{ $item->simple_name }}</span>
                                                    </span>
                                                </li>
                                                <li class="flex items-center gap-1.5">
                                                    <x-lucide-circle-check class="w-5 h-5 text-green-700" />
                                                    <span>
                                                        <span class="text-green-700">Nom de domaine : </span>
                                                        <span>{{ $item->domain_name }}</span>
                                                    </span>
                                                </li>
                                                <li class="flex items-center gap-1.5">
                                                    <x-lucide-circle-check class="w-5 h-5 text-green-700" />
                                                    <span>
                                                        <span class="text-green-700">Type école : </span>
                                                        <span>{{ $item->school_type }}</span>
                                                    </span>
                                                </li>
                                                <li class="flex items-center gap-1.5">
                                                    <x-lucide-circle-check class="w-5 h-5 text-green-700" />
                                                    <span>
                                                        <span class="text-green-700">Type Enseignement : </span>
                                                        <span>{{ $item->enseignement_type }}</span>
                                                    </span>
                                                </li>
                                                <li class="flex items-center gap-1.5">
                                                    <x-lucide-circle-check class="w-5 h-5 text-green-700" />
                                                    <span>
                                                        <span class="text-green-700">Type de période : </span>
                                                        <span>{{ $item->periode_type }}</span>
                                                    </span>
                                                </li>
                                                <li class="flex items-center gap-1.5">
                                                    <x-lucide-circle-check class="w-5 h-5 text-green-700" />
                                                    <span>
                                                        <span class="text-green-700">Type Devoirs : </span>
                                                        <span>{{ $item->devoirs_type }}</span>
                                                    </span>
                                                </li>
                                                <li class="flex items-center gap-1.5">
                                                    <x-lucide-circle-check class="w-5 h-5 text-green-700" />
                                                    <span>
                                                        <span class="text-green-700">Dévise école : </span>
                                                        <span>{{ $item->school_devise }}</span>
                                                    </span>
                                                </li>

                                            </ul>

                                        </div>
                                    </div>
                                </div>
                                {{-- ACTIONS --}}
                                <div class="mt-6 flex flex-wrap gap-3">

                                    <button wire:key="demande-send-request-{{ $item->domain_name }}" wire:click="mailBuilder('{{ $item->domain_name }}')" wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-gray-500/10 hover:bg-gray-500/20 text-gray-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="mailBuilder">
                                            <x-lucide-message-square class="w-4 h-4" />
                                            Envoyer données
                                        </span>
                                        <span wire:loading.flex wire:target="mailBuilder" class="items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>

                                    @if (!$item->validated)
                                        <button wire:key="demande-val-request-{{ $item->id }}" wire:click="validateRequest('{{ $item->id }}')" wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-green-500/10 hover:bg-green-500/20 text-green-400 ">
                                            <span wire:loading.remove class="flex items-center gap-1.5" wire:target="validateRequest">
                                                <x-lucide-user-check class="w-4 h-4" />
                                                Accepter
                                            </span>
                                            <span wire:loading.flex wire:target="validateRequest" class="items-center gap-1.5">
                                                <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                <span>En cours...</span>
                                            </span>
                                        </button>
                                    @endif

                                </div>

                                {{-- ACTIONS 2 --}}
                                <div class="mt-3 grid grid-cols-2 gap-3">

                                    {{-- SUSPEND --}}
                                    <button
                                        class="flex-1 min-w-[120px]
                                   h-11 rounded-2xl
                                   bg-amber-500/10
                                   hover:bg-amber-500/20
                                   text-amber-400
                                   flex items-center justify-center gap-2
                                   transition-all duration-200">

                                        <x-lucide-ban class="w-5 h-5" />
                                        Rejeter

                                    </button>

                                    {{-- DELETE --}}
                                    <button
                                        class="flex-1 min-w-[120px]
                                   h-11 rounded-2xl
                                   bg-red-500/10
                                   hover:bg-red-500/20
                                   text-red-400
                                   flex items-center justify-center gap-2
                                   transition-all duration-200">

                                        <x-lucide-trash-2 class="w-5 h-5" />
                                        Supprimer

                                    </button>

                                </div>

                            </div>
                            <div class="my-1 border-t border-t-slate-800 p-2 flex items-center w-full">
                                <h4 class="flex gap-x-2.2 items-center text-slate-500">
                                    <x-lucide-calendar-check class="w-5 h-5" />
                                    <span>
                                        Demande envoyée le {{ __formatDateTime($item->created_at) }}
                                    </span>
                                </h4>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

            {{-- PAGINATION --}}
            <div class="p-5 sm:p-6 border-t border-slate-800
                   flex flex-col sm:flex-row
                   sm:items-center sm:justify-between gap-4">

                <p class="text-sm text-slate-400">
                    Affichage de 1 à 10 sur 128 demandes
                </p>

                <div class="flex items-center gap-2">

                    <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700
                           flex items-center justify-center">

                        <x-lucide-chevron-left class="w-4 h-4" />

                    </button>

                    <button class="w-10 h-10 rounded-xl
                           bg-indigo-500 text-white font-semibold">
                        1
                    </button>

                    <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700">
                        2
                    </button>

                    <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700">
                        3
                    </button>

                    <button class="w-10 h-10 rounded-xl
                           border border-slate-700
                           bg-slate-800 hover:bg-slate-700
                           flex items-center justify-center">

                        <x-lucide-chevron-right class="w-4 h-4" />

                    </button>

                </div>

            </div>
        @else
            <div class="w-full p-2 rounded-2xl bg-slate-400/35 text-gray-900 my-5">
                <h5 class="text-lg text-center font-semibold">
                    <span>
                        Oupps aucune données trouvées!
                    </span>
                </h5>
            </div>
        @endif

    </section>

</div>

