Nous allons a présent définir le composant de gestion des classes : défintion des profs par matière d'une classe donnée

le principe :

dans chaque classe un prof peut avoir plusieurs matieres
si dans la classe, il exise deja un prof qui execute deja cette matiere dans la classe alors un autre prof ne peut pas etre lié a cette matière dans la meme classe

la table du model (ClasseSubjectOfSchoolYear::class) qui gere la relation teacher subject classe school_year

Schema::create('classe_subject_of_school_years', function (Blueprint $table) {
$table->id();
$table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
$table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
$table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
$table->foreignId('school_year_id')->constrained('school_years')->cascadeOnDelete();
$table->decimal('coefficient', 4, 2)->default(1);
$table->boolean('is_active')->default(true);

    // ─── Remplacement ─────────────────────────────────────────
    $table->timestamp('started_at')->nullable();    // début de l'enseignement
    $table->timestamp('ended_at')->nullable();      // null = enseignant actuel
    $table->string('replacement_reason')->nullable(); // motif du remplacement
    $table->foreignId('replaced_by')->nullable()    // qui a enregistré le remplacement
    	->constrained('users')->nullOnDelete();

    $table->timestamps();

    // Pas d'unique — géré par logique métier (ended_at = null = actuel)

    $table->index(['classe_id', 'subject_id', 'school_year_id'], 'cssy_classe_subject_year_idx');
    $table->index(['teacher_id', 'school_year_id']);
    $table->index('ended_at');                       // pour filtrer les actifs rapidement

});

Le composant est chargé depuis une classe: donc la route comporte classe_slug

on liste ensuite les matières dont le level est le meme que celui de la classe

on selectionne une matière => les prof de cette matière sont ensuite listés => on slectionne le prof au choix

on aura une section qui affiche les matières déjà définie et les prof choisie avec des boutton action pour les retirer (on met alors à lour ended_at et la raison on met juste 'remaniement d'emploi du temps')

Utilise des wire:loading pour les chargement lors des actions et lors des selections
