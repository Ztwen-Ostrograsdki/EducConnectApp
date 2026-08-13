<div class="flex flex-col gap-7 p-4 sm:p-6 max-w-7xl mx-auto">

    {{-- ===================== HEADER ===================== --}}
    <section
        class="relative overflow-hidden rounded-[2rem] bg-slate-950 border-2 border-violet-500/40 shadow-[0_0_40px_-10px_rgba(139,92,246,0.35)]">

        {{-- Background décoratif --}}
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-violet-600/20 via-transparent to-transparent">
        </div>
        <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-fuchsia-600/10 rounded-full blur-3xl"></div>

        <div class="relative px-6 py-7 sm:px-8 sm:py-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                <div class="flex items-center gap-5">
                    <div
                        class="flex h-18 w-18 sm:h-20 sm:w-20 items-center justify-center rounded-2xl bg-violet-600/20 border-2 border-violet-400/40 shadow-inner">
                        <x-lucide-graduation-cap class="h-10 w-10 text-violet-300" />
                    </div>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                            Migrations Enseignants
                        </h1>
                        <p class="mt-1 text-slate-400 text-sm sm:text-base">
                            Ajouts & Créations • Gestion des utilisateurs enseignants
                        </p>
                    </div>
                </div>

                <a href="{{ route('tenant.teachers.crud.tasks') }}"
                    class="group relative inline-flex items-center gap-3 px-6 py-3.5 rounded-2xl font-semibold text-white overflow-hidden transition-all duration-300 hover:scale-[1.03] active:scale-95">
                    <span class="absolute inset-0 bg-gradient-to-r from-violet-600 to-fuchsia-600"></span>
                    <span
                        class="absolute inset-0 bg-gradient-to-r from-violet-500 to-fuchsia-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <x-lucide-activity class="relative w-5 h-5" />
                    <span class="relative">Status des migrations</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== TOGGLE + ALERTE ===================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

        {{-- Bouton toggle --}}
        <button wire:click="toggleImportMode"
            class="relative inline-flex items-center gap-3 px-6 py-4 rounded-2xl font-bold text-sm tracking-wide transition-all duration-300 active:scale-95
                       {{ $showImportMode
                           ? 'bg-slate-800 text-slate-300 border-2 border-slate-600 hover:border-slate-500'
                           : 'bg-emerald-500 text-white border-2 border-emerald-400 shadow-[0_8px_0_0_#059669] hover:shadow-[0_4px_0_0_#059669] hover:translate-y-[4px]' }}">

            <span wire:loading.remove wire:target="toggleImportMode" class="flex items-center gap-2.5">
                @if ($showImportMode)
                    <x-lucide-pen-line class="w-5 h-5" />
                    Saisie manuelle
                @else
                    <x-lucide-file-spreadsheet class="w-5 h-5" />
                    Import Excel
                @endif
            </span>
            <span wire:loading wire:target="toggleImportMode" class="flex items-center gap-2.5">
                <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                Chargement...
            </span>
        </button>

        @if (count($this->teachers))
            <div class="flex items-center gap-3">
                <a href="#inserts-teachers"
                    class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-amber-500/15 border border-amber-500/40 text-amber-300 text-sm font-medium animate-pulse">
                    <x-lucide-database class="w-4 h-4" />
                    {{ count($this->teachers) }} en attente
                </a>

                <button wire:click="clearAddedData"
                    class="group relative inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm text-white overflow-hidden transition-all active:scale-95">
                    <span class="absolute inset-0 bg-gradient-to-r from-rose-600 to-red-600"></span>
                    <span
                        class="absolute inset-0 bg-rose-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span wire:loading.remove wire:target="clearAddedData" class="relative flex items-center gap-2">
                        <x-lucide-trash-2 class="w-4 h-4" />
                        Vider
                    </span>
                    <span wire:loading wire:target="clearAddedData" class="relative flex items-center gap-2">
                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    </span>
                </button>
            </div>
        @endif
    </div>

    {{-- ===================== LOADING ===================== --}}
    <div wire:loading wire:target="toggleImportMode" class="flex flex-col items-center justify-center py-20 gap-4">
        <div class="relative">
            <div class="absolute inset-0 bg-violet-500/30 rounded-full blur-xl animate-pulse"></div>
            <x-lucide-loader-2 class="relative w-14 h-14 text-violet-400 animate-spin" />
        </div>
        <p class="text-slate-400 font-medium">Changement de mode...</p>
    </div>

    <div wire:loading.remove wire:target="toggleImportMode" class="space-y-7">

        {{-- ===================== MODE IMPORT ===================== --}}
        @if ($showImportMode)
            <div class="rounded-[1.75rem] bg-slate-900/80 border-2 border-slate-700 p-6 sm:p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-600/20 border border-violet-500/40">
                        <x-lucide-file-up class="w-6 h-6 text-violet-300" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Import depuis Excel</h3>
                        <p class="text-sm text-slate-400">Sélectionnez le fichier au format attendu</p>
                    </div>
                </div>

                <div class="mb-6 p-4 rounded-2xl bg-slate-950/70 border border-slate-700">
                    <p class="text-xs sm:text-sm text-slate-400">
                        <span class="font-semibold text-violet-300">Format attendu :</span>
                        <span class="font-mono text-[11px] sm:text-xs text-slate-300 ml-1">
                            Nom | Prénoms | Email | Contact | Genre | Pays | Département | Ville | Fonction | Date
                            naissance
                        </span>
                    </p>
                </div>

                <input type="file" wire:model="excelFile" accept=".xlsx,.xls"
                    class="block w-full text-sm text-slate-400
                              file:mr-5 file:py-3.5 file:px-6
                              file:rounded-xl file:border-0
                              file:bg-violet-600 file:text-white file:font-semibold
                              hover:file:bg-violet-500 file:cursor-pointer
                              file:transition-all file:duration-200
                              cursor-pointer rounded-2xl border-2 border-dashed border-slate-600 bg-slate-950/50 p-3 hover:border-violet-500/50 transition-colors" />

                <div wire:loading wire:target="excelFile"
                    class="mt-6 flex items-center justify-center gap-3 text-violet-300">
                    <x-lucide-loader-2 class="w-6 h-6 animate-spin" />
                    <span class="font-medium">Lecture du fichier...</span>
                </div>

                @if (!empty($importErrors))
                    <div class="mt-8 space-y-2">
                        <p class="text-xs font-bold uppercase tracking-widest text-rose-400 mb-3">Lignes ignorées</p>
                        @foreach ($importErrors as $err)
                            <div
                                class="px-4 py-2.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm">
                                {{ $err }}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ===================== MODE MANUEL ===================== --}}
        @else
            <div class="space-y-6">

                {{-- Banner --}}
                <div class="flex items-start gap-3 px-5 py-4 rounded-2xl bg-violet-500/10 border border-violet-500/30">
                    <x-lucide-info class="w-5 h-5 text-violet-300 mt-0.5 shrink-0" />
                    <p class="text-sm text-violet-100/90 leading-relaxed">
                        Mode manuel — Remplissez le formulaire puis cliquez sur <strong
                            class="text-white">Ajouter</strong>.
                        Une fois terminé, lancez la migration avec le bouton <strong
                            class="text-white">Terminer</strong>.
                    </p>
                </div>

                {{-- ===== Infos personnelles ===== --}}
                <div class="rounded-[1.75rem] bg-slate-900/70 border-2 border-slate-700 p-6 sm:p-7">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-600/20 text-violet-300">
                            <x-lucide-user class="w-5 h-5" />
                        </div>
                        <h3 class="text-lg font-bold text-white">Informations personnelles</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="name">
                                Nom <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="name" type="text" id="name"
                                class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all"
                                placeholder="Nom de l'enseignant">
                            @error('name')
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="prenames">
                                Prénoms <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="prenames" type="text" id="prenames"
                                class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all"
                                placeholder="Prénoms de l'enseignant">
                            @error('prenames')
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="email">
                                Email <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="email" type="email" id="email"
                                class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all"
                                placeholder="email@exemple.com">
                            @error('email')
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="contacts">
                                Contact <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="contacts" type="text" id="contacts"
                                class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all"
                                placeholder="01617777777">
                            @error('contacts')
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="birth_date">
                                Date de naissance <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="birth_date" type="date" id="birth_date"
                                class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all">
                            @error('birth_date')
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="gender">
                                Genre <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="gender" id="gender"
                                class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all">
                                <option value="">Sélectionnez</option>
                                @foreach ($this->genders as $gk => $g)
                                    <option value="{{ $gk }}">{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="job_name">
                                Fonction
                            </label>
                            <input wire:model.live="job_name" type="text" id="job_name"
                                class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all"
                                placeholder="Ex: Enseignant de Math">
                            @error('job_name')
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ===== Adresse ===== --}}
                <div class="rounded-[1.75rem] bg-slate-900/70 border-2 border-fuchsia-500/40 p-6 sm:p-7">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-fuchsia-600/20 text-fuchsia-300">
                            <x-lucide-map-pin class="w-5 h-5" />
                        </div>
                        <h3 class="text-lg font-bold text-white">Adresse</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="country">
                                Pays <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="country" id="country"
                                class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20 transition-all">
                                <option value="">Sélectionnez le pays</option>
                                @foreach ($this->countries as $ck => $ctn)
                                    <option value="{{ $ctn }}">{{ $ctn }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="department">
                                Département <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="department" id="department"
                                class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20 transition-all">
                                <option value="">Sélectionnez le département</option>
                                @foreach ($this->departments as $dk => $dn)
                                    <option value="{{ $dn }}">{{ $dn }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <div wire:loading wire:target="department"
                                class="flex items-center gap-2 py-3.5 text-slate-400">
                                <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                                <span>Chargement des villes...</span>
                            </div>

                            @if ($department)
                                <div wire:loading.remove wire:target="department">
                                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="city">
                                        Ville <span class="text-rose-400">*</span>
                                    </label>
                                    <select wire:model.live="city" id="city"
                                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20 transition-all">
                                        <option value="">Sélectionnez la ville</option>
                                        @foreach ($cities as $ck => $cn)
                                            <option value="{{ $cn }}">{{ $cn }}</option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                        <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                                            <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Bouton Ajouter / Mettre à jour --}}
                <button type="button" wire:click="{{ $editingUuid ? 'updateTeacher' : 'addTeacher' }}"
                    wire:loading.attr="disabled"
                    class="group relative w-full overflow-hidden rounded-2xl py-4 font-bold text-white transition-all duration-300 active:scale-[0.98] disabled:opacity-70">

                    <span
                        class="absolute inset-0 bg-gradient-to-r from-violet-600 via-fuchsia-600 to-violet-600 bg-[length:200%_100%] group-hover:animate-[gradient_3s_ease_infinite]"></span>
                    <span
                        class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></span>

                    <span wire:loading.remove wire:target="updateTeacher,addTeacher"
                        class="relative flex items-center justify-center gap-2.5 text-lg">
                        <x-lucide-user-plus class="w-5 h-5" />
                        {{ $editingUuid ? 'Mettre à jour l\'enseignant' : 'Ajouter l\'enseignant' }}
                    </span>

                    <span wire:loading.flex wire:target="updateTeacher,addTeacher"
                        class="relative items-center justify-center gap-2.5 text-lg">
                        <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                        Traitement...
                    </span>
                </button>
            </div>
        @endif
    </div>

    {{-- ===================== LISTE DES ENSEIGNANTS ===================== --}}
    <div id="inserts-teachers" class="mt-2">
        @if (count($this->teachers))
            <section class="rounded-[1.75rem] bg-slate-950 border-2 border-slate-700 overflow-hidden shadow-2xl">

                {{-- Header liste --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 bg-slate-900/80 border-b-2 border-slate-800">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-300">
                            <x-lucide-users class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg">Enseignants ajoutés</h4>
                            <p class="text-sm text-slate-400">{{ count($this->teachers) }} enregistrement(s)</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button wire:click="finish" wire:loading.attr="disabled"
                            class="group relative inline-flex items-center gap-2.5 px-5 py-3 rounded-xl font-bold text-white overflow-hidden transition-all active:scale-95">
                            <span class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500"></span>
                            <span
                                class="absolute inset-0 bg-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            <span wire:loading.remove wire:target="finish" class="relative flex items-center gap-2">
                                <x-lucide-send class="w-4.5 h-4.5" />
                                Terminer
                            </span>
                            <span wire:loading.flex wire:target="finish" class="relative items-center gap-2">
                                <x-lucide-loader-2 class="w-4.5 h-4.5 animate-spin" />
                            </span>
                        </button>

                        <button wire:click="clearAddedData"
                            class="group relative inline-flex items-center gap-2 px-4 py-3 rounded-xl font-semibold text-sm text-white overflow-hidden transition-all active:scale-95">
                            <span class="absolute inset-0 bg-gradient-to-r from-rose-600 to-red-600"></span>
                            <span
                                class="absolute inset-0 bg-rose-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            <span wire:loading.remove wire:target="clearAddedData"
                                class="relative flex items-center gap-2">
                                <x-lucide-trash-2 class="w-4 h-4" />
                                Vider
                            </span>
                            <span wire:loading wire:target="clearAddedData" class="relative flex items-center gap-2">
                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-900/90 text-slate-400 text-xs uppercase tracking-wider">
                                <th class="px-5 py-4 text-left font-semibold">N°</th>
                                <th class="px-5 py-4 text-left font-semibold">Enseignant</th>
                                <th class="px-5 py-4 text-left font-semibold">Email</th>
                                <th class="px-5 py-4 text-left font-semibold">Contact</th>
                                <th class="px-5 py-4 text-left font-semibold">Naissance</th>
                                <th class="px-5 py-4 text-right font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach ($this->teachers as $teacher)
                                <tr wire:key="{{ $teacher['uuid'] }}"
                                    class="hover:bg-slate-900/60 transition-colors">

                                    <td class="px-5 py-4 text-slate-500 font-mono text-xs">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-white">
                                            {{ $teacher['name'] }} {{ $teacher['prenames'] }}
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-xs px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                                {{ $teacher['gender'] }}
                                            </span>
                                            @if (!empty($teacher['job_name']))
                                                <span class="text-xs text-slate-400">{{ $teacher['job_name'] }}</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $teacher['city'] }} · {{ $teacher['department'] }} ·
                                            {{ $teacher['country'] }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-300">
                                        {{ $teacher['email'] }}
                                    </td>

                                    <td class="px-5 py-4 font-mono text-slate-300">
                                        {{ $teacher['contacts'] }}
                                    </td>

                                    <td class="px-5 py-4 text-slate-300">
                                        {{ $teacher['birth_date'] }}
                                    </td>

                                    {{-- ========== BOUTONS D'ACTIONS STYLÉS ========== --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2.5">

                                            {{-- Bouton Modifier --}}
                                            <button wire:click="editTeacher('{{ $teacher['uuid'] }}')"
                                                wire:loading.attr="disabled"
                                                class="group relative inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm overflow-hidden transition-all duration-200 hover:scale-105 active:scale-95 disabled:opacity-60">

                                                <span
                                                    class="absolute inset-0 bg-sky-500/15 border border-sky-400/40 rounded-xl group-hover:bg-sky-500/25 group-hover:border-sky-400/60 transition-all"></span>
                                                <span
                                                    class="absolute inset-0 shadow-[0_0_20px_-5px_rgba(14,165,233,0.4)] opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"></span>

                                                <span wire:loading.remove
                                                    wire:target="editTeacher('{{ $teacher['uuid'] }}')"
                                                    class="relative flex items-center gap-2 text-sky-300">
                                                    <x-lucide-pen class="w-4 h-4" />
                                                    <span>Modifier</span>
                                                </span>
                                                <span wire:loading.flex
                                                    wire:target="editTeacher('{{ $teacher['uuid'] }}')"
                                                    class="relative items-center gap-2 text-sky-300">
                                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                                </span>
                                            </button>

                                            {{-- Bouton Retirer --}}
                                            <button wire:click="deleteTeacher('{{ $teacher['uuid'] }}')"
                                                wire:loading.attr="disabled"
                                                title="Retirer {{ $teacher['name'] }} {{ $teacher['prenames'] }}"
                                                class="group relative inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm overflow-hidden transition-all duration-200 hover:scale-105 active:scale-95 disabled:opacity-60">

                                                <span
                                                    class="absolute inset-0 bg-rose-500/15 border border-rose-400/40 rounded-xl group-hover:bg-rose-500/25 group-hover:border-rose-400/60 transition-all"></span>
                                                <span
                                                    class="absolute inset-0 shadow-[0_0_20px_-5px_rgba(244,63,94,0.4)] opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"></span>

                                                <span wire:loading.remove
                                                    wire:target="deleteTeacher('{{ $teacher['uuid'] }}')"
                                                    class="relative flex items-center gap-2 text-rose-300">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                    <span>Retirer</span>
                                                </span>
                                                <span wire:loading.flex
                                                    wire:target="deleteTeacher('{{ $teacher['uuid'] }}')"
                                                    class="relative items-center gap-2 text-rose-300">
                                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                                </span>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Bouton Terminer en bas --}}
                <div class="p-6 border-t-2 border-slate-800 bg-slate-900/50">
                    <button wire:click="finish" wire:loading.attr="disabled"
                        class="group relative w-full overflow-hidden rounded-2xl py-4 font-bold text-white transition-all duration-300 active:scale-[0.98]">

                        <span
                            class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-500 bg-[length:200%_100%]"></span>
                        <span
                            class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></span>

                        <span wire:loading.remove wire:target="finish"
                            class="relative flex items-center justify-center gap-2.5 text-lg">
                            <x-lucide-send class="w-5 h-5" />
                            Terminer & Lancer la migration
                        </span>
                        <span wire:loading.flex wire:target="finish"
                            class="relative items-center justify-center gap-2.5 text-lg">
                            <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                            Traitement en cours...
                        </span>
                    </button>
                </div>
            </section>
        @endif
    </div>
</div>
