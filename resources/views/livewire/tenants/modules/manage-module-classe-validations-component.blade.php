@php
    $activeClasse = $selectedClasseId ? $this->selectedClasse : null;
    $classes = $this->classes;
    $modules = $activeClasse ? $this->modulesForClasse : collect();

    $configuredCount = $modules->filter(fn($m) => $m->validationConfig)->count();
    $totalModules = $modules->count();
    $progress = $totalModules > 0 ? round(($configuredCount / $totalModules) * 100) : 0;

    $classesIndex = $classes
        ->map(
            fn($c) => [
                'id' => $c->id,
                't' => \Illuminate\Support\Str::ascii(\Illuminate\Support\Str::lower($c->name . ' ' . $c->code)),
            ],
        )
        ->values();

    $yearLabel = $this->activeSchoolYear?->name ?? ($this->activeSchoolYear?->label ?? '—');

    // Toutes les actions qui déclenchent l'indicateur global du tableau
$tableTargets =
    'searchModules,startEdit,startCreate,saveEdit,saveCreate,cancelEdit,cancelCreate,confirmDeleteModuleClasseValidation';
@endphp

<div class="space-y-6 pb-12">

    {{-- ═════════ Header + sélecteur de classe ═════════ --}}
    <div
        class="relative rounded-3xl border border-white/10 bg-gradient-to-br from-slate-900 via-[#0c1220] to-[#0a1020] p-6 shadow-2xl shadow-black/40 sm:p-8">
        <div class="pointer-events-none absolute inset-0 overflow-hidden rounded-3xl" aria-hidden="true">
            <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-emerald-500/10 blur-3xl"></div>
            <div class="absolute -left-12 bottom-0 h-48 w-48 rounded-full bg-teal-500/10 blur-3xl"></div>
        </div>

        <div class="relative space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-500/10 shadow-inner ring-1 ring-emerald-400/30">
                        <x-lucide-gauge class="h-6 w-6 text-emerald-400" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-white">Seuils de validation des modules</h1>
                        <p class="mt-1 max-w-2xl text-sm text-slate-400">
                            Définissez, par classe et par année scolaire, la moyenne minimale permettant de valider un
                            module.
                            Par défaut, le seuil est de <strong class="font-semibold text-slate-200">10</strong>.
                        </p>
                    </div>
                </div>

                <div
                    class="inline-flex shrink-0 items-center gap-2 self-start rounded-full border border-slate-700/70 bg-slate-800/50 px-3.5 py-1.5 text-xs text-slate-400">
                    <x-lucide-calendar class="h-3.5 w-3.5 text-emerald-400" />
                    Année scolaire
                    <span class="font-semibold text-slate-100">{{ $this->activeSchoolYear?->slug ?? ' - ' }}</span>
                </div>
            </div>

            {{-- Select de classe avec recherche --}}
            <div>
                <span id="classe-select-label"
                    class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-300">
                    <x-lucide-school class="h-4 w-4 text-emerald-400" />
                    Classe
                </span>

                <div x-data="{
                    open: false,
                    search: '',
                    index: @js($classesIndex),
                    norm(s) { return s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim() },
                    get visibleIds() {
                        const q = this.norm(this.search)
                        return this.index.filter(c => c.t.includes(q)).map(c => c.id)
                    },
                    isVisible(id) { return this.visibleIds.includes(id) },
                }" @click.away="open = false" @keydown.escape.window="open = false"
                    class="relative">

                    <button type="button" @click="open = !open" wire:loading.attr="disabled" wire:target="selectClasse"
                        aria-haspopup="listbox" :aria-expanded="open" aria-labelledby="classe-select-label"
                        class="flex w-full items-center justify-between gap-3 rounded-2xl border border-slate-700/80 bg-slate-950/80 px-4 py-3.5 text-left text-sm shadow-inner transition hover:border-emerald-500/50 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 disabled:cursor-wait disabled:opacity-70"
                        :class="open ? 'border-emerald-500/60 ring-2 ring-emerald-500/20' : ''">
                        <div class="flex min-w-0 flex-1 items-center gap-3">
                            <span
                                class="h-2 w-2 shrink-0 rounded-full {{ $activeClasse ? 'bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.9)]' : 'bg-slate-600' }}"></span>
                            <span class="truncate font-medium {{ $activeClasse ? 'text-white' : 'text-slate-400' }}">
                                {{ $activeClasse?->name ?? 'Choisir une classe...' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            @if ($activeClasse?->code)
                                <span
                                    class="rounded-md bg-slate-800 px-2 py-0.5 font-mono text-xs text-slate-400">{{ $activeClasse->code }}</span>
                            @endif
                            {{-- Chevron ⇄ spinner pendant le changement de classe --}}
                            <span wire:loading.remove wire:target="selectClasse">
                                <x-lucide-chevron-down class="h-4 w-4 text-slate-400 transition-transform duration-200"
                                    ::class="open ? 'rotate-180 text-emerald-400' : ''" />
                            </span>
                            <span wire:loading wire:target="selectClasse">
                                <x-lucide-loader-2 class="h-4 w-4 animate-spin text-emerald-400" />
                            </span>
                        </div>
                    </button>

                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                        x-effect="if (open) $nextTick(() => $refs.search.focus())"
                        class="absolute z-50 mt-2 w-full overflow-hidden rounded-2xl border border-slate-700/80 bg-slate-900/95 shadow-2xl shadow-black/50 backdrop-blur-xl">

                        <div class="border-b border-slate-800/80 bg-slate-950/50 p-3">
                            <div class="relative">
                                <x-lucide-search
                                    class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" />
                                <input x-ref="search" type="text" x-model="search"
                                    placeholder="Rechercher une classe..."
                                    class="w-full rounded-xl border border-slate-700/70 bg-slate-900/90 py-2 pl-9 pr-3 text-sm text-slate-200 placeholder-slate-500 focus:border-emerald-500/60 focus:outline-none focus:ring-1 focus:ring-emerald-500/30" />
                            </div>
                        </div>

                        <div role="listbox" class="max-h-72 space-y-0.5 overflow-y-auto p-1.5">
                            @forelse ($classes as $classe)
                                @php $isCurrent = $selectedClasseId === $classe->id; @endphp
                                <button type="button" role="option" wire:key="classe-opt-{{ $classe->id }}"
                                    aria-selected="{{ $isCurrent ? 'true' : 'false' }}"
                                    x-show="isVisible({{ $classe->id }})" @click="open = false; search = ''"
                                    wire:click="selectClasse({{ $classe->id }})"
                                    class="flex w-full items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 text-left transition
                                        {{ $isCurrent ? 'bg-emerald-600/20 text-emerald-100 ring-1 ring-emerald-500/30' : 'text-slate-200 hover:bg-slate-800/60 hover:text-white' }}">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium">{{ $classe->name }}</p>
                                        <p class="font-mono text-xs text-slate-500">{{ $classe->code ?? '—' }}</p>
                                    </div>
                                    @if ($isCurrent)
                                        <x-lucide-check class="h-4 w-4 shrink-0 text-emerald-400" />
                                    @endif
                                </button>
                            @empty
                                <div class="px-4 py-8 text-center text-sm text-slate-500">
                                    <x-lucide-school class="mx-auto mb-2 h-8 w-8 opacity-40" />
                                    Aucune classe trouvée pour l’année active.
                                </div>
                            @endforelse

                            @if ($classes->isNotEmpty())
                                <div x-show="visibleIds.length === 0" x-cloak
                                    class="px-4 py-8 text-center text-sm text-slate-500">
                                    Aucune classe ne correspond à votre recherche.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═════════ Skeleton affiché pendant le changement de classe ═════════ --}}
    <div wire:loading.block wire:target="selectClasse" class="animate-pulse space-y-6" aria-hidden="true">
        <div class="h-24 rounded-2xl border border-slate-700/60 bg-slate-900/50"></div>
        <div class="overflow-hidden rounded-2xl border border-slate-700/60 bg-slate-900/50">
            <div class="h-12 border-b border-slate-800/80 bg-slate-900/70"></div>
            @foreach (range(1, 5) as $i)
                <div class="flex items-center gap-4 border-b border-slate-800/70 px-5 py-4 last:border-0">
                    <div class="flex-1 space-y-2">
                        <div class="h-3.5 w-1/3 rounded bg-slate-800"></div>
                        <div class="h-2.5 w-16 rounded bg-slate-800/70"></div>
                    </div>
                    <div class="h-6 w-16 rounded-full bg-slate-800"></div>
                    <div class="h-6 w-10 rounded bg-slate-800"></div>
                    <div class="h-8 w-20 rounded-lg bg-slate-800"></div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ═════════ Contenu (masqué pendant le changement de classe) ═════════ --}}
    <div wire:loading.remove wire:target="selectClasse" class="space-y-6">
        @if (!$activeClasse)
            <div
                class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-700/80 bg-slate-900/30 px-6 py-20 text-center">
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-800/60 shadow-lg ring-1 ring-slate-700/50">
                    <x-lucide-mouse-pointer-click class="h-8 w-8 text-emerald-400" />
                </div>
                <p class="text-base font-semibold text-slate-300">Sélectionnez une classe</p>
                <p class="mt-1.5 max-w-sm text-sm text-slate-400">
                    Choisissez une classe dans le menu ci-dessus pour gérer les seuils de validation de ses modules.
                </p>
            </div>
        @else
            {{-- Bandeau de la classe --}}
            <div
                class="rounded-2xl border border-slate-700/60 bg-gradient-to-r from-slate-900/90 via-slate-900/60 to-slate-900/90 p-5 shadow-xl shadow-black/20 backdrop-blur-md">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex min-w-0 items-center gap-3.5">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15 ring-1 ring-emerald-400/30">
                            <x-lucide-users class="h-5 w-5 text-emerald-400" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="truncate text-lg font-bold tracking-tight text-white">{{ $activeClasse->name }}
                            </h2>
                            <p class="mt-0.5 font-mono text-xs text-emerald-300/80">{{ $activeClasse->code ?? '—' }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full lg:w-64">
                        <div class="mb-1.5 flex items-center justify-between text-xs">
                            <span class="text-slate-400">Seuils définis</span>
                            <span class="font-semibold text-slate-200">{{ $configuredCount }} /
                                {{ $totalModules }}</span>
                        </div>
                        <div class="h-1.5 overflow-hidden rounded-full bg-slate-800">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-500"
                                style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    {{-- Recherche modules : loupe ⇄ spinner --}}
                    <div class="relative w-full lg:w-64">
                        <span wire:loading.remove wire:target="searchModules"
                            class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2">
                            <x-lucide-search class="h-3.5 w-3.5 text-slate-400" />
                        </span>
                        <span wire:loading wire:target="searchModules"
                            class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2">
                            <x-lucide-loader-2 class="h-3.5 w-3.5 animate-spin text-emerald-400" />
                        </span>
                        <input type="search" wire:model.live.debounce.300ms="searchModules"
                            placeholder="Filtrer les modules..." aria-label="Filtrer les modules"
                            class="w-full rounded-xl border border-slate-700/70 bg-slate-950/80 py-2 pl-9 pr-3 text-sm text-slate-200 placeholder-slate-500 transition focus:border-emerald-500/50 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                    </div>
                </div>
            </div>

            {{-- Tableau des modules --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-700/60 bg-slate-900/50 shadow-xl shadow-black/20 backdrop-blur-md"
                aria-busy="false">

                {{-- Barre de chargement globale (recherche + toutes les actions) --}}
                <div wire:loading.delay.short wire:target="{{ $tableTargets }}"
                    class="pointer-events-none absolute inset-x-0 top-0 z-10 h-0.5 overflow-hidden"
                    aria-hidden="true">
                    <div
                        class="h-full w-full animate-pulse bg-gradient-to-r from-transparent via-emerald-400 to-transparent">
                    </div>
                </div>

                {{-- Le tableau s'estompe pendant une recherche --}}
                <div class="overflow-x-auto transition-opacity duration-150" wire:loading.class="opacity-50"
                    wire:target="searchModules">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="border-b border-slate-800/80 bg-slate-900/70 text-left text-xs uppercase tracking-wider text-slate-400">
                                <th class="px-5 py-3.5 font-semibold">Module</th>
                                <th class="w-32 px-4 py-3.5 font-semibold">Seuil</th>
                                <th class="w-28 px-4 py-3.5 font-semibold">Coef</th>
                                <th class="w-40 px-5 py-3.5 text-right font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/70">
                            @forelse ($modules as $module)
                                @php
                                    $config = $module->validationConfig;
                                    $isEditing = $config && $editingValidationId === $config->id;
                                    $isCreating = !$config && $creatingForModuleId === $module->id;
                                @endphp

                                <tr wire:key="module-val-{{ $module->id }}"
                                    class="transition-colors hover:bg-slate-800/40 {{ $isEditing || $isCreating ? 'bg-emerald-500/5' : '' }}">
                                    <td class="px-5 py-3.5">
                                        <div class="font-medium text-slate-100">{{ $module->name }}</div>
                                        <div class="font-mono text-xs text-slate-500">{{ $module->code ?? '—' }}</div>
                                    </td>

                                    @if ($isEditing)
                                        <td class="px-4 py-3.5">
                                            <input type="number" step="0.01" min="0" max="20"
                                                autofocus wire:model="editSeuil" wire:keydown.enter="saveEdit"
                                                wire:keydown.escape="cancelEdit" wire:loading.attr="disabled"
                                                wire:target="saveEdit" aria-label="Seuil"
                                                class="w-full rounded-lg border border-slate-600 bg-slate-950 px-2.5 py-1.5 text-sm text-slate-100 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 disabled:opacity-50" />
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <input type="number" min="0" step="1" placeholder="—"
                                                wire:model="editCoef" wire:keydown.enter="saveEdit"
                                                wire:keydown.escape="cancelEdit" wire:loading.attr="disabled"
                                                wire:target="saveEdit" aria-label="Coefficient"
                                                class="w-full rounded-lg border border-slate-600 bg-slate-950 px-2.5 py-1.5 text-sm text-slate-100 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 disabled:opacity-50" />
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <div class="inline-flex items-center gap-1.5">
                                                <button type="button" wire:click="saveEdit"
                                                    wire:loading.attr="disabled" wire:target="saveEdit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-500 active:scale-95 disabled:cursor-wait disabled:opacity-60">
                                                    <span wire:loading.remove wire:target="saveEdit"><x-lucide-check
                                                            class="h-3.5 w-3.5" /></span>
                                                    <span wire:loading wire:target="saveEdit"><x-lucide-loader-2
                                                            class="h-3.5 w-3.5 animate-spin" /></span>
                                                    <span wire:loading.remove wire:target="saveEdit">Sauver</span>
                                                    <span wire:loading wire:target="saveEdit">Enregistrement…</span>
                                                </button>
                                                <button type="button" wire:click="cancelEdit" title="Annuler"
                                                    aria-label="Annuler" wire:loading.attr="disabled"
                                                    wire:target="saveEdit"
                                                    class="inline-flex items-center rounded-lg border border-slate-600 p-1.5 text-slate-400 transition hover:bg-slate-800 hover:text-slate-200 disabled:opacity-40">
                                                    <x-lucide-x class="h-3.5 w-3.5" />
                                                </button>
                                            </div>
                                        </td>
                                    @elseif ($isCreating)
                                        <td class="px-4 py-3.5">
                                            <input type="number" step="0.01" min="0" max="20"
                                                autofocus wire:model="createSeuil" wire:keydown.enter="saveCreate"
                                                wire:keydown.escape="cancelCreate" wire:loading.attr="disabled"
                                                wire:target="saveCreate" aria-label="Seuil"
                                                class="w-full rounded-lg border border-slate-600 bg-slate-950 px-2.5 py-1.5 text-sm text-slate-100 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 disabled:opacity-50" />
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <input type="number" min="0" step="1" placeholder="—"
                                                wire:model="createCoef" wire:keydown.enter="saveCreate"
                                                wire:keydown.escape="cancelCreate" wire:loading.attr="disabled"
                                                wire:target="saveCreate" aria-label="Coefficient"
                                                class="w-full rounded-lg border border-slate-600 bg-slate-950 px-3 py-1.5 text-sm text-slate-100 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 disabled:opacity-50" />
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <div class="inline-flex items-center gap-1.5">
                                                <button type="button" wire:click="saveCreate"
                                                    wire:loading.attr="disabled" wire:target="saveCreate"
                                                    class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600/45 px-3 py-2.5 text-xs font-semibold text-white hover:text-green-950 shadow-lg shadow-emerald-600/20 transition hover:bg-green-400/80 active:scale-95 disabled:cursor-wait disabled:opacity-60">
                                                    <span wire:loading.remove wire:target="saveCreate"><x-lucide-check
                                                            class="h-3.5 w-3.5" /></span>
                                                    <span wire:loading wire:target="saveCreate"><x-lucide-loader-2
                                                            class="h-3.5 w-3.5 animate-spin" /></span>
                                                    <span wire:loading.remove
                                                        wire:target="saveCreate">Enregistré</span>
                                                    <span wire:loading wire:target="saveCreate">Création…</span>
                                                </button>
                                                <button type="button" wire:click="cancelCreate" title="Annuler"
                                                    aria-label="Annuler" wire:loading.attr="disabled"
                                                    wire:target="saveCreate"
                                                    class="inline-flex items-center rounded-xl bg-orange-400/25 px-3 py-2 text-orange-200 transition hover:bg-orange-400 hover:text-orange-950 disabled:opacity-40">
                                                    <span class="inline-flex items-center gap-x-2">
                                                        <span class="inline-flex items-center gap-x-2">
                                                            <span>Fermer</span>
                                                            <x-lucide-x class="h-3.5 w-3.5" />
                                                        </span>
                                                    </span>
                                                </button>
                                            </div>
                                        </td>
                                    @else
                                        <td class="px-4 py-3.5">
                                            @if ($config)
                                                @php $seuil = (float) $config->seuil; @endphp
                                                <span
                                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-sm font-semibold ring-1
                                                    {{ $seuil >= 10 ? 'bg-emerald-500/10 text-emerald-300 ring-emerald-500/30' : 'bg-amber-500/10 text-amber-300 ring-amber-500/30' }}">
                                                    {{ number_format($seuil, 2) }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-slate-800/80 px-2.5 py-0.5 text-xs text-slate-400 ring-1 ring-slate-700/50">
                                                    Non défini
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-300">
                                            {{ $config?->coef ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <div class="inline-flex items-center gap-1.5">
                                                @if ($config)
                                                    {{-- Modifier --}}
                                                    <button type="button"
                                                        wire:click="startEdit({{ $config->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="startEdit({{ $config->id }})" title="Modifier"
                                                        aria-label="Modifier le seuil"
                                                        class="inline-flex items-center rounded-lg border border-slate-700 p-2 text-slate-400 transition hover:border-amber-500/40 hover:bg-amber-500/10 hover:text-amber-300 disabled:cursor-wait disabled:opacity-60">
                                                        <span wire:loading.remove
                                                            wire:target="startEdit({{ $config->id }})">
                                                            <x-lucide-pencil class="h-3.5 w-3.5" />
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="startEdit({{ $config->id }})">
                                                            <x-lucide-loader-2
                                                                class="h-3.5 w-3.5 animate-spin text-amber-300" />
                                                        </span>
                                                    </button>
                                                    {{-- Supprimer --}}
                                                    <button type="button"
                                                        wire:click="confirmDeleteModuleClasseValidation({{ $config->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="confirmDeleteModuleClasseValidation({{ $config->id }})"
                                                        title="Supprimer" aria-label="Supprimer le seuil"
                                                        class="inline-flex items-center rounded-lg border border-slate-700 p-2 text-slate-400 transition hover:border-rose-500/40 hover:bg-rose-500/10 hover:text-rose-300 disabled:cursor-wait disabled:opacity-60">
                                                        <span wire:loading.remove
                                                            wire:target="confirmDeleteModuleClasseValidation({{ $config->id }})">
                                                            <x-lucide-trash-2 class="h-3.5 w-3.5" />
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="confirmDeleteModuleClasseValidation({{ $config->id }})">
                                                            <x-lucide-loader-2
                                                                class="h-3.5 w-3.5 animate-spin text-rose-300" />
                                                        </span>
                                                    </button>
                                                @else
                                                    {{-- Définir --}}
                                                    <button type="button"
                                                        wire:click="startCreate({{ $module->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="startCreate({{ $module->id }})"
                                                        class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-600/40 bg-emerald-600/10 px-3 py-1.5 text-xs font-semibold text-emerald-300 hover:text-green-950 transition hover:bg-emerald-400/80 disabled:cursor-wait disabled:opacity-60 group">
                                                        <span wire:loading.remove
                                                            wire:target="startCreate({{ $module->id }})">
                                                            <x-lucide-plus class="h-3.5 w-3.5" />
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="startCreate({{ $module->id }})">
                                                            <x-lucide-loader-2
                                                                class="h-3.5 w-3.5 animate-spin hover:rotate-90" />
                                                        </span>
                                                        Définir
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-14 text-center text-sm text-slate-500">
                                        <x-lucide-package-open class="mx-auto mb-2 h-8 w-8 opacity-40" />
                                        {{ $searchModules ? 'Aucun module ne correspond à votre filtre.' : 'Aucun module assigné à cette classe pour l’année active.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

