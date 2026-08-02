<div class="w-full overflow-x-hidden bg-[#0b0f19] min-h-screen">

    <div class="mx-auto w-full max-w-[1900px] px-4 sm:px-6 lg:px-8 py-8">

        {{-- ===================== HEADER ===================== --}}
        <section class="mb-8">
            <div class="rounded-2xl bg-[#121826] border border-white/5 overflow-hidden">
                <div class="p-5 sm:p-6 lg:p-8">
                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-8">

                        {{-- Identity --}}
                        <div class="flex flex-col sm:flex-row gap-5 min-w-0">
                            <div class="flex justify-center sm:block shrink-0">
                                <img src="{{ $user->profil_photo_url }}" alt="{{ $parent->getFullName() }}"
                                    class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl object-cover ring-2 ring-white/10">
                            </div>

                            <div class="min-w-0 text-center sm:text-left">
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                                    <h1 class="text-2xl sm:text-3xl font-semibold text-white tracking-tight">
                                        {{ $parent->getFullName() }}
                                    </h1>
                                    @if ($parent->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[11px] font-medium border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            Compte actif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-500/10 text-rose-400 text-[11px] font-medium border border-rose-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                            Compte inactif
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-2 text-sm text-amber-400/90">
                                    {{ $user->email }}
                                </p>

                                <p class="mt-1.5 text-sm text-slate-400">
                                    @if (count($this->children))
                                        Parent avec accès à
                                        <span class="text-violet-300 font-medium">{{ __zero(count($this->children)) }}
                                            apprenant(s)</span>
                                    @else
                                        Pas d’apprenant lié
                                    @endif
                                </p>

                                <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-2">
                                    @if ($user->job_name)
                                        <span
                                            class="px-3 py-1.5 rounded-lg bg-[#0b0f19] border border-white/5 text-xs text-slate-300">
                                            {{ $user->job_name }}
                                        </span>
                                    @endif
                                    @if ($user->adresse)
                                        <span
                                            class="px-3 py-1.5 rounded-lg bg-[#0b0f19] border border-white/5 text-xs text-slate-300">
                                            {{ $user->adresse }}
                                        </span>
                                    @endif
                                    @if ($user->gender)
                                        <span
                                            class="px-3 py-1.5 rounded-lg bg-[#0b0f19] border border-white/5 text-xs text-slate-300">
                                            {{ $user->gender }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-wrap gap-2 shrink-0">
                            <a wire:navigate
                                href="{{ route('tenant.parents.manage.relations', ['parent_uuid' => $parent->uuid]) }}"
                                class="h-10 px-4 rounded-lg bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium transition-all active:scale-[0.97] inline-flex items-center">
                                Ajouter des apprenants
                            </a>
                            <button
                                class="h-10 px-4 rounded-lg bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/25 text-emerald-300 text-sm font-medium transition-all">
                                Envoyer bulletin
                            </button>
                            <button
                                class="h-10 px-4 rounded-lg bg-sky-500/15 hover:bg-sky-500/25 border border-sky-500/25 text-sky-300 text-sm font-medium transition-all">
                                Envoyer notes
                            </button>
                            <button wire:click='sendCredentialsToTheTutor'
                                class="h-10 px-4 rounded-lg bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/25 text-amber-300 text-sm font-medium transition-all">
                                <span>Envoyez données</span>
                            </button>
                            <button
                                class="h-10 px-4 rounded-lg bg-rose-500/15 hover:bg-rose-500/25 border border-rose-500/25 text-rose-300 text-sm font-medium transition-all">
                                Bloquer accès
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== CONTENT ===================== --}}
        <section>
            <div class="grid grid-cols-1 2xl:grid-cols-[minmax(0,1fr)_380px] gap-6">

                {{-- LEFT --}}
                <div class="space-y-6 min-w-0">

                    {{-- État civil --}}
                    <div class="rounded-2xl bg-[#121826] border border-white/5 p-5 sm:p-6">
                        <h2 class="text-lg font-semibold text-white">État civil & identité</h2>
                        <p class="mt-1 text-sm text-slate-500">Informations administratives du parent</p>

                        <div class="mt-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                            @foreach ($this->parentInfos as $info)
                                <div class="rounded-xl bg-[#0b0f19] border border-white/5 p-4">
                                    <p class="text-[11px] uppercase tracking-wider text-slate-500">{{ $info[0] }}
                                    </p>
                                    <p class="mt-1.5 text-sm font-medium text-slate-200">{{ $info[1] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- Enfants associés --}}
                    <div class="rounded-2xl bg-[#121826] border border-white/5 overflow-hidden">
                        <div class="p-5 border-b border-white/5">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h2 class="text-lg font-semibold text-white">
                                        Enfants associés
                                        <span class="text-violet-400 font-mono text-sm ml-1">
                                            ({{ __zero(count($this->children)) }})
                                        </span>
                                    </h2>
                                    <p class="mt-1 text-sm text-slate-500">Apprenants liés à ce parent</p>
                                </div>
                                <a wire:navigate
                                    href="{{ route('tenant.parents.manage.relations', ['parent_uuid' => $parent->uuid]) }}"
                                    class="h-9 px-4 rounded-lg bg-violet-500/15 hover:bg-violet-500/25 border border-violet-500/20 text-violet-300 text-xs font-medium transition-all inline-flex items-center justify-center">
                                    Gérer les liaisons parentales
                                </a>
                            </div>
                        </div>
                        <div class="p-4 space-y-3">
                            @forelse ($this->children as $rel)
                                @php $student = $rel->student; @endphp
                                <div
                                    class="rounded-xl bg-[#0b0f19] border border-white/5 p-4 hover:border-violet-500/20 transition-all">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                        <div class="flex-1 min-w-0">
                                            <a wire:navigate
                                                href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                                class="font-medium text-white hover:text-violet-300 transition-colors">
                                                {{ $student->getFullName() }}
                                            </a>
                                            <div
                                                class="mt-1.5 flex flex-wrap items-center gap-2 text-xs text-slate-400">
                                                <span>{{ $student->gender }}</span>
                                                <span class="text-slate-600">·</span>
                                                <span>{{ __getAge($student->birth_date) }} ans</span>
                                                <span class="text-slate-600">·</span>
                                                <span class="text-lime-400/90">{{ $rel->parent_relation }}</span>
                                            </div>
                                            <div class="mt-2">
                                                @if ($student->currentClasse() && $student->currentClasse()->classe)
                                                    @php $r = $student->currentClasse()->classe; @endphp
                                                    <span
                                                        class="inline-flex px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-[11px] font-mono">
                                                        {{ $r->code ?: $r->name }}
                                                    </span>
                                                @else
                                                    <span class="text-[11px] text-amber-400/80">
                                                        Pas de classe · {{ $this->activeYear?->slug }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                                            <a wire:navigate
                                                href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                                class="h-8 px-3 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/20 text-sky-300 text-xs font-medium transition-all  inline-flex items-center justify-center">
                                                <span>
                                                    Profil
                                                </span>
                                            </a>
                                            <a href="{{ route('tenant.student.marks', ['student_uuid' => $student->uuid]) }}"
                                                class="h-8 px-3 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-300 text-xs inline-flex items-center justify-center font-medium transition-all">
                                                <span>
                                                    Notes
                                                </span>
                                            </a>
                                            <button wire:click="removeRelation({{ $rel->student_id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="removeRelation({{ $rel->student_id }})"
                                                class="h-8 px-3 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-300 text-xs font-medium transition-all disabled:opacity-50">
                                                <span wire:loading.remove
                                                    wire:target="removeRelation({{ $rel->student_id }})">
                                                    Dissocier
                                                </span>
                                                <span wire:loading
                                                    wire:target="removeRelation({{ $rel->student_id }})">
                                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-10 text-center text-sm text-slate-600">
                                    Aucun apprenant associé
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="space-y-6">

                    {{-- Contact rapide --}}
                    <div class="rounded-2xl bg-[#121826] border border-white/5 p-5">
                        <h2 class="text-sm font-semibold text-white mb-4">Contact rapide</h2>
                        <div class="space-y-2">
                            @foreach (['Envoyer notification', 'Envoyer email', 'Envoyer SMS', 'Envoyer WhatsApp', 'Partager bulletin'] as $action)
                                <button
                                    class="w-full h-10 rounded-lg bg-[#0b0f19] hover:bg-white/5 border border-white/5 text-sm text-slate-300 transition-all">
                                    {{ $action }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Performance --}}
                    <div class="rounded-2xl bg-[#121826] border border-white/5 p-5">
                        <h2 class="text-sm font-semibold text-white mb-5">Performance globale</h2>
                        <div class="space-y-5">
                            @foreach ([['Moyenne générale', '13.42', 67, 'bg-emerald-500'], ['Présence', '94%', 94, 'bg-indigo-500'], ['Retards', '12%', 12, 'bg-amber-500'], ['Admissibilité', '100%', 100, 'bg-sky-500']] as $perf)
                                <div>
                                    <div class="flex justify-between text-sm mb-1.5">
                                        <span class="text-slate-400">{{ $perf[0] }}</span>
                                        <span class="font-semibold text-slate-200">{{ $perf[1] }}</span>
                                    </div>
                                    <div class="h-1.5 rounded-full bg-[#0b0f19] overflow-hidden">
                                        <div class="h-full rounded-full {{ $perf[3] }}"
                                            style="width: {{ $perf[2] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- QR Code --}}
                    <div class="rounded-2xl bg-[#121826] border border-white/5 p-5">
                        <h2 class="text-sm font-semibold text-white mb-4">QR Code</h2>
                        <div class="flex justify-center">
                            <img class="w-44 h-44 rounded-xl bg-white p-2" src="{{ $parent->qr_code }}"
                                alt="QR Code de {{ $parent->getFullName() }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

