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
        Schema::create('autorisation_tuteur_annees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tuteur_id')->constrained('tuteurs')->cascadeOnDelete();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->cascadeOnDelete();

            $table->boolean('blocked')->default(false);
            $table->text('blocked_reasons')->default("");

            // ─── Token ────────────────────────────────────────────────
            $table->string('token')->nullable()->unique();
            $table->timestamp('token_expires_at')->nullable();          // +72h
            $table->timestamp('token_requested_at')->nullable();
            $table->integer('token_attempts')->default(0);
            $table->timestamp('validated_at')->nullable();

            // ─── Statut annuel ────────────────────────────────────────
            $table->enum('status', [
                'pending',      // token envoyé, pas encore validé
                'active',       // accès OK pour cette année
                'expired',      // token expiré sans validation
                'suspended',    // suspendu par le directeur
                'inactive',     // enfant non inscrit cette année
            ])->default('pending');

            // ─── Suspension ───────────────────────────────────────────
            $table->text('suspension_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->foreignId('suspended_by')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Un tuteur ne peut avoir qu'une entrée par élève par année
            $table->unique(['tuteur_id', 'eleve_id', 'annee_scolaire_id']);
            $table->index(['annee_scolaire_id', 'status']);
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autorisation_tuteur_annees');
    }
};
