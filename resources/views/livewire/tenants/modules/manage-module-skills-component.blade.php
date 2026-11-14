@php
    $activeModule = $selectedModuleId ? $this->selectedModule : null;

    // Index de recherche (sans accents, minuscules) transmis proprement à Alpine
    $modulesIndex = $this->modules
        ->map(
            fn($m) => [
                'id' => $m->id,
                't' => \Illuminate\Support\Str::ascii(\Illuminate\Support\Str::lower($m->name . ' ' . $m->code)),
            ],
        )
        ->values();

    $linkedTotal = $activeModule?->module_skills_count ?? 0;
@endphp

<div class="min-h-screen space-y-6 pb-12 font-trebuchet">

    {{-- Header --}}
    <div
        class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-900 via-[#0c1220] to-[#0a1020] p-6 sm:p-8 shadow-2xl shadow-black/40">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-violet-500/10 blur-3xl"></div>
            <div class="absolute -left-12 bottom-0 h-48 w-48 rounded-full bg-indigo-500/10 blur-3xl"></div>
        </div>

        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-violet-500/10 ring-1 ring-violet-400/30 shadow-inner">
                    <x-lucide-layers class="h-6 w-6 text-violet-400" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-white">Modules & Compétences</h1>
                    <p class="mt-1 flex flex-wrap items-center gap-2 text-sm text-slate-400">
                        <span>Liez ou déliez des compétences aux modules pédagogiques.</span>
                        <code
                            class="rounded-lg bg-slate-800/90 px-2 py-0.5 text-xs text-indigo-300 ring-1 ring-slate-700/50">Les
                            matières considérées comme des modules</code>
                    </p>
                </div>
            </div>

            <a href="{{ route('tenant.skills.manage') }}" wire:navigate
                class="inline-flex items-center gap-2.5 self-start rounded-xl border border-slate-700/80 bg-slate-800/40 px-4 py-2.5 text-sm font-medium text-slate-200 transition-all duration-200 hover:border-violet-500/50 hover:bg-violet-500/10 hover:text-violet-300 hover:shadow-lg hover:shadow-violet-500/10">
                <x-lucide-brain-circuit class="h-4 w-4 text-violet-400" />
                Gérer les compétences
            </a>
        </div>
    </div>

    {{-- Main Layout --}}
    <div class="space-y-4 lg:sticky lg:top-6 lg:col-span-4">
        <div
            class="space-y-4 rounded-2xl border border-slate-700/60 bg-slate-900/50 p-5 shadow-xl shadow-black/20 backdrop-blur-md">
            <span id="module-select-label"
                class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-300">
                <x-lucide-book-open class="h-4 w-4 text-violet-400" />
                Sélectionner un module
            </span>

            <div x-data="{
                open: false,
                search: '',
                index: @js($modulesIndex),
                norm(s) { return s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim() },
                get visibleIds() {
                    const q = this.norm(this.search)
                    return this.index.filter(m => m.t.includes(q)).map(m => m.id)
                },
                isVisible(id) { return this.visibleIds.includes(id) },
            }" @click.away="open = false" @keydown.escape.window="open = false"
                class="relative">

                {{-- Bouton déclencheur (libellé rendu côté serveur) --}}
                <button type="button" @click="open = !open" aria-haspopup="listbox" :aria-expanded="open"
                    aria-labelledby="module-select-label"
                    class="flex w-full items-center justify-between gap-3 rounded-xl border border-slate-700/80 bg-slate-950/80 px-4 py-3 text-left text-sm text-slate-200 shadow-inner transition hover:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <div class="flex min-w-0 flex-1 items-center gap-2.5">
                        <span
                            class="h-2 w-2 shrink-0 rounded-full {{ $activeModule ? 'bg-violet-400 shadow-[0_0_10px_rgba(167,139,250,0.9)]' : 'bg-slate-600' }}"></span>
                        <span
                            class="truncate font-medium text-white">{{ $activeModule?->name ?? 'Choisir un module...' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($activeModule?->code)
                            <span
                                class="rounded bg-slate-800 px-1.5 py-0.5 font-mono text-xs text-slate-400">{{ $activeModule->code }}</span>
                        @endif
                        <x-lucide-chevron-down class="h-4 w-4 text-slate-400 transition-transform duration-200"
                            ::class="open ? 'rotate-180 text-violet-400' : ''" />
                    </div>
                </button>

                {{-- Panneau déroulant --}}
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                    x-effect="if (open) $nextTick(() => $refs.search.focus())"
                    class="absolute z-50 mt-2 w-full overflow-hidden rounded-2xl border border-slate-700/80 bg-slate-900/95 shadow-2xl backdrop-blur-xl">

                    <div class="border-b border-slate-800/80 bg-slate-950/50 p-3">
                        <div class="relative">
                            <x-lucide-search
                                class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" />
                            <input x-ref="search" type="text" x-model="search" placeholder="Rechercher un module..."
                                class="w-full rounded-xl border border-slate-700/70 bg-slate-900/90 py-2 pl-9 pr-3 text-xs text-slate-200 placeholder-slate-500 focus:border-violet-500/60 focus:outline-none focus:ring-1 focus:ring-violet-500/30" />
                        </div>
                    </div>

                    <div role="listbox" class="max-h-64 divide-y divide-slate-800/50 overflow-y-auto p-1.5">
                        @forelse ($this->modules as $module)
                            <button type="button" role="option" wire:key="module-opt-{{ $module->id }}"
                                aria-selected="{{ $selectedModuleId == $module->id ? 'true' : 'false' }}"
                                x-show="isVisible({{ $module->id }})" @click="open = false; search = ''"
                                wire:click="$set('selectedModuleId', {{ $module->id }})"
                                class="group flex w-full items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 text-left transition {{ $selectedModuleId == $module->id ? 'bg-violet-600/20 text-violet-200' : 'text-slate-200 hover:bg-slate-800/60 hover:text-white' }}">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium">{{ $module->name }}</p>
                                    <p class="font-mono text-xs text-slate-400">{{ $module->code ?? '—' }}</p>
                                </div>
                                <span
                                    class="inline-flex shrink-0 items-center gap-1 rounded-full bg-slate-800/90 px-2 py-0.5 text-xs font-medium text-slate-300 ring-1 ring-slate-700/60">
                                    <x-lucide-link class="h-3 w-3 text-violet-400" />
                                    {{ $module->module_skills_count }}
                                </span>
                            </button>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-500">Aucun module disponible</div>
                        @endforelse

                        @if ($this->modules->isNotEmpty())
                            <div x-show="visibleIds.length === 0" x-cloak
                                class="py-8 text-center text-xs text-slate-500">
                                Aucun module ne correspond à votre recherche
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gap-6 ">

        <div class="space-y-6">
            @if (!$activeModule)
                <div
                    class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-700/80 bg-slate-900/30 px-6 py-24 text-center">
                    <div
                        class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-800/60 ring-1 ring-slate-700/50 shadow-lg">
                        <x-lucide-mouse-pointer-click class="h-8 w-8 text-violet-400" />
                    </div>
                    <p class="text-base font-semibold text-slate-300">Sélectionnez un module</p>
                    <p class="mt-1.5 max-w-sm text-sm text-slate-400">Choisissez un module dans le menu déroulant pour
                        afficher et configurer ses compétences liées.</p>
                </div>
            @else
                {{-- Bannière du module --}}
                <div
                    class="rounded-2xl border border-sky-800 bg-slate-950 p-5 shadow-xl shadow-black/20 backdrop-blur-md">
                    <p
                        class="p-1 text-sm rounded-2xl bg-green-600/30 text-green-400 animate-pulse inline-flex items-center text-center px-6">
                        Module
                        selectionné</p>
                    <div class="flex items-center justify-between gap-4 border-t border-t-slate-500 mt-2">
                        <div class="flex min-w-0 items-center gap-3.5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-500/15 ring-1 ring-violet-400/30">
                                <x-lucide-book-open class="h-5 w-5 text-violet-400" />
                            </div>
                            <div class="min-w-0">

                                <h2 class="truncate text-lg font-bold tracking-tight text-amber-600 font-trebuchet">
                                    {{ $activeModule->name }}</h2>
                                <p
                                    class="mt-0.5 font-mono text-xs inline-flex text-center items-center bg-amber-500/45 text-amber-300 p-1 px-3 rounded-2xl">
                                    {{ $activeModule->code ?? '—' }}
                                </p>
                            </div>
                        </div>
                        <span
                            class="inline-flex shrink-0 items-center gap-2 rounded-full bg-violet-500/10 px-3.5 py-1.5 text-xs font-semibold text-violet-300 ring-1 ring-violet-500/30 shadow-sm">
                            <x-lucide-link class="h-3.5 w-3.5 text-violet-400" />
                            {{ trans_choice('{0} Aucune compétence liée|{1} :count compétence liée|[2,*] :count compétences liées', $linkedTotal) }}
                        </span>
                    </div>
                </div>

                {{-- Compétences liées --}}
                <div
                    class="overflow-hidden rounded-2xl border border-green-600 bg-slate-950 shadow-xl shadow-black/20 backdrop-blur-md">
                    <div
                        class="flex flex-col gap-3 border-b border-slate-800/80 bg-slate-950 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="flex items-center gap-2 text-sm font-bold text-white">
                            <x-lucide-check-circle-2 class="h-4 w-4 text-emerald-400" />
                            <div class="inline-flex items-center gap-x-1">
                                Compétences liées au module
                                <h2 class="truncate font-bold tracking-tight text-green-600 font-trebuchet">
                                    {{ $activeModule->name }}</h2>
                                <p
                                    class="mt-0.5 font-mono text-xs inline-flex text-center items-center bg-amber-500/45 text-amber-300 p-1 px-3 rounded-2xl">
                                    {{ $activeModule->code ?? '—' }}
                                </p>
                            </div>
                        </h3>
                        <div class="relative w-full sm:w-60">
                            <x-lucide-search
                                class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" />
                            <input type="search" wire:model.live.debounce.300ms="searchSkills"
                                placeholder="Filtrer les liées..." aria-label="Filtrer les compétences liées"
                                class="w-full rounded-xl border border-slate-700/70 bg-slate-950/80 py-2 pl-9 pr-3 text-xs text-slate-200 placeholder-slate-500 transition focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20" />
                        </div>
                    </div>

                    <div class="max-h-[24rem] divide-y divide-slate-800/70 overflow-y-auto">
                        @forelse ($this->linkedSkills as $ms)
                            <div wire:key="ms-{{ $ms->id }}"
                                class="group flex items-start justify-between gap-4 px-6 py-4 transition-colors duration-150 hover:bg-slate-800/40">
                                <div class="min-w-0 flex-1 space-y-2">
                                    <p
                                        class="text-sm font-medium leading-relaxed text-slate-200 group-hover:text-white">
                                        {{ $ms->skill?->content }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if ($code = $ms->code ?? $ms->skill?->code)
                                            <span
                                                class="inline-flex items-center rounded-lg bg-slate-800 px-2.5 py-0.5 font-mono text-[11px] font-medium text-slate-300 ring-1 ring-slate-700/60">{{ $code }}</span>
                                        @endif
                                        @if (!is_null($ms->coef))
                                            <span
                                                class="inline-flex items-center rounded-lg bg-indigo-500/10 px-2.5 py-0.5 text-[11px] font-medium text-indigo-300 ring-1 ring-indigo-500/20">Coef
                                                {{ $ms->coef }}</span>
                                        @endif
                                        @if (!is_null($ms->weight))
                                            <span
                                                class="inline-flex items-center rounded-lg bg-slate-800 px-2.5 py-0.5 text-[11px] font-medium text-slate-300 ring-1 ring-slate-700/60">Poids
                                                {{ $ms->weight }}</span>
                                        @endif
                                        @if ($ms->is_active)
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-400 ring-1 ring-emerald-500/30">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-0.5 text-[11px] font-medium text-slate-400 ring-1 ring-slate-700/50">Inactive</span>
                                        @endif
                                    </div>
                                </div>

                                <div
                                    class="flex shrink-0 items-center gap-1.5 opacity-85 transition group-hover:opacity-100">
                                    <button type="button" wire:click="toggleModuleSkillActive({{ $ms->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="toggleModuleSkillActive({{ $ms->id }})"
                                        title="Activer / Désactiver" aria-label="Activer ou désactiver la compétence"
                                        class="rounded-xl p-2.5 text-slate-400 ring-1 ring-transparent transition hover:bg-indigo-500/15 hover:text-indigo-300 hover:ring-indigo-500/30">
                                        <span wire:loading.remove
                                            wire:target="toggleModuleSkillActive({{ $ms->id }})">
                                            <x-lucide-power class="h-4 w-4" />
                                        </span>
                                        <span wire:loading wire:target="toggleModuleSkillActive({{ $ms->id }})">
                                            <x-lucide-loader-2 class="h-4 w-4 animate-spin text-indigo-400" />
                                        </span>
                                    </button>
                                    <button type="button" wire:click="confirmUnlinkModuleSkill({{ $ms->id }})"
                                        title="Délier la compétence" aria-label="Délier la compétence"
                                        class="rounded-xl p-2.5 text-slate-400 ring-1 ring-transparent transition hover:bg-rose-500/15 hover:text-rose-300 hover:ring-rose-500/30">
                                        <x-lucide-unlink class="h-4 w-4" />
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                                <div
                                    class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-800/60 ring-1 ring-slate-700/50">
                                    <x-lucide-unlink class="h-6 w-6 text-slate-400" />
                                </div>
                                <p class="text-sm font-medium text-slate-400">
                                    {{ $searchSkills ? 'Aucune compétence ne correspond à votre filtre.' : 'Aucune compétence liée à ce module pour le moment.' }}
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Formulaire de liaison --}}
                <div
                    class="space-y-5 rounded-2xl border border-indigo-700/60 bg-indigo-950/20 p-6 shadow-xl shadow-black/20 backdrop-blur-md">
                    <div class="flex items-center gap-2.5">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/15 ring-1 ring-emerald-400/30">
                            <x-lucide-link-2 class="h-4 w-4 text-emerald-400" />
                        </div>
                        <div class="inline-flex items-center gap-x-1">
                            Lier de nouvelles compétences au module
                            <h2 class="truncate font-bold tracking-tight text-amber-600 font-trebuchet">
                                {{ $activeModule->name }}</h2>
                            <p class="mt-0.5 font-mono text-xs inline-flex text-center items-center p-1 rounded-2xl">
                                {{ $activeModule->code ?? '—' }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="sm:col-span-2">
                            <label for="skillToLinkId"
                                class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-300">Compétences
                                disponibles</label>
                            <select id="skillToLinkId" wire:model="skillToLinkId"
                                class="w-full rounded-xl border border-slate-700/80 bg-slate-950/80 px-3.5 py-2.5 text-sm text-slate-200 shadow-inner transition focus:border-emerald-500/60 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                <option value="">— Choisir une compétence —</option>
                                @foreach ($this->availableSkills as $skill)
                                    <option value="{{ $skill->id }}">
                                        {{ \Illuminate\Support\Str::limit($skill->content, 60) }}{{ $skill->code ? " ({$skill->code})" : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('skillToLinkId')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="linkCode"
                                class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-300">Code
                                (optionnel)</label>
                            <input id="linkCode" type="text" wire:model="linkCode" placeholder="Ex: C1"
                                class="w-full rounded-xl border border-slate-700/80 bg-slate-950/80 px-3.5 py-2.5 text-sm text-slate-200 shadow-inner transition focus:border-emerald-500/60 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                            @error('linkCode')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="linkCoef"
                                class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-300">Coef
                                (optionnel)</label>
                            <input id="linkCoef" type="number" wire:model="linkCoef" min="0"
                                step="1" placeholder="1"
                                class="w-full rounded-xl border border-slate-700/80 bg-slate-950/80 px-3.5 py-2.5 text-sm text-slate-200 shadow-inner transition focus:border-emerald-500/60 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                            @error('linkCoef')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col flex-wrap items-center justify-between gap-4 pt-2 sm:flex-row">
                        <button type="button" wire:click="linkSelectedSkill" wire:loading.attr="disabled"
                            wire:target="linkSelectedSkill"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-600/25 transition-all hover:bg-emerald-500 active:scale-95 disabled:opacity-50 sm:w-auto">
                            <span wire:loading.remove wire:target="linkSelectedSkill"><x-lucide-link
                                    class="h-4 w-4" /></span>
                            <span wire:loading wire:target="linkSelectedSkill"><x-lucide-loader-2
                                    class="h-4 w-4 animate-spin" /></span>
                            Lier la compétence
                        </button>

                        <div class="relative w-full sm:w-64">
                            <x-lucide-search
                                class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" />
                            <input type="search" wire:model.live.debounce.300ms="searchAvailable"
                                placeholder="Filtrer les disponibles..."
                                aria-label="Filtrer les compétences disponibles"
                                class="w-full rounded-xl border border-slate-700/80 bg-slate-950/80 py-2.5 pl-9 pr-3 text-xs text-slate-200 placeholder-slate-500 transition focus:border-emerald-500/50 focus:outline-none focus:ring-2 focus:ring-emerald-500/15" />
                        </div>
                    </div>

                    {{-- Liaison rapide --}}
                    @if ($searchAvailable && $this->availableSkills->isNotEmpty())
                        <div class="flex flex-wrap gap-2 border-t border-slate-800/80 pt-4">
                            @foreach ($this->availableSkills->take(8) as $skill)
                                <button type="button" wire:key="quick-{{ $skill->id }}"
                                    wire:click="quickLink({{ $skill->id }})" wire:loading.attr="disabled"
                                    wire:target="quickLink({{ $skill->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-full border border-slate-700/70 bg-slate-800/50 px-3 py-1.5 text-xs font-medium text-slate-300 transition hover:border-emerald-500/50 hover:bg-emerald-500/10 hover:text-emerald-300 disabled:opacity-50">
                                    <x-lucide-plus class="h-3 w-3 text-emerald-400" />
                                    {{ \Illuminate\Support\Str::limit($skill->content, 40) }}
                                </button>
                            @endforeach
                        </div>
                    @elseif ($searchAvailable)
                        <p class="border-t border-slate-800/80 pt-4 text-xs text-slate-500">Aucune compétence
                            disponible ne correspond à votre recherche.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

