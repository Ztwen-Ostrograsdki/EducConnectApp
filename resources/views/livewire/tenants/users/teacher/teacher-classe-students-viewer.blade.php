<div class="min-h-screen bg-[#070a12] text-slate-100">

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        {{-- ========== HEADER ========== --}}
        <section class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-lg sm:text-xl font-bold text-white tracking-tight">
                        Liste des apprenants
                        <span class="text-amber-400 font-mono uppercase">{{ $this->classe->code }}</span>
                    </h1>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                                 bg-indigo-500/15 text-indigo-400 border border-indigo-500/25">
                        {{ $this->effectifs['apprenants'] }} élève{{ $this->effectifs['apprenants'] > 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        </section>

        {{-- ========== LISTE ========== --}}
        <section>
            @if (count($this->students))
                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" style="min-width: 560px;">
                            <thead>
                                <tr class="border-b border-white/[0.05]">
                                    <th
                                        class="px-4 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 w-14">
                                        N°
                                    </th>
                                    <th
                                        class="px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Apprenant
                                    </th>
                                    <th
                                        class="px-4 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Naissance
                                    </th>
                                    <th
                                        class="px-4 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 w-24">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-white/[0.04]">
                                @foreach ($this->students as $student)
                                    <tr class="group hover:bg-white/[0.02] transition-colors"
                                        wire:key="student-{{ $student->id }}">

                                        <td class="px-4 py-3 text-center font-mono text-xs text-slate-600">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div
                                                    class="w-10 h-10 rounded-full overflow-hidden shrink-0
                                                            ring-2 ring-white/[0.06]
                                                            group-hover:ring-sky-500/40 transition-all">
                                                    <img src="{{ $student->profil_photo_url }}"
                                                        alt="{{ $student->getFullName() }}"
                                                        class="w-full h-full object-cover" loading="lazy" />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="font-medium text-slate-200 truncate
                                                                   group-hover:text-sky-400 transition-colors">
                                                            {{ $student->getFullName() }}
                                                        </span>
                                                        @if ($student->gender)
                                                            <span
                                                                class="shrink-0 text-[10px] font-mono uppercase px-1.5 py-0.5 rounded
                                                                         bg-white/[0.04] text-slate-500 border border-white/[0.06]">
                                                                {{ str()->initials($student->gender) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if ($student->educMaster)
                                                        <p class="text-[11px] text-slate-600 truncate mt-0.5">
                                                            {{ $student->educMaster }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            <p class="text-xs text-slate-300 font-mono">
                                                {{ ucwords(__formatDate($student->birth_date)) }}
                                            </p>
                                            <p class="text-[11px] text-slate-600 mt-0.5">
                                                {{ getAge($student->birth_date) }} ans
                                            </p>
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            {{-- Actions futures --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] py-16 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-500/10 mb-4">
                        <x-lucide-users class="w-7 h-7 text-indigo-400" />
                    </div>
                    <p class="text-slate-400 text-sm">Aucun apprenant dans cette classe</p>
                    @if ($search || $gender)
                        <button wire:click="resetFilters"
                            class="mt-4 px-4 py-2 rounded-xl text-sm
                                       bg-white/[0.04] border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.08] hover:text-white transition-all">
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            @endif
        </section>

    </div>
</div>

