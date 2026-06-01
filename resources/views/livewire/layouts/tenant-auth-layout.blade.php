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

            <nav class="s-nav">
                <div class="s-section">
                    <div class="s-section-label">Général</div>
                    <a data-sidebar-item href="{{ route('tenant.dashboard') }}" class="s-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                        <div class="s-icon">📊</div><span class="s-label">Dashboard</span>
                    </a>
                </div>

                <div class="s-section">
                    <div class="s-section-label">Pédagogie</div>
                    <div class="s-acc" id="acc-classes">
                        <div class="s-acc-trigger" onclick="toggleAcc('acc-classes')">
                            <div class="s-icon">🏫</div>
                            <span class="s-label">Classes</span>
                            <span class="s-acc-arrow">▶</span>
                        </div>
                        <div class="s-acc-content">
                            <a href="{{ route('tenant.classes.portal') }}" class="s-link" style="font-size:.78rem;">
                                <div class="s-icon" style="font-size:.72rem;">📋</div>
                                <span class="s-label">Toutes les classes</span>
                            </a>
                            <a href="{{ route('tenant.classe.profil', ['classe_slug' => 'premiere-f2']) }}" class="s-link" style="font-size:.78rem;">
                                <div class="s-icon" style="font-size:.72rem;">📋</div>
                                <span class="s-label">Classe de 1ère F2</span>
                            </a>
                            <a href="#" class="s-link" style="font-size:.78rem;">
                                <div class="s-icon" style="font-size:.72rem;">➕</div><span class="s-label">Nouvelle classe</span>
                            </a>
                        </div>
                    </div>
                    <a data-sidebar-item href="{{ route('tenant.subjects.portal') }}" class="s-link">
                        <div class="s-icon">📚</div><span class="s-label">Matières</span>
                    </a>
                    <a data-sidebar-item href="{{ route('tenant.promotions.portal') }}" class="s-link">
                        <div class="s-icon">🎯</div><span class="s-label">Promotions</span>
                    </a>
                    <a data-sidebar-item href="{{ route('tenant.filiars.portal') }}" class="s-link">
                        <div class="s-icon">🎯</div><span class="s-label">Filières</span>
                    </a>
                    <a data-sidebar-item href="{{ route('tenant.serials.portal') }}" class="s-link">
                        <div class="s-icon">🎯</div><span class="s-label">Séries</span>
                    </a>
                    <a data-sidebar-item href="#" class="s-link">
                        <div class="s-icon">🗓️</div><span class="s-label">Emploi du temps</span>
                    </a>
                </div>

                <div class="s-section">
                    <div class="s-section-label">Personnes</div>
                    <a href="{{ route('tenant.students.portal') }}" class="s-link">
                        <div class="s-icon">👨‍🎓</div><span class="s-label">Apprenants</span><span class="s-badge badge-indigo">847</span>
                    </a>
                    <a href="{{ route('tenant.teachers.portal') }}" class="s-link">
                        <div class="s-icon">👩‍🏫</div><span class="s-label">Enseignants</span><span class="s-badge badge-green">42</span>
                    </a>
                    <a href="{{ route('tenant.parents.portal') }}" class="s-link">
                        <div class="s-icon">👨‍👩‍👧</div><span class="s-label">Parents / Tuteurs</span>
                    </a>
                </div>

                <div class="s-section">
                    <div class="s-section-label">Statistiques</div>
                    <a href="{{ route('tenant.stats.general') }}" class="s-link">
                        <div class="s-icon">📝</div><span class="s-label">Générale</span><span class="s-badge badge-red">3</span>
                    </a>
                </div>

                <div class="s-section">
                    <div class="s-section-label">Évaluation</div>
                    <a href="#" class="s-link">
                        <div class="s-icon">📝</div><span class="s-label">Notes</span><span class="s-badge badge-red">3</span>
                    </a>
                    <a href="#" class="s-link">
                        <div class="s-icon">✅</div><span class="s-label">Présences</span>
                    </a>
                    <a href="#" class="s-link">
                        <div class="s-icon">📄</div><span class="s-label">Bulletins PDF</span>
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
                    <a href="{{ route('tenant.notifications.center') }}" class="s-link">
                        <div class="s-icon">🔔</div><span class="s-label">Notifications</span>
                    </a>
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
                        <button type="submit" class="s-logout" title="Déconnexion">⏻</button>
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

                        <livewire:school-year-selector-component />
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
                                <div class="notif-title">📝 Notes Maths — 3ème 1 saisies</div>
                                <div class="notif-time">il y a 5 min</div>
                            </div>
                            <div class="notif-item notif-unread">
                                <div class="notif-title">👤 Nouveau parent inscrit</div>
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
                            <a href="#" class="dd-item">👤 Mon profil</a>
                            <a href="#" class="dd-item">⚙️ Paramètres du compte</a>
                            <a href="#" class="dd-item">❓ Support</a>
                            <div class="dd-sep"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dd-item danger">⏻ Déconnexion</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1
            min-w-0
            w-full
            max-w-full
            overflow-x-hidden p-3" id="content">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>

