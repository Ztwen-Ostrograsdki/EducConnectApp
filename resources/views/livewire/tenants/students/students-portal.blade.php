<div class="w-full overflow-x-hidden p-2">
    <div wire:loading
        wire:target='gender,status,department,city,clearFilters,subject_id,classe_id,promotion_id,filiar_id,forceDeleteTeachers,search,previousPage,nextPage,gotoPage'
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
    <div class="mx-auto w-full max-w-462.5 px-3 sm:px-3 lg:px-6 xl:px-8">
        <section class="mb-6">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">
                <div class="min-w-0">

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl sm:text-3xl font-bold">

                            Apprenants

                        </h1>

                        <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">

                            {{ __zero($this->allStudentsCounter) }} Apprenants

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm sm:text-base">

                        Vue globale des apprenants de l’établissement

                    </p>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-wrap items-center gap-3">

                    <button wire:click='printStudentsList'
                        class="py-2.5 px-5 rounded-2xl bg-sky-500/50 hover:bg-sky-600/75 transition-all text-sm">
                        <span wire:loading.remove wire:target='printStudentsList'
                            class="inline-flex gap-x-2 items-center">
                            <x-lucide-save class="w-4 h-4" />
                            Exporter la liste en PDF
                        </span>
                        <span wire:loading wire:target='printStudentsList' class="inline-flex items-center gap-x-2">
                            <span class="flex items-center gap-x-2.2">
                                <span>Document en cours...</span>
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            </span>
                        </span>

                    </button>

                    <a href="{{ route('tenant.students.create') }}"
                        class="py-2.5 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm">
                        Ajouter apprenants
                    </a>
                    <a href="{{ route('tenant.students.print.list') }}"
                        class="py-2.5 px-5 rounded-2xl bg-gray-500 hover:bg-gray-600 transition-all text-sm">
                        Liste imprimable
                    </a>

                    @if ($doc = \App\Models\GeneratedDocument::ofType('student_list')->forUser(auth()->id())->latest()->first())

                        <div class="flex items-center gap-3">
                            <button wire:click="trackDownload({{ $doc->id }})"
                                class="bg-green-600 hover:bg-green-800 text-white rounded-2xl py-2.5 px-5 transition-all text-sm">
                                <span wire:loading.remove wire:target='trackDownload({{ $doc->id }})'
                                    class="inline-flex gap-x-2 items-center">
                                    <x-lucide-save class="w-4 h-4" />
                                    Télécharger liste
                                    @if ($doc->downloaded_count > 0)
                                        <span class="text-xs opacity-60">({{ $doc->downloaded_count }}x)</span>
                                    @endif
                                </span>
                                <span wire:loading wire:target='trackDownload({{ $doc->id }})'
                                    class="inline-flex items-center gap-x-2">
                                    <span class="flex items-center gap-x-2.2">
                                        <span>Document en cours...</span>
                                        <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                    </span>
                                </span>
                            </button>
                            @if (!$doc->downloaded)
                                <span wire:loading.remove wire:target='trackDownload({{ $doc->id }})'
                                    class="text-xs border border-green-600 text-green-600 bg-gray-900 p-0.5 rounded-xl relative right-16 -top-5 px-1.5 animate-pulse">Nouveau</span>
                            @endif
                        </div>
                    @endif

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- KPI --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

                @foreach ([['Total', __zero($this->allStudentsCounter), 'text-indigo-400'], ['Actifs', __zero($this->activesStudentsCounter), 'text-emerald-400'], ['Taux Présence', '96%', 'text-amber-400']] as $kpi)
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-4 sm:p-5">
                        <p class="text-xs sm:text-sm text-slate-400">
                            {{ $kpi[0] }}
                        </p>
                        <h2 class="mt-3 text-2xl sm:text-3xl xl:text-4xl font-bold {{ $kpi[2] }}">
                            {{ $kpi[1] }}
                        </h2>
                    </div>
                @endforeach

            </div>

        </section>

        <section class="mb-6">

            <div class="rounded-3xl border border-slate-800  bg-slate-900 p-4 sm:p-5">
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-7 gap-x-3">
                        <div class="relative col-span-5">

                            <input wire:model.live.debounce.400ms='search' type="text"
                                placeholder="Rechercher un apprenant..."
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

                        <select wire:model.live='classe_id'
                            class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                            <option value="">Toutes les classes</option>
                            @foreach ($this->classes as $cl)
                                <option value="{{ $cl->id }}">
                                    Classe de {{ $cl->code ? $cl->code : $cl->name }}
                                </option>
                            @endforeach
                        </select>

                        <select wire:model.live='filiar_id'
                            class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                            <option value="">Toutes les filières</option>
                            @foreach ($this->filiars as $f)
                                <option value="{{ $f->id }}">
                                    Filière {{ $f->code ? $f->code : $f->name }}
                                </option>
                            @endforeach
                        </select>

                        <select wire:model.live='promotion_id'
                            class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                            <option value="">Toutes les promotions</option>
                            @foreach ($this->promotions as $promo)
                                <option value="{{ $promo->id }}">
                                    Promotion
                                    {{ $promo->code ? $promo->code : $promo->name }}
                                </option>
                            @endforeach
                        </select>

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
                            <option class="text-green-400" value="actifs">
                                <span>
                                    <span>Actifs</span>
                                </span>
                            </option>
                            <option value="desactives">
                                <span>Désactivés</span>
                            </option>
                            <option class="text-orange-600" value="de la corbeille">
                                <span>La corbeille</span>
                            </option>
                        </select>
                    </div>

                </div>

            </div>

        </section>

        <section class="flex items-end justify-end gap-3 py-4 my-3">
            <a href="{{ route('tenant.students.print.configuration') }}"
                class="py-3 px-5 bg-indigo-700/40 hover:bg-indigo-800 text-white hover:text-black flex items-center gap-2 active:scale-95 rounded-2xl">
                <x-lucide-printer class="w-4 h-4" />
                <span>Page d'impression dynamique</span>
            </a>
        </section>

        <section>

            <div class="space-y-6 min-w-0">
                <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

                    <div class="border-b border-slate-800 p-4 sm:p-6">
                        <div class="flex flex-col gap-y-3">
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold">
                                    Liste @if (!$status)
                                        de tous les
                                    @else
                                        des
                                        @endif apprenants @if ($status)
                                            <span class="uppercase text-orange-600">
                                                {{ $status }}
                                            </span>
                                        @endif
                                </h2>
                                <p class="mt-1 text-sm text-slate-400">
                                    Gestion et suivi des apprenants

                                </p>
                            </div>

                            <div class="flex w-full justify-end">
                                <div class="flex flex-wrap items-center gap-3 text-sm ">
                                    <button title="Réactiver tous les apprenants désactivés"
                                        wire:click="reactivateStudents" wire:loading.attr="disabled"
                                        wire:target="reactivateStudents"
                                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-purple-600/50 hover:bg-purple-500/90 hover:text-black text-yellow-200 transition-all whitespace-nowrap disabled:opacity-50">
                                        <span wire:loading.remove wire:target="reactivateStudents"
                                            class="inline-flex items-center gap-1.5">
                                            <x-lucide-lock-keyhole-open class="w-4 h-4" />
                                            <span>Réactiver les apprenants</span>
                                        </span>
                                        <span wire:loading wire:target="reactivateStudents"
                                            class="inline-flex items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1.5">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                <span>En cours...</span>
                                            </span>
                                        </span>
                                    </button>

                                    <button title="Restorer tous les apprenants de la corbeille"
                                        wire:click="restoreStudents" wire:loading.attr="disabled"
                                        wire:target="restoreStudents"
                                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-green-800/80 hover:bg-green-600/90 hover:text-black text-green-400 transition-all whitespace-nowrap disabled:opacity-50">
                                        <span wire:loading.remove wire:target="restoreStudents"
                                            class="inline-flex items-center gap-1.5">
                                            <x-lucide-recycle class="w-4 h-4" />
                                            <span>Restorer les apprenants</span>
                                        </span>
                                        <span wire:loading wire:target="restoreStudents"
                                            class="inline-flex items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1.5">
                                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                <span>En cours...</span>
                                            </span>
                                        </span>
                                    </button>

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="overflow-x-auto p-2">

                        @if (count($this->students))
                            <table class="z-table-border w-full">

                                <thead class="bg-slate-950 border-b border-slate-800">

                                    <tr>

                                        <th class="px-3 py-4 text-left text-sm text-slate-400">
                                            N°
                                        </th>
                                        <th class="px-3 py-4 text-left text-sm text-slate-400">
                                            Apprenant
                                        </th>

                                        <th class="px-3 py-4 text-center text-sm text-slate-400">
                                            Classe
                                        </th>

                                        <th class="px-3 py-4 text-center text-sm text-slate-400">
                                            Père
                                        </th>

                                        <th class="px-3 py-4 text-center text-sm text-slate-400">
                                            Mère
                                        </th>

                                        <th class="px-3 py-4 text-center text-sm text-slate-400">
                                            Statut
                                        </th>

                                        <th class="px-6 py-4 text-center text-sm text-slate-400">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach ($this->students as $student)
                                        <tr wire:key='liste-enseignants-du-portail-'{{ $student->id }}
                                            class="hover:bg-slate-800/40 transition-all">
                                            <td class="px-3 py-5 text-center whitespace-nowrap">

                                                {{ __zero($this->students->firstItem() + $loop->iteration - 1) }}

                                            </td>

                                            {{-- PROFILE --}}
                                            <td class="px-6 py-5">

                                                <a title="Charger le profil de l'apprenant {{ $student->getFullName() }}"
                                                    href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                                    class="flex items-center gap-4 hover:underline">

                                                    <img src="{{ $student->profil_photo_url() }}" alt=""
                                                        class="w-14 h-14 rounded-full object-cover border-4 border-slate-700">
                                                    <div class="min-w-0">

                                                        <h3 class="font-medium truncate">

                                                            {{ $student->getFullName() }}

                                                        </h3>

                                                        @if ($student->email)
                                                            <p
                                                                class="mt-1 text-sm text-slate-400 truncate flex items-center gap-x-1.5">
                                                                <x-lucide-mail class="w-3.5 h-3.5" />
                                                                <span>
                                                                    {{ $student->email }}
                                                                </span>
                                                            </p>
                                                        @endif
                                                        @if ($student->contacts)
                                                            <p
                                                                class="mt-1 text-sm text-slate-400 truncate font-mono flex items-center gap-x-1.5">

                                                                <x-lucide-phone class="w-3.5 h-3.5" />
                                                                <span>
                                                                    {{ $student->contacts }}
                                                                </span>
                                                            </p>
                                                        @endif

                                                    </div>

                                                </a>

                                            </td>

                                            {{-- SUBJECT --}}
                                            <td class="px-3 py-5 text-center whitespace-nowrap font-mono text-xs">

                                                @if ($student->currentClasse() && $student->currentClasse()->classe)
                                                    @php
                                                        $rel = $student->currentClasse()->classe;
                                                    @endphp
                                                    <a wire:navigate
                                                        href="{{ route('tenant.classe.profil', ['classe_slug' => $rel->slug]) }}"
                                                        class="flex border justify-center rounded-2xl p-2 bg-green-700/20 text-green-300 border-sky-500 hover:bg-green-700/70 hover:text-white hover:border-white">
                                                        <span>{{ $rel->code ? $rel->code : $rel->name }}</span>
                                                    </a>
                                                @else
                                                    <span
                                                        class="flex-col flex gap-1 justify-center text-xs text-slate-600">
                                                        <span>Pas encore de</span>
                                                        <span>classe en {{ $this->activeYear?->slug }}</span>
                                                    </span>
                                                @endif

                                            </td>

                                            {{-- CLASSES --}}
                                            <td class="px-3 py-5 text-center">

                                                -

                                            </td>

                                            {{-- HOURS --}}
                                            <td class="px-3 py-5 text-center text-gray-500">

                                                26h

                                            </td>

                                            {{-- STATUS --}}
                                            <td class="px-3 py-5 text-center">

                                                <span
                                                    class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                    Actif

                                                </span>

                                            </td>

                                            {{-- ACTIONS --}}
                                            <td class="px-3 py-5 truncate">
                                                <div class="flex items-center justify-center gap-2 text-sm w-full">

                                                    <button
                                                        title="{{ $student->is_active ? 'Désactiver' : 'Activer' }} {{ $student->getFullName() }}"
                                                        wire:click="{{ $student->is_active ? 'desactivateStudent(' . $student->id . ')' : 'activateStudent(' . $student->id . ')' }}"
                                                        wire:loading.attr="disabled"
                                                        wire:target="activateStudent({{ $student->id }}), desactivateStudent({{ $student->id }})"
                                                        class=" active:scale-95 inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $student->is_active ? 'bg-amber-600/40 hover:bg-amber-600/80 text-amber-400' : 'bg-green-600/30 hover:bg-green-600/90 text-green-400' }} hover:text-black">
                                                        <span wire:loading.remove
                                                            wire:target="activateStudent({{ $student->id }}), desactivateStudent({{ $student->id }})"
                                                            class="inline-flex items-center gap-1.5">
                                                            @if ($student->is_active)
                                                                <x-lucide-user-x class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Désactiver</span>
                                                            @else
                                                                <x-lucide-user-check class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Activer</span>
                                                            @endif
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="activateStudent({{ $student->id }}), desactivateStudent({{ $student->id }})"
                                                            class="inline-flex items-center gap-1.5">
                                                            <span class="inline-flex items-center gap-1.5">
                                                                <x-lucide-refresh-ccw
                                                                    class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                <span>En cours...</span>
                                                            </span>
                                                        </span>
                                                    </button>

                                                    <button
                                                        title="{{ $student->deleted_at ? 'Restaurer' : 'Mettre en corbeille' }} {{ $student->getFullName() }}"
                                                        wire:click="{{ $student->deleted_at ? 'restoreStudent(' . $student->id . ')' : 'deleteStudent(' . $student->id . ')' }}"
                                                        wire:loading.attr="disabled"
                                                        wire:target="deleteStudent({{ $student->id }}), restoreStudent({{ $student->id }})"
                                                        class="active:scale-95 inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $student->deleted_at ? 'bg-violet-600/50 hover:bg-violet-800/50 text-black-400 hover:text-yellow-300' : 'bg-orange-600/40 hover:bg-orange-600/90 text-yellow-400 hover:text-black' }}">
                                                        <span wire:loading.remove
                                                            wire:target="deleteStudent({{ $student->id }}), restoreStudent({{ $student->id }})"
                                                            class="inline-flex items-center gap-1.5">
                                                            @if ($student->deleted_at)
                                                                <x-lucide-recycle class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Restaurer</span>
                                                            @else
                                                                <x-lucide-trash class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Corbeille</span>
                                                            @endif
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="deleteStudent({{ $student->id }}), restoreStudent({{ $student->id }})"
                                                            class="inline-flex items-center gap-1.5">
                                                            <span class="inline-flex items-center gap-1.5">
                                                                <x-lucide-refresh-ccw
                                                                    class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                <span>En cours...</span>
                                                            </span>
                                                        </span>
                                                    </button>

                                                    @if ($student->deleted_at)
                                                        <button
                                                            title="Supprimer définitivement l'apprenant {{ $student->getFullName() }}"
                                                            wire:click="forceDeleteStudent({{ $student->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="forceDeleteStudent({{ $student->id }})"
                                                            class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-red-600/40 hover:bg-red-600/90 text-white hover:text-black transition-all whitespace-nowrap disabled:opacity-50 active:scale-95">
                                                            <span wire:loading.remove
                                                                wire:target="forceDeleteStudent({{ $student->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                <x-lucide-trash-2 class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Suppr. déf.</span>
                                                            </span>
                                                            <span wire:loading
                                                                wire:target="forceDeleteStudent({{ $student->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                <span class="inline-flex items-center gap-1.5">
                                                                    <x-lucide-refresh-ccw
                                                                        class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                    <span>En cours...</span>
                                                                </span>
                                                            </span>
                                                        </button>
                                                    @endif

                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        @else
                            <div class="w-full justify-center p-3">
                                <div class="p-5 flex justify-center w-full text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <p class="text-slate-500 text-sm">Aucune promotion trouvée.</p>
                                        @if ($search || $gender || $serial_id)
                                            <button wire:click="resetFilters"
                                                class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                                Réinitialiser les filtres
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @endif
                    </div>
                    @if ($this->students->hasPages())
                        <section class="py-6 p-2">
                            <div class="flex justify-center bg-slate-900 p-4">
                                <div class="flex flex-col justify-center gap-4">
                                    <div class="text-sm text-slate-400">
                                        Affichage {{ $this->students->firstItem() }} à
                                        {{ $this->students->lastItem() }}
                                        sur
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
                                                class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->students->currentPage() ? 'bg-indigo-500 text-white ' : 'bg-slate-800 hover:bg-slate-700' }}">
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

        </section>

    </div>

</div>

