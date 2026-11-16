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
        Schema::create('module_classe_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained('school_years')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->unsignedInteger('coef')->nullable()->default(null);
            $table->float('seuil')->default(10);
            $table->timestamps();

            $table->unique(
                ['school_year_id', 'module_id', 'classe_id'],
                'module_classe_validations_unique'
            );
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('module_classe_validations');
        Schema::enableForeignKeyConstraints();
    }
};
