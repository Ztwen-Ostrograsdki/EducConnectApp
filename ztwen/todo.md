Structure Dashboard Tenant Responsive Mode Sombre

##### Important : Utilisation des TailwindCss et Motion en tenant compte des configurations app.js et app.css

---

## 1. Architecture Globale & Design System (Mode Sombre)

### 1.1 Palette de Couleurs (Dark Mode Palette)

Pour un mode sombre reposant et professionnel, évitez le noir pur (`#000000`). Privilégiez des nuances de gris foncé, de bleu nuit ou d'ardoise :

- **Fond principal (App Background) :** `Slate-900` (`#0f172a`) ou `Zinc-950` (`#09090b`)
- **Surfaces et Cartes (Card/Surface Background) :** `Slate-800` (`#1e293b`) ou `Zinc-900` (`#18181b`)
- **Bordures et Séparateurs :** `Slate-700` (`#334155`) ou `Zinc-800` (`#27272a`)
- **Texte Principal :** `Slate-100` (`#f1f5f9`) - Haute lisibilité
- **Texte Secondaire :** `Slate-400` (`#94a3b8`) - Pour les légendes et labels
- **Couleur d'Accent principale :** Indigo (`#6366f1`), Violet (`#8b5cf6`) ou Émeraude (`#10b981`) pour les états de succès.

### 1.2 Grille et Layout Responsive

- **Structure globale :** Layout en grille (Grid) ou Flexbox avec deux zones majeures : la barre latérale (Sidebar) et le conteneur principal (qui englobe la Navbar et la zone de contenu).
- **Points de rupture (Breakpoints) :**
    - `Mobile (< 768px)` : Sidebar masquée par défaut (mode tiroir/drawer), Navbar simplifiée, contenu sur 1 colonne.
    - `Tablette (768px - 1024px)` : Sidebar compacte (icônes uniquement) ou masquée, contenu sur 1 à 2 colonnes.
    - `Desktop (> 1024px)` : Sidebar pleinement visible et fixe, Navbar complète, contenu multi-colonnes (grille fluide)

---

## 2. La Barre de Navigation Supérieure (Navbar)

La Navbar reste fixe en haut de l'écran et s'adapte à la largeur du viewport.

### 2.1 Éléments à Gauche

- **Bouton Menu (Hambourg) :** Visible uniquement sur mobile et tablette. Déclenche l'ouverture de la Sidebar en mode _Drawer_ (coulissant).
- **Fil d'Ariane (Breadcrumbs) ou Titre Dynamique :** Indique la section actuelle (ex: _Dashboard > Analyses / Ventes_). Masqué sur très petit écran.
- **Barre de Recherche :**
    - _Desktop_ : Champ de recherche textuel étendu avec raccourci clavier mis en avant (ex: `Ctrl + K`).
    - _Mobile_ : Simple icône de loupe qui ouvre une modal de recherche plein écran au clic.

### 2.2 Éléments à Droite

- **Sélecteur de Langue / Version :** Menu déroulant discret (icône globe ou drapeau + texte court).
- **Sélecteur de Thème (Light/Dark Toggle) :** Icône Lune/Soleil pour basculer manuellement le thème.
- **Centre de Notifications :**
    - Icône de cloche avec un badge rouge dynamique (nombre de notifications non lues).
    - Au clic : Menu déroulant (Dropdown) affichant les 5 dernières notifications avec un bouton "Voir tout".
- **Profil Utilisateur (User Dropdown) :**
    - Avatar rond de l'utilisateur + Nom/Rôle (Nom masqué sur mobile).
    - Au clic, un menu déroulant contenant :
        - Mon Profil
        - Paramètres du compte
        - Support / Aide
        - _Séparateur visuel_
        - Déconnexion (Texte en couleur destructive, ex: Rouge/Rose).

---

## 3. La Barre Latérale de Navigation (Sidebar)

La Sidebar gère l'accès aux différentes fonctionnalités de l'application.

### 3.1 Comportement et États

- **Desktop :** Fixe à gauche, largeur constante (ex: `260px`). Option de réduction (Collapse) pour ne garder que les icônes (`80px`).
- **Mobile :** Masquée hors écran (`transform: translateX(-100%)`). À l'activation, elle glisse au-dessus du contenu avec un arrière-plan semi-transparent (Overlay/Backdrop) pour flouter le reste de l'application.

### 3.2 Structure Interne

1.  **En-tête (Brand Section) :**
    - Logo de l'application + Nom de la marque.
    - Bouton de fermeture rapide (uniquement sur mobile).
