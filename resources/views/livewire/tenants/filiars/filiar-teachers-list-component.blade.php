<section class="mb-6 p-2 relative" x-data="{ open: true }">
    <div wire:loading
        wire:target='teachers_classe_id,teachers_gender,teachers_subject_id,teachers_promotion_id,previousPage,nextPage,resetFilters,resetTeachersFilters, gotoPage'
        class="absolute inset-0 flex items-center justify-center bg-slate-800/10 backdrop-blur-sm"
        style="z-index: 200 !important;">

        <div class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-col">
            <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <span class="text-xl font-mono ls-1">Chargement en cours...</span>
        </div>
    </div>
    <div class="rounded-tl-2xl rounded-tr-2xl bg-indigo-900/10 border border-indigo-900 overflow-hidden">

        <div type="button" @click="open = !open"
            class="w-full text-left p-5 border-b border-slate-800 flex flex-col gap-4">
            <div>
                <h2 class="text-xl font-semibold flex items-center gap-2">
                    Enseignants de la filière
                    <svg :class="open ? 'rotate-180' : 'rotate-0'" class="w-5 h-5 transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </h2>
                <p class="mt-1 text-sm text-slate-400">Gestion des enseignants et classes concernées.</p>
            </div>

            {{-- FILTERS --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-3 items-center" @click.stop>
                <select wire:model.live='teachers_gender'
                    class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                    <option value="">Tout genre</option>
                    @foreach (config('app.genders') as $tgk => $tg)
                        <option value="{{ $tgk }}">
                            {{ $tg }}
                        </option>
                    @endforeach
                </select>
                <select wire:model.live='teachers_subject_id'
                    class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                    <option value="">Toutes les matières</option>
                    @foreach ($this->subjects as $tsubject)
                        <option value="{{ $tsubject->id }}">
                            {{ $tsubject->code ? $tsubject->code : $tsubject->name }}
                        </option>
                    @endforeach
                </select>

                <select wire:model.live='teachers_classe_id'
                    class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                    <option>Toutes les classes</option>
                    @foreach ($this->classes as $tclasse)
                        <option value="{{ $tclasse->id }}">
                            Classe de {{ $tclasse->code ? $tclasse->code : $tclasse->name }}
                        </option>
                    @endforeach
                </select>

                <select wire:model.live='teachers_promotion_id'
                    class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                    <option>Toutes les promotions</option>
                    @foreach ($this->promotions as $tpromo)
                        <option value="{{ $tpromo->id }}">
                            Promotion {{ $tpromo->code ? $tpromo->code : $tpromo->name }}
                        </option>
                    @endforeach
                </select>
                <span wire:click='resetTeachersFilters'
                    class="flex items-center justify-center h-12 rounded-2xl bg-slate-600 px-4 hover:bg-slate-800 text-sm uppercase font-mono cursor-pointer">
                    <span wire:loading.remove wire:target='resetTeachersFilters'
                        class="inline-flex gap-x-2 items-center">
                        <span class="inline-flex gap-x-2 items-center">
                            <x-lucide-refresh-ccw class="w-4 h-4" />
                            <span class="truncate">Réinitialiser les filtres</span>
                        </span>
                    </span>
                    <span wire:loading wire:target='resetTeachersFilters' class="inline-flex items-center gap-x-2">
                        <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                    </span>
                </span>
            </div>
        </div>

        <div x-show="open" x-collapse x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="overflow-x-auto">
            @livewire('tenants.components.teachers-lister-component', ['filiar_id' => $this->filiar->id, 'filiar' => $this->filiar, 'teachers_promotion_id' => $teachers_promotion_id, 'teachers_gender' => $teachers_gender, 'teachers_classe_id' => $teachers_classe_id])
        </div>

    </div>
</section>

