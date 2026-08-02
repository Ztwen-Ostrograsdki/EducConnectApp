<?php

declare(strict_types=1);

use App\Livewire\Auth\LogoutComponent;
use App\Livewire\Auth\PasswordForgotPage;
use App\Livewire\Auth\PasswordUpdatePage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\Auth\TenantLogin;
use App\Livewire\Tenants\Classes\ClasseProfil;
use App\Livewire\Tenants\Classes\ClassesPortal;
use App\Livewire\Tenants\Classes\ClassesPrintableDocumentsPage;
use App\Livewire\Tenants\Classes\ClassesPrintableListComponent;
use App\Livewire\Tenants\Classes\ClassesPrintsManagerComponent;
use App\Livewire\Tenants\Classes\CreateClasseComponent;
use App\Livewire\Tenants\Classes\EditClasseComponent;
use App\Livewire\Tenants\Classes\ManageClasseYearlyResponsiblesComponent;
use App\Livewire\Tenants\Classes\ManageYearlyClasseSubjectsTeacherComponent;
use App\Livewire\Tenants\Classes\MarkRankingPrintableListComponent;
use App\Livewire\Tenants\Classes\MarkRankingPrintsManagerComponent;
use App\Livewire\Tenants\Classes\MarksRankingPrintableDocumentsPage;
use App\Livewire\Tenants\Classes\MigrateStudentsToClassesComponent;
use App\Livewire\Tenants\Filiars\CreateFiliarComponent;
use App\Livewire\Tenants\Filiars\FiliarProfil;
use App\Livewire\Tenants\Filiars\FiliarsPortal;
use App\Livewire\Tenants\Filiars\FiliarStudentsListComponent;
use App\Livewire\Tenants\Filiars\FiliarTeachersListComponent;
use App\Livewire\Tenants\Filiars\ManageFiliarChiefsComponent;
use App\Livewire\Tenants\Filiars\ManageFiliarComponent;
use App\Livewire\Tenants\HomePage;
use App\Livewire\Tenants\MyProfilPage;
use App\Livewire\Tenants\Parents\CreateTutors;
use App\Livewire\Tenants\Parents\ManageParentsStudentsRelationComponent;
use App\Livewire\Tenants\Parents\ParentProfil;
use App\Livewire\Tenants\Parents\ParentsPortal;
use App\Livewire\Tenants\Parents\TutorsCreationMonitorComponent;
use App\Livewire\Tenants\ProfilPhotoManagerByDirectorComponent;
use App\Livewire\Tenants\Promotions\CreatePromotionComponent;
use App\Livewire\Tenants\Promotions\ManagePromotionComponent;
use App\Livewire\Tenants\Promotions\PromotionProfil;
use App\Livewire\Tenants\Promotions\PromotionsPortal;
use App\Livewire\Tenants\Promotions\PromotionStudentsComponent;
use App\Livewire\Tenants\Promotions\PromotionTeachersComponent;
use App\Livewire\Tenants\Reports\MarksDiagnosticForTeachersPrintableDocumentsPage;
use App\Livewire\Tenants\Reports\MarksDiagnosticManagerComponent;
use App\Livewire\Tenants\Reports\MarksDiagnosticPrintableListComponent;
use App\Livewire\Tenants\Schoolyears\CreateSchoolYear;
use App\Livewire\Tenants\Schoolyears\ManageSchoolYearComponent;
use App\Livewire\Tenants\Schoolyears\SchoolYearProfil;
use App\Livewire\Tenants\Schoolyears\SchoolYearsPortal;
use App\Livewire\Tenants\Serials\CreateSerialComponent;
use App\Livewire\Tenants\Serials\ManageSerialComponent;
use App\Livewire\Tenants\Serials\SerialProfil;
use App\Livewire\Tenants\Serials\SerialsPortal;
use App\Livewire\Tenants\Serials\SerialStudentsListComponent;
use App\Livewire\Tenants\Serials\SerialTeachersListComponent;
use App\Livewire\Tenants\Stats\PeriodicalStatistiqueComponent;
use App\Livewire\Tenants\StudentDataManagerByDirectorComponent;
use App\Livewire\Tenants\Students\CreateStudents;
use App\Livewire\Tenants\Students\ManageStudentClassroomComponent;
use App\Livewire\Tenants\Students\ManageStudentParentsRelationComponent;
use App\Livewire\Tenants\Students\MarksPrintableDocumentsPage;
use App\Livewire\Tenants\Students\MarksPrintableListComponent;
use App\Livewire\Tenants\Students\MarksPrintsManagerComponent;
use App\Livewire\Tenants\Students\StudentMarksComponent;
use App\Livewire\Tenants\Students\StudentProfilPage;
use App\Livewire\Tenants\Students\StudentsCreationMonitorComponent;
use App\Livewire\Tenants\Students\StudentsPortal;
use App\Livewire\Tenants\Students\StudentsPrintableDocumentsPage;
use App\Livewire\Tenants\Students\StudentsPrintableListComponent;
use App\Livewire\Tenants\Students\StudentsPrintsManagerComponent;
use App\Livewire\Tenants\Subjects\CreateSubjectComponent;
use App\Livewire\Tenants\Subjects\ManagePromotionSpecialityCoefComponent;
use App\Livewire\Tenants\Subjects\ManageSubjectChiefsComponent;
use App\Livewire\Tenants\Subjects\ManageSubjectComponent;
use App\Livewire\Tenants\Subjects\SubjectProfil;
use App\Livewire\Tenants\Subjects\SubjectsPortal;
use App\Livewire\Tenants\Teachers\CreateTeachers;
use App\Livewire\Tenants\Teachers\ManageTeacherSubjectsComponent;
use App\Livewire\Tenants\Teachers\ManageTeacherYearlyClassesAssignmentComponent;
use App\Livewire\Tenants\Teachers\TeacherProfilPage;
use App\Livewire\Tenants\Teachers\TeachersCreationMonitorComponent;
use App\Livewire\Tenants\Teachers\TeachersPortal;
use App\Livewire\Tenants\Teachers\TeachersPrintableDocumentsPage;
use App\Livewire\Tenants\Teachers\TeachersPrintableListComponent;
use App\Livewire\Tenants\Teachers\TeachersPrintsManagerComponent;
use App\Livewire\Tenants\TenantDashboard;
use App\Livewire\Tenants\UpdateProfilePhoto;
use App\Livewire\Tenants\Users\NotificationsPage;
use App\Livewire\Tenants\Users\Parent\ParentDashboard;
use App\Livewire\Tenants\Users\Parent\ParentStudentsBulletinViewer;
use App\Livewire\Tenants\Users\Parent\ParentStudentsMarksViewer;
use App\Livewire\Tenants\Users\Teacher\TeacherClasseMarksManagerComponent;
use App\Livewire\Tenants\Users\Teacher\TeacherClasseMarksViewer;
use App\Livewire\Tenants\Users\Teacher\TeacherClasseStudentsViewer;
use App\Livewire\Tenants\Users\Teacher\TeacherDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
])->group(function () {

    Route::post('/broadcasting/auth', function () {
        return Broadcast::auth(request());
    });

    // ─── Auth ─────────────────────────────────────────────────────────
    Route::get('/login', TenantLogin::class)->name('login')->middleware('guest:tenant');

    Route::get('/mot-de-passe-oublie/{token?}/{email?}', PasswordForgotPage::class)->middleware('guest:tenant')->name('tenant.password.forgot');

    Route::get('/password-reset/{token?}/{email?}', ResetPasswordPage::class)->middleware('guest:tenant')->name('tenant.password.reset');

    Route::get('/', HomePage::class)->name('tenants.home');


    Route::get('/deconnexion-force', LogoutComponent::class)->name('tenant.logout.force')->middleware('web');



    Route::post('/logout', function () {

        Auth::guard('tenant')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route('login');
    })->name('logout')->middleware('auth:tenant');

    // ─── Pages authentifiées ──────────────────────────────────────────
    Route::middleware(['auth:tenant', 'tenant.domain.open', 'tenant.domain.not.deleted.at', 'logout.when.inactivity.too.long'])->group(function () {

        Route::get('/changer-mot-de-passe', PasswordUpdatePage::class)->name('tenant.update.password');

        // ── Directeur ─────────────────────────────────────────────────

        Route::middleware(['role:directeur'])->prefix('administration')->name('tenant.')->group(function () {
            
            // ANNEES SCOLAIRES
            Route::get('/annees-scolaires/portail', SchoolYearsPortal::class)->name('schoolyears.portal');

            Route::get('/annees-scolaires/details-annee-scolaire/{school_year}', SchoolYearProfil::class)->name('schoolyear.profil');

            Route::get('/annees-scolaires/creation-nouvelle-annee', CreateSchoolYear::class)->name('schoolYears.create');

            Route::get('/annees-scolaires/{school_year}/edition', ManageSchoolYearComponent::class)->name('schoolYears.edit');

        });

        Route::middleware(['role:directeur', 'tenant.has.active.schoolYear'])->prefix('administration')->name('tenant.')->group(function () {
            
            // TABLEAU DE BORD DIRECTEUR
            Route::get('/', TenantDashboard::class)->name('dashboard');

            // PROMOTIONS
            Route::get('/promotions', PromotionsPortal::class)->name('promotions.portal');

            Route::get('/promotions/details-promotion/{promotion_slug}', PromotionProfil::class)->name('promotion.profil');

            Route::get('/promotions/details-promotion/{promotion_slug}/les-apprenenats', PromotionStudentsComponent::class)->name('promotion.students');
            Route::get('/promotions/details-promotion/{promotion_slug}/les-enseignants', PromotionTeachersComponent::class)->name('promotion.teachers');


            Route::get('/promotions/{promotion_slug}/edition', ManagePromotionComponent::class)->name('promotion.edit');

            Route::get('/promotions/nouvelle-promotion', CreatePromotionComponent::class)->name('promotion.create');

            // LES MATIERES
            Route::get('/matieres/portail-des-matieres', SubjectsPortal::class)->name('subjects.portal');

            Route::get('/matieres/details-matiere/{subject_slug}', SubjectProfil::class)->name('subject.profil');

            Route::get('/matieres/nouvelle-matiere', CreateSubjectComponent::class)->name('subject.create');
            Route::get('/matieres/{subject_slug}/edition', ManageSubjectComponent::class)->name('subject.edit');
            Route::get('/matieres/{subject_slug}/AE/edition', ManageSubjectChiefsComponent::class)->name('subject.edit.ae');

            Route::get('/matieres/gestion-des-coefiscients/{subject_slug?}/{uuid?}', ManagePromotionSpecialityCoefComponent::class)->name('subjects.coefs.manage');


            // FILIRES
            Route::get('/filiars/portail-des-filiars', FiliarsPortal::class)->name('filiars.portal');

            Route::get('/filieres/details-filiere/{filiar_slug}', FiliarProfil::class)->name('filiar.profil');
            
            Route::get('/filieres/details-filiere/{filiar_slug}/les-apprenenats', FiliarStudentsListComponent::class)->name('filiar.students');
            Route::get('/filieres/details-filiere/{filiar_slug}/les-enseignants', FiliarTeachersListComponent::class)->name('filiar.teachers');

            Route::get('/filieres/nouvelle-filiere', CreateFiliarComponent::class)->name('filiar.create');
            Route::get('/filieres/{filiar_slug}/edition', ManageFiliarComponent::class)->name('filiar.edit');
            Route::get('/filieres/{filiar_slug}/AE/edition', ManageFiliarChiefsComponent::class)->name('filiar.edit.ca');
            
            // SERIES
            Route::get('/series/portail-des-series', SerialsPortal::class)->name('serials.portal');

            Route::get('/series/details-serie/{serial_slug}', SerialProfil::class)->name('serial.profil');

            Route::get('/series/details-serie/{serial_slug}/les-apprenenats', SerialStudentsListComponent::class)->name('serial.students');
            Route::get('/series/details-serie/{serial_slug}/les-enseignants', SerialTeachersListComponent::class)->name('serial.teachers');


            Route::get('/series/nouvelle-serie', CreateSerialComponent::class)->name('serial.create');

            Route::get('/series/{serial_slug}/edition', ManageSerialComponent::class)->name('serial.edit');



            // LES CLASSES
            Route::get('/classes/portail-classses', ClassesPortal::class)->name('classes.portal');

            Route::get('/classes/details-classe/{classe_slug}', ClasseProfil::class)->name('classe.profil');

            Route::get('/classes/nouvelle-classe', CreateClasseComponent::class)->name('classes.create');

            Route::get('/classes/{classe_slug}/edition', EditClasseComponent::class)->name('classe.edit');

            Route::get('/classes/migration-des-apprenants/{classe_slug?}', MigrateStudentsToClassesComponent::class)->name('classe.migrate.students');

            Route::get('/classes/{classe_slug}/gestion-pp-responsables', ManageClasseYearlyResponsiblesComponent::class)->name('classe.respos');

            Route::get('/classes/{classe_slug}/gestion-enseignant-par-matiere', ManageYearlyClasseSubjectsTeacherComponent::class)->name('classe.manage.subjects.teacher');

            Route::get('/classes/impression', ClassesPrintableListComponent::class)->name('classes.print.list');

            Route::get('/classes/gestion-impression/configuration', ClassesPrintsManagerComponent::class)->name('classes.print.configuration');

            Route::get('/classes/documents/imprimable/{filiar_slug?}', ClassesPrintableDocumentsPage::class)->name('classes.docs');

            //NOTES
            Route::get('/notes/gestion-impression/configuration/{classe_slug?}', MarksPrintsManagerComponent::class)->name('notes.print.configuration');

            Route::get('/notes/impression/previsualisation', MarksPrintableListComponent::class)->name('notes.print.preview');

            Route::get('/notes/documents/imprimable/{classe_slug?}', MarksPrintableDocumentsPage::class)->name('notes.docs');


            //LES MEILLEURS - FAIBLES APPRENANTS
            Route::get('/apprenants-remarquables/gestion-impression/configuration/{classe_slug?}', MarkRankingPrintsManagerComponent::class)->name('students.bests.weaks.print.configuration');

            Route::get('/apprenants-remarquables/impression/previsualisation', MarkRankingPrintableListComponent::class)->name('students.bests.weaks.print.preview');

            Route::get('/apprenants-remarquables/documents/imprimable/{classe_slug?}', MarksRankingPrintableDocumentsPage::class)->name('students.bests.weaks.docs');


            //RAPPORTS NOTES EFFECTUEES PAR ENSEIGNANTS
            Route::get('/rapport-notes/gestion-impression/configuration/{classe_slug?}', MarksDiagnosticManagerComponent::class)->name('marks.reports.print.configuration');

            Route::get('/rapport-notes/impression/previsualisation', MarksDiagnosticPrintableListComponent::class)->name('marks.reports.print.preview');

            Route::get('/rapport-notes/documents/imprimable/{classe_slug?}', MarksDiagnosticForTeachersPrintableDocumentsPage::class)->name('marks.reports.docs');


            // LES ENSEIGNANTS
            Route::get('/enseignants/portail-enseignants', TeachersPortal::class)->name('teachers.portal');

            Route::get('/enseignants/ajout', CreateTeachers::class)->name('teachers.create');

            Route::get('/enseignants/status-des-ajouts', TeachersCreationMonitorComponent::class)->name('teachers.crud.tasks');

            Route::get('/details/enseignant/profil/{teacher_uuid}', TeacherProfilPage::class)->name('teacher.profil');

            Route::get('/enseignants/impression', TeachersPrintableListComponent::class)->name('teachers.print.list');
            
            Route::get('/enseignants/gestion-des-matieres/{teacher_uuid?}', ManageTeacherSubjectsComponent::class)->name('teacher.manage.subjects');

            Route::get('/enseignants/{teacher_uuid}/gestion-classes', ManageTeacherYearlyClassesAssignmentComponent::class)->name('teacher.manage.classes');

            Route::get('/enseignants/gestion-impression/configuration/{classe_slug?}', TeachersPrintsManagerComponent::class)->name('teachers.print.configuration');

            Route::get('/enseignants/documents/imprimable/{classe_slug?}', TeachersPrintableDocumentsPage::class)->name('teachers.docs');




            // LES ELEVES
            Route::get('/apprenants/portail-apprenants', StudentsPortal::class)->name('students.portal');

            Route::get('/apprenants/ajout', CreateStudents::class)->name('students.create');

            Route::get('/apprenants/status-des-ajouts', StudentsCreationMonitorComponent::class)->name('students.crud.tasks');

            Route::get('/apprenants/vue-page-impression', StudentsPrintableListComponent::class)->name('students.print.list');

            Route::get('/apprenants/documents/imprimable/{classe_slug?}', StudentsPrintableDocumentsPage::class)->name('students.docs');

            Route::get('/apprenants/gestion-impression/configuration/{classe_slug?}', StudentsPrintsManagerComponent::class)->name('students.print.configuration');

            Route::get('/apprenant/gestion-de-classe-actuelle/{student_uuid}', ManageStudentClassroomComponent::class)->name('student.manage.classe');

            Route::get('/apprenant/profil/{student_uuid}', StudentProfilPage::class)->name('student.profil');

            Route::get('/apprenant/editions-des-relations-parents-apprenants/{student_uuid}', ManageStudentParentsRelationComponent::class)->name('student.manage.relations');

            




            // LES PARENTS
            Route::get('/parents-tuteurs/portail-parents-des-apprenants', ParentsPortal::class)->name('parents.portal');

            Route::get('/parents-tuteurs/ajout', CreateTutors::class)->name('parents.create');

            Route::get('/parents-tuteurs/status-des-ajouts', TutorsCreationMonitorComponent::class)->name('parents.crud.tasks');

            Route::get('/parents-tuteurs/editions-des-relations-parents-apprenants/{parent_uuid}', ManageParentsStudentsRelationComponent::class)->name('parents.manage.relations');

            Route::get("/statistiques-semestrielles", PeriodicalStatistiqueComponent::class)->name('stats.general');

            Route::get('/details/parent-des-apprenants/profil/{parent_uuid}', ParentProfil::class)->name('parent.profil');

            Route::get('/details/apprenant/les-notes/{student_uuid}', StudentMarksComponent::class)->name('student.marks');


            Route::get('/mise-a-jour-photo-de-profil-utilisateur/{target}/{modelUuid}', ProfilPhotoManagerByDirectorComponent::class)->name('director.manage.profil.photo');
            
            Route::get('/mise-a-jour-informations/apprenant/{studentUuid}', StudentDataManagerByDirectorComponent::class)->name('director.manage.student.data');

        });

        Route::get('/centre-de-notifications', NotificationsPage::class)->name('tenant.notifications.center');

        


        Route::middleware(['tenant.domain.open.for.others.too', 'user.not.blocked'])->group(function(){

            Route::get('/mon-profil', MyProfilPage::class)->name('tenant.my.profil');

            Route::get('/mon-profil/editer-photo-profil', UpdateProfilePhoto::class)->name('tenant.update.profil.photo');

            //ESPACE ENSEIGNANT
            Route::middleware(['role:enseignant', 'teacher.not.blocked', 'has.valid.access'])->name('tenant.teacher.')->group(function () {
                
                Route::get('/mon-espace-enseignant', TeacherDashboard::class)->name('my.dashboard');
                
                Route::get('/mon-espace-enseignant/{classe_slug}/{subject_slug}/les-notes', TeacherClasseMarksViewer::class)->name('classe.marks');
                
                Route::get('/mon-espace-enseignant/{classe_slug}/{subject_slug}/insertion-notes', TeacherClasseMarksManagerComponent::class)->name('classe.marks.manager')->middleware('tenant.classe.is.active.and.not.locked');
                
                Route::get('/mon-espace-enseignant/{classe_slug}/{subject_slug}/liste-apprenants', TeacherClasseStudentsViewer::class)->name('classe.students');
                
            });

            // ── Enseignant ────────────────────────────────────────────────
            Route::middleware('role:enseignant|directeur')->prefix('teacher')->name('teacher.')->group(function () {
                // sera rempli au fur et à mesure
            });

            // ── Tuteur ────────────────────────────────────────────────────
            Route::middleware('role:tuteur')->group(function () {
                // sera rempli au fur et à mesure

                Route::get('/mon-espace-parent', ParentDashboard::class)->name('tenant.parent.space');

                Route::get('/mon-espace-parent/{student_uuid}/notes-enfant', ParentStudentsMarksViewer::class)->name('tenant.parent.space.marks');
                Route::get('/mon-espace-parent/{student_uuid}/bulletin-de-notes-enfant', ParentStudentsBulletinViewer::class)->name('tenant.parent.space.bulletin');
            });

            // ── Élève ─────────────────────────────────────────────────────
            Route::middleware('role:eleve')->prefix('student')->name('student.')->group(function () {
                // sera rempli au fur et à mesure
            }); 

        });
        
    });

});
