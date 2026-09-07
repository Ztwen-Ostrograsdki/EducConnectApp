<div class="min-h-screen bg-[#070b14] text-slate-100 space-y-6 p-4 sm:p-6 lg:p-8">

    {{-- ===================== HEADER ===================== --}}
    <header
        class="relative overflow-hidden rounded-2xl border border-white/[0.06] bg-[#0f1523] p-6 sm:p-8 shadow-xl shadow-black/20 animate-[fadeInUp_0.5s_ease-out]">
        <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-indigo-500/15 blur-3xl">
        </div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-40 w-40 rounded-full bg-violet-500/10 blur-3xl">
        </div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0">
                <div
                    class="inline-flex items-center gap-1.5 rounded-full border border-indigo-500/25 bg-indigo-500/10 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-indigo-300">
                    <x-lucide-puzzle class="h-3 w-3" />
                    Modules d’accès
                </div>

                <h1 class="mt-4 flex items-center gap-3 text-sm font-bold tracking-tight text-white sm:text-lg">
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-indigo-500/25 bg-indigo-500/15">
                        <x-lucide-blocks class="h-5 w-5 text-indigo-400" />
                    </span>
                    Gestion des modules de l'abonnement <span
                        class="text-amber-500 font-mono">#{{ $this->subscription->key }}</span>
                </h1>

                @if ($this->subscription)
                    <div class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-slate-400 font-mono">
                        <span class="inline-flex items-center gap-1.5">
                            <x-lucide-school class="h-3.5 w-3.5 text-slate-500" />
                            <span class="font-medium text-slate-200">
                                {{ $this->subscription->tenant?->school_name ?? $this->subscription->tenant_id }}
                            </span>
                        </span>
                        <span class="text-slate-700">·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <x-lucide-package class="h-3.5 w-3.5 text-slate-500" />
                            Pack
                            <span class="font-medium text-indigo-300">
                                {{ $this->subscription->plan?->packLabel() ?? ($this->moduleAccess?->pack ?? '—') }}
                            </span>
                        </span>
                        <span class="text-slate-700">·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <x-lucide-calendar-clock class="h-3.5 w-3.5 text-slate-500" />
                            Expire le
                            <span class="font-medium text-slate-200">
                                {{ $this->subscription->expire_at?->format('d/m/Y') ?? '—' }}
                            </span>
                            @if ($this->subscription->daysRemaining() !== null)
                                <span @class([
                                    'ml-1 tabular-nums ',
                                    'text-amber-400' => $this->subscription->daysRemaining() < 15,
                                    'text-emerald-400' => $this->subscription->daysRemaining() >= 15,
                                ])>
                                    ({{ $this->subscription->daysRemaining() }} jours)
                                </span>
                            @endif
                        </span>
                    </div>
                @else
                    <p class="mt-3 flex items-center gap-2 text-sm text-rose-400">
                        <x-lucide-alert-circle class="h-4 w-4" />
                        Subscription introuvable.
                    </p>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($this->canEdit)
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/25 bg-emerald-500/10 px-3 py-1.5 text-xs font-medium text-emerald-300">
                        <x-lucide-pencil class="h-3.5 w-3.5" />
                        Éditable
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-rose-500/25 bg-rose-500/10 px-3 py-1.5 text-xs font-medium text-rose-300">
                        <x-lucide-lock class="h-3.5 w-3.5" />
                        Lecture seule
                    </span>
                @endif
            </div>
        </div>
    </header>

    @if (!$this->moduleAccess)
        <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] px-6 py-20 text-center">
            <div
                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/5 bg-white/5">
                <x-lucide-puzzle class="h-8 w-8 text-slate-600" />
            </div>
            <p class="mt-5 text-sm font-medium text-slate-400">Aucun accès modules configuré</p>
            <p class="mt-1 text-xs text-slate-600">
                Aucun enregistrement <code class="text-slate-500">TenantModuleAccess</code> pour cette subscription.
            </p>
        </div>
    @else
        {{-- ===================== APPLIQUER UN PACK ===================== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-lg shadow-black/10">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-indigo-500/25 bg-indigo-500/15">
                        <x-lucide-layers class="h-5 w-5 text-indigo-400" />
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-white">Appliquer un pack</h2>
                        <p class="mt-1 max-w-xl text-sm text-slate-500">
                            Réinitialise tous les modules selon le pack choisi.
                            Le pack passera en <span class="text-slate-400">custom</span> dès qu’un module est modifié
                            manuellement.
                        </p>
                        <p class="mt-2 inline-flex items-center gap-2 text-xs text-slate-500">
                            <x-lucide-tag class="h-3.5 w-3.5" />
                            Pack actuel :
                            <span
                                class="rounded-md border border-indigo-500/20 bg-indigo-500/10 px-2 py-0.5 font-semibold uppercase tracking-wide text-indigo-300">
                                {{ $this->moduleAccess->pack ?? '—' }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="relative">
                        <x-lucide-package
                            class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
                        <select wire:model="selectedPack" @disabled(!$this->canEdit)
                            class="h-11 appearance-none rounded-xl border border-white/10 bg-[#070b14] pl-9 pr-8 text-sm text-slate-200 outline-none transition focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/30 disabled:cursor-not-allowed disabled:opacity-50">
                            @foreach ($this->availablePacks as $pack)
                                <option value="{{ $pack }}">{{ ucfirst($pack) }}</option>
                            @endforeach
                            <option class="hidden" value="custom">Custom</option>
                        </select>
                    </div>

                    <button type="button" wire:click="applyPack" @disabled(!$this->canEdit || $selectedPack === 'custom')
                        class="inline-flex h-11 items-center gap-2 rounded-xl border border-indigo-500/30 bg-indigo-500/15 px-4 text-sm font-semibold text-indigo-300 transition-all hover:bg-indigo-500/25 disabled:cursor-not-allowed disabled:opacity-40">
                        <x-lucide-download class="h-4 w-4" />
                        Appliquer
                    </button>
                </div>
            </div>
        </section>

        {{-- ===================== MODULES PAR CATÉGORIE ===================== --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            @foreach ($this->modulesByCategory as $category => $modules)
                @php
                    // Palette cyclique par index (garantit des couleurs différentes)
                    $palettes = [
                        [
                            'icon' => 'graduation-cap',
                            'accent' => 'from-sky-500/25 via-sky-500/8 to-transparent',
                            'border' => 'border-sky-500/30 hover:border-sky-400/50',
                            'glow' => 'hover:shadow-sky-500/15',
                            'iconBg' => 'bg-sky-500/20 border-sky-500/30 text-sky-300',
                            'progress' => 'from-sky-500 to-cyan-400',
                            'badge' => 'text-sky-300',
                            'bar' => 'bg-sky-400',
                        ],
                        [
                            'icon' => 'building-2',
                            'accent' => 'from-indigo-500/25 via-indigo-500/8 to-transparent',
                            'border' => 'border-indigo-500/30 hover:border-indigo-400/50',
                            'glow' => 'hover:shadow-indigo-500/15',
                            'iconBg' => 'bg-indigo-500/20 border-indigo-500/30 text-indigo-300',
                            'progress' => 'from-indigo-500 to-violet-400',
                            'badge' => 'text-indigo-300',
                            'bar' => 'bg-indigo-400',
                        ],
                        [
                            'icon' => 'wallet',
                            'accent' => 'from-amber-500/25 via-amber-500/8 to-transparent',
                            'border' => 'border-amber-500/30 hover:border-amber-400/50',
                            'glow' => 'hover:shadow-amber-500/15',
                            'iconBg' => 'bg-amber-500/20 border-amber-500/30 text-amber-300',
                            'progress' => 'from-amber-500 to-orange-400',
                            'badge' => 'text-amber-300',
                            'bar' => 'bg-amber-400',
                        ],
                        [
                            'icon' => 'message-square',
                            'accent' => 'from-violet-500/25 via-violet-500/8 to-transparent',
                            'border' => 'border-violet-500/30 hover:border-violet-400/50',
                            'glow' => 'hover:shadow-violet-500/15',
                            'iconBg' => 'bg-violet-500/20 border-violet-500/30 text-violet-300',
                            'progress' => 'from-violet-500 to-fuchsia-400',
                            'badge' => 'text-violet-300',
                            'bar' => 'bg-violet-400',
                        ],
                        [
                            'icon' => 'users',
                            'accent' => 'from-emerald-500/25 via-emerald-500/8 to-transparent',
                            'border' => 'border-emerald-500/30 hover:border-emerald-400/50',
                            'glow' => 'hover:shadow-emerald-500/15',
                            'iconBg' => 'bg-emerald-500/20 border-emerald-500/30 text-emerald-300',
                            'progress' => 'from-emerald-500 to-teal-400',
                            'badge' => 'text-emerald-300',
                            'bar' => 'bg-emerald-400',
                        ],
                        [
                            'icon' => 'shield',
                            'accent' => 'from-rose-500/25 via-rose-500/8 to-transparent',
                            'border' => 'border-rose-500/30 hover:border-rose-400/50',
                            'glow' => 'hover:shadow-rose-500/15',
                            'iconBg' => 'bg-rose-500/20 border-rose-500/30 text-rose-300',
                            'progress' => 'from-rose-500 to-pink-400',
                            'badge' => 'text-rose-300',
                            'bar' => 'bg-rose-400',
                        ],
                        [
                            'icon' => 'blocks',
                            'accent' => 'from-cyan-500/25 via-cyan-500/8 to-transparent',
                            'border' => 'border-cyan-500/30 hover:border-cyan-400/50',
                            'glow' => 'hover:shadow-cyan-500/15',
                            'iconBg' => 'bg-cyan-500/20 border-cyan-500/30 text-cyan-300',
                            'progress' => 'from-cyan-500 to-sky-400',
                            'badge' => 'text-cyan-300',
                            'bar' => 'bg-cyan-400',
                        ],
                        [
                            'icon' => 'sparkles',
                            'accent' => 'from-fuchsia-500/25 via-fuchsia-500/8 to-transparent',
                            'border' => 'border-fuchsia-500/30 hover:border-fuchsia-400/50',
                            'glow' => 'hover:shadow-fuchsia-500/15',
                            'iconBg' => 'bg-fuchsia-500/20 border-fuchsia-500/30 text-fuchsia-300',
                            'progress' => 'from-fuchsia-500 to-pink-400',
                            'badge' => 'text-fuchsia-300',
                            'bar' => 'bg-fuchsia-400',
                        ],
                    ];

                    $palette = $palettes[$loop->index % count($palettes)];
                    $enabledCount = collect($modules)->where('enabled', true)->count();
                    $total = count($modules);
                @endphp

                <section
                    class="group relative overflow-hidden rounded-2xl border bg-[#0f1523] shadow-sm shadow-black/10
                   transition-all duration-300 hover:-translate-y-1 hover:shadow-lg
                   {{ $palette['border'] }} {{ $palette['glow'] }}">
                    {{-- Fond coloré --}}
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-br {{ $palette['accent'] }} transition-opacity duration-300 opacity-90 group-hover:opacity-100">
                    </div>

                    {{-- Barre latérale --}}
                    <div
                        class="absolute left-0 top-0 h-full w-1.5 {{ $palette['bar'] }} opacity-80 transition-all duration-300 group-hover:opacity-100 group-hover:w-2">
                    </div>

                    <div class="relative">
                        {{-- Header --}}
                        <div
                            class="flex flex-col gap-3 border-b border-white/[0.06] p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border transition-transform duration-300 group-hover:scale-110 {{ $palette['iconBg'] }}">
                                    <x-dynamic-component :component="'lucide-' . $palette['icon']" class="h-5 w-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-white">{{ $category }}</h2>
                                    <p class="mt-0.5 flex items-center gap-1.5 text-xs text-slate-500">
                                        <x-lucide-check-circle-2 class="h-3.5 w-3.5 {{ $palette['badge'] }}" />
                                        <span class="font-semibold {{ $palette['badge'] }}">{{ $enabledCount }}</span>
                                        <span>/ {{ $total }} actifs</span>
                                    </p>
                                </div>
                            </div>

                            @if ($this->canEdit)
                                <div class="flex gap-2">
                                    <button type="button" wire:click="enableAllInCategory('{{ $category }}')"
                                        class="inline-flex h-8 items-center gap-1.5 rounded-lg border border-emerald-500/25 bg-emerald-500/10 px-2.5 text-xs font-medium text-emerald-300 transition hover:bg-emerald-500/25">
                                        <x-lucide-check class="h-3.5 w-3.5" />
                                        Tout activer
                                    </button>
                                    <button type="button" wire:click="disableAllInCategory('{{ $category }}')"
                                        class="inline-flex h-8 items-center gap-1.5 rounded-lg border border-rose-500/25 bg-rose-500/10 px-2.5 text-xs font-medium text-rose-300 transition hover:bg-rose-500/25">
                                        <x-lucide-x class="h-3.5 w-3.5" />
                                        Tout désactiver
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Progress --}}
                        <div class="h-1 w-full bg-white/[0.04]">
                            <div class="h-full rounded-r-full bg-gradient-to-r {{ $palette['progress'] }} transition-all duration-500"
                                style="width: {{ $total > 0 ? ($enabledCount / $total) * 100 : 0 }}%"></div>
                        </div>

                        {{-- Modules --}}
                        <ul class="divide-y divide-white/[0.04]">
                            @foreach ($modules as $module)
                                <li
                                    class="flex items-center justify-between gap-4 px-5 py-4 transition-colors duration-200 hover:bg-white/[0.04]">
                                    <div class="flex min-w-0 items-start gap-3">
                                        <div @class([
                                            'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border transition-all duration-200',
                                            'border-emerald-500/30 bg-emerald-500/15 text-emerald-400' =>
                                                $module['enabled'],
                                            'border-white/5 bg-white/[0.03] text-slate-600' => !$module['enabled'],
                                        ])>
                                            <x-lucide-box class="h-4 w-4" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-200">{{ $module['label'] }}</p>
                                            <p class="mt-0.5 text-xs leading-relaxed text-slate-500">
                                                {{ $module['description'] }}</p>
                                        </div>
                                    </div>

                                    <button type="button" wire:click="toggleModule('{{ $module['key'] }}')"
                                        @disabled(!$this->canEdit) role="switch"
                                        aria-checked="{{ $module['enabled'] ? 'true' : 'false' }}"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border transition-all duration-200
                                    {{ $module['enabled']
                                        ? 'border-emerald-500/40 bg-emerald-500/90 shadow-sm shadow-emerald-500/25'
                                        : 'border-white/10 bg-slate-700/80' }}
                                    disabled:cursor-not-allowed disabled:opacity-40">
                                        <span
                                            class="pointer-events-none absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200 {{ $module['enabled'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</div>

{{-- Animation CSS légère (optionnelle) --}}
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

