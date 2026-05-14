<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tuteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->nullable()->unique();
            $table->string('telephone')->nullable();
            $table->string('telephone_whatsapp')->nullable();
            $table->enum('sexe', ['M', 'F']);
            $table->string('profession')->nullable();
            $table->text('adresse')->nullable();
            $table->enum('lien_parente', ['pere', 'mere', 'tuteur', 'autre'])->default('tuteur');
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->string('token_invitation')->nullable()->unique();
            $table->timestamp('token_expire_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nom', 'prenom']);
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tuteurs');
    }
};
