<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@300;400;500&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        :root {
            --bg: #0f172a; --surface: #1e293b; --surface2: #27374d;
            --border: #334155; --border2: #475569;
            --accent: #6366f1; --accent2: #8b5cf6;
            --teal: #10b981; --coral: #f43f5e; --gold: #f59e0b;
            --text: #f1f5f9; --text2: #94a3b8; --text3: #64748b;
            --sidebar-w: 260px; --sidebar-c: 72px; --header-h: 64px;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Syne', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; overflow-x: hidden; }
        ::-webkit-scrollbar { width: 4px; } ::-webkit-scrollbar-track { background: var(--bg); } ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

        /* Shell */
        .shell { display: flex; min-height: 100vh; }

        /* Overlay */
        .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.7); backdrop-filter: blur(4px); z-index: 40; }
        .overlay.open { display: block; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-w); background: var(--surface); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 50; transition: width .3s cubic-bezier(.4,0,.2,1), transform .3s cubic-bezier(.4,0,.2,1); overflow: hidden;
        }
        .sidebar.collapsed { width: var(--sidebar-c); }

        /* Brand */
        .s-brand { height: var(--header-h); padding: 0 1.1rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: .75rem; flex-shrink: 0; }
        .s-brand-icon { width: 36px; height: 36px; background: linear-gradient(135deg,var(--accent),var(--accent2)); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .s-brand-text { font-family: 'Instrument Serif',serif; font-size: 1.1rem; font-style: italic; background: linear-gradient(135deg,var(--accent),var(--teal)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; white-space: nowrap; transition: opacity .2s; }
        .sidebar.collapsed .s-brand-text { opacity: 0; width: 0; overflow: hidden; }
        .s-collapse { margin-left: auto; width: 24px; height: 24px; border-radius: 5px; background: var(--surface2); border: 1px solid var(--border); color: var(--text2); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: .6rem; transition: all .2s; flex-shrink: 0; }
        .s-collapse:hover { background: var(--border); color: var(--text); }
        .sidebar.collapsed .s-collapse { transform: rotate(180deg); }

        /* School info */
        .s-school { padding: .75rem 1rem; border-bottom: 1px solid var(--border); flex-shrink: 0; }
        .s-school-inner { background: rgba(99,102,241,.08); border: 1px solid rgba(99,102,241,.18); border-radius: .55rem; padding: .55rem .7rem; }
        .sidebar.collapsed .s-school-inner { display: flex; justify-content: center; padding: .4rem; }
        .s-school-name { font-size: .72rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: .1rem; }
        .s-school-year { font-family: 'DM Mono',monospace; font-size: .58rem; color: var(--teal); display: flex; align-items: center; gap: .3rem; }
        .s-school-dot { width: 5px; height: 5px; background: var(--teal); border-radius: 50%; animation: blink 2s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
        .sidebar.collapsed .s-school-name, .sidebar.collapsed .s-school-year { display: none; }

        /* Nav */
        .s-nav { flex: 1; padding: .75rem; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; }
        .s-section { margin-bottom: 1.25rem; }
        .s-section-label { font-family: 'DM Mono',monospace; font-size: .57rem; color: var(--text3); text-transform: uppercase; letter-spacing: .12em; padding: 0 .5rem; margin-bottom: .4rem; white-space: nowrap; transition: opacity .2s; }
        .sidebar.collapsed .s-section-label { opacity: 0; }

        .s-link { display: flex; align-items: center; gap: .65rem; padding: .52rem .5rem; border-radius: .5rem; color: var(--text2); text-decoration: none; font-size: .82rem; font-weight: 500; transition: all .15s; position: relative; margin-bottom: .08rem; white-space: nowrap; }
        .s-link:hover { background: rgba(255,255,255,.05); color: var(--text); }
        .s-link.active { background: rgba(99,102,241,.12); color: #818cf8; border-left: 2px solid var(--accent); }
        .s-icon { width: 28px; height: 28px; border-radius: .4rem; display: flex; align-items: center; justify-content: center; font-size: .82rem; flex-shrink: 0; background: rgba(255,255,255,.04); transition: background .15s; }
        .s-link.active .s-icon { background: rgba(99,102,241,.15); }
        .s-label { transition: opacity .2s; }
        .sidebar.collapsed .s-label { opacity: 0; width: 0; overflow: hidden; }
        .s-badge { margin-left: auto; font-family: 'DM Mono',monospace; font-size: .57rem; padding: .1rem .4rem; border-radius: 100px; flex-shrink: 0; transition: opacity .2s; }
        .sidebar.collapsed .s-badge { opacity: 0; }
        .badge-red { background: rgba(244,63,94,.12); color: var(--coral); border: 1px solid rgba(244,63,94,.2); }
        .badge-green { background: rgba(16,185,129,.12); color: var(--teal); border: 1px solid rgba(16,185,129,.2); }
        .badge-indigo { background: rgba(99,102,241,.12); color: #818cf8; border: 1px solid rgba(99,102,241,.2); }

        /* Accordion */
        .s-acc-trigger { display: flex; align-items: center; gap: .65rem; padding: .52rem .5rem; border-radius: .5rem; color: var(--text2); font-size: .82rem; font-weight: 500; cursor: pointer; transition: all .15s; white-space: nowrap; user-select: none; }
        .s-acc-trigger:hover { background: rgba(255,255,255,.05); color: var(--text); }
        .s-acc-arrow { margin-left: auto; font-size: .55rem; transition: transform .2s; flex-shrink: 0; }
        .s-acc.open .s-acc-arrow { transform: rotate(90deg); }
        .s-acc-content { max-height: 0; overflow: hidden; transition: max-height .3s ease; padding-left: .875rem; }
        .s-acc.open .s-acc-content { max-height: 300px; }

        /* Sidebar footer */
        .s-footer { padding: .875rem 1rem; border-top: 1px solid var(--border); flex-shrink: 0; }
        .s-user { display: flex; align-items: center; gap: .65rem; }
        .s-avatar { width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg,var(--accent),var(--teal)); display: flex; align-items: center; justify-content: center; font-size: .65rem; font-weight: 800; color: white; flex-shrink: 0; }
        .s-user-info { flex: 1; min-width: 0; transition: opacity .2s; }
        .sidebar.collapsed .s-user-info { opacity: 0; width: 0; overflow: hidden; }
        .s-user-name { font-size: .75rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .s-user-role { font-family: 'DM Mono',monospace; font-size: .57rem; color: var(--teal); text-transform: uppercase; }
        .s-logout { width: 26px; height: 26px; border-radius: 5px; background: rgba(244,63,94,.08); border: 1px solid rgba(244,63,94,.15); color: var(--coral); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; flex-shrink: 0; font-size: .7rem; }
        .s-logout:hover { background: rgba(244,63,94,.15); }

        /* Main wrapper */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; transition: margin-left .3s cubic-bezier(.4,0,.2,1); }
        .main.collapsed { margin-left: var(--sidebar-c); }

        /* Header */
        .header { height: var(--header-h); background: var(--surface); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 30; display: flex; align-items: center; padding: 0 1.5rem; gap: .875rem; }
        .hamburger { display: none; width: 34px; height: 34px; border-radius: .5rem; background: var(--surface2); border: 1px solid var(--border); align-items: center; justify-content: center; cursor: pointer; flex-direction: column; gap: 4px; flex-shrink: 0; }
        .hamburger span { display: block; width: 15px; height: 1.5px; background: var(--text2); border-radius: 2px; }

        /* Breadcrumb */
        .breadcrumb { display: flex; align-items: center; gap: .4rem; font-size: .78rem; color: var(--text2); }
        .breadcrumb-sep { color: var(--text3); }
        .breadcrumb-current { color: var(--text); font-weight: 600; }

        /* Search */
        .search { display: flex; align-items: center; gap: .5rem; padding: .35rem .7rem; background: var(--surface2); border: 1px solid var(--border); border-radius: .5rem; cursor: pointer; transition: border-color .2s; min-width: 180px; }
        .search:hover { border-color: var(--border2); }
        .search-text { font-size: .73rem; color: var(--text3); flex: 1; }
        .search-kbd { font-family: 'DM Mono',monospace; font-size: .58rem; color: var(--text3); padding: .08rem .3rem; background: var(--surface); border: 1px solid var(--border); border-radius: 3px; }

        /* Header right */
        .h-right { margin-left: auto; display: flex; align-items: center; gap: .5rem; }
        .h-btn { width: 34px; height: 34px; border-radius: .5rem; background: var(--surface2); border: 1px solid var(--border); color: var(--text2); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; font-size: .85rem; position: relative; text-decoration: none; }
        .h-btn:hover { border-color: var(--border2); color: var(--text); }
        .h-notif-dot { position: absolute; top: 5px; right: 5px; width: 14px; height: 14px; background: var(--coral); border-radius: 50%; border: 2px solid var(--surface); font-family: 'DM Mono',monospace; font-size: .48rem; color: white; display: flex; align-items: center; justify-content: center; }
        .year-pill { display: flex; align-items: center; gap: .35rem; padding: .3rem .65rem; background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.2); border-radius: .5rem; font-family: 'DM Mono',monospace; font-size: .65rem; color: var(--teal); cursor: pointer; white-space: nowrap; }

        /* User trigger */
        .user-trigger { display: flex; align-items: center; gap: .45rem; padding: .3rem .65rem .3rem .3rem; background: var(--surface2); border: 1px solid var(--border); border-radius: .5rem; cursor: pointer; transition: border-color .2s; }
        .user-trigger:hover { border-color: var(--border2); }
        .ut-avatar { width: 26px; height: 26px; border-radius: 50%; background: linear-gradient(135deg,var(--accent),var(--teal)); display: flex; align-items: center; justify-content: center; font-size: .6rem; font-weight: 800; color: white; }
        .ut-name { font-size: .7rem; font-weight: 600; }
        .ut-role { font-family: 'DM Mono',monospace; font-size: .57rem; color: var(--text3); }
        .ut-arrow { font-size: .55rem; color: var(--text3); }

        /* Dropdown */
        .dd { position: relative; }
        .dd-menu { position: absolute; top: calc(100% + 8px); right: 0; background: var(--surface); border: 1px solid var(--border); border-radius: .75rem; min-width: 210px; box-shadow: 0 20px 40px rgba(0,0,0,.4); z-index: 100; overflow: hidden; display: none; }
        .dd-menu.open { display: block; animation: ddIn .15s ease; }
        @keyframes ddIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
        .dd-head { padding: .7rem .9rem; border-bottom: 1px solid var(--border); }
        .dd-title { font-size: .72rem; font-weight: 700; }
        .dd-sub { font-family: 'DM Mono',monospace; font-size: .58rem; color: var(--text3); margin-top: .1rem; }
        .dd-item { display: flex; align-items: center; gap: .55rem; padding: .55rem .9rem; font-size: .78rem; color: var(--text2); text-decoration: none; transition: all .15s; cursor: pointer; border: none; background: none; width: 100%; text-align: left; }
        .dd-item:hover { background: rgba(255,255,255,.04); color: var(--text); }
        .dd-item.danger { color: var(--coral); }
        .dd-item.danger:hover { background: rgba(244,63,94,.08); }
        .dd-sep { height: 1px; background: var(--border); margin: .2rem 0; }
        .notif-item { padding: .65rem .9rem; border-bottom: 1px solid var(--border); cursor: pointer; transition: background .15s; }
        .notif-item:hover { background: rgba(255,255,255,.03); }
        .notif-item:last-of-type { border-bottom: none; }
        .notif-unread { border-left: 2px solid var(--accent); }
        .notif-title { font-size: .75rem; font-weight: 500; margin-bottom: .1rem; }
        .notif-time { font-family: 'DM Mono',monospace; font-size: .58rem; color: var(--text3); }

        /* Content */
        .content { flex: 1; padding: 1.5rem; overflow-y: auto; }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); width: var(--sidebar-w) !important; }
            .sidebar.open { transform: translateX(0); box-shadow: 0 0 60px rgba(0,0,0,.5); }
            .main { margin-left: 0 !important; }
            .hamburger { display: flex; }
            .search { display: none; }
            .s-collapse { display: none; }
        }
        @media (max-width: 640px) {
            .header { padding: 0 1rem; gap: .5rem; }
            .breadcrumb { display: none; }
            .year-pill .lbl { display: none; }
            .ut-name, .ut-role { display: none; }
            .content { padding: .875rem; }
        }
    </style>
