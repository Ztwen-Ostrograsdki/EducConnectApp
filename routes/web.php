<?php

use App\Livewire\Auth\CentralLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



// ─── Auth centrale ────────────────────────────────────────────────────
Route::get('/login', CentralLogin::class)->name('central.login')->middleware('guest');
Route::post('/logout', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    $user->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('central.login');
})->name('logout')->middleware('auth');

// ─── Pages super admin ────────────────────────────────────────────────
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('central.')->group(function () {

    // Dashboard central
    Route::get('/dashboard', function () {
        return view('welcome'); // sera remplacé par le composant CentralDashboard
    })->name('dashboard');

    // Gestion des écoles (tenants)
    Route::prefix('schools')->name('schools.')->group(function () {
        // sera rempli au fur et à mesure
    });

    // Gestion des abonnements
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        // sera rempli au fur et à mesure
    });

});