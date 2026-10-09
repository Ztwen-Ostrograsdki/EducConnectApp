<div class="min-h-screen bg-[#070a12] text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

        {{-- ========== HEADER ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] overflow-hidden">
            <div class="relative p-5 sm:p-6 lg:p-7">
                <div
                    class="absolute top-0 right-0 w-72 h-72 bg-violet-500/10 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/3 pointer-events-none">
                </div>

                <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-violet-500/15 border border-violet-500/25
                                    flex items-center justify-center shrink-0">
                            <x-lucide-users class="w-6 h-6 text-violet-400" />
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                                Portail Personnels
                            </h1>
                            <p class="text-sm text-slate-500 mt-0.5">
                                Liste · Recherche · Filtres · Actions
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('tenant.personnels.create') }}"
                        class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-sm font-medium
                              bg-violet-500 hover:bg-violet-400 text-white
                              shadow-lg shadow-violet-500/20 transition-all shrink-0">
                        <x-lucide-user-plus class="w-4 h-4" />
                        Ajouter
                    </a>
                </div>
            </div>
        </section>

        {{-- ========== FILTRES ========== --}}
        <section class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 sm:p-5 space-y-4">
            <div class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1">
                    <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input wire:model.live.debounce.300ms="search" type="search"
                        placeholder="Rechercher (nom, prénom, fonction, contact…)"
                        class="w-full h-10 rounded-xl bg-[#070a12] border border-white/[0.08]
                                  pl-10 pr-4 text-sm text-white placeholder:text-slate-600
                                  focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/20
                                  outline-none transition-all" />
                </div>
                <select wire:model.live="perPage"
                    class="h-10 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm text-slate-300
                               focus:border-violet-500/50 focus:outline-none transition min-w-[110px]">
                    <option value="8">8 / page</option>
                    <option value="12">12 / page</option>
                    <option value="24">24 / page</option>
                    <option value="48">48 / page</option>
                </select>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div>
                    <label
                        class="block text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">Genre</label>
                    <select wire:model.live="filterGender"
                        class="w-full h-10 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm text-white
                                   focus:border-violet-500/50 focus:outline-none transition">
                        <option value="">Tous</option>
                        @foreach ($this->genders as $gk => $g)
                            <option value="{{ $gk }}">{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        class="block text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">Statut</label>
                    <select wire:model.live="filterStatus"
                        class="w-full h-10 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm text-white
                                   focus:border-violet-500/50 focus:outline-none transition">
                        <option value="">Tous</option>
                        <option value="active">Actifs</option>
                        <option value="inactive">Inactifs</option>
                    </select>
                </div>
                <div>
                    <label
                        class="block text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">Visibilité</label>
                    <select wire:model.live="filterVisibility"
                        class="w-full h-10 rounded-xl bg-[#070a12] border border-white/[0.08] px-3 text-sm text-white
                                   focus:border-violet-500/50 focus:outline-none transition">
                        <option value="">Tous</option>
                        <option value="visible">Visibles</option>
                        <option value="hidden">Masqués</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button wire:click="clearFilters" type="button"
                        class="w-full h-10 inline-flex items-center justify-center gap-2 rounded-xl text-sm font-medium
                                   bg-white/[0.04] border border-white/[0.08] text-slate-400
                                   hover:bg-white/[0.08] hover:text-white transition-all">
                        <span wire:loading.remove wire:target="clearFilters" class="inline-flex items-center gap-2">
                            <x-lucide-rotate-ccw class="w-3.5 h-3.5" />
                            Réinitialiser
                        </span>
                        <span wire:loading wire:target="clearFilters">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                </div>
            </div>
        </section>

        {{-- ========== LOADING ========== --}}
        <div wire:loading.flex
            wire:target="search,filterGender,filterStatus,filterVisibility,perPage,sortBy,clearFilters"
            class="flex flex-col items-center justify-center py-16 gap-3">
            <x-lucide-loader-2 class="w-8 h-8 text-violet-400 animate-spin" />
            <p class="text-sm text-slate-500">Chargement…</p>
        </div>

        {{-- ========== LISTE ========== --}}
        <div wire:loading.remove
            wire:target="search,filterGender,filterStatus,filterVisibility,perPage,sortBy,clearFilters">

            @if ($this->personnels->isEmpty())
                <div class="rounded-2xl border border-dashed border-white/[0.08] bg-white/[0.02] py-16 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-violet-500/10 mb-4">
                        <x-lucide-users class="w-7 h-7 text-violet-400" />
                    </div>
                    <p class="text-slate-400 font-medium">Aucun personnel trouvé</p>
                    <p class="text-sm text-slate-600 mt-1 mb-5">Modifiez vos filtres ou ajoutez un personnel.</p>
                    <a href="{{ route('tenant.personnels.create') }}"
                        class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-sm font-medium
                              bg-violet-500 hover:bg-violet-400 text-white transition-all">
                        <x-lucide-user-plus class="w-4 h-4" />
                        Ajouter
                    </a>
                </div>
            @else
                {{-- Stats + tri --}}
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-slate-500">
                        <span class="font-semibold text-white">{{ $this->personnels->total() }}</span>
                        personnel(s)
                        @if ($search || $filterGender || $filterStatus || $filterVisibility)
                            <span class="text-violet-400/80">· filtres actifs</span>
                        @endif
                    </p>
                    <div class="flex items-center gap-1.5">
                        @foreach (['name' => 'Nom', 'title' => 'Fonction', 'since' => 'Depuis'] as $field => $label)
                            <button wire:click="sortBy('{{ $field }}')" type="button"
                                class="h-8 px-2.5 rounded-lg text-[11px] font-medium border transition-all
                                           {{ $sortField === $field
                                               ? 'bg-violet-500/15 text-violet-300 border-violet-500/30'
                                               : 'border-white/[0.06] text-slate-500 hover:text-slate-300 hover:border-white/10' }}">
                                {{ $label }}
                                @if ($sortField === $field)
                                    <span class="ml-0.5">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Cards --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-4">
                    @foreach ($this->personnels as $personnel)
                        <article wire:key="personnel-{{ $personnel->id }}"
                            class="group rounded-2xl border border-white/[0.06] bg-white/[0.02]
                                        hover:border-violet-500/25 transition-all overflow-hidden
                                        {{ $personnel->hidden ? 'opacity-60' : '' }}">

                            <div class="p-5">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="relative shrink-0">
                                        <img src="{{ $personnel->profil_photo_url }}"
                                            alt="{{ $personnel->full_name }}"
                                            class="h-14 w-14 rounded-xl object-cover border border-white/[0.08]
                                                    group-hover:border-violet-500/40 transition-colors bg-slate-800" />
                                        <button type="button" wire:click="openPhotoModal({{ $personnel->id }})"
                                            wire:loading.attr="disabled" title="Changer la photo"
                                            class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full
                                                       bg-violet-500 border-2 border-[#070a12]
                                                       flex items-center justify-center text-white
                                                       hover:bg-violet-400 transition-colors">
                                            <span wire:loading.remove
                                                wire:target="openPhotoModal({{ $personnel->id }})">
                                                <x-lucide-camera class="w-3 h-3" />
                                            </span>
                                            <span wire:loading wire:target="openPhotoModal({{ $personnel->id }})">
                                                <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                            </span>
                                        </button>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="font-semibold text-white text-sm truncate">
                                                {{ $personnel->name }} {{ $personnel->prenames }}
                                            </h3>
                                            @if ($personnel->hidden)
                                                <span
                                                    class="px-1.5 py-0.5 rounded text-[10px] font-medium
                                                             bg-slate-500/15 text-slate-400 border border-slate-500/20">
                                                    Masqué
                                                </span>
                                            @endif
                                            @if (!$personnel->is_active)
                                                <span
                                                    class="px-1.5 py-0.5 rounded text-[10px] font-medium
                                                             bg-rose-500/15 text-rose-400 border border-rose-500/20">
                                                    Inactif
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-violet-400/90 mt-0.5 truncate">
                                            {{ $personnel->title ?? '—' }}
                                        </p>
                                        <div class="flex flex-wrap gap-1.5 mt-2">
                                            @if ($personnel->gender)
                                                <span
                                                    class="text-[11px] px-2 py-0.5 rounded-md
                                                             bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                    {{ $this->genders[$personnel->gender] ?? $personnel->gender }}
                                                </span>
                                            @endif
                                            @if ($personnel->grade)
                                                <span
                                                    class="text-[11px] px-2 py-0.5 rounded-md
                                                             bg-white/[0.04] text-slate-400 border border-white/[0.06]">
                                                    {{ $personnel->grade }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-1 text-xs text-slate-500 mb-3">
                                    @if ($personnel->contacts)
                                        <div class="flex items-center gap-2">
                                            <x-lucide-phone class="w-3.5 h-3.5 shrink-0" />
                                            <span class="font-mono truncate">{{ $personnel->contacts }}</span>
                                        </div>
                                    @endif
                                    @if ($personnel->since)
                                        <div class="flex items-center gap-2">
                                            <x-lucide-calendar class="w-3.5 h-3.5 shrink-0" />
                                            <span>Depuis {{ $personnel->since->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <p class="text-xs text-slate-500 italic leading-relaxed mb-4 line-clamp-2">
                                    « {{ $personnel->description ?? 'Aucune citation' }} »
                                </p>

                                {{-- Actions --}}
                                <div class="flex flex-wrap items-center gap-1.5 pt-3 border-t border-white/[0.04]">
                                    <a href="{{ route('tenant.personnels.edit', $personnel) }}"
                                        class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                              bg-sky-500/10 text-sky-400 border border-sky-500/20
                                              hover:bg-sky-500 hover:text-white hover:border-sky-500 transition-all">
                                        <x-lucide-pen class="w-3 h-3" />
                                        Éditer
                                    </a>
                                    <button type="button" wire:click="openPhotoModal({{ $personnel->id }})"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                   bg-violet-500/10 text-violet-400 border border-violet-500/20
                                                   hover:bg-violet-500 hover:text-white hover:border-violet-500
                                                   transition-all disabled:opacity-50">
                                        <span wire:loading.remove wire:target="openPhotoModal({{ $personnel->id }})"
                                            class="inline-flex items-center gap-1">
                                            <x-lucide-image class="w-3 h-3" /> Photo
                                        </span>
                                        <span wire:loading wire:target="openPhotoModal({{ $personnel->id }})">
                                            <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                        </span>
                                    </button>
                                    <button type="button" wire:click="toggleHidden({{ $personnel->id }})"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                   bg-amber-500/10 text-amber-400 border border-amber-500/20
                                                   hover:bg-amber-500 hover:text-white hover:border-amber-500
                                                   transition-all disabled:opacity-50">
                                        <span wire:loading.remove wire:target="toggleHidden({{ $personnel->id }})"
                                            class="inline-flex items-center gap-1">
                                            @if ($personnel->hidden)
                                                <x-lucide-eye class="w-3 h-3" /> Afficher
                                            @else
                                                <x-lucide-eye-off class="w-3 h-3" /> Masquer
                                            @endif
                                        </span>
                                        <span wire:loading wire:target="toggleHidden({{ $personnel->id }})">
                                            <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                        </span>
                                    </button>
                                    <a wire:navigate
                                        href="{{ route('tenant.personnels.manage.profil.photo', $personnel) }}"
                                        class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                              bg-purple-500/10 text-purple-400 border border-purple-500/20
                                              hover:bg-purple-500 hover:text-white hover:border-purple-500 transition-all">
                                        <x-lucide-image class="w-3 h-3" />
                                        Gérer photo
                                    </a>
                                    <button type="button" wire:click="confirmDelete({{ $personnel->id }})"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg text-[11px] font-medium
                                                   bg-rose-500/10 text-rose-400 border border-rose-500/20
                                                   hover:bg-rose-500 hover:text-white hover:border-rose-500
                                                   transition-all disabled:opacity-50 ml-auto">
                                        <span wire:loading.remove wire:target="confirmDelete({{ $personnel->id }})"
                                            class="inline-flex items-center gap-1">
                                            <x-lucide-trash-2 class="w-3 h-3" />
                                        </span>
                                        <span wire:loading wire:target="confirmDelete({{ $personnel->id }})">
                                            <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $this->personnels->links() }}
                </div>
            @endif
        </div>

        {{-- ========== MODAL SUPPRESSION ========== --}}
        @if ($showDeleteModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="cancelDelete"></div>
                <div
                    class="relative w-full max-w-md rounded-2xl border border-white/[0.08] bg-[#0c101c] p-6 shadow-2xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-rose-500/15 border border-rose-500/25
                                    flex items-center justify-center">
                            <x-lucide-alert-triangle class="w-5 h-5 text-rose-400" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Confirmer la suppression</h3>
                            <p class="text-xs text-slate-500">Action irréversible</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-300 mb-5">
                        Supprimer <strong class="text-white">{{ $deletingName }}</strong> ?
                    </p>
                    <div class="flex gap-2">
                        <button type="button" wire:click="cancelDelete"
                            class="flex-1 h-10 rounded-xl text-sm font-medium
                                       bg-white/[0.04] border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.08] hover:text-white transition-all">
                            Annuler
                        </button>
                        <button type="button" wire:click="deletePersonnel" wire:loading.attr="disabled"
                            class="flex-1 h-10 rounded-xl text-sm font-medium
                                       bg-rose-500 hover:bg-rose-400 text-white
                                       transition-all disabled:opacity-50
                                       flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="deletePersonnel">Supprimer</span>
                            <span wire:loading wire:target="deletePersonnel" class="inline-flex items-center gap-2">
                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========== MODAL PHOTO ========== --}}
        @if ($showPhotoModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closePhotoModal"></div>
                <div
                    class="relative w-full max-w-md rounded-2xl border border-violet-500/25 bg-[#0c101c] p-6 shadow-2xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-violet-500/15 border border-violet-500/25
                                    flex items-center justify-center">
                            <x-lucide-image class="w-5 h-5 text-violet-400" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Photo de profil</h3>
                            <p class="text-xs text-slate-500 truncate max-w-[200px]">{{ $photoPersonnelName }}</p>
                        </div>
                    </div>

                    <div class="mb-5">
                        <input type="file" wire:model="newProfilPhoto" accept="image/*"
                            class="block w-full text-sm text-slate-400
                                      file:mr-3 file:h-9 file:px-4 file:rounded-lg file:border-0
                                      file:bg-violet-500 file:text-white file:text-sm file:font-medium
                                      file:cursor-pointer hover:file:bg-violet-400
                                      rounded-xl border border-dashed border-white/[0.1] bg-[#070a12] p-3
                                      hover:border-violet-500/40 transition-colors" />
                        <div wire:loading wire:target="newProfilPhoto"
                            class="mt-2 flex items-center gap-2 text-violet-400 text-xs">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            Chargement…
                        </div>
                        @error('newProfilPhoto')
                            <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="button" wire:click="closePhotoModal"
                            class="flex-1 h-10 rounded-xl text-sm font-medium
                                       bg-white/[0.04] border border-white/[0.08] text-slate-400
                                       hover:bg-white/[0.08] hover:text-white transition-all">
                            Annuler
                        </button>
                        <button type="button" wire:click="updatePhoto" wire:loading.attr="disabled"
                            class="flex-1 h-10 rounded-xl text-sm font-medium
                                       bg-violet-500 hover:bg-violet-400 text-white
                                       transition-all disabled:opacity-50
                                       flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="updatePhoto"
                                class="inline-flex items-center gap-2">
                                <x-lucide-upload class="w-4 h-4" />
                                Enregistrer
                            </span>
                            <span wire:loading wire:target="updatePhoto">
                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
