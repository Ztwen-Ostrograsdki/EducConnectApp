<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->enum('sexe', ['M', 'F']);
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('nationalite')->nullable();
            $table->string('photo')->nullable();
            $table->text('adresse')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nom', 'prenom']);
            $table->index('matricule');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
