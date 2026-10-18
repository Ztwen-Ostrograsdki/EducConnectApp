<div class="min-h-screen bg-[#070b14] text-slate-100" wire:key="my-epreuves">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6">

        {{-- Header --}}
        <header
            class="relative overflow-hidden rounded-2xl border border-white/5 bg-gradient-to-br from-slate-900 via-[#0c1525] to-[#0a1220] px-6 py-7 shadow-2xl shadow-black/40">
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-indigo-500/15 blur-3xl"></div>
                <div class="absolute -left-12 bottom-0 h-40 w-40 rounded-full bg-cyan-500/10 blur-3xl"></div>
            </div>
            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-500/10 ring-1 ring-indigo-400/30">
                        <x-lucide-history class="h-6 w-6 text-indigo-400" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-white">Mes épreuves envoyées</h1>
                        <p class="mt-1 text-sm text-slate-400">Historique de vos envois. Filtrez par année, période,
                            classe…</p>
                    </div>
                </div>
                <a href="{{ route('tenant.send.epreuves') }}"
                    class="inline-flex items-center gap-2 self-start rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-cyan-600/20 transition hover:bg-cyan-500">
                    <x-lucide-upload-cloud class="h-4 w-4" />
                    Nouvel envoi
                </a>
            </div>
        </header>

        {{-- Filtres --}}
        <section class="rounded-2xl border border-white/5 bg-[#0c1220] p-5 shadow-xl shadow-black/30">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <x-lucide-filter class="h-4 w-4 text-slate-400" />
                    <span class="text-sm font-semibold text-slate-200">Filtres</span>
                </div>
                <button type="button" wire:click="clearFilters"
                    class="inline-flex items-center gap-1.5 text-xs text-slate-400 transition hover:text-cyan-400">
                    <x-lucide-rotate-ccw class="h-3.5 w-3.5" />
                    Réinitialiser
                </button>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="sm:col-span-2">
                    <x-input icon="magnifying-glass" placeholder="Rechercher (nom, classe, matière…)"
                        wire:model.live.debounce.400ms="search" />
                </div>

                <x-select placeholder="Année scolaire" wire:model.live="school_year_id">
                    @foreach ($this->schoolYears as $y)
                        <x-select.option :value="$y->id" :label="$y->slug ?? $y->min_year . '-' . $y->max_year" />
                    @endforeach
                </x-select>

                <x-select placeholder="Période" wire:model.live="period">
                    <x-select.option value="" label="Toutes les périodes" />
                    @foreach ($this->periods as $p)
                        <x-select.option :value="$p['index']" :label="$p['label']" />
                    @endforeach
                </x-select>

                <x-select placeholder="Classe" wire:model.live="classe_id" option-label="name" option-value="id"
                    :options="$this->classes" />
                <x-select placeholder="Filière" wire:model.live="filiar_id" option-label="name" option-value="id"
                    :options="$this->filiars" />
                <x-select placeholder="Promotion" wire:model.live="promotion_id" option-label="name" option-value="id"
                    :options="$this->promotions" />
                <x-select placeholder="Matière" wire:model.live="subject_id" option-label="name" option-value="id"
                    :options="$this->subjects" />
                <x-input placeholder="Type d'épreuve" wire:model.live.debounce.400ms="type" />
            </div>
        </section>

        {{-- Actions de masse (enseignant) --}}
        @if (count($selected) > 0)
            <div class="flex flex-wrap items-center gap-2 rounded-xl border border-cyan-500/20 bg-cyan-500/5 px-4 py-3">
                <span class="mr-2 text-xs font-medium text-cyan-300">Actions sur la sélection :</span>
                <x-button xs primary label="Publier" wire:click="bulkPublish" icon="eye" />
                <x-button xs secondary label="Dépublier" wire:click="bulkUnpublish" icon="eye-slash" />
                <x-button xs secondary label="Téléchargeable" wire:click="bulkMakeDownloadable" icon="share" />
                <x-button xs secondary label="Privé" wire:click="bulkMakePrivate" icon="lock-closed" />
                <x-button xs negative label="Supprimer" wire:click="confirmBulkDelete" icon="trash" />
            </div>
        @endif

        {{-- Table --}}
        <section class="overflow-hidden rounded-2xl border border-white/5 bg-[#0c1220] shadow-xl shadow-black/30">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5 text-sm w-full">
                    <thead class="bg-[#080e1a] w-full">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <input type="checkbox" wire:click="toggleSelectAll"
                                    class="rounded border-slate-600 bg-slate-800 text-cyan-500 focus:ring-cyan-500/40" />
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Fichier</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Contexte</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Période / Type</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Statuts</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Envoyé le</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($this->epreuves as $epreuve)
                            <tr wire:key="my-epreuve-{{ $epreuve->id }}" class="transition hover:bg-white/[0.03]">
                                <td class="px-4 py-3">
                                    <input type="checkbox" wire:model.live="selected" value="{{ $epreuve->id }}"
                                        class="rounded border-slate-600 bg-slate-800 text-cyan-500 focus:ring-cyan-500/40" />
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-800 text-slate-400 ring-1 ring-white/5">
                                            <x-lucide-file class="h-4 w-4" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-slate-200"
                                                title="{{ $epreuve->filename }}">{{ $epreuve->filename }}</p>
                                            <p class="text-xs text-slate-500">Lot #{{ $epreuve->lot ?? '—' }} ·
                                                {{ $epreuve->downloaded_count }} dl</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-0.5 text-xs text-slate-400">
                                        <p>{{ $epreuve->classe?->name ?? ($epreuve->classe_name ?? '—') }}</p>
                                        <p>{{ $epreuve->subject?->name ?? '—' }} · {{ $epreuve->filiar?->name ?? '—' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-0.5 text-xs">
                                        <p class="text-slate-300">
                                            {{ $epreuve->period_as_label ?? 'P' . $epreuve->period }}</p>
                                        <p class="text-slate-500">{{ $epreuve->type }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @if ($epreuve->published)
                                            <span
                                                class="inline-flex items-center rounded-full bg-emerald-500/15 px-2 py-0.5 text-[10px] font-medium text-emerald-400 ring-1 ring-emerald-500/25">Publié</span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-slate-500/15 px-2 py-0.5 text-[10px] font-medium text-slate-400 ring-1 ring-slate-500/25">Privé</span>
                                        @endif
                                        @if ($epreuve->downloadable_by_others)
                                            <span
                                                class="inline-flex items-center rounded-full bg-sky-500/15 px-2 py-0.5 text-[10px] font-medium text-sky-400 ring-1 ring-sky-500/25">Partagé</span>
                                        @endif
                                        @if ($epreuve->locked)
                                            <span
                                                class="inline-flex items-center gap-0.5 rounded-full bg-amber-500/15 px-2 py-0.5 text-[10px] font-medium text-amber-400 ring-1 ring-amber-500/25">
                                                <x-lucide-lock class="h-2.5 w-2.5" />
                                                Verrouillé
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-400">
                                    {{ $epreuve->created_at?->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button wire:click="trackDownload({{ $epreuve->id }})"
                                            class="rounded-lg p-1.5 text-slate-400 transition hover:bg-cyan-500/10 hover:text-cyan-400"
                                            title="Télécharger">
                                            <x-lucide-download class="h-4 w-4" />
                                        </button>
                                        <button wire:click="togglePublished({{ $epreuve->id }})"
                                            class="rounded-lg p-1.5 text-slate-400 transition hover:bg-emerald-500/10 hover:text-emerald-400"
                                            title="{{ $epreuve->published ? 'Dépublier' : 'Publier' }}">
                                            <x-lucide-eye class="h-4 w-4" />
                                        </button>
                                        <button wire:click="toggleOthersVisibility({{ $epreuve->id }})"
                                            class="rounded-lg p-1.5 text-slate-400 transition hover:bg-sky-500/10 hover:text-sky-400"
                                            title="Partage">
                                            <x-lucide-share-2 class="h-4 w-4" />
                                        </button>
                                        @if (!$epreuve->locked)
                                            <button wire:click="confirmDelete({{ $epreuve->id }})"
                                                class="rounded-lg p-1.5 text-slate-400 transition hover:bg-rose-500/10 hover:text-rose-400"
                                                title="Supprimer">
                                                <x-lucide-trash-2 class="h-4 w-4" />
                                            </button>
                                        @else
                                            <span class="rounded-lg p-1.5 text-slate-600"
                                                title="Verrouillé — suppression impossible">
                                                <x-lucide-lock class="h-4 w-4" />
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <x-lucide-inbox class="h-10 w-10 text-slate-600" />
                                        <p class="text-sm text-slate-500">Vous n’avez encore envoyé aucune épreuve pour
                                            ces filtres.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->epreuves->hasPages())
                <div class="border-t border-white/5 px-4 py-3">
                    {{ $this->epreuves->links() }}
                </div>
            @endif
        </section>
    </div>
</div>

