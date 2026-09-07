<div class="min-h-screen bg-[#070b14] text-slate-100" x-data="{
    loaded: false,
    init() {
        this.$nextTick(() => this.loaded = true)
    }
}">
    <div class="mx-auto max-w-7xl space-y-6 p-3 sm:p-5" x-show="loaded"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0">

        {{-- ════════════════ HEADER ════════════════ --}}
        <header
            class="relative overflow-hidden rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 shadow-xl shadow-black/20 sm:p-6">
            <div
                class="pointer-events-none absolute inset-0 bg-gradient-to-br from-indigo-500/[0.07] via-transparent to-emerald-500/[0.04]">
            </div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-1.5">
                    <div
                        class="inline-flex items-center gap-1.5 rounded-full border border-indigo-500/20 bg-indigo-500/10 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-indigo-300">
                        <x-lucide-credit-card class="h-3 w-3" />
                        Central
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-white sm:text-2xl">
                        Abonnements
                    </h1>
                    <p class="text-sm text-slate-500">
                        Suivi et gestion des abonnements des établissements
                    </p>
                </div>

                <div class="relative w-full shrink-0 sm:w-72">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                        <x-lucide-search class="h-4 w-4" />
                    </span>
                    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher une école…"
                        class="h-11 w-full rounded-xl border border-white/10 bg-[#070b14]/80 pl-10 pr-10 text-sm text-slate-200 placeholder:text-slate-600 backdrop-blur-sm transition-all focus:border-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    @if ($search)
                        <button wire:click="$set('search', '')" type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 transition-colors hover:text-slate-300">
                            <x-lucide-x class="h-4 w-4" />
                        </button>
                    @endif
                </div>
            </div>
        </header>

        {{-- ════════════════ FILTRES + COMPTEURS ════════════════ --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                @foreach ([
        'actif' => ['label' => 'En cours', 'icon' => 'clock'],
        'expire' => ['label' => 'Expirés', 'icon' => 'circle-x'],
        null => ['label' => 'Tout', 'icon' => 'list'],
    ] as $key => $tab)
                    <button wire:click="$set('filter', {{ $key === null ? 'null' : "'{$key}'" }})" type="button"
                        class="group relative inline-flex h-9 shrink-0 items-center gap-1.5 rounded-full px-3.5 text-xs font-medium transition-all duration-200
                            {{ $filter === $key
                                ? 'bg-white text-[#070b14] shadow-sm shadow-sky-800'
                                : 'border border-white/5 bg-white/5 text-slate-400 hover:bg-white/10 hover:text-slate-200' }}">
                        <x-dynamic-component :component="'lucide-' . $tab['icon']" class="h-3.5 w-3.5" />
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>

            <div class="text-xs tabular-nums text-slate-500">
                {{ $this->subscriptions->total() }}
                abonnement{{ $this->subscriptions->total() > 1 ? 's' : '' }}
            </div>
        </div>

        {{-- ════════════════ LISTE ════════════════ --}}
        <div class="relative space-y-3" wire:loading.class="pointer-events-none" wire:target="filter, search, gotoPage">

            {{-- Overlay chargement --}}
            <div wire:loading.flex wire:target="filter, search, gotoPage"
                class="absolute inset-0 z-20 flex items-center justify-center rounded-2xl bg-[#070b14]/40 backdrop-blur-[2px]">
                <div class="relative flex h-14 w-14 items-center justify-center">
                    <div class="absolute inset-0 animate-pulse rounded-full bg-indigo-500/30 blur-xl"></div>
                    <div class="absolute inset-0 animate-ping rounded-full bg-indigo-400/20 blur-md"></div>
                    <div class="relative h-12 w-12 rounded-full border-2 border-indigo-400/30">
                        <div class="absolute inset-0 animate-spin rounded-full border-t-2 border-indigo-400"></div>
                    </div>
                </div>
            </div>

            <div wire:loading.class="opacity-30 scale-[0.985] blur-[1px]" wire:target="filter, search, gotoPage"
                class="space-y-3 transition-all duration-300 ease-out">

                @forelse ($this->subscriptions as $subscription)
                    @php
                        $isExpired = $subscription->isExpired();
                        $isCurrent =
                            $subscription->tenant?->activeSubscription?->id === $subscription->id && !$isExpired;
                        $isFuture =
                            $subscription->tenant?->activeSubscription &&
                            $subscription->tenant->activeSubscription->id !== $subscription->id &&
                            !$isExpired;
                    @endphp

                    <article wire:key="subscription-{{ $subscription->id }}" x-data="{ show: false }"
                        x-init="setTimeout(() => show = true, {{ $loop->index * 35 }})" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-3"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="group relative overflow-hidden rounded-2xl border border-white/[0.06] bg-[#0f1523] shadow-lg shadow-black/10 transition-all duration-300 hover:border-indigo-500/25 hover:shadow-indigo-500/5">

                        {{-- Barre d’accent --}}
                        <div
                            class="absolute bottom-0 left-0 top-0 w-1 rounded-l-2xl transition-colors
                                {{ $isExpired ? 'bg-red-400' : ($isCurrent ? 'bg-emerald-400' : 'bg-sky-400') }}">
                        </div>

                        <div class="flex flex-col gap-4 p-4 pl-5 sm:p-5 lg:flex-row lg:items-center">

                            {{-- École --}}
                            <div class="flex min-w-0 items-center gap-3 lg:w-[240px] lg:shrink-0">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-indigo-500/20 bg-indigo-500/15 text-indigo-300 transition-transform group-hover:scale-105">
                                    <x-lucide-school class="h-5 w-5" />
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-white">
                                        {{ $subscription->tenant?->school_name ?? '—' }}
                                        @if ($subscription->tenant?->simple_name)
                                            <span class="text-[11px] font-medium text-slate-500">
                                                ({{ $subscription->tenant->simple_name }})
                                            </span>
                                        @endif
                                    </p>
                                    <p class="truncate text-[11px] text-slate-500">
                                        {{ $subscription->tenant?->getFullName() ?? $subscription->tenant_id }}
                                    </p>
                                    <p class="truncate text-[11px] text-amber-400/90">
                                        {{ $subscription->tenant?->email ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Plan + dates --}}
                            <div class="min-w-0 lg:flex-1">
                                <p class="text-sm font-medium text-slate-200">
                                    {{ $subscription->plan?->name ?? '—' }}
                                    @if ($subscription->key)
                                        <span class="ml-1 text-xs font-normal text-lime-500/90">
                                            #{{ $subscription->key }}
                                        </span>
                                    @endif
                                </p>
                                <p class="mt-0.5 text-[11px] text-slate-500">
                                    <span class="font-medium text-slate-400">
                                        {{ number_format($subscription->plan?->price ?? 0, 0, ',', ' ') }} FCFA
                                    </span>
                                    <span class="mx-1.5 text-slate-700">·</span>
                                    {{ __formatDateTime($subscription->created_at) }}
                                </p>
                                <p class="mt-1 inline-flex flex-wrap items-center gap-x-2 text-xs text-slate-400">
                                    <span>
                                        Expire le
                                        <span class="font-medium text-slate-300">
                                            {{ __formatDateTime($subscription->expire_at) }}
                                        </span>
                                    </span>
                                    <span class="text-slate-600">·</span>
                                    <span
                                        class="font-semibold tabular-nums
                                            {{ $isExpired ? 'text-red-400' : ($subscription->daysRemaining() < 15 ? 'text-amber-400' : 'text-emerald-400') }}">
                                        {{ $isExpired ? 'Expiré' : $subscription->daysRemaining() . ' jours restants' }}
                                    </span>
                                </p>
                            </div>

                            {{-- Transaction --}}
                            <div class="min-w-0 shrink-0 lg:w-[170px]">
                                @if ($subscription->subscriptionRequest?->transaction_id ?? ($subscription->transaction_id ?? null))
                                    <span
                                        class="inline-flex max-w-full items-center gap-1.5 rounded-lg border border-white/5 bg-[#070b14] px-2.5 py-1.5 font-mono text-[11px] text-slate-300">
                                        <x-lucide-receipt class="h-3 w-3 shrink-0 text-emerald-400" />
                                        <span class="truncate">
                                            {{ $subscription->subscriptionRequest?->transaction_id ?? $subscription->transaction_id }}
                                        </span>
                                    </span>
                                @else
                                    <span class="text-[11px] italic text-slate-600">Pas de transaction</span>
                                @endif
                            </div>

                            {{-- Statut --}}
                            <div class="shrink-0">
                                @if ($subscription->isSuspended() && !$isExpired)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border border-amber-500/20 bg-amber-500/10 px-2.5 py-1 text-[11px] font-medium text-amber-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                        Suspendu
                                    </span>
                                @elseif ($isCurrent)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-1 text-[11px] font-medium text-emerald-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                        En cours
                                    </span>
                                @elseif ($isFuture)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border border-sky-500/20 bg-sky-500/10 px-2.5 py-1 text-[11px] font-medium text-sky-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                                        En file d’attente
                                    </span>
                                @elseif ($isExpired)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border border-red-500/20 bg-red-500/10 px-2.5 py-1 text-[11px] font-medium text-red-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                        Expiré
                                    </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div
                                class="flex shrink-0 flex-wrap items-center gap-1.5 lg:border-l lg:border-white/5 lg:pl-3">

                                {{-- Modules --}}
                                <a href="{{ route('central.manage.subscription.modules', $subscription->id) }}"
                                    title="Gérer les modules"
                                    class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-indigo-500/20 bg-indigo-500/10 px-3 text-xs font-medium text-indigo-300 transition-all hover:bg-indigo-500/20 hover:text-indigo-200
                                        {{ $isExpired ? 'opacity-60' : '' }}">
                                    <x-lucide-puzzle class="h-3.5 w-3.5" />
                                    Modules
                                </a>

                                {{-- Activer / Suspendre --}}
                                @if (!$isExpired)
                                    <button wire:click="toggleSubscriptionStatus({{ $subscription->id }})"
                                        type="button"
                                        title="{{ $subscription->isSuspended() ? 'Réactiver' : 'Suspendre temporairement' }}"
                                        class="inline-flex h-9 items-center gap-1.5 rounded-lg border px-3 text-xs font-medium transition-all
                                            {{ $subscription->isSuspended()
                                                ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20'
                                                : 'border-amber-500/20 bg-amber-500/10 text-amber-400 hover:bg-amber-500/20' }}">
                                        @if ($subscription->isSuspended())
                                            <x-lucide-play class="h-3.5 w-3.5" />
                                            Activer
                                        @else
                                            <x-lucide-pause class="h-3.5 w-3.5" />
                                            Suspendre
                                        @endif
                                    </button>
                                @endif

                                {{-- Supprimer --}}
                                <button wire:click="deleteSubscription({{ $subscription->id }})" type="button"
                                    title="Supprimer cet abonnement"
                                    class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-red-500/20 bg-red-500/10 px-3 text-xs font-medium text-red-400 transition-all hover:bg-red-500/20 hover:text-red-300">
                                    <x-lucide-trash-2 class="h-3.5 w-3.5" />
                                    Suppr.
                                </button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] py-20 text-center">
                        <div
                            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/5 bg-white/5">
                            <x-lucide-inbox class="h-6 w-6 text-slate-600" />
                        </div>
                        <p class="text-sm font-medium text-slate-400">Aucun abonnement</p>
                        <p class="mt-1 text-xs text-slate-600">Aucun résultat pour ce filtre</p>
                    </div>
                @endforelse

                @if ($this->subscriptions->hasPages())
                    <div class="flex justify-center pt-4 sm:justify-end">
                        {{ $this->subscriptions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

