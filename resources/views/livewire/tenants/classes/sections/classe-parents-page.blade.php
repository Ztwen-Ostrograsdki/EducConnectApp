<div class="w-full overflow-x-hidden bg-transparent min-h-screen">

    <div class="mx-auto w-full max-w-[1850px] px-4 sm:px-6 lg:px-8 py-8">

        {{-- ===================== HEADER ===================== --}}
        <header class="mb-8 border border-indigo-950 rounded-2xl p-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-white tracking-tight flex items-center gap-3">
                        Liste des parents de {{ $classe->code }}
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-2xl bg-violet-500/15 border border-violet-500/25 text-violet-300 text-sm font-medium tabular-nums">
                            {{ $this->tutors->total() }} parent(s)
                        </span>
                    </h1>
                    <p class="mt-1.5 text-sm text-slate-500">
                        Gestion des accès et suivi des représentants
                    </p>
                </div>
            </div>
        </header>

        {{-- ===================== FILTERS ===================== --}}
        <section class="mb-6 relative">
            <div wire:loading wire:target="status,clearFilters,search,previousPage,nextPage,gotoPage"
                class="absolute inset-0 z-20 flex items-center justify-center bg-[#0b0f19]/60 rounded-2xl">
                <div class="flex flex-col items-center gap-2 text-slate-400">
                    <svg class="animate-spin w-7 h-7 text-violet-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>
                    <span class="text-xs font-mono">Chargement…</span>
                </div>
            </div>

            <div class="rounded-2xl bg-transparent border border-white/5 p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <input wire:model.live.debounce.600ms="search" type="text" placeholder="Trouver un parent…"
                            class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500">🔍</span>
                    </div>

                    <select wire:model.live="status"
                        class="h-11 min-w-[150px] rounded-xl bg-[#0b0f19] border border-white/10 px-3 text-sm text-slate-300 focus:outline-none focus:border-violet-500/40">
                        <option value="">Tout statut</option>
                        <option value="actives">Actifs</option>
                        <option value="desactives">Bloqués</option>
                        <option value="corbeille">Corbeille</option>
                    </select>

                    <button wire:click="clearFilters"
                        class="h-11 px-5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all shrink-0">
                        <span wire:loading.remove wire:target="clearFilters" class="inline-flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-4 h-4" />
                            Réinitialiser
                        </span>
                        <span wire:loading wire:target="clearFilters" class="inline-flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            …
                        </span>
                    </button>
                </div>
            </div>
        </section>

        {{-- ===================== LIST ===================== --}}
        <section class="mb-16">
            @if (count($this->tutors) > 0)
                <div class="space-y-3">
                    @foreach ($this->tutors as $parent)
                        @php
                            $orderNumber = $this->tutors->firstItem() + $loop->iteration - 1;
                        @endphp

                        <article
                            class="rounded-2xl bg-slate-950 shadow-sm shadow-sky-600 border border-white/5 hover:border-violet-500/20 transition-all overflow-hidden">
                            <div class="p-4 sm:p-5">
                                <div class="flex flex-col lg:flex-row gap-5">

                                    {{-- N° + Identity --}}
                                    <div class="flex gap-3.5 min-w-0 lg:w-[340px] shrink-0">
                                        <div class="flex items-start pt-1">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-violet-500/15 border border-violet-500/25 text-violet-300 text-xs font-bold tabular-nums">
                                                {{ __zero($orderNumber) }}
                                            </span>
                                        </div>

                                        <a href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent->uuid]) }}"
                                            class="flex gap-3 min-w-0 flex-1 group">
                                            <img src="{{ $parent->profil_photo_url() }}" alt="{{ $parent->fullName() }}"
                                                class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/10 group-hover:ring-violet-500/40 transition-all shrink-0">
                                            <div class="min-w-0">
                                                <h3
                                                    class="font-semibold text-white group-hover:text-violet-300 transition-colors truncate text-sm">
                                                    {{ $parent->getFullName() }}
                                                </h3>
                                                <div class="mt-1.5 space-y-0.5 text-[11px] text-slate-400">
                                                    <p class="flex items-center gap-1.5 truncate">
                                                        <x-lucide-mail class="w-3 h-3 shrink-0 text-slate-500" />
                                                        {{ $parent->user->email }}
                                                    </p>
                                                    @if ($parent->user->contacts)
                                                        <p class="flex items-center gap-1.5 truncate">
                                                            <x-lucide-phone class="w-3 h-3 shrink-0 text-slate-500" />
                                                            {{ $parent->user->contacts }}
                                                        </p>
                                                    @endif
                                                    @if ($parent->user->job_name)
                                                        <p class="flex items-center gap-1.5 truncate">
                                                            <x-lucide-briefcase-business
                                                                class="w-3 h-3 shrink-0 text-slate-500" />
                                                            {{ $parent->user->job_name }}
                                                        </p>
                                                    @endif
                                                    @if ($parent->user->adresse)
                                                        <p class="flex items-center gap-1.5 truncate">
                                                            <x-lucide-map-pin-check
                                                                class="w-3 h-3 shrink-0 text-slate-500" />
                                                            {{ $parent->user->adresse }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    {{-- Status + children --}}
                                    <div
                                        class="flex-1 min-w-0 border-t lg:border-t-0 lg:border-l border-white/5 pt-3 lg:pt-0 lg:pl-5 flex flex-wrap items-center gap-3">
                                        @if ($parent->deleted_at)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-500/10 text-rose-400 text-[11px] font-medium border border-rose-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                Supprimé
                                            </span>
                                        @elseif ($parent->is_active)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[11px] font-medium border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                Actif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-400 text-[11px] font-medium border border-amber-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                Bloqué
                                            </span>
                                        @endif

                                        @if (count($parent->myChildren))
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-300 text-[11px] font-medium border border-emerald-500/20">
                                                {{ count($parent->myChildren) }} apprenant(s)
                                            </span>
                                        @else
                                            <span class="text-[11px] text-slate-600 italic">Aucun apprenant lié</span>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div
                                        class="lg:w-[200px] shrink-0 border-t lg:border-t-0 lg:border-l border-white/5 pt-3 lg:pt-0 lg:pl-4 flex flex-wrap lg:flex-col gap-2">
                                        @if (!$parent->user->credentials_sent)
                                            <button wire:click="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                class="h-8 px-3 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/20 text-sky-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                                <span wire:loading.remove
                                                    wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                    class="inline-flex items-center gap-1.5">
                                                    <x-lucide-send class="w-3.5 h-3.5" /> Envoyer
                                                </span>
                                                <span wire:loading
                                                    wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')">
                                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>
                                        @endif

                                        <button
                                            wire:click="{{ !$parent->is_active ? 'activateTutor(' . $parent->id . ')' : 'desactivateTutor(' . $parent->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                            class="h-8 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ !$parent->is_active
                                                           ? 'bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-300'
                                                           : 'bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-300' }}">
                                            <span wire:loading.remove
                                                wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                                class="inline-flex items-center gap-1.5">
                                                @if (!$parent->is_active)
                                                    <x-lucide-lock-keyhole-open class="w-3.5 h-3.5" /> Activer
                                                @else
                                                    <x-lucide-ban class="w-3.5 h-3.5" /> Désactiver
                                                @endif
                                            </span>
                                            <span wire:loading
                                                wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        <button
                                            wire:click="{{ $parent->deleted_at ? 'restoreTutor(' . $parent->id . ')' : 'deleteTutor(' . $parent->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                            class="h-8 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ $parent->deleted_at
                                                           ? 'bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-300'
                                                           : 'bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300' }}">
                                            <span wire:loading.remove
                                                wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($parent->deleted_at)
                                                    <x-lucide-recycle class="w-3.5 h-3.5" /> Restaurer
                                                @else
                                                    <x-lucide-trash class="w-3.5 h-3.5" /> Corbeille
                                                @endif
                                            </span>
                                            <span wire:loading
                                                wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        @if ($parent->deleted_at)
                                            <button wire:click="forceDeleteTutor({{ $parent->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="forceDeleteTutor({{ $parent->id }})"
                                                class="h-8 px-3 rounded-lg bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                                <span wire:loading.remove
                                                    wire:target="forceDeleteTutor({{ $parent->id }})"
                                                    class="inline-flex items-center gap-1.5">
                                                    <x-lucide-trash-2 class="w-3.5 h-3.5" /> Suppr. déf.
                                                </span>
                                                <span wire:loading
                                                    wire:target="forceDeleteTutor({{ $parent->id }})">
                                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($this->tutors->hasPages())
                    <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-xs text-slate-500">
                            Affichage {{ $this->tutors->firstItem() }} à {{ $this->tutors->lastItem() }}
                            sur {{ $this->tutors->total() }} parents
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$this->tutors->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                    ← Précédent
                                </button>
                            @endif
                            @foreach ($this->tutors->getUrlRange(1, $this->tutors->lastPage()) as $page => $url)
                                <button @disabled($page === $this->tutors->currentPage()) wire:click="gotoPage({{ $page }})"
                                    class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                               {{ $page === $this->tutors->currentPage()
                                                   ? 'bg-violet-600 text-white'
                                                   : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                            @if ($this->tutors->hasMorePages())
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
                    <span class="text-4xl mb-4 block">👤</span>
                    <p class="text-slate-500 text-sm mb-4">Aucun parent ou tuteur trouvé</p>
                    @if ($search || $status)
                        <button wire:click="clearFilters"
                            class="h-9 px-4 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all">
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            @endif
        </section>

    </div>
</div>

