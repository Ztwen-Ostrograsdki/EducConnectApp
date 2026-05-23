// resources/js/app.js
// ─────────────────────────────────────────────────────────────
// Entrée principale — Laravel + Livewire + Alpine.js + Motion
// ─────────────────────────────────────────────────────────────

import "./bootstrap";

import "./layouts/dashboards-layouts";

// Alpine.js (complément naturel de Livewire)
import Alpine from "alpinejs";
// window.Alpine = Alpine;
// Alpine.start();

// import { createIcons, icons } from "lucide";

// createIcons({ icons });

// Motion — animations déclaratives sur les éléments du DOM
import { animate, stagger, inView, scroll } from "motion";
window.Motion = { animate, stagger, inView, scroll };

document.addEventListener("DOMContentLoaded", () => {
    const animateCard = () => {
        const zCards = document.querySelectorAll("[data-z-card]");

        if (zCards.length) {
            animate(
                zCards,
                { opacity: [0, 1], y: [20, 0] },
                {
                    delay: stagger(0.1),
                    duration: 0.4,
                    easing: "ease-out",
                },
            );
        }
    };

    // Animation initiale
    animateCard();

    const cards = document.querySelectorAll("[data-animate='card']");

    if (cards.length) {
        animate(
            cards,
            { opacity: [0, 1], y: [20, 0] },
            {
                delay: stagger(0.1),
                duration: 0.4,
                easing: "ease-out",
            },
        );
    }

    // inView("[data-sidebar-item]", ({ target }) => {
    //     animate(
    //         target,
    //         {
    //             opacity: [0, 1],
    //             x: [-12, 0],
    //         },
    //         {
    //             duration: 0.3,
    //         },
    //     );
    // });

    const reveals = document.querySelectorAll("[data-animate='reveal']");

    if (reveals.length) {
        inView(reveals, ({ target }) => {
            animate(target, { opacity: [0, 1], y: [30, 0] }, { duration: 0.5 });
        });
    }
});

// ─── Hook Livewire : ré-animer après les mises à jour DOM ────
// document.addEventListener("livewire:navigated", () => {
//     animate("main", { opacity: [0, 1] }, { duration: 0.3 });
// });

document.addEventListener("livewire:navigated", () => {
    requestAnimationFrame(() => {
        const main = document.querySelector("main");

        if (main) {
            animate(main, { opacity: [0, 1] }, { duration: 0.3 });
        }
    });
});

// ─── Utilitaires Motion exportés globalement ─────────────────
// Usage dans les composants Blade / Alpine :
//   window.Motion.animate('#mon-element', { scale: [1, 1.05] })

// ─── Notifications toast animées ─────────────────────────────
window.showToast = (message, type = "success") => {
    const colors = {
        success: "#22c55e",
        error: "#ef4444",
        info: "#3b82f6",
    };

    const toast = document.createElement("div");
    toast.textContent = message;
    toast.style.cssText = `
    position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;
    background: ${colors[type]}; color: white;
    padding: 0.75rem 1.25rem; border-radius: 0.5rem;
    font-size: 0.875rem; font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
    opacity: 0;
  `;

    document.body.appendChild(toast);

    animate(toast, { opacity: [0, 1], y: [16, 0] }, { duration: 0.3 });

    setTimeout(() => {
        animate(toast, { opacity: [1, 0], y: [0, 16] }, { duration: 0.3 }).finished.then(() => toast.remove());
    }, 3500);
};

document.addEventListener("livewire:initialized", () => {
    const animateLoginCard = () => {
        const loginCard = document.querySelector("[data-login-card]");

        if (!loginCard) return;

        animate(
            loginCard,
            {
                opacity: [0, 1],
                y: [24, 0],
            },
            {
                duration: 0.4,
                easing: "ease-out",
            },
        );
    };

    // Animation initiale
    animateLoginCard();

    // Réanimation après navigation Livewire
    document.addEventListener("livewire:navigated", () => {
        requestAnimationFrame(() => {
            animateLoginCard();
        });

        window.dispatchEvent(new Event("wireui:load"));
    });
});

// Dans la console du navigateur
console.log(import.meta.env.VITE_REVERB_APP_KEY); // doit afficher "localkey"
console.log(import.meta.env.VITE_REVERB_HOST); // doit afficher "localhost"
console.log(import.meta.env.VITE_REVERB_PORT); // doit afficher "8080"
console.log(import.meta.env.VITE_REVERB_SCHEME); // doit afficher "http"