</head>
<body>
<div class="shell">

    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="s-brand">
            <div class="s-brand-icon">🎓</div>
            <span class="s-brand-text">EducConnect</span>
            <button class="s-collapse" onclick="toggleCollapse()">◀</button>
        </div>

        <div class="s-school">
            <div class="s-school-inner">
                <div class="s-school-name">{{ tenant()?->name ?? 'Mon École' }}</div>
                <div class="s-school-year">
                    <span class="s-school-dot"></span>
                    Année 2024–2025
                </div>
            </div>
        </div>

        <nav class="s-nav">
            <div class="s-section">
                <div class="s-section-label">Général</div>
                <a href="{{ route('dashboard') }}" class="s-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="s-icon">📊</div><span class="s-label">Vue d'ensemble</span>
                </a>
                <a href="#" class="s-link"><div class="s-icon">📅</div><span class="s-label">Années scolaires</span></a>
            </div>

            <div class="s-section">
                <div class="s-section-label">Pédagogie</div>
                <div class="s-acc" id="acc-classes">
                    <div class="s-acc-trigger" onclick="toggleAcc('acc-classes')">
                        <div class="s-icon">🏫</div>
                        <span class="s-label">Classes</span>
                        <span class="s-acc-arrow s-label">▶</span>
                    </div>
                    <div class="s-acc-content">
                        <a href="#" class="s-link" style="font-size:.78rem;"><div class="s-icon" style="font-size:.72rem;">📋</div><span class="s-label">Toutes les classes</span></a>
                        <a href="#" class="s-link" style="font-size:.78rem;"><div class="s-icon" style="font-size:.72rem;">➕</div><span class="s-label">Nouvelle classe</span></a>
                    </div>
                </div>
                <a href="#" class="s-link"><div class="s-icon">📚</div><span class="s-label">Matières</span></a>
                <a href="#" class="s-link"><div class="s-icon">🎯</div><span class="s-label">Promotions</span></a>
                <a href="#" class="s-link"><div class="s-icon">🗓️</div><span class="s-label">Emploi du temps</span></a>
            </div>

            <div class="s-section">
                <div class="s-section-label">Personnes</div>
                <a href="#" class="s-link"><div class="s-icon">👨‍🎓</div><span class="s-label">Apprenants</span><span class="s-badge badge-indigo">847</span></a>
                <a href="#" class="s-link"><div class="s-icon">👩‍🏫</div><span class="s-label">Enseignants</span><span class="s-badge badge-green">42</span></a>
                <a href="#" class="s-link"><div class="s-icon">👨‍👩‍👧</div><span class="s-label">Parents / Tuteurs</span></a>
            </div>

            <div class="s-section">
                <div class="s-section-label">Évaluation</div>
                <a href="#" class="s-link"><div class="s-icon">📝</div><span class="s-label">Notes</span><span class="s-badge badge-red">3</span></a>
                <a href="#" class="s-link"><div class="s-icon">✅</div><span class="s-label">Présences</span></a>
                <a href="#" class="s-link"><div class="s-icon">📄</div><span class="s-label">Bulletins PDF</span></a>
            </div>

            <div class="s-section">
                <div class="s-section-label">Finance</div>
                <a href="#" class="s-link"><div class="s-icon">💳</div><span class="s-label">Paiements</span></a>
            </div>

            <div class="s-section">
                <div class="s-section-label">Administration</div>
                <a href="#" class="s-link"><div class="s-icon">⚙️</div><span class="s-label">Paramètres</span></a>
                <a href="#" class="s-link"><div class="s-icon">🔔</div><span class="s-label">Notifications</span></a>
            </div>
        </nav>

        <div class="s-footer">
            <div class="s-user">
                <div class="s-avatar">{{ strtoupper(substr(Auth::guard('tenant')->user()?->name ?? 'U', 0, 1)) }}</div>
                <div class="s-user-info">
                    <div class="s-user-name">{{ Auth::guard('tenant')->user()?->name ?? 'Utilisateur' }}</div>
                    <div class="s-user-role">
                        @php $u = Auth::guard('tenant')->user(); @endphp
                        @if($u?->hasRole('directeur')) Directeur
                        @elseif($u?->hasRole('enseignant')) Enseignant
                        @elseif($u?->hasRole('tuteur')) Parent
                        @elseif($u?->hasRole('eleve')) Élève
                        @else Utilisateur @endif
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
                <div class="year-pill">📅 <span class="lbl">2024–2025</span></div>

                <button class="h-btn" title="Thème">🌙</button>

                <div class="dd">
                    <button class="h-btn" onclick="toggleDD('notif-menu')">
                        🔔 <span class="h-notif-dot">3</span>
                    </button>
                    <div class="dd-menu" id="notif-menu">
                        <div class="dd-head"><div class="dd-title">Notifications</div><div class="dd-sub">3 non lues</div></div>
                        <div class="notif-item notif-unread"><div class="notif-title">📝 Notes Maths — 3ème 1 saisies</div><div class="notif-time">il y a 5 min</div></div>
                        <div class="notif-item notif-unread"><div class="notif-title">👤 Nouveau parent inscrit</div><div class="notif-time">il y a 1h</div></div>
                        <div class="notif-item notif-unread"><div class="notif-title">💳 Paiement reçu — Kofi A.</div><div class="notif-time">il y a 3h</div></div>
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

        <main class="content" id="content">{{ $slot }}</main>
    </div>
