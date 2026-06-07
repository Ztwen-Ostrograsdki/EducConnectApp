<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->text('qr_code')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('identifiant')->unique();    // matricule
            $table->json('identity_card_details')->nullable();
            $table->string('email')->unique();
            $table->json('specialties')->nullable();
            $table->json('diploma')->nullable();
            $table->boolean('blocked')->default(false);
            $table->string('blocked_reasons')->default('Non précisée');

            // Statut global de l'enseignant dans l'école
            // (indépendant du statut annuel)
            $table->enum('status', [
                'active',       // affilié et actif dans l'école
                'unactive',     // plus dans l'école
            ])->default('unactive');

            $table->timestamp('affiliated_at')->nullable();     // date d'affiliation initiale
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index('email');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('teachers');
        Schema::enableForeignKeyConstraints();
    }
};
