<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                              // ex: BTP, Informatique
            $table->string('code')->nullable();                 // ex: BTP, INFO
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
        Schema::dropIfExists('filieres');
    }
};
