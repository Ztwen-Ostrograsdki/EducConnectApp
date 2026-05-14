<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                              // ex: Terminale, Troisième, Sixième
            $table->string('code')->nullable();                 // ex: TLE, 3EME, 6EME
            $table->enum('niveau', ['primaire', 'secondaire', 'superieur']);
            $table->integer('ordre')->default(0);               // pour trier les promotions
            $table->boolean('est_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('nom');
            $table->index(['niveau', 'est_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
