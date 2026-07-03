Structure de base
<type>(<scope>): <description>

type : la nature du changement (obligatoire)
scope : la partie du code concernée, entre parenthèses (optionnel mais très utile chez toi vu le nombre de modules)
description : à l'impératif, courte, sans majuscule au début, sans point final

Les types principaux
TypeQuand l'utiliserfeatNouvelle fonctionnalitéfixCorrection de bugrefactorRéorganisation du code sans changement de comportementstyleFormatage, indentation, rien de fonctionnel (ex: ton passage Tailwind sur une ligne)docsDocumentationtestAjout/modification de testschoreConfig, dépendances, scripts (.bat, composer.json...)perfAmélioration de performance
Exemples adaptés à ton projet actuel
bashgit commit -m "feat(subject): bloque la suppression si assignée à un prof"
git commit -m "fix(broadcast): corrige la redirection 302 sur /broadcasting/auth"
git commit -m "refactor(school-year): remplace #[Computed] par une propriété publique"
git commit -m "feat(teacher): ajoute le composant d'assignation inverse classe-prof"
git commit -m "style(blade): compacte les classes Tailwind sur une ligne"
git commit -m "chore(queue): configure Redis avec DB séparées par usage"
git commit -m "fix(tenancy): corrige TenantCouldNotBeIdentifiedById sur les jobs"
Si le commit casse la compatibilité (breaking change)
bashgit commit -m "feat(tenancy)!: change la structure de QueueTenancyBootstrapper"
