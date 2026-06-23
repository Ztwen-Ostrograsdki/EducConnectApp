<?php

declare(strict_types=1);

use App\Livewire\Auth\PasswordForgotPage;
use App\Livewire\Auth\PasswordUpdatePage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\Auth\TenantLogin;
use App\Livewire\Tenants\Classes\ClasseProfil;
use App\Livewire\Tenants\Classes\ClassesPortal;
use App\Livewire\Tenants\Filiars\CreateFiliarComponent;
use App\Livewire\Tenants\Filiars\FiliarProfil;
use App\Livewire\Tenants\Filiars\FiliarsPortal;
use App\Livewire\Tenants\Filiars\ManageFiliarComponent;
use App\Livewire\Tenants\HomePage;
use App\Livewire\Tenants\MyProfilPage;
use App\Livewire\Tenants\Parents\ParentProfil;
use App\Livewire\Tenants\Parents\ParentsPortal;
use App\Livewire\Tenants\ProfilPhotoManagerByDirectorComponent;
use App\Livewire\Tenants\Promotions\CreatePromotionComponent;
use App\Livewire\Tenants\Promotions\ManagePromotionComponent;
use App\Livewire\Tenants\Promotions\PromotionProfil;
use App\Livewire\Tenants\Promotions\PromotionsPortal;
use App\Livewire\Tenants\Schoolyears\CreateSchoolYear;
use App\Livewire\Tenants\Schoolyears\ManageSchoolYearComponent;
use App\Livewire\Tenants\Schoolyears\SchoolYearProfil;
use App\Livewire\Tenants\Schoolyears\SchoolYearsPortal;
use App\Livewire\Tenants\Serials\CreateSerialComponent;
use App\Livewire\Tenants\Serials\ManageSerialComponent;
use App\Livewire\Tenants\Serials\SerialProfil;
use App\Livewire\Tenants\Serials\SerialsPortal;
use App\Livewire\Tenants\Stats\PeriodicalStatistiqueComponent;
use App\Livewire\Tenants\StudentDataManagerByDirectorComponent;
use App\Livewire\Tenants\Students\CreateStudents;
use App\Livewire\Tenants\Students\StudentMarksComponent;
use App\Livewire\Tenants\Students\StudentProfilPage;
use App\Livewire\Tenants\Students\StudentsCreationMonitorComponent;
use App\Livewire\Tenants\Students\StudentsPortal;
use App\Livewire\Tenants\Students\StudentsPrintableListComponent;
use App\Livewire\Tenants\Subjects\CreateSubjectComponent;
use App\Livewire\Tenants\Subjects\ManageSubjectComponent;
use App\Livewire\Tenants\Subjects\SubjectProfil;
use App\Livewire\Tenants\Subjects\SubjectsPortal;
use App\Livewire\Tenants\Teachers\CreateTeachers;
use App\Livewire\Tenants\Teachers\PrintableListComponent;
use App\Livewire\Tenants\Teachers\TeacherProfilPage;
use App\Livewire\Tenants\Teachers\TeachersCreationMonitorComponent;
use App\Livewire\Tenants\Teachers\TeachersPortal;
use App\Livewire\Tenants\TenantDashboard;
use App\Livewire\Tenants\UpdateProfilePhoto;
use App\Livewire\Tenants\Users\NotificationsPage;
use App\Livewire\Tenants\Users\Parent\ParentDashboard;
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

    Route::get('/password-reset/{?token}/{?email}', ResetPasswordPage::class)->middleware('guest:tenant')->name('tenant.password.reset');

    Route::get('/', HomePage::class)->name('tenants.home');

    Route::post('/logout', function () {

        Auth::guard('tenant')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route('login');
    })->name('logout')->middleware('auth:tenant');

    // ─── Pages authentifiées ──────────────────────────────────────────
    Route::middleware(['auth:tenant', 'tenant.domain.open', 'tenant.domain.not.deleted.at'])->group(function () {

        Route::get('/changer-mot-de-passe', PasswordUpdatePage::class)->name('tenant.update.password');

        // ── Directeur ─────────────────────────────────────────────────
        Route::middleware('role:directeur')->prefix('administration')->name('tenant.')->group(function () {
            Route::get('/', TenantDashboard::class)->name('dashboard');


            // ANNEES SCOLAIRES
            Route::get('/annees-scolaires/portail', SchoolYearsPortal::class)->name('schoolyears.portal');

            Route::get('/annees-scolaires/details-annee-scolaire/{school_year}', SchoolYearProfil::class)->name('schoolyear.profil');


            Route::get('/annees-scolaires/creation-nouvelle-annee', CreateSchoolYear::class)->name('schoolYears.create');

            Route::get('/annees-scolaires/{school_year}/edition', ManageSchoolYearComponent::class)->name('schoolYears.edit');

            // PROMOTIONS
            Route::get('/promotions/promotions', PromotionsPortal::class)->name('promotions.portal');

            Route::get('/promotions/promotions/profil-promotion/{promotion_slug}', PromotionProfil::class)->name('promotion.profil');

            Route::get('/promotions/promotions/{promotion_slug}/edition', ManagePromotionComponent::class)->name('promotion.edit');

            Route::get('/promotions/promotions/nouvelle-promotion', CreatePromotionComponent::class)->name('promotion.create');

            // LES MATIERES
            Route::get('/matieres/portail-des-matieres', SubjectsPortal::class)->name('subjects.portal');

            Route::get('/matieres/matieres/profil-matiere/{subject_slug}', SubjectProfil::class)->name('subject.profil');

            Route::get('/matieres/matieres/nouvelle-matiere', CreateSubjectComponent::class)->name('subject.create');
            Route::get('/matieres/matieres/{subject_slug}/edition', ManageSubjectComponent::class)->name('subject.edit');


            // FILIRES
            Route::get('/filiars/portail-des-filiars', FiliarsPortal::class)->name('filiars.portal');

            Route::get('/filieres/filieres/profil-filiere/{filiar_slug}', FiliarProfil::class)->name('filiar.profil');

            Route::get('/filieres/nouvelle-matiere', CreateFiliarComponent::class)->name('filiar.create');
            Route::get('/filieres/{filiar_slug}/edition', ManageFiliarComponent::class)->name('filiar.edit');

            // SERIES
            Route::get('/series/portail-des-series', SerialsPortal::class)->name('serials.portal');

            Route::get('/series/profil-serie/{serial_slug}', SerialProfil::class)->name('serial.profil');

            Route::get('/series/nouvelle-serie', CreateSerialComponent::class)->name('serial.create');

            Route::get('/series/{serial_slug}/edition', ManageSerialComponent::class)->name('serial.edit');



            // LES ENSEIGNANTS
            Route::get('/enseignants/portail-enseignants', TeachersPortal::class)->name('teachers.portal');

            Route::get('/enseignants/ajout', CreateTeachers::class)->name('teachers.create');

            Route::get('/enseignants/status-des-ajouts', TeachersCreationMonitorComponent::class)->name('teachers.crud.tasks');

            Route::get('/enseignants/impression', PrintableListComponent::class)->name('teachers.print.list');




            // LES ELEVES
            Route::get('/apprenants/portail-apprenants', StudentsPortal::class)->name('students.portal');

            Route::get('/apprenants/ajout', CreateStudents::class)->name('students.create');

            Route::get('/apprenants/status-des-ajouts', StudentsCreationMonitorComponent::class)->name('students.crud.tasks');

            Route::get('/apprenants/impression', StudentsPrintableListComponent::class)->name('students.print.list');




            // LES PARENTS
            Route::get('/parents-des-apprenants/portail-parents-des-apprenants', ParentsPortal::class)->name('parents.portal');



            // LE CLASSES
            Route::get('/classes/portail-classses', ClassesPortal::class)->name('classes.portal');


            Route::get('/classes/portail-classses/profil-classe/ID={classe_slug}', ClasseProfil::class)->name('classe.profil');

            Route::get("/statistiques-semestrielles", PeriodicalStatistiqueComponent::class)->name('stats.general');

            Route::get('/details/apprenant/profil/{student_uuid}', StudentProfilPage::class)->name('student.profil');

            Route::get('/details/parent-des-apprenants/profil/{parent_uuid}', ParentProfil::class)->name('parent.profil');

            Route::get('/details/apprenant/les-notes/{student_uuid}', StudentMarksComponent::class)->name('student.marks');

            Route::get('/details/enseignant/profil/{teacher_uuid}', TeacherProfilPage::class)->name('teacher.profil');


            Route::get('/mise-a-jour-photo-de-profil-utilisateur/{target}/{modelUuid}', ProfilPhotoManagerByDirectorComponent::class)->name('director.manage.profil.photo');
            
            Route::get('/mise-a-jour-informations/apprenant/{studentUuid}', StudentDataManagerByDirectorComponent::class)->name('director.manage.student.data');

        });

        Route::get('/centre-de-notifications', NotificationsPage::class)->name('tenant.notifications.center');

        


        // ESPACE PARENT
        Route::get('/mon-profil', MyProfilPage::class)->name('tenant.my.profil');

        Route::get('/mon-profil/editer-photo-profil', UpdateProfilePhoto::class)->name('tenant.update.profil.photo');

        Route::get('/mon-espace-parent', ParentDashboard::class)->name('tenant.my.parent.space');

        Route::get('/mon-espace-parent/notes-enfants', ParentStudentsMarksViewer::class)->name('tenant.my.parent.space.marks');

        //ESPACE ENSEIGNANT
        Route::middleware(['role:enseignant', 'teacher.not.blocked'])->name('tenant.my.teacher.')->group(function () {
            Route::get('/mon-espace-enseignant', TeacherDashboard::class)->name('space');
            Route::get('/mon-espace-enseignant/les-notes', TeacherClasseMarksViewer::class)->name('space.marks');
            Route::get('/mon-espace-enseignant/insertion-notes', TeacherClasseMarksManagerComponent::class)->name('space.marks.manager');
            Route::get('/mon-espace-enseignant/liste-apprenants', TeacherClasseStudentsViewer::class)->name('space.students');
            
        });
        



       

        
        Route::middleware('tenant.domain.open.for.others.too')->group(function () {
            // ── Enseignant ────────────────────────────────────────────────
            Route::middleware('role:enseignant|directeur')->prefix('teacher')->name('teacher.')->group(function () {
                // sera rempli au fur et à mesure
            });

            // ── Tuteur ────────────────────────────────────────────────────
            Route::middleware('role:tuteur')->prefix('tutor')->name('tutor.')->group(function () {
                // sera rempli au fur et à mesure
            });

            // ── Élève ─────────────────────────────────────────────────────
            Route::middleware('role:eleve')->prefix('student')->name('student.')->group(function () {
                // sera rempli au fur et à mesure
            }); 

        });
    });

});
