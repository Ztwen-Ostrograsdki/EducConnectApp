<div class="min-h-screen space-y-6 p-4 sm:p-6 lg:p-8">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}
    <header
        class="relative overflow-hidden rounded-2xl border border-white/5 bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950/40 p-6 sm:p-8">
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl">
        </div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0">
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-indigo-400/20 bg-indigo-500/10 px-3 py-1 text-xs font-medium text-indigo-300">
                    <x-lucide-puzzle class="h-3.5 w-3.5" />
                    Modules d’accès
                </div>

                <h1 class="mt-4 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                    Gestion des modules
                </h1>

                @if ($this->subscription)
                    <p class="mt-2 text-sm text-slate-400">
                        <span class="font-medium text-slate-300">
                            {{ $this->subscription->tenant?->school_name ?? $this->subscription->tenant_id }}
                        </span>
                        <span class="text-slate-600">·</span>
                        Pack
                        <span class="font-medium text-slate-300">
                            {{ $this->subscription->plan?->packLabel() ?? ($this->moduleAccess?->pack ?? '—') }}
                        </span>
                        <span class="text-slate-600">·</span>
                        Expire le
                        <span class="font-medium text-slate-300">
                            {{ $this->subscription->expire_at?->format('d/m/Y') ?? '—' }}
                        </span>
                        @if ($this->subscription->daysRemaining() !== null)
                            <span
                                class="ml-1 tabular-nums text-{{ $this->subscription->daysRemaining() < 15 ? 'amber' : 'emerald' }}-400">
                                ({{ $this->subscription->daysRemaining() }} j)
                            </span>
                        @endif
                    </p>
                @else
                    <p class="mt-2 text-sm text-rose-400">Subscription introuvable.</p>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($this->canEdit)
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-400 ring-1 ring-emerald-500/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Éditable
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/10 px-3 py-1 text-xs font-medium text-rose-400 ring-1 ring-rose-500/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                        Lecture seule (subscription expirée ou inactive)
                    </span>
                @endif
            </div>
        </div>
    </header>

    @if (!$this->moduleAccess)
        <div class="rounded-2xl border border-white/5 bg-slate-900/70 px-6 py-16 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-800/80">
                <x-lucide-puzzle class="h-7 w-7 text-slate-500" />
            </div>
            <p class="mt-4 text-sm text-slate-400">
                Aucun enregistrement <code class="text-slate-300">TenantModuleAccess</code> pour cette subscription.
            </p>
        </div>
    @else
        {{-- ===================================================== --}}
        {{-- APPLIQUER UN PACK --}}
        {{-- ===================================================== --}}
        <section class="rounded-2xl border border-white/5 bg-slate-900/70 p-5 sm:p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white">Appliquer un pack</h2>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Réinitialise tous les modules selon le pack choisi. Le pack passera en
                        <span class="text-slate-400">custom</span> dès qu’un module est basculé manuellement.
                    </p>
                    <p class="mt-2 text-xs text-slate-500">
                        Pack actuel :
                        <span class="font-medium uppercase tracking-wide text-indigo-300">
                            {{ $this->moduleAccess->pack ?? '—' }}
                        </span>
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <select wire:model="selectedPack" @disabled(!$this->canEdit)
                        class="h-10 rounded-lg border border-white/10 bg-slate-800/80 px-3 text-sm text-slate-200 outline-none focus:border-indigo-500/50 disabled:cursor-not-allowed disabled:opacity-50">
                        @foreach ($this->availablePacks as $pack)
                            <option value="{{ $pack }}">{{ ucfirst($pack) }}</option>
                        @endforeach
                        <option value="custom">Custom</option>
                    </select>

                    <button type="button" wire:click="applyPack" @disabled(!$this->canEdit || $selectedPack === 'custom')
                        class="inline-flex h-10 items-center gap-2 rounded-lg bg-indigo-500/15 px-4 text-sm font-medium text-indigo-300 ring-1 ring-indigo-500/25 transition hover:bg-indigo-500/25 disabled:cursor-not-allowed disabled:opacity-40">
                        <x-lucide-download class="h-4 w-4" />
                        Appliquer
                    </button>
                </div>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- MODULES PAR CATÉGORIE --}}
        {{-- ===================================================== --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            @foreach ($this->modulesByCategory as $category => $modules)
                <section class="overflow-hidden rounded-2xl border border-white/5 bg-slate-900/70">
                    <div
                        class="flex flex-col gap-3 border-b border-white/5 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-base font-semibold text-white">{{ $category }}</h2>
                            <p class="mt-0.5 text-xs text-slate-500">
                                {{ collect($modules)->where('enabled', true)->count() }} / {{ count($modules) }} actifs
                            </p>
                        </div>

                        @if ($this->canEdit)
                            <div class="flex gap-2">
                                <button type="button" wire:click="enableAllInCategory('{{ $category }}')"
                                    class="inline-flex h-8 items-center rounded-lg bg-emerald-500/10 px-2.5 text-xs font-medium text-emerald-400 ring-1 ring-emerald-500/20 transition hover:bg-emerald-500/20">
                                    Tout activer
                                </button>
                                <button type="button" wire:click="disableAllInCategory('{{ $category }}')"
                                    class="inline-flex h-8 items-center rounded-lg bg-rose-500/10 px-2.5 text-xs font-medium text-rose-400 ring-1 ring-rose-500/20 transition hover:bg-rose-500/20">
                                    Tout désactiver
                                </button>
                            </div>
                        @endif
                    </div>

                    <ul class="divide-y divide-white/5">
                        @foreach ($modules as $module)
                            <li
                                class="flex items-center justify-between gap-4 px-5 py-4 transition hover:bg-white/[0.02]">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-200">{{ $module['label'] }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $module['description'] }}</p>
                                </div>

                                <button type="button" wire:click="toggleModule('{{ $module['key'] }}')"
                                    @disabled(!$this->canEdit) role="switch"
                                    aria-checked="{{ $module['enabled'] ? 'true' : 'false' }}"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border transition
                                           {{ $module['enabled'] ? 'border-emerald-500/40 bg-emerald-500/80' : 'border-white/10 bg-slate-700' }}
                                           disabled:cursor-not-allowed disabled:opacity-40">
                                    <span
                                        class="pointer-events-none absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition
                                               {{ $module['enabled'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>

    @endif
</div>

