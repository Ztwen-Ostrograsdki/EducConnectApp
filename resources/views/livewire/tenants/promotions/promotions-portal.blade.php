<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8">
        <section class="mb-6">

            <div class="relative overflow-hidden  rounded-[32px]  border border-slate-800 bg-slate-900">
                <div class="absolute inset-0  bg-gradient-to-br from-indigo-500/10 via-slate-900 to-slate-900">
                </div>
                <div class="relative p-5 sm:p-6 lg:p-8">

                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-8">

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl sm:text-3xl font-bold">
                                    Dashboard Promotions
                                </h1>

                                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">
                                    Gestion Académique
                                </span>

                            </div>

                            <p class="mt-3 text-slate-400 max-w-3xl">

                                Vue globale des promotions,
                                performances académiques,
                                statistiques des apprenants
                                et gestion des classes.

                            </p>

                            {{-- BADGES --}}
                            <div class="mt-6 flex flex-wrap gap-3">

                                <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">

                                    {{ __zero(tenancy()->tenant?->promotionsCount()) }} Promotions

                                </div>

                                <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">

                                    {{ __zero($this->classes) }} Classes

                                </div>

                                <div class="px-4 py-2 rounded-2xl bg-slate-800 border border-slate-700">

                                    {{ __zero($this->students) }} Apprenants

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <section class="mb-6">

            <div class="grid grid-cols-2 lg:grid-cols-4 2xl:grid-cols-5 gap-4">

                @foreach ([['Promotions', __zero(tenancy()->tenant?->promotionsCount()), 'text-indigo-400'], ['Classes', __zero($this->classes), 'text-sky-400'], ['Apprenants', __zero($this->students), 'text-emerald-400'], ['Séries', __zero(count($this->serials)), 'text-amber-400'], ['Filières', __zero(count($this->filiars)), 'text-rose-400']] as $kpi)
                    <div class="rounded-3xl bg-slate-900 border border-slate-800 p-5">
                        <p class="text-sm text-slate-400">
                            {{ $kpi[0] }}
                        </p>
                        <h2 class="mt-3 text-2xl font-bold {{ $kpi[1] ? $kpi[2] : '' }}">
                            {{ $kpi[1] }}
                        </h2>

                    </div>
                @endforeach

            </div>

        </section>
        <section class="my-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="text-sm text-slate-400">

                </div>
                <div class="flex flex-wrap gap-3">

                    <a wire:navigate href="{{ route('tenant.promotion.create') }}"
                        class="px-5 py-3 flex justify-center items-center rounded-2xl bg-blue-500 hover:bg-blue-800 transition">
                        <span class="flex justify-center items-center">
                            <span class="flex justify-center items-center gap-x-3">
                                <x-lucide-plus class="w-4 h-4" />
                                <span>Nouvelle Promotion</span>
                            </span>
                        </span>
                    </a>

                    <button class="h-11 px-5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 transition">

                        Export Excel

                    </button>

                    <button class="h-11 px-5 rounded-2xlbg-rose-500 hover:bg-rose-600 transition">

                        Export PDF

                    </button>

                </div>

            </div>
        </section>
        <section>
            <div class="rounded-[32px] bg-slate-900 border border-slate-800 overflow-hidden p-3 mb-20">
                <div class="border-b border-slate-800 my-5 py-6">
                    <div class="flex flex-col gap-y-3">
                        <div>
                            <h2 class="text-xl font-semibold">
                                Liste des Promotions
                            </h2>

                            <p class="mt-1 text-sm text-slate-400 font-mono">
                                Analyse détaillée des promotions,
                                performances et statistiques.

                            </p>

                        </div>
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3 w-full 2xl:w-auto">
                            <div class="flex-1 min-w-0">
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="search"
                                        placeholder="Rechercher une promotion..."
                                        class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm outline-none focus:border-indigo-500 transition-all" />
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">🔍</div>
                                    <div wire:loading wire:target="search"
                                        class="absolute right-4 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin w-4 h-4 text-indigo-400" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <select wire:model.live='filiar_id'
                                class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm">

                                <option>Toutes les Filières</option>
                                @foreach ($this->filiars as $filiar)
                                    <option value="{{ $filiar->id }}">{{ $filiar->name }}
                                        (<span>{{ $filiar->code }}</span>)
                                    </option>
                                @endforeach
                            </select>

                            <select wire:model.live='serial_id'
                                class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm">

                                <option>Toutes les séries</option>
                                @foreach ($this->serials as $serial)
                                    <option value="{{ $serial->id }}">{{ $serial->name }}
                                        (<span>{{ $serial->code }}</span>)
                                    </option>
                                @endforeach
                            </select>

                            <button wire:click="resetFilters"
                                class="h-12 px-5 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all text-sm">
                                Réinitialiser
                            </button>

                        </div>

                    </div>

                </div>
                <div class="w-full flex gap-x-1 justify-start items-center">
                    <div wire:loading
                        wire:target='search,serial_id,filiar_id,previousPage,nextPage,resetFilters,gotoPage'
                        class="fixed inset-0 flex items-center justify-center bg-indigo-800/40"
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
                </div>
                <div wire:loading.class="opacity-20"
                    wire:target='search,serial_id,filiar_id,previousPage,nextPage,resetFilters,gotoPage'
                    class="overflow-x-auto mb-10">

                    @if (count($promotions))
                        <table class="z-table-border w-full">

                            <thead class="bg-slate-950 border-b border-slate-800 text-center">

                                <tr>

                                    <th class="px-6 py-4  text-sm text-slate-400">
                                        N°
                                    </th>
                                    <th class="px-6 py-4  text-sm text-slate-400">
                                        Promotion
                                    </th>

                                    <th class="px-4 py-4 text-center text-sm text-slate-400">
                                        <span class="flex flex-col">
                                            <span>Nombre de</span>
                                            <span>classes</span>
                                        </span>
                                    </th>

                                    <th class="px-4 py-4 text-center text-sm text-slate-400">
                                        <span class="flex flex-col">
                                            <span>Effectif</span>
                                            <span>apprenants</span>
                                        </span>
                                    </th>

                                    <th class="px-6 py-4  text-sm text-slate-400">
                                        Meilleur Élève
                                    </th>

                                    <th class="px-6 py-4  text-sm text-slate-400">
                                        Plus Faible
                                    </th>

                                    <th class="px-6 py-4 text-center text-sm text-slate-400">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            {{-- BODY --}}
                            <tbody class="divide-y divide-slate-800 text-slate-400">

                                @foreach ($promotions as $promo)
                                    <tr class="hover:bg-slate-800/40 transition-colors duration-200">

                                        <td class="px-6 py-5 truncate">
                                            {{ __zero($promotions->firstItem() + $loop->iteration - 1) }}</td>

                                        <td class="px-6 py-5">

                                            <a wire:navigate
                                                href="{{ route('tenant.promotion.profil', ['promotion_slug' => $promo->slug]) }}"
                                                class="hover:underline underline-offset-4 hover:text-amber-600">

                                                <h3 class="font-semibold text-sm">

                                                    {{ $promo->name . ' ' . $promo->specialityModel()?->code }}

                                                </h3>

                                                <p class="mt-1 text-sm text-slate-400">
                                                    @if ($promo->code)
                                                        {{ $promo->code }}
                                                    @else
                                                        {{ $promo->name . '-' . $promo->specialityModel()?->code }}
                                                    @endif
                                                </p>

                                            </a>

                                        </td>
                                        <td class="px-4 py-5 text-center">
                                            <span class="font-semibold">
                                                {{ __zero($promo->getPromotionClassesOfSchoolYearCount()) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-5 text-center">
                                            <span class="text-indigo-400">
                                                {{ __zero($promo->getPromotionStudentsOfSchoolYearCount()) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div>
                                                <h3 class="font-medium">
                                                    En cours..
                                                </h3>
                                                <p class="text-sm text-emerald-400">
                                                    ....
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div>
                                                <h3 class="font-medium">
                                                    En cours...
                                                </h3>
                                                <p class="text-sm text-rose-400">
                                                    ....
                                                </p>
                                            </div>

                                        </td>

                                        <td class="px-6 py-5">
                                            <div class="flex items-center justify-center gap-2">
                                                <a wire:navigate
                                                    href="{{ route('tenant.promotion.edit', ['promotion_slug' => $promo->slug]) }}"
                                                    class="py-2 px-4 rounded-2xl bg-blue-500/20 text-blue-400  hover:bg-blue-500/70 transition-all text-sm inline-block text-center hover:text-black">
                                                    <span class="inline-flex items-center gap-x-2 justify-center">
                                                        <x-lucide-pen class="w-4 h-4" />
                                                        <span>Editer</span>
                                                    </span>
                                                </a>
                                                <button
                                                    title="{{ $promo->is_active ? 'Fermer ' : 'Activer ' }} cette promotion "
                                                    wire:click="{{ $promo->is_active ? 'closePromotion(' . $promo->id . ')' : 'activatePromotion(' . $promo->id . ')' }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="activatePromotion, closePromotion"
                                                    class="relative py-3 px-4 rounded-xl text-white {{ !$promo->is_active ? 'bg-lime-600/60 hover:bg-lime-500 hover:text-black' : 'bg-orange-500/20 hover:bg-orange-600/60' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50">
                                                    <span wire:loading.remove
                                                        wire:target="activatePromotion, closePromotion"
                                                        class="inline-flex items-center justify-center gap-3">
                                                        <span class="inline-flex items-center justify-center gap-3">
                                                            @if ($promo->is_active)
                                                                <x-lucide-lock class="w-4 h-4" />
                                                                <span>Fermer</span>
                                                            @else
                                                                <x-lucide-unlock class="w-4 h-4" />
                                                                <span>Activer</span>
                                                            @endif
                                                        </span>
                                                    </span>

                                                    <span wire:loading wire:target="activatePromotion, closePromotion"
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
                                                    title="{{ $promo->deleted_at ? 'Restaurer cette promotion de la corbeille ' : 'Mettre cette promotion dans la corbeille ' }} "
                                                    wire:click="{{ $promo->deleted_at ? 'restorePromotion(' . $promo->id . ')' : 'deletePromotion(' . $promo->id . ')' }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="deletePromotion, restorePromotion"
                                                    class="relative py-3 px-4 rounded-xl text-white {{ $promo->deleted_at ? 'bg-green-600/40 hover:bg-green-800/80' : 'bg-red-500/40 hover:bg-red-600/60' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50">
                                                    <span wire:loading.remove
                                                        wire:target="deletePromotion, restorePromotion"
                                                        class="inline-flex items-center justify-center gap-3">
                                                        <span class="inline-flex items-center justify-center gap-3">
                                                            @if ($promo->deleted_at)
                                                                <x-lucide-refresh-ccw class="w-4 h-4" />
                                                                <span>Restaurer</span>
                                                            @else
                                                                <x-lucide-trash class="w-4 h-4" />
                                                                <span>Corbeille</span>
                                                            @endif
                                                        </span>
                                                    </span>

                                                    <span wire:loading wire:target="restorePromotion, deletePromotion"
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
                        @if ($promotions->hasPages())
                            <section class="py-6">
                                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div class="text-sm text-slate-400">
                                            Affichage {{ $promotions->firstItem() }} à {{ $promotions->lastItem() }}
                                            sur
                                            {{ $promotions->total() }} promotions
                                        </div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if (!$promotions->onFirstPage())
                                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                                    wire:target="previousPage"
                                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                                    Précédent
                                                </button>
                                            @endif

                                            @foreach ($promotions->getUrlRange(1, $promotions->lastPage()) as $page => $url)
                                                <button @disabled($page === $promotions->currentPage())
                                                    wire:click="gotoPage({{ $page }})"
                                                    class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $promotions->currentPage() ? 'bg-indigo-500 text-white ' : 'bg-slate-800 hover:bg-slate-700' }}">
                                                    {{ $page }}
                                                </button>
                                            @endforeach

                                            @if ($promotions->hasMorePages())
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
                        <div class="w-full justify-center p-3">
                            <div class="p-5 flex justify-center w-full text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <p class="text-slate-500 text-sm">Aucune promotion trouvée.</p>
                                    @if ($search || $filiar_id || $serial_id)
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

