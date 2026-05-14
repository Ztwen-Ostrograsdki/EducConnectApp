<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des matières
        Schema::create('matieres', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');                              // ex: Mathématiques
            $table->string('code')->nullable();                 // ex: MATH
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
            $table->index('is_active');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('matieres');
    }
};
