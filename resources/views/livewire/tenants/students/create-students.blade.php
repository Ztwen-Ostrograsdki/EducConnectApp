<div class="flex flex-col gap-6 p-4 sm:p-6 w-full max-w-7xl mx-auto">

    {{-- ===================== HEADER ===================== --}}
    <section
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900/90 via-slate-800/80 to-indigo-950/70 backdrop-blur-xl border border-slate-700/60 shadow-2xl shadow-indigo-500/10">

        {{-- Glow décoratif --}}
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl"></div>

        <div class="relative px-5 py-6 sm:px-8 sm:py-7">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">

                {{-- LEFT --}}
                <div class="flex items-start sm:items-center gap-5">
                    <div class="shrink-0">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-sky-500/10 border border-indigo-400/30 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <x-lucide-user-plus class="h-9 w-9 sm:h-10 sm:w-10 text-indigo-300" />
                        </div>
                    </div>

                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight leading-tight">
                            Gestion des migrations apprenants
                        </h1>
                        <p class="mt-1.5 text-sm sm:text-base text-slate-400">
                            Ajouts et créations • Migrations utilisateurs apprenants
                        </p>
                    </div>
                </div>

                {{-- RIGHT BUTTON --}}
                <a href="{{ route('tenant.students.crud.tasks') }}"
                    class="group inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-medium shadow-lg shadow-blue-600/25 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                    <x-lucide-octagon-alert class="w-5 h-5 group-hover:animate-pulse" />
                    <span>Voir le status des migrations</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== TOGGLE MODE ===================== --}}
    <div class="flex flex-wrap gap-3">
        <button wire:click="toggleImportMode"
            class="relative overflow-hidden px-5 py-3.5 rounded-2xl font-medium transition-all duration-300 flex items-center gap-2.5
                       {{ $showImportMode
                           ? 'bg-slate-700/80 text-slate-300 hover:bg-slate-600/80'
                           : 'bg-gradient-to-r from-emerald-600 to-green-600 text-white shadow-lg shadow-emerald-600/25 hover:shadow-emerald-500/40 hover:scale-[1.02]' }}">

            <span wire:loading.remove wire:target="toggleImportMode" class="flex items-center gap-2">
                @if ($showImportMode)
                    <x-lucide-pen class="w-4.5 h-4.5" />
                    <span>Saisie manuelle</span>
                @else
                    <x-lucide-file-spreadsheet class="w-4.5 h-4.5" />
                    <span>Importer depuis Excel</span>
                @endif
            </span>

            <span wire:loading wire:target="toggleImportMode" class="flex items-center gap-2">
                <x-lucide-loader-2 class="w-4.5 h-4.5 animate-spin" />
                <span>Changement...</span>
            </span>
        </button>
    </div>

    {{-- ===================== ALERTE DONNÉES EN ATTENTE ===================== --}}
    @if (count($this->students))
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 backdrop-blur-sm">
            <a href="#inserts-students"
                class="flex items-center gap-3 text-amber-300 hover:text-amber-200 transition-colors">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/20">
                    <x-lucide-database class="w-5 h-5 animate-pulse" />
                </div>
                <div>
                    <p class="font-semibold">{{ count($this->students) }} donnée(s) en attente</p>
                    <p class="text-sm text-amber-400/80">Cliquez pour voir la liste et lancer la migration</p>
                </div>
            </a>

            <button wire:click="clearAddedData"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-600/90 hover:bg-red-500 text-white text-sm font-medium transition-all hover:scale-[1.02]">
                <span wire:loading.remove wire:target="clearAddedData" class="flex items-center gap-2">
                    <x-lucide-trash-2 class="w-4 h-4" />
                    Vider
                </span>
                <span wire:loading wire:target="clearAddedData" class="flex items-center gap-2">
                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    ...
                </span>
            </button>
        </div>
    @endif

    {{-- ===================== LOADING GLOBAL TOGGLE ===================== --}}
    <div wire:loading wire:target="toggleImportMode"
        class="flex flex-col items-center justify-center py-16 gap-4 text-slate-400">
        <x-lucide-loader-2 class="w-12 h-12 animate-spin text-indigo-400" />
        <p class="text-lg font-medium">Chargement en cours...</p>
    </div>

    <div wire:loading.remove wire:target="toggleImportMode" class="space-y-6">

        {{-- ===================== MODE IMPORT ===================== --}}
        @if ($showImportMode)
            <div class="rounded-3xl bg-slate-900/70 border border-slate-700/60 p-6 sm:p-8 shadow-xl">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/20 text-indigo-300">
                        <x-lucide-file-spreadsheet class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">Mode importation Excel</h3>
                        <p class="text-sm text-slate-400">Sélectionnez un fichier au format attendu</p>
                    </div>
                </div>

                <div class="mb-6 p-4 rounded-2xl bg-slate-800/60 border border-slate-700/50">
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        <span class="font-medium text-slate-300">Colonnes attendues :</span><br>
                        <span class="font-mono text-[11px] sm:text-xs text-indigo-300/90">
                            A=Nom • B=Prénoms • C=Pays • D=Ville • E=Sexe • F=N° EducMaster • G=Department • H=Date de
                            naissance • I=Lieu de naissance • J=Contacts • K=Email • L=Nom de la mère • M=Nom du Père
                        </span>
                    </p>
                </div>

                <input type="file" wire:model="excelFile" accept=".xlsx,.xls"
                    class="block w-full text-sm text-slate-400
                              file:mr-4 file:py-3 file:px-5
                              file:rounded-xl file:border-0
                              file:bg-indigo-600 file:text-white file:font-medium
                              hover:file:bg-indigo-500 file:cursor-pointer
                              file:transition-all file:duration-300
                              cursor-pointer rounded-xl border border-slate-700 bg-slate-800/40 p-2" />

                <div wire:loading wire:target="excelFile" class="mt-5 flex flex-col items-center gap-3 text-indigo-300">
                    <x-lucide-loader-2 class="w-8 h-8 animate-spin" />
                    <span class="text-sm">Lecture du fichier en cours...</span>
                </div>

                @if (!empty($importErrors))
                    <div class="mt-8">
                        <h5
                            class="text-center text-sm font-semibold uppercase tracking-wider text-orange-400 mb-4 pb-2 border-b border-orange-500/30">
                            Lignes ignorées lors de l'importation
                        </h5>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach ($importErrors as $err)
                                <span wire:key="students-import-error-{{ $loop->iteration }}"
                                    class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs bg-red-500/15 text-red-300 border border-red-500/30">
                                    {{ $err }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ===================== MODE MANUEL ===================== --}}
        @else
            <div class="space-y-6">

                {{-- Banner mode --}}
                <div
                    class="flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-sky-500/10 border border-sky-500/25 text-sky-200">
                    <x-lucide-info class="w-5 h-5 shrink-0" />
                    <p class="text-sm">
                        Mode manuel — Renseignez le formulaire puis cliquez sur <strong>« Ajouter »</strong>. Une fois
                        terminé, lancez la migration avec <strong>« Terminer »</strong>.
                    </p>
                </div>

                {{-- ===== Section 1 : Infos personnelles ===== --}}
                <div
                    class="rounded-3xl bg-slate-900/70 border border-slate-700/60 p-5 sm:p-7 shadow-xl transition-all duration-300 hover:border-slate-600/80">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-700/70">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-500/15 text-indigo-300">
                            <x-lucide-user class="w-5 h-5" />
                        </div>
                        <h3 class="font-semibold text-white">Informations personnelles</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="name">
                                Nom <span class="text-red-400">*</span>
                            </label>
                            <input wire:model.live="name" type="text" id="name"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                                placeholder="Nom de l'apprenant">
                            @error('name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="prenames">
                                Prénoms <span class="text-red-400">*</span>
                            </label>
                            <input wire:model.live="prenames" type="text" id="prenames"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                                placeholder="Prénoms de l'apprenant">
                            @error('prenames')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="educMaster">
                                N° EducMaster <span class="text-red-400">*</span>
                            </label>
                            <input wire:model.live="educMaster" type="text" id="educMaster"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                                placeholder="N° EducMaster">
                            @error('educMaster')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="contacts">
                                Contact parent <span class="text-red-400">*</span>
                            </label>
                            <input wire:model.live="contacts" type="text" id="contacts"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                                placeholder="01617777777">
                            @error('contacts')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="email">
                                Email <span class="text-sky-400 text-xs font-normal">(facultatif)</span>
                            </label>
                            <input wire:model.live="email" type="email" id="email"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                                placeholder="email@exemple.com">
                            @error('email')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="birth_date">
                                Date de naissance <span class="text-red-400">*</span>
                            </label>
                            <input wire:model.live="birth_date" type="date" id="birth_date"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                            @error('birth_date')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="birth_place">
                                Lieu de naissance
                            </label>
                            <input wire:model.live="birth_place" type="text" id="birth_place"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                                placeholder="Lieu de naissance">
                            @error('birth_place')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="gender">
                                Genre <span class="text-red-400">*</span>
                            </label>
                            <select wire:model.live="gender" id="gender"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                                <option value="">Sélectionnez</option>
                                @foreach ($this->genders as $gk => $g)
                                    <option value="{{ $gk }}">{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ===== Section 2 : Adresse ===== --}}
                <div
                    class="rounded-3xl bg-slate-900/70 border border-sky-500/30 p-5 sm:p-7 shadow-xl shadow-sky-500/5 transition-all duration-300 hover:border-sky-500/50">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-700/70">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-500/15 text-sky-300">
                            <x-lucide-map-pin class="w-5 h-5" />
                        </div>
                        <h3 class="font-semibold text-white">Adresse actuelle</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="country">
                                Pays <span class="text-red-400">*</span>
                            </label>
                            <select wire:model.live="country" id="country"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500/50 transition-all">
                                <option value="">Sélectionnez le pays</option>
                                @foreach ($this->countries as $ck => $ctn)
                                    <option value="{{ $ctn }}">{{ $ctn }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="department">
                                Département <span class="text-red-400">*</span>
                            </label>
                            <select wire:model.live="department" id="department"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500/50 transition-all">
                                <option value="">Sélectionnez le département</option>
                                @foreach ($this->departments as $dk => $dn)
                                    <option value="{{ $dn }}">{{ $dn }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <div wire:loading wire:target="department"
                                class="flex items-center gap-2 py-3 text-slate-400">
                                <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                                <span>Chargement des villes...</span>
                            </div>

                            @if ($department)
                                <div wire:loading.remove wire:target="department">
                                    <label class="block text-sm font-medium text-slate-300 mb-2" for="city">
                                        Ville <span class="text-red-400">*</span>
                                    </label>
                                    <select wire:model.live="city" id="city"
                                        class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500/50 transition-all">
                                        <option value="">Sélectionnez la ville</option>
                                        @foreach ($cities as $ck => $cn)
                                            <option value="{{ $cn }}">{{ $cn }}</option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                        <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                            <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ===== Section 3 : Géniteurs ===== --}}
                <div class="rounded-3xl bg-slate-900/70 border border-slate-700/60 p-5 sm:p-7 shadow-xl">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-700/70">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-500/15 text-violet-300">
                            <x-lucide-users class="w-5 h-5" />
                        </div>
                        <h3 class="font-semibold text-white">Informations sur les géniteurs <span
                                class="text-slate-400 text-sm font-normal">(si vivant)</span></h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="father_full_name">
                                Nom et prénoms du père <span class="text-sky-400 text-xs">(facultatif)</span>
                            </label>
                            <input wire:model.live="father_full_name" type="text" id="father_full_name"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500/50 transition-all"
                                placeholder="Nom complet du père">
                            @error('father_full_name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="mother_full_name">
                                Nom et prénoms de la mère <span class="text-sky-400 text-xs">(facultatif)</span>
                            </label>
                            <input wire:model.live="mother_full_name" type="text" id="mother_full_name"
                                class="w-full bg-slate-950/60 border border-slate-700/80 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500/50 transition-all"
                                placeholder="Nom complet de la mère">
                            @error('mother_full_name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Bouton Ajouter / Mettre à jour --}}
                <button type="button" wire:click="{{ $editingUuid ? 'updateStudent' : 'addStudent' }}"
                    wire:loading.attr="disabled"
                    class="w-full group relative overflow-hidden rounded-2xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-semibold py-4 px-6 shadow-xl shadow-sky-600/25 transition-all duration-300 hover:scale-[1.01] active:scale-[0.99] disabled:opacity-70">

                    <span wire:loading.remove wire:target="updateStudent,addStudent"
                        class="flex items-center justify-center gap-2.5">
                        <x-lucide-user-plus class="w-5 h-5" />
                        {{ $editingUuid ? 'Mettre à jour l\'apprenant' : 'Ajouter l\'apprenant' }}
                    </span>

                    <span wire:loading.flex wire:target="updateStudent,addStudent"
                        class="items-center justify-center gap-2.5">
                        <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                        Traitement en cours...
                    </span>
                </button>
            </div>
        @endif
    </div>

    {{-- ===================== LISTE DES APPRENANTS ===================== --}}
    <div id="inserts-students" class="mt-4">
        @if (count($this->students))
            <section class="rounded-3xl bg-slate-900/80 border border-slate-700/60 overflow-hidden shadow-2xl">

                {{-- Header liste --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-5 py-4 bg-slate-950/80 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-300">
                            <x-lucide-list class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="font-semibold text-white">Liste des apprenants ajoutés</h4>
                            <p class="text-sm text-slate-400">{{ count($this->students) }} enregistrement(s)</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2.5">
                        <button wire:click="finish" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-medium shadow-lg shadow-emerald-600/20 transition-all hover:scale-[1.02]">
                            <span wire:loading.remove wire:target="finish" class="flex items-center gap-2">
                                <x-lucide-send class="w-4.5 h-4.5" />
                                Terminer
                            </span>
                            <span wire:loading.flex wire:target="finish" class="items-center gap-2">
                                <x-lucide-loader-2 class="w-4.5 h-4.5 animate-spin" />
                                ...
                            </span>
                        </button>

                        <button wire:click="clearAddedData"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-600/90 hover:bg-red-500 text-white text-sm font-medium transition-all hover:scale-[1.02]">
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
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-950/90 text-slate-400 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3.5 font-medium">N°</th>
                                <th class="px-4 py-3.5 font-medium">Nom & Prénoms</th>
                                <th class="px-4 py-3.5 font-medium">Email</th>
                                <th class="px-4 py-3.5 font-medium">Contact</th>
                                <th class="px-4 py-3.5 font-medium">Naissance</th>
                                <th class="px-4 py-3.5 font-medium">Adresse</th>
                                <th class="px-4 py-3.5 font-medium">EducMaster</th>
                                <th class="px-4 py-3.5 font-medium">Père</th>
                                <th class="px-4 py-3.5 font-medium">Mère</th>
                                <th class="px-4 py-3.5 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @foreach ($this->students as $student)
                                <tr wire:key="{{ $student['uuid'] }}"
                                    class="hover:bg-slate-800/50 transition-colors duration-200">

                                    <td class="px-4 py-3.5 text-slate-500 font-mono text-xs">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-4 py-3.5">
                                        <div class="font-medium text-white">
                                            {{ $student['name'] }} {{ $student['prenames'] }}
                                        </div>
                                        <div class="text-xs text-amber-400/90 mt-0.5">
                                            {{ $student['gender'] }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3.5 text-slate-300">
                                        {{ $student['email'] ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3.5 font-mono text-slate-300">
                                        {{ $student['contacts'] ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3.5 text-slate-300">
                                        <div>{{ $student['birth_date'] }}</div>
                                        <div class="text-xs text-slate-500">{{ $student['birth_place'] ?? '—' }}</div>
                                    </td>

                                    <td class="px-4 py-3.5 text-slate-300">
                                        <div>{{ $student['city'] }} <span
                                                class="text-slate-500">({{ $student['department'] }})</span></div>
                                        <div class="text-xs text-slate-500">{{ $student['country'] }}</div>
                                    </td>

                                    <td class="px-4 py-3.5 font-mono text-indigo-300">
                                        {{ $student['educMaster'] ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3.5 text-slate-400 text-xs">
                                        {{ $student['father_full_name'] ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3.5 text-slate-400 text-xs">
                                        {{ $student['mother_full_name'] ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="editStudent('{{ $student['uuid'] }}')"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-sky-500/10 hover:bg-sky-500/20 text-sky-300 text-xs font-medium transition-all hover:scale-105">
                                                <span wire:loading.remove
                                                    wire:target="editStudent('{{ $student['uuid'] }}')"
                                                    class="flex items-center gap-1.5">
                                                    <x-lucide-pen class="w-3.5 h-3.5" />
                                                    Modifier
                                                </span>
                                                <span wire:loading.flex
                                                    wire:target="editStudent('{{ $student['uuid'] }}')"
                                                    class="items-center gap-1.5">
                                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                </span>
                                            </button>

                                            <button wire:click="deleteStudent('{{ $student['uuid'] }}')"
                                                wire:loading.attr="disabled"
                                                title="Retirer {{ $student['name'] }} {{ $student['prenames'] }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-orange-500/10 hover:bg-orange-500/20 text-orange-300 text-xs font-medium transition-all hover:scale-105">
                                                <span wire:loading.remove
                                                    wire:target="deleteStudent('{{ $student['uuid'] }}')"
                                                    class="flex items-center gap-1.5">
                                                    <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                    Retirer
                                                </span>
                                                <span wire:loading.flex
                                                    wire:target="deleteStudent('{{ $student['uuid'] }}')"
                                                    class="items-center gap-1.5">
                                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" /&gt; </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Bouton Terminer en bas --}}
                <div class="p-5 border-t border-slate-800 bg-slate-950/50">
                    <button wire:click="finish" wire:loading.attr="disabled"
                        class="w-full inline-flex items-center justify-center gap-2.5 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-semibold shadow-lg shadow-emerald-600/25 transition-all hover:scale-[1.01]">
                        <span wire:loading.remove wire:target="finish" class="flex items-center gap-2.5">
                            <x-lucide-send class="w-5 h-5" />
                            Terminer & Lancer la migration
                        </span>
                        <span wire:loading.flex wire:target="finish" class="items-center gap-2.5">
                            <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                            Traitement en cours...
                        </span>
                    </button>
                </div>
            </section>
        @endif
    </div>
</div>
