<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                              // ex: A, B, C, D
            $table->string('code')->nullable();                 // ex: A, B, C
            $table->text('description')->nullable();
            $table->boolean('est_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('nom');
            $table->index('est_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
