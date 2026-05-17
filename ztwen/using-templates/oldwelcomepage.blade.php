<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EducConnect — L'École de Demain, Aujourd'hui</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --navy:    #0a0e1a;
            --deep:    #0d1424;
            --gold:    #f5c842;
            --gold2:   #e8a020;
            --teal:    #00c9a7;
            --coral:   #ff6b6b;
            --lavender:#a78bfa;
            --white:   #f8f9ff;
            --muted:   #8892a4;
            --card-bg: rgba(255,255,255,0.04);
            --border:  rgba(255,255,255,0.08);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--navy);
            color: var(--white);
            overflow-x: hidden;
            cursor: none;
        }

        /* ── Curseur custom ── */
        .cursor {
            width: 12px; height: 12px;
            background: var(--gold);
            border-radius: 50%;
            position: fixed;
            top: 0; left: 0;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.1s;
            mix-blend-mode: difference;
        }
        .cursor-follower {
            width: 40px; height: 40px;
            border: 1.5px solid var(--gold);
            border-radius: 50%;
            position: fixed;
            top: 0; left: 0;
            pointer-events: none;
            z-index: 9998;
            transition: all 0.15s ease;
            opacity: 0.5;
        }

        /* ── Noise overlay ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
            opacity: 0.4;
        }

        /* ── NAVBAR ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 1.25rem 4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            background: rgba(10,14,26,0.6);
        }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, var(--gold), var(--teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-links a {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            transition: color 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px; left: 0;
            width: 0; height: 1px;
            background: var(--gold);
            transition: width 0.3s;
        }

        .nav-links a:hover { color: var(--white); }
        .nav-links a:hover::after { width: 100%; }

        .nav-cta {
            padding: 0.6rem 1.5rem;
            background: var(--gold);
            color: var(--navy) !important;
            border-radius: 100px;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            text-transform: none !important;
            letter-spacing: 0 !important;
            transition: all 0.3s !important;
        }
        .nav-cta::after { display: none !important; }
        .nav-cta:hover { background: var(--gold2) !important; transform: scale(1.05); }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 8rem 4rem 4rem;
        }

        /* Mesh gradient background */
        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 50%, rgba(0,201,167,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 30%, rgba(167,139,250,0.1) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 60% 80%, rgba(245,200,66,0.08) 0%, transparent 50%);
        }

        /* Grille décorative */
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black, transparent);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: rgba(245,200,66,0.1);
            border: 1px solid rgba(245,200,66,0.3);
            border-radius: 100px;
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            color: var(--gold);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 2rem;
            opacity: 0;
        }

        .hero-badge .dot {
            width: 6px; height: 6px;
            background: var(--teal);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.5); }
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(3rem, 7vw, 6rem);
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
            opacity: 0;
        }

        .hero-title .line { display: block; overflow: hidden; }
        .hero-title .line span { display: block; }

        .hero-title .accent {
            font-style: italic;
            background: linear-gradient(135deg, var(--gold) 0%, var(--teal) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-sub {
            font-size: 1.15rem;
            color: var(--muted);
            line-height: 1.7;
            max-width: 580px;
            margin: 0 auto 3rem;
            opacity: 0;
            font-weight: 300;
        }

        .hero-sub strong {
            color: var(--white);
            font-weight: 500;
        }

        /* ── Boutons de connexion rôles ── */
        .role-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            opacity: 0;
            margin-bottom: 4rem;
        }

        .role-btn {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.75rem;
            border-radius: 1rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid transparent;
        }

        .role-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .role-btn:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .role-btn:hover::before { opacity: 1; }

        .role-btn .icon {
            width: 36px; height: 36px;
            border-radius: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .role-btn .label { display: flex; flex-direction: column; text-align: left; }
        .role-btn .label small {
            font-size: 0.7rem;
            font-weight: 400;
            opacity: 0.7;
            font-family: 'Space Mono', monospace;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Directeur */
        .btn-director {
            background: linear-gradient(135deg, rgba(245,200,66,0.15), rgba(232,160,32,0.1));
            border-color: rgba(245,200,66,0.3);
            color: var(--gold);
        }
        .btn-director::before {
            background: linear-gradient(135deg, rgba(245,200,66,0.2), rgba(232,160,32,0.15));
        }
        .btn-director .icon { background: rgba(245,200,66,0.15); }

        /* Enseignant */
        .btn-teacher {
            background: linear-gradient(135deg, rgba(0,201,167,0.15), rgba(0,180,150,0.1));
            border-color: rgba(0,201,167,0.3);
            color: var(--teal);
        }
        .btn-teacher::before {
            background: linear-gradient(135deg, rgba(0,201,167,0.2), rgba(0,180,150,0.15));
        }
        .btn-teacher .icon { background: rgba(0,201,167,0.15); }

        /* Parent */
        .btn-parent {
            background: linear-gradient(135deg, rgba(167,139,250,0.15), rgba(139,92,246,0.1));
            border-color: rgba(167,139,250,0.3);
            color: var(--lavender);
        }
        .btn-parent::before {
            background: linear-gradient(135deg, rgba(167,139,250,0.2), rgba(139,92,246,0.15));
        }
        .btn-parent .icon { background: rgba(167,139,250,0.15); }

        /* Élève */
        .btn-student {
            background: linear-gradient(135deg, rgba(255,107,107,0.15), rgba(255,80,80,0.1));
            border-color: rgba(255,107,107,0.3);
            color: var(--coral);
        }
        .btn-student::before {
            background: linear-gradient(135deg, rgba(255,107,107,0.2), rgba(255,80,80,0.15));
        }
        .btn-student .icon { background: rgba(255,107,107,0.15); }

        /* ── Stats ── */
        .hero-stats {
            display: flex;
            gap: 3rem;
            justify-content: center;
            opacity: 0;
        }

        .stat {
            text-align: center;
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--gold), var(--teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 0.25rem;
            font-family: 'Space Mono', monospace;
        }

        /* ── Scroll indicator ── */
        .scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            color: var(--muted);
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            font-family: 'Space Mono', monospace;
        }

        .scroll-line {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, var(--gold), transparent);
            animation: scrollLine 2s ease infinite;
        }

        @keyframes scrollLine {
            0% { transform: scaleY(0); transform-origin: top; }
            50% { transform: scaleY(1); transform-origin: top; }
            51% { transform: scaleY(1); transform-origin: bottom; }
            100% { transform: scaleY(0); transform-origin: bottom; }
        }

        /* ── FEATURES SECTION ── */
        .section {
            padding: 8rem 4rem;
            position: relative;
        }

        .section-tag {
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            color: var(--teal);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
        }

        .section-sub {
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.7;
            max-width: 480px;
            font-weight: 300;
        }

        /* Features grid */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            align-items: center;
        }

        .features-text { padding-right: 4rem; }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .feature-item {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            opacity: 0;
            transform: translateX(-20px);
        }

        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .feature-icon.gold { background: rgba(245,200,66,0.1); }
        .feature-icon.teal { background: rgba(0,201,167,0.1); }
        .feature-icon.lavender { background: rgba(167,139,250,0.1); }
        .feature-icon.coral { background: rgba(255,107,107,0.1); }

        .feature-text h4 {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .feature-text p {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── Dashboard mockup ── */
        .dashboard-mockup {
            position: relative;
            opacity: 0;
            transform: translateX(30px);
        }

        .mockup-window {
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.05),
                0 40px 80px rgba(0,0,0,0.5),
                0 0 80px rgba(0,201,167,0.08);
        }

        .mockup-bar {
            padding: 0.75rem 1rem;
            background: rgba(255,255,255,0.03);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .mockup-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
        }

        .mockup-content {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .mockup-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .mockup-card .mc-label {
            font-size: 0.65rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-family: 'Space Mono', monospace;
            margin-bottom: 0.5rem;
        }

        .mockup-card .mc-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 900;
        }

        .mc-gold { color: var(--gold); }
        .mc-teal { color: var(--teal); }
        .mc-lavender { color: var(--lavender); }
        .mc-coral { color: var(--coral); }

        .mockup-bar-chart {
            grid-column: span 2;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .bar-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.6rem;
        }

        .bar-label {
            font-size: 0.65rem;
            color: var(--muted);
            width: 60px;
            text-align: right;
            font-family: 'Space Mono', monospace;
        }

        .bar-track {
            flex: 1;
            height: 6px;
            background: rgba(255,255,255,0.05);
            border-radius: 100px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            border-radius: 100px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* ── SLIDES / TESTIMONIALS ── */
        .slides-section {
            background: rgba(255,255,255,0.02);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            overflow: hidden;
        }

        .slides-track {
            display: flex;
            gap: 1.5rem;
            animation: slideTrack 30s linear infinite;
            width: max-content;
        }

        .slides-track:hover { animation-play-state: paused; }

        @keyframes slideTrack {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        .slide-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            padding: 1.75rem;
            width: 340px;
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .slide-card:hover {
            border-color: rgba(245,200,66,0.2);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .slide-quote {
            font-size: 0.9rem;
            line-height: 1.7;
            color: rgba(248,249,255,0.8);
            margin-bottom: 1.25rem;
            font-style: italic;
        }

        .slide-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .slide-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .slide-info h5 {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .slide-info small {
            font-size: 0.7rem;
            color: var(--muted);
            font-family: 'Space Mono', monospace;
        }

        /* ── HOW IT WORKS ── */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            max-width: 1200px;
            margin: 4rem auto 0;
        }

        .step {
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
        }

        .step-number {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, rgba(245,200,66,0.3), rgba(0,201,167,0.3));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .step-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .step-desc {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── ROLES SECTION ── */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            max-width: 1200px;
            margin: 4rem auto 0;
        }

        .role-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            padding: 2rem 1.5rem;
            text-align: center;
            text-decoration: none;
            color: var(--white);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            opacity: 0;
            transform: translateY(20px);
            position: relative;
            overflow: hidden;
        }

        .role-card::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.4s;
        }

        .role-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }

        .role-card:hover::before { opacity: 1; }

        .role-card.director::before { background: radial-gradient(ellipse at top, rgba(245,200,66,0.12), transparent 70%); }
        .role-card.teacher::before  { background: radial-gradient(ellipse at top, rgba(0,201,167,0.12), transparent 70%); }
        .role-card.parent::before   { background: radial-gradient(ellipse at top, rgba(167,139,250,0.12), transparent 70%); }
        .role-card.student::before  { background: radial-gradient(ellipse at top, rgba(255,107,107,0.12), transparent 70%); }

        .role-card:hover.director { border-color: rgba(245,200,66,0.4); }
        .role-card:hover.teacher  { border-color: rgba(0,201,167,0.4); }
        .role-card:hover.parent   { border-color: rgba(167,139,250,0.4); }
        .role-card:hover.student  { border-color: rgba(255,107,107,0.4); }

        .role-emoji {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        .role-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .role-desc {
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .role-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 100px;
            transition: all 0.3s;
            font-family: 'Space Mono', monospace;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .director .role-link { background: rgba(245,200,66,0.1); color: var(--gold); border: 1px solid rgba(245,200,66,0.2); }
        .teacher  .role-link { background: rgba(0,201,167,0.1);  color: var(--teal); border: 1px solid rgba(0,201,167,0.2); }
        .parent   .role-link { background: rgba(167,139,250,0.1);color: var(--lavender); border: 1px solid rgba(167,139,250,0.2); }
        .student  .role-link { background: rgba(255,107,107,0.1); color: var(--coral); border: 1px solid rgba(255,107,107,0.2); }

        .role-card:hover .role-link { transform: scale(1.05); }

        /* ── CTA SECTION ── */
        .cta-section {
            padding: 8rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% 50%,
                rgba(0,201,167,0.08) 0%, transparent 70%);
        }

        .cta-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
            opacity: 0;
        }

        .cta-sub {
            font-size: 1.1rem;
            color: var(--muted);
            margin-bottom: 3rem;
            opacity: 0;
            font-weight: 300;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            color: var(--navy);
            border-radius: 100px;
            font-weight: 800;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            opacity: 0;
            box-shadow: 0 0 40px rgba(245,200,66,0.3);
        }

        .cta-btn:hover {
            transform: scale(1.06) translateY(-3px);
            box-shadow: 0 0 60px rgba(245,200,66,0.5);
        }

        /* ── FOOTER ── */
        footer {
            background: var(--deep);
            border-top: 1px solid var(--border);
            padding: 4rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto 3rem;
        }

        .footer-brand .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--gold), var(--teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .footer-brand p {
            font-size: 0.875rem;
            color: var(--muted);
            line-height: 1.7;
            max-width: 280px;
        }

        .footer-col h5 {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--muted);
            margin-bottom: 1.25rem;
            font-family: 'Space Mono', monospace;
        }

        .footer-col ul { list-style: none; }

        .footer-col ul li {
            margin-bottom: 0.75rem;
        }

        .footer-col ul li a {
            font-size: 0.875rem;
            color: rgba(248,249,255,0.6);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-col ul li a:hover { color: var(--gold); }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-bottom p {
            font-size: 0.75rem;
            color: var(--muted);
            font-family: 'Space Mono', monospace;
        }

        .footer-love {
            font-size: 0.75rem;
            color: var(--muted);
            font-family: 'Space Mono', monospace;
        }

        .footer-love span { color: var(--coral); }

        /* ── Floating orbs ── */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            nav { padding: 1rem 1.5rem; }
            .nav-links { display: none; }
            .hero { padding: 6rem 1.5rem 3rem; }
            .section { padding: 5rem 1.5rem; }
            .features-grid { grid-template-columns: 1fr; }
            .features-text { padding-right: 0; }
            .steps-grid { grid-template-columns: repeat(2, 1fr); }
            .roles-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr; }
            .role-buttons { flex-direction: column; align-items: center; }
            .hero-stats { gap: 1.5rem; }
        }
    </style>
