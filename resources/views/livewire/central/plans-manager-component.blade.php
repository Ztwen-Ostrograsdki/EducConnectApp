<div class="min-h-screen bg-[#070b14] text-slate-100 space-y-6 p-3">
    <div class="mx-auto space-y-6 relative">

        {{-- Loading overlay --}}
        <div wire:loading.flex wire:target="toggleActive, deletePlan, save"
            class="fixed inset-0 z-50 items-center justify-center bg-[#070b14]/70 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-3">
                <div class="w-12 h-12 rounded-full border-2 border-indigo-500/30 border-t-indigo-400 animate-spin"></div>
                <span class="text-xs font-mono text-slate-500">Chargement…</span>
            </div>
        </div>

        {{-- ════════════════ HEADER ════════════════ --}}
        <header class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-6 shadow-xl shadow-black/20">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-semibold uppercase tracking-wider mb-2">
                        <x-lucide-layers class="w-3 h-3" />
                        Central
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                        Gestion des plans
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Offres d’abonnement proposées aux écoles
                    </p>
                </div>

                @if (!$showForm)
                    <button wire:click="openCreateForm" type="button"
                        class="h-11 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition-all active:scale-[0.97] inline-flex items-center gap-2 shrink-0">
                        <x-lucide-plus class="w-4 h-4" />
                        Nouveau plan
                    </button>
                @endif
            </div>
        </header>

        {{-- ════════════════ FORMULAIRE ════════════════ --}}
        @if ($showForm)
            <section x-data x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
                class="rounded-2xl bg-[#0f1523] border border-indigo-500/20 overflow-hidden shadow-xl shadow-indigo-950/20">

                <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-xl bg-indigo-500/15 border border-indigo-500/25 flex items-center justify-center">
                            <x-lucide-package-plus class="w-4 h-4 text-indigo-400" />
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
                        class="w-8 h-8 rounded-lg hover:bg-white/5 text-slate-500 hover:text-slate-300 transition-all flex items-center justify-center">
                        <x-lucide-x class="w-4 h-4" />
                    </button>
                </div>

                <form wire:submit="save" class="p-5 sm:p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Nom du plan <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" wire:model.live.debounce.400ms="name"
                                placeholder="Ex: Pack Pro Annuel"
                                class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/30 transition-all
                                          @error('name') border-rose-500/50 @enderror">
                            @error('name')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Slug
                            </label>
                            <input type="text" wire:model="slug" placeholder="pack-pro-annuel"
                                class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm font-mono text-slate-300 placeholder:text-slate-600 focus:outline-none focus:border-indigo-500/50 transition-all
                                          @error('slug') border-rose-500/50 @enderror">
                            @error('slug')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Description
                            </label>
                            <textarea wire:model="description" rows="2" placeholder="Description courte du plan"
                                class="w-full rounded-xl bg-[#070b14] border border-white/10 px-3.5 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-indigo-500/50 transition-all resize-none"></textarea>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Prix (FCFA) <span class="text-rose-400">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" wire:model="price" min="0" placeholder="50000"
                                    class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 pr-14 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-indigo-500/50 transition-all
                                              @error('price') border-rose-500/50 @enderror">
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-slate-600 font-medium">FCFA</span>
                            </div>
                            @error('price')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Durée (jours) <span class="text-rose-400">*</span>
                            </label>
                            <input type="number" wire:model="days_count" min="1" placeholder="365"
                                class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-indigo-500/50 transition-all
                                          @error('days_count') border-rose-500/50 @enderror">
                            @error('days_count')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Pack de modules
                            </label>
                            <select wire:model="pack"
                                class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-indigo-500/50 transition-all">
                                <option value="starter">Starter</option>
                                <option value="pro">Pro</option>
                                <option value="premium">Premium</option>
                                <option value="custom">Sur mesure</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <label
                                class="flex items-center justify-between gap-4 w-full h-11 rounded-xl bg-[#070b14] border border-white/[0.05] px-3.5 cursor-pointer">
                                <span class="text-sm text-slate-300">Plan actif</span>
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <span
                                    class="relative h-6 w-11 rounded-full bg-slate-700 peer-checked:bg-indigo-500 transition-colors shrink-0 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:after:translate-x-5"></span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-white/[0.05]">
                        <button type="button" wire:click="cancelForm"
                            class="h-10 px-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-400 transition-all">
                            Annuler
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="h-10 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white transition-all disabled:opacity-50 inline-flex items-center gap-2 shadow-lg shadow-indigo-900/30">
                            <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                                <x-lucide-check class="w-4 h-4" />
                                {{ $editingPlanId ? 'Enregistrer' : 'Créer le plan' }}
                            </span>
                            <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
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
                    class="group rounded-2xl bg-[#0f1523] border border-white/[0.06] hover:border-indigo-500/25 transition-all duration-200 overflow-hidden shadow-lg shadow-black/10">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-5">

                        {{-- Icône pack --}}
                        <div
                            class="w-12 h-12 rounded-xl {{ $packColor['bg'] }} border {{ $packColor['border'] }} flex items-center justify-center shrink-0">
                            <x-lucide-box class="w-5 h-5 {{ $packColor['text'] }}" />
                        </div>

                        {{-- Infos --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-semibold text-white">{{ $plan->name }}</h3>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $packColor['bg'] }} border {{ $packColor['border'] }} {{ $packColor['text'] }} text-[10px] font-semibold">
                                    {{ $plan->packLabel() }}
                                </span>
                            </div>
                            <p class="mt-0.5 text-[11px] font-mono text-slate-600">{{ $plan->slug }}</p>
                            @if ($plan->description)
                                <p class="mt-1.5 text-xs text-slate-500 line-clamp-1">{{ $plan->description }}</p>
                            @endif
                        </div>

                        {{-- Prix + durée --}}
                        <div class="flex items-center gap-4 sm:gap-6 shrink-0">
                            <div class="text-right">
                                <p class="text-sm font-bold text-white tabular-nums">
                                    {{ number_format($plan->price, 0, ',', ' ') }}
                                    <span class="text-[10px] font-medium text-slate-500">FCFA</span>
                                </p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $plan->days_count }} jours</p>
                            </div>

                            {{-- Statut toggle --}}
                            <button wire:click="toggleActive({{ $plan->id }})" type="button"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium transition-all
                                           {{ $plan->is_active
                                               ? 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 hover:bg-emerald-500/20'
                                               : 'bg-white/5 text-slate-500 border border-white/10 hover:bg-white/10' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ $plan->is_active ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                                {{ $plan->is_active ? 'Actif' : 'Inactif' }}
                            </button>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 shrink-0 sm:pl-2 sm:border-l sm:border-white/5">
                            <button wire:click="editPlan({{ $plan->id }})" type="button" title="Modifier"
                                class="w-9 h-9 rounded-lg bg-white/5 hover:bg-indigo-500/15 border border-white/10 hover:border-indigo-500/30 text-slate-500 hover:text-indigo-300 transition-all flex items-center justify-center">
                                <x-lucide-pencil class="w-4 h-4" />
                            </button>
                            <button wire:click="confirmDelete({{ $plan->id }})" type="button" title="Supprimer"
                                class="w-9 h-9 rounded-lg bg-white/5 hover:bg-rose-500/15 border border-white/10 hover:border-rose-500/30 text-slate-500 hover:text-rose-300 transition-all flex items-center justify-center">
                                <x-lucide-trash-2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] py-20 text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-4">
                        <x-lucide-package-open class="w-6 h-6 text-indigo-400/60" />
                    </div>
                    <p class="text-sm font-medium text-slate-400">Aucun plan créé</p>
                    <p class="mt-1 text-xs text-slate-600">Créez votre première offre d’abonnement</p>
                    <button wire:click="openCreateForm" type="button"
                        class="mt-5 h-10 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white transition-all inline-flex items-center gap-2">
                        <x-lucide-plus class="w-4 h-4" />
                        Nouveau plan
                    </button>
                </div>
            @endforelse
        </section>

    </div>
</div>

