<div class="min-h-screen bg-slate-950 text-slate-100 overflow-x-hidden">
    <div class="w-full overflow-x-hidden">
        <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl rounded-2xl">
            <div class="px-3 py-2">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    {{-- LEFT --}}
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-lg md:text-base font-bold break-words py-4">
                                Détails Généraux de la {{ $classe->code }}
                            </h1>
                        </div>
                    </div>
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
                                <h3 class="font-semibold text-base sm:text-lg truncate">Élèves récemment ajoutés</h3>
                                <p class="mt-1 text-sm text-slate-400 truncate">Liste récente des ajouts
                                    <span class="text-gray-500 italic">Il y a deux semaines environ</span>
                                </p>
                            </div>
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
                        @if ($classe->principal)
                            <a wire:navigate
                                href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $classe->principal?->uuid]) }}"
                                class="mt-5 flex items-center gap-4 min-w-0 group">
                                <div
                                    class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4 group-hover:border-sky-600">
                                    <img src="{{ $classe->principal?->user->profil_photo_url }}"
                                        class="w-full h-full object-cover rounded-full ">
                                </div>
                                <div class="min-w-0 flex-1 hover:text-sky-500">
                                    <h4 class="font-semibold truncate group-hover:underline underline-offset-4">
                                        {{ $classe->principal ? $classe->principal?->getFullName() : 'Non encore défini' }}
                                    </h4>
                                    @if ($classe->principal?->getSubjectsForThisClasse($classe->id))
                                        <p class="text-xs font-mono text-slate-400 truncate flex flex-wrap gap-2 ">
                                            @foreach ($classe->principal?->getSubjectsForThisClasse($classe->id) as $classeSubject)
                                                <span
                                                    class="rounded-xl group-hover:border-sky-500 group-hover:bg-sky-600/40 p-1 px-3 group-hover:text-sky-500 border border-green-600 bg-green-600/40 text-green-300 uppercase">{{ $classeSubject->subject?->code }}</span>
                                            @endforeach
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endif
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
                        @if ($classe->respo_1_id && $classe->respo_2_id)
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
                        @endif
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

