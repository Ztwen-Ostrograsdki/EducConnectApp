<?php

declare(strict_types=1);

use App\Livewire\Auth\TenantLogin;
use App\Livewire\Tenants\Classes\ClasseProfil;
use App\Livewire\Tenants\Classes\ClassesPortal;
use App\Livewire\Tenants\Dashboard;
use App\Livewire\Tenants\Students\StudentProfilPage;
use App\Livewire\Tenants\Students\StudentsPortal;
use App\Livewire\Tenants\Teachers\TeacherProfilPage;
use App\Livewire\Tenants\Teachers\TeachersPortal;
use App\Livewire\Tenants\TenantDashboard;
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
            
            Route::get('/dashboard/apprenants/portail-apprenants', StudentsPortal::class)->name('students.portal');

            Route::get('/dashboard/classes/portail-classses/{classe_slug}', ClasseProfil::class)->name('classe.profil');

            
        });


        Route::get('/details/apprenant/profil/{student_uuid}', StudentProfilPage::class)->name('tenant.student.profil');

        

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
