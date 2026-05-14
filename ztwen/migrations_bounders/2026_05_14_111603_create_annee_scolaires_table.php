<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annees_scolaires', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');                          // ex: 2024-2025
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('type_periode', ['semestre', 'trimestre'])->default('semestre');
            $table->boolean('est_active')->default(false);      // année en cours
            $table->boolean('est_cloturee')->default(false);    // année terminée, plus modifiable
            $table->timestamps();

            $table->unique('libelle');
            $table->index('est_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annees_scolaires');
    }
};
