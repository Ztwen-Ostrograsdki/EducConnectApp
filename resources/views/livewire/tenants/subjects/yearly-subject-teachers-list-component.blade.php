<div>
    <section class="mb-6">
        @php
            $unaccesses = tenancy()->tenant?->getTeachersWithoutYearlyAccesses();
        @endphp
        @if (count($unaccesses))
            <div
                class="rounded-2xl border border-red-800 bg-red-900/30 p-2 font-mono text-sm animate-pulse text-red-400 my-3">
                <span>{{ __zero(count($unaccesses)) }} enseignant(s) sont sans accès pour cette année scolaire
                    {{ $this->activeYear?->slug ?? '' }}</span>
                <p>
                    Veuillez leur accorder les accès. Autrement, vous ne
                    pourriez ni définir leurs matières ni leur attribuer de classe!
                </p>
            </div>
        @endif

        <div
            class="rounded-tr-2xl rounded-tl-2xl
                        bg-slate-900
                        border border-slate-800
                        overflow-hidden">

            {{-- HEADER --}}
            <div class="p-5 border-b border-slate-800">

                <div
                    class="flex flex-col
                                xl:flex-row
                                xl:items-center
                                xl:justify-between
                                gap-4">

                    <div>

                        <h2 class="text-xl font-semibold">

                            Enseignants de la Matière

                        </h2>

                        <p class="mt-1 text-sm text-slate-400">

                            Gestion des enseignants
                            et classes concernées.

                        </p>

                    </div>
                </div>

            </div>
            <section class="mb-6">
                <div wire:loading
                    wire:target='classe_id,gender,promotion_id,filiar_id,search,previousPage,nextPage,resetFilters,gotoPage'
                    class="fixed inset-0 flex items-center justify-center bg-slate-800/10 backdrop-blur-sm"
                    style="z-index: 200 !important;">

                    <div
                        class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-col">
                        <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        <span class="text-xl font-mono ls-1">Chargement en cours...</span>
                    </div>
                </div>

                <div class="bg-slate-900 p-4 sm:p-5">
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-7 gap-x-3">
                            <div class="relative col-span-5">

                                <input wire:model.live='search' type="text" placeholder="Rechercher un enseignant..."
                                    class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm  focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                                    🔍
                                </div>
                            </div>
                            <button wire:click='resetFilters'
                                class="py-2 rounded-2xl bg-slate-600 hover:bg-slate-800 transition-all text-sm col-span-2">
                                <span wire:loading.remove wire:target='resetFilters'
                                    class="inline-flex gap-x-2 items-center ">
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

                            <select wire:model.live='gender'
                                class="h-11 px-3 rounded-2xl bg-slate-950 border border-slate-800 text-sm">
                                <option value="">Sexe</option>
                                @foreach (config('app.genders') as $gk => $gdr)
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

            {{-- TABLE --}}
            <div class="overflow-x-auto p-4">

                @if (count($this->teachers))
                    <table class="z-table-border w-full">

                        <thead class="bg-slate-950 border-b border-slate-800">

                            <tr>

                                <th class="px-3 py-4 text-left text-sm text-slate-400">
                                    N°
                                </th>
                                <th class="px-3 py-4 text-left text-sm text-slate-400">
                                    Enseignant
                                </th>

                                <th class="px-3 py-4 text-center text-sm text-slate-400">
                                    Matière
                                </th>

                                <th class="px-3 py-4 text-center text-sm text-slate-400">
                                    Classes
                                </th>

                                <th class="px-3 py-4 text-center text-sm text-slate-400">
                                    Heures/Sem
                                </th>

                                <th class="px-6 py-4 text-center text-sm text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @foreach ($this->teachers as $teacher)
                                <tr wire:key='liste-enseignants-du-portail-'{{ $teacher->id }}
                                    class="hover:bg-slate-800/40 transition-all">
                                    <td class="px-3 py-5 text-center whitespace-nowrap">

                                        {{ __zero($this->teachers->firstItem() + $loop->iteration - 1) }}

                                    </td>

                                    {{-- PROFILE --}}
                                    <td class="px-6 py-5 text-slate-400">

                                        <a title="Charger le profil de l'enseignant {{ $teacher->getFullName() }}"
                                            href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                            class="flex items-center gap-4 underline-offset-4 hover:underline hover:text-amber-600">

                                            <img src="{{ $teacher->profil_photo_url() }}"
                                                alt="Photo de profil de {{ $teacher->fullName() }}"
                                                class="w-14 h-14 rounded-full object-cover border-4 border-slate-700">
                                            <div class="min-w-0">

                                                <h3 class="font-medium ">

                                                    {{ $teacher->getFullName() }}

                                                </h3>

                                                <p class="mt-1 text-sm text-slate-400 flex items-center gap-x-1.5">
                                                    <x-lucide-mail class="w-3.5 h-3.5" />
                                                    <span>
                                                        {{ $teacher->user->email }}
                                                    </span>

                                                </p>
                                                <p
                                                    class="mt-1 text-sm text-slate-400 font-mono flex items-center gap-x-1.5">

                                                    <x-lucide-phone class="w-3.5 h-3.5" />
                                                    <span>
                                                        {{ $teacher->user->contacts }}
                                                    </span>

                                                </p>

                                            </div>

                                        </a>
                                        <span
                                            class="px-3 rounded-full @if ($teacher->hasValidAccessForYear()) bg-emerald-500/10 text-emerald-400 @else  bg-red-500/10 text-red-400 animate-pulse @endif border border-slate-600 w-full flex text-xs py-1 mt-2 text-center items-center justify-center gap-x-1">
                                            <span>Accès
                                                {{ tenancy()->tenant?->getActiveSchoolYear()?->slug }}</span>
                                            @if ($teacher->hasValidAccessForYear())
                                                <span> accordé</span>
                                            @else
                                                <span> non accordé</span>
                                            @endif
                                        </span>

                                    </td>

                                    {{-- SUBJECT --}}
                                    <td class="px-3 py-5 text-center whitespace-nowrap">

                                        <div class="mt-1 font-medium flex gap-2 text-sm justify-center">
                                            @foreach ($teacher->getYearlySubjects() as $yearly_subject)
                                                <span
                                                    class="rounded-xl p-1 px-3 font-mono bg-indigo-900/40 text-slate-400 cursor-pointer hover:scale-105 transition-transform border border-amber-600/40 uppercase">{{ $yearly_subject->subject->code }}</span>
                                            @endforeach
                                        </div>

                                    </td>

                                    {{-- CLASSES --}}
                                    <td class="px-3 py-5 text-center truncate">

                                        @php
                                            $teacher_classes = $teacher->getTeacherClassesForThisSchoolYear([]);

                                        @endphp
                                        @if (count($teacher_classes))
                                            @foreach ($teacher_classes as $cl)
                                                <span
                                                    class="px-2 py-1 rounded-xl bg-slate-800 text-xs uppercase font-mono border border-sky-700">
                                                    {{ $cl?->code ?? $cl->name }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span
                                                class="px-2 py-1 rounded-xl text-slate-400 ls-2 italic text-xs flex justify-center flex-col">
                                                <span>Aucune</span>
                                                <span>classe assignée</span>
                                            </span>
                                        @endif

                                    </td>

                                    {{-- HOURS --}}
                                    <td class="px-3 py-5 text-center text-gray-500">

                                        -

                                    </td>

                                    <td class="px-3 py-5">
                                        <div class="flex flex-wrap gap-2 items-center justify-center text-xs">

                                            {{-- Matières --}}
                                            @if ($teacher->hasValidAccessForYear())
                                                <a title="Définir les matières de {{ $teacher->getFullName() }}"
                                                    wire:navigate
                                                    href="{{ route('tenant.teacher.manage.subjects', ['teacher_uuid' => $teacher->uuid]) }}"
                                                    class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-indigo-600/30 hover:bg-indigo-600/80 text-white transition-all whitespace-nowrap">
                                                    <span>⚙️</span>
                                                    <span>Matières</span>
                                                </a>
                                            @endif

                                            {{-- Envoyer credentials --}}
                                            <button
                                                title="{{ $teacher->blocked ? 'Débloquer' : 'Bloquer' }} {{ $teacher->getFullName() }}"
                                                wire:click="{{ $teacher->blocked ? 'unlockTeacher(' . $teacher->id . ')' : 'lockTeacher(' . $teacher->id . ')' }}"
                                                wire:loading.attr="disabled"
                                                wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                                class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $teacher->blocked ? 'bg-green-600/30 hover:bg-green-800/80 text-black' : 'bg-red-600/30 hover:bg-red-700/80 text-red-200' }}">
                                                <span wire:loading.remove
                                                    wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                                    class="inline-flex items-center gap-1.5">
                                                    @if ($teacher->blocked)
                                                        <x-lucide-lock-keyhole-open class="w-3.5 h-3.5 shrink-0" />
                                                        <span>Débloquer</span>
                                                    @else
                                                        <x-lucide-ban class="w-3.5 h-3.5 shrink-0" />
                                                        <span>Bloquer</span>
                                                    @endif
                                                </span>
                                                <span wire:loading
                                                    wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                                    class="inline-flex items-center gap-1.5">
                                                    <span class="inline-flex items-center gap-1.5">
                                                        <x-lucide-refresh-ccw
                                                            class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                        <span>En cours...</span>
                                                    </span>
                                                </span>
                                            </button>

                                            <button
                                                title="Retirer à {{ $teacher->getFullName() }} la matière {{ $subject->name }}"
                                                wire:click="retrieveSubject('{{ $teacher->id }}', {{ $subject->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="retrieveSubject('{{ $teacher->id }}', {{ $subject->id }})"
                                                class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-orange-600/30 hover:bg-orange-600/80 text-white transition-all whitespace-nowrap disabled:opacity-50">
                                                <span wire:loading.remove
                                                    wire:target="retrieveSubject('{{ $teacher->id }}', {{ $subject->id }})"
                                                    class="inline-flex items-center gap-1.5">
                                                    <x-lucide-user-x class="w-3.5 h-3.5 shrink-0" />
                                                    <span>Retirer matière</span>
                                                </span>
                                                <span wire:loading
                                                    wire:target="retrieveSubject('{{ $teacher->id }}', {{ $subject->id }})"
                                                    class="inline-flex items-center gap-1.5">
                                                    <span class="inline-flex items-center gap-1.5">
                                                        <x-lucide-refresh-ccw
                                                            class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                        <span>En cours...</span>
                                                    </span>
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
                                <p class="text-slate-500 text-sm">Aucune enseignant trouvé </p>
                                @if ($search || $status || $classe_id || $promotion_id || $filiar_id || $gender || $city || $department)
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
            @if ($this->teachers->hasPages())
                <section class="py-6">
                    <div class="flex justify-center bg-slate-900 p-4">
                        <div class="flex flex-col items-center gap-4">
                            <div class="text-sm text-slate-400">
                                Affichage {{ $this->teachers->firstItem() }} à {{ $this->teachers->lastItem() }} sur
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
                                    <button @disabled($page === $this->teachers->currentPage()) wire:click="gotoPage({{ $page }})"
                                        class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->teachers->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                        {{ $page }}
                                    </button>
                                @endforeach

                                @if ($this->teachers->hasMorePages())
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

    </section>
</div>

