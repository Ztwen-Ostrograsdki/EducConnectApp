<div class="min-h-screen bg-[#070b14] text-slate-100 space-y-6 p-3 sm:p-5 mb-24">

    {{-- ===================================================== --}}
    {{-- HEADER / HERO --}}
    {{-- ===================================================== --}}
    <section
        class="relative overflow-hidden rounded-3xl border border-white/[0.06] bg-[#0f1523] shadow-xl shadow-black/20">

        {{-- COVER --}}
        <div class="relative h-48 sm:h-56 bg-gradient-to-br from-indigo-600 via-sky-600 to-cyan-500">
            <div
                class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white/10 via-transparent to-black/40">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f1523] via-transparent to-transparent opacity-90">
            </div>

            {{-- BADGES --}}
            <div class="absolute top-4 right-4 sm:top-5 sm:right-5 flex flex-wrap justify-end gap-2">
                @if ($this->tenant->isActive())
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/20 px-3 py-1.5 text-xs font-semibold text-emerald-200 backdrop-blur-md">
                        <x-lucide-badge-check class="h-3.5 w-3.5" />
                        École active
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-rose-400/30 bg-rose-500/20 px-3 py-1.5 text-xs font-semibold text-rose-200 backdrop-blur-md">
                        <x-lucide-lock class="h-3.5 w-3.5" />
                        Non active
                    </span>
                @endif

                @if ($this->activeSubscription)
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-indigo-300/40 bg-indigo-500/90 px-3 py-1.5 text-xs font-semibold text-white shadow-lg shadow-indigo-900/40 backdrop-blur-md">
                        <x-lucide-crown class="h-3.5 w-3.5" />
                        {{ $this->activeSubscription->plan?->name }}
                    </span>
                @endif
            </div>
        </div>

        {{-- SCHOOL INFO --}}
        <div class="relative px-5 pb-6 sm:px-6 -mt-16 sm:-mt-20">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

                <div class="flex flex-col gap-5 sm:flex-row sm:items-end">
                    {{-- LOGO --}}
                    <div class="relative shrink-0">
                        <div
                            class="flex h-28 w-28 sm:h-32 sm:w-32 items-center justify-center rounded-3xl border-4 border-[#0f1523] bg-[#151c2c] shadow-2xl shadow-black/50 ring-1 ring-white/5">
                            <x-lucide-school class="h-14 w-14 text-indigo-400" />
                        </div>
                    </div>

                    {{-- DETAILS --}}
                    <div class="min-w-0 space-y-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl font-black tracking-tight text-white sm:text-3xl lg:text-4xl">
                                {{ $this->tenant->school_name }}
                                @if ($this->tenant->simple_name)
                                    <span class="font-semibold text-slate-400">· {{ $this->tenant->simple_name }}</span>
                                @endif
                            </h1>

                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border border-sky-500/25 bg-sky-500/10 px-3 py-1 text-xs font-semibold text-sky-300">
                                <x-lucide-graduation-cap class="h-3.5 w-3.5" />
                                {{ $this->tenant->enseignement_type }}
                            </span>
                        </div>

                        @if ($this->tenant->devise)
                            <p class="text-base text-slate-400 italic">« {{ $this->tenant->devise }} »</p>
                        @endif

                        {{-- INFOS RAPIDES --}}
                        <div class="flex flex-wrap gap-2.5 pt-1">
                            <div
                                class="inline-flex items-center gap-2 rounded-xl border border-white/5 bg-[#070b14]/60 px-3.5 py-2 text-sm text-slate-300 backdrop-blur-sm">
                                <x-lucide-map-pinned class="h-4 w-4 shrink-0 text-amber-400" />
                                <span class="truncate">{{ $this->tenant->adresse }}, {{ $this->tenant->country }}</span>
                            </div>

                            <div
                                class="inline-flex items-center gap-2 rounded-xl border border-white/5 bg-[#070b14]/60 px-3.5 py-2 text-sm text-slate-300 backdrop-blur-sm">
                                <x-lucide-phone class="h-4 w-4 shrink-0 text-emerald-400" />
                                <span>{{ $this->tenant->contacts }}</span>
                            </div>

                            <div
                                class="inline-flex max-w-full items-center gap-2 rounded-xl border border-white/5 bg-[#070b14]/60 px-3.5 py-2 text-sm text-slate-300 backdrop-blur-sm">
                                <x-lucide-mail class="h-4 w-4 shrink-0 text-sky-400" />
                                <span class="truncate">{{ $this->tenant->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================== --}}
    {{-- ABONNEMENT STATUS --}}
    {{-- ===================================================== --}}
    <section>
        @if ($this->activeSubscription)
            <div
                class="relative overflow-hidden rounded-2xl border border-emerald-500/20 bg-gradient-to-r from-emerald-500/10 via-emerald-500/[0.04] to-transparent p-5 sm:p-6">
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-emerald-400/10 blur-3xl"></div>

                <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-500/25 bg-emerald-500/15">
                        <x-lucide-shield-check class="h-6 w-6 text-emerald-400" />
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-400/80">
                            Abonnement actif
                        </p>
                        <h2 class="mt-0.5 text-lg font-bold text-white">
                            {{ $this->activeSubscription->plan->name }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-400">
                            Expire le
                            <span
                                class="font-medium text-slate-200">{{ $this->activeSubscription->expire_at->format('d/m/Y') }}</span>
                            <span class="mx-1.5 text-slate-600">·</span>
                            <span class="font-semibold tabular-nums text-emerald-300">
                                {{ $this->activeSubscription->daysRemaining() }} jours
                            </span>
                            restants
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div
                class="relative overflow-hidden rounded-2xl border border-rose-500/20 bg-gradient-to-r from-rose-500/10 via-rose-500/[0.04] to-transparent p-5 sm:p-6">
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-rose-400/10 blur-3xl"></div>

                <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-rose-500/25 bg-rose-500/15">
                        <x-lucide-shield-off class="h-6 w-6 text-rose-400" />
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-rose-400/80">
                            Aucun abonnement actif
                        </p>
                        <p class="mt-1 text-sm text-slate-300">
                            Cette école n’a actuellement aucun abonnement en cours.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- ===================================================== --}}
    {{-- ACTIONS --}}
    {{-- ===================================================== --}}
    <section class="flex flex-wrap items-center justify-end gap-2.5">
        <button wire:click="openGrantFreeModal" type="button"
            class="inline-flex h-11 items-center gap-2 rounded-xl border border-fuchsia-500/25 bg-fuchsia-500/10 px-4 text-sm font-medium text-fuchsia-300 transition-all hover:bg-fuchsia-500/20 hover:border-fuchsia-500/40">
            <x-lucide-gift class="h-4 w-4" />
            Offrir un abonnement
        </button>

        <button type="button"
            class="inline-flex h-11 items-center gap-2 rounded-xl border border-sky-500/25 bg-sky-500/10 px-4 text-sm font-medium text-sky-300 transition-all hover:bg-sky-500/20 hover:border-sky-500/40">
            <x-lucide-send class="h-4 w-4" />
            Notifier
        </button>

        @if (!$this->tenant->domain_blocked)
            <button title="Bloquer l'accès au domaine de l'école {{ $this->tenant->school_name }}"
                wire:click="blockDomain('{{ $this->tenant->id }}')" wire:loading.attr="disabled"
                wire:target="blockDomain"
                class="inline-flex h-11 items-center gap-2 rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 text-sm font-medium text-rose-300 transition-all hover:bg-rose-500/20 disabled:opacity-60">
                <span wire:loading.remove wire:target="blockDomain" class="inline-flex items-center gap-2">
                    <x-lucide-ban class="h-4 w-4" />
                    Bloquer accès
                </span>
                <span wire:loading wire:target="blockDomain" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                    En cours…
                </span>
            </button>
        @else
            <button title="Débloquer et re-accorder l'accès au domaine de l'école {{ $this->tenant->school_name }}"
                wire:click="unblockDomain('{{ $this->tenant->id }}')" wire:loading.attr="disabled"
                wire:target="unblockDomain"
                class="inline-flex h-11 items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 text-sm font-medium text-emerald-300 transition-all hover:bg-emerald-500/20 disabled:opacity-60">
                <span wire:loading.remove wire:target="unblockDomain" class="inline-flex items-center gap-2">
                    <x-lucide-unlock class="h-4 w-4" />
                    Accorder accès
                </span>
                <span wire:loading wire:target="unblockDomain" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                    En cours…
                </span>
            </button>
        @endif
    </section>

    {{-- ===================================================== --}}
    {{-- DETAILS + DIRECTOR --}}
    {{-- ===================================================== --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- INFORMATIONS GÉNÉRALES --}}
        <div
            class="xl:col-span-2 rounded-3xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-lg shadow-black/10">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-white">Informations générales</h2>
                <p class="mt-1 text-sm text-slate-500">Détails administratifs de l’établissement</p>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                @foreach ($this->infos as $info)
                    <div
                        class="group flex items-center gap-3.5 rounded-2xl border border-white/[0.04] bg-[#070b14]/50 p-4 transition-all hover:border-indigo-500/20 hover:bg-[#070b14]/80">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-500/10 border border-indigo-500/15 text-indigo-400 transition-colors group-hover:bg-indigo-500/15">
                            @switch($info[2])
                                @case('user-round')
                                    <x-lucide-user-round class="h-5 w-5" />
                                @break

                                @case('layers-3')
                                    <x-lucide-layers-3 class="h-5 w-5" />
                                @break

                                @case('network')
                                    <x-lucide-network class="h-5 w-5" />
                                @break

                                @case('git-branch')
                                    <x-lucide-git-branch class="h-5 w-5" />
                                @break

                                @case('badge-check')
                                    <x-lucide-badge-check class="h-5 w-5" />
                                @break

                                @case('database')
                                    <x-lucide-database class="h-5 w-5" />
                                @break

                                @case('globe')
                                    <x-lucide-globe class="h-5 w-5" />
                                @break

                                @default
                                    <x-lucide-calendar-days class="h-5 w-5" />
                            @endswitch
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-slate-500">
                                {{ $info[0] }}
                            </p>
                            <p class="mt-0.5 truncate font-semibold text-slate-100">
                                {{ $info[1] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- DIRECTEUR --}}
        <div class="rounded-3xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-lg shadow-black/10">
            <h2 class="text-lg font-bold text-white">Directeur</h2>

            <div class="mt-6 text-center">
                <div class="relative mx-auto h-28 w-28">
                    <img src="{{ $this->profil_photo_url }}" alt="Photo du directeur"
                        class="h-full w-full rounded-2xl object-cover ring-2 ring-white/10 shadow-xl">
                    <div
                        class="absolute -bottom-1 -right-1 flex h-7 w-7 items-center justify-center rounded-full border-2 border-[#0f1523] bg-emerald-500">
                        <x-lucide-check class="h-3.5 w-3.5 text-white" />
                    </div>
                </div>

                <h3 class="mt-5 text-xl font-bold text-white">
                    {{ $this->tenant->getFullName() }}
                </h3>

                <span
                    class="mt-2 inline-flex items-center rounded-full bg-emerald-500/15 border border-emerald-500/25 px-3.5 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-300">
                    Directeur
                </span>
            </div>

            <div class="mt-6 space-y-2.5">
                <div class="flex items-center gap-3 rounded-xl border border-white/[0.04] bg-[#070b14]/50 px-4 py-3">
                    <x-lucide-mail class="h-4 w-4 shrink-0 text-sky-400" />
                    <span class="truncate text-sm text-slate-300">{{ $this->tenant->email }}</span>
                </div>

                <div class="flex items-center gap-3 rounded-xl border border-white/[0.04] bg-[#070b14]/50 px-4 py-3">
                    <x-lucide-phone class="h-4 w-4 shrink-0 text-emerald-400" />
                    <span class="text-sm text-slate-300">{{ $this->tenant->contacts }}</span>
                </div>

                <div class="flex items-center gap-3 rounded-xl border border-white/[0.04] bg-[#070b14]/50 px-4 py-3">
                    <x-lucide-clock-3 class="h-4 w-4 shrink-0 text-amber-400" />
                    <span class="text-sm text-slate-400">Dernière connexion : —</span>
                </div>
            </div>

            <div class="mt-6">
                <button wire:click="sendCredentialsToTenant('{{ $this->tenant->id }}')" wire:loading.attr="disabled"
                    class="h-11 rounded-2xl w-full flex items-center justify-center cursor-pointer bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 ">
                    <span wire:loading.remove class="flex items-center gap-1.5"
                        wire:target="sendCredentialsToTenant('{{ $this->tenant->id }}')">
                        <x-lucide-message-square class="w-4 h-4" />
                        Envoyer données de connexion
                    </span>
                    <span wire:loading.flex wire:target="sendCredentialsToTenant('{{ $this->tenant->id }}')"
                        class="items-center gap-1.5">
                        <span class="inline-flex items-center gap-1">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </span>
                </button>
            </div>
        </div>
    </section>

    {{-- ===================================================== --}}
    {{-- MODAL OFFRIR ABONNEMENT --}}
    {{-- ===================================================== --}}
    @if ($showGrantFreeModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#070b14]/80 p-4 backdrop-blur-sm" x-data
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="w-full max-w-md overflow-hidden rounded-2xl border border-white/[0.08] bg-[#0f1523] shadow-2xl shadow-black/50"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                @click.outside="$wire.closeGrantFreeModal()">
                <div class="flex items-center gap-3 border-b border-white/[0.05] px-5 py-4">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-fuchsia-500/25 bg-fuchsia-500/15">
                        <x-lucide-gift class="h-5 w-5 text-fuchsia-400" />
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-white">Offrir un abonnement</h2>
                        <p class="text-xs text-slate-500">Pour « {{ $this->tenant->school_name }} »</p>
                    </div>
                </div>

                <div class="space-y-4 p-5">
                    <div>
                        <label
                            class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">Plan</label>
                        <select wire:model="grantPlanId"
                            class="w-full rounded-xl border border-white/10 bg-[#070b14] px-3.5 py-2.5 text-sm text-slate-200 focus:border-fuchsia-500/50 focus:outline-none focus:ring-1 focus:ring-fuchsia-500/30">
                            <option value="">-- Choisir un plan --</option>
                            @foreach ($this->plans as $plan)
                                <option value="{{ $plan->id }}">
                                    {{ $plan->name }} ({{ $plan->packLabel() }})
                                </option>
                            @endforeach
                        </select>
                        @error('grantPlanId')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">Nombre
                            de jours</label>
                        <input type="number" wire:model="grantDaysCount" min="1"
                            class="w-full rounded-xl border border-white/10 bg-[#070b14] px-3.5 py-2.5 text-sm text-slate-200 focus:border-fuchsia-500/50 focus:outline-none focus:ring-1 focus:ring-fuchsia-500/30">
                        @error('grantDaysCount')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 border-t border-white/[0.05] px-5 py-4">
                    <button wire:click="closeGrantFreeModal" type="button"
                        class="h-10 rounded-xl border border-white/10 bg-white/5 px-4 text-sm text-slate-400 transition-all hover:bg-white/10">
                        Annuler
                    </button>

                    <button wire:click="confirmGrantFreeSubscription" wire:loading.attr="disabled"
                        wire:target="confirmGrantFreeSubscription" type="button"
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-fuchsia-600 px-5 text-sm font-semibold text-white shadow-lg shadow-fuchsia-900/30 transition-all hover:bg-fuchsia-500 disabled:opacity-60">
                        <span wire:loading.remove wire:target="confirmGrantFreeSubscription"
                            class="inline-flex items-center gap-2">
                            <x-lucide-gift class="h-4 w-4" />
                            Offrir
                        </span>
                        <span wire:loading wire:target="confirmGrantFreeSubscription"
                            class="inline-flex items-center gap-2">
                            <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                            Traitement…
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

