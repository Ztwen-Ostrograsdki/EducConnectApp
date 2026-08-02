<?php

use App\Models\CentralUser;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// Guard dynamique selon le contexte
if (tenant() === null) {
    Broadcast::routes(['middleware' => ['web', 'auth:central']]);
}

// ── Channel commun à tous les users du tenant ─────────────
// ex: maintenance, annonces globales
Broadcast::channel('tenant.{tenantId}', function (User $user, string $tenantId) {
    return tenant() !== null
        && tenant('id') === $tenantId;
});

// ── Channel commun à tous les users du tenant ─────────────
// ex: maintenance, annonces globales
Broadcast::channel('tenant.{tenantId}.others', function (User $user, string $tenantId) {
    return tenant() !== null
        && tenant('id') === $tenantId
        && !$user->hasRole('directeur');
});

// ── Channel réservé au directeur ──────────────────────────
// ex: paiements, gestion des enseignants, stats
Broadcast::channel('tenant.{tenantId}.directeur', function (User $user, string $tenantId) {
    return tenant() !== null
        && tenant('id') === $tenantId
        && $user->hasRole('directeur');
});



// ── Channel réservé aux enseignants ───────────────────────
// ex: emploi du temps, notes, absences
Broadcast::channel('tenant.{tenantId}.enseignant', function (User $user, string $tenantId) {
    return tenant() !== null
        && tenant('id') === $tenantId
        && $user->hasRole('enseignant');
});

Broadcast::channel('tenant.{tenantId}.tuteur', function (User $user, string $tenantId) {
    return tenant() !== null
        && tenant('id') === $tenantId
        && $user->hasRole('tuteur');
});

Broadcast::channel('tenant.{tenantId}.tuteur.{userId}', function (User $user, string $tenantId, int $userId) {
    return tenant() !== null
        && tenant('id') === $tenantId
        && $user->hasRole('tuteur')
        && $user->id === $userId;
});
Broadcast::channel('tenant.{tenantId}.eleve', function (User $user, string $tenantId) {
    return tenant() !== null
        && tenant('id') === $tenantId
        && $user->hasRole('eleve');
});

// ── Channel personnel (un user enseignant spécifique) ────────────────
Broadcast::channel('tenant.{tenantId}.enseignant.{userId}', function (User $user, string $tenantId, int $userId) {
    return tenant() !== null
        && tenant('id') === $tenantId
        && $user->hasRole('enseignant')
        && $user->id === $userId;
});

// ── Channel personnel (un user spécifique) ────────────────
// ex: notification de blocage individuel
Broadcast::channel('tenant.{tenantId}.user.{userId}', function (User $user, string $tenantId, int $userId) {
    return tenant() !== null
        && tenant('id') === $tenantId
        && $user->id === $userId;
});

// ── Channel central ───────────────────────────────────────
Broadcast::channel('central-admin', function (CentralUser $user) {
    return tenant() === null;
});