export function registerTutorListeners(tenantId, userId) {
    window.Echo.private(`tenant.${tenantId}.tuteur.${userId}`).listen(
        "TutorWasBlockedEvent",
        (e) => {
            Livewire.dispatch("TutorWasBlockedLiveEvent");
            $wireui.notify({
                title: "COMPTE PARENT/TUTEUR BLOQUE",
                timeout: 0,
                description: "Votre compte a été bloqué",
                icon: mapTypeToIcon("warning"),
            });
        },
    );

    window.Echo.private(`tenant.${tenantId}.tuteur`)
        .listen("SchoolYearClosed", (e) => {
            $wireui.notify({
                title: "ANNEE SCOLAIRE FERMEE ",
                timeout: 0,
                description: "L'année scolaire en cours a été clôturée!",
                icon: mapTypeToIcon("info"),
            });
        })
        .listen("DataUpdatedEvent", (e) => {
            Livewire.dispatch("DataUpdatedEventLiveEvent");
        });
}

export function unregisterTutorListeners(tenantId, userId) {
    window.Echo.leave(`tenant.${tenantId}.tuteur`);
    window.Echo.leave(`tenant.${tenantId}.tuteur.${userId}`);
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
