# 🏫 EducConnect — ROADMAP & Documentation Technique

## 📌 Contexte du projet
Plateforme éducative **multi-école SaaS** construite avec Laravel 13 + Livewire 4 + stancl/tenancy.
Chaque école a son propre subdomain et sa propre base de données isolée.

---

## 🏗️ Stack technique

| Technologie | Version | Rôle |
|---|---|---|
| Laravel | 13.9.0 | Framework principal |
| Livewire | 4.3 | Composants dynamiques |
| stancl/tenancy | 3.10 | Multi-tenant (subdomain + DB séparée) |
| spatie/laravel-permission | 7.4 | Rôles & permissions |
| maatwebsite/excel | 3.1 | Import/Export Excel |
| spatie/browsershot | 5.3 | Génération PDF (bulletins) |
| Alpine.js | 3.15 | Interactivité JS légère |
| Motion | 12.38 | Animations |
| Tailwind CSS | 4.3 | Styles |
| Puppeteer | 24.43 | Rendu PDF headless |

---

## 🌐 Architecture Multi-tenant

### Type : Subdomain + DB séparée par école
```
educconnect.com          → Super Admin (DB centrale)
ecole-lumiere.educconnect.com  → DB tenant école Lumière
ecole-soleil.educconnect.com   → DB tenant école Soleil
```

### DB Centrale (landlord)
- Tenants (écoles)
- Domains (subdomains)
- Abonnements
- Super Admin

### DB Tenant (par école)
- Users (directeur, enseignants, parents)
- Élèves
- Classes
- Matières
- Notes
- Présences
- Années scolaires
- Paiements frais scolaires

---

## 👥 Rôles

| Rôle | Périmètre |
|---|---|
| `super_admin` | Plateforme entière, validation écoles, abonnements |
| `directeur` | Son école uniquement, tout gérer |
| `enseignant` | Ses classes + ses matières uniquement |
| `parent` | Données de ses enfants uniquement |
| `eleve` | Ses propres données |

---

## 🏫 Modèle École (Tenant)

```
Tenant
├── name
├── subdomain (ex: ecole-lumiere)
├── type_enseignement (general | technique | hybride)
├── type_periode (semestre | trimestre) → modifiable uniquement en début d'année
├── statut_abonnement
└── date_expiration_abonnement
```

---

## 📚 Structure Pédagogique

```
Niveau (level)
└── primaire | secondaire | supérieur

Type enseignement (par école)
└── général | technique | professionnel | hybride

AnneeScolaire
└── ex: 2024-2025 (année en cours par défaut)

Promotion
└── ensemble de classes de même niveau
└── ex: "Terminale", "Troisième", "Sixième"
└── appartient à une AnneeScolaire

Filière (enseignement technique/professionnel — créée par l'école)
└── ex: BTP, Informatique, Commerce

Série (enseignement général — créée par l'école)
└── ex: A, B, C, D

Classe
├── nom (ex: "Terminale BTP 2")
├── promotion_id
├── filiere_id (nullable)
├── serie_id (nullable)
├── niveau (primaire | secondaire | supérieur)
├── annee_scolaire_id
├── professeur_principal_id (enseignant)
├── responsable_1_id (apprenant)
└── responsable_2_id (apprenant)
```

### Exemples concrets
| Classe | Promotion | Filière | Série | Niveau |
|---|---|---|---|---|
| Terminale BTP 2 | Terminale | BTP | null | secondaire |
| Troisième 1 | Troisième | null | null | secondaire |
| Terminale C | Terminale | null | C | secondaire |
| BTS Informatique 1 | BTS | Informatique | null | supérieur |

---

## 👨‍🎓 Apprenants

- Ajout unitaire (dans une classe ou avec sélection de classe)
- Ajout en masse via fichier Excel
- **Pas de doublons** dans la base
- Ancien apprenant → réattribution de classe, archives consultables par année
- Apprenant sans classe active possible (abandon, non-inscription...)
- Toutes les données archivées par année, **rien n'est supprimé**
- Soft deletes sur tous les modèles

---

## 👩‍🏫 Enseignants

- Ajout par affiliation directe (directeur)
- Ajout par lien d'invitation (WhatsApp ou email)
- Plusieurs classes + plusieurs matières possibles
- Accès révocable immédiatement
- Pas de doublons

---

## 📝 Notes

### Types de notes
| Type | Sur |
|---|---|
| Interrogation 1..n | 10 |
| Devoir 1 | 20 |
| Devoir 2 | 20 |
| Examen | 20 |

