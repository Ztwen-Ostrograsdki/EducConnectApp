<?php

use App\Livewire\Actions\RequestPage;
use App\Livewire\Auth\CentralLogin;
use App\Livewire\Central\CentralDashboard;
use App\Livewire\Central\CentralNotificationsComponent;
use App\Livewire\Central\NotificationsCenter;
use App\Livewire\Central\PlansManagerComponent;
use App\Livewire\Central\SchoolProfilComponent;
use App\Livewire\Central\SchoolsComponent;
use App\Livewire\Central\SchoolSpaceRequestsManageComponent;
use App\Livewire\Central\SubscriptionsComponent;
use App\Livewire\Central\SubscriptionsRequestsManageComponent;
use App\Livewire\Central\TenantProfilComponent;
use App\Livewire\Central\TenantsComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});



Route::post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
})->middleware(['web', 'auth:central']);

Route::get('/demande-d-espace-ecole', RequestPage::class)->name('central.request.to.create.tenant')->middleware('guest:central');



// ─── Auth centrale ────────────────────────────────────────────────────
Route::get('/login', CentralLogin::class)->name('central.login')->middleware('guest:central');

Route::post('/logout', function () {

    /** @noinspection PhpUndefinedMethodInspection */
    Auth::guard('central')->logout();
    Session::invalidate();
    Session::regenerateToken();

    return redirect()->route('central.login');
})->name('central.logout')->middleware('auth:central');

// ─── Pages super admin ────────────────────────────────────────────────
Route::middleware(['auth:central'])->prefix('administration/central')->name('central.')->group(function () {

    // Dashboard central
    Route::get('/', CentralDashboard::class)->name('dashboard');

    // Gestion des plans d'abonnement
    Route::get('/plans/gestion', PlansManagerComponent::class)->name('plans.portal');

    // Gestion des demandes d'espace ecole
    Route::get('/les-demandes-espace/gestion/{status?}', SchoolSpaceRequestsManageComponent::class)->name('requests.school.space.portal');

    // Gestion des écoles (tenants)
    Route::get('/ecoles/gestion/{status?}', SchoolsComponent::class)->name('schools.portal');

    // Gestion des directeurs ou tenants inscrits
    Route::get('/tenants/gestion', TenantsComponent::class)->name('tenants.portal');

    // Gestion des demandes abonnements
    Route::get('/les-demandes-abonnement/gestion', SubscriptionsRequestsManageComponent::class)->name('pendings.subscriptions.requests.portal');


    // Gestion des abonnements
    Route::get('/les-abonnement/gestion', SubscriptionsComponent::class)->name('validateds.subscriptions.portal');

    // Gestion des tenants
    Route::get('/les-abonnes/profil/ID={tenant_uuid}', TenantProfilComponent::class)->name('tenant.profil');


    Route::get('/profil/ecole/{school}', SchoolProfilComponent::class)->name('school.profil');


    Route::get('/centre-de-notifications', CentralNotificationsComponent::class)->name('notifications.center');

});

