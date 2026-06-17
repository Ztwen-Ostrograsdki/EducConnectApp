export function registerDirectorListeners(tenantId) {
    window.Echo.private(`tenant.${tenantId}.directeur`)

        // STUDENTS CREATIONS EVENTS
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
        })

        // TEACHERS CREATIONS EVENTS

        .listen("TeachersCreationTaskStartedEvent", (e) => {
            Livewire.dispatch("TeachersCreationsTasksStartedLiveEvent", {
                totalJobs: e.totalJobs,
                batchId: e.batchId,
            });
        })

        .listen("ATeacherCreationFailedEvent", (e) => {
            Livewire.dispatch("ATeacherCreationFailedLiveEvent", {
                teacherName: e.userName,
                error: e.error,
            });
        })
        .listen("TeacherCreatedEvent", (e) => {
            Livewire.dispatch("TeacherCreatedSucessfullyLiveEvent", {
                teacherName: e.userName,
                message: null,
            });
        })
        .listen("TeachersCreationStatusUpdatedEvent", (data) => {
            Livewire.dispatch("TeachersCreationProgressLiveEvent", data);
        })
        .listen("ProcessToCreateTeachersCompletedSuccesfullyEvent", (data) => {
            Livewire.dispatch("ProcessToCreateTeachersCompletedSuccesfullyLiveEvent", data);
        });
}

export function unregisterDirectorListeners(tenantId) {
    window.Echo.leave(`tenant.${tenantId}.directeur`);
}
