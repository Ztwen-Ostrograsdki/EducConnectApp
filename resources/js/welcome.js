document.addEventListener("DOMContentLoaded", () => {
    // Utiliser window.Motion chargé par app.js
    const { animate, stagger, inView, scroll } = window.Motion;

    // ── Compteur stats ──
    function animateCounter(el, target, suffix = "") {
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

    inView("#heroStats", () => {
        document.querySelectorAll(".stat-num").forEach((el) => {
            const target = parseInt(el.dataset.target);
            const suffix = el.dataset.target === "99" ? "%" : el.dataset.target === "50000" ? "+" : "+";
            animateCounter(el, target, suffix);
        });
    });

    // ── Features animations ──

    inView("#dashboardMockup", ({ target }) => {
        // Animer les barres
        setTimeout(() => {
            document.querySelectorAll(".bar-fill").forEach((bar, i) => {
                bar.style.transform = "scaleX(1)";
            });
        }, 400);
    });

    // ── Navbar scroll ──
    const navbar = document.getElementById("navbar");
    window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
            navbar.style.background = "rgba(10,14,26,0.95)";
        } else {
            navbar.style.background = "rgba(10,14,26,0.6)";
        }
    }); // fin scroll listener
}); // fin DOMContentLoaded

// ── Hamburger menu ──
function toggleMenu() {
    const nav = document.getElementById("navLinks");
    const burger = document.getElementById("hamburger");
    nav.classList.toggle("open");
    burger.classList.toggle("open");
    document.body.style.overflow = nav.classList.contains("open") ? "hidden" : "";
}

function closeMenu() {
    const nav = document.getElementById("navLinks");
    const burger = document.getElementById("hamburger");
    nav.classList.remove("open");
    burger.classList.remove("open");
    document.body.style.overflow = "";
}

// Fermer le menu au clic en dehors
document.addEventListener("click", (e) => {
    const nav = document.getElementById("navLinks");
    const burger = document.getElementById("hamburger");
    if (nav.classList.contains("open") && !nav.contains(e.target) && !burger.contains(e.target)) {
        closeMenu();
    }
});
