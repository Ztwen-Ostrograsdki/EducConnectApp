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
        <section class="my-2 flex justify-end bg-slate-950 py-2 px-1.5">
            <span class="rounded-lg py-2 px-3 bg-yellow-800/50 border border-yellow-700 text-yellow-400 text-lg">
                Matière : {{ $subject->name }}
            </span>
        </section>
        <section class="my-3" x-data="{ open: false }">
            <div class="border border-slate-800 bg-slate-950 rounded-lg overflow-hidden">

                {{-- Toggle --}}
                <span type="button" @click="open = !open"
                    class="w-full flex items-center justify-between gap-2 p-4 sm:p-5 text-slate-300 hover:text-sky-500 transition-colors cursor-pointer">
                    <span class="font-semibold">PP et Responsables de classe</span>

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="w-5 h-5 shrink-0 transition-transform duration-300" :class="open && 'rotate-180'">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </span>

                {{-- Contenu --}}
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="border-t border-slate-800 p-4 sm:p-5 space-y-6">

                    {{-- Titulaire de la classe --}}
                    <div>
                        <h3 class="text-xs uppercase tracking-wide text-slate-500 font-semibold mb-3">
                            Titulaire de la classe
                        </h3>

                        @if ($classe->principal)
                            <div class="flex items-center gap-4 min-w-0 group">
                                <div
                                    class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4 border-slate-700 group-hover:border-sky-600 transition-colors">
                                    <img src="{{ $classe->principal->user->profil_photo_url }}"
                                        class="w-full h-full object-cover rounded-full">
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h4
                                        class="font-semibold text-slate-200 truncate group-hover:text-sky-500 group-hover:underline underline-offset-4">
                                        {{ $classe->principal->getFullName() }}
                                    </h4>

                                    @if ($subjects = $classe->principal->getSubjectsForThisClasse($classe->id))
                                        <div class="mt-1.5 flex flex-wrap gap-1.5">
                                            @foreach ($subjects as $classeSubject)
                                                <span
                                                    class="text-2xs rounded-2xl border border-orange-600 bg-orange-600/40 text-orange-500 px-2 py-0.5 group-hover:border-sky-500 group-hover:bg-sky-600/40 group-hover:text-sky-500 transition-colors">
                                                    {{ $classeSubject->subject?->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">Non encore défini</p>
                        @endif
                    </div>

                    {{-- Responsables de la classe --}}
                    @if ($classe->respo_1_id || $classe->respo_2_id)
                        <div>
                            <h3 class="text-xs uppercase tracking-wide text-slate-500 font-semibold mb-3">
                                Responsables de la classe
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($classe->responsables() as $respo)
                                    <div class="border border-slate-700 rounded-2xl overflow-hidden"
                                        wire:key="respo-{{ $respo?->id ?? $loop->iteration }}">
                                        <h5
                                            class="text-center text-xs uppercase tracking-wide text-slate-500 border-b border-slate-700 py-2">
                                            Responsable N° {{ $loop->iteration }}
                                        </h5>

                                        <div
                                            class="flex items-center gap-4 p-3 group hover:text-amber-500 transition-colors">
                                            <div
                                                class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4 border-slate-700">
                                                <img src="{{ $respo?->profil_photo_url }}"
                                                    class="w-full h-full object-cover rounded-full">
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <h6 class="truncate group-hover:underline underline-offset-4">
                                                    {{ $respo ? $respo->getFullName() : 'Non encore défini' }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </section>

    @endif
</div>

