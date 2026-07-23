<div>
    @if ($classe && $subject)
        <section class="flex flex-col sm:flex-row gap-4 sm:gap-5 min-w-0 flex-1 bg-slate-950">
            <div class="shrink-0 self-start font-mono hidden md:flex p-3">
                <div
                    class="w-32 h-32 sm:w-20 sm:h-20 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                    <span class="text-indigo-400">
                        {{ $classe->code }}
                    </span>
                </div>
            </div>
            <div class="min-w-0 flex-1 p-2">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold leading-tight break-words">
                        {{ $classe->name }}
                    </h1>
                    @if ($classe->is_active)
                        <span
                            class="shrink-0 px-3 py-1 rounded-full text-xs bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                            Active
                        </span>
                    @else
                        <span
                            class="shrink-0 px-3 py-1 rounded-full text-xs bg-red-500/10 border border-red-500/20 text-red-400">
                            Fermée
                        </span>
                    @endif

                    @if (!$classe->is_locked)
                        <span
                            class="shrink-0 px-3 py-1 rounded-full text-xs bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                            Accessible
                        </span>
                    @else
                        <span
                            class="shrink-0 px-3 py-1 rounded-full text-xs bg-red-500/10 border border-red-500/20 text-red-400">
                            Verrouillée
                        </span>
                    @endif

                    <span class="float-right">
                        📅 {{ $classe->schoolYear->slug }}
                    </span>
                </div>
                <p class="mt-3 text-sm sm:text-base text-slate-400 break-words">
                    <span>
                        🔗
                        @if ($classe->filiar_id)
                            Filière | Spécialité :
                        @elseif($classe->serial_id)
                            Série :
                        @else
                        @endif
                        {{ $classe->specialityModel()?->name }}
                    </span>

                    <span>
                        📍 {{ $classe->localization ?? 'Non précisée' }}
                    </span>
                </p>

                {{-- META --}}
                <div class="mt-4 flex flex-col sm:flex-wrap gap-2 sm:gap-5 text-sm text-slate-400">
                    <span class="flex font-mono text-xs items-center text-green-300 gap-x-2">
                        <span class="rounded-lg p-1.5 bg-green-800/50 border border-green-700">

                            Apprenant(s) : {{ $this->effectifs['apprenants'] }}</span>
                        <span class="rounded-lg p-1.5 bg-indigo-800/50 border border-indigo-700">
                            F: {{ $this->effectifs['apprenants_par_sexe']['F'] }}
                        </span>
                        <span class="rounded-lg p-1.5 bg-indigo-800/50 border border-indigo-700">
                            G: {{ $this->effectifs['apprenants_par_sexe']['M'] }}
                        </span>
                        <span class="rounded-lg p-1.5 bg-orange-800/50 border border-orange-700 text-orange-400">
                            Abd: {{ $this->effectifs['abandons'] }}</span>
                        <span class="rounded-lg p-1.5 bg-sky-800/50 border border-sky-700 text-sky-400">
                            Prof(s) : {{ $this->effectifs['profs'] }} </span>
                    </span>
                </div>
            </div>

        </section>
        <section>
            <div class="flex flex-col gap-3">
                <div class="flex">
                    @if ($this->principal)
                        <div
                            class="inline-flex items-center gap-4 min-w-0  rounded-xl p-1 border border-indigo-500 bg-indigo-500/40">
                            <div class="flex items-center gap-3">
                                <h4 class="font-semibold text-slate-400">
                                    <span class="flex items-center gap-3">
                                        <span>PP : </span>
                                        <span>{{ $this->principal->getFullName() }}</span>
                                    </span>
                                </h4>

                                <div class="border-l border-l-slate-700 px-2">
                                    <div class="text-slate-400">
                                        <span>Contacts : </span>
                                        <span> {{ $this->principal->user->contacts }} </span>
                                    </div>
                                    <div class="flex gap-x-2 items-center text-slate-400">
                                        @if ($subjects = $this->principalSubjects)
                                            <span>Matières : </span>
                                            <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                                @foreach ($subjects as $classeSubject)
                                                    <span
                                                        class="text-2xs rounded-2xl border border-sky-600 bg-sky-600/40 text-sky-500 px-2 py-0.5">
                                                        {{ $classeSubject->subject?->code }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p
                            class="text-sm text-slate-300 italic inline-flex px-4 py-0.5 mt-1.5  items-center gap-3   rounded-xl p-1 border border-gray-500 bg-gray-500/40">
                            <span>PP : </span>
                            <span>Non encore défini</span>
                        </p>
                    @endif
                </div>

                @if ($classe->respo_1_id || $classe->respo_2_id)
                    <div>
                        <div class="inline-flex flex-col gap-3">
                            @foreach ($classe->responsables() as $rk => $respo)
                                <div class=" overflow-hidden" wire:key="respo-{{ $respo?->id ?? $loop->iteration }}">
                                    <div
                                        class="flex gap-3 items-center  rounded-xl p-1 border border-indigo-500 bg-indigo-500/40">
                                        <h5
                                            class="text-center text-xs uppercase tracking-wide text-slate-400 font-mono  py-2">
                                            {{ $rk }} :
                                        </h5>
                                        <h6 class="text-indigo-300">
                                            {{ $respo ? $respo->getFullName() : 'Non encore défini' }}
                                        </h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p
                        class="text-sm text-slate-300 italic inline-flex px-4 py-0.5 mt-1.5  items-center gap-3   rounded-xl p-1 border border-gray-500 bg-gray-500/40">
                        <span>Respos : </span>
                        <span>Non encore défini</span>
                    </p>
                @endif

            </div>
        </section>
        <section class="my-2 flex justify-end bg-slate-950 py-2 px-1.5">
            <span class="rounded-lg py-2 px-3 bg-yellow-800/50 border border-yellow-700 text-yellow-400 text-lg">
                Matière : {{ $subject->name }}
            </span>
        </section>
    @endif
</div>

