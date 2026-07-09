<div class="mx-auto w-full max-w-475">

    {{-- HERO --}}
    <section class="mb-6">
        <div class="rounded-4xl bg-slate-900 border border-slate-800 p-5 sm:p-6 lg:p-8">
            <div class="flex flex-col 2xl:flex-row 2xl:items-center 2xl:justify-between gap-6">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Centre de Notifications</h1>
                        <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">Communication Temps
                            Réel</span>
                        @if ($this->unreadCount > 0)
                            <span
                                class="relative flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 text-red-400 text-xs">
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                                {{ $this->unreadCount }} non lue{{ $this->unreadCount > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>
                    <p class="mt-3 text-slate-400 max-w-3xl">Gérez les notifications, alertes, annonces et
                        communications envoyées aux élèves, parents, enseignants et personnels.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button
                        class="h-11 px-5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 transition-colors duration-200 text-white font-medium">Nouvelle
                        Notification</button>
                    <button
                        class="h-11 px-5 rounded-2xl bg-indigo-500 hover:bg-indigo-600 transition-colors duration-200 text-white font-medium">Historique</button>
                </div>
            </div>
        </div>
    </section>

    {{-- PANNEAU --}}
    <div class="w-full rounded-2xl border border-white/10 bg-gray-900 shadow-2xl overflow-hidden">

        {{-- HEADER : onglets + actions --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 py-3 border-b border-white/10">

            {{-- Onglets segmentés avec indicateur glissant --}}
            <div x-data="{ tab: @entangle('filterType') }" class="relative grid grid-cols-3 w-full sm:w-72 rounded-xl bg-white/5 p-1">
                <div class="absolute inset-y-1 left-1 w-[calc(33.333%-4px)] rounded-lg bg-indigo-500 transition-transform duration-300 ease-out"
                    :style="`transform: translateX(${ tab === 'all' ? '0%' : tab === 'unread' ? '100%' : '200%' })`">
                </div>
                <button wire:click="$set('filterType', 'all')"
                    class="relative z-10 h-8 text-xs font-medium rounded-lg transition-colors duration-200"
                    :class="tab === 'all' ? 'text-white' : 'text-gray-400 hover:text-gray-200'">Toutes</button>
                <button wire:click="$set('filterType', 'unread')"
                    class="relative z-10 h-8 text-xs font-medium rounded-lg transition-colors duration-200"
                    :class="tab === 'unread' ? 'text-white' : 'text-gray-400 hover:text-gray-200'">Non lues</button>
                <button wire:click="$set('filterType', 'read')"
                    class="relative z-10 h-8 text-xs font-medium rounded-lg transition-colors duration-200"
                    :class="tab === 'read' ? 'text-white' : 'text-gray-400 hover:text-gray-200'">Lues</button>
            </div>

            <div class="flex gap-2">
                @if ($this->unreadCount > 0)
                    <button wire:click="markAllAsRead" wire:loading.attr="disabled" wire:target="markAllAsRead"
                        class="text-xs text-gray-100 transition-all duration-200 bg-purple-600 hover:bg-purple-700 active:scale-95 disabled:opacity-50 rounded-2xl px-3 py-2.5">
                        <span wire:loading.remove wire:target="markAllAsRead" class="inline-flex items-center gap-x-2">
                            <x-lucide-check-check class="h-4 w-4" />
                            <span>Tout lire</span>
                        </span>
                        <span wire:loading wire:target="markAllAsRead" class="inline-flex items-center gap-x-2">
                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </button>
                @endif

                @if ($this->notifications->total() > 0)
                    <button wire:click="confirmDeleteAll" wire:loading.attr="disabled" wire:target="deleteAll"
                        class="text-xs text-gray-900 transition-all duration-200 bg-red-400 hover:bg-red-500 hover:text-white active:scale-95 disabled:opacity-50 rounded-2xl px-3 py-2.5">
                        <span wire:loading.remove wire:target="deleteAll" class="inline-flex items-center gap-x-2">
                            <x-lucide-trash class="h-4 w-4" />
                            <span>Tout effacer</span>
                        </span>
                        <span wire:loading wire:target="deleteAll" class="inline-flex items-center gap-x-2">
                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                            <span>En cours...</span>
                        </span>
                    </button>
                @endif
            </div>
        </div>

        {{-- LISTE --}}
        <div wire:loading.class="opacity-40 pointer-events-none" wire:target="gotoPage,previousPage,nextPage,filterType"
            class="transition-opacity duration-200">
            <div class="p-3 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 min-h-40">
                @forelse ($this->notifications as $notif)
                    @php
                        $iconClass = match ($notif['type']) {
                            'success' => 'bg-emerald-400',
                            'warning' => 'bg-orange-400',
                            'error' => 'bg-red-400',
                            default => 'bg-indigo-400',
                        };
                        $ring = match ($notif['type']) {
                            'success' => 'hover:ring-emerald-400/30',
                            'warning' => 'hover:ring-orange-400/30',
                            'error' => 'hover:ring-red-400/30',
                            default => 'hover:ring-indigo-400/30',
                        };
                    @endphp
                    <div wire:key="notif-{{ $notif['id'] }}" wire:transition.duration.300ms x-data
                        x-init="$el.style.opacity = 0;
                        $el.style.transform = 'translateY(10px) scale(0.98)';
                        requestAnimationFrame(() => {
                            $el.style.transition = 'all .35s cubic-bezier(0.16, 1, 0.3, 1)';
                            $el.style.opacity = 1;
                            $el.style.transform = 'translateY(0) scale(1)';
                        })"
                        class="group flex flex-col gap-3 rounded-2xl border border-white/5 bg-white/[0.03] p-4 hover:bg-white/[0.06] hover:ring-1 {{ $ring }} transition-all duration-200 {{ !is_null($notif['read_at']) ? 'opacity-50' : '' }}">

                        <div class="flex gap-x-2.5">
                            <div class="mt-1.5 shrink-0">
                                <div
                                    class="w-2 h-2 rounded-full {{ $iconClass }} {{ is_null($notif['read_at']) ? 'animate-pulse' : '' }}">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-amber-400 truncate font-mono font-semibold">{{ $notif['title'] }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $notif['message'] }}</p>
                                <p class="text-[10px] text-gray-600 mt-1.5">{{ $notif['created_at'] }}</p>
                            </div>
                        </div>

                        <div
                            class="flex gap-1.5 shrink-0 text-sm justify-end opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            @if (is_null($notif['read_at']))
                                <button wire:click="markAsRead('{{ $notif['id'] }}')" wire:loading.attr="disabled"
                                    wire:target="markAsRead('{{ $notif['id'] }}')" title="Marquer comme lu"
                                    class="text-gray-300 hover:text-indigo-400 transition-colors duration-200 bg-gray-700 gap-x-1.5 rounded-2xl flex items-center px-3 py-2 active:scale-95">
                                    <x-lucide-check class="w-3.5 h-3.5" />
                                    <span>Lu</span>
                                </button>
                            @endif
                            <button wire:click="confirmDeleteNotification('{{ $notif['id'] }}')"
                                wire:loading.attr="disabled" wire:target="deleteNotification('{{ $notif['id'] }}')"
                                title="Supprimer"
                                class="text-gray-500 bg-red-500/40 hover:text-red-300 hover:bg-red-500/60 transition-colors duration-200 gap-x-1.5 rounded-2xl flex items-center px-3 py-2 active:scale-95">
                                <x-lucide-x class="w-3.5 h-3.5" />
                                <span>Supprimer</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center gap-3 px-4 py-14 text-center"
                        x-data x-init="$el.style.opacity = 0;
                        requestAnimationFrame(() => {
                            $el.style.transition = 'opacity .4s ease';
                            $el.style.opacity = 1;
                        })">
                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center">
                            <x-lucide-bell-off class="w-6 h-6 text-gray-500" />
                        </div>
                        <p class="text-sm text-gray-500">Aucune notification
                            {{ $this->filterType === 'unread' ? 'non lue' : ($this->filterType === 'read' ? 'lue' : '') }}
                            pour le moment</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- PAGINATION --}}
        @if ($this->notifications->hasPages())
            <div class="flex items-center justify-between gap-4 px-4 py-3 border-t border-white/10">
                <span
                    class="text-xs text-gray-500">{{ $this->notifications->firstItem() }}–{{ $this->notifications->lastItem() }}
                    sur {{ $this->notifications->total() }}</span>

                <div class="flex items-center gap-1.5 flex-wrap">
                    <button wire:click="previousPage" wire:loading.attr="disabled" wire:target="previousPage"
                        @disabled($this->notifications->onFirstPage())
                        class="h-8 w-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 transition-colors duration-200 disabled:opacity-30 disabled:cursor-not-allowed">
                        <x-lucide-chevron-left class="w-4 h-4 text-gray-300" />
                    </button>

                    @foreach ($this->notifications->getUrlRange(1, $this->notifications->lastPage()) as $page => $url)
                        <button wire:click="gotoPage({{ $page }})" @disabled($page === $this->notifications->currentPage())
                            class="h-8 min-w-8 px-2 rounded-lg text-xs font-medium transition-all duration-200 {{ $page === $this->notifications->currentPage() ? 'bg-indigo-500 text-white scale-105' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-gray-200' }}">
                            {{ $page }}
                        </button>
                    @endforeach

                    <button wire:click="nextPage" wire:loading.attr="disabled" wire:target="nextPage"
                        @disabled(!$this->notifications->hasMorePages())
                        class="h-8 w-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 transition-colors duration-200 disabled:opacity-30 disabled:cursor-not-allowed">
                        <x-lucide-chevron-right class="w-4 h-4 text-gray-300" />
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

