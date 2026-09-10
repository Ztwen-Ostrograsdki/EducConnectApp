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
                            Portail Personnels
                        </h1>
                        <p class="mt-1 text-slate-400 text-sm sm:text-base">
                            Liste • Recherche • Filtres • Actions
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('tenant.personnels.create') }}"
                        class="group relative inline-flex items-center gap-3 px-6 py-3.5 rounded-2xl font-semibold text-white overflow-hidden transition-all duration-300 hover:scale-[1.03] active:scale-95">
                        <span class="absolute inset-0 bg-gradient-to-r from-violet-600 to-fuchsia-600"></span>
                        <span
                            class="absolute inset-0 bg-gradient-to-r from-violet-500 to-fuchsia-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        <x-lucide-user-plus class="relative w-5 h-5" />
                        <span class="relative">Ajouter</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FILTRES & RECHERCHE ===================== --}}
    <div class="rounded-[1.75rem] bg-slate-900/70 border-2 border-slate-700 p-5 sm:p-6 space-y-5">

        <div class="flex flex-col lg:flex-row gap-4">
            {{-- Recherche --}}
            <div class="flex-1 relative">
                <x-lucide-search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" />
                <input wire:model.live.debounce.300ms="search" type="search"
                    placeholder="Rechercher (nom, prénom, fonction, contact…)"
                    class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 pl-12 pr-4 text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all" />
            </div>

            {{-- Per page --}}
            <div class="w-full lg:w-36">
                <select wire:model.live="perPage"
                    class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3.5 px-4 text-white focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all">
                    <option value="8">8 / page</option>
                    <option value="12">12 / page</option>
                    <option value="24">24 / page</option>
                    <option value="48">48 / page</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Genre --}}
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Genre</label>
                <select wire:model.live="filterGender"
                    class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all">
                    <option value="">Tous</option>
                    @foreach ($this->genders as $gk => $g)
                        <option value="{{ $gk }}">{{ $g }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Statut --}}
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Statut</label>
                <select wire:model.live="filterStatus"
                    class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all">
                    <option value="">Tous</option>
                    <option value="active">Actifs</option>
                    <option value="inactive">Inactifs</option>
                </select>
            </div>

            {{-- Visibilité --}}
            <div>
                <label
                    class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Visibilité</label>
                <select wire:model.live="filterVisibility"
                    class="w-full bg-slate-950 border-2 border-slate-700 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all">
                    <option value="">Tous</option>
                    <option value="visible">Visibles</option>
                    <option value="hidden">Masqués</option>
                </select>
            </div>

            {{-- Reset --}}
            <div class="flex items-end">
                <button wire:click="clearFilters" type="button"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-800 border-2 border-slate-600 text-slate-300 hover:bg-slate-700 hover:border-slate-500 transition-all text-sm font-semibold active:scale-95">
                    <span wire:loading.remove wire:target="clearFilters" class="flex items-center gap-2">
                        <x-lucide-rotate-ccw class="w-4 h-4" />
                        Réinitialiser
                    </span>
                    <span wire:loading wire:target="clearFilters" class="flex items-center gap-2">
                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===================== LOADING GLOBAL ===================== --}}
    <div wire:loading.flex wire:target="search,filterGender,filterStatus,filterVisibility,perPage,sortBy,clearFilters"
        class="flex flex-col items-center justify-center py-16 gap-4">
        <div class="relative">
            <div class="absolute inset-0 bg-violet-500/30 rounded-full blur-xl animate-pulse"></div>
            <x-lucide-loader-2 class="relative w-12 h-12 text-violet-400 animate-spin" />
        </div>
        <p class="text-slate-400 font-medium">Chargement des personnels…</p>
    </div>

    {{-- ===================== LISTE ===================== --}}
    <div wire:loading.remove
        wire:target="search,filterGender,filterStatus,filterVisibility,perPage,sortBy,clearFilters">

        @if ($this->personnels->isEmpty())
            <div
                class="rounded-[1.75rem] bg-slate-900/50 border-2 border-dashed border-slate-700 py-20 flex flex-col items-center justify-center gap-4">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-800 border border-slate-600">
                    <x-lucide-users class="w-8 h-8 text-slate-500" />
                </div>
                <p class="text-slate-400 font-medium text-lg">Aucun personnel trouvé</p>
                <p class="text-slate-500 text-sm">Modifiez vos filtres ou ajoutez un nouveau personnel.</p>
                <a href="{{ route('tenant.personnels.create') }}"
                    class="mt-2 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-violet-600 text-white font-semibold hover:bg-violet-500 transition-colors">
                    <x-lucide-user-plus class="w-4 h-4" />
                    Ajouter un personnel
                </a>
            </div>
        @else
            {{-- Stats bar --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <p class="text-sm text-slate-400">
                    <span class="font-semibold text-white">{{ $this->personnels->total() }}</span>
                    personnel(s)
                    @if ($search || $filterGender || $filterStatus || $filterVisibility)
                        <span class="text-slate-500">• filtres actifs</span>
                    @endif
                </p>

                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <button wire:click="sortBy('name')" type="button"
                        class="px-3 py-1.5 rounded-lg border border-slate-700 hover:border-violet-500/50 hover:text-violet-300 transition-colors
                            {{ $sortField === 'name' ? 'bg-violet-500/15 text-violet-300 border-violet-500/40' : '' }}">
                        Nom
                        @if ($sortField === 'name')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('title')" type="button"
                        class="px-3 py-1.5 rounded-lg border border-slate-700 hover:border-violet-500/50 hover:text-violet-300 transition-colors
                            {{ $sortField === 'title' ? 'bg-violet-500/15 text-violet-300 border-violet-500/40' : '' }}">
                        Fonction
                        @if ($sortField === 'title')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('since')" type="button"
                        class="px-3 py-1.5 rounded-lg border border-slate-700 hover:border-violet-500/50 hover:text-violet-300 transition-colors
                            {{ $sortField === 'since' ? 'bg-violet-500/15 text-violet-300 border-violet-500/40' : '' }}">
                        Depuis
                        @if ($sortField === 'since')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                </div>
            </div>

            {{-- Cards grid --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                @foreach ($this->personnels as $personnel)
                    <article wire:key="personnel-{{ $personnel->id }}"
                        class="group relative rounded-2xl bg-slate-900/80 border-2 border-slate-700 hover:border-violet-500/40 transition-all duration-300 overflow-hidden shadow-lg
                            {{ $personnel->hidden ? 'opacity-70' : '' }}">

                        {{-- Badge hidden --}}
                        @if ($personnel->hidden)
                            <div
                                class="absolute top-3 left-3 z-10 px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-600 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                Masqué
                            </div>
                        @endif

                        {{-- Badge inactive --}}
                        @if (!$personnel->is_active)
                            <div
                                class="absolute top-3 right-3 z-10 px-2.5 py-1 rounded-lg bg-rose-500/20 border border-rose-500/40 text-[10px] font-bold uppercase tracking-wider text-rose-300">
                                Inactif
                            </div>
                        @endif

                        <div class="p-5">
                            {{-- Photo + identité --}}
                            <div class="flex items-start gap-4 mb-4">
                                <div class="relative shrink-0">
                                    <img src="{{ $personnel->profil_photo_url }}" alt="{{ $personnel->full_name }}"
                                        class="h-16 w-16 rounded-2xl object-cover border-2 border-slate-600 group-hover:border-violet-500/50 transition-colors bg-slate-800" />
                                    <button type="button" wire:click="openPhotoModal({{ $personnel->id }})"
                                        wire:loading.attr="disabled" title="Changer la photo"
                                        class="absolute -bottom-1 -right-1 flex h-7 w-7 items-center justify-center rounded-full bg-violet-600 border-2 border-slate-900 text-white hover:bg-violet-500 transition-colors shadow-lg">
                                        <span wire:loading.remove wire:target="openPhotoModal({{ $personnel->id }})">
                                            <x-lucide-camera class="w-3.5 h-3.5" />
                                        </span>
                                        <span wire:loading wire:target="openPhotoModal({{ $personnel->id }})">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                </div>

                                <div class="min-w-0 flex-1 pt-0.5">
                                    <h3 class="font-bold text-white text-base truncate leading-tight">
                                        {{ $personnel->name }} {{ $personnel->prenames }}
                                    </h3>
                                    <p class="text-sm text-violet-300/90 mt-0.5 truncate">
                                        {{ $personnel->title ?? '—' }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        @if ($personnel->gender)
                                            <span
                                                class="text-[11px] px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                                {{ $this->genders[$personnel->gender] ?? $personnel->gender }}
                                            </span>
                                        @endif
                                        @if ($personnel->grade)
                                            <span
                                                class="text-[11px] px-2 py-0.5 rounded-md bg-slate-700/80 text-slate-300 border border-slate-600">
                                                {{ $personnel->grade }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Infos secondaires --}}
                            <div class="space-y-1.5 text-sm text-slate-400 mb-5">
                                @if ($personnel->contacts)
                                    <div class="flex items-center gap-2">
                                        <x-lucide-phone class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                        <span class="font-mono truncate">{{ $personnel->contacts }}</span>
                                    </div>
                                @endif
                                @if ($personnel->since)
                                    <div class="flex items-center gap-2">
                                        <x-lucide-calendar class="w-3.5 h-3.5 shrink-0 text-slate-500" />
                                        <span>Depuis {{ $personnel->since->format('d/m/Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-1.5 text-sm text-slate-400 mb-5 italic">
                                <p class="text-sm text-slate-400 leading-relaxed italic">
                                    « {{ $personnel->description ?? 'Aucune citation ajoutée' }} »
                                </p>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-between gap-2 pt-4 border-t border-slate-800">

                                <div class="inline-flex justify-start gap-2">
                                    {{-- Éditer --}}
                                    <a href="{{ route('tenant.personnels.edit', $personnel) }}"
                                        class="group/btn relative inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold overflow-hidden transition-all hover:scale-105 active:scale-95">
                                        <span
                                            class="absolute inset-0 bg-sky-500/15 border border-sky-400/40 rounded-xl group-hover/btn:bg-sky-500/25 transition-all"></span>
                                        <span class="relative flex items-center gap-1.5 text-sky-300">
                                            <x-lucide-pen class="w-3.5 h-3.5" />
                                            Éditer
                                        </span>
                                    </a>

                                    {{-- Photo --}}
                                    <button type="button" wire:click="openPhotoModal({{ $personnel->id }})"
                                        wire:loading.attr="disabled"
                                        class="group/btn relative inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold overflow-hidden transition-all hover:scale-105 active:scale-95 disabled:opacity-60">
                                        <span
                                            class="absolute inset-0 bg-violet-500/15 border border-violet-400/40 rounded-xl group-hover/btn:bg-violet-500/25 transition-all"></span>
                                        <span wire:loading.remove wire:target="openPhotoModal({{ $personnel->id }})"
                                            class="relative flex items-center gap-1.5 text-violet-300">
                                            <x-lucide-image class="w-3.5 h-3.5" />
                                            Photo
                                        </span>
                                        <span wire:loading.flex wire:target="openPhotoModal({{ $personnel->id }})"
                                            class="relative items-center gap-1.5 text-violet-300">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>

                                    {{-- Hide / Unhide --}}
                                    <button type="button" wire:click="toggleHidden({{ $personnel->id }})"
                                        wire:loading.attr="disabled"
                                        title="{{ $personnel->hidden ? 'Rendre visible' : 'Masquer' }}"
                                        class="group/btn relative inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold overflow-hidden transition-all hover:scale-105 active:scale-95 disabled:opacity-60">
                                        <span
                                            class="absolute inset-0 bg-amber-500/15 border border-amber-400/40 rounded-xl group-hover/btn:bg-amber-500/25 transition-all"></span>
                                        <span wire:loading.remove wire:target="toggleHidden({{ $personnel->id }})"
                                            class="relative flex items-center gap-1.5 text-amber-300">
                                            @if ($personnel->hidden)
                                                <x-lucide-eye class="w-3.5 h-3.5" />
                                                Afficher
                                            @else
                                                <x-lucide-eye-off class="w-3.5 h-3.5" />
                                                Masquer
                                            @endif
                                        </span>
                                        <span wire:loading.flex wire:target="toggleHidden({{ $personnel->id }})"
                                            class="relative items-center gap-1.5 text-amber-300">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                </div>

                                {{-- Delete --}}
                                <div class="inline-flex justify-end items-center gap-2">
                                    <button type="button" wire:click="confirmDelete({{ $personnel->id }})"
                                        wire:loading.attr="disabled"
                                        class="group/btn relative inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold overflow-hidden transition-all hover:scale-105 active:scale-95 disabled:opacity-60 ml-auto">
                                        <span
                                            class="absolute inset-0 bg-rose-500/15 border border-rose-400/40 rounded-xl group-hover/btn:bg-rose-500/25 transition-all"></span>
                                        <span wire:loading.remove wire:target="confirmDelete({{ $personnel->id }})"
                                            class="relative flex items-center gap-1.5 text-rose-300">
                                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                            Suppr.
                                        </span>
                                        <span wire:loading.flex wire:target="confirmDelete({{ $personnel->id }})"
                                            class="relative items-center gap-1.5 text-rose-300">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                        </span>
                                    </button>
                                    <a wire:navigate
                                        href="{{ route('tenant.personnels.manage.profil.photo', $personnel) }}"
                                        wire:loading.attr="disabled"
                                        class="group/btn relative inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold overflow-hidden transition-all hover:scale-105 disabled:opacity-60 ml-auto">
                                        <span
                                            class="absolute inset-0 bg-purple-500/15 border border-purple-400/40 rounded-xl group-hover/btn:bg-purple-500/25 transition-all"></span>
                                        <span class="relative flex items-center gap-1.5 text-purple-300">
                                            <x-lucide-image class="w-3.5 h-3.5" />
                                            Gérer photo
                                        </span>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $this->personnels->links() }}
            </div>
        @endif
    </div>

    {{-- ===================== MODAL SUPPRESSION ===================== --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="cancelDelete"></div>

            <div
                class="relative w-full max-w-md rounded-2xl bg-slate-900 border-2 border-slate-700 shadow-2xl p-6 sm:p-7">
                <div class="flex items-center gap-4 mb-5">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/20 border border-rose-500/40">
                        <x-lucide-alert-triangle class="w-6 h-6 text-rose-400" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Confirmer la suppression</h3>
                        <p class="text-sm text-slate-400">Cette action est irréversible.</p>
                    </div>
                </div>

                <p class="text-slate-300 mb-6 leading-relaxed">
                    Voulez-vous vraiment supprimer
                    <strong class="text-white">{{ $deletingName }}</strong> ?
                </p>

                <div class="flex gap-3">
                    <button type="button" wire:click="cancelDelete"
                        class="flex-1 px-4 py-3 rounded-xl bg-slate-800 border border-slate-600 text-slate-300 font-semibold hover:bg-slate-700 transition-colors">
                        Annuler
                    </button>
                    <button type="button" wire:click="deletePersonnel" wire:loading.attr="disabled"
                        class="flex-1 px-4 py-3 rounded-xl bg-rose-600 text-white font-semibold hover:bg-rose-500 transition-colors disabled:opacity-70 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="deletePersonnel">Supprimer</span>
                        <span wire:loading wire:target="deletePersonnel" class="flex items-center gap-2">
                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                            Suppression…
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ===================== MODAL PHOTO ===================== --}}
    @if ($showPhotoModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closePhotoModal"></div>

            <div
                class="relative w-full max-w-md rounded-2xl bg-slate-900 border-2 border-violet-500/40 shadow-2xl p-6 sm:p-7">
                <div class="flex items-center gap-4 mb-5">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-500/20 border border-violet-500/40">
                        <x-lucide-image class="w-6 h-6 text-violet-300" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Photo de profil</h3>
                        <p class="text-sm text-slate-400 truncate max-w-[220px]">{{ $photoPersonnelName }}</p>
                    </div>
                </div>

                <div class="mb-5">
                    <input type="file" wire:model="newProfilPhoto" accept="image/*"
                        class="block w-full text-sm text-slate-400
                                  file:mr-4 file:py-2.5 file:px-4
                                  file:rounded-xl file:border-0
                                  file:bg-violet-600 file:text-white file:font-semibold
                                  hover:file:bg-violet-500 file:cursor-pointer
                                  file:transition-all
                                  cursor-pointer rounded-xl border-2 border-dashed border-slate-600 bg-slate-950/50 p-3 hover:border-violet-500/50 transition-colors" />

                    <div wire:loading wire:target="newProfilPhoto"
                        class="mt-3 flex items-center gap-2 text-violet-300 text-sm">
                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                        Chargement de l'image…
                    </div>

                    @error('newProfilPhoto')
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-400">
                            <x-lucide-alert-circle class="w-4 h-4" /> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="button" wire:click="closePhotoModal"
                        class="flex-1 px-4 py-3 rounded-xl bg-slate-800 border border-slate-600 text-slate-300 font-semibold hover:bg-slate-700 transition-colors">
                        Annuler
                    </button>
                    <button type="button" wire:click="updatePhoto" wire:loading.attr="disabled"
                        class="flex-1 px-4 py-3 rounded-xl bg-violet-600 text-white font-semibold hover:bg-violet-500 transition-colors disabled:opacity-70 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="updatePhoto" class="flex items-center gap-2">
                            <x-lucide-upload class="w-4 h-4" />
                            Enregistrer
                        </span>
                        <span wire:loading wire:target="updatePhoto" class="flex items-center gap-2">
                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                            Envoi…
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

