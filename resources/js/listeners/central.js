export function registerCentralListeners() {
    window.Echo.channel("central-admin")
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
        });
}

export function unregisterCentralListeners() {
    window.Echo.leaveChannel("central-admin");
}
