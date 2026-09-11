<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Gestion de la galerie</h1>
            <p class="mt-1 text-sm text-slate-400">{{ $galleries->total() }} image(s) au total</p>
        </div>
        <a href="{{ route('tenant.galleries.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
            + Ajouter des images
        </a>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Rechercher par titre ou description..."
                class="w-full rounded-lg bg-[#0f1523] border border-white/10 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition">
        </div>

        <select wire:model.live="status"
            class="rounded-lg bg-[#0f1523] border border-white/10 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition">
            <option value="all">Tous les statuts</option>
            <option value="visible">Visibles uniquement</option>
            <option value="hidden">Masqués uniquement</option>
        </select>

        @if ($search || $status !== 'all')
            <button type="button" wire:click="clearFilters"
                class="rounded-lg border border-white/10 px-4 py-2.5 text-sm text-slate-400 hover:text-white hover:bg-white/5 transition">
                Réinitialiser
            </button>
        @endif
    </div>

    <div wire:loading.flex wire:target="search,status" class="justify-center py-8">
        <div class="text-sm text-indigo-400">Chargement...</div>
    </div>

    <div wire:loading.remove wire:target="search,status">
        @if ($galleries->isEmpty())
            <div class="rounded-xl bg-[#0f1523] border border-white/10 p-12 text-center">
                <p class="text-slate-500 text-sm">Aucune image trouvée.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($galleries as $gallery)
                    <div class="group relative rounded-xl bg-[#0f1523] border border-white/10 overflow-hidden"
                        wire:key="gallery-{{ $gallery->uuid }}">
                        <div class="aspect-[4/3] bg-slate-900 relative">
                            <img src="{{ $gallery->path_url }}" alt="{{ $gallery->title ?? 'Image galerie' }}"
                                class="w-full h-full object-cover">
                            @if ($gallery->hidden)
                                <span
                                    class="absolute top-2 left-2 rounded-md bg-rose-500/90 px-2 py-0.5 text-[10px] font-bold uppercase text-white">
                                    Masqué
                                </span>
                            @endif
                        </div>

                        <div class="p-3">
                            <h3 class="text-sm font-semibold text-white truncate">
                                {{ $gallery->title ?: 'Sans titre' }}
                            </h3>
                            @if ($gallery->description)
                                <p class="mt-0.5 text-xs text-slate-500 line-clamp-2">{{ $gallery->description }}</p>
                            @endif
                            <p class="mt-1.5 text-[10px] text-slate-600">
                                {{ $gallery->created_at?->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition">
                            @if ($gallery->hidden)
                                <button type="button" wire:click="unhideGallery('{{ $gallery->uuid }}')"
                                    class="rounded-lg bg-emerald-600/90 p-1.5 text-white hover:bg-emerald-500 transition"
                                    title="Rendre visible">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            @else
                                <button type="button" wire:click="hideGallery('{{ $gallery->uuid }}')"
                                    class="rounded-lg bg-amber-600/90 p-1.5 text-white hover:bg-amber-500 transition"
                                    title="Masquer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            @endif

                            <button type="button" wire:click="deleteGallery('{{ $gallery->uuid }}')"
                                class="rounded-lg bg-rose-600/90 p-1.5 text-white hover:bg-rose-500 transition"
                                title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
</div>

