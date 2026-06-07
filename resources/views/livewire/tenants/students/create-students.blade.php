<div class="flex flex-col gap-1 p-5 w-4/5 justify-center mx-auto">
    <div class="">
        <section class=" bg-slate-900/80 backdrop-blur-xl rounded-2xl mt-2.5 border border-slate-600">

            <div class="w-full px-4 py-1">

                <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                    {{-- LEFT --}}
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 min-w-0 flex-1 p-2 my-2">

                        {{-- ICON --}}
                        <div class="shrink-0 self-start">

                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                                <x-lucide-user-plus class="h-10 w-10 font-extrabold text-slate-600" />
                            </div>

                        </div>

                        {{-- CONTENT --}}
                        <div class="min-w-0 flex-1">

                            <div class="flex flex-wrap items-center gap-2">

                                <h1
                                    class="text-xl sm:text-xl lg:text-xl
                                           font-bold
                                           leading-tight
                                           break-words font-mono">
                                    Gestion des migrations apprenants: Ajouts et Créations
                                </h1>
                            </div>

                            <p class="mt-3 text-sm sm:text-base
                                      text-slate-400
                                      break-words">
                                Gestion des migrations sur les utilisateurs apprenants
                            </p>

                        </div>

                    </div>

                </div>
                <div class="flex justify-end w-full">
                    <a href="{{ route('tenant.students.crud.tasks') }}" class="rounded-2xl flex items-center bg-blue-600 hover:bg-blue-800 text-white gap-x-3 px-3.5 py-2.5 my-2">
                        <x-lucide-octagon-alert class="w-5 h-5" />
                        <span class="s-label">Voir le status des migrations lancées</span>
                    </a>
                </div>

            </div>

        </section>
    </div>

    {{-- Bouton bascule formulaire / import --}}
    <div class="flex gap-3 my-3 mb-4">
        <button class="px-4 py-3.5 rounded-2xl text-black @if ($showImportMode) bg-gray-500 hover:bg-gray-500 @else bg-green-600 hover:bg-green-800 @endif" wire:click="toggleImportMode">
            <span wire:loading.remove wire:target='toggleImportMode' class="flex gap-1.5 items-center">
                @if ($showImportMode)
                    <x-lucide-pen class="w-4 h-4" />
                    <span>Saisie manuelle des données</span>
                @else
                    <x-lucide-file class="w-4 h-4" />
                    <span>Importer depuis un fichier Excel</span>
                @endif
            </span>
            <span wire:loading wire:target='toggleImportMode' class="flex gap-1.5 items-center">
                <span>En cours ...</span>
            </span>
        </button>
    </div>

    <div class="flex-col justify-center w-full mx-auto">
        <div class="flex gap-x-2 justify-end">
            @if (count($this->students))
                <a href="#inserts-students" class="block text-orange-500 p-2 my-2.2 text-end rounded-2xl mb-2 font-mono text-sm animate-pulse">
                    {{ count($this->students) }} données ont été ajoutées et attendente d'être migrer en base de données!
                </a>
                <button class="px-4 py-2 rounded-2xl text-white bg-red-600 hover:bg-red-800" wire:click="clearAddedData">
                    <span wire:loading.remove wire:target='clearAddedData' class="flex gap-1.5 items-center">
                        <x-lucide-trash-2 class="w-4 h-4" />
                        <span>Vider les données ajouter</span>
                    </span>
                    <span wire:loading wire:target='clearAddedData' class="flex gap-1.5 items-center">
                        <span>En cours ...</span>
                    </span>
                </button>
            @endif

        </div>
        <div wire:loading wire:target='toggleImportMode' class="flex gap-1.5 w-full items-center text-center text-gray-600 justify-center mt-9.5 mx-auto">
            <x-lucide-loader class="w-10 h-10 animate-spin text-center" />
            <span>Chargement en cours ...</span>
        </div>
        <div wire:loading.remove wire:target='toggleImportMode'>
            {{-- Zone import Excel --}}
            @if ($showImportMode)
                <h6 class="bg-info-700 text-black p-2 my-2.2 text-center rounded-2xl mb-5 font-mono text-sm">
                    Vous êtes en mode importation depuis un fichier excel. Veuillez sélectionner le fichier au format indiqué!
                </h6>
                <div class="bg-slate-800 rounded-xl p-6 mb-6 border border-slate-700">
                    <p class="text-sm text-slate-400 mb-4">
                        Format attendu : <span class="text-slate-300 font-mono text-xs">Nom | Prénoms | Contact | Genre | Pays | Département | Ville | Date naissance | lieu de naissance | nom et prénoms père | nom et prénoms mère | Email
                        </span>
                    </p>

                    <input type="file" wire:model="excelFile" accept=".xlsx,.xls"
                        class="block w-full text-sm text-slate-400
                   file:mr-4 file:py-2 file:px-4
                   file:rounded-lg file:border-0
                   file:bg-indigo-600 file:text-white
                   hover:file:bg-indigo-700 cursor-pointer" />

                    <div wire:loading wire:target="excelFile" class="mt-3 text-sm text-indigo-400">
                        Lecture du fichier en cours...
                    </div>

                    {{-- Erreurs de lignes ignorées --}}
                    @if (!empty($importErrors))
                        <div class="mt-4 space-y-1">
                            @foreach ($importErrors as $err)
                                <p class="text-xs text-rose-400">{{ $err }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <h6 class="bg-info-700 text-black p-2 my-2.2 text-center rounded-2xl mb-5 font-mono text-sm">
                    Vous êtes en mode manuel. Veuillez renseigner le formulaire et ajouter les données au fur et a mesure. Une fois terminée, lancer la migration des données en cliquant "Terminer"
                </h6>
                <div class="space-y-6">
                    <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-slate-700 rounded-2xl border border-slate-700">
                        <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                            <x-lucide-user class="w-5 h-5" />
                            <h3 class="">Informations personnelles de l'apprenant</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="name">Nom
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input wire:model.live='name' type="text" id="name" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                        placeholder="Nom de l'apprenant">
                                    @error('name')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="prenames">Prénoms
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" wire:model.live='prenames' id="prenames" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                        placeholder="Prenoms de l'apprenant ">
                                    @error('prenames')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="email">Email
                                    <span class="text-sky-500">facultative</span>
                                </label>
                                <div class="relative">
                                    <input type="text" wire:model.live='email' id="email" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                        placeholder="Email personnel de l'apprenant">
                                    @error('email')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="contacts">Contact d'un parent légitime (unique)
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input wire:model.live='contacts' type="text" id="contacts" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                        placeholder="01617777777">
                                    @error('contacts')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="birth_date">Date de naissance
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input wire:model.live='birth_date' type="date" id="birth_date" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all">
                                    @error('birth_date')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="birth_place">Lieu de naissance</label>
                                <div class="relative">
                                    <input type="text" wire:model.live='birth_place' id="birth_place"
                                        class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all" placeholder="Lieu de naissance....">
                                    @error('birth_place')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="gender">Genre
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select wire:model.live='gender' id="gender" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                        <option value="">Sélectionnez le genre</option>
                                        @foreach ($genders as $g)
                                            <option class="bg-slate-800 text-slate-300" value="{{ $g }}">{{ $g }}</option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-sky-500 rounded-2xl border border-sky-500">
                        <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                            <x-lucide-map-pin-check class="w-5 h-5" />
                            <h3 class="">Adresse actuelle de l'apprenant</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="name">Pays
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select wire:model.live='country' id="country" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                        <option value="">Sélectionnez le pays
                                        </option>
                                        @foreach ($countries as $ck => $ctn)
                                            <option class="bg-slate-800 text-slate-300" value="{{ $ctn }}">{{ $ctn }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="grid grid-cols-1 items-center md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="department">Le département
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select wire:model.live='department' id="department" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                        <option value="">Sélectionnez le département
                                        </option>
                                        @foreach ($departments as $dk => $dn)
                                            <option class="bg-slate-800 text-slate-300" value="{{ $dn }}">{{ $dn }}</option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div wire:loading wire:target='department' wire:target='city'>
                                <div class="py-3 mt-3 flex justify-center items-center gap-x-3 text-gray-600">
                                    <x-lucide-loader class="w-5 h-5 animate-spin" />
                                    <h5>Chargement en cours ...</h5>
                                </div>
                            </div>
                            @if ($department)
                                <div data-animate='card' wire:target='department' wire:target='city' wire:loading.remove>
                                    <label class="block text-sm font-medium mb-2 text-gray-300" for="city">La ville
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select wire:model.live='city' id="city" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                            <option value="">Sélectionnez la ville</option>
                                            @foreach ($cities as $ck => $cn)
                                                <option class="bg-slate-800 text-slate-300" value="{{ $cn }}">{{ $cn }}</option>
                                            @endforeach
                                        </select>
                                        @error('city')
                                            <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                                <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-slate-700 rounded-2xl border border-slate-700">
                        <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                            <x-lucide-user class="w-5 h-5" />
                            <h3 class="">informations sur géniteurs (si vivant)</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="father_full_name">Nom et Prenoms du père
                                    <span class="text-sky-500">(facultative)</span>
                                </label>
                                <div class="relative">
                                    <input wire:model.live='father_full_name' type="text" id="father_full_name"
                                        class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all" placeholder="Nom et prénoms du père de l'apprenant">
                                    @error('father_full_name')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="mother_full_name">Nom et Prenoms de la mère
                                    <span class="text-sky-500">(facultative)</span>
                                </label>
                                <div class="relative">
                                    <input wire:model.live='mother_full_name' type="text" id="mother_full_name"
                                        class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all" placeholder="Nom et prénoms de la mère de l'apprenant">
                                    @error('mother_full_name')
                                        <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                            <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" wire:loading.attr="disabled" wire:click="{{ $editingUuid ? 'updateTeacher' : 'addTeacher' }}"
                        class="p-3 rounded-2xl w-full flex items-center justify-center cursor-pointer bg-sky-600 hover:bg-sky-800">
                        <span class="flex items-center gap-1.5" wire:target='updateTeacher, addTeacher' wire:loading.remove>
                            <span>{{ $editingUuid ? 'Mettre à jour' : 'Ajouter' }}</span>
                            <x-lucide-user-plus class="w-4 h-4" />
                        </span>
                        <span wire:target='updateTeacher, addTeacher' wire:loading.flex class="items-center gap-1.5">
                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </button>
                </div>
            @endif

        </div>
    </div>

    <div id="inserts-teachers" class="mt-5 flex flex-col w-full gap-1.5 mb-40">
        @if (count($this->teachers))
            <section class="w-full">
                <h4 class="p-2 rounded-lg border border-slate-800 bg-slate-950 overflow-hidden my-2 flex justify-between items-center">
                    <div class="text-gray-400">
                        Liste des données déjà ajoutées
                        <span>
                            Nombre:
                            {{ count($this->teachers) }}
                        </span>
                    </div>
                    <button class="px-4 py-2 rounded-2xl text-white bg-red-600 hover:bg-red-800" wire:click="clearAddedData">
                        <span wire:loading.remove wire:target='clearAddedData' class="flex gap-1.5 items-center">
                            <x-lucide-trash-2 class="w-4 h-4" />
                            <span>Vider les données ajouter</span>
                        </span>
                        <span wire:loading wire:target='clearAddedData' class="flex gap-1.5 items-center">
                            <span>En cours ...</span>
                        </span>
                    </button>
                </h4>

                <div class="rounded-lg border border-slate-800 bg-slate-900 overflow-hidden">

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-full text-xs">

                            <thead class="bg-slate-950 border-b border-slate-800">
                                <tr>
                                    <th class="text-left p-2 font-medium text-slate-400">
                                        N°
                                    </th>
                                    <th class="text-left p-2 font-medium text-slate-400">
                                        Nom et Prenoms
                                    </th>

                                    <th class="text-left p-2 font-medium text-slate-400">
                                        Email
                                    </th>

                                    <th class="text-left p-2 font-medium text-slate-400">
                                        contacts
                                    </th>
                                    <th class="text-left p-2 font-medium text-slate-400">
                                        Date de naissance
                                    </th>

                                    <th class="text-center p-2 font-medium text-slate-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-800">

                                @foreach ($this->teachers as $teacher)
                                    <tr wire:key="{{ $teacher['uuid'] }}" class="hover:bg-slate-800/40 transition-all">
                                        <td class="p-2 truncate text-slate-300">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="p-2">

                                            <div class="flex items-center gap-4 min-w-0">

                                                <div class="min-w-0">

                                                    <h3 class="font-medium truncate">
                                                        {{ $teacher['name'] }} {{ $teacher['prenames'] }} ({{ $teacher['gender'] }})
                                                    </h3>

                                                    <p class=text-slate-400 truncate">
                                                        {{ $teacher['job_name'] }}
                                                    </p>
                                                    <p class="text-xs">
                                                        {{ $teacher['city'] }} - {{ $teacher['department'] }} , {{ $teacher['country'] }}
                                                    </p>

                                                </div>

                                            </div>

                                        </td>

                                        <td class="p-2 truncate text-slate-300">
                                            {{ $teacher['email'] }}
                                        </td>

                                        <td class="p-2 truncate">
                                            {{ $teacher['contacts'] }}
                                        </td>
                                        <td class="p-2 truncate">
                                            {{ $teacher['birth_date'] }}
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="p-2">

                                            <div class="flex items-center justify-end gap-2 truncate">
                                                <button wire:key="edit-teacher-{{ $teacher['uuid'] }}" wire:click="editTeacher('{{ $teacher['uuid'] }}')" wire:loading.attr="disabled"
                                                    class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 ">
                                                    <span wire:loading.remove class="flex items-center gap-1.5" wire:target="editTeacher">
                                                        <x-lucide-pen class="w-4 h-4" />
                                                        Modifier
                                                    </span>
                                                    <span wire:loading.flex wire:target="editTeacher" class="items-center gap-1.5">
                                                        <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                        <span>En cours...</span>
                                                    </span>
                                                </button>

                                                <button wire:key="rem-teacher-{{ $teacher['uuid'] }}" wire:click="deleteTeacher('{{ $teacher['uuid'] }}')" wire:loading.attr="disabled"
                                                    class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 ">
                                                    <span wire:loading.remove class="flex items-center gap-1.5" wire:target="deleteTeacher">
                                                        <x-lucide-trash-2 class="w-4 h-4" />
                                                        Supprimer
                                                    </span>
                                                    <span wire:loading.flex wire:target="deleteTeacher" class="items-center gap-1.5">
                                                        <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                        <span>En cours...</span>
                                                    </span>
                                                </button>
                                                <x-confirm-modal wire:key="confirm-teach-del-{{ $teacher['uuid'] }}" :show="$showTeacherRemoveModal" title="Retirer de la liste" confirm-text="Oui, Retirer" cancel-text="Annuler"
                                                    confirm-action="confirmDeleteTeacher" close-action="resetModal">
                                                    <p>
                                                        Cette action retirera cette donnée de la liste.
                                                    <p class="text-orange-500 font-semibold py-1.5">Action irreversible!</p>
                                                    </p>
                                                </x-confirm-modal>

                                            </div>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </section>
            <button type="button" wire:loading.attr="disabled" wire:click="{{ 'finish' }}" class="p-3 rounded-2xl w-full flex items-center justify-center cursor-pointer bg-green-600 hover:bg-green-800">
                <span class="flex items-center gap-1.5" wire:target='finish' wire:loading.remove>
                    <span>Terminer</span>
                    <x-lucide-send class="w-5 h-5" />
                </span>
                <span wire:target='finish' wire:loading.flex class="items-center gap-1.5">
                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                    <span>En cours...</span>
                </span>
            </button>
        @endif
    </div>
</div>

