<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 relative">

        {{-- ════════════════ HEADER ════════════════ --}}
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <div
                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-300 text-[10px] font-semibold uppercase tracking-wider mb-2">
                    <x-lucide-images class="w-3 h-3" />
                    Médias
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Galerie
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    <span class="font-semibold text-slate-300 tabular-nums">{{ $galleries->total() }}</span>
                    image{{ $galleries->total() > 1 ? 's' : '' }}
                </p>
            </div>

            <a href="{{ route('tenant.galleries.create') }}"
                class="h-11 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition-all active:scale-[0.98] inline-flex items-center gap-2 shrink-0">
                <x-lucide-upload class="w-4 h-4" />
                Ajouter des images
            </a>
        </header>

        {{-- ════════════════ FILTRES STICKY ════════════════ --}}
        <div
            class="sticky top-2 z-10 mb-8 rounded-2xl bg-[#0e1219]/90 backdrop-blur-xl border border-white/[0.06] p-2 shadow-xl shadow-black/30">
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <x-lucide-search
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-600 pointer-events-none" />
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Titre ou description…"
                        class="w-full h-10 rounded-xl bg-transparent border-0 pl-10 pr-10 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-0">
                    <span wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <span
                            class="block w-2 h-2 rounded-full bg-indigo-400 shadow-[0_0_10px_2px_rgba(129,140,248,0.7)] animate-pulse"></span>
                    </span>
                </div>

                <div class="flex items-center gap-1 p-0.5 rounded-xl bg-white/5">
                    @foreach ([
        'all' => 'Tous',
        'visible' => 'Visibles',
        'hidden' => 'Masqués',
    ] as $val => $label)
                        <button type="button" wire:click="$set('status', '{{ $val }}')"
                            class="h-9 px-3 rounded-lg text-xs font-medium transition-all
                                       {{ $status === $val
                                           ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/40'
                                           : 'text-slate-500 hover:text-slate-300' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                @if ($search || $status !== 'all')
                    <button type="button" wire:click="clearFilters"
                        class="h-10 w-10 rounded-xl text-slate-500 hover:text-white hover:bg-white/5 transition-all flex items-center justify-center shrink-0">
                        <x-lucide-rotate-ccw class="w-4 h-4" />
                    </button>
                @endif
            </div>
        </div>

        {{-- ════════════════ GRILLE ════════════════ --}}
        <div class="relative min-h-[240px]">

            {{-- Overlay lueur --}}
            <div wire:loading.flex
                wire:target="search,status,clearFilters,hideGallery,unhideGallery,deleteGallery,gotoPage,previousPage,nextPage"
                class="absolute inset-0 z-20 items-center justify-center">
                <div class="relative">
                    <div class="absolute -inset-8 rounded-full bg-violet-500/25 blur-2xl animate-pulse"></div>
                    <div
                        class="relative w-11 h-11 rounded-full border-2 border-violet-400/30 border-t-violet-300 animate-spin shadow-[0_0_28px_rgba(167,139,250,0.5)]">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
                wire:loading.class="opacity-30 blur-[1px] pointer-events-none transition-all duration-300"
                wire:target="search,status,clearFilters,hideGallery,unhideGallery,deleteGallery,gotoPage,previousPage,nextPage">

                @forelse ($galleries as $gallery)
                    <article wire:key="gallery-{{ $gallery->uuid }}"
                        class="group relative rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden hover:border-violet-500/25 transition-all duration-200 shadow-lg shadow-black/10">

                        {{-- Image --}}
                        <div class="relative aspect-[4/3] bg-[#070b14] overflow-hidden">
                            <img src="{{ $gallery->path_url }}" alt="{{ $gallery->title ?? 'Image galerie' }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                            {{-- Gradient bas --}}
                            <div
                                class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-[#0f1523] via-[#0f1523]/60 to-transparent pointer-events-none">
                            </div>

                            {{-- Badge statut --}}
                            @if ($gallery->hidden)
                                <span
                                    class="absolute top-2.5 left-2.5 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-500/90 backdrop-blur-sm text-[10px] font-bold uppercase text-white">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    Masqué
                                </span>
                            @else
                                <span
                                    class="absolute top-2.5 left-2.5 opacity-0 group-hover:opacity-100 transition-opacity inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/90 backdrop-blur-sm text-[10px] font-bold uppercase text-white">
                                    Visible
                                </span>
                            @endif

                            {{-- Actions flottantes --}}
                            <div
                                class="absolute top-2.5 right-2.5 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-all duration-200 translate-y-1 group-hover:translate-y-0">
                                @if ($gallery->hidden)
                                    <button type="button" wire:click="unhideGallery('{{ $gallery->uuid }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="unhideGallery('{{ $gallery->uuid }}')" title="Rendre visible"
                                        class="w-8 h-8 rounded-lg bg-emerald-600/90 hover:bg-emerald-500 backdrop-blur-sm text-white flex items-center justify-center transition-all disabled:opacity-50 shadow-lg">
                                        <span wire:loading.remove wire:target="unhideGallery('{{ $gallery->uuid }}')">
                                            <x-lucide-eye class="w-3.5 h-3.5" />
                                        </span>
                                        <span wire:loading wire:target="unhideGallery('{{ $gallery->uuid }}')"
                                            class="relative flex h-3.5 w-3.5">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-40"></span>
                                            <x-lucide-loader-2 class="relative w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                @else
                                    <button type="button" wire:click="hideGallery('{{ $gallery->uuid }}')"
                                        wire:loading.attr="disabled" wire:target="hideGallery('{{ $gallery->uuid }}')"
                                        title="Masquer"
                                        class="w-8 h-8 rounded-lg bg-amber-600/90 hover:bg-amber-500 backdrop-blur-sm text-white flex items-center justify-center transition-all disabled:opacity-50 shadow-lg">
                                        <span wire:loading.remove wire:target="hideGallery('{{ $gallery->uuid }}')">
                                            <x-lucide-eye-off class="w-3.5 h-3.5" />
                                        </span>
                                        <span wire:loading wire:target="hideGallery('{{ $gallery->uuid }}')"
                                            class="relative flex h-3.5 w-3.5">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-40"></span>
                                            <x-lucide-loader-2 class="relative w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                @endif

                                <button type="button" wire:click="deleteGallery('{{ $gallery->uuid }}')"
                                    wire:loading.attr="disabled" wire:target="deleteGallery('{{ $gallery->uuid }}')"
                                    title="Supprimer"
                                    class="w-8 h-8 rounded-lg bg-rose-600/90 hover:bg-rose-500 backdrop-blur-sm text-white flex items-center justify-center transition-all disabled:opacity-50 shadow-lg">
                                    <span wire:loading.remove wire:target="deleteGallery('{{ $gallery->uuid }}')">
                                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                    </span>
                                    <span wire:loading wire:target="deleteGallery('{{ $gallery->uuid }}')"
                                        class="relative flex h-3.5 w-3.5">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-40"></span>
                                        <x-lucide-loader-2 class="relative w-3.5 h-3.5 animate-spin" />
                                    </span>
                                </button>
                            </div>
                        </div>

                        {{-- Meta --}}
                        <div class="p-3.5">
                            <h3 class="text-sm font-semibold text-white truncate">
                                {{ $gallery->title ?: 'Sans titre' }}
                            </h3>
                            @if ($gallery->description)
                                <p class="mt-1 text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $gallery->description }}
                                </p>
                            @endif
                            <p class="mt-2 text-[10px] font-mono text-slate-600">
                                {{ $gallery->created_at?->format('d/m/Y · H:i') }}
                            </p>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl bg-[#0f1523] border border-white/[0.06] py-20 text-center">
                        <div
                            class="w-14 h-14 mx-auto rounded-2xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center mb-4">
                            <x-lucide-image-off class="w-6 h-6 text-violet-400/50" />
                        </div>
                        <p class="text-sm text-slate-500">Aucune image trouvée</p>
                        <a href="{{ route('tenant.galleries.create') }}"
                            class="mt-4 inline-flex h-9 px-4 rounded-xl bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-500/25 text-indigo-300 text-xs font-medium transition-all items-center gap-1.5">
                            <x-lucide-upload class="w-3.5 h-3.5" />
                            Ajouter des images
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        @if ($galleries->hasPages())
            <div class="mt-8 flex justify-center sm:justify-end">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
</div>
