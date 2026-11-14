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
        Schema::create('module_skills', function (Blueprint $table) {
            $table->id();
            $table->string('weight')->nullable()->default(null);
            $table->unsignedInteger('coef')->nullable()->default(null);
            $table->string('code')->nullable()->default(null);
            $table->boolean('is_active')->default(true);
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('module_skills');
        Schema::enableForeignKeyConstraints();
    }
};