</div>

<script>
let collapsed = localStorage.getItem('sc') === '1';

function applyCollapse() {
    document.getElementById('sidebar').classList.toggle('collapsed', collapsed);
    document.getElementById('main').classList.toggle('collapsed', collapsed);
}

function toggleCollapse() {
    collapsed = !collapsed;
    localStorage.setItem('sc', collapsed ? '1' : '0');
    applyCollapse();
}

function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
    document.body.style.overflow = '';
}

function toggleAcc(id) { document.getElementById(id).classList.toggle('open'); }

function toggleDD(id) {
    document.querySelectorAll('.dd-menu').forEach(m => { if (m.id !== id) m.classList.remove('open'); });
    document.getElementById(id).classList.toggle('open');
}

document.addEventListener('click', e => {
    if (!e.target.closest('.dd')) document.querySelectorAll('.dd-menu').forEach(m => m.classList.remove('open'));
});

document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeSidebar(); document.querySelectorAll('.dd-menu').forEach(m => m.classList.remove('open')); } });

function openSearch() { /* TODO: modal recherche */ }

document.addEventListener('DOMContentLoaded', () => {
    applyCollapse();
    if (window.Motion) window.Motion.animate('#content', { opacity: [0,1] }, { duration: .3 });
});
</script>
@livewireScripts
</body>
</html>