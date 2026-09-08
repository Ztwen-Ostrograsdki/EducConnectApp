<div class="mx-auto max-w-6xl space-y-8 mb-48" wire:key="personnel-form">

    {{-- HEADER --}}
    <div class="space-y-1">
        <h2 class="text-2xl font-semibold tracking-tight text-white">
            {{ $personnel ? 'Modifier un membre du personnel' : 'Ajouter un membre du personnel' }}
        </h2>
        <p class="text-sm text-slate-400">
            {{ $personnel ? "Mise à jour des informations de {$personnel->getFullName()}" : 'Renseignez les informations du nouveau membre' }}
        </p>
    </div>

    <form wire:submit="save" class="space-y-6">

        {{-- PHOTO --}}
        <div class="rounded-2xl border border-slate-700/60 bg-slate-900/50 p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-sm font-medium text-slate-300">Photo de profil</h3>
                <span class="text-xs text-slate-500">Optionnel</span>
            </div>

            <div class="flex flex-col items-start gap-6 sm:flex-row sm:items-center">
                {{-- Avatar --}}
                <div class="relative group">
                    <div
                        class="h-24 w-24 overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-slate-700/80 shadow-inner">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="h-full w-full object-cover">
                        @elseif ($this->currentPhotoUrl)
                            <img src="{{ $this->currentPhotoUrl }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-slate-500">
                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Upload zone --}}
                <div class="flex-1 space-y-3">
                    <label
                        class="flex cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-slate-600/80 bg-slate-800/40 px-6 py-5 transition hover:border-orange-500/50 hover:bg-slate-800/60">
                        <div class="flex items-center gap-2 text-sm text-slate-300">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <span>Choisir une photo</span>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">PNG, JPG jusqu’à 2 Mo</p>
                        <input type="file" wire:model="photo" accept="image/*" class="hidden" />
                    </label>

                    <div wire:loading wire:target="photo" class="flex items-center gap-2 text-xs text-orange-400">
                        <svg class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        Téléversement en cours…
                    </div>

                    @error('photo')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror

                    @if ($personnel?->profil_photo && !$photo)
                        <button type="button" wire:click="removePhoto" wire:loading.attr="disabled"
                            wire:target="removePhoto"
                            class="text-xs font-medium text-red-400 transition hover:text-red-300 disabled:opacity-50">
                            Retirer la photo actuelle
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- IDENTITÉ --}}
        <div class="rounded-2xl border border-slate-700/60 bg-slate-900/50 p-6 shadow-sm">
            <h3 class="mb-5 text-sm font-medium text-slate-300">Identité</h3>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-300">
                        Nom <span class="text-red-400">*</span>
                    </label>
                    <input type="text" wire:model="name" placeholder="Nom de famille"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    @error('name')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-300">
                        Prénoms <span class="text-red-400">*</span>
                    </label>
                    <input type="text" wire:model="prenames" placeholder="Prénom(s)"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    @error('prenames')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-300">Date de naissance</label>
                    <input type="date" wire:model="birth_date"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    @error('birth_date')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-300">Sexe</label>
                    <select wire:model="gender"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                        <option value="">— Sélectionner —</option>
                        @foreach ($this->genders as $g => $gk)
                            <option value="{{ $gk }}">{{ $g }}</option>
                        @endforeach
                    </select>
                    @error('gender')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5 sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-300">Contact</label>
                    <input type="text" wire:model="contacts" placeholder="Téléphone, email..."
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    @error('contacts')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- FONCTION --}}
        <div class="rounded-2xl border border-slate-700/60 bg-slate-900/50 p-6 shadow-sm">
            <h3 class="mb-5 text-sm font-medium text-slate-300">Fonction</h3>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-300">Poste</label>
                    <input type="text" wire:model="title" placeholder="Ex : Secrétaire administrative"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    @error('title')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-300">Grade</label>
                    <input type="text" wire:model="grade" placeholder="Grade ou échelon"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    @error('grade')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-300">Année scolaire</label>
                    <select wire:model="school_year_id"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                        <option value="">— Sélectionner —</option>
                        @foreach ($this->schoolYears as $year)
                            <option value="{{ $year->id }}">{{ $year->slug }}</option>
                        @endforeach
                    </select>
                    @error('school_year_id')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Depuis le</label>
                        <input type="date" wire:model="since"
                            class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                        @error('since')
                            <p class="text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Jusqu’au</label>
                        <input type="date" wire:model="ended_at"
                            class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                        @error('ended_at')
                            <p class="text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-1.5 sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-300">Description</label>
                    <textarea wire:model="description" rows="3" placeholder="Courte description du rôle ou des responsabilités…"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-800/60 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 transition focus:border-orange-500/70 focus:outline-none focus:ring-2 focus:ring-orange-500/20"></textarea>
                    @error('description')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- VISIBILITÉ --}}
        <div class="rounded-2xl border border-slate-700/60 bg-slate-900/50 p-6 shadow-sm">
            <h3 class="mb-5 text-sm font-medium text-slate-300">Visibilité</h3>

            <div class="flex flex-col gap-4 sm:flex-row sm:gap-10">
                <label class="inline-flex cursor-pointer items-center gap-3">
                    <input type="checkbox" wire:model="is_active"
                        class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-orange-500 focus:ring-orange-500/40">
                    <span class="text-sm text-slate-300">Actif</span>
                </label>

                <label class="inline-flex cursor-pointer items-center gap-3">
                    <input type="checkbox" wire:model="hidden"
                        class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-orange-500 focus:ring-orange-500/40">
                    <span class="text-sm text-slate-300">Masquer de l’annuaire public</span>
                </label>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" wire:loading.attr="disabled" wire:target="save, photo"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500/40 disabled:cursor-not-allowed disabled:opacity-60">
                <span wire:loading wire:target="save">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </span>
                <span wire:loading.remove wire:target="save">
                    {{ $personnel ? 'Enregistrer les modifications' : 'Ajouter le personnel' }}
                </span>
            </button>
        </div>

    </form>
</div>

