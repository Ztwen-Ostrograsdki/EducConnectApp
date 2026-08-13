<div class="w-full overflow-x-hidden">

    {{-- ===================================================== --}}
    {{-- GLOBAL CONTAINER --}}
    {{-- ===================================================== --}}
    <div class="mx-auto
                w-full
                max-w-[1850px]
                mb-28">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div
                class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        overflow-hidden">

                <div class="p-4 sm:p-6 xl:p-8">

                    <div class="flex flex-col xl:flex-row gap-6 xl:gap-8">

                        {{-- LEFT --}}
                        <div class="flex flex-col sm:flex-row gap-5 flex-1 min-w-0">

                            {{-- AVATAR --}}
                            <div class="flex justify-center sm:block shrink-0 relative">

                                <img src="{{ $this->user->profil_photo_url }}" alt=""
                                    class="w-40 h-40
                               rounded-full
                               object-cover
                               border-4
                               border-slate-700">

                                <a title="Editer ma photo de profil" href="{{ route('tenant.update.profil.photo') }}"
                                    class="absolute bottom-2 right-2
                               w-12 h-12 rounded-full
                               bg-indigo-800/75 hover:bg-indigo-500 hover:text-black
                               flex items-center justify-center">
                                    <x-lucide-camera class="w-5 h-5" />
                                </a>

                            </div>

                            {{-- INFOS --}}
                            <div class="flex-1 min-w-0">

                                <div class="flex flex-col gap-4">

                                    {{-- TOP --}}
                                    <div class="min-w-0">

                                        <div class="flex flex-wrap items-center gap-2">

                                            <h1
                                                class="text-2xl sm:text-3xl
                                                       font-bold
                                                       break-words">

                                                {{ $this->teacher->getFullName(true) }}

                                            </h1>

                                            <span
                                                class="px-3 py-1 rounded-full
                                                         bg-indigo-500/10
                                                         text-indigo-400
                                                         text-xs shrink-0">

                                                Enseignant

                                            </span>

                                        </div>

                                        <p class="mt-2 text-slate-400 text-sm">

                                            ID : {{ $this->teacher->identifiant }}

                                        </p>

                                    </div>

                                    {{-- GRID INFOS --}}
                                    <div
                                        class="grid
                                                grid-cols-2
                                                lg:grid-cols-3
                                                gap-3">

                                        <div class="rounded-2xl bg-slate-950 p-3">

                                            <p class="text-xs text-slate-500">
                                                Téléphone
                                            </p>

                                            <h4 class="mt-1 font-medium truncate">
                                                {{ $this->user->contacts }}
                                            </h4>

                                        </div>

                                        <div class="rounded-2xl bg-slate-950 p-3">

                                            <p class="text-xs text-slate-500">
                                                Email
                                            </p>

                                            <h4 class="mt-1 font-medium">
                                                {{ $this->user->email }}
                                            </h4>

                                        </div>

                                        <div class="rounded-2xl bg-slate-950 p-3">

                                            <p class="text-xs text-slate-500">
                                                Statut
                                            </p>

                                            <h4 class="mt-1 font-medium text-emerald-400 text-sm">
                                                <div
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full @if (!$this->teacher->blocked) text-emerald-400 @else  text-red-400 @endif">

                                                    <x-lucide-circle-check class="w-4 h-4" />

                                                    @if (!$this->teacher->blocked)
                                                        Compte actif
                                                    @else
                                                        Compte bloqué
                                                    @endif

                                                </div>
                                            </h4>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>
            <div class="rounded-2xl bg-slate-950 p-3 text-xs my-2">

                <p class="text-lg text-slate-500 border-b border-b-slate-600">
                    Matière(s) | Spécialité(s)
                </p>

                <h4 class="mt-1 font-medium flex flex-wrap gap-2 ">
                    @forelse ($this->teacher->getYearlySubjects() as $yearly_subject)
                        <span
                            class="rounded-2xl p-2 font-mono bg-indigo-900/40 text-slate-400 cursor-pointer hover:scale-105 transition-transform">{{ $yearly_subject->subject->name }}</span>
                    @empty
                        <span class="text-orange-600/50 italic ls-1 font-mono py-4">Matières
                            et spacialités non
                            spécifiées</span>
                    @endforelse
                </h4>

            </div>

        </section>

        <section class="my-1.5 flex items-center justify-end gap-2">
            <a wire:navigate href="{{ route('tenant.subjects.coefs.manage') }}"
                class="p-2.5 rounded-2xl bg-green-500/20 text-green-400  hover:bg-green-500/60 hover:text-black transition-all text-sm flex items-center text-center">
                <span class="flex items-center justify-center gap-x-2">
                    <span class="flex items-center justify-center gap-x-2">
                        <x-lucide-plus class="w-4 h-4" />
                        <span> Ajouter un coéf</span>
                    </span>
                </span>
            </a>
        </section>

        <section>

            <div
                class="grid
                        grid-cols-1
                        
                        gap-6">

                <div class="space-y-6 min-w-0">

                    <div
                        class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                overflow-hidden">

                        {{-- HEADER --}}
                        <div class="border-b border-slate-800
                                    p-4 sm:p-6">

                            <div
                                class="flex flex-col lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                        gap-4">

                                <div>

                                    <h2 class="text-lg sm:text-xl font-semibold">
                                        Mes classes
                                    </h2>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Classes qui me sont assignées en <span
                                            class="text-orange-600">{{ $this->activeYear->slug }}</span>
                                    </p>

                                </div>

                            </div>

                        </div>

                        {{-- TABLE --}}
                        <div class="overflow-x-auto p-2">
                            @php
                                $classes = $this->teacher?->getTeacherClassesWithSubjectsForThisSchoolYear();
                            @endphp

                            @if (count($classes))
                                <table class="w-full  z-table-border text-slate-400 text-sm mb-12"
                                    style="width: 1300px; min-width: 1300px;">
                                    <colgroup>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                    </colgroup>

                                    <thead class="bg-slate-950 border-b border-slate-800">

                                        <tr>

                                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                N°
                                            </th>
                                            <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                Classe
                                            </th>

                                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                Matière
                                            </th>

                                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                Notes faites
                                            </th>

                                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                Heures/Sem
                                            </th>
                                            <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                Aller à
                                            </th>
                                        </tr>

                                    </thead>

                                    <tbody class="divide-y divide-slate-800">

                                        @foreach ($classes as $kls)
                                            <tr class="hover:bg-slate-800/40 transition-all">
                                                <td
                                                    class="px-2 sm:px-6 py-1.5 truncate text-center font-mono text-slate-400 text-xs sm:text-sm">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-5">

                                                    <div class="flex items-center gap-3">

                                                        <a wire:navigate
                                                            href="{{ route('tenant.teacher.classe.students', ['classe_slug' => $kls->classe->slug, 'subject_slug' => $kls->subject->slug]) }}"
                                                            class="hover:underline underline-offset-4 hover:text-lime-500">

                                                            <h3 class="font-medium">
                                                                {{ $kls->classe?->name }}
                                                            </h3>

                                                            <p class="text-xs text-amber-700">
                                                                {{ $kls->classe?->speciality() }}
                                                            </p>

                                                        </a>

                                                    </div>

                                                </td>

                                                <td class="px-4 py-5 text-center">
                                                    {{ $kls->subject?->code ?? $kls->subject?->name }}
                                                </td>
                                                <td class="px-4 py-5 text-center">

                                                    <span
                                                        class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                        86

                                                    </span>

                                                </td>

                                                <td class="px-4 py-5 text-center">
                                                    4h
                                                </td>
                                                <td class="px-4 py-5 text-center">
                                                    <div class="flex flex-wrap gap-2 items-center justify-center">
                                                        <a wire:navigate
                                                            class="bg-sky-900 hover:bg-sky-400 hover:text-black border border-sky-600 rounded-2xl p-2"
                                                            href="{{ route('tenant.teacher.classe.students', ['classe_slug' => $kls->classe->slug, 'subject_slug' => $kls->subject->slug]) }}">
                                                            <span class="flex items-center gap-x-2">
                                                                <x-lucide-eye class="w-4 h-4" />
                                                                <span>Voir la classe</span>
                                                            </span>
                                                        </a>

                                                        <a wire:navigate
                                                            class="bg-green-900 hover:bg-green-400 hover:text-black border border-green-600 rounded-2xl p-2"
                                                            href="{{ route('tenant.teacher.classe.marks', ['classe_slug' => $kls->classe->slug, 'subject_slug' => $kls->subject->slug]) }}">
                                                            <span class="flex items-center gap-x-2">
                                                                <x-lucide-eye class="w-4 h-4" />
                                                                <span>Notes de classe</span>
                                                            </span>
                                                        </a>
                                                        @if (
                                                            $this->activeYear &&
                                                                $this->activeYear->active_period &&
                                                                $kls->classe->is_active &&
                                                                !$kls->classe->is_locked &&
                                                                auth('tenant')->user()->teacher->canAccessIntoClasse($kls->classe->id))
                                                            <a wire:navigate
                                                                class="bg-blue-900 hover:bg-blue-600 hover:text-black border border-blue-600 rounded-2xl p-2"
                                                                href="{{ route('tenant.teacher.classe.marks.manager', ['classe_slug' => $kls->classe->slug, 'subject_slug' => $kls->subject->slug]) }}">
                                                                <span class="flex items-center gap-x-2">
                                                                    <x-lucide-pen class="w-4 h-4" />
                                                                    <span>Insertion de notes</span>
                                                                </span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            @else
                                <div>
                                    <div class="p-6 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <x-lucide-school class="w-10 h-10 text-orange-600" />
                                            <p class="text-slate-500 text-lg animate-pulse">Aucune classe assignée</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>

            </div>

        </section>

    </div>

</div>

