<div class="rounded-3xl
            bg-slate-900
            border border-slate-800
            overflow-hidden p-2">
    <section class="mb-6">

        <div
            class="flex flex-col
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                        gap-5">

            {{-- LEFT --}}
            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-2xl sm:text-3xl font-bold">

                        Dashboard des matières

                    </h1>

                    <span
                        class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400
                                     text-xs">

                        145 Matières

                    </span>

                </div>

                <p class="mt-2 text-slate-400 text-sm sm:text-base">

                    Gestion centralisée des matières de l'établissement

                </p>

            </div>

            {{-- ACTIONS --}}
        </div>

    </section>

    <section class="mb-6">

        <div
            class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        p-4 sm:p-5">

            <div class="flex flex-col gap-4">

                {{-- SEARCH --}}
                <div class="relative">

                    <input wire:model.live.debounce.300ms='search' type="text"
                        placeholder="Rechercher une matière..."
                        class="w-full h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-500/40">

                    <div
                        class="absolute left-4 top-1/2
                                    -translate-y-1/2
                                    text-slate-500">

                        🔍

                    </div>

                </div>

                {{-- FILTER GRID --}}
                <div class="grid w-full my-2 gap-3 grid-cols-12">
                    <select wire:model.live='is_active'
                        class="py-3 col-span-3 uppercase font-mono rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm">
                        <option value="">
                            <span>Toutes les matières</span>
                            <span>({{ __zero($this->activesSubjects + $this->unActivesSubjects) }})</span>
                        </option>
                        <option class="text-green-400" value="actives">
                            <span>
                                <span>Actives</span>
                                <span c>({{ __zero($this->activesSubjects) }})</span>
                            </span>
                        </option>
                        <option value="desactives">
                            <span>Désactivées</span>
                            <span>({{ __zero($this->unActivesSubjects) }})</span>
                        </option>
                        <option class="text-orange-600" value="corbeille">
                            <span>La corbeille</span>
                            <span>({{ __zero($this->trashedsSubjects) }})</span>
                        </option>

                    </select>

                    <select wire:model.live='type'
                        class="py-3 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm col-span-6">
                        <option value="">Tous types de matières</option>
                        @foreach (config('app.subject_types') as $subk => $sub)
                            <option class="uppercase" value="{{ $sub }}">
                                {{ $sub }}
                            </option>
                        @endforeach
                    </select>

                    <button wire:click='clearFilters'
                        class="py-3 rounded-2xl bg-slate-600 hover:bg-slate-800 transition-all text-sm col-span-3">
                        <span wire:loading.remove wire:target='clearFilters' class="inline-flex gap-x-2 items-center ">
                            <span class="inline-flex gap-x-2 items-center">
                                <x-lucide-refresh-ccw class="w-4 h-4" />
                                <span>Réinitialiser</span>
                            </span>
                        </span>
                        <span wire:loading wire:target='clearFilters' class="inline-flex items-center gap-x-2">
                            <span class="inline-flex items-center gap-x-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                <span>Rechargement ...</span>
                            </span>
                        </span>

                    </button>

                </div>
            </div>

        </div>

    </section>

    <section class="my-4 py-3 border-y border-y-slate-700 w-full">
        <div class="flex items-center gap-3 justify-end w-full">

            <button
                class="py-3 rounded-2xl
                                       bg-indigo-500
                                       hover:bg-indigo-600
                                       transition-all text-sm px-3">

                Imprimer en PDF

            </button>

            <button
                class="py-3 rounded-2xl
                                       bg-emerald-500
                                       hover:bg-emerald-600
                                       transition-all text-sm px-3">

                Imprimer en Excel

            </button>
            <a wire:navigate href="{{ route('tenant.subject.create') }}"
                class="py-3 px-5 rounded-2xl bg-purple-700 hover:bg-purple-900 flex justify-center items-center">
                + Créer une matière 📚
            </a>
            @if ($this->unActivesSubjects)
                <button title="Réactiver les {{ $this->unActivesSubjects }} matières désactivées "
                    wire:click="activateUnactivesSubjects" wire:loading.attr="disabled"
                    wire:target="activateUnactivesSubjects"
                    class="relative py-3 px-4 text-white  text-xs inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50 bg-orange-600/60 hover:bg-orange-600 hover:text-black">
                    <span wire:loading.remove wire:target="activateFiliar, activateUnactivesSubjects"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            <x-lucide-lock class="w-4 h-4" />
                            <span>Réactiver les matières
                                ({{ __zero($this->unActivesSubjects) }})
                            </span>
                        </span>
                    </span>

                    <span wire:loading wire:target="activateUnactivesSubjects" class="inline-flex items-center gap-1">
                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </span>
                </button>
            @endif

            @if ($this->trashedsSubjects)
                <button title="Restorer les {{ $this->trashedsSubjects }} matières de la corbeille "
                    wire:click="restoreTrashedsSubjects" wire:loading.attr="disabled"
                    wire:target="restoreTrashedsSubjects"
                    class="relative py-3 px-4 text-white  text-xs inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50 bg-rose-600/60 hover:bg-rose-600 hover:text-black">
                    <span wire:loading.remove wire:target="restoreTrashedsSubjects"
                        class="inline-flex items-center justify-center gap-3">
                        <span class="inline-flex items-center justify-center gap-3">
                            <x-lucide-trash class="w-4 h-4" />
                            <span>Restorer les matières
                                ({{ __zero($this->trashedsSubjects) }})
                            </span>
                        </span>
                    </span>

                    <span wire:loading wire:target="restoreTrashedsSubjects" class="inline-flex items-center gap-1">
                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </span>
                </button>
            @endif
        </div>
    </section>

    <div class="overflow-x-auto">
        <div wire:loading
            wire:target='clearFilters,is_active,type,activateSubject,activateUnactivesSubjects,restoreTrashedsSubjects,desactivateSubject,deleteSubject,forceDeleteSubject,search,previousPage,nextPage,gotoPage'
            class="fixed inset-0 flex items-center justify-center bg-slate-800/20 backdrop-blur-sm"
            style="z-index: 200 !important;">
            <div class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-row">
                <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
                <span class="text-2xl font-mono ls-1">Chargement en cours...</span>
            </div>
        </div>

        @if (count($this->subjects))

            <table class=" w-full">

                <thead class="bg-slate-950 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                            N°
                        </th>
                        <th class="px-6 py-4 text-left text-sm text-slate-400">
                            Matière
                        </th>

                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                            Chef Atelier
                        </th>

                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                            Nbre enseignants
                        </th>

                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                            Nbre de classes
                        </th>

                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                            Meilleur classe
                        </th>

                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                            Réussite
                        </th>

                        <th class="px-4 py-4 text-center text-sm text-slate-400">
                            Volume Horaire
                        </th>

                        <th class="px-6 py-4 text-center text-sm text-slate-400">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-800">

                    @foreach ($this->subjects as $subject)
                        <tr class="hover:bg-slate-800/40 truncate">

                            <td class="px-6 py-5 font-medium">

                                {{ $loop->iteration }}

                            </td>
                            <td class="px-6 py-5 font-medium">

                                <a class="hover:underline underline-offset-2 text-slate-300 font-semibold font-mono"
                                    wire:navigate
                                    href="{{ route('tenant.subject.profil', ['subject_slug' => $subject->slug]) }}">
                                    {{ $subject->name }}
                                    <p class="text-slate-500 font-mono text-sm">
                                        {{ $subject->code }}
                                    </p>
                                </a>

                            </td>

                            <td class="px-4 py-5 text-center">

                                -

                            </td>

                            <td class="px-4 py-5 text-center">

                                {{ __zero($subject->getSubjectTeachersOfSchoolYearCount()) }}

                            </td>

                            <td class="px-4 py-5 text-center">

                                28

                            </td>

                            <td class="px-4 py-5 text-center">

                                13.48

                            </td>

                            <td class="px-4 py-5 text-center">

                                <span class="text-emerald-400">

                                    84%

                                </span>

                            </td>

                            <td class="px-4 py-5 text-center">

                                18h

                            </td>

                            <td class="px-6 py-5">

                                <div class="flex gap-2 truncate">
                                    <a wire:navigate
                                        href="{{ route('tenant.subject.profil', ['subject_slug' => $subject->slug]) }}"
                                        class="p-2.5 rounded-2xl bg-indigo-500/20 text-indigo-400  hover:bg-indigo-500/60 hover:text-black transition-all text-sm inline-block text-center">
                                        <span class="flex items-center justify-center gap-x-2">
                                            <span class="flex items-center justify-center gap-x-2">
                                                <x-lucide-pen class="w-4 h-4" />
                                                <span>Voir détails</span>
                                            </span>
                                        </span>
                                    </a>
                                    <a wire:navigate
                                        href="{{ route('tenant.subject.profil', ['subject_slug' => $subject->slug]) }}"
                                        class="p-2.5 rounded-2xl bg-blue-500/20 text-blue-400  hover:bg-blue-500/60 hover:text-black transition-all text-sm inline-block text-center">
                                        <span class="flex items-center justify-center gap-x-2">
                                            <span class="flex items-center justify-center gap-x-2">
                                                <x-lucide-eye class="w-4 h-4" />
                                                <span>Voir détails</span>
                                            </span>
                                        </span>
                                    </a>

                                    <button title="{{ $subject->is_active ? 'Fermer ' : 'Activer ' }} cette matière "
                                        wire:click="{{ $subject->is_active ? 'desactivateSubject(' . $subject->id . ')' : 'activateSubject(' . $subject->id . ')' }}"
                                        wire:loading.attr="disabled" wire:target="activateSubject, desactivateSubject"
                                        class="relative py-3 px-4 rounded-xl text-white {{ !$subject->is_active ? 'bg-lime-600/60 hover:bg-lime-500 hover:text-black' : 'bg-orange-500/60 hover:bg-orange-600/90' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50 hover:text-black">
                                        <span wire:loading.remove wire:target="activateSubject, desactivateSubject"
                                            class="inline-flex items-center justify-center gap-3">
                                            <span class="inline-flex items-center justify-center gap-3">
                                                @if ($subject->is_active)
                                                    <x-lucide-lock class="w-4 h-4" />
                                                    <span>Fermer</span>
                                                @else
                                                    <x-lucide-unlock class="w-4 h-4" />
                                                    <span>Activer</span>
                                                @endif
                                            </span>
                                        </span>

                                        <span wire:loading wire:target="activateSubject, desactivateSubject"
                                            class="inline-flex items-center gap-1">
                                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v8z" />
                                            </svg>
                                        </span>
                                    </button>

                                    <button
                                        title="{{ $subject->deleted_at ? 'Restaurer cette matière de la corbeille ' : 'Mettre cette matière dans la corbeille ' }} "
                                        wire:click="{{ $subject->deleted_at ? 'restoreSubject(' . $subject->id . ')' : 'deleteSubject(' . $subject->id . ')' }}"
                                        wire:loading.attr="disabled" wire:target="deleteSubject, restoreSubject"
                                        class="relative py-3 px-4 rounded-xl text-white {{ $subject->deleted_at ? 'bg-green-600/50 hover:bg-green-800/80' : 'bg-red-500/60 hover:bg-red-600/80' }} text-xs font-medium inline-flex items-center justify-center gap-1.5  rounded-xl transition-all whitespace-nowrap disabled:opacity-50 hover:text-black">
                                        <span wire:loading.remove wire:target="deleteSubject, restoreSubject"
                                            class="inline-flex items-center justify-center gap-3">
                                            <span class="inline-flex items-center justify-center gap-3">
                                                @if ($subject->deleted_at)
                                                    <x-lucide-refresh-ccw class="w-4 h-4" />
                                                    <span>Restaurer</span>
                                                @else
                                                    <x-lucide-trash class="w-4 h-4" />
                                                    <span>Corbeille</span>
                                                @endif
                                            </span>
                                        </span>

                                        <span wire:loading wire:target="restoreSubject, deleteSubject"
                                            class="inline-flex items-center gap-1">
                                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
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
            <div class="flex w-full itecn justify-center">
                <div class="p-6 flex justify-center text-center">
                    <div class="flex flex-col items-center gap-3">
                        <span class="text-4xl">🎯</span>
                        <p class="text-slate-500 text-sm">Aucune matière trouvée </p>
                        @if ($search || $type)
                            <button wire:click="clearFilters"
                                class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                Réinitialiser les filtres
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($this->subjects->hasPages())
            <section class="py-6">
                <div class="flex justify-center bg-slate-900 p-4">
                    <div class="flex flex-col items-center gap-4">
                        <div class="text-sm text-slate-400">
                            Affichage {{ $this->subjects->firstItem() }} à {{ $this->subjects->lastItem() }} sur
                            {{ $this->subjects->total() }} matières
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            @if (!$this->subjects->onFirstPage())
                                <button wire:click="previousPage" wire:loading.attr="disabled"
                                    wire:target="previousPage"
                                    class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                    Précédent
                                </button>
                            @endif

                            @foreach ($this->subjects->getUrlRange(1, $this->subjects->lastPage()) as $page => $url)
                                <button @disabled($page === $this->subjects->currentPage()) wire:click="gotoPage({{ $page }})"
                                    class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->subjects->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                    {{ $page }}
                                </button>
                            @endforeach

                            @if ($this->subjects->hasMorePages())
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

