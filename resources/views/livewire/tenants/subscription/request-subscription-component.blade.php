<div class="min-h-screen bg-[#070b14] text-slate-100 p-6 space-y-6">
    <div class="mx-auto space-y-8 relative">

        {{-- ════════════════ HEADER ════════════════ --}}
        <header class="text-center sm:text-left border-b border-b-slate-700">
            <div
                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-semibold uppercase tracking-wider mb-3">
                <x-lucide-sparkles class="w-3 h-3" />
                Abonnement
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                Votre abonnement
            </h1>
            <p class="mt-2 text-sm text-slate-500 max-w-lg">
                Choisissez un plan adapté à votre établissement et suivez vos demandes
            </p>
        </header>

        {{-- ════════════════ ABONNEMENT ACTIF ════════════════ --}}
        @if ($this->activeSubscription)
            <div
                class="relative rounded-2xl overflow-hidden border border-emerald-500/25 bg-gradient-to-r from-emerald-500/10 via-emerald-500/5 to-transparent p-5 sm:p-6">
                <div
                    class="absolute top-0 right-0 w-40 h-40 bg-emerald-400/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3">
                </div>
                <div class="relative flex flex-col sm:flex-row sm:items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center shrink-0">
                        <x-lucide-shield-check class="w-6 h-6 text-emerald-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-400/80 mb-0.5">
                            Abonnement actif
                        </p>
                        <h2 class="text-lg font-bold text-white">
                            {{ $this->activeSubscription->plan->name }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-400">
                            Expire le
                            <span
                                class="text-slate-200 font-medium">{{ $this->activeSubscription->expire_at->format('d/m/Y') }}</span>
                            ·
                            <span
                                class="text-emerald-300 font-semibold tabular-nums">{{ $this->activeSubscription->daysRemaining() }}
                                jours</span>
                            restants
                        </p>
                    </div>
                    <div class="shrink-0">
                        <div class="h-2 w-full sm:w-32 rounded-full bg-emerald-950/80 overflow-hidden">
                            @php
                                $pct = min(
                                    100,
                                    max(
                                        0,
                                        ($this->activeSubscription->daysRemaining() /
                                            max(1, $this->activeSubscription->plan->days_count)) *
                                            100,
                                    ),
                                );
                            @endphp
                            <div class="h-full rounded-full bg-emerald-500/90 transition-all"
                                style="width: {{ $pct }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div
                class="relative rounded-2xl overflow-hidden border border-red-500/25 bg-gradient-to-r from-red-500/10 via-red-500/5 to-transparent p-5 sm:p-6 animate-pulse font-mono">
                <div
                    class="absolute top-0 right-0 w-40 h-40 bg-red-400/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3">
                </div>
                <div class="relative flex flex-col sm:flex-row sm:items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-red-500/15 border border-red-500/25 flex items-center justify-center shrink-0">
                        <x-lucide-shield-off class="w-6 h-6 text-red-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class=" font-semibold tracking-wider  mb-0.5 inline-flex gap-2 flex-col">
                            <span class="text-sm uppercase text-red-500/80">Vous n'avez aucun abonnement actif</span>
                            <span class="text-xs text-red-300/80">Veuillez en activer pour avoir accès à votre espace
                                administration et
                                permetrre aussi
                                à vos utilisateurs de pouvoir se connecter!</span>
                        </p>

                    </div>
                </div>
            </div>
        @endif

        {{-- ════════════════ MES DEMANDES ════════════════ --}}
        <section class="border border-slate-700 rounded-2xl bg-slate-950 p-3">
            <div class="mb-5">
                <h2 class="text-base font-semibold text-white">Mes demandes</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Historique et suivi de vos demandes d’abonnement</p>
            </div>

            <div class="space-y-3">
                @forelse ($this->demandes as $demande)
                    <article wire:key="demande-{{ $demande->id }}"
                        class="rounded-2xl bg-[#0f1523] border border-white/[0.06] hover:border-white/10 transition-all overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-5">

                            {{-- Plan --}}
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center shrink-0">
                                    <x-lucide-package class="w-4 h-4 text-indigo-400" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-white truncate">{{ $demande->plan->name }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $demande->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Transaction --}}
                            <div class="min-w-0 sm:w-[160px] shrink-0">
                                @if ($demande->transaction_id)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-[#070b14] border border-white/5 text-[11px] font-mono text-slate-300">
                                        <x-lucide-receipt class="w-3 h-3 text-emerald-400 shrink-0" />
                                        <span class="truncate">{{ $demande->transaction_id }}</span>
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-600 italic">Pas de transaction</span>
                                @endif
                            </div>

                            {{-- Statut --}}
                            <div class="shrink-0 space-y-1">
                                <span @class([
                                    'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-medium border',
                                    'bg-amber-500/10 text-amber-300 border-amber-500/20' =>
                                        $demande->statusColor() === 'amber',
                                    'bg-sky-500/10 text-sky-300 border-sky-500/20' =>
                                        $demande->statusColor() === 'sky',
                                    'bg-emerald-500/10 text-emerald-300 border-emerald-500/20' =>
                                        $demande->statusColor() === 'emerald',
                                    'bg-rose-500/10 text-rose-300 border-rose-500/20' =>
                                        $demande->statusColor() === 'rose',
                                ])>
                                    <span
                                        class="w-1.5 h-1.5 rounded-full
                                        {{ $demande->statusColor() === 'amber' ? 'bg-amber-400' : '' }}
                                        {{ $demande->statusColor() === 'sky' ? 'bg-sky-400' : '' }}
                                        {{ $demande->statusColor() === 'emerald' ? 'bg-emerald-400' : '' }}
                                        {{ $demande->statusColor() === 'rose' ? 'bg-rose-400' : '' }}"></span>
                                    {{ $demande->statusLabel() }}
                                </span>
                                @if ($demande->payment_reminder_sent_at)
                                    <p class="text-[10px] text-amber-400/80 flex items-center gap-1">
                                        <x-lucide-bell class="w-3 h-3" />
                                        Paiement réclamé
                                    </p>
                                @endif
                                @if ($demande->isRejected() && $demande->reject_reason)
                                    <p class="text-[10px] text-rose-400/90 max-w-[180px] line-clamp-2">
                                        {{ $demande->reject_reason }}
                                    </p>
                                @endif
                            </div>

                            {{-- Action --}}
                            <div class="shrink-0 sm:pl-2 sm:border-l sm:border-white/5">
                                @if (!$demande->isApproved())
                                    <button wire:click="confirmDelete({{ $demande->id }})"
                                        wire:loading.attr="disabled" wire:target="confirmDelete({{ $demande->id }})"
                                        type="button"
                                        class="group relative h-9 px-3.5 rounded-xl overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.98] transition-transform">
                                        <span class="absolute inset-0 bg-red-400"></span>
                                        <span
                                            class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-gradient-to-r from-red-500 via-red-400 to-red-700 transition-opacity"></span>
                                        <span
                                            class="relative flex items-center gap-2.5 text-sm font-semibold text-black">
                                            <span wire:loading.remove wire:target="confirmDelete({{ $demande->id }})"
                                                class="inline-flex items-center gap-2.5">
                                                <x-lucide-trash-2 class="w-4 h-4" />
                                                Annuler la demande
                                            </span>
                                            <span wire:loading wire:target="confirmDelete({{ $demande->id }})"
                                                class="inline-flex items-center gap-2.5">
                                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                                Annulation…
                                            </span>
                                        </span>
                                    </button>
                                @endif
                                @if ($demande->canClaimPayment())
                                    <button wire:click="openClaimModal({{ $demande->id }})" type="button"
                                        class="h-9 px-3.5 rounded-xl bg-sky-500/15 hover:bg-sky-500/25 border border-sky-500/25 text-sky-300 text-xs font-medium transition-all inline-flex items-center gap-1.5">
                                        <x-lucide-banknote class="w-3.5 h-3.5" />
                                        J’ai payé
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] py-16 text-center">
                        <div
                            class="w-14 h-14 mx-auto rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center mb-4">
                            <x-lucide-inbox class="w-6 h-6 text-slate-600" />
                        </div>
                        <p class="text-sm font-medium text-slate-400">Aucune demande</p>
                        <p class="mt-1 text-xs text-slate-600">Sélectionnez un plan ci-dessus pour commencer</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- ════════════════ MODAL "J'AI PAYÉ" ════════════════ --}}
        @if ($showClaimModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#070b14]/80 backdrop-blur-sm p-4" x-data
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <div class="w-full max-w-md rounded-2xl bg-[#0f1523] border border-white/[0.08] shadow-2xl shadow-black/40 overflow-hidden"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="px-5 py-4 border-b border-white/[0.05] flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-xl bg-sky-500/15 border border-sky-500/25 flex items-center justify-center">
                            <x-lucide-banknote class="w-4 h-4 text-sky-400" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-white">Signaler votre paiement</h2>
                            <p class="text-[11px] text-slate-500">Le central vérifiera la transaction</p>
                        </div>
                    </div>

                    <div class="p-5 space-y-4">
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Renseignez l’identifiant de la transaction effectuée (Mobile Money, virement, etc.).
                        </p>

                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                ID transaction
                            </label>
                            <input type="text" wire:model="transactionId" placeholder="Ex: MOMO-2026-XXXXXX"
                                class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm font-mono text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30 transition-all
                                          @error('transactionId') border-rose-500/50 @enderror">
                            @error('transactionId')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-1">
                            <button wire:click="closeClaimModal" type="button"
                                class="h-10 px-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-400 transition-all">
                                Annuler
                            </button>
                            <button wire:click="submitClaimPayment" wire:loading.attr="disabled"
                                wire:target="submitClaimPayment" type="button"
                                class="h-10 px-5 rounded-xl bg-sky-600 hover:bg-sky-500 text-sm font-semibold text-white transition-all disabled:opacity-50 inline-flex items-center gap-2 shadow-lg shadow-sky-900/30">
                                <span wire:loading.remove wire:target="submitClaimPayment"
                                    class="inline-flex items-center gap-2">
                                    <x-lucide-check class="w-4 h-4" />
                                    Confirmer
                                </span>
                                <span wire:loading wire:target="submitClaimPayment"
                                    class="inline-flex items-center gap-2">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                    Envoi…
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ════════════════ PLANS ════════════════ --}}
        <section class="border border-slate-700 rounded-2xl bg-slate-950 p-3">
            <div class="flex items-center justify-between mb-5 border-b border-b-slate-800">
                <div>
                    <h2 class="text-base font-semibold text-white">Plans disponibles</h2>
                    <p class="text-[11px] text-slate-500 mt-0.5">Sélectionnez une offre pour envoyer une demande</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($this->plans as $plan)
                    @php $selected = $selectedPlanId === $plan->id; @endphp
                    <div wire:click="selectPlan({{ $plan->id }})"
                        class="group cursor-pointer relative text-left rounded-2xl border p-5 transition-all duration-200
                                   {{ $selected
                                       ? 'border-indigo-500/50 bg-indigo-500/10 ring-2 ring-indigo-500/30 shadow-lg shadow-indigo-950/30'
                                       : 'border-white/[0.06] bg-[#0f1523] hover:border-indigo-500/25 hover:bg-[#121a2b]' }}">

                        @if ($selected)
                            <span
                                class="absolute top-3 right-3 w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center">
                                <x-lucide-check class="w-3.5 h-3.5 text-white" />
                            </span>
                        @endif

                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-4
                                    {{ $selected ? 'bg-indigo-500/20 border-indigo-500/30' : '' }}">
                            <x-lucide-package class="w-5 h-5 text-indigo-400" />
                        </div>

                        <h3 class="text-sm font-semibold text-white pr-8">{{ $plan->name }}</h3>

                        <div class="mt-3 flex items-baseline gap-1">
                            <span class="text-2xl font-bold text-white tabular-nums">
                                {{ number_format($plan->price, 0, ',', ' ') }}
                            </span>
                            <span class="text-xs text-slate-500 font-medium">FCFA</span>
                        </div>

                        <p class="mt-1 text-[11px] text-slate-500">
                            {{ $plan->days_count }} jours d’accès
                        </p>

                        @if ($plan->description)
                            <p class="mt-3 text-xs text-slate-400 leading-relaxed line-clamp-2">
                                {{ $plan->description }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($selectedPlanId)
                <div class="mt-6 flex justify-center sm:justify-end items-center gap-3">
                    <div class="mt-6 flex justify-center sm:justify-end" x-data
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <button wire:click="resetSelectedPlan" wire:loading.attr="disabled"
                            wire:target="resetSelectedPlan" type="button"
                            class="group relative h-12 px-6 rounded-xl overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.98] transition-transform">
                            <span
                                class="absolute inset-0 bg-gradient-to-r from-gray-600 via-slate-600 to-gray-600"></span>
                            <span
                                class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-gradient-to-r from-gray-500 via-slate-500 to-gray-500 transition-opacity"></span>
                            <span class="relative flex items-center gap-2.5 text-sm font-semibold text-black">
                                <span wire:loading.remove wire:target="resetSelectedPlan"
                                    class="inline-flex items-center gap-2.5">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                    Annuler la demande
                                </span>
                                <span wire:loading wire:target="resetSelectedPlan"
                                    class="inline-flex items-center gap-2.5">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                    Annulation…
                                </span>
                            </span>
                        </button>
                    </div>
                    <div class="mt-6 flex justify-center sm:justify-end" x-data
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <button wire:click="confirmRequestSubscription" wire:loading.attr="disabled"
                            wire:target="confirmRequestSubscription" type="button"
                            class="group relative h-12 px-6 rounded-xl overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.98] transition-transform">
                            <span
                                class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-violet-600 to-indigo-600"></span>
                            <span
                                class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-gradient-to-r from-indigo-500 via-violet-500 to-indigo-500 transition-opacity"></span>
                            <span class="relative flex items-center gap-2.5 text-sm font-semibold text-white">
                                <span wire:loading.remove wire:target="confirmRequestSubscription"
                                    class="inline-flex items-center gap-2.5">
                                    <x-lucide-send class="w-4 h-4" />
                                    Envoyer la demande
                                </span>
                                <span wire:loading wire:target="confirmRequestSubscription"
                                    class="inline-flex items-center gap-2.5">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                    Envoi…
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            @endif
        </section>

    </div>
</div>

