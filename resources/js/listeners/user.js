export function registerUserListeners(tenantId, userId) {
    window.Echo.private(`tenant.${tenantId}.user.${userId}`)
        .notification((notification) => {
            $wireui.notify({
                title: notification.title,
                timeout: 0,
                description: notification.message,
                icon: mapTypeToIcon(notification.type ?? "info"),
            });
        })

        .listen("UserAccountWasBlockedEvent", (e) => {
            Livewire.dispatch("UserAccountWasBlockedLiveEvent");

            $wireui.notify({
                title: "COMPTE UTILISATEUR BLOQUE",
                timeout: 0,
                description: "Votre compte a été bloqué",
                icon: mapTypeToIcon("error"),
            });

            window.location.href = "/deconnexion-force";
        })
        .listen("UserAccessWasRevokedEvent", (e) => {
            $wireui.notify({
                title: "ACCES UTILISATEUR REVOQUE",
                timeout: 0,
                description:
                    "Votre accès de cette année scolaire a été revoqué",
                icon: mapTypeToIcon("error"),
            });

            window.location.href = "/deconnexion-force";
        })
        .listen("UserTaskAssigned", (e) => {
            window.dispatchEvent(
                new CustomEvent("user:task-assigned", {
                    detail: { task: e.task },
                }),
            );
        });

    window.Echo.private(`tenant.${tenantId}.others`)
        .listen("TenantSpaceRestrictedOnlyForDirectorEvent", (e) => {
            $wireui.notify({
                title: "ESPACE RESTREINT",
                timeout: 0,
                description: "L'espace du domaine a été restreint",
                icon: mapTypeToIcon("error"),
            });

            window.location.href = "/deconnexion-force";
        })
        .listen("SchoolYearDesactivatedEvent", (e) => {
            window.location.href = "/deconnexion-force";
        });

    window.Echo.private(`tenant.${tenantId}`).listen(
        "TenantSpaceWasBlockedEvent",
        (e) => {
            $wireui.notify({
                title: "ESPACE ECOLE BLOQUE",
                timeout: 0,
                description: "L'accès à l'espace de cette école a été bloqué",
                icon: mapTypeToIcon("error"),
            });

            window.location.href = "/deconnexion-force";
        },
    );
}

export function unregisterUserListeners(tenantId, userId) {
    window.Echo.leave(`tenant.${tenantId}.user.${userId}`);
    window.Echo.leave(`tenant.${tenantId}.others`);
    window.Echo.leave(`tenant.${tenantId}`);
}

/**
 * Mappe ton type custom vers les icônes WireUI.
 * @param {'info'|'success'|'warning'|'error'} type
 * @returns {string}
 */
function mapTypeToIcon(type) {
    const icons = {
        info: "information-circle",
        success: "check-circle",
        warning: "exclamation-circle",
        error: "x-circle",
    };
    return icons[type] ?? "information-circle";
}
