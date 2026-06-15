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
            $table->text('qr_code')->nullable();
            $table->string('educMaster')->default(null)->nullable();
            $table->string('name');
            $table->string('prenames');
            $table->string('contacts')->nullable()->default(null);
            $table->string('gender')->nullable()->default('Masculin');
            $table->date('birth_date')->nullable()->default(null);
            $table->string('birth_place')->nullable()->default(null);
            $table->string('country')->nullable()->default(null);
            $table->string('adresse')->nullable()->default(null);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email')->nullable()->unique();
            $table->string('city')->nullable()->default(null);
            $table->string('department')->nullable()->default(null);
            $table->string('profil_photo')->nullable()->default(null);
            $table->string('father_full_name')->nullable()->default(null);
            $table->string('mother_full_name')->nullable()->default(null);
            $table->enum('status', ['active', 'unactive'])->default('unactive');
            $table->boolean('is_active')->default(false);
            $table->boolean('blocked')->default(false);
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
