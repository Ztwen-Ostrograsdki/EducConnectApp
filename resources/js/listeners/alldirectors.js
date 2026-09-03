export function registerAllDirectorsListeners() {
    window.Echo.private("tenant.directeurs").listen(
        "TenantDirectorDataUpdatedEvent",
        (e) => {
            Livewire.dispatch("TenantDirectorDataUpdatedEventLiveEvent");
        },
    );
}
