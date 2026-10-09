<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-8">

        {{-- ========== HEADER ========== --}}
        <div class="flex items-start gap-4">
            <a href="{{ route('tenant.personnels.page') }}"
                class="mt-1 inline-flex items-center justify-center w-9 h-9 rounded-xl
                      border border-white/[0.08] text-slate-400
                      hover:text-white hover:bg-white/[0.06] transition-all shrink-0">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>

            <div class="flex items-center gap-4 min-w-0 flex-1">
                <div
                    class="hidden sm:flex shrink-0 w-14 h-14 rounded-xl overflow-hidden
                            border border-violet-500/25 bg-violet-500/10">
                    @if ($personnel->profil_photo_url)
                        <img src="{{ $personnel->profil_photo_url }}" alt="Photo" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <x-lucide-user class="w-6 h-6 text-violet-400" />
                        </div>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-violet-400/80 mb-1">
                        Gestion Ressources humaines - Personnel
                    </p>
                    <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                        Édition du personnel
                    </h1>
                    <p class="text-sm text-slate-400 mt-0.5 truncate">
                        {{ $personnel->full_name }}
                    </p>
                </div>
            </div>
        </div>

        <form wire:submit="update" class="space-y-6">

            {{-- ========== PERSONNELLES ========== --}}
            <section class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-violet-400"></div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Informations personnelles
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
                            <input wire:model.live="name" type="text" id="name"
                                class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                          px-3 text-sm text-white
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
                            <input wire:model.live="prenames" type="text" id="prenames"
                                class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                          px-3 text-sm text-white
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
                            <input wire:model.live="contacts" type="text" id="contacts"
                                class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                          px-3 text-sm text-white
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                                for="birth_date">
                                <x-lucide-cake class="w-3.5 h-3.5 text-pink-400" />
                                Date de naissance
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
                    </div>
                </div>
            </section>

            {{-- ========== PROFESSIONNELLES ========== --}}
            <section class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-fuchsia-400"></div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Informations professionnelles
                    </h2>
                </div>

                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                                for="title">
                                <x-lucide-briefcase class="w-3.5 h-3.5 text-fuchsia-400" />
                                Fonction / Titre <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="title" type="text" id="title"
                                class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                          px-3 text-sm text-white
                                          focus:border-fuchsia-500/50 focus:ring-1 focus:ring-fuchsia-500/20
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
                            <input wire:model.live="grade" type="text" id="grade"
                                class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                          px-3 text-sm text-white
                                          focus:border-fuchsia-500/50 focus:ring-1 focus:ring-fuchsia-500/20
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
                                          focus:border-fuchsia-500/50 focus:ring-1 focus:ring-fuchsia-500/20
                                          outline-none transition-all" />
                            @error('since')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5"
                                for="ended_at">
                                <x-lucide-calendar-x class="w-3.5 h-3.5 text-rose-400" />
                                Date de fin
                            </label>
                            <input wire:model.live="ended_at" type="date" id="ended_at"
                                class="w-full h-11 rounded-xl border border-white/[0.08] bg-[#070a12]
                                          px-3 text-sm text-white
                                          focus:border-fuchsia-500/50 focus:ring-1 focus:ring-fuchsia-500/20
                                          outline-none transition-all" />
                            @error('ended_at')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 mb-1.5">
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500"
                                for="description">
                                <x-lucide-align-left class="w-3.5 h-3.5 text-violet-400" />
                                Description / Notes
                            </label>
                            <button type="button" wire:click="fillRandomCitation" wire:loading.attr="disabled"
                                class="inline-flex items-center gap-1.5 h-7 px-2.5 rounded-lg text-[11px] font-medium
                                           bg-violet-500/10 text-violet-400 border border-violet-500/20
                                           hover:bg-violet-500 hover:text-white hover:border-violet-500
                                           transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="fillRandomCitation"
                                    class="inline-flex items-center gap-1.5">
                                    <x-lucide-sparkles class="w-3 h-3" />
                                    Citation
                                </span>
                                <span wire:loading wire:target="fillRandomCitation">
                                    <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                </span>
                            </button>
                        </div>
                        <textarea wire:model.live="description" id="description" rows="3"
                            placeholder="Notes, description ou citation…"
                            class="w-full rounded-xl border border-white/[0.08] bg-[#070a12]
                                         px-3 py-2.5 text-sm text-white placeholder:text-slate-600
                                         focus:border-fuchsia-500/50 focus:ring-1 focus:ring-fuchsia-500/20
                                         outline-none transition-all resize-none"></textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Switches --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <label
                            class="flex items-center justify-between gap-4 cursor-pointer select-none
                                      rounded-xl border border-white/[0.06] bg-[#070a12]/60 px-4 py-3">
                            <span class="inline-flex items-center gap-2.5 text-sm text-slate-300">
                                <span
                                    class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20
                                             flex items-center justify-center shrink-0">
                                    <x-lucide-check-circle class="w-4 h-4 text-emerald-400" />
                                </span>
                                <span>
                                    <span class="block font-medium">Actif</span>
                                    <span class="block text-[11px] text-slate-600">En activité</span>
                                </span>
                            </span>
                            <button type="button" wire:click="$toggle('is_active')" role="switch"
                                aria-checked="{{ $is_active ? 'true' : 'false' }}"
                                class="relative h-6 w-11 shrink-0 rounded-full transition-colors
                                           {{ $is_active ? 'bg-emerald-500' : 'bg-slate-700' }}">
                                <span
                                    class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow
                                             transition-transform {{ $is_active ? 'translate-x-5' : '' }}"></span>
                            </button>
                        </label>

                        <label
                            class="flex items-center justify-between gap-4 cursor-pointer select-none
                                      rounded-xl border border-white/[0.06] bg-[#070a12]/60 px-4 py-3">
                            <span class="inline-flex items-center gap-2.5 text-sm text-slate-300">
                                <span
                                    class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/20
                                             flex items-center justify-center shrink-0">
                                    <x-lucide-eye-off class="w-4 h-4 text-amber-400" />
                                </span>
                                <span>
                                    <span class="block font-medium">Masqué</span>
                                    <span class="block text-[11px] text-slate-600">Hors liste publique</span>
                                </span>
                            </span>
                            <button type="button" wire:click="$toggle('hidden')" role="switch"
                                aria-checked="{{ $hidden ? 'true' : 'false' }}"
                                class="relative h-6 w-11 shrink-0 rounded-full transition-colors
                                           {{ $hidden ? 'bg-amber-500' : 'bg-slate-700' }}">
                                <span
                                    class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow
                                             transition-transform {{ $hidden ? 'translate-x-5' : '' }}"></span>
                            </button>
                        </label>
                    </div>
                </div>
            </section>

            {{-- ========== PHOTO ========== --}}
            <section class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-violet-400"></div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Photo de profil
                    </h2>
                </div>

                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 space-y-4">
                    @if ($personnel->profil_photo_url)
                        <div class="flex items-center gap-4">
                            <img src="{{ $personnel->profil_photo_url }}" alt="Photo actuelle"
                                class="h-16 w-16 rounded-xl object-cover border border-white/[0.08]">
                            <button type="button" wire:click="removePhoto"
                                class="inline-flex items-center gap-1.5 text-xs text-rose-400
                                           hover:text-rose-300 transition-colors">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                Supprimer
                            </button>
                        </div>
                    @endif

                    <input type="file" wire:model="profil_photo" accept="image/*"
                        class="block w-full text-sm text-slate-400
                                  file:mr-3 file:h-9 file:px-4 file:rounded-lg file:border-0
                                  file:bg-violet-500 file:text-white file:text-sm file:font-medium
                                  file:cursor-pointer hover:file:bg-violet-400
                                  rounded-xl border border-dashed border-white/[0.1] bg-[#070a12] p-3
                                  hover:border-violet-500/40 transition-colors" />

                    <div wire:loading wire:target="profil_photo"
                        class="flex items-center gap-2 text-violet-400 text-xs">
                        <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        Chargement…
                    </div>
                    @error('profil_photo')
                        <p class="text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            {{-- ========== SUBMIT ========== --}}
            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('tenant.personnels.page') }}"
                    class="inline-flex items-center h-10 px-4 rounded-xl text-sm font-medium
                          border border-white/[0.08] text-slate-400
                          hover:bg-white/[0.06] hover:text-white transition-all">
                    Annuler
                </a>
                <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 h-10 px-5 rounded-xl text-sm font-medium
                               bg-violet-500 hover:bg-violet-400 text-white
                               shadow-lg shadow-violet-500/20
                               transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="update" class="inline-flex items-center gap-2">
                        <x-lucide-save class="w-4 h-4" />
                        Enregistrer
                    </span>
                    <span wire:loading wire:target="update" class="inline-flex items-center gap-2">
                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                        Enregistrement…
                    </span>
                </button>
            </div>
        </form>

    </div>
</div>

