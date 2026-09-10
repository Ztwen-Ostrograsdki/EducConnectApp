<?php

/**
 * Citations éducatives, motivationnelles et de développement personnel.
 *
 * Utilisation :
 *   config('citation')
 *   config('citation.citations')
 *   collect(config('citation.citations'))->random()
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Citations
    |--------------------------------------------------------------------------
    |
    | Tableau de citations structurées.
    | Chaque entrée contient : 'text', 'author' (optionnel), 'category'
    |
    */

    'citations' => [

        // ===================== ÉDUCATION =====================
        [
            'text' => 'L\'éducation est l\'arme la plus puissante que l\'on puisse utiliser pour changer le monde.',
            'author' => 'Nelson Mandela',
            'category' => 'education',
        ],
        [
            'text' => 'L\'éducation n\'est pas la préparation à la vie ; l\'éducation est la vie elle-même.',
            'author' => 'John Dewey',
            'category' => 'education',
        ],
        [
            'text' => 'Apprendre sans réfléchir est vain. Réfléchir sans apprendre est dangereux.',
            'author' => 'Confucius',
            'category' => 'education',
        ],
        [
            'text' => 'Le but de l\'éducation est de remplacer un esprit vide par un esprit ouvert.',
            'author' => 'Malcolm Forbes',
            'category' => 'education',
        ],
        [
            'text' => 'L\'ignorance est la racine de tous les maux.',
            'author' => 'Platon',
            'category' => 'education',
        ],
        [
            'text' => 'On ne peut enseigner quelque chose à quelqu\'un, on peut seulement l\'aider à le découvrir en lui.',
            'author' => 'Galilée',
            'category' => 'education',
        ],
        [
            'text' => 'L\'éducation est le mouvement de l\'obscurité à la lumière.',
            'author' => 'Allan Bloom',
            'category' => 'education',
        ],
        [
            'text' => 'Celui qui ouvre une porte d\'école, ferme une prison.',
            'author' => 'Victor Hugo',
            'category' => 'education',
        ],
        [
            'text' => 'L\'instruction est le plus beau des cadeaux que l\'on puisse offrir.',
            'author' => 'Proverbe africain',
            'category' => 'education',
        ],
        [
            'text' => 'Savoir, c\'est pouvoir.',
            'author' => 'Francis Bacon',
            'category' => 'education',
        ],
        [
            'text' => 'L\'éducation commence par le respect de l\'enfant.',
            'author' => 'Maria Montessori',
            'category' => 'education',
        ],
        [
            'text' => 'Un livre ouvert est un cerveau qui parle ; fermé, un ami qui attend ; oublié, une âme qui pardonne ; détruit, un cœur qui pleure.',
            'author' => 'Proverbe hindou',
            'category' => 'education',
        ],
        [
            'text' => 'La lecture est à l\'esprit ce que l\'exercice est au corps.',
            'author' => 'Joseph Addison',
            'category' => 'education',
        ],
        [
            'text' => 'L\'école doit être un lieu où l\'on apprend à aimer apprendre.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],
        [
            'text' => 'Le maître n\'est pas celui qui enseigne, mais celui qui inspire le désir d\'apprendre.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],
        [
            'text' => 'L\'éducation est un droit, pas un privilège.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],
        [
            'text' => 'Investir dans l\'éducation, c\'est investir dans l\'avenir.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],
        [
            'text' => 'Les enfants ne sont pas des vases à remplir, mais des feux à allumer.',
            'author' => 'Rabelais',
            'category' => 'education',
        ],
        [
            'text' => 'L\'éducation est la clé qui ouvre toutes les portes.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],
        [
            'text' => 'Un esprit cultivé est un esprit libre.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],
        [
            'text' => 'Apprendre, c\'est se transformer.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],
        [
            'text' => 'La curiosité est le moteur de l\'apprentissage.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],
        [
            'text' => 'Chaque erreur est une leçon déguisée.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],
        [
            'text' => 'Le savoir s\'acquiert par le travail, non par le hasard.',
            'author' => 'Proverbe arabe',
            'category' => 'education',
        ],
        [
            'text' => 'L\'enseignant qui marche dans l\'ombre de ses élèves a réussi.',
            'author' => 'Anonyme',
            'category' => 'education',
        ],

        // ===================== MOTIVATION =====================
        [
            'text' => 'Le succès n\'est pas final, l\'échec n\'est pas fatal : c\'est le courage de continuer qui compte.',
            'author' => 'Winston Churchill',
            'category' => 'motivation',
        ],
        [
            'text' => 'Croyez en vous et en tout ce que vous êtes. Sachez qu\'il y a quelque chose en vous de plus grand que n\'importe quel obstacle.',
            'author' => 'Christian D. Larson',
            'category' => 'motivation',
        ],
        [
            'text' => 'Ce n\'est pas parce que les choses sont difficiles que nous n\'osons pas, c\'est parce que nous n\'osons pas qu\'elles sont difficiles.',
            'author' => 'Sénèque',
            'category' => 'motivation',
        ],
        [
            'text' => 'Le seul moyen de faire du bon travail est d\'aimer ce que vous faites.',
            'author' => 'Steve Jobs',
            'category' => 'motivation',
        ],
        [
            'text' => 'N\'attendez pas. Le moment que vous attendez n\'arrivera jamais. Commencez là où vous êtes.',
            'author' => 'Anonyme',
            'category' => 'motivation',
        ],
        [
            'text' => 'La discipline est le pont entre les objectifs et les accomplissements.',
            'author' => 'Jim Rohn',
            'category' => 'motivation',
        ],
        [
            'text' => 'Tout ce que l\'esprit d\'un homme peut concevoir et croire, il peut l\'accomplir.',
            'author' => 'Napoleon Hill',
            'category' => 'motivation',
        ],
        [
            'text' => 'Le futur appartient à ceux qui croient en la beauté de leurs rêves.',
            'author' => 'Eleanor Roosevelt',
            'category' => 'motivation',
        ],
        [
            'text' => 'Ne jugez pas chaque jour par la récolte que vous récoltez, mais par les graines que vous plantez.',
            'author' => 'Robert Louis Stevenson',
            'category' => 'motivation',
        ],
        [
            'text' => 'L\'action est la clé fondamentale de tout succès.',
            'author' => 'Pablo Picasso',
            'category' => 'motivation',
        ],
        [
            'text' => 'Vous ne perdez jamais. Soit vous gagnez, soit vous apprenez.',
            'author' => 'Nelson Mandela',
            'category' => 'motivation',
        ],
        [
            'text' => 'La motivation vous fait commencer. L\'habitude vous fait continuer.',
            'author' => 'Jim Ryun',
            'category' => 'motivation',
        ],
        [
            'text' => 'Faites aujourd\'hui ce que les autres ne veulent pas faire, demain vous aurez ce que les autres n\'auront pas.',
            'author' => 'Anonyme',
            'category' => 'motivation',
        ],
        [
            'text' => 'Le chemin vers le succès est toujours en construction.',
            'author' => 'Lily Tomlin',
            'category' => 'motivation',
        ],
        [
            'text' => 'Commencez par faire le nécessaire, ensuite ce qui est possible, et soudain vous ferez l\'impossible.',
            'author' => 'Saint François d\'Assise',
            'category' => 'motivation',
        ],
        [
            'text' => 'Votre seule limite, c\'est vous.',
            'author' => 'Anonyme',
            'category' => 'motivation',
        ],
        [
            'text' => 'Le succès, c\'est d\'aller d\'échec en échec sans perdre son enthousiasme.',
            'author' => 'Winston Churchill',
            'category' => 'motivation',
        ],
        [
            'text' => 'Ne rêve pas ta vie, vis tes rêves.',
            'author' => 'Anonyme',
            'category' => 'motivation',
        ],
        [
            'text' => 'La persévérance transforme l\'échec en accomplissement.',
            'author' => 'Anonyme',
            'category' => 'motivation',
        ],
        [
            'text' => 'Chaque jour est une nouvelle chance de devenir une meilleure version de soi.',
            'author' => 'Anonyme',
            'category' => 'motivation',
        ],
        [
            'text' => 'Le courage n\'est pas l\'absence de peur, mais la capacité de la vaincre.',
            'author' => 'Nelson Mandela',
            'category' => 'motivation',
        ],
        [
            'text' => 'Ce que vous obtenez en atteignant vos objectifs n\'est pas aussi important que ce que vous devenez en les atteignant.',
            'author' => 'Zig Ziglar',
            'category' => 'motivation',
        ],
        [
            'text' => 'La différence entre l\'ordinaire et l\'extraordinaire, c\'est ce petit « extra ».',
            'author' => 'Jimmy Johnson',
            'category' => 'motivation',
        ],
        [
            'text' => 'Ne laissez personne vous dire que vous ne pouvez pas y arriver.',
            'author' => 'Anonyme',
            'category' => 'motivation',
        ],
        [
            'text' => 'Les grandes choses ne sont jamais faites par une seule personne. Elles sont faites par une équipe.',
            'author' => 'Steve Jobs',
            'category' => 'motivation',
        ],
        [
            'text' => 'Le meilleur moment pour planter un arbre était il y a 20 ans. Le deuxième meilleur moment, c\'est maintenant.',
            'author' => 'Proverbe chinois',
            'category' => 'motivation',
        ],
        [
            'text' => 'L\'énergie et la persistance conquièrent toutes choses.',
            'author' => 'Benjamin Franklin',
            'category' => 'motivation',
        ],
        [
            'text' => 'Vous êtes plus fort que vous ne le pensez.',
            'author' => 'Anonyme',
            'category' => 'motivation',
        ],
        [
            'text' => 'Le doute tue plus de rêves que l\'échec.',
            'author' => 'Suzy Kassem',
            'category' => 'motivation',
        ],
        [
            'text' => 'Travaillez dur en silence, laissez votre succès faire du bruit.',
            'author' => 'Frank Ocean',
            'category' => 'motivation',
        ],
        [
            'text' => 'Il n\'y a pas de raccourci vers un endroit qui en vaut la peine.',
            'author' => 'Beverly Sills',
            'category' => 'motivation',
        ],
        [
            'text' => 'La seule personne que vous êtes destiné à devenir est la personne que vous décidez d\'être.',
            'author' => 'Ralph Waldo Emerson',
            'category' => 'motivation',
        ],
        [
            'text' => 'Fais de ta vie un rêve, et d\'un rêve, une réalité.',
            'author' => 'Antoine de Saint-Exupéry',
            'category' => 'motivation',
        ],
        [
            'text' => 'Le succès est la somme de petits efforts répétés jour après jour.',
            'author' => 'Robert Collier',
            'category' => 'motivation',
        ],
        [
            'text' => 'Ne regardez pas l\'horloge ; faites comme elle. Continuez.',
            'author' => 'Sam Levenson',
            'category' => 'motivation',
        ],
        [
            'text' => 'Votre attitude détermine votre altitude.',
            'author' => 'Zig Ziglar',
            'category' => 'motivation',
        ],
        [
            'text' => 'Les obstacles sont ces choses effrayantes que vous voyez lorsque vous détournez les yeux de votre objectif.',
            'author' => 'Henry Ford',
            'category' => 'motivation',
        ],
        [
            'text' => 'La seule façon de faire un excellent travail est d\'aimer ce que vous faites.',
            'author' => 'Steve Jobs',
            'category' => 'motivation',
        ],
        [
            'text' => 'Si vous pouvez le rêver, vous pouvez le faire.',
            'author' => 'Walt Disney',
            'category' => 'motivation',
        ],
        [
            'text' => 'Le génie, c\'est 1 % d\'inspiration et 99 % de transpiration.',
            'author' => 'Thomas Edison',
            'category' => 'motivation',
        ],

        // ===================== DÉVELOPPEMENT PERSONNEL =====================
        [
            'text' => 'Connais-toi toi-même.',
            'author' => 'Socrate',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Le changement n\'est pas douloureux. Seule la résistance au changement l\'est.',
            'author' => 'Anonyme',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Nous ne pouvons pas changer le vent, mais nous pouvons orienter les voiles.',
            'author' => 'Aristote',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La plus grande gloire n\'est pas de ne jamais tomber, mais de se relever à chaque chute.',
            'author' => 'Confucius',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Ce n\'est pas ce qui vous arrive qui compte, c\'est la façon dont vous réagissez.',
            'author' => 'Epictète',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La vie est 10 % ce qui vous arrive et 90 % comment vous y réagissez.',
            'author' => 'Charles R. Swindoll',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Soyez le changement que vous voulez voir dans le monde.',
            'author' => 'Mahatma Gandhi',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La simplicité est la sophistication suprême.',
            'author' => 'Léonard de Vinci',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Celui qui déplace une montagne commence par déplacer de petites pierres.',
            'author' => 'Confucius',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La sagesse commence dans l\'émerveillement.',
            'author' => 'Socrate',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Nous devenons ce que nous pensons le plus.',
            'author' => 'Earl Nightingale',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La gratitude transforme ce que nous avons en suffisance.',
            'author' => 'Anonyme',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Le bonheur n\'est pas quelque chose de prêt à l\'emploi. Il vient de vos propres actions.',
            'author' => 'Dalaï Lama',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Prenez soin de votre corps. C\'est le seul endroit où vous devez vivre.',
            'author' => 'Jim Rohn',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La qualité de votre vie dépend de la qualité de vos pensées.',
            'author' => 'Marc Aurèle',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Lâcher prise ne signifie pas abandonner, cela signifie accepter que certaines choses ne peuvent pas être.',
            'author' => 'Anonyme',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Le silence est parfois la meilleure réponse.',
            'author' => 'Dalaï Lama',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Comparez-vous à qui vous étiez hier, pas à qui quelqu\'un d\'autre est aujourd\'hui.',
            'author' => 'Jordan Peterson',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'L\'échec est simplement l\'opportunité de recommencer, cette fois de manière plus intelligente.',
            'author' => 'Henry Ford',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La confiance en soi ne s\'obtient pas en se regardant dans le miroir, mais en se regardant agir.',
            'author' => 'Anonyme',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Ce que nous plantons dans la solitude, nous le récoltons dans le caractère.',
            'author' => 'Henry David Thoreau',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La discipline est choisir entre ce que vous voulez maintenant et ce que vous voulez le plus.',
            'author' => 'Anonyme',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Vos pensées deviennent vos paroles. Vos paroles deviennent vos actions. Vos actions deviennent vos habitudes. Vos habitudes deviennent vos valeurs. Vos valeurs deviennent votre destin.',
            'author' => 'Mahatma Gandhi',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Le plus grand combat que vous mènerez sera toujours contre vous-même.',
            'author' => 'Anonyme',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La patience est amère, mais son fruit est doux.',
            'author' => 'Jean-Jacques Rousseau',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Celui qui conquiert les autres est fort ; celui qui se conquiert lui-même est puissant.',
            'author' => 'Lao Tseu',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La vie n\'est pas d\'attendre que les orages passent, c\'est d\'apprendre à danser sous la pluie.',
            'author' => 'Sénèque',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Le vrai voyage de découverte ne consiste pas à chercher de nouveaux paysages, mais à avoir de nouveaux yeux.',
            'author' => 'Marcel Proust',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Soyez vous-même ; tous les autres sont déjà pris.',
            'author' => 'Oscar Wilde',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La seule chose qui se dresse entre vous et votre rêve, c\'est la volonté de l\'essayer et la conviction que cela est réellement possible.',
            'author' => 'Joel Brown',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Le bonheur est un parfum que l\'on ne peut répandre sur autrui sans en faire couler quelques gouttes sur soi-même.',
            'author' => 'Ralph Waldo Emerson',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Cultivez votre jardin.',
            'author' => 'Voltaire',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'L\'homme est libre au moment où il veut l\'être.',
            'author' => 'Voltaire',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Le présent est le seul moment où nous pouvons vraiment vivre.',
            'author' => 'Thich Nhat Hanh',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La gentillesse est une langue que les sourds peuvent entendre et que les aveugles peuvent voir.',
            'author' => 'Mark Twain',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Rien n\'est permanent, sauf le changement.',
            'author' => 'Héraclite',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Celui qui n\'a pas de but trouve rarement le chemin.',
            'author' => 'Anonyme',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'La paix intérieure commence au moment où vous choisissez de ne pas laisser une autre personne ou un événement contrôler vos émotions.',
            'author' => 'Anonyme',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Le succès personnel commence par la maîtrise de soi.',
            'author' => 'Anonyme',
            'category' => 'developpement_personnel',
        ],
        [
            'text' => 'Chaque matin, nous renaissons. Ce que nous faisons aujourd\'hui est ce qui compte le plus.',
            'author' => 'Bouddha',
            'category' => 'developpement_personnel',
        ],

        // ===================== TRAVAIL & EXCELLENCE =====================
        [
            'text' => 'L\'excellence n\'est pas un acte, mais une habitude.',
            'author' => 'Aristote',
            'category' => 'travail',
        ],
        [
            'text' => 'Le travail éloigne de nous trois grands maux : l\'ennui, le vice et le besoin.',
            'author' => 'Voltaire',
            'category' => 'travail',
        ],
        [
            'text' => 'Choisissez un travail que vous aimez et vous n\'aurez pas à travailler un seul jour de votre vie.',
            'author' => 'Confucius',
            'category' => 'travail',
        ],
        [
            'text' => 'La qualité n\'est jamais un accident ; c\'est toujours le résultat d\'un effort intelligent.',
            'author' => 'John Ruskin',
            'category' => 'travail',
        ],
        [
            'text' => 'Le détail fait la perfection, et la perfection n\'est pas un détail.',
            'author' => 'Léonard de Vinci',
            'category' => 'travail',
        ],
        [
            'text' => 'Il n\'y a pas de progrès sans effort.',
            'author' => 'Anonyme',
            'category' => 'travail',
        ],
        [
            'text' => 'Le travail d\'équipe divise le travail et multiplie les résultats.',
            'author' => 'Anonyme',
            'category' => 'travail',
        ],
        [
            'text' => 'La productivité n\'est jamais un accident. C\'est toujours le résultat d\'un engagement envers l\'excellence.',
            'author' => 'Paul J. Meyer',
            'category' => 'travail',
        ],
        [
            'text' => 'Faites ce que vous pouvez, avec ce que vous avez, là où vous êtes.',
            'author' => 'Theodore Roosevelt',
            'category' => 'travail',
        ],
        [
            'text' => 'Le professionnalisme, c\'est faire le travail même quand on n\'en a pas envie.',
            'author' => 'Anonyme',
            'category' => 'travail',
        ],
        [
            'text' => 'L\'efficacité, c\'est faire les choses bien. L\'efficience, c\'est faire les bonnes choses.',
            'author' => 'Peter Drucker',
            'category' => 'travail',
        ],
        [
            'text' => 'Le temps est la ressource la plus précieuse. Utilisez-le sagement.',
            'author' => 'Anonyme',
            'category' => 'travail',
        ],
        [
            'text' => 'Un objectif sans plan n\'est qu\'un souhait.',
            'author' => 'Antoine de Saint-Exupéry',
            'category' => 'travail',
        ],
        [
            'text' => 'La concentration est la clé de la réussite.',
            'author' => 'Anonyme',
            'category' => 'travail',
        ],
        [
            'text' => 'Le leadership, c\'est l\'art de faire faire aux autres quelque chose que vous voulez voir fait, parce qu\'ils ont envie de le faire.',
            'author' => 'Dwight D. Eisenhower',
            'category' => 'travail',
        ],

        // ===================== SAGESSE & PHILOSOPHIE =====================
        [
            'text' => 'La seule chose que je sais, c\'est que je ne sais rien.',
            'author' => 'Socrate',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Le vrai bonheur consiste à faire le bien.',
            'author' => 'Aristote',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Nous souffrons plus dans l\'imagination que dans la réalité.',
            'author' => 'Sénèque',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Ce n\'est pas la mort qu\'il faut craindre, mais de ne jamais avoir commencé à vivre.',
            'author' => 'Marc Aurèle',
            'category' => 'sagesse',
        ],
        [
            'text' => 'La liberté, c\'est de ne pas avoir peur.',
            'author' => 'Anonyme',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Celui qui a un « pourquoi » pour vivre peut supporter presque n\'importe quel « comment ».',
            'author' => 'Friedrich Nietzsche',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Le doute est le commencement de la sagesse.',
            'author' => 'Aristote',
            'category' => 'sagesse',
        ],
        [
            'text' => 'La vérité est comme le soleil. Vous pouvez la cacher un moment, mais elle ne disparaît pas.',
            'author' => 'Anonyme',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Mieux vaut allumer une chandelle que de maudire l\'obscurité.',
            'author' => 'Proverbe chinois',
            'category' => 'sagesse',
        ],
        [
            'text' => 'La colère est un poison que l\'on boit en espérant que l\'autre meure.',
            'author' => 'Bouddha',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Ce que l\'on fait pour soi meurt avec soi. Ce que l\'on fait pour les autres reste et est immortel.',
            'author' => 'Albert Pike',
            'category' => 'sagesse',
        ],
        [
            'text' => 'La modestie est le début de la sagesse.',
            'author' => 'Anonyme',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Le temps révèle la vérité.',
            'author' => 'Proverbe',
            'category' => 'sagesse',
        ],
        [
            'text' => 'La connaissance parle, mais la sagesse écoute.',
            'author' => 'Jimi Hendrix',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Celui qui rit de lui-même ne manquera jamais de sujet.',
            'author' => 'Anonyme',
            'category' => 'sagesse',
        ],
        [
            'text' => 'La vraie richesse est d\'être content de ce que l\'on a.',
            'author' => 'Lao Tseu',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Le voyage de mille lieues commence par un pas.',
            'author' => 'Lao Tseu',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Quand l\'élève est prêt, le maître apparaît.',
            'author' => 'Proverbe bouddhiste',
            'category' => 'sagesse',
        ],
        [
            'text' => 'La nature ne se presse pas, pourtant tout est accompli.',
            'author' => 'Lao Tseu',
            'category' => 'sagesse',
        ],
        [
            'text' => 'Le plus grand ennemi de la connaissance n\'est pas l\'ignorance, c\'est l\'illusion de la connaissance.',
            'author' => 'Stephen Hawking',
            'category' => 'sagesse',
        ],

        // ===================== RÉUSSITE SCOLAIRE / ÉTUDIANTS =====================
        [
            'text' => 'Les études ne sont pas un sprint, c\'est un marathon.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Chaque page tournée est une victoire sur l\'ignorance.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'La concentration d\'aujourd\'hui est la réussite de demain.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Un examen n\'est pas une fin, c\'est un passage.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Travaillez comme si tout dépendait de vous. Priez comme si tout dépendait de Dieu.',
            'author' => 'Saint Augustin',
            'category' => 'etudes',
        ],
        [
            'text' => 'La constance bat le talent quand le talent n\'est pas constant.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Réviser, c\'est investir dans sa liberté future.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Les notes ne définissent pas votre valeur, mais l\'effort définit votre caractère.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Un jour de plus d\'effort, c\'est un jour de plus d\'avance.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'La réussite scolaire commence par la discipline personnelle.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Ne comparez pas votre chapitre 1 au chapitre 20 de quelqu\'un d\'autre.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'L\'échec à un examen n\'est pas un échec de vie.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Posez des questions. Les plus grands esprits ont commencé par ne pas savoir.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Le silence de la bibliothèque est le bruit de l\'avenir en construction.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],
        [
            'text' => 'Chaque exercice résolu renforce le muscle de la compréhension.',
            'author' => 'Anonyme',
            'category' => 'etudes',
        ],

        // ===================== ENSEIGNEMENT =====================
        [
            'text' => 'Enseigner, c\'est toucher une vie pour toujours.',
            'author' => 'Anonyme',
            'category' => 'enseignement',
        ],
        [
            'text' => 'Un bon enseignant inspire l\'espoir, allume l\'imagination et instille l\'amour de l\'apprentissage.',
            'author' => 'Anonyme',
            'category' => 'enseignement',
        ],
        [
            'text' => 'Les enseignants ouvrent la porte, mais vous devez entrer vous-même.',
            'author' => 'Proverbe chinois',
            'category' => 'enseignement',
        ],
        [
            'text' => 'L\'enseignement qui laisse une trace n\'est pas celui qui se fait de tête à tête, mais de cœur à cœur.',
            'author' => 'Anonyme',
            'category' => 'enseignement',
        ],
        [
            'text' => 'Chaque élève a une lumière. Le rôle de l\'enseignant est de l\'allumer.',
            'author' => 'Anonyme',
            'category' => 'enseignement',
        ],
        [
            'text' => 'La patience est la qualité première de l\'éducateur.',
            'author' => 'Anonyme',
            'category' => 'enseignement',
        ],
        [
            'text' => 'Un enseignant affecte l\'éternité ; on ne peut jamais dire où s\'arrête son influence.',
            'author' => 'Henry Adams',
            'category' => 'enseignement',
        ],
        [
            'text' => 'L\'éducation est l\'allumage d\'une flamme, pas le remplissage d\'un vase.',
            'author' => 'Socrate',
            'category' => 'enseignement',
        ],
        [
            'text' => 'Les meilleurs enseignants sont ceux qui vous montrent où chercher, mais ne vous disent pas quoi voir.',
            'author' => 'Alexandra K. Trenfor',
            'category' => 'enseignement',
        ],
        [
            'text' => 'Enseigner avec le cœur, c\'est former des êtres, pas seulement des cerveaux.',
            'author' => 'Anonyme',
            'category' => 'enseignement',
        ],

        // ===================== PERSEVERANCE =====================
        [
            'text' => 'La chute n\'est pas un échec. L\'échec, c\'est de rester par terre.',
            'author' => 'Anonyme',
            'category' => 'perseverance',
        ],
        [
            'text' => 'Tombez sept fois, relevez-vous huit.',
            'author' => 'Proverbe japonais',
            'category' => 'perseverance',
        ],
        [
            'text' => 'La rivière coupe la roche non par sa force, mais par sa persistance.',
            'author' => 'James N. Watkins',
            'category' => 'perseverance',
        ],
        [
            'text' => 'Beaucoup de gens abandonnent juste avant d\'atteindre le succès.',
            'author' => 'Anonyme',
            'category' => 'perseverance',
        ],
        [
            'text' => 'La persévérance est plus efficace que le talent.',
            'author' => 'Anonyme',
            'category' => 'perseverance',
        ],
        [
            'text' => 'Continuez. Même quand c\'est difficile. Surtout quand c\'est difficile.',
            'author' => 'Anonyme',
            'category' => 'perseverance',
        ],
        [
            'text' => 'Le succès est souvent juste derrière le moment où l\'on voulait abandonner.',
            'author' => 'Anonyme',
            'category' => 'perseverance',
        ],
        [
            'text' => 'La résilience n\'est pas d\'éviter la tempête, c\'est d\'apprendre à danser sous la pluie.',
            'author' => 'Anonyme',
            'category' => 'perseverance',
        ],
        [
            'text' => 'Chaque « non » vous rapproche d\'un « oui ».',
            'author' => 'Anonyme',
            'category' => 'perseverance',
        ],
        [
            'text' => 'La ténacité transforme les rêves en réalité.',
            'author' => 'Anonyme',
            'category' => 'perseverance',
        ],

        // ===================== CONFIANCE EN SOI =====================
        [
            'text' => 'Croyez en vous, même si personne d\'autre ne le fait.',
            'author' => 'Anonyme',
            'category' => 'confiance',
        ],
        [
            'text' => 'Vous êtes capable de bien plus que vous ne l\'imaginez.',
            'author' => 'Anonyme',
            'category' => 'confiance',
        ],
        [
            'text' => 'La confiance en soi est le premier secret du succès.',
            'author' => 'Ralph Waldo Emerson',
            'category' => 'confiance',
        ],
        [
            'text' => 'Ne demandez pas la permission d\'être grand.',
            'author' => 'Anonyme',
            'category' => 'confiance',
        ],
        [
            'text' => 'Votre valeur ne diminue pas parce que quelqu\'un n\'est pas capable de la voir.',
            'author' => 'Anonyme',
            'category' => 'confiance',
        ],
        [
            'text' => 'Osez. Même si vous avez peur.',
            'author' => 'Anonyme',
            'category' => 'confiance',
        ],
        [
            'text' => 'La timidité disparaît avec l\'action.',
            'author' => 'Anonyme',
            'category' => 'confiance',
        ],
        [
            'text' => 'Vous n\'avez pas besoin d\'être parfait pour commencer. Vous avez besoin de commencer pour devenir meilleur.',
            'author' => 'Anonyme',
            'category' => 'confiance',
        ],
        [
            'text' => 'La voix intérieure qui dit « tu peux » est plus forte que celle qui dit « tu ne peux pas ».',
            'author' => 'Anonyme',
            'category' => 'confiance',
        ],
        [
            'text' => 'Marchez comme si vous saviez déjà où vous allez.',
            'author' => 'Anonyme',
            'category' => 'confiance',
        ],

        // ===================== TEMPS & HABITUDES =====================
        [
            'text' => 'Le temps perdu ne se rattrape jamais.',
            'author' => 'Benjamin Franklin',
            'category' => 'temps',
        ],
        [
            'text' => 'Nous sommes ce que nous répétons chaque jour. L\'excellence n\'est donc pas un acte, mais une habitude.',
            'author' => 'Aristote',
            'category' => 'temps',
        ],
        [
            'text' => 'Les petites habitudes font les grandes transformations.',
            'author' => 'Anonyme',
            'category' => 'temps',
        ],
        [
            'text' => 'Aujourd\'hui est le lendemain que vous avez reporté hier.',
            'author' => 'Anonyme',
            'category' => 'temps',
        ],
        [
            'text' => 'La procrastination est le voleur du temps.',
            'author' => 'Edward Young',
            'category' => 'temps',
        ],
        [
            'text' => 'Une heure d\'effort concentré vaut mieux que trois heures de distraction.',
            'author' => 'Anonyme',
            'category' => 'temps',
        ],
        [
            'text' => 'Le matin décide souvent de la qualité de la journée.',
            'author' => 'Anonyme',
            'category' => 'temps',
        ],
        [
            'text' => 'Ce que vous faites chaque jour compte plus que ce que vous faites de temps en temps.',
            'author' => 'Gretchen Rubin',
            'category' => 'temps',
        ],
        [
            'text' => 'Les habitudes sont les intérêts composés du développement personnel.',
            'author' => 'James Clear',
            'category' => 'temps',
        ],
        [
            'text' => 'Maîtrisez votre temps, ou quelqu\'un d\'autre le fera pour vous.',
            'author' => 'Anonyme',
            'category' => 'temps',
        ],

        // ===================== DIVERS / INSPIRATION =====================
        [
            'text' => 'La vie est trop courte pour être petite.',
            'author' => 'Benjamin Disraeli',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Là où il y a une volonté, il y a un chemin.',
            'author' => 'Proverbe',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le soleil brille même derrière les nuages.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Chaque fin est un nouveau départ.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La lumière que vous cherchez est en vous.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Ne baissez jamais les bras. Le miracle arrive souvent au dernier moment.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le monde a besoin de votre lumière. Ne l\'éteignez pas.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Soyez la raison pour laquelle quelqu\'un croit encore en la bonté.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Les étoiles ne brillent que dans le noir.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Votre histoire n\'est pas encore terminée.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le courage commence par le premier pas.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Rien de grand ne s\'est accompli sans passion.',
            'author' => 'Georg Wilhelm Friedrich Hegel',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La vie récompense l\'action, pas l\'intention.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Ce qui compte, ce n\'est pas la destination, c\'est le chemin.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'espoir est le rêve de l\'homme éveillé.',
            'author' => 'Aristote',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La joie se trouve dans le voyage, pas dans l\'arrivée.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Un sourire est le commencement de la paix.',
            'author' => 'Mère Teresa',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La beauté commence au moment où vous décidez d\'être vous-même.',
            'author' => 'Coco Chanel',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le monde change avec votre exemple, pas avec votre opinion.',
            'author' => 'Paulo Coelho',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Donnez le meilleur de vous-même. Le reste suivra.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La générosité est la marque des grands esprits.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Aimez ce que vous faites, et vous n\'aurez jamais à travailler.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le respect s\'obtient par les actes, pas par les titres.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La simplicité est la clé de la clarté.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Ce que vous donnez revient toujours multiplié.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La paix commence par un sourire.',
            'author' => 'Mère Teresa',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Vivre, c\'est choisir. Choisir, c\'est grandir.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le meilleur investissement, c\'est en soi-même.',
            'author' => 'Warren Buffett',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La curiosité est le début de tout savoir.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Un esprit ouvert capture les opportunités.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La gratitude multiplie les bonnes choses.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le silence est parfois la plus belle des réponses.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'humilité attire, l\'orgueil repousse.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le caractère se forge dans l\'adversité.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Rien n\'est impossible à celui qui essaie.',
            'author' => 'Alexandre le Grand',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La volonté peut déplacer des montagnes.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Chaque jour est une page blanche. Écrivez bien.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le succès aime la préparation.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'effort d\'aujourd\'hui est la fierté de demain.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Ne sous-estimez jamais le pouvoir d\'un nouveau départ.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La discipline personnelle est la liberté véritable.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Celui qui lit beaucoup vit plusieurs vies.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'apprentissage ne s\'arrête jamais.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le savoir partagé multiplie sa valeur.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Un mentor accélère le chemin.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La vision sans action est un rêve. L\'action sans vision est un cauchemar.',
            'author' => 'Proverbe japonais',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le leadership commence par l\'exemple.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Les grandes destinées commencent par de petites décisions.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La passion allume, la discipline entretient.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Vous n\'avez pas à être le meilleur. Vous avez à être vous, en mieux.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le respect de soi est le fondement de tout respect.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La clarté d\'intention précède la force d\'exécution.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Ce que vous nourrissez grandit. Choisissez bien.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'avenir appartient à ceux qui se préparent aujourd\'hui.',
            'author' => 'Malcolm X',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Un pas après l\'autre, et la montagne devient accessible.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La réussite se construit dans le silence des efforts quotidiens.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le doute retarde. La décision avance.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Votre environnement influence votre évolution. Choisissez-le.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La cohérence est plus forte que la motivation passagère.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le progrès, pas la perfection.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Chaque compétence a commencé par l\'incompétence.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'apprentissage actif bat la mémorisation passive.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La question est plus importante que la réponse.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'échec intelligent est un investissement.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La focus est une superpuissance rare.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le feedback est un cadeau. Acceptez-le.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La croissance commence hors de la zone de confort.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Un objectif écrit a plus de chance d\'être atteint.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La lecture est un voyage sans bouger.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le mentorat accélère l\'expérience.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La créativité naît de la contrainte parfois.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le respect du temps des autres est du respect de soi.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La communication claire évite les conflits inutiles.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'écoute active est une forme d\'intelligence.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La gratitude quotidienne change la perception du monde.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le sommeil est un pilier de la performance.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le mouvement du corps nourrit l\'esprit.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La solitude choisie recharge. L\'isolement subit épuise.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Les relations de qualité sont un capital précieux.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le service aux autres donne un sens durable.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La vision long terme guide les choix du jour.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'intégrité est faire le bien même quand personne ne regarde.',
            'author' => 'C.S. Lewis',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le caractère se révèle dans les petits gestes.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La constance bat l\'intensité sporadique.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Chaque expert a été débutant.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le progrès est invisible au quotidien, évident dans le temps.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La responsabilité personnelle est le début de la liberté.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le courage d\'essayer vaut mieux que le regret de n\'avoir pas osé.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La clarté précède la maîtrise.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Un environnement ordonné favorise un esprit clair.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La bienveillance envers soi accélère la croissance.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le doute méthodique est un outil, le doute paralysant un frein.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La curiosité intellectuelle est un trésor inépuisable.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le savoir appliqué devient sagesse.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La pratique délibérée transforme le potentiel en compétence.',
            'author' => 'Anders Ericsson',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le feedback honnête est rare et précieux.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La patience stratégique n\'est pas de la passivité.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'énergie suit l\'attention. Dirigez-la bien.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Un bon système bat la motivation isolée.',
            'author' => 'James Clear',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La fin de quelque chose est souvent le début de mieux.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le respect des processus produit des résultats durables.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La simplicité dans l\'exécution révèle la profondeur de la compréhension.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Chaque jour offre une occasion de progresser d\'un millimètre.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le vrai luxe, c\'est d\'avoir du temps pour ce qui compte.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La maîtrise commence par l\'humilité d\'apprendre.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Les petites victoires construisent la grande confiance.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le présent est le seul terrain de jeu disponible.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La discipline est l\'amour de soi en action.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Un objectif clair attire les ressources nécessaires.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La lecture quotidienne est un compound interest pour l\'esprit.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le silence intérieur permet d\'entendre l\'essentiel.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La cohérence entre les valeurs et les actes produit la paix intérieure.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le progrès mesurable motive plus que les intentions vagues.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La qualité de vos questions détermine la qualité de votre vie.',
            'author' => 'Tony Robbins',
            'category' => 'inspiration',
        ],
        [
            'text' => 'L\'apprentissage permanent est le meilleur avantage compétitif.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La bienveillance n\'est pas une faiblesse. C\'est une force rare.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le temps investi en soi n\'est jamais perdu.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La clarté d\'esprit précède la clarté d\'action.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Chaque obstacle cache une leçon. Cherchez-la.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le respect de la routine construit la liberté créative.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La réussite est un sous-produit de l\'engagement quotidien.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Un esprit discipliné est un esprit libre.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'La croissance demande du temps. Accordez-le-vous.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
        [
            'text' => 'Le meilleur moment pour commencer était hier. Le suivant, c\'est maintenant.',
            'author' => 'Anonyme',
            'category' => 'inspiration',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Catégories disponibles
    |--------------------------------------------------------------------------
    */
    'categories' => [
        'education'               => 'Éducation',
        'motivation'              => 'Motivation',
        'developpement_personnel' => 'Développement personnel',
        'travail'                 => 'Travail & excellence',
        'sagesse'                 => 'Sagesse & philosophie',
        'etudes'                  => 'Études & réussite scolaire',
        'enseignement'            => 'Enseignement',
        'perseverance'            => 'Persévérance',
        'confiance'               => 'Confiance en soi',
        'temps'                   => 'Temps & habitudes',
        'inspiration'             => 'Inspiration',
    ],

];
