<div class="w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- CONTAINER --}}
    {{-- ===================================================== --}}
    <div
        class="mx-auto
                w-full
                max-w-[1850px]
                px-3
                sm:px-4
                lg:px-6
                xl:px-8">

        <section class="mb-6 relative">
            <div wire:loading wire:target='status,clearFilters,search,previousPage,nextPage,gotoPage'
                class="absolute inset-0 flex items-center justify-center bg-slate-800/10 backdrop-blur-xs rounded-2xl"
                style="z-index: 200 !important;">

                <div class="items-center text-slate-400 relative top-1/2 mx-auto flex justify-center flex-col gap-3">
                    <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>
                    <span class="text-xl font-mono ls-1">Chargement en cours...</span>
                </div>
            </div>

            <div class="grid
                        grid-cols-1
                        gap-6">

                <div class="space-y-6 min-w-0 col-span-1">

                    {{-- TABLE --}}
                    <div
                        class="rounded-3xl
                                
                                bg-slate-950
                                overflow-hidden">

                        {{-- HEADER --}}
                        <div class="
                                    p-4 sm:p-6">

                            <div
                                class="flex flex-col
                                        lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                        gap-4 w-full">

                                <div class="flex justify-between items-center w-full">

                                    <div class="border-b border-slate-800 w-full py-2">
                                        <h2 class="text-lg sm:text-xl font-semibold flex items-center gap-2">

                                            <span>
                                                Liste des Parents
                                            </span>
                                            <span
                                                class=" text-indigo-500 text-xs font-mono bg-indigo-600/40 rounded-4xl p-1 md:px-4 border border-indigo-800 truncate">
                                                {{ $this->tutors->total() }} parents
                                            </span>

                                        </h2>

                                        <p class="mt-1 text-sm text-slate-400 font-mono">

                                            Gestion des accès et suivi des représentants

                                        </p>
                                    </div>

                                </div>

                            </div>

                            <div class="flex flex-col gap-4 my-4">

                                <section class="">

                                    <div class="rounded-3xl bg-slate-950">
                                        <div class="flex flex-col gap-4">
                                            <div class="grid grid-cols-7 gap-x-3">
                                                <div class="relative col-span-5">

                                                    <input wire:model.live.debounce.600ms='search' type="text"
                                                        placeholder="Trouver un parent..."
                                                        class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                                                    <div
                                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                                                        🔍
                                                    </div>
                                                </div>
                                                <button wire:click='clearFilters'
                                                    class="py-2 rounded-2xl bg-slate-600 hover:bg-slate-800 transition-all text-sm col-span-2">
                                                    <span wire:loading.remove wire:target='clearFilters'
                                                        class="inline-flex gap-x-2 items-center ">
                                                        <span class="inline-flex gap-x-2 items-center">
                                                            <x-lucide-refresh-ccw class="w-4 h-4" />
                                                            <span>Réinitialiser</span>
                                                        </span>
                                                    </span>
                                                    <span wire:loading wire:target='clearFilters'
                                                        class="inline-flex items-center gap-x-2">
                                                        <span class="inline-flex items-center gap-x-2">
                                                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                                            <span>Rechargement ...</span>
                                                        </span>
                                                    </span>

                                                </button>
                                            </div>

                                            <div class="flex items-center flex-wrap gap-3">

                                                <select wire:model.live='status'
                                                    class="h-12  uppercase font-mono rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm">
                                                    <option value="">
                                                        <span>Tout statut </span>
                                                    </option>
                                                    <option class="text-green-400" value="actives">
                                                        <span>
                                                            <span>Actifs</span>
                                                        </span>
                                                    </option>
                                                    <option value="desactives">
                                                        <span>Bloqués</span>
                                                    </option>
                                                    <option class="text-orange-600" value="corbeille">
                                                        <span>La corbeille</span>
                                                    </option>
                                                </select>
                                            </div>

                                        </div>

                                    </div>

                                </section>

                            </div>

                        </div>

                        @if (count($this->tutors) > 0)
                            <div class="overflow-x-auto my-3 font-mono text-slate-500">

                                <table class="z-table-border w-full mb-4">

                                    <thead class="bg-slate-950 border-b border-slate-800">

                                        <tr>
                                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                N°
                                            </th>

                                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                Parent
                                            </th>

                                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                Statut
                                            </th>

                                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                Actions
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody class="divide-y divide-slate-800">

                                        @foreach ($this->tutors as $parent)
                                            <tr class="hover:bg-slate-800/40 transition-all">

                                                <td class="px-4 py-5 text-center">

                                                    {{ $this->tutors->firstItem() + $loop->iteration - 1 }}

                                                </td>

                                                {{-- PROFILE --}}
                                                <td class="px-6 py-5">

                                                    <div class="w-full flex flex-col gap-2">
                                                        <a title="Charger le profil de ce parent"
                                                            href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent->uuid]) }}"
                                                            class="flex items-center gap-4 hover:bg-slate-950 p-2 rounded-2xl group">

                                                            <img src="{{ $parent->profil_photo_url() }}"
                                                                alt="Photo de profil de {{ $parent->fullName() }}"
                                                                class="w-14 h-14 rounded-full object-cover border-4 border-slate-700 group-hover:border-sky-500">

                                                            <div class="min-w-0 group-hover:text-sky-600 flex flex-col">
                                                                <h3 class="font-medium ">
                                                                    {{ $parent->getFullName() }}
                                                                </h3>
                                                            </div>

                                                        </a>
                                                        <div class="flex flex-col gap-2">
                                                            <p
                                                                class="mt-1 text-sm text-sky-400  inline-flex items-center gap-2">
                                                                <x-lucide-mail class="w-4 h-4 text-slate-200" />
                                                                {{ $parent->user->email }}
                                                            </p>
                                                            <p
                                                                class="mt-1 text-sm text-slate-400  inline-flex items-center gap-2">
                                                                <x-lucide-briefcase-business
                                                                    class="w-4 h-4 text-slate-200" />
                                                                {{ $parent->user->job_name ? $parent->user->job_name : '' }}
                                                            </p>

                                                            <p
                                                                class="mt-1 text-sm text-amber-400 inline-flex items-center gap-2">
                                                                <x-lucide-map-pin-check
                                                                    class="w-4 h-4 text-slate-200" />
                                                                {{ $parent->user->adresse }}
                                                            </p>
                                                            <p class="inline-flex items-center gap-2">
                                                                <x-lucide-phone class="w-4 h-4 text-slate-200" />
                                                                {{ $parent->user->contacts }}
                                                            </p>
                                                        </div>
                                                        <div class="flex justify-end animate-pulse">
                                                            @if (count($parent->myChildren))
                                                                <span
                                                                    class="text-center rounded-3xl p-1 border text-green-400 bg-green-700/40 border-green-600 px-4">
                                                                    {{ count($parent->myChildren) . ' apprenant(s)' }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="text-slate-500 font-mono text-center animate-pulse">Aucun
                                                                    apprenant
                                                                    lié</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </td>

                                                <td class="px-4 py-5 text-center">
                                                    @if ($parent->deleted_at)
                                                        <span
                                                            class="px-3 py-1 rounded-full
                                                         bg-red-500/10
                                                         text-red-400 text-sm">

                                                            Supprimé

                                                        </span>
                                                    @elseif ($parent->is_active)
                                                        <span
                                                            class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                            Actif

                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-3 py-1 rounded-full
                                                         bg-red-500/10
                                                         text-red-400 text-sm">

                                                            Bloqué

                                                        </span>
                                                    @endif

                                                </td>

                                                <td class="px-6 py-5 ">
                                                    <div class="flex items-center justify-center gap-2 text-xs">

                                                        @if (!$parent->user->credentials_sent)
                                                            <button
                                                                title="Envoyer les données de connexion à {{ $parent->getFullName() }}"
                                                                wire:click="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                                wire:loading.attr="disabled"
                                                                wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                                class="inline-flex items-center justify-center gap-1.5 py-3 px-3 rounded-xl bg-sky-600/50 hover:bg-sky-800/50 text-sky-400 transition-all whitespace-nowrap disabled:opacity-50">
                                                                <span wire:loading.remove
                                                                    wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                                    class="inline-flex items-center gap-1.5">
                                                                    <span class="flex items-center gap-x-3">
                                                                        <x-lucide-send class="w-3.5 h-3.5 shrink-0" />
                                                                        <span>Envoyer</span>
                                                                    </span>
                                                                </span>
                                                                <span wire:loading
                                                                    wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                                    class="inline-flex items-center gap-1.5">
                                                                    <span class="flex items-center gap-x-2">
                                                                        <span class="flex items-center gap-x-2">
                                                                            <x-lucide-refresh-ccw
                                                                                class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                            <span>En cours...</span>
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                            </button>
                                                        @endif

                                                        <button
                                                            title="{{ !$parent->is_active ? 'Débloquer' : 'Bloquer' }} {{ $parent->getFullName() }}"
                                                            wire:click="{{ !$parent->is_active ? 'activateTutor(' . $parent->id . ')' : 'desactivateTutor(' . $parent->id . ')' }}"
                                                            wire:loading.attr="disabled"
                                                            wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                                            class="inline-flex items-center justify-center gap-1.5 py-3 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ !$parent->is_active ? 'bg-lime-600/50 hover:bg-lime-800/50 text-lime-400' : 'bg-amber-600/50 hover:bg-amber-800/50 text-amber-400' }}">
                                                            <span wire:loading.remove
                                                                wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                @if (!$parent->is_active)
                                                                    <x-lucide-lock-keyhole-open
                                                                        class="w-3.5 h-3.5 shrink-0" />
                                                                    <span>Activer</span>
                                                                @else
                                                                    <x-lucide-ban class="w-3.5 h-3.5 shrink-0" />
                                                                    <span>Désactiver</span>
                                                                @endif
                                                            </span>
                                                            <span wire:loading
                                                                wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                <span class="flex items-center gap-x-2">
                                                                    <span class="flex items-center gap-x-2">
                                                                        <x-lucide-refresh-ccw
                                                                            class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                        <span>En cours...</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </button>

                                                        <button
                                                            title="{{ $parent->deleted_at ? 'Restorer' : 'Mettre en corbeille' }} {{ $parent->getFullName() }}"
                                                            wire:click="{{ $parent->deleted_at ? 'restoreTutor(' . $parent->id . ')' : 'deleteTutor(' . $parent->id . ')' }}"
                                                            wire:loading.attr="disabled"
                                                            wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                                            class="inline-flex items-center justify-center gap-1.5 py-3 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $parent->deleted_at ? 'bg-violet-600/50 hover:bg-violet-800/50 text-violet-400' : 'bg-rose-600/50 hover:bg-rose-800/50 text-rose-400' }}">
                                                            <span wire:loading.remove
                                                                wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                @if ($parent->deleted_at)
                                                                    <x-lucide-recycle class="w-3.5 h-3.5 shrink-0" />
                                                                    <span>Restaurer</span>
                                                                @else
                                                                    <x-lucide-trash class="w-3.5 h-3.5 shrink-0" />
                                                                    <span>Corbeille</span>
                                                                @endif
                                                            </span>
                                                            <span wire:loading
                                                                wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                <span class="flex items-center gap-x-2">
                                                                    <x-lucide-refresh-ccw
                                                                        class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                    <span>En cours...</span>
                                                                </span>
                                                            </span>
                                                        </button>

                                                        @if ($parent->deleted_at)
                                                            <button
                                                                title="Supprimer définitivement {{ $parent->getFullName() }}"
                                                                wire:click="forceDeleteTutor({{ $parent->id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="forceDeleteTutor({{ $parent->id }})"
                                                                class="inline-flex items-center justify-center gap-1.5 py-3 px-3 rounded-xl bg-red-600/50 hover:bg-red-800/50 text-red-400 transition-all whitespace-nowrap disabled:opacity-50">
                                                                <span wire:loading.remove
                                                                    wire:target="forceDeleteTutor({{ $parent->id }})"
                                                                    class="inline-flex items-center gap-1.5">
                                                                    <x-lucide-trash-2 class="w-3.5 h-3.5 shrink-0" />
                                                                    <span>Suppr. déf.</span>
                                                                </span>
                                                                <span wire:loading
                                                                    wire:target="forceDeleteTutor({{ $parent->id }})"
                                                                    class="inline-flex items-center gap-1.5">
                                                                    <span class="flex items-center gap-x-2">
                                                                        <x-lucide-refresh-ccw
                                                                            class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                        <span>En cours...</span>
                                                                    </span>
                                                                </span>
                                                            </button>
                                                        @endif

                                                    </div>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>
                        @else
                            <div class="flex w-full itecn justify-center">
                                <div class="p-6 flex justify-center text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="text-4xl">🎯</span>
                                        <p class="text-slate-500 text-sm">Aucun parent ou tuteur trouvé </p>
                                        @if ($search || $status)
                                            <button wire:click="clearFilters"
                                                class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                                Recharger les données
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($this->tutors->hasPages())
                            <section class="py-6 p-2 font-mono">
                                <div class="flex justify-center bg-transparent p-4">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="text-sm text-slate-400">
                                            Affichage {{ $this->tutors->firstItem() }} à
                                            {{ $this->tutors->lastItem() }}
                                            sur
                                            {{ $this->tutors->total() }} parents/tuteurs
                                        </div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if (!$this->tutors->onFirstPage())
                                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                                    wire:target="previousPage"
                                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                                    Précédent
                                                </button>
                                            @endif

                                            @foreach ($this->tutors->getUrlRange(1, $this->tutors->lastPage()) as $page => $url)
                                                <button @disabled($page === $this->tutors->currentPage())
                                                    wire:click="gotoPage({{ $page }})"
                                                    class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->tutors->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                                    {{ $page }}
                                                </button>
                                            @endforeach

                                            @if ($this->tutors->hasMorePages())
                                                <button wire:click="nextPage" wire:loading.attr="disabled"
                                                    wire:target="nextPage"
                                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                                    Suivant
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

