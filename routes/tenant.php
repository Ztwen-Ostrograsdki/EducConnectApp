<?php

declare(strict_types=1);

use App\Livewire\Auth\TenantLogin;
use App\Livewire\Tenants\Classes\ClasseProfil;
use App\Livewire\Tenants\Classes\ClassesPortal;
use App\Livewire\Tenants\Filiars\FiliarProfil;
use App\Livewire\Tenants\Filiars\FiliarsPortal;
use App\Livewire\Tenants\Parents\ParentProfil;
use App\Livewire\Tenants\Parents\ParentsPortal;
use App\Livewire\Tenants\Promotions\PromotionProfil;
use App\Livewire\Tenants\Promotions\PromotionsPortal;
use App\Livewire\Tenants\Serials\SerialProfil;
use App\Livewire\Tenants\Serials\SerialsPortal;
use App\Livewire\Tenants\Stats\PeriodicalStatistiqueComponent;
use App\Livewire\Tenants\Students\StudentMarksComponent;
use App\Livewire\Tenants\Students\StudentProfilPage;
use App\Livewire\Tenants\Students\StudentsPortal;
use App\Livewire\Tenants\Subjects\SubjectProfil;
use App\Livewire\Tenants\Subjects\SubjectsPortal;
use App\Livewire\Tenants\Teachers\TeacherProfilPage;
use App\Livewire\Tenants\Teachers\TeachersPortal;
use App\Livewire\Tenants\TenantDashboard;
use App\Livewire\Tenants\Users\NotificationsPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
])->group(function () {

    // ─── Auth ─────────────────────────────────────────────────────────
    Route::get('/login', TenantLogin::class)->name('login')->middleware('guest:tenant');
    
    
    Route::post('/logout', function () {

        Auth::guard('tenant')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout')->middleware('auth:tenant');

    // ─── Pages authentifiées ──────────────────────────────────────────
    Route::middleware(['auth:tenant'])->group(function () {

        Route::get('/dashboard', TenantDashboard::class)->name('dashboard');

        // ── Directeur ─────────────────────────────────────────────────
        Route::middleware('role:directeur')->prefix('ecole')->name('tenant.')->group(function () {
            Route::get('/dashboard/classes/portail-classses', ClassesPortal::class)->name('classes.portal');
            
            Route::get('/dashboard/enseignants/portail-enseignants', TeachersPortal::class)->name('teachers.portal');
            
            Route::get('/dashboard/parents-des-apprenants/portail-parents-des-apprenants', ParentsPortal::class)->name('parents.portal');
            
            Route::get('/dashboard/apprenants/portail-apprenants', StudentsPortal::class)->name('students.portal');

            Route::get('/dashboard/matieres/portail-des-matieres', SubjectsPortal::class)->name('subjects.portal');
            
            Route::get('/dashboard/promotions/portail-des-promotions', PromotionsPortal::class)->name('promotions.portal');

            Route::get('/dashboard/promotions/portail-des-promotions/profil-promotion/ID={promotion_slug}', PromotionProfil::class)->name('promotion.profil');
            
            Route::get('/dashboard/matieres/portail-des-matieres/profil-matiere/ID={subject_slug}', SubjectProfil::class)->name('subject.profil');
            
            Route::get('/dashboard/classes/portail-classses/profil-classe/ID={classe_slug}', ClasseProfil::class)->name('classe.profil');

            Route::get('/dashboard/filiars/portail-des-filiars', FiliarsPortal::class)->name('filiars.portal');

            Route::get('/dashboard/filieres/portail-des-filieres/profil-filiere/ID={filiar_slug}', FiliarProfil::class)->name('filiar.profil');

            Route::get('/dashboard/series/portail-des-series', SerialsPortal::class)->name('serials.portal');

            Route::get('/dashboard/series/portail-des-series/profil-serie/ID={serial_slug}', SerialProfil::class)->name('serial.profil');
            
            Route::get('/dashboard/statistiques-semestrielles/',PeriodicalStatistiqueComponent::class)->name('stats.general');

            
        });


        Route::get('/details/apprenant/profil/{student_uuid}', StudentProfilPage::class)->name('tenant.student.profil');

        Route::get('/centre-de-notifications', NotificationsPage::class)->name('tenant.notifications.center');
        
        
        
        Route::get('/details/parent-des-apprenants/profil/{parent_uuid}', ParentProfil::class)->name('tenant.parent.profil');
        
        
        Route::get('/details/apprenant/les-notes/{student_uuid}', StudentMarksComponent::class)->name('tenant.student.marks');

        

        Route::get('/details/enseignant/profil/{teacher_uuid}', TeacherProfilPage::class)->name('tenant.teacher.profil');

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
