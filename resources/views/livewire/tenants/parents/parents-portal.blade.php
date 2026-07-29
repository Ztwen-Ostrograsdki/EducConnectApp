<div class="w-full overflow-x-hidden bg-slate-950 p-3">

    {{-- ===================================================== --}}
    {{-- GLOBAL CONTAINER --}}
    {{-- ===================================================== --}}
    <div
        class="mx-auto
                w-full
                max-w-[1850px]
                px-3
                sm:px-4
                lg:px-6
                xl:px-8">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6 flex justify-between items-center">

            <div
                class="flex flex-col
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                        gap-5 w-full">

                {{-- LEFT --}}
                <div class="min-w-0">

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl sm:text-3xl font-bold">

                            Parents d'Élèves

                        </h1>

                        <span
                            class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400
                                     text-xs">

                            {{ $this->tutors->total() }} Parents

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm sm:text-base">

                        Gestion centralisée des représentants légaux des apprenants

                    </p>

                </div>
                <div>
                    <a class="flex items-center gap-3 bg-primary-700/35 text-white hover:text-black hover:bg-primary-500 px-3 py-3 rounded-2xl active:scale-95"
                        href="{{ route('tenant.parents.create') }}">
                        <x-lucide-user-plus class="w-4 h-4" />
                        <span> Créer des comptes parents</span>
                    </a>
                </div>
            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- FILTERS --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div
                class="rounded-3xl
                        border border-slate-800
                        bg-slate-950
                        p-4 sm:p-5">

                <div class="flex flex-col gap-4">

                    <section class="mb-6">

                        <div class="rounded-3xl border border-slate-800  bg-slate-950 p-4 sm:p-5">
                            <div class="flex flex-col gap-4">
                                <div class="grid grid-cols-7 gap-x-3">
                                    <div class="relative col-span-5">

                                        <input wire:model.live.debounce.600ms='search' type="text"
                                            placeholder="Trouver un parent..."
                                            class="w-full h-12 rounded-2xl bg-slate-950 border border-slate-800 pl-12 pr-4 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
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
                                        <span wire:loading wire:target='clearFilters'
                                            class="inline-flex items-center gap-x-2">
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
                                        <option value="">Toutes les séries</option>
                                        @foreach ($this->serials as $s)
                                            <option value="{{ $s->id }}">
                                                La série {{ $s->code ? $s->code : $s->name }}
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

                </div>

            </div>

        </section>

        <section class="rounded-2xl border border-slate-800 relative">
            <div wire:loading
                wire:target='gender,status,department,city,clearFilters,classe_id,promotion_id,filiar_id,serial_id,search,previousPage,nextPage,gotoPage'
                class="absolute inset-0 flex items-center justify-center bg-slate-800/10 backdrop-blur-xs rounded-2xl"
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

            <div class="flex flex-col gap-3 p-2">

                <div
                    class="flex justify-end flex-wrap gap-3 border-b border-b-slate-800 text-gray-950 py-2 font-mono md:text-sm text-xs">

                    <button title="Désactiver tous les comptes parents actifs" wire:click="desactivateTutors"
                        wire:loading.attr="disabled" wire:target="desactivateTutors"
                        class="inline-flex items-center justify-center gap-1.5 p-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 bg-orange-600/45 text-white hover:text-black hover:bg-orange-500 active:scale-95">
                        <span wire:loading.remove wire:target="desactivateTutors"
                            class="inline-flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-ban class="w-3.5 h-3.5 shrink-0" />
                                <span>Désactiver tous les comptes</span>
                            </span>
                        </span>
                        <span wire:loading wire:target="desactivateTutors" class="inline-flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>

                    <button title="Réactiver tous les comptes parents non actifs" wire:click="activateTutors"
                        wire:loading.attr="disabled" wire:target="activateTutors"
                        class="inline-flex items-center justify-center gap-1.5 p-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 bg-green-600/45 text-white hover:text-black hover:bg-green-500 active:scale-95">
                        <span wire:loading.remove wire:target="activateTutors"
                            class="inline-flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-user-check class="w-3.5 h-3.5 shrink-0" />
                                <span>Réactiver tous les comptes</span>
                            </span>
                        </span>
                        <span wire:loading wire:target="activateTutors" class="inline-flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>

                    <button title="Restorer tous les comptes parents de la corbeille" wire:click="restoreTutors"
                        wire:loading.attr="disabled" wire:target="restoreTutors"
                        class="inline-flex items-center justify-center gap-1.5 p-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 bg-purple-600/45 text-white hover:text-black hover:bg-purple-500 active:scale-95">
                        <span wire:loading.remove wire:target="restoreTutors"
                            class="inline-flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-recycle class="w-3.5 h-3.5 shrink-0" />
                                <span>Restorer tous les comptes</span>
                            </span>
                        </span>
                        <span wire:loading wire:target="restoreTutors" class="inline-flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>

                    <button title="Supprimer définitivement tous les comptes parents de la corbeille"
                        wire:click="forceDeleteTutors" wire:loading.attr="disabled" wire:target="forceDeleteTutors"
                        class="inline-flex items-center justify-center gap-1.5 p-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 bg-red-600/45 text-white hover:text-black hover:bg-red-500 active:scale-95">
                        <span wire:loading.remove wire:target="forceDeleteTutors"
                            class="inline-flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-user-x class="w-3.5 h-3.5 shrink-0" />
                                <span>Suppr. déf. tous les comptes</span>
                            </span>
                        </span>
                        <span wire:loading wire:target="forceDeleteTutors" class="inline-flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-refresh-ccw class="w-3.5 h-3.5 animate-spin shrink-0" />
                                <span>En cours...</span>
                            </span>
                        </span>
                    </button>

                </div>

                <div class="grid
                        grid-cols-1
                        gap-6">

                    <div class="space-y-6 min-w-0 col-span-1">

                        {{-- TABLE --}}
                        <div
                            class="rounded-3xl
                                
                                bg-slate-950
                                overflow-hidden">

                            {{-- HEADER --}}
                            <div class="border-b border-slate-800
                                    p-4 sm:p-6">

                                <div
                                    class="flex flex-col
                                        lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                        gap-4">

                                    <div>

                                        <h2 class="text-lg sm:text-xl font-semibold">

                                            Liste des Parents

                                        </h2>

                                        <p class="mt-1 text-sm text-slate-400">

                                            Gestion des accès et suivi des représentants

                                        </p>

                                    </div>

                                </div>

                            </div>

                            @if (count($this->tutors) > 0)
                                <div class="overflow-x-auto my-3 font-mono text-slate-500">

                                    <table class="z-table-border w-full mb-4">

                                        <thead class="bg-slate-950 border-b border-slate-800">

                                            <tr>
                                                <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                    N°
                                                </th>

                                                <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                    Parent
                                                </th>

                                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                    Enfant(s)
                                                </th>
                                                <th class="px-4 py-4 text-center text-sm text-slate-400">
                                                    Statut
                                                </th>

                                                <th class="px-6 py-4 text-center text-sm text-slate-400">
                                                    Actions
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody class="divide-y divide-slate-800">

                                            @foreach ($this->tutors as $parent)
                                                <tr class="hover:bg-slate-800/40 transition-all">

                                                    <td class="px-4 py-5 text-center">

                                                        {{ $this->tutors->firstItem() + $loop->iteration - 1 }}

                                                    </td>

                                                    {{-- PROFILE --}}
                                                    <td class="px-6 py-5">

                                                        <div class="w-full flex flex-col gap-2">
                                                            <a title="Charger le profil de ce parent"
                                                                href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent->uuid]) }}"
                                                                class="flex items-center gap-4 hover:bg-slate-950 p-2 rounded-2xl group">

                                                                <img src="{{ $parent->profil_photo_url() }}"
                                                                    alt="Photo de profil de {{ $parent->fullName() }}"
                                                                    class="w-14 h-14 rounded-full object-cover border-4 border-slate-700 group-hover:border-sky-500">

                                                                <div
                                                                    class="min-w-0 group-hover:text-sky-600 flex flex-col">
                                                                    <h3 class="font-medium truncate">
                                                                        {{ $parent->getFullName() }}
                                                                    </h3>
                                                                </div>

                                                            </a>
                                                            <div class="flex flex-col gap-2">
                                                                <p
                                                                    class="mt-1 text-sm text-sky-400 truncate inline-flex items-center gap-2">
                                                                    <x-lucide-mail class="w-4 h-4 text-slate-200" />
                                                                    {{ $parent->user->email }}
                                                                </p>
                                                                <p
                                                                    class="mt-1 text-sm text-slate-400 truncate inline-flex items-center gap-2">
                                                                    <x-lucide-briefcase-business
                                                                        class="w-4 h-4 text-slate-200" />
                                                                    {{ $parent->user->job_name ? $parent->user->job_name : '' }}
                                                                </p>

                                                                <p
                                                                    class="mt-1 text-sm text-amber-400 inline-flex items-center gap-2">
                                                                    <x-lucide-map-pin-check
                                                                        class="w-4 h-4 text-slate-200" />
                                                                    {{ $parent->user->adresse }}
                                                                </p>
                                                                <p class="inline-flex items-center gap-2">
                                                                    <x-lucide-phone class="w-4 h-4 text-slate-200" />
                                                                    {{ $parent->user->contacts }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                    </td>

                                                    {{-- CHILDREN --}}
                                                    <td class="px-4 py-5 truncate">

                                                        <div class="flex justify-center  flex-col gap-3 text-center">
                                                            @if (count($parent->myChildren))
                                                                <span
                                                                    class="text-center rounded-2xl p-0.5 border border-slate-600">
                                                                    {{ count($parent->myChildren) }} apprenant(s)
                                                                </span>
                                                                @foreach ($parent->myChildren()->take(2)->get() as $rel)
                                                                    @php
                                                                        $student = $rel->student;
                                                                    @endphp
                                                                    <a wire:navigate
                                                                        href="{{ route('tenant.student.profil', ['student_uuid' => $student->uuid]) }}"
                                                                        class="px-3 py-2 flex rounded-xl bg-slate-950 text-sm hover:bg-gray-900 border border-slate-950 hover:border-sky-600 active:scale-95 flex-col items-center justify-center">

                                                                        <span>{{ $student->getFullName() }}</span>

                                                                        @if ($student->currentClasse() && $student->currentClasse()->classe)
                                                                            @php
                                                                                $r = $student->currentClasse()->classe;
                                                                            @endphp
                                                                            <span
                                                                                class="p-2 rounded-xl border border-orange-500 my-1">{{ $r->code ? $r->code : $r->name }}</span>
                                                                        @else
                                                                            <span
                                                                                class="inline-flex gap-1 justify-center text-xs text-orange-600/50 group-hover:text-orange-700 group-hover:bg-slate-950 group-hover:rounded-2xl p-1">
                                                                                <span>Pas encore de classe en
                                                                                    {{ $this->activeYear?->slug }}</span>
                                                                            </span>
                                                                        @endif

                                                                    </a>
                                                                @endforeach
                                                                @if (count($parent->myChildren) > 2)
                                                                    <a href="{{ route('tenant.parent.profil', ['parent_uuid' => $parent->uuid]) }}"
                                                                        class="items-center gap-4 hover:bg-indigo-800 p-2 rounded-2xl active:scale-95 text-2xs hover:text-black text-center bg-indigo-800/40 flex justify-center">
                                                                        <span>Voir le reste des apprenants</span>
                                                                        <x-lucide-chevron-down class="w-4 h-4" />
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <span
                                                                    class="text-slate-500 font-mono text-center animate-pulse">Aucun
                                                                    apprenant
                                                                    lié</span>
                                                            @endif
                                                        </div>

                                                    </td>

                                                    {{-- STATUS --}}
                                                    <td class="px-4 py-5 text-center">

                                                        @if ($parent->is_active)
                                                            <span
                                                                class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                                Actif

                                                            </span>
                                                        @else
                                                            <span
                                                                class="px-3 py-1 rounded-full
                                                         bg-red-500/10
                                                         text-red-400 text-sm">

                                                                Bloqué

                                                            </span>
                                                        @endif

                                                    </td>

                                                    <td class="px-6 py-5 truncate">
                                                        <div class="flex items-center gap-2 text-xs">
                                                            <a wire:navigate
                                                                href="{{ route('tenant.parents.manage.relations', ['parent_uuid' => $parent->uuid]) }}"
                                                                class="px-3 py-3 rounded-2xl
                                           bg-indigo-500/40 hover:bg-indigo-400 hover:text-black active:scale-95 text-center text-white">

                                                                Apprenants associés

                                                            </a>

                                                            @if (!$parent->user->credentials_sent)
                                                                <button
                                                                    title="Envoyer les données de connexion à {{ $parent->getFullName() }}"
                                                                    wire:click="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                                    class="inline-flex items-center justify-center gap-1.5 py-3 px-3 rounded-xl bg-sky-600/50 hover:bg-sky-800/50 text-sky-400 transition-all whitespace-nowrap disabled:opacity-50">
                                                                    <span wire:loading.remove
                                                                        wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                                        class="inline-flex items-center gap-1.5">
                                                                        <span class="flex items-center gap-x-3">
                                                                            <x-lucide-send
                                                                                class="w-3.5 h-3.5 shrink-0" />
                                                                            <span>Envoyer</span>
                                                                        </span>
                                                                    </span>
                                                                    <span wire:loading
                                                                        wire:target="sendCredentialsToTutor('{{ $parent->user->uuid }}')"
                                                                        class="inline-flex items-center gap-1.5">
                                                                        <span class="flex items-center gap-x-2">
                                                                            <span class="flex items-center gap-x-2">
                                                                                <x-lucide-refresh-ccw
                                                                                    class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                                <span>En cours...</span>
                                                                            </span>
                                                                        </span>
                                                                    </span>
                                                                </button>
                                                            @endif

                                                            <button
                                                                title="{{ !$parent->is_active ? 'Débloquer' : 'Bloquer' }} {{ $parent->getFullName() }}"
                                                                wire:click="{{ !$parent->is_active ? 'activateTutor(' . $parent->id . ')' : 'desactivateTutor(' . $parent->id . ')' }}"
                                                                wire:loading.attr="disabled"
                                                                wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                                                class="inline-flex items-center justify-center gap-1.5 py-3 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ !$parent->is_active ? 'bg-lime-600/50 hover:bg-lime-800/50 text-lime-400' : 'bg-amber-600/50 hover:bg-amber-800/50 text-amber-400' }}">
                                                                <span wire:loading.remove
                                                                    wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                                                    class="inline-flex items-center gap-1.5">
                                                                    @if (!$parent->is_active)
                                                                        <x-lucide-lock-keyhole-open
                                                                            class="w-3.5 h-3.5 shrink-0" />
                                                                        <span>Activer</span>
                                                                    @else
                                                                        <x-lucide-ban class="w-3.5 h-3.5 shrink-0" />
                                                                        <span>Désactiver</span>
                                                                    @endif
                                                                </span>
                                                                <span wire:loading
                                                                    wire:target="desactivateTutor({{ $parent->id }}), activateTutor({{ $parent->id }})"
                                                                    class="inline-flex items-center gap-1.5">
                                                                    <span class="flex items-center gap-x-2">
                                                                        <span class="flex items-center gap-x-2">
                                                                            <x-lucide-refresh-ccw
                                                                                class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                            <span>En cours...</span>
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                            </button>

                                                            <button
                                                                title="{{ $parent->deleted_at ? 'Restorer' : 'Mettre en corbeille' }} {{ $parent->getFullName() }}"
                                                                wire:click="{{ $parent->deleted_at ? 'restoreTutor(' . $parent->id . ')' : 'deleteTutor(' . $parent->id . ')' }}"
                                                                wire:loading.attr="disabled"
                                                                wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                                                class="inline-flex items-center justify-center gap-1.5 py-3 px-3 rounded-xl transition-all whitespace-nowrap disabled:opacity-50 {{ $parent->deleted_at ? 'bg-violet-600/50 hover:bg-violet-800/50 text-violet-400' : 'bg-rose-600/50 hover:bg-rose-800/50 text-rose-400' }}">
                                                                <span wire:loading.remove
                                                                    wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                                                    class="inline-flex items-center gap-1.5">
                                                                    @if ($parent->deleted_at)
                                                                        <x-lucide-recycle
                                                                            class="w-3.5 h-3.5 shrink-0" />
                                                                        <span>Restaurer</span>
                                                                    @else
                                                                        <x-lucide-trash class="w-3.5 h-3.5 shrink-0" />
                                                                        <span>Corbeille</span>
                                                                    @endif
                                                                </span>
                                                                <span wire:loading
                                                                    wire:target="deleteTutor({{ $parent->id }}), restoreTutor({{ $parent->id }})"
                                                                    class="inline-flex items-center gap-1.5">
                                                                    <span class="flex items-center gap-x-2">
                                                                        <x-lucide-refresh-ccw
                                                                            class="w-3.5 h-3.5 animate-spin shrink-0" />
                                                                        <span>En cours...</span>
                                                                    </span>
                                                                </span>
                                                            </button>

                                                            @if ($parent->deleted_at)
                                                                <button
                                                                    title="Supprimer définitivement {{ $parent->getFullName() }}"
                                                                    wire:click="forceDeleteTutor({{ $parent->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="forceDeleteTutor({{ $parent->id }})"
                                                                    class="inline-flex items-center justify-center gap-1.5 py-3 px-3 rounded-xl bg-red-600/50 hover:bg-red-800/50 text-red-400 transition-all whitespace-nowrap disabled:opacity-50">
                                                                    <span wire:loading.remove
                                                                        wire:target="forceDeleteTutor({{ $parent->id }})"
                                                                        class="inline-flex items-center gap-1.5">
                                                                        <x-lucide-trash-2
                                                                            class="w-3.5 h-3.5 shrink-0" />
                                                                        <span>Suppr. déf.</span>
                                                                    </span>
                                                                    <span wire:loading
                                                                        wire:target="forceDeleteTutor({{ $parent->id }})"
                                                                        class="inline-flex items-center gap-1.5">
                                                                        <span class="flex items-center gap-x-2">
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

                                </div>
                            @else
                                <div class="flex w-full itecn justify-center">
                                    <div class="p-6 flex justify-center text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="text-4xl">🎯</span>
                                            <p class="text-slate-500 text-sm">Aucun parent ou tuteur trouvé </p>
                                            @if ($search || $status || $classe_id || $serial_id || $promotion_id || $filiar_id || $gender || $city || $department)
                                                <button wire:click="clearFilters"
                                                    class="mt-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm transition">
                                                    Recharger les données
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($this->tutors->hasPages())
                                <section class="py-6 p-2 font-mono">
                                    <div class="flex justify-center bg-transparent p-4">
                                        <div class="flex flex-col items-center gap-4">
                                            <div class="text-sm text-slate-400">
                                                Affichage {{ $this->tutors->firstItem() }} à
                                                {{ $this->tutors->lastItem() }}
                                                sur
                                                {{ $this->tutors->total() }} parents/tuteurs
                                            </div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                @if (!$this->tutors->onFirstPage())
                                                    <button wire:click="previousPage" wire:loading.attr="disabled"
                                                        wire:target="previousPage"
                                                        class="h-10 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 transition-all text-sm disabled:opacity-50">
                                                        Précédent
                                                    </button>
                                                @endif

                                                @foreach ($this->tutors->getUrlRange(1, $this->tutors->lastPage()) as $page => $url)
                                                    <button @disabled($page === $this->tutors->currentPage())
                                                        wire:click="gotoPage({{ $page }})"
                                                        class="h-10 px-4 rounded-xl text-sm transition-all {{ $page === $this->tutors->currentPage() ? 'bg-indigo-500 text-white' : 'bg-slate-800 hover:bg-slate-700' }}">
                                                        {{ $page }}
                                                    </button>
                                                @endforeach

                                                @if ($this->tutors->hasMorePages())
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

</div>

