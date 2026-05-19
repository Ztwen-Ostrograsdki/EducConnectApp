<?php

declare(strict_types=1);

use App\Livewire\Auth\TenantLogin;
use App\Livewire\Tenants\Dashboard;
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
        Route::middleware('role:directeur')->prefix('director')->name('director.')->group(function () {
            // sera rempli au fur et à mesure
        });

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
