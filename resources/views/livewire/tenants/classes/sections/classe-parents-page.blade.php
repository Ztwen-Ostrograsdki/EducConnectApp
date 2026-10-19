<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ========== HEADER ========== --}}
        <header class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-violet-400/80 mb-1">
                        Vie scolaire
                    </p>
                    <h1
                        class="text-xl sm:text-2xl font-bold text-white tracking-tight flex flex-wrap items-center gap-2.5">
                        Parents de
                        <span class="text-violet-400 font-mono">{{ $classe->code }}</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold
                                     bg-violet-500/15 text-violet-300 border border-violet-500/25 tabular-nums">
                            {{ $this->tutors->total() }}
                        </span>
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Accès et suivi des représentants légaux
                    </p>
                </div>
            </div>
        </header>

        {{-- ========== FILTRES ========== --}}
        <section class="relative rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5">
            <div wire:loading wire:target="status,clearFilters,search,previousPage,nextPage,gotoPage"
                class="absolute inset-0 z-20 flex items-center justify-center
                        bg-[#070a12]/70 backdrop-blur-sm rounded-2xl">
                <div class="flex flex-col items-center gap-2 text-slate-400">
                    <x-lucide-loader-2 class="w-7 h-7 text-violet-400 animate-spin" />
                    <span class="text-xs">Chargement…</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input wire:model.live.debounce.600ms="search" type="text" placeholder="Trouver un parent…"
                        class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                  pl-10 pr-4 text-sm text-white placeholder:text-slate-600
                                  focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                  outline-none transition-all" />
                </div>

                <select wire:model.live="status"
                    class="h-11 min-w-[150px] rounded-xl border border-white/[0.08] bg-[#070a12]
                               px-3 text-sm text-slate-300
                               focus:border-violet-500/50 focus:outline-none transition-all">
                    <option value="">Tout statut</option>
                    <option value="actives">Actifs</option>
                    <option value="desactives">Bloqués</option>
                    <option value="corbeille">Corbeille</option>
                </select>

                <button wire:click="clearFilters"
                    class="h-11 px-4 rounded-xl text-xs font-medium
                               bg-white/[0.04] border border-white/[0.08] text-slate-400
                               hover:bg-white/[0.08] hover:text-white transition-all shrink-0
                               inline-flex items-center gap-1.5">
                    <span wire:loading.remove wire:target="clearFilters" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5" />
                        Réinitialiser
                    </span>
                    <span wire:loading wire:target="clearFilters">
                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                    </span>
                </button>
            </div>
        </section>

        {{-- ========== LISTE ========== --}}
        <section class="space-y-3 pb-12">
            @if (count($this->tutors) > 0)
                @foreach ($this->tutors as $parent)
                    @php $orderNumber = $this->tutors->firstItem() + $loop->iteration - 1; @endphp

                    <article
                        class="rounded-2xl border border-white/[0.06] bg-white/[0.02]
                                    hover:border-violet-500/20 transition-all overflow-hidden">
                        <div class="p-4 sm:p-5">
                            <div class="flex flex-col lg:flex-row gap-4 lg:gap-5">

                                {{-- Identité --}}
                                <div class="flex gap-3 min-w-0 lg:w-[360px] shrink-0">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                 bg-violet-500/15 border border-violet-500/25
                                                 text-violet-300 text-xs font-bold tabular-nums shrink-0 mt-1">
                                        {{ __zero($orderNumber) }}
                                    </span>

                                    <a href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent->uuid]) }}"
                                        wire:navigate class="flex gap-3 min-w-0 flex-1 group">
                                        <img src="{{ $parent->profil_photo_url() }}" alt="{{ $parent->fullName() }}"
                                            class="w-12 h-12 rounded-xl object-cover
                                                    border border-white/[0.08]
                                                    group-hover:border-violet-500/40 transition-all shrink-0" />
                                        <div class="min-w-0">
                                            <h3
                                                class="font-semibold text-white text-sm
                                                       group-hover:text-violet-300 transition-colors truncate">
                                                {{ $parent->getFullName() }}
                                            </h3>
                                            <div class="mt-1.5 space-y-0.5 text-[11px] text-slate-500">
                                                <p class="flex items-center gap-1.5 truncate">
                                                    <x-lucide-mail class="w-3 h-3 shrink-0" />
                                                    {{ $parent->user->email }}
                                                </p>
                                                @if ($parent->user->contacts)
                                                    <p class="flex items-center gap-1.5 truncate">
                                                        <x-lucide-phone class="w-3 h-3 shrink-0" />
                                                        {{ $parent->user->contacts }}
                                                    </p>
                                                @endif
                                                @if ($parent->user->job_name)
                                                    <p class="flex items-center gap-1.5 truncate">
                                                        <x-lucide-briefcase class="w-3 h-3 shrink-0" />
                                                        {{ $parent->user->job_name }}
                                                    </p>
                                                @endif
                                                @if ($parent->user->adresse)
                                                    <p class="flex items-center gap-1.5 truncate">
                                                        <x-lucide-map-pin class="w-3 h-3 shrink-0" />
                                                        {{ $parent->user->adresse }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                {{-- Statut --}}
                                <div
                                    class="flex-1 min-w-0 border-t lg:border-t-0 lg:border-l border-white/[0.05]
                                            pt-3 lg:pt-0 lg:pl-5 flex flex-wrap items-center gap-2">
                                    @if ($parent->deleted_at)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md
                                                     text-[11px] font-medium
                                                     bg-rose-500/15 text-rose-400 border border-rose-500/25">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                            Supprimé
                                        </span>
                                    @elseif ($parent->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md
                                                     text-[11px] font-medium
                                                     bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            Actif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md
                                                     text-[11px] font-medium
                                                     bg-amber-500/15 text-amber-400 border border-amber-500/25">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                            Bloqué
                                        </span>
                                    @endif

                                    @if (count($parent->myChildren))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md
                                                     text-[11px] font-medium
                                                     bg-sky-500/10 text-sky-300 border border-sky-500/20">
                                            {{ count($parent->myChildren) }} apprenant(s)
                                        </span>
                                    @else
                                        <span class="text-[11px] text-slate-600 italic">Aucun apprenant lié</span>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div
                                    class="lg:w-[180px] shrink-0 border-t lg:border-t-0 lg:border-l border-white/[0.05]
                                            pt-3 lg:pt-0 lg:pl-4 flex flex-wrap lg:flex-col gap-1.5">

                                    @if (!$parent->user->credentials_sent)
                                        <button wire:click="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                            class="h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                       bg-sky-500/15 text-sky-400 border border-sky-500/25
                                                       hover:bg-sky-500 hover:text-white hover:border-sky-500
                                                       transition-all disabled:opacity-50
                                                       inline-flex items-center justify-center gap-1.5">
                                            <span wire:loading.remove
                                                wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                class="inline-flex items-center gap-1.5">
                                                <x-lucide-send class="w-3.5 h-3.5" /> Envoyer
                                            </span>
                                            <span wire:loading
                                                wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')">
                                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>
                                    @endif

                                    <button
                                        wire:click="{{ !$parent->is_active ? 'activateTutor(' . $parent->id . ')' : 'desactivateTutor(' . $parent->id . ')' }}"
                                        wire:loading.attr="disabled"
                                        wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                        class="h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                   transition-all disabled:opacity-50
                                                   inline-flex items-center justify-center gap-1.5
                                                   {{ !$parent->is_active
                                                       ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                                                       : 'bg-amber-500/15 text-amber-400 border border-amber-500/25 hover:bg-amber-500 hover:text-white hover:border-amber-500' }}">
                                        <span wire:loading.remove
                                            wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                            class="inline-flex items-center gap-1.5">
                                            @if (!$parent->is_active)
                                                <x-lucide-unlock class="w-3.5 h-3.5" /> Activer
                                            @else
                                                <x-lucide-ban class="w-3.5 h-3.5" /> Désactiver
                                            @endif
                                        </span>
                                        <span wire:loading
                                            wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>

                                    <button
                                        wire:click="{{ $parent->deleted_at ? 'restoreTutor(' . $parent->id . ')' : 'deleteTutor(' . $parent->id . ')' }}"
                                        wire:loading.attr="disabled"
                                        wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                        class="h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                   transition-all disabled:opacity-50
                                                   inline-flex items-center justify-center gap-1.5
                                                   {{ $parent->deleted_at
                                                       ? 'bg-violet-500/15 text-violet-400 border border-violet-500/25 hover:bg-violet-500 hover:text-white hover:border-violet-500'
                                                       : 'bg-rose-500/15 text-rose-400 border border-rose-500/25 hover:bg-rose-500 hover:text-white hover:border-rose-500' }}">
                                        <span wire:loading.remove
                                            wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                            class="inline-flex items-center gap-1.5">
                                            @if ($parent->deleted_at)
                                                <x-lucide-recycle class="w-3.5 h-3.5" /> Restaurer
                                            @else
                                                <x-lucide-trash-2 class="w-3.5 h-3.5" /> Corbeille
                                            @endif
                                        </span>
                                        <span wire:loading
                                            wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>

                                    @if ($parent->deleted_at)
                                        <button wire:click="forceDeleteTutor({{ $parent->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="forceDeleteTutor({{ $parent->id }})"
                                            class="h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                       bg-red-500/15 text-red-400 border border-red-500/25
                                                       hover:bg-red-500 hover:text-white hover:border-red-500
                                                       transition-all disabled:opacity-50
                                                       inline-flex items-center justify-center gap-1.5">
                                            <span wire:loading.remove
                                                wire:target="forceDeleteTutor({{ $parent->id }})"
                                                class="inline-flex items-center gap-1.5">
                                                <x-lucide-trash-2 class="w-3.5 h-3.5" /> Suppr. déf.
                                            </span>
                                            <span wire:loading wire:target="forceDeleteTutor({{ $parent->id }})">
                                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach

                {{-- Pagination --}}
                @if ($this->tutors->hasPages())
                    <div class="pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-xs text-slate-500">
                            {{ $this->tutors->firstItem() }}–{{ $this->tutors->lastItem() }}
                            sur {{ $this->tutors->total() }}
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$this->tutors->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-9 px-3 rounded-lg text-xs font-medium
                                               bg-white/[0.04] border border-white/[0.08] text-slate-400
                                               hover:bg-white/[0.08] hover:text-white transition-all disabled:opacity-50">
                                    ← Préc.
                                </button>
                            @endif
                            @foreach ($this->tutors->getUrlRange(1, $this->tutors->lastPage()) as $page => $url)
                                <button @disabled($page === $this->tutors->currentPage()) wire:click="gotoPage({{ $page }})"
                                    class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                               {{ $page === $this->tutors->currentPage()
                                                   ? 'bg-violet-500 text-white'
                                                   : 'bg-white/[0.04] border border-white/[0.08] text-slate-400 hover:bg-white/[0.08] hover:text-white' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                            @if ($this->tutors->hasMorePages())
                                <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                    class="h-9 px-3 rounded-lg text-xs font-medium
                                               bg-white/[0.04] border border-white/[0.08] text-slate-400
                                               hover:bg-white/[0.08] hover:text-white transition-all disabled:opacity-50">
                                    Suiv. →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-2xl border border-dashed border-white/[0.08] bg-white/[0.02] py-16 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-violet-500/10 mb-4">
                        <x-lucide-users class="w-7 h-7 text-violet-400" />
                    </div>
                    <p class="text-sm text-slate-500 mb-4">Aucun parent ou tuteur trouvé</p>
                    @if ($search || $status)
                        <button wire:click="clearFilters"
                            class="h-9 px-4 rounded-xl text-xs font-medium
                                       bg-white/[0.04] border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.08] hover:text-white transition-all">
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            @endif
        </section>

    </div>
</div>
