<div class="min-h-screen bg-slate-950 text-slate-100 w-full max-w-full px-3 overflow-x-hidden">

    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}
    <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl rounded-2xl mt-2.5">
        <div class="w-full max-w-full px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                {{-- LEFT --}}
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 min-w-0 flex-1">

                    {{-- ICON --}}
                    <div class="shrink-0 self-start">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                            {{ $school_year_model->slug }}
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold leading-tight break-words">
                                Année scolaire : <span
                                    class="text-amber-500 opacity-70">{{ $school_year_model->slug }}</span>
                            </h1>
                            <span
                                class="shrink-0 px-3 py-1 rounded-full text-xs bg-emerald-500/10 border border-emerald-500/20 {{ $school_year_model->is_active ? 'text-emerald-400' : 'text-red-400' }} text-xs shrink-0">
                                {{ $school_year_model->is_active ? 'Active' : 'Non active' }}
                            </span>
                        </div>
                        <p class="mt-3 text-sm sm:text-base text-slate-400 break-words">
                            Les détails génraux de l'année scolaire {{ $school_year_model->slug }}
                        </p>

                        {{-- META --}}
                        <div class="mt-4 flex flex-col sm:flex-row sm:flex-wrap gap-2 sm:gap-5 text-sm text-slate-400">
                            <div class="break-words">📑 Périodes en {{ $school_year_model->periode_type }}</div>
                            <div class="break-words">Durée : 🕒 {{ $school_year_model->getDuration() }}</div>
                        </div>
                    </div>

                </div>

                {{-- ACTIONS --}}

            </div>
            <div class="flex flex-wrap gap-3 items-center justify-end w-full xl:w-auto">

                @if (!$school_year_model->is_closed)
                    <button type="button" wire:loading.attr="disabled" wire:click="{{ 'closed' }}"
                        class="p-3 rounded-2xl my-3.5 flex items-center justify-center cursor-pointer bg-amber-600 hover:bg-amber-800">
                        <span class="flex items-center gap-1.5" wire:target='closed' wire:loading.remove>
                            <span>Fermer cette année</span>
                            <x-lucide-check class="w-5 h-5" />
                        </span>
                        <span wire:target='closed' wire:loading.flex class="items-center gap-1.5">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </button>
                @else
                    <button type="button" wire:loading.attr="disabled" wire:click="{{ 'reOpen' }}"
                        class="p-3 rounded-2xl my-3.5 flex items-center justify-center cursor-pointer bg-lime-600 hover:bg-lime-800">
                        <span class="flex items-center gap-1.5" wire:target='reOpen' wire:loading.remove>
                            <span>Réouvrir</span>
                            <x-lucide-check class="w-5 h-5" />
                        </span>
                        <span wire:target='reOpen' wire:loading.flex class="items-center gap-1.5">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </button>
                @endif

                @if (!$school_year_model->is_active)
                    <button type="button" wire:loading.attr="disabled" wire:click="{{ 'activateSchoolYear' }}"
                        class="p-3 rounded-2xl my-3.5 flex items-center justify-center cursor-pointer bg-green-600 hover:bg-green-800">
                        <span class="flex items-center gap-1.5" wire:target='activateSchoolYear' wire:loading.remove>
                            <span>Définir comme année active</span>
                            <x-lucide-check class="w-5 h-5" />
                        </span>
                        <span wire:target='activateSchoolYear' wire:loading.flex class="items-center gap-1.5">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </button>
                @else
                    <button type="button" wire:loading.attr="disabled" wire:click="{{ 'desactivateSchoolYear' }}"
                        class="p-3 rounded-2xl my-3.5 flex items-center justify-center cursor-pointer bg-red-600 hover:bg-red-800">
                        <span class="flex items-center gap-1.5" wire:target='desactivateSchoolYear' wire:loading.remove>
                            <span>Désactiver</span>
                            <x-lucide-check class="w-5 h-5" />
                        </span>
                        <span wire:target='desactivateSchoolYear' wire:loading.flex class="items-center gap-1.5">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </button>
                @endif
                <a href="{{ route('tenant.schoolYears.edit', ['school_year' => $school_year_model->slug]) }}"
                    class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300 text-sm sm:text-base">
                    Editer cette année scolaire
                </a>
            </div>
        </div>
    </section>
    <section class="w-full justify-center flex items-center my-4">
        <div class="flex flex-col gap-y-4 items-center w-full">
            @foreach ($school_year_model->periods as $position => $period)
                @php
                    $start = \Carbon\Carbon::parse($period['start']);
                    $end = \Carbon\Carbon::parse($period['end']);
                    $today = now()->startOfDay();

                    $totalDays = $start->diffInDays($end) + 1; // inclusif
                    $weeks = intdiv($totalDays, 7);
                    $remDays = $totalDays % 7;

                    $status = $today->lt($start) ? 'a_venir' : ($today->gt($end) ? 'passe' : 'en_cours');

                    $elapsed = max(0, min($totalDays, $start->diffInDays($today) + 1));
                    $progress = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 0;

                    $dayCount = $today->between($start, $end) ? $start->diffInDays($today) + 1 : null;
                @endphp
                <div wire:key='period-of-school-year-{{ $loop->iteration }}'
                    class="w-full rounded-2xl border {{ $status === 'passe' ? 'border-slate-800 bg-slate-900/40' : 'border-slate-700 bg-slate-900/80' }} backdrop-blur-xl p-5 transition-all opacity-50 hover:opacity-100">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-5 border-b border-b-gray-600 py-2">
                        <h3
                            class=" font-mono uppercase text-base font-semibold {{ $status === 'passe' ? 'text-amber-700' : 'text-green-600' }}">
                            {{ $position }}
                        </h3>

                        @if ($status === 'en_cours')
                            <span
                                class="flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-400"></span>
                                </span>
                                En cours
                            </span>
                        @elseif ($status === 'passe')
                            <span
                                class="flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-amber-800 text-amber-500 border border-amber-700">
                                <x-lucide-check class="w-3 h-3" />
                                Terminé
                            </span>
                        @else
                            <span
                                class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-800 text-slate-400 border border-slate-700">
                                À venir
                            </span>
                        @endif
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-slate-500 mb-1">Début</p>
                            <p
                                class="font-mono text-sm tabular-nums {{ $status === 'passe' ? 'text-slate-500' : 'text-slate-200' }}">
                                {{ $start->locale('fr')->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-slate-500 mb-1">Fin</p>
                            <p
                                class="font-mono text-sm tabular-nums {{ $status === 'passe' ? 'text-slate-500' : 'text-slate-200' }}">
                                {{ $end->locale('fr')->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-slate-500 mb-1">Durée</p>
                            <p
                                class="font-mono text-sm tabular-nums {{ $status === 'passe' ? 'text-slate-500' : 'text-slate-200' }}">
                                {{ $weeks }}
                                sem{{ $weeks > 1 ? 's' : '' }}{{ $remDays > 0 ? ' ' . $remDays . ' j' : '' }}
                            </p>
                        </div>
                    </div>

                    {{-- Timeline signature --}}
                    <div>
                        <div class="relative h-1.5 rounded-full bg-slate-800 overflow-hidden">
                            <div class="absolute inset-y-0 left-0 rounded-full transition-all duration-500 {{ $status === 'passe' ? 'bg-slate-600' : 'bg-indigo-500' }}"
                                style="width: {{ $progress }}%"></div>

                            @if ($status === 'en_cours')
                                <div class="absolute top-1/2 -translate-y-1/2 h-3 w-3 rounded-full bg-indigo-400 ring-4 ring-indigo-400/20"
                                    style="left: calc({{ $progress }}% - 6px)"></div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between mt-2 text-[11px] text-slate-500 font-mono">
                            <span>{{ $start->locale('fr')->translatedFormat('d M') }}</span>
                            @if ($status === 'en_cours' && $dayCount)
                                <span class="text-indigo-400">Jour {{ $dayCount }} /
                                    {{ $totalDays }}</span>
                            @endif
                            <span>{{ $end->locale('fr')->translatedFormat('d M') }}</span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </section>

</div>

