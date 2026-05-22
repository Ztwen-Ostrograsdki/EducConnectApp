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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->enum('type', ['scientifique', 'litteraire', 'sportive', 'informatique', 'culture'])->default('scientifique');
            $table->enum('level', ['primaire', 'secondaire', 'superieur'])->default('secondaire');
            $table->string('slug');
            $table->string('name');                              // ex: Mathématiques
            $table->string('code')->nullable();                 // ex: MATH
            $table->string('description')->nullable()->default(null);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
            
            $table->index('is_active');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('subjects');
        Schema::enableForeignKeyConstraints();
    }
};
