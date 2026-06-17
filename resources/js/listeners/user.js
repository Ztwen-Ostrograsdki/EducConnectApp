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
