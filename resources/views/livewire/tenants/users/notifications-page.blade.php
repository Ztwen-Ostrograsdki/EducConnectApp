<div class="min-h-screen bg-[#080b12] text-slate-100">
    <div class="mx-auto w-full max-w-[900px] px-4 sm:px-6 py-8">

        {{-- ════════════════ TOP BAR ════════════════ --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-600 mb-1">Centre</p>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                    Notifications
                    @if ($this->unreadCount > 0)
                        <span
                            class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-1.5 rounded-md bg-rose-500 text-white text-xs font-bold tabular-nums">
                            {{ $this->unreadCount }}
                        </span>
                    @endif
                </h1>
            </div>

            <div class="flex gap-2">
                <button
                    class="h-10 px-4 rounded-xl bg-white text-[#080b12] text-sm font-semibold hover:bg-slate-200 transition-all inline-flex items-center gap-2">
                    <x-lucide-pen-line class="w-4 h-4" />
                    Écrire
                </button>
                <button
                    class="h-10 w-10 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400 transition-all flex items-center justify-center">
                    <x-lucide-archive class="w-4 h-4" />
                </button>
            </div>
        </div>

        {{-- ════════════════ FILTERS + ACTIONS ════════════════ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            {{-- Filter chips --}}
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                @foreach ([['all', 'Toutes', null], ['unread', 'Non lues', $this->unreadCount], ['read', 'Lues', null]] as [$key, $label, $count])
                    <button wire:click="$set('filterType', '{{ $key }}')"
                        class="h-8 px-3.5 rounded-full text-xs font-medium whitespace-nowrap transition-all
                                   {{ $this->filterType === $key
                                       ? 'bg-white text-[#080b12] shadow-md'
                                       : 'bg-white/5 text-slate-400 hover:bg-white/10 hover:text-slate-200' }}">
                        {{ $label }}
                        @if ($count)
                            <span class="ml-1 opacity-70">{{ $count }}</span>
                        @endif
                    </button>
                @endforeach
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if ($this->unreadCount > 0)
                    <button wire:click="markAllAsRead" wire:loading.attr="disabled" wire:target="markAllAsRead"
                        class="h-8 px-3 rounded-lg text-xs text-slate-400 hover:text-indigo-300 hover:bg-indigo-500/10 transition-all disabled:opacity-50 inline-flex items-center gap-1.5">
                        <span wire:loading.remove wire:target="markAllAsRead" class="inline-flex items-center gap-1.5">
                            <x-lucide-check-check class="w-3.5 h-3.5" />
                            Tout marquer lu
                        </span>
                        <span wire:loading wire:target="markAllAsRead">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </button>
                @endif
                @if ($this->notifications->total() > 0)
                    <button wire:click="confirmDeleteAll" wire:loading.attr="disabled" wire:target="deleteAll"
                        class="h-8 px-3 rounded-lg text-xs text-slate-500 hover:text-rose-300 hover:bg-rose-500/10 transition-all disabled:opacity-50 inline-flex items-center gap-1.5">
                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                        Effacer
                    </button>
                @endif
            </div>
        </div>

        {{-- ════════════════ LISTE TIMELINE ════════════════ --}}
        <div wire:loading.class="opacity-50 pointer-events-none" wire:target="gotoPage,previousPage,nextPage,filterType"
            class="transition-opacity duration-200">

            <div class="rounded-2xl border border-white/[0.06] bg-[#0e1219] overflow-hidden">
                @forelse ($this->notifications as $notif)
                    @php
                        $isUnread = is_null($notif['read_at']);
                        $config = match ($notif['type']) {
                            'success' => [
                                'icon' => 'circle-check',
                                'bg' => 'bg-emerald-500/15',
                                'text' => 'text-emerald-400',
                                'ring' => 'ring-emerald-500/20',
                            ],
                            'warning' => [
                                'icon' => 'triangle-alert',
                                'bg' => 'bg-amber-500/15',
                                'text' => 'text-amber-400',
                                'ring' => 'ring-amber-500/20',
                            ],
                            'error' => [
                                'icon' => 'circle-x',
                                'bg' => 'bg-rose-500/15',
                                'text' => 'text-rose-400',
                                'ring' => 'ring-rose-500/20',
                            ],
                            default => [
                                'icon' => 'bell',
                                'bg' => 'bg-indigo-500/15',
                                'text' => 'text-indigo-400',
                                'ring' => 'ring-indigo-500/20',
                            ],
                        };
                    @endphp

                    <div wire:key="notif-{{ $notif['id'] }}"
                        class="group relative flex gap-4 px-4 sm:px-5 py-4 border-b border-white/[0.04] last:border-0
                                hover:bg-white/[0.02] transition-colors duration-150
                                {{ $isUnread ? '' : 'opacity-55' }}">

                        {{-- Icône type --}}
                        <div class="shrink-0 mt-0.5">
                            <div
                                class="w-10 h-10 rounded-xl {{ $config['bg'] }} ring-1 {{ $config['ring'] }} flex items-center justify-center {{ $config['text'] }}">
                                @switch($config['icon'])
                                    @case('circle-check')
                                        <x-lucide-circle-check class="w-5 h-5" />
                                    @break

                                    @case('triangle-alert')
                                        <x-lucide-triangle-alert class="w-5 h-5" />
                                    @break

                                    @case('circle-x')
                                        <x-lucide-circle-x class="w-5 h-5" />
                                    @break

                                    @default
                                        <x-lucide-bell class="w-5 h-5" />
                                @endswitch
                            </div>
                        </div>

                        {{-- Contenu --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        @if ($isUnread)
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                                        @endif
                                        <p class="text-sm font-semibold text-white truncate">
                                            {{ $notif['title'] }}
                                        </p>
                                    </div>
                                    <p class="mt-1 text-sm text-slate-400 leading-relaxed line-clamp-2">
                                        {{ $notif['message'] }}
                                    </p>
                                    <p class="mt-2 text-[11px] text-slate-600 font-mono">
                                        {{ $notif['created_at'] }}
                                    </p>
                                </div>

                                {{-- Actions --}}
                                <div
                                    class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                    @if ($isUnread)
                                        <button wire:click="markAsRead('{{ $notif['id'] }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="markAsRead('{{ $notif['id'] }}')" title="Marquer comme lu"
                                            class="w-8 h-8 rounded-lg hover:bg-indigo-500/15 text-slate-500 hover:text-indigo-300 transition-all flex items-center justify-center">
                                            <x-lucide-check class="w-4 h-4" />
                                        </button>
                                    @endif
                                    <button wire:click="confirmDeleteNotification('{{ $notif['id'] }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="deleteNotification('{{ $notif['id'] }}')" title="Supprimer"
                                        class="w-8 h-8 rounded-lg hover:bg-rose-500/15 text-slate-500 hover:text-rose-300 transition-all flex items-center justify-center">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                            <div
                                class="w-16 h-16 rounded-2xl bg-white/[0.03] border border-white/5 flex items-center justify-center mb-4">
                                <x-lucide-bell-off class="w-7 h-7 text-slate-600" />
                            </div>
                            <p class="text-sm font-medium text-slate-400">Boîte vide</p>
                            <p class="mt-1 text-xs text-slate-600 max-w-xs">
                                Aucune notification
                                {{ $this->filterType === 'unread' ? 'non lue' : ($this->filterType === 'read' ? 'lue' : '') }}
                                pour le moment.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ════════════════ PAGINATION ════════════════ --}}
            @if ($this->notifications->hasPages())
                <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <p class="text-xs text-slate-600">
                        {{ $this->notifications->firstItem() }}–{{ $this->notifications->lastItem() }}
                        sur {{ $this->notifications->total() }}
                    </p>
                    <div class="flex items-center gap-1">
                        <button wire:click="previousPage" wire:loading.attr="disabled" @disabled($this->notifications->onFirstPage())
                            class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/5 text-xs text-slate-400 transition-all disabled:opacity-30 inline-flex items-center gap-1">
                            <x-lucide-chevron-left class="w-4 h-4" />
                            Préc.
                        </button>

                        @foreach ($this->notifications->getUrlRange(1, $this->notifications->lastPage()) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})" @disabled($page === $this->notifications->currentPage())
                                class="h-9 min-w-9 px-2 rounded-lg text-xs font-medium transition-all
                                       {{ $page === $this->notifications->currentPage()
                                           ? 'bg-white text-[#080b12]'
                                           : 'bg-white/5 text-slate-500 hover:bg-white/10 border border-white/5' }}">
                                {{ $page }}
                            </button>
                        @endforeach

                        <button wire:click="nextPage" wire:loading.attr="disabled" @disabled(!$this->notifications->hasMorePages())
                            class="h-9 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/5 text-xs text-slate-400 transition-all disabled:opacity-30 inline-flex items-center gap-1">
                            Suiv.
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
