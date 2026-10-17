<?php

namespace App\Livewire\Tenants;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Guide et Aide pour prise en mains')]
class HelpGuideComponent extends Component
{
    /**
     * Section actuellement ouverte dans le modal « Lire de près ».
     */
    public ?string $modalSectionId = null;

    /**
     * Guide complet de mise en route et de gestion courante d'EducConnect,
     * pensé pour orienter le directeur étape par étape.
     *
     * Chaque entrée accepte :
     * - id        : ancre pour la navigation (#id)
     * - icon      : nom de l'icône Lucide (sans préfixe x-lucide-)
     * - badge     : 'obligatoire' | 'recommande' | 'optionnel' | 'avance'
     * - title     : titre de la section
     * - intro     : courte phrase d'introduction
     * - points    : liste de points expliqués (string ou ['label' => ..., 'text' => ...])
     * - route     : nom de route Laravel (utilisé avec route())
     * - cta       : libellé du bouton d'action rapide
     */
    #[Computed]
    public function sections(): array
    {
        return [

            // ───────────────────────── 0. Paramétrage initial ─────────────────────────
            [
                'id'    => 'parametrage',
                'icon'  => 'settings-2',
                'badge' => 'obligatoire',
                'order' => 0,
                'color' => 'indigo',
                'title' => 'Paramétrer l\'établissement',
                'intro' => 'Avant toute saisie de données, configurez l\'année scolaire : ces choix s\'appliquent à toute l\'année et ne peuvent plus être changés une fois la rentrée lancée.',
                'points' => [
                    ['label' => 'Type de période', 'text' => 'Choisissez si l\'établissement fonctionne par trimestre ou par semestre.'],
                    ['label' => 'Format des devoirs', 'text' => 'Définissez si les évaluations sommatives suivent le format Devoir 1 / Devoir 2, ou Devoir / Composition.'],
                    ['label' => 'Seuil de réussite', 'text' => 'Fixez la moyenne minimale de validation d\'une période (par défaut 10/20, ajustable).'],
                    ['label' => 'Identité visuelle', 'text' => 'Ajoutez le logo et l\'en-tête utilisés sur les documents générés (bulletins, listes, PDF...), ainsi que la photo de couverture de la page d\'accueil.'],
                    ['label' => 'Sécurité', 'text' => 'Activez ou restreignez l\'accès au domaine, et décidez si les PP, AE et CA peuvent éditer les coefficients des matières.'],
                ],
                'route' => 'tenant.settings',
                'cta'   => 'Ouvrir les paramètres',
            ],

            // ───────────────────────── 1. Année scolaire active ─────────────────────────
            [
                'id'    => 'annee-scolaire',
                'icon'  => 'calendar-check-2',
                'badge' => 'obligatoire',
                'order' => 1,
                'color' => 'violet',
                'title' => 'Activer une année scolaire',
                'intro' => 'Aucune donnée ne peut être créée tant qu\'une année scolaire n\'est pas explicitement activée.',
                'points' => [
                    'Si aucune année scolaire n\'existe, vous serez automatiquement redirigé vers sa création à la connexion.',
                    'Même si plusieurs années existent, une seule doit être activée : c\'est elle qui sera manipulable par tous les utilisateurs.',
                    'Depuis le profil de l\'année scolaire, gérez chaque période individuellement : active (notes modifiables), verrouillée (saisie bloquée) ou fermée.',
                    'Profitez-en pour définir les dates de couverture de chaque période (ex. les bornes du 1er trimestre).',
                ],
                'route' => 'tenant.schoolyears.portal',
                'cta'   => 'Gérer les années scolaires',
            ],

            // ───────────────────────── 2. Filières / Séries ─────────────────────────
            [
                'id'    => 'filieres-series',
                'icon'  => 'git-branch',
                'badge' => 'obligatoire',
                'order' => 2,
                'color' => 'fuchsia',
                'title' => 'Créer les filières et/ou les séries',
                'intro' => 'Première brique de l\'organisation pédagogique — tout le reste (promotions, classes) en dépend.',
                'points' => [
                    ['label' => 'Enseignement technique', 'text' => 'Créez les filières (ex. F3, F4, MVP...).'],
                    ['label' => 'Enseignement général', 'text' => 'Créez les séries (ex. AB, C, D...).'],
                    ['label' => 'École hybride', 'text' => 'Créez les deux : filières et séries.'],
                ],
                'route' => 'tenant.filiars.portal',
                'cta'   => 'Gérer les filières / séries',
            ],

            // ───────────────────────── 3. Promotions ─────────────────────────
            [
                'id'    => 'promotions',
                'icon'  => 'layers',
                'badge' => 'obligatoire',
                'order' => 3,
                'color' => 'rose',
                'title' => 'Créer les promotions',
                'intro' => 'Une promotion rattache un niveau à une filière ou une série.',
                'points' => [
                    'Exemples : « Première F4 », « Seconde MVP », « Seconde C »...',
                    'Les niveaux inférieurs en enseignement général (6e à 3e) n\'ont pas besoin de filière/série précisée.',
                    'À partir de la Seconde en enseignement général, la filière ou la série devient obligatoire.',
                ],
                'route' => 'tenant.promotions.portal',
                'cta'   => 'Gérer les promotions',
            ],

            // ───────────────────────── 4. Classes ─────────────────────────
            [
                'id'    => 'classes',
                'icon'  => 'door-open',
                'badge' => 'obligatoire',
                'order' => 4,
                'color' => 'orange',
                'title' => 'Créer les classes et les peupler',
                'intro' => 'Une classe existe pour une année scolaire donnée — elle est à recréer (ou relier) chaque année.',
                'points' => [
                    'En cas de classes parallèles sur une même promotion, différenciez-les librement (ex. « Seconde MVP 2 » ou « Seconde MVP A »).',
                    'Une fois la classe créée, ajoutez-y des apprenants et des enseignants déjà existants dans la base — aucune création à la volée depuis cet écran.',
                    'Un apprenant ne peut appartenir qu\'à une seule classe par année scolaire.',
                ],
                'route' => 'tenant.classes.portal',
                'cta'   => 'Gérer les classes',
            ],

            // ───────────────────────── 5. Matières ─────────────────────────
            [
                'id'    => 'matieres',
                'icon'  => 'book-open',
                'badge' => 'obligatoire',
                'order' => 5,
                'color' => 'amber',
                'title' => 'Créer les matières',
                'intro' => 'Les matières doivent exister avant de pouvoir être associées à une classe ou à un enseignant.',
                'points' => [
                    'Rendez-vous dans la section Matières pour créer toutes les disciplines enseignées dans l\'établissement.',
                ],
                'route' => 'tenant.subjects.portal',
                'cta'   => 'Gérer les matières',
            ],

            // ───────────────────────── 6. Enseignants & matières enseignées ─────────────────────────
            [
                'id'    => 'enseignants-matieres',
                'icon'  => 'user-check',
                'badge' => 'obligatoire',
                'order' => 6,
                'color' => 'lime',
                'title' => 'Lier chaque enseignant à ses matières',
                'intro' => 'Étape souvent oubliée, mais indispensable pour l\'affectation en classe et les rôles AE/CA.',
                'points' => [
                    'Depuis le profil de chaque enseignant, liez les matières qu\'il enseigne — un enseignant peut en piloter plusieurs.',
                    'Cette liaison conditionne quels enseignants apparaîtront comme éligibles lors de l\'affectation matière ↔ classe, et lors de la nomination d\'un AE.',
                ],
                'route' => 'tenant.teachers.portal',
                'cta'   => 'Gérer les enseignants',
            ],

            // ───────────────────────── 7. Affectation matière + prof par classe ─────────────────────────
            [
                'id'    => 'affectation-classe',
                'icon'  => 'link-2',
                'badge' => 'obligatoire',
                'order' => 7,
                'color' => 'emerald',
                'title' => 'Affecter matières et professeurs à une classe',
                'intro' => 'Depuis la fiche d\'une classe, associez chaque matière enseignée à l\'enseignant correspondant.',
                'points' => [
                    'Cliquez sur « Ajouter les matières / profs », cochez une matière, puis choisissez un enseignant parmi ceux qui l\'enseignent.',
                    'Répétez l\'opération pour chaque matière de la classe — l\'association matière + professeur se fait par paire.',
                    'Si aucun professeur n\'est disponible pour une matière, celle-ci ne pourra pas encore être associée à la classe.',
                ],
                'route' => 'tenant.classes.portal',
                'cta'   => 'Ouvrir une classe',
            ],

            // ───────────────────────── 8. Responsables de classe ─────────────────────────
            [
                'id'    => 'responsables-classe',
                'icon'  => 'award',
                'badge' => 'recommande',
                'order' => 8,
                'color' => 'teal',
                'title' => 'Définir les responsables de la classe',
                'intro' => 'Professeur principal et délégués élèves complètent l\'organisation de la classe.',
                'points' => [
                    'Le professeur principal (PP) contrôle le cahier de conduite de la classe.',
                    'Dès sa désignation, il devient automatiquement titulaire d\'une matière « Conduite » propre à sa classe.',
                    'Sans PP défini, les apprenants n\'auront simplement pas de note de conduite — cela n\'affecte pas le calcul des autres moyennes.',
                    'Désignez également les délégués élèves de la classe.',
                ],
                'route' => 'tenant.classes.portal',
                'cta'   => 'Définir les responsables',
            ],

            // ───────────────────────── 9. CA & AE ─────────────────────────
            [
                'id'    => 'ca-ae',
                'icon'  => 'users',
                'badge' => 'recommande',
                'order' => 9,
                'color' => 'cyan',
                'title' => 'Nommer les CA et les AE',
                'intro' => 'Chef d\'Atelier (CA, par filière) et Animateur d\'Établissement (AE, par matière), chacun avec un titulaire et un adjoint.',
                'points' => [
                    ['label' => 'AE (par matière)', 'text' => 'Seuls les enseignants liés à cette matière apparaissent comme éligibles.'],
                    ['label' => 'CA (par filière)', 'text' => 'Seuls les enseignants qui enseignent dans au moins une classe rattachée à cette filière apparaissent comme éligibles (il n\'y a pas de liaison directe enseignant ↔ filière).'],
                    'Le choix final parmi les enseignants éligibles reste entièrement à votre discrétion.',
                ],
                'route' => 'tenant.subjects.portal',
                'cta'   => 'Gérer CA et AE',
            ],

            // ───────────────────────── 10. Heures AP & emploi du temps ─────────────────────────
            [
                'id'    => 'emploi-du-temps',
                'icon'  => 'clock-4',
                'badge' => 'obligatoire',
                'order' => 10,
                'color' => 'sky',
                'title' => 'Définir les heures et l\'emploi du temps',
                'intro' => 'Un passage obligé avant de construire le moindre emploi du temps de classe.',
                'points' => [
                    'Dans la section Matières, puis dans la section Filières, définissez le volume horaire (heures d\'AP) de chaque matière et de chaque filière.',
                    'Cette étape évite les collisions d\'horaire : un même enseignant ne peut pas être sollicité sur deux classes au même créneau.',
                    'Créez ensuite l\'emploi du temps propre à chaque classe, puis ajoutez-y les heures — valable uniquement pour l\'année scolaire en cours.',
                ],
                'route' => 'tenant.timeplans.dashboard',
                'cta'   => 'Gérer les emplois du temps',
            ],

            // ───────────────────────── 11. Parents / Tuteurs ─────────────────────────
            [
                'id'    => 'parents',
                'icon'  => 'users-round',
                'badge' => 'optionnel',
                'order' => 11,
                'color' => 'blue',
                'title' => 'Créer et lier les parents (tuteurs)',
                'intro' => 'Les parents peuvent être créés indépendamment, sans connaître leurs enfants au préalable.',
                'points' => [
                    'Depuis le profil d\'un parent (« Gérer les liaisons parentales ») ou depuis le profil d\'un apprenant, liez parent et élève — un parent peut suivre plusieurs enfants.',
                    'Une fois lié, le parent accède à un espace restreint : notes, emploi du temps et suivi de performance de son enfant.',
                    'Contrairement aux enseignants, l\'accès d\'un parent n\'a pas besoin d\'être renouvelé chaque année scolaire.',
                ],
                'route' => 'tenant.parents.portal',
                'cta'   => 'Gérer les parents',
            ],

            // ───────────────────────── 12. Cycle de vie du compte enseignant ─────────────────────────
            [
                'id'    => 'comptes-enseignants',
                'icon'  => 'key-round',
                'badge' => 'recommande',
                'order' => 12,
                'color' => 'purple',
                'title' => 'Gérer le cycle de vie des comptes enseignants',
                'intro' => 'Identifiants, accès annuel et bonnes pratiques pour éviter les blocages.',
                'points' => [
                    'À la création, l\'enseignant reçoit automatiquement ses identifiants de connexion par e-mail.',
                    'En l\'absence de réception, utilisez le bouton « Envoyer les données » sur son profil — attention : cela réinitialise son mot de passe.',
                    'L\'accès à la plateforme est lié à l\'année scolaire : pensez à l\'accorder (ou le retirer) chaque année depuis le profil de l\'enseignant.',
                    'Retrait impossible si l\'enseignant a des classes ou des notes actives : retirez-le d\'abord de ses classes (le nouvel enseignant affecté reprend alors la propriété des notes déjà saisies) avant de retirer son accès.',
                ],
                'route' => 'tenant.teachers.portal',
                'cta'   => 'Gérer les enseignants',
            ],

            // ───────────────────────── 13. Rôles cumulés ─────────────────────────
            [
                'id'    => 'roles-cumules',
                'icon'  => 'user-cog',
                'badge' => 'optionnel',
                'order' => 13,
                'color' => 'pink',
                'title' => 'Cumuler les rôles enseignant et parent',
                'intro' => 'Un même utilisateur peut porter plusieurs casquettes sur la plateforme.',
                'points' => [
                    'Depuis le tableau de bord des comptes utilisateurs, ajoutez le rôle Enseignant et/ou le rôle Tuteur à un même utilisateur.',
                    'Les deux accès suivent des cycles indépendants : l\'accès tuteur reste actif en continu, l\'accès enseignant doit être renouvelé chaque année.',
                    'Un utilisateur sans aucun rôle attribué n\'a accès à aucun espace de la plateforme.',
                ],
                'route' => 'tenant.accounts',
                'cta'   => 'Gérer les comptes utilisateurs',
            ],

            // ───────────────────────── 14. Vitrine de l'établissement ─────────────────────────
            [
                'id'    => 'vitrine',
                'icon'  => 'image',
                'badge' => 'optionnel',
                'order' => 14,
                'color' => 'red',
                'title' => 'Mettre en valeur la page d\'accueil',
                'intro' => 'Donnez de la visibilité à votre établissement — entièrement facultatif.',
                'points' => [
                    'Présentez le personnel de l\'établissement, photos à l\'appui.',
                    'Constituez une galerie des moments forts (filières, grandes tendances de l\'établissement).',
                    'Activez et modérez les témoignages visibles publiquement sur la plateforme.',
                ],
                'route' => 'tenants.home',
                'cta'   => 'Personnaliser la page d\'accueil',
            ],

            // ───────────────────────── 15. Consulter une archive ─────────────────────────
            [
                'id'    => 'archives',
                'icon'  => 'archive',
                'badge' => 'avance',
                'order' => 15,
                'color' => 'yellow',
                'title' => 'Consulter une année scolaire archivée',
                'intro' => 'À manier avec précaution : activer une ancienne année a des implications pour tous les utilisateurs.',
                'points' => [
                    'Une année « active » est celle manipulable par tous ; une année « fermée » reste consultable mais verrouillée pour les autres utilisateurs.',
                    'Avant de consulter une archive, il est recommandé de restreindre temporairement l\'accès au domaine (section Paramètres → Sécurité).',
                    'Si vous rouvrez une ancienne année, assurez-vous que ses périodes restent verrouillées pour éviter tout mouvement de notes involontaire.',
                    'Pensez à réactiver l\'accès au domaine et à revenir sur l\'année scolaire courante une fois la consultation terminée.',
                ],
                'route' => 'tenant.schoolyears.portal',
                'cta'   => 'Gérer les années scolaires',
            ],
        ];
    }

    #[Computed]
    public function modalSection(): ?array
    {
        if (! $this->modalSectionId) {
            return null;
        }

        return collect($this->sections)->firstWhere('id', $this->modalSectionId);
    }

    public function openModal(string $id): void
    {
        $this->modalSectionId = $id;
    }

    public function closeModal(): void
    {
        $this->modalSectionId = null;
    }

    public function render()
    {
        return view('livewire.tenants.help-guide-component');
    }
}
