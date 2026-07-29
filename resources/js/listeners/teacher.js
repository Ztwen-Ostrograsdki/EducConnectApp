export function registerTeacherListeners(tenantId, userId) {
    window.Echo.private(`tenant.${tenantId}.enseignant.${userId}`).listen(
        "TeacherWasBlockedEvent",
        (e) => {
            Livewire.dispatch("TeacherWasBlockedLiveEvent");
            $wireui.notify({
                title: "COMPTE ENSEIGNANT BLOQUE",
                timeout: 0,
                description: "Votre compte a été bloqué",
                icon: mapTypeToIcon("warning"),
            });
        },
    );

    window.Echo.private(`tenant.${tenantId}.enseignant`)
        .listen("SchoolYearClosed", (e) => {
            $wireui.notify({
                title: "ANNEE SCOLAIRE FERME ",
                timeout: 0,
                description: "L'année scolaire en cours a été clôturée!",
                icon: mapTypeToIcon("info"),
            });
        })
        .listen("DataUpdatedEvent", (e) => {
            Livewire.dispatch("DataUpdatedEventLiveEvent");
        });
}

export function unregisterTeacherListeners(tenantId, userId) {
    window.Echo.leave(`tenant.${tenantId}.enseignant`);
    window.Echo.leave(`tenant.${tenantId}.enseignant.${userId}`);
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
