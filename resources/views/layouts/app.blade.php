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
    {{-- <script>
        window.__APP__ = @json(\App\Helpers\Support\TenantContext::forJs());
    </script> --}}

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

        {{-- @livewire('app-guard') --}}

        <x-notifications />
        <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

        {{-- SIDEBAR --}}
        <aside class="sidebar" id="sidebar">
            <div class="s-brand">
                <div class="s-brand-content">
                    <div class="s-brand-icon">🎓</div>
                    <span class="s-brand-text">EducConnect</span>
                </div>
                <button type="button" class="s-collapse" onclick="toggleCollapse()">
                    <span id="collapse-icon">◀</span>
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
                @php
                    $currentTenant = tenant();
                @endphp
                <nav class="s-nav">
                    {{-- ─── GÉNÉRAL ──────────────────────────────────────── --}}
                    <div class="s-section">
                        <div class="s-section-label">Général</div>

                        @if (auth('tenant')->user()?->hasRole('directeur'))
                            <a wire:navigate data-sidebar-item href="{{ route('tenant.dashboard') }}"
                                class="s-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                                <div class="s-icon">📊</div>
                                <span class="s-label">Administration</span>
                            </a>
                        @endif

                        <a wire:navigate data-sidebar-item href="{{ route('tenant.my.profil') }}"
                            class="s-link {{ request()->routeIs('tenant.my.profil') ? 'active' : '' }}">
                            <div class="s-icon">
                                <x-lucide-user class="h-3 w-3" />
                            </div>
                            <span class="s-label">Mon profil</span>
                        </a>
                        <a onclick="Livewire.dispatch('open-testimonial-modal')" data-sidebar-item href="#"
                            class="s-link {{ request()->routeIs('tenant.testimonials.create') ? 'active' : '' }}">
                            <div class="s-icon">
                                <x-lucide-info class="h-3 w-3" />
                            </div>
                            <span class="s-label">Donner son avis</span>
                        </a>
                    </div>

                    {{-- ─── ESPACE ENSEIGNANT ────────────────────────────── --}}
                    @if (auth('tenant')->user()->hasRole('enseignant') && ($currentTenant?->teacherPortalAble() ?? false))
                        <div class="s-section">
                            <div class="s-section-label">Mon espace enseignant</div>

                            <a wire:navigate data-sidebar-item href="{{ route('tenant.teacher.my.dashboard') }}"
                                class="s-link {{ request()->routeIs('tenant.teacher.my.dashboard') ? 'active' : '' }}">
                                <div class="s-icon">
                                    <x-lucide-user class="h-3 w-3" />
                                </div>
                                <span class="s-label">Mon espace enseignant</span>
                            </a>

                            @if (auth('tenant')->user()->teacher)
                                @php
                                    $classes = auth('tenant')
                                        ->user()
                                        ->teacher?->getTeacherClassesWithSubjectsForThisSchoolYear();
                                @endphp

                                @foreach ($classes as $kls)
                                    <a data-sidebar-item wire:navigate
                                        href="{{ route('tenant.teacher.classe.students', [
                                            'classe_slug' => $kls->classe->slug,
                                            'subject_slug' => $kls->subject->slug,
                                        ]) }}"
                                        class="s-link {{ request()->routeIs('tenant.teacher.classe.*') &&
                                        request()->route('classe_slug') === $kls->classe->slug &&
                                        request()->route('subject_slug') === $kls->subject->slug
                                            ? 'active'
                                            : '' }}">
                                        <div class="s-icon uppercase">🏫</div>
                                        <span class="s-label">
                                            {{ $kls->classe->code }} ({{ $kls->subject->code }})
                                        </span>
                                    </a>
                                @endforeach
                            @endif

                            @if ($currentTenant?->timetableAble())
                                <a data-sidebar-item href="#" class="s-link">
                                    <div class="s-icon">🗓️</div>
                                    <span class="s-label">Mon Emploi du temps</span>
                                </a>
                            @endif

                            <a data-sidebar-item href="{{ route('tenant.subjects.coefs.manage') }}"
                                class="s-link {{ request()->routeIs('tenant.subjects.coefs.manage') ? 'active' : '' }}">
                                <div class="s-icon">🛠️</div>
                                <span class="s-label">Gérer les coéf.</span>
                            </a>
                        </div>
                    @endif

                    {{-- ─── ESPACE PP ────────────────────────────────────── --}}
                    @if (auth('tenant')->user()->hasRole('enseignant') && auth('tenant')->user()->teacher?->hasCurrentlyPPRole())
                        <div class="s-section">
                            <div class="s-section-label">Mon espace PP</div>
                            @if (auth('tenant')->user()->teacher)
                                @php
                                    $classes = auth('tenant')->user()->teacher->getClassesWhereIsPrincipal();
                                @endphp
                                @foreach ($classes as $cl)
                                    <a data-sidebar-item wire:navigate
                                        href="{{ route('tenant.teacher.pp.students.marks', ['classe_slug' => $cl->slug]) }}"
                                        class="s-link {{ request()->routeIs('tenant.teacher.pp.students.marks') && request()->route('classe_slug') === $cl->slug
                                            ? 'active'
                                            : '' }}">
                                        <div class="s-icon uppercase">📚</div>
                                        <span class="s-label">Notes de {{ $cl->code }}</span>
                                    </a>

                                    <a data-sidebar-item wire:navigate
                                        href="{{ route('tenant.teacher.pp.classe.teachers.list', ['classe_slug' => $cl->slug]) }}"
                                        class="s-link {{ request()->routeIs('tenant.teacher.pp.classe.teachers.list') && request()->route('classe_slug') === $cl->slug
                                            ? 'active'
                                            : '' }}">
                                        <div class="s-icon uppercase">📬</div>
                                        <span class="s-label">Liste prof de {{ $cl->code }}</span>

                                    </a>
                                    <a data-sidebar-item wire:navigate
                                        href="{{ route('tenant.teacher.pp.classe.tutors.list', ['classe_slug' => $cl->slug]) }}"
                                        class="s-link {{ request()->routeIs('tenant.teacher.pp.classe.tutors.list') && request()->route('classe_slug') === $cl->slug
                                            ? 'active'
                                            : '' }}">
                                        <div class="s-icon uppercase">👥</div>
                                        <span class="s-label">Parents de {{ $cl->code }}</span>

                                    </a>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    {{-- ─── ESPACE PARENT ────────────────────────────────── --}}
                    @if (auth('tenant')->user()->hasRole('tuteur') && ($currentTenant?->parentPortalAble() ?? false))
                        @php
                            $children = auth('tenant')->user()->tutor->myChildren;
                        @endphp

                        <div class="s-section">
                            <div class="s-section-label">Mon espace parent</div>
                            <a wire:navigate data-sidebar-item href="{{ route('tenant.parent.space') }}"
                                class="s-link {{ request()->routeIs('tenant.parent.space') ? 'active' : '' }}">
                                <div class="s-icon">
                                    <x-lucide-user class="h-3 w-3" />
                                </div>
                                <span class="s-label">Dashboard</span>
                            </a>
                        </div>

                        <div class="s-section">
                            <div class="s-section-label">Les notes</div>
                            @foreach ($children as $child_rel)
                                <a wire:navigate data-sidebar-item
                                    href="{{ route('tenant.parent.space.marks', ['student_uuid' => $child_rel->student->uuid]) }}"
                                    class="s-link {{ request()->routeIs('tenant.parent.space.marks') &&
                                    request()->route('student_uuid') === $child_rel->student->uuid
                                        ? 'active'
                                        : '' }}">
                                    <div class="s-icon">
                                        <x-lucide-pen-line class="h-3 w-3 text-sky-400" />
                                    </div>
                                    <span class="s-label truncate">
                                        Notes de {{ $child_rel->student->getFullName() }}
                                    </span>
                                </a>
                            @endforeach
                        </div>

                        {{-- <div class="s-section">
                            <div class="s-section-label">Les emplois du temps</div>
                            @foreach ($children as $child_rel)
                                <a wire:navigate data-sidebar-item href="#" class="s-link">
                                    <div class="s-icon">
                                        <x-lucide-file class="h-3 w-3" />
                                    </div>
                                    <span class="s-label truncate">
                                        Emploi de {{ $child_rel->student->getFullName() }}
                                    </span>
                                </a>
                            @endforeach
                        </div> --}}

                        @if ($currentTenant?->pdfBulletinsAble())
                            <div class="s-section">
                                <div class="s-section-label">Les bulletins</div>
                                @foreach ($children as $child_rel)
                                    <a wire:navigate data-sidebar-item
                                        href="{{ route('tenant.parent.space.bulletin', ['student_uuid' => $child_rel->student->uuid]) }}"
                                        class="s-link {{ request()->routeIs('tenant.parent.space.bulletin') &&
                                        request()->route('student_uuid') === $child_rel->student->uuid
                                            ? 'active'
                                            : '' }}">
                                        <div class="s-icon">
                                            <x-lucide-file class="h-3 w-3 text-amber-500" />
                                        </div>
                                        <span class="s-label truncate">
                                            Bulletin de {{ $child_rel->student->getFullName() }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </nav>

                {{-- FOOTER (inchangé) --}}
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

                {{-- <div class="search" onclick="openSearch()">
                    <span style="font-size:.8rem;color:var(--text3)">🔍</span>
                    <span class="search-text">Rechercher...</span>
                    <span class="search-kbd">Ctrl K</span>
                </div> --}}

                <div class="h-right">
                    <div class="year-switcher">
                        <span class="year-icon">📅</span>

                        <livewire:school-year-selector-component />
                    </div>

                    <button class="h-btn" title="Thème">🌙</button>
                    @auth('tenant')
                        @livewire('notification-badge', ['guard' => 'tenant'])

                        <div class="dd">
                            <div class="user-trigger" onclick="toggleDD('user-menu')">
                                <div class="ut-avatar">
                                    @if (auth()->guard('tenant')->user()->profil_photo)
                                        <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}"
                                            class="h-10 w-10 rounded-full object-cover">
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
                                <a href="{{ route('tenant.my.profil') }}" class="dd-item">👤 Mon profil</a>
                                <a href="{{ route('tenant.testimonials.create') }}" class="dd-item">
                                    📢 Donner un avis</a>
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
            <main class="flex-1 min-w-0 w-full max-w-full overflow-x-hidden bg-[#070b14]" id="content">
                <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6">

                    {{-- Breadcrumb / espace perso --}}
                    <div class="py-3 sm:py-4">
                        <div
                            class="flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-[#0f1523] border border-white/[0.06] px-4 sm:px-5 py-3 shadow-lg shadow-black/10">
                            <div class="flex items-center gap-3 min-w-0">
                                <div
                                    class="w-9 h-9 rounded-xl bg-sky-500/15 border border-sky-500/25 flex items-center justify-center shrink-0">
                                    <x-lucide-layout-dashboard class="w-4 h-4 text-sky-400" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-600">
                                        Espace personnel
                                    </p>
                                    <h1 class="text-sm sm:text-base font-semibold text-white truncate">
                                        Mon espace
                                        <span class="text-slate-600 font-normal mx-1.5">·</span>
                                        <span class="text-sky-400 font-mono font-medium">
                                            {{ auth('tenant')->user()?->getFullName() }}
                                        </span>
                                    </h1>
                                </div>
                            </div>

                            <div
                                class="hidden sm:flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span class="text-[10px] font-medium text-emerald-400">Connecté</span>
                            </div>
                        </div>
                    </div>

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
    @livewire('tenants.testimonials.add-testimonial-modal')
</body>

</html>

