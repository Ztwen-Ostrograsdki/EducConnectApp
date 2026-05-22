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
        Schema::disableForeignKeyConstraints();
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
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('classe_subject_of_school_years');
        Schema::enableForeignKeyConstraints();
    }
};
