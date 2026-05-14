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
        Schema::create('autorisation_enseignant_annees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enseignant_id')->constrained('enseignants')->cascadeOnDelete();
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->cascadeOnDelete();
 
            // ─── Token de validation annuel ───────────────────────────
            $table->string('token')->nullable()->unique();
            $table->timestamp('token_expires_at')->nullable();      // +72h après génération
            $table->timestamp('token_requested_at')->nullable();    // dernière demande de renouvellement
            $table->integer('token_attempts')->default(0);          // nb de renouvellements demandés
            $table->timestamp('validated_at')->nullable();          // date de validation du token
 
            // ─── Statut annuel ────────────────────────────────────────
            $table->enum('status', [
                'pending',      // token envoyé, en attente de validation
                'active',       // token validé, accès OK pour cette année
                'expired',      // token expiré sans validation
                'suspended',    // suspendu manuellement par le directeur
                'inactive',     // retiré de l'année (plus de classes assignées)
            ])->default('pending');
 
            // ─── Suspension ───────────────────────────────────────────
            $table->text('suspension_reason')->nullable();          // motif de suspension
            $table->timestamp('suspended_at')->nullable();          // date de suspension
            $table->foreignId('suspended_by')->nullable()           // directeur qui a suspendu
                  ->constrained('users')->nullOnDelete();
 
            $table->timestamps();
 
            // Un enseignant ne peut avoir qu'une entrée par année scolaire
            $table->unique(['enseignant_id', 'annee_scolaire_id']);
 
            $table->index(['annee_scolaire_id', 'status']);
            $table->index('token');
            $table->index('token_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autorisation_enseignant_annees');
    }
};
