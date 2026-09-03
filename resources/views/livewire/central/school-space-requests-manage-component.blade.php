<div class="space-y-6 p-3 mb-20">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <section class="rounded-3xl border border-slate-950
               bg-slate-950 p-5 sm:p-6">

        <div
            class="flex flex-col
                   xl:flex-row xl:items-center
                   xl:justify-between gap-6">

            {{-- LEFT --}}
            <div class="min-w-0">

                <div
                    class="inline-flex items-center gap-2
                           px-3 py-1 rounded-full
                           bg-indigo-950
                           border border-indigo-500/20
                           text-indigo-300 text-xs font-medium">

                    <x-lucide-credit-card class="w-4 h-4" />

                    @if ($status)
                        Gestion des demandes avec le statut <span
                            class="text-orange-500 font-semibold">{{ $status }}</span>
                    @else
                        Gestion de toutes les demandes d'espace école
                    @endif

                </div>

                <h1 class="mt-4 text-2xl sm:text-4xl
                           font-sans tracking-tight">

                    Demandes d’espace école

                </h1>

                <p class="mt-3 text-sm sm:text-base
                           text-slate-400 max-w-3xl">
                    @if ($status)
                        Gérer les demandes avec le statut <span
                            class="text-orange-500 font-semibold">{{ $status }}</span>
                    @else
                        Gérer toutes les demandes d'espace école
                    @endif
                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row
                       gap-3 w-full xl:w-auto">

                <button
                    class="h-12 px-5 rounded-2xl
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
    <section class="rounded-3xl border border-slate-950
               bg-slate-950 p-5 sm:p-6">

        <div class="flex flex-col
                   2xl:items-center
                   gap-4">

            {{-- SEARCH --}}
            <div class="relative w-full">
                <x-lucide-search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500" />
                <input wire:model.live.debounce.600ms="search" type="text" placeholder="Filtrer les demandes..."
                    class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-700 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
            </div>

            {{-- SELECTS --}}
            <div
                class="grid grid-cols-1
                       sm:grid-cols-2
                       xl:grid-cols-5 gap-3
                       w-full">

                <select wire:model.live='status'
                    class="h-12 px-4 rounded-2xl
                           bg-slate-950 border border-slate-700
                           text-sm">
                    <option value="">Tous les statuts</option>
                    @foreach ($tenant_request_statuses as $stat)
                        <option value="{{ $stat }}">{{ $stat }}</option>
                    @endforeach
                </select>
                <select class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-700 text-sm">
                    <option>Tous les types d'écoles</option>
                    @foreach ($school_types as $sch)
                        <option value="{{ $sch }}">{{ $sch }}</option>
                    @endforeach
                </select>
                <select class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-700 text-sm">
                    <option>Tous les enseignements</option>
                    @foreach ($this->enseignement_types as $ens)
                        <option value="{{ $ens }}">{{ $ens }}</option>
                    @endforeach
                </select>
                <button wire:click='clearFilters'
                    class="h-12 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-400 text-white font-semibold flex items-center justify-center gap-2">
                    <x-lucide-filter class="w-4 h-4" />
                    Filtrer
                </button>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-950
               bg-slate-950 overflow-hidden p-2">
        {{-- HEADER --}}
        <div wire:loading wire:target="gender,search,status,clearFilters,previousPage,nextPage,gotoPage"
            class="fixed inset-0 z-[200] flex items-center justify-center bg-[#0b0f19]/70">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="animate-spin w-8 h-8 text-violet-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
                <span class="text-sm font-mono">Chargement…</span>
            </div>
        </div>
        <div
            class="p-5 sm:p-6 border-b border-slate-900 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">
                    @if ($status)
                        Liste des demandes avec le statut <span
                            class="text-orange-500 font-semibold">{{ $status }}</span>
                    @else
                        Liste de toutes les demandes
                    @endif
                </h2>
                <p class="mt-1 text-sm text-slate-400">
                    Validation et supervision des abonnements
                </p>
            </div>

            {{-- EXPORT --}}
            <div class="flex flex-wrap gap-3">
                <button
                    class="h-11 px-4 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 flex items-center gap-2">

                    <x-lucide-file-spreadsheet class="w-4 h-4" />

                    Excel
                </button>
                <button
                    class="h-11 px-4 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 flex items-center gap-2">
                    <x-lucide-file-text class="w-4 h-4" />
                    PDF
                </button>
            </div>
        </div>
        {{-- TABLE --}}
        @if (count($this->demandes_requests))
            <div class="overflow-x-auto p-2">

                <div class="grid grid-cols-1
               lg:grid-cols-2
               2xl:grid-cols-2 gap-6">

                    @foreach ($this->demandes_requests as $item)
                        <div
                            class="group relative overflow-hidden
                       rounded-3xl border border-slate-900
                       bg-slate-950
                       hover:border-indigo-500/30
                       transition-all duration-300">

                            {{-- TOP BAR --}}
                            <div
                                class="h-1 w-full
                           bg-gradient-to-r
                           from-indigo-500
                           via-sky-500
                           to-cyan-400">

                            </div>

                            {{-- BG ICON --}}
                            <div
                                class="absolute -right-6 -top-6
                           opacity-5 group-hover:opacity-10
                           transition-all duration-300">

                                <x-lucide-school class="w-36 h-36" />

                            </div>

                            <div class="relative p-5 sm:p-6">

                                {{-- SCHOOL --}}
                                <div
                                    class="flex flex-row-reverse items-center justify-between gap-4 border-b border-b-slate-800 pb-2">

                                    <div
                                        class="flex items-center flex-row-reverse gap-4 min-w-0 border-l border-l-sky-500 pl-2">

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

                                                <span
                                                    class="w-2 h-2 rounded-full
                                               bg-emerald-400">

                                                </span>

                                                {{ $item->school_type }}

                                            </div>

                                        </div>

                                    </div>

                                    {{-- ITERATION --}}
                                    <div class="shrink-0 text-right">
                                        <p
                                            class="mt-1 text-sm font-black
                                       text-emerald-400 flex items-center gap-2 bg-slate-950 shadow-sm shadow-green-600 rounded-2xl p-2">
                                            N°
                                            {{ __zero($loop->iteration) }}
                                        </p>
                                    </div>

                                </div>

                                {{-- DIRECTOR --}}
                                <div
                                    class="mt-6 rounded-2xl
                               border border-slate-900
                               bg-slate-950/40 p-4">

                                    <div class="flex items-center gap-3 border-b border-b-slate-900 pb-2">

                                        <div
                                            class="w-12 h-12 rounded-2xl
                                       bg-slate-950
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
                                    <div class="mt-4 grid grid-cols-1 gap-3 font-mono">

                                        <div
                                            class="flex items-center gap-3
                                       text-sm text-slate-300">

                                            <x-lucide-mail class="w-4 h-4 text-slate-500 shrink-0" />

                                            <span class="truncate">
                                                {{ $item->email }}
                                            </span>

                                        </div>

                                        <div
                                            class="flex items-center gap-3
                                       text-sm text-slate-300">

                                            <x-lucide-phone class="w-4 h-4 text-slate-500 shrink-0" />

                                            {{ $item->contacts }}

                                        </div>

                                        <div
                                            class="flex items-center gap-3
                                       text-sm text-slate-300">

                                            <x-lucide-map-pin class="w-4 h-4 text-slate-500 shrink-0" />

                                            {{ $item->adresse }}, {{ $item->country }}

                                        </div>

                                    </div>

                                </div>

                                <div
                                    class="mt-5 rounded-2xl
                               border border-sky-500/20
                               bg-sky-500/5 p-4">

                                    <div class="flex items-center justify-between gap-3 text-sm">
                                        <div class="felx flex-col justify-center w-full">
                                            <p
                                                class="text-lg flex items-center gap-2 text-slate-400 border-b border-b-slate-700 w-full">
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

                                    @if ($item->validated)
                                        <button wire:key="demande-send-request-{{ $item->domain_name }}"
                                            wire:click="sendCredentialsToTenant('{{ $item->domain_name }}')"
                                            wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-gray-500/10 hover:bg-gray-500/20 text-gray-400 ">
                                            <span wire:loading.remove class="flex items-center gap-1.5"
                                                wire:target="sendCredentialsToTenant">
                                                <x-lucide-message-square class="w-4 h-4" />
                                                Envoyer données
                                            </span>
                                            <span wire:loading.flex wire:target="sendCredentialsToTenant"
                                                class="items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>
                                    @endif

                                    @if (!$item->validated)
                                        <button wire:key="demande-val-request-{{ $item->id }}"
                                            wire:click="validateRequest('{{ $item->id }}')"
                                            wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-green-500/10 hover:bg-green-500/20 text-green-400 ">
                                            <span wire:loading.remove class="flex items-center gap-1.5"
                                                wire:target="validateRequest">
                                                <x-lucide-user-check class="w-4 h-4" />
                                                Accepter
                                            </span>
                                            <span wire:loading.flex wire:target="validateRequest"
                                                class="items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>
                                    @endif
                                </div>

                                {{-- ACTIONS 2 --}}
                                <div class="mt-3 grid grid-cols-2 gap-3">
                                    <button wire:key="demande-rej-request-{{ $item->id }}"
                                        wire:click="rejectRequest('{{ $item->id }}')"
                                        wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5"
                                            wire:target="rejectRequest">
                                            <x-lucide-ban class="w-4 h-4" />
                                            Rejeter
                                        </span>
                                        <span wire:loading.flex wire:target="rejectRequest"
                                            class="items-center inline-flex">
                                            <span class="inline-flex items-center gap-1">
                                                <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                <span>En cours...</span>
                                            </span>
                                        </span>
                                    </button>

                                    {{-- DELETE --}}
                                    @if (!$item->validated)
                                        <button wire:key="demande-del-request-{{ $item->id }}"
                                            wire:click="deleteRequest('{{ $item->id }}')"
                                            wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                            <span wire:loading.remove class="flex items-center gap-1.5"
                                                wire:target="deleteRequest">
                                                <x-lucide-trash-2 class="w-4 h-4" />
                                                Supprimer
                                            </span>
                                            <span wire:loading.flex wire:target="deleteRequest"
                                                class="items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>
                                    @endif

                                </div>

                            </div>
                            <div class="my-1 border-t border-t-slate-900 p-2 flex justify-center items-center w-full">
                                <h4
                                    class="flex gap-x-2.2 items-center justify-center text-xs font-mono text-slate-600">
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

            @if ($this->demandes_requests->hasPages())
                <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        Affichage {{ $this->demandes_requests->firstItem() }} à
                        {{ $this->demandes_requests->lastItem() }}
                        sur {{ $this->demandes_requests->total() }} demandes
                    </p>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if (!$this->demandes_requests->onFirstPage())
                            <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                                class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                ← Précédent
                            </button>
                        @endif
                        @foreach ($this->demandes_requests->getUrlRange(1, $this->demandes_requests->lastPage()) as $page => $url)
                            <button @disabled($page === $this->demandes_requests->currentPage()) wire:click="gotoPage({{ $page }})"
                                class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                               {{ $page === $this->demandes_requests->currentPage()
                                                   ? 'bg-violet-600 text-white'
                                                   : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300' }}">
                                {{ $page }}
                            </button>
                        @endforeach
                        @if ($this->demandes_requests->hasMorePages())
                            <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                Suivant →
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="rounded-2xl bg-[#121826] border border-white/5 py-20 text-center">
                <span class="text-4xl mb-4 block">🎓</span>
                <p class="text-slate-500 text-sm mb-4">Aucune donnée trouvée</p>
                @if ($search)
                    <button wire:click="clearFilters"
                        class="h-9 px-4 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all">
                        Réinitialiser les filtres
                    </button>
                @endif
            </div>
        @endif

    </section>

</div>

