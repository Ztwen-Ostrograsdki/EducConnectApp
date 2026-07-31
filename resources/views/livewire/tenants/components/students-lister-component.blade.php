<div class="w-full overflow-x-hidden">

    {{-- ===================== FILTERS ===================== --}}
    <section class="mb-6">
        <div class="rounded-2xl bg-slate-950 shadow-xs shadow-sky-900 border border-white/5 p-4 sm:p-5">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <input wire:model.live.debounce.400ms="search" type="text"
                            placeholder="Rechercher un apprenant…"
                            class="w-full h-11 rounded-xl bg-[#0b0f19] border border-white/10 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500">🔍</span>
                    </div>
                    <button wire:click="resetFilters"
                        class="h-11 px-5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all shrink-0">
                        <span wire:loading.remove wire:target="resetFilters" class="inline-flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-4 h-4" />
                            Réinitialiser
                        </span>
                        <span wire:loading wire:target="resetFilters" class="inline-flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            Rechargement…
                        </span>
                    </button>
                </div>

                <div class="flex flex-wrap gap-2.5">
                    @if (!$classe)
                        <select wire:model.live="classe_id"
                            class="h-10 min-w-[160px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les classes</option>
                            @foreach ($this->classes as $cl)
                                <option value="{{ $cl->id }}">{{ $cl->code ?: $cl->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if (!$classe)
                        @if (!$serial && !$filiar && !$promotionModel)
                            <select wire:model.live="filiar_id"
                                class="h-10 min-w-[150px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                                <option value="">Toutes les filières</option>
                                @foreach ($this->filiars as $f)
                                    <option value="{{ $f->id }}">{{ $f->code ?: $f->name }}</option>
                                @endforeach
                            </select>
                            <select wire:model.live="serial_id"
                                class="h-10 min-w-[140px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                                <option value="">Toutes les séries</option>
                                @foreach ($this->serials as $s)
                                    <option value="{{ $s->id }}">{{ $s->code ?: $s->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if (!$promotion && !$promotionModel)
                            <select wire:model.live="promotionInGroups"
                                class="h-10 min-w-[160px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                                <option value="">Toutes les promotions</option>
                                @foreach ($this->promotions as $promo)
                                    <option value="{{ $promo }}">{{ $promo }}</option>
                                @endforeach
                            </select>
                        @endif
                    @endif

                    <select wire:model.live="gender"
                        class="h-10 min-w-[100px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                        <option value="">Sexe</option>
                        @foreach ($this->genders as $gk => $gdr)
                            <option value="{{ $gk }}">{{ $gdr }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="status"
                        class="h-10 min-w-[140px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                        <option value="">Tout statut</option>
                        <option value="actifs">Actifs</option>
                        <option value="desactives">Désactivés</option>
                        <option value="ayant de classe">Ayant de classe</option>
                        <option value="ayant abandonés">Déclarés abandons</option>
                        <option value="sans classe">Sans classe</option>
                        <option value="de la corbeille">Corbeille</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== LIST HEADER ===================== --}}
    <section class="mb-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-white">Liste</h2>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-violet-500/15 border border-violet-500/25 text-violet-300 text-xs font-medium tabular-nums">
                    {{ $this->students->total() }} apprenant{{ $this->students->total() > 1 ? 's' : '' }}
                </span>
            </div>
            <a wire:navigate href="{{ route('tenant.students.print.configuration') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/15 text-violet-300 border border-violet-500/20 hover:bg-violet-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-printer class="w-3.5 h-3.5" />
                Génération PDF personnalisée
            </a>
        </div>
    </section>

    {{-- ===================== LIST ===================== --}}
    <section class="relative mb-12">
        <div wire:loading
            wire:target="gender,classe_id,filiar_id,serial_id,promotionInGroups,resetFilters,search,previousPage,nextPage,gotoPage"
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

        @if (count($this->students))
            <div class="space-y-3">
                @foreach ($this->students as $student)
                    @php
                        $orderNumber = $this->students->firstItem() + $loop->iteration - 1;
                        $cl = $student->currentClasse();
                    @endphp

                    <article wire:key="student-{{ $student->id }}"
                        class="rounded-2xl bg-slate-950 shadow-xs shadow-purple-900 border border-white/5 hover:border-violet-500/20 transition-all overflow-hidden">
                        <div class="p-4 sm:p-5">
                            <div class="flex flex-col xl:flex-row gap-5">

                                {{-- N° + Identity --}}
                                <div class="flex gap-3.5 min-w-0 xl:w-[320px] shrink-0">
                                    <div class="flex items-start pt-1">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-violet-500/15 border border-violet-500/25 text-violet-300 text-xs font-bold tabular-nums">
                                            {{ __zero($orderNumber) }}
                                        </span>
                                    </div>

                                    <a href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                        class="flex gap-3 min-w-0 flex-1 group">
                                        <img src="{{ $student->profil_photo_url }}" alt=""
                                            class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/10 group-hover:ring-violet-500/40 transition-all shrink-0">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <h3
                                                    class="font-semibold text-white group-hover:text-violet-300 transition-colors truncate text-sm">
                                                    {{ $student->getFullName() }}
                                                </h3>
                                                @if ($student->gender)
                                                    <span
                                                        class="text-[10px] font-mono text-slate-500 uppercase shrink-0">
                                                        {{ str()->initials($student->gender) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-1 space-y-0.5 text-[11px] text-slate-500 font-mono">
                                                @if ($student->educMaster)
                                                    <p>{{ $student->educMaster }}</p>
                                                @endif
                                                @if ($student->matricule)
                                                    <p>#{{ $student->matricule }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                {{-- Classe + Naissance + Statut --}}
                                <div
                                    class="flex-1 min-w-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-3 xl:pt-0 xl:pl-5 flex flex-wrap items-start gap-4">
                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1">Classe</p>
                                        @if ($cl)
                                            <a wire:navigate
                                                href="{{ route('tenant.classe.profil', ['classe_slug' => $cl->classe->slug]) }}"
                                                class="inline-flex px-2.5 py-1 rounded-lg bg-sky-500/10 border border-sky-500/20 text-sky-300 text-xs font-mono hover:bg-sky-500/20 transition-all">
                                                {{ $cl->classe->code ?: $cl->classe->name }}
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-600 italic">
                                                Pas de classe · {{ $this->activeYear?->slug }}
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1">Naissance
                                        </p>
                                        <p class="text-xs text-slate-300">
                                            {{ ucwords(__formatDate($student->birth_date)) }}</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ getAge($student->birth_date) }}
                                            ans</p>
                                    </div>

                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1">Présence</p>
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded-full bg-white/5 text-slate-500 text-[11px]">
                                            En cours…
                                        </span>
                                    </div>

                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1">Statut</p>
                                        @if (!$student->checkIfStudentNotLeavedYet())
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-orange-500/10 text-orange-400 text-[11px] font-medium border border-orange-500/20">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                                                Abandonné
                                            </span>
                                        @elseif ($student->blocked)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-500/10 text-rose-400 text-[11px] font-medium border border-rose-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                Bloqué
                                            </span>
                                        @elseif ($student->is_active)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[11px] font-medium border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                Actif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/5 text-slate-400 text-[11px] font-medium border border-white/10">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Inactif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div
                                    class="xl:w-[140px] shrink-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-3 xl:pt-0 xl:pl-4 flex flex-col gap-2">
                                    @if ($student->checkIfStudentNotLeavedYet())
                                        <button wire:click="markStudentAsLeaved({{ $student->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="markStudentAsLeaved({{ $student->id }})"
                                            class="h-8 px-3 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="markStudentAsLeaved({{ $student->id }})">
                                                Abandonné
                                            </span>
                                            <span wire:loading wire:target="markStudentAsLeaved({{ $student->id }})">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>
                                    @else
                                        <button wire:click="reinsertIntoClasseAsActive({{ $student->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="reinsertIntoClasseAsActive({{ $student->id }})"
                                            class="h-8 px-3 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/20 text-sky-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="reinsertIntoClasseAsActive({{ $student->id }})">
                                                Réinsérer
                                            </span>
                                            <span wire:loading
                                                wire:target="reinsertIntoClasseAsActive({{ $student->id }})">
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
            @if ($this->students->hasPages())
                <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        Affichage {{ $this->students->firstItem() }} à {{ $this->students->lastItem() }}
                        sur {{ $this->students->total() }} apprenants
                    </p>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if (!$this->students->onFirstPage())
                            <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                                class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                ← Précédent
                            </button>
                        @endif
                        @foreach ($this->students->getUrlRange(1, $this->students->lastPage()) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})"
                                class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                           {{ $page === $this->students->currentPage()
                                               ? 'bg-violet-600 text-white'
                                               : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300' }}">
                                {{ $page }}
                            </button>
                        @endforeach
                        @if ($this->students->hasMorePages())
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
                <span class="text-4xl mb-4 block">🎓</span>
                <p class="text-slate-500 text-sm mb-4">Aucun apprenant trouvé</p>
                <button wire:click="resetFilters"
                    class="h-9 px-4 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all">
                    Réinitialiser les filtres
                </button>
            </div>
        @endif
    </section>

</div>

