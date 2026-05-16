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
        Schema::create('tutor_yearly_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained('school_years')->cascadeOnDelete();

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

            // Un tutor ne peut avoir qu'une entrée par élève par année
            $table->unique(['tutor_id', 'student_id', 'school_year_id']);
            $table->index(['school_year_id', 'status']);
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_yearly_accesses');
    }
};
