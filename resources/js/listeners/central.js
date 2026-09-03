export function registerCentralListeners() {
    window.Echo.private("central-admin")
        .notification((notification) => {
            $wireui.notify({
                title: notification.title,
                timeout: 0,
                description: notification.message,
                icon: mapTypeToIcon(notification.type ?? "info"),
            });
        })
        .listen("SchoolRegistered", (e) => {
            window.dispatchEvent(
                new CustomEvent("central:school-registered", {
                    detail: { school: e.school, message: e.message },
                }),
            );
        })
        .listen("SchoolApproved", (e) => {
            window.dispatchEvent(
                new CustomEvent("central:school-approved", {
                    detail: { school: e.school },
                }),
            );
        })
        .listen("CentralDataUpdatedEvent", (e) => {
            Livewire.dispatch("CentralDataUpdatedLiveEvent");
        })
        .listen("SubscriptionRequestCreatedEvent", (e) => {
            $wireui.notify({
                title: "NOUVELLE DEMANDE D'ABONNEMENT",
                timeout: 0,
                description: "Vous avez réçu une nouvelle demande d'abonnement",
                icon: mapTypeToIcon("success"),
            });

            Livewire.dispatch("CentralDataUpdatedLiveEvent");
        });
}

export function unregisterCentralListeners() {
    window.Echo.leaveChannel("central-admin");
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