2.  **Navigation Principale (Liens groupés) :**
    - Les liens sont organisés par catégories logiques (ex: _Général, Gestion, Rapports_).
    - Chaque ligne contient : Une icône vectorielle (SVG) + Un libellé textuel + (Optionnel) Un badge d'état (ex: "New", "3").
    - _État Actif_ : Le lien de la page courante se distingue par un fond légèrement plus clair (`Slate-700`), une bordure gauche colorée (couleur d'accent) et un texte plus brillant.
3.  **Menus Imbriqués (Dropdowns de navigation) :**
    - Possibilité d'avoir des sous-sections fléchées qui se déploient vers le bas en accordéon.
4.  **Pied de la Sidebar (Footer Section) :**
    - Rappel rapide de l'utilisateur connecté ou interrupteur de fin de session.
    - Indicateur de version de l'application (ex: `v1.2.0`).

---

## 4. Les Sections Principales du Contenu (Main Content Area)

Le contenu s'organise de manière fluide à l'intérieur d'un conteneur avec un défilement vertical (Scroll) indépendant.
La section à afficher depend de la section active dans la sidebar. par defaut on aura une section statistique sur le nombre d'eleves, d'enseigants, de parents d'eleves ....
Par exemple si dans la section je clique sur classe -> sous menu 1ère F2-1 alors la section chargera le profil de la classe avec les details complets: liste apprenants, liste enseigants, emploi du temps.... Possibilité d'ajout d'enseigants ou d'éleves, ou enrollement de parents. si dans cette section on clique sur les notes alors la page (chargée la route classe_id/notes/) des notes sera chargée en fonction de la matière selectionnée en <select> pareil si on clique sur enseigants
chaque section selectionnée chargera une route précise.

# la partie note necessitera une select semestre/trimestre

### 4.1 En-tête de Page (Page Header)

- Titre principal de la page (H1) et description succincte.
- Zone d'actions contextuelles (ex: Bouton "Exporter en PDF", "Ajouter un utilisateur", Filtre de date global).

### 4.2 Zone des Indicateurs Clés (KPI Cards Grid)

- Disposition en grille responsive : `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`.
- Chaque carte comprend :
    - Un titre d'indicateur (ex: _Chiffre d'Affaires_).
    - La valeur principale en grand format numérique.
    - Une icône contextuelle en haut à droite en opacité réduite.
    - Un indicateur de tendance : Flèche vers le haut (Vert) ou vers le bas (Rouge) accompagné du pourcentage de variation.

### 4.3 Zone des Graphiques (Charts Section)

- Structure responsive : Écrans larges = 2 graphiques côte à côte (ex: Évolution des ventes sur 2/3 de la largeur, Répartition des catégories sur 1/3). Écrans mobiles = Empilés verticalement.
- Composants : Graphique linéaire (Line Chart), Histogramme (Bar Chart) ou Graphique en anneau (Donut Chart).
- _Note Dark Mode_ : Les grilles des graphiques doivent être très discrètes (`Slate-700`) et les infobulles (Tooltips) stylisées avec le fond de carte sombre.

### 4.4 Tableaux de Données et Listes Récentes (Data Section)

- **Tableau Responsive :** Sur mobile, le tableau doit soit obtenir un scroll horizontal (`overflow-x: auto`), soit se transformer en une liste de cartes individuelles empilées.
- **Fonctionnalités intégrées :**
    - Barre d'outils du tableau : Filtres rapides, tri par colonne, sélecteur du nombre de lignes.
    - Pagination : Boutons Précédent / Suivant et indicateur textuel (ex: _Affichage 1 à 10 sur 150_).

---

## 5. Le Panneau des Paramètres (Settings View)

Cette section dédiée est souvent structurée avec des onglets verticaux (sur Desktop) ou horizontaux (sur Mobile).

### 5.1 Onglet 1 : Profil & Compte

- **Gestion de l'Avatar :** Zone de glisser-déposer (Drag & Drop) pour téléverser une image avec prévisualisation en direct.
- **Informations Personnelles :** Formulaire incluant des champs de saisie (inputs) stylisés pour le mode sombre (fond sombre, bordure grise, focus avec anneau de couleur d'accent).
    - Champs : Nom complet, Adresse Email, Numéro de téléphone, Profession/Rôle.

### 5.2 Onglet 2 : Sécurité & Authentification

- **Changement de mot de passe :** Champs pour le mot de passe actuel, le nouveau mot de passe et la confirmation.
- **Double Authentification (2FA) :** Interrupteur (Toggle switch) pour activer/désactiver, affichage du QR Code de configuration et des codes de secours.
- **Sessions Actives :** Liste des appareils et navigateurs connecteurs connectés avec option de déconnexion à distance.

### 5.3 Onglet 3 : Préférences de l'Interface

- **Gestion du Thème :** Options radio ou cartes cliquables pour sélectionner : _Mode Clair_, _Mode Sombre_, ou _Synchronisation avec le système_.
- **Densité d'affichage :** Choix entre un mode "Compact" (Moins de paddings, idéal pour les experts de données) ou "Spacieux" (Plus d'espace blanc).
- **Langue par Défaut :** Menu de sélection de la langue principale de l'application.

### 5.4 Onglet 4 : Notifications & Alertes

- **Alertes par Email :** Cases à cocher pour définir les rapports reçus (Hebdomadaire, Mensuel, Immédiat).
- **Notifications Push (In-App) :** Gestion fine des événements déclencheurs (ex: _Nouvelle commande_, _Commentaire reçu_, _Alerte sécurité_).

### 5.5 Onglet 5 : Intégrations & Développeurs

- **Gestion des clés API :** Tableau listant les clés API générées, leurs permissions (Read/Write) et un bouton pour révoquer ou générer une nouvelle clé.
- **Webhooks :** Configuration des URLs de destination pour les événements sortants.

---

## 6. Bonnes Pratiques de Développement (Tips de Stack)

- **Tailwind CSS :** Utilisez systématiquement le préfixe `dark:` pour appliquer les styles spécifiques au mode sombre (ex: `bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100`). Assurez-vous que la configuration `darkMode` est réglée sur `'class'` ou `'media'` dans votre fichier `tailwind.config.js`.
- **Accessibilité (a11y) :** Vérifiez que le contraste entre la couleur du texte et le fond sombre respecte les normes WCAG AA (ratio minimal de 4.5:1).
- **Transitions fluides :** Ajoutez une transition douce sur les changements de couleurs lors du basculement de thème (`transition-colors duration-200`).
- **Gestion du State :** Conservez la préférence de thème de l'utilisateur dans le `localStorage` ou via un cookie de session pour éviter l'effet de flash blanc au rechargement de la page.
  structure_dashboard_responsive.md
  Affichage de structure_dashboard_responsive.md.
