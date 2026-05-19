<?php

use App\Livewire\Auth\CentralLogin;
use App\Livewire\Central\CentralDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return view('welcome');
});

// ─── Auth centrale ────────────────────────────────────────────────────
Route::get('/login', CentralLogin::class)->name('central.login')->middleware('guest:central');

Route::post('/logout', function () {
    
    Auth::guard('central')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('central.login');
})->name('central.logout')->middleware('auth:central');




// ─── Pages super admin ────────────────────────────────────────────────
Route::middleware(['auth:central'])->prefix('admin')->name('central.')->group(function () {

    // Dashboard central
    Route::get('/dashboard', CentralDashboard::class)->name('dashboard');

    // Gestion des écoles (tenants)
    Route::prefix('schools')->name('schools.')->group(function () {
        // sera rempli au fur et à mesure
    });

    // Gestion des abonnements
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        // sera rempli au fur et à mesure
    });

});