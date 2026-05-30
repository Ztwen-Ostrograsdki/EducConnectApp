<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMINISTRATION CENTRALE - {{ $title ?? config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@300;400;500&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    {{-- <script>
        window.__APP__ = @json(\App\Helpers\Support\TenantContext::forJs());
    </script> --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @wireUiScripts
    @livewireStyles
</head>

<body>
    <div class="shell">

        @livewire('app-guard')

        <x-notifications />
        <x-dialog />

        <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

        {{-- SIDEBAR --}}
        <aside class="sidebar" id="sidebar">
            <div class="s-brand">

                <div class="s-brand-content">

                    <div class="s-brand-icon">
                        🎓
                    </div>

                    <span class="s-brand-text">
                        EducConnect
                    </span>

                </div>
                <button type="button" class="s-collapse" onclick="toggleCollapse()">
                    <span id="collapse-icon">
                        ◀
                    </span>
                </button>

            </div>

            {{-- {{ dd(auth()->guard('central')->user()) }} --}}
            <div class="s-school">
                <div class="s-school-inner">
                    <div class="s-school-name">{{ auth()->guard('central')->user()?->name ?? 'Super Admin' }}</div>
                    <div class="s-school-year">
                        <span class="s-school-dot"></span>
                        <livewire:school-year-selector-component key='sidebaar' />
                    </div>
                </div>
            </div>

            <nav class="s-nav">
                <div class="s-section">
                    <a data-sidebar-item href="{{ route('central.dashboard') }}" class="s-link {{ request()->routeIs('central.dashboard') ? 'active' : '' }}">
                        <div class="s-icon">📊</div><span class="s-label">Dashboard - Acceuil</span>
                    </a>
                </div>
                <div class="s-section">
                    <div class="s-section-label">Les demandes d'espace école</div>
                    <a href="{{ route('central.requests.school.space.portal') }}" class="s-link {{ request()->routeIs('central.requests.school.space.portal') ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-calendar-sync class="w-3 h-3 text-orange-400" />
                        </div>
                        <span class="s-label">En attentes</span>
                        <span class="rounded-lg px-3 bg-orange-400/80 text-orange-900 border border-gray-500">
                            {{ __zero(count(getSpace_requests('status', 'pending'))) }}
                        </span>
                    </a>
                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-circle-check-big class="w-3 h-3 text-green-400" />
                        </div>
                        <span class="s-label">Approuvées</span>
                        <span class="rounded-lg px-3 bg-green-400/20 text-green-300 border border-gray-500">
                            {{ __zero(count(getSpace_requests('validated', 1))) }}
                        </span>
                    </a>
                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-circle-x class="w-3 h-3 text-red-400" />
                        </div>
                        <span class="s-label">Rejetées</span>
                        <span class="rounded-lg px-3 bg-red-400/20 text-red-300 border border-gray-500">
                            {{ __zero(count(getSpace_requests('status', 'rejected'))) }}
                        </span>
                    </a>
                </div>
                <div class="s-section">
                    <div class="s-section-label">Les demandes d'abonnement</div>
                    <a href="{{ route('central.pendings.subscriptions.requests.portal') }}" class="s-link {{ request()->routeIs('central.pendings.subscriptions.requests.portal') ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-calendar-sync class="w-3 h-3 text-green-400" />
                        </div>
                        <span class="s-label">En attentes</span>
                        <span class="rounded-lg px-3 bg-orange-400/20 text-orange-300 border border-gray-500">100</span>
                    </a>
                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-circle-check-big class="w-3 h-3 text-red-400" />
                        </div>
                        <span class="s-label">Approuvées</span>
                        <span class="rounded-lg px-3 bg-green-400/20 text-red-300 border border-gray-500">7</span>
                    </a>
                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-circle-x class="w-3 h-3 text-red-400" />
                        </div>
                        <span class="s-label">Rejetées</span>
                        <span class="rounded-lg px-3 bg-red-400/20 text-red-300 border border-gray-500">7</span>
                    </a>
                </div>
                <div class="s-section">
                    <div class="s-section-label">General</div>
                    <div class="s-acc" id="acc-tenants">
                        <div class="s-acc-trigger" onclick="toggleAcc('acc-tenants')">
                            <div class="s-icon">
                                <x-lucide-users class="w-3 h-3 text-gray-900" />
                            </div>
                            <span class="s-label">Les tenants</span>
                            <span class="s-acc-arrow">▶</span>
                        </div>
                        <div class="s-acc-content">
                            <a href="{{ route('central.tenants.portal') }}" class="s-link {{ request()->routeIs('central.tenants.portal') ? 'active' : '' }}" style="font-size:.78rem;">
                                <div class="s-icon" style="font-size:.72rem;">
                                    <x-lucide-circle-pile class="w-3 h-3 text-gray-500" />
                                </div>
                                <span class="s-label">Tous les tenants</span>
                            </a>
                            @for ($i = 1; $i < 4; $i++)
                                <a href="{{ route('central.tenant.profil', ['tenant_uuid' => $i]) }}" class="s-link {{ request()->routeIs('central.tenant.profil') ? 'active' : '' }}" style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">
                                        <x-lucide-user class="w-3 h-3 text-indigo-400" />
                                    </div>
                                    <span class="s-label">
                                        Mr Tenant {{ $i }}
                                    </span>
                                </a>
                            @endfor
                            <a href="#" class="s-link" style="font-size:.78rem;">
                                <div class="s-icon" style="font-size:.72rem;">➕</div><span class="s-label">Nouveau tenant</span>
                            </a>
                        </div>
                    </div>
                    <div class="s-acc" id="acc-schools">
                        <div class="s-acc-trigger" onclick="toggleAcc('acc-schools')">
                            <div class="s-icon">🏫</div>
                            <span class="s-label">Les Ecoles</span>
                            <span class="s-acc-arrow">▶</span>
                        </div>
                        <div class="s-acc-content">
                            <a href="{{ route('central.schools.portal') }}" class="s-link {{ request()->routeIs('central.schools.portal') ? 'active' : '' }}" style="font-size:.78rem;">
                                <div class="s-icon" style="font-size:.72rem;">
                                    <x-lucide-sliders-vertical class="w-3 h-3 text-amber-800" />

                                </div>
                                <span class="s-label">Toutes les écoles</span>
                            </a>
                            @for ($i = 1; $i < 4; $i++)
                                <a href="{{ route('central.school.profil', ['school_uuid' => $i]) }}" class="s-link {{ request()->routeIs('central.school.profil') ? 'active' : '' }}" style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">
                                        <x-lucide-school class="w-3 h-3 text-amber-400" />
                                    </div>
                                    <span class="s-label">
                                        Ecole {{ $i }}
                                    </span>
                                </a>
                            @endfor
                            <a href="#" class="s-link" style="font-size:.78rem;">
                                <div class="s-icon" style="font-size:.72rem;">➕</div><span class="s-label">Nouvelle école</span>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="s-section">
                    <div class="s-section-label">Les abonnements validés</div>
                    <a href="{{ route('central.validateds.subscriptions.portal') }}" class="s-link {{ request()->routeIs('central.validateds.subscriptions.portal') ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-calendar-check-2 class="w-3 h-3 text-green-400" />
                        </div>
                        <span class="s-label">Actifs</span>
                        <span class="rounded-lg px-3 bg-indigo-400/20 text-indigo-300 border border-gray-500">777</span>
                    </a>
                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-copy-x class="w-3 h-3 text-red-400" />
                        </div>
                        <span class="s-label">Expirés</span>
                        <span class="rounded-lg px-3 bg-indigo-400/20 text-red-300 border border-gray-500">7</span>
                    </a>
                </div>

                <div class="s-section">
                    <div class="s-section-label">Finance</div>
                    <a href="#" class="s-link">
                        <div class="s-icon">💳</div><span class="s-label">Paiements</span>
                    </a>
                </div>

                <div class="s-section">
                    <div class="s-section-label">Administration</div>
                    <a href="#" class="s-link">
                        <div class="s-icon">⚙️</div><span class="s-label">Paramètres</span>
                    </a>
                    <a href="#" class="s-link">
                        <div class="s-icon">🔔</div><span class="s-label">Notifications</span>
                    </a>
                </div>
            </nav>

            <div class="s-footer">
                <div class="s-user">
                    <div class="s-avatar">
                        <x-lucide-user-round-cog class="w-5 h-5 text-gray-900" />
                    </div>
                    <div class="s-user-info">
                        <div class="s-user-name">{{ auth()->guard('central')->user()?->name ?? 'Utilisateur' }}</div>
                        <div class="s-user-role">
                            {{ auth()->guard('central')->user()?->name ?? 'Super Admin' }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="s-logout" title="Déconnexion">
                            <x-lucide-log-out class="w-5 h-5 text-red-400" />
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="main" id="main">
            <header class="header">
                <button class="hamburger" onclick="openSidebar()"><span></span><span></span><span></span></button>

                <div class="breadcrumb">
                    <span>EducConnect</span>
                    <span class="breadcrumb-sep">›</span>
                    <span class="breadcrumb-current">{{ $header ?? 'Dashboard' }}</span>
                </div>

                <div class="search" onclick="openSearch()">
                    <span style="font-size:.8rem;color:var(--text3)">🔍</span>
                    <span class="search-text">Rechercher...</span>
                    <span class="search-kbd">Ctrl K</span>
                </div>

                <div class="h-right">
                    <div class="year-switcher">
                        <span class="year-icon">📅</span>

                        <livewire:school-year-selector-component key='navbaar' />
                    </div>

                    <button class="h-btn" title="Thème">🌙</button>

                    <div class="dd">
                        <button class="h-btn" onclick="toggleDD('notif-menu')">
                            🔔 <span class="h-notif-dot">3</span>
                        </button>
                        <div class="dd-menu" id="notif-menu">
                            <div class="dd-head">
                                <div class="dd-title">Notifications</div>
                                <div class="dd-sub">3 non lues</div>
                            </div>
                            <div class="notif-item notif-unread">
                                <div class="notif-title">📝 Nouvelles demandes d'abonnement</div>
                                <div class="notif-time">il y a 5 min</div>
                            </div>
                            <div class="notif-item notif-unread">
                                <div class="notif-title">👤 Nouvelle connexion</div>
                                <div class="notif-time">il y a 1h</div>
                            </div>
                            <div class="notif-item notif-unread">
                                <div class="notif-title">💳 Paiement reçu — Kofi A.</div>
                                <div class="notif-time">il y a 3h</div>
                            </div>
                            <div class="dd-item" style="justify-content:center;color:var(--accent);font-size:.73rem;">Voir toutes →</div>
                        </div>
                    </div>

                    <div class="dd">
                        <div class="user-trigger" onclick="toggleDD('user-menu')">
                            <div class="ut-avatar">
                                <x-lucide-user-round-cog class="w-5 h-5 text-amber-400" />
                            </div>
                            <div>
                                <div class="ut-name">{{ auth()->guard('central')->user()?->name ?? 'Super Admin' }}</div>
                                <div class="ut-role">
                                    {{ 'Super Admin' }}
                                </div>
                            </div>
                            <span class="ut-arrow">▾</span>
                        </div>
                        <div class="dd-menu" id="user-menu">
                            <div class="dd-head">
                                <x-lucide-user-round-cog class="w-5 h-5 text-amber-400" />
                                <div class="dd-title">
                                    {{ auth()->guard('central')->user()?->name ?? 'Super Admin' }}
                                </div>
                                <div class="dd-sub">{{ auth()->guard('central')->user()?->email ?? '' }}</div>
                            </div>
                            <a href="#" class="dd-item">👤 Mon profil</a>
                            <a href="#" class="dd-item">⚙️ Paramètres du compte</a>
                            <a href="#" class="dd-item">❓ Support</a>
                            <div class="dd-sep"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dd-item danger">
                                    Déconnexion
                                    <x-lucide-log-out class="w-5 h-5 text-red-400" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1
            min-w-0
            w-full
            max-w-full
            overflow-x-hidden" id="content">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>

