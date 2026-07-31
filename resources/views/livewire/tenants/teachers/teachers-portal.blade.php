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
                        Enseignants
                    </h1>
                    <p class="mt-2 text-slate-400 text-sm max-w-lg">
                        Vue globale du personnel enseignant de l’établissement
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="hidden sm:flex flex-col items-end mr-2">
                        <span
                            class="text-2xl font-semibold text-white tabular-nums">{{ $this->teachers->total() }}</span>
                        <span class="text-xs text-slate-500 uppercase tracking-wider">Enseignants</span>
                    </div>

                    <button wire:click="printTeachersList"
                        class="h-11 px-5 rounded-full bg-sky-500/20 hover:bg-sky-500/30 border border-sky-500/30 text-sky-300 text-sm font-medium transition-all active:scale-[0.97]">
                        <span wire:loading.remove wire:target="printTeachersList"
                            class="inline-flex items-center gap-2">
                            <x-lucide-save class="w-4 h-4" />
                            Exporter PDF
                        </span>
                        <span wire:loading wire:target="printTeachersList" class="inline-flex items-center gap-2">
                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            Document…
                        </span>
                    </button>

                    <a href="{{ route('tenant.teachers.create') }}"
                        class="h-11 px-5 rounded-full bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium shadow-lg shadow-violet-900/30 transition-all active:scale-[0.97] inline-flex items-center gap-2">
                        <x-lucide-user-plus class="w-4 h-4" />
                        Ajouter enseignant
                    </a>

                    @if ($doc = \App\Models\GeneratedDocument::ofType('teacher_list')->forUser(auth()->id())->latest()->first())
                        <div class="relative">
                            <button wire:click="trackDownload({{ $doc->id }})"
                                class="h-11 px-5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium transition-all active:scale-[0.97]">
                                <span wire:loading.remove wire:target="trackDownload({{ $doc->id }})"
                                    class="inline-flex items-center gap-2">
                                    <x-lucide-download class="w-4 h-4" />
                                    Télécharger liste
                                    @if ($doc->downloaded_count > 0)
                                        <span class="text-xs opacity-70">({{ $doc->downloaded_count }}×)</span>
                                    @endif
                                </span>
                                <span wire:loading wire:target="trackDownload({{ $doc->id }})"
                                    class="inline-flex items-center gap-2">
                                    <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                    …
                                </span>
                            </button>
                            @if (!$doc->downloaded)
                                <span wire:loading.remove wire:target="trackDownload({{ $doc->id }})"
                                    class="absolute -top-2 -right-2 text-[10px] font-bold bg-emerald-400 text-emerald-950 px-1.5 py-0.5 rounded-full animate-pulse">
                                    Nouveau
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </header>

        {{-- ===================== FILTERS ===================== --}}
        <section class="mb-8">
            <div class="rounded-2xl bg-[#121826] border border-white/5 p-5">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input wire:model.live.debounce.400ms="search" type="text"
                                placeholder="Rechercher un enseignant…"
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

                    <div class="flex flex-wrap gap-2.5">
                        <select wire:model.live="subject_id"
                            class="h-10 min-w-[160px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les matières</option>
                            @foreach ($this->subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->code ?: $sub->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="promotionInGroups"
                            class="h-10 min-w-[180px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Promotions groupées</option>
                            @foreach ($this->promotionsGrouped as $kp => $n)
                                <option value="{{ $n }}">Promotion {{ $n }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="classe_id"
                            class="h-10 min-w-[160px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les classes</option>
                            @foreach ($this->classes as $cl)
                                <option value="{{ $cl->id }}">{{ $cl->code ?: $cl->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filiar_id"
                            class="h-10 min-w-[150px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Toutes les filières</option>
                            @foreach ($this->filiars as $f)
                                <option value="{{ $f->id }}">{{ $f->code ?: $f->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="promotion_id"
                            class="h-10 min-w-[180px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Promotions spécifiques</option>
                            @foreach ($this->promotions as $promo)
                                <option value="{{ $promo->id }}">{{ $promo->code ?: $promo->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="department"
                            class="h-10 min-w-[130px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Département</option>
                            @foreach ($this->departments as $dp => $dpv)
                                <option value="{{ $dpv }}">{{ $dpv }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="city"
                            class="h-10 min-w-[120px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Ville</option>
                            @foreach ($this->cities as $ct => $ctv)
                                <option value="{{ $ctv }}">{{ $ctv }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="gender"
                            class="h-10 min-w-[100px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Sexe</option>
                            @foreach ($this->genders as $gk => $gdr)
                                <option value="{{ $gk }}">{{ $gdr }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="status"
                            class="h-10 min-w-[120px] rounded-lg bg-[#0b0f19] border border-white/10 px-3 text-xs text-slate-300 focus:outline-none focus:border-violet-500/40">
                            <option value="">Tout statut</option>
                            <option value="actives">Actifs</option>
                            <option value="desactives">Bloqués</option>
                            <option value="corbeille">Corbeille</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== DOCS LINKS ===================== --}}
        <section class="mb-6">
            <div class="flex flex-wrap gap-2">
                <a wire:navigate href="{{ route('tenant.teachers.docs') }}"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-sky-500/10 text-sky-300 border border-sky-500/20 hover:bg-sky-500/20 transition-all inline-flex items-center gap-1.5">
                    <x-lucide-file class="w-3.5 h-3.5" />
                    Documents PDF/Excel
                </a>
                <a href="{{ route('tenant.teachers.print.list') }}"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                    <x-lucide-eye class="w-3.5 h-3.5" />
                    Aperçu document
                </a>
                <a href="{{ route('tenant.teachers.print.configuration') }}"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/10 text-violet-300 border border-violet-500/20 hover:bg-violet-500/20 transition-all inline-flex items-center gap-1.5">
                    <x-lucide-printer class="w-3.5 h-3.5" />
                    Génération PDF dynamique
                </a>
            </div>
        </section>

        {{-- ===================== ALERT ACCÈS ===================== --}}
        @php
            $unaccesses = tenancy()->tenant?->getTeachersWithoutYearlyAccesses();
        @endphp
        @if (count($unaccesses))
            <div class="mb-6 rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-300">
                <p class="font-medium">
                    {{ __zero(count($unaccesses)) }} enseignant(s) sans accès pour
                    {{ $this->activeYear?->slug ?? 'cette année' }}
                </p>
                <p class="mt-1 text-xs text-rose-300/70">
                    Accordez-leur les accès, sinon vous ne pourrez ni définir leurs matières ni leur attribuer de
                    classe.
                </p>
            </div>
        @endif

        {{-- ===================== BULK ACTIONS ===================== --}}
        <section class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button wire:click="giveAccessesToTeachersForThisSchoolYear" wire:loading.attr="disabled"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/10 text-violet-300 border border-violet-500/20 hover:bg-violet-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="giveAccessesToTeachersForThisSchoolYear"
                        class="inline-flex items-center gap-1.5">
                        <x-lucide-user-key class="w-3.5 h-3.5" /> Accorder accès
                    </span>
                    <span wire:loading wire:target="giveAccessesToTeachersForThisSchoolYear"
                        class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> …
                    </span>
                </button>
                <button wire:click="unlockTeachers" wire:loading.attr="disabled"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="unlockTeachers" class="inline-flex items-center gap-1.5">
                        <x-lucide-lock-keyhole-open class="w-3.5 h-3.5" /> Débloquer tous
                    </span>
                    <span wire:loading wire:target="unlockTeachers" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> …
                    </span>
                </button>
                <button wire:click="lockTeachers" wire:loading.attr="disabled"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-amber-500/10 text-amber-300 border border-amber-500/20 hover:bg-amber-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="lockTeachers" class="inline-flex items-center gap-1.5">
                        <x-lucide-ban class="w-3.5 h-3.5" /> Bloquer tous
                    </span>
                    <span wire:loading wire:target="lockTeachers" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> …
                    </span>
                </button>
                <button wire:click="restoreTeachers" wire:loading.attr="disabled"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/10 text-violet-300 border border-violet-500/20 hover:bg-violet-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="restoreTeachers" class="inline-flex items-center gap-1.5">
                        <x-lucide-recycle class="w-3.5 h-3.5" /> Restaurer tous
                    </span>
                    <span wire:loading wire:target="restoreTeachers" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> …
                    </span>
                </button>
                <button wire:click="forceDeleteTeachers" wire:loading.attr="disabled"
                    class="h-9 px-3.5 rounded-lg text-xs font-medium bg-rose-500/10 text-rose-300 border border-rose-500/20 hover:bg-rose-500/20 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="forceDeleteTeachers"
                        class="inline-flex items-center gap-1.5">
                        <x-lucide-trash-2 class="w-3.5 h-3.5" /> Suppr. déf. tous
                    </span>
                    <span wire:loading wire:target="forceDeleteTeachers" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" /> …
                    </span>
                </button>
            </div>
        </section>

        {{-- ===================== LIST ===================== --}}
        <section class="relative mb-16">
            <div wire:loading
                wire:target="gender,status,promotionInGroups,department,city,restoreTeachers,unlockTeachers,giveAccessesToTeachersForThisSchoolYear,lockTeachers,clearFilters,subject_id,classe_id,promotion_id,filiar_id,forceDeleteTeachers,search,previousPage,nextPage,gotoPage"
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

            @if (!$this->teachers->isEmpty())
                <div class="space-y-4">
                    @foreach ($this->teachers as $teacher)
                        <article wire:key="liste-enseignants-du-portail-{{ $teacher->id }}"
                            class="rounded-2xl bg-[#121826] border border-white/5 hover:border-violet-500/20 transition-all overflow-hidden">
                            <div class="p-5 sm:p-6">
                                <div class="flex flex-col xl:flex-row gap-6">

                                    {{-- IDENTITY --}}
                                    <div class="flex gap-4 min-w-0 xl:w-[300px] shrink-0">
                                        <a href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                            class="shrink-0">
                                            <img src="{{ $teacher->profil_photo_url() }}"
                                                alt="{{ $teacher->fullName() }}"
                                                class="w-16 h-16 rounded-2xl object-cover ring-2 ring-white/10 hover:ring-violet-500/40 transition-all">
                                        </a>
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                                class="block font-semibold text-white hover:text-violet-300 transition-colors truncate">
                                                {{ $teacher->getFullName() }}
                                                @if ($teacher->user->gender)
                                                    <span class="ml-1 text-[10px] font-mono text-slate-500 uppercase">
                                                        {{ str()->initials($teacher->user->gender) }}
                                                    </span>
                                                @endif
                                            </a>
                                            <div class="mt-2 space-y-1 text-xs text-slate-400">
                                                <p class="flex items-center gap-1.5 truncate">
                                                    <x-lucide-mail class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                                    {{ $teacher->user->email }}
                                                </p>
                                                <p class="flex items-center gap-1.5 truncate">
                                                    <x-lucide-phone class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                                    {{ $teacher->user->contacts }}
                                                </p>
                                            </div>

                                            <div class="mt-3">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium border
                                                    {{ $teacher->hasValidAccessForYear()
                                                        ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                                                        : 'bg-rose-500/10 text-rose-400 border-rose-500/20 animate-pulse' }}">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full {{ $teacher->hasValidAccessForYear() ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                                    Accès {{ tenancy()->tenant?->getActiveSchoolYear()?->slug }}
                                                    {{ $teacher->hasValidAccessForYear() ? 'accordé' : 'non accordé' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- MATIÈRES + CLASSES --}}
                                    <div
                                        class="flex-1 min-w-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-6 space-y-4">
                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-2">
                                                Matières</p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @forelse ($teacher->getYearlySubjects() as $yearly_subject)
                                                    <span
                                                        class="px-2.5 py-1 rounded-lg bg-indigo-500/15 border border-indigo-500/25 text-indigo-300 text-[11px] font-mono uppercase">
                                                        {{ $yearly_subject->subject->code }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-slate-600 italic">Aucune matière</span>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-2">Classes
                                            </p>
                                            @php $teacher_classes = $teacher->getTeacherClassesForThisSchoolYear([]); @endphp
                                            @if (count($teacher_classes))
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach ($teacher_classes as $cl)
                                                        <span
                                                            class="px-2.5 py-1 rounded-lg bg-sky-500/10 border border-sky-500/20 text-sky-300 text-[11px] font-mono uppercase">
                                                            {{ $cl?->code ?? $cl->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-600 italic">Aucune classe
                                                    assignée</span>
                                            @endif
                                        </div>

                                        <div class="text-xs text-slate-600">
                                            Heures/sem. : <span class="text-slate-400">—</span>
                                        </div>
                                    </div>

                                    {{-- ACTIONS --}}
                                    <div
                                        class="xl:w-[220px] shrink-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-4 xl:pt-0 xl:pl-5 flex flex-col gap-2">
                                        @if ($teacher->hasValidAccessForYear())
                                            <a wire:navigate
                                                href="{{ route('tenant.teacher.manage.subjects', ['teacher_uuid' => $teacher->uuid]) }}"
                                                class="h-9 px-3 rounded-lg bg-indigo-500/15 hover:bg-indigo-500/25 border border-indigo-500/20 text-indigo-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all">
                                                ⚙️ Gérer les matières
                                            </a>
                                        @endif

                                        @if (!$teacher->user->blocked)
                                            <button
                                                wire:click="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                class="h-9 px-3 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/20 text-sky-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                                <span wire:loading.remove
                                                    wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                    class="inline-flex items-center gap-1.5">
                                                    <x-lucide-send class="w-3.5 h-3.5" /> Envoyer identifiants
                                                </span>
                                                <span wire:loading
                                                    wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')">
                                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>
                                        @endif

                                        <button
                                            wire:click="{{ $teacher->blocked ? 'unlockTeacher(' . $teacher->id . ')' : 'lockTeacher(' . $teacher->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ $teacher->blocked
                                                           ? 'bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-300'
                                                           : 'bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-300' }}">
                                            <span wire:loading.remove
                                                wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($teacher->blocked)
                                                    <x-lucide-lock-keyhole-open class="w-3.5 h-3.5" /> Débloquer prof
                                                @else
                                                    <x-lucide-ban class="w-3.5 h-3.5" /> Bloquer prof
                                                @endif
                                            </span>
                                            <span wire:loading
                                                wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        <button
                                            wire:click="{{ $teacher->user->blocked ? 'unlockUser(' . $teacher->user->id . ')' : 'lockUser(' . $teacher->user->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="lockUser({{ $teacher->user->id }}), unlockUser({{ $teacher->user->id }})"
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ $teacher->user->blocked
                                                           ? 'bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/20 text-indigo-300'
                                                           : 'bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300' }}">
                                            <span wire:loading.remove
                                                wire:target="lockUser({{ $teacher->user->id }}), unlockUser({{ $teacher->user->id }})"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($teacher->user->blocked)
                                                    <x-lucide-unlock class="w-3.5 h-3.5" /> Débloquer compte
                                                @else
                                                    <x-lucide-user-lock class="w-3.5 h-3.5" /> Bloquer compte
                                                @endif
                                            </span>
                                            <span wire:loading
                                                wire:target="lockUser({{ $teacher->user->id }}), unlockUser({{ $teacher->user->id }})">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        @if (!$teacher->deleted_at)
                                            <button
                                                wire:click="{{ $teacher->hasValidAccessForYear() ? 'removeAccessForThisSchoolYear(' . $teacher->id . ')' : 'giveAccessForThisSchoolYear(' . $teacher->id . ')' }}"
                                                wire:loading.attr="disabled"
                                                wire:target="giveAccessForThisSchoolYear({{ $teacher->id }}), removeAccessForThisSchoolYear({{ $teacher->id }})"
                                                class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                           {{ $teacher->hasValidAccessForYear()
                                                               ? 'bg-orange-500/10 hover:bg-orange-500/20 border border-orange-500/20 text-orange-300'
                                                               : 'bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-300' }}">
                                                <span wire:loading.remove
                                                    wire:target="giveAccessForThisSchoolYear({{ $teacher->id }}), removeAccessForThisSchoolYear({{ $teacher->id }})"
                                                    class="inline-flex items-center gap-1.5">
                                                    @if ($teacher->hasValidAccessForYear())
                                                        <x-lucide-user-key class="w-3.5 h-3.5" /> Retirer accès
                                                    @else
                                                        <x-lucide-key class="w-3.5 h-3.5" /> Accorder accès
                                                    @endif
                                                </span>
                                                <span wire:loading
                                                    wire:target="giveAccessForThisSchoolYear({{ $teacher->id }}), removeAccessForThisSchoolYear({{ $teacher->id }})">
                                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>
                                        @endif

                                        <button
                                            wire:click="{{ $teacher->deleted_at ? 'restoreTeacher(' . $teacher->id . ')' : 'deleteTeacher(' . $teacher->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="deleteTeacher({{ $teacher->id }}), restoreTeacher({{ $teacher->id }})"
                                            class="h-9 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50
                                                       {{ $teacher->deleted_at
                                                           ? 'bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-300'
                                                           : 'bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300' }}">
                                            <span wire:loading.remove
                                                wire:target="deleteTeacher({{ $teacher->id }}), restoreTeacher({{ $teacher->id }})"
                                                class="inline-flex items-center gap-1.5">
                                                @if ($teacher->deleted_at)
                                                    <x-lucide-recycle class="w-3.5 h-3.5" /> Restaurer
                                                @else
                                                    <x-lucide-trash class="w-3.5 h-3.5" /> Corbeille
                                                @endif
                                            </span>
                                            <span wire:loading
                                                wire:target="deleteTeacher({{ $teacher->id }}), restoreTeacher({{ $teacher->id }})">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>

                                        @if ($teacher->deleted_at)
                                            <button wire:click="forceDeleteTeacher({{ $teacher->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="forceDeleteTeacher({{ $teacher->id }})"
                                                class="h-9 px-3 rounded-lg bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-300 text-xs font-medium flex items-center justify-center gap-1.5 transition-all disabled:opacity-50">
                                                <span wire:loading.remove
                                                    wire:target="forceDeleteTeacher({{ $teacher->id }})"
                                                    class="inline-flex items-center gap-1.5">
                                                    <x-lucide-trash-2 class="w-3.5 h-3.5" /> Suppr. déf.
                                                </span>
                                                <span wire:loading
                                                    wire:target="forceDeleteTeacher({{ $teacher->id }})">
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
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
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
            @else
                <div class="rounded-2xl bg-[#121826] border border-white/5 py-20 text-center">
                    <span class="text-4xl mb-4 block">👨‍🏫</span>
                    <p class="text-slate-500 text-sm mb-4">Aucun enseignant trouvé</p>
                    @if ($search || $status || $classe_id || $subject_id || $promotion_id || $filiar_id || $gender || $city || $department)
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
