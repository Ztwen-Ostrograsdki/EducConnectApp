<div class="flex flex-col gap-7 p-4 sm:p-6 max-w-7xl mx-auto">

    {{-- ===================== HEADER ===================== --}}
    <section
        class="relative overflow-hidden rounded-[2rem] bg-slate-950 border-2 border-violet-500/40 shadow-[0_0_40px_-10px_rgba(139,92,246,0.35)]">

        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-violet-600/20 via-transparent to-transparent">
        </div>
        <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-fuchsia-600/10 rounded-full blur-3xl"></div>

        <div class="relative px-6 py-7 sm:px-8 sm:py-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                <div class="flex items-center gap-5">
                    <div
                        class="flex h-18 w-18 sm:h-20 sm:w-20 items-center justify-center rounded-2xl bg-violet-600/20 border-2 border-violet-400/40 shadow-inner">
                        <x-lucide-users class="h-10 w-10 text-violet-300" />
                    </div>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                            Création des Personnels
                        </h1>
                        <p class="mt-1 text-slate-400 text-sm sm:text-base">
                            Ajouts & Créations • Gestion du personnel administratif
                        </p>
                    </div>
                </div>

                <a href="#"
                    class="group relative inline-flex items-center gap-3 px-6 py-3.5 rounded-2xl font-semibold text-white overflow-hidden transition-all duration-300 hover:scale-[1.03] active:scale-95">
                    <span class="absolute inset-0 bg-gradient-to-r from-violet-600 to-fuchsia-600"></span>
                    <span
                        class="absolute inset-0 bg-gradient-to-r from-violet-500 to-fuchsia-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <x-lucide-list class="relative w-5 h-5" />
                    <span class="relative">Liste des personnels</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== ALERTE + ACTIONS ===================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

        <div class="flex items-start gap-3 px-5 py-4 rounded-2xl bg-violet-500/10 border border-violet-500/30 flex-1">
            <x-lucide-info class="w-5 h-5 text-violet-300 mt-0.5 shrink-0" />
            <p class="text-sm text-violet-100/90 leading-relaxed">
                Mode manuel — Remplissez le formulaire puis cliquez sur <strong class="text-white">Ajouter</strong>.
                Une fois terminé, lancez la création avec le bouton <strong class="text-white">Terminer</strong>.
            </p>
        </div>

        @if (count($this->personnels))
            <div class="flex items-center gap-3">
                <a href="#inserts-personnels"
                    class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-amber-500/15 border border-amber-500/40 text-amber-300 text-sm font-medium animate-pulse">
                    <x-lucide-database class="w-4 h-4" />
                    {{ count($this->personnels) }} en attente
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

    {{-- ===================== FORMULAIRE ===================== --}}
    <div class="space-y-6">

        {{-- ===== Infos personnelles ===== --}}
        <div class="rounded-[1.75rem] bg-slate-900/70 border-2 border-slate-700 p-6 sm:p-7">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-600/20 text-violet-300">
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
                        placeholder="Nom du personnel">
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
                        placeholder="Prénoms du personnel">
                    @error('prenames')
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                            <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="contacts">
                        Contact
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
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="birth_date">
                        Date de naissance
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
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="title">
                        Fonction / Titre <span class="text-rose-400">*</span>
                    </label>
                    <input wire:model.live="title" type="text" id="title"
                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all"
                        placeholder="Ex: Secrétaire administrative">
                    @error('title')
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                            <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="grade">
                        Grade
                    </label>
                    <input wire:model.live="grade" type="text" id="grade"
                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all"
                        placeholder="Ex: A1, B2...">
                    @error('grade')
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                            <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ===== Infos professionnelles ===== --}}
        <div class="rounded-[1.75rem] bg-slate-900/70 border-2 border-fuchsia-500/40 p-6 sm:p-7">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-fuchsia-600/20 text-fuchsia-300">
                    <x-lucide-briefcase class="w-5 h-5" />
                </div>
                <h3 class="text-lg font-bold text-white">Informations professionnelles</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="since">
                        En poste depuis
                    </label>
                    <input wire:model.live="since" type="date" id="since"
                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20 transition-all">
                    @error('since')
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                            <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2" for="description">
                    Description / Notes
                </label>
                <textarea wire:model.live="description" id="description" rows="3"
                    class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20 transition-all"
                    placeholder="Informations complémentaires (optionnel)"></textarea>
                @error('description')
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                        <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Bouton Ajouter / Mettre à jour --}}
        <button type="button" wire:click="{{ $editingUuid ? 'updatePersonnel' : 'addPersonnel' }}"
            wire:loading.attr="disabled"
            class="group relative w-full overflow-hidden rounded-2xl py-4 font-bold text-white transition-all duration-300 active:scale-[0.98] disabled:opacity-70">

            <span
                class="absolute inset-0 bg-gradient-to-r from-violet-600 via-fuchsia-600 to-violet-600 bg-[length:200%_100%] group-hover:animate-[gradient_3s_ease_infinite]"></span>
            <span class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></span>

            <span wire:loading.remove wire:target="updatePersonnel,addPersonnel"
                class="relative flex items-center justify-center gap-2.5 text-lg">
                <x-lucide-user-plus class="w-5 h-5" />
                {{ $editingUuid ? 'Mettre à jour le personnel' : 'Ajouter le personnel' }}
            </span>

            <span wire:loading.flex wire:target="updatePersonnel,addPersonnel"
                class="relative items-center justify-center gap-2.5 text-lg">
                <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                Traitement...
            </span>
        </button>
    </div>

    {{-- ===================== LISTE DES PERSONNELS ===================== --}}
    <div id="inserts-personnels" class="mt-2">
        @if (count($this->personnels))
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
                            <h4 class="font-bold text-white text-lg">Personnels ajoutés</h4>
                            <p class="text-sm text-slate-400">{{ count($this->personnels) }} enregistrement(s)</p>
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
                                <th class="px-5 py-4 text-left font-semibold">Personnel</th>
                                <th class="px-5 py-4 text-left font-semibold">Fonction</th>
                                <th class="px-5 py-4 text-left font-semibold">Contact</th>
                                <th class="px-5 py-4 text-left font-semibold">Depuis</th>
                                <th class="px-5 py-4 text-right font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach ($this->personnels as $personnel)
                                <tr wire:key="{{ $personnel['uuid'] }}"
                                    class="hover:bg-slate-900/60 transition-colors">

                                    <td class="px-5 py-4 text-slate-500 font-mono text-xs">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-white">
                                            {{ $personnel['name'] }} {{ $personnel['prenames'] }}
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-xs px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                                {{ $personnel['gender'] }}
                                            </span>
                                            @if (!empty($personnel['grade']))
                                                <span class="text-xs text-slate-400">{{ $personnel['grade'] }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-300">
                                        {{ $personnel['title'] ?? '—' }}
                                    </td>

                                    <td class="px-5 py-4 font-mono text-slate-300">
                                        {{ $personnel['contacts'] ?? '—' }}
                                    </td>

                                    <td class="px-5 py-4 text-slate-300">
                                        {{ $personnel['since'] ?? '—' }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2.5">

                                            {{-- Bouton Modifier --}}
                                            <button wire:click="editPersonnel('{{ $personnel['uuid'] }}')"
                                                wire:loading.attr="disabled"
                                                class="group relative inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm overflow-hidden transition-all duration-200 hover:scale-105 active:scale-95 disabled:opacity-60">

                                                <span
                                                    class="absolute inset-0 bg-sky-500/15 border border-sky-400/40 rounded-xl group-hover:bg-sky-500/25 group-hover:border-sky-400/60 transition-all"></span>
                                                <span
                                                    class="absolute inset-0 shadow-[0_0_20px_-5px_rgba(14,165,233,0.4)] opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"></span>

                                                <span wire:loading.remove
                                                    wire:target="editPersonnel('{{ $personnel['uuid'] }}')"
                                                    class="relative flex items-center gap-2 text-sky-300">
                                                    <x-lucide-pen class="w-4 h-4" />
                                                    <span>Modifier</span>
                                                </span>
                                                <span wire:loading.flex
                                                    wire:target="editPersonnel('{{ $personnel['uuid'] }}')"
                                                    class="relative items-center gap-2 text-sky-300">
                                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                                </span>
                                            </button>

                                            {{-- Bouton Retirer --}}
                                            <button wire:click="deletePersonnel('{{ $personnel['uuid'] }}')"
                                                wire:loading.attr="disabled"
                                                title="Retirer {{ $personnel['name'] }} {{ $personnel['prenames'] }}"
                                                class="group relative inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm overflow-hidden transition-all duration-200 hover:scale-105 active:scale-95 disabled:opacity-60">

                                                <span
                                                    class="absolute inset-0 bg-rose-500/15 border border-rose-400/40 rounded-xl group-hover:bg-rose-500/25 group-hover:border-rose-400/60 transition-all"></span>
                                                <span
                                                    class="absolute inset-0 shadow-[0_0_20px_-5px_rgba(244,63,94,0.4)] opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"></span>

                                                <span wire:loading.remove
                                                    wire:target="deletePersonnel('{{ $personnel['uuid'] }}')"
                                                    class="relative flex items-center gap-2 text-rose-300">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                    <span>Retirer</span>
                                                </span>
                                                <span wire:loading.flex
                                                    wire:target="deletePersonnel('{{ $personnel['uuid'] }}')"
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
                            Terminer & Lancer la création
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

