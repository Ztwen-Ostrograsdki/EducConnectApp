<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des matières
        Schema::create('matieres', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');                              // ex: Mathématiques
            $table->string('code')->nullable();                 // ex: MATH
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
            $table->index('is_active');
        });

        // Table pivot : une matière peut être dans plusieurs classes
        // avec un coefficient différent par classe
        Schema::create('classe_matiere', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->foreignId('enseignant_id')->nullable()->constrained('enseignants')->nullOnDelete();
            $table->decimal('coefficient', 4, 2)->default(1);  // coefficient de la matière dans la classe
            $table->timestamps();

            $table->unique(['classe_id', 'matiere_id']);        // pas de doublon
            $table->index(['classe_id', 'enseignant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classe_matiere');
        Schema::dropIfExists('matieres');
    }
};
