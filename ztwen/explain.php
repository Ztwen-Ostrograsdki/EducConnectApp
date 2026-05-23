<?php

// Vers tous les users du tenant (ex: maintenance)
// broadcast(new MaintenancePrevue($tenant, 'Maintenance ce soir à 22h'));
// → channel: tenant.{id}

// Vers le directeur uniquement
// broadcast(new PaiementRecu($tenant, $montant));
// → channel: tenant.{id}.directeur

// Vers tous les enseignants
// broadcast(new EmploiUpdated($tenant));
// → channel: tenant.{id}.enseignant

// Vers un user spécifique
// broadcast(new UserNotification($tenant, $user, 'Votre profil a été mis à jour'));
// → channel: tenant.{id}.user.{userId}