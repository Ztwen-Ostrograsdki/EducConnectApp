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
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->string('slug')->unique();
            $table->integer('min_year')->unique();
            $table->integer('max_year')->unique();
            $table->enum('periode_type', ['semestre', 'trimestre'])->default('semestre');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_current_school_year')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->string('locked_for_period')->nullable();
            $table->json('marks_locked_for_periods')->nullable();
            $table->json('periods')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('is_active');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_years');
    }
};
