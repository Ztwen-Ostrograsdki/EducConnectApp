<div class="flex flex-col gap-7 p-4 sm:p-6 max-w-7xl mx-auto">

    {{-- ===================== HEADER ===================== --}}
    <section
        class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-900 via-slate-900 to-amber-950/40 border border-amber-500/20 shadow-xl shadow-amber-900/10">

        <div
            class="absolute top-0 right-0 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3">
        </div>
        <div
            class="absolute bottom-0 left-0 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4">
        </div>

        <div class="relative px-6 py-7 sm:px-8 sm:py-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                <div class="flex items-center gap-5">
                    <div
                        class="flex h-16 w-16 sm:h-20 sm:w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-500/10 border border-amber-400/30 shadow-inner">
                        <x-lucide-users class="h-9 w-9 sm:h-10 sm:w-10 text-amber-300" />
                    </div>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                            Migrations Tuteurs
                        </h1>
                        <p class="mt-1.5 text-slate-400 text-sm sm:text-base">
                            Ajouts & Créations • Gestion des utilisateurs tuteurs
                        </p>
                    </div>
                </div>

                <a wire:navigate href="{{ route('tenant.parents.crud.tasks') }}"
                    class="group inline-flex items-center gap-2.5 px-5 py-3.5 rounded-2xl font-medium text-white bg-amber-600/90 hover:bg-amber-500 shadow-lg shadow-amber-600/20 transition-all duration-300 hover:scale-[1.03] active:scale-95">
                    <x-lucide-activity class="w-5 h-5 group-hover:animate-pulse" />
                    <span>Status des migrations</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== TOGGLE + ALERTE ===================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

        <button wire:click="toggleImportMode"
            class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 active:translate-y-[3px] active:shadow-none
               {{ $showImportMode
                   ? 'bg-slate-800 text-slate-300 border-2 border-slate-600 shadow-[0_4px_0_0_#334155] hover:bg-slate-700'
                   : 'bg-teal-500 text-white border-2 border-teal-400 shadow-[0_5px_0_0_#0f766e] hover:bg-teal-400 hover:shadow-[0_3px_0_0_#0f766e]' }}">

            <span wire:loading.remove wire:target="toggleImportMode" class="flex items-center gap-2.5">
                @if ($showImportMode)
                    <x-lucide-pen-line class="w-4.5 h-4.5" />
                    Saisie manuelle
                @else
                    <x-lucide-file-spreadsheet class="w-4.5 h-4.5" />
                    Import Excel
                @endif
            </span>

            <span wire:loading wire:target="toggleImportMode" class="flex items-center gap-2.5">
                <x-lucide-loader-2 class="w-4.5 h-4.5 animate-spin" />
                Chargement...
            </span>
        </button>
        @if (count($this->tutors))
            <div class="flex items-center gap-3">
                <a href="#inserts-tutors"
                    class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-sm font-medium">
                    <x-lucide-database class="w-4 h-4 animate-pulse" />
                    {{ count($this->tutors) }} en attente
                </a>

                <button wire:click="clearAddedData"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-sm font-medium shadow-md shadow-rose-600/20 transition-all active:scale-95">
                    <span wire:loading.remove wire:target="clearAddedData" class="flex items-center gap-2">
                        <x-lucide-trash-2 class="w-4 h-4" />
                        Vider
                    </span>
                    <span wire:loading wire:target="clearAddedData" class="flex items-center gap-2">
                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    </span>
                </button>
            </div>
        @endif
    </div>

    {{-- ===================== LOADING ===================== --}}
    <div wire:loading wire:target="toggleImportMode" class="flex flex-col items-center justify-center py-20 gap-4">
        <x-lucide-loader-2 class="w-12 h-12 text-amber-400 animate-spin" />
        <p class="text-slate-400 font-medium">Changement de mode...</p>
    </div>

    <div wire:loading.remove wire:target="toggleImportMode" class="space-y-6">

        {{-- ===================== MODE IMPORT ===================== --}}
        @if ($showImportMode)
            <div class="rounded-[1.75rem] bg-slate-900/70 border border-slate-700/80 p-6 sm:p-8 shadow-xl">
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/15 border border-amber-400/30">
                        <x-lucide-file-up class="w-6 h-6 text-amber-300" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Import depuis Excel</h3>
                        <p class="text-sm text-slate-400">Sélectionnez le fichier au format attendu</p>
                    </div>
                </div>

                <div class="mb-6 p-4 rounded-2xl bg-slate-950/60 border border-slate-700/60">
                    <p class="text-xs sm:text-sm text-slate-400">
                        <span class="font-medium text-amber-300">Format attendu :</span>
                        <span class="font-mono text-[11px] sm:text-xs text-slate-300 ml-1">
                            Nom | Prénoms | Email | Contact | Genre | Pays | Département | Ville | Fonction | Date
                            naissance
                        </span>
                    </p>
                </div>

                <input type="file" wire:model="excelFile" accept=".xlsx,.xls"
                    class="block w-full text-sm text-slate-400
                              file:mr-4 file:py-3 file:px-5
                              file:rounded-xl file:border-0
                              file:bg-amber-600 file:text-white file:font-medium
                              hover:file:bg-amber-500 file:cursor-pointer
                              file:transition-all
                              cursor-pointer rounded-2xl border border-dashed border-slate-600 bg-slate-950/40 p-3 hover:border-amber-500/40 transition-colors" />

                <div wire:loading wire:target="excelFile"
                    class="mt-5 flex items-center justify-center gap-3 text-amber-300">
                    <x-lucide-loader-2 class="w-6 h-6 animate-spin" />
                    <span class="font-medium">Lecture du fichier...</span>
                </div>

                @if (!empty($importErrors))
                    <div class="mt-8 space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-rose-400 mb-3">Lignes ignorées</p>
                        @foreach ($importErrors as $err)
                            <div
                                class="px-4 py-2.5 rounded-xl bg-rose-500/10 border border-rose-500/25 text-rose-300 text-sm">
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
                <div class="flex items-start gap-3 px-5 py-4 rounded-2xl bg-teal-500/10 border border-teal-500/25">
                    <x-lucide-info class="w-5 h-5 text-teal-300 mt-0.5 shrink-0" />
                    <p class="text-sm text-teal-100/90 leading-relaxed">
                        Mode manuel — Remplissez le formulaire puis cliquez sur <strong
                            class="text-white">Ajouter</strong>.
                        Une fois terminé, lancez la migration avec le bouton <strong
                            class="text-white">Terminer</strong>.
                    </p>
                </div>

                {{-- ===== Infos personnelles ===== --}}
                <div class="rounded-[1.75rem] bg-slate-900/70 border border-slate-700/70 p-6 sm:p-7 shadow-lg">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-700/60">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/15 text-amber-300">
                            <x-lucide-user class="w-5 h-5" />
                        </div>
                        <h3 class="text-lg font-semibold text-white">Informations personnelles du tuteur</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="name">
                                Nom <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="name" type="text" id="name"
                                class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
                                placeholder="Nom du tuteur">
                            @error('name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="prenames">
                                Prénoms <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="prenames" type="text" id="prenames"
                                class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
                                placeholder="Prénoms du tuteur">
                            @error('prenames')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="email">
                                Email <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="email" type="email" id="email"
                                class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
                                placeholder="email@exemple.com">
                            @error('email')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="contacts">
                                Contact <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="contacts" type="text" id="contacts"
                                class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
                                placeholder="01617777777">
                            @error('contacts')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="birth_date">
                                Date de naissance <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="birth_date" type="date" id="birth_date"
                                class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                            @error('birth_date')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="gender">
                                Genre <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="gender" id="gender"
                                class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                                <option value="">Sélectionnez</option>
                                @foreach ($genders as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="job_name">
                                Fonction
                            </label>
                            <input wire:model.live="job_name" type="text" id="job_name"
                                class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
                                placeholder="Ex: Entrepreneur">
                            @error('job_name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ===== Adresse ===== --}}
                <div
                    class="rounded-[1.75rem] bg-slate-900/70 border border-teal-500/30 p-6 sm:p-7 shadow-lg shadow-teal-900/5">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-700/60">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-500/15 text-teal-300">
                            <x-lucide-map-pin class="w-5 h-5" />
                        </div>
                        <h3 class="text-lg font-semibold text-white">Adresse</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="country">
                                Pays <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="country" id="country"
                                class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
                                <option value="">Sélectionnez le pays</option>
                                @foreach ($countries as $ck => $ctn)
                                    <option value="{{ $ctn }}">{{ $ctn }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
                                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="department">
                                Département <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="department" id="department"
                                class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
                                <option value="">Sélectionnez le département</option>
                                @foreach ($departments as $dk => $dn)
                                    <option value="{{ $dn }}">{{ $dn }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
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
                                    <label class="block text-sm font-medium text-slate-300 mb-2" for="city">
                                        Ville <span class="text-rose-400">*</span>
                                    </label>
                                    <select wire:model.live="city" id="city"
                                        class="w-full bg-slate-950/70 border border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
                                        <option value="">Sélectionnez la ville</option>
                                        @foreach ($cities as $ck => $cn)
                                            <option value="{{ $cn }}">{{ $cn }}</option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                        <p class="mt-1.5 flex items-center gap-1.5 text-sm text-rose-400">
                                            <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Bouton Ajouter / Mettre à jour --}}
                <button type="button" wire:click="{{ $editingUuid ? 'updateTutor' : 'addTutor' }}"
                    wire:loading.attr="disabled"
                    class="w-full group relative overflow-hidden rounded-2xl py-4 font-semibold text-white transition-all duration-300 hover:scale-[1.01] active:scale-[0.98] disabled:opacity-70">

                    <span class="absolute inset-0 bg-gradient-to-r from-amber-600 to-orange-600"></span>
                    <span
                        class="absolute inset-0 bg-gradient-to-r from-amber-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>

                    <span wire:loading.remove wire:target="updateTutor,addTutor"
                        class="relative flex items-center justify-center gap-2.5">
                        <x-lucide-user-plus class="w-5 h-5" />
                        {{ $editingUuid ? 'Mettre à jour le tuteur' : 'Ajouter le tuteur' }}
                    </span>

                    <span wire:loading.flex wire:target="updateTutor,addTutor"
                        class="relative items-center justify-center gap-2.5">
                        <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                        Traitement...
                    </span>
                </button>
            </div>
        @endif
    </div>

    {{-- ===================== LISTE DES TUTEURS ===================== --}}
    <div id="inserts-tutors" class="mt-2">
        @if (count($this->tutors))
            <section class="rounded-[1.75rem] bg-slate-900/80 border border-slate-700/70 overflow-hidden shadow-xl">

                {{-- Header liste --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 bg-slate-950/60 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-500/15 text-teal-300">
                            <x-lucide-list class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="font-semibold text-white text-lg">Tuteurs ajoutés</h4>
                            <p class="text-sm text-slate-400">{{ count($this->tutors) }} enregistrement(s)</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2.5">
                        <button wire:click="finish" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-medium shadow-md shadow-teal-600/20 transition-all active:scale-95">
                            <span wire:loading.remove wire:target="finish" class="flex items-center gap-2">
                                <x-lucide-send class="w-4.5 h-4.5" />
                                Terminer
                            </span>
                            <span wire:loading.flex wire:target="finish" class="items-center gap-2">
                                <x-lucide-loader-2 class="w-4.5 h-4.5 animate-spin" />
                            </span>
                        </button>

                        <button wire:click="clearAddedData"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600/90 hover:bg-rose-500 text-white text-sm font-medium transition-all active:scale-95">
                            <span wire:loading.remove wire:target="clearAddedData" class="flex items-center gap-2">
                                <x-lucide-trash-2 class="w-4 h-4" />
                                Vider
                            </span>
                            <span wire:loading wire:target="clearAddedData" class="flex items-center gap-2">
                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-950/80 text-slate-400 text-xs uppercase tracking-wider">
                                <th class="px-5 py-4 text-left font-medium">N°</th>
                                <th class="px-5 py-4 text-left font-medium">Tuteur</th>
                                <th class="px-5 py-4 text-left font-medium">Email</th>
                                <th class="px-5 py-4 text-left font-medium">Contact</th>
                                <th class="px-5 py-4 text-left font-medium">Naissance</th>
                                <th class="px-5 py-4 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @foreach ($this->tutors as $tutor)
                                <tr wire:key="{{ $tutor['uuid'] }}" class="hover:bg-slate-800/40 transition-colors">

                                    <td class="px-5 py-4 text-slate-500 font-mono text-xs">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-medium text-white">
                                            {{ $tutor['name'] }} {{ $tutor['prenames'] }}
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-xs px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-300 border border-amber-500/25">
                                                {{ $tutor['gender'] }}
                                            </span>
                                            @if (!empty($tutor['job_name']))
                                                <span class="text-xs text-slate-400">{{ $tutor['job_name'] }}</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $tutor['city'] }} · {{ $tutor['department'] }} ·
                                            {{ $tutor['country'] }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-300">
                                        {{ $tutor['email'] }}
                                    </td>

                                    <td class="px-5 py-4 font-mono text-slate-300">
                                        {{ $tutor['contacts'] }}
                                    </td>

                                    <td class="px-5 py-4 text-slate-300">
                                        {{ $tutor['birth_date'] }}
                                    </td>

                                    {{-- ========== BOUTONS D'ACTIONS ========== --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">

                                            {{-- Modifier --}}
                                            <button wire:click="editTutor('{{ $tutor['uuid'] }}')"
                                                wire:loading.attr="disabled"
                                                class="group inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-medium
                                                           bg-sky-500/10 text-sky-300 border border-sky-400/30
                                                           hover:bg-sky-500/20 hover:border-sky-400/50 hover:shadow-[0_0_15px_-3px_rgba(14,165,233,0.4)]
                                                           transition-all duration-200 hover:scale-105 active:scale-95 disabled:opacity-60">
                                                <span wire:loading.remove
                                                    wire:target="editTutor('{{ $tutor['uuid'] }}')"
                                                    class="flex items-center gap-1.5">
                                                    <x-lucide-pen class="w-3.5 h-3.5" />
                                                    Modifier
                                                </span>
                                                <span wire:loading.flex
                                                    wire:target="editTutor('{{ $tutor['uuid'] }}')"
                                                    class="items-center gap-1.5">
                                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>

                                            {{-- Supprimer --}}
                                            <button wire:click="deleteTutor('{{ $tutor['uuid'] }}')"
                                                wire:loading.attr="disabled"
                                                class="group inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-medium
                                                           bg-rose-500/10 text-rose-300 border border-rose-400/30
                                                           hover:bg-rose-500/20 hover:border-rose-400/50 hover:shadow-[0_0_15px_-3px_rgba(244,63,94,0.4)]
                                                           transition-all duration-200 hover:scale-105 active:scale-95 disabled:opacity-60">
                                                <span wire:loading.remove
                                                    wire:target="deleteTutor('{{ $tutor['uuid'] }}')"
                                                    class="flex items-center gap-1.5">
                                                    <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                    Supprimer
                                                </span>
                                                <span wire:loading.flex
                                                    wire:target="deleteTutor('{{ $tutor['uuid'] }}')"
                                                    class="items-center gap-1.5">
                                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
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
                <div class="p-6 border-t border-slate-800 bg-slate-950/40">
                    <button wire:click="finish" wire:loading.attr="disabled"
                        class="w-full group relative overflow-hidden rounded-2xl py-4 font-semibold text-white transition-all duration-300 hover:scale-[1.01] active:scale-[0.98]">

                        <span class="absolute inset-0 bg-gradient-to-r from-teal-600 to-emerald-600"></span>
                        <span
                            class="absolute inset-0 bg-gradient-to-r from-teal-500 to-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>

                        <span wire:loading.remove wire:target="finish"
                            class="relative flex items-center justify-center gap-2.5">
                            <x-lucide-send class="w-5 h-5" />
                            Terminer & Lancer la migration
                        </span>
                        <span wire:loading.flex wire:target="finish"
                            class="relative items-center justify-center gap-2.5">
                            <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                            Traitement en cours...
                        </span>
                    </button>
                </div>
            </section>
        @endif
    </div>
</div>

