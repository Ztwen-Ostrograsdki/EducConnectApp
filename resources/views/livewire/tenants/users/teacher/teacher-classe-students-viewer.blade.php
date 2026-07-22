<div
    class="min-h-screen bg-slate-950 text-slate-100 w-full
                max-w-full
                overflow-x-hidden">

    <div class="w-full max-w-[100vw] overflow-x-hidden p-3">
        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        <section class="border-b border-slate-800 bg-slate-900 backdrop-blur-xl p-2">

            <div class="px-2 sm:px-3 lg:px-5 py-5">

                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 border-b border-b-slate-800 py-2">

                    {{-- LEFT --}}
                    <div class="min-w-0 ">

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-lg sm:text-xl font-bold break-words text-slate-300">
                                Liste des apprenants de la <span class="text-orange-400 font-mono uppercase">
                                    {{ $this->classe->code }}
                                </span>
                            </h1>

                            <span
                                class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20
                                         text-indigo-400 text-xs shrink-0 font-mono uppercase">

                                {{ $this->effectifs['apprenants'] }} élèves
                            </span>
                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                        <button
                            class="w-full sm:w-auto
                                       px-5 py-3 rounded-2xl
                                       bg-slate-800
                                       border border-slate-700
                                       hover:bg-slate-700
                                       transition-all duration-300
                                       text-sm sm:text-base">

                            Exporter liste PDF

                        </button>

                    </div>

                </div>

            </div>

        </section>

        <section class="w-full my-2">

            @if (count($this->students))
                <div class="border border-slate-800 bg-slate-900 overflow-hidden p-2">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full z-table-border">
                            <thead class="bg-slate-950 border-b border-slate-800 text-center">
                                <tr>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">N°</th>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">Apprenant</th>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">
                                        <span class="flex flex-col">
                                            <span>Date de naissance</span>
                                            <span>Age</span>
                                        </span>
                                    </th>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">Présence</th>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">Statut</th>
                                    <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @forelse ($this->students as $student)
                                    <tr class="hover:bg-slate-800/40 transition-all"
                                        wire:key="student-{{ $student->id }}">

                                        {{-- Apprenant --}}
                                        <td class="px-6 py-1.5 truncate text-center font-mono text-slate-400">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-1.5 truncate">
                                            <div class="flex items-center gap-2 min-w-0 group">
                                                <div
                                                    class="w-11 h-11 bg-slate-800 shrink-0 rounded-full border-4 group-hover:border-sky-400">
                                                    <img src="{{ $student->profil_photo_url }}"
                                                        class="w-full h-full object-cover rounded-full">
                                                </div>
                                                <div class="flex flex-col w-full">
                                                    <div class="font-medium w-full transition flex justify-between">
                                                        <span
                                                            class="group-hover:underline underline-offset-4 group-hover:text-sky-500 font-mono text-slate-300">{{ $student->getFullName() }}</span>
                                                        @if ($student->gender)
                                                            <span
                                                                class="uppercase float-right text-slate-500 font-mono py-1 px-2 bg-slate-950 shadow-sm shadow-sky-700 group-hover:shadow-orange-500">{{ str()->initials($student->gender) }}</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-xs text-slate-500 flex-col mt-0.5 flex gap-x-1">
                                                        @if ($student->educMaster)
                                                            <span class="font-mono">{{ $student->educMaster }}</span>
                                                        @endif

                                                    </p>

                                                </div>

                                            </div>

                                        </td>

                                        {{-- Matricule --}}
                                        <td class="px-6 py-1.5 text-sm text-slate-300 font-mono">
                                            <div class="flex flex-col gap-y-2">
                                                <p>{{ ucwords(__formatDate($student->birth_date)) }}</p>
                                                <p class="text-slate-500 text-left ">
                                                    {{ getAge($student->birth_date) }}
                                                    ans
                                                </p>
                                            </div>
                                        </td>

                                        {{-- Présence --}}
                                        <td class="px-6 py-1.5 text-sm text-slate-400">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full bg-slate-700 text-slate-400 text-xs">
                                                En cours...
                                            </span>
                                        </td>

                                        {{-- Statut --}}
                                        <td class="px-6 py-1.5">
                                            @if ($student->blocked)
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 text-xs border border-rose-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Bloqué
                                                </span>
                                            @elseif ($student->is_active)
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs border border-emerald-500/20">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                    Actif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-700 text-slate-400 text-xs">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactif
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-1.5">

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="flex justify-center items-center">
                    <div class="p-5 text-center flex justify-center">
                        <div class="flex flex-col items-center gap-3">
                            <span class="text-4xl">👨‍🎓</span>
                            <p class="text-slate-500 text-lg">Aucun apprenant dans cette classe.</p>
                            @if ($search || $gender)
                                <button wire:click="resetFilters"
                                    class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                    Réinitialiser les filtres
                                </button>
                            @endif
                        </div>
                    </div>
                    </tr>
            @endif

        </section>

    </div>

</div>