</head>
<body>

    <!-- Curseur custom -->
    <div class="cursor" id="cursor"></div>
    <div class="cursor-follower" id="cursorFollower"></div>

    <!-- NAVBAR -->
    <nav id="navbar">
        <div class="nav-logo">EducConnect</div>
        <ul class="nav-links">
            <li><a href="#features">Fonctionnalités</a></li>
            <li><a href="#how">Comment ça marche</a></li>
            <li><a href="#roles">Accès</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="/login" class="nav-cta">Connexion →</a></li>
        </ul>
    </nav>

    <!-- HERO -->
    <section class="hero" id="hero">
        <div class="hero-bg"></div>
        <div class="hero-grid"></div>

        <!-- Orbs flottants -->
        <div class="orb" style="width:400px;height:400px;background:rgba(0,201,167,0.06);top:-100px;left:-100px;animation-delay:0s;"></div>
        <div class="orb" style="width:300px;height:300px;background:rgba(245,200,66,0.05);bottom:-50px;right:-50px;animation-delay:3s;"></div>
        <div class="orb" style="width:200px;height:200px;background:rgba(167,139,250,0.06);top:40%;right:10%;animation-delay:1.5s;"></div>

        <div class="hero-content">
            <div class="hero-badge" id="heroBadge">
                <span class="dot"></span>
                Plateforme SaaS Multi-École — 2025
            </div>

            <h1 class="hero-title" id="heroTitle">
                <span class="line"><span>L'éducation</span></span>
                <span class="line"><span class="accent">réinventée,</span></span>
                <span class="line"><span>sans frontières.</span></span>
            </h1>

            <p class="hero-sub" id="heroSub">
                EducConnect unifie <strong>directeurs, enseignants, parents et élèves</strong>
                dans un écosystème intelligent. Chaque école, son univers.
                Chaque acteur, son pouvoir.
            </p>

            <div class="role-buttons" id="roleButtons">
                <a href="/login?role=director" class="role-btn btn-director">
                    <div class="icon">🏛️</div>
                    <div class="label">
                        <small>Espace</small>
                        Directeur
                    </div>
                </a>
                <a href="/login?role=teacher" class="role-btn btn-teacher">
                    <div class="icon">📚</div>
                    <div class="label">
                        <small>Espace</small>
                        Enseignant
                    </div>
                </a>
                <a href="/login?role=parent" class="role-btn btn-parent">
                    <div class="icon">👨‍👩‍👧</div>
                    <div class="label">
                        <small>Espace</small>
                        Parent d'élève
                    </div>
                </a>
                <a href="/login?role=student" class="role-btn btn-student">
                    <div class="icon">🎓</div>
                    <div class="label">
                        <small>Espace</small>
                        Élève
                    </div>
                </a>
            </div>

            <div class="hero-stats" id="heroStats">
                <div class="stat">
                    <div class="stat-num" data-target="500">0</div>
                    <div class="stat-label">Écoles connectées</div>
                </div>
                <div class="stat">
                    <div class="stat-num" data-target="50000">0</div>
                    <div class="stat-label">Élèves suivis</div>
                </div>
                <div class="stat">
                    <div class="stat-num" data-target="99">0</div>
                    <div class="stat-label">% Satisfaction</div>
                </div>
            </div>
        </div>

        <div class="scroll-indicator" id="scrollIndicator">
            <div class="scroll-line"></div>
            <span>Défiler</span>
        </div>
    </section>

    <!-- SLIDES / TESTIMONIALS -->
    <section class="slides-section section" style="padding: 4rem 0;">
        <div style="padding: 0 4rem; margin-bottom: 2rem; text-align:center;">
            <div class="section-tag">Témoignages</div>
        </div>
        <div style="overflow: hidden; padding: 1rem 0;">
            <div class="slides-track" id="slidesTrack">
                <!-- Les cartes sont dupliquées pour l'effet infini -->
                <div class="slide-card">
                    <p class="slide-quote">« EducConnect a transformé notre manière de gérer l'école. Les bulletins PDF générés automatiquement nous font gagner des heures chaque trimestre. »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(245,200,66,0.15);color:var(--gold);">ML</div>
                        <div class="slide-info">
                            <h5>Marie-Louise Adjovi</h5>
                            <small>Directrice — Collège Saint-Michel</small>
                        </div>
                    </div>
                </div>
                <div class="slide-card">
                    <p class="slide-quote">« Je consulte les notes de mon fils en temps réel. Fini les surprises en fin de trimestre. C'est une révolution pour nous les parents. »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(167,139,250,0.15);color:var(--lavender);">KD</div>
                        <div class="slide-info">
                            <h5>Kofi Danso</h5>
                            <small>Parent d'élève</small>
                        </div>
                    </div>
                </div>
                <div class="slide-card">
                    <p class="slide-quote">« La saisie des notes par Excel ou unitairement, l'historique des modifications... tout est pensé pour l'enseignant. Aucun autre outil n'arrive à la cheville. »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(0,201,167,0.15);color:var(--teal);">FB</div>
                        <div class="slide-info">
                            <h5>Fabrice Bossou</h5>
                            <small>Professeur de Mathématiques</small>
                        </div>
                    </div>
                </div>
                <div class="slide-card">
                    <p class="slide-quote">« Mon bulletin est disponible en quelques secondes. Je peux le partager avec mes parents directement depuis l'application. Vraiment top ! »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(255,107,107,0.15);color:var(--coral);">AM</div>
                        <div class="slide-info">
                            <h5>Aminata Mensah</h5>
                            <small>Élève — Terminale C</small>
                        </div>
                    </div>
                </div>
                <div class="slide-card">
                    <p class="slide-quote">« L'isolation des données entre écoles est parfaite. Chaque établissement a son propre univers sécurisé. Une architecture exemplaire. »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(245,200,66,0.15);color:var(--gold);">JK</div>
                        <div class="slide-info">
                            <h5>Jean-Baptiste Koudé</h5>
                            <small>DSI — Groupe Scolaire Excellence</small>
                        </div>
                    </div>
                </div>
                <div class="slide-card">
                    <p class="slide-quote">« Le système de token annuel pour les enseignants est brillant. Chaque année, un nouveau cycle, une nouvelle validation. Sécurité maximale. »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(0,201,167,0.15);color:var(--teal);">SA</div>
                        <div class="slide-info">
                            <h5>Sylvie Amoussou</h5>
                            <small>Directrice Administrative</small>
                        </div>
                    </div>
                </div>
                <!-- Duplicate pour boucle infinie -->
                <div class="slide-card">
                    <p class="slide-quote">« EducConnect a transformé notre manière de gérer l'école. Les bulletins PDF générés automatiquement nous font gagner des heures chaque trimestre. »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(245,200,66,0.15);color:var(--gold);">ML</div>
                        <div class="slide-info">
                            <h5>Marie-Louise Adjovi</h5>
                            <small>Directrice — Collège Saint-Michel</small>
                        </div>
                    </div>
                </div>
                <div class="slide-card">
                    <p class="slide-quote">« Je consulte les notes de mon fils en temps réel. Fini les surprises en fin de trimestre. C'est une révolution pour nous les parents. »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(167,139,250,0.15);color:var(--lavender);">KD</div>
                        <div class="slide-info">
                            <h5>Kofi Danso</h5>
                            <small>Parent d'élève</small>
                        </div>
                    </div>
                </div>
                <div class="slide-card">
                    <p class="slide-quote">« La saisie des notes par Excel ou unitairement, l'historique des modifications... tout est pensé pour l'enseignant. Aucun autre outil n'arrive à la cheville. »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(0,201,167,0.15);color:var(--teal);">FB</div>
                        <div class="slide-info">
                            <h5>Fabrice Bossou</h5>
                            <small>Professeur de Mathématiques</small>
                        </div>
                    </div>
                </div>
                <div class="slide-card">
                    <p class="slide-quote">« Mon bulletin est disponible en quelques secondes. Je peux le partager avec mes parents directement depuis l'application. Vraiment top ! »</p>
                    <div class="slide-author">
                        <div class="slide-avatar" style="background:rgba(255,107,107,0.15);color:var(--coral);">AM</div>
                        <div class="slide-info">
                            <h5>Aminata Mensah</h5>
                            <small>Élève — Terminale C</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="section" id="features">
        <div class="features-grid">
            <div class="features-text">
                <div class="section-tag">Fonctionnalités</div>
                <h2 class="section-title">
                    Tout ce dont une<br>
                    <em style="font-style:italic;background:linear-gradient(135deg,var(--gold),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                        école moderne
                    </em><br>
                    a besoin.
                </h2>
                <p class="section-sub">
                    De la gestion des inscriptions aux bulletins PDF, en passant par
                    le suivi des présences et les notifications WhatsApp —
                    EducConnect couvre l'intégralité du cycle scolaire.
                </p>

                <div class="feature-list">
                    <div class="feature-item" id="f1">
                        <div class="feature-icon gold">📊</div>
                        <div class="feature-text">
                            <h4>Gestion multi-année intelligente</h4>
                            <p>Toutes les données archivées par année scolaire. Rien n'est jamais perdu. Naviguez dans le passé en un clic.</p>
                        </div>
                    </div>
                    <div class="feature-item" id="f2">
                        <div class="feature-icon teal">📝</div>
                        <div class="feature-text">
                            <h4>Notes avec historique d'édition</h4>
                            <p>Chaque modification tracée, chaque éditeur identifié. La transparence comme fondement de la confiance.</p>
                        </div>
                    </div>
                    <div class="feature-item" id="f3">
                        <div class="feature-icon lavender">📱</div>
                        <div class="feature-text">
                            <h4>Notifications SMS & WhatsApp</h4>
                            <p>Bulletins, absences, résultats — les parents reçoivent l'information instantanément, sur le canal qu'ils préfèrent.</p>
                        </div>
                    </div>
                    <div class="feature-item" id="f4">
                        <div class="feature-icon coral">🔐</div>
                        <div class="feature-text">
                            <h4>Accès sécurisé par token annuel</h4>
                            <p>Enseignants et parents revalidés chaque année. Un système vivant, adapté aux réalités du terrain.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-mockup" id="dashboardMockup">
                <div class="mockup-window">
                    <div class="mockup-bar">
                        <div class="mockup-dot" style="background:#ff5f57;"></div>
                        <div class="mockup-dot" style="background:#febc2e;"></div>
                        <div class="mockup-dot" style="background:#28c840;"></div>
                        <div style="flex:1;text-align:center;font-size:0.65rem;color:var(--muted);font-family:'Space Mono',monospace;">
                            ecole-excellence.educconnect.com
                        </div>
                    </div>
                    <div class="mockup-content">
                        <div class="mockup-card">
                            <div class="mc-label">Élèves actifs</div>
                            <div class="mc-value mc-gold">847</div>
                        </div>
                        <div class="mockup-card">
                            <div class="mc-label">Enseignants</div>
                            <div class="mc-value mc-teal">42</div>
                        </div>
                        <div class="mockup-card">
                            <div class="mc-label">Classes</div>
                            <div class="mc-value mc-lavender">18</div>
                        </div>
                        <div class="mockup-card">
                            <div class="mc-label">Taux présence</div>
                            <div class="mc-value mc-coral">94%</div>
                        </div>
                        <div class="mockup-bar-chart">
                            <div class="mc-label" style="margin-bottom:0.75rem;">Moyennes par classe</div>
                            <div class="bar-row">
                                <div class="bar-label">Tle C</div>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:82%;background:linear-gradient(90deg,var(--gold),var(--gold2));"></div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label">3ème 1</div>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:74%;background:linear-gradient(90deg,var(--teal),#00b494);"></div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label">6ème A</div>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:88%;background:linear-gradient(90deg,var(--lavender),#8b5cf6);"></div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label">Tle BTP</div>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:65%;background:linear-gradient(90deg,var(--coral),#ff4040);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="section" id="how" style="background:rgba(255,255,255,0.01);border-top:1px solid var(--border);">
        <div style="max-width:1200px;margin:0 auto;text-align:center;">
            <div class="section-tag">Comment ça marche</div>
            <h2 class="section-title">Simple. Rapide.<br><em style="font-style:italic;background:linear-gradient(135deg,var(--gold),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Révolutionnaire.</em></h2>
        </div>
        <div class="steps-grid">
            <div class="step" id="step1">
                <div class="step-number">01</div>
                <h3 class="step-title">Demande de création</h3>
                <p class="step-desc">Le directeur soumet une demande avec les informations de son établissement et choisit son abonnement.</p>
            </div>
            <div class="step" id="step2">
                <div class="step-number">02</div>
                <h3 class="step-title">Activation instantanée</h3>
                <p class="step-desc">L'administrateur valide. Un sous-domaine unique est créé. La base de données de l'école est initialisée.</p>
            </div>
            <div class="step" id="step3">
                <div class="step-number">03</div>
                <h3 class="step-title">Configuration de l'école</h3>
                <p class="step-desc">Classes, filières, matières, enseignants — tout est paramétrable en quelques clics.</p>
            </div>
            <div class="step" id="step4">
                <div class="step-number">04</div>
                <h3 class="step-title">Toute la communauté</h3>
                <p class="step-desc">Élèves inscrits, parents notifiés, enseignants invités. L'école vit, grandit, prospère.</p>
            </div>
        </div>
    </section>

    <!-- ROLES -->
    <section class="section" id="roles">
        <div style="max-width:1200px;margin:0 auto;text-align:center;">
            <div class="section-tag">Votre espace</div>
            <h2 class="section-title">Chaque acteur,<br><em style="font-style:italic;background:linear-gradient(135deg,var(--gold),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">son univers.</em></h2>
            <p class="section-sub" style="margin:0 auto;">
                EducConnect s'adapte à votre rôle. Connectez-vous et découvrez un espace taillé sur mesure pour vous.
            </p>
        </div>
        <div class="roles-grid">
            <a href="/login?role=director" class="role-card director" id="rc1">
                <span class="role-emoji">🏛️</span>
                <h3 class="role-name">Directeur</h3>
                <p class="role-desc">Pilotez votre établissement avec une vue d'ensemble totale. Gérez les classes, les enseignants, les inscriptions et les finances.</p>
                <span class="role-link">Accéder →</span>
            </a>
            <a href="/login?role=teacher" class="role-card teacher" id="rc2">
                <span class="role-emoji">📚</span>
                <h3 class="role-name">Enseignant</h3>
                <p class="role-desc">Vos classes, vos matières, vos notes. Un espace dédié pour gérer vos élèves et communiquer avec les familles.</p>
                <span class="role-link">Accéder →</span>
            </a>
            <a href="/login?role=parent" class="role-card parent" id="rc3">
                <span class="role-emoji">👨‍👩‍👧</span>
                <h3 class="role-name">Parent d'élève</h3>
                <p class="role-desc">Suivez la scolarité de vos enfants en temps réel. Notes, présences, bulletins — tout en un seul endroit.</p>
                <span class="role-link">Accéder →</span>
            </a>
            <a href="/login?role=student" class="role-card student" id="rc4">
                <span class="role-emoji">🎓</span>
                <h3 class="role-name">Élève</h3>
                <p class="role-desc">Consultez vos résultats, téléchargez vos bulletins et suivez votre progression tout au long de l'année.</p>
                <span class="role-link">Accéder →</span>
            </a>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section" id="contact">
        <div class="cta-bg"></div>
        <div style="position:relative;z-index:2;">
            <h2 class="cta-title" id="ctaTitle">
                Prêt à transformer<br>
                <em style="font-style:italic;background:linear-gradient(135deg,var(--gold),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                    votre école ?
                </em>
            </h2>
            <p class="cta-sub" id="ctaSub">
                Rejoignez des centaines d'établissements qui font confiance à EducConnect.<br>
                Démarrez gratuitement, évoluez à votre rythme.
            </p>
            <a href="/login" class="cta-btn" id="ctaBtn">
                ✦ Créer mon école maintenant
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="brand-name">EducConnect</div>
                <p>La plateforme SaaS qui connecte toute la communauté éducative dans un écosystème intelligent, sécurisé et multi-école.</p>
                <div style="margin-top:1.5rem;display:flex;gap:0.75rem;">
                    <a href="#" style="width:36px;height:36px;background:var(--card-bg);border:1px solid var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--muted);text-decoration:none;transition:all 0.3s;font-size:0.9rem;" onmouseover="this.style.borderColor='rgba(245,200,66,0.4)';this.style.color='var(--gold)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">✉</a>
                    <a href="#" style="width:36px;height:36px;background:var(--card-bg);border:1px solid var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--muted);text-decoration:none;transition:all 0.3s;font-size:0.9rem;" onmouseover="this.style.borderColor='rgba(0,201,167,0.4)';this.style.color='var(--teal)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">📱</a>
                </div>
            </div>
            <div class="footer-col">
                <h5>Plateforme</h5>
                <ul>
                    <li><a href="#features">Fonctionnalités</a></li>
                    <li><a href="#how">Comment ça marche</a></li>
                    <li><a href="#">Tarifs</a></li>
                    <li><a href="#">Roadmap</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Espaces</h5>
                <ul>
                    <li><a href="/login?role=director">Directeur</a></li>
                    <li><a href="/login?role=teacher">Enseignant</a></li>
                    <li><a href="/login?role=parent">Parent</a></li>
                    <li><a href="/login?role=student">Élève</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Légal</h5>
                <ul>
                    <li><a href="#">Confidentialité</a></li>
                    <li><a href="#">CGU</a></li>
                    <li><a href="#">Sécurité</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 EducConnect. Tous droits réservés.</p>
            <p class="footer-love">Fait avec <span>♥</span> pour l'éducation africaine</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        // Utiliser window.Motion chargé par app.js
        const { animate, stagger, inView, scroll } = window.Motion;

        // ── Curseur custom ──
        const cursor = document.getElementById('cursor');
        const follower = document.getElementById('cursorFollower');
        let mouseX = 0, mouseY = 0;
        let followerX = 0, followerY = 0;

        // Cacher le curseur natif partout
        document.documentElement.style.cursor = 'none';

        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
            cursor.style.left   = mouseX - 6 + 'px';
            cursor.style.top    = mouseY - 6 + 'px';
            cursor.style.opacity = '1';
            follower.style.opacity = '0.5';
        });

        document.addEventListener('mouseleave', () => {
            cursor.style.opacity   = '0';
            follower.style.opacity = '0';
        });

        document.addEventListener('mouseenter', () => {
            cursor.style.opacity   = '1';
            follower.style.opacity = '0.5';
        });

        function animateFollower() {
            followerX += (mouseX - followerX) * 0.12;
            followerY += (mouseY - followerY) * 0.12;
            follower.style.left = followerX - 20 + 'px';
            follower.style.top  = followerY - 20 + 'px';
            requestAnimationFrame(animateFollower);
        }
        animateFollower();

        document.querySelectorAll('a, button').forEach(el => {
            el.style.cursor = 'none';
            el.addEventListener('mouseenter', () => {
                cursor.style.transform = 'scale(2)';
                follower.style.transform = 'scale(1.5)';
            });
            el.addEventListener('mouseleave', () => {
                cursor.style.transform = 'scale(1)';
                follower.style.transform = 'scale(1)';
            });
        });

        document.querySelectorAll('a, button').forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.style.transform = 'scale(2)';
                follower.style.transform = 'scale(1.5)';
            });
            el.addEventListener('mouseleave', () => {
                cursor.style.transform = 'scale(1)';
                follower.style.transform = 'scale(1)';
            });
        });

        // ── Hero animations ──
        animate('#heroBadge',
            { opacity: [0, 1], y: [20, 0] },
            { duration: 0.6, delay: 0.2 }
        );

        animate('#heroTitle',
            { opacity: [0, 1] },
            { duration: 0.1, delay: 0.4 }
        );

        // Animate each line of title
        document.querySelectorAll('.hero-title .line span').forEach((el, i) => {
            animate(el,
                { y: ['100%', '0%'] },
                { duration: 0.7, delay: 0.5 + i * 0.15, easing: [0.22, 1, 0.36, 1] }
            );
        });

        animate('#heroSub',
            { opacity: [0, 1], y: [20, 0] },
            { duration: 0.6, delay: 1 }
        );

        animate('#roleButtons',
            { opacity: [0, 1], y: [20, 0] },
            { duration: 0.6, delay: 1.2 }
        );

        animate('#heroStats',
            { opacity: [0, 1], y: [20, 0] },
            { duration: 0.6, delay: 1.4 }
        );

        animate('#scrollIndicator',
            { opacity: [0, 0.7] },
            { duration: 0.6, delay: 2 }
        );

        // ── Compteur stats ──
        function animateCounter(el, target, suffix = '') {
            let start = 0;
            const duration = 2000;
            const step = (timestamp) => {
                if (!start) start = timestamp;
                const progress = Math.min((timestamp - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.floor(eased * target).toLocaleString() + suffix;
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        }

        inView('#heroStats', () => {
            document.querySelectorAll('.stat-num').forEach(el => {
                const target = parseInt(el.dataset.target);
                const suffix = el.dataset.target === '99' ? '%' :
                               el.dataset.target === '50000' ? '+' : '+';
                animateCounter(el, target, suffix);
            });
        });

        // ── Features animations ──
        inView('#f1', ({ target }) => animate(target, { opacity: [0,1], x: [-20,0] }, { duration: 0.5 }));
        inView('#f2', ({ target }) => animate(target, { opacity: [0,1], x: [-20,0] }, { duration: 0.5, delay: 0.1 }));
        inView('#f3', ({ target }) => animate(target, { opacity: [0,1], x: [-20,0] }, { duration: 0.5, delay: 0.2 }));
        inView('#f4', ({ target }) => animate(target, { opacity: [0,1], x: [-20,0] }, { duration: 0.5, delay: 0.3 }));

        inView('#dashboardMockup', ({ target }) => {
            animate(target, { opacity: [0,1], x: [30,0] }, { duration: 0.7 });
            // Animer les barres
            setTimeout(() => {
                document.querySelectorAll('.bar-fill').forEach((bar, i) => {
                    bar.style.transform = 'scaleX(1)';
                });
            }, 400);
        });

        // ── Steps ──
        ['step1','step2','step3','step4'].forEach((id, i) => {
            inView(`#${id}`, ({ target }) => {
                animate(target, { opacity: [0,1], y: [20,0] }, { duration: 0.5, delay: i * 0.1 });
            });
        });

        // ── Role cards ──
        ['rc1','rc2','rc3','rc4'].forEach((id, i) => {
            inView(`#${id}`, ({ target }) => {
                animate(target, { opacity: [0,1], y: [20,0] }, { duration: 0.5, delay: i * 0.1 });
            });
        });

        // ── CTA ──
        inView('#ctaTitle', ({ target }) => animate(target, { opacity: [0,1], y: [30,0] }, { duration: 0.6 }));
        inView('#ctaSub',   ({ target }) => animate(target, { opacity: [0,1], y: [20,0] }, { duration: 0.6, delay: 0.15 }));
        inView('#ctaBtn',   ({ target }) => animate(target, { opacity: [0,1], y: [20,0] }, { duration: 0.6, delay: 0.3 }));

        // ── Navbar scroll ──
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(10,14,26,0.95)';
            } else {
                navbar.style.background = 'rgba(10,14,26,0.6)';
            }
        }); // fin scroll listener

        }); // fin DOMContentLoaded
    </script>
</body>
</html>