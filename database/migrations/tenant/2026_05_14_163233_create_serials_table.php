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
        Schema::create('serials', function (Blueprint $table) {
            $table->id();
            $table->string('slug'); 
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->string('name');                              // ex: A, B, C, D
            $table->string('code')->nullable();                 // ex: A, B, C
            $table->string('description')->nullable()->default(null);
            $table->boolean('is_active')->default(true);
            $table->unique('name');
            $table->index('is_active');
            $table->timestamps();
            $table->softDeletes();

        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('serials');
        Schema::enableForeignKeyConstraints();
    }
};
