<?php

use App\Livewire\Auth\CentralLogin;
use App\Livewire\Central\CentralDashboard;
use App\Livewire\Central\RequestsComponent;
use App\Livewire\Central\SchoolProfilComponent;
use App\Livewire\Central\SchoolsComponent;
use App\Livewire\Central\SubscriptionsComponent;
use App\Livewire\Central\TenantProfilComponent;
use App\Livewire\Central\TenantsComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
})->middleware(['web', 'auth:central']);

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
    Route::get('/ecoles/gestion', SchoolsComponent::class)->name('schools.portal');

    // Gestion des directeurs ou tenants inscrits
    Route::get('/tenants/gestion', TenantsComponent::class)->name('tenants.portal');

    // Gestion des demandes abonnements
    Route::get('/les-demandes-abonnement/gestion', RequestsComponent::class)->name('requests.portal');


    // Gestion des abonnements
    Route::get('/les-abonnement/gestion', SubscriptionsComponent::class)->name('subscriptions.portal');

    // Gestion des tenants
    Route::get('/les-abonnes/profil/ID={tenant_uuid}', TenantProfilComponent::class)->name('tenant.profil');


    Route::get('/les-ecoles/profil/ID={school_uuid}', SchoolProfilComponent::class)->name('school.profil');

});

