<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot : inscription d'un élève dans une classe pour une année
        Schema::create('classe_eleve', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->date('date_inscription')->nullable();
            $table->enum('statut', ['actif', 'abandonne', 'transfere', 'exclu'])->default('actif');
            $table->text('motif')->nullable();                  // motif abandon/exclusion
            $table->timestamps();

            // Un élève ne peut être qu'une fois dans une classe par année
            $table->unique(['classe_id', 'eleve_id', 'annee_scolaire_id']);
            $table->index(['annee_scolaire_id', 'eleve_id']);
            $table->index('statut');
        });

        // Pivot : lien entre élève et tuteur
        Schema::create('eleve_tuteur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->foreignId('tuteur_id')->constrained('tuteurs')->cascadeOnDelete();
            $table->boolean('est_contact_principal')->default(false);
            $table->timestamps();

            $table->unique(['eleve_id', 'tuteur_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleve_tuteur');
        Schema::dropIfExists('classe_eleve');
    }
};
