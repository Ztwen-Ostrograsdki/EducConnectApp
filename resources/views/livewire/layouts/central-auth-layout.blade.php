<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMINISTRATION CENTRALE - {{ $title ?? config('app.name') }}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@300;400;500&family=Instrument+Serif:ital@0;1&display=swap"
        rel="stylesheet">

    {{-- <script>
        window.__APP__ = @json(\App\Helpers\Support\TenantContext::forJs());
    </script> --}}

    <script>
        window.__APP_CONTEXT__ = {
            tenantId: {{ 'null' }},
            userId: {{ auth('central')->id() ?? 'null' }},
            role: "{{ '' }}",
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @wireUiScripts
    @livewireStyles
</head>

<body>
    <div class="shell">

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
                    <div class="s-school-name">{{ auth()->guard('central')->user()?->name ?? 'Super Admin' }}</div>
                    <div class="s-school-year">
                        <span class="s-school-dot"></span>
                        <select name="" id="">
                            <option value="">2022</option>
                        </select>
                    </div>
                </div>
            </div>

            <nav class="s-nav">

                {{-- ===================== DASHBOARD ===================== --}}
                <div class="s-section">
                    <a data-sidebar-item href="{{ route('central.dashboard') }}"
                        class="s-link {{ request()->routeIs('central.dashboard') ? 'active' : '' }}">
                        <div class="s-icon">📊</div>
                        <span class="s-label">Dashboard - Accueil</span>
                    </a>
                </div>

                {{-- ===================== DEMANDES D'ESPACE ÉCOLE ===================== --}}
                <div class="s-section">
                    <div class="s-section-label">Les demandes d'espace école</div>

                    {{-- En attente --}}
                    <a href="{{ route('central.requests.school.space.portal', ['status' => 'tout']) }}"
                        class="s-link {{ request()->routeIs('central.requests.school.space.portal') && request()->route('status') === 'tout' ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-calendar-sync class="w-3.5 h-3.5 text-amber-400" />
                        </div>
                        <span class="s-label">En attente</span>

                    </a>

                    {{-- Approuvées --}}
                    <a href="{{ route('central.requests.school.space.portal', ['status' => 'active']) }}"
                        class="s-link {{ request()->routeIs('central.requests.school.space.portal') && request()->route('status') === 'active' ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-circle-check-big class="w-3.5 h-3.5 text-emerald-400" />
                        </div>
                        <span class="s-label">Approuvées</span>

                    </a>

                    {{-- Rejetées --}}
                    <a href="{{ route('central.requests.school.space.portal', ['status' => 'suspended']) }}"
                        class="s-link {{ request()->routeIs('central.requests.school.space.portal') && request()->route('status') === 'suspended' ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-circle-x class="w-3.5 h-3.5 text-rose-400" />
                        </div>
                        <span class="s-label">Rejetées</span>

                    </a>
                </div>

                {{-- ===================== DEMANDES D'ABONNEMENT ===================== --}}
                <div class="s-section">
                    <div class="s-section-label">Les demandes d'abonnement</div>

                    {{-- En attente --}}
                    <a href="{{ route('central.pendings.subscriptions.requests.portal') }}"
                        class="s-link {{ request()->routeIs('central.pendings.subscriptions.requests.portal') ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-calendar-sync class="w-3.5 h-3.5 text-amber-400" />
                        </div>
                        <span class="s-label">En attente</span>

                    </a>

                    {{-- Approuvées --}}
                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-circle-check-big class="w-3.5 h-3.5 text-emerald-400" />
                        </div>
                        <span class="s-label">Approuvées</span>

                    </a>

                    {{-- Rejetées --}}
                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-circle-x class="w-3.5 h-3.5 text-rose-400" />
                        </div>
                        <span class="s-label">Rejetées</span>

                    </a>
                </div>

                {{-- ===================== LES ÉCOLES ===================== --}}
                <div class="s-section">
                    <div class="s-section-label">Les écoles</div>

                    {{-- Actives --}}
                    <a href="{{ route('central.schools.portal', ['status' => 'active']) }}"
                        class="s-link {{ request()->routeIs('central.schools.portal') && request()->route('status') === 'active' ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-school class="w-3.5 h-3.5 text-emerald-400" />
                        </div>
                        <span class="s-label">Actives</span>

                    </a>

                    {{-- Inactives / Corbeille --}}
                    <a href="{{ route('central.schools.portal', ['status' => 'corbeille']) }}"
                        class="s-link {{ request()->routeIs('central.schools.portal') && request()->route('status') === 'corbeille' ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-trash-2 class="w-3.5 h-3.5 text-rose-400" />
                        </div>
                        <span class="s-label">Inactives | Corbeille</span>

                    </a>
                </div>

                {{-- ===================== GÉNÉRAL (Accordion) ===================== --}}
                <div class="s-section">
                    <div class="s-section-label">Général</div>

                    <div class="s-acc" id="acc-schools">
                        <div class="s-acc-trigger" onclick="toggleAcc('acc-schools')">
                            <div class="s-icon">🏫</div>
                            <span class="s-label">Les Écoles</span>
                            <span class="s-acc-arrow">▶</span>
                        </div>

                        <div class="s-acc-content">
                            <a href="{{ route('central.schools.portal') }}"
                                class="s-link {{ request()->routeIs('central.schools.portal') ? 'active' : '' }}"
                                style="font-size:.78rem;">
                                <div class="s-icon" style="font-size:.72rem;">
                                    <x-lucide-sliders-vertical class="w-3 h-3 text-slate-400" />
                                </div>
                                <span class="s-label">Toutes les écoles</span>
                            </a>

                            @foreach (getTenants() as $ten)
                                <a href="{{ route('central.school.profil', ['school' => $ten->id]) }}"
                                    class="s-link {{ request()->routeIs('central.school.profil') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">
                                        <x-lucide-school class="w-3 h-3 text-amber-400" />
                                    </div>
                                    <span class="s-label">
                                        <span>{{ $ten->school_name }}</span>
                                        <span class="text-slate-600 text-xs">({{ $ten->simple_name }})</span>
                                    </span>
                                </a>
                            @endforeach

                            <a href="#" class="s-link" style="font-size:.78rem;">
                                <div class="s-icon" style="font-size:.72rem;">
                                    <x-lucide-plus class="w-3 h-3 text-emerald-400" />
                                </div>
                                <span class="s-label">Nouvelle école</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ===================== ABONNEMENTS VALIDÉS ===================== --}}
                <div class="s-section">
                    <div class="s-section-label">Les abonnements validés</div>

                    {{-- Actifs --}}
                    <a href="{{ route('central.validateds.subscriptions.portal') }}"
                        class="s-link {{ request()->routeIs('central.validateds.subscriptions.portal') ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-calendar-check-2 class="w-3.5 h-3.5 text-indigo-400" />
                        </div>
                        <span class="s-label">Actifs</span>

                    </a>

                    {{-- Expirés --}}
                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-calendar-x-2 class="w-3.5 h-3.5 text-rose-400" />
                        </div>
                        <span class="s-label">Expirés</span>

                    </a>
                </div>

                {{-- ===================== FINANCE ===================== --}}
                <div class="s-section">
                    <div class="s-section-label">Finance</div>
                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-credit-card class="w-3.5 h-3.5 text-sky-400" />
                        </div>
                        <span class="s-label">Paiements</span>
                    </a>
                </div>

                {{-- ===================== ADMINISTRATION ===================== --}}
                <div class="s-section">
                    <div class="s-section-label">Administration</div>

                    <a wire:navigate href="{{ route('central.plans.portal') }}"
                        class="s-link {{ request()->routeIs('central.plans.portal') ? 'active' : '' }}">
                        <div class="s-icon">
                            <x-lucide-package class="w-3.5 h-3.5 text-violet-400" />
                        </div>
                        <span class="s-label">Les packs</span>
                    </a>

                    <a href="#" class="s-link">
                        <div class="s-icon">
                            <x-lucide-settings class="w-3.5 h-3.5 text-slate-400" />
                        </div>
                        <span class="s-label">Paramètres</span>
                    </a>

                    <a wire:navigate href="{{ route('central.notifications.center') }}" class="s-link">
                        <div class="s-icon">
                            <x-lucide-bell class="w-3.5 h-3.5 text-amber-400" />
                        </div>
                        <span class="s-label">Notifications</span>
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

                <div class="h-right">
                    <div class="year-switcher">
                        <span class="year-icon">📅</span>

                    </div>

                    <button class="h-btn" title="Thème">🌙</button>

                    @livewire('notification-badge', ['guard' => 'central'])

                    <div class="dd">
                        <div class="user-trigger" onclick="toggleDD('user-menu')">
                            <div class="ut-avatar">
                                <x-lucide-user-round-cog class="w-5 h-5 text-amber-400" />
                            </div>
                            <div>
                                <div class="ut-name">{{ auth()->guard('central')->user()?->name ?? 'Super Admin' }}
                                </div>
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

            <main
                class="flex-1
            min-w-0
            w-full
            max-w-full
            overflow-x-hidden bg-transparent"
                id="content">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>

