<div class="min-h-screen bg-slate-950 text-slate-100 w-full max-w-full overflow-x-hidden">
    <div wire:loading wire:target='gender,resetFilters,search,previousPage,nextPage,gotoPage'
        class="fixed inset-0 flex items-center justify-center bg-slate-800/20 backdrop-blur-sm"
        style="z-index: 200 !important;">

        <div class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-col gap-3">
            <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <span class="text-xl font-mono ls-1">Chargement en cours...</span>
        </div>
    </div>
    <div class="w-full max-w-[100vw] overflow-x-hidden">
        <section class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-xl">
            <div class="px-2 sm:px-3 lg:px-5 py-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-xl sm:text-2xl font-bold break-words">
                                Liste des apprenants
                            </h1>
                            <span
                                class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs shrink-0">
                                {{ $this->students->total() }} élève{{ $this->students->total() > 1 ? 's' : '' }}
                                @if ($gender || $search)
                                    filtrés
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <button
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all duration-300 text-sm sm:text-base">
                            Exporter liste PDF
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4">
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5">
                <div class="flex flex-col xl:flex-row gap-4">

                    {{-- Search --}}
                    <div class="flex-1 min-w-0">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Rechercher un apprenant..."
                                class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm outline-none focus:border-indigo-500 transition-all" />
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">🔍</div>
                            <div wire:loading wire:target="search" class="absolute right-4 top-1/2 -translate-y-1/2">
                                <svg class="animate-spin w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Filtres --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:flex gap-3">
                        <select wire:model.live="gender"
                            class="h-12 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm focus:border-indigo-500 focus:outline-none transition">
                            <option value="">Tous les genres</option>
                            <option value="Masculin">Masculin</option>
                            <option value="Féminin">Féminin</option>
                        </select>

                        <button wire:click="resetFilters"
                            class="px-5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                            <span wire:loading.remove wire:target='resetFilters'>Réinitialiser les filtres</span>
                            <span wire:loading wire:target='resetFilters'
                                class="inline-flex justify-center gap-3.5 items-center">
                                <span class="inline-flex justify-center gap-3.5 items-center">
                                    <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                    <span>En cours...</span>
                                </span>
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </section>

        <section class="w-full my-4">
            @if (count($this->students))
                <div class="border border-slate-800 bg-slate-900 overflow-hidden">
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
                                        <td class="px-6 py-5 truncate">
                                            {{ __zero($this->students->firstItem() + $loop->iteration - 1) }}
                                        </td>
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
                                                <p class="text-slate-500 text-left ">{{ getAge($student->birth_date) }}
                                                    ans
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
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
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
                                                        class="relative py-2 px-3 rounded-xl text-xs font-medium transition-all
                                                {{ $student->checkIfStudentNotLeavedYet()
                                                    ? 'bg-slate-500/20 hover:bg-slate-500 text-slate-400 hover:text-white'
                                                    : 'bg-sky-500/20 hover:bg-sky-500 text-sky-400 hover:text-white' }}">
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
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
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
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v8z" />
                                                        </svg>
                                                    </span>
                                                </button>

                                            </div>
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

        @if ($this->students->hasPages())
            <section class="py-6">
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-slate-400">
                            Affichage {{ $this->students->firstItem() }} à {{ $this->students->lastItem() }} sur
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
                                <button wire:click="gotoPage({{ $page }})"
                                    class="h-10 px-4 rounded-xl text-sm transition-all
                                {{ $page === $this->students->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                    {{ $page }}
                                </button>
                            @endforeach

                            @if ($this->students->hasMorePages())
                                <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                    Suivant
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section class="w-full my-3 mt-10">

            <div class=" border border-orange-700 p-2 bg-orange-900/10 overflow-hidden">
                <div class="overflow-x-auto" wire:loading.class="opacity-50"
                    wire:target="search, gender, previousPage, nextPage, gotoPage, resetFilters">
                    @if (count($this->leave_students))
                        <div class="p-4 bg-orange-500/15 border border-orange-500 my-2">
                            <h5 class="text-orange-500 text-base font-mono ls-1">Liste des apprenants ayant abandonnés
                            </h5>
                        </div>
                        <table class="w-full z-table-border">
                            <thead class="bg-slate-950 border-b border-slate-800 text-center">
                                <tr>
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
                                @foreach ($this->leave_students as $leave_student)
                                    <tr class="hover:bg-slate-800/40 transition-all"
                                        wire:key="student-{{ $leave_student->id }}">

                                        {{-- Apprenant --}}
                                        <td class="px-6 py-5 truncate">
                                            <a href="{{ route('tenant.student.profil', ['student_uuid' => $leave_student->uuid]) }}"
                                                class="flex items-center gap-4 min-w-0 hover:underline hover:underline-offset-4 hover:text-amber-500">
                                                <div class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4">
                                                    <img src="{{ $leave_student->profil_photo_url }}"
                                                        class="w-full h-full object-cover rounded-full">
                                                </div>
                                                <div class="flex flex-col">
                                                    <div class="font-medium  transition block">
                                                        {{ $leave_student->getFullName() }}
                                                    </div>

                                                </div>

                                            </a>
                                            <p class="text-xs text-slate-500 mt-0.5 flex gap-x-1">
                                                <span>{{ $leave_student->gender ?? '—' }}</span>
                                                @if ($leave_student->educMaster)
                                                    · EducMaster : <span
                                                        class="font-mono">{{ $leave_student->educMaster }}</span>
                                                @endif
                                                @if ($leave_student->matricule)
                                                    · Matricule : <span
                                                        class="font-mono">{{ $leave_student->matricule }}</span>
                                                @endif
                                            </p>
                                        </td>

                                        {{-- Matricule --}}
                                        <td class="px-6 py-5 text-sm text-slate-300 font-mono">
                                            <div class="flex flex-col gap-y-2">
                                                <p>{{ ucwords(__formatDate($leave_student->birth_date)) }}</p>
                                                <p class="text-slate-500 text-left ">
                                                    {{ getAge($leave_student->birth_date) }}
                                                    ans
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
                                            @if ($leave_student->blocked)
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 text-xs border border-rose-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Bloqué
                                                </span>
                                            @endif
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-orange-500/10 text-orange-400 text-xs border border-orange-500/20">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                                                Abandonné
                                            </span>
                                        </td>

                                        <td class="px-6 py-5">
                                            <div class="flex items-center justify-end gap-2">

                                                <button wire:click="toggleBlockStudent({{ $leave_student->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="toggleBlockStudent({{ $leave_student->id }})"
                                                    class="relative py-2 px-3 rounded-xl text-xs font-medium transition-all
                                                {{ $leave_student->blocked
                                                    ? 'bg-emerald-500/20 hover:bg-emerald-500 text-emerald-400 hover:text-white'
                                                    : 'bg-amber-500/20 hover:bg-amber-500 text-amber-400 hover:text-white' }}">
                                                    <span wire:loading.remove
                                                        wire:target="toggleBlockStudent({{ $leave_student->id }})">
                                                        {{ $leave_student->blocked ? 'Débloquer' : 'Bloquer' }}
                                                    </span>
                                                    <span wire:loading
                                                        wire:target="toggleBlockStudent({{ $leave_student->id }})"
                                                        class="inline-flex items-center gap-1">
                                                        <svg class="animate-spin w-3 h-3" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v8z" />
                                                        </svg>
                                                    </span>
                                                </button>

                                                @if (!$leave_student->checkIfStudentNotLeavedYet())
                                                    <button
                                                        title="Réinséré {{ $leave_student->getFullName() }} dans la liste des apprenants actifs"
                                                        wire:click="reinsertIntoClasseAsActive({{ $leave_student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="reinsertIntoClasseAsActive({{ $leave_student->id }})"
                                                        class="relative py-2 px-3 rounded-xl text-xs font-medium transition-all  hover:text-white bg-sky-500/20 hover:bg-sky-500 text-sky-400 hover:text-white' }}">
                                                        <span wire:loading.remove
                                                            wire:target="reinsertIntoClasseAsActive({{ $leave_student->id }})">
                                                            Réinséré
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="reinsertIntoClasseAsActive({{ $leave_student->id }})"
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

                                                <button wire:click="removeFromClasse({{ $leave_student->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="removeFromClasse({{ $leave_student->id }})"
                                                    class="relative py-2 px-3 rounded-xl bg-orange-500/20 hover:bg-orange-500 text-orange-400 hover:text-white transition-all text-xs font-medium">
                                                    <span wire:loading.remove
                                                        wire:target="removeFromClasse({{ $leave_student->id }})">
                                                        Retirer
                                                    </span>
                                                    <span wire:loading
                                                        wire:target="removeFromClasse({{ $leave_student->id }})"
                                                        class="inline-flex items-center gap-1">
                                                        <svg class="animate-spin w-3 h-3" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v8z" />
                                                        </svg>
                                                    </span>
                                                </button>

                                                <button wire:click="deleteStudent({{ $leave_student->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="deleteStudent({{ $leave_student->id }})"
                                                    class="relative py-2 px-3 rounded-xl bg-rose-500/20 hover:bg-rose-500 text-rose-400 hover:text-white transition-all text-xs font-medium">
                                                    <span wire:loading.remove
                                                        wire:target="deleteStudent({{ $leave_student->id }})">
                                                        Supprimer
                                                    </span>
                                                    <span wire:loading
                                                        wire:target="deleteStudent({{ $leave_student->id }})"
                                                        class="inline-flex items-center gap-1">
                                                        <svg class="animate-spin w-3 h-3" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v8z" />
                                                        </svg>
                                                    </span>
                                                </button>

                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="w-full max-auto flex justify-center">
                            <div class="text-center p-5">
                                <div class="flex flex-col items-center gap-3">
                                    <p class="text-orange-500 animate-pulse font-semibold text-lg ls-1 font-mono">Aucun
                                        apprenant n'a abandonné dans
                                        cette classe.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if ($this->students->hasPages())
                    <section class="py-6">
                        <div class="flex justify-center bg-slate-900 p-4">
                            <div class="flex flex-col items-center gap-4">
                                <div class="text-sm text-slate-400">
                                    Affichage {{ $this->students->firstItem() }} à {{ $this->students->lastItem() }}
                                    sur
                                    {{ $this->students->total() }} enseignants
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
        </section>

    </div>
</div>

