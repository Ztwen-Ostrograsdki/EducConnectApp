<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@300;400;500&family=Instrument+Serif:ital@0;1&display=swap"
        rel="stylesheet">

    <script>
        window.__APP_CONTEXT__ = {
            tenantId: '{{ tenant('id') ?? 'null' }}',
            userId: {{ auth('tenant')->id() ?? 'null' }},
            role: "{{ auth('tenant')->user()?->getRoleNames()->first() ?? '' }}",
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @wireUiScripts
    @livewireStyles

</head>

<body>
    <div class="shell">

        <x-notifications />
        <x-dialog />

        <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

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
                        <a wire:navigate class="hover:text-orange-500" href="{{ route('tenants.home') }}">
                            {{ tenant()?->school_name ?? 'Mon École' }}
                        </a>
                    </div>
                </div>
            </div>

            <nav class="s-nav">
                <div class="s-section">
                    <div class="s-section-label">Général</div>
                    <a wire:navigate data-sidebar-item href="{{ route('tenant.dashboard') }}"
                        class="s-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                        <div class="s-icon">📊</div><span class="s-label">Dashboard</span>
                    </a>
                    <div class="s-acc" id="acc-schoolyears">
                        <div class="s-acc-trigger" onclick="toggleAcc('acc-schoolyears')">
                            <div class="s-icon">
                                📅
                            </div>
                            <span class="s-label">Les années scolaires</span>
                            <span class="s-acc-arrow">▶</span>
                        </div>
                        <div class="s-acc-content">
                            <a wire:navigate href="{{ route('tenant.schoolyears.portal') }}"
                                class="s-link {{ request()->routeIs('tenant.schoolyears.portal') ? 'active' : '' }}">
                                <div class="s-icon">📅</div><span class="s-label">Dashboard</span>
                            </a>
                            <a wire:navigate href="{{ route('tenant.schoolYears.create') }}"
                                class="s-link {{ request()->routeIs('tenant.schoolYears.create') ? 'active' : '' }}">
                                <div class="s-icon">
                                    <span>➕</span>
                                </div><span class="s-label">Créer une année</span>
                            </a>
                        </div>
                    </div>
                </div>

                @if (tenancy()->tenant->hasActiveSchoolYear())
                    <div class="s-section">
                        <div class="s-section-label">Pédagogie</div>

                        {{-- CLASSES --}}
                        <div class="s-acc" id="acc-classes">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-classes')">
                                <div class="s-icon">🏫</div>
                                <span class="s-label">Classes</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a wire:navigate href="{{ route('tenant.classes.portal') }}"
                                    class="s-link {{ request()->routeIs('tenant.classes.portal') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Portail</span>
                                </a>

                                <a wire:navigate href="{{ route('tenant.classes.print.configuration') }}"
                                    class="s-link {{ request()->routeIs('tenant.classes.print.configuration') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-file class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Impression personalisée</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.classes.docs') }}"
                                    class="s-link {{ request()->routeIs('tenant.classes.docs') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-printer class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Fichiers imprimables</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.classes.create') }}"
                                    class="s-link {{ request()->routeIs('tenant.classes.create') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">➕</div>
                                    <span class="s-label">Nouvelle classe</span>
                                </a>
                            </div>
                        </div>

                        {{-- PROMOTIONS --}}
                        <div class="s-acc" id="acc-promotions">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-promotions')">
                                <div class="s-icon">🎯</div>
                                <span class="s-label">Promotions</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a wire:navigate href="{{ route('tenant.promotions.portal') }}"
                                    class="s-link {{ request()->routeIs('tenant.promotions.portal') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Toutes les promotions
                                        <span class="ml-3 text-sky-600"></span>
                                    </span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.promotion.create') }}"
                                    class="s-link {{ request()->routeIs('tenant.promotion.create') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">➕</div>
                                    <span class="s-label">Nouvelle promotion</span>
                                </a>
                            </div>
                        </div>

                        {{-- FILIÈRES --}}
                        <div class="s-acc" id="acc-filiars">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-filiars')">
                                <div class="s-icon">🎯</div>
                                <span class="s-label">Filières</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a wire:navigate href="{{ route('tenant.filiars.portal') }}"
                                    class="s-link {{ request()->routeIs('tenant.filiars.portal') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Toutes les filières
                                        <span class="ml-3 text-sky-600"></span>
                                    </span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.filiar.create') }}"
                                    class="s-link {{ request()->routeIs('tenant.filiar.create') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">➕</div>
                                    <span class="s-label">Nouvelle filière</span>
                                </a>
                            </div>
                        </div>

                        {{-- SÉRIES --}}
                        <div class="s-acc" id="acc-serials">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-serials')">
                                <div class="s-icon">🎯</div>
                                <span class="s-label">Séries</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a wire:navigate href="{{ route('tenant.serials.portal') }}"
                                    class="s-link {{ request()->routeIs('tenant.serials.portal') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Toutes les séries
                                        <span class="ml-3 text-sky-600"></span>
                                    </span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.serial.create') }}"
                                    class="s-link {{ request()->routeIs('tenant.serial.create') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">➕</div>
                                    <span class="s-label">Nouvelle série</span>
                                </a>
                            </div>
                        </div>

                        {{-- MATIÈRES --}}
                        <div class="s-acc" id="acc-subjects">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-subjects')">
                                <div class="s-icon">📚</div>
                                <span class="s-label">Matières</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a wire:navigate href="{{ route('tenant.subjects.portal') }}"
                                    class="s-link {{ request()->routeIs('tenant.subjects.portal') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">📋</div>
                                    <span class="s-label">Toutes les matières
                                        <span class="ml-3 text-sky-600"></span>
                                    </span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.subject.create') }}"
                                    class="s-link {{ request()->routeIs('tenant.subject.create') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">➕</div>
                                    <span class="s-label">Nouvelle matière</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.teacher.manage.subjects') }}"
                                    class="s-link {{ request()->routeIs('tenant.teacher.manage.subjects') ? 'active' : '' }}"
                                    style="font-size:.78rem;">
                                    <div class="s-icon" style="font-size:.72rem;">
                                        ⚙️
                                    </div>
                                    <span class="s-label">
                                        Attribution
                                    </span>
                                </a>
                            </div>
                        </div>

                        <a data-sidebar-item href="#" class="s-link">
                            <div class="s-icon">🗓️</div><span class="s-label">Emploi du temps</span>
                        </a>
                    </div>

                    {{-- PERSONNES --}}
                    <div class="s-section">
                        <div class="s-section-label">Personnes</div>

                        {{-- APPRENANTS --}}
                        <div class="s-acc" id="acc-students">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-students')">
                                <div class="s-icon">👥</div>
                                <span class="s-label">Les apprenants</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a wire:navigate href="{{ route('tenant.students.portal') }}"
                                    class="s-link {{ request()->routeIs('tenant.students.portal') ? 'active' : '' }}">
                                    <div class="s-icon">👥</div><span class="s-label">Dashboard</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.students.create') }}"
                                    class="s-link {{ request()->routeIs('tenant.students.create') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-user-plus class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Ajouter apprenants</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.students.crud.tasks') }}"
                                    class="s-link {{ request()->routeIs('tenant.students.crud.tasks') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-octagon-alert class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Status des ajouts</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.students.print.configuration') }}"
                                    class="s-link {{ request()->routeIs('tenant.students.print.configuration') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-file class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Impression personalisée</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.students.docs') }}"
                                    class="s-link {{ request()->routeIs('tenant.students.docs') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-printer class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Fichiers imprimables</span>
                                </a>
                            </div>
                        </div>

                        {{-- ENSEIGNANTS --}}
                        <div class="s-acc" id="acc-teachers">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-teachers')">
                                <div class="s-icon">👩‍🏫</div>
                                <span class="s-label">Les enseignants</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a wire:navigate href="{{ route('tenant.teachers.portal') }}"
                                    class="s-link {{ request()->routeIs('tenant.teachers.portal') ? 'active' : '' }}">
                                    <div class="s-icon">👩‍🏫</div><span class="s-label">Dashboard</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.teachers.create') }}"
                                    class="s-link {{ request()->routeIs('tenant.teachers.create') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-user-plus class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Ajouter enseignants</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.teachers.crud.tasks') }}"
                                    class="s-link {{ request()->routeIs('tenant.teachers.crud.tasks') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-octagon-alert class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Status des ajouts</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.teachers.print.configuration') }}"
                                    class="s-link {{ request()->routeIs('tenant.teachers.print.configuration') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-file class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Impression personalisée</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.teachers.docs') }}"
                                    class="s-link {{ request()->routeIs('tenant.teachers.docs') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-printer class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Fichiers imprimables</span>
                                </a>
                            </div>
                        </div>

                        {{-- PARENTS --}}
                        <div class="s-acc" id="acc-parents">
                            <div class="s-acc-trigger" onclick="toggleAcc('acc-parents')">
                                <div class="s-icon">👨‍👩‍👧</div>
                                <span class="s-label">Parents / Tuteurs</span>
                                <span class="s-acc-arrow">▶</span>
                            </div>
                            <div class="s-acc-content">
                                <a href="{{ route('tenant.parents.portal') }}"
                                    class="s-link {{ request()->routeIs('tenant.parents.portal') ? 'active' : '' }}">
                                    <div class="s-icon">👨‍👩‍👧</div><span class="s-label">Dashboard</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.parents.create') }}"
                                    class="s-link {{ request()->routeIs('tenant.parents.create') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-user-plus class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Ajouter parent/tuteur</span>
                                </a>
                                <a wire:navigate href="{{ route('tenant.parents.crud.tasks') }}"
                                    class="s-link {{ request()->routeIs('tenant.parents.crud.tasks') ? 'active' : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-octagon-alert class="w-3 h-3 text-sky-600" />
                                    </div><span class="s-label">Status des ajouts</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- FICHES DE NOTES --}}
                    <div class="s-section">
                        <div class="s-section-label">Gestion des notes </div>
                        <a wire:navigate href="{{ route('tenant.notes.print.configuration') }}"
                            class="s-link {{ request()->routeIs('tenant.notes.print.configuration') ? 'active' : '' }}">
                            <div class="s-icon">
                                <x-lucide-file class="w-3 h-3 text-yellow-300" />
                            </div><span class="s-label">Impression personalisée</span>
                        </a>
                        <a href="{{ route('tenant.notes.print.preview') }}"
                            class="s-link {{ request()->routeIs('tenant.notes.print.preview') ? 'active' : '' }}">
                            <div class="s-icon">
                                <x-lucide-printer class="w-3 h-3 text-yellow-300" />
                            </div><span class="s-label">Prévisualisation</span>
                        </a>
                        <a wire:navigate href="{{ route('tenant.notes.docs') }}"
                            class="s-link {{ request()->routeIs('tenant.notes.docs') ? 'active' : '' }}">
                            <div class="s-icon">
                                <x-lucide-printer class="w-3 h-3 text-yellow-300" />
                            </div><span class="s-label">Notes imprimables</span>
                        </a>
                    </div>

                    {{-- MEILLEURS / FAIBLES --}}
                    <div class="s-section">
                        <div class="s-section-label">Meilleurs/Faibles</div>
                        <a wire:navigate href="{{ route('tenant.students.bests.weaks.print.configuration') }}"
                            class="s-link {{ request()->routeIs('tenant.students.bests.weaks.print.configuration') ? 'active' : '' }}">
                            <div class="s-icon">
                                <x-lucide-file class="w-3 h-3 text-amber-600" />
                            </div><span class="s-label">Impression personalisée</span>
                        </a>
                        <a href="{{ route('tenant.students.bests.weaks.print.preview') }}"
                            class="s-link {{ request()->routeIs('tenant.students.bests.weaks.print.preview') ? 'active' : '' }}">
                            <div class="s-icon">
                                <x-lucide-printer class="w-3 h-3 text-amber-600" />
                            </div><span class="s-label">Prévisualisation</span>
                        </a>
                        <a wire:navigate href="{{ route('tenant.students.bests.weaks.docs') }}"
                            class="s-link {{ request()->routeIs('tenant.students.bests.weaks.docs') ? 'active' : '' }}">
                            <div class="s-icon">
                                <x-lucide-printer class="w-3 h-3 text-amber-600" />
                            </div><span class="s-label">Notes imprimables</span>
                        </a>
                    </div>

                    {{-- DIAGNOSTIQUES NOTES --}}
                    <div class="s-section">
                        <div class="s-section-label">Rapports notes renseignées</div>
                        <a wire:navigate href="{{ route('tenant.marks.reports.print.configuration') }}"
                            class="s-link {{ request()->routeIs('tenant.marks.reports.print.configuration') ? 'active' : '' }}">
                            <div class="s-icon">🖥️</div><span class="s-label">Lancer</span>
                        </a>
                        <a href="{{ route('tenant.marks.reports.print.preview') }}"
                            class="s-link {{ request()->routeIs('tenant.marks.reports.print.preview') ? 'active' : '' }}">
                            <div class="s-icon">📋</div><span class="s-label">Parcourir</span>
                        </a>
                        <a wire:navigate href="{{ route('tenant.marks.reports.docs') }}"
                            class="s-link {{ request()->routeIs('tenant.marks.reports.docs') ? 'active' : '' }}">
                            <div class="s-icon">📑</div><span class="s-label">Fichiers disponibles</span>
                        </a>
                    </div>
                    <div class="s-section">
                        <div class="s-section-label">Gestion bulletins de notes</div>
                        <a wire:navigate href="{{ route('tenant.bulletins.print.configuration') }}"
                            class="s-link {{ request()->routeIs('tenant.bulletins.print.configuration') ? 'active' : '' }}">
                            <div class="s-icon">🖥️</div><span class="s-label">Page de génération</span>
                        </a>
                        <a href="{{ route('tenant.bulletins.print.preview') }}"
                            class="s-link {{ request()->routeIs('tenant.bulletins.print.preview') ? 'active' : '' }}">
                            <div class="s-icon">📖</div><span class="s-label">Parcourir</span>
                        </a>
                        <a wire:navigate href="{{ route('tenant.bulletins.docs') }}"
                            class="s-link {{ request()->routeIs('tenant.bulletins.docs') ? 'active' : '' }}">
                            <div class="s-icon">📑</div><span class="s-label">Bulletins disponibles</span>
                        </a>
                    </div>
                    <div class="s-section">
                        <div class="s-section-label">Statistiques périodiques</div>
                        <a wire:navigate href="{{ route('tenant.stats.print.configuration') }}"
                            class="s-link {{ request()->routeIs('tenant.stats.print.configuration') ? 'active' : '' }}">
                            <div class="s-icon">📊</div><span class="s-label">Configuration</span>
                        </a>
                        <a href="{{ route('tenant.stats.print.preview') }}"
                            class="s-link {{ request()->routeIs('tenant.stats.print.preview') ? 'active' : '' }}">
                            <div class="s-icon">📈</div><span class="s-label">Lecture</span>
                        </a>
                        <a wire:navigate href="{{ route('tenant.stats.docs') }}"
                            class="s-link {{ request()->routeIs('tenant.stats.docs') ? 'active' : '' }}">
                            <div class="s-icon">📑</div><span class="s-label">Stats en fichiers disponibles</span>
                        </a>
                    </div>
                @else
                    <div class="s-section">
                        <div class="s-section-label break-all">
                            <span
                                class="flex items-center flex-col gap-1 text-red-400 bg-red-500/10 rounded-2xl p-3 animate-pulse">
                                <span>Veuillez activer </span>
                                <span>une année scolaire</span>
                                <span>pour voir le </span>
                                <span>menu complet</span>
                            </span>
                        </div>
                    </div>
                @endif

                <div class="s-section">
                    <div class="s-section-label">Administration</div>
                    <a wire:navigate href="{{ route('tenant.settings') }}"
                        class="s-link {{ request()->routeIs('tenant.settings') ? 'active' : '' }}">
                        <div class="s-icon">⚙️</div><span class="s-label">Paramètres</span>
                    </a>
                    <a wire:navigate href="{{ route('tenant.notifications.center') }}"
                        class="s-link {{ request()->routeIs('tenant.notifications.center') ? 'active' : '' }}">
                        <div class="s-icon">🔔</div><span class="s-label">
                            Notifications
                            @livewire('notifications-counter', ['guard' => 'tenant'])
                        </span>
                    </a>
                </div>
            </nav>

            <div class="s-footer">
                <div class="s-user">
                    <div class="s-avatar">
                        @if (auth()->guard('tenant')->user()->profil_photo)
                            <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}"
                                class="h-10 w-10 rounded-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::guard('tenant')->user()?->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <a wire:navigate href="{{ route('tenant.my.profil') }}"
                        class="s-user-info {{ request()->routeIs('tenant.my.profil') ? 'active' : '' }}">
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
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="s-logout" title="Déconnexion">
                            <x-lucide-log-out class="w-4 h-4 text-red-500" />
                        </button>
                    </form>
                </div>
            </div>
        </aside>

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

                    @livewire('notification-badge', ['guard' => 'tenant'])

                    <div class="dd">
                        <div class="user-trigger" onclick="toggleDD('user-menu')">
                            <div class="ut-avatar">
                                @if (auth()->guard('tenant')->user()->profil_photo)
                                    <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}"
                                        class="h-6.5 w-8 rounded-full object-cover">
                                @else
                                    {{ strtoupper(substr(Auth::guard('tenant')->user()?->name ?? 'U', 0, 1)) }}
                                @endif
                            </div>
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
                            <a wire:navigate href="{{ route('tenant.my.profil') }}" class="dd-item">👤 Mon
                                profil</a>
                            <a href="#" class="dd-item">⚙️ Paramètres du compte</a>
                            <a href="#" class="dd-item">❓ Support</a>
                            <div class="dd-sep"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dd-item danger"> Déconnexion
                                    <x-lucide-log-out class="w-4 h-4 text-red-500" />
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
            overflow-x-hidden p-3"
                id="content">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>

