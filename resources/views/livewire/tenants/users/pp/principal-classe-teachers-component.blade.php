<div class="min-h-screen bg-slate-950 text-slate-100 w-full max-w-full overflow-x-hidden p-3 pb-32">

    <div class="w-full max-w-[100vw] overflow-x-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-6">

            {{-- LEFT --}}
            <div class="min-w-0 w-full px-3 border-b border-b-slate-800">

                <div class="flex flex-wrap items-center gap-3 w-full">

                    <h1 class="md:text-xl text-base font-bold break-words py-3 ">
                        Liste des enseignants de <span
                            class="text-sky-600 uppercase font-mono">{{ $this->classe->code }}</span>
                    </h1>
                    <span
                        class="px-3 py-1 rounded-full
                                 bg-indigo-500/10
                                 border border-indigo-500/20
                                 text-indigo-400 text-xs shrink-0 font-mono">

                        {{ count($this->teachers) }} enseignants

                    </span>

                </div>

                <p class="mt-1 text-slate-400 text-sm sm:text-base font-mono">

                    Liste complète des enseignants intervenant dans la classe.

                </p>
            </div>

        </div>
        <section class="relative mb-16">
            <div wire:loading wire:target="previousPage,nextPage,gotoPage"
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
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($this->teachers as $teacher)
                        @php
                            $orderNumber = $this->teachers->firstItem() + $loop->iteration - 1;
                            $this->classess = $teacher->getTeacherClassesForThisSchoolYear();
                        @endphp

                        <article
                            class="rounded-2xl bg-slate-950 shadow-xs shadow-purple-700 border border-white/5 hover:border-violet-500/20 transition-all overflow-hidden">
                            <div class="p-4 sm:p-5">
                                <div class="flex flex-col xl:flex-row gap-5">

                                    {{-- N° + Identity --}}
                                    <div class="flex flex-col gap-3.5 min-w-0 xl:w-[300px] shrink-0">
                                        <div class="flex gap-3.5 min-w-0 xl:w-[300px] shrink-0">
                                            <div class="flex items-start pt-1">
                                                <span
                                                    class="inline-flex items-center justify-center px-2 h-8 rounded-lg bg-violet-500/15 border border-violet-500/25 text-violet-300 text-xs font-bold tabular-nums">
                                                    N° {{ __zero($orderNumber) }}
                                                </span>
                                            </div>

                                            <a href="#" class="flex gap-3 min-w-0 flex-1 group">
                                                <img src="{{ $teacher->user->profil_photo_url }}" alt=""
                                                    class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/10 group-hover:ring-violet-500/40 transition-all shrink-0">
                                                <div class="min-w-0">
                                                    <h3
                                                        class="font-semibold text-white group-hover:text-violet-300 transition-colors break-normal text-sm">
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
                                                </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">
                                                Matières
                                                @if ($this->classe)
                                                    <span class="text-slate-600 normal-case">· dispensée dans cette
                                                        classe</span>
                                                @endif
                                            </p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @if ($this->classe)
                                                    @forelse ($teacher->getSubjectsForThisClasse($this->classe->id) as $subjectRelation)
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
                                    </div>

                                    {{-- Matières + Classes --}}
                                    <div
                                        class="flex-1 min-w-0 border-t xl:border-t-0 xl:border-l border-white/5 pt-3 xl:pt-0 xl:pl-5 space-y-3">
                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">Autres
                                                classes</p>
                                            @if (count($this->classess))
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach ($this->classess as $cl)
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
            @endif
        </section>
    </div>
</div>

