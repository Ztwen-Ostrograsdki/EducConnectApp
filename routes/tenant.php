<?php

declare(strict_types=1);

use App\Livewire\Auth\TenantLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // ─── Auth ─────────────────────────────────────────────────────────
    Route::get('/login', TenantLogin::class)->name('login')->middleware('guest');
    
    
    Route::post('/logout', function () {

        // Vérifier que c'est bien le super admin
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout')->middleware('auth');

    // ─── Pages authentifiées ──────────────────────────────────────────
    Route::middleware('auth')->group(function () {

        // Dashboard
        Route::get('/', function () {
            return view('welcome'); // sera remplacé par le composant Dashboard
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
