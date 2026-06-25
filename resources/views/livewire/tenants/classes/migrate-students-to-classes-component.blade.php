<div class="min-h-screen bg-slate-950 text-slate-100">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ═══════════════════════════════════════════ --}}
        {{-- HEADER --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">Migration des apprenants</h1>
                <p class="mt-1 text-sm text-slate-400">
                    Affecter des apprenants à une classe ·
                    <span class="text-indigo-400 font-medium">{{ $this->activeYear?->slug ?? '—' }}</span>
                </p>
            </div>
            @if (!empty($this->draftStudents))
                <button wire:click="clearDraft" wire:loading.attr="disabled" wire:target="clearDraft"
                    class="relative px-4 py-2 rounded-xl border border-rose-500/30 bg-rose-500/10 text-rose-400 text-sm hover:bg-rose-500/20 transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="clearDraft">Vider la sélection</span>
                    <span wire:loading wire:target="clearDraft" class="flex items-center gap-2">
                        <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        Vidage...
                    </span>
                </button>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- SÉLECTION CLASSE --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
            <label class="block text-xs font-medium text-slate-400 mb-2">
                Classe cible <span class="text-rose-400">*</span>
            </label>
            <select wire:model.live="classeId"
                class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                <option value="">— Sélectionner une classe —</option>
                @foreach ($this->classes as $classe)
                    <option wire:selected='{{ $selectedClasse->id === $classe->id }}' value="{{ $classe->id }}">
                        {{ $classe->name }}
                        {{ $classe->promotion ? '· ' . $classe->promotion->name : '' }}
                        {{ $classe->filiar ? '· ' . $classe->filiar->code : '' }}
                        {{ $classe->serial ? '· ' . $classe->serial->name : '' }}
                    </option>
                @endforeach
            </select>
            @if ($selectedClasse)
                <div class="mt-3 flex flex-wrap gap-2">
                    <span
                        class="px-2.5 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs border border-indigo-500/20">{{ $selectedClasse->name }}</span>
                    @if ($selectedClasse->promotion)
                        <span
                            class="px-2.5 py-1 rounded-full bg-slate-700 text-slate-300 text-xs">{{ $selectedClasse->promotion->name }}</span>
                    @endif
                    <span
                        class="px-2.5 py-1 rounded-full bg-slate-700 text-slate-300 text-xs capitalize">{{ $selectedClasse->level }}</span>
                </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- SWITCHER MODE --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="flex gap-2 p-1 rounded-2xl bg-slate-900 border border-slate-800">
            @foreach (['manual' => '✏️ Saisie manuelle', 'excel' => '📊 Import Excel', 'browse' => '☑️ Sélection liste'] as $m => $label)
                <button wire:click="$set('mode', '{{ $m }}')"
                    class="flex-1 py-2.5 rounded-xl text-sm font-medium transition-all
                    {{ $mode === $m ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- MODE : SAISIE MANUELLE --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if ($mode === 'manual')
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">
                        Matricules ou EducMaster
                        <span class="text-slate-500">(séparés par virgule, tiret ou retour à la ligne)</span>
                    </label>
                    <textarea wire:model="identifiersInput" rows="4" placeholder="Ex: MAT001, MAT002 - EDU123&#10;MAT003"
                        class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition resize-none font-mono"></textarea>
                </div>
                <div class="flex justify-end">
                    <button wire:click="loadManual" wire:loading.attr="disabled" wire:target="loadManual"
                        class="relative inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition disabled:opacity-60">

                        {{-- Overlay de loading --}}
                        <span wire:loading wire:target="loadManual"
                            class="absolute inset-0 flex items-center justify-center gap-2 rounded-xl bg-indigo-700">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            <span class="text-white text-sm">Chargement...</span>
                        </span>

                        <span wire:loading.remove wire:target="loadManual">
                            Charger les apprenants
                        </span>
                        <span wire:loading wire:target="loadManual" class="invisible">Charger les apprenants</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- MODE : IMPORT EXCEL --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if ($mode === 'excel')
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5 space-y-5">

                {{-- Info colonnes attendues --}}
                <div
                    class="rounded-xl bg-slate-800/60 border border-slate-700 px-4 py-3 text-xs text-slate-400 space-y-1">
                    <p class="font-medium text-slate-300">En-têtes acceptées :</p>
                    <p>· <span class="font-mono text-indigo-400">matricule</span> (toute casse) → recherche sur la
                        colonne matricule</p>
                    <p>· <span class="font-mono text-violet-400">educMaster / educ_master</span> (toute casse) →
                        recherche sur educMaster</p>
                    <p>· <span class="font-mono text-amber-400">identifiant / id / code</span> → recherche sur les deux
                        colonnes simultanément</p>
                </div>

                {{-- Zone upload --}}
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">
                        Fichier Excel <span class="text-slate-500">(.xlsx, .xls — max 5 Mo)</span>
                    </label>

                    {{-- Drop zone --}}
                    <label for="excel-upload"
                        class="relative flex flex-col items-center justify-center gap-3 w-full rounded-2xl border-2 border-dashed border-slate-700 bg-slate-800/40
                        hover:border-indigo-500/50 hover:bg-indigo-500/5 transition-all cursor-pointer p-8 group">

                        {{-- Spinner pendant l'upload --}}
                        <div wire:loading wire:target="excelFile"
                            class="absolute inset-0 flex flex-col items-center justify-center gap-3 rounded-2xl bg-slate-900/80 backdrop-blur-sm z-10">
                            <svg class="animate-spin w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            <p class="text-sm text-slate-300 font-medium">Lecture du fichier...</p>
                            <p class="text-xs text-slate-500">Détection des colonnes en cours</p>
                        </div>

                        <div wire:loading.remove wire:target="excelFile" class="flex flex-col items-center gap-3">
                            <div
                                class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center group-hover:bg-emerald-500/20 transition">
                                <span class="text-2xl">📊</span>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-medium text-slate-300">
                                    Cliquez pour sélectionner un fichier
                                </p>
                                <p class="text-xs text-slate-500 mt-1">.xlsx ou .xls uniquement</p>
                            </div>
                        </div>

                        <input id="excel-upload" type="file" wire:model="excelFile" accept=".xlsx,.xls"
                            class="sr-only" />
                    </label>

                    @error('excelFile')
                        <p class="mt-2 text-xs text-rose-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Colonne détectée --}}
                @if ($detectedColumn)
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-slate-500">Colonne détectée :</span>
                        <span
                            class="px-2.5 py-1 rounded-full font-mono
                    {{ $detectedColumn === 'matricule' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : ($detectedColumn === 'educMaster' ? 'bg-violet-500/10 text-violet-400 border border-violet-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                            {{ $detectedColumn === 'both' ? 'matricule + educMaster' : $detectedColumn }}
                        </span>
                    </div>
                @endif

                {{-- Erreurs d'import --}}
                @if (!empty($importErrors))
                    <div class="rounded-xl border border-amber-500/30 bg-amber-500/5 p-4">
                        <p class="text-xs font-medium text-amber-400 mb-2">
                            {{ count($importErrors) }} ligne(s) ignorée(s) :
                        </p>
                        <ul class="space-y-1 max-h-40 overflow-y-auto">
                            @foreach ($importErrors as $err)
                                <li class="text-xs text-slate-400 flex items-start gap-1.5">
                                    <span class="text-amber-500 shrink-0 mt-0.5">·</span>
                                    {{ $err }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- MODE : BROWSE --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if ($mode === 'browse')
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5 space-y-4">

                {{-- Search --}}
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="browseSearch"
                        placeholder="Rechercher par nom, prénom ou matricule..."
                        class="w-full h-11 rounded-xl border border-slate-700 bg-slate-800 pl-10 pr-4 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs">🔍</span>

                    {{-- Spinner search --}}
                    <div wire:loading wire:target="browseSearch" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </div>
                </div>

                @if ($browseStudents)

                    {{-- Compteur sélection --}}
                    @if (count($this->draftStudents) > 0)
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>{{ $browseStudents->total() }} apprenant(s) · Page
                                {{ $browseStudents->currentPage() }}</span>
                            <span class="text-indigo-400 font-medium">{{ count($this->draftStudents) }}
                                sélectionné(s)</span>
                        </div>
                    @endif

                    {{-- Liste --}}
                    <div class="space-y-1 max-h-[500px] overflow-y-auto pr-1" wire:loading.class="opacity-50"
                        wire:target="browseSearch, previousPage, nextPage, gotoPage">
                        @forelse ($browseStudents as $student)
                            @php $inDraft = $this->isInDraft($student->id); @endphp
                            @php $draftEntry = $inDraft ? collect($this->draftStudents)->firstWhere('id', $student->id) : null; @endphp

                            <div wire:key="browse-{{ $student->id }}"
                                wire:click="toggleStudent({{ $student->id }})"
                                wire:loading.class="pointer-events-none opacity-60"
                                wire:target="toggleStudent({{ $student->id }})"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl cursor-pointer transition-all select-none
                        {{ $inDraft ? 'bg-indigo-500/10 border border-indigo-500/30' : 'bg-slate-800 border border-transparent hover:border-slate-700' }}">

                                {{-- Spinner sur l'item cliqué --}}
                                <div wire:loading wire:target="toggleStudent({{ $student->id }})"
                                    class="w-5 h-5 flex items-center justify-center shrink-0">
                                    <svg class="animate-spin w-4 h-4 text-indigo-400" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                </div>

                                {{-- Checkbox visuelle --}}
                                <div wire:loading.remove wire:target="toggleStudent({{ $student->id }})"
                                    class="w-5 h-5 rounded-md border flex items-center justify-center shrink-0 transition
                            {{ $inDraft ? 'bg-indigo-600 border-indigo-600' : 'border-slate-600' }}">
                                    @if ($inDraft)
                                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>

                                {{-- Infos apprenant --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">
                                        {{ $student->name }} {{ $student->prenames }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-mono">
                                        {{ $student->matricule }}
                                        @if ($student->educMaster)
                                            · {{ $student->educMaster }}
                                        @endif
                                    </p>
                                </div>

                                {{-- Badge conflit --}}
                                @if ($inDraft && $draftEntry && $draftEntry['conflict'])
                                    <span
                                        class="text-xs text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-500/20 shrink-0">
                                        ⚠️ {{ $draftEntry['conflict_classe'] }}
                                    </span>
                                @endif
                            </div>
                        @empty
                            <div class="py-12 text-center">
                                <p class="text-sm text-slate-500">Aucun apprenant trouvé.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination browse --}}
                    @if ($browseStudents->hasPages())
                        <div class="pt-3 border-t border-slate-800 flex items-center justify-between gap-4">
                            <span class="text-xs text-slate-500">
                                {{ $browseStudents->firstItem() }}–{{ $browseStudents->lastItem() }} sur
                                {{ $browseStudents->total() }}
                            </span>
                            <div class="flex items-center gap-2">
                                @if (!$browseStudents->onFirstPage())
                                    <button wire:click="previousPage" wire:loading.attr="disabled"
                                        wire:target="previousPage"
                                        class="h-8 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs transition disabled:opacity-50">
                                        ← Préc.
                                    </button>
                                @endif

                                <span class="text-xs text-slate-400 px-2">
                                    Page {{ $browseStudents->currentPage() }} / {{ $browseStudents->lastPage() }}
                                </span>

                                @if ($browseStudents->hasMorePages())
                                    <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                        class="h-8 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs transition disabled:opacity-50">
                                        Suiv. →
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                @endif
            </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- ERREUR GLOBALE --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if ($errorMessage)
            <div
                class="rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 flex items-center gap-3 text-sm text-rose-400">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                {{ $errorMessage }}
            </div>
        @endif

        {{-- Identifiants non trouvés --}}
        @if (!empty($notFoundIds))
            <div class="rounded-xl border border-amber-500/30 bg-amber-500/5 px-4 py-3 text-sm text-amber-400">
                <p class="font-medium mb-1">{{ count($notFoundIds) }} identifiant(s) non trouvé(s) :</p>
                <p class="font-mono text-xs text-slate-400">{{ implode(', ', $notFoundIds) }}</p>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- LISTE DE TRAVAIL (DRAFT) --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if (!empty($this->draftStudents))
            <div class="rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="font-semibold text-sm text-white">Apprenants sélectionnés</h3>
                        <span
                            class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-xs border border-emerald-500/20">
                            {{ count($this->validStudents) }} valide(s)
                        </span>
                        @if (count($this->conflictStudents) > 0)
                            <span
                                class="px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-400 text-xs border border-amber-500/20">
                                ⚠️ {{ count($this->conflictStudents) }} conflit(s) — seront ignorés
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Liste draft --}}
                <div class="divide-y divide-slate-800 max-h-96 overflow-y-auto">
                    @foreach ($this->draftStudents as $student)
                        <div wire:key="draft-{{ $student['id'] }}"
                            class="flex items-center gap-4 px-5 py-3.5 transition
                        {{ $student['conflict'] ? 'bg-amber-500/5 opacity-70' : 'hover:bg-slate-800/50' }}">

                            {{-- Avatar --}}
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                        {{ $student['conflict'] ? 'bg-amber-500/20 text-amber-400' : 'bg-indigo-500/20 text-indigo-400' }}">
                                {{ strtoupper(substr($student['name'], 0, 1)) }}
                            </div>

                            {{-- Infos --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">
                                    {{ $student['name'] }} {{ $student['prenames'] }}
                                </p>
                                <p class="text-xs text-slate-500 font-mono">
                                    {{ $student['matricule'] }}
                                    @if ($student['educMaster'])
                                        · {{ $student['educMaster'] }}
                                    @endif
                                </p>
                            </div>

                            {{-- Badge statut --}}
                            @if ($student['conflict'])
                                <div class="shrink-0 text-right">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 text-xs border border-amber-500/20">
                                        ⚠️ Déjà dans « {{ $student['conflict_classe'] }} »
                                    </span>
                                    <p class="mt-0.5 text-xs text-slate-600">Ignoré</p>
                                </div>
                            @else
                                <span
                                    class="shrink-0 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs border border-emerald-500/20">✓
                                    Valide</span>
                            @endif

                            {{-- Retirer avec loading --}}
                            <button wire:click="removeFromDraft({{ $student['id'] }})" wire:loading.attr="disabled"
                                wire:target="removeFromDraft({{ $student['id'] }})"
                                class="shrink-0 w-7 h-7 rounded-lg text-slate-600 hover:text-rose-400 hover:bg-rose-500/10 transition flex items-center justify-center disabled:opacity-40">
                                <span wire:loading.remove wire:target="removeFromDraft({{ $student['id'] }})">
                                    <x-lucide-user-x class="w-4 h-4" />
                                </span>
                                <span wire:loading wire:target="removeFromDraft({{ $student['id'] }})">
                                    <svg class="animate-spin w-3.5 h-3.5 text-rose-400" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Footer migration --}}
                <div
                    class="px-5 py-4 border-t border-slate-800 bg-slate-900/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-400">
                            @if (count($this->validStudents) > 0)
                                <span class="text-white font-medium">{{ count($this->validStudents) }}</span>
                                apprenant(s) seront migrés vers
                                <span class="text-indigo-400 font-medium">{{ $this->selectedClasse?->name }}</span>
                            @else
                                <span class="text-slate-500">Aucun apprenant valide à migrer.</span>
                            @endif
                        </p>
                        @if (count($this->conflictStudents) > 0)
                            <p class="text-xs text-amber-500/70 mt-0.5">
                                {{ count($this->conflictStudents) }} apprenant(s) déjà affecté(s) seront ignorés.
                            </p>
                        @endif
                    </div>

                    <button wire:click="migrate" wire:loading.attr="disabled" wire:target="migrate"
                        @disabled(count($this->validStudents) === 0 || !$this->classeId)
                        class="relative inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-sm font-semibold transition disabled:opacity-40 disabled:cursor-not-allowed min-w-[180px] justify-center">

                        {{-- Loading overlay --}}
                        <span wire:loading wire:target="migrate"
                            class="absolute inset-0 flex items-center justify-center gap-2 rounded-xl bg-emerald-700">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            <span class="text-white">Lancement...</span>
                        </span>

                        <span wire:loading.remove wire:target="migrate">
                            🚀 Lancer la migration
                        </span>
                        <span wire:loading wire:target="migrate" class="invisible">🚀 Lancer la migration</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- POST-MIGRATION --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if ($migrating && empty($this->draftStudents))
            <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0 text-xl">✅
                </div>
                <div>
                    <p class="text-sm font-medium text-emerald-400">Migration lancée avec succès</p>
                    <p class="text-xs text-slate-500 mt-0.5">Les apprenants seront affectés dans quelques instants via
                        le job en arrière-plan.</p>
                </div>
            </div>
        @endif

    </div>
</div>

