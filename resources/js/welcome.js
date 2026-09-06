document.addEventListener("DOMContentLoaded", () => {
    // Utiliser window.Motion chargé par app.js (si disponible)
    const Motion = window.Motion || {};
    const { animate, stagger, inView, scroll } = Motion;

    // ── Compteur stats ──
    function animateCounter(el, target, suffix = "") {
        let start = 0;
        const duration = 2000;
        const step = (timestamp) => {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent =
                Math.floor(eased * target).toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }

    if (typeof inView === "function") {
        inView("#heroStats", () => {
            document.querySelectorAll(".stat-num").forEach((el) => {
                const target = parseInt(el.dataset.target, 10);
                const suffix =
                    el.dataset.target === "99"
                        ? "%"
                        : el.dataset.target === "50000"
                          ? "+"
                          : "+";
                animateCounter(el, target, suffix);
            });
        });

        inView("#dashboardMockup", () => {
            setTimeout(() => {
                document.querySelectorAll(".bar-fill").forEach((bar) => {
                    bar.style.transform = "scaleX(1)";
                });
            }, 400);
        });
    } else {
        const stats = document.querySelectorAll(".stat-num");
        if (stats.length) {
            stats.forEach((el) => {
                const target = parseInt(el.dataset.target, 10);
                const suffix =
                    el.dataset.target === "99"
                        ? "%"
                        : el.dataset.target === "50000"
                          ? "+"
                          : "+";
                animateCounter(el, target, suffix);
            });
        }
        document.querySelectorAll(".bar-fill").forEach((bar) => {
            bar.style.transform = "scaleX(1)";
        });
    }

    // ── Navbar scroll ──
    const navbar = document.getElementById("navbar");
    if (navbar) {
        window.addEventListener(
            "scroll",
            () => {
                if (window.scrollY > 50) {
                    navbar.style.background = "rgba(10,14,26,0.95)";
                } else {
                    navbar.style.background = "rgba(10,14,26,0.6)";
                }
            },
            { passive: true },
        );
    }

    // ── Menu mobile (sidebar) ──
    const nav = document.getElementById("navLinks");
    const burger = document.getElementById("hamburger");
    const overlay = document.getElementById("navOverlay");

    function openMenu() {
        if (!nav || !burger) return;
        nav.classList.add("open");
        burger.classList.add("open");
        burger.setAttribute("aria-expanded", "true");
        if (overlay) {
            overlay.classList.add("open");
            overlay.setAttribute("aria-hidden", "false");
        }
        document.body.style.overflow = "hidden";
    }

    function closeMenu() {
        if (!nav || !burger) return;
        nav.classList.remove("open");
        burger.classList.remove("open");
        burger.setAttribute("aria-expanded", "false");
        if (overlay) {
            overlay.classList.remove("open");
            overlay.setAttribute("aria-hidden", "true");
        }
        document.body.style.overflow = "";
    }

    function toggleMenu(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        if (!nav || !burger) return;
        if (nav.classList.contains("open")) {
            closeMenu();
        } else {
            openMenu();
        }
    }

    // Exposer globalement
    window.toggleMenu = toggleMenu;
    window.closeMenu = closeMenu;

    if (burger) {
        burger.setAttribute("aria-expanded", "false");
        burger.addEventListener("click", toggleMenu);
    }

    // Clic sur l'overlay → fermer
    if (overlay) {
        overlay.addEventListener("click", closeMenu);
    }

    // Fermer au clic sur un lien du menu
    if (nav) {
        nav.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", () => {
                closeMenu();
            });
        });
    }

    // Fermer avec Escape
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeMenu();
    });

    // Fermer si on repasse en desktop
    window.addEventListener(
        "resize",
        () => {
            if (window.innerWidth > 1024) {
                closeMenu();
            }
        },
        { passive: true },
    );
});
