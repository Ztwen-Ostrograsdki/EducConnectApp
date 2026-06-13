    <div class=" mx-auto
                w-full
                max-w-[1900px]
                ">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}
        <section class="mb-6">

            <div class="rounded-[32px]
                        bg-slate-900
                        border border-slate-800
                        p-5 sm:p-6 lg:p-8">

                <div class="flex flex-col
                            2xl:flex-row
                            2xl:items-center
                            2xl:justify-between
                            gap-6">

                    {{-- LEFT --}}
                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-3">

                            <h1 class="text-2xl sm:text-3xl font-bold">

                                Centre de Notifications

                            </h1>

                            <span class="px-3 py-1 rounded-full
                                         bg-indigo-500/10
                                         text-indigo-400 text-xs">

                                Communication Temps Réel

                            </span>

                        </div>

                        <p class="mt-3 text-slate-400 max-w-3xl">

                            Gérez les notifications,
                            alertes, annonces et communications
                            envoyées aux élèves, parents,
                            enseignants et personnels.

                        </p>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-wrap gap-3">

                        <button class="h-11 px-5 rounded-2xl
                                       bg-emerald-500 hover:bg-emerald-600">

                            Nouvelle Notification

                        </button>

                        <button class="h-11 px-5 rounded-2xl
                                       bg-indigo-500 hover:bg-indigo-600">

                            Historique

                        </button>

                    </div>

                </div>

            </div>

        </section>

        {{-- resources/views/livewire/notification-bell.blade.php --}}
        <div class=" h-full">

            {{-- Panneau --}}
            <div class=" mt-2 w-full rounded-xl border border-white/10 bg-gray-900 shadow-2xl z-50">
                {{-- Header --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-white/10">
                    <span class="text-sm font-semibold text-white">Notifications</span>
                    <div class="flex gap-2">
                        @if ($unreadCount > 0)
                            <button wire:click="toutMarquerLu" class="text-xs text-indigo-400 hover:text-indigo-300 transition">
                                Tout lire
                            </button>
                        @endif
                        @if ($notifications->count() > 0)
                            <button wire:click="toutSupprimer" class="text-xs text-red-400 hover:text-red-300 transition">
                                Tout effacer
                            </button>
                        @endif
                    </div>
                </div>

                <div class="overflow-y-auto divide-y divide-white/5 flex flex-wrap p-2 gap-1.5">
                    @foreach ($notifications as $notif)
                        @php
                            $iconClass = match ($notif['type']) {
                                'success' => 'bg-green-400',
                                'warning' => 'bg-orange-400',
                                'error' => 'bg-red-400',
                                default => 'bg-indigo-400',
                            };

                            $bg = match ($notif['type']) {
                                'success' => 'shadow-green-400',
                                'warning' => 'shadow-orange-400',
                                'error' => 'shadow-red-400',
                                default => 'shadow-indigo-400',
                            };
                        @endphp
                        <div class="flex flex-col gap-3 px-4 py-3 rounded-2xl p-3 cursor-pointer shadow-sm {{ $bg }}  hover:bg-white/5 transition
                           {{ !is_null($notif['read_at']) ? 'opacity-40' : '' }}">
                            {{-- Icône selon type --}}
                            <div class="flex gap-x-2">
                                <div class="mt-0.5 shrink-0">
                                    <div class="w-2 h-2 rounded-full mt-1.5 {{ $iconClass }} bg-current"></div>
                                </div>

                                {{-- Contenu --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-amber-600 truncate font-mono font-semibold">{{ $notif['title'] }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5 line-clamp-2 my-2.2 font-normal">{{ $notif['message'] }}</p>
                                    <p class="text-[10px] text-gray-600 mt-1">{{ $notif['created_at'] }}</p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-1 shrink-0 text-sm justify-end">
                                @if (is_null($notif['read_at']))
                                    <button wire:click="marquerLue('{{ $notif['id'] }}')" title="Marquer comme lu" class="text-gray-300 hover:text-indigo-400 transition bg-gray-700 gap-x-1.5 rounded-2xl flex items-center px-3 py-2">
                                        <x-lucide-check class="w-3.5 h-3.5" />
                                        <span>Lu</span>
                                    </button>
                                @endif
                                <button wire:click="supprimer('{{ $notif['id'] }}')" title="Supprimer" class="text-gray-500 bg-red-500/40 hover:text-red-400 transition gap-x-1.5 rounded-2xl flex items-center px-3 py-2">
                                    <x-lucide-x class="w-3.5 h-3.5" />
                                    <span>Supprimer</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if (count($notifications) < 1)
                        <div class="px-4 py-8 text-center text-sm text-gray-500">
                            Aucune notification
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

