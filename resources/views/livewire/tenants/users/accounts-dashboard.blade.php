<div class="min-h-screen bg-[#070b14] text-slate-100" wire:key="accounts-dashboard">
    <div class="mx-auto w-full max-w-[1400px] space-y-6 p-3">

        {{-- ════════════════ HEADER ════════════════ --}}
        <header
            class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-6 shadow-xl shadow-black/20 font-mono">
            <div class="flex flex-col  gap-5">
                <div class="min-w-0">
                    <div
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-300 text-[10px] font-semibold uppercase tracking-wider mb-2">
                        <x-lucide-users class="w-3 h-3" />
                        Accès
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                        Comptes utilisateurs
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Rôles, accès et statut des membres de l’établissement
                    </p>
                </div>

                {{-- Filtres --}}
                <div class="flex flex-col gap-2.5 shrink-0">
                    <div class="relative">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-600">
                            <x-lucide-search class="w-4 h-4" />
                        </span>
                        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Nom, email, contact…"
                            class="w-full  h-10 rounded-xl bg-[#070b14] border border-white/10 pl-10 pr-9 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-orange-500/40 focus:ring-1 focus:ring-orange-500/20 transition-all">
                        <span wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <x-lucide-loader-2 class="w-4 h-4 animate-spin text-orange-400" />
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <select wire:model.live="roleFilter"
                            class="h-10 rounded-xl bg-[#070b14] border border-white/10 px-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500/40 transition-all">
                            <option value="">Tous les rôles</option>
                            @foreach ($this->roles as $r)
                                <option class="uppercase" value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="statusFilter"
                            class="h-10 rounded-xl bg-[#070b14] border border-white/10 px-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500/40 transition-all">
                            <option value="">Tous les statuts</option>
                            <option value="active">Actif</option>
                            <option value="blocked">Bloqué</option>
                        </select>
                    </div>

                </div>
            </div>
        </header>

        {{-- ════════════════ STATS ════════════════ --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 font-mono">
            @foreach ([['Comptes', $this->stats['users'], 'users', 'slate'], ['Profs', $this->stats['users_teachers'], 'graduation-cap', 'orange'], ['Profs en classes', $this->stats['teachers_in_classes'], 'graduation-cap', 'purple'], ['Tuteurs', $this->stats['users_tutors'], 'user', 'sky'], ['Sans rôle', $this->stats['users_without_roles'], 'circle-alert', 'amber'], ['Bloqués', $this->stats['users_blockeds'], 'lock', 'rose']] as [$label, $value, $icon, $color])
                <div
                    class="rounded-xl bg-[#0f1523] border border-white/[0.05] p-4 hover:border-{{ $color }}-500/20 transition-colors">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-lg bg-{{ $color }}-500/10 border border-{{ $color }}-500/20 flex items-center justify-center text-{{ $color }}-400 shrink-0">
                            @switch($icon)
                                @case('users')
                                    <x-lucide-users class="w-4 h-4" />
                                @break

                                @case('graduation-cap')
                                    <x-lucide-graduation-cap class="w-4 h-4" />
                                @break

                                @case('user')
                                    <x-lucide-user class="w-4 h-4" />
                                @break

                                @case('circle-alert')
                                    <x-lucide-circle-alert class="w-4 h-4" />
                                @break

                                @case('lock')
                                    <x-lucide-lock class="w-4 h-4" />
                                @break
                            @endswitch
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-600">
                                {{ $label }}</p>
                            <p class="text-lg font-bold text-white tabular-nums">{{ $value }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ════════════════ LISTE ════════════════ --}}
        <section
            class="relative rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/20 font-mono">

            {{-- Loading --}}
            <div wire:loading.flex wire:target="search,roleFilter,statusFilter,gotoPage,previousPage,nextPage,perPage"
                class="absolute inset-0 z-20 hidden items-center justify-center bg-[#070b14]/70 backdrop-blur-sm">
                <div class="flex items-center gap-3 rounded-xl bg-[#0f1523] border border-white/10 px-5 py-3 shadow-lg">
                    <x-lucide-loader-2 class="w-5 h-5 animate-spin text-orange-400" />
                    <span class="text-sm text-slate-400">Chargement…</span>
                </div>
            </div>

            <div class="divide-y divide-white/[0.04]">
                @forelse ($this->users as $user)
                    @php
                        $orderNumber = $this->users->firstItem() + $loop->iteration - 1;
                        $initials = collect(explode(' ', $user->getFullName()))
                            ->filter()
                            ->take(2)
                            ->map(fn($w) => strtoupper(str()->substr($w, 0, 1)))
                            ->implode('');
                    @endphp

                    <article wire:key="user-row-{{ $user->id }}"
                        class="group flex flex-col xl:flex-row xl:items-center gap-4 px-4 sm:px-5 py-4 hover:bg-white/[0.02] transition-colors">

                        @php
                            $url = '#';

                            if ($user->hasRole('enseignant') && $user->teacher) {
                                $url = route('tenant.teacher.profil', ['teacher_uuid' => $user->teacher->uuid]);
                            } elseif ($user->hasRole('tuteur') && $user->parent) {
                                $url = route('tenant.parent.profil', ['parent_uuid' => $user->parent->uuid]);
                            }

                        @endphp

                        {{-- Identité --}}
                        <a wire:navigate href="{{ $url }}"
                            class="flex items-center gap-3 min-w-0 xl:w-[280px] shrink-0 group">
                            <span
                                class="hidden sm:flex items-center justify-center w-7 h-7 rounded-lg bg-orange-500/10 border border-orange-500/20 text-orange-300 text-[11px] font-bold tabular-nums shrink-0">
                                {{ __zero($orderNumber) }}
                            </span>

                            <div class="relative shrink-0">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center text-xs font-bold text-white">
                                    {{ $initials }}
                                </div>
                                @if (!$user->blocked)
                                    <span
                                        class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-[#0f1523]"></span>
                                @else
                                    <span
                                        class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-rose-500 ring-2 ring-[#0f1523]"></span>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-white truncate group-hover:text-sky-700">
                                    {{ $user->getFullName() }}</p>
                                <p
                                    class="text-[11px] text-slate-500 truncate inline-flex items-center gap-x-1 group-hover:text-sky-700">
                                    <x-lucide-mail class="h-3 w-4 " />
                                    <span>{{ $user->email }}</span>
                                </p>
                            </div>
                        </a>

                        {{-- Rôles --}}
                        <div class="flex flex-wrap items-center gap-1.5 xl:flex-1 min-w-0">
                            @forelse ($user->roles as $role)
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium
                                             {{ $role->name === 'enseignant'
                                                 ? 'bg-orange-500/10 text-orange-300 border border-orange-500/20'
                                                 : 'bg-sky-500/10 text-sky-300 border border-sky-500/20' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $role->name === 'enseignant' ? 'bg-orange-400' : 'bg-sky-400' }}"></span>
                                    {{ ucfirst($role->name) }}
                                </span>
                            @empty
                                <span class="text-xs italic text-slate-600">Aucun rôle</span>
                            @endforelse
                        </div>

                        {{-- Contact + statut --}}
                        <div class="flex items-center gap-3 xl:w-[200px] shrink-0">
                            <span class="text-xs text-slate-500 truncate hidden sm:block">
                                <span
                                    class="inline-flex items-center gap-x-1 px-2 py-0.5 rounded-full bg-slate-500/10 border border-slate-500/20 text-slate-400 ">
                                    <x-lucide-phone class="h-3 w-4" />
                                    {{ $user->contacts ?? '—' }}
                                </span>
                            </span>
                            @if ($user->blocked)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[10px] font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                    Bloqué
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    Actif
                                </span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1.5 xl:justify-end shrink-0">
                            {{-- Enseignant --}}
                            <button type="button" wire:click="toggleRole({{ $user->id }}, 'enseignant')"
                                wire:loading.attr="disabled"
                                wire:target="toggleRole({{ $user->id }}, 'enseignant')"
                                title="{{ $user->hasRole('enseignant') ? 'Retirer Enseignant' : 'Attribuer Enseignant' }}"
                                class="h-8 w-8 rounded-lg flex items-center justify-center text-[11px] font-bold transition-all disabled:opacity-50
                                           {{ $user->hasRole('enseignant')
                                               ? 'bg-orange-500/15 text-orange-400 border border-orange-500/30 hover:bg-orange-500/25'
                                               : 'bg-white/5 text-slate-500 border border-white/10 hover:bg-white/10 hover:text-slate-300' }}">
                                <span wire:loading wire:target="toggleRole({{ $user->id }}, 'enseignant')">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                </span>
                                <span wire:loading.remove
                                    wire:target="toggleRole({{ $user->id }}, 'enseignant')">E</span>
                            </button>

                            {{-- Tuteur --}}
                            <button type="button" wire:click="toggleRole({{ $user->id }}, 'tuteur')"
                                wire:loading.attr="disabled" wire:target="toggleRole({{ $user->id }}, 'tuteur')"
                                title="{{ $user->hasRole('tuteur') ? 'Retirer Tuteur' : 'Attribuer Tuteur' }}"
                                class="h-8 w-8 rounded-lg flex items-center justify-center text-[11px] font-bold transition-all disabled:opacity-50
                                           {{ $user->hasRole('tuteur')
                                               ? 'bg-sky-500/15 text-sky-400 border border-sky-500/30 hover:bg-sky-500/25'
                                               : 'bg-white/5 text-slate-500 border border-white/10 hover:bg-white/10 hover:text-slate-300' }}">
                                <span wire:loading wire:target="toggleRole({{ $user->id }}, 'tuteur')">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                </span>
                                <span wire:loading.remove
                                    wire:target="toggleRole({{ $user->id }}, 'tuteur')">T</span>
                            </button>

                            {{-- Retirer rôles --}}
                            <button type="button" wire:click="removeAllRoles({{ $user->id }})"
                                wire:loading.attr="disabled" wire:target="removeAllRoles({{ $user->id }})"
                                title="Retirer tous les rôles" @disabled($user->roles->isEmpty())
                                class="h-8 w-8 rounded-lg flex items-center justify-center bg-white/5 text-slate-500 border border-white/10
                                           hover:bg-rose-500/15 hover:text-rose-400 hover:border-rose-500/30 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                                <span wire:loading wire:target="removeAllRoles({{ $user->id }})">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                </span>
                                <x-lucide-x wire:loading.remove wire:target="removeAllRoles({{ $user->id }})"
                                    class="w-3.5 h-3.5" />
                            </button>

                            {{-- Bloquer / Débloquer --}}
                            <button type="button" wire:click="toggleBlocked({{ $user->id }})"
                                wire:loading.attr="disabled" wire:target="toggleBlocked({{ $user->id }})"
                                title="{{ $user->blocked ? 'Débloquer' : 'Bloquer' }}"
                                class="h-8 w-8 rounded-lg flex items-center justify-center border transition-all disabled:opacity-50
                                           {{ $user->blocked
                                               ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30 hover:bg-emerald-500/25'
                                               : 'bg-rose-500/10 text-rose-400 border-rose-500/25 hover:bg-rose-500/20' }}">
                                <span wire:loading wire:target="toggleBlocked({{ $user->id }})">
                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                </span>
                                @if ($user->blocked)
                                    <x-lucide-lock-open wire:loading.remove
                                        wire:target="toggleBlocked({{ $user->id }})" class="w-3.5 h-3.5" />
                                @else
                                    <x-lucide-lock wire:loading.remove
                                        wire:target="toggleBlocked({{ $user->id }})" class="w-3.5 h-3.5" />
                                @endif
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                        <div
                            class="w-14 h-14 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center mb-4">
                            <x-lucide-users class="w-6 h-6 text-slate-600" />
                        </div>
                        <p class="text-sm font-medium text-slate-400">Aucun utilisateur trouvé</p>
                        <p class="mt-1 text-xs text-slate-600">Modifiez vos filtres ou votre recherche</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- ════════════════ PAGINATION ════════════════ --}}
        @if ($this->users->hasPages())
            <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-xs text-slate-500">
                    Affichage {{ $this->users->firstItem() }} à {{ $this->users->lastItem() }}
                    sur {{ $this->users->total() }} utilisateurs
                </p>
                <div class="flex items-center gap-1.5 flex-wrap">
                    @if (!$this->users->onFirstPage())
                        <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                            class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                            ← Précédent
                        </button>
                    @endif
                    @foreach ($this->users->getUrlRange(1, $this->users->lastPage()) as $page => $url)
                        <button @disabled($page === $this->users->currentPage()) wire:click="gotoPage({{ $page }})"
                            class="h-9 min-w-[36px] px-2 rounded-lg text-xs font-medium transition-all
                                           {{ $page === $this->users->currentPage()
                                               ? 'bg-violet-600 text-white'
                                               : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300' }}">
                            {{ $page }}
                        </button>
                    @endforeach
                    @if ($this->users->hasMorePages())
                        <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                            class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-slate-300 transition-all disabled:opacity-50">
                            Suivant →
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

