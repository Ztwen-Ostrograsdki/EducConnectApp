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
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('EducMaster')->default(null)->nullable();
            $table->string('nom');
            $table->string('prenoms');
            // contacts: tableau associatif ['nom et prenoms parent', 'lien parenté', 'contact', 'email']
            $table->json('contacts')->nullable()->default(null);
            $table->enum('gender', ['M', 'F'])->default('M');
            $table->date('birth_date')->nullable()->default(null);
            $table->string('birth_place')->nullable()->default(null);
            $table->string('nationality')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['nom', 'prenoms']);
            $table->index('matricule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
