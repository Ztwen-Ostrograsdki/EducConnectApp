<div class="min-h-screen bg-[#070b14] text-slate-100 p-4 sm:p-6">

    <div class="mx-auto max-w-4xl space-y-6">

        {{-- ===================== HEADER ===================== --}}
        <section
            class="relative overflow-hidden rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-xl shadow-black/20">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/[0.07] via-transparent to-transparent"></div>

            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-indigo-500/25 bg-indigo-500/15">
                        <x-lucide-calendar-plus class="h-7 w-7 text-indigo-400" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-white sm:text-2xl">
                            Nouvelle année scolaire
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Définissez l’année et configurez les périodes
                        </p>
                    </div>
                </div>

                <button type="button" wire:click="discardDraft"
                    class="inline-flex h-10 items-center gap-2 self-start rounded-xl border border-rose-500/25 bg-rose-500/10 px-4 text-sm font-medium text-rose-300 transition-all hover:bg-rose-500/20 hover:border-rose-500/40 sm:self-center">
                    <x-lucide-trash-2 class="h-4 w-4" />
                    Vider le formulaire
                </button>
            </div>
        </section>

        {{-- ===================== FORMULAIRE ===================== --}}
        <div class="space-y-5">

            {{-- Année + Type de période --}}
            <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-lg shadow-black/10">
                <div class="mb-5 flex items-center gap-3 border-b border-white/[0.05] pb-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-indigo-500/20 bg-indigo-500/15">
                        <x-lucide-calendar class="h-4.5 w-4.5 text-indigo-400" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">L’année scolaire</h3>
                        <p class="text-xs text-slate-500">Choisissez l’année et le type de périodes</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                            Année scolaire <span class="text-rose-400">*</span>
                        </label>
                        <select wire:model.live="schoolYear" id="schoolYear"
                            class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                            <option value="">Sélectionnez l’année scolaire</option>
                            @foreach (__defaultSchoolYears() as $sy)
                                <option value="{{ $sy }}">{{ $sy }}</option>
                            @endforeach
                        </select>
                        @error('schoolYear')
                            <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                <x-lucide-octagon-alert class="h-3.5 w-3.5" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                            Type de périodes <span class="text-rose-400">*</span>
                        </label>
                        <select wire:model.live="periode_type" id="periode_type"
                            class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                            <option value="">Sélectionnez le type de période</option>
                            @foreach (config('app.periode_types') as $pt)
                                <option value="{{ $pt }}">{{ $pt }}</option>
                            @endforeach
                        </select>
                        @error('periode_type')
                            <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                <x-lucide-octagon-alert class="h-3.5 w-3.5" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Loading --}}
            <div wire:loading.flex wire:target="schoolYear, periode_type"
                class="flex flex-col items-center justify-center gap-3 rounded-2xl border border-white/[0.06] bg-[#0f1523]/60 py-12">
                <div class="relative flex h-12 w-12 items-center justify-center">
                    <div class="absolute inset-0 rounded-full bg-indigo-500/20 blur-md animate-pulse"></div>
                    <x-lucide-loader-2 class="relative h-8 w-8 animate-spin text-indigo-400" />
                </div>
                <p class="text-sm text-slate-500">Chargement des périodes…</p>
            </div>

            {{-- Périodes dynamiques --}}
            @if ($periode_type && $schoolYear)
                <div wire:loading.remove wire:target="schoolYear, periode_type" class="space-y-4">
                    @foreach ($periods as $i => $period)
                        <div wire:key="period-{{ $i }}"
                            class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-lg shadow-black/10">
                            <div class="mb-5 flex items-center gap-3 border-b border-white/[0.05] pb-4">
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-sky-500/20 bg-sky-500/15">
                                    <span class="text-sm font-bold text-sky-400">{{ $i + 1 }}</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-white">
                                        {{ $periode_type }} {{ $i + 1 }}
                                    </h3>
                                    <p class="text-xs text-slate-500">
                                        Définissez les dates de début et de fin
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                {{-- Début --}}
                                <div>
                                    <label
                                        class="mb-1.5 flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                                        <span>
                                            Début <span class="text-rose-400">*</span>
                                        </span>
                                        @if ($period['start'])
                                            <span
                                                class="rounded-md bg-indigo-500/15 px-2 py-0.5 text-[10px] font-medium normal-case text-indigo-300">
                                                {{ $this->formattedDate($period['start']) }}
                                            </span>
                                        @endif
                                    </label>
                                    <input wire:model.live="periods.{{ $i }}.start" type="date"
                                        min="{{ $i === 0
                                            ? $year_min
                                            : ($periods[$i - 1]['end'] ?? null
                                                ? \Carbon\Carbon::parse($periods[$i - 1]['end'])->addDay()->toDateString()
                                                : $year_min) }}"
                                        max="{{ $year_max }}" id="periods.{{ $i }}.start"
                                        class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                    @error("periods.{$i}.start")
                                        <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                            <x-lucide-octagon-alert class="h-3.5 w-3.5" />
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Fin --}}
                                <div>
                                    <label
                                        class="mb-1.5 flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                                        <span>
                                            Fin <span class="text-rose-400">*</span>
                                        </span>
                                        @if ($period['end'])
                                            <span
                                                class="rounded-md bg-indigo-500/15 px-2 py-0.5 text-[10px] font-medium normal-case text-indigo-300">
                                                {{ $this->formattedDate($period['end']) }}
                                            </span>
                                        @endif
                                    </label>
                                    <input wire:model.live="periods.{{ $i }}.end" type="date"
                                        min="{{ $period['start'] ? \Carbon\Carbon::parse($period['start'])->addDay()->toDateString() : $year_min }}"
                                        max="{{ $year_max }}" id="periods.{{ $i }}.end"
                                        class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                    @error("periods.{$i}.end")
                                        <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                            <x-lucide-octagon-alert class="h-3.5 w-3.5" />
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ===================== BOUTON ===================== --}}
        <button type="button" wire:click="create" wire:loading.attr="disabled"
            class="group relative flex h-13 w-full items-center justify-center gap-2 overflow-hidden rounded-2xl bg-emerald-600 text-sm font-semibold text-white shadow-xl shadow-emerald-900/40 transition-all hover:bg-emerald-500 disabled:opacity-60">
            <span
                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>

            <span wire:loading.remove wire:target="create" class="relative inline-flex items-center gap-2">
                Terminer
                <x-lucide-check class="h-4.5 w-4.5" />
            </span>
            <span wire:loading wire:target="create" class="relative inline-flex items-center gap-2">
                <x-lucide-loader-2 class="h-4.5 w-4.5 animate-spin" />
                Création en cours…
            </span>
        </button>

    </div>
</div>
