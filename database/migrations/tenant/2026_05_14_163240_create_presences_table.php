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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained('school_years');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'retard', 'excuse'])->default('present');
            $table->string('reason')->nullable()->default(null);                  // motif absence/retard
            $table->boolean('tutor_advised')->default(false);  // tuteur notifié ?
            $table->timestamp('tutor_advised_at')->nullable();
            $table->timestamps();
            // Un élève ne peut avoir qu'une présence par jour par matière
            $table->unique(['student_id', 'classe_id', 'date', 'subject_id'], 'presence_unique');
            $table->index(['classe_id', 'date']);
            $table->index(['student_id', 'school_year_id']);
            $table->index('status');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('presences');
        Schema::enableForeignKeyConstraints();
    }
};
