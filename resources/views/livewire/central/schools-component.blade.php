{{-- ===================================================== --}}
{{-- ABONNEMENTS CARDS --}}
{{-- ===================================================== --}}
<section class="space-y-6 p-2 mb-24">
    {{-- HEADER --}}
    <div class="rounded-3xl border border-slate-800
               bg-slate-950 p-5 sm:p-6">

        <div
            class="flex flex-col xl:flex-row
                   xl:items-center xl:justify-between
                   gap-5">

            {{-- TITLE --}}
            <div>

                <div
                    class="inline-flex items-center gap-2
                           px-3 py-1 rounded-full
                           bg-slate-950
                           border border-indigo-500/20
                           text-indigo-300 text-xs font-semibold">

                    <x-lucide-credit-card class="w-4 h-4" />

                    @if ($status)
                        Gestion des établissements <span class="text-orange-500 font-semibold">{{ $status }}</span>
                    @else
                        Gestion de tous les établissements
                    @endif

                </div>

                <h2 class="mt-4 text-2xl sm:text-3xl font-sans">

                    Etablissements
                    @if ($status)
                        <span class="text-orange-500 font-semibold">{{ $status }}</span>
                    @endif

                </h2>

                <p class="mt-2 text-sm text-slate-400">

                    Supervision activités et statuts des établissements.

                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-wrap gap-3">

                <button
                    class="h-11 px-5 rounded-2xl
                           bg-indigo-500 hover:bg-indigo-400
                           text-white font-semibold
                           flex items-center gap-2">

                    <x-lucide-plus class="w-4 h-4" />

                    Nouvelle école

                </button>

                <button
                    class="h-11 px-5 rounded-2xl
                           border border-slate-700
                           bg-slate-950 hover:bg-slate-700
                           text-slate-200 font-medium
                           flex items-center gap-2">

                    <x-lucide-download class="w-4 h-4" />

                    Exporter

                </button>

            </div>

        </div>

        {{-- FILTERS --}}
        <div class="mt-6 grid grid-cols-1
                   md:grid-cols-2
                   xl:grid-cols-4 gap-4">

            {{-- SEARCH --}}
            <div class="xl:col-span-2 relative">

                <x-lucide-search
                    class="w-4 h-4 absolute left-4 top-1/2
                           -translate-y-1/2 text-slate-500" />

                <input wire:model.live.debounce.500ms="search" type="text"
                    placeholder="Rechercher une école, directeur..."
                    class="w-full h-12 rounded-2xl
                           bg-slate-950 border border-slate-700
                           pl-11 pr-4 text-sm
                           focus:outline-none
                           focus:border-indigo-500">

            </div>

            {{-- TYPE --}}
            <select
                class="h-12 rounded-2xl
                       bg-slate-950 border border-slate-700
                       px-4 text-sm">

                <option>Tous les enseignements</option>
                <option>Technique</option>
                <option>Général</option>
                <option>Hybride</option>

            </select>

            {{-- STATUS --}}
            <select
                class="h-12 rounded-2xl
                       bg-slate-950 border border-slate-700
                       px-4 text-sm">

                <option>Tous les types</option>
                <option>Privées</option>
                <option>Publics</option>
            </select>

        </div>

    </div>

    {{-- ===================================================== --}}
    {{-- GRID --}}
    {{-- ===================================================== --}}
    @if (count($this->tenants))
        <div class="grid grid-cols-1
               lg:grid-cols-2
               2xl:grid-cols-2 gap-6">

            @foreach ($this->tenants as $tenant)
                <div wire:key="tenant-{{ $tenant->id }}"
                    class="group relative overflow-hidden
                       rounded-3xl border border-slate-800
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
                        <div class="flex items-start justify-between gap-4">

                            <div class="flex items-center gap-4 min-w-0">

                                {{-- LOGO --}}
                                <div
                                    class="w-16 h-16 rounded-2xl
                                       bg-slate-950
                                       border border-indigo-500/20
                                       flex items-center justify-center
                                       shrink-0">

                                    <x-lucide-school class="w-8 h-8 text-indigo-400" />

                                </div>

                                {{-- INFOS --}}
                                <div class="min-w-0">

                                    <h3 class="text-lg font-bold truncate">

                                        {{ $tenant->school_name }}

                                    </h3>

                                    <p class="text-sm text-slate-400 truncate">

                                        {{ $tenant->school_type }}

                                    </p>

                                    <div
                                        class="mt-2 inline-flex items-center
                                           gap-2 px-3 py-1 rounded-full
                                           bg-emerald-500/10
                                           text-emerald-400
                                           text-xs font-semibold">

                                        <span
                                            class="w-2 h-2 rounded-full
                                               bg-emerald-400">

                                        </span>

                                        Actif

                                    </div>

                                </div>

                            </div>

                            {{-- DAYS --}}
                            <div class="shrink-0 text-right">

                                <p class="text-xs text-slate-500">

                                    Restants

                                </p>

                                <p
                                    class="mt-1 text-xl font-black
                                       text-emerald-400">

                                    98j

                                </p>

                            </div>

                        </div>

                        {{-- DIRECTOR --}}
                        <div
                            class="mt-6 rounded-2xl
                               border border-slate-800
                               bg-slate-950/40 p-4">

                            <div class="flex items-center gap-3">

                                <div
                                    class="w-12 h-12 rounded-2xl
                                       bg-slate-950
                                       flex items-center justify-center">

                                    <x-lucide-user class="w-5 h-5 text-slate-300" />

                                </div>

                                <div class="min-w-0">

                                    <p class="font-semibold truncate">

                                        {{ $tenant->getFullName() }}

                                    </p>

                                    <p class="text-xs text-slate-500">

                                        {{ $tenant->role }}

                                    </p>

                                </div>

                            </div>

                            {{-- CONTACTS --}}
                            <div class="mt-4 grid grid-cols-1 gap-3">

                                <div
                                    class="flex items-center gap-3
                                       text-sm text-slate-300">

                                    <x-lucide-mail class="w-4 h-4 text-slate-500 shrink-0" />

                                    <span class="truncate">
                                        {{ $tenant->email }}
                                    </span>

                                </div>

                                <div
                                    class="flex items-center gap-3
                                       text-sm text-slate-300">
                                    <x-lucide-phone class="w-4 h-4 text-slate-500 shrink-0" />
                                    {{ $tenant->contacts }}
                                </div>

                                <div
                                    class="flex items-center gap-3
                                       text-sm text-slate-300">

                                    <x-lucide-map-pin class="w-4 h-4 text-slate-500 shrink-0" />
                                    {{ $tenant->country }}, {{ $tenant->city }}
                                </div>

                            </div>

                        </div>

                        {{-- STATS --}}
                        <div class="mt-5 grid grid-cols-2 gap-4">

                            <div
                                class="rounded-2xl border border-slate-800
                                   bg-slate-950/40 p-4">

                                <p class="text-xs text-slate-500">

                                    Début

                                </p>

                                <p class="mt-2 font-bold">

                                    12 Jan 2026

                                </p>

                            </div>

                            <div
                                class="rounded-2xl border border-slate-800
                                   bg-slate-950/40 p-4">

                                <p class="text-xs text-slate-500">

                                    Expiration

                                </p>

                                <p class="mt-2 font-bold text-rose-400">

                                    12 Jan 2027

                                </p>

                            </div>

                        </div>

                        {{-- PACK --}}
                        <div
                            class="mt-5 rounded-2xl
                               border border-sky-500/20
                               bg-sky-500/5 p-4">

                            <div class="flex items-center justify-between gap-3">

                                <div>

                                    <p class="text-xs text-slate-500">

                                        Pack souscrit

                                    </p>

                                    <p
                                        class="mt-1 text-lg font-black
                                           text-sky-400">

                                        Premium Annual

                                    </p>

                                </div>

                                <div
                                    class="w-12 h-12 rounded-2xl
                                       bg-sky-500/10
                                       flex items-center justify-center">

                                    <x-lucide-crown class="w-6 h-6 text-sky-400" />

                                </div>

                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="mt-6 flex flex-wrap gap-3">

                            {{-- MESSAGE --}}
                            <button
                                class="flex-1 min-w-[120px]
                                   h-11 rounded-2xl
                                   bg-slate-950 hover:bg-slate-700
                                   border border-slate-700
                                   text-slate-200
                                   flex items-center justify-center gap-2
                                   transition-all duration-200">

                                <x-lucide-message-square class="w-4 h-4" />

                                Message

                            </button>

                            {{-- REMINDER --}}
                            <button title="Envoyer un rappel de jours restants à {{ $tenant->getFullName() }}"
                                class="flex-1 min-w-[120px]
                                   h-11 rounded-2xl
                                   bg-amber-500/10
                                   hover:bg-amber-500/20
                                   text-amber-400
                                   flex items-center justify-center gap-2
                                   transition-all duration-200">

                                <x-lucide-bell-ring class="w-4 h-4" />

                                Rappel

                            </button>

                        </div>

                        {{-- ACTIONS 2 --}}
                        <div class="mt-3 grid grid-cols-4 gap-3">
                            @if (!$tenant->deleted_at)
                                {{-- EXTEND --}}
                                <button title="Editer le delai de {{ $tenant->school_name }}"
                                    class="h-11 rounded-2xl
                                   bg-indigo-700/25
                                   hover:bg-indigo-500/50
                                   text-indigo-400
                                   flex items-center justify-center">

                                    <x-lucide-calendar-plus class="w-5 h-5" />

                                </button>

                                {{-- LOCK DOMAIN --}}
                                <button
                                    title=" {{ $tenant->domain_blocked ? 'Débloquer' : 'Bloquer' }} l'accès de cette école. Le tenant et ces utilisateurs n'auront plus accès à l'espace de cette école."
                                    wire:key="tenant-domain-{{ $tenant->id }}"
                                    wire:click="{{ $tenant->domain_blocked ? 'unblockDomain' : 'blockDomain' }}('{{ $tenant->id }}')"
                                    wire:loading.attr="disabled"
                                    class="h-11 rounded-2xl flex items-center justify-center cursor-pointer {{ $tenant->domain_blocked ? 'bg-green-500/10 hover:bg-green-500/20 text-green-400' : 'bg-amber-500/10 hover:bg-amber-500/20 text-amber-400' }}">
                                    {{-- NORMAL STATE --}}
                                    <span wire:loading.remove wire:target="blockDomain,unblockDomain">
                                        @if ($tenant->domain_blocked)
                                            <x-lucide-lock-open class="w-5 h-5" />
                                        @else
                                            <x-lucide-lock class="w-5 h-5" />
                                        @endif
                                    </span>
                                    {{-- LOADING --}}
                                    <span wire:loading.flex wire:target="blockDomain,unblockDomain"
                                        class="items-center inline-flex">
                                        <span class="items-center inline-flex gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </span>
                                </button>

                                {{-- SUSPEND --}}
                                <button title="Suspendre temporairement l'abonnement de cette école"
                                    class="h-11 rounded-2xl
                                   bg-orange-500/10
                                   hover:bg-orange-500/20
                                   text-orange-400
                                   flex items-center justify-center">

                                    <x-lucide-ban class="w-5 h-5" />

                                </button>

                                {{-- DELETE --}}

                                <button wire:key="del-tenant-{{ $tenant->id }}"
                                    wire:click="deleteTenant('{{ $tenant->id }}')" wire:loading.attr="disabled"
                                    class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                    <span wire:loading.remove class="flex items-center gap-1.5"
                                        wire:target="deleteTenant">
                                        <x-lucide-trash class="w-4 h-4" />
                                        Corbeille
                                    </span>
                                    <span wire:loading.flex wire:target="deleteTenant"
                                        class="items-center inline-flex">
                                        <span class="items-center inline-flex gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </span>
                                </button>
                            @else
                                <button wire:key="restore-tenant-{{ $tenant->id }}"
                                    wire:click="restoreTenant('{{ $tenant->id }}')" wire:loading.attr="disabled"
                                    class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-purple-500/10 hover:bg-purple-500/20 text-purple-400 ">
                                    <span wire:loading.remove class="flex items-center gap-1.5"
                                        wire:target="restoreTenant">
                                        <x-lucide-recycle class="w-4 h-4" />
                                        Restaurer
                                    </span>
                                    <span wire:loading.flex wire:target="restoreTenant" class="items-center gap-1.5">
                                        <span class="items-center inline-flex gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </span>
                                </button>

                                <button wire:key="force-del-tenant-{{ $tenant->id }}"
                                    wire:click="forceDelete('{{ $tenant->id }}')" wire:loading.attr="disabled"
                                    class="h-11 rounded-2xl col-span-3 flex items-center flex-1 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                    <span wire:loading.remove class="flex items-center gap-1.5"
                                        wire:target="forceDelete">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                        Supprimer déf.
                                    </span>
                                    <span wire:loading.flex wire:target="forceDelete" class="items-center gap-1.5">
                                        <span class="items-center inline-flex gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </span>
                                </button>
                            @endif

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

        @if ($this->tenants->hasPages())
            <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-xs text-slate-500">
                    Affichage {{ $this->tenants->firstItem() }} à
                    {{ $this->tenants->lastItem() }}
                    sur {{ $this->tenants->total() }} écoles
                </p>
                <div class="flex items-center gap-1.5 flex-wrap">
                    @if (!$this->tenants->onFirstPage())
                        <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                            class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                            ← Précédent
                        </button>
                    @endif
                    @foreach ($this->tenants->getUrlRange(1, $this->tenants->lastPage()) as $page => $url)
                        <button @disabled($page === $this->tenants->currentPage()) wire:click="gotoPage({{ $page }})"
                            class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                               {{ $page === $this->tenants->currentPage()
                                                   ? 'bg-violet-600 text-white'
                                                   : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300' }}">
                            {{ $page }}
                        </button>
                    @endforeach
                    @if ($this->tenants->hasMorePages())
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

