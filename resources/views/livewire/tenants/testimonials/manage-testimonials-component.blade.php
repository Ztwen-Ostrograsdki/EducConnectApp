<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 relative">

        {{-- ════════════════ HEADER ════════════════ --}}
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">Témoignages</h1>
                <p class="mt-1 text-sm text-slate-500">
                    <span class="text-slate-300 font-semibold tabular-nums">{{ $testimonials->total() }}</span>
                    au total
                </p>
            </div>
            <a href="{{ route('tenant.testimonials.create') }}"
                class="h-11 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition-all inline-flex items-center gap-2 shrink-0">
                <x-lucide-plus class="w-4 h-4" />
                Ajouter un témoignage
            </a>
        </header>

        {{-- ════════════════ NAVIGATION (conservée) ════════════════ --}}
        <div
            class="sticky top-2 z-10 mb-8 rounded-2xl bg-[#0e1219]/90 backdrop-blur-xl border border-white/[0.06] p-2 shadow-xl shadow-black/30">
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <x-lucide-search
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-600 pointer-events-none" />
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Filtrer les témoignages…"
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
                wire:target="search,status,clearFilters,hideTestimonial,unhideTestimonial,deleteTestimonial,gotoPage,previousPage,nextPage"
                class="absolute inset-0 z-20 items-center justify-center">
                <div class="relative">
                    <div class="absolute -inset-8 rounded-full bg-indigo-500/25 blur-2xl animate-pulse"></div>
                    <div
                        class="relative w-11 h-11 rounded-full border-2 border-indigo-400/30 border-t-indigo-300 animate-spin shadow-[0_0_28px_rgba(129,140,248,0.55)]">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4"
                wire:loading.class="opacity-30 blur-[1px] pointer-events-none transition-all duration-300"
                wire:target="search,status,clearFilters,hideTestimonial,unhideTestimonial,deleteTestimonial,gotoPage,previousPage,nextPage">

                @forelse ($testimonials as $testimonial)
                    @php
                        $author = $testimonial->user;
                        $authorName = $author?->name ?? 'Anonyme';
                        $authorPhoto = $author?->profil_photo_url ?? ($author?->profile_photo_url ?? null);
                        $initial = strtoupper(mb_substr($authorName, 0, 1));
                    @endphp

                    <article wire:key="testimonial-{{ $testimonial->uuid }}"
                        class="group flex flex-col rounded-2xl bg-[#0f1523] border border-white/[0.06] hover:border-indigo-500/25 transition-all duration-200 overflow-hidden shadow-lg shadow-black/10">

                        {{-- Bandeau statut --}}
                        <div class="h-1 w-full {{ $testimonial->hidden ? 'bg-rose-500/60' : 'bg-emerald-500/60' }}">
                        </div>

                        <div class="flex flex-col flex-1 p-5">
                            {{-- Citation --}}
                            <div class="flex-1">
                                <x-lucide-quote class="w-5 h-5 text-indigo-500/40 mb-3" />
                                <p class="text-sm text-slate-300 leading-relaxed whitespace-pre-line line-clamp-5">
                                    {{ $testimonial->content }}
                                </p>
                            </div>

                            {{-- Footer auteur + actions --}}
                            <div class="mt-5 pt-4 border-t border-white/[0.05] flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    @if ($authorPhoto)
                                        <img src="{{ $authorPhoto }}" alt="{{ $authorName }}"
                                            class="w-9 h-9 rounded-full object-cover ring-2 ring-white/10 shrink-0">
                                    @else
                                        <div
                                            class="w-9 h-9 rounded-full bg-indigo-500/15 border border-indigo-500/25 flex items-center justify-center text-xs font-bold text-indigo-300 shrink-0">
                                            {{ $initial }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-white truncate">{{ $authorName }}</p>
                                        <p class="text-[10px] text-slate-600 font-mono">
                                            {{ $testimonial->created_at?->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 shrink-0">
                                    @if ($testimonial->hidden)
                                        <button type="button"
                                            wire:click="unhideTestimonial('{{ $testimonial->uuid }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="unhideTestimonial('{{ $testimonial->uuid }}')"
                                            title="Rendre visible"
                                            class="h-8 w-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-400 transition-all flex items-center justify-center disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="unhideTestimonial('{{ $testimonial->uuid }}')">
                                                <x-lucide-eye class="w-3.5 h-3.5" />
                                            </span>
                                            <span wire:loading
                                                wire:target="unhideTestimonial('{{ $testimonial->uuid }}')"
                                                class="relative flex h-3.5 w-3.5">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-50"></span>
                                                <x-lucide-loader-2 class="relative w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>
                                    @else
                                        <button type="button" wire:click="hideTestimonial('{{ $testimonial->uuid }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="hideTestimonial('{{ $testimonial->uuid }}')" title="Masquer"
                                            class="h-8 w-8 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 text-amber-400 transition-all flex items-center justify-center disabled:opacity-50">
                                            <span wire:loading.remove
                                                wire:target="hideTestimonial('{{ $testimonial->uuid }}')">
                                                <x-lucide-eye-off class="w-3.5 h-3.5" />
                                            </span>
                                            <span wire:loading
                                                wire:target="hideTestimonial('{{ $testimonial->uuid }}')"
                                                class="relative flex h-3.5 w-3.5">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-50"></span>
                                                <x-lucide-loader-2 class="relative w-3.5 h-3.5 animate-spin" />
                                            </span>
                                        </button>
                                    @endif

                                    <button type="button" wire:click="deleteTestimonial('{{ $testimonial->uuid }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="deleteTestimonial('{{ $testimonial->uuid }}')" title="Supprimer"
                                        class="h-8 w-8 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 transition-all flex items-center justify-center disabled:opacity-50">
                                        <span wire:loading.remove
                                            wire:target="deleteTestimonial('{{ $testimonial->uuid }}')">
                                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                        </span>
                                        <span wire:loading wire:target="deleteTestimonial('{{ $testimonial->uuid }}')"
                                            class="relative flex h-3.5 w-3.5">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-50"></span>
                                            <x-lucide-loader-2 class="relative w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 rounded-2xl bg-[#0f1523] border border-white/[0.06] py-20 text-center">
                        <x-lucide-message-square-off class="w-8 h-8 text-slate-600 mx-auto mb-3" />
                        <p class="text-sm text-slate-500">Aucun témoignage trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if ($testimonials->hasPages())
            <div class="mt-8 flex justify-center sm:justify-end">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</div>
