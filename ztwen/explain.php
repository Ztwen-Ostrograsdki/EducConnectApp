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


// Liste enseignants → storage/app/public/tenants/{id}/documents/liste-enseignants_20260614_094500.pdf
// PdfFactory::dispatch(
//     view:         'livewire.teachers.print-teacher-list',
//     data:         $viewData,
//     outputPath:   PdfFactory::outputPath('teachers', 'liste-enseignants', tenant('id')),
//     overrides:    ['landscape' => true],
//     tenantId:     tenant('id'),
//     notifiable:   \App\Models\User::class,
//     notifiableId: auth()->id(),
//     notification: \App\Notifications\PdfReadyNotification::class,
// );

// // Bulletin → storage/app/public/tenants/{id}/bulletins/bulletin-dupont-jean_20260614_094500.pdf
// PdfFactory::dispatch(
//     view:       'pdf.bulletin-notes',
//     data:       ['eleve' => $eleve, 'trimestre' => 2],
//     outputPath: PdfFactory::outputPath('bulletins', "bulletin-{$eleve->nom}-{$eleve->prenom}", tenant('id')),
//     tenantId:   tenant('id'),
// );

// // Epreuve → storage/app/public/tenants/{id}/epreuves/sujet-maths-term_20260614_094500.pdf
// PdfFactory::dispatch(
//     view:       'pdf.sujet-epreuve',
//     data:       ['epreuve' => $epreuve],
//     outputPath: PdfFactory::outputPath('epreuves', "sujet-{$epreuve->matiere}-{$epreuve->classe}", tenant('id')),
//     tenantId:   tenant('id'),
// );