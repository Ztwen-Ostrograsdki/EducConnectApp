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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->string('name');                              // ex: Terminale, Troisième, Sixième
            $table->string('code')->nullable();                 // ex: TLE, 3EME, 6EME
            $table->enum('level', ['primaire', 'secondaire', 'superieur']);
            $table->integer('order')->default(1);               // pour trier les promotions
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
            $table->index(['level', 'is_active']);
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('promotions');
        Schema::enableForeignKeyConstraints();
    }
};
