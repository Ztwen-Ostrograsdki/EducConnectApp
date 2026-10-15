<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 py-8 sm:py-10">

        <header class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div class="space-y-3">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[11px] font-semibold uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                        Documents PDF
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                        {{ $pageTitle }}
                    </h1>
                    <p class="text-sm text-slate-500 max-w-xl">
                        Téléchargez, partagez ou supprimez vos PDF générés.
                        @if (!empty($targeted))
                            <span
                                class="ml-1 inline-flex items-center rounded-lg px-2.5 py-0.5 text-[11px] font-mono uppercase bg-orange-500/15 text-orange-400 border border-orange-500/20">
                                {{ $targeted }}
                            </span>
                        @endif
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a wire:navigate href="{{ route('tenant.students.portal') }}"
                        class="h-9 px-3.5 rounded-xl text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                        <x-lucide-layout-dashboard class="w-3.5 h-3.5" />
                        Portail apprenants
                    </a>
                    <a href="{{ route('tenant.notes.print.preview') }}"
                        class="h-9 px-3.5 rounded-xl text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                        <x-lucide-eye class="w-3.5 h-3.5" />
                        Aperçu
                    </a>
                    <a wire:navigate href="{{ route('tenant.notes.print.configuration') }}"
                        class="h-9 px-3.5 rounded-xl text-xs font-semibold bg-indigo-500/15 text-indigo-300 border border-indigo-500/25 hover:bg-indigo-500/25 transition-all inline-flex items-center gap-1.5">
                        <x-lucide-plus class="w-3.5 h-3.5" />
                        Générer
                    </a>
                </div>
            </div>
        </header>

        <section class="mb-6">
            @livewire('tenants.components.printable-documents-component')
        </section>

        <section class="mb-6 rounded-2xl bg-[#0f1523] border border-white/[0.06] p-4 sm:p-5 shadow-xl shadow-black/20">
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                <div class="relative flex-1">
                    <x-lucide-search
                        class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 pointer-events-none" />
                    <input type="search" wire:model.live.debounce.350ms="search"
                        placeholder="Rechercher un document par nom…"
                        class="w-full h-11 pl-10 pr-4 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/30 transition-all" />
                </div>

            </div>
        </section>

        <section class="relative min-h-[280px]">
            <div wire:loading.flex
                wire:target="search, deleteDocument, trackDownload, toggleOthersVisibility, confirmDelete, gotoPage, resetPage, nextPage, previousPage, scope"
                class="absolute inset-0 z-20 items-center justify-center rounded-2xl bg-[#070b14]/70 backdrop-blur-sm">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-9 h-9 rounded-full border-2 border-indigo-500/30 border-t-indigo-400 animate-spin">
                    </div>
                    <span class="text-xs font-mono text-slate-400 tracking-wide">Chargement…</span>
                </div>
            </div>

            <div wire:loading.class="opacity-40 pointer-events-none"
                wire:target="search, deleteDocument, trackDownload, toggleOthersVisibility, confirmDelete, gotoPage, resetPage, nextPage, previousPage, scope"
                class="transition-opacity duration-200">

                @if ($this->documents->isEmpty())
                    <div
                        class="rounded-2xl border border-dashed border-white/10 bg-[#0f1523]/50 px-6 py-16 text-center">
                        <div
                            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 border border-white/10">
                            <x-lucide-file-x class="w-6 h-6 text-slate-500" />
                        </div>
                        <p class="text-sm font-medium text-slate-300">Aucun document trouvé</p>
                        <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                            @if ($search !== '')
                                Aucun résultat pour « {{ $search }} ».
                            @else
                                Les fichiers de notes que vous générez apparaîtront ici.
                            @endif
                        </p>
                        <a wire:navigate href="{{ route('tenant.notes.print.configuration') }}"
                            class="mt-5 inline-flex items-center gap-1.5 h-9 px-4 rounded-xl text-xs font-medium bg-indigo-500/15 text-indigo-300 border border-indigo-500/25 hover:bg-indigo-500/25 transition-all">
                            <x-lucide-plus class="w-3.5 h-3.5" />
                            Générer un PDF
                        </a>
                    </div>
                @else
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <span
                            class="inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 text-[11px] font-mono uppercase tracking-wide bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            {{ $this->documents->total() }} document(s)
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($this->documents as $doc)
                            <article wire:key="doc-{{ $doc->id }}"
                                class="group relative flex flex-col rounded-2xl bg-[#0f1523] border border-white/[0.06] hover:border-indigo-500/30 transition-all duration-200 shadow-lg shadow-black/15 overflow-hidden">

                                <div class="p-5 flex-1">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-500/15 border border-indigo-500/25">
                                            <x-lucide-clipboard-list class="w-5 h-5 text-indigo-400" />
                                        </div>
                                        <div class="flex flex-col items-end gap-1.5">
                                            @if ($doc->downloaded)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    <x-lucide-check class="w-3 h-3" />
                                                    Téléchargé
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-white/5 text-slate-400 border border-white/10">
                                                    Nouveau
                                                </span>
                                            @endif
                                            @if ($doc->downloadable_by_others)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                                                    <x-lucide-globe class="w-3 h-3" />
                                                    Partagé
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h3 class="text-sm font-semibold text-white leading-snug line-clamp-2 mb-2"
                                        title="{{ $doc->filename }}">
                                        {{ str()->before(ucfirst(str_replace(['.pdf', '-'], ' ', $doc->filename)), '_') }}
                                    </h3>

                                    <div class="flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-slate-500 font-mono">
                                        <span class="inline-flex items-center gap-1">
                                            <x-lucide-calendar class="w-3 h-3" />
                                            {{ $doc->created_at?->timezone('Africa/Abidjan')->format('d/m/Y H:i') }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-amber-400/90">
                                            <x-lucide-download class="w-3 h-3" />
                                            {{ $doc->downloaded_count ?? 0 }}
                                        </span>
                                    </div>
                                </div>

                                <div class="px-5 py-3 border-t border-white/[0.06] flex items-center gap-2">
                                    <button type="button" wire:click="trackDownload({{ $doc->id }})"
                                        wire:loading.attr="disabled" wire:target="trackDownload({{ $doc->id }})"
                                        class="flex-1 h-9 rounded-xl text-xs font-semibold bg-indigo-500/15 text-indigo-300 border border-indigo-500/25 hover:bg-indigo-500/25 transition-all inline-flex items-center justify-center gap-1.5 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="trackDownload({{ $doc->id }})"
                                            class="inline-flex items-center gap-1.5">
                                            <x-lucide-download class="w-3.5 h-3.5" />
                                            Télécharger
                                        </span>
                                        <span wire:loading wire:target="trackDownload({{ $doc->id }})"
                                            class="inline-flex items-center gap-1.5">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                    <button type="button" wire:click="toggleOthersVisibility({{ $doc->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="toggleOthersVisibility({{ $doc->id }})"
                                        title="{{ $doc->downloadable_by_others ? 'Rendre privé' : 'Partager aux autres' }}"
                                        class="h-9 w-9 rounded-xl transition-all inline-flex items-center justify-center
                                            {{ $doc->downloadable_by_others
                                                ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/25 hover:bg-emerald-500/20'
                                                : 'text-slate-400 bg-white/5 border border-white/10 hover:bg-white/10' }}">
                                        @if ($doc->downloadable_by_others)
                                            <x-lucide-eye class="w-3.5 h-3.5" />
                                        @else
                                            <x-lucide-eye-off class="w-3.5 h-3.5" />
                                        @endif
                                    </button>
                                    <button type="button" wire:click="confirmDelete({{ $doc->id }})"
                                        class="h-9 w-9 rounded-xl text-rose-400 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500/20 transition-all inline-flex items-center justify-center"
                                        title="Supprimer">
                                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if ($this->documents->hasPages())
                        <div
                            class="mt-8 flex flex-col items-center gap-4 rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5">
                            <p class="text-xs text-slate-500 font-mono">
                                {{ $this->documents->firstItem() }} – {{ $this->documents->lastItem() }}
                                sur {{ $this->documents->total() }}
                            </p>
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                @if (!$this->documents->onFirstPage())
                                    <button wire:click="previousPage" wire:loading.attr="disabled"
                                        wire:target="previousPage"
                                        class="h-9 px-4 rounded-xl text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 disabled:opacity-50 transition-all">
                                        Précédent
                                    </button>
                                @endif
                                @foreach ($this->documents->getUrlRange(1, $this->documents->lastPage()) as $page => $url)
                                    <button @disabled($page === $this->documents->currentPage()) wire:click="gotoPage({{ $page }})"
                                        class="h-9 min-w-9 px-3 rounded-xl text-xs font-medium transition-all
                                            {{ $page === $this->documents->currentPage()
                                                ? 'bg-indigo-500/25 text-indigo-200 border border-indigo-500/40'
                                                : 'bg-white/5 text-slate-400 border border-white/10 hover:bg-white/10' }}">
                                        {{ $page }}
                                    </button>
                                @endforeach
                                @if ($this->documents->hasMorePages())
                                    <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                        class="h-9 px-4 rounded-xl text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 disabled:opacity-50 transition-all">
                                        Suivant
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </section>
    </div>
</div>

