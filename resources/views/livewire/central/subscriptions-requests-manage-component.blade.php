<div class="min-h-screen bg-[#070b14] text-slate-100 space-y-6 p-3">
    <div class="mx-auto space-y-6 relative">

        {{-- ════════════════ HEADER ════════════════ --}}
        <header class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-6 shadow-xl shadow-black/20">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <div
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 text-[10px] font-semibold uppercase tracking-wider mb-2">
                        <x-lucide-inbox class="w-3 h-3" />
                        Central
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                        Demandes d’abonnement
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Demandes envoyées par les écoles
                    </p>
                </div>

                <div class="relative shrink-0">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-600">
                        <x-lucide-search class="w-4 h-4" />
                    </span>
                    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher une école…"
                        class="w-full sm:w-64 h-10 rounded-xl bg-[#070b14] border border-white/10 pl-10 pr-3.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-indigo-500/40 focus:ring-1 focus:ring-indigo-500/20 transition-all">
                </div>
            </div>
        </header>

        {{-- ════════════════ FILTRES ════════════════ --}}
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1">
            @foreach ([
        'awaiting' => ['label' => 'En attente', 'icon' => 'clock', 'color' => 'amber'],
        'approved' => ['label' => 'Approuvées', 'icon' => 'check-circle-2', 'color' => 'emerald'],
        'rejected' => ['label' => 'Rejetées', 'icon' => 'x-circle', 'color' => 'rose'],
        'all' => ['label' => 'Toutes', 'icon' => 'list', 'color' => 'slate'],
    ] as $key => $tab)
                <button wire:click="$set('filter', '{{ $key }}')" type="button"
                    class="h-9 px-3.5 rounded-full text-xs font-medium whitespace-nowrap transition-all inline-flex items-center gap-1.5
                               {{ $filter === $key
                                   ? 'bg-white text-[#070b14] shadow-md'
                                   : 'bg-white/5 text-slate-400 hover:bg-white/10 hover:text-slate-200 border border-white/5' }}">
                    <x-dynamic-component :component="'lucide-' . $tab['icon']" class="w-3.5 h-3.5" />
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        {{-- ════════════════ LISTE ════════════════ --}}
        <div wire:loading.class="opacity-50 pointer-events-none" wire:target="filter, search, gotoPage"
            class="space-y-3 transition-opacity duration-200">

            @forelse ($requests as $request)

                <article wire:key="request-{{ $request->id }}"
                    class="group rounded-2xl bg-[#0f1523] border border-white/[0.06] hover:border-indigo-500/20 transition-all duration-200 overflow-hidden shadow-lg shadow-black/10">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4 p-4 sm:p-5">

                        {{-- École --}}
                        <div class="flex items-center gap-3 min-w-0 lg:w-[240px] shrink-0">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-500/15 border border-indigo-500/20 flex items-center justify-center text-indigo-300 shrink-0">
                                <x-lucide-school class="w-4.5 h-4.5" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-white truncate">
                                    {{ $request->tenant->getFullName() ?? $request->tenant_id }}
                                </p>
                                <p class="text-[11px] text-slate-500 truncate">
                                    {{ $request->tenant->email ?? '—' }}
                                </p>
                            </div>
                        </div>

                        {{-- Plan --}}
                        <div class="min-w-0 lg:flex-1">
                            <p class="text-sm font-medium text-slate-200">{{ $request->plan->name }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                {{ number_format($request->plan->price, 0, ',', ' ') }} FCFA
                                <span class="text-slate-700 mx-1">·</span>
                                {{ $request->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        {{-- Transaction --}}
                        <div class="min-w-0 lg:w-[180px] shrink-0">
                            @if ($request->transaction_id)
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-[#070b14] border border-white/5 text-[11px] font-mono text-slate-300">
                                    <x-lucide-receipt class="w-3 h-3 text-emerald-400 shrink-0" />
                                    <span class="truncate max-w-[120px]">{{ $request->transaction_id }}</span>
                                </span>
                            @else
                                <span class="text-[11px] text-slate-600 italic">Pas de transaction</span>
                            @endif
                            @if ($request->payment_reminder_sent_at)
                                <p class="mt-1 text-[10px] text-amber-400/80 flex items-center gap-1">
                                    <x-lucide-bell class="w-3 h-3" />
                                    Relancé {{ $request->payment_reminder_sent_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>

                        {{-- Statut --}}
                        <div class="shrink-0">
                            <span @class([
                                'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-medium border',
                                'bg-amber-500/10 text-amber-300 border-amber-500/20' =>
                                    $request->statusColor() === 'amber',
                                'bg-sky-500/10 text-sky-300 border-sky-500/20' =>
                                    $request->statusColor() === 'sky',
                                'bg-emerald-500/10 text-emerald-300 border-emerald-500/20' =>
                                    $request->statusColor() === 'emerald',
                                'bg-rose-500/10 text-rose-300 border-rose-500/20' =>
                                    $request->statusColor() === 'rose',
                            ])>
                                <span
                                    class="w-1.5 h-1.5 rounded-full
                                    {{ $request->statusColor() === 'amber' ? 'bg-amber-400' : '' }}
                                    {{ $request->statusColor() === 'sky' ? 'bg-sky-400' : '' }}
                                    {{ $request->statusColor() === 'emerald' ? 'bg-emerald-400' : '' }}
                                    {{ $request->statusColor() === 'rose' ? 'bg-rose-400' : '' }}"></span>
                                {{ $request->statusLabel() }}
                            </span>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 text-sm shrink-0 lg:pl-2 lg:border-l lg:border-white/5">
                            @if ($request->canBeActedOn())
                                <button wire:click="confirmApprove({{ $request->id }})" wire:loading.attr="disabled"
                                    type="button" title="Approuver"
                                    class="w-9 h-9 rounded-lg bg-white/5 hover:bg-emerald-500/15 border border-white/10 hover:border-emerald-500/30 text-slate-500 hover:text-emerald-300 transition-all flex items-center justify-center disabled:opacity-50">
                                    <x-lucide-check-circle class="w-4 h-4" />
                                </button>

                                @if ($request->isPending())
                                    <button wire:click="confirmRemindPayment({{ $request->id }})"
                                        wire:loading.attr="disabled" type="button" title="Réclamer le paiement"
                                        class="w-9 h-9 rounded-lg bg-white/5 hover:bg-amber-500/15 border border-white/10 hover:border-amber-500/30 text-slate-500 hover:text-amber-300 transition-all flex items-center justify-center disabled:opacity-50">
                                        <x-lucide-bell-ring class="w-4 h-4" />
                                    </button>
                                @endif

                                <button wire:click="openRejectModal({{ $request->id }})" type="button"
                                    title="Rejeter"
                                    class="w-9 h-9 rounded-lg bg-white/5 hover:bg-rose-500/15 border border-white/10 hover:border-rose-500/30 text-slate-500 hover:text-rose-300 transition-all flex items-center justify-center">
                                    <x-lucide-x-circle class="w-4 h-4" />
                                </button>
                            @endif

                            @if ($request->isApproved())
                                <button wire:click="deleteSubscription({{ $request->id }})" type="button"
                                    title="Supprimer cet abonnement"
                                    class="px-3 h-9 rounded-lg bg-red-500/30 hover:bg-red-500/50 border border-white/10 text-red-500/60 hover:text-red-300 transition-all flex items-center justify-center">
                                    <span class=" inline-flex items-center gap-2">
                                        <span class=" inline-flex items-center gap-2">
                                            <span>Suppr.</span>
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </span>
                                    </span>
                                </button>
                            @else
                                <button wire:click="deleteRequest({{ $request->id }})" type="button"
                                    title="Supprimer cette demande"
                                    class="px-3 h-9 rounded-lg bg-white/5 hover:bg-orange-600/50 border border-white/10 text-slate-500 hover:text-orange-300 transition-all flex items-center justify-center">
                                    <span class=" inline-flex items-center gap-2">
                                        <span class=" inline-flex items-center gap-2">
                                            <span>Suppr.</span>
                                            <x-lucide-trash class="w-4 h-4" />
                                        </span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] py-20 text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center mb-4">
                        <x-lucide-inbox class="w-6 h-6 text-slate-600" />
                    </div>
                    <p class="text-sm font-medium text-slate-400">Aucune demande</p>
                    <p class="mt-1 text-xs text-slate-600">Aucune demande pour ce filtre</p>
                </div>
            @endforelse

            @if ($requests->hasPages())
                <div class="pt-2 flex justify-center sm:justify-end">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>

        {{-- ════════════════ MODAL REJET ════════════════ --}}
        @if ($showRejectModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#070b14]/80 backdrop-blur-sm p-4" x-data
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <div class="w-full max-w-md rounded-2xl bg-[#0f1523] border border-white/[0.08] shadow-2xl shadow-black/40 overflow-hidden"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="px-5 py-4 border-b border-white/[0.05] flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-xl bg-rose-500/15 border border-rose-500/25 flex items-center justify-center">
                            <x-lucide-x-octagon class="w-4 h-4 text-rose-400" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-white">Motif du rejet</h2>
                            <p class="text-[11px] text-slate-500">Cette raison sera visible par l’école</p>
                        </div>
                    </div>

                    <div class="p-5 space-y-4">
                        <textarea wire:model="reject_reason" rows="3" placeholder="Expliquez pourquoi cette demande est rejetée…"
                            class="w-full rounded-xl bg-[#070b14] border border-white/10 px-3.5 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-rose-500/50 focus:ring-1 focus:ring-rose-500/30 transition-all resize-none
                                         @error('reject_reason') border-rose-500/50 @enderror"></textarea>
                        @error('reject_reason')
                            <p class="text-xs text-rose-400">{{ $message }}</p>
                        @enderror

                        <div class="flex items-center justify-end gap-2.5">
                            <button wire:click="closeRejectModal" type="button"
                                class="h-10 px-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-400 transition-all">
                                Annuler
                            </button>
                            <button wire:click="confirmReject" wire:loading.attr="disabled"
                                wire:target="confirmReject" type="button"
                                class="h-10 px-5 rounded-xl bg-rose-600 hover:bg-rose-500 text-sm font-semibold text-white transition-all disabled:opacity-50 inline-flex items-center gap-2 shadow-lg shadow-rose-900/30">
                                <span wire:loading.remove wire:target="confirmReject"
                                    class="inline-flex items-center gap-2">
                                    <x-lucide-x class="w-4 h-4" />
                                    Confirmer le rejet
                                </span>
                                <span wire:loading wire:target="confirmReject" class="inline-flex items-center gap-2">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
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

