<div class="w-full overflow-x-hidden">

    <div
        class="mx-auto
                w-full
                max-w-[1900px]
                px-3 sm:px-4 lg:px-6 xl:px-8">
        <section class="mb-6">

            <div
                class="relative overflow-hidden
                        rounded-[32px]
                        border border-slate-800
                        bg-slate-900">

                {{-- BG --}}
                <div
                    class="absolute inset-0
                            bg-gradient-to-br
                            from-indigo-500/10
                            via-slate-900
                            to-slate-900">
                </div>

                <div class="relative p-5 sm:p-6 lg:p-8">

                    <div
                        class="flex flex-col
                                xl:flex-row
                                xl:items-start
                                xl:justify-between
                                gap-8">

                        {{-- LEFT --}}
                        <div class="min-w-0">

                            <div
                                class="flex flex-wrap
                                        items-center
                                        gap-3">

                                <h1 class="text-2xl sm:text-3xl font-bold">

                                    Portail des séries

                                </h1>

                                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">
                                    Gestion Académique {{ $this->activeYear?->slug }}
                                </span>

                            </div>

                            <p class="mt-3 text-slate-400 max-w-3xl">

                                Vue globale des Séries,
                                performances académiques,
                                statistiques des apprenants
                                et gestion des classes.

                            </p>

                            {{-- BADGES --}}
                            <div class="mt-6 flex flex-wrap gap-3">

                                <div
                                    class="px-4 py-2 rounded-2xl
                                            bg-slate-800 border border-slate-700">

                                    {{ __zero($this->serials->total()) }} Séries

                                </div>

                                @if ($this->unActivesSerials)
                                    <div
                                        class="px-4 py-2 rounded-2xl animate-pulse font-mono bg-rose-800/20 border text-rose-400 border-rose-700">
                                        {{ __zero($this->unActivesSerials) }} séries désactivées
                                    </div>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <section class="my-4 mb-5 flex justify-end border-y border-y-slate-800 py-4">
            <div class="flex gap-3">

                <a wire:navigate href="{{ route('tenant.classes.create') }}"
                    class="py-3 px-5 rounded-2xl bg-purple-700 hover:bg-purple-900">
                    + Créer une classe 🏠
                </a>
                <a wire:navigate href="{{ route('tenant.serial.create') }}"
                    class="py-3 flex justify-center items-center px-5 rounded-2xl bg-blue-500 hover:bg-blue-800  transition">

                    + Créer une série 🛠️

                </a>
                <button class="py-3 px-5 rounded-2xl bg-emerald-500 hover:bg-emerald-600">Export PDF</button>
                @if ($this->unActivesSerials)
                    <button title="Réactiver les {{ $this->unActivesSerials }} séries désactivées "
                        wire:click="activateUnactivesserials" wire:loading.attr="disabled"
                        wire:target="activateUnactivesserials"
                        class="relative py-3 px-4 text-white  text-xs inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50 bg-orange-600/60 hover:bg-orange-600 hover:text-black">
                        <span wire:loading.remove wire:target="activateSerial, activateUnactivesSerials"
                            class="inline-flex items-center justify-center gap-3">
                            <span class="inline-flex items-center justify-center gap-3">
                                <x-lucide-lock class="w-4 h-4" />
                                <span>Réactiver les séries
                                    ({{ __zero($this->unActivesSerials) }})
                                </span>
                            </span>
                        </span>

                        <span wire:loading wire:target="activateUnactivesserials"
                            class="inline-flex items-center gap-1">
                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                        </span>
                    </button>
                @endif

                @if ($this->trashedsSerials)
                    <button title="Restorer les {{ $this->trashedsSerials }} séries de la corbeille "
                        wire:click="restoreTrashedsserials" wire:loading.attr="disabled"
                        wire:target="restoreTrashedsserials"
                        class="relative py-3 px-4 text-white  text-xs inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50 bg-rose-600/60 hover:bg-rose-600 hover:text-black">
                        <span wire:loading.remove wire:target="activateSerial, restoreTrashedsserials"
                            class="inline-flex items-center justify-center gap-3">
                            <span class="inline-flex items-center justify-center gap-3">
                                <x-lucide-trash class="w-4 h-4" />
                                <span>Restorer les séries
                                    ({{ __zero($this->trashedsSerials) }})
                                </span>
                            </span>
                        </span>

                        <span wire:loading wire:target="restoreTrashedsSerials" class="inline-flex items-center gap-1">
                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                        </span>
                    </button>
                @endif
            </div>
        </section>

        <section class="mb-28">

            <div
                class="rounded-tl-2xl rounded-tr-2xl
                        bg-slate-900
                        border border-slate-800
                        overflow-hidden p-2">

                {{-- ===================================================== --}}
                {{-- HEADER --}}
                {{-- ===================================================== --}}
                <div class="p-5 sm:p-6 border-b border-slate-800">

                    <div class="flex flex-col
                                gap-5">

                        <div class="font-mono">

                            <h2 class="text-xl font-bold">
                                Liste des séries
                                <span class="ml-3 text-orange-600/60 uppercase font-mono">{{ $is_active }}</span>
                            </h2>
                            <p class="mt-1 text-sm text-slate-400">
                                Analyse détaillée des séries.

                            </p>

                        </div>

                        <div class="grid grid-cols-7 gap-x-3 font-mono">

                            <div class="relative col-span-4">
                                <input wire:model.live.debounce.600ms='search' type="text"
                                    placeholder="Rechercher une serie..."
                                    class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                                    🔍
                                </div>
                            </div>

                            <select wire:model.live='is_active'
                                class="h-12 col-span-3 uppercase font-mono rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm">
                                <option value="">
                                    <span>Toutes les filières</span>
                                    <span>({{ __zero($this->activesSerials + $this->unActivesSerials) }})</span>
                                </option>
                                <option class="text-green-400" value="actives">
                                    <span>
                                        <span>Actives</span>
                                        <span c>({{ __zero($this->activesSerials) }})</span>
                                    </span>
                                </option>
                                <option value="desactives">
                                    <span>Désactivées</span>
                                    <span>({{ __zero($this->unActivesSerials) }})</span>
                                </option>
                                <option class="text-orange-600" value="corbeille">
                                    <span>La corbeille</span>
                                    <span>({{ __zero($this->trashedsSerials) }})</span>
                                </option>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto relative  mt-1.5 p-2">
                    <div wire:loading wire:target='is_active,search,previousPage,nextPage,resetFilters, gotoPage'
                        class="absolute inset-0 flex items-center justify-center bg-slate-800/20 backdrop-blur-sm"
                        style="z-index: 200 !important;">

                        <div
                            class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-row">
                            <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            <span class="text-2xl font-mono ls-1">Chargement en cours...</span>
                        </div>
                    </div>

                    @if (count($this->serials))
                        <table class="w-full z-table-border">
                            <thead
                                class="bg-slate-950
                                     border-b border-slate-800 font-semibold ls-1 text-sm">

                                <tr class="truncate text-center ">

                                    <th class="px-6 py-4 text-slate-400">
                                        N°
                                    </th>
                                    <th class="px-6 py-4 text-slate-400">
                                        Série
                                    </th>

                                    <th class="px-4 py-4 text-center text-slate-400">
                                        <span class="flex flex-col gap-y-2">
                                            <span>Effectif</span>
                                            <span class="text-yellow-600">Classes</span>
                                            <span class="text-purple-300">Elèves</span>
                                        </span>
                                    </th>

                                    <th class="px-6 py-4 text-slate-400">
                                        Meilleur Élève
                                    </th>

                                    <th class="px-6 py-4 text-slate-400">
                                        Plus Faible
                                    </th>

                                    <th class="px-6 py-4 text-slate-400">
                                        Plus Jeune
                                    </th>

                                    <th class="px-6 py-4 text-slate-400">
                                        Plus Âgé
                                    </th>

                                    <th class="px-6 py-4 text-center text-slate-400">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            {{-- BODY --}}
                            <tbody class="divide-y divide-slate-800 text-slate-400 font-mono">

                                @foreach ($this->serials as $serial)
                                    @php
                                        $details = app(
                                            \App\Services\SerialsServices\SerialDetailsCacheService::class,
                                        )->get($serial->id);
                                    @endphp
                                    <tr
                                        class="hover:bg-slate-800/40
                                       transition-colors duration-200 @if ($serial->deleted_at) trashed-tr trashed-text @endif">

                                        <td class="px-6 py-5 truncate">
                                            {{ __zero($this->serials->firstItem() + $loop->iteration - 1) }}
                                        </td>

                                        <td class="px-6 py-5 truncate">

                                            <a wire:navigate
                                                href="{{ route('tenant.serial.profil', ['serial_slug' => $serial->slug]) }}"
                                                class="hover:underline flex underline-offset-2 p-3 rounded-2xl bg-primary-700/20 text-primary-300 text-center justify-center hover:bg-primary-800 hover:text-white">

                                                <h3
                                                    class="font-semibold text-lg @if ($serial->deleted_at) trashed-text @endif">
                                                    {{ $serial->name }}
                                                </h3>
                                            </a>

                                        </td>

                                        <td class="px-4 py-5 text-center truncate">

                                            <span class="flex flex-col gap-y-1 text-xs font-thin">
                                                <span class="text-yellow-500">
                                                    {{ __zero($details['classes_count']) }}
                                                    classe(s)
                                                </span>
                                                <span class="text-purple-300">
                                                    {{ __zero($details['students_count']) }}
                                                    apprenant(s)
                                                </span>
                                            </span>

                                        </td>

                                        {{-- BEST --}}
                                        <td class="px-6 py-5">

                                            <div class="truncate">

                                                <h3 class="font-medium">

                                                    KOUASSI Sarah

                                                </h3>

                                                <p class="text-sm text-emerald-400">

                                                    (18.92)
                                                </p>

                                            </div>

                                        </td>

                                        {{-- WORST --}}
                                        <td class="px-6 py-5">

                                            <div class="truncate">

                                                <h3 class="font-medium">

                                                    HOUNKPE David

                                                </h3>

                                                <p class="text-sm text-rose-400">

                                                    (03.42)

                                                </p>

                                            </div>

                                        </td>

                                        {{-- YOUNGEST --}}
                                        <td class="px-6 py-5">

                                            <div class="truncate">

                                                <h3 class="font-medium">

                                                    ADJOVI Esther

                                                </h3>

                                                <p class="text-sm text-slate-400">

                                                    10 ans

                                                </p>

                                            </div>

                                        </td>

                                        {{-- OLDEST --}}
                                        <td class="px-6 py-5">

                                            <div class="truncate">

                                                <h3 class="font-medium">

                                                    AKAKPO Jonas

                                                </h3>

                                                <p class="text-sm text-slate-400">

                                                    19 ans

                                                </p>

                                            </div>

                                        </td>

                                        <td class="px-6 py-5">

                                            <div class="flex gap-2 truncate">
                                                <a wire:navigate
                                                    href="{{ route('tenant.serial.profil', ['serial_slug' => $serial->slug]) }}"
                                                    class="p-2.5 rounded-2xl bg-blue-500/20 text-blue-400  hover:bg-blue-500/60 hover:text-black transition-all text-sm inline-block text-center">
                                                    <span class="flex items-center justify-center gap-x-2">
                                                        <span class="flex items-center justify-center gap-x-2">
                                                            <x-lucide-eye class="w-4 h-4" />
                                                            <span>Voir détails</span>
                                                        </span>
                                                    </span>
                                                </a>

                                                <button
                                                    title="{{ $serial->is_active ? 'Fermer ' : 'Activer ' }} cette série "
                                                    wire:click="{{ $serial->is_active ? 'closeSerial(' . $serial->id . ')' : 'activateSerial(' . $serial->id . ')' }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="activateSerial, closeSerial"
                                                    class="relative py-3 px-4 rounded-xl text-white {{ !$serial->is_active ? 'bg-lime-600/60 hover:bg-lime-500 hover:text-black' : 'bg-orange-500/60 hover:bg-orange-600/90' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50 hover:text-black">
                                                    <span wire:loading.remove wire:target="activateSerial, closeSerial"
                                                        class="inline-flex items-center justify-center gap-3">
                                                        <span class="inline-flex items-center justify-center gap-3">
                                                            @if ($serial->is_active)
                                                                <x-lucide-lock class="w-4 h-4" />
                                                                <span>Fermer</span>
                                                            @else
                                                                <x-lucide-unlock class="w-4 h-4" />
                                                                <span>Activer</span>
                                                            @endif
                                                        </span>
                                                    </span>

                                                    <span wire:loading wire:target="activateSerial, closeSerial"
                                                        class="inline-flex items-center gap-1">
                                                        <svg class="animate-spin w-3 h-3" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v8z" />
                                                        </svg>
                                                    </span>
                                                </button>

                                                <button
                                                    title="{{ $serial->deleted_at ? 'Restaurer cette série de la corbeille ' : 'Mettre cette série dans la corbeille ' }} "
                                                    wire:click="{{ $serial->deleted_at ? 'restoreSerial(' . $serial->id . ')' : 'deleteSerial(' . $serial->id . ')' }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="deleteFiliar, restoreFiliar"
                                                    class="relative py-3 px-4 rounded-xl text-white {{ $serial->deleted_at ? 'bg-green-600/50 hover:bg-green-800/80' : 'bg-red-500/60 hover:bg-red-600/80' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50 hover:text-black">
                                                    <span wire:loading.remove wire:target="deleteFiliar, restoreFiliar"
                                                        class="inline-flex items-center justify-center gap-3">
                                                        <span class="inline-flex items-center justify-center gap-3">
                                                            @if ($serial->deleted_at)
                                                                <x-lucide-refresh-ccw class="w-4 h-4" />
                                                                <span>Restaurer</span>
                                                            @else
                                                                <x-lucide-trash class="w-4 h-4" />
                                                                <span>Corbeille</span>
                                                            @endif
                                                        </span>
                                                    </span>

                                                    <span wire:loading wire:target="restoreSerial, deleteSerial"
                                                        class="inline-flex items-center gap-1">
                                                        <svg class="animate-spin w-3 h-3" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v8z" />
                                                        </svg>
                                                    </span>
                                                </button>

                                            </div>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                        @if ($this->serials->hasPages())
                            <section class="py-6 w-full">
                                <div class="w-full p-4 font-mono">
                                    <div class="flex flex-col justify-center items-center gap-4">
                                        <div class="text-sm text-slate-400">
                                            Affichage {{ $this->serials->firstItem() }} à
                                            {{ $this->serials->lastItem() }} sur
                                            {{ $this->serials->total() }} séries
                                        </div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if (!$this->serials->onFirstPage())
                                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                                    wire:target="previousPage"
                                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                                    Précédent
                                                </button>
                                            @endif

                                            @foreach ($this->serials->getUrlRange(1, $this->serials->lastPage()) as $page => $url)
                                                <button @disabled($page === $this->serials->currentPage())
                                                    wire:click="gotoPage({{ $page }})"
                                                    class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->serials->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                                    {{ $page }}
                                                </button>
                                            @endforeach

                                            @if ($this->serials->hasMorePages())
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
                    @else
                        <div class="flex w-full itecn justify-center">
                            <div class="p-6 flex justify-center text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="text-4xl">🎯</span>
                                    <p class="text-slate-500 text-sm">Aucune série trouvée </p>
                                    @if ($search || $is_active)
                                        <button wire:click="resetFilters"
                                            class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                            Réinitialiser les filtres
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </section>

    </div>

</div>

