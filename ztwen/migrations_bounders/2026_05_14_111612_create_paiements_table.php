<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('tuteur_id')->nullable()->constrained('tuteurs')->nullOnDelete();
            $table->string('reference')->unique();               // numéro de reçu
            $table->enum('type', [
                'inscription',
                'scolarite',
                'transport',
                'cantine',
                'autre',
            ])->default('scolarite');
            $table->decimal('montant', 10, 2);
            $table->decimal('montant_paye', 10, 2)->default(0);
            $table->decimal('reste', 10, 2)->default(0);
            $table->enum('statut', ['en_attente', 'partiel', 'complet'])->default('en_attente');
            $table->enum('mode_paiement', [
                'especes',
                'mobile_money',
                'virement',
                'cheque',
                'autre',
            ])->nullable();
            $table->date('date_paiement')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('enregistre_par')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['eleve_id', 'annee_scolaire_id']);
            $table->index(['statut', 'type']);
            $table->index('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
