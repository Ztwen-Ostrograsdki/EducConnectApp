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