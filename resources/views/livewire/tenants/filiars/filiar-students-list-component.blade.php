<section class="mb-6 relative" x-data="{ open: true }">
    <div wire:loading
        wire:target='students_classe_id,students_gender,students_promotion_id,previousPage,nextPage,resetFilters,resetStudentsFilters, gotoPage'
        class="absolute inset-0 flex items-center justify-center rounded-[32px] bg-slate-800/20 backdrop-blur-sm"
        style="z-index: 200 !important;">

        <div class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-row">
            <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <span class="text-2xl font-mono ls-1">Chargement en cours...</span>
        </div>
    </div>
    <div class="rounded-tl-2xl rounded-tr-2xl bg-purple-900/15 border border-purple-800 overflow-hidden">
        <button type="button" @click="open = !open"
            class="w-full text-left p-5 sm:p-6 border-b border-slate-800 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">
            <div>
                <h2 class="text-xl font-semibold flex items-center gap-2">
                    Apprenants de la filière
                    <svg :class="open ? 'rotate-180' : 'rotate-0'" class="w-5 h-5 transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </h2>
                <p class="mt-1 text-sm text-slate-400">Gestion des apprenants de la filière selon les
                    promotions, classes</p>
            </div>

            <div class="flex w-full justify-end gap-3 items-center" @click.stop>
                <span wire:click='resetStudentsFilters'
                    class="flex items-center justify-center h-12 min-w-[220px] rounded-2xl bg-purple-900/10 px-4 hover:bg-purple-900 border border-white text-sm uppercase font-mono">
                    <span wire:loading.remove wire:target='resetStudentsFilters'
                        class="inline-flex gap-x-2 items-center">
                        <span class="inline-flex gap-x-2 items-center">
                            <x-lucide-refresh-ccw class="w-4 h-4" />
                            <span class="truncate">Réinitialiser les filtres</span>
                        </span>
                    </span>
                    <span wire:loading wire:target='resetStudentsFilters' class="inline-flex items-center gap-x-2">
                        <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                    </span>

                </span>

                <select wire:model.live='students_gender'
                    class="h-12 min-w-[220px] rounded-2xl bg-slate-950 text-purple-800 border border-purple-900 px-4 text-sm uppercase font-mono">
                    <option value="">Tout genre</option>
                    @foreach (config('app.genders') as $sgk => $sg)
                        <option value="{{ $sgk }}">
                            {{ $sg }}
                        </option>
                    @endforeach
                </select>

                <select wire:model.live='students_classe_id'
                    class="h-12 min-w-[220px] rounded-2xl bg-slate-950 text-purple-800 border border-purple-900 px-4 text-sm uppercase font-mono">
                    <option>Toutes les classes</option>
                    @foreach ($this->classes as $sclasse)
                        <option value="{{ $sclasse->id }}">
                            Classe de {{ $sclasse->code ? $sclasse->code : $sclasse->name }}
                        </option>
                    @endforeach
                </select>

                <select wire:model.live='students_promotion_id'
                    class="h-12 min-w-[220px] rounded-2xl bg-slate-950 text-purple-800 border border-purple-900 px-4 text-sm uppercase font-mono">
                    <option>Toutes les promotions</option>
                    @foreach ($this->promotions as $spromo)
                        <option value="{{ $spromo->id }}">
                            Promotion {{ $spromo->code ? $spromo->code : $spromo->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </button>

        <section x-show="open" x-collapse x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="w-full p-2">
            <div class="rounded-3xl bg-purple-900/15 overflow-hidden">
                <div class="overflow-x-auto p-2">
                    <div class="overflow-x-auto">
                        @if (count($this->students))
                            <table class="w-full min-w-full z-table-border">
                                <thead class="bg-slate-950 border-b border-slate-800 text-center">
                                    <tr>
                                        <th class=" px-6 py-4 text-sm font-medium text-slate-400">Apprenant
                                        </th>
                                        <th class=" px-6 py-4 text-sm font-medium text-slate-400">
                                            <span class="flex flex-col">
                                                <span>Date de naissance</span>
                                                <span>Age</span>
                                            </span>
                                        </th>
                                        <th class=" px-6 py-4 text-sm font-medium text-slate-400">Présence</th>
                                        <th class=" px-6 py-4 text-sm font-medium text-slate-400">Statut</th>
                                        <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800">
                                    @forelse ($this->students as $student)
                                        <tr class="hover:bg-slate-800/40 transition-all"
                                            wire:key="student-{{ $student->id }}">

                                            {{-- Apprenant --}}
                                            <td class="px-6 py-5 truncate">
                                                <a href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                                    class="flex items-center gap-4 min-w-0 hover:underline hover:underline-offset-4 hover:text-amber-500">
                                                    <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4">
                                                        <img src="{{ $student->profil_photo_url }}"
                                                            class="w-full h-full object-cover rounded-full">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <div class="font-medium  transition block">
                                                            {{ $student->getFullName() }}
                                                        </div>

                                                    </div>

                                                </a>
                                                <p class="text-xs text-slate-500 mt-0.5 flex gap-x-1">
                                                    <span>{{ $student->gender ?? '—' }}</span>
                                                    @if ($student->educMaster)
                                                        · EducMaster : <span
                                                            class="font-mono">{{ $student->educMaster }}</span>
                                                    @endif
                                                    @if ($student->matricule)
                                                        · Matricule : <span
                                                            class="font-mono">{{ $student->matricule }}</span>
                                                    @endif
                                                </p>
                                            </td>

                                            {{-- Matricule --}}
                                            <td class="px-6 py-5 text-sm text-slate-300 font-mono">
                                                <div class="flex flex-col gap-y-2">
                                                    <p>{{ ucwords(__formatDate($student->birth_date)) }}</p>
                                                    <p class="text-slate-500 text-left ">
                                                        {{ getAge($student->birth_date) }} ans
                                                    </p>
                                                </div>
                                            </td>

                                            {{-- Présence --}}
                                            <td class="px-6 py-5 text-sm text-slate-400">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full bg-slate-700 text-slate-400 text-xs">
                                                    En cours...
                                                </span>
                                            </td>

                                            {{-- Statut --}}
                                            <td class="px-6 py-5">
                                                @if ($student->blocked)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 text-xs border border-rose-500/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                        Bloqué
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
                                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                        Inactif
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-5">
                                                <div class="flex items-center justify-end gap-2">

                                                    <button wire:click="toggleBlockStudent({{ $student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="toggleBlockStudent({{ $student->id }})"
                                                        class="relative py-2 px-3 rounded-xl text-xs font-medium transition-all
                                                {{ $student->blocked
                                                    ? 'bg-emerald-500/20 hover:bg-emerald-500 text-emerald-400 hover:text-white'
                                                    : 'bg-amber-500/20 hover:bg-amber-500 text-amber-400 hover:text-white' }}">
                                                        <span wire:loading.remove
                                                            wire:target="toggleBlockStudent({{ $student->id }})">
                                                            {{ $student->blocked ? 'Débloquer' : 'Bloquer' }}
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="toggleBlockStudent({{ $student->id }})"
                                                            class="inline-flex items-center gap-1">
                                                            <svg class="animate-spin w-3 h-3" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12"
                                                                    cy="12" r="10" stroke="currentColor"
                                                                    stroke-width="4" />
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8v8z" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    @if ($student->checkIfStudentNotLeavedYet())
                                                        <button
                                                            title="Marquer {{ $student->getFullName() }} comme ayant abandonné"
                                                            wire:click="markStudentAsLeaved({{ $student->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="markStudentAsLeaved({{ $student->id }})"
                                                            class="relative py-2 px-3 rounded-xl text-xs font-medium transition-all {{ $student->checkIfStudentNotLeavedYet() ? 'bg-slate-500/20 hover:bg-slate-500 text-slate-400 hover:text-white' : 'bg-sky-500/20 hover:bg-sky-500 text-sky-400 hover:text-white' }}">
                                                            <span wire:loading.remove
                                                                wire:target="markStudentAsLeaved({{ $student->id }})">
                                                                {{ $student->checkIfStudentNotLeavedYet() ? 'Abandon' : 'Réinséré' }}
                                                            </span>
                                                            <span wire:loading
                                                                wire:target="markStudentAsLeaved({{ $student->id }})"
                                                                class="inline-flex items-center gap-1">
                                                                <svg class="animate-spin w-3 h-3" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4" />
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8v8z" />
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    @endif

                                                    <button wire:click="removeFromClasse({{ $student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="removeFromClasse({{ $student->id }})"
                                                        class="relative py-2 px-3 rounded-xl bg-orange-500/20 hover:bg-orange-500 text-orange-400 hover:text-white transition-all text-xs font-medium">
                                                        <span wire:loading.remove
                                                            wire:target="removeFromClasse({{ $student->id }})">
                                                            Retirer
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="removeFromClasse({{ $student->id }})"
                                                            class="inline-flex items-center gap-1">
                                                            <svg class="animate-spin w-3 h-3" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12"
                                                                    cy="12" r="10" stroke="currentColor"
                                                                    stroke-width="4" />
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8v8z" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    <button wire:click="deleteStudent({{ $student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="deleteStudent({{ $student->id }})"
                                                        class="relative py-2 px-3 rounded-xl bg-rose-500/20 hover:bg-rose-500 text-rose-400 hover:text-white transition-all text-xs font-medium">
                                                        <span wire:loading.remove
                                                            wire:target="deleteStudent({{ $student->id }})">
                                                            Supprimer
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="deleteStudent({{ $student->id }})"
                                                            class="inline-flex items-center gap-1">
                                                            <svg class="animate-spin w-3 h-3" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12"
                                                                    cy="12" r="10" stroke="currentColor"
                                                                    stroke-width="4" />
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8v8z" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-16 text-center">
                                                <div class="flex flex-col items-center gap-3">
                                                    <span class="text-4xl">👨‍🎓</span>
                                                    <p class="text-slate-500 text-sm">Aucun apprenant dans
                                                        cette
                                                        classe.</p>
                                                    @if ($search || $gender)
                                                        <button wire:click="resetFilters"
                                                            class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                                            Réinitialiser les filtres
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @else
                            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-16 text-center">
                                <div class="text-4xl mb-4">
                                    <x-lucide-user class="w-4 h-4" />
                                </div>
                                <p class="text-slate-400 text-sm">Aucun apprenant trouvé</p>
                                <button wire:click="resetStudentsFilters"
                                    class="mt-4 px-5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                    <span wire:loading.remove wire:target='resetStudentsFilters'>Réinitialiser
                                        les
                                        filtres</span>
                                    <span wire:loading wire:target='resetStudentsFilters'
                                        class="inline-flex justify-center gap-3.5 items-center">
                                        <span class="inline-flex justify-center gap-3.5 items-center">
                                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </span>
                                </button>
                            </div>
                        @endif

                        @if ($this->students->hasPages())
                            <section class="py-6">
                                <div class="border border-slate-800 bg-slate-900 p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div class="text-sm text-slate-400">
                                            Affichage {{ $this->students->firstItem() }} à
                                            {{ $this->students->lastItem() }} sur
                                            {{ $this->students->total() }} apprenants
                                        </div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if (!$this->students->onFirstPage())
                                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                                    wire:target="previousPage"
                                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                                    Précédent
                                                </button>
                                            @endif

                                            @foreach ($this->students->getUrlRange(1, $this->students->lastPage()) as $page => $url)
                                                <button @disabled($page === $this->students->currentPage())
                                                    wire:click="gotoPage({{ $page }})"
                                                    class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->students->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                                    {{ $page }}
                                                </button>
                                            @endforeach

                                            @if ($this->students->hasMorePages())
                                                <button wire:click="nextPage" wire:loading.attr="disabled"
                                                    wire:target="nextPage"
                                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                                    Suivant
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    </div>
</section>

