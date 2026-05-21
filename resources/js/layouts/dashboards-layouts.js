const sidebar = document.getElementById("sidebar");
const main = document.getElementById("main");
const overlay = document.getElementById("overlay");

let collapsed = localStorage.getItem("sidebar-collapsed") === "1";

/* ─────────────────────────────────────────
   APPLY COLLAPSE (DESKTOP UNIQUEMENT)
───────────────────────────────────────── */
function applyCollapse() {
    // Ne jamais collapse sur mobile
    if (window.innerWidth < 1024) {
        sidebar.classList.remove("collapsed");
        main.classList.remove("collapsed");
        return;
    }

    sidebar.classList.toggle("collapsed", collapsed);
    main.classList.toggle("collapsed", collapsed);

    const icon = document.getElementById("collapse-icon");

    if (icon) {
        icon.style.transform = collapsed ? "rotate(180deg)" : "rotate(0deg)";
    }
}

/* ─────────────────────────────────────────
   TOGGLE DESKTOP SIDEBAR
───────────────────────────────────────── */
window.toggleCollapse = function () {
    collapsed = !collapsed;

    localStorage.setItem("sidebar-collapsed", collapsed ? "1" : "0");

    applyCollapse();
};

/* ─────────────────────────────────────────
   MOBILE SIDEBAR
───────────────────────────────────────── */
window.openSidebar = function () {
    sidebar.classList.add("open");
    overlay.classList.add("open");

    document.body.style.overflow = "hidden";
};

window.closeSidebar = function () {
    sidebar.classList.remove("open");
    overlay.classList.remove("open");

    document.body.style.overflow = "";
};

/* ─────────────────────────────────────────
   ACCORDIONS
───────────────────────────────────────── */
window.toggleAcc = function (id) {
    document.getElementById(id)?.classList.toggle("open");
};

/* ─────────────────────────────────────────
   DROPDOWNS
───────────────────────────────────────── */
window.toggleDD = function (id) {
    document.querySelectorAll(".dd-menu").forEach((menu) => {
        if (menu.id !== id) {
            menu.classList.remove("open");
        }
    });

    document.getElementById(id)?.classList.toggle("open");
};

/* ─────────────────────────────────────────
   EVENTS
───────────────────────────────────────── */

document.addEventListener("click", (e) => {
    if (!e.target.closest(".dd")) {
        document.querySelectorAll(".dd-menu").forEach((menu) => menu.classList.remove("open"));
    }
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        closeSidebar();

        document.querySelectorAll(".dd-menu").forEach((menu) => menu.classList.remove("open"));
    }
});

/* ─────────────────────────────────────────
   RESIZE FIX
───────────────────────────────────────── */
window.addEventListener("resize", () => {
    applyCollapse();

    // Reset mobile state quand on revient desktop
    if (window.innerWidth >= 1024) {
        sidebar.classList.remove("open");
        overlay.classList.remove("open");

        document.body.style.overflow = "";
    }
});

/* ─────────────────────────────────────────
   INIT
───────────────────────────────────────── */
document.addEventListener("DOMContentLoaded", () => {
    applyCollapse();

    if (window.Motion) {
        window.Motion.animate(
            "#content",
            {
                opacity: [0, 1],
                y: [10, 0],
            },
            {
                duration: 0.35,
            },
        );
    }
});
