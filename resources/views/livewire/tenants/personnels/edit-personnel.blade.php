<div class="flex flex-col gap-7 p-4 sm:p-6 max-w-6xl mx-auto">

    {{-- ===================== HEADER ===================== --}}
    <section
        class="relative overflow-hidden rounded-[2rem] bg-slate-950 border border-violet-500/40 shadow-[0_0_20px_-10px_rgba(139,92,246,0.35)]">

        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-violet-600/20 via-transparent to-transparent">
        </div>

        <div class="relative px-6 py-7 sm:px-8 sm:py-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">

                <div class="flex items-center gap-5">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-600/20 border-2 border-violet-400/40 shadow-inner overflow-hidden">
                        @if ($personnel->profil_photo_url)
                            <img src="{{ $personnel->profil_photo_url }}" alt="Photo"
                                class="h-full w-full object-cover">
                        @else
                            <x-lucide-user class="h-8 w-8 text-violet-300" />
                        @endif
                    </div>

                    <div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                            Édition du personnel
                        </h1>
                        <p class="mt-1 text-slate-400 text-sm">
                            {{ $personnel->full_name }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('tenant.personnels.page') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-slate-800 border border-slate-600 text-slate-300 hover:bg-slate-700 transition-colors font-medium text-sm">
                    <x-lucide-arrow-left class="w-4 h-4" />
                    Retour à la liste
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== FORMULAIRE ===================== --}}
    <form wire:submit="update" class="space-y-6">

        {{-- Infos personnelles --}}
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
                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all">
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
                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all">
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
                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
            </div>
        </div>

        {{-- Infos professionnelles --}}
        <div class="rounded-[1.75rem] bg-slate-900/70 border-2 border-fuchsia-500/40 p-6 sm:p-7">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-fuchsia-600/20 text-fuchsia-300">
                    <x-lucide-briefcase class="w-5 h-5" />
                </div>
                <h3 class="text-lg font-bold text-white">Informations professionnelles</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="title">
                        Fonction / Titre <span class="text-rose-400">*</span>
                    </label>
                    <input wire:model.live="title" type="text" id="title"
                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20 transition-all">
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
                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20 transition-all">
                    @error('grade')
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                            <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                        </p>
                    @enderror
                </div>
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

                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="ended_at">
                        Date de fin (si applicable)
                    </label>
                    <input wire:model.live="ended_at" type="date" id="ended_at"
                        class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20 transition-all">
                    @error('ended_at')
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                            <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Description + bouton citation aléatoire --}}
            <div class="mb-5">
                <div class="flex items-center justify-between gap-3 mb-2">
                    <label class="block text-sm font-semibold text-slate-300" for="description">
                        Description / Notes
                    </label>

                    <button type="button" wire:click="fillRandomCitation" wire:loading.attr="disabled"
                        title="Remplir avec une citation aléatoire"
                        class="group relative inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold overflow-hidden transition-all duration-200 hover:scale-105 active:scale-95 disabled:opacity-60">
                        <span
                            class="absolute inset-0 bg-violet-500/15 border border-violet-400/40 rounded-xl group-hover:bg-violet-500/25 group-hover:border-violet-400/60 transition-all"></span>
                        <span
                            class="absolute inset-0 shadow-[0_0_16px_-4px_rgba(139,92,246,0.4)] opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"></span>

                        <span wire:loading.remove wire:target="fillRandomCitation"
                            class="relative flex items-center gap-2 text-violet-300">
                            <x-lucide-sparkles class="w-4 h-4" />
                            Citation aléatoire
                        </span>
                        <span wire:loading.flex wire:target="fillRandomCitation"
                            class="relative items-center gap-2 text-violet-300">
                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                            Chargement…
                        </span>
                    </button>
                </div>

                <textarea wire:model.live="description" id="description" rows="4"
                    class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white placeholder-slate-500 focus:outline-none focus:border-fuchsia-500 focus:ring-4 focus:ring-fuchsia-500/20 transition-all"
                    placeholder="Notes, description ou citation…"></textarea>
                @error('description')
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                        <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Switches Actif / Masqué --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Switch Actif --}}
                <div
                    class="flex items-center justify-between gap-4 px-4 py-3.5 rounded-xl bg-slate-950/60 border border-slate-700">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-300">
                            <x-lucide-check-circle class="w-4.5 h-4.5" />
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-200">Actif</p>
                            <p class="text-xs text-slate-500">Personnel en activité</p>
                        </div>
                    </div>

                    <button type="button" wire:click="$toggle('is_active')" role="switch"
                        aria-checked="{{ $is_active ? 'true' : 'false' }}"
                        class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:ring-offset-2 focus:ring-offset-slate-900
                            {{ $is_active ? 'bg-emerald-500' : 'bg-slate-600' }}">
                        <span
                            class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}">
                        </span>
                    </button>
                </div>

                {{-- Switch Masqué --}}
                <div
                    class="flex items-center justify-between gap-4 px-4 py-3.5 rounded-xl bg-slate-950/60 border border-slate-700">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/15 text-amber-300">
                            <x-lucide-eye-off class="w-4.5 h-4.5" />
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-200">Masqué</p>
                            <p class="text-xs text-slate-500">Caché de la liste publique</p>
                        </div>
                    </div>

                    <button type="button" wire:click="$toggle('hidden')" role="switch"
                        aria-checked="{{ $hidden ? 'true' : 'false' }}"
                        class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:ring-offset-2 focus:ring-offset-slate-900
                            {{ $hidden ? 'bg-amber-500' : 'bg-slate-600' }}">
                        <span
                            class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $hidden ? 'translate-x-5' : 'translate-x-0' }}">
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Photo de profil --}}
        <div class="rounded-[1.75rem] bg-slate-900/70 border-2 border-slate-700 p-6 sm:p-7">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-600/20 text-violet-300">
                    <x-lucide-image class="w-5 h-5" />
                </div>
                <h3 class="text-lg font-bold text-white">Photo de profil</h3>
            </div>

            @if ($personnel->profil_photo_url)
                <div class="mb-4 flex items-center gap-4">
                    <img src="{{ $personnel->profil_photo_url }}" alt="Photo actuelle"
                        class="h-20 w-20 rounded-xl object-cover border-2 border-slate-600">
                    <button type="button" wire:click="removePhoto"
                        class="text-sm text-rose-400 hover:text-rose-300 font-medium flex items-center gap-1.5">
                        <x-lucide-trash-2 class="w-4 h-4" />
                        Supprimer la photo
                    </button>
                </div>
            @endif

            <input type="file" wire:model="profil_photo" accept="image/*"
                class="block w-full text-sm text-slate-400
                          file:mr-5 file:py-3 file:px-5
                          file:rounded-xl file:border-0
                          file:bg-violet-600 file:text-white file:font-semibold
                          hover:file:bg-violet-500 file:cursor-pointer
                          file:transition-all file:duration-200
                          cursor-pointer rounded-2xl border-2 border-dashed border-slate-600 bg-slate-950/50 p-3 hover:border-violet-500/50 transition-colors" />

            <div wire:loading wire:target="profil_photo" class="mt-3 flex items-center gap-2 text-violet-300 text-sm">
                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                Chargement de l'image...
            </div>

            @error('profil_photo')
                <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                    <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Bouton submit --}}
        <button type="submit" wire:loading.attr="disabled"
            class="group relative w-full overflow-hidden rounded-2xl py-4 font-bold text-white transition-all duration-300 active:scale-[0.98] disabled:opacity-70">

            <span
                class="absolute inset-0 bg-gradient-to-r from-violet-600 via-fuchsia-600 to-violet-600 bg-[length:200%_100%]"></span>
            <span class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></span>

            <span wire:loading.remove wire:target="update"
                class="relative flex items-center justify-center gap-2.5 text-lg">
                <x-lucide-save class="w-5 h-5" />
                Enregistrer les modifications
            </span>

            <span wire:loading.flex wire:target="update" class="relative items-center justify-center gap-2.5 text-lg">
                <x-lucide-loader-2 class="w-5 h-5 animate-spin" />
                Enregistrement...
            </span>
        </button>
    </form>
</div>

