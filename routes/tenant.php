<?php

declare(strict_types=1);

use App\Livewire\Auth\TenantLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
])->group(function () {

    // ─── Auth ─────────────────────────────────────────────────────────
    Route::get('/login', TenantLogin::class)->name('login')->middleware('guest');
    
    
    Route::post('/logout', function () {

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout')->middleware('auth');


    

    // ─── Pages authentifiées ──────────────────────────────────────────
    Route::middleware(['auth'])->group(function () {

        Route::get('/dashboard', function () {
            
        })->name('dashboard');

        // ── Directeur ─────────────────────────────────────────────────
        Route::middleware('role:directeur')->prefix('admin')->name('admin.')->group(function () {
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
