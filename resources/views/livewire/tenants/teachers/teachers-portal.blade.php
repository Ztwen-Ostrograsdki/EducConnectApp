<div class="w-full overflow-x-hidden p-2">

    {{-- ===================================================== --}}
    {{-- GLOBAL CONTAINER --}}
    {{-- ===================================================== --}}
    <div class="mx-auto
                w-full
                max-w-[1850px]
                px-3
                sm:px-3
                lg:px-6
                xl:px-8">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="flex flex-col
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                        gap-5">

                {{-- LEFT --}}
                <div class="min-w-0">

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl sm:text-3xl font-bold">

                            Enseignants

                        </h1>

                        <span class="px-3 py-1 rounded-full
                                     bg-indigo-500/10
                                     text-indigo-400
                                     text-xs">

                            {{ __zero($allTeachersCounter) }} Enseignants

                        </span>

                    </div>

                    <p class="mt-2 text-slate-400 text-sm sm:text-base">

                        Vue globale du personnel enseignant de l’établissement

                    </p>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-wrap items-center gap-3">

                    <button class="h-11 px-5 rounded-2xl
                                   bg-slate-800
                                   hover:bg-slate-700
                                   transition-all text-sm">

                        Exporter

                    </button>

                    <a href="{{ route('tenant.teachers.create') }}"
                        class="py-2.5 px-5 rounded-2xl
                                   bg-indigo-500
                                   hover:bg-indigo-600
                                   transition-all text-sm">
                        Ajouter Enseignant

                    </a>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- KPI --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="grid
                        grid-cols-2
                        xl:grid-cols-4
                        gap-4">

                @foreach ([['Total', __zero($allTeachersCounter), 'text-indigo-400'], ['Actifs', __zero($activesTeachersCounter), 'text-emerald-400'], ['Taux Présence', '96%', 'text-amber-400']] as $kpi)
                    <div class="rounded-3xl
                            border border-slate-800
                            bg-slate-900
                            p-4 sm:p-5">

                        <p class="text-xs sm:text-sm text-slate-400">
                            {{ $kpi[0] }}
                        </p>

                        <h2 class="mt-3
                               text-2xl sm:text-3xl xl:text-4xl
                               font-bold {{ $kpi[2] }}">

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

            <div class="rounded-3xl
                        border border-slate-800
                        bg-slate-900
                        p-4 sm:p-5">

                <div class="flex flex-col gap-4">

                    {{-- SEARCH --}}
                    <div class="relative">

                        <input wire:model.live='search' type="text" placeholder="Rechercher un enseignant..."
                            class="w-full h-12 rounded-2xl
                                   bg-slate-950
                                   border border-slate-800
                                   pl-12 pr-4
                                   text-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-500/40">

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">

                            🔍

                        </div>

                    </div>

                    {{-- FILTERS --}}
                    <div class="grid
                                grid-cols-1
                                sm:grid-cols-2
                                xl:grid-cols-5
                                gap-3">

                        <select class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes matières</option>
                            <option>Mathématiques</option>
                            <option>Physique</option>

                        </select>

                        <select class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Toutes classes</option>
                            <option>Terminale F2-1</option>

                        </select>

                        <select class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Sexe</option>
                            <option>Masculin</option>
                            <option>Féminin</option>

                        </select>

                        <select class="h-11 px-3 rounded-2xl
                                       bg-slate-950
                                       border border-slate-800
                                       text-sm">

                            <option>Statut</option>
                            <option>Actif</option>
                            <option>Suspendu</option>

                        </select>

                        <button wire:click='clearFilters' class="h-11 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-all text-sm">
                            <span wire:loading.remove wire:target='clearFilters' class="inline-flex gap-x-2 items-center">
                                <x-lucide-brush-cleaning class="w-4 h-4" />
                                Réinitialiser
                            </span>
                            <span wire:loading wire:target='clearFilters' class="inline-flex items-center gap-x-2">
                                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            </span>

                        </button>

                    </div>

                </div>

            </div>

        </section>

        {{-- ===================================================== --}}
        {{-- MAIN GRID --}}
        {{-- ===================================================== --}}
        <section>

            <div class="space-y-6 min-w-0">

                {{-- TEACHERS TABLE --}}
                <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                overflow-hidden">

                    {{-- HEADER --}}
                    <div class="border-b border-slate-800
                                    p-4 sm:p-6">

                        <div class="flex flex-col gap-y-3">
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold">

                                    Liste des Enseignants

                                </h2>

                                <p class="mt-1 text-sm text-slate-400">

                                    Gestion et suivi du personnel

                                </p>

                            </div>

                            <div class="flex w-full justify-end">
                                <div class="flex items-center gap-3 text-sm w-4/6 justify-items-end">
                                    <button wire:key="del-tenant-" wire:click="deleteTenant('')" wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="deleteTenant">
                                            <x-lucide-ban class="w-4 h-4" />
                                            Bloquer
                                        </span>
                                        <span wire:loading.flex wire:target="deleteTenant" class="items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>
                                    <x-confirm-modal wire:key="confirm-del-tenant-" :show="$showConfirmDeleteModal" title="Placer le tenant dans la Corbeille" confirm-text="Oui, placer dans la corbeille" cancel-text="Annuler"
                                        confirm-action="ConfirmSchoolDeletion" close-action="closeModal">
                                        <p>Cette action entrainera : </p>
                                        <ul class="text-green-500 text-xs">
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La mise en corbeille du tenant</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La suppression surperficielle de l'école</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>L'innacessiblité au domaine par le directeur et les autres users du domaine</span>
                                            </li>
                                        </ul>
                                    </x-confirm-modal>

                                    {{-- DELETE --}}

                                    <button wire:key="del-tenant-" wire:click="deleteTenant('')" wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="deleteTenant">
                                            <x-lucide-trash class="w-4 h-4" />
                                            Corbeille
                                        </span>
                                        <span wire:loading.flex wire:target="deleteTenant" class="items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>
                                    <x-confirm-modal wire:key="confirm-del-tenant-" :show="$showConfirmDeleteModal" title="Placer le tenant dans la Corbeille" confirm-text="Oui, placer dans la corbeille" cancel-text="Annuler"
                                        confirm-action="ConfirmSchoolDeletion" close-action="closeModal">
                                        <p>Cette action entrainera : </p>
                                        <ul class="text-green-500 text-xs">
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La mise en corbeille du tenant</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La suppression surperficielle de l'école</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>L'innacessiblité au domaine par le directeur et les autres users du domaine</span>
                                            </li>
                                        </ul>
                                    </x-confirm-modal>
                                    <button wire:key="restore-tenant-" wire:click="restoreTenant('')" wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-purple-500/10 hover:bg-purple-500/20 text-purple-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="restoreTenant">
                                            <x-lucide-recycle class="w-4 h-4" />
                                            Restaurer
                                        </span>
                                        <span wire:loading.flex wire:target="restoreTenant" class="items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>
                                    <x-confirm-modal wire:key="confirm-restore-tenant-" :show="$showConfirmRestorationModal" title="restauration du tenant" confirm-text="Oui, restaurer le tenant" cancel-text="Annuler"
                                        confirm-action="ConfirmSchoolRestoration" close-action="closeModal">
                                        <p>Cette action entrainera : </p>
                                        <ul class="text-green-500 text-xs">
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La restauration du tenant</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La restauration de l'école</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>L'acessiblité au domaine par le directeur et les autres users du domaine</span>
                                            </li>
                                        </ul>
                                    </x-confirm-modal>

                                    <button wire:key="force-del-tenant-" wire:click="forceDelete('')" wire:loading.attr="disabled"
                                        class="h-11 rounded-2xl col-span-3 flex items-center flex-1 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="forceDelete">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                            Supprimer déf.
                                        </span>
                                        <span wire:loading.flex wire:target="forceDelete" class="items-center gap-1.5">
                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                            <span>En cours...</span>
                                        </span>
                                    </button>
                                    <x-confirm-modal wire:key="confirm-force-del-tenant-" :show="$showConfirmForceDeleteModal" title="Suppression définitive du tenant " confirm-text="Oui, Supprimer Déf." cancel-text="Annuler"
                                        confirm-action="ConfirmSchoolForceDelete" close-action="closeModal">
                                        <p>Cette action entrainera : </p>
                                        <ul class="text-green-500 text-xs">
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La suppresion définitive du tenant</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La suppresion définitive de l'école</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La suppression définitive de la base de données ainsi que les données stockées dans cette base</span>
                                            </li>
                                            <li class="flex items-center gap-x-1">
                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                <span>La suppresion du domaine</span>
                                            </li>
                                        </ul>
                                    </x-confirm-modal>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-x-auto">

                        <table class="min-w-[1200px] w-full">

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

                                    <th class="px-3 py-4 text-center text-sm text-slate-400">
                                        Statut
                                    </th>

                                    <th class="px-6 py-4 text-center text-sm text-slate-400">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-800">

                                @foreach ($teachers as $teacher)
                                    <tr wire:key='liste-enseignants-du-portail-'{{ $teacher->id }} class="hover:bg-slate-800/40 transition-all">
                                        <td class="px-3 py-5 text-center whitespace-nowrap">

                                            {{ __zero($loop->iteration) }}

                                        </td>

                                        {{-- PROFILE --}}
                                        <td class="px-6 py-5">

                                            <a title="Charger le profil de l'enseignant {{ $teacher->user->getFullName() }}" href="{{ route('tenant.teacher.profil', ['teacher_uuid' => $teacher->uuid]) }}"
                                                class="flex items-center gap-4 hover:text-sky-500 hover:underline">

                                                <img src="{{ $teacher->user->profil_photo_url }}" alt="" class="w-14 h-14 rounded-full object-cover border-4 border-slate-700">
                                                <div class="min-w-0">

                                                    <h3 class="font-medium truncate">

                                                        {{ $teacher->user->getFullName() }}

                                                    </h3>

                                                    <p class="mt-1 text-sm text-slate-400 truncate flex items-center gap-x-1.5">
                                                        <x-lucide-mail class="w-3.5 h-3.5" />
                                                        <span>
                                                            {{ $teacher->user->email }}
                                                        </span>

                                                    </p>
                                                    <p class="mt-1 text-sm text-slate-400 truncate font-mono flex items-center gap-x-1.5">

                                                        <x-lucide-phone class="w-3.5 h-3.5" />
                                                        <span>
                                                            {{ $teacher->user->contacts }}
                                                        </span>

                                                    </p>

                                                </div>

                                            </a>

                                        </td>

                                        {{-- SUBJECT --}}
                                        <td class="px-3 py-5 text-center whitespace-nowrap">

                                            {{ $teacher->specialties ? implode($teacher->specialties, '-') : '-' }}

                                        </td>

                                        {{-- CLASSES --}}
                                        <td class="px-3 py-5 text-center">

                                            -

                                        </td>

                                        {{-- HOURS --}}
                                        <td class="px-3 py-5 text-center">

                                            26h

                                        </td>

                                        {{-- STATUS --}}
                                        <td class="px-3 py-5 text-center">

                                            <span class="px-3 py-1 rounded-full
                                                         bg-emerald-500/10
                                                         text-emerald-400 text-sm">

                                                Actif

                                            </span>

                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="px-6 py-5">

                                            <div class="flex justify-center items-center gap-3  text-sm">
                                                @if (!$teacher->deleted_at)
                                                    <button wire:key="del-tenant-{{ $teacher->id }}" wire:click="deleteTenant('{{ $teacher->id }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="deleteTenant">
                                                            <x-lucide-ban class="w-4 h-4" />
                                                            Bloquer
                                                        </span>
                                                        <span wire:loading.flex wire:target="deleteTenant" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-del-tenant-{{ $teacher->id }}" :show="$showConfirmDeleteModal" title="Placer le tenant {{ $teacher->domain_name }}dans la Corbeille"
                                                        confirm-text="Oui, placer dans la corbeille" cancel-text="Annuler" confirm-action="ConfirmSchoolDeletion" close-action="closeModal">
                                                        <p>Cette action entrainera : </p>
                                                        <ul class="text-green-500 text-xs">
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La mise en corbeille du tenant</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La suppression surperficielle de l'école</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>L'innacessiblité au domaine par le directeur et les autres users du domaine</span>
                                                            </li>
                                                        </ul>
                                                    </x-confirm-modal>

                                                    {{-- DELETE --}}

                                                    <button wire:key="del-tenant-{{ $teacher->id }}" wire:click="deleteTenant('{{ $teacher->id }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="deleteTenant">
                                                            <x-lucide-trash class="w-4 h-4" />
                                                            Corbeille
                                                        </span>
                                                        <span wire:loading.flex wire:target="deleteTenant" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-del-tenant-{{ $teacher->id }}" :show="$showConfirmDeleteModal" title="Placer le tenant {{ $teacher->domain_name }}dans la Corbeille"
                                                        confirm-text="Oui, placer dans la corbeille" cancel-text="Annuler" confirm-action="ConfirmSchoolDeletion" close-action="closeModal">
                                                        <p>Cette action entrainera : </p>
                                                        <ul class="text-green-500 text-xs">
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La mise en corbeille du tenant</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La suppression surperficielle de l'école</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>L'innacessiblité au domaine par le directeur et les autres users du domaine</span>
                                                            </li>
                                                        </ul>
                                                    </x-confirm-modal>
                                                @else
                                                    <button wire:key="restore-tenant-{{ $teacher->id }}" wire:click="restoreTenant('{{ $teacher->id }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-purple-500/10 hover:bg-purple-500/20 text-purple-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="restoreTenant">
                                                            <x-lucide-recycle class="w-4 h-4" />
                                                            Restaurer
                                                        </span>
                                                        <span wire:loading.flex wire:target="restoreTenant" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-restore-tenant-{{ $teacher->id }}" :show="$showConfirmRestorationModal" title="restauration du tenant {{ $teacher->domain_name }}"
                                                        confirm-text="Oui, restaurer le tenant" cancel-text="Annuler" confirm-action="ConfirmSchoolRestoration" close-action="closeModal">
                                                        <p>Cette action entrainera : </p>
                                                        <ul class="text-green-500 text-xs">
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La restauration du tenant</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La restauration de l'école</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>L'acessiblité au domaine par le directeur et les autres users du domaine</span>
                                                            </li>
                                                        </ul>
                                                    </x-confirm-modal>

                                                    <button wire:key="force-del-tenant-{{ $teacher->id }}" wire:click="forceDelete('{{ $teacher->id }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl col-span-3 flex items-center flex-1 justify-center cursor-pointer bg-red-500/10 hover:bg-red-500/20 text-red-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="forceDelete">
                                                            <x-lucide-trash-2 class="w-4 h-4" />
                                                            Supprimer déf.
                                                        </span>
                                                        <span wire:loading.flex wire:target="forceDelete" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-force-del-tenant-{{ $teacher->id }}" :show="$showConfirmForceDeleteModal" title="Suppression définitive du tenant {{ $teacher->domain_name }}"
                                                        confirm-text="Oui, Supprimer Déf." cancel-text="Annuler" confirm-action="ConfirmSchoolForceDelete" close-action="closeModal">
                                                        <p>Cette action entrainera : </p>
                                                        <ul class="text-green-500 text-xs">
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La suppresion définitive du tenant</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La suppresion définitive de l'école</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La suppression définitive de la base de données ainsi que les données stockées dans cette base</span>
                                                            </li>
                                                            <li class="flex items-center gap-x-1">
                                                                <x-lucide-check class="w-5 h-5 text-green-800" />
                                                                <span>La suppresion du domaine</span>
                                                            </li>
                                                        </ul>
                                                    </x-confirm-modal>
                                                @endif

                                            </div>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    {{-- PAGINATION --}}
                    <div class="border-t border-slate-800
                                    px-3 sm:px-6 py-4">

                        <div class="flex flex-col sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                        gap-4">

                            <p class="text-sm text-slate-400">

                                Affichage de 1 à 10 sur 248 enseignants

                            </p>

                            <div class="flex items-center gap-2">

                                <button
                                    class="h-10 px-3 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                    Précédent

                                </button>

                                <button
                                    class="h-10 px-3 rounded-xl
                                                   bg-indigo-500
                                                   hover:bg-indigo-600
                                                   transition-all text-sm">

                                    1

                                </button>

                                <button
                                    class="h-10 px-3 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                    2

                                </button>

                                <button
                                    class="h-10 px-3 rounded-xl
                                                   bg-slate-800
                                                   hover:bg-slate-700
                                                   transition-all text-sm">

                                    Suivant

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>
        <section class="my-4">
            <div class="space-y-6">

                {{-- QUICK STATS --}}
                <div class="rounded-3xl
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

                                <div class="mt-2 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                                    <div class="h-full rounded-full {{ $item[2] }}" style="width: {{ $item[1] }}">
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- RECENT ACTIVITY --}}
                <div class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

                    <h2 class="text-lg font-semibold">

                        Activités Récentes

                    </h2>

                    <div class="mt-5 space-y-4">

                        @foreach (range(1, 5) as $activity)
                            <div class="rounded-2xl
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

