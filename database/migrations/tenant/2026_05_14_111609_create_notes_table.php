<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('enseignant_id')->nullable()->constrained('enseignants')->nullOnDelete();
            $table->integer('periode');                         // 1, 2 (semestre) ou 1, 2, 3 (trimestre)
            $table->enum('type_note', [
                'interro1',
                'interro2',
                'interro3',
                'interro4',
                'interro5',
                'interro6',
                'compo',
                'devoir1',
                'devoir2',
                'devoir3',
                'examen',
            ]);
            $table->decimal('value', 5, 2);                    // valeur de la note
            $table->text('commentaire')->nullable();
            $table->boolean('editable')->default(true);     // devient false après délai
            $table->timestamp('locked_at')->nullable();         // date de verrouillage
            $table->foreignId('locked_by')->nullable()          // qui a verrouillé
                  ->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Un élève ne peut avoir qu'une note de chaque type par matière/période/année
            $table->unique([
                'eleve_id', 'matiere_id', 'annee_scolaire_id',
                'periode', 'type_note'
            ], 'note_unique');

            $table->index(['eleve_id', 'annee_scolaire_id', 'periode']);
            $table->index(['classe_id', 'matiere_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
