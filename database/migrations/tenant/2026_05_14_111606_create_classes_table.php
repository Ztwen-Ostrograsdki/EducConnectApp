<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->cascadeOnDelete();
            $table->foreignId('promotion_id')->constrained('promotions');
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->nullOnDelete();
            $table->foreignId('serie_id')->nullable()->constrained('series')->nullOnDelete();
            $table->string('name');                                      // ex: Terminale BTP 2
            $table->string('code')->nullable();                         // ex: TLE-BTP-2
            $table->enum('niveau', ['primaire', 'secondaire', 'superieur']);
            $table->integer('effectif_max')->default(50);
            $table->foreignId('principal_id')
                  ->nullable()
                  ->constrained('enseignants')
                  ->nullOnDelete();
            $table->foreignId('respo_1_id')                       // apprenant responsable
                  ->nullable()
                  ->constrained('eleves')
                  ->nullOnDelete();
            $table->foreignId('respo_2_id')                       // apprenant responsable
                  ->nullable()
                  ->constrained('eleves')
                  ->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_locked')->default(false); // on pourra empêcher l'accès aux enseignants momentanement
            $table->json('locked_for_enseignants')->default(null); // un tableau des id des enseignant sur lequels on pourra empêcher l'accès à certains enseignants momentanement
            $table->timestamps();
            $table->softDeletes();

            // Une classe ne peut pas avoir le même nom dans la même année
            $table->unique(['annee_scolaire_id', 'nom']);
            $table->index(['annee_scolaire_id', 'promotion_id']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
