<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('enseignant_id')->nullable()->constrained('enseignants')->nullOnDelete();
            $table->foreignId('matiere_id')->nullable()->constrained('matieres')->nullOnDelete();
            $table->date('date');
            $table->enum('statut', ['present', 'absent', 'retard', 'excuse'])->default('present');
            $table->text('motif')->nullable();                  // motif absence/retard
            $table->boolean('notifie_tuteur')->default(false);  // tuteur notifié ?
            $table->timestamp('notifie_at')->nullable();
            $table->timestamps();

            // Un élève ne peut avoir qu'une présence par jour par matière
            $table->unique(['eleve_id', 'classe_id', 'date', 'matiere_id'], 'presence_unique');
            $table->index(['classe_id', 'date']);
            $table->index(['eleve_id', 'annee_scolaire_id']);
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
