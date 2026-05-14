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
        Schema::create('relation_eleve_tuteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->foreignId('tuteur_id')->constrained('tuteurs')->cascadeOnDelete();
            $table->string('parent_realtion')->default('tuteur');
            $table->boolean('contact_primaire')->default(false);
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->boolean('locked')->default(false);
            $table->unique(['eleve_id', 'tuteur_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relation_eleve_tuteurs');
    }
};
