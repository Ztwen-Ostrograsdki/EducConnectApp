<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        {{-- ========== HEADER ========== --}}
        <div class="flex items-start gap-4">
            <a href="{{ route('tenant.personnels.page') }}"
                class="mt-1 inline-flex items-center justify-center w-9 h-9 rounded-xl
                      border border-white/[0.08] text-slate-400
                      hover:text-white hover:bg-white/[0.06] transition-all shrink-0">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-violet-400/80 mb-1">
                    Gestion RH
                </p>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Création des personnels
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Ajouts groupés · Mode manuel
                </p>
            </div>
            <a href="{{ route('tenant.personnels.page') }}"
                class="hidden sm:inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                      border border-white/[0.08] text-slate-400
                      hover:bg-white/[0.06] hover:text-white transition-all shrink-0">
                <x-lucide-list class="w-3.5 h-3.5" />
                Liste
            </a>
        </div>

        {{-- ========== ALERTE ========== --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div
                class="flex-1 flex items-start gap-3 rounded-xl border border-violet-500/20 bg-violet-500/10 px-4 py-3">
                <x-lucide-info class="w-4 h-4 text-violet-400 mt-0.5 shrink-0" />
                <p class="text-xs text-violet-200/90 leading-relaxed">
                    Remplissez le formulaire puis <strong class="text-white">Ajouter</strong>.
                    Ensuite lancez la création avec <strong class="text-white">Terminer</strong>.
                </p>
            </div>

            @if (count($this->personnels))
                <div class="flex items-center gap-2 shrink-0">
                    <a href="#inserts-personnels"
                        class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                              bg-amber-500/15 text-amber-400 border border-amber-500/25">
                        <x-lucide-database class="w-3.5 h-3.5" />
                        {{ count($this->personnels) }} en attente
                    </a>
                    <button wire:click="clearAddedData"
                        class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                   bg-rose-500/15 text-rose-400 border border-rose-500/25
                                   hover:bg-rose-500 hover:text-white hover:border-rose-500
                                   transition-all">
                        <span wire:loading.remove wire:target="clearAddedData" class="inline-flex items-center gap-1.5">
                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                            Vider
                        </span>
                        <span wire:loading wire:target="clearAddedData">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                </div>
            @endif
        </div>

        {{-- ========== FORMULAIRE ========== --}}
        <section class="space-y-4">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-violet-400"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Informations
                </h2>
            </div>

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                            for="name">
                            <x-lucide-user class="w-3.5 h-3.5 text-violet-400" />
                            Nom <span class="text-rose-400">*</span>
                        </label>
                        <input wire:model.live="name" type="text" id="name" placeholder="Nom"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      px-3 text-sm text-white placeholder:text-slate-600
                                      focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                      outline-none transition-all" />
                        @error('name')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                            for="prenames">
                            <x-lucide-user class="w-3.5 h-3.5 text-violet-400" />
                            Prénoms <span class="text-rose-400">*</span>
                        </label>
                        <input wire:model.live="prenames" type="text" id="prenames" placeholder="Prénoms"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      px-3 text-sm text-white placeholder:text-slate-600
                                      focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                      outline-none transition-all" />
                        @error('prenames')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                            for="contacts">
                            <x-lucide-phone class="w-3.5 h-3.5 text-sky-400" />
                            Contact
                        </label>
                        <input wire:model.live="contacts" type="text" id="contacts" placeholder="01617777777"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      px-3 text-sm text-white placeholder:text-slate-600
                                      focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                      outline-none transition-all" />
                        @error('contacts')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                            for="gender">
                            <x-lucide-users class="w-3.5 h-3.5 text-amber-400" />
                            Genre <span class="text-rose-400">*</span>
                        </label>
                        <select wire:model.live="gender" id="gender"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                       px-3 text-sm text-white
                                       focus:border-violet-500/50 focus:outline-none transition-all">
                            <option value="">Sélectionnez</option>
                            @foreach ($this->genders as $gk => $g)
                                <option value="{{ $gk }}">{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                            for="birth_date">
                            <x-lucide-cake class="w-3.5 h-3.5 text-pink-400" />
                            Naissance
                        </label>
                        <input wire:model.live="birth_date" type="date" id="birth_date"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      px-3 text-sm text-white
                                      focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                      outline-none transition-all" />
                        @error('birth_date')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                            for="title">
                            <x-lucide-briefcase class="w-3.5 h-3.5 text-fuchsia-400" />
                            Fonction <span class="text-rose-400">*</span>
                        </label>
                        <input wire:model.live="title" type="text" id="title" placeholder="Ex: Secrétaire"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      px-3 text-sm text-white placeholder:text-slate-600
                                      focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                      outline-none transition-all" />
                        @error('title')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                            for="grade">
                            <x-lucide-award class="w-3.5 h-3.5 text-amber-400" />
                            Grade
                        </label>
                        <input wire:model.live="grade" type="text" id="grade" placeholder="Ex: A1"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      px-3 text-sm text-white placeholder:text-slate-600
                                      focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                      outline-none transition-all" />
                        @error('grade')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                            for="since">
                            <x-lucide-calendar class="w-3.5 h-3.5 text-emerald-400" />
                            En poste depuis
                        </label>
                        <input wire:model.live="since" type="date" id="since"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      px-3 text-sm text-white
                                      focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                      outline-none transition-all" />
                        @error('since')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                            for="description">
                            <x-lucide-align-left class="w-3.5 h-3.5 text-violet-400" />
                            Description
                        </label>
                        <input wire:model.live="description" type="text" id="description"
                            placeholder="Notes (optionnel)"
                            class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                      px-3 text-sm text-white placeholder:text-slate-600
                                      focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                      outline-none transition-all" />
                        @error('description')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="button" wire:click="{{ $editingUuid ? 'updatePersonnel' : 'addPersonnel' }}"
                wire:loading.attr="disabled"
                class="w-full h-11 inline-flex items-center justify-center gap-2 rounded-xl text-sm font-medium
                           bg-violet-500 hover:bg-violet-400 text-white
                           shadow-lg shadow-violet-500/20
                           transition-all disabled:opacity-50">
                <span wire:loading.remove wire:target="updatePersonnel,addPersonnel"
                    class="inline-flex items-center gap-2">
                    <x-lucide-user-plus class="w-4 h-4" />
                    {{ $editingUuid ? 'Mettre à jour' : 'Ajouter le personnel' }}
                </span>
                <span wire:loading wire:target="updatePersonnel,addPersonnel" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    Traitement…
                </span>
            </button>
        </section>

        {{-- ========== LISTE EN ATTENTE ========== --}}
        @if (count($this->personnels))
            <section id="inserts-personnels" class="space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            En attente
                            <span class="text-emerald-400 font-mono ml-1">{{ count($this->personnels) }}</span>
                        </h2>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="finish" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-medium
                                       bg-emerald-500 hover:bg-emerald-400 text-white
                                       shadow-lg shadow-emerald-500/20 transition-all disabled:opacity-50">
                            <span wire:loading.remove wire:target="finish" class="inline-flex items-center gap-1.5">
                                <x-lucide-send class="w-3.5 h-3.5" />
                                Terminer
                            </span>
                            <span wire:loading wire:target="finish">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                        <button wire:click="clearAddedData"
                            class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl text-xs font-medium
                                       bg-rose-500/15 text-rose-400 border border-rose-500/25
                                       hover:bg-rose-500 hover:text-white hover:border-rose-500
                                       transition-all">
                            <span wire:loading.remove wire:target="clearAddedData"
                                class="inline-flex items-center gap-1.5">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                Vider
                            </span>
                            <span wire:loading wire:target="clearAddedData">
                                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            </span>
                        </button>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-white/[0.05]">
                                    <th
                                        class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 w-12">
                                        N°</th>
                                    <th
                                        class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Personnel</th>
                                    <th
                                        class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Fonction</th>
                                    <th
                                        class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Contact</th>
                                    <th
                                        class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Depuis</th>
                                    <th
                                        class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/[0.04]">
                                @foreach ($this->personnels as $personnel)
                                    <tr wire:key="{{ $personnel['uuid'] }}"
                                        class="hover:bg-white/[0.02] transition-colors">
                                        <td class="px-4 py-3 text-xs font-mono text-slate-600">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-white text-sm">
                                                {{ $personnel['name'] }} {{ $personnel['prenames'] }}
                                            </p>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span
                                                    class="text-[10px] px-1.5 py-0.5 rounded
                                                             bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                    {{ $personnel['gender'] }}
                                                </span>
                                                @if (!empty($personnel['grade']))
                                                    <span
                                                        class="text-[10px] text-slate-500">{{ $personnel['grade'] }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-400 text-sm">
                                            {{ $personnel['title'] ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-xs text-slate-400">
                                            {{ $personnel['contacts'] ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-500">
                                            {{ $personnel['since'] ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button wire:click="editPersonnel('{{ $personnel['uuid'] }}')"
                                                    wire:loading.attr="disabled"
                                                    class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                               bg-sky-500/10 text-sky-400 border border-sky-500/20
                                                               hover:bg-sky-500 hover:text-white hover:border-sky-500
                                                               transition-all disabled:opacity-50">
                                                    <span wire:loading.remove
                                                        wire:target="editPersonnel('{{ $personnel['uuid'] }}')"
                                                        class="inline-flex items-center gap-1">
                                                        <x-lucide-pen class="w-3 h-3" />
                                                        Modifier
                                                    </span>
                                                    <span wire:loading
                                                        wire:target="editPersonnel('{{ $personnel['uuid'] }}')">
                                                        <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                                    </span>
                                                </button>
                                                <button wire:click="deletePersonnel('{{ $personnel['uuid'] }}')"
                                                    wire:loading.attr="disabled"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                               bg-rose-500/10 text-rose-400 border border-rose-500/20
                                                               hover:bg-rose-500 hover:text-white hover:border-rose-500
                                                               transition-all disabled:opacity-50">
                                                    <span wire:loading.remove
                                                        wire:target="deletePersonnel('{{ $personnel['uuid'] }}')">
                                                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                    </span>
                                                    <span wire:loading
                                                        wire:target="deletePersonnel('{{ $personnel['uuid'] }}')">
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

                    <div class="p-4 border-t border-white/[0.05]">
                        <button wire:click="finish" wire:loading.attr="disabled"
                            class="w-full h-11 inline-flex items-center justify-center gap-2 rounded-xl text-sm font-medium
                                       bg-emerald-500 hover:bg-emerald-400 text-white
                                       shadow-lg shadow-emerald-500/20 transition-all disabled:opacity-50">
                            <span wire:loading.remove wire:target="finish" class="inline-flex items-center gap-2">
                                <x-lucide-send class="w-4 h-4" />
                                Terminer & lancer la création
                            </span>
                            <span wire:loading wire:target="finish" class="inline-flex items-center gap-2">
                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                Traitement…
                            </span>
                        </button>
                    </div>
                </div>
            </section>
        @endif

    </div>
</div>
