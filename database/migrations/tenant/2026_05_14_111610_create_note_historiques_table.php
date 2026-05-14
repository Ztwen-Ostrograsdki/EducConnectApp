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
        Schema::create('note_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained('notes')->cascadeOnDelete();
            $table->foreignId('editor_id')->nullable()         // qui a modifié
                  ->constrained('users')->nullOnDelete();
            $table->decimal('old_value', 5, 2);           // valeur avant modification
            $table->decimal('new_value', 5, 2);           // valeur après modification
            $table->text('motif')->nullable()->default(null);                   // justification de la modification
            $table->boolean('authorized_by_director')->default(false);
            $table->foreignId('authorized_by')->nullable()        // directeur qui a autorisé
                  ->constrained('users')->nullOnDelete();
            $table->timestamp('authorized_at')->nullable();
            $table->timestamps();

            $table->index(['note_id', 'created_at']);
            $table->index('editor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_historiques');
    }
};
