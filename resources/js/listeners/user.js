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
        .listen("DataUpdatedEvent", (e) => {
            Livewire.dispatch("DataUpdatedEventLiveEvent");
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
}

export function unregisterUserListeners(tenantId, userId) {
    window.Echo.leave(`tenant.${tenantId}.user.${userId}`);
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
