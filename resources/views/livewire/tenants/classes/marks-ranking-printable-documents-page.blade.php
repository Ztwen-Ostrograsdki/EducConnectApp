<div class="p-4 md:p-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-lg md:text-xl font-bold text-slate-300">{{ $pageTitle }}</h1>
            <p class="text-sm text-slate-400 mt-1 inline-flex flex-col my-3 gap-y-3">
                <span class="inline-flex gap-3 justify-between items-center">
                    @if ($targeted)
                        <span
                            class="rounded-2xl py-1.5 px-3 bg-orange-600/30 text-orange-500 inline-flex uppercase text-center w-full items-center justify-center font-mono">
                            {{ $targeted }}
                        </span>
                    @endif
                </span>

                <span class="inline-flex gap-3 justify-between">
                    <span>
                        Retrouvez, téléchargez ou supprimez vos documents PDF générés.
                    </span>
                </span>
            </p>
        </div>

    </div>
    <div class="flex flex-wrap justify-center gap-2 mb-8">
        <a wire:navigate href="{{ route('tenant.students.portal') }}"
            class="h-9 px-3.5 rounded-lg text-xs font-medium bg-violet-500/15 text-violet-300 border border-violet-500/20 hover:bg-violet-500/25 transition-all inline-flex items-center gap-1.5">
            <x-lucide-users class="w-3.5 h-3.5" />
            Portail des apprenants
        </a>
        <a href="{{ route('tenant.students.bests.weaks.print.preview') }}"
            class="h-9 px-3.5 rounded-lg text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
            <x-lucide-eye class="w-3.5 h-3.5" />
            Aperçu du document
        </a>
        <a wire:navigate href="{{ route('tenant.students.bests.weaks.docs') }}"
            class="h-9 px-3.5 rounded-lg text-xs font-medium bg-amber-500/15 text-amber-300 border border-amber-500/20 hover:bg-amber-500/25 transition-all inline-flex items-center gap-1.5">
            <x-lucide-file class="w-3.5 h-3.5" />
            Fichiers disponibles
        </a>
    </div>
    <div class="relative w-full my-3">
        <x-lucide-search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" />
        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher un document..."
            class="w-full h-10 pl-9 pr-3 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 transition-colors">
    </div>

    <div wire:loading.class="opacity-20 pointer-events-none"
        wire:target="search, deleteDocument, gotoPage, resetPage, nextPage, previousPage"
        class="transition-opacity duration-200">

        @if ($this->documents->isEmpty())
            <div
                class="flex flex-col items-center justify-center py-20 text-center border border-dashed border-slate-700 rounded-2xl">
                <x-lucide-file-x class="w-12 h-12 text-slate-600 mb-3" />
                <p class="text-slate-400 font-mono">Aucun document trouvé.</p>
                <p class="text-slate-600 text-sm mt-1">Les listes que vous générez apparaîtront ici.</p>
            </div>
        @else
            <div class="my-3 flex justify-end items-center">
                <span
                    class="rounded-2xl py-1.5 px-6 bg-green-600/30 text-green-500 inline-flex uppercase text-center items-center justify-center font-mono animate-pulse">
                    {{ $this->documents->total() }} document(s) disponible(s)
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($this->documents as $doc)
                    <div wire:key="doc-{{ $doc->id }}"
                        class="group relative flex flex-col rounded-2xl border border-slate-700 bg-slate-800/60 p-4 transition-all duration-200 hover:border-indigo-500/60 hover:bg-slate-800 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-indigo-950/40">

                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div
                                class="flex items-center justify-center w-11 h-11 rounded-xl bg-sky-500/10 border border-sky-500/20 shrink-0">
                                <x-lucide-file-text class="w-5 h-5 text-sky-400" />
                            </div>

                            @if ($doc->downloaded)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-2xs font-semibold uppercase tracking-wide bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <x-lucide-check class="w-3 h-3" />
                                    Déjà téléchargé
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-2xs font-semibold uppercase tracking-wide bg-slate-700/50 text-slate-400 border border-slate-600">
                                    Nouveau
                                </span>
                            @endif
                        </div>

                        <h3 class="text-sm font-semibold text-white break-all mb-1" title="{{ $doc->filename }}">
                            {{ str()->before(ucfirst(str_replace(['.pdf', '-'], ' ', $doc->filename)), '_') }}
                        </h3>

                        <div class="flex items-center gap-3 text-xs text-slate-500 font-mono mb-4">
                            <span class="inline-flex items-center gap-1">
                                <x-lucide-calendar class="w-3.5 h-3.5" />
                                <span class="text-2xs">Généré le
                                    {{ ucwords($doc->created_at->isoFormat('dddd D MMMM YYYY [à] HH:mm')) }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1 text-yellow-500">
                                <x-lucide-download class="w-3.5 h-3.5" />
                                {{ $doc->downloaded_count }}
                            </span>
                        </div>

                        <div
                            class="mt-auto grid grid-cols-7  items-center gap-2 pt-3 border-t border-slate-700/60 w-full text-sm font-mono">
                            <button wire:click="trackDownload({{ $doc->id }})" wire:loading.attr="disabled"
                                wire:target="trackDownload({{ $doc->id }})"
                                class="items-center justify-center p-1.5 rounded-lg bg-indigo-600/30 hover:bg-indigo-500 text-white hover:text-black border border-slate-950 font-semibold transition-all active:scale-95 disabled:opacity-50 gap-2 col-span-4">
                                <span wire:loading.remove wire:target="trackDownload({{ $doc->id }})"
                                    class="inline-flex items-center gap-1.5 ">
                                    <x-lucide-download class="w-3.5 h-3.5" />
                                    Télécharger
                                </span>
                                <span wire:loading wire:target="trackDownload({{ $doc->id }})"
                                    class="inline-flex items-center gap-1.5 ">
                                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin" />
                                </span>
                            </button>

                            <button wire:click="confirmDelete({{ $doc->id }})" title="Supprimer ce document"
                                class="flex items-center justify-center p-1.5 rounded-lg bg-slate-700/50 hover:bg-red-500/20 text-slate-400 hover:text-red-400 border border-slate-600 hover:border-red-500/40 transition-all active:scale-95 col-span-3">
                                <span class="flex items-center gap-2">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                    <span>Supprimer</span>
                                </span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                @if ($this->documents->hasPages())
                    <section class="py-6 p-2">
                        <div class="flex justify-center bg-slate-900 p-4">
                            <div class="flex flex-col justify-center gap-4">
                                <div class="text-sm text-slate-400">
                                    Affichage {{ $this->documents->firstItem() }} à
                                    {{ $this->documents->lastItem() }}
                                    sur
                                    {{ $this->documents->total() }} documents
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if (!$this->documents->onFirstPage())
                                        <button wire:click="previousPage" wire:loading.attr="disabled"
                                            wire:target="previousPage"
                                            class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                            Précédent
                                        </button>
                                    @endif

                                    @foreach ($this->documents->getUrlRange(1, $this->documents->lastPage()) as $page => $url)
                                        <button @disabled($page === $this->documents->currentPage()) wire:click="gotoPage({{ $page }})"
                                            class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->documents->currentPage() ? 'bg-indigo-500 text-white ' : 'bg-slate-800 hover:bg-slate-700' }}">
                                            {{ $page }}
                                        </button>
                                    @endforeach

                                    @if ($this->documents->hasMorePages())
                                        <button wire:click="nextPage" wire:loading.attr="disabled"
                                            wire:target="nextPage"
                                            class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                            Suivant
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
            </div>
        @endif
    </div>
</div>

