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
        Schema::create('annees_scolaires', function (Blueprint $table) {
            $table->id();
            $table->string('slug');                          // ex: 2024-2025
            $table->date('start');
            $table->date('end');
            $table->enum('type_periode', ['semestre', 'trimestre'])->default('semestre');
            $table->boolean('is_active')->default(false);      // année en cours
            $table->boolean('is_closed')->default(false);    // année terminée, plus modifiable
            $table->timestamps();

            $table->unique('slug');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annee_scolaires');
    }
};
