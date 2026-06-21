<div class="flex flex-col gap-1 p-5 w-full justify-center mx-auto">
    <div>
        <section class="bg-slate-900/80 backdrop-blur-xl rounded-2xl mt-2.5 border border-slate-600">
            <div class="w-full px-4 py-1">
                <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 min-w-0 flex-1 p-2 my-2">
                        <div class="shrink-0 self-start">
                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                                <x-lucide-calendar-cog class="h-10 w-10 font-extrabold text-slate-600" />
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h1 class="text-xl font-bold leading-tight break-words font-mono">
                                    Modifier l'année scolaire {{ $schoolYear }}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="flex-col justify-center w-full mx-auto">
        <div class="space-y-6">
            <div
                class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-slate-700 rounded-2xl border border-slate-700">
                <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                    <x-lucide-calendar class="w-5 h-5" />
                    <h3>L'année scolaire</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="schoolYear">Année scolaire
                            <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live='schoolYear' id="schoolYear"
                            class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all">
                            @foreach ($this->yearOptions as $sy)
                                <option class="bg-slate-800 text-slate-300" value="{{ $sy }}">
                                    {{ $sy }}</option>
                            @endforeach
                        </select>
                        @error('schoolYear')
                            <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="periode_type">Type de périodes
                            <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live='periode_type' id="periode_type"
                            class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all">
                            @foreach (config('app.periode_types') as $pt)
                                <option class="bg-slate-800 text-slate-300" value="{{ $pt }}">
                                    {{ $pt }}</option>
                            @endforeach
                        </select>
                        @error('periode_type')
                            <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div wire:loading wire:target='schoolYear, periode_type'
                class="flex gap-1.5 w-full items-center text-center text-gray-600 justify-center mt-9.5 mx-auto">
                <div class="w-full flex flex-col items-center justify-center p-3">
                    <x-lucide-loader class="w-10 h-10 animate-spin" />
                    <span>Chargement en cours ...</span>
                </div>
            </div>

            @foreach ($periods as $i => $period)
                <div wire:loading.remove wire:target='schoolYear, periode_type' wire:key="period-{{ $i }}"
                    class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-slate-700 rounded-2xl border border-slate-700">
                    <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                        <x-lucide-calendar class="w-5 h-5" />
                        <h3>{{ $periode_type }} {{ $i + 1 }}</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium mb-2 text-gray-300">
                                <span>Début du {{ $periode_type }} {{ $i + 1 }} <span
                                        class="text-red-500">*</span></span>
                                @if ($period['start'])
                                    <span
                                        class="text-xs text-indigo-400 font-normal">{{ $this->formattedDate($period['start']) }}</span>
                                @endif
                            </label>
                            <input wire:model.live="periods.{{ $i }}.start"
                                min="{{ $i === 0? $year_min: ($periods[$i - 1]['end'] ?? null? \Carbon\Carbon::parse($periods[$i - 1]['end'])->addDay()->toDateString(): $year_min) }}"
                                max="{{ $year_max }}" type="date"
                                class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all">
                            @error("periods.{$i}.start")
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium mb-2 text-gray-300">
                                <span>Fin du {{ $periode_type }} {{ $i + 1 }} <span
                                        class="text-red-500">*</span></span>
                                @if ($period['end'])
                                    <span
                                        class="text-xs text-indigo-400 font-normal">{{ $this->formattedDate($period['end']) }}</span>
                                @endif
                            </label>
                            <input wire:model.live="periods.{{ $i }}.end"
                                min="{{ $period['start'] ? \Carbon\Carbon::parse($period['start'])->addDay()->toDateString() : $year_min }}"
                                max="{{ $year_max }}" type="date"
                                class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all">
                            @error("periods.{$i}.end")
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" wire:loading.attr="disabled" wire:click="update"
            class="p-3 rounded-2xl w-full my-3.5 flex items-center justify-center cursor-pointer bg-indigo-600 hover:bg-indigo-800">
            <span class="flex items-center gap-1.5" wire:target='update' wire:loading.remove>
                <span>Enregistrer les modifications</span>
                <x-lucide-save class="w-5 h-5" />
            </span>
            <span wire:target='update' wire:loading.flex class="items-center gap-1.5">
                <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                <span>En cours...</span>
            </span>
        </button>
    </div>
</div>
