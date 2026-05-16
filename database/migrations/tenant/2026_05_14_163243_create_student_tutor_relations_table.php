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
        Schema::create('student_tutor_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sutudent_id')->constrained('sutudents')->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained('tutors')->cascadeOnDelete();
            $table->string('parent_relation')->default('tutor');
            $table->boolean('is_primary_contact')->default(false);
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->boolean('locked')->default(false);
            $table->unique(['sutudent_id', 'tutor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_tutor_relations');
    }
};
