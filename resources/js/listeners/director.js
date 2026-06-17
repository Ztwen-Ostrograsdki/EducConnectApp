export function registerDirectorListeners(tenantId) {
    window.Echo.private(`tenant.${tenantId}.directeur`)

        .listen("StudentsCreationTaskStartedEvent", (e) => {
            Livewire.dispatch("StudentsCreationsTasksStartedLiveEvent", {
                totalJobs: e.totalJobs,
                batchId: e.batchId,
            });
        })
        .listen("AStudentCreationFailedEvent", (e) => {
            Livewire.dispatch("AStudentCreationFailedLiveEvent", {
                studentName: e.userName,
                error: e.error,
            });
        })
        .listen("StudentCreatedEvent", (e) => {
            Livewire.dispatch("StudentCreatedSucessfullyLiveEvent", {
                studentName: e.userName,
                message: null,
            });
        })
        .listen("StudentsCreationStatusUpdatedEvent", (data) => {
            Livewire.dispatch("StudentsCreationProgressLiveEvent", data);
        })
        .listen("ProcessToCreateStudentsCompletedSuccesfullyEvent", (data) => {
            Livewire.dispatch("ProcessToCreateStudentsCompletedSuccesfullyLiveEvent", data);
        });
}

export function unregisterDirectorListeners(tenantId) {
    window.Echo.leave(`tenant.${tenantId}.directeur`);
}
