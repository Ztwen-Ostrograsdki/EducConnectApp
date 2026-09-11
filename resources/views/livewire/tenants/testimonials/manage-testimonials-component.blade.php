<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Gestion des témoignages</h1>
            <p class="mt-1 text-sm text-slate-400">{{ $testimonials->total() }} témoignage(s) au total</p>
        </div>
        <a href="{{ route('tenant.testimonials.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
            + Ajouter un témoignage
        </a>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher dans le contenu..."
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

    <div wire:loading.remove wire:target="search,status" class="space-y-3">
        @if ($testimonials->isEmpty())
            <div class="rounded-xl bg-[#0f1523] border border-white/10 p-12 text-center">
                <p class="text-slate-500 text-sm">Aucun témoignage trouvé.</p>
            </div>
        @else
            @foreach ($testimonials as $testimonial)
                <div class="rounded-xl bg-[#0f1523] border border-white/10 p-4 sm:p-5"
                    wire:key="testimonial-{{ $testimonial->uuid }}">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                @if ($testimonial->hidden)
                                    <span
                                        class="rounded-md bg-rose-500/20 px-2 py-0.5 text-[10px] font-bold uppercase text-rose-400">
                                        Masqué
                                    </span>
                                @else
                                    <span
                                        class="rounded-md bg-emerald-500/20 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-400">
                                        Visible
                                    </span>
                                @endif
                                <span class="text-[11px] text-slate-500">
                                    {{ $testimonial->user?->name ?? 'Anonyme' }} ·
                                    {{ $testimonial->created_at?->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-300 leading-relaxed whitespace-pre-line">
                                {{ $testimonial->content }}</p>
                        </div>

                        <div class="flex sm:flex-col gap-2 shrink-0">
                            @if ($testimonial->hidden)
                                <button type="button" wire:click="unhideTestimonial('{{ $testimonial->uuid }}')"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-600/20 border border-emerald-500/30 px-3 py-1.5 text-xs font-medium text-emerald-400 hover:bg-emerald-600/30 transition">
                                    Rendre visible
                                </button>
                            @else
                                <button type="button" wire:click="hideTestimonial('{{ $testimonial->uuid }}')"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-amber-600/20 border border-amber-500/30 px-3 py-1.5 text-xs font-medium text-amber-400 hover:bg-amber-600/30 transition">
                                    Masquer
                                </button>
                            @endif

                            <button type="button" wire:click="deleteTestimonial('{{ $testimonial->uuid }}')"
                                class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-rose-600/20 border border-rose-500/30 px-3 py-1.5 text-xs font-medium text-rose-400 hover:bg-rose-600/30 transition">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-6">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</div>

