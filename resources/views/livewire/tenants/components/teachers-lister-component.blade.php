<div class="p-2 bg-indigo-900/10">
    <section>
        <section>
            <section class="mb-6">

                <div class="border border-slate-800  bg-slate-900 p-4 sm:p-5">
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-7 gap-x-3">
                            <div class="relative col-span-5">

                                <input wire:model.live.debounce.400ms='search' type="text"
                                    placeholder="Rechercher un enseignant..."
                                    class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm  focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                                    🔍
                                </div>
                            </div>
                            <button wire:click='clearFilters'
                                class="py-2 rounded-2xl bg-slate-600 hover:bg-slate-800 transition-all text-sm col-span-2">
                                <span wire:loading.remove wire:target='clearFilters'
                                    class="inline-flex gap-x-2 items-center ">
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

                        <div class="flex items-center flex-wrap gap-3">

                            @if (!$subject)
                                <select wire:model.live='subject_id'
                                    class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                                    <option value="">Toutes les matières</option>
                                    @foreach ($this->subjects as $sub)
                                        <option value="{{ $sub->id }}">
                                            {{ $sub->code ? $sub->code : $sub->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select wire:model.live='subject_type'
                                    class="py-3 px-4 rounded-2xl bg-slate-950 border border-slate-800 text-sm col-span-6 uppercase">
                                    <option value="">Tous types de matières</option>
                                    @foreach ($this->subject_types as $subk => $sub)
                                        <option class="uppercase" value="{{ $sub }}">
                                            {{ $sub }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            @if (!$promotion && !$promotionModel)
                                <select wire:model.live='promotionInGroups'
                                    class="h-12  rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                                    <option value="">Toutes les promotions groupées</option>
                                    @foreach ($this->promotions as $kp => $n)
                                        <option value="{{ $n }}">
                                            Promotion
                                            {{ $n }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

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

                                @if (!$serial && !$filiar && !$promotionModel)
                                    <select wire:model.live='filiar_id'
                                        class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                                        <option value="">Toutes les filières</option>
                                        @foreach ($this->filiars as $f)
                                            <option value="{{ $f->id }}">
                                                Filière {{ $f->code ? $f->code : $f->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                            @endif

                            <select wire:model.live='department'
                                class="h-11 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                                <option value="">Department</option>
                                @foreach ($this->departments as $dp => $dpv)
                                    <option value="{{ $dpv }}">{{ $dpv }}</option>
                                @endforeach
                            </select>

                            <select wire:model.live='city'
                                class="h-11 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                                <option value="">Ville</option>
                                @foreach ($this->cities as $ct => $ctv)
                                    <option value="{{ $ctv }}">{{ $ctv }}</option>
                                @endforeach
                            </select>

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
                                <option class="text-green-400" value="actives">
                                    <span>
                                        <span>Actifs</span>
                                    </span>
                                </option>
                                <option value="desactives">
                                    <span>Bloqués</span>
                                </option>
                                <option class="text-orange-600" value="corbeille">
                                    <span>La corbeille</span>
                                </option>
                            </select>
                        </div>

                    </div>

                </div>

            </section>

            <section class="my-3 flex justify-end gap-3 rounded-2xl border border-slate-700 p-3">
                <a wire:navigate href="{{ route('tenant.teachers.docs') }}"
                    class="py-2.5 px-5 active:scale-95 rounded-2xl bg-sky-500 hover:bg-sky-600 transition-all text-sm">
                    <span class="inline-flex gap-x-3 items-center">
                        <x-lucide-file class="w-4 h-4" />
                        <span>Documents PDF/Excel Dispo.</span>
                    </span>
                </a>
                <a href="{{ route('tenant.teachers.print.list') }}"
                    class="py-2.5 px-5 rounded-2xl bg-gray-500/40 hover:bg-gray-600 hover:text-black transition-all text-sm">
                    <span class="inline-flex items-center gap-2">
                        <x-lucide-eye class="w-4 h-4" />
                        <span>Aperçue du document</span>
                    </span>
                </a>
                <a href="{{ route('tenant.teachers.print.configuration') }}"
                    class="py-2 px-2 bg-indigo-700/40 hover:bg-indigo-800 text-white hover:text-black flex items-center gap-2 active:scale-95 rounded-2xl">
                    <x-lucide-printer class="w-4 h-4" />
                    <span>Page de génération de documents en PDF dynamique</span>
                </a>
            </section>
        </section>
        <section class="my-3 py-3">
            <span
                class="p-3 rounded-2xl bg-orange-500/10 border border-orange-500/20 text-orange-400 text-lg shrink-0 ls-1">
                {{ __zero($this->teachers->total()) }} enseignants
            </span>
        </section>
        <section class="w-full">

            <div class="border border-slate-800 bg-slate-900 overflow-hidden text-slate-300 mb-28 relative">
                <div wire:loading
                    wire:target='search,gender,status,subject_type,clearFilters,previousPage,nextPage,gotoPage'
                    class="absolute inset-0 flex items-center justify-center bg-slate-800/5 backdrop-blur-xs"
                    style="z-index: 200 !important;">

                    <div
                        class="items-center gap-1 text-slate-400 relative top-1/6 mx-auto flex justify-center flex-col gap-3">
                        <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        <span class="text-xl font-mono ls-1">Rechargement en cours...</span>
                    </div>
                </div>

                <div class="overflow-x-auto">

                    @if ($this->teachers->isEmpty())
                        <div class=" border-slate-800 border bg-transparent p-16 text-center">
                            <div class="text-4xl mb-4">🏫</div>
                            <p class="text-slate-400 text-sm">Aucune classe trouvée pour ces filtres.</p>
                            <button wire:click="clearFilters"
                                class="mt-4 px-5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                <span wire:loading.remove wire:target='clearFilters'>Réinitialiser les filtres</span>
                                <span wire:loading wire:target='clearFilters'
                                    class="inline-flex justify-center gap-3.5 items-center">
                                    <span class="inline-flex justify-center gap-3.5 items-center">
                                        <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                        <span>En cours...</span>
                                    </span>
                                </span>
                            </button>
                        </div>
                    @else
                        <table class="w-full z-table z-table-border">
                            <thead class="bg-slate-950 border-b border-slate-800 truncate">
                                <tr>
                                    <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                        N°
                                    </th>
                                    <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                        Enseignant
                                    </th>

                                    <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                        <span class="inline-flex flex-col">
                                            <span> Matière </span>
                                            <span>enseignées</span>
                                            @if ($classe)
                                                <span>dans cette classe</span>
                                            @endif
                                        </span>
                                    </th>

                                    <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                        Autres Classes
                                    </th>

                                    <th class="text-center px-6 py-4 text-sm font-medium text-slate-400">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-800 text-center">

                                @foreach ($this->teachers as $teacher)
                                    <tr class="hover:bg-slate-800/40 transition-all">
                                        <td class="px-3 py-5 text-center whitespace-nowrap">

                                            {{ __zero($this->teachers->firstItem() + $loop->iteration - 1) }}

                                        </td>
                                        <td class="px-6 py-5 truncate">
                                            <a href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                                class="flex items-center gap-4 group font-mono">
                                                <div
                                                    class="w-16 h-16 bg-slate-800 shrink-0 rounded-full border-4 group-hover:border-sky-600">
                                                    <img src="{{ $teacher->user->profil_photo_url }}"
                                                        class="w-full h-full object-cover rounded-full ">
                                                </div>
                                                <div class="text-left">
                                                    <span class="flex flex-col">
                                                        <h3
                                                            class="truncate text-slate-200 group-hover:underline underline-offset-4 group-hover:text-sky-600">
                                                            {{ $teacher->getFullName() }}
                                                        </h3>
                                                        <span class="text-slate-500 font-mono text-xs">
                                                            ID: {{ $teacher->identifiant }}
                                                        </span>
                                                        <span
                                                            class="text-slate-500 font-mono text-xs flex gap-x-2 items-center">
                                                            <x-lucide-phone class="w-3 h-3" />
                                                            <span
                                                                class="ls-1">{{ $teacher->user?->contacts }}</span>
                                                        </span>
                                                    </span>
                                                </div>

                                            </a>
                                            @if ($classe)
                                                <small
                                                    class="font-mono text-2xs flex justify-end w-full text-yellow-500">
                                                    Inséré dans la classe
                                                    {{ __formatDate($teacher->classeSubjects->first()->started_at) }}
                                                </small>
                                                @if ($teacher->cannotAccessIntoClasse($classe->id))
                                                    <span
                                                        class="bg-red-400/30 text-red-500 mt-2 rounded-2xl border border-red-500 animate-pulse p-1 font-mono text-xs flex w-full px-2.5 justify-center">
                                                        Accès à la classe bloqué
                                                    </span>
                                                @endif
                                            @endif

                                        </td>

                                        <td class="px-6 py-5 truncate">
                                            <div
                                                class="mt-1 font-medium flex gap-2 flex-col items-center text-sm justify-center">
                                                @if ($classe)
                                                    @foreach ($teacher->getSubjectsForThisClasse($classe->id) as $subjectRelation)
                                                        <span
                                                            class="px-3 py-1 border rounded-full bg-indigo-500/10 text-indigo-400 text-sm uppercase">
                                                            {{ $subjectRelation->subject->code }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    @foreach ($teacher->getYearlySubjects() as $yearly_subject)
                                                        <span
                                                            class="rounded-xl p-1 px-3 font-mono bg-indigo-900/40 text-slate-400 cursor-pointer hover:scale-105 transition-transform border border-amber-600/40 uppercase">{{ $yearly_subject->subject->code }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-5 truncate">

                                            <div class="flex flex-col items-center justify-center gap-2 ">
                                                @php
                                                    $classess = $teacher->getTeacherClassesForThisSchoolYear();

                                                @endphp
                                                @if (count($classess))
                                                    @foreach ($classess as $cl)
                                                        <span
                                                            class="px-2 py-1 rounded-xl bg-slate-800 text-xs uppercase font-mono border border-sky-700">
                                                            {{ $cl?->code ? $cl->code : $cl->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span
                                                        class="px-2 py-1 rounded-xl text-slate-400 ls-2 italic text-xs flex justify-center flex-col">
                                                        <span>Aucune autre</span>
                                                        <span>classe assignée</span>
                                                    </span>
                                                @endif

                                            </div>

                                        </td>
                                        <td class="px-6 py-5">

                                            <div class="flex items-center justify-end gap-2 truncate">

                                                <a wire:navigate
                                                    href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                                    class="cursor-pointer bg-indigo-800 hover:bg-indigo-500 ttext-xs font-medium inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 text-xs">
                                                    <x-lucide-user class="w-4 h-4" />
                                                    <span>Profil</span>
                                                </a>

                                                @if ($teacher->user)
                                                    <button
                                                        title="Envoyer les données de connexion à {{ $teacher->getFullName() }}"
                                                        wire:click="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                        class=" inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-sky-600/50 hover:bg-sky-800/50 text-sky-400 transition-all whitespace-nowrap disabled:opacity-50 text-xs">
                                                        <span wire:loading.remove
                                                            wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                            class="flex items-center gap-1.5">
                                                            <x-lucide-send class="w-4 h-4" />
                                                            Envoyer
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                            class="flex items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                @endif

                                                <button
                                                    title="{{ $teacher->is_locked ? 'Débloquer ' : 'Bloquer ' }} cet enseigant "
                                                    wire:click="{{ $teacher->is_locked ? 'unlockTeacher(' . $teacher->id . ')' : 'lockTeacher(' . $teacher->id . ')' }}"
                                                    wire:loading.attr="disabled"
                                                    wire:target="lockTeacher, unlockTeacher"
                                                    class="relative py-3 px-4 rounded-xl {{ !$teacher->is_locked ? 'bg-amber-600/80 hover:bg-amber-800/80' : 'bg-purple-500/20 hover:bg-purple-600/60' }} text-xs font-medium inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50">
                                                    <span wire:loading.remove wire:target="lockTeacher, unlockTeacher"
                                                        class="inline-flex items-center justify-center gap-3">
                                                        <span class="inline-flex items-center justify-center gap-3">
                                                            @if ($teacher->is_locked)
                                                                <x-lucide-unlock class="w-4 h-4" />
                                                                <span>Débloquer</span>
                                                            @else
                                                                <x-lucide-user-lock class="w-4 h-4" />
                                                                <span>Bloquer</span>
                                                            @endif
                                                        </span>
                                                    </span>

                                                    <span wire:loading wire:target="lockTeacher, unlockTeacher"
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

                                                @if ($classe)
                                                    <button
                                                        title="{{ $teacher->cannotAccessIntoClasse($classe->id) ? 'Déverouiller' : 'Vérouiller ' }} l'édition et l'insertion des notes du prof dans la classe"
                                                        wire:click="{{ $teacher->cannotAccessIntoClasse($classe->id)
                                                            ? 'unLockAccessToClasse(' . $teacher->id . ',' . $classe->id . ')'
                                                            : 'lockAccessToClasse(' . $teacher->id . ',' . $classe->id . ')' }}"
                                                        wire:loading.attr="disabled"
                                                        wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                        class="relative py-3 px-4 rounded-xl {{ !$teacher->cannotAccessIntoClasse($classe->id) ? 'bg-red-600/50 hover:bg-red-500/80' : 'bg-green-500/20 hover:bg-green-600/60' }}  text-xs font-medium inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50">
                                                        <span wire:loading.remove
                                                            wire:target="lockAccessToClasse, unLockAccessToClasse"
                                                            class="inline-flex items-center justify-center gap-3">
                                                            <span
                                                                class="inline-flex items-center justify-center gap-3">
                                                                @if ($teacher->cannotAccessIntoClasse($classe->id))
                                                                    <x-lucide-check class="w-4 h-4" />
                                                                    <span>Déverouiller </span>
                                                                @else
                                                                    <x-lucide-user-lock class="w-4 h-4" />
                                                                    <span>Verouiller </span>
                                                                @endif
                                                            </span>
                                                        </span>

                                                        <span wire:loading
                                                            wire:target="lockAccessToClasse, unLockAccessToClasse"
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

                    @endif

                    @if ($this->teachers->hasPages())
                        <section class="py-6">
                            <div class="flex justify-center bg-slate-900 p-4">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="text-sm text-slate-400">
                                        Affichage {{ $this->teachers->firstItem() }} à
                                        {{ $this->teachers->lastItem() }}
                                        sur
                                        {{ $this->teachers->total() }} enseignants
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if (!$this->teachers->onFirstPage())
                                            <button wire:click="previousPage" wire:loading.attr="disabled"
                                                wire:target="previousPage"
                                                class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                                Précédent
                                            </button>
                                        @endif

                                        @foreach ($this->teachers->getUrlRange(1, $this->teachers->lastPage()) as $page => $url)
                                            <button @disabled($page === $this->teachers->currentPage())
                                                wire:click="gotoPage({{ $page }})"
                                                class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->teachers->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                                {{ $page }}
                                            </button>
                                        @endforeach

                                        @if ($this->teachers->hasMorePages())
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

        </section>
    </section>
</div>

