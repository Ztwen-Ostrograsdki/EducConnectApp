<?php

use App\Livewire\Auth\CentralLogin;
use App\Livewire\Central\CentralDashboard;
use App\Livewire\Central\RequestsComponent;
use App\Livewire\Central\SchoolProfilComponent;
use App\Livewire\Central\SubscriptionsComponent;
use App\Livewire\Central\TenantProfilComponent;
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
Route::middleware(['auth:central'])->prefix('administration/master')->name('central.')->group(function () {

    // Dashboard central
    Route::get('/dashboard', CentralDashboard::class)->name('dashboard');

    // Gestion des écoles (tenants)
    Route::prefix('schools')->name('schools.')->group(function () {
        // sera rempli au fur et à mesure
    });

    // Gestion des demandes abonnements
    Route::get('/les-demandes-abonnement/gestion', RequestsComponent::class)->name('requests.portal');


    // Gestion des abonnements
    Route::get('/les-abonnement/gestion', SubscriptionsComponent::class)->name('subscriptions.portal');

    // Gestion des tenants
    Route::get('/les-abonnes/profil/ID={tenant_uuid}', TenantProfilComponent::class)->name('tenant.profil');
    Route::get('/les-ecoles/profil/ID={school_uuid}', SchoolProfilComponent::class)->name('school.profil');

});
