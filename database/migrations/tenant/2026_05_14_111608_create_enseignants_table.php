<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('matricule')->unique();    // matricule
            $table->string('nom');
            $table->string('prenoms');
            $table->string('email')->unique();
            $table->string('contacts')->nullable()->default(null);
            $table->enum('gender', ['M', 'F'])->default('M');
            $table->date('birth_date')->nullable()->default(null);
            $table->string('birth_place')->nullable()->default(null);
            $table->string('nationality')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->string('photo')->nullable()->default(null);
            $table->json('specialties')->default(null)->nullable();
            $table->json('diploma')->default(null)->nullable();
            $table->boolean('blocked')->default(false);
            $table->text('blocked_reasons')->default("Non précisée");
 
            // Statut global de l'enseignant dans l'école
            // (indépendant du statut annuel)
            $table->enum('status', [
                'active',       // affilié et actif dans l'école
                'inactive',     // plus dans l'école
            ])->default('inactive');
 
            $table->timestamp('affiliated_at')->nullable();     // date d'affiliation initiale
            $table->timestamps();
            $table->softDeletes();
 
            $table->index(['status', 'nom']);
            $table->index('email');
        });

        
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
