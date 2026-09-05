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
            class="relative overflow-hidden rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-6 shadow-xl shadow-black/20">
            {{-- subtle gradient accent --}}
            <div
                class="pointer-events-none absolute inset-0 bg-gradient-to-br from-indigo-500/[0.07] via-transparent to-amber-500/[0.04]">
            </div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-1.5">
                    <div
                        class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-amber-300">
                        <x-lucide-inbox class="h-3 w-3" />
                        Central
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-white sm:text-2xl">
                        Les abonnements approuvés
                    </h1>
                    <p class="text-sm text-slate-500">
                        Les abonnements des écoles
                    </p>
                </div>

                <div class="relative w-full shrink-0 sm:w-72">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                        <x-lucide-search class="h-4 w-4" />
                    </span>
                    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher une école…"
                        class="h-11 w-full rounded-xl border border-white/10 bg-[#070b14]/80 pl-10 pr-4 text-sm text-slate-200 placeholder:text-slate-600 backdrop-blur-sm transition-all focus:border-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    @if ($search)
                        <button wire:click="$set('search', '')" type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-300 transition-colors">
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
        'actif' => ['label' => 'En cours', 'icon' => 'clock', 'color' => 'amber'],
        'expire' => ['label' => 'Expirés', 'icon' => 'circle-x', 'color' => 'emerald'],
        null => ['label' => 'Tout', 'icon' => 'list', 'color' => 'slate'],
    ] as $key => $tab)
                    <button wire:click="$set('filter', '{{ $key }}')" type="button"
                        class="group relative h-9 shrink-0 rounded-full px-3.5 text-xs font-medium transition-all duration-200 inline-flex items-center gap-1.5
                            {{ $filter === $key
                                ? 'bg-white text-[#070b14] shadow-sm shadow-sky-800'
                                : 'bg-white/5 text-slate-400 hover:bg-white/10 hover:text-slate-200 border border-white/5' }}">
                        <x-dynamic-component :component="'lucide-' . $tab['icon']" class="h-3.5 w-3.5" />
                        {{ $tab['label'] }}

                        {{-- active indicator underline animation --}}

                    </button>
                @endforeach
            </div>

            <div class="text-xs text-slate-500 tabular-nums">
                {{ $this->subscriptions->total() }} abonnement{{ $this->subscriptions->total() > 1 ? 's' : '' }}
                {{ $filter }}{{ $this->subscriptions->total() > 1 ? 's' : '' }}
            </div>
        </div>

        {{-- ════════════════ LISTE ════════════════ --}}
        {{-- ════════════════ LISTE ════════════════ --}}
        <div class="relative space-y-3" wire:loading.class="pointer-events-none" wire:target="filter, search, gotoPage">
            {{-- Overlay lueur pendant le chargement --}}
            <div wire:loading.flex wire:target="filter, search, gotoPage"
                class="absolute inset-0 z-20 flex items-center justify-center rounded-2xl bg-[#070b14]/40 backdrop-blur-[2px]">
                <div class="relative flex h-14 w-14 items-center justify-center">
                    {{-- Glow externe --}}
                    <div class="absolute inset-0 rounded-full bg-indigo-500/30 blur-xl animate-pulse"></div>
                    <div class="absolute inset-0 rounded-full bg-indigo-400/20 blur-md animate-ping"></div>

                    {{-- Cercle avec effet miroir / shimmer --}}
                    <div class="relative h-12 w-12 rounded-full border-2 border-indigo-400/30">
                        <div class="absolute inset-0 rounded-full border-t-2 border-indigo-400 animate-spin"></div>
                        <div
                            class="absolute inset-1 rounded-full bg-gradient-to-tr from-indigo-500/20 via-transparent to-indigo-300/10">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contenu de la liste (avec effet de fondu) --}}
            <div wire:loading.class="opacity-30 scale-[0.985] blur-[1px]" wire:target="filter, search, gotoPage"
                class="space-y-3 transition-all duration-300 ease-out">
                @forelse ($this->subscriptions as $subscription)
                    <article wire:key="subscription-{{ $subscription->id }}" x-data="{ show: false }"
                        x-init="setTimeout(() => show = true, {{ $loop->index * 35 }})" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-3"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="group relative overflow-hidden rounded-2xl bg-[#0f1523] border border-white/6 hover:border-indigo-500/25 transition-all duration-300 shadow-lg shadow-black/10 hover:shadow-indigo-500/5">
                        {{-- Barre d’accent gauche --}}
                        <div
                            class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl transition-colors @if (!$subscription->isExpired()) bg-green-400 @else bg-red-400 @endif">
                        </div>

                        <div class="flex flex-col gap-4 p-4 pl-5 sm:p-5 lg:flex-row lg:items-center font-mono">
                            {{-- École --}}
                            <div class="flex min-w-0 items-center gap-3 lg:w-[240px] shrink-0">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-500/15 border border-indigo-500/20 text-indigo-300 transition-transform group-hover:scale-105">
                                    <x-lucide-school class="h-5 w-5" />
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-white">
                                        <span>
                                            {{ $subscription->tenant->school_name }}
                                        </span>
                                        <span class="text-2xs font-semibold text-slate-500">
                                            ({{ $subscription->tenant->simple_name }})
                                        </span>
                                    </p>
                                    <p class="truncate text-2xs font-semibold text-slate-500">
                                        {{ $subscription->tenant->getFullName() ?? $subscription->tenant_id }}
                                    </p>
                                    <p class="truncate text-[11px] text-amber-400">
                                        {{ $subscription->tenant->email ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Plan --}}
                            <div class="min-w-0 lg:flex-1">
                                <p class="text-sm font-medium text-slate-200">{{ $subscription->plan->name }}</p>
                                <p class="mt-0.5 text-[11px] text-slate-500">
                                    <span
                                        class="font-medium text-slate-400">{{ number_format($subscription->plan->price, 0, ',', ' ') }}
                                        FCFA</span>
                                    <span class="mx-1.5 text-slate-700">·</span>
                                    {{ $subscription->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p
                                    class="mt-1 text-xs text-slate-400 rounded-lg p-1 bg-green-500/20 inline-flex gap-x-2 px-3">
                                    Expire le
                                    <span
                                        class="text-slate-200 font-medium">{{ $subscription->expire_at->format('d/m/Y') }}</span>
                                    ·
                                    <span
                                        class="text-emerald-300 font-semibold tabular-nums">{{ $subscription->daysRemaining() }}
                                        jours</span>
                                    restants
                                </p>
                            </div>

                            {{-- Transaction --}}
                            <div class="min-w-0 shrink-0 lg:w-[190px]">
                                @if ($subscription->transaction_id)
                                    <span
                                        class="inline-flex max-w-full items-center gap-1.5 rounded-lg border border-white/5 bg-[#070b14] px-2.5 py-1.5 font-mono text-[11px] text-slate-300">
                                        <x-lucide-receipt class="h-3 w-3 shrink-0 text-emerald-400" />
                                        <span class="truncate">{{ $subscription->transaction_id }}</span>
                                    </span>
                                @else
                                    <span class="text-[11px] italic text-slate-600">Pas de transaction</span>
                                @endif

                                @if ($subscription->payment_reminder_sent_at)
                                    <p class="mt-1.5 flex items-center gap-1 text-[10px] text-amber-400/80">
                                        <x-lucide-bell class="h-3 w-3" />
                                        Relancé {{ $subscription->payment_reminder_sent_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>

                            {{-- Statut --}}
                            <div class="shrink-0">
                                @if (!$subscription->isExpired())
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-medium bg-green-500/10 text-green-300 border-green-500/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-400"></span>
                                        {{ 'En cours...' }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-medium bg-red-500/10 text-red-300 border-red-500/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                        {{ 'expiré' }}
                                    </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex shrink-0 items-center gap-1 text-sm lg:border-l lg:border-white/5 lg:pl-3">
                                <button wire:click="deleteSubscription({{ $subscription->id }})" type="button"
                                    title="Supprimer cet abonnement"
                                    class="ml-1 flex h-9 items-center gap-1.5 rounded-lg border border-red-500/20 bg-red-500/10 px-3 text-xs font-medium text-red-400 transition-all hover:bg-red-500/20 hover:text-red-300">
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
                        <p class="mt-1 text-xs text-slate-600">Aucun abonnement pour ce filtre</p>
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

