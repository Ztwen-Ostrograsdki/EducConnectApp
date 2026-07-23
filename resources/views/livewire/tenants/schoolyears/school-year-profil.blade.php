<div class="min-h-screen bg-slate-950 text-slate-100 w-full max-w-full px-3 overflow-x-hidden">
    <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl rounded-2xl mt-2.5">
        <div class="w-full max-w-full px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 min-w-0 flex-1">

                    <div class="shrink-0 self-start">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                            {{ $school_year_model->slug }}
                        </div>
                    </div>

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
                            @if ($school_year_model->is_closed)
                                <span
                                    class="shrink-0 px-3 py-1 rounded-full text-xs bg-orange-500/10 border border-orange-500/20 text-orange-400">
                                    Clôturée
                                </span>
                            @endif
                            @if ($school_year_model->trashed())
                                <span
                                    class="shrink-0 px-3 py-1 rounded-full text-xs bg-red-500/10 border border-red-500/20 text-red-400">
                                    À la corbeille
                                </span>
                            @endif
                        </div>
                        <p class="mt-3 text-sm sm:text-base text-slate-400 break-words">
                            Les détails généraux de l'année scolaire {{ $school_year_model->slug }}
                        </p>

                        <div
                            class="mt-4 flex items-center font-mono flex-col sm:flex-row sm:flex-wrap gap-2 sm:gap-5 text-sm text-slate-400">
                            <div class="break-words">📑 Périodes en {{ $school_year_model->periode_type }}</div>
                            <div class="break-words">Durée : 🕒 {{ $school_year_model->getDuration() }}</div>

                            @if ($school_year_model->active_period)
                                <div class="break-words rounded-2xl p-2 bg-green-600/10 text-green-600">Période active ✅
                                    :
                                    {{ $this->school_year_model->periodLabel() . ' ' . $this->active_period }}</div>
                            @else
                                <div class="break-words rounded-2xl p-2 bg-red-600/40 text-red-300 animate-pulse">

                                    Aucun {{ $this->school_year_model->periodLabel() }} n'est actif</div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
            <div class="mt-5 flex flex-wrap gap-3 items-center justify-end w-full xl:w-auto"
                wire:loading.class="opacity-60 pointer-events-none"
                wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}'),closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}'),deleteSchoolYear('{{ $school_year_model->slug }}'),restoreSchoolYear('{{ $school_year_model->slug }}')">

                <a href="{{ route('tenant.schoolYears.edit', ['school_year' => $school_year_model->slug]) }}"
                    class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300 text-sm sm:text-base text-center">
                    Editer cette année scolaire
                </a>

                <button
                    title="{{ $school_year_model->is_active ? 'Désactiver' : 'Activer' }} l'année scolaire {{ $school_year_model->slug }}"
                    wire:click="{{ $school_year_model->is_active ? "deactivateSchoolYear('{$school_year_model->slug}')" : "activateSchoolYear('{$school_year_model->slug}')" }}"
                    wire:loading.attr="disabled"
                    wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}')"
                    class="relative w-full sm:w-auto px-4 py-3 rounded-2xl text-white text-sm sm:text-base font-medium inline-flex items-center justify-center gap-1.5 transition-all duration-300 whitespace-nowrap disabled:opacity-50 {{ $school_year_model->is_active ? 'bg-emerald-600/30 hover:bg-red-600/40' : 'bg-indigo-600/40 hover:bg-indigo-500' }}">
                    <span wire:loading.remove
                        wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}')"
                        class="inline-flex items-center gap-2">
                        @if ($school_year_model->is_active)
                            <x-lucide-star-off class="w-4 h-4" />
                            <span>Désactiver</span>
                        @else
                            <x-lucide-star class="w-4 h-4" />
                            <span>Activer</span>
                        @endif
                    </span>
                    <span wire:loading
                        wire:target="activateSchoolYear('{{ $school_year_model->slug }}'),deactivateSchoolYear('{{ $school_year_model->slug }}')"
                        class="inline-flex items-center gap-2">
                        <span class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            <span>Patientez...</span>
                        </span>
                    </span>
                </button>

                <button
                    title="{{ $school_year_model->is_closed ? 'Réouvrir' : 'Clôturer' }} l'année scolaire {{ $school_year_model->slug }}"
                    wire:click="{{ $school_year_model->is_closed ? "reopenSchoolYear('{$school_year_model->slug}')" : "closeSchoolYear('{$school_year_model->slug}')" }}"
                    wire:loading.attr="disabled"
                    wire:target="closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}')"
                    class="relative w-full sm:w-auto px-4 py-3 rounded-2xl text-white text-sm sm:text-base font-medium inline-flex items-center justify-center gap-1.5 transition-all duration-300 whitespace-nowrap disabled:opacity-50 {{ $school_year_model->is_closed ? 'bg-lime-600/60 hover:bg-lime-500 hover:text-black' : 'bg-orange-500/20 hover:bg-orange-600/60' }}">
                    <span wire:loading.remove
                        wire:target="closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}')"
                        class="inline-flex items-center gap-2">
                        @if ($school_year_model->is_closed)
                            <x-lucide-unlock class="w-4 h-4" />
                            <span>Réouvrir</span>
                        @else
                            <x-lucide-lock class="w-4 h-4" />
                            <span>Clôturer</span>
                        @endif
                    </span>
                    <span wire:loading
                        wire:target="closeSchoolYear('{{ $school_year_model->slug }}'),reopenSchoolYear('{{ $school_year_model->slug }}')"
                        class="inline-flex items-center gap-2">
                        <span class="inline-flex items-center gap-3">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            <span>Patientez...</span>
                        </span>
                    </span>
                </button>

                @if ($school_year_model->trashed())
                    <button title="Restaurer l'année scolaire {{ $school_year_model->slug }}"
                        wire:click="restoreSchoolYear('{{ $school_year_model->slug }}')" wire:loading.attr="disabled"
                        wire:target="restoreSchoolYear('{{ $school_year_model->slug }}')"
                        class="relative w-full sm:w-auto px-4 py-3 rounded-2xl bg-emerald-600/30 hover:bg-emerald-600/60 text-white text-sm sm:text-base font-medium inline-flex items-center justify-center gap-1.5 transition-all duration-300 whitespace-nowrap disabled:opacity-50">
                        <span wire:loading.remove wire:target="restoreSchoolYear('{{ $school_year_model->slug }}')"
                            class="inline-flex items-center gap-2">
                            <x-lucide-rotate-ccw class="w-4 h-4" />
                            <span>Restaurer</span>
                        </span>
                        <span wire:loading wire:target="restoreSchoolYear('{{ $school_year_model->slug }}')"
                            class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            <span>Patientez...</span>
                        </span>
                    </button>
                @else
                    <button title="Mettre l'année scolaire {{ $school_year_model->slug }} à la corbeille"
                        wire:click="deleteSchoolYear('{{ $school_year_model->slug }}')" wire:loading.attr="disabled"
                        wire:target="deleteSchoolYear('{{ $school_year_model->slug }}')"
                        class="relative w-full sm:w-auto px-4 py-3 rounded-2xl bg-red-500/10 hover:bg-red-600/40 text-red-300 hover:text-white text-sm sm:text-base font-medium inline-flex items-center justify-center gap-1.5 transition-all duration-300 whitespace-nowrap disabled:opacity-50">
                        <span wire:loading.remove wire:target="deleteSchoolYear('{{ $school_year_model->slug }}')"
                            class="inline-flex items-center gap-2">
                            <x-lucide-trash-2 class="w-4 h-4" />
                            <span>Supprimer</span>
                        </span>
                        <span wire:loading wire:target="deleteSchoolYear('{{ $school_year_model->slug }}')"
                            class="inline-flex items-center gap-2">
                            <span class="inline-flex items-center gap-3">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                <span>Patientez...</span>
                            </span>
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </section>
    <section class="my-3 font-mono flex flex-col gap-3">
        <div>
            <button
                title="{{ $editing ? 'Fermer le formulaire ' : 'Ouvrir le formulaire' }} édition du {{ $school_year_model->periode_type }} actif de l'année scolaire {{ $school_year_model->slug }}"
                wire:click="toggleEdition" wire:loading.attr="disabled" wire:target="toggleEdition"
                class="relative w-full sm:w-auto px-6 py-3 rounded-2xl text-white text-sm sm:text-base font-medium inline-flex items-center justify-center gap-1.5 transition-all duration-300 whitespace-nowrap disabled:opacity-50 {{ $editing ? 'bg-gray-600/30 hover:bg-gray-600/40 hover:text-orange-500' : 'bg-indigo-600/40 hover:bg-indigo-500 hover:text-black' }} ">
                <span wire:loading.remove wire:target="toggleEdition" class="inline-flex items-center gap-2">
                    @if ($editing)
                        <x-lucide-x class="w-4 h-4" />
                        <span>Annuler</span>
                    @else
                        <x-lucide-pen class="w-4 h-4" />
                        <span>Définir le {{ $school_year_model->periode_type }} actif </span>
                    @endif
                </span>
                <span wire:loading wire:target="toggleEdition" class="inline-flex items-center gap-2">
                    <span class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        <span>Patientez...</span>
                    </span>
                </span>
            </button>
        </div>

        @if ($editing)
            <div class="md:flex border grid-cols-1 grid justify-between border-slate-800 rounded-2xl p-2 items-center">
                <select class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm"
                    wire:model.live='active_period'>
                    <option value="">Choisissez la {{ $school_year_model->periode_type }} actif</option>
                    @foreach ($this->periods as $kp => $pv)
                        <option class="" value="{{ $pv['index'] }}">{{ $pv['label'] }}</option>
                    @endforeach
                </select>

                <button type="button" wire:loading.attr="disabled" wire:click="saveActivePediod"
                    class="p-3 rounded-2xl my-3.5 flex items-center justify-center cursor-pointer bg-indigo-600/50 hover:bg-indigo-500 hover:text-black">
                    <span class="flex items-center gap-1.5" wire:target='saveActivePediod' wire:loading.remove>
                        <span>Enregistrer</span>
                        <x-lucide-save class="w-5 h-5" />
                    </span>
                    <span wire:target='saveActivePediod' wire:loading.flex class="items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                        <span>En cours...</span>
                    </span>
                </button>
            </div>
        @endif
    </section>
    <section class="w-full justify-center flex items-center my-4 ">
        <div class="flex flex-col gap-y-4 items-center w-full">
            @foreach ($school_year_model->periods as $position => $period)
                @php
                    $start = \Carbon\Carbon::parse($period['start']);
                    $end = \Carbon\Carbon::parse($period['end']);
                    $today = now()->startOfDay();

                    $totalDays = $start->diffInDays($end) + 1;
                    $weeks = intdiv($totalDays, 7);
                    $remDays = $totalDays % 7;

                    $status = $today->lt($start) ? 'a_venir' : ($today->gt($end) ? 'passe' : 'en_cours');

                    $elapsed = max(0, min($totalDays, $start->diffInDays($today) + 1));
                    $progress = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 0;

                    $dayCount = $today->between($start, $end) ? $start->diffInDays($today) + 1 : null;
                @endphp
                <div wire:key='period-of-school-year-{{ $loop->iteration }}'
                    class="w-full rounded-2xl border {{ $status === 'passe' ? 'border-slate-800 bg-slate-900/40' : 'border-slate-700 bg-slate-900/80' }} backdrop-blur-xl p-5 transition-all opacity-50 hover:opacity-100 ">
                    @if (str()->lower($this->school_year_model->periodLabel() . ' ' . $school_year_model->active_period) ==
                            str()->lower($position))
                        <span class="text-xs font-medium h-3 w-3 inline-block rounded-full bg-green-400 animate-pulse">
                        </span>
                        <span class="text-xs font-medium h-3 w-3 inline-block rounded-full bg-green-600 animate-pulse">
                        </span>
                        <span class="text-xs font-medium h-3 w-3 inline-block rounded-full bg-green-700 animate-pulse">
                        </span>
                    @endif

                    <div class="flex items-center justify-between mb-5 border-b border-b-gray-600 py-2">
                        <h3
                            class=" font-mono uppercase flex items-center gap-2 text-base font-semibold py-2 {{ $status === 'passe' ? 'text-amber-700' : 'text-green-600' }}">

                            <span
                                class="@if (str()->lower($this->school_year_model->periodLabel() . ' ' . $school_year_model->active_period) ==
                                        str()->lower($position)) ) border border-green-500 bg-green-600/30 text-green-400 rounded-3xl p-2 @endif">
                                {{ $position }}
                            </span>

                            @if (str()->lower($this->school_year_model->periodLabel() . ' ' . $school_year_model->active_period) ==
                                    str()->lower($position))
                                <span
                                    class="text-xs font-medium p-3 rounded-full bg-slate-800 text-slate-400 border border-slate-700">
                                    {{ $this->school_year_model->periodLabel() }} actif ✅
                                </span>
                            @endif
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

