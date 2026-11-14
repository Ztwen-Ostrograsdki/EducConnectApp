<div class="min-h-screen space-y-6">

    {{-- Header --}}
    <div
        class="relative overflow-hidden rounded-2xl border border-white/5 bg-gradient-to-br from-slate-900 via-[#0c1220] to-[#0a1020] px-6 py-6 shadow-xl shadow-black/30">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-violet-500/15 blur-3xl"></div>
            <div class="absolute -left-8 bottom-0 h-32 w-32 rounded-full bg-indigo-500/10 blur-3xl"></div>
        </div>

        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-4">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-500/10 ring-1 ring-violet-400/30">
                    <x-lucide-layers class="h-5 w-5 text-violet-400" />
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white">Modules & Compétences</h1>
                    <p class="mt-1 text-sm text-slate-400">
                        Liez ou déliez des compétences aux modules
                        <code
                            class="rounded bg-slate-800/80 px-1.5 py-0.5 text-xs text-indigo-300">is_module=true</code>
                    </p>
                </div>
            </div>

            <a href="{{ route('tenant.skills.manage') }}" wire:navigate
                class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-600/80 bg-slate-800/50 px-4 py-2.5 text-sm font-medium text-slate-300 transition hover:border-violet-500/40 hover:bg-violet-500/10 hover:text-violet-300">
                <x-lucide-brain-circuit class="h-4 w-4" />
                Gérer les compétences
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        {{-- Colonne gauche : Modules --}}
        <div class="lg:col-span-4 space-y-3">
            {{-- Search --}}
            <div class="relative">
                <x-lucide-search
                    class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
                <input type="search" wire:model.live.debounce.300ms="searchModules"
                    placeholder="Filtrer les modules..."
                    class="w-full rounded-xl border border-slate-700/80 bg-slate-900/60 py-2.5 pl-10 pr-4 text-sm text-slate-200 placeholder-slate-500 transition focus:border-violet-500/60 focus:outline-none focus:ring-2 focus:ring-violet-500/20" />
            </div>

            {{-- Liste modules --}}
            <div
                class="overflow-hidden rounded-2xl border border-slate-700/60 bg-slate-900/40 shadow-lg shadow-black/20">
                <div class="max-h-[32rem] overflow-y-auto divide-y divide-slate-800/80">
                    @forelse ($this->modules as $module)
                        <button type="button" wire:click="selectModule({{ $module->id }})"
                            wire:loading.attr="disabled"
                            class="group w-full px-4 py-3.5 text-left transition
                                   {{ $selectedModuleId === $module->id ? 'bg-violet-500/10' : 'hover:bg-slate-800/50' }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        @if ($selectedModuleId === $module->id)
                                            <span
                                                class="h-1.5 w-1.5 shrink-0 rounded-full bg-violet-400 shadow-[0_0_8px_rgba(167,139,250,0.8)]"></span>
                                        @endif
                                        <span
                                            class="truncate text-sm font-medium {{ $selectedModuleId === $module->id ? 'text-violet-200' : 'text-slate-200' }}">
                                            {{ $module->name }}
                                        </span>
                                    </div>
                                    <div class="mt-0.5 font-mono text-xs text-slate-500">
                                        {{ $module->code ?? '—' }}
                                    </div>
                                </div>

                                <span
                                    class="inline-flex shrink-0 items-center gap-1 rounded-full bg-slate-800/80 px-2 py-0.5 text-[11px] font-medium text-slate-400 ring-1 ring-slate-700/60 transition group-hover:ring-slate-600">
                                    <x-lucide-link class="h-3 w-3" />
                                    {{ $module->module_skills_count }}
                                </span>
                            </div>
                        </button>
                    @empty
                        <div class="flex flex-col items-center justify-center px-4 py-12 text-center">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-800/60">
                                <x-lucide-package-open class="h-6 w-6 text-slate-500" />
                            </div>
                            <p class="text-sm text-slate-500">Aucun module trouvé.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="lg:col-span-8 space-y-4">
            @if (!$selectedModuleId)
                {{-- Empty state --}}
                <div
                    class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-700/70 bg-slate-900/30 px-6 py-20 text-center">
                    <div
                        class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-800/60 ring-1 ring-slate-700/50">
                        <x-lucide-mouse-pointer-click class="h-7 w-7 text-slate-500" />
                    </div>
                    <p class="text-sm font-medium text-slate-400">Sélectionnez un module</p>
                    <p class="mt-1 text-xs text-slate-500">Choisissez un module à gauche pour gérer ses compétences.</p>
                </div>
            @else
                @php $module = $this->selectedModule; @endphp

                {{-- Module sélectionné --}}
                <div class="rounded-2xl border border-slate-700/60 bg-slate-900/50 p-5 shadow-lg shadow-black/10">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-500/10 ring-1 ring-violet-400/25">
                                <x-lucide-book-open class="h-5 w-5 text-violet-400" />
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-100">{{ $module?->name }}</h2>
                                <p class="mt-0.5 font-mono text-xs text-slate-500">{{ $module?->code }}</p>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-violet-500/10 px-3 py-1 text-xs font-medium text-violet-300 ring-1 ring-violet-500/20">
                            <x-lucide-link class="h-3.5 w-3.5" />
                            {{ $this->linkedSkills->count() }} liée(s)
                        </span>
                    </div>
                </div>

                {{-- Formulaire de liaison --}}
                <div
                    class="rounded-2xl border border-slate-700/60 bg-slate-900/50 p-5 shadow-lg shadow-black/10 space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/10">
                            <x-lucide-link-2 class="h-4 w-4 text-emerald-400" />
                        </div>
                        <h3 class="text-sm font-semibold text-slate-200">Lier une compétence</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-medium text-slate-500">Compétence disponible</label>
                            <select wire:model="skillToLinkId"
                                class="w-full rounded-xl border border-slate-700/80 bg-slate-950/70 px-3.5 py-2.5 text-sm text-slate-200 transition focus:border-emerald-500/60 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                <option value="">— Choisir une compétence —</option>
                                @foreach ($this->availableSkills as $skill)
                                    <option value="{{ $skill->id }}">
                                        {{ \Illuminate\Support\Str::limit($skill->content, 60) }}
                                        @if ($skill->code)
                                            ({{ $skill->code }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-500">Code (optionnel)</label>
                            <input type="text" wire:model="linkCode"
                                class="w-full rounded-xl border border-slate-700/80 bg-slate-950/70 px-3.5 py-2.5 text-sm text-slate-200 transition focus:border-emerald-500/60 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-500">Coef (optionnel)</label>
                            <input type="number" wire:model="linkCoef" min="0"
                                class="w-full rounded-xl border border-slate-700/80 bg-slate-950/70 px-3.5 py-2.5 text-sm text-slate-200 transition focus:border-emerald-500/60 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" wire:click="linkSelectedSkill" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-500 disabled:opacity-50">
                            <span wire:loading.remove wire:target="linkSelectedSkill">
                                <x-lucide-link class="h-4 w-4" />
                            </span>
                            <span wire:loading wire:target="linkSelectedSkill">
                                <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                            </span>
                            Lier la compétence
                        </button>

                        <div class="relative min-w-[200px] flex-1 max-w-xs">
                            <x-lucide-search
                                class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-500" />
                            <input type="search" wire:model.live.debounce.300ms="searchAvailable"
                                placeholder="Filtrer les disponibles..."
                                class="w-full rounded-xl border border-slate-700/80 bg-slate-950/70 py-2 pl-9 pr-3 text-xs text-slate-200 placeholder-slate-500 transition focus:border-emerald-500/50 focus:outline-none focus:ring-2 focus:ring-emerald-500/15" />
                        </div>
                    </div>

                    {{-- Quick links --}}
                    @if ($this->availableSkills->isNotEmpty() && $searchAvailable)
                        <div class="flex flex-wrap gap-2 border-t border-slate-800/80 pt-3">
                            @foreach ($this->availableSkills->take(8) as $skill)
                                <button type="button" wire:click="quickLink({{ $skill->id }})"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-1.5 rounded-full border border-slate-700/70 bg-slate-800/40 px-3 py-1.5 text-xs text-slate-300 transition hover:border-emerald-500/50 hover:bg-emerald-500/10 hover:text-emerald-300">
                                    <x-lucide-plus class="h-3 w-3" />
                                    {{ \Illuminate\Support\Str::limit($skill->content, 40) }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Compétences liées --}}
                <div
                    class="overflow-hidden rounded-2xl border border-slate-700/60 bg-slate-900/40 shadow-lg shadow-black/10">
                    <div
                        class="flex items-center justify-between gap-3 border-b border-slate-800/80 bg-slate-900/70 px-5 py-3.5">
                        <h3 class="text-sm font-semibold text-slate-200">Compétences liées</h3>
                        <div class="relative w-52">
                            <x-lucide-search
                                class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-500" />
                            <input type="search" wire:model.live.debounce.300ms="searchSkills"
                                placeholder="Filtrer..."
                                class="w-full rounded-lg border border-slate-700/70 bg-slate-950/70 py-1.5 pl-9 pr-3 text-xs text-slate-200 placeholder-slate-500 transition focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/15" />
                        </div>
                    </div>

                    <div class="divide-y divide-slate-800/70">
                        @forelse ($this->linkedSkills as $ms)
                            <div wire:key="ms-{{ $ms->id }}"
                                class="group flex items-start justify-between gap-4 px-5 py-4 transition hover:bg-slate-800/30">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium leading-snug text-slate-200">
                                        {{ $ms->skill?->content }}
                                    </p>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        @if ($ms->code || $ms->skill?->code)
                                            <span
                                                class="inline-flex items-center rounded-md bg-slate-800/80 px-2 py-0.5 font-mono text-[11px] text-slate-400 ring-1 ring-slate-700/50">
                                                {{ $ms->code ?? $ms->skill?->code }}
                                            </span>
                                        @endif
                                        @if ($ms->coef)
                                            <span
                                                class="inline-flex items-center rounded-md bg-slate-800/80 px-2 py-0.5 text-[11px] text-slate-400 ring-1 ring-slate-700/50">
                                                Coef {{ $ms->coef }}
                                            </span>
                                        @endif
                                        @if ($ms->weight)
                                            <span
                                                class="inline-flex items-center rounded-md bg-slate-800/80 px-2 py-0.5 text-[11px] text-slate-400 ring-1 ring-slate-700/50">
                                                Poids {{ $ms->weight }}
                                            </span>
                                        @endif
                                        @if ($ms->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2 py-0.5 text-[11px] font-medium text-emerald-400 ring-1 ring-emerald-500/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-slate-700/40 px-2 py-0.5 text-[11px] font-medium text-slate-500 ring-1 ring-slate-600/40">
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div
                                    class="flex shrink-0 items-center gap-1 opacity-70 transition group-hover:opacity-100">
                                    <button type="button" wire:click="toggleModuleSkillActive({{ $ms->id }})"
                                        wire:loading.attr="disabled"
                                        class="rounded-lg p-2 text-slate-400 transition hover:bg-indigo-500/10 hover:text-indigo-400"
                                        title="Activer / Désactiver">
                                        <span wire:loading.remove
                                            wire:target="toggleModuleSkillActive({{ $ms->id }})">
                                            <x-lucide-power class="h-4 w-4" />
                                        </span>
                                        <span wire:loading wire:target="toggleModuleSkillActive({{ $ms->id }})">
                                            <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                                        </span>
                                    </button>
                                    <button type="button" wire:click="confirmUnlinkModuleSkill({{ $ms->id }})"
                                        wire:loading.attr="disabled"
                                        class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-500/10 hover:text-rose-400"
                                        title="Délier">
                                        <x-lucide-unlink class="h-4 w-4" />
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center px-5 py-14 text-center">
                                <div
                                    class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-800/50">
                                    <x-lucide-unlink class="h-6 w-6 text-slate-500" />
                                </div>
                                <p class="text-sm text-slate-500">Aucune compétence liée à ce module.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
