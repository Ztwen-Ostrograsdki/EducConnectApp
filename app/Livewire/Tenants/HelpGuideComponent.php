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
    public function colorMap(): ?array
    {
        return [
            'indigo' => [
                'border' => 'border-indigo-500/25',
                'borderHover' => 'hover:border-indigo-400/50',
                'bg' => 'bg-indigo-500/5',
                'bgIcon' => 'bg-indigo-500/15',
                'text' => 'text-indigo-300',
                'ring' => 'ring-indigo-500/20',
                'shadow' => 'hover:shadow-indigo-500/10',
                'accent' => 'from-indigo-500/20',
                'btn' => 'text-indigo-300 hover:text-indigo-200',
                'btnBg' => 'bg-indigo-500/10 border-indigo-500/25 hover:bg-indigo-500/20',
                'navHover' => 'hover:bg-indigo-500/10 hover:text-indigo-200',
                'navIconHover' =>
                    'group-hover:border-indigo-500/40 group-hover:bg-indigo-500/15 group-hover:text-indigo-300',
                'chip' =>
                    'border-indigo-500/25 bg-indigo-500/10 text-indigo-300 hover:bg-indigo-500/20 hover:border-indigo-500/40',
            ],
            'violet' => [
                'border' => 'border-violet-500/25',
                'borderHover' => 'hover:border-violet-400/50',
                'bg' => 'bg-violet-500/5',
                'bgIcon' => 'bg-violet-500/15',
                'text' => 'text-violet-300',
                'ring' => 'ring-violet-500/20',
                'shadow' => 'hover:shadow-violet-500/10',
                'accent' => 'from-violet-500/20',
                'btn' => 'text-violet-300 hover:text-violet-200',
                'btnBg' => 'bg-violet-500/10 border-violet-500/25 hover:bg-violet-500/20',
                'navHover' => 'hover:bg-violet-500/10 hover:text-violet-200',
                'navIconHover' =>
                    'group-hover:border-violet-500/40 group-hover:bg-violet-500/15 group-hover:text-violet-300',
                'chip' =>
                    'border-violet-500/25 bg-violet-500/10 text-violet-300 hover:bg-violet-500/20 hover:border-violet-500/40',
            ],
            'fuchsia' => [
                'border' => 'border-fuchsia-500/25',
                'borderHover' => 'hover:border-fuchsia-400/50',
                'bg' => 'bg-fuchsia-500/5',
                'bgIcon' => 'bg-fuchsia-500/15',
                'text' => 'text-fuchsia-300',
                'ring' => 'ring-fuchsia-500/20',
                'shadow' => 'hover:shadow-fuchsia-500/10',
                'accent' => 'from-fuchsia-500/20',
                'btn' => 'text-fuchsia-300 hover:text-fuchsia-200',
                'btnBg' => 'bg-fuchsia-500/10 border-fuchsia-500/25 hover:bg-fuchsia-500/20',
                'navHover' => 'hover:bg-fuchsia-500/10 hover:text-fuchsia-200',
                'navIconHover' =>
                    'group-hover:border-fuchsia-500/40 group-hover:bg-fuchsia-500/15 group-hover:text-fuchsia-300',
                'chip' =>
                    'border-fuchsia-500/25 bg-fuchsia-500/10 text-fuchsia-300 hover:bg-fuchsia-500/20 hover:border-fuchsia-500/40',
            ],
            'rose' => [
                'border' => 'border-rose-500/25',
                'borderHover' => 'hover:border-rose-400/50',
                'bg' => 'bg-rose-500/5',
                'bgIcon' => 'bg-rose-500/15',
                'text' => 'text-rose-300',
                'ring' => 'ring-rose-500/20',
                'shadow' => 'hover:shadow-rose-500/10',
                'accent' => 'from-rose-500/20',
                'btn' => 'text-rose-300 hover:text-rose-200',
                'btnBg' => 'bg-rose-500/10 border-rose-500/25 hover:bg-rose-500/20',
                'navHover' => 'hover:bg-rose-500/10 hover:text-rose-200',
                'navIconHover' =>
                    'group-hover:border-rose-500/40 group-hover:bg-rose-500/15 group-hover:text-rose-300',
                'chip' =>
                    'border-rose-500/25 bg-rose-500/10 text-rose-300 hover:bg-rose-500/20 hover:border-rose-500/40',
            ],
            'orange' => [
                'border' => 'border-orange-500/25',
                'borderHover' => 'hover:border-orange-400/50',
                'bg' => 'bg-orange-500/5',
                'bgIcon' => 'bg-orange-500/15',
                'text' => 'text-orange-300',
                'ring' => 'ring-orange-500/20',
                'shadow' => 'hover:shadow-orange-500/10',
                'accent' => 'from-orange-500/20',
                'btn' => 'text-orange-300 hover:text-orange-200',
                'btnBg' => 'bg-orange-500/10 border-orange-500/25 hover:bg-orange-500/20',
                'navHover' => 'hover:bg-orange-500/10 hover:text-orange-200',
                'navIconHover' =>
                    'group-hover:border-orange-500/40 group-hover:bg-orange-500/15 group-hover:text-orange-300',
                'chip' =>
                    'border-orange-500/25 bg-orange-500/10 text-orange-300 hover:bg-orange-500/20 hover:border-orange-500/40',
            ],
            'amber' => [
                'border' => 'border-amber-500/25',
                'borderHover' => 'hover:border-amber-400/50',
                'bg' => 'bg-amber-500/5',
                'bgIcon' => 'bg-amber-500/15',
                'text' => 'text-amber-300',
                'ring' => 'ring-amber-500/20',
                'shadow' => 'hover:shadow-amber-500/10',
                'accent' => 'from-amber-500/20',
                'btn' => 'text-amber-300 hover:text-amber-200',
                'btnBg' => 'bg-amber-500/10 border-amber-500/25 hover:bg-amber-500/20',
                'navHover' => 'hover:bg-amber-500/10 hover:text-amber-200',
                'navIconHover' =>
                    'group-hover:border-amber-500/40 group-hover:bg-amber-500/15 group-hover:text-amber-300',
                'chip' =>
                    'border-amber-500/25 bg-amber-500/10 text-amber-300 hover:bg-amber-500/20 hover:border-amber-500/40',
            ],
            'lime' => [
                'border' => 'border-lime-500/25',
                'borderHover' => 'hover:border-lime-400/50',
                'bg' => 'bg-lime-500/5',
                'bgIcon' => 'bg-lime-500/15',
                'text' => 'text-lime-300',
                'ring' => 'ring-lime-500/20',
                'shadow' => 'hover:shadow-lime-500/10',
                'accent' => 'from-lime-500/20',
                'btn' => 'text-lime-300 hover:text-lime-200',
                'btnBg' => 'bg-lime-500/10 border-lime-500/25 hover:bg-lime-500/20',
                'navHover' => 'hover:bg-lime-500/10 hover:text-lime-200',
                'navIconHover' =>
                    'group-hover:border-lime-500/40 group-hover:bg-lime-500/15 group-hover:text-lime-300',
                'chip' =>
                    'border-lime-500/25 bg-lime-500/10 text-lime-300 hover:bg-lime-500/20 hover:border-lime-500/40',
            ],
            'emerald' => [
                'border' => 'border-emerald-500/25',
                'borderHover' => 'hover:border-emerald-400/50',
                'bg' => 'bg-emerald-500/5',
                'bgIcon' => 'bg-emerald-500/15',
                'text' => 'text-emerald-300',
                'ring' => 'ring-emerald-500/20',
                'shadow' => 'hover:shadow-emerald-500/10',
                'accent' => 'from-emerald-500/20',
                'btn' => 'text-emerald-300 hover:text-emerald-200',
                'btnBg' => 'bg-emerald-500/10 border-emerald-500/25 hover:bg-emerald-500/20',
                'navHover' => 'hover:bg-emerald-500/10 hover:text-emerald-200',
                'navIconHover' =>
                    'group-hover:border-emerald-500/40 group-hover:bg-emerald-500/15 group-hover:text-emerald-300',
                'chip' =>
                    'border-emerald-500/25 bg-emerald-500/10 text-emerald-300 hover:bg-emerald-500/20 hover:border-emerald-500/40',
            ],
            'teal' => [
                'border' => 'border-teal-500/25',
                'borderHover' => 'hover:border-teal-400/50',
                'bg' => 'bg-teal-500/5',
                'bgIcon' => 'bg-teal-500/15',
                'text' => 'text-teal-300',
                'ring' => 'ring-teal-500/20',
                'shadow' => 'hover:shadow-teal-500/10',
                'accent' => 'from-teal-500/20',
                'btn' => 'text-teal-300 hover:text-teal-200',
                'btnBg' => 'bg-teal-500/10 border-teal-500/25 hover:bg-teal-500/20',
                'navHover' => 'hover:bg-teal-500/10 hover:text-teal-200',
                'navIconHover' =>
                    'group-hover:border-teal-500/40 group-hover:bg-teal-500/15 group-hover:text-teal-300',
                'chip' =>
                    'border-teal-500/25 bg-teal-500/10 text-teal-300 hover:bg-teal-500/20 hover:border-teal-500/40',
            ],
            'cyan' => [
                'border' => 'border-cyan-500/25',
                'borderHover' => 'hover:border-cyan-400/50',
                'bg' => 'bg-cyan-500/5',
                'bgIcon' => 'bg-cyan-500/15',
                'text' => 'text-cyan-300',
                'ring' => 'ring-cyan-500/20',
                'shadow' => 'hover:shadow-cyan-500/10',
                'accent' => 'from-cyan-500/20',
                'btn' => 'text-cyan-300 hover:text-cyan-200',
                'btnBg' => 'bg-cyan-500/10 border-cyan-500/25 hover:bg-cyan-500/20',
                'navHover' => 'hover:bg-cyan-500/10 hover:text-cyan-200',
                'navIconHover' =>
                    'group-hover:border-cyan-500/40 group-hover:bg-cyan-500/15 group-hover:text-cyan-300',
                'chip' =>
                    'border-cyan-500/25 bg-cyan-500/10 text-cyan-300 hover:bg-cyan-500/20 hover:border-cyan-500/40',
            ],
            'sky' => [
                'border' => 'border-sky-500/25',
                'borderHover' => 'hover:border-sky-400/50',
                'bg' => 'bg-sky-500/5',
                'bgIcon' => 'bg-sky-500/15',
                'text' => 'text-sky-300',
                'ring' => 'ring-sky-500/20',
                'shadow' => 'hover:shadow-sky-500/10',
                'accent' => 'from-sky-500/20',
                'btn' => 'text-sky-300 hover:text-sky-200',
                'btnBg' => 'bg-sky-500/10 border-sky-500/25 hover:bg-sky-500/20',
                'navHover' => 'hover:bg-sky-500/10 hover:text-sky-200',
                'navIconHover' =>
                    'group-hover:border-sky-500/40 group-hover:bg-sky-500/15 group-hover:text-sky-300',
                'chip' =>
                    'border-sky-500/25 bg-sky-500/10 text-sky-300 hover:bg-sky-500/20 hover:border-sky-500/40',
            ],
            'blue' => [
                'border' => 'border-blue-500/25',
                'borderHover' => 'hover:border-blue-400/50',
                'bg' => 'bg-blue-500/5',
                'bgIcon' => 'bg-blue-500/15',
                'text' => 'text-blue-300',
                'ring' => 'ring-blue-500/20',
                'shadow' => 'hover:shadow-blue-500/10',
                'accent' => 'from-blue-500/20',
                'btn' => 'text-blue-300 hover:text-blue-200',
                'btnBg' => 'bg-blue-500/10 border-blue-500/25 hover:bg-blue-500/20',
                'navHover' => 'hover:bg-blue-500/10 hover:text-blue-200',
                'navIconHover' =>
                    'group-hover:border-blue-500/40 group-hover:bg-blue-500/15 group-hover:text-blue-300',
                'chip' =>
                    'border-blue-500/25 bg-blue-500/10 text-blue-300 hover:bg-blue-500/20 hover:border-blue-500/40',
            ],
            'purple' => [
                'border' => 'border-purple-500/25',
                'borderHover' => 'hover:border-purple-400/50',
                'bg' => 'bg-purple-500/5',
                'bgIcon' => 'bg-purple-500/15',
                'text' => 'text-purple-300',
                'ring' => 'ring-purple-500/20',
                'shadow' => 'hover:shadow-purple-500/10',
                'accent' => 'from-purple-500/20',
                'btn' => 'text-purple-300 hover:text-purple-200',
                'btnBg' => 'bg-purple-500/10 border-purple-500/25 hover:bg-purple-500/20',
                'navHover' => 'hover:bg-purple-500/10 hover:text-purple-200',
                'navIconHover' =>
                    'group-hover:border-purple-500/40 group-hover:bg-purple-500/15 group-hover:text-purple-300',
                'chip' =>
                    'border-purple-500/25 bg-purple-500/10 text-purple-300 hover:bg-purple-500/20 hover:border-purple-500/40',
            ],
            'pink' => [
                'border' => 'border-pink-500/25',
                'borderHover' => 'hover:border-pink-400/50',
                'bg' => 'bg-pink-500/5',
                'bgIcon' => 'bg-pink-500/15',
                'text' => 'text-pink-300',
                'ring' => 'ring-pink-500/20',
                'shadow' => 'hover:shadow-pink-500/10',
                'accent' => 'from-pink-500/20',
                'btn' => 'text-pink-300 hover:text-pink-200',
                'btnBg' => 'bg-pink-500/10 border-pink-500/25 hover:bg-pink-500/20',
                'navHover' => 'hover:bg-pink-500/10 hover:text-pink-200',
                'navIconHover' =>
                    'group-hover:border-pink-500/40 group-hover:bg-pink-500/15 group-hover:text-pink-300',
                'chip' =>
                    'border-pink-500/25 bg-pink-500/10 text-pink-300 hover:bg-pink-500/20 hover:border-pink-500/40',
            ],
            'red' => [
                'border' => 'border-red-500/25',
                'borderHover' => 'hover:border-red-400/50',
                'bg' => 'bg-red-500/5',
                'bgIcon' => 'bg-red-500/15',
                'text' => 'text-red-300',
                'ring' => 'ring-red-500/20',
                'shadow' => 'hover:shadow-red-500/10',
                'accent' => 'from-red-500/20',
                'btn' => 'text-red-300 hover:text-red-200',
                'btnBg' => 'bg-red-500/10 border-red-500/25 hover:bg-red-500/20',
                'navHover' => 'hover:bg-red-500/10 hover:text-red-200',
                'navIconHover' =>
                    'group-hover:border-red-500/40 group-hover:bg-red-500/15 group-hover:text-red-300',
                'chip' =>
                    'border-red-500/25 bg-red-500/10 text-red-300 hover:bg-red-500/20 hover:border-red-500/40',
            ],
            'yellow' => [
                'border' => 'border-yellow-500/25',
                'borderHover' => 'hover:border-yellow-400/50',
                'bg' => 'bg-yellow-500/5',
                'bgIcon' => 'bg-yellow-500/15',
                'text' => 'text-yellow-300',
                'ring' => 'ring-yellow-500/20',
                'shadow' => 'hover:shadow-yellow-500/10',
                'accent' => 'from-yellow-500/20',
                'btn' => 'text-yellow-300 hover:text-yellow-200',
                'btnBg' => 'bg-yellow-500/10 border-yellow-500/25 hover:bg-yellow-500/20',
                'navHover' => 'hover:bg-yellow-500/10 hover:text-yellow-200',
                'navIconHover' =>
                    'group-hover:border-yellow-500/40 group-hover:bg-yellow-500/15 group-hover:text-yellow-300',
                'chip' =>
                    'border-yellow-500/25 bg-yellow-500/10 text-yellow-300 hover:bg-yellow-500/20 hover:border-yellow-500/40',
            ],
        ];
    
    }
    
    #[Computed]
    public function badgeStyles(): ?array
    {
        return [
            'obligatoire' => 'bg-rose-500/15 text-rose-300 border-rose-500/25',
            'recommande' => 'bg-amber-500/15 text-amber-300 border-amber-500/25',
            'optionnel' => 'bg-sky-500/15 text-sky-300 border-sky-500/25',
            'avance' => 'bg-violet-500/15 text-violet-300 border-violet-500/25',
        ];
            
    }
    
    #[Computed]
    public function badgeLabels(): ?array
    {
        return [
            'obligatoire' => 'Obligatoire',
            'recommande' => 'Recommandé',
            'optionnel' => 'Optionnel',
            'avance' => 'Avancé',
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
