<div class="min-h-screen space-y-8 p-4 sm:p-6 lg:p-8">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <header
        class="relative overflow-hidden rounded-2xl border border-white/5 bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950/40 p-6 sm:p-8">
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl">
        </div>
        <div class="pointer-events-none absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-sky-500/10 blur-3xl">
        </div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-indigo-400/20 bg-indigo-500/10 px-3 py-1 text-xs font-medium text-indigo-300">
                    <x-lucide-shield-check class="h-3.5 w-3.5" />
                    Administration Centrale
                    <span class="text-indigo-400/70">·</span>
                    <span>{{ __formatDate(now()) }}</span>
                </div>

                <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    Dashboard Central
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400 sm:text-base">
                    Supervision globale des établissements, abonnements, demandes d’inscription et accès plateforme.
                </p>
            </div>
        </div>
    </header>

    {{-- ===================================================== --}}
    {{-- KPI CARDS --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($this->kpis as $card)
            <article
                class="group relative overflow-hidden rounded-2xl border border-white/5 bg-slate-900/70 p-5 backdrop-blur transition hover:border-white/10 hover:bg-slate-900">
                <div class="absolute -right-6 -top-6 opacity-[0.07] transition group-hover:opacity-[0.12]">
                    <x-dynamic-component :component="'lucide-' . $card[3]" class="h-28 w-28" />
                </div>

                <div class="relative flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">
                            {{ $card[0] }}
                        </p>
                        <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-white sm:text-4xl">
                            {{ $card[1] }}
                        </p>
                    </div>

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-{{ $card[4] }}-500/15 ring-1 ring-{{ $card[4] }}-500/20">
                        <x-dynamic-component :component="'lucide-' . $card[3]" class="h-5 w-5 text-{{ $card[4] }}-400" />
                    </div>
                </div>

                <div class="relative mt-4 flex items-center gap-1.5 text-xs font-medium text-{{ $card[4] }}-400">
                    <x-lucide-trending-up class="h-3.5 w-3.5" />
                    <span>{{ $card[2] }}</span>
                    <span class="font-normal text-slate-500">ce mois-ci</span>
                </div>
            </article>
        @endforeach
    </section>

    {{-- ===================================================== --}}
    {{-- GRAPH + DISTRIBUTION --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Graph placeholder --}}
        <div class="xl:col-span-2 rounded-2xl border border-white/5 bg-slate-900/70 p-5 sm:p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white">Évolution des inscriptions</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Suivi des écoles enregistrées</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <select
                        class="h-9 rounded-lg border border-white/10 bg-slate-800/80 px-3 text-sm text-slate-300 outline-none focus:border-indigo-500/50">
                        <option>12 derniers mois</option>
                        <option>6 derniers mois</option>
                    </select>
                    <button type="button"
                        class="inline-flex h-9 items-center gap-2 rounded-lg border border-white/10 bg-slate-800/80 px-3 text-sm text-slate-300 transition hover:bg-slate-700/80">
                        <x-lucide-refresh-cw class="h-3.5 w-3.5" />
                        Actualiser
                    </button>
                </div>
            </div>

            <div
                class="mt-6 flex h-[320px] items-center justify-center rounded-xl border border-dashed border-white/10 bg-slate-950/40">
                <div class="text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-800/80">
                        <x-lucide-line-chart class="h-7 w-7 text-slate-500" />
                    </div>
                    <p class="mt-3 text-sm text-slate-500">Graphique des inscriptions</p>
                </div>
            </div>
        </div>

        {{-- Distribution --}}
        <div class="rounded-2xl border border-white/5 bg-slate-900/70 p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-white">Répartition des écoles</h2>
            <p class="mt-0.5 text-sm text-slate-500">Par type d’enseignement</p>

            <div class="mt-6 space-y-5">
                @foreach ($this->schoolsDistribution as $item)
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-sm">
                            <span class="text-slate-300">{{ $item[0] }}</span>
                            <span
                                class="font-medium tabular-nums text-{{ $item[2] }}-400">{{ $item[1] }}</span>
                        </div>
                        <div class="h-1.5 overflow-hidden rounded-full bg-slate-800">
                            <div class="h-full rounded-full bg-{{ $item[2] }}-500 transition-all duration-500"
                                style="width: {{ $item[1] }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3">
                @foreach ($this->miniStats as $mini)
                    <div class="rounded-xl border border-white/5 bg-slate-950/50 p-3.5">
                        <p class="text-[11px] font-medium uppercase tracking-wider text-slate-500">{{ $mini[0] }}
                        </p>
                        <p class="mt-1 text-xl font-bold tabular-nums text-white">{{ $mini[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================================================== --}}
    {{-- DEMANDES D'INSCRIPTION (nouveaux tenants) --}}
    {{-- ===================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-white/5 bg-slate-900/70">
        <div
            class="flex flex-col gap-4 border-b border-white/5 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div>
                <h2 class="text-lg font-semibold text-white">Demandes d’inscription</h2>
                <p class="mt-0.5 text-sm text-slate-500">Validation des nouveaux établissements</p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <div class="relative">
                    <x-lucide-search
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
                    <input type="text" wire:model.live.debounce.300ms="searchRequests" placeholder="Rechercher…"
                        class="h-9 w-full rounded-lg border border-white/10 bg-slate-800/80 pl-9 pr-3 text-sm text-slate-200 placeholder:text-slate-500 outline-none focus:border-indigo-500/50 sm:w-56" />
                </div>

                <select wire:model.live="statusFilter"
                    class="h-9 rounded-lg border border-white/10 bg-slate-800/80 px-3 text-sm text-slate-300 outline-none focus:border-indigo-500/50">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="payment_claimed">Paiement signalé</option>
                    <option value="approved">Validées</option>
                    <option value="rejected">Rejetées</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left text-sm">
                <thead>
                    <tr
                        class="border-b border-white/5 bg-slate-950/50 text-xs font-medium uppercase tracking-wider text-slate-500">
                        @foreach (['École', 'Directeur', 'Email', 'Type', 'Date', 'Statut', 'Actions'] as $head)
                            <th class="px-5 py-3.5 font-medium">{{ $head }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($this->pendingNewTenantRequests as $request)
                        <tr class="transition hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-500/10 ring-1 ring-indigo-500/20">
                                        <x-lucide-school class="h-5 w-5 text-indigo-400" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-white">{{ $request->school_name ?? '—' }}
                                        </p>
                                        <p class="truncate text-xs text-slate-500">
                                            {{ $request->city ?? ($request->country ?? '—') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-300">
                                {{ $request->getUserNamePrefix(true) }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-400">
                                {{ $request->email ?? '—' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap capitalize text-slate-300">
                                {{ $request->enseignement_type ?? '—' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-400">
                                {{ $request->created_at?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="inline-flex items-center rounded-full bg-amber-500/10 px-2.5 py-0.5 text-xs font-medium text-amber-400 ring-1 ring-amber-500/20">
                                    En attente
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        class="inline-flex h-8 items-center rounded-lg bg-emerald-500/10 px-3 text-xs font-medium text-emerald-400 ring-1 ring-emerald-500/20 transition hover:bg-emerald-500/20">
                                        Valider
                                    </button>
                                    <button type="button"
                                        class="inline-flex h-8 items-center rounded-lg bg-rose-500/10 px-3 text-xs font-medium text-rose-400 ring-1 ring-rose-500/20 transition hover:bg-rose-500/20">
                                        Refuser
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-800/80">
                                    <x-lucide-inbox class="h-6 w-6 text-slate-500" />
                                </div>
                                <p class="mt-3 text-sm text-slate-500">Aucune demande d’inscription en attente</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===================================================== --}}
    {{-- DEMANDES D'ABONNEMENT --}}
    {{-- ===================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-white/5 bg-slate-900/70">
        <div class="border-b border-white/5 p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-white">Demandes d’abonnement</h2>
            <p class="mt-0.5 text-sm text-slate-500">Validation des paiements et renouvellements</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead>
                    <tr
                        class="border-b border-white/5 bg-slate-950/50 text-xs font-medium uppercase tracking-wider text-slate-500">
                        @foreach (['École', 'Pack', 'Transaction', 'Date', 'Statut', 'Actions'] as $head)
                            <th class="px-5 py-3.5 font-medium">{{ $head }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($this->pendingsSubscriptionRequests as $req)
                        <tr class="transition hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-500/10 ring-1 ring-sky-500/20">
                                        <x-lucide-building-2 class="h-5 w-5 text-sky-400" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-white">
                                            {{ $req->tenant?->data['school_name'] ?? ($req->tenant_id ?? '—') }}
                                        </p>
                                        <p class="truncate text-xs text-slate-500">{{ $req->tenant_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-300">
                                {{ $req->plan?->packLabel() ?? ($req->plan?->name ?? '—') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-slate-400">
                                {{ $req->transaction_id ?? '—' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-400">
                                {{ $req->created_at?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="inline-flex items-center rounded-full bg-{{ $req->statusColor() }}-500/10 px-2.5 py-0.5 text-xs font-medium text-{{ $req->statusColor() }}-400 ring-1 ring-{{ $req->statusColor() }}-500/20">
                                    {{ $req->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if ($req->canBeActedOn())
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                            class="inline-flex h-8 items-center rounded-lg bg-emerald-500/10 px-3 text-xs font-medium text-emerald-400 ring-1 ring-emerald-500/20 transition hover:bg-emerald-500/20">
                                            Approuver
                                        </button>
                                        <button type="button"
                                            class="inline-flex h-8 items-center rounded-lg bg-rose-500/10 px-3 text-xs font-medium text-rose-400 ring-1 ring-rose-500/20 transition hover:bg-rose-500/20">
                                            Rejeter
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500">Traité</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-800/80">
                                    <x-lucide-inbox class="h-6 w-6 text-slate-500" />
                                </div>
                                <p class="mt-3 text-sm text-slate-500">Aucune demande d’abonnement en attente</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===================================================== --}}
    {{-- ABONNEMENTS + ÉCOLES --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Abonnements actifs --}}
        <div class="overflow-hidden rounded-2xl border border-white/5 bg-slate-900/70">
            <div class="border-b border-white/5 p-5 sm:p-6">
                <h2 class="text-lg font-semibold text-white">Abonnements actifs</h2>
                <p class="mt-0.5 text-sm text-slate-500">Suivi des échéances</p>
            </div>

            <div class="divide-y divide-white/5">
                @forelse ($this->activesSubscriptions as $sub)
                    <div class="p-5 transition hover:bg-white/[0.02]">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-500/10 ring-1 ring-sky-500/20">
                                        <x-lucide-credit-card class="h-5 w-5 text-sky-400" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-white">
                                            {{ $sub->tenant?->data['school_name'] ?? ($sub->tenant_id ?? 'École') }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            Pack {{ $sub->plan?->packLabel() ?? ($sub->plan?->name ?? '—') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-xs text-slate-500">Début</p>
                                        <p class="mt-0.5 text-slate-300">
                                            {{ $sub->started_at?->format('d M Y') ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500">Restants</p>
                                        <p
                                            class="mt-0.5 font-medium tabular-nums {{ $sub->daysRemaining() < 15 ? 'text-amber-400' : 'text-emerald-400' }}">
                                            {{ $sub->daysRemaining() }} jours
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <button type="button"
                                    class="inline-flex h-8 items-center rounded-lg bg-amber-500/10 px-3 text-xs font-medium text-amber-400 ring-1 ring-amber-500/20 transition hover:bg-amber-500/20">
                                    Rappel
                                </button>
                                <button type="button"
                                    class="inline-flex h-8 items-center rounded-lg bg-indigo-500/10 px-3 text-xs font-medium text-indigo-400 ring-1 ring-indigo-500/20 transition hover:bg-indigo-500/20">
                                    Étendre
                                </button>
                                <button type="button"
                                    class="inline-flex h-8 items-center rounded-lg bg-rose-500/10 px-3 text-xs font-medium text-rose-400 ring-1 ring-rose-500/20 transition hover:bg-rose-500/20">
                                    Suspendre
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-14 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-800/80">
                            <x-lucide-credit-card class="h-6 w-6 text-slate-500" />
                        </div>
                        <p class="mt-3 text-sm text-slate-500">Aucun abonnement actif</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Écoles enregistrées --}}
        <div class="overflow-hidden rounded-2xl border border-white/5 bg-slate-900/70">
            <div class="border-b border-white/5 p-5 sm:p-6">
                <h2 class="text-lg font-semibold text-white">Écoles enregistrées</h2>
                <p class="mt-0.5 text-sm text-slate-500">Aperçu des établissements</p>
            </div>

            <div class="divide-y divide-white/5">
                @forelse ($this->schools as $tenant)
                    @php
                        $stats = $tenant->statistics?->first() ?? $tenant->statistics;
                        $schoolName = $tenant->data['school_name'] ?? ($tenant->id ?? 'École');
                        $enseignement = $tenant->data['enseignement_type'] ?? '—';
                    @endphp

                    <div class="p-5 transition hover:bg-white/[0.02]">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h3 class="font-medium text-white">{{ $schoolName }}</h3>
                                <p class="mt-0.5 text-sm capitalize text-slate-500">{{ $enseignement }}</p>
                            </div>
                            <span
                                class="inline-flex w-fit items-center rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-400 ring-1 ring-emerald-500/20">
                                Active
                            </span>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                            @php
                                $mini = [
                                    ['Classes', $stats->classes_count ?? 0],
                                    ['Élèves', $stats->students_count ?? 0],
                                    ['Enseignants', $stats->teachers_count ?? 0],
                                    ['Parents', $stats->parents_count ?? 0],
                                    ['Paiements', $stats->payments_count ?? 0],
                                    ['Année', $stats->current_school_year ?? '—'],
                                ];
                            @endphp

                            @foreach ($mini as $stat)
                                <div class="rounded-lg border border-white/5 bg-slate-950/40 px-3 py-2.5">
                                    <p class="text-[11px] text-slate-500">{{ $stat[0] }}</p>
                                    <p class="mt-0.5 text-sm font-semibold tabular-nums text-white">
                                        {{ $stat[1] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-14 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-800/80">
                            <x-lucide-school class="h-6 w-6 text-slate-500" />
                        </div>
                        <p class="mt-3 text-sm text-slate-500">Aucune école enregistrée</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

</div>

