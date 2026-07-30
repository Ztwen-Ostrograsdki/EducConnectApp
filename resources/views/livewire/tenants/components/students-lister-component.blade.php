<div>
    <section>
        <div class="flex flex-col gap-4">
            <div class="grid grid-cols-7 gap-x-3">
                <div class="relative col-span-5">

                    <input wire:model.live.debounce.400ms='search' type="text" placeholder="Rechercher un apprenant..."
                        class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm  focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                        🔍
                    </div>
                </div>
                <button wire:click='resetFilters'
                    class="py-2 rounded-2xl bg-slate-600 hover:bg-slate-800 transition-all text-sm col-span-2">
                    <span wire:loading.remove wire:target='resetFilters' class="inline-flex gap-x-2 items-center ">
                        <span class="inline-flex gap-x-2 items-center">
                            <x-lucide-refresh-ccw class="w-4 h-4" />
                            <span>Réinitialiser</span>
                        </span>
                    </span>
                    <span wire:loading wire:target='resetFilters' class="inline-flex items-center gap-x-2">
                        <span class="inline-flex items-center gap-x-2">
                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            <span>Rechargement ...</span>
                        </span>
                    </span>

                </button>
            </div>

            <div class="flex items-center flex-wrap gap-3">

                @if (!$classe)
                    <select wire:model.live='classe_id'
                        class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                        <option value="">Toutes les classes</option>
                        @foreach ($this->classes as $cl)
                            <option value="{{ $cl->id }}">
                                Classe de {{ $cl->code ? $cl->code : $cl->name }}
                            </option>
                        @endforeach
                    </select>
                @endif

                @if (!$classe)
                    @if (!$filiar)
                        <select wire:model.live='filiar_id'
                            class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                            <option value="">Toutes les filières</option>
                            @foreach ($this->filiars as $f)
                                <option value="{{ $f->id }}">
                                    Filière {{ $f->code ? $f->code : $f->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    @if (!$serial)
                        <select wire:model.live='serial_id'
                            class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                            <option value="">Toutes les séries</option>
                            @foreach ($this->serials as $s)
                                <option value="{{ $s->id }}">
                                    Série {{ $s->code ? $s->code : $s->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    @if (!$promotion)
                        <select wire:model.live='promotionInGroups'
                            class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                            <option value="">Toutes les promotions</option>
                            @foreach ($this->promotions as $promo)
                                <option value="{{ $promo }}">
                                    Promotion
                                    {{ $promo }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                @endif

                <select wire:model.live='gender'
                    class="h-11 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                    <option value="">Sexe</option>
                    @foreach ($this->genders as $gk => $gdr)
                        <option value="{{ $gk }}">{{ $gdr }}</option>
                    @endforeach
                </select>

                <select wire:model.live='status'
                    class="h-12  uppercase font-mono rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm">
                    <option value="">
                        <span>Tout statut </span>
                    </option>
                    <option class="text-green-400" value="actifs">
                        <span>
                            <span>Actifs</span>
                        </span>
                    </option>
                    <option value="desactives">
                        <span>Désactivés</span>
                    </option>
                    <option value="ayant de classe">
                        <span>Ayant de classe</span>
                    </option>
                    <option value="ayant abandonés">
                        <span>Déclarés abandons</span>
                    </option>
                    <option class="text-orange-500" value="sans classe">
                        <span>Sans classes</span>
                    </option>
                    <option class="text-red-600" value="de la corbeille">
                        <span>La corbeille</span>
                    </option>
                </select>
            </div>

        </div>
        <section class="w-full my-4 relative">
            <section class="border-b rounded-3xl border-slate-800 bg-slate-900/80 backdrop-blur-xl my-3">
                <div class="px-2 sm:px-3 lg:px-5 py-5">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="text-lg sm:text-xl text-slate-300 font-bold break-words">
                                    Liste
                                </h1>
                                <span
                                    class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs shrink-0 font-mono uppercase">
                                    {{ $this->students->total() }}
                                    Apprenant{{ $this->students->total() > 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                            <a wire:navigate href="{{ route('tenant.students.print.configuration') }}"
                                class="py-2 px-2 bg-indigo-700/40 hover:bg-indigo-800 text-white hover:text-black flex items-center gap-2 active:scale-95 rounded-2xl">
                                <x-lucide-printer class="w-4 h-4" />
                                <span>Génaration personnalisée de la liste en PDF</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <div wire:loading
                wire:target='gender,classe_id,filia_id,serial_id,promotionInGroups,resetFilters,search,previousPage,nextPage,gotoPage'
                class="absolute inset-0 flex items-center justify-center bg-slate-800/5 backdrop-blur-xs"
                style="z-index: 200 !important;">

                <div class="items-center text-slate-400 relative top-1/2 mx-auto flex justify-center flex-col gap-3">
                    <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>
                    <span class="text-xl font-mono ls-1">Chargement en cours...</span>
                </div>
            </div>
            @if (count($this->students))
                <div class="border border-slate-800 bg-slate-900 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full z-table-border">
                            <thead class="bg-slate-950 border-b border-slate-800 text-center">
                                <tr>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">N°</th>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">Apprenant</th>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">Classe</th>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">
                                        <span class="flex flex-col">
                                            <span>Date de naissance</span>
                                            <span>Age</span>
                                        </span>
                                    </th>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">Présence</th>
                                    <th class=" px-6 py-4 text-sm font-medium text-slate-400">Statut</th>
                                    <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @forelse ($this->students as $student)
                                    <tr class="hover:bg-slate-800/40 transition-all"
                                        wire:key="student-{{ $student->id }}">

                                        {{-- Apprenant --}}
                                        <td class="px-6 py-5 truncate font-mono text-slate-400">
                                            {{ __zero($this->students->firstItem() + $loop->iteration - 1) }}
                                        </td>
                                        <td class="px-6 py-5 truncate">
                                            <a href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                                class="flex items-center gap-4 min-w-0 group">
                                                <div
                                                    class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4 group-hover:border-sky-400">
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
                                                        @if ($student->matricule)
                                                            <span class="font-mono">{{ $student->matricule }}</span>
                                                        @endif
                                                    </p>

                                                </div>

                                            </a>

                                        </td>

                                        {{-- Matricule --}}
                                        <td class="px-6 py-5 text-sm text-slate-300 font-mono">
                                            <div class="flex flex-col gap-y-2">
                                                @php
                                                    $cl = $student->currentClasse();
                                                @endphp
                                                @if ($cl)
                                                    <a wire:navigate
                                                        href="{{ route('tenant.classe.profil', ['classe_slug' => $cl->classe->slug]) }}"
                                                        class="flex justify-center items-center hover:text-orange-500 hover:bg-gray-900 uppercase font-mono text-sky-500 truncate p-2 rounded-2xl">
                                                        <span>{{ $cl->classe->code ? $cl->classe->code : $cl->classe->name }}</span>
                                                    </a>
                                                @else
                                                    <span
                                                        class="flex-col flex gap-1 justify-center text-xs text-slate-600">
                                                        <span>Pas encore de</span>
                                                        <span>classe en {{ $this->activeYear?->slug }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-5 text-sm text-slate-300 font-mono">
                                            <div class="flex flex-col gap-y-2">
                                                <p>{{ ucwords(__formatDate($student->birth_date)) }}</p>
                                                <p class="text-slate-500 text-left ">
                                                    {{ getAge($student->birth_date) }}
                                                    ans
                                                </p>
                                            </div>
                                        </td>

                                        {{-- Présence --}}
                                        <td class="px-6 py-5 text-sm text-slate-400">
                                            <div class="flex justify-center">
                                                <span
                                                    class="inline-flex justify-center text-center items-center px-3 py-1 rounded-full bg-slate-700 text-slate-400 text-xs">
                                                    En cours...
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Statut --}}
                                        <td class="px-6 py-5">
                                            <div class="flex justify-center">
                                                @if (!$student->checkIfStudentNotLeavedYet())
                                                    <span
                                                        class="inline-flex justify-center items-center gap-1.5 px-2.5 py-1 rounded-full bg-orange-500/10 text-orange-400 text-xs border border-orange-500/20">
                                                        <span
                                                            class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                                                        Abandonné
                                                    </span>
                                                @else
                                                    @if ($student->blocked)
                                                        <span
                                                            class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 text-xs border border-rose-500/20">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                            Bloqué
                                                        </span>
                                                    @elseif ($student->is_active)
                                                        <span
                                                            class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs border border-emerald-500/20">
                                                            <span
                                                                class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                            Actif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-700 text-slate-400 text-xs">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                            Inactif
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-5">
                                            <div class="flex items-center justify-center gap-2">

                                                @if ($student->checkIfStudentNotLeavedYet())
                                                    <button
                                                        title="Marquer {{ $student->getFullName() }} comme ayant abandonné"
                                                        wire:click="markStudentAsLeaved({{ $student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="markStudentAsLeaved({{ $student->id }})"
                                                        class="relative py-2 px-3 rounded-xl text-xs font-medium transition-all text-red-300 bg-red-500/50 hover:bg-red-700 hover:text-black">
                                                        <span wire:loading.remove
                                                            wire:target="markStudentAsLeaved({{ $student->id }})">
                                                            Abandonné
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
                                                @else
                                                    <button
                                                        title="Réinséré {{ $student->getFullName() }} dans la liste des apprenants actifs"
                                                        wire:click="reinsertIntoClasseAsActive({{ $student->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="reinsertIntoClasseAsActive({{ $student->id }})"
                                                        class="relative py-2 px-3 rounded-xl text-xs font-medium transition-all  hover:text-black bg-sky-500/20 hover:bg-sky-500 text-sky-400">
                                                        <span wire:loading.remove
                                                            wire:target="reinsertIntoClasseAsActive({{ $student->id }})">
                                                            Réinséré
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="reinsertIntoClasseAsActive({{ $student->id }})"
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
                            <button wire:click="resetFilters"
                                class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                Recharger
                            </button>
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
    </section>
</div>

