<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- ── Sidebar ──────────────────────────────────────────────── --}}
        <aside
            id="sidebar"
            class="w-64 bg-white border-r border-gray-100 flex flex-col shrink-0
                   transition-transform duration-300 ease-out"
        >
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0
                                 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952
                                 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900 text-lg leading-none">EducConnect</span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                {{ $navigation ?? '' }}
            </nav>

            {{-- User info bas de sidebar --}}
            <div class="px-4 py-4 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                        <span class="text-xs font-semibold text-primary-700">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ Auth::user()->name ?? '' }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ Auth::user()->email ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ── Contenu principal ────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Navbar top --}}
            <header class="h-16 bg-white border-b border-gray-100 flex items-center px-6 shrink-0">
                <div class="flex items-center justify-between w-full">

                    {{-- Titre de la page --}}
                    <h1 class="text-lg font-semibold text-gray-900">
                        {{ $header ?? '' }}
                    </h1>

                    {{-- Actions navbar droite --}}
                    <div class="flex items-center gap-3">

                        {{-- Notifications --}}
                        <button class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                                         6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388
                                         6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3
                                         0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </button>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm
                                           text-gray-600 hover:bg-gray-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3
                                             3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Déconnexion
                            </button>
                        </form>

                    </div>
                </div>
            </header>

            {{-- Contenu de la page --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    @livewireScripts
</body>
</html>