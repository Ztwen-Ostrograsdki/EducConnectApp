<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="mx-auto max-w-[1100px] px-4 sm:px-6 py-10 relative">

        {{-- Header --}}
        <header class="mb-10 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-[11px] font-semibold uppercase tracking-[0.2em] mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                Documents
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                Documents téléchargeables
            </h1>
            <p class="mt-3 text-slate-500 text-sm max-w-lg mx-auto">
                Stats, emplois du temps, listes d’apprenants, listes de classes et classements
                de l’année
                <span class="text-slate-300 font-medium">{{ $this->activeYear?->slug ?? '—' }}</span>.
            </p>
        </header>

        @if (auth('tenant')->user()->hasRole('directeur'))
            <section class="mb-6">
                @livewire('tenants.components.printable-documents-component')
            </section>
        @endif
        <section
            class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20 mb-6">
            <div class="p-5 flex flex-col gap-4">
                <div class="relative flex-1">
                    <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input type="search" wire:model.live.debounce.300ms="search"
                        placeholder="Rechercher par nom de fichier…"
                        class="w-full h-11 pl-10 pr-4 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 transition-all" />
                </div>

                <div class="flex flex-wrap items-center gap-1.5 p-1 rounded-xl bg-[#070b14] border border-white/10">
                    <button type="button" wire:click="$set('scope', 'all')"
                        class="h-9 px-3 rounded-lg text-xs font-medium transition-all
                               {{ $scope === 'all' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'text-slate-500 hover:text-slate-300' }}">
                        Tous
                    </button>
                    <button type="button" wire:click="$set('scope', 'stats')"
                        class="h-9 px-3 rounded-lg text-xs font-medium transition-all
                               {{ $scope === 'stats' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'text-slate-500 hover:text-slate-300' }}">
                        Stats
                    </button>
                    <button type="button" wire:click="$set('scope', 'timeplans')"
                        class="h-9 px-3 rounded-lg text-xs font-medium transition-all
                               {{ $scope === 'timeplans' ? 'bg-violet-500/20 text-violet-300 border border-violet-500/30' : 'text-slate-500 hover:text-slate-300' }}">
                        Emplois du temps
                    </button>
                    <button type="button" wire:click="$set('scope', 'students')"
                        class="h-9 px-3 rounded-lg text-xs font-medium transition-all
                               {{ $scope === 'students' ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'text-slate-500 hover:text-slate-300' }}">
                        Apprenants
                    </button>
                    <button type="button" wire:click="$set('scope', 'classes')"
                        class="h-9 px-3 rounded-lg text-xs font-medium transition-all
                               {{ $scope === 'classes' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'text-slate-500 hover:text-slate-300' }}">
                        Classes
                    </button>
                    <button type="button" wire:click="$set('scope', 'ranking')"
                        class="h-9 px-3 rounded-lg text-xs font-medium transition-all
                               {{ $scope === 'ranking' ? 'bg-orange-500/20 text-orange-300 border border-orange-500/30' : 'text-slate-500 hover:text-slate-300' }}">
                        Meilleurs élèves
                    </button>
                </div>
            </div>
        </section>

        <section class="my-2 mx-auto flex items-center w-full p-2">
            <div wire:loading.flex
                wire:target="trackDownload, confirmDelete, deleteDocument, toggleOthersVisibility, search, scope"
                class="items-center justify-center bg-[#070b14]/80 backdrop-blur-sm">
                <div class="flex flex-col items-center gap-4">
                    <div class="w-8 h-8 rounded-full border-2 border-orange-500/30 border-t-orange-400 animate-spin">
                    </div>
                    <span class="text-sm font-mono text-slate-400 tracking-wide">Chargement…</span>
                </div>
            </div>
        </section>

        {{-- Liste --}}
        @if ($this->documents->isEmpty())
            <div class="rounded-2xl border border-dashed border-white/10 bg-[#0f1523]/50 px-6 py-16 text-center">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 border border-white/10">
                    <x-lucide-file-x class="w-6 h-6 text-slate-500" />
                </div>
                <p class="text-sm font-medium text-slate-300">Aucun document trouvé</p>
                <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                    @if ($search !== '' || $scope !== 'all')
                        Aucun résultat pour ces critères. Modifiez la recherche ou le filtre.
                    @else
                        Aucun document partagé pour l’année en cours.
                        Les listes, stats et emplois du temps apparaîtront ici une fois générés et partagés.
                    @endif
                </p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4" wire:loading.class="opacity-20"
                wire:target="trackDownload, confirmDelete, deleteDocument, toggleOthersVisibility, search, scope">
                @foreach ($this->documents as $doc)
                    @php
                        $meta = \App\Livewire\Tenants\UserDownloadableDocumentsPage::typeMeta($doc->type ?? '');
                        $canDelete = (int) $doc->user_id === (int) auth('tenant')->id();
                        $iconBox = match ($doc->type) {
                            'moyenne_stats_list' => 'bg-emerald-500/15 border border-emerald-500/25',
                            'time_plan' => 'bg-violet-500/15 border border-violet-500/25',
                            'student_list' => 'bg-sky-500/15 border border-sky-500/25',
                            'classe_list' => 'bg-amber-500/15 border border-amber-500/25',
                            'marks_ranking_list' => 'bg-orange-500/15 border border-orange-500/25',
                            default => 'bg-slate-500/15 border border-slate-500/25',
                        };
                        $iconColor = match ($doc->type) {
                            'moyenne_stats_list' => 'text-emerald-400',
                            'time_plan' => 'text-violet-400',
                            'student_list' => 'text-sky-400',
                            'classe_list' => 'text-amber-400',
                            'marks_ranking_list' => 'text-orange-400',
                            default => 'text-slate-400',
                        };
                    @endphp
                    <article
                        class="group rounded-2xl bg-[#0f1523] border border-white/[0.06] hover:border-cyan-500/25 transition-all shadow-lg shadow-black/20 overflow-hidden flex flex-col">
                        <div class="p-5 flex-1">
                            <div class="flex items-start gap-3 mb-3">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $iconBox }}">
                                    @switch($meta['icon'])
                                        @case('bar-chart-3')
                                            <x-lucide-bar-chart-3 class="w-5 h-5 {{ $iconColor }}" />
                                        @break

                                        @case('calendar-range')
                                            <x-lucide-calendar-range class="w-5 h-5 {{ $iconColor }}" />
                                        @break

                                        @case('users')
                                            <x-lucide-users class="w-5 h-5 {{ $iconColor }}" />
                                        @break

                                        @case('school')
                                            <x-lucide-school class="w-5 h-5 {{ $iconColor }}" />
                                        @break

                                        @case('trophy')
                                            <x-lucide-trophy class="w-5 h-5 {{ $iconColor }}" />
                                        @break

                                        @default
                                            <x-lucide-file-text class="w-5 h-5 text-slate-400" />
                                    @endswitch
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-white truncate" title="{{ $doc->filename }}">
                                        {{ $doc->filename }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $meta['label'] }}
                                        @if ($doc->school_year_slug)
                                            · {{ $doc->school_year_slug }}
                                        @endif
                                        @if ($doc->period)
                                            · {{ $doc->period }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-slate-500 font-mono">
                                <span class="inline-flex items-center gap-1">
                                    <x-lucide-calendar class="w-3 h-3" />
                                    {{ $doc->created_at?->timezone('Africa/Abidjan')->format('d/m/Y H:i') }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <x-lucide-download class="w-3 h-3" />
                                    {{ $doc->downloaded_count ?? 0 }} dl
                                </span>
                                @if ($doc->downloadable_by_others)
                                    <span class="inline-flex items-center gap-1 text-emerald-400/80">
                                        <x-lucide-globe class="w-3 h-3" />
                                        Partagé
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="px-5 py-3 border-t border-white/[0.06] flex items-center gap-2">
                            <button type="button" wire:click="trackDownload({{ $doc->id }})"
                                wire:loading.attr="disabled" wire:target="trackDownload({{ $doc->id }})"
                                class="flex-1 h-9 rounded-xl text-xs font-semibold bg-cyan-500/15 text-cyan-300 border border-cyan-500/25 hover:bg-cyan-500/25 transition-all inline-flex items-center justify-center gap-1.5 disabled:opacity-60">
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
                            @if (auth('tenant')->user()->hasRole('directeur') && $canDelete)
                                <button type="button" wire:click="toggleOthersVisibility({{ $doc->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="toggleOthersVisibility({{ $doc->id }})"
                                    class="h-9 w-9 rounded-xl transition-all inline-flex items-center justify-center
                                        {{ $doc->downloadable_by_others
                                            ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/25 hover:bg-emerald-500/20'
                                            : 'text-slate-400 bg-white/5 border border-white/10 hover:bg-white/10' }}"
                                    title="{{ $doc->downloadable_by_others ? 'Rendre privé' : 'Partager aux autres' }}">
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
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $this->documents->links() }}
            </div>
        @endif
    </div>
</div>

