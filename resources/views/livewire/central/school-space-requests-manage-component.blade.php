<div class="min-h-screen bg-[#070b14] text-slate-100 space-y-6 p-3 sm:p-5 mb-20">

    {{-- ===================== LOADER (lueur + miroir) ===================== --}}
    <div wire:loading.flex
        wire:target="search, status, clearFilters, previousPage, nextPage, gotoPage, validateRequest, rejectRequest, deleteRequest, sendCredentialsToTenant"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-[#070b14]/75 backdrop-blur-sm">
        <div class="relative flex flex-col items-center gap-4">
            {{-- Glow externe --}}
            <div class="absolute inset-0 scale-150 rounded-full bg-indigo-500/25 blur-2xl animate-pulse"></div>
            <div class="absolute inset-0 scale-125 rounded-full bg-sky-400/15 blur-xl animate-ping"></div>

            {{-- Cercle miroir --}}
            <div class="relative h-14 w-14">
                <div class="absolute inset-0 rounded-full border-2 border-indigo-400/20"></div>
                <div class="absolute inset-0 rounded-full border-t-2 border-r-2 border-indigo-400 animate-spin"></div>
                <div
                    class="absolute inset-2 rounded-full bg-gradient-to-tr from-indigo-500/30 via-transparent to-sky-400/20">
                </div>
            </div>

            <span class="relative text-xs font-medium tracking-wider text-slate-400">
                Chargement…
            </span>
        </div>
    </div>

    {{-- ===================== HEADER ===================== --}}
    <section
        class="relative overflow-hidden rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-xl shadow-black/20">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/[0.06] via-transparent to-transparent"></div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
            <div class="min-w-0">
                <div
                    class="inline-flex items-center gap-1.5 rounded-full border border-indigo-500/20 bg-indigo-500/10 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-indigo-300">
                    <x-lucide-credit-card class="h-3 w-3" />
                    Central
                </div>

                <h1 class="mt-3 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                    Demandes d’espace école
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    @if ($status)
                        Demandes avec le statut
                        <span class="font-semibold text-amber-400">{{ $status }}</span>
                    @else
                        Supervision et validation des demandes d’espace
                    @endif
                </p>
            </div>

            <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
                <button type="button"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/30 transition-all hover:bg-emerald-500">
                    <x-lucide-check-check class="h-4 w-4" />
                    Tout approuver
                </button>

                <button type="button"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-rose-500/25 bg-rose-500/10 px-5 text-sm font-semibold text-rose-300 transition-all hover:bg-rose-500/20">
                    <x-lucide-trash-2 class="h-4 w-4" />
                    Tout supprimer
                </button>
            </div>
        </div>
    </section>

    {{-- ===================== FILTERS ===================== --}}
    <section class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-4 sm:p-5 shadow-lg shadow-black/10">
        <div class="space-y-4">
            {{-- Search --}}
            <div class="relative">
                <x-lucide-search class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
                <input wire:model.live.debounce.500ms="search" type="text"
                    placeholder="Rechercher une école, un directeur, un domaine…"
                    class="h-11 w-full rounded-xl border border-white/10 bg-[#070b14] pl-10 pr-4 text-sm text-slate-200 placeholder:text-slate-600 transition-all focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30">
            </div>

            {{-- Selects --}}
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <select wire:model.live="status"
                    class="h-11 rounded-xl border border-white/10 bg-[#070b14] px-3.5 text-sm text-slate-200 transition-all focus:border-indigo-500/50 focus:outline-none">
                    <option value="">Tous les statuts</option>
                    @foreach ($tenant_request_statuses as $stat)
                        <option value="{{ $stat }}">{{ $stat }}</option>
                    @endforeach
                </select>

                <select
                    class="h-11 rounded-xl border border-white/10 bg-[#070b14] px-3.5 text-sm text-slate-200 transition-all focus:border-indigo-500/50 focus:outline-none">
                    <option value="">Tous les types d’écoles</option>
                    @foreach ($school_types as $sch)
                        <option value="{{ $sch }}">{{ $sch }}</option>
                    @endforeach
                </select>

                <select
                    class="h-11 rounded-xl border border-white/10 bg-[#070b14] px-3.5 text-sm text-slate-200 transition-all focus:border-indigo-500/50 focus:outline-none">
                    <option value="">Tous les enseignements</option>
                    @foreach ($this->enseignement_types as $ens)
                        <option value="{{ $ens }}">{{ $ens }}</option>
                    @endforeach
                </select>

                <button wire:click="clearFilters" type="button"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 text-sm font-medium text-slate-300 transition-all hover:bg-white/10">
                    <x-lucide-x class="h-4 w-4" />
                    Réinitialiser
                </button>
            </div>
        </div>
    </section>

    {{-- ===================== LISTE ===================== --}}
    <section class="space-y-4">

        {{-- Titre + Export --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-white">
                    @if ($status)
                        Demandes · <span class="text-amber-400">{{ $status }}</span>
                    @else
                        Toutes les demandes
                    @endif
                </h2>
                <p class="mt-0.5 text-sm text-slate-500">
                    Validation et supervision
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 text-sm font-medium text-emerald-300 transition-all hover:bg-emerald-500/20">
                    <x-lucide-file-spreadsheet class="h-4 w-4" />
                    Excel
                </button>
                <button type="button"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-rose-500/20 bg-rose-500/10 px-4 text-sm font-medium text-rose-300 transition-all hover:bg-rose-500/20">
                    <x-lucide-file-text class="h-4 w-4" />
                    PDF
                </button>
            </div>
        </div>

        @if (count($this->demandes_requests))
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                @foreach ($this->demandes_requests as $item)
                    <article wire:key="demande-{{ $item->id }}"
                        class="group relative overflow-hidden rounded-2xl border border-white/[0.06] bg-[#0f1523] shadow-lg shadow-black/10 transition-all duration-300 hover:border-indigo-500/25">
                        {{-- Accent bar --}}
                        <div
                            class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-indigo-500 via-sky-500 to-cyan-400 opacity-80">
                        </div>

                        <div class="relative p-5 sm:p-6">

                            {{-- Header card --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex min-w-0 items-start gap-3">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-indigo-500/20 bg-indigo-500/15">
                                        <x-lucide-school class="h-6 w-6 text-indigo-400" />
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-bold text-white">
                                            {{ $item->school_name }}
                                        </h3>
                                        <p class="mt-0.5 truncate text-sm text-slate-500">
                                            {{ $item->enseignement_type }}
                                        </p>
                                        <span
                                            class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-0.5 text-[11px] font-medium text-emerald-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                            {{ $item->school_type }}
                                        </span>
                                    </div>
                                </div>

                                <span
                                    class="shrink-0 rounded-lg border border-white/5 bg-[#070b14] px-2.5 py-1 font-mono text-xs font-semibold text-slate-400">
                                    #{{ __zero($loop->iteration) }}
                                </span>
                            </div>

                            {{-- Directeur --}}
                            <div class="mt-5 rounded-xl border border-white/[0.04] bg-[#070b14]/50 p-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/5">
                                        <x-lucide-user class="h-4.5 w-4.5 text-slate-400" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-white">
                                            {{ $item->name }} {{ $item->prenames }}
                                        </p>
                                        <p class="text-[11px] text-slate-500">Directeur</p>
                                    </div>
                                </div>

                                <div class="mt-3 space-y-2 text-sm text-slate-400">
                                    <div class="flex items-center gap-2.5">
                                        <x-lucide-mail class="h-3.5 w-3.5 shrink-0 text-slate-600" />
                                        <span class="truncate">{{ $item->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <x-lucide-phone class="h-3.5 w-3.5 shrink-0 text-slate-600" />
                                        <span>{{ $item->contacts }}</span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <x-lucide-map-pin class="h-3.5 w-3.5 shrink-0 text-slate-600" />
                                        <span class="truncate">{{ $item->adresse }}, {{ $item->country }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Détails école --}}
                            <div class="mt-4 rounded-xl border border-sky-500/15 bg-sky-500/[0.04] p-4">
                                <p
                                    class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-sky-400/80">
                                    <x-lucide-info class="h-3.5 w-3.5" />
                                    Détails
                                </p>
                                <div class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                                    <div>
                                        <span class="text-[11px] text-slate-600">Abréviation</span>
                                        <p class="font-medium text-slate-300">{{ $item->simple_name ?: '—' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[11px] text-slate-600">Domaine</span>
                                        <p class="font-mono text-slate-300">{{ $item->domain_name }}</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-[11px] text-slate-600">Devise</span>
                                        <p class="text-slate-300">{{ $item->school_devise ?: '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-5 flex flex-wrap gap-2">
                                @if ($item->validated)
                                    <button wire:click="sendCredentialsToTenant('{{ $item->domain_name }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="sendCredentialsToTenant('{{ $item->domain_name }}')"
                                        type="button"
                                        class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-xl border border-slate-500/25 bg-slate-500/10 text-sm font-medium text-slate-300 transition-all hover:bg-slate-500/20 disabled:opacity-50">
                                        <span wire:loading.remove
                                            wire:target="sendCredentialsToTenant('{{ $item->domain_name }}')"
                                            class="inline-flex items-center gap-2">
                                            <x-lucide-send class="h-4 w-4" />
                                            Envoyer données
                                        </span>
                                        <span wire:loading
                                            wire:target="sendCredentialsToTenant('{{ $item->domain_name }}')"
                                            class="inline-flex items-center gap-2">
                                            <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                                            Envoi…
                                        </span>
                                    </button>
                                @else
                                    <button wire:click="validateRequest('{{ $item->id }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="validateRequest('{{ $item->id }}')" type="button"
                                        class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-xl border border-emerald-500/25 bg-emerald-500/10 text-sm font-medium text-emerald-300 transition-all hover:bg-emerald-500/20 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="validateRequest('{{ $item->id }}')"
                                            class="inline-flex items-center gap-2">
                                            <x-lucide-user-check class="h-4 w-4" />
                                            Accepter
                                        </span>
                                        <span wire:loading wire:target="validateRequest('{{ $item->id }}')"
                                            class="inline-flex items-center gap-2">
                                            <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                                            …
                                        </span>
                                    </button>

                                    <button wire:click="rejectRequest('{{ $item->id }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="rejectRequest('{{ $item->id }}')" type="button"
                                        class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-xl border border-amber-500/25 bg-amber-500/10 text-sm font-medium text-amber-300 transition-all hover:bg-amber-500/20 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="rejectRequest('{{ $item->id }}')"
                                            class="inline-flex items-center gap-2">
                                            <x-lucide-ban class="h-4 w-4" />
                                            Rejeter
                                        </span>
                                        <span wire:loading wire:target="rejectRequest('{{ $item->id }}')"
                                            class="inline-flex items-center gap-2">
                                            <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                                            …
                                        </span>
                                    </button>

                                    <button wire:click="deleteRequest('{{ $item->id }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="deleteRequest('{{ $item->id }}')" type="button"
                                        class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-rose-500/25 bg-rose-500/10 px-4 text-sm font-medium text-rose-300 transition-all hover:bg-rose-500/20 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="deleteRequest('{{ $item->id }}')"
                                            class="inline-flex items-center gap-2">
                                            <x-lucide-trash-2 class="h-4 w-4" />
                                        </span>
                                        <span wire:loading wire:target="deleteRequest('{{ $item->id }}')"
                                            class="inline-flex items-center gap-2">
                                            <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Footer date --}}
                        <div
                            class="flex items-center justify-center gap-2 border-t border-white/[0.04] px-5 py-3 text-[11px] text-slate-600">
                            <x-lucide-calendar-check class="h-3.5 w-3.5" />
                            Demande du {{ __formatDateTime($item->created_at) }}
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($this->demandes_requests->hasPages())
                <div class="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-slate-500">
                        Affichage {{ $this->demandes_requests->firstItem() }}
                        à {{ $this->demandes_requests->lastItem() }}
                        sur {{ $this->demandes_requests->total() }}
                    </p>

                    <div class="flex flex-wrap items-center gap-1.5">
                        @if (!$this->demandes_requests->onFirstPage())
                            <button wire:click="previousPage" wire:loading.attr="disabled"
                                class="h-9 rounded-lg border border-white/10 bg-white/5 px-3 text-xs text-slate-300 transition-all hover:bg-white/10 disabled:opacity-50">
                                ← Précédent
                            </button>
                        @endif

                        @foreach ($this->demandes_requests->getUrlRange(1, $this->demandes_requests->lastPage()) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})" @disabled($page === $this->demandes_requests->currentPage())
                                class="h-9 min-w-[36px] rounded-lg px-2 text-xs font-medium transition-all
                                    {{ $page === $this->demandes_requests->currentPage()
                                        ? 'bg-indigo-600 text-white'
                                        : 'border border-white/10 bg-white/5 text-slate-300 hover:bg-white/10' }}">
                                {{ $page }}
                            </button>
                        @endforeach

                        @if ($this->demandes_requests->hasMorePages())
                            <button wire:click="nextPage" wire:loading.attr="disabled"
                                class="h-9 rounded-lg border border-white/10 bg-white/5 px-3 text-xs text-slate-300 transition-all hover:bg-white/10 disabled:opacity-50">
                                Suivant →
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] py-20 text-center">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/5 bg-white/5">
                    <x-lucide-inbox class="h-6 w-6 text-slate-600" />
                </div>
                <p class="text-sm font-medium text-slate-400">Aucune demande trouvée</p>
                <p class="mt-1 text-xs text-slate-600">Essayez de modifier vos filtres</p>
                @if ($search || $status)
                    <button wire:click="clearFilters" type="button"
                        class="mt-5 inline-flex h-10 items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 text-sm text-slate-300 transition-all hover:bg-white/10">
                        <x-lucide-x class="h-4 w-4" />
                        Réinitialiser les filtres
                    </button>
                @endif
            </div>
        @endif
    </section>
</div>
