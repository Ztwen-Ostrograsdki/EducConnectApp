<div class="mx-auto w-full space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('tenant.classes.portal') }}" wire:navigate
            class="rounded-xl border border-slate-700 p-2 text-slate-400 hover:text-white hover:bg-slate-800 transition">
            <x-lucide-arrow-left class="w-4 h-4" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Nouvelle classe</h1>
            <p class="text-sm text-slate-500 mt-0.5">Créer une classe pour une année scolaire</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-700 bg-slate-900 p-6 space-y-5">

        {{-- Année scolaire --}}
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">
                Année scolaire <span class="text-rose-400">*</span>
            </label>
            <select wire:model="school_year_id"
                class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                <option value="0" disabled>Sélectionner une année</option>
                @foreach ($this->schoolYears as $year)
                    <option value="{{ $year->id }}">
                        {{ $year->slug }}{{ $year->is_active ? ' (active)' : '' }}
                    </option>
                @endforeach
            </select>
            @error('school_year_id')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nom + Code --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Nom de la classe <span class="text-rose-400">*</span>
                </label>
                <input type="text" wire:model="name" placeholder="ex: Terminale BTP 2"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                @error('name')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Code <span class="text-slate-600">(optionnel)</span>
                </label>
                <input type="text" wire:model="code" placeholder="ex: TLE-BTP-2"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />
                @error('code')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Promotion + Niveau --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Promotion <span class="text-rose-400">*</span>
                </label>
                <select wire:model="promotion_id"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                    <option value="0" disabled>Sélectionner</option>
                    @foreach ($this->promotions as $promotion)
                        <option value="{{ $promotion->id }}">
                            {{ $promotion->name }}{{ $promotion->code ? ' (' . $promotion->code . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('promotion_id')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Niveau <span class="text-rose-400">*</span>
                </label>
                <select wire:model="level"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                    <option value="primaire">Primaire</option>
                    <option value="secondaire">Secondaire</option>
                    <option value="superieur">Supérieur</option>
                </select>
                @error('level')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Filière + Série --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Filière <span class="text-slate-600">(optionnel)</span>
                </label>
                <select wire:model="filiar_id"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                    <option value="">— Aucune —</option>
                    @foreach ($this->filiars as $filiar)
                        <option value="{{ $filiar->id }}">
                            {{ $filiar->name }}{{ $filiar->code ? ' (' . $filiar->code . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('filiar_id')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">
                    Série <span class="text-slate-600">(optionnel)</span>
                </label>
                <select wire:model="serial_id"
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition">
                    <option value="">— Aucune —</option>
                    @foreach ($this->serials as $serial)
                        <option value="{{ $serial->id }}">
                            {{ $serial->name }}{{ $serial->code ? ' (' . $serial->code . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('serial_id')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Effectif max --}}
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">
                Effectif maximum <span class="text-rose-400">*</span>
            </label>
            <input type="number" wire:model="effectif_max" min="1" max="200"
                class="w-32 rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none transition" />
            @error('effectif_max')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Prof principal (searchable) --}}
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1.5">
                Professeur principal <span class="text-slate-600">(optionnel)</span>
            </label>

            @if ($principal_id)
                {{-- Sélectionné --}}
                <div
                    class="flex items-center gap-3 rounded-xl border border-indigo-500/40 bg-indigo-500/10 px-4 py-2.5">
                    <div
                        class="h-7 w-7 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold text-white">
                        {{ strtoupper(substr($teacherSearch, 0, 1)) }}
                    </div>
                    <span class="text-sm text-white flex-1">{{ $teacherSearch }}</span>
                    <button wire:click="clearTeacher" class="text-slate-500 hover:text-rose-400 transition">
                        <x-lucide-check class="w-4 h-4" />
                    </button>
                </div>
            @else
                {{-- Search input --}}
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="teacherSearch"
                        placeholder="Rechercher un enseignant..."
                        class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none transition" />

                    @if ($this->teachers->isNotEmpty())
                        <div
                            class="absolute z-20 mt-1 w-full rounded-xl border border-slate-700 bg-slate-800 shadow-2xl overflow-hidden">
                            @foreach ($this->teachers as $teacher)
                                <button
                                    wire:click="selectTeacher({{ $teacher->id }}, '{{ addslashes($teacher->name) }}')"
                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left hover:bg-slate-700 transition">
                                    <div
                                        class="h-7 w-7 rounded-full bg-slate-600 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm text-white">{{ $teacher->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $teacher->email }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @elseif (strlen($teacherSearch) >= 2)
                        <div
                            class="absolute z-20 mt-1 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-slate-500">
                            Aucun enseignant trouvé avec un accès valide pour cette année.
                        </div>
                    @endif
                </div>
            @endif

            @error('principal_id')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Toggles --}}
        <div class="flex flex-col gap-3 pt-1">
            <label class="flex items-center gap-3 cursor-pointer">
                <div class="relative">
                    <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                    <div class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-indigo-600 transition"></div>
                    <div
                        class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white transition peer-checked:translate-x-4">
                    </div>
                </div>
                <span class="text-sm text-slate-300">Classe active</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <div class="relative">
                    <input type="checkbox" wire:model="is_locked" class="sr-only peer" />
                    <div class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-amber-600 transition"></div>
                    <div
                        class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white transition peer-checked:translate-x-4">
                    </div>
                </div>
                <span class="text-sm text-slate-300">Verrouiller l'accès enseignants</span>
            </label>
        </div>

    </div>

    {{-- Footer --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('tenant.classes.portal') }}" wire:navigate
            class="px-5 py-2.5 text-sm rounded-xl border border-slate-700 text-slate-400 hover:bg-slate-800 transition">
            Annuler
        </a>
        <button wire:click="save" wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium transition disabled:opacity-50">
            <span wire:loading.remove wire:target="save">
                <x-lucide-check class="w-4 h-4 inline -mt-0.5" /> Créer la classe
            </span>
            <span wire:loading wire:target="save">Enregistrement...</span>
        </button>
    </div>
</div>

