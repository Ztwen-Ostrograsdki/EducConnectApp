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
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->enum('sexe', ['M', 'F']);
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->text('adresse')->nullable();
            $table->string('photo')->nullable();
            $table->string('specialite')->nullable();
            $table->string('diplome')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'invite'])->default('invite');
            $table->string('token_invitation')->nullable()->unique(); // lien d'invitation
            $table->timestamp('token_expire_at')->nullable();
            $table->timestamp('affilie_at')->nullable();             // date d'affiliation
            $table->timestamps();
            $table->softDeletes();

            $table->index(['statut', 'nom']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignants');

        
    }
};
