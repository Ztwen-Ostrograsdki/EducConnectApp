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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->string('qr_code')->nullable();
            $table->string('EducMaster')->default(null)->nullable();
            $table->string('name');
            $table->string('prenames');
            // contacts: tableau associatif ['nom et prenoms parent', 'lien parenté', 'contact', 'email']
            $table->json('contacts')->nullable();
            $table->enum('gender', ['M', 'F'])->default('M');
            $table->date('birth_date')->nullable()->default(null);
            $table->string('birth_place')->nullable()->default(null);
            $table->string('nationality')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['name', 'prenames']);
            $table->index('matricule');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('students');
        Schema::enableForeignKeyConstraints();
    }
};
