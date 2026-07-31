<div class="min-h-screen bg-transparent text-slate-100 overflow-x-hidden">
    <div class="w-full max-w-[1850px] mx-auto px-3 sm:px-4 lg:px-6 py-6">

        {{-- ===================== HEADER ===================== --}}
        <header class="mb-8 border-b border-b-sky-900 pb-3">
            <div class="flex items-center gap-3 mb-1">
                <span class="h-8 w-1 rounded-full bg-cyan-400"></span>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-cyan-400/70">
                        Classe
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                        {{ $classe->code }}
                    </h1>
                </div>
            </div>
            <p class="ml-4 text-sm text-slate-500">
                Détails généraux et suivi de la classe
            </p>
        </header>

        {{-- ===================== GRID ===================== --}}
        <div class="grid grid-cols-1 2xl:grid-cols-12 gap-5">

            {{-- ========== LEFT (8 cols) ========== --}}
            <div class="2xl:col-span-8 space-y-5 min-w-0">

                {{-- Élèves récemment ajoutés --}}
                <div
                    class="rounded-2xl bg-[#111827] border border-white/[0.06] overflow-hidden shadow-sm shadow-sky-600">
                    <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-base font-semibold text-white">Élèves récemment ajoutés</h2>
                            <p class="mt-0.5 text-xs text-slate-500">
                                Ajouts des 2 dernières semaines
                            </p>
                        </div>
                        <span
                            class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-lg bg-cyan-500/10 text-cyan-400 text-[11px] font-medium border border-cyan-500/20">
                            Récent
                        </span>
                    </div>

                    <div class="divide-y divide-white/[0.04] bg-slate-950 shadow-sm shadow-sky-600">
                        @forelse ($classe->recentStudentsMigratedsIntoClasse(2) as $student)
                            <a wire:navigate
                                href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                class="flex items-center gap-4 px-5 py-4 hover:bg-white/[0.02] transition-colors group">
                                <img src="{{ $student->profil_photo_url }}" alt=""
                                    class="w-11 h-11 rounded-xl object-cover ring-2 ring-white/10 group-hover:ring-cyan-500/40 transition-all shrink-0">
                                <div class="min-w-0 flex-1">
                                    <p
                                        class="text-sm font-medium text-slate-200 group-hover:text-cyan-300 transition-colors truncate">
                                        {{ $student->getFullName() }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-mono mt-0.5">
                                        #{{ $student->matricule }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[11px] text-slate-500">Ajouté le</p>
                                    <p class="text-xs text-slate-400 font-mono mt-0.5">
                                        {{ __formatDate($student->currentYearlyAccess($classe->id)?->started_at) }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-12 text-center">
                                <p class="text-sm text-slate-600">Aucun élève récemment ajouté</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- ========== RIGHT (4 cols) ========== --}}
            <div class="2xl:col-span-4 space-y-5 min-w-0">

                {{-- Prof principal --}}
                <div
                    class="rounded-2xl bg-slate-950 shadow-sm shadow-sky-600 border border-white/[0.06] overflow-hidden">
                    <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-white">Professeur principal</h2>
                        <a wire:navigate href="{{ route('tenant.classe.respos', ['classe_slug' => $classe->slug]) }}"
                            class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all">
                            <x-lucide-pen class="w-3.5 h-3.5" />
                            Éditer
                        </a>
                    </div>

                    <div class="p-5">
                        @if ($classe->principal)
                            <a wire:navigate
                                href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $classe->principal?->uuid]) }}"
                                class="flex items-center gap-3.5 group">
                                <img src="{{ $classe->principal?->user->profil_photo_url }}" alt=""
                                    class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/10 group-hover:ring-cyan-500/40 transition-all shrink-0">
                                <div class="min-w-0">
                                    <p
                                        class="text-sm font-medium text-slate-200 group-hover:text-cyan-300 transition-colors truncate">
                                        {{ $classe->principal?->getFullName() ?? 'Non défini' }}
                                    </p>
                                    @if ($classe->principal?->getSubjectsForThisClasse($classe->id))
                                        <div class="mt-1.5 flex flex-wrap gap-1">
                                            @foreach ($classe->principal?->getSubjectsForThisClasse($classe->id) as $classeSubject)
                                                <span
                                                    class="px-2 py-0.5 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-[10px] font-mono uppercase">
                                                    {{ $classeSubject->subject?->code }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @else
                            <p class="text-sm text-slate-600 italic py-2">Non encore défini</p>
                        @endif
                    </div>
                </div>

                {{-- Responsables de classe --}}
                <div
                    class="rounded-2xl bg-slate-950 shadow-sm shadow-sky-600 border border-white/[0.06] overflow-hidden">
                    <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-white">Responsables de classe</h2>
                        <a wire:navigate href="{{ route('tenant.classe.respos', ['classe_slug' => $classe->slug]) }}"
                            class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all">
                            <x-lucide-pen class="w-3.5 h-3.5" />
                            Éditer
                        </a>
                    </div>

                    <div class="p-4 space-y-3">
                        @if ($classe->respo_1_id && $classe->respo_2_id)
                            @foreach ($classe->responsables() as $key => $respo)
                                <a wire:navigate
                                    href="{{ route('tenant.student.profil', ['student_uuid' => $respo->uuid]) }}"
                                    class="flex items-center gap-3 p-3 rounded-xl bg-transparent shadow-xs shadow-purple-600 border border-white/[0.04] hover:border-cyan-500/30 transition-all group"
                                    wire:key="respo-{{ $loop->iteration }}">
                                    <div
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-cyan-500/15 border border-cyan-500/25 text-cyan-400 text-[11px] font-bold shrink-0">
                                        {{ $loop->iteration }}
                                    </div>
                                    <img src="{{ $respo->profil_photo_url }}" alt=""
                                        class="w-9 h-9 rounded-lg object-cover ring-1 ring-white/10 shrink-0">
                                    <p
                                        class="text-sm text-slate-300 group-hover:text-cyan-300 transition-colors truncate min-w-0">
                                        {{ $respo?->getFullName() ?? 'Non défini' }}
                                    </p>
                                </a>
                            @endforeach
                        @else
                            <p class="text-sm text-slate-600 italic py-3 text-center">Non encore définis</p>
                        @endif
                    </div>
                </div>

                {{-- Statistiques --}}
                <div class="rounded-2xl bg-slate-950 shadow-sm shadow-sky-600 border border-white/[0.06] p-5">
                    <h2 class="text-sm font-semibold text-white mb-5">Statistiques</h2>

                    <div class="space-y-5">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-slate-400">Présence</span>
                                <span class="text-xs font-mono text-emerald-400">96%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-[#0a0e17] overflow-hidden">
                                <div
                                    class="h-full w-[96%] rounded-full bg-gradient-to-r from-emerald-600 to-emerald-400">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-slate-400">Réussite</span>
                                <span class="text-xs font-mono text-indigo-400">82%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-[#0a0e17] overflow-hidden">
                                <div class="h-full w-[82%] rounded-full bg-gradient-to-r from-indigo-600 to-indigo-400">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

