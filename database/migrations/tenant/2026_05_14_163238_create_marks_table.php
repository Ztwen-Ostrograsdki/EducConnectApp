<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained('school_years');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->integer('period');                         // 1, 2 (semestre) ou 1, 2, 3 (trimestre)
            $table->enum('mark_type', [
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
            $table->string('comments')->nullable();
            $table->boolean('editable')->default(true);     // devient false après délai
            $table->boolean('validated')->default(true);
            $table->timestamp('locked_at')->nullable();         // date de verrouillage
            $table->foreignId('locked_by')->nullable()          // qui a verrouillé
                ->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Un élève ne peut avoir qu'une note de chaque type par matière/période/année
            $table->unique([
                'student_id', 'subject_id', 'school_year_id',
                'period', 'mark_type',
            ], 'uniq_mark');

            $table->index(['student_id', 'school_year_id', 'period']);
            $table->index(['classe_id', 'subject_id', 'period']);
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('marks');
        Schema::enableForeignKeyConstraints();
    }
};
