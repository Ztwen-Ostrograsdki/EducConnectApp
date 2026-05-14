<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained('notes')->cascadeOnDelete();
            $table->foreignId('editeur_id')->nullable()         // qui a modifié
                  ->constrained('users')->nullOnDelete();
            $table->decimal('ancienne_valeur', 5, 2);           // valeur avant modification
            $table->decimal('nouvelle_valeur', 5, 2);           // valeur après modification
            $table->text('motif')->nullable();                   // justification de la modification
            $table->boolean('autorise_par_directeur')->default(false);
            $table->foreignId('autorise_par')->nullable()        // directeur qui a autorisé
                  ->constrained('users')->nullOnDelete();
            $table->timestamp('autorise_at')->nullable();
            $table->timestamps();

            $table->index(['note_id', 'created_at']);
            $table->index('editeur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_historiques');
    }
};
