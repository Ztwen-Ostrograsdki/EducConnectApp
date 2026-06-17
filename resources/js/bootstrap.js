import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import "./echo";
import { registerCentralListeners } from "./listeners/central";
import { registerDirectorListeners } from "./listeners/director";
import { registerUserListeners } from "./listeners/user";

const { tenantId, userId, role } = window.__APP_CONTEXT__ ?? {};

// Toujours actif (super-admin ou pages centrales)
registerCentralListeners();

if (tenantId) {
    // Actif pour tous les users du tenant
    registerUserListeners(tenantId, userId);

    // Actif uniquement pour le directeur
    if (role === "directeur") {
        registerDirectorListeners(tenantId);
    }
}
