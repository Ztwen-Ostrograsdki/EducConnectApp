<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
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

            <div class="s-school">
                <div class="s-school-inner">
                    <div class="s-school-name">
                        <a class="hover:text-orange-500" href="{{ route('tenants.home') }}">
                            {{ tenant()?->school_name ?? 'Mon École' }}
                        </a>
                    </div>
                </div>
            </div>

            @auth('tenant')
                <nav class="s-nav">
                    <div class="s-section">
                        <div class="s-section-label">Général</div>

                        @if (auth('tenant')->user()?->hasRole('directeur'))
                            <a data-sidebar-item href="{{ route('tenant.dashboard') }}" class="s-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                                <div class="s-icon">📊</div><span class="s-label">Administration</span>
                            </a>
                        @endif
                        <a data-sidebar-item href="{{ route('tenant.my.profil') }}" class="s-link {{ request()->routeIs('tenant.my.profil') ? 'active' : '' }}">
                            <div class="s-icon">
                                <x-lucide-user class="h-3 w-3" />
                            </div><span class="s-label">Mon profil</span>
                        </a>
                    </div>

                    <div class="s-section">
                        <div class="s-section-label">Mon espace enseignant</div>
                        <div class="s-acc" id="acc-classes">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-classes')">
                                <div class="s-icon">🏫</div>
                                <span class="s-label">Mon espace enseignant</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a href="{{ route('tenant.my.teacher.space') }}" class="s-link" style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Dashboard</span>
                                </a>
                                <a href="{{ route('tenant.my.teacher.space.marks') }}" class="s-link" style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Les notes</span>
                                </a>
                                <a href="{{ route('tenant.my.teacher.space.marks.manager') }}" class="s-link" style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Insertion notes</span>
                                </a>
                                <a href="{{ route('tenant.my.teacher.space.students') }}" class="s-link" style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Liste apprenant</span>
                                </a>
                            </div>
                        </div>

                        <a data-sidebar-item href="#" class="s-link">
                            <div class="s-icon">🗓️</div><span class="s-label">Mon Emploi du temps</span>
                        </a>
                    </div>
                    <div class="s-section">
                        <div class="s-section-label">Mon espace parent</div>
                        <div class="s-acc" id="acc-parent-space">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-parent-space')">
                                <div class="s-icon">🏫</div>
                                <span class="s-label">Mon espace parent</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a href="{{ route('tenant.my.parent.space') }}" class="s-link" style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Dashboard</span>
                                </a>
                                <a href="{{ route('tenant.my.parent.space.marks') }}" class="s-link" style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Les notes</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
                <div class="s-footer">
                    <div class="s-user">
                        <div class="s-avatar">{{ strtoupper(substr(Auth::guard('tenant')->user()?->name ?? 'U', 0, 1)) }}</div>
                        <div class="s-user-info">
                            <div class="s-user-name">{{ Auth::guard('tenant')->user()?->name ?? 'Utilisateur' }}</div>
                            <div class="s-user-role">
                                @php $u = Auth::guard('tenant')->user(); @endphp
                                @if ($u?->hasRole('directeur'))
                                    Directeur
                                @elseif($u?->hasRole('enseignant'))
                                    Enseignant
                                @elseif($u?->hasRole('tuteur'))
                                    Parent
                                @elseif($u?->hasRole('eleve'))
                                    Élève
                                @else
                                    Utilisateur
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="s-logout" title="Déconnexion">
                                <x-lucide-log-out class="w-4 h-4 text-red-500" />
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
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

                        <livewire:school-year-selector-component />
                    </div>

                    <button class="h-btn" title="Thème">🌙</button>
                    @auth('tenant')
                        <div class="dd">
                            <button class="h-btn" onclick="toggleDD('notif-menu')">
                                🔔 <span class="h-notif-dot">3</span>
                            </button>
                            <div class="dd-menu" id="notif-menu">
                                <div class="dd-head">
                                    <div class="dd-title">Notifications</div>
                                    <div class="dd-sub">3 non lues</div>
                                </div>

                                <div class="dd-item" style="justify-content:center;color:var(--accent);font-size:.73rem;">Voir toutes →</div>
                            </div>
                        </div>

                        <div class="dd">
                            <div class="user-trigger" onclick="toggleDD('user-menu')">
                                <div class="ut-avatar">{{ strtoupper(substr(Auth::guard('tenant')->user()?->name ?? 'U', 0, 1)) }}</div>
                                <div>
                                    <div class="ut-name">{{ Auth::guard('tenant')->user()?->name ?? '' }}</div>
                                    <div class="ut-role">Directeur</div>
                                </div>
                                <span class="ut-arrow">▾</span>
                            </div>
                            <div class="dd-menu" id="user-menu">
                                <div class="dd-head">
                                    <div class="dd-title">{{ Auth::guard('tenant')->user()?->name ?? '' }}</div>
                                    <div class="dd-sub">{{ Auth::guard('tenant')->user()?->email ?? '' }}</div>
                                </div>
                                <a href="{{ route('tenant.my.profil') }}" class="dd-item">👤 Mon profil</a>
                                <a href="{{ route('tenant.my.profil') }}" class="dd-item">⚙️ Paramètres du compte</a>
                                <div class="dd-sep"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dd-item danger">
                                        <span>Déconnexion</span>
                                        <x-lucide-log-out class="w-4 h-4 text-red-500" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <main class="flex-1
            min-w-0
            w-full
            max-w-full
            overflow-x-hidden p-3" id="content">

                <div class="mx-auto
                w-full
                max-w-[1900px]
                ">
                    <div class="flex flex-wrap items-center gap-3 p-3 bg-indigo-500/10 rounded-4xl my-1.5">
                        <h1 class="text-lg font-bold">
                            Mon epace
                        </h1>
                        <div class="flex items-center text-sky-400 font-mono">
                            <h4>
                                {{ auth('tenant')->user()?->getFullName() }}
                            </h4>
                        </div>
                    </div>
                </div>

                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>

