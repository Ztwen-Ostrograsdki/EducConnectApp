<div class="min-h-screen bg-[#070b14] text-slate-100 space-y-6 p-3 sm:p-5">
    <div class="mx-auto max-w-6xl space-y-6 relative">

        {{-- Loading overlay --}}
        <div wire:loading.flex wire:target="toggleActive, deletePlan, save"
            class="fixed inset-0 z-50 items-center justify-center bg-[#070b14]/70 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-3">
                <div class="h-12 w-12 rounded-full border-2 border-indigo-500/30 border-t-indigo-400 animate-spin"></div>
                <span class="text-xs font-mono text-slate-500">Chargement…</span>
            </div>
        </div>

        {{-- ════════════════ HEADER ════════════════ --}}
        <header class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-xl shadow-black/20">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div
                        class="mb-2 inline-flex items-center gap-1.5 rounded-full border border-indigo-500/20 bg-indigo-500/10 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-indigo-300">
                        <x-lucide-layers class="h-3 w-3" />
                        Central
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-white sm:text-2xl">
                        Gestion des plans
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Offres d’abonnement proposées aux écoles
                    </p>
                </div>

                @if (!$showForm)
                    <button wire:click="openCreateForm" type="button"
                        class="inline-flex h-11 shrink-0 items-center gap-2 rounded-xl bg-indigo-600 px-5 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition-all hover:bg-indigo-500 active:scale-[0.97]">
                        <x-lucide-plus class="h-4 w-4" />
                        Nouveau plan
                    </button>
                @endif
            </div>
        </header>

        {{-- ════════════════ FORMULAIRE ════════════════ --}}
        @if ($showForm)
            <section x-data x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
                class="overflow-hidden rounded-2xl border border-indigo-500/20 bg-[#0f1523] shadow-xl shadow-indigo-950/20">
                <div class="flex items-center justify-between gap-3 border-b border-white/[0.05] px-5 py-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-indigo-500/25 bg-indigo-500/15">
                            <x-lucide-package-plus class="h-4 w-4 text-indigo-400" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-white">
                                {{ $editingPlanId ? 'Modifier le plan' : 'Créer un plan' }}
                            </h2>
                            <p class="text-[11px] text-slate-500">
                                {{ $editingPlanId ? 'Mettez à jour les détails de l’offre' : 'Définissez une nouvelle offre d’abonnement' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="cancelForm"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition-all hover:bg-white/5 hover:text-slate-300">
                        <x-lucide-x class="h-4 w-4" />
                    </button>
                </div>

                <form wire:submit="save" class="space-y-5 p-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label
                                class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Nom du plan <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" wire:model.live.debounce.400ms="name"
                                placeholder="Ex: Pack Pro Annuel"
                                class="h-11 w-full rounded-xl border border-white/10 bg-[#070b14] px-3.5 text-sm text-slate-200 placeholder:text-slate-600 transition-all focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 @error('name') border-rose-500/50 @enderror">
                            @error('name')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Slug
                            </label>
                            <input type="text" wire:model="slug" placeholder="pack-pro-annuel"
                                class="h-11 w-full rounded-xl border border-white/10 bg-[#070b14] px-3.5 font-mono text-sm text-slate-300 placeholder:text-slate-600 transition-all focus:border-indigo-500/50 focus:outline-none @error('slug') border-rose-500/50 @enderror">
                            @error('slug')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label
                                class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Description
                            </label>
                            <textarea wire:model="description" rows="2" placeholder="Description courte du plan"
                                class="w-full resize-none rounded-xl border border-white/10 bg-[#070b14] px-3.5 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 transition-all focus:border-indigo-500/50 focus:outline-none"></textarea>
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Prix (FCFA) <span class="text-rose-400">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" wire:model="price" min="0" placeholder="50000"
                                    class="h-11 w-full rounded-xl border border-white/10 bg-[#070b14] px-3.5 pr-14 text-sm text-slate-200 placeholder:text-slate-600 transition-all focus:border-indigo-500/50 focus:outline-none @error('price') border-rose-500/50 @enderror">
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] font-medium text-slate-600">FCFA</span>
                            </div>
                            @error('price')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Durée (jours) <span class="text-rose-400">*</span>
                            </label>
                            <input type="number" wire:model="days_count" min="1" placeholder="365"
                                class="h-11 w-full rounded-xl border border-white/10 bg-[#070b14] px-3.5 text-sm text-slate-200 placeholder:text-slate-600 transition-all focus:border-indigo-500/50 focus:outline-none @error('days_count') border-rose-500/50 @enderror">
                            @error('days_count')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Pack de modules
                            </label>
                            <select wire:model="pack"
                                class="h-11 w-full rounded-xl border border-white/10 bg-[#070b14] px-3.5 text-sm text-slate-200 transition-all focus:border-indigo-500/50 focus:outline-none">
                                <option value="starter">Starter</option>
                                <option value="pro">Pro</option>
                                <option value="premium">Premium</option>
                                <option value="custom">Sur mesure</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <label
                                class="flex h-11 w-full cursor-pointer items-center justify-between gap-4 rounded-xl border border-white/[0.05] bg-[#070b14] px-3.5">
                                <span class="text-sm text-slate-300">Plan actif</span>
                                <input type="checkbox" wire:model="is_active" class="peer sr-only">
                                <span
                                    class="relative h-6 w-11 shrink-0 rounded-full bg-slate-700 transition-colors peer-checked:bg-indigo-500 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform after:content-[''] peer-checked:after:translate-x-5"></span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 border-t border-white/[0.05] pt-4">
                        <button type="button" wire:click="cancelForm"
                            class="h-10 rounded-xl border border-white/10 bg-white/5 px-4 text-sm text-slate-400 transition-all hover:bg-white/10">
                            Annuler
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-indigo-600 px-5 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition-all hover:bg-indigo-500 disabled:opacity-50">
                            <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                                <x-lucide-check class="h-4 w-4" />
                                {{ $editingPlanId ? 'Enregistrer' : 'Créer le plan' }}
                            </span>
                            <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                                <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                                Enregistrement…
                            </span>
                        </button>
                    </div>
                </form>
            </section>
        @endif

        {{-- ════════════════ LISTE DES PLANS ════════════════ --}}
        <section class="space-y-3">
            @forelse ($this->plans as $plan)
                @php
                    $packColor = match ($plan->pack ?? '') {
                        'starter' => [
                            'bg' => 'bg-slate-500/15',
                            'text' => 'text-slate-300',
                            'border' => 'border-slate-500/20',
                        ],
                        'pro' => [
                            'bg' => 'bg-indigo-500/15',
                            'text' => 'text-indigo-300',
                            'border' => 'border-indigo-500/20',
                        ],
                        'premium' => [
                            'bg' => 'bg-amber-500/15',
                            'text' => 'text-amber-300',
                            'border' => 'border-amber-500/20',
                        ],
                        'custom' => [
                            'bg' => 'bg-violet-500/15',
                            'text' => 'text-violet-300',
                            'border' => 'border-violet-500/20',
                        ],
                        default => [
                            'bg' => 'bg-indigo-500/15',
                            'text' => 'text-indigo-300',
                            'border' => 'border-indigo-500/20',
                        ],
                    };
                @endphp

                <article wire:key="plan-{{ $plan->id }}"
                    class="group overflow-hidden rounded-2xl border border-white/[0.06] bg-[#0f1523] shadow-lg shadow-black/10 transition-all duration-200 hover:border-indigo-500/25">
                    <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:gap-5 sm:p-5">

                        {{-- Icône --}}
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border {{ $packColor['border'] }} {{ $packColor['bg'] }}">
                            <x-lucide-box class="h-5 w-5 {{ $packColor['text'] }}" />
                        </div>

                        {{-- Infos principales --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-semibold text-white">{{ $plan->name }}</h3>
                                <span
                                    class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $packColor['bg'] }} {{ $packColor['border'] }} {{ $packColor['text'] }}">
                                    {{ $plan->packLabel() }}
                                </span>
                            </div>

                            <p class="mt-0.5 font-mono text-[11px] text-slate-600">{{ $plan->slug }}</p>

                            @if ($plan->description)
                                <p class="mt-1.5 line-clamp-1 text-xs text-slate-500">{{ $plan->description }}</p>
                            @endif

                            <p class="mt-2 text-[11px] text-slate-500">
                                <span class="font-semibold tabular-nums text-amber-400">
                                    {{ __zero(count($plan->subscriptions)) }}
                                </span>
                                abonnement{{ count($plan->subscriptions) > 1 ? 's' : '' }}
                                lié{{ count($plan->subscriptions) > 1 ? 's' : '' }}
                            </p>
                        </div>

                        {{-- Prix + durée --}}
                        <div class="shrink-0 text-left sm:text-right">
                            <p class="text-sm font-bold tabular-nums text-white">
                                {{ number_format($plan->price, 0, ',', ' ') }}
                                <span class="text-[10px] font-medium text-slate-500">FCFA</span>
                            </p>
                            <p class="mt-0.5 text-[11px] text-slate-500">{{ $plan->days_count }} jours</p>
                        </div>

                        {{-- Statut --}}
                        <button wire:click="toggleActive({{ $plan->id }})" type="button"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-medium transition-all
                                {{ $plan->is_active
                                    ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-300 hover:bg-emerald-500/20'
                                    : 'border-white/10 bg-white/5 text-slate-500 hover:bg-white/10' }}">
                            <span
                                class="h-1.5 w-1.5 rounded-full {{ $plan->is_active ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                            {{ $plan->is_active ? 'Actif' : 'Inactif' }}
                        </button>

                        {{-- Actions --}}
                        <div class="flex shrink-0 items-center gap-1 sm:border-l sm:border-white/5 sm:pl-3">
                            @if ($plan->isEditable())
                                <button wire:click="editPlan({{ $plan->id }})" type="button" title="Modifier"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-slate-500 transition-all hover:border-indigo-500/30 hover:bg-indigo-500/15 hover:text-indigo-300">
                                    <x-lucide-pencil class="h-4 w-4" />
                                </button>
                            @endif

                            @if ($plan->isDeletable())
                                <button wire:click="confirmDelete({{ $plan->id }})" type="button"
                                    title="Supprimer"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-slate-500 transition-all hover:border-rose-500/30 hover:bg-rose-500/15 hover:text-rose-300">
                                    <x-lucide-trash-2 class="h-4 w-4" />
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] py-20 text-center">
                    <div
                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-indigo-500/20 bg-indigo-500/10">
                        <x-lucide-package-open class="h-6 w-6 text-indigo-400/60" />
                    </div>
                    <p class="text-sm font-medium text-slate-400">Aucun plan créé</p>
                    <p class="mt-1 text-xs text-slate-600">Créez votre première offre d’abonnement</p>
                    <button wire:click="openCreateForm" type="button"
                        class="mt-5 inline-flex h-10 items-center gap-2 rounded-xl bg-indigo-600 px-5 text-sm font-semibold text-white transition-all hover:bg-indigo-500">
                        <x-lucide-plus class="h-4 w-4" />
                        Nouveau plan
                    </button>
                </div>
            @endforelse
        </section>

    </div>
</div>
