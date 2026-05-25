<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EducConnect — L'École de Demain, Aujourd'hui</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/welcome.css', 'resources/js/welcome.js'])

</head>

<body>

    <!-- Curseur custom -->
    <div class="cursor" id="cursor"></div>
    <div class="cursor-follower" id="cursorFollower"></div>

    <!-- NAVBAR -->
    <nav id="navbar">
        <div class="nav-logo">EducConnect</div>
        <ul class="nav-links" id="navLinks">
            <li><a href="#features" onclick="closeMenu()">Fonctionnalités</a></li>
            <li><a href="#how" onclick="closeMenu()">Comment ça marche</a></li>
            <li><a href="#roles" onclick="closeMenu()">Accès</a></li>
            <li><a href="#contact" onclick="closeMenu()">Contact</a></li>
            <li><a href="/login" class="nav-cta">Connexion →</a></li>
        </ul>
        <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
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
            <div data-animate='card' class="hero-badge" id="heroBadge">
                <span class="dot"></span>
                Plateforme SaaS Multi-École — 2025
            </div>

            <h1 data-animate='card' class="hero-title" id="heroTitle">
                <span class="line"><span>L'éducation</span></span>
                <span class="line"><span class="accent">réinventée,</span></span>
                <span class="line"><span>sans frontières.</span></span>
            </h1>

            <p data-animate='card' class="hero-sub" id="heroSub">
                EducConnect unifie <strong>directeurs, enseignants, parents et élèves</strong>
                dans un écosystème intelligent. Chaque école, son univers.
                Chaque acteur, son pouvoir.
            </p>

            <div class="role-buttons" id="roleButtons">
                <a data-animate='card' href="/login?role=director" class="role-btn btn-director">
                    <div class="icon">🏛️</div>
                    <div class="label">
                        <small>Espace</small>
                        Directeur
                    </div>
                </a>
                <a data-animate='card' href="/login?role=teacher" class="role-btn btn-teacher">
                    <div class="icon">📚</div>
                    <div class="label">
                        <small>Espace</small>
                        Enseignant
                    </div>
                </a>
                <a data-animate='card' href="/login?role=parent" class="role-btn btn-parent">
                    <div class="icon">👨‍👩‍👧</div>
                    <div class="label">
                        <small>Espace</small>
                        Parent d'élève
                    </div>
                </a>
                <a data-animate='card' href="/login?role=student" class="role-btn btn-student">
                    <div class="icon">🎓</div>
                    <div class="label">
                        <small>Espace</small>
                        Élève
                    </div>
                </a>
            </div>

            <div data-animate='card' class="hero-stats" id="heroStats">
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
                <div data-animate='card' class="section-tag" data>Fonctionnalités</div>
                <h2 data-animate='card' class="section-title">
                    Tout ce dont une<br>
                    <em style="font-style:italic;background:linear-gradient(135deg,var(--gold),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                        école moderne
                    </em><br>
                    a besoin.
                </h2>
                <p data-animate='card' class="section-sub">
                    De la gestion des inscriptions aux bulletins PDF, en passant par
                    le suivi des présences et les notifications WhatsApp —
                    EducConnect couvre l'intégralité du cycle scolaire.
                </p>

                <div class="feature-list">
                    <div data-animate='card' class="feature-item" id="f1">
                        <div class="feature-icon gold">📊</div>
                        <div class="feature-text">
                            <h4>Gestion multi-année intelligente</h4>
                            <p>Toutes les données archivées par année scolaire. Rien n'est jamais perdu. Naviguez dans le passé en un clic.</p>
                        </div>
                    </div>
                    <div data-animate='card' class="feature-item" id="f2">
                        <div class="feature-icon teal">📝</div>
                        <div class="feature-text">
                            <h4>Notes avec historique d'édition</h4>
                            <p>Chaque modification tracée, chaque éditeur identifié. La transparence comme fondement de la confiance.</p>
                        </div>
                    </div>
                    <div data-animate='card' class="feature-item" id="f3">
                        <div class="feature-icon lavender">📱</div>
                        <div class="feature-text">
                            <h4>Notifications SMS & WhatsApp</h4>
                            <p>Bulletins, absences, résultats — les parents reçoivent l'information instantanément, sur le canal qu'ils préfèrent.</p>
                        </div>
                    </div>
                    <div data-animate='card' class="feature-item" id="f4">
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
                    <div data-animate='card' class="mockup-bar">
                        <div class="mockup-dot" style="background:#ff5f57;"></div>
                        <div class="mockup-dot" style="background:#febc2e;"></div>
                        <div class="mockup-dot" style="background:#28c840;"></div>
                        <div style="flex:1;text-align:center;font-size:0.65rem;color:var(--muted);font-family:'Space Mono',monospace;">
                            ecole-excellence.educconnect.com
                        </div>
                    </div>
                    <div class="mockup-content">
                        <div data-animate='card' class="mockup-card">
                            <div class="mc-label">Élèves actifs</div>
                            <div class="mc-value mc-gold">847</div>
                        </div>
                        <div data-animate='card' class="mockup-card">
                            <div class="mc-label">Enseignants</div>
                            <div class="mc-value mc-teal">42</div>
                        </div>
                        <div data-animate='card' class="mockup-card">
                            <div class="mc-label">Classes</div>
                            <div class="mc-value mc-lavender">18</div>
                        </div>
                        <div data-animate='card' class="mockup-card">
                            <div class="mc-label">Taux présence</div>
                            <div class="mc-value mc-coral">94%</div>
                        </div>
                        <div data-animate='card' class="mockup-bar-chart">
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
            <div class="step-ztwen" id="step1-ztwen" data-animate='card'>
                <div class="step-number">01</div>
                <h3 class="step-title">Demande de création</h3>
                <p class="step-desc">Le directeur soumet une demande avec les informations de son établissement et choisit son abonnement.</p>
            </div>
            <div class="step-ztwen" id="step2-ztwen" data-animate='card'>
                <div class="step-number">02</div>
                <h3 class="step-title">Activation instantanée</h3>
                <p class="step-desc">L'administrateur valide. Un sous-domaine unique est créé. La base de données de l'école est initialisée.</p>
            </div>
            <div class="step-ztwen" id="step3-ztwen" data-animate='card'>
                <div class="step-number">03</div>
                <h3 class="step-title">Configuration de l'école</h3>
                <p class="step-desc">Classes, filières, matières, enseignants — tout est paramétrable en quelques clics.</p>
            </div>
            <div class="step-ztwen" id="step4-ztwen" data-animate='card'>
                <div class="step-number">04</div>
                <h3 class="step-title">Toute la communauté</h3>
                <p class="step-desc">Élèves inscrits, parents notifiés, enseignants invités. L'école vit, grandit, prospère.</p>
            </div>

        </div>
        @guest
            <div class="w-full flex justify-center items-center relative top-6">
                <a data-animate='card' href="{{ route('central.request.to.create.tenant') }}" class="cta-btn ">
                    ✦ Créer mon école maintenant
                </a>
            </div>
        @endguest
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
            <a data-animate='card' href="#" class="role-card-ztwen director" id="rc1">
                <span class="role-emoji">🏛️</span>
                <h3 class="role-name">Directeur</h3>
                <p class="role-desc">Pilotez votre établissement avec une vue d'ensemble totale. Gérez les classes, les enseignants, les inscriptions et les finances.</p>
                <span class="role-link">Accéder →</span>
            </a>
            <a data-animate='card' href="#" class="role-card-ztwen teacher" id="rc2">
                <span class="role-emoji">📚</span>
                <h3 class="role-name">Enseignant</h3>
                <p class="role-desc">Vos classes, vos matières, vos notes. Un espace dédié pour gérer vos élèves et communiquer avec les familles.</p>
                <span class="role-link">Accéder →</span>
            </a>
            <a data-animate='card' href="#" class="role-card-ztwen parent" id="rc3">
                <span class="role-emoji">👨‍👩‍👧</span>
                <h3 class="role-name">Parent d'élève</h3>
                <p class="role-desc">Suivez la scolarité de vos enfants en temps réel. Notes, présences, bulletins — tout en un seul endroit.</p>
                <span class="role-link">Accéder →</span>
            </a>
            <a data-animate='card' href="#" class="role-card-ztwen student" id="rc4">
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
            <h2 data-animate='card' class="cta-title" id="ctaTitle">
                Prêt à transformer<br>
                <em style="font-style:italic;background:linear-gradient(135deg,var(--gold),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                    votre école ?
                </em>
            </h2>
            <p data-animate='card' class="cta-sub" id="ctaSub">
                Rejoignez des centaines d'établissements qui font confiance à EducConnect.<br>
                Démarrez gratuitement, évoluez à votre rythme.
            </p>
            @guest
                <a data-animate='card' href="{{ route('central.request.to.create.tenant') }}" class="cta-btn" id="ctaBtn">
                    ✦ Créer mon école maintenant
                </a>
            @endguest
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="brand-name">EducConnect</div>
                <p>La plateforme SaaS qui connecte toute la communauté éducative dans un écosystème intelligent, sécurisé et multi-école.</p>
                <div style="margin-top:1.5rem;display:flex;gap:0.75rem;">
                    <a href="#"
                        style="width:36px;height:36px;background:var(--card-bg);border:1px solid var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--muted);text-decoration:none;transition:all 0.3s;font-size:0.9rem;"
                        onmouseover="this.style.borderColor='rgba(245,200,66,0.4)';this.style.color='var(--gold)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">✉</a>
                    <a href="#"
                        style="width:36px;height:36px;background:var(--card-bg);border:1px solid var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--muted);text-decoration:none;transition:all 0.3s;font-size:0.9rem;"
                        onmouseover="this.style.borderColor='rgba(0,201,167,0.4)';this.style.color='var(--teal)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">📱</a>
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
</body>

</html>

