<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8">

        <div class="flex flex-wrap items-center gap-3 p-3 bg-indigo-500/10 rounded-4xl my-1.5">

            <h1 class="text-lg sm:text-xl font-bold text-slate-400 px-3 py-2.5 uppercase">
                Promotion
                <span class="font-mono text-amber-400 font-semibold tracking-wider">
                    {{ $promotion->name . ' ' . $promotion->specialityModel()?->code }}
                </span>
            </h1>

            <span
                class="px-3 py-1 rounded-full 
                @if ($promotion->is_active) bg-emerald-500/10 text-emerald-400 
                @else bg-red-500/10 text-red-400 @endif text-xs">
                Promotion {{ $promotion->is_active ? 'active' : 'non active' }}
            </span>

        </div>
        <section class="mb-6">

            <div class="relative overflow-hidden rounded-[32px] border border-slate-800 bg-slate-900">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-slate-900 to-slate-900"></div>

                <div class="relative p-5 sm:p-6 lg:p-8">

                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-8">

                        {{-- LEFT --}}
                        <div class="flex flex-col lg:flex-row gap-6 min-w-0">

                            {{-- ICON --}}
                            <div class="flex justify-center lg:block">
                                <div
                                    class="w-32 h-32 sm:w-36 sm:h-36 rounded-[30px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-lg shrink-0 uppercase font-mono">
                                    {{ $promotion->code ?? cutter($promotion->name, 1) }}
                                </div>
                            </div>

                            {{-- INFOS --}}
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h1 class="text-2xl sm:text-3xl font-bold">
                                        {{ $promotion->name }}
                                        <span>{{ $promotion->specialityModel()?->code }}</span>
                                    </h1>
                                </div>

                                <p class="mt-2 text-slate-400">
                                    Tableau global des statistiques, performances de la promotion
                                    {{ $promotion->name }} <span>{{ $promotion->specialityModel()?->code }}</span>.
                                </p>

                                {{-- BADGES --}}
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <a href="{{ $promotion->toSpecialityProfilRoute() }}"
                                        class="px-6 py-2 rounded-2xl text-indigo-400 bg-indigo-800/20 border border-indigo-700 hover:text-orange-400 hover:bg-orange-800/20 hover:border-orange-700 transition-all">
                                        {{ $promotion->specialityModel()?->name }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </section>

        <section class="my-4 mb-5 flex justify-end">
            <div class="flex gap-3">
                <button class="py-3 px-5 rounded-2xl bg-blue-500 hover:bg-blue-800 ">
                    Ajouter une classe
                </button>

                <button class="py-3 px-5 rounded-2xl bg-emerald-500 hover:bg-emerald-600">
                    Export PDF
                </button>

                <a wire:navigate href="{{ route('tenant.promotion.edit', ['promotion_slug' => $promotion->slug]) }}"
                    class="py-3 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600">
                    Editer cette promotion
                </a>
            </div>
        </section>

        {{-- BEST / WORST --}}
        <section class="mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                {{-- BEST --}}
                <div class="rounded-[32px] bg-slate-900 border border-emerald-500/20 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-2xl">
                            🏆</div>
                        <div>
                            <h2 class="text-xl font-semibold">Meilleure Performance</h2>
                            <p class="text-slate-400">Plus forte moyenne enregistrée.</p>
                        </div>
                    </div>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                            <h3 class="text-lg font-semibold">KOUASSI Sarah</h3>
                            <p class="mt-2 text-slate-400">Classe : Terminale F4-1</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs">Moyenne
                                    : 19.75</span>
                                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">Promotion
                                    : Terminale</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- WORST --}}
                <div class="rounded-[32px] bg-slate-900 border border-rose-500/20 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-rose-500/10 flex items-center justify-center text-2xl">⚠️
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold">Plus Faible Performance</h2>
                            <p class="text-slate-400">Plus faible moyenne enregistrée.</p>
                        </div>
                    </div>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-950 border border-slate-800 p-5">
                            <h3 class="text-lg font-semibold">HOUNKPE David</h3>
                            <p class="mt-2 text-slate-400">Classe : Tle F4-2</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <span class="px-3 py-1 rounded-full bg-rose-500/10 text-rose-400 text-xs">Moyenne :
                                    02.15</span>
                                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">Promotion
                                    : Terminale F4</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- BEST BOY / BEST GIRL --}}
        <section class="mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- MEILLEUR GARÇON --}}
                <div class="rounded-[32px] bg-slate-900 border border-sky-500/20 overflow-hidden">
                    <div class="p-6 border-b border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-sky-500/10 flex items-center justify-center text-2xl">
                                🏅</div>
                            <div>
                                <h2 class="text-xl font-semibold">Meilleur Garçon</h2>
                                <p class="mt-1 text-sm text-slate-400">Meilleure performance masculine dans la matière.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                            <div class="flex justify-center lg:block">
                                <div class="w-28 h-28 rounded-[28px] bg-slate-800 border border-slate-700 shrink-0">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-2xl font-bold">HOUNKPE David</h3>
                                    <span class="px-3 py-1 rounded-full bg-sky-500/10 text-sky-400 text-xs">Rang #1
                                        Garçon</span>
                                </div>
                                <p class="mt-2 text-slate-400">Terminale F4-1 — Promotion Terminale</p>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Moyenne :
                                        18.92</div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Coef : 4
                                    </div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Prof : M.
                                        AHOLOU</div>
                                </div>
                                <div class="mt-6 flex flex-wrap gap-3">
                                    <button class="h-11 px-5 rounded-2xl bg-sky-500 hover:bg-sky-600">Voir
                                        Profil</button>
                                    <button class="h-11 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600">Historique
                                        Notes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MEILLEURE FILLE --}}
                <div class="rounded-[32px] bg-slate-900 border border-pink-500/20 overflow-hidden">
                    <div class="p-6 border-b border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-pink-500/10 flex items-center justify-center text-2xl">
                                👑</div>
                            <div>
                                <h2 class="text-xl font-semibold">Meilleure Fille</h2>
                                <p class="mt-1 text-sm text-slate-400">Meilleure performance féminine dans la matière.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                            <div class="flex justify-center lg:block">
                                <div class="w-28 h-28 rounded-[28px] bg-slate-800 border border-slate-700 shrink-0">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-2xl font-bold">KOUASSI Sarah</h3>
                                    <span class="px-3 py-1 rounded-full bg-pink-500/10 text-pink-400 text-xs">Rang #1
                                        Fille</span>
                                </div>
                                <p class="mt-2 text-slate-400">Terminale F4-2 — Promotion Terminale</p>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Moyenne :
                                        19.41</div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Coef : 4
                                    </div>
                                    <div class="px-4 py-2 rounded-2xl bg-slate-950 border border-slate-800">Prof : Mme
                                        ADJOVI</div>
                                </div>
                                <div class="mt-6 flex flex-wrap gap-3">
                                    <button class="h-11 px-5 rounded-2xl bg-pink-500 hover:bg-pink-600">Voir
                                        Profil</button>
                                    <button class="h-11 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600">Historique
                                        Notes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <div>
            <section class="mb-6">

                <div
                    class="rounded-tr-2xl rounded-tl-2xl
                        bg-slate-900
                        border border-slate-800
                        overflow-hidden">

                    <section class="mb-6">
                        <div wire:loading
                            wire:target='classe_id,gender,filiar_id,search,previousPage,nextPage,resetFilters,gotoPage'
                            class="fixed inset-0 flex items-center justify-center bg-slate-800/10 backdrop-blur-sm"
                            style="z-index: 200 !important;">

                            <div
                                class="items-center gap-1 text-slate-400 relative top-1/2 mx-auto flex justify-center flex-col">
                                <svg class="animate-spin w-10 h-10" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                <span class="text-xl font-mono ls-1">Chargement en cours...</span>
                            </div>
                        </div>

                        <div class="bg-slate-900 p-4 sm:p-5">
                            <div class="flex flex-col gap-4">
                                <div class="grid grid-cols-7 gap-x-3">
                                    <div class="relative col-span-5">

                                        <input wire:model.live='search' type="text"
                                            placeholder="Rechercher un enseignant..."
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
                                        <span wire:loading wire:target='resetFilters'
                                            class="inline-flex items-center gap-x-2">
                                            <span class="inline-flex items-center gap-x-2">
                                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                                                <span>Rechargement ...</span>
                                            </span>
                                        </span>

                                    </button>
                                </div>

                                <div class="flex items-center flex-wrap gap-3">

                                    <select wire:model.live='filiar_id'
                                        class="h-12 min-w-[220px] rounded-2xl bg-slate-950 border border-slate-800 px-4 text-sm uppercase font-mono">
                                        <option value="">Toutes les filières</option>
                                        @foreach ($this->filiars as $f)
                                            <option value="{{ $f->id }}">
                                                Filière {{ $f->code ? $f->code : $f->name }}
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

                                                        <p
                                                            class="mt-1 text-sm text-slate-400 flex items-center gap-x-1.5">
                                                            <x-lucide-mail class="w-3.5 h-3.5" />
                                                            <span>
                                                                {{ $teacher->user->email }}
                                                            </span>

                                                        </p>
                                                    </div>

                                                </a>
                                                @if (!$teacher->hasValidAccessForYear())
                                                    <span
                                                        class="px-3 rounded-full bg-red-500/10 text-red-400 animate-pulse border border-slate-600 w-full flex text-xs py-1 mt-2 text-center items-center justify-center gap-x-1">
                                                        <span>Accès
                                                            {{ tenancy()->tenant?->getActiveSchoolYear()?->slug }}</span>

                                                        <span> non accordé</span>
                                                    </span>
                                                @endif

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
                                        @if ($search || $status || $filiar_id || $gender)
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
                                        Affichage {{ $this->teachers->firstItem() }} à
                                        {{ $this->teachers->lastItem() }} sur
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

            </section>
        </div>

    </div>
</div>

