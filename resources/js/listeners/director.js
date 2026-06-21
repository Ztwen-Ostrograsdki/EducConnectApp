export function registerDirectorListeners(tenantId) {
    window.Echo.private(`tenant.${tenantId}.directeur`)

        // NOTIFICATIONS
        .notification((notification) => {
            $wireui.notify({
                title: notification.title,
                timeout: 0,
                description: notification.message,
                icon: mapTypeToIcon(notification.type ?? "info"),
            });

            if (notification.name && notification.name === "make.pdf") {
                Livewire.dispatch(`${notification.eventName}`);
            }
        })

        // SCHOOL YEAR EVENTS
        .listen("NewSchoolYearCreated", (e) => {
            $wireui.notify({
                title: "Nouvelle année scolaire créée",
                timeout: 0,
                description:
                    "L'année scolaire " +
                    e.school_year +
                    " a été créée avec succès!",
                icon: "info",
            });

            Livewire.dispatch("NewSchoolYearCreatedLiveEvent");
        })
        .listen("NewSchoolYearActivatedEvent", (e) => {
            $wireui.notify({
                title: "Nouvelle année scolaire active",
                timeout: 0,
                description:
                    "L'année scolaire " + e.school_year + " a été activée!",
                icon: "info",
            });

            Livewire.dispatch("NewSchoolYearCreatedLiveEvent");

            Livewire.dispatch("NewSchoolYearActivatedLiveEvent");
        })

        .listen("SchoolYearUpdatedEvent", (e) => {
            Livewire.dispatch("SchoolYearUpdatedLiveEvent");
        })

        // ERRORS EVENTS
        .listen("AnyErrorEvent", (e) => {
            $wireui.notify({
                title: "Une erreur s'est produite",
                timeout: 0,
                description: e.error,
                icon: mapTypeToIcon(e.target ?? "info"),
            });
        })

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
            Livewire.dispatch(
                "ProcessToCreateStudentsCompletedSuccesfullyLiveEvent",
                data,
            );
        })

        .listen("StudentDataUpdatedEvent", (data) => {
            Livewire.dispatch("StudentDataUpdatedEventLiveEvent", data);
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
            Livewire.dispatch(
                "ProcessToCreateTeachersCompletedSuccesfullyLiveEvent",
                data,
            );
        });
}

export function unregisterDirectorListeners(tenantId) {
    window.Echo.leave(`tenant.${tenantId}.directeur`);
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
