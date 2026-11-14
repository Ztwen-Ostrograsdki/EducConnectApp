<div class="w-full overflow-x-hidden bg-[#0b0f19] min-h-screen">

    <div class="mx-auto w-full max-w-[1850px] px-4 sm:px-6 lg:px-8 py-8">

        {{-- ===================== HEADER ===================== --}}
        <header class="mb-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-violet-400/80 mb-2">
                        Administration
                    </p>
                    <h1 class="text-3xl sm:text-4xl font-semibold text-white tracking-tight">
                        Gestion des compétences
                    </h1>
                    <p class="mt-2 text-slate-400 text-sm max-w-lg">
                        Créez et gérez les compétences utilisées par les modules des classes de nouveaux métiers.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-2xl font-semibold text-white tabular-nums">
                            {{ __zero($this->skills->total()) }}
                        </span>
                        <span class="text-xs text-slate-500 uppercase tracking-wider">Compétences</span>
                    </div>

                    <button type="button" wire:click="openCreateForm" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 h-11 px-5 rounded-full bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium shadow-lg shadow-violet-900/30 transition-all active:scale-[0.97] disabled:opacity-50">
                        <span wire:loading.remove wire:target="openCreateForm">+</span>
                        <span wire:loading wire:target="openCreateForm">
                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                        </span>
                        Nouvelle compétence
                    </button>
                </div>
            </div>
        </header>

        {{-- ===================== FILTERS ===================== --}}
        <section class="mb-8">
            <div class="rounded-2xl bg-[#121826] border border-white/5 p-5">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Rechercher par énoncé ou code…"
                                class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500">
                                <x-lucide-search class="w-4 h-4" />
                            </span>
                        </div>

                        <button wire:click="clearFilters" wire:loading.attr="disabled"
                            class="h-11 px-5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all shrink-0 disabled:opacity-50">
                            <span wire:loading.remove wire:target="clearFilters" class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4" />
                                Réinitialiser
                            </span>
                            <span wire:loading wire:target="clearFilters" class="inline-flex items-center gap-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                Rechargement…
                            </span>
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-2.5">
                        <select wire:model.live="filterActive"
                            class="h-10 min-w-[180px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="all">Toutes</option>
                            <option value="active">Actives</option>
                            <option value="inactive">Inactives</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== FORMULAIRE ===================== --}}
        @if ($showForm)
            <section class="mb-8">
                <div class="rounded-2xl bg-[#121826] border border-white/5 p-6" wire:key="skill-form">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl {{ $editingId ? 'bg-amber-500/15 border border-amber-500/25' : 'bg-emerald-500/15 border border-emerald-500/25' }}">
                                @if ($editingId)
                                    <x-lucide-pencil class="w-5 h-5 text-amber-400" />
                                @else
                                    <x-lucide-plus-circle class="w-5 h-5 text-emerald-400" />
                                @endif
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-white">
                                    {{ $editingId ? 'Modifier la compétence' : 'Nouvelle compétence' }}
                                </h2>
                                <p class="text-xs text-slate-500">
                                    {{ $editingId ? 'Mettez à jour les informations' : 'Remplissez les champs ci-dessous' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="cancelForm"
                            class="h-9 w-9 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>

                    <form wire:submit="saveSkill" class="space-y-5">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">
                                Énoncé de la compétence *
                            </label>
                            <textarea wire:model="content" rows="3"
                                class="w-full rounded-xl bg-[#0b0f19] border border-white/10 px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all"
                                placeholder="Décrire clairement la compétence..."></textarea>
                            @error('content')
                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">Code</label>
                                <input type="text" wire:model="code"
                                    class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all" />
                                @error('code')
                                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">Poids</label>
                                <input type="text" wire:model="weight"
                                    class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all" />
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">Coefficient</label>
                                <input type="number" wire:model="coef" min="0"
                                    class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all" />
                            </div>

                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">Filière</label>
                                <select wire:model="filiar_id"
                                    class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                                    <option value="">— Aucune —</option>
                                    @foreach ($this->filiars as $f)
                                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">Série</label>
                                <select wire:model="serial_id"
                                    class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 px-4 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                                    <option value="">— Aucune —</option>
                                    @foreach ($this->serials as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5">
                            <label class="flex items-center justify-between gap-4 cursor-pointer select-none">
                                <span class="inline-flex items-center gap-2.5 text-sm text-slate-300">
                                    <span
                                        class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20
                                     flex items-center justify-center shrink-0">
                                        <x-lucide-power class="w-4 h-4 text-indigo-400" />
                                    </span>
                                    Compétence active
                                </span>
                                <div class="relative shrink-0">
                                    <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                                    <div
                                        class="h-6 w-11 rounded-full bg-slate-700 peer-checked:bg-indigo-500 transition-colors">
                                    </div>
                                    <div
                                        class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow
                                    transition-transform peer-checked:translate-x-5">
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2 border-t border-white/5">
                            <button type="button" wire:click="cancelForm"
                                class="h-10 px-5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all">
                                Annuler
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="h-10 px-6 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium shadow-lg shadow-violet-900/30 transition-all disabled:opacity-50 inline-flex items-center gap-2">
                                <span wire:loading.remove wire:target="saveSkill">
                                    <x-lucide-save class="w-4 h-4" />
                                </span>
                                <span wire:loading wire:target="saveSkill">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                </span>
                                {{ $editingId ? 'Mettre à jour' : 'Enregistrer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        @endif

        {{-- ===================== ACTIONS DE MASSE ===================== --}}
        @if (count($selected) > 0)
            <section class="mb-6">
                <div
                    class="flex flex-wrap items-center gap-2.5 rounded-2xl bg-[#121826] border border-violet-500/20 px-5 py-3.5">
                    <span
                        class="inline-flex items-center justify-center h-7 min-w-[28px] px-2 rounded-full bg-violet-500/20 text-xs font-bold text-violet-300">
                        {{ count($selected) }}
                    </span>
                    <span class="text-sm text-slate-400">sélectionnée(s)</span>

                    <div class="w-px h-5 bg-white/10 mx-1"></div>

                    <button wire:click="bulkActivateSkills" wire:loading.attr="disabled"
                        class="h-9 px-3.5 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all disabled:opacity-50 inline-flex items-center gap-1.5">
                        <x-lucide-check-circle class="w-3.5 h-3.5" />
                        Activer
                    </button>
                    <button wire:click="bulkDeactivateSkills" wire:loading.attr="disabled"
                        class="h-9 px-3.5 rounded-lg text-xs font-medium bg-amber-500/10 text-amber-300 border border-amber-500/20 hover:bg-amber-500/20 transition-all disabled:opacity-50 inline-flex items-center gap-1.5">
                        <x-lucide-circle-off class="w-3.5 h-3.5" />
                        Désactiver
                    </button>
                    <button wire:click="confirmBulkDeleteSkills" wire:loading.attr="disabled"
                        class="h-9 px-3.5 rounded-lg text-xs font-medium bg-rose-500/10 text-rose-300 border border-rose-500/20 hover:bg-rose-500/20 transition-all disabled:opacity-50 inline-flex items-center gap-1.5">
                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                        Supprimer
                    </button>
                </div>
            </section>
        @endif

        {{-- ===================== LISTE ===================== --}}
        <section class="relative mb-16">
            <div wire:loading
                wire:target="search,filterActive,clearFilters,saveSkill,openCreateForm,openEditForm,cancelForm,toggleSkillActive,confirmDeleteSkill,bulkActivateSkills,bulkDeactivateSkills,confirmBulkDeleteSkills,previousPage,nextPage,gotoPage"
                class="absolute inset-0 z-20 flex items-center justify-center bg-[#0b0f19]/70 rounded-2xl">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                    <svg class="animate-spin w-8 h-8 text-violet-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>
                    <span class="text-sm font-mono">Chargement…</span>
                </div>
            </div>

            @if (count($this->skills))
                <div class="space-y-4">
                    @foreach ($this->skills as $skill)
                        @php
                            $orderNumber = $this->skills->firstItem() + $loop->iteration - 1;
                        @endphp

                        <article
                            class="rounded-2xl bg-[#121826] border border-white/5 hover:border-violet-500/20 transition-all overflow-hidden">
                            <div class="p-5 sm:p-6">
                                <div class="flex flex-col xl:flex-row gap-6">

                                    {{-- N° + IDENTITY --}}
                                    <div class="flex gap-4 min-w-0 xl:w-[340px] shrink-0">
                                        <div class="flex items-start pt-1">
                                            <span
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-violet-500/15 border border-violet-500/25 text-violet-300 text-sm font-bold tabular-nums">
                                                {{ __zero($orderNumber) }}
                                            </span>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start gap-3">

                                                <div class="min-w-0">
                                                    {{-- Checkbox --}}
                                                    <div class="inline-flex items-center gap-2">
                                                        <label
                                                            class="inline-flex items-center justify-center cursor-pointer select-none rounded-full">
                                                            <input type="checkbox" value="{{ $skill->id }}"
                                                                wire:model.live="selected"
                                                                class="w-4 h-4 border rounded-full text-brand focus:ring-2 focus:ring-brand-soft focus:ring-offset-0 cursor-pointer checked:border-none transition-all" />
                                                        </label>
                                                        <h3 class="font-semibold text-white leading-snug">
                                                            {{ $skill->content }}
                                                        </h3>
                                                    </div>

                                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                                        @if ($skill->code)
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-gray-700/35 text-gray-400 text-xs font-mono uppercase">
                                                                {{ $skill->code }}
                                                            </span>
                                                        @endif

                                                        @if ($skill->is_active)
                                                            <span
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium">
                                                                <span
                                                                    class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                                Active
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-700/40 border border-slate-600/40 text-slate-500 text-xs font-medium">
                                                                Inactive
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- DETAILS --}}
                                    <div
                                        class="flex-1 min-w-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-6">
                                        <div class="flex flex-wrap gap-4 text-xs text-slate-500">
                                            <span>
                                                Coefficient :
                                                <span
                                                    class="text-slate-300 font-medium">{{ $skill->coef ?? '—' }}</span>
                                            </span>
                                            <span class="text-slate-700">·</span>
                                            <span>
                                                Poids :
                                                <span
                                                    class="text-slate-300 font-medium">{{ $skill->weight ?? '—' }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- ACTIONS --}}
                                    <div
                                        class="xl:w-[200px] shrink-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-5 flex flex-col gap-2">
                                        <button type="button" wire:click="openEditForm({{ $skill->id }})"
                                            wire:loading.attr="disabled"
                                            class="h-9 px-3 rounded-lg bg-indigo-500/15 hover:bg-indigo-500/25 border border-indigo-500/20 text-indigo-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                            <x-lucide-pen class="w-3.5 h-3.5" />
                                            Éditer
                                        </button>

                                        <button type="button" wire:click="toggleSkillActive({{ $skill->id }})"
                                            wire:loading.attr="disabled"
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                   {{ $skill->is_active
                                                       ? 'bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-300'
                                                       : 'bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-300' }}">
                                            <span wire:loading.remove
                                                wire:target="toggleSkillActive({{ $skill->id }})"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($skill->is_active)
                                                    <x-lucide-lock class="w-3.5 h-3.5" /> Désactiver
                                                @else
                                                    <x-lucide-unlock class="w-3.5 h-3.5" /> Activer
                                                @endif
                                            </span>
                                            <span wire:loading wire:target="toggleSkillActive({{ $skill->id }})">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        <button type="button" wire:click="confirmDeleteSkill({{ $skill->id }})"
                                            wire:loading.attr="disabled"
                                            class="h-9 px-3 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                            <x-lucide-trash class="w-3.5 h-3.5" />
                                            Supprimer
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($this->skills->hasPages())
                    <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-xs text-slate-500">
                            Affichage {{ $this->skills->firstItem() }} à {{ $this->skills->lastItem() }}
                            sur {{ $this->skills->total() }} compétences
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$this->skills->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                    ← Précédent
                                </button>
                            @endif

                            @foreach ($this->skills->getUrlRange(1, $this->skills->lastPage()) as $page => $url)
                                <button @disabled($page === $this->skills->currentPage()) wire:click="gotoPage({{ $page }})"
                                    class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                           {{ $page === $this->skills->currentPage()
                                               ? 'bg-violet-600 text-white'
                                               : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300' }}">
                                    {{ $page }}
                                </button>
                            @endforeach

                            @if ($this->skills->hasMorePages())
                                <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                    Suivant →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-2xl bg-[#121826] border border-white/5 py-20 text-center">
                    <span class="text-4xl mb-4 block">🧠</span>
                    <p class="text-slate-500 text-sm mb-4">Aucune compétence trouvée</p>
                    @if ($search || $filterActive !== 'all')
                        <button wire:click="clearFilters"
                            class="h-9 px-4 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all">
                            Réinitialiser les filtres
                        </button>
                    @else
                        <button type="button" wire:click="openCreateForm"
                            class="h-9 px-4 rounded-lg bg-violet-600 hover:bg-violet-500 text-sm text-white transition-all">
                            + Créer une compétence
                        </button>
                    @endif
                </div>
            @endif
        </section>

    </div>
</div>

