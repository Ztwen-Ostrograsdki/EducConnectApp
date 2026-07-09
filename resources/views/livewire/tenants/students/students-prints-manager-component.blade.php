<div>
    <section class="mb-6 p-2 relative">
        <div wire:loading wire:target='resetFilters'
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
        <div class="rounded-2xl p-3 bg-indigo-900/10 border border-indigo-900 overflow-hidden flex flex-col gap-3">

            <div class="flex w-full justify-end gap-3 items-center">

                <div class="flex items-center flex-wrap gap-3">

                    <select wire:model.live='classe_id'
                        class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                        <option value="">Toutes les classes</option>
                        @foreach ($this->classes as $cl)
                            <option value="{{ $cl->id }}">
                                Classe de {{ $cl->code ? $cl->code : $cl->name }}
                            </option>
                        @endforeach
                    </select>

                    <select wire:model.live='filiar_id'
                        class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                        <option value="">Toutes les filières</option>
                        @foreach ($this->filiars as $f)
                            <option value="{{ $f->id }}">
                                Filière {{ $f->code ? $f->code : $f->name }}
                            </option>
                        @endforeach
                    </select>

                    <select wire:model.live='promotion_id'
                        class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                        <option value="">Toutes les promotions</option>
                        @foreach ($this->promotions as $promo)
                            <option value="{{ $promo->id }}">
                                Promotion
                                {{ $promo->code ? $promo->code : $promo->name }}
                            </option>
                        @endforeach
                    </select>

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

                    <select wire:model.live='status'
                        class="h-12  uppercase font-mono rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm">
                        <option value="">
                            <span>Tout statut </span>
                        </option>
                        <option class="text-green-400" value="actives">
                            <span>
                                <span>Actifs</span>
                            </span>
                        </option>
                        <option value="desactives">
                            <span>Bloqués</span>
                        </option>
                        <option class="text-orange-600" value="corbeille">
                            <span>La corbeille</span>
                        </option>
                    </select>
                </div>
            </div>
            <span wire:click='resetFilters'
                class="flex items-center justify-center h-12 min-w-[220px] rounded-2xl bg-slate-600 px-4 hover:bg-slate-800 text-sm uppercase font-mono">
                <span wire:loading.remove wire:target='resetFilters' class="inline-flex gap-x-2 items-center">
                    <span class="inline-flex gap-x-2 items-center">
                        <x-lucide-refresh-ccw class="w-4 h-4" />
                        <span class="truncate">Réinitialiser les sélections</span>
                    </span>
                </span>
                <span wire:loading wire:target='resetFilters' class="inline-flex items-center gap-x-2">
                    <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                </span>

            </span>
        </div>

    </section>

</div>

