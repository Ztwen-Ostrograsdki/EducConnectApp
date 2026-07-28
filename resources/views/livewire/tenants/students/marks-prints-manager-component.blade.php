<div>
    <div class="mb-20 p-2 relative">

        <div wire:loading wire:target='resetFilters, restoreSelects, initPrintProcess'
            class="absolute inset-0 flex items-center justify-center bg-slate-800/10 backdrop-blur-xs"
            style="z-index: 200 !important;">
            <div class="items-center gap-1 text-slate-400 relative mx-auto flex justify-center flex-col">
                <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
                <span class="text-xl font-mono ls-1">Rehargement en cours...</span>
            </div>
        </div>

        {{-- ═══ PÉRIODE + MATIÈRE ═══ --}}
        <section
            class="rounded-2xl p-3 bg-indigo-900/10 border border-indigo-900 overflow-hidden flex flex-col gap-3 mb-3"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0">

            <h4 class="text-lg font-bold ls-1 border-b border-b-slate-600 py-2 text-amber-400/80">
                Période et matière
            </h4>

            <div class="grid sm:grid-cols-2 gap-3 w-full">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-mono text-slate-500 uppercase tracking-wide">
                        Période <span class="text-red-400">*</span>
                    </label>
                    <select wire:model.live='period'
                        class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono transition-colors focus:border-indigo-500">
                        <option value="">Sélectionner le {{ $this->activeYear?->periodLabel() }}</option>
                        @foreach ($this->periods_types as $p)
                            <option value="{{ $p['index'] }}">{{ $p['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-mono text-slate-500 uppercase tracking-wide">
                        Matière <span class="text-slate-600">(optionnel)</span>
                    </label>
                    <select wire:model.live='subject_id'
                        class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono transition-colors focus:border-indigo-500">
                        <option value="">Toutes matières (moyenne semestrielle)</option>
                        @foreach ($this->subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->code ?: $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div wire:key="subject-hint-{{ $subject_id ?: 'none' }}"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                class="flex items-start gap-2 bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3">
                <x-lucide-info class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" />
                <p class="text-xs text-slate-400 font-mono">
                    @if ($subject_id)
                        Une matière est ciblée : le tableau affichera le détail des interrogations, devoirs, moyenne et
                        rang <span class="text-indigo-300">pour cette matière uniquement</span>.
                    @else
                        Aucune matière ciblée : le tableau affichera la <span class="text-indigo-300">moyenne
                            semestrielle</span> (toutes matières confondues, pondérée par coefficient) et le rang
                        correspondant.
                    @endif
                </p>
            </div>
        </section>

        {{-- ═══ CIBLAGE DES CLASSES ═══ --}}
        <section
            class="rounded-2xl p-3 bg-indigo-900/10 border border-indigo-900 overflow-hidden flex flex-col gap-3 mb-3">
            <h4 class="text-lg font-bold ls-1 border-b border-b-slate-600 py-2 text-amber-400/80">
                Cibler les classes concernées
            </h4>

            <div class="grid grid-cols-3 items-center gap-3 w-full">

                @if (!$filiar_id && !$serial_id && !$promotion_id && !$promotionInGroups)
                    <select wire:model.live='classe_id'
                        class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono transition-colors focus:border-indigo-500">
                        <option value="">Toutes les classes</option>
                        @foreach ($this->classes as $cl)
                            <option value="{{ $cl->id }}">Classe de {{ $cl->code ?: $cl->name }}</option>
                        @endforeach
                    </select>
                @endif

                @if (!$classe_id)
                    @if (!$promotion_id)
                        @if (!$serial_id)
                            <select wire:model.live='filiar_id'
                                class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono transition-colors focus:border-indigo-500">
                                <option value="">Toutes les filières</option>
                                @foreach ($this->filiars as $f)
                                    <option value="{{ $f->id }}">Filière {{ $f->code ?: $f->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if (!$filiar_id)
                            <select wire:model.live='serial_id'
                                class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono transition-colors focus:border-indigo-500">
                                <option value="">Toutes les séries</option>
                                @foreach ($this->serials as $sr)
                                    <option value="{{ $sr->id }}">Série {{ $sr->code ?: $sr->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    @endif

                    @if (!$filiar_id && !$serial_id && !$promotionInGroups)
                        <select wire:model.live='promotion_id'
                            class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono transition-colors focus:border-indigo-500">
                            <option value="">Toutes les promotions spécifiées</option>
                            @foreach ($this->promotions as $promo)
                                <option value="{{ $promo->id }}">Promotion {{ $promo->code ?: $promo->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    @if (!$promotion_id)
                        <select wire:model.live='promotionInGroups'
                            class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono transition-colors focus:border-indigo-500">
                            <option value="">Toutes les promotions</option>
                            @foreach ($this->promotionsGrouped as $n)
                                <option value="{{ $n }}">Promotion {{ $n }}</option>
                            @endforeach
                        </select>
                    @endif
                @endif

                <input type="text" wire:model.live.debounce.400ms='level' placeholder="Niveau (ex: 6ème, 2nde...)"
                    class="h-11 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm transition-colors focus:border-indigo-500">
            </div>
        </section>

        {{-- ═══ STATUT DES APPRENANTS ═══ --}}
        <section
            class="rounded-2xl p-3 bg-indigo-900/10 border border-indigo-900 overflow-hidden flex flex-col gap-3 mb-3">
            <h4 class="text-lg font-bold ls-1 border-b border-b-slate-600 py-2 text-amber-400/80">
                Statut des apprenants
            </h4>

            <select wire:model.live='leavesStatus'
                class="h-12 rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm font-mono transition-colors focus:border-indigo-500 w-full sm:w-1/2">
                @foreach ($leavesStatuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </section>

        {{-- ═══ COMPTEUR + RESET + TITRE DYNAMIQUE ═══ --}}
        <section class="my-2 border border-slate-700 rounded-2xl p-3 flex flex-col gap-2">
            <div class="flex justify-between items-center p-3 border-b border-b-slate-600 font-mono">
                <span>Format ou cible à imprimer</span>

                <button wire:click='resetFilters'
                    class="flex items-center justify-center py-2 rounded-2xl bg-slate-600 px-4 hover:bg-slate-800 text-sm uppercase font-mono active:scale-95 transition-all duration-150">
                    <span wire:loading.remove wire:target='resetFilters' class="inline-flex gap-x-2 items-center">
                        <x-lucide-refresh-ccw class="w-4 h-4" />
                        <span class="truncate">Réinitialiser les sélections</span>
                    </span>
                    <span wire:loading wire:target='resetFilters' class="inline-flex items-center gap-x-2">
                        <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                    </span>
                </button>
            </div>

            <div class="flex justify-start items-center text-xl text-slate-600 font-mono">
                <span>Classes trouvées : {{ __zero($this->allClassesCounter) }}</span>
            </div>

            <div wire:key="doc-title-{{ md5($this->currentDocTitle) }}"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                class="flex items-start gap-2 bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 mt-1">
                <x-lucide-file-text class="w-4 h-4 text-orange-400 shrink-0 mt-0.5" />
                <div>
                    <span class="block text-xs uppercase tracking-wide text-slate-500 font-mono mb-0.5">
                        Titre du document généré
                    </span>
                    <span class="text-sm text-orange-300 font-semibold">
                        {{ $this->currentDocTitle }}
                    </span>
                </div>
            </div>
        </section>

        {{-- ═══ SÉLECTEUR DE COLONNES ═══ --}}
        <section class="my-2 border border-slate-700 rounded-2xl p-3 flex flex-col gap-2">
            <div class="flex justify-between items-center p-1 border-b gap-2 border-b-slate-600 font-mono">
                <h3 class="text-left text-orange-500/70 md:text-lg text-sm">
                    Personnaliser l'entête du tableau de la liste.
                </h3>
                <button title="Nettoyer les sélections" wire:click="restoreSelects" wire:loading.attr="disabled"
                    wire:target="restoreSelects"
                    class="flex items-center min-w-auto justify-center gap-1.5 h-9 px-3 rounded-xl bg-orange-800/80 hover:bg-orange-600/90 hover:text-black text-orange-400 transition-all whitespace-nowrap disabled:opacity-50 active:scale-95 text-sm">
                    <span wire:loading.remove wire:target="restoreSelects" class="inline-flex items-center gap-1.5">
                        <x-lucide-trash class="w-4 h-4" />
                        <span>Nettoyer</span>
                    </span>
                    <span wire:loading wire:target="restoreSelects" class="inline-flex items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                        <span>En cours...</span>
                    </span>
                </button>
            </div>

            <div class="grid sm:grid-cols-4 grid-cols-2 gap-5">
                @foreach ($columns as $key => $column)
                    @php($order = array_search($key, $selectedColumns, true))
                    @php($irrelevant = $subject_id ? in_array($key, ['moy_semestrielle']) : in_array($key, ['interro1', 'interro2', 'interro3', 'interro4', 'moy_interro', 'devoir1', 'devoir2', 'compo', 'moy', 'moy_coef']))
                    <div wire:key="col-{{ $key }}" x-data="{ checked: {{ $order !== false ? 'true' : 'false' }} }"
                        class="flex flex-col gap-2 items-center rounded-2xl p-2 border transition-all duration-200
                            {{ $irrelevant ? 'opacity-40' : '' }}"
                        :class="checked ? 'border-indigo-500/70 bg-indigo-500/5' : 'border-slate-500'">
                        <label class="flex cursor-pointer items-center justify-between gap-2 w-full h-full">
                            <span class="flex justify-start gap-2 font-mono items-center">
                                <template x-if="checked">
                                    <span x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-50"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-50"
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs text-white border-2 p-1">
                                        {{ $order !== false ? $order + 1 : '' }}
                                    </span>
                                </template>
                                <h4 class="{{ $irrelevant ? 'line-through' : '' }}">{{ $column['label'] }}</h4>
                            </span>
                            <span class="relative flex shrink-0 items-center justify-end">
                                <input type="checkbox" wire:click="toggleColumn('{{ $key }}')"
                                    x-on:click="checked = !checked"
                                    wire:key="input-{{ $key }}-{{ $order !== false ? 'on' : 'off' }}"
                                    @checked($order !== false) class="peer sr-only">
                                <span
                                    class="h-6 w-11 rounded-full bg-slate-600 transition-colors duration-200 peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-500/50 peer-focus:ring-offset-2 peer-focus:ring-offset-slate-900"></span>
                                <span
                                    class="absolute left-1 top-1 h-4 w-4 rounded-full bg-slate-400 peer-checked:bg-white shadow transition-transform duration-200 peer-checked:translate-x-5"></span>
                            </span>
                        </label>
                    </div>
                @endforeach
            </div>

            @if (!$subject_id)
                <p class="text-xs text-slate-500 font-mono mt-1">
                    <x-lucide-alert-triangle class="w-3.5 h-3.5 inline text-yellow-500" />
                    Colonnes barrées : sans matière ciblée, seule la moyenne semestrielle est calculable — même si
                    cochées, elles resteront vides dans le document.
                </p>
            @else
                <p class="text-xs text-slate-500 font-mono mt-1">
                    <x-lucide-alert-triangle class="w-3.5 h-3.5 inline text-yellow-500" />
                    Colonne barrée : la moyenne semestrielle n'est calculable que sans matière ciblée — elle restera
                    vide dans le document.
                </p>
            @endif
        </section>

        {{-- ═══ PRÉVISUALISATION DE L'ENTÊTE ═══ --}}
        <section
            class="flex flex-col justify-center items-center p-3 my-3 w-full border border-orange-500/30 rounded-2xl overflow-hidden">
            @if (count($this->orderedColumns))
                <h3 class="text-center p-3 border-b border-b-slate-600 text-xl font-mono text-slate-500 w-full">
                    Format de l'entête <span class="text-indigo-400">(selon votre sélection)</span>
                </h3>
                <div wire:key="preview-selected-{{ implode('-', $selectedColumns) }}"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex items-center flex-col my-4 mx-auto w-full overflow-x-auto">
                    <table class="z-table-border w-full">
                        <colgroup>
                            <col>
                            @foreach ($this->orderedColumns as $th)
                                <col>
                            @endforeach
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="px-3 py-4 text-center text-sm text-slate-400">N°</th>
                                @foreach ($this->orderedColumns as $th)
                                    <th class="px-3 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                                        {{ $th }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
            @else
                <h3 class="text-center p-3 border-b border-b-slate-600 text-xl font-mono text-slate-500 w-full">
                    Format <span class="text-orange-500/55 underline underline-offset-2">par défaut</span> de l'entête
                </h3>
                <div wire:key="preview-default" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex items-center flex-col my-4 mx-auto w-full overflow-x-auto opacity-75">
                    <table class="z-table-border w-full font-thin">
                        <colgroup>
                            <col>
                            @foreach ($this->defaultOrderedColumns as $th)
                                <col>
                            @endforeach
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="px-3 py-4 text-center text-sm text-slate-400">N°</th>
                                @foreach ($this->defaultOrderedColumns as $th)
                                    <th
                                        class="px-3 py-4 text-center text-sm text-slate-400 font-thin whitespace-nowrap">
                                        {{ $th }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
                <p class="text-center text-slate-600 text-sm font-mono mt-2 animate-pulse">
                    Cochez une colonne ci-dessus pour personnaliser l'entête...
                </p>
            @endif
        </section>

        <button title="Lancer la procédure d'impression" wire:click="initPrintProcess" wire:loading.attr="disabled"
            wire:target="initPrintProcess"
            class="flex items-center w-full justify-center gap-1.5 py-4 px-3 rounded-xl bg-sky-800/80 hover:bg-sky-600/90 hover:text-black text-sky-400 transition-all whitespace-nowrap disabled:opacity-50 active:scale-95 text-sm">
            <span wire:loading.remove wire:target="initPrintProcess"
                class="inline-flex items-center gap-1.5 font-semibold">
                <x-lucide-send class="w-4 h-4" />
                <span>Lancer la procédure d'impression</span>
                <span class="text-xs">({{ __zero($this->allClassesCounter) }} classe(s) concernée(s))</span>
            </span>
            <span wire:loading wire:target="initPrintProcess" class="inline-flex items-center gap-1.5">
                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                <span>En cours...</span>
            </span>
        </button>

    </div>
</div>

