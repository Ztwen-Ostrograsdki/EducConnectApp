# EducConnect — TimePlan

Module Laravel tenant-aware pour gérer les emplois du temps hebdomadaires d'une classe, les affectations pédagogiques et l'affichage du planning dans le profil de classe.

## Fonctionnalités
- Modèles `TimePlan` et `TimePlanSlot` sur la connexion `tenant`.
- Migrations tenant.
- Gestion Livewire des emplois du temps et vue Tailwind.
- Transfert des créneaux lors du remplacement d'un enseignant (voir guide d'installation).
- Composant `ClassTimetable` pour afficher uniquement l'emploi du temps publié d'une classe.

Consulter `docs/INSTALLATION.md` avant intégration. Le module doit être adapté et testé dans l'application hôte, notamment les noms de guards, la migration tenant et l'accessor du nom des enseignants.
