<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="mx-auto max-w-[1100px] px-4 sm:px-6 py-10 relative">

        <div wire:loading.flex wire:target="initPrintProcess, resetFilters, selectAllClasses, selectAllTeachers"
            class="fixed inset-0 z-[200] items-center justify-center bg-[#070b14]/80 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 rounded-full border-2 border-cyan-500/30 border-t-cyan-400 animate-spin"></div>
                <span class="text-sm font-mono text-slate-400 tracking-wide">Chargement…</span>
            </div>
        </div>

        {{-- Header --}}
        <header class="mb-10 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-[11px] font-semibold uppercase tracking-[0.2em] mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                Emplois du temps
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                Configuration de l’impression
            </h1>
            <p class="mt-3 text-slate-500 text-sm max-w-lg mx-auto">
                Classes, enseignants ou matière — un EDT par page, format paysage.
            </p>
            @if ($this->activeYear)
                <p class="mt-2 text-xs text-slate-600">
                    Année active : <span
                        class="text-slate-400">{{ $this->activeYear->slug ?? $this->activeYear->name }}</span>
                </p>
            @endif
        </header>

        <div class="flex flex-wrap justify-center gap-2 mb-8">
            <a wire:navigate href="{{ route('tenant.timeplans.dashboard') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-cyan-500/15 text-cyan-300 border border-cyan-500/20 hover:bg-cyan-500/25 transition-all inline-flex items-center gap-1.5">
                <x-lucide-calendar-range class="w-3.5 h-3.5" />
                Tableau des EDT
            </a>
            <a href="{{ route('tenant.timeplans.print.preview') }}"
                class="h-9 px-3.5 rounded-lg text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                <x-lucide-eye class="w-3.5 h-3.5" />
                Aperçu du document
            </a>
        </div>

        <div class="space-y-6">

            {{-- Mode --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-cyan-500/15 border border-cyan-500/25">
                        <x-lucide-layers class="w-4 h-4 text-cyan-400" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Type d’impression</h2>
                        <p class="text-[11px] text-slate-500">Choisir le périmètre des emplois du temps</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid sm:grid-cols-3 gap-3">
                        <button type="button" wire:click="$set('mode', 'classes')"
                            class="rounded-xl border p-4 text-left transition-all
                                   {{ $mode === 'classes'
                                       ? 'border-cyan-500/40 bg-cyan-500/10 ring-1 ring-cyan-500/30'
                                       : 'border-white/[0.06] bg-[#070b14] hover:border-white/15' }}">
                            <div class="flex items-center gap-2 mb-1">
                                <x-lucide-school
                                    class="w-4 h-4 {{ $mode === 'classes' ? 'text-cyan-400' : 'text-slate-500' }}" />
                                <span class="text-sm font-semibold text-white">Classes</span>
                            </div>
                            <p class="text-[11px] text-slate-500">EDT par classe (matières + profs)</p>
                        </button>

                        <button type="button" wire:click="$set('mode', 'teachers')"
                            class="rounded-xl border p-4 text-left transition-all
                                   {{ $mode === 'teachers'
                                       ? 'border-violet-500/40 bg-violet-500/10 ring-1 ring-violet-500/30'
                                       : 'border-white/[0.06] bg-[#070b14] hover:border-white/15' }}">
                            <div class="flex items-center gap-2 mb-1">
                                <x-lucide-user
                                    class="w-4 h-4 {{ $mode === 'teachers' ? 'text-violet-400' : 'text-slate-500' }}" />
                                <span class="text-sm font-semibold text-white">Enseignants</span>
                            </div>
                            <p class="text-[11px] text-slate-500">Sélection libre de professeurs</p>
                        </button>

                        <button type="button" wire:click="$set('mode', 'subject')"
                            class="rounded-xl border p-4 text-left transition-all
                                   {{ $mode === 'subject'
                                       ? 'border-amber-500/40 bg-amber-500/10 ring-1 ring-amber-500/30'
                                       : 'border-white/[0.06] bg-[#070b14] hover:border-white/15' }}">
                            <div class="flex items-center gap-2 mb-1">
                                <x-lucide-book-open
                                    class="w-4 h-4 {{ $mode === 'subject' ? 'text-amber-400' : 'text-slate-500' }}" />
                                <span class="text-sm font-semibold text-white">Par matière</span>
                            </div>
                            <p class="text-[11px] text-slate-500">Tous les profs d’une matière</p>
                        </button>
                    </div>
                </div>
            </section>

            {{-- Filtres selon mode --}}
            @if ($mode === 'classes')
                <section
                    class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                    <div class="px-6 py-4 border-b border-white/[0.06] flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-9 h-9 rounded-xl bg-sky-500/15 border border-sky-500/25">
                                <x-lucide-filter class="w-4 h-4 text-sky-400" />
                            </div>
                            <div>
                                <h2 class="text-sm font-semibold text-white">Classes à imprimer</h2>
                                <p class="text-[11px] text-slate-500">Filtres optionnels · sélection multiple</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="selectAllClasses"
                                class="h-8 px-2.5 rounded-lg text-[11px] font-medium text-cyan-300 bg-cyan-500/10 border border-cyan-500/20 hover:bg-cyan-500/20 transition-all">
                                Tout sélectionner
                            </button>
                            <button type="button" wire:click="clearClasses"
                                class="h-8 px-2.5 rounded-lg text-[11px] font-medium text-slate-400 bg-white/5 border border-white/10 hover:bg-white/10 transition-all">
                                Vider
                            </button>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid sm:grid-cols-3 gap-3">
                            <div>
                                <label
                                    class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Filière</label>
                                <select wire:model.live="filiar_id"
                                    class="w-full h-10 rounded-xl bg-[#070b14] border border-white/10 px-3 text-sm text-slate-200 focus:outline-none focus:border-cyan-500/50">
                                    <option value="">Toutes</option>
                                    @foreach ($this->filiars as $f)
                                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Série</label>
                                <select wire:model.live="serial_id"
                                    class="w-full h-10 rounded-xl bg-[#070b14] border border-white/10 px-3 text-sm text-slate-200 focus:outline-none focus:border-cyan-500/50">
                                    <option value="">Toutes</option>
                                    @foreach ($this->serials as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Promotion</label>
                                <select wire:model.live="promotion_id"
                                    class="w-full h-10 rounded-xl bg-[#070b14] border border-white/10 px-3 text-sm text-slate-200 focus:outline-none focus:border-cyan-500/50">
                                    <option value="">Toutes</option>
                                    @foreach ($this->promotions as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Statut des EDT
                            </label>
                            <select wire:model.live="plan_status"
                                class="w-full sm:w-64 h-10 rounded-xl bg-[#070b14] border border-white/10 px-3 text-sm text-slate-200 focus:outline-none focus:border-cyan-500/50">
                                <option value="published">Publiés uniquement</option>
                                <option value="draft">Brouillons</option>
                                <option value="">Tous (non archivés)</option>
                            </select>
                        </div>

                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-72 overflow-y-auto pr-1">
                            @forelse ($this->classes as $classe)
                                <button type="button" wire:click="toggleClasse({{ $classe->id }})"
                                    class="flex items-center gap-2 rounded-xl border px-3 py-2.5 text-left text-sm transition-all
                                           {{ in_array($classe->id, $selected_classe_ids, true)
                                               ? 'border-cyan-500/40 bg-cyan-500/10 text-white'
                                               : 'border-white/[0.06] bg-[#070b14] text-slate-400 hover:border-white/15' }}">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded border
                                                 {{ in_array($classe->id, $selected_classe_ids, true)
                                                     ? 'border-cyan-400 bg-cyan-500 text-white'
                                                     : 'border-white/20' }}">
                                        @if (in_array($classe->id, $selected_classe_ids, true))
                                            <x-lucide-check class="w-3 h-3" />
                                        @endif
                                    </span>
                                    <span class="truncate font-medium">{{ $classe->name }}</span>
                                    @if ($classe->code)
                                        <span
                                            class="ml-auto text-[10px] text-slate-600 font-mono">{{ $classe->code }}</span>
                                    @endif
                                </button>
                            @empty
                                <p class="text-sm text-slate-500 col-span-full py-6 text-center">Aucune classe trouvée.
                                </p>
                            @endforelse
                        </div>

                        <p class="text-xs text-slate-500">
                            {{ count($selected_classe_ids) }} classe(s) sélectionnée(s)
                            · si aucune sélection, toutes les classes filtrées seront imprimées
                        </p>
                    </div>
                </section>
            @endif

            @if ($mode === 'teachers')
                <section
                    class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                    <div class="px-6 py-4 border-b border-white/[0.06] flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-9 h-9 rounded-xl bg-violet-500/15 border border-violet-500/25">
                                <x-lucide-users class="w-4 h-4 text-violet-400" />
                            </div>
                            <div>
                                <h2 class="text-sm font-semibold text-white">Enseignants</h2>
                                <p class="text-[11px] text-slate-500">Sélection multiple · cours + heures AP inclus</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="selectAllTeachers"
                                class="h-8 px-2.5 rounded-lg text-[11px] font-medium text-violet-300 bg-violet-500/10 border border-violet-500/20 hover:bg-violet-500/20 transition-all">
                                Tout sélectionner
                            </button>
                            <button type="button" wire:click="clearTeachers"
                                class="h-8 px-2.5 rounded-lg text-[11px] font-medium text-slate-400 bg-white/5 border border-white/10 hover:bg-white/10 transition-all">
                                Vider
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-80 overflow-y-auto pr-1">
                            @forelse ($this->teachers as $teacher)
                                <button type="button" wire:click="toggleTeacher({{ $teacher->id }})"
                                    class="flex items-center gap-2 rounded-xl border px-3 py-2.5 text-left text-sm transition-all
                                           {{ in_array($teacher->id, $selected_teacher_ids, true)
                                               ? 'border-violet-500/40 bg-violet-500/10 text-white'
                                               : 'border-white/[0.06] bg-[#070b14] text-slate-400 hover:border-white/15' }}">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded border
                                                 {{ in_array($teacher->id, $selected_teacher_ids, true)
                                                     ? 'border-violet-400 bg-violet-500 text-white'
                                                     : 'border-white/20' }}">
                                        @if (in_array($teacher->id, $selected_teacher_ids, true))
                                            <x-lucide-check class="w-3 h-3" />
                                        @endif
                                    </span>
                                    <span class="truncate font-medium">{{ $teacher->getFullName() }}</span>
                                </button>
                            @empty
                                <p class="text-sm text-slate-500 col-span-full py-6 text-center">Aucun enseignant.</p>
                            @endforelse
                        </div>
                        <p class="mt-3 text-xs text-slate-500">{{ count($selected_teacher_ids) }} enseignant(s)
                            sélectionné(s)</p>
                    </div>
                </section>
            @endif

            @if ($mode === 'subject')
                <section
                    class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                    <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/25">
                            <x-lucide-book-open class="w-4 h-4 text-amber-400" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-white">Matière</h2>
                            <p class="text-[11px] text-slate-500">Impression des EDT de tous les profs de la matière
                            </p>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label
                                class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Matière <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="subject_id"
                                class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3 text-sm text-slate-200 focus:outline-none focus:border-amber-500/50">
                                <option value="">Choisir une matière</option>
                                @foreach ($this->subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($subject_id)
                            <div class="rounded-xl border border-white/[0.06] bg-[#070b14] p-4">
                                <p class="text-xs text-slate-500 mb-2">
                                    {{ $this->teachers->count() }} enseignant(s) trouvé(s) pour cette matière
                                </p>
                                <ul class="text-sm text-slate-300 space-y-1 max-h-40 overflow-y-auto">
                                    @foreach ($this->teachers as $t)
                                        <li class="flex items-center gap-2">
                                            <x-lucide-user class="w-3.5 h-3.5 text-slate-600" />
                                            {{ $t->getFullName() }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </section>
            @endif

            {{-- Actions --}}
            <section
                class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20">
                <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm text-white font-medium">
                            Prêt à générer
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Environ {{ $this->plansCount }} page(s) · format A4 paysage
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" wire:click="resetFilters"
                            class="h-11 px-4 rounded-xl text-sm font-medium border border-white/10 text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                            Réinitialiser
                        </button>
                        <a href="{{ route('tenant.timeplans.print.preview') }}"
                            class="h-11 px-4 rounded-xl text-sm font-medium border border-white/10 text-slate-300 hover:bg-white/5 inline-flex items-center gap-1.5 transition-all">
                            <x-lucide-eye class="w-4 h-4" />
                            Aperçu
                        </a>
                        <button type="button" wire:click="initPrintProcess" wire:loading.attr="disabled"
                            wire:target="initPrintProcess"
                            class="h-11 px-5 rounded-xl text-sm font-medium bg-cyan-500 hover:bg-cyan-400 text-white shadow-lg shadow-cyan-500/25
                                   inline-flex items-center gap-2 transition-all disabled:opacity-60">
                            <span wire:loading.remove wire:target="initPrintProcess"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-file-down class="w-4 h-4" />
                                Générer le PDF
                            </span>
                            <span wire:loading wire:target="initPrintProcess"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                Lancement…
                            </span>
                        </button>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

