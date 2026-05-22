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
        Schema::create('yearly_classe_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained('school_years');
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('Approuvé');
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable()->default(null);
            $table->timestamps();
            $table->unique([
                'student_id',
                'school_year_id'
            ]);

        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('yearly_classe_students');
        Schema::enableForeignKeyConstraints();
    }
};
