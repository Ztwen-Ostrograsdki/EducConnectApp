<div class="w-full overflow-x-hidden">

    {{-- ===================== FILTERS ===================== --}}
    <section class="mb-6">
        <div class="rounded-2xl bg-indigo-950/35 shadow-xs shadow-indigo-900 border border-white/5 p-4 sm:p-5">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <input wire:model.live.debounce.400ms="search" type="text"
                            placeholder="Rechercher un enseignant…"
                            class="w-full h-11 rounded-xl bg-slate-950 border border-white/10 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30 transition-all">
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

                <div class="flex flex-wrap gap-2.5">
                    @if (!$subject)
                        <select wire:model.live="subject_id"
                            class="h-10 min-w-[160px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les matières</option>
                            @foreach ($this->subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->code ?: $sub->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="subject_type"
                            class="h-10 min-w-[160px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Tous types de matières</option>
                            @foreach ($this->subject_types as $subk => $sub)
                                <option value="{{ $sub }}">{{ $sub }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if (!$promotion && !$promotionModel)
                        <select wire:model.live="promotionInGroups"
                            class="h-10 min-w-[180px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Promotions groupées</option>
                            @foreach ($this->promotions as $kp => $n)
                                <option value="{{ $n }}">Promotion {{ $n }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if (!$classe)
                        <select wire:model.live="classe_id"
                            class="h-10 min-w-[160px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les classes</option>
                            @foreach ($this->classes as $cl)
                                <option value="{{ $cl->id }}">{{ $cl->code ?: $cl->name }}</option>
                            @endforeach
                        </select>

                        @if (!$serial && !$filiar && !$promotionModel)
                            <select wire:model.live="filiar_id"
                                class="h-10 min-w-[150px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                                <option value="">Toutes les filières</option>
                                @foreach ($this->filiars as $f)
                                    <option value="{{ $f->id }}">{{ $f->code ?: $f->name }}</option>
                                @endforeach
                            </select>
                            <select wire:model.live="serial_id"
                                class="h-10 min-w-[140px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                                <option value="">Toutes les séries</option>
                                @foreach ($this->serials as $s)
                                    <option value="{{ $s->id }}">{{ $s->code ?: $s->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    @endif

                    <select wire:model.live="department"
                        class="h-10 min-w-[130px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                        <option value="">Département</option>
                        @foreach ($this->departments as $dp => $dpv)
                            <option value="{{ $dpv }}">{{ $dpv }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="city"
                        class="h-10 min-w-[120px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                        <option value="">Ville</option>
                        @foreach ($this->cities as $ct => $ctv)
                            <option value="{{ $ctv }}">{{ $ctv }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="gender"
                        class="h-10 min-w-[100px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                        <option value="">Sexe</option>
                        @foreach ($this->genders as $gk => $gdr)
                            <option value="{{ $gk }}">{{ $gdr }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="status"
                        class="h-10 min-w-[120px] rounded-lg bg-slate-950 border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                        <option value="">Tout statut</option>
                        <option value="actives">Actifs</option>
                        <option value="desactives">Bloqués</option>
                        <option value="corbeille">Corbeille</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== DOCS + COUNT ===================== --}}
    <section class="mb-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <span
                class="inline-flex items-center px-3 py-1.5 rounded-full bg-violet-500/15 border border-violet-500/25 text-violet-300 text-sm font-medium tabular-nums">
                {{ __zero($this->teachers->total()) }} enseignant{{ $this->teachers->total() > 1 ? 's' : '' }}
            </span>

            <div class="flex flex-wrap gap-2">
                <a wire:navigate href="{{ route('tenant.teachers.docs') }}"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-sky-500/10 text-sky-300 border border-sky-500/20 hover:bg-sky-500/20 transition-all inline-flex items-center gap-1.5">
                    <x-lucide-file class="w-3.5 h-3.5" />
                    Documents PDF/Excel
                </a>
                <a href="{{ route('tenant.teachers.print.list') }}"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                    <x-lucide-eye class="w-3.5 h-3.5" />
                    Aperçu
                </a>
                <a href="{{ route('tenant.teachers.print.configuration') }}"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/10 text-violet-300 border border-violet-500/20 hover:bg-violet-500/20 transition-all inline-flex items-center gap-1.5">
                    <x-lucide-printer class="w-3.5 h-3.5" />
                    Génération PDF
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== LIST ===================== --}}
    <section class="relative mb-16">
        <div wire:loading wire:target="search,gender,status,subject_type,clearFilters,previousPage,nextPage,gotoPage"
            class="absolute inset-0 z-20 flex items-center justify-center bg-slate-950/70 rounded-2xl">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="animate-spin w-8 h-8 text-violet-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
                <span class="text-sm font-mono">Chargement…</span>
            </div>
        </div>

        @if ($this->teachers->isEmpty())
            <div class="rounded-2xl bg-[#121826] border border-white/5 py-20 text-center">
                <span class="text-4xl mb-4 block">👨‍🏫</span>
                <p class="text-slate-500 text-sm mb-4">Aucun enseignant trouvé</p>
                <button wire:click="clearFilters"
                    class="h-9 px-4 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-sm text-slate-300 transition-all">
                    <span wire:loading.remove wire:target="clearFilters">Réinitialiser les filtres</span>
                    <span wire:loading wire:target="clearFilters" class="inline-flex items-center gap-2">
                        <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                        En cours…
                    </span>
                </button>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($this->teachers as $teacher)
                    @php
                        $orderNumber = $this->teachers->firstItem() + $loop->iteration - 1;
                        $classess = $teacher->getTeacherClassesForThisSchoolYear();
                    @endphp

                    <article
                        class="rounded-2xl bg-slate-950 shadow-xs shadow-purple-700 border border-white/5 hover:border-violet-500/20 transition-all overflow-hidden">
                        <div class="p-4 sm:p-5">
                            <div class="flex flex-col xl:flex-row gap-5">

                                {{-- N° + Identity --}}
                                <div class="flex gap-3.5 min-w-0 xl:w-[300px] shrink-0">
                                    <div class="flex items-start pt-1">
                                        <span
                                            class="inline-flex items-center justify-center px-2 h-8 rounded-lg bg-violet-500/15 border border-violet-500/25 text-violet-300 text-xs font-bold tabular-nums">
                                            N° {{ __zero($orderNumber) }}
                                        </span>
                                    </div>

                                    <a href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                        class="flex gap-3 min-w-0 flex-1 group">
                                        <img src="{{ $teacher->user->profil_photo_url }}" alt=""
                                            class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/10 group-hover:ring-violet-500/40 transition-all shrink-0">
                                        <div class="min-w-0">
                                            <h3
                                                class="font-semibold text-white group-hover:text-violet-300 transition-colors truncate text-sm">
                                                {{ $teacher->getFullName() }}
                                            </h3>
                                            <div class="mt-1 space-y-0.5 text-[11px] text-slate-500">
                                                <p class="font-mono">ID: {{ $teacher->identifiant }}</p>
                                                @if ($teacher->user?->contacts)
                                                    <p class="flex items-center gap-1">
                                                        <x-lucide-phone class="w-3 h-3 shrink-0" />
                                                        {{ $teacher->user->contacts }}
                                                    </p>
                                                @endif
                                            </div>

                                            @if ($classe)
                                                <p class="mt-1.5 text-[10px] text-amber-400/80 font-mono">
                                                    Inséré le
                                                    {{ __formatDate($teacher->classeSubjects->first()->started_at) }}
                                                </p>
                                                @if ($teacher->cannotAccessIntoClasse($classe->id))
                                                    <span
                                                        class="mt-1.5 inline-flex px-2 py-0.5 rounded-md bg-rose-500/15 border border-rose-500/25 text-rose-400 text-[10px] font-medium animate-pulse">
                                                        Accès classe bloqué
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </a>
                                </div>

                                {{-- Matières + Classes --}}
                                <div
                                    class="flex-1 min-w-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-3 xl:pt-0 xl:pl-5 space-y-3">
                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">
                                            Matières
                                            @if ($classe)
                                                <span class="text-slate-600 normal-case">· cette classe</span>
                                            @endif
                                        </p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @if ($classe)
                                                @forelse ($teacher->getSubjectsForThisClasse($classe->id) as $subjectRelation)
                                                    <span
                                                        class="px-2.5 py-1 rounded-lg bg-indigo-500/15 border border-indigo-500/25 text-indigo-300 text-[11px] font-mono uppercase">
                                                        {{ $subjectRelation->subject->code }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-slate-600 italic">Aucune</span>
                                                @endforelse
                                            @else
                                                @forelse ($teacher->getYearlySubjects() as $yearly_subject)
                                                    <span
                                                        class="px-2.5 py-1 rounded-lg bg-indigo-500/15 border border-indigo-500/25 text-indigo-300 text-[11px] font-mono uppercase">
                                                        {{ $yearly_subject->subject->code }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-slate-600 italic">Aucune</span>
                                                @endforelse
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">Autres
                                            classes</p>
                                        @if (count($classess))
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach ($classess as $cl)
                                                    <span
                                                        class="px-2.5 py-1 rounded-lg bg-sky-500/10 border border-sky-500/20 text-sky-300 text-[11px] font-mono uppercase">
                                                        {{ $cl?->code ?: $cl->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-600 italic">Aucune autre classe</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div
                                    class="xl:w-[180px] shrink-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-3 xl:pt-0 xl:pl-4 flex flex-col gap-2">
                                    <a wire:navigate
                                        href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                        class="h-8 px-3 rounded-lg bg-violet-500/15 hover:bg-violet-500/25 border border-violet-500/20 text-violet-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all">
                                        <x-lucide-user class="w-3.5 h-3.5" />
                                        Profil
                                    </a>

                                    @if ($teacher->user)
                                        <button wire:click="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                            class="h-8 px-3 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/20 text-sky-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                class="inline-flex items-center gap-1.5">
                                                <x-lucide-send class="w-3.5 h-3.5" /> Envoyer
                                            </span>
                                            <span wire:loading
                                                wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>
                                    @endif

                                    <button
                                        wire:click="{{ $teacher->is_locked ? 'unlockTeacher(' . $teacher->id . ')' : 'lockTeacher(' . $teacher->id . ')' }}"
                                        wire:loading.attr="disabled" wire:target="lockTeacher, unlockTeacher"
                                        class="h-8 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                   {{ $teacher->is_locked
                                                       ? 'bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-300'
                                                       : 'bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-300' }}">
                                        <span wire:loading.remove wire:target="lockTeacher, unlockTeacher"
                                            class="inline-flex items-center gap-1.5">
                                            @if ($teacher->is_locked)
                                                <x-lucide-unlock class="w-3.5 h-3.5" /> Débloquer
                                            @else
                                                <x-lucide-user-lock class="w-3.5 h-3.5" /> Bloquer
                                            @endif
                                        </span>
                                        <span wire:loading wire:target="lockTeacher, unlockTeacher">
                                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>

                                    @if ($classe)
                                        <button
                                            wire:click="{{ $teacher->cannotAccessIntoClasse($classe->id)
                                                ? 'unLockAccessToClasse(' . $teacher->id . ',' . $classe->id . ')'
                                                : 'lockAccessToClasse(' . $teacher->id . ',' . $classe->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="lockAccessToClasse, unLockAccessToClasse"
                                            class="h-8 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ $teacher->cannotAccessIntoClasse($classe->id)
                                                           ? 'bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-300'
                                                           : 'bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300' }}">
                                            <span wire:loading.remove
                                                wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($teacher->cannotAccessIntoClasse($classe->id))
                                                    <x-lucide-check class="w-3.5 h-3.5" /> Déverrouiller
                                                @else
                                                    <x-lucide-user-lock class="w-3.5 h-3.5" /> Verrouiller
                                                @endif
                                            </span>
                                            <span wire:loading wire:target="lockAccessToClasse, unLockAccessToClasse">
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
            @if ($this->teachers->hasPages())
                <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        Affichage {{ $this->teachers->firstItem() }} à {{ $this->teachers->lastItem() }}
                        sur {{ $this->teachers->total() }} enseignants
                    </p>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if (!$this->teachers->onFirstPage())
                            <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                                class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                ← Précédent
                            </button>
                        @endif
                        @foreach ($this->teachers->getUrlRange(1, $this->teachers->lastPage()) as $page => $url)
                            <button @disabled($page === $this->teachers->currentPage()) wire:click="gotoPage({{ $page }})"
                                class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                           {{ $page === $this->teachers->currentPage()
                                               ? 'bg-violet-600 text-white'
                                               : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300' }}">
                                {{ $page }}
                            </button>
                        @endforeach
                        @if ($this->teachers->hasMorePages())
                            <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                                Suivant →
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </section>

</div>

