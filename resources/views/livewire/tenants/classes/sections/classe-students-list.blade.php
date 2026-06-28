<div class="min-h-screen bg-slate-950 text-slate-100 w-full max-w-full overflow-x-hidden">
    <div class="w-full max-w-[100vw] overflow-x-hidden">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
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
                                {{ $students->total() }} élève{{ $students->total() > 1 ? 's' : '' }}
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

        {{-- ===================================================== --}}
        {{-- TOOLBAR --}}
        {{-- ===================================================== --}}
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
                            class="h-12 px-5 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all text-sm">
                            Réinitialiser
                        </button>
                    </div>

                </div>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- TABLE --}}
        {{-- ===================================================== --}}
        <section class="w-full">
            <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto" wire:loading.class="opacity-50"
                    wire:target="search, gender, previousPage, nextPage, gotoPage">
                    <table class="w-full min-w-full">
                        <thead class="bg-slate-950 border-b border-slate-800">
                            <tr>
                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Apprenant</th>
                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">
                                    <span class="flex flex-col">
                                        <span>Date de naissance</span>
                                        <span>Age</span>
                                    </span>
                                </th>
                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Moyenne</th>
                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Présence</th>
                                <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Statut</th>
                                <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($students as $student)
                                <tr class="hover:bg-slate-800/40 transition-all" wire:key="student-{{ $student->id }}">

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
                                                · EducMaster : <span class="font-mono">{{ $student->educMaster }}</span>
                                            @endif
                                            @if ($student->matricule)
                                                · Matricule : <span class="font-mono">{{ $student->matricule }}</span>
                                            @endif
                                        </p>
                                    </td>

                                    {{-- Matricule --}}
                                    <td class="px-6 py-5 text-sm text-slate-300 font-mono">
                                        <div class="flex flex-col gap-y-2">
                                            <p>{{ ucwords(__formatDate($student->birth_date)) }}</p>
                                            <p class="text-slate-500 text-left ">{{ getAge($student->birth_date) }} ans
                                            </p>
                                        </div>
                                    </td>

                                    {{-- Moyenne --}}
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full bg-slate-700 text-slate-400 text-xs">
                                            En cours...
                                        </span>
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

                                    {{-- Actions --}}
                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-end gap-2">

                                            {{-- Profil --}}
                                            <a wire:navigate
                                                href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                                class="py-2 px-3 rounded-xl bg-indigo-500/20 hover:bg-indigo-500 text-indigo-400 hover:text-white transition-all text-xs font-medium">
                                                Profil
                                            </a>

                                            {{-- Bloquer / Débloquer --}}
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
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4" />
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8v8z" />
                                                    </svg>
                                                </span>
                                            </button>

                                            {{-- Retirer de la classe --}}
                                            <button wire:click="removeFromClasse({{ $student->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="removeFromClasse({{ $student->id }})"
                                                class="relative py-2 px-3 rounded-xl bg-orange-500/20 hover:bg-orange-500 text-orange-400 hover:text-white transition-all text-xs font-medium">
                                                <span wire:loading.remove
                                                    wire:target="removeFromClasse({{ $student->id }})">
                                                    Retirer
                                                </span>
                                                <span wire:loading wire:target="removeFromClasse({{ $student->id }})"
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

                                            {{-- Supprimer --}}
                                            <button wire:click="deleteStudent({{ $student->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="deleteStudent({{ $student->id }})"
                                                class="relative py-2 px-3 rounded-xl bg-rose-500/20 hover:bg-rose-500 text-rose-400 hover:text-white transition-all text-xs font-medium">
                                                <span wire:loading.remove
                                                    wire:target="deleteStudent({{ $student->id }})">
                                                    Supprimer
                                                </span>
                                                <span wire:loading wire:target="deleteStudent({{ $student->id }})"
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
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="text-4xl">👨‍🎓</span>
                                            <p class="text-slate-500 text-sm">Aucun apprenant dans cette classe.</p>
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
                </div>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}
        @if ($students->hasPages())
            <section class="py-6">
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-slate-400">
                            Affichage {{ $students->firstItem() }} à {{ $students->lastItem() }} sur
                            {{ $students->total() }} apprenants
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            @if (!$students->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                    Précédent
                                </button>
                            @endif

                            @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                                <button wire:click="gotoPage({{ $page }})"
                                    class="h-10 px-4 rounded-xl text-sm transition-all
                                {{ $page === $students->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                    {{ $page }}
                                </button>
                            @endforeach

                            @if ($students->hasMorePages())
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

    </div>
</div>

