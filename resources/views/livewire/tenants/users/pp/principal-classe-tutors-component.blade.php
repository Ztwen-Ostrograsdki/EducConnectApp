<div class="min-h-screen bg-[#070b14] text-slate-100 overflow-x-hidden">
    <div class="mx-auto w-full max-w-[1400px] px-4 py-8">

        {{-- ════════════════ HEADER ════════════════ --}}
        <header class="mb-8">
            <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-6 shadow-xl shadow-black/20">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                                Parents de
                                <span class="text-violet-400 uppercase font-mono">{{ $this->classe->code }}</span>
                            </h1>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-violet-500/15 border border-violet-500/25 text-violet-300 text-xs font-semibold tabular-nums">
                                {{ __zero($this->tutors->total()) }} parents / tuteurs
                            </span>
                        </div>
                        <p class="mt-1.5 text-sm text-slate-500">
                            Représentants légaux · accès et suivi
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#070b14] border border-white/5 text-[11px] text-slate-500">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Classe active
                        </span>
                    </div>
                </div>
            </div>
        </header>

        {{-- ════════════════ LISTE ════════════════ --}}
        <section class="relative mb-16">
            <div wire:loading wire:target="previousPage,nextPage,gotoPage"
                class="absolute inset-0 z-20 flex items-center justify-center bg-[#070b14]/75 backdrop-blur-sm rounded-2xl">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 rounded-full border-2 border-violet-500/30 border-t-violet-400 animate-spin">
                    </div>
                    <span class="text-xs font-mono text-slate-500">Chargement…</span>
                </div>
            </div>

            @if (count($this->tutors) > 0)
                <div class="space-y-3">
                    @foreach ($this->tutors as $parent)
                        @php
                            $orderNumber = $this->tutors->firstItem() + $loop->iteration - 1;
                        @endphp

                        <article
                            class="group rounded-2xl bg-[#0f1523] border border-sky-700/25 hover:border-violet-500 transition-all duration-200 overflow-hidden  shadow-xs shadow-slate-600">
                            <div class="p-4 sm:p-5">
                                <div class="flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-6">

                                    {{-- N° + Photo + Identité --}}
                                    <div class="flex items-center gap-3 sm:gap-4 min-w-0 lg:w-[340px] shrink-0">
                                        <span
                                            class="hidden sm:flex items-center justify-center w-8 h-8 rounded-lg bg-violet-500/10 border border-violet-500/20 text-violet-300 text-xs font-bold tabular-nums shrink-0">
                                            {{ __zero($orderNumber) }}
                                        </span>

                                        <a href="#" class="shrink-0 relative">
                                            <img src="{{ $parent->profil_photo_url() }}"
                                                alt="{{ $parent->getFullName() }}"
                                                class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl object-cover ring-2 ring-white/10 group-hover:ring-violet-500/40 transition-all">
                                        </a>

                                        <div class="min-w-0 flex-1">
                                            <a href="#"
                                                class="block font-semibold text-white hover:text-violet-300 transition-colors truncate text-sm sm:text-base">
                                                {{ $parent->getFullName() }}
                                            </a>
                                            <div
                                                class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-0.5 text-sm text-slate-400">
                                                @if ($parent->user->contacts)
                                                    <span class="inline-flex items-center gap-1">
                                                        <x-lucide-phone class="w-3 h-3 shrink-0" />
                                                        {{ $parent->user->contacts }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($parent->user->job_name || $parent->user->adresse)
                                                <div class="mt-0.5 flex flex-wrap gap-x-3 text-sm text-slate-400">
                                                    @if ($parent->user->job_name)
                                                        <span class="inline-flex items-center gap-1 truncate">
                                                            <x-lucide-briefcase class="w-3 h-3 shrink-0" />
                                                            {{ $parent->user->job_name }}
                                                        </span>
                                                    @endif
                                                    @if ($parent->user->adresse)
                                                        <span class="inline-flex items-center gap-1 truncate">
                                                            <x-lucide-map-pin class="w-3 h-3 shrink-0" />
                                                            {{ $parent->user->adresse }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Enfants --}}
                                    <div class="flex-1 min-w-0 lg:border-l lg:border-white/5 lg:pl-6">
                                        <p
                                            class="text-[10px] uppercase tracking-wider text-slate-600 font-semibold mb-2 lg:hidden">
                                            Enfant(s)
                                        </p>

                                        @if (count($parent->myChildren))
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($parent->myChildren()->get() as $rel)
                                                    @php $student = $rel->student; @endphp
                                                    <a wire:navigate href="#"
                                                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#070b14] border border-white/5 hover:border-violet-500/30 hover:bg-violet-500/5 transition-all active:scale-[0.98]">
                                                        <span
                                                            class="w-6 h-6 rounded-md bg-violet-500/15 flex items-center justify-center text-[10px] font-bold text-violet-300 shrink-0">
                                                            {{ strtoupper(str()->substr($student->getFullName(), 0, 1)) }}
                                                        </span>
                                                        <span
                                                            class="text-xs font-medium text-slate-300 truncate max-w-[140px]">
                                                            {{ $student->getFullName() }}
                                                        </span>
                                                        <span class="text-[10px] font-mono text-amber-400/80 shrink-0">
                                                            {{ $this->classe->code ?: $this->classe->name }}
                                                        </span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-600 italic">Aucun apprenant lié</p>
                                        @endif
                                    </div>

                                    {{-- Action --}}
                                    <div class="flex items-center gap-2 lg:shrink-0 lg:pl-2">
                                        <a href="#"
                                            class="h-9 px-3.5 rounded-xl bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-300 text-xs font-medium transition-all inline-flex items-center gap-1.5">
                                            Voir profil
                                            <x-lucide-arrow-right class="w-3.5 h-3.5" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($this->tutors->hasPages())
                    <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-xs text-slate-600">
                            {{ $this->tutors->firstItem() }}–{{ $this->tutors->lastItem() }}
                            sur {{ $this->tutors->total() }} parents
                        </p>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$this->tutors->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                    ← Préc.
                                </button>
                            @endif
                            @foreach ($this->tutors->getUrlRange(1, $this->tutors->lastPage()) as $page => $url)
                                <button @disabled($page === $this->tutors->currentPage()) wire:click="gotoPage({{ $page }})"
                                    class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                               {{ $page === $this->tutors->currentPage()
                                                   ? 'bg-violet-600 text-white shadow-lg shadow-violet-900/30'
                                                   : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                            @if ($this->tutors->hasMorePages())
                                <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                    class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-400 transition-all disabled:opacity-50">
                                    Suiv. →
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] py-24 text-center">
                    <div
                        class="w-16 h-16 mx-auto rounded-2xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-3xl mb-4">
                        👤
                    </div>
                    <p class="text-slate-500 text-sm">Aucun parent ou tuteur trouvé pour cette classe</p>
                </div>
            @endif
        </section>
    </div>
</div>

