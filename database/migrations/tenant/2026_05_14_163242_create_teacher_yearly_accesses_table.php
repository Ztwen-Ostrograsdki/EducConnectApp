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
        Schema::disableForeignKeyConstraints();
        Schema::create('teacher_yearly_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained('school_years')->cascadeOnDelete();

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
            $table->string('suspension_reason')->nullable();          // motif de suspension
            $table->timestamp('suspended_at')->nullable();          // date de suspension
            $table->foreignId('suspended_by')->nullable()           // directeur qui a suspendu
                ->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Un teacher ne peut avoir qu'une entrée par année scolaire
            $table->unique(['teacher_id', 'school_year_id']);

            $table->index(['school_year_id', 'status']);
            $table->index('token');
            $table->index('token_expires_at');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('teacher_yearly_accesses');
        Schema::enableForeignKeyConstraints();
    }
};
