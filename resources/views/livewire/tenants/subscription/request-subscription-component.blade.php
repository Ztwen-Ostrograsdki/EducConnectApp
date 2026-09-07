<div class="min-h-screen bg-[#050810] text-slate-100 p-4 sm:p-6 space-y-8 font-sans selection:bg-indigo-500/30">
    <div class="mx-auto max-w-6xl space-y-10 relative">

        {{-- ════════════════ HEADER ════════════════ --}}
        <header class="relative text-center sm:text-left pt-4 pb-6 border-b border-white/[0.05]">
            <div
                class="absolute top-0 left-1/2 sm:left-0 w-64 h-64 bg-indigo-600/20 rounded-full blur-[100px] -translate-x-1/2 sm:translate-x-0 pointer-events-none">
            </div>

            <div class="relative z-10">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 text-xs font-bold uppercase tracking-widest mb-4 shadow-[0_0_10px_rgba(99,102,241,0.2)]">
                    <x-lucide-sparkles class="w-3.5 h-3.5 text-indigo-400" />
                    Espace Abonnement
                </div>
                <h1
                    class="text-3xl sm:text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400">
                    Gérez votre abonnement
                </h1>
                <p class="mt-3 text-sm sm:text-base text-slate-400 max-w-xl">
                    Sélectionnez une offre, suivez l'évolution de vos demandes et accédez à l'historique complet de vos
                    souscriptions en un coup d'œil.
                </p>
            </div>
        </header>

        {{-- ════════════════ SECTION 1 — ABONNEMENT EN COURS ════════════════ --}}
        <section class="relative z-10">
            <div class="mb-4">
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Statut actuel</h2>
            </div>

            @if ($this->activeSubscription)
                <div
                    class="relative rounded-2xl overflow-hidden border border-emerald-500/30 bg-[#061412] p-5 sm:p-8 shadow-[0_0_30px_rgba(16,185,129,0.05)] transition-all">
                    <div
                        class="absolute -top-20 -right-20 w-72 h-72 bg-emerald-500/10 rounded-full blur-[80px] pointer-events-none">
                    </div>

                    <div class="relative flex flex-col sm:flex-row sm:items-center gap-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center shrink-0 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                            <x-lucide-shield-check
                                class="w-7 h-7 text-emerald-400 drop-shadow-[0_0_8px_rgba(52,211,153,0.8)]" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-[11px] font-bold uppercase tracking-widest text-emerald-500 mb-1.5 flex items-center gap-2">
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Abonnement actif
                            </p>
                            <h3 class="text-2xl font-bold text-white mb-1">
                                {{ $this->activeSubscription->plan?->name }}
                                <span
                                    class="ml-2 text-sm py-1 px-3 rounded-lg bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 font-mono align-middle">
                                    #{{ $this->activeSubscription->key }}
                                </span>
                            </h3>
                            <p class="text-sm text-slate-400">
                                Valide du <span
                                    class="text-slate-200 font-medium">{{ __formatDateTime($this->activeSubscription->started_at) }}</span>
                                au <span
                                    class="text-slate-200 font-medium">{{ __formatDateTime($this->activeSubscription->expire_at) }}</span>
                            </p>
                        </div>
                        <div class="shrink-0 flex flex-col items-end sm:items-center gap-2">
                            <div class="text-emerald-400 font-bold text-xl drop-shadow-[0_0_5px_rgba(52,211,153,0.4)]">
                                {{ $this->activeSubscription->daysRemaining() }} jours
                            </div>
                            <span class="text-xs text-slate-500">restants</span>

                            <div
                                class="h-2 w-full sm:w-32 rounded-full bg-emerald-950/80 overflow-hidden mt-1 ring-1 ring-emerald-500/20">
                                @php
                                    $pct = min(
                                        100,
                                        max(
                                            0,
                                            ($this->activeSubscription->daysRemaining() /
                                                max(1, $this->activeSubscription->plan?->days_count ?? 1)) *
                                                100,
                                        ),
                                    );
                                @endphp
                                <div class="h-full rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.8)] transition-all duration-500"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="relative rounded-2xl overflow-hidden border border-red-500/20 bg-[#140608] p-5 sm:p-6 shadow-[0_0_20px_rgba(239,68,68,0.05)]">
                    <div
                        class="absolute top-0 right-0 w-40 h-40 bg-red-500/10 rounded-full blur-[60px] pointer-events-none">
                    </div>
                    <div class="relative flex flex-col sm:flex-row sm:items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-red-500/10 border border-red-500/30 flex items-center justify-center shrink-0">
                            <x-lucide-shield-off class="w-6 h-6 text-red-400" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-red-400 mb-1 tracking-wide">Aucun abonnement en cours
                            </h3>
                            <p class="text-sm text-red-300/60 leading-relaxed">
                                Activez un plan ci-dessous pour débloquer l'accès à l'administration et permettre la
                                connexion de vos utilisateurs.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        {{-- ════════════════ SECTION 2 — MES ABONNEMENTS ════════════════ --}}
        <section class="border border-white/5 rounded-3xl bg-[#0b1121] p-4 sm:p-6 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 left-20 w-96 h-96 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none">
            </div>

            <div
                class="relative z-10 mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between border-b border-white/[0.05] pb-4">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-wide">Historique des abonnements</h2>
                    <p class="text-xs text-slate-400 mt-1">Consultez l'état de vos souscriptions passées et présentes
                    </p>
                </div>

                {{-- Filtres avec effet glow actif --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                    @foreach (['actifs' => 'Actifs', 'desactives' => 'Désactivés', 'expires' => 'Expirés', 'all' => 'Tout'] as $key => $label)
                        <button wire:click="$set('subsFilter', '{{ $key }}')" type="button"
                            class="h-9 shrink-0 rounded-xl px-4 text-xs font-semibold transition-all duration-300
                                {{ $subsFilter === $key
                                    ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/50 shadow-[0_0_12px_rgba(99,102,241,0.3)]'
                                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="relative min-h-[150px]">
                <div wire:loading.flex wire:target="subsFilter, gotoPage"
                    class="absolute inset-0 z-20 items-center justify-center bg-[#0b1121]/80 backdrop-blur-sm rounded-xl">
                    <x-lucide-loader
                        class="w-8 h-8 text-indigo-400 animate-spin drop-shadow-[0_0_10px_rgba(99,102,241,0.8)]" />
                </div>

                <div class="space-y-3 relative z-10">
                    @forelse ($subscriptions as $subscription)
                        @php $state = $this->subscriptionState($subscription); @endphp
                        <article wire:key="sub-{{ $subscription->id }}"
                            class="group rounded-2xl bg-[#111827] border border-white/5 hover:border-white/15 hover:bg-[#151f32] transition-all duration-300 overflow-hidden">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4">
                                <div class="flex items-center gap-4 min-w-0 flex-1">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                        <x-lucide-credit-card
                                            class="w-5 h-5 text-slate-400 group-hover:text-indigo-400 transition-colors" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="text-sm font-semibold text-slate-100 truncate">
                                                {{ $subscription->plan?->name ?? '—' }}
                                            </h4>
                                            <span
                                                class="text-[10px] px-2 py-0.5 rounded bg-white/5 text-slate-400 font-mono border border-white/10">
                                                #{{ $subscription->key }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 flex items-center gap-2">
                                            Du <span
                                                class="text-slate-300">{{ __formatDateTime($subscription->started_at) }}</span>
                                            au <span
                                                class="text-slate-300">{{ __formatDateTime($subscription->expire_at) }}</span>

                                            @if (!$subscription->isExpired() && $subscription->status === 'active')
                                                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                                <span
                                                    class="text-emerald-400 font-medium tracking-wide drop-shadow-[0_0_2px_rgba(52,211,153,0.5)]">
                                                    {{ $subscription->daysRemaining() }} j restants
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span @class([
                                        'inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-bold uppercase tracking-wider border',
                                        'bg-emerald-500/10 text-emerald-400 border-emerald-500/30 shadow-[0_0_10px_rgba(16,185,129,0.1)]' =>
                                            $state['color'] === 'emerald',
                                        'bg-sky-500/10 text-sky-400 border-sky-500/30 shadow-[0_0_10px_rgba(14,165,233,0.1)]' =>
                                            $state['color'] === 'sky',
                                        'bg-amber-500/10 text-amber-400 border-amber-500/30 shadow-[0_0_10px_rgba(245,158,11,0.1)]' =>
                                            $state['color'] === 'amber',
                                        'bg-rose-500/10 text-rose-400 border-rose-500/30 shadow-[0_0_10px_rgba(244,63,94,0.1)]' =>
                                            $state['color'] === 'rose',
                                        'bg-slate-800 text-slate-400 border-slate-700' =>
                                            $state['color'] === 'slate',
                                    ])>
                                        <span @class([
                                            'w-1.5 h-1.5 rounded-full animate-pulse',
                                            'bg-emerald-400 shadow-[0_0_5px_rgba(52,211,153,0.8)]' =>
                                                $state['color'] === 'emerald',
                                            'bg-sky-400 shadow-[0_0_5px_rgba(56,189,248,0.8)]' =>
                                                $state['color'] === 'sky',
                                            'bg-amber-400 shadow-[0_0_5px_rgba(251,191,36,0.8)]' =>
                                                $state['color'] === 'amber',
                                            'bg-rose-400 shadow-[0_0_5px_rgba(251,113,133,0.8)]' =>
                                                $state['color'] === 'rose',
                                            'bg-slate-400' => $state['color'] === 'slate',
                                        ])></span>
                                        {{ $state['label'] }}
                                    </span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div
                            class="rounded-2xl border border-dashed border-white/10 py-16 text-center bg-[#0b1121]/50 backdrop-blur-sm">
                            <div
                                class="w-16 h-16 mx-auto rounded-full bg-white/5 border border-white/5 flex items-center justify-center mb-4">
                                <x-lucide-history class="w-6 h-6 text-slate-500" />
                            </div>
                            <p class="text-base font-medium text-slate-300">Aucun abonnement trouvé</p>
                            <p class="mt-1 text-sm text-slate-500">Essayez de modifier vos filtres.</p>
                        </div>
                    @endforelse

                    @if ($subscriptions->hasPages())
                        <div class="pt-4 flex justify-end">
                            {{ $subscriptions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- ════════════════ SECTION 3 — MES DEMANDES ════════════════ --}}
        <section class="border border-white/5 rounded-3xl bg-[#10172A] p-4 sm:p-6 shadow-xl relative overflow-hidden">
            <div
                class="absolute bottom-0 right-20 w-80 h-80 bg-purple-500/5 rounded-full blur-[100px] pointer-events-none">
            </div>

            <div class="relative z-10 mb-6 border-b border-white/[0.05] pb-4">
                <h2 class="text-lg font-bold text-white tracking-wide">Suivi des demandes</h2>
                <p class="text-xs text-slate-400 mt-1">Gérez vos demandes d'abonnement et déclarez vos paiements</p>
            </div>

            <div class="space-y-4 relative z-10">
                @forelse ($this->demandes as $demande)
                    <article wire:key="demande-{{ $demande->id }}"
                        class="rounded-2xl bg-[#1E293B]/50 border border-white/5 hover:border-white/20 transition-all duration-300">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-5 p-4 sm:p-5">

                            <div class="flex items-center gap-4 min-w-0 flex-1">
                                <div
                                    class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center shrink-0">
                                    <x-lucide-inbox class="w-5 h-5 text-purple-400" />
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-3 flex-wrap mb-1">
                                        <p class="text-sm font-bold text-white">{{ $demande->plan?->name }}</p>

                                        @if ($this->activeSubscription && $this->activeSubscription->id === $demande->subscription?->id)
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-wider rounded-md px-2 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1 shadow-[0_0_8px_rgba(16,185,129,0.15)]">
                                                <x-lucide-check-circle class="w-3 h-3" />
                                                En cours
                                            </span>
                                        @elseif ($demande->isApproved() && $demande->subscription && $demande->subscription->isExpired())
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-wider rounded-md px-2 py-1 bg-red-500/10 text-red-400 border border-red-500/20 flex items-center gap-1">
                                                <x-lucide-x-circle class="w-3 h-3" />
                                                Expiré
                                            </span>
                                        @endif
                                    </div>
                                    <div
                                        class="text-xs text-slate-400 flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3">
                                        <span>Créée le {{ __formatDateTime($demande->created_at) }}</span>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="bg-black/30 px-2 py-0.5 rounded font-mono border border-white/5">
                                                Demande <span class="text-amber-400">#{{ $demande->key }}</span>
                                            </span>
                                            @if ($demande->subscription)
                                                <span
                                                    class="bg-black/30 px-2 py-0.5 rounded font-mono border border-white/5">
                                                    Abon. <span
                                                        class="text-sky-400">#{{ $demande->subscription->key }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-start sm:items-end gap-2 shrink-0 min-w-[160px]">
                                <span @class([
                                    'inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-bold border',
                                    'bg-amber-500/10 text-amber-400 border-amber-500/30' =>
                                        $demande->statusColor() === 'amber',
                                    'bg-sky-500/10 text-sky-400 border-sky-500/30' =>
                                        $demande->statusColor() === 'sky',
                                    'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' =>
                                        $demande->statusColor() === 'emerald',
                                    'bg-rose-500/10 text-rose-400 border-rose-500/30' =>
                                        $demande->statusColor() === 'rose',
                                ])>
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $demande->statusColor() === 'amber' ? 'bg-amber-400 shadow-[0_0_5px_rgba(251,191,36,0.8)]' : '' }} {{ $demande->statusColor() === 'sky' ? 'bg-sky-400' : '' }} {{ $demande->statusColor() === 'emerald' ? 'bg-emerald-400' : '' }} {{ $demande->statusColor() === 'rose' ? 'bg-rose-400' : '' }}"></span>
                                    {{ $demande->statusLabel() }}
                                </span>

                                @if ($demande->transaction_id)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-black/40 border border-white/5 text-[11px] font-mono text-slate-300">
                                        <x-lucide-hash class="w-3 h-3 text-slate-500 shrink-0" />
                                        <span class="truncate max-w-[120px]">{{ $demande->transaction_id }}</span>
                                    </span>
                                @endif

                                @if ($demande->payment_reminder_sent_at)
                                    <p class="text-[10px] text-amber-400 flex items-center gap-1 animate-pulse">
                                        <x-lucide-bell-ring class="w-3 h-3" />
                                        Paiement en attente
                                    </p>
                                @endif
                                @if ($demande->isRejected() && $demande->reject_reason)
                                    <p class="text-[10px] text-rose-400 max-w-[180px] line-clamp-2 text-right">
                                        Motif : {{ $demande->reject_reason }}
                                    </p>
                                @endif
                            </div>

                            <div
                                class="shrink-0 sm:pl-4 sm:border-l sm:border-white/10 flex flex-row sm:flex-col justify-end gap-2">
                                @if (!$demande->isApproved())
                                    <button wire:click="confirmDelete({{ $demande->id }})"
                                        wire:loading.attr="disabled" type="button"
                                        class="h-8 px-3 rounded-lg bg-white/5 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 text-slate-400 hover:text-rose-400 text-xs font-medium transition-all flex items-center gap-1.5">
                                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                        <span class="hidden sm:inline">Annuler</span>
                                    </button>
                                @endif
                                @if ($demande->canClaimPayment())
                                    <button wire:click="openClaimModal({{ $demande->id }})" type="button"
                                        class="h-8 px-4 rounded-lg bg-indigo-500/20 hover:bg-indigo-500/30 border border-indigo-500/40 text-indigo-300 text-xs font-bold transition-all flex items-center gap-1.5 shadow-[0_0_10px_rgba(99,102,241,0.15)] hover:shadow-[0_0_15px_rgba(99,102,241,0.3)]">
                                        <x-lucide-badge-dollar-sign class="w-4 h-4" />
                                        J’ai payé
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-white/10 py-16 text-center bg-[#1E293B]/20">
                        <div
                            class="w-14 h-14 mx-auto rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center mb-4">
                            <x-lucide-folder-open class="w-6 h-6 text-slate-600" />
                        </div>
                        <p class="text-sm font-medium text-slate-300">Aucune demande en cours</p>
                        <p class="mt-1 text-xs text-slate-500">Consultez les plans ci-dessous pour démarrer.</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- ════════════════ SECTION 4 — PLANS DISPONIBLES ════════════════ --}}
        <section class="border border-white/5 rounded-3xl bg-[#0D1527] p-4 sm:p-6 shadow-2xl relative">
            <div class="mb-6 border-b border-white/[0.05] pb-4">
                <h2 class="text-lg font-bold text-white tracking-wide">Plans disponibles</h2>
                <p class="text-xs text-slate-400 mt-1">Sélectionnez une offre pour envoyer une nouvelle demande
                    d'abonnement</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ($this->plans as $plan)
                    @php $selected = $selectedPlanId === $plan->id; @endphp
                    <div wire:click="selectPlan({{ $plan->id }})"
                        class="group cursor-pointer relative text-left rounded-2xl border p-6 transition-all duration-300 overflow-hidden
                            {{ $selected
                                ? 'border-indigo-500/60 bg-indigo-900/20 shadow-[0_0_30px_rgba(99,102,241,0.2)] -translate-y-1'
                                : 'border-white/5 bg-[#121B30] hover:border-indigo-500/30 hover:bg-[#16213B] hover:-translate-y-0.5' }}">

                        @if ($selected)
                            <div
                                class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-[40px] pointer-events-none">
                            </div>

                            <span
                                class="absolute top-4 right-4 w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center shadow-[0_0_10px_rgba(99,102,241,0.5)]">
                                <x-lucide-check class="w-3.5 h-3.5 text-white" />
                            </span>
                        @endif

                        <div
                            class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-5 transition-colors
                                {{ $selected ? 'bg-indigo-500/30 border-indigo-500/50 shadow-[0_0_15px_rgba(99,102,241,0.3)]' : 'group-hover:bg-indigo-500/20' }}">
                            <x-lucide-layers class="w-6 h-6 text-indigo-400" />
                        </div>

                        <h3 class="text-base font-bold text-white pr-8">{{ $plan->name }}</h3>

                        <div class="mt-4 flex items-baseline gap-1.5">
                            <span class="text-3xl font-black text-white tabular-nums tracking-tight">
                                {{ number_format($plan->price, 0, ',', ' ') }}
                            </span>
                            <span class="text-sm text-slate-500 font-bold uppercase">FCFA</span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-white/[0.05]">
                            <p class="text-xs font-medium text-indigo-300 flex items-center gap-2">
                                <x-lucide-calendar-days class="w-4 h-4" />
                                {{ $plan->days_count }} jours d’accès
                            </p>
                            @if ($plan->description)
                                <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                                    {{ $plan->description }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($selectedPlanId)
                <div
                    class="mt-8 pt-6 border-t border-white/5 flex justify-center sm:justify-end items-center gap-3 flex-wrap">
                    <button wire:click="resetSelectedPlan" type="button"
                        class="h-12 px-6 rounded-xl bg-white/5 hover:bg-white/10 border border-transparent hover:border-white/10 text-sm font-medium text-slate-300 transition-all">
                        Annuler
                    </button>
                    <button wire:click="confirmRequestSubscription" wire:loading.attr="disabled" type="button"
                        class="group relative h-12 px-8 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-bold text-white transition-all disabled:opacity-50 overflow-hidden shadow-[0_0_20px_rgba(79,70,229,0.4)] hover:shadow-[0_0_30px_rgba(79,70,229,0.6)]">
                        <span wire:loading.remove wire:target="confirmRequestSubscription"
                            class="relative z-10 flex items-center gap-2">
                            Envoyer la demande
                            <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                        </span>

                        <span wire:loading wire:target="confirmRequestSubscription"
                            class="relative z-10 flex items-center gap-2">
                            <x-lucide-loader-2
                                class="w-5 h-5 animate-spin drop-shadow-[0_0_8px_rgba(255,255,255,0.8)]" />
                            Traitement...
                        </span>
                    </button>
                </div>
            @endif
        </section>

        {{-- ════════════════ MODAL "J'AI PAYÉ" ════════════════ --}}
        @if ($showClaimModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#020408]/80 backdrop-blur-md p-4">
                <div
                    class="w-full max-w-md rounded-2xl bg-[#0D1527] border border-white/10 shadow-[0_0_50px_rgba(0,0,0,0.5)] overflow-hidden relative">
                    <div
                        class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-sky-500 to-transparent opacity-50">
                    </div>
                    <div
                        class="absolute -top-10 left-1/2 -translate-x-1/2 w-48 h-24 bg-sky-500/20 blur-[30px] pointer-events-none">
                    </div>

                    <div class="px-6 py-5 border-b border-white/[0.05] flex items-center gap-4 relative z-10">
                        <div
                            class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center shadow-[0_0_15px_rgba(14,165,233,0.15)]">
                            <x-lucide-badge-dollar-sign
                                class="w-5 h-5 text-sky-400 drop-shadow-[0_0_5px_rgba(14,165,233,0.5)]" />
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">Confirmation de paiement</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Vérification via Mobile Money ou virement</p>
                        </div>
                    </div>

                    <div class="p-6 space-y-5 relative z-10">
                        <p class="text-sm text-slate-300 leading-relaxed">
                            Veuillez renseigner l'ID de votre transaction pour que nous puissions valider votre
                            abonnement.
                        </p>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                ID de Transaction
                            </label>
                            <input type="text" wire:model="transactionId" placeholder="Ex: MOMO-2026-XXXXXX"
                                class="w-full h-12 rounded-xl bg-black/40 border border-white/10 px-4 text-sm font-mono text-white placeholder:text-slate-600 focus:outline-none focus:border-sky-500/50 focus:ring-2 focus:ring-sky-500/20 transition-all
                                    @error('transactionId') border-rose-500/50 focus:ring-rose-500/20 @enderror">
                            @error('transactionId')
                                <p class="mt-2 text-xs font-medium text-rose-400 flex items-center gap-1">
                                    <x-lucide-alert-circle class="w-3.5 h-3.5" />
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button wire:click="closeClaimModal" type="button"
                                class="h-11 px-5 rounded-xl bg-white/5 hover:bg-white/10 border border-transparent text-sm font-medium text-slate-300 transition-all">
                                Annuler
                            </button>
                            <button wire:click="submitClaimPayment" wire:loading.attr="disabled" type="button"
                                class="h-11 px-6 rounded-xl bg-sky-600 hover:bg-sky-500 text-sm font-bold text-white transition-all disabled:opacity-50 flex items-center gap-2 shadow-[0_0_20px_rgba(14,165,233,0.3)] hover:shadow-[0_0_30px_rgba(14,165,233,0.5)]">
                                <span wire:loading.remove wire:target="submitClaimPayment"
                                    class="flex items-center gap-2">
                                    <x-lucide-check-circle-2 class="w-4 h-4" />
                                    Confirmer
                                </span>
                                <span wire:loading wire:target="submitClaimPayment" class="flex items-center gap-2">
                                    <x-lucide-loader-2
                                        class="w-4 h-4 animate-spin drop-shadow-[0_0_5px_rgba(255,255,255,0.8)]" />
                                    Vérification...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
