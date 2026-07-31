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
                        Parents d’élèves
                    </h1>
                    <p class="mt-2 text-slate-400 text-sm max-w-lg">
                        Gestion centralisée des représentants légaux des apprenants
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-2xl font-semibold text-white tabular-nums">{{ $this->tutors->total() }}</span>
                        <span class="text-xs text-slate-500 uppercase tracking-wider">Parents</span>
                    </div>
                    <a href="{{ route('tenant.parents.create') }}"
                        class="inline-flex items-center gap-2 h-11 px-5 rounded-full bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium shadow-lg shadow-violet-900/30 transition-all active:scale-[0.97]">
                        <x-lucide-user-plus class="w-4 h-4" />
                        Créer des comptes
                    </a>
                </div>
            </div>
        </header>

        {{-- ===================== FILTERS ===================== --}}
        <section class="mb-8">
            <div class="rounded-2xl bg-[#121826] border border-white/5 p-5">
                <div class="flex flex-col gap-4">
                    {{-- Search --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input wire:model.live.debounce.600ms="search" type="text"
                                placeholder="Trouver un parent…"
                                class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500">🔍</span>
                        </div>
                        <button wire:click="clearFilters"
                            class="h-11 px-5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all shrink-0">
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

                    {{-- Selects --}}
                    <div class="flex flex-wrap gap-2.5">
                        <select wire:model.live="classe_id"
                            class="h-10 min-w-[180px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les classes</option>
                            @foreach ($this->classes as $cl)
                                <option value="{{ $cl->id }}">{{ $cl->code ?: $cl->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filiar_id"
                            class="h-10 min-w-[160px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les séries</option>
                            @foreach ($this->serials as $s)
                                <option value="{{ $s->id }}">{{ $s->code ?: $s->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filiar_id"
                            class="h-10 min-w-[160px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les filières</option>
                            @foreach ($this->filiars as $f)
                                <option value="{{ $f->id }}">{{ $f->code ?: $f->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="department"
                            class="h-10 min-w-[140px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Département</option>
                            @foreach ($this->departments as $dp => $dpv)
                                <option value="{{ $dpv }}">{{ $dpv }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="city"
                            class="h-10 min-w-[130px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Ville</option>
                            @foreach ($this->cities as $ct => $ctv)
                                <option value="{{ $ctv }}">{{ $ctv }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="gender"
                            class="h-10 min-w-[110px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Sexe</option>
                            @foreach (config('app.genders') as $gk => $gdr)
                                <option value="{{ $gk }}">{{ $gdr }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="status"
                            class="h-10 min-w-[130px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Tout statut</option>
                            <option value="actives">Actifs</option>
                            <option value="desactives">Bloqués</option>
                            <option value="corbeille">Corbeille</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== BULK ACTIONS ===================== --}}
        <section class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button wire:click="desactivateTutors" wire:loading.attr="disabled" wire:target="desactivateTutors"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-amber-500/10 text-amber-300 border border-amber-500/20 hover:bg-amber-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="desactivateTutors" class="inline-flex items-center gap-1.5">
                        <x-lucide-ban class="w-3.5 h-3.5" /> Désactiver tous
                    </span>
                    <span wire:loading wire:target="desactivateTutors" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> En cours…
                    </span>
                </button>
                <button wire:click="activateTutors" wire:loading.attr="disabled" wire:target="activateTutors"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="activateTutors" class="inline-flex items-center gap-1.5">
                        <x-lucide-user-check class="w-3.5 h-3.5" /> Réactiver tous
                    </span>
                    <span wire:loading wire:target="activateTutors" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> En cours…
                    </span>
                </button>
                <button wire:click="restoreTutors" wire:loading.attr="disabled" wire:target="restoreTutors"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/10 text-violet-300 border border-violet-500/20 hover:bg-violet-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="restoreTutors" class="inline-flex items-center gap-1.5">
                        <x-lucide-recycle class="w-3.5 h-3.5" /> Restaurer tous
                    </span>
                    <span wire:loading wire:target="restoreTutors" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> En cours…
                    </span>
                </button>
                <button wire:click="forceDeleteTutors" wire:loading.attr="disabled" wire:target="forceDeleteTutors"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-rose-500/10 text-rose-300 border border-rose-500/20 hover:bg-rose-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="forceDeleteTutors" class="inline-flex items-center gap-1.5">
                        <x-lucide-user-x class="w-3.5 h-3.5" /> Suppr. déf. tous
                    </span>
                    <span wire:loading wire:target="forceDeleteTutors" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> En cours…
                    </span>
                </button>
            </div>
        </section>

        {{-- ===================== LIST ===================== --}}
        <section class="relative mb-16">
            {{-- Loading --}}
            <div wire:loading
                wire:target="gender,status,department,city,clearFilters,classe_id,promotion_id,filiar_id,serial_id,search,previousPage,nextPage,gotoPage"
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

            @if (count($this->tutors) > 0)
                <div class="space-y-4">
                    @foreach ($this->tutors as $parent)
                        <article
                            class="rounded-2xl bg-[#121826] border border-white/5 hover:border-violet-500/20 transition-all overflow-hidden">
                            <div class="p-5 sm:p-6">
                                <div class="flex flex-col xl:flex-row gap-6">

                                    {{-- IDENTITY --}}
                                    <div class="flex gap-4 min-w-0 xl:w-[320px] shrink-0">
                                        <a href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent->uuid]) }}"
                                            class="shrink-0">
                                            <img src="{{ $parent->profil_photo_url() }}"
                                                alt="{{ $parent->fullName() }}"
                                                class="w-16 h-16 rounded-2xl object-cover ring-2 ring-white/10 hover:ring-violet-500/40 transition-all">
                                        </a>
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent->uuid]) }}"
                                                class="block font-semibold text-white hover:text-violet-300 transition-colors truncate">
                                                {{ $parent->getFullName() }}
                                            </a>
                                            <div class="mt-2 space-y-1 text-xs text-slate-400">
                                                <p class="flex items-center gap-1.5 truncate">
                                                    <x-lucide-mail class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                                    {{ $parent->user->email }}
                                                </p>
                                                @if ($parent->user->job_name)
                                                    <p class="flex items-center gap-1.5 truncate">
                                                        <x-lucide-briefcase-business
                                                            class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                                        {{ $parent->user->job_name }}
                                                    </p>
                                                @endif
                                                <p class="flex items-center gap-1.5 truncate">
                                                    <x-lucide-phone class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                                    {{ $parent->user->contacts }}
                                                </p>
                                                @if ($parent->user->adresse)
                                                    <p class="flex items-center gap-1.5 truncate">
                                                        <x-lucide-map-pin-check
                                                            class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                                        {{ $parent->user->adresse }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="mt-3">
                                                @if ($parent->is_active)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[11px] font-medium border border-emerald-500/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                        Actif
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-500/10 text-rose-400 text-[11px] font-medium border border-rose-500/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                        Bloqué
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- CHILDREN --}}
                                    <div
                                        class="flex-1 min-w-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-6">
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-3">
                                            Enfant(s)
                                            @if (count($parent->myChildren))
                                                <span class="text-slate-400">· {{ count($parent->myChildren) }}</span>
                                            @endif
                                        </p>

                                        @if (count($parent->myChildren))
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($parent->myChildren()->take(2)->get() as $rel)
                                                    @php $student = $rel->student; @endphp
                                                    <a wire:navigate
                                                        href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                                        class="inline-flex flex-col px-3 py-2 rounded-xl bg-[#0b0f19] border border-white/5 hover:border-violet-500/30 text-sm transition-all active:scale-[0.98]">
                                                        <span
                                                            class="font-medium text-slate-200">{{ $student->getFullName() }}</span>
                                                        @if ($student->currentClasse() && $student->currentClasse()->classe)
                                                            @php $r = $student->currentClasse()->classe; @endphp
                                                            <span class="mt-1 text-[11px] text-amber-400/90 font-mono">
                                                                {{ $r->code ?: $r->name }}
                                                            </span>
                                                        @else
                                                            <span class="mt-1 text-[11px] text-slate-600">Pas de
                                                                classe</span>
                                                        @endif
                                                    </a>
                                                @endforeach

                                                @if (count($parent->myChildren) > 2)
                                                    <a href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent->uuid]) }}"
                                                        class="inline-flex items-center gap-1 px-3 py-2 rounded-xl bg-violet-500/10 border border-violet-500/20 text-violet-300 text-xs hover:bg-violet-500/20 transition-all">
                                                        +{{ count($parent->myChildren) - 2 }} autres
                                                        <x-lucide-chevron-right class="w-3.5 h-3.5" />
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-sm text-slate-600 italic">Aucun apprenant lié</p>
                                        @endif
                                    </div>

                                    {{-- ACTIONS --}}
                                    <div
                                        class="xl:w-[200px] shrink-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-5 flex flex-col gap-2">
                                        <a wire:navigate
                                            href="{{ route('tenant.parents.manage.relations', ['parent_uuid' => $parent->uuid]) }}"
                                            class="h-9 px-3 rounded-lg bg-violet-500/15 hover:bg-violet-500/25 border border-violet-500/20 text-violet-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all">
                                            Apprenants associés
                                        </a>

                                        @if (!$parent->user->credentials_sent)
                                            <button wire:click="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                class="h-9 px-3 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/20 text-sky-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                                <span wire:loading.remove
                                                    wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                    class="inline-flex items-center gap-1.5">
                                                    <x-lucide-send class="w-3.5 h-3.5" /> Envoyer identifiants
                                                </span>
                                                <span wire:loading
                                                    wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                    class="inline-flex items-center gap-1.5">
                                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> …
                                                </span>
                                            </button>
                                        @endif

                                        <button
                                            wire:click="{{ !$parent->is_active ? 'activateTutor(' . $parent->id . ')' : 'desactivateTutor(' . $parent->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
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
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
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
                                                class="h-9 px-3 rounded-lg bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
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
                    @if ($search || $status || $classe_id || $serial_id || $promotion_id || $filiar_id || $gender || $city || $department)
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
