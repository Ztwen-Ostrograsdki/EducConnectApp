<div class="min-h-screen bg-[#070b14] text-slate-100 overflow-x-hidden">
    <div class="mx-auto w-full max-w-[1400px] px-3 sm:px-4 lg:px-6 py-6 space-y-6">

        {{-- ════════════════ HEADER PROFIL ════════════════ --}}
        <section>
            <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">

                {{-- Cover léger --}}
                <div class="relative h-24 sm:h-28 bg-gradient-to-r from-indigo-950/80 via-slate-900 to-violet-950/60">
                    <div
                        class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-500/15 via-transparent to-transparent">
                    </div>
                    <div class="absolute bottom-0 inset-x-0 h-12 bg-gradient-to-t from-[#0f1523] to-transparent"></div>
                </div>

                <div class="relative px-5 sm:px-7 pb-6 -mt-12">
                    <div class="flex flex-col sm:flex-row gap-5 sm:gap-6 items-center sm:items-end">

                        {{-- Avatar --}}
                        <div class="relative shrink-0">
                            <div
                                class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-sky-400 opacity-40 blur-sm">
                            </div>
                            <div
                                class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-2xl ring-4 ring-[#0f1523] overflow-hidden shadow-2xl bg-[#070b14]">
                                <img src="{{ $this->teacher->user->profil_photo_url }}"
                                    alt="{{ $this->teacher->user->getFullName() }}" class="w-full h-full object-cover">
                            </div>
                            <span class="absolute bottom-1.5 right-1.5 flex h-4 w-4">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-40"></span>
                                <span
                                    class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 ring-2 ring-[#0f1523]"></span>
                            </span>
                        </div>

                        {{-- Identité --}}
                        <div class="flex-1 min-w-0 text-center sm:text-left pb-1">
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                                    {{ $this->teacher->user->getFullName(true) }}
                                </h1>
                                <span class="inline-flex gap-3 items-center">
                                    @foreach ($this->user->roles as $r)
                                        <span
                                            class="px-2.5 py-0.5 rounded-full bg-indigo-500/15 border border-indigo-500/25 text-indigo-300 text-[11px] font-semibold">
                                            {{ $r->name }}
                                        </span>
                                    @endforeach
                                </span>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-500 font-mono">
                                ID · {{ $this->teacher->identifiant }}
                            </p>
                        </div>
                    </div>

                    {{-- Mini infos --}}
                    <div class="mt-5 grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        <div class="rounded-xl bg-[#070b14] border border-white/[0.04] px-3.5 py-3">
                            <p class="text-[10px] uppercase tracking-wider text-slate-600">Téléphone</p>
                            <p class="mt-1 text-sm font-medium text-slate-200 truncate">
                                {{ $this->teacher->user->contacts ?? '—' }}</p>
                        </div>
                        <div class="rounded-xl bg-[#070b14] border border-white/[0.04] px-3.5 py-3">
                            <p class="text-[10px] uppercase tracking-wider text-slate-600">Expérience</p>
                            <p class="mt-1 text-sm font-medium text-slate-200">12 ans</p>
                        </div>
                        <div class="rounded-xl bg-[#070b14] border border-white/[0.04] px-3.5 py-3">
                            <p class="text-[10px] uppercase tracking-wider text-slate-600">Statut</p>
                            <p class="mt-1 text-sm font-medium text-emerald-400">Actif</p>
                        </div>
                        <div class="rounded-xl bg-[#070b14] border border-white/[0.04] px-3.5 py-3">
                            <p class="text-[10px] uppercase tracking-wider text-slate-600">Email</p>
                            <p class="mt-1 text-sm font-medium text-slate-200 truncate">
                                {{ $this->teacher->user?->email ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- Matières --}}
                    <div class="mt-4 pt-4 border-t border-white/[0.04]">
                        <p class="text-[10px] uppercase tracking-wider text-slate-600 font-semibold mb-2.5">
                            Matières · Spécialités
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($this->teacher->getYearlySubjects() as $yearly_subject)
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-mono">
                                    {{ $yearly_subject->subject->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-600 italic">Non spécifiées</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ════════════════ ACTIONS ════════════════ --}}
        <section>
            <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-4 sm:p-5 shadow-xl shadow-black/10">
                <div class="flex items-center gap-2 mb-4">
                    <span
                        class="w-8 h-8 rounded-lg bg-indigo-500/15 border border-indigo-500/20 flex items-center justify-center">
                        <x-lucide-zap class="w-4 h-4 text-indigo-400" />
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Actions</h2>
                        <p class="text-[11px] text-slate-500">Gestion du profil et des accès</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($this->user->parent)
                        <a wire:navigate
                            href="{{ route('tenant.parent.profil', ['parent_uuid' => $this->user->parent->uuid]) }}"
                            class="h-9 px-3.5 rounded-xl bg-gray-500/15 hover:bg-gray-500/25 border border-gray-500/20 text-gray-300 text-xs font-medium transition-all inline-flex items-center gap-1.5">
                            <x-lucide-user class="w-3.5 h-3.5" />
                            Voir profil parent
                        </a>
                    @endif
                    @if ($this->teacher->hasValidAccessForYear())
                        <a wire:navigate
                            href="{{ route('tenant.teacher.manage.subjects', ['teacher_uuid' => $this->teacher->uuid]) }}"
                            class="h-9 px-3.5 rounded-xl bg-indigo-500/15 hover:bg-indigo-500/25 border border-indigo-500/20 text-indigo-300 text-xs font-medium transition-all inline-flex items-center gap-1.5">
                            <x-lucide-book-open class="w-3.5 h-3.5" />
                            Matières
                        </a>
                    @endif

                    @if (!$this->teacher->user->blocked)
                        <button wire:click="sendCredentialsToTeacher('{{ $this->teacher->user->uuid }}')"
                            wire:loading.attr="disabled"
                            wire:target="sendCredentialsToTeacher('{{ $this->teacher->user->uuid }}')"
                            class="h-9 px-3.5 rounded-xl bg-sky-500/15 hover:bg-sky-500/25 border border-sky-500/20 text-sky-300 text-xs font-medium transition-all disabled:opacity-50 inline-flex items-center gap-1.5">
                            <span wire:loading.remove
                                wire:target="sendCredentialsToTeacher('{{ $this->teacher->user->uuid }}')"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-send class="w-3.5 h-3.5" />
                                Envoyer accès
                            </span>
                            <span wire:loading
                                wire:target="sendCredentialsToTeacher('{{ $this->teacher->user->uuid }}')">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    @endif

                    <button
                        wire:click="{{ $this->teacher->blocked ? 'unlockTeacher(' . $this->teacher->id . ')' : 'lockTeacher(' . $this->teacher->id . ')' }}"
                        wire:loading.attr="disabled"
                        wire:target="lockTeacher({{ $this->teacher->id }}), unlockTeacher({{ $this->teacher->id }})"
                        class="h-9 px-3.5 rounded-xl text-xs font-medium transition-all disabled:opacity-50 inline-flex items-center gap-1.5
                                   {{ $this->teacher->blocked
                                       ? 'bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/20 text-emerald-300'
                                       : 'bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/20 text-amber-300' }}">
                        <span wire:loading.remove
                            wire:target="lockTeacher({{ $this->teacher->id }}), unlockTeacher({{ $this->teacher->id }})"
                            class="inline-flex items-center gap-1.5">
                            @if ($this->teacher->blocked)
                                <x-lucide-lock-open class="w-3.5 h-3.5" /> Débloquer prof
                            @else
                                <x-lucide-ban class="w-3.5 h-3.5" /> Bloquer prof
                            @endif
                        </span>
                        <span wire:loading
                            wire:target="lockTeacher({{ $this->teacher->id }}), unlockTeacher({{ $this->teacher->id }})">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>

                    <button
                        wire:click="{{ $this->teacher->user->blocked ? 'unlockUser(' . $this->teacher->user->id . ')' : 'lockUser(' . $this->teacher->user->id . ')' }}"
                        wire:loading.attr="disabled"
                        wire:target="lockUser({{ $this->teacher->user->id }}), unlockUser({{ $this->teacher->user->id }})"
                        class="h-9 px-3.5 rounded-xl text-xs font-medium transition-all disabled:opacity-50 inline-flex items-center gap-1.5
                                   {{ $this->teacher->user->blocked
                                       ? 'bg-indigo-500/15 hover:bg-indigo-500/25 border border-indigo-500/20 text-indigo-300'
                                       : 'bg-rose-500/15 hover:bg-rose-500/25 border border-rose-500/20 text-rose-300' }}">
                        <span wire:loading.remove
                            wire:target="lockUser({{ $this->teacher->user->id }}), unlockUser({{ $this->teacher->user->id }})"
                            class="inline-flex items-center gap-1.5">
                            @if ($this->teacher->user->blocked)
                                <x-lucide-unlock class="w-3.5 h-3.5" /> Débloquer compte
                            @else
                                <x-lucide-user-lock class="w-3.5 h-3.5" /> Bloquer compte
                            @endif
                        </span>
                        <span wire:loading
                            wire:target="lockUser({{ $this->teacher->user->id }}), unlockUser({{ $this->teacher->user->id }})">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>

                    @if (!$this->teacher->deleted_at)
                        <button
                            wire:click="{{ $this->teacher->hasValidAccessForYear() ? 'removeAccessForThisSchoolYear(' . $this->teacher->id . ')' : 'giveAccessForThisSchoolYear(' . $this->teacher->id . ')' }}"
                            wire:loading.attr="disabled"
                            wire:target="giveAccessForThisSchoolYear({{ $this->teacher->id }}), removeAccessForThisSchoolYear({{ $this->teacher->id }})"
                            class="h-9 px-3.5 rounded-xl text-xs font-medium transition-all disabled:opacity-50 inline-flex items-center gap-1.5
                                       {{ $this->teacher->hasValidAccessForYear()
                                           ? 'bg-orange-500/15 hover:bg-orange-500/25 border border-orange-500/20 text-orange-300'
                                           : 'bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/20 text-emerald-300' }}">
                            <span wire:loading.remove
                                wire:target="giveAccessForThisSchoolYear({{ $this->teacher->id }}), removeAccessForThisSchoolYear({{ $this->teacher->id }})"
                                class="inline-flex items-center gap-1.5">
                                @if ($this->teacher->hasValidAccessForYear())
                                    <x-lucide-key class="w-3.5 h-3.5" /> Retirer accès
                                @else
                                    <x-lucide-key-round class="w-3.5 h-3.5" /> Accorder accès
                                @endif
                            </span>
                            <span wire:loading
                                wire:target="giveAccessForThisSchoolYear({{ $this->teacher->id }}), removeAccessForThisSchoolYear({{ $this->teacher->id }})">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    @endif

                    <button
                        wire:click="{{ $this->teacher->deleted_at ? 'restoreTeacher(' . $this->teacher->id . ')' : 'deleteTeacher(' . $this->teacher->id . ')' }}"
                        wire:loading.attr="disabled"
                        wire:target="deleteTeacher({{ $this->teacher->id }}), restoreTeacher({{ $this->teacher->id }})"
                        class="h-9 px-3.5 rounded-xl text-xs font-medium transition-all disabled:opacity-50 inline-flex items-center gap-1.5
                                   {{ $this->teacher->deleted_at
                                       ? 'bg-violet-500/15 hover:bg-violet-500/25 border border-violet-500/20 text-violet-300'
                                       : 'bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300' }}">
                        <span wire:loading.remove
                            wire:target="deleteTeacher({{ $this->teacher->id }}), restoreTeacher({{ $this->teacher->id }})"
                            class="inline-flex items-center gap-1.5">
                            @if ($this->teacher->deleted_at)
                                <x-lucide-recycle class="w-3.5 h-3.5" /> Restaurer
                            @else
                                <x-lucide-trash class="w-3.5 h-3.5" /> Corbeille
                            @endif
                        </span>
                        <span wire:loading
                            wire:target="deleteTeacher({{ $this->teacher->id }}), restoreTeacher({{ $this->teacher->id }})">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>

                    @if ($this->teacher->deleted_at)
                        <button wire:click="forceDeleteTeacher({{ $this->teacher->id }})"
                            wire:loading.attr="disabled" wire:target="forceDeleteTeacher({{ $this->teacher->id }})"
                            class="h-9 px-3.5 rounded-xl bg-red-500/15 hover:bg-red-500/25 border border-red-500/25 text-red-300 text-xs font-medium transition-all disabled:opacity-50 inline-flex items-center gap-1.5">
                            <span wire:loading.remove wire:target="forceDeleteTeacher({{ $this->teacher->id }})"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                Suppr. déf.
                            </span>
                            <span wire:loading wire:target="forceDeleteTeacher({{ $this->teacher->id }})">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </section>

        {{-- ════════════════ KPIs ════════════════ --}}
        <section>
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
                @foreach ([['Classes', __zero($this->teacher?->getTeacherClassesCountForThisSchoolYear()), 'text-indigo-400', 'indigo'], ['Heures/Sem.', '---', 'text-emerald-400', 'emerald'], ['Notes publiées', '---', 'text-amber-400', 'amber'], ['Présence', '---', 'text-sky-400', 'sky']] as $kpi)
                    <div
                        class="rounded-xl bg-[#0f1523] border border-white/[0.05] p-4 sm:p-5 hover:border-{{ $kpi[3] }}-500/20 transition-colors">
                        <p class="text-[10px] uppercase tracking-wider text-slate-600 font-semibold">
                            {{ $kpi[0] }}
                        </p>
                        <p class="mt-2 text-2xl sm:text-3xl font-bold {{ $kpi[2] }} tabular-nums">
                            {{ $kpi[1] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ════════════════ CONTENU ════════════════ --}}
        <section class="grid grid-cols-1 2xl:grid-cols-[1fr_360px] gap-6 pb-12">

            {{-- Colonne principale --}}
            <div class="space-y-6 min-w-0">

                {{-- Classes --}}
                <div
                    class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-b border-white/[0.05]">
                        <div>
                            <h2 class="text-sm font-semibold text-white">Classes assignées</h2>
                            <p class="text-[11px] text-slate-500 mt-0.5">Année en cours</p>
                        </div>
                        <a href="{{ route('tenant.teacher.manage.classes', ['teacher_uuid' => $this->teacher->uuid]) }}"
                            class="h-9 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white transition-all inline-flex items-center gap-1.5 shrink-0">
                            <x-lucide-settings-2 class="w-3.5 h-3.5" />
                            Gérer
                        </a>
                    </div>

                    @php
                        $classes = $this->teacher?->getTeacherClassesWithSubjectsForThisSchoolYear();
                    @endphp

                    @if (count($classes))
                        <div class="divide-y divide-white/[0.04]">
                            @foreach ($classes as $kls)
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center gap-3 px-5 py-4 hover:bg-white/[0.02] transition-colors">
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('tenant.classe.profil', ['classe_slug' => $kls->classe?->slug]) }}"
                                            class="text-sm font-semibold text-slate-100 hover:text-indigo-300 transition-colors">
                                            {{ $kls->classe?->name }}
                                        </a>
                                        <p class="text-[11px] text-amber-500/80 mt-0.5">
                                            {{ $kls->classe?->speciality() }}
                                            ·
                                            <span
                                                class="text-slate-500">{{ $kls->subject?->code ?? $kls->subject?->name }}</span>
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                        <span
                                            class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/15">86
                                            notes</span>
                                        <span class="px-2 py-0.5 rounded-md bg-white/5 border border-white/5">4h</span>
                                    </div>

                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button
                                            wire:click="{{ $this->teacher->cannotAccessIntoClasse($kls->classe?->id)
                                                ? 'unLockAccessToClasse(' . $this->teacher->id . ',' . $kls->classe?->id . ')'
                                                : 'lockAccessToClasse(' . $this->teacher->id . ',' . $kls->classe?->id . ')' }}"
                                            wire:loading.attr="disabled"
                                            wire:target="lockAccessToClasse, unLockAccessToClasse"
                                            class="h-8 px-2.5 rounded-lg text-[11px] font-medium transition-all disabled:opacity-50 inline-flex items-center gap-1
                                                       {{ $this->teacher->cannotAccessIntoClasse($kls->classe?->id)
                                                           ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/20'
                                                           : 'bg-rose-500/10 text-rose-300 border border-rose-500/20' }}">
                                            @if ($this->teacher->cannotAccessIntoClasse($kls->classe?->id))
                                                <x-lucide-check class="w-3.5 h-3.5" /> Accès OK
                                            @else
                                                <x-lucide-lock class="w-3.5 h-3.5" /> Verrouiller
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-14 text-center px-4">
                            <x-lucide-school class="w-10 h-10 text-slate-700 mx-auto mb-3" />
                            <p class="text-sm text-slate-500 mb-3">Aucune classe assignée</p>
                            <a href="{{ route('tenant.teacher.manage.classes', ['teacher_uuid' => $this->teacher->uuid]) }}"
                                class="inline-flex h-9 px-4 rounded-xl bg-white/5 hover:bg-indigo-500/15 border border-white/10 text-xs text-slate-400 hover:text-indigo-300 transition-all">
                                Attribuer des classes
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Emploi du temps enseignant --}}
                @php
                    $days = $this->weekDays();
                    $timeColPx = 112; // largeur colonne Horaire
                    $dayColPx = 168; // largeur fixe de chaque jour (à ajuster)
                    $tableWidth = $timeColPx + count($days) * $dayColPx;
                @endphp

                <div
                    class="min-w-0 max-w-full rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-b border-white/[0.05]">
                        <div>
                            <h2 class="text-sm font-semibold text-white">Emploi du temps</h2>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Grille 07h–19h · année {{ $this->activeYear?->slug ?? '—' }}
                                · {{ $this->teacherSlots->count() }} créneau(x)
                            </p>
                        </div>
                        <button type="button" wire:click="openCreateSlot" wire:loading.attr="disabled"
                            wire:target="openCreateSlot"
                            class="h-9 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white transition-all inline-flex items-center gap-1.5 shrink-0 disabled:opacity-60">
                            <span wire:loading.remove wire:target="openCreateSlot"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-plus class="w-3.5 h-3.5" /> Créneau
                            </span>
                            <span wire:loading wire:target="openCreateSlot">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    </div>

                    <div class="overflow-x-auto overscroll-x-contain">
                        <table class="table-fixed border-collapse text-left text-xs"
                            style="width: {{ $tableWidth }}px; min-width: {{ $tableWidth }}px;">
                            <colgroup>
                                <col style="width: {{ $timeColPx }}px">
                                @foreach ($days as $day => $dayName)
                                    <col style="width: {{ $dayColPx }}px">
                                @endforeach
                            </colgroup>

                            <thead>
                                <tr class="border-b border-white/[0.06]">
                                    <th
                                        class="sticky left-0 z-20 bg-[#0c101c] px-2 py-3 text-center
                               text-[11px] font-bold uppercase tracking-wider text-slate-500 border-r border-white/[0.06]">
                                        Horaire
                                    </th>
                                    @foreach ($days as $day => $dayName)
                                        @php $isTodayColumn = (int) $day === (int) now()->dayOfWeekIso; @endphp
                                        <th
                                            class="px-2 py-3 text-center text-sm font-semibold border-r border-white/[0.05] last:border-r-0
                                   {{ $isTodayColumn ? 'text-cyan-300 bg-cyan-500/10' : 'text-indigo-300 bg-white/[0.02]' }}">
                                            <span class="inline-flex items-center justify-center gap-1.5">
                                                {{ $dayName }}
                                                @if ($isTodayColumn)
                                                    <span
                                                        class="h-1.5 w-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                                @endif
                                            </span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($this->scheduleRows() as $row)
                                    @if ($row['type'] === 'break')
                                        <tr class="border-b border-white/[0.05]">
                                            {{-- Fond opaque sur le td, teinte dans le div interne --}}
                                            <td
                                                class="sticky left-0 z-10 bg-[#0c101c] p-0 border-r border-white/[0.06]">
                                                <div
                                                    class="px-2 py-2 text-center {{ $row['variant'] === 'lunch' ? 'bg-amber-500/5' : 'bg-sky-500/5' }}">
                                                    <span
                                                        class="inline-flex flex-col items-center gap-0.5 text-[10px] font-semibold uppercase tracking-wide
                                               {{ $row['variant'] === 'lunch' ? 'text-amber-400/90' : 'text-sky-400/90' }}">
                                                        <x-lucide-coffee class="w-3.5 h-3.5" />
                                                        {{ $row['label'] }}
                                                    </span>
                                                </div>
                                            </td>

                                            @foreach ($days as $day => $dayName)
                                                <td
                                                    class="px-1 py-1.5 border-r border-white/[0.05] last:border-r-0
                                           {{ $row['variant'] === 'lunch' ? 'bg-amber-500/5' : 'bg-sky-500/5' }}">
                                                    <div
                                                        class="h-8 rounded-lg border border-dashed flex items-center justify-center text-[10px] font-medium
                                               {{ $row['variant'] === 'lunch'
                                                   ? 'border-amber-500/20 bg-amber-500/5 text-amber-500/70'
                                                   : 'border-sky-500/20 bg-sky-500/5 text-sky-500/70' }}">
                                                        {{ $row['label'] }}
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @else
                                        <tr class="border-b border-white/[0.05]"
                                            wire:key="teacher-row-{{ $row['start'] }}">
                                            <td
                                                class="sticky left-0 z-10 bg-[#0c101c] px-2 py-2 text-center border-r border-white/[0.06] align-middle">
                                                <span
                                                    class="font-mono text-xs font-bold text-slate-400 whitespace-nowrap">
                                                    {{ \Illuminate\Support\Carbon::parse($row['start'])->format('H\hi') }}
                                                    <span class="text-slate-600">–</span>
                                                    {{ \Illuminate\Support\Carbon::parse($row['end'])->format('H\hi') }}
                                                </span>
                                            </td>

                                            @foreach ($days as $day => $dayName)
                                                @php
                                                    $covered = $this->isCellCoveredBySpanTeacher($day, $row['start']);
                                                    $slot = $covered
                                                        ? null
                                                        : $this->slotFragmentAtTeacher($day, $row['start']);
                                                    $span = $slot ? $this->slotContiguousSpan($slot, $row['start']) : 1;
                                                    $isLive = $slot ? $this->isSlotLive($slot) : false;
                                                    $planId = $slot?->time_plan_id;
                                                @endphp

                                                @if ($covered)
                                                    {{-- absorbé par rowspan --}}
                                                @elseif ($slot)
                                                    @php $minH = max(72, $span * 72); @endphp
                                                    <td rowspan="{{ $span }}"
                                                        class="p-1.5 border-r border-white/[0.05] last:border-r-0 align-top"
                                                        wire:key="teacher-slot-{{ $slot->id }}">
                                                        <div @class([
                                                            'relative h-full rounded-xl p-2.5 flex flex-col gap-1.5 transition-all border',
                                                            'border-cyan-400/80 bg-cyan-500/15 shadow-lg shadow-cyan-500/20 ring-2 ring-cyan-400/30' => $isLive,
                                                            'border-indigo-500/25 bg-indigo-500/10' => !$isLive,
                                                        ])
                                                            style="min-height: {{ $minH }}px;">

                                                            @if ($isLive)
                                                                <div class="absolute -top-2 right-2 z-10">
                                                                    <span
                                                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-cyan-500 text-white shadow-md animate-pulse">
                                                                        <span
                                                                            class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                                                        En cours
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            @if ($planId && !$slot->timePlan?->archived)
                                                                <div @class([
                                                                    'absolute top-1.5 right-1.5 flex items-center gap-0.5 opacity-70 hover:opacity-100',
                                                                    'mt-3' => $isLive,
                                                                ])>
                                                                    <button type="button"
                                                                        wire:click="editSlot({{ $slot->id }}, {{ $planId }})"
                                                                        wire:loading.attr="disabled"
                                                                        wire:target="editSlot({{ $slot->id }}, {{ $planId }})"
                                                                        title="Modifier"
                                                                        class="rounded p-1 text-slate-400 hover:text-cyan-300 hover:bg-white/10 transition-all">
                                                                        <span wire:loading.remove
                                                                            wire:target="editSlot({{ $slot->id }}, {{ $planId }})">
                                                                            <x-lucide-pencil class="w-3.5 h-3.5" />
                                                                        </span>
                                                                        <span wire:loading
                                                                            wire:target="editSlot({{ $slot->id }}, {{ $planId }})">
                                                                            <x-lucide-loader-2
                                                                                class="w-3.5 h-3.5 animate-spin" />
                                                                        </span>
                                                                    </button>
                                                                    <button type="button"
                                                                        wire:click="deleteSlot({{ $slot->id }}, {{ $planId }})"
                                                                        wire:loading.attr="disabled"
                                                                        wire:target="deleteSlot({{ $slot->id }}, {{ $planId }})"
                                                                        title="Supprimer"
                                                                        class="rounded p-1 text-slate-400 hover:text-rose-300 hover:bg-white/10 transition-all">
                                                                        <span wire:loading.remove
                                                                            wire:target="deleteSlot({{ $slot->id }}, {{ $planId }})">
                                                                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                                        </span>
                                                                        <span wire:loading
                                                                            wire:target="deleteSlot({{ $slot->id }}, {{ $planId }})">
                                                                            <x-lucide-loader-2
                                                                                class="w-3.5 h-3.5 animate-spin" />
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            @endif

                                                            <div class="flex items-start gap-1.5 pr-6">
                                                                <x-lucide-book-open @class([
                                                                    'w-3.5 h-3.5 shrink-0 mt-0.5',
                                                                    'text-cyan-300' => $isLive,
                                                                    'text-indigo-300' => !$isLive,
                                                                ]) />
                                                                <p class="font-semibold leading-5 text-white text-sm">
                                                                    {{ $slot->subject?->code ?? ($slot->subject?->name ?? ($slot->label ?? 'Cours')) }}
                                                                </p>
                                                            </div>

                                                            <p class="text-[11px] text-slate-400 truncate">
                                                                {{ $slot->timePlan?->classe?->name ?? ($slot->classeSubjectOfSchoolYear?->classe?->name ?? 'Classe') }}
                                                            </p>

                                                            <div class="pt-1 mt-auto">
                                                                <div @class([
                                                                    'flex items-center gap-1.5 font-mono text-xs',
                                                                    'text-cyan-300' => $isLive,
                                                                    'text-orange-400' => !$isLive,
                                                                ])>
                                                                    <x-lucide-clock class="w-3.5 h-3.5 shrink-0" />
                                                                    <span>{{ $slot->start }}</span>
                                                                    <span class="opacity-50">–</span>
                                                                    <span>{{ $slot->end }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @else
                                                    <td class="p-1.5 border-r border-white/[0.05] last:border-r-0 align-top"
                                                        wire:key="teacher-empty-{{ $day }}-{{ $row['start'] }}">
                                                        <div
                                                            class="h-[72px] rounded-xl bg-white/[0.015] border border-white/[0.03]">
                                                        </div>
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5 min-w-0">

                {{-- Responsabilités --}}
                <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 shadow-xl shadow-black/10">
                    <h2 class="text-sm font-semibold text-white mb-1">Responsabilités</h2>
                    <p class="text-[11px] text-slate-500 mb-4">{{ session('school_year_selected') }}</p>

                    @php $pp_classes = $this->teacher?->getClassesWhereIsPrincipal(); @endphp

                    <div class="space-y-2">
                        @forelse ($pp_classes as $cl)
                            <div
                                class="flex items-center gap-2.5 rounded-xl bg-[#070b14] border border-white/[0.04] px-3 py-2.5">
                                <x-lucide-user-cog class="w-4 h-4 text-amber-400 shrink-0" />
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-amber-300">Prof. principal</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $cl->code ?? $cl->name }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-600 italic py-2">Aucune responsabilité cette année</p>
                        @endforelse
                    </div>
                </div>

                {{-- Infos --}}
                <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 shadow-xl shadow-black/10">
                    <h2 class="text-sm font-semibold text-white mb-4">Informations</h2>
                    <div class="space-y-2.5">
                        @foreach ([['Email', $this->teacher->user?->email, 'mail'], ['Diplôme', 'Non renseigné', 'award'], ['Adresse', $this->teacher->user?->adresse, 'map-pin'], ['Recrutement', __formatDate($this->teacher->affiliated_at), 'calendar']] as $info)
                            <div class="rounded-xl bg-[#070b14] border border-white/[0.04] px-3.5 py-3">
                                <p class="text-[10px] uppercase tracking-wider text-slate-600">{{ $info[0] }}
                                </p>
                                <p class="mt-1 text-sm text-slate-200 break-words">{{ $info[1] ?? '—' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- QR --}}
                <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 shadow-xl shadow-black/10">
                    <h2 class="text-sm font-semibold text-white mb-4">QR Code</h2>
                    <div class="flex justify-center">
                        <div class="rounded-xl bg-white p-3 shadow-lg">
                            <img class="w-40 h-40" src="{{ $this->teacher->qr_code }}"
                                alt="QR Code de {{ $this->teacher->user->getFullName() }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Modal créneau enseignant --}}
        @if ($showSlotForm)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-data
                x-transition>
                <div class="w-full max-w-lg rounded-2xl border border-white/[0.08] bg-[#0c1019] shadow-2xl"
                    @click.outside="$wire.set('showSlotForm', false)">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                        <h3 class="text-base font-semibold text-white">
                            {{ $slotId ? 'Modifier le créneau' : 'Nouveau créneau' }}
                        </h3>
                        <button type="button" wire:click="$set('showSlotForm', false)"
                            class="rounded-lg p-1.5 text-slate-500 hover:text-white hover:bg-white/[0.06] transition-all">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>
                    <form wire:submit="saveSlot" class="p-5 space-y-4">
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-school class="w-3.5 h-3.5 text-indigo-400" /> Classe / Matière
                            </label>
                            <select wire:model="assignment_id" required
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-indigo-500/50 focus:outline-none transition-all">
                                <option value="">Choisir une affectation…</option>
                                @foreach ($this->assignments as $assignment)
                                    <option value="{{ $assignment->id }}">
                                        {{ $assignment->classe?->name }}
                                        — {{ $assignment->subject?->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assignment_id')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                            @if ($this->assignments->isEmpty())
                                <p class="mt-1 text-xs text-amber-400">
                                    Aucune classe assignée à cet enseignant pour l’année en cours.
                                </p>
                            @endif
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-calendar-days class="w-3.5 h-3.5 text-violet-400" /> Jour
                            </label>
                            <select wire:model="day_of_week" required
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-indigo-500/50 focus:outline-none transition-all">
                                @foreach ($this->weekDays() as $num => $label)
                                    <option value="{{ $num }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                    <x-lucide-clock class="w-3.5 h-3.5 text-emerald-400" /> Début
                                </label>
                                <input wire:model="starts_at" type="time" required
                                    class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                           focus:border-indigo-500/50 focus:outline-none transition-all" />
                                @error('starts_at')
                                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                    <x-lucide-clock class="w-3.5 h-3.5 text-rose-400" /> Fin
                                </label>
                                <input wire:model="ends_at" type="time" required
                                    class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                           focus:border-indigo-500/50 focus:outline-none transition-all" />
                                @error('ends_at')
                                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-type class="w-3.5 h-3.5 text-amber-400" />
                                Libellé <span class="text-slate-600 normal-case">(facultatif)</span>
                            </label>
                            <input wire:model="slot_label" type="text" maxlength="150"
                                placeholder="Ex. Salle B12, TP…"
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       placeholder:text-slate-600 focus:border-indigo-500/50 outline-none transition-all" />
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-align-left class="w-3.5 h-3.5 text-slate-500" />
                                Notes <span class="text-slate-600 normal-case">(facultatif)</span>
                            </label>
                            <textarea wire:model="slot_notes" rows="2"
                                class="w-full rounded-xl border border-white/[0.08] bg-[#070a12] px-3 py-2.5 text-sm text-white
                                       focus:border-indigo-500/50 outline-none transition-all resize-none"></textarea>
                        </div>
                        <div class="flex justify-end gap-2 pt-3 border-t border-white/[0.05]">
                            <button type="button" wire:click="$set('showSlotForm', false)"
                                class="h-10 px-4 rounded-xl text-sm font-medium border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.06] hover:text-white transition-all">
                                Annuler
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="saveSlot"
                                class="h-10 px-4 rounded-xl text-sm font-medium bg-indigo-600 hover:bg-indigo-500 text-white
                                       transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="saveSlot">Enregistrer</span>
                                <span wire:loading wire:target="saveSlot" class="inline-flex items-center gap-2">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" /> Enregistrement…
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

