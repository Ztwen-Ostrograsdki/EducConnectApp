<div>
    <div class="mb-20 p-2 relative ">
        <div wire:loading wire:target='resetFilters, restoreSelects, promotionInGroups, initPrintProcess'
            class="absolute inset-0 flex items-center justify-center bg-slate-800/10 backdrop-blur-sm"
            style="z-index: 200 !important;">

            <div class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-col">
                <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
                <span class="text-xl font-mono ls-1">Chargement en cours...</span>
            </div>
        </div>
        <div class="flex justify-end p-3">
            <a href="{{ route('tenant.students.print.list') }}"
                class="py-2.5 px-5 rounded-2xl bg-purple-500/60 hover:bg-purple-600 text-white hover:text-black border border-purple-400 transition-all text-sm inline-flex items-center justify-center active:scale-95">
                <span class="flex items-center gap-2">
                    <x-lucide-eye class="h4 w-4" />
                    <span>Prévisulaisation de la liste à imprimer</span>
                </span>
            </a>
        </div>
        <section
            class="rounded-2xl p-3 bg-indigo-900/10 border border-indigo-900 overflow-hidden flex flex-col gap-3 mb-3">

            <h4 class="text-lg font-bold ls-1 border-b border-b-slate-600 py-2 text-indigo-700">Cibles les apprenants à
                imprimer</h4>

            <div class="flex w-full gap-3 items-center">

                <div class="grid grid-cols-3 items-center gap-3 w-full">

                    @if (!$filiar_id && !$serial_id && !$promotion_id && !$promotionInGroups)
                        <select wire:model.live='classe_id'
                            class="h-12  rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                            <option value="">Toutes les classes</option>
                            @foreach ($this->classes as $cl)
                                <option value="{{ $cl->id }}">
                                    Classe de {{ $cl->code ? $cl->code : $cl->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    @if (!$classe_id)
                        @if (!$promotion_id)
                            @if (!$serial_id)
                                <select wire:model.live='filiar_id'
                                    class="h-12  rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                                    <option value="">Toutes les filières</option>
                                    @foreach ($this->filiars as $f)
                                        <option value="{{ $f->id }}">
                                            Filière {{ $f->code ? $f->code : $f->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            @if (!$filiar_id)
                                <select wire:model.live='serial_id'
                                    class="h-12  rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                                    <option value="">Toutes les séries</option>
                                    @foreach ($this->serials as $sr)
                                        <option value="{{ $sr->id }}">
                                            Série {{ $sr->code ? $sr->code : $sr->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        @endif

                        @if (!$filiar_id && !$serial_id && !$promotionInGroups)
                            <select wire:model.live='promotion_id'
                                class="h-12  rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                                <option value="">Toutes les promotions spécifiées</option>
                                @foreach ($this->promotions as $promo)
                                    <option value="{{ $promo->id }}">
                                        Promotion
                                        {{ $promo->code ? $promo->code : $promo->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        @if (!$promotion_id)
                            <select wire:model.live='promotionInGroups'
                                class="h-12  rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                                <option value="">Toutes les promotions</option>
                                @foreach ($this->promotionsGrouped as $kp => $n)
                                    <option value="{{ $n }}">
                                        Promotion
                                        {{ $n }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    @endif

                    <select wire:model.live='department'
                        class="h-11 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        <option value="">Department</option>
                        @foreach ($this->departments as $dp => $dpv)
                            <option value="{{ $dpv }}">{{ $dpv }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live='city'
                        class="h-11 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        <option value="">Ville</option>
                        @foreach ($this->cities as $ct => $ctv)
                            <option value="{{ $ctv }}">{{ $ctv }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live='gender'
                        class="h-11 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        <option value="">Sexe</option>
                        @foreach ($this->genders as $gk => $gdr)
                            <option value="{{ $gk }}">{{ $gdr }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </section>

        <section
            class="rounded-2xl p-3 bg-indigo-900/10 border border-indigo-900 overflow-hidden flex flex-col gap-3 mb-3">

            <h4 class="text-lg font-bold ls-1 border-b border-b-slate-600 py-2 text-indigo-700">Cibles le statut des
                apprenants à imprimer</h4>

            <div class="flex w-full gap-3 items-center">

                <div class="grid grid-cols-3 items-center gap-3 w-full font-mono text-slate-500">
                    <select wire:model.live='studentTypesActivesOrNotTargeted'
                        class="py-3 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        @foreach ($studentsTypesActivesOrNot as $stk => $stn)
                            <option value="{{ $stk }}">{{ $stn }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live='studentsTypesWithOrWithoutClasses'
                        class="py-3 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        @foreach ($studentsWithOrWithoutClasses as $sck => $scn)
                            <option value="{{ $sck }}">{{ $scn }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live='trashedStatus'
                        class="py-3 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                        @foreach ($trashedStatuses as $sstk => $sstn)
                            <option value="{{ $sstk }}">{{ $sstn }}</option>
                        @endforeach
                    </select>

                </div>
            </div>

        </section>

        <section class="my-2 border border-slate-700 rounded-2xl p-3 flex flex-col gap-2">
            <div class="flex justify-between items-center p-3 border-b border-b-slate-600  font-mono">
                <span>Format ou cible à imprimer</span>

                <button wire:click='resetFilters'
                    class="flex items-center justify-center py-2 rounded-2xl bg-slate-600 px-4 hover:bg-slate-800 text-sm uppercase font-mono active:scale-95">
                    <span wire:loading.remove wire:target='resetFilters' class="inline-flex gap-x-2 items-center">
                        <span class="inline-flex gap-x-2 items-center">
                            <x-lucide-refresh-ccw class="w-4 h-4" />
                            <span class="truncate">Réinitialiser les sélections</span>
                        </span>
                    </span>
                    <span wire:loading wire:target='resetFilters' class="inline-flex items-center gap-x-2">
                        <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                    </span>
                </button>
            </div>
            <div class="flex justify-start items-center text-xl text-slate-600 font-mono">
                <span>Enregistrements trouvés : {{ __zero($this->allStudentsCounter) }}</span>
            </div>
        </section>
        <section class="my-2 border border-slate-700 rounded-2xl p-3 flex flex-col gap-2">
            <div class="flex justify-between items-center p-1 border-b gap-2 border-b-slate-600  font-mono">
                <h3 class="text-left text-orange-500/70 md:text-lg text-sm">
                    Personnaliser l'entête du tableau de la liste.
                </h3>
                <button title="Nettoyer les selections" wire:click="restoreSelects" wire:loading.attr="disabled"
                    wire:target="restoreSelects"
                    class="flex items-center min-w-auto justify-center gap-1.5 h-9 px-3 rounded-xl bg-orange-800/80 hover:bg-orange-600/90 hover:text-black text-orange-400 transition-all whitespace-nowrap disabled:opacity-50 active:scale-95 text-sm">
                    <span wire:loading.remove wire:target="restoreSelects" class="inline-flex items-center gap-1.5">
                        <x-lucide-trash class="w-4 h-4" />
                        <span>Nettoyer</span>
                    </span>
                    <span wire:loading wire:target="restoreSelects" class="inline-flex items-center gap-1.5">
                        <span class="inline-flex items-center gap-1.5">
                            <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                            <span>En cours...</span>
                        </span>
                    </span>
                </button>
            </div>
            <div class="grid sm:grid-cols-4 grid-cols-2 gap-5">
                @foreach ($this->availableColumns as $key => $column)
                    @php($order = array_search($key, $selectedColumns, true))
                    <div class="flex flex-col gap-2 items-center rounded-2xl p-2 border border-slate-500"
                        wire:key="col-{{ $key }}">
                        <label class="flex cursor-pointer items-center justify-between gap-2 w-full h-full">
                            <span class="flex justify-start gap-2 font-mono">
                                @if ($order !== false)
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs text-white animate-pulse border-2 p-1">
                                        {{ $order + 1 }}
                                    </span>
                                @endif
                                <h4>{{ $column['label'] }}</h4> {{-- <-- ici --}}
                            </span>
                            <span class="relative flex shrink-0 items-center justify-end">
                                <input type="checkbox" wire:click="toggleColumn('{{ $key }}')"
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
        </section>
        <section
            class="flex flex-col justify-center items-center p-3 my-3 w-full border border-orange-500/30 rounded-2xl">
            @if (count($this->orderedColumns))
                <h3 class="text-center p-3 border-b border-b-slate-600 text-xl font-mono text-slate-500 w-full">Format
                    de
                    l'entête
                </h3>
                <div class="flex items-center flex-col my-4 mx-auto w-full">
                    <table class="z-table-border w-full">
                        <colgroup>
                            <col style="">
                            @foreach ($this->orderedColumns as $kc => $th)
                                <col style="">
                            @endforeach
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="px-3 py-4 text-center text-sm text-slate-400">N°</th>
                                @foreach ($this->orderedColumns as $kc => $th)
                                    <th class="px-3 py-4 text-center text-sm text-slate-400">{{ $th }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
            @elseif($defaultColumns)
                <h3 class="text-center p-3 border-b border-b-slate-600 text-xl font-mono text-slate-500 w-full">Format
                    <span class="text-orange-500/55 underline underline-offset-2">par défaut</span>
                    de
                    l'entête
                </h3>
                <div class="flex items-center flex-col my-4 mx-auto w-full opacity-75">
                    <table class="z-table-border w-full font-thin">
                        <colgroup>
                            <col style="">
                            @foreach ($defaultColumns as $dfkc => $dfth)
                                <col style="">
                            @endforeach
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="px-3 py-4 text-center text-sm text-slate-400">N°</th>
                                @foreach ($defaultColumns as $dfkc => $dfth)
                                    <th class="px-3 py-4 text-center text-sm text-slate-400 font-thin">
                                        {{ $dfth['label'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
            @else
                <span class="text-center my-5 font-mono text-slate-700 animate-pulse text-xl">En attente des
                    sélections....</span>
            @endif
        </section>

        <button title="Lancer la procédure d'inmpression" wire:click="initPrintProcess" wire:loading.attr="disabled"
            wire:target="initPrintProcess"
            class="flex items-center w-full justify-center gap-1.5 py-4 px-3 rounded-xl bg-sky-800/80 hover:bg-sky-600/90 hover:text-black text-sky-400 transition-all whitespace-nowrap disabled:opacity-50 active:scale-95  text-sm border border-sky-300">
            <span wire:loading.remove wire:target="initPrintProcess"
                class="inline-flex items-center gap-1.5 font-semibold">
                <x-lucide-send class="w-4 h-4" />
                <span>Lancer la procédure d'impression </span>
                <span class="text-xs">({{ __zero($this->allStudentsCounter) }} enregistrements seront imprimés)</span>
            </span>
            <span wire:loading wire:target="initPrintProcess" class="inline-flex items-center gap-1.5">
                <span class="inline-flex items-center gap-1.5">
                    <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                    <span>En cours...</span>
                </span>
            </span>
        </button>

    </div>

</div>