### Règles
- Saisie unitaire ou via Excel
- **Non modifiable après délai** sans justification validée par le directeur
- Historique complet des modifications + éditeur référencé
- Envoi aux parents : SMS, Email, WhatsApp

---

## 📅 Périodes

- Choix par le directeur : **2 semestres** ou **3 trimestres**
- Modifiable uniquement en début d'année (pas en pleine année)
- Option stockée sur le tenant + l'année scolaire

---

## 📄 Bulletins PDF

- Générés via **Browsershot** (Puppeteer)
- Par élève, par classe, ou par promotion
- Envoi par email ou WhatsApp

---

## 📬 Notifications

| Canal | Provider |
|---|---|
| SMS | Africa's Talking |
| WhatsApp | Meta Cloud API (WhatsApp Business) |
| Email | Resend |

---

## 💳 Abonnements & Paiements

- Paiement en ligne intégré (provider non encore choisi)
- Architecture prévue, implémentation ultérieure

---

## ✅ État d'avancement

### Fait
- [x] Laravel 13 installé
- [x] Livewire, Tenancy, Spatie Permission, Excel, Browsershot installés
- [x] Alpine.js, Motion, Tailwind, Puppeteer installés
- [x] `php artisan tenancy:install` exécuté
- [x] Config `central_domains` à mettre à jour pour production
- [x] DB centrale `educconnect_central` créée
- [x] Connexion `central` ajoutée dans `config/database.php`
- [x] `.env` configuré
- [x] Repo GitHub lié

### En cours
- [ ] `php artisan migrate` (migrations centrales)
- [ ] Configuration TenancyServiceProvider
- [ ] Modèle Tenant personnalisé
- [ ] Migrations tenant (toutes les tables métier)
- [ ] Modèles avec relations Laravel
- [ ] Rôles & permissions Spatie
- [ ] Configuration vite.config.js + app.js + app.css

### À faire ensuite
- [ ] Layout principal (sidebar, navbar)
- [ ] Page super admin (gestion écoles, abonnements)
- [ ] Auth multi-tenant (login par subdomain)
- [ ] Module Années scolaires
- [ ] Module Classes & Promotions
- [ ] Module Apprenants (CRUD + import Excel)
- [ ] Module Enseignants (CRUD + invitation)
- [ ] Module Notes (saisie + historique)
- [ ] Module Présences
- [ ] Module Bulletins (PDF Browsershot)
- [ ] Module Notifications (SMS, WhatsApp, Email)
- [ ] Module Paiements frais scolaires
- [ ] Module Abonnements (super admin)

---

## 🗂️ Structure des dossiers prévue

```
app/
├── Models/
│   ├── Central/
│   │   ├── Tenant.php
│   │   └── Domain.php
│   └── Tenant/
│       ├── User.php
│       ├── Eleve.php
│       ├── Enseignant.php
│       ├── Classe.php
│       ├── Matiere.php
│       ├── Promotion.php
│       ├── Filiere.php
│       ├── Serie.php
│       ├── AnneeScolaire.php
│       ├── Note.php
│       ├── NoteHistorique.php
│       ├── Presence.php
│       └── Paiement.php
│
├── Livewire/
│   ├── Central/           ← Super Admin
│   │   ├── Dashboard.php
│   │   └── GestionEcoles.php
│   └── Tenant/            ← Par école
│       ├── Dashboard/
│       ├── Eleves/
│       ├── Enseignants/
│       ├── Classes/
│       ├── Notes/
│       ├── Presences/
│       └── Bulletins/
│
database/
├── migrations/            ← DB centrale
│   ├── create_tenants_table
│   └── create_domains_table
│
└── migrations/tenant/     ← DB par école
    ├── create_users_table
    ├── create_annees_scolaires_table
    ├── create_filieres_table
    ├── create_series_table
    ├── create_promotions_table
    ├── create_classes_table
    ├── create_matieres_table
    ├── create_eleves_table
    ├── create_enseignants_table
    ├── create_notes_table
    ├── create_note_historiques_table
    └── create_presences_table
```

---

## 💡 Comment reprendre cette conversation

Si la conversation est coupée, ouvre une nouvelle conversation avec Claude et dis :

> "Je travaille sur EducConnect, une plateforme SaaS multi-école Laravel + Livewire + stancl/tenancy.
> Voici mon ROADMAP : [colle ce fichier]
> On s'était arrêtés à : [indique l'étape en cours]"
