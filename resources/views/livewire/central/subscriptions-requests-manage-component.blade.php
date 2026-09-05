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
                        Demandes d’abonnement
                    </h1>
                    <p class="text-sm text-slate-500">
                        Demandes envoyées par les écoles
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
        'awaiting' => ['label' => 'En attente', 'icon' => 'clock', 'color' => 'amber'],
        'approved' => ['label' => 'Approuvées', 'icon' => 'check-circle-2', 'color' => 'emerald'],
        'rejected' => ['label' => 'Rejetées', 'icon' => 'x-circle', 'color' => 'rose'],
        'all' => ['label' => 'Toutes', 'icon' => 'list', 'color' => 'slate'],
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
                {{ $this->requests->total() }} demande{{ $this->requests->total() > 1 ? 's' : '' }}
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
                @forelse ($this->requests as $request)
                    <article wire:key="request-{{ $request->id }}" x-data="{ show: false }" x-init="setTimeout(() => show = true, {{ $loop->index * 35 }})"
                        x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-3"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="group relative overflow-hidden rounded-2xl bg-[#0f1523] border border-white/[0.06] hover:border-indigo-500/25 transition-all duration-300 shadow-lg shadow-black/10 hover:shadow-indigo-500/5">
                        {{-- Barre d’accent gauche --}}
                        <div @class([
                            'absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl transition-colors',
                            'bg-amber-400' => $request->statusColor() === 'amber',
                            'bg-sky-400' => $request->statusColor() === 'sky',
                            'bg-emerald-400' => $request->statusColor() === 'emerald',
                            'bg-rose-400' => $request->statusColor() === 'rose',
                            'bg-slate-600' => !in_array($request->statusColor(), [
                                'amber',
                                'sky',
                                'emerald',
                                'rose',
                            ]),
                        ])></div>

                        <div class="flex flex-col gap-4 p-4 pl-5 sm:p-5 lg:flex-row lg:items-center">
                            {{-- École --}}
                            <div class="flex min-w-0 items-center gap-3 lg:w-[240px] shrink-0">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-500/15 border border-indigo-500/20 text-indigo-300 transition-transform group-hover:scale-105">
                                    <x-lucide-school class="h-5 w-5" />
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-white">
                                        {{ $request->tenant->getFullName() ?? $request->tenant_id }}
                                    </p>
                                    <p class="truncate text-[11px] text-slate-500">
                                        {{ $request->tenant->email ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Plan --}}
                            <div class="min-w-0 lg:flex-1">
                                <p class="text-sm font-medium text-slate-200">{{ $request->plan->name }}</p>
                                <p class="mt-0.5 text-[11px] text-slate-500">
                                    <span
                                        class="font-medium text-slate-400">{{ number_format($request->plan->price, 0, ',', ' ') }}
                                        FCFA</span>
                                    <span class="mx-1.5 text-slate-700">·</span>
                                    {{ $request->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            {{-- Transaction --}}
                            <div class="min-w-0 shrink-0 lg:w-[190px]">
                                @if ($request->transaction_id)
                                    <span
                                        class="inline-flex max-w-full items-center gap-1.5 rounded-lg border border-white/5 bg-[#070b14] px-2.5 py-1.5 font-mono text-[11px] text-slate-300">
                                        <x-lucide-receipt class="h-3 w-3 shrink-0 text-emerald-400" />
                                        <span class="truncate">{{ $request->transaction_id }}</span>
                                    </span>
                                @else
                                    <span class="text-[11px] italic text-slate-600">Pas de transaction</span>
                                @endif

                                @if ($request->payment_reminder_sent_at)
                                    <p class="mt-1.5 flex items-center gap-1 text-[10px] text-amber-400/80">
                                        <x-lucide-bell class="h-3 w-3" />
                                        Relancé {{ $request->payment_reminder_sent_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>

                            {{-- Statut --}}
                            <div class="shrink-0">
                                <span @class([
                                    'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-medium',
                                    'bg-amber-500/10 text-amber-300 border-amber-500/20' =>
                                        $request->statusColor() === 'amber',
                                    'bg-sky-500/10 text-sky-300 border-sky-500/20' =>
                                        $request->statusColor() === 'sky',
                                    'bg-emerald-500/10 text-emerald-300 border-emerald-500/20' =>
                                        $request->statusColor() === 'emerald',
                                    'bg-rose-500/10 text-rose-300 border-rose-500/20' =>
                                        $request->statusColor() === 'rose',
                                ])>
                                    <span @class([
                                        'h-1.5 w-1.5 rounded-full',
                                        'bg-amber-400' => $request->statusColor() === 'amber',
                                        'bg-sky-400' => $request->statusColor() === 'sky',
                                        'bg-emerald-400' => $request->statusColor() === 'emerald',
                                        'bg-rose-400' => $request->statusColor() === 'rose',
                                    ])></span>
                                    {{ $request->statusLabel() }}
                                </span>
                            </div>

                            {{-- Actions --}}
                            <div class="flex shrink-0 items-center gap-1 text-sm lg:border-l lg:border-white/5 lg:pl-3">
                                @if ($request->canBeActedOn())
                                    <button wire:click="confirmApprove({{ $request->id }})"
                                        wire:loading.attr="disabled" type="button" title="Approuver"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-slate-500 transition-all hover:border-emerald-500/40 hover:bg-emerald-500/15 hover:text-emerald-300 disabled:opacity-50">
                                        <x-lucide-check-circle class="h-4 w-4" />
                                    </button>

                                    @if ($request->isPending())
                                        <button wire:click="confirmRemindPayment({{ $request->id }})"
                                            wire:loading.attr="disabled" type="button" title="Réclamer le paiement"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-slate-500 transition-all hover:border-amber-500/40 hover:bg-amber-500/15 hover:text-amber-300 disabled:opacity-50">
                                            <x-lucide-bell-ring class="h-4 w-4" />
                                        </button>
                                    @endif

                                    <button wire:click="openRejectModal({{ $request->id }})" type="button"
                                        title="Rejeter"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-slate-500 transition-all hover:border-rose-500/40 hover:bg-rose-500/15 hover:text-rose-300">
                                        <x-lucide-x-circle class="h-4 w-4" />
                                    </button>
                                @endif

                                @if ($request->isApproved())
                                    <button wire:click="deleteSubscription({{ $request->id }})" type="button"
                                        title="Supprimer cet abonnement"
                                        class="ml-1 flex h-9 items-center gap-1.5 rounded-lg border border-red-500/20 bg-red-500/10 px-3 text-xs font-medium text-red-400 transition-all hover:bg-red-500/20 hover:text-red-300">
                                        <x-lucide-trash-2 class="h-3.5 w-3.5" />
                                        Suppr.
                                    </button>
                                @else
                                    <button wire:click="deleteRequest({{ $request->id }})" type="button"
                                        title="Supprimer cette demande"
                                        class="ml-1 flex h-9 items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 text-xs font-medium text-slate-500 transition-all hover:border-orange-500/30 hover:bg-orange-500/15 hover:text-orange-300">
                                        <x-lucide-trash class="h-3.5 w-3.5" />
                                        Suppr.
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] py-20 text-center">
                        <div
                            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/5 bg-white/5">
                            <x-lucide-inbox class="h-6 w-6 text-slate-600" />
                        </div>
                        <p class="text-sm font-medium text-slate-400">Aucune demande</p>
                        <p class="mt-1 text-xs text-slate-600">Aucune demande pour ce filtre</p>
                    </div>
                @endforelse

                @if ($this->requests->hasPages())
                    <div class="flex justify-center pt-4 sm:justify-end">
                        {{ $this->requests->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- ════════════════ MODAL REJET ════════════════ --}}
        @if ($showRejectModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#070b14]/80 p-4 backdrop-blur-sm"
                x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="w-full max-w-md overflow-hidden rounded-2xl border border-white/[0.08] bg-[#0f1523] shadow-2xl shadow-black/50"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    @click.outside="$wire.closeRejectModal()">
                    <div class="flex items-center gap-3 border-b border-white/[0.05] px-5 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-rose-500/25 bg-rose-500/15">
                            <x-lucide-x-octagon class="h-4 w-4 text-rose-400" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-white">Motif du rejet</h2>
                            <p class="text-[11px] text-slate-500">Cette raison sera visible par l’école</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <textarea wire:model="reject_reason" rows="3" placeholder="Expliquez pourquoi cette demande est rejetée…"
                            class="w-full resize-none rounded-xl border border-white/10 bg-[#070b14] px-3.5 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 transition-all focus:border-rose-500/50 focus:outline-none focus:ring-1 focus:ring-rose-500/30
                                @error('reject_reason') border-rose-500/50 @enderror"></textarea>
                        @error('reject_reason')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror

                        <div class="flex items-center justify-end gap-2.5">
                            <button wire:click="closeRejectModal" type="button"
                                class="h-10 rounded-xl border border-white/10 bg-white/5 px-4 text-sm text-slate-400 transition-all hover:bg-white/10">
                                Annuler
                            </button>
                            <button wire:click="confirmReject" wire:loading.attr="disabled"
                                wire:target="confirmReject" type="button"
                                class="inline-flex h-10 items-center gap-2 rounded-xl bg-rose-600 px-5 text-sm font-semibold text-white shadow-lg shadow-rose-900/30 transition-all hover:bg-rose-500 disabled:opacity-50">
                                <span wire:loading.remove wire:target="confirmReject"
                                    class="inline-flex items-center gap-2">
                                    <x-lucide-x class="h-4 w-4" />
                                    Confirmer le rejet
                                </span>
                                <span wire:loading wire:target="confirmReject" class="inline-flex items-center gap-2">
                                    <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                                    Envoi…
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

