<div class="min-h-screen bg-slate-950 text-slate-100 overflow-x-hidden">
    <div class="w-full overflow-x-hidden">
        <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl rounded-2xl">
            <div class="px-3 py-2">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    {{-- LEFT --}}
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-xl sm:text-2xl font-bold break-words">
                                Détails Généraux
                            </h1>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <button
                            wire:click="{{ $classe->is_active ? 'closeClasse(' . $classe->id . ')' : 'activateClasse(' . $classe->id . ')' }}"
                            wire:loading.attr="disabled" wire:target="activateClasse, closeClasse"
                            class="relative inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold transition disabled:opacity-40 disabled:cursor-not-allowed justify-center  {{ $classe->is_active ? 'bg-orange-400 hover:bg-orange-700' : 'bg-green-500/40 hover:bg-green-800/30' }}">

                            <span wire:loading.remove wire:target="activateClasse, closeClasse"
                                class="inline-flex items-center justify-center gap-3">
                                <span class="inline-flex items-center justify-center gap-3">
                                    @if (!$classe->is_active)
                                        <x-lucide-check class="w-4 h-4" />
                                        <span>Activer</span>
                                    @else
                                        <x-lucide-x class="w-4 h-4" />
                                        <span>Fermer</span>
                                    @endif
                                </span>
                            </span>

                            <span wire:loading wire:target="closeClasse, activateClasse"
                                class="inline-flex items-center justify-center gap-3">
                                <span class="inline-flex items-center justify-center gap-3">
                                    <span>En cours...</span>
                                    <x-lucide-refresh-cw class="w-4 h-4 animate-spin" />
                                </span>
                            </span>
                        </button>

                        <button
                            wire:click="{{ $classe->is_locked ? 'unlockClasse(' . $classe->id . ')' : 'lockClasse(' . $classe->id . ')' }}"
                            wire:loading.attr="disabled" wire:target="lockClasse, unlockClasse"
                            class="relative inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold transition disabled:opacity-40 disabled:cursor-not-allowed justify-center  {{ $classe->is_locked ? 'bg-emerald-600 hover:bg-emerald-500' : 'bg-amber-500 hover:bg-amber-600' }}">

                            <span wire:loading.remove wire:target="lockClasse, unlockClasse"
                                class="inline-flex items-center justify-center gap-3">
                                <span class="inline-flex items-center justify-center gap-3">
                                    @if ($classe->is_locked)
                                        <x-lucide-lock-open class="w-4 h-4" />
                                        <span>Déverrouiller</span>
                                    @else
                                        <x-lucide-lock class="w-4 h-4" />
                                        <span>Verrouiller</span>
                                    @endif
                                </span>
                            </span>

                            <span wire:loading wire:target="lockClasse, unlockClasse"
                                class="inline-flex items-center justify-center gap-3">
                                <span class="inline-flex items-center justify-center gap-3">
                                    <span>En cours...</span>
                                    <x-lucide-refresh-cw class="w-4 h-4 animate-spin" />
                                </span>
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </section>

        <section class="w-full max-w-full my-3 overflow-hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">

                <div class="min-w-0 overflow-hidden rounded-3xl border border-slate-800 bg-slate-900 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm text-slate-400 truncate">Élèves</p>
                            <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                                {{ __zero($classe->effectif()) }}
                            </h2>
                        </div>
                        <div class="shrink-0 w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center">
                            👨‍🎓
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-emerald-400 truncate">+12% ce trimestre</div>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">
                    <p class="text-sm text-slate-400 truncate">Enseignants</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate">
                        {{ __zero($classe->teachersCount()) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">
                    <p class="text-sm text-slate-400 truncate">Présence</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate text-slate-600">
                        En cours...
                    </h2>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 overflow-hidden">
                    <p class="text-sm text-slate-400 truncate">Moyenne</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold truncate text-slate-700">
                        En cours...
                    </h2>
                </div>

            </div>
        </section>

        <section class="w-full max-w-full my-3 overflow-hidden">
            <div class="grid grid-cols-1 2xl:grid-cols-3 gap-4 sm:gap-6">

                <div class="2xl:col-span-2 min-w-0 space-y-6">

                    <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

                        <div
                            class="p-4 sm:p-5 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-base sm:text-lg truncate">Récents Élèves ajoutés</h3>
                                <p class="mt-1 text-sm text-slate-400 truncate">Liste récente des ajouts
                                    <span class="text-gray-700 italic">Il y a deux semaines</span>
                                </p>
                            </div>
                            <button class="text-indigo-400 text-sm shrink-0">Voir tout</button>
                        </div>

                        <div class="divide-y divide-slate-800">
                            @foreach ($classe->recentStudentsMigratedsIntoClasse(2) as $student)
                                <div class="p-4 sm:p-5">
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-w-0">

                                        <a wire:navigate
                                            href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                            class="flex items-center gap-4 min-w-0 flex-1 hover:text-amber-500 underline-offset-4 hover:underline">
                                            <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4">
                                                <img src="{{ $student->profil_photo_url }}"
                                                    class="w-full h-full object-cover rounded-full">
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-medium truncate">Élève {{ $student->getFullName() }}
                                                </h4>
                                                <p class="text-sm text-slate-400 truncate">Matricule
                                                    #458{{ $student->matricule }}</p>
                                            </div>
                                        </a>

                                        <div class="text-xs font-mono text-slate-400 shrink-0">Ajouté à la classe le
                                            {{ __formatDate($student->currentYearlyAccess($classe->id)?->started_at) }}
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>

                <div class="min-w-0 space-y-6 text-slate-400 font-semibold">

                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5 overflow-hidden">
                        <h3 class="font-semibold text-base flex justify-between items-center">
                            <span>Prof principal (PP)</span>
                            <a class="inline-flex items-center gap-x-3 px-4 py-2 rounded-2xl bg-slate-600 hover:bg-slate-800 text-slate-200"
                                wire:navigate
                                href="{{ route('tenant.classe.respos', ['classe_slug' => $classe->slug]) }}">
                                <x-lucide-pen class="h-4 w-4" />
                                <span>Editer</span>
                            </a>

                        </h3>
                        <div class="mt-5 flex items-center gap-4 min-w-0">
                            <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4">
                                <img src="{{ $classe->principal->user->profil_photo_url }}"
                                    class="w-full h-full object-cover rounded-full">
                            </div>
                            <a wire:navigate
                                href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $classe->principal->uuid]) }}"
                                class="min-w-0 flex-1 hover:text-sky-500 underline-offset-4 hover:underline">
                                <h4 class="font-semibold truncate">
                                    {{ $classe->principal ? $classe->principal?->getFullName() : 'Non encore défini' }}
                                </h4>
                                @if ($classe->principal?->getSubjectsForThisClasse($classe->id))
                                    <p class="text-xs font-mono text-slate-400 truncate flex flex-wrap gap-2">
                                        @foreach ($classe->principal?->getSubjectsForThisClasse($classe->id) as $classeSubject)
                                            <span>{{ $classeSubject->subject?->name }}</span>
                                        @endforeach
                                    </p>
                                @endif
                            </a>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5 overflow-hidden">
                        <h3 class="font-semibold text-base flex justify-between items-center">
                            <span>Responsables de classes</span>
                            <a class="inline-flex items-center gap-x-3 px-4 py-2 rounded-2xl bg-slate-600 hover:bg-slate-800 text-slate-200"
                                wire:navigate
                                href="{{ route('tenant.classe.respos', ['classe_slug' => $classe->slug]) }}">
                                <x-lucide-pen class="h-4 w-4" />
                                <span>Editer</span>
                            </a>

                        </h3>
                        <div class="flex flex-col gap-2.5 my-2.5">
                            @foreach ($classe->responsables() as $key => $respo)
                                <div class="flex-col items-center justify-center border border-gray-600 rounded-2xl"
                                    wire:key='respo-{{ $loop->iteration }}'>
                                    <h5 class=" text-center border-b border-b-slate-600 py-2.5">
                                        Responsable N° {{ $loop->iteration }}
                                    </h5>
                                    <a wire:navigate
                                        href="{{ route('tenant.student.profil', ['student_uuid' => $respo->uuid]) }}"
                                        class="mt-5 flex items-center gap-4 min-w-0 p-2 hover:text-amber-500 underline-offset-4 hover:underline">
                                        <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4">
                                            <img src="{{ $respo->profil_photo_url }}"
                                                class="w-full h-full object-cover rounded-full">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h6 class=" truncate">
                                                {{ $respo ? $respo?->getFullName() : 'Non encore défini' }}
                                            </h6>

                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5 overflow-hidden">
                        <h3 class="font-semibold text-base sm:text-lg">Statistiques</h3>
                        <div class="mt-5 space-y-5">

                            <div>
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <span class="text-sm truncate">Présence</span>
                                    <span class="text-sm shrink-0 text-slate-600">En cours...</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-800 overflow-hidden">
                                    <div class="h-full w-[96%] bg-emerald-500 rounded-full"></div>
                                </div>
                            </div>

                            {{-- BAR --}}
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <span class="text-sm truncate">Réussite</span>
                                    <span class="text-sm shrink-0 text-slate-600">En cours...</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-800 overflow-hidden">
                                    <div class="h-full w-[82%] bg-indigo-500 rounded-full"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </section>

    </div>

</div>

