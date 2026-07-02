<div class="w-full overflow-x-hidden p-2">
    <div class="mx-auto w-full max-w-[1850px] px-3 sm:px-3 lg:px-6 xl:px-8">
        <section class="mb-6">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl sm:text-3xl font-bold">
                            Enseignants
                        </h1>
                        <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">

                            {{ __zero($allTeachersCounter) }} Enseignants

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm sm:text-base">
                        Vue globale du personnel enseignant de l’établissement
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">

                    <button wire:click='printTeachersList'
                        class="py-2.5 px-5 rounded-2xl bg-sky-500/50 hover:bg-sky-600/75 transition-all text-sm">
                        <span wire:loading.remove wire:target='printTeachersList'
                            class="inline-flex gap-x-2 items-center">
                            <x-lucide-save class="w-4 h-4" />
                            Exporter la liste en PDF
                        </span>
                        <span wire:loading wire:target='printTeachersList' class="inline-flex items-center gap-x-2">
                            <span class="flex items-center gap-x-2.2">
                                <span>Document en cours...</span>
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            </span>
                        </span>

                    </button>

                    <a href="{{ route('tenant.teachers.create') }}"
                        class="py-2.5 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm">
                        Ajouter Enseignant
                    </a>

                    @if ($doc = \App\Models\GeneratedDocument::ofType('teacher_list')->forUser(auth()->id())->latest()->first())
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
        <section class="mb-6">
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

                @foreach ([['Total', __zero($allTeachersCounter), 'text-indigo-400'], ['Actifs', __zero($activesTeachersCounter), 'text-emerald-400'], ['Taux Présence', '96%', 'text-amber-400'], ['Sans accès', __zero(count(tenancy()->tenant?->getTeachersWithoutYearlyAccesses()))]] as $kpi)
                    <div
                        class="rounded-3xl
                            border border-slate-800
                            bg-slate-900
                            p-4 sm:p-5">

                        <p class="text-xs sm:text-sm text-slate-400">
                            {{ $kpi[0] }}
                        </p>

                        <h2
                            class="mt-3
                               text-2xl sm:text-3xl xl:text-4xl
                               font-bold {{ $kpi[2] ?? 'text-sky-600' }}">

                            {{ $kpi[1] }}

                        </h2>

                    </div>
                @endforeach

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- FILTER BAR --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-3xl border border-slate-800  bg-slate-900 p-4 sm:p-5">
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-7 gap-x-3">
                        <div class="relative col-span-5">

                            <input wire:model.live='search' type="text" placeholder="Rechercher un enseignant..."
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
                        <select wire:model.live='subject_id'
                            class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                            <option value="">Toutes les matières</option>
                            @foreach ($this->subjects as $sub)
                                <option value="{{ $sub->id }}">
                                    {{ $sub->code ? $sub->code : $sub->name }}
                                </option>
                            @endforeach
                        </select>

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
        <section>

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

            <div class="space-y-6 min-w-0">
                <div class="rounded-tr-2xl rounded-tl-2xl border border-slate-800 bg-slate-900 overflow-hidden p-2">
                    <div class="border-b border-slate-800 p-4 sm:p-6">
                        <div class="flex flex-col gap-y-3">
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold">
                                    Liste des Enseignants
                                </h2>
                                <p class="mt-1 text-sm text-slate-400">
                                    Gestion et suivi du personnel enseignant
                                </p>
                            </div>

                            <div class="flex w-full justify-end">
                                <div class="flex w-full justify-end">
                                    <div class="flex flex-wrap items-center gap-3 text-sm">

                                        {{-- Débloquer tous --}}
                                        <button wire:click="giveAccessesToTeachersForThisSchoolYear"
                                            wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex items-center px-2.5 justify-center cursor-pointer bg-violet-500/10 hover:bg-violet-700/50 text-violet-400 bg">
                                            <span wire:loading.remove
                                                wire:target="giveAccessesToTeachersForThisSchoolYear"
                                                class="flex items-center gap-1.5">
                                                <x-lucide-user-key class="w-4 h-4" />
                                                Accoder accès aux enseignants
                                            </span>
                                            <span wire:loading wire:target="giveAccessesToTeachersForThisSchoolYear"
                                                class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-x-2">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>
                                        <button wire:click="unlockTeachers" wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex items-center px-2.5 justify-center cursor-pointer bg-lime-500/10 hover:bg-lime-700/50 text-lime-400">
                                            <span wire:loading.remove wire:target="unlockTeachers"
                                                class="flex items-center gap-1.5">
                                                <x-lucide-lock-keyhole-open class="w-4 h-4" />
                                                Débloquer tous
                                            </span>
                                            <span wire:loading wire:target="unlockTeachers"
                                                class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-x-2">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>

                                        {{-- Bloquer tous --}}
                                        <button wire:click="lockTeachers" wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex px-2.5 items-center justify-center cursor-pointer bg-orange-500/10 hover:bg-orange-500/20 text-orange-400">
                                            <span wire:loading.remove wire:target="lockTeachers"
                                                class="flex items-center gap-1.5">
                                                <x-lucide-ban class="w-4 h-4" />
                                                Bloquer tous
                                            </span>
                                            <span wire:loading wire:target="lockTeachers"
                                                class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-x-2">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>

                                        {{-- Restaurer tous --}}
                                        <button wire:click="restoreTeachers" wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex px-2.5 items-center justify-center cursor-pointer bg-purple-500/10 hover:bg-purple-500/20 text-purple-400">
                                            <span wire:loading.remove wire:target="restoreTeachers"
                                                class="flex items-center gap-1.5">
                                                <x-lucide-recycle class="w-4 h-4" />
                                                Restaurer tous
                                            </span>
                                            <span wire:loading wire:target="restoreTeachers"
                                                class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-x-2">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>

                                        {{-- Supprimer déf. tous --}}
                                        <button wire:click="forceDeleteTeachers" wire:loading.attr="disabled"
                                            class="h-11 rounded-2xl flex px-2.5 items-center justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400">
                                            <span wire:loading.remove wire:target="forceDeleteTeachers"
                                                class="flex items-center gap-1.5">
                                                <x-lucide-trash-2 class="w-4 h-4" />
                                                Supprimer déf. tous
                                            </span>
                                            <span wire:loading wire:target="forceDeleteTeachers"
                                                class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-x-2">
                                                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                    <span>En cours...</span>
                                                </span>
                                            </span>
                                        </button>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="overflow-x-auto relative">
                        <div wire:loading
                            wire:target='gender,status,department,city,restoreTeachers,unlockTeachers,giveAccessesToTeachersForThisSchoolYear,lockTeachers,clearFilters,subject_id,classe_id,promotion_id,filiar_id,forceDeleteTeachers,search,previousPage,nextPage,gotoPage'
                            class="absolute inset-0 flex items-center justify-center bg-slate-800/20 backdrop-blur-sm"
                            style="z-index: 200 !important;">

                            <div
                                class="items-center gap-1 text-slate-400 relative top-1/7 mx-auto flex justify-center flex-row">
                                <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                <span class="text-2xl font-mono ls-1">Chargement en cours...</span>
                            </div>
                        </div>
                        @if (count($teachers))
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

                                    @foreach ($teachers as $teacher)
                                        <tr wire:key='liste-enseignants-du-portail-'{{ $teacher->id }}
                                            class="hover:bg-slate-800/40 transition-all">
                                            <td class="px-3 py-5 text-center whitespace-nowrap">

                                                {{ __zero($teachers->firstItem() + $loop->iteration - 1) }}

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

                                                        <p
                                                            class="mt-1 text-sm text-slate-400 flex items-center gap-x-1.5">
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
                                                <div class="flex flex-wrap items-center gap-2 text-xs">

                                                    {{-- Matières --}}
                                                    @if ($teacher->hasValidAccessForYear())
                                                        <a title="Définir les matières de {{ $teacher->getFullName() }}"
                                                            wire:navigate
                                                            href="{{ route('tenant.teacher.manage.subjects', ['teacher_uuid' => $teacher->uuid]) }}"
                                                            class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-indigo-600/50 hover:bg-indigo-800/50 text-indigo-400 transition-all whitespace-nowrap">
                                                            <span>⚙️</span>
                                                            <span>Matières</span>
                                                        </a>
                                                    @endif

                                                    {{-- Envoyer credentials --}}
                                                    @if (!$teacher->user->credentials_sent)
                                                        <button
                                                            title="Envoyer les données de connexion à {{ $teacher->getFullName() }}"
                                                            wire:click="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                            wire:loading.attr="disabled"
                                                            wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                            class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-sky-600/50 hover:bg-sky-800/50 text-sky-400 transition-all whitespace-nowrap disabled:opacity-50">
                                                            <span wire:loading.remove
                                                                wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                                class="inline-flex items-center gap-1.5">
                                                                <x-lucide-send class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Envoyer</span>
                                                            </span>
                                                            <span wire:loading
                                                                wire:target="sendCredentialsToTeacher('{{ $teacher->user->uuid }}')"
                                                                class="inline-flex items-center gap-1.5">
                                                                <x-lucide-refresh-ccw
                                                                    class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                <span>En cours...</span>
                                                            </span>
                                                        </button>
                                                    @endif

                                                    {{-- Bloquer / Débloquer --}}
                                                    <button
                                                        title="{{ $teacher->blocked ? 'Débloquer' : 'Bloquer' }} {{ $teacher->getFullName() }}"
                                                        wire:click="{{ $teacher->blocked ? 'unlockTeacher(' . $teacher->id . ')' : 'lockTeacher(' . $teacher->id . ')' }}"
                                                        wire:loading.attr="disabled"
                                                        wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $teacher->blocked ? 'bg-lime-600/50 hover:bg-lime-800/50 text-lime-400' : 'bg-amber-600/50 hover:bg-amber-800/50 text-amber-400' }}">
                                                        <span wire:loading.remove
                                                            wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                                            class="inline-flex items-center gap-1.5">
                                                            @if ($teacher->blocked)
                                                                <x-lucide-lock-keyhole-open
                                                                    class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Débloquer</span>
                                                            @else
                                                                <x-lucide-ban class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Bloquer</span>
                                                            @endif
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="lockTeacher({{ $teacher->id }}), unlockTeacher({{ $teacher->id }})"
                                                            class="inline-flex items-center gap-1.5">
                                                            <x-lucide-refresh-ccw
                                                                class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>

                                                    {{-- Accorder / Retirer accès année --}}
                                                    @if (!$teacher->deleted_at)
                                                        <button
                                                            title="{{ $teacher->hasValidAccessForYear() ? 'Retirer' : 'Accorder' }} l'accès à {{ $teacher->getFullName() }}"
                                                            wire:click="{{ $teacher->hasValidAccessForYear() ? 'removeAccessForThisSchoolYear(' . $teacher->id . ')' : 'giveAccessForThisSchoolYear(' . $teacher->id . ')' }}"
                                                            wire:loading.attr="disabled"
                                                            wire:target="giveAccessForThisSchoolYear({{ $teacher->id }}), removeAccessForThisSchoolYear({{ $teacher->id }})"
                                                            class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $teacher->hasValidAccessForYear() ? 'bg-orange-600/50 hover:bg-orange-800/50 text-orange-400' : 'bg-emerald-600/50 hover:bg-emerald-800/50 text-emerald-400' }}">
                                                            <span wire:loading.remove
                                                                wire:target="giveAccessForThisSchoolYear({{ $teacher->id }}), removeAccessForThisSchoolYear({{ $teacher->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                @if ($teacher->hasValidAccessForYear())
                                                                    <x-lucide-user-lock class="w-3.5 h-3.5 shrink-0" />
                                                                    <span>Retirer accès</span>
                                                                @else
                                                                    <x-lucide-key class="w-3.5 h-3.5 shrink-0" />
                                                                    <span>Accorder accès</span>
                                                                @endif
                                                            </span>
                                                            <span wire:loading
                                                                wire:target="giveAccessForThisSchoolYear({{ $teacher->id }}), removeAccessForThisSchoolYear({{ $teacher->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                <x-lucide-refresh-ccw
                                                                    class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                <span>En cours...</span>
                                                            </span>
                                                        </button>
                                                    @endif

                                                    {{-- Corbeille / Restaurer --}}
                                                    <button
                                                        title="{{ $teacher->deleted_at ? 'Restaurer' : 'Mettre en corbeille' }} {{ $teacher->getFullName() }}"
                                                        wire:click="{{ $teacher->deleted_at ? 'restoreTeacher(' . $teacher->id . ')' : 'deleteTeacher(' . $teacher->id . ')' }}"
                                                        wire:loading.attr="disabled"
                                                        wire:target="deleteTeacher({{ $teacher->id }}), restoreTeacher({{ $teacher->id }})"
                                                        class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $teacher->deleted_at ? 'bg-violet-600/50 hover:bg-violet-800/50 text-violet-400' : 'bg-rose-600/50 hover:bg-rose-800/50 text-rose-400' }}">
                                                        <span wire:loading.remove
                                                            wire:target="deleteTeacher({{ $teacher->id }}), restoreTeacher({{ $teacher->id }})"
                                                            class="inline-flex items-center gap-1.5">
                                                            @if ($teacher->deleted_at)
                                                                <x-lucide-recycle class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Restaurer</span>
                                                            @else
                                                                <x-lucide-trash class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Corbeille</span>
                                                            @endif
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="deleteTeacher({{ $teacher->id }}), restoreTeacher({{ $teacher->id }})"
                                                            class="inline-flex items-center gap-1.5">
                                                            <x-lucide-refresh-ccw
                                                                class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>

                                                    {{-- Supprimer définitivement --}}
                                                    @if ($teacher->deleted_at)
                                                        <button
                                                            title="Supprimer définitivement {{ $teacher->getFullName() }}"
                                                            wire:click="forceDeleteTeacher({{ $teacher->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="forceDeleteTeacher({{ $teacher->id }})"
                                                            class="inline-flex items-center justify-center gap-1.5 h-9 px-3 rounded-xl bg-red-600/50 hover:bg-red-800/50 text-red-400 transition-all whitespace-nowrap disabled:opacity-50">
                                                            <span wire:loading.remove
                                                                wire:target="forceDeleteTeacher({{ $teacher->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                <x-lucide-trash-2 class="w-3.5 h-3.5 shrink-0" />
                                                                <span>Suppr. déf.</span>
                                                            </span>
                                                            <span wire:loading
                                                                wire:target="forceDeleteTeacher({{ $teacher->id }})"
                                                                class="inline-flex items-center gap-1.5">
                                                                <x-lucide-refresh-ccw
                                                                    class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                <span>En cours...</span>
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
                            <div class="flex w-full itecn justify-center">
                                <div class="p-6 flex justify-center text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="text-4xl">🎯</span>
                                        <p class="text-slate-500 text-sm">Aucune enseignant trouvé </p>
                                        @if ($search || $status || $classe_id || $subject_id || $promotion_id || $filiar_id || $gender || $city || $department)
                                            <button wire:click="clearFilters"
                                                class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                                Réinitialiser les filtres
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- PAGINATION --}}
                    @if ($teachers->hasPages())
                        <section class="py-6">
                            <div class="flex justify-center bg-slate-900 p-4">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="text-sm text-slate-400">
                                        Affichage {{ $teachers->firstItem() }} à {{ $teachers->lastItem() }} sur
                                        {{ $teachers->total() }} enseignants
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if (!$teachers->onFirstPage())
                                            <button wire:click="previousPage" wire:loading.attr="disabled"
                                                wire:target="previousPage"
                                                class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                                Précédent
                                            </button>
                                        @endif

                                        @foreach ($teachers->getUrlRange(1, $teachers->lastPage()) as $page => $url)
                                            <button @disabled($page === $teachers->currentPage())
                                                wire:click="gotoPage({{ $page }})"
                                                class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $teachers->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                                {{ $page }}
                                            </button>
                                        @endforeach

                                        @if ($teachers->hasMorePages())
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
        <section class="my-4">
            <div class="space-y-6">

                {{-- QUICK STATS --}}
                <div
                    class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                    <h2 class="text-lg font-semibold">

                        Répartition Matières

                    </h2>

                    <div class="mt-5 space-y-5">

                        @foreach ([['Mathématiques', '82%', 'bg-indigo-500'], ['Physique', '70%', 'bg-emerald-500'], ['Informatique', '65%', 'bg-amber-500'], ['Français', '58%', 'bg-sky-500']] as $item)
                            <div>

                                <div class="flex items-center justify-between">

                                    <span class="text-sm text-slate-300">
                                        {{ $item[0] }}
                                    </span>

                                    <span class="text-sm font-semibold">
                                        {{ $item[1] }}
                                    </span>

                                </div>

                                <div
                                    class="mt-2 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                                    <div class="h-full rounded-full {{ $item[2] }}"
                                        style="width: {{ $item[1] }}">
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- RECENT ACTIVITY --}}
                <div
                    class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                    <h2 class="text-lg font-semibold">

                        Activités Récentes

                    </h2>

                    <div class="mt-5 space-y-4">

                        @foreach (range(1, 5) as $activity)
                            <div
                                class="rounded-2xl
                                        bg-slate-950
                                        p-4">

                                <div class="flex items-start gap-3">

                                    <div
                                        class="w-11 h-11 rounded-2xl
                                                bg-indigo-500/10
                                                shrink-0
                                                flex items-center justify-center
                                                text-indigo-400">

                                        ✓

                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="font-medium text-sm">

                                            Notes publiées

                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">

                                            M. Jean Kouassi a publié les notes de Terminale F2-1

                                        </p>

                                        <p class="mt-2 text-xs text-slate-500">

                                            Il y a 2 heures

                                        </p>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>
        </section>

    </div>

</div>

