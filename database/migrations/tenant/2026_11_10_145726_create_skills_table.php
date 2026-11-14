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
        Schema::disableForeignKeyConstraints();
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable('Enoncé de la compétence');
            $table->string('weight')->nullable()->default(null);
            $table->unsignedInteger('coef')->nullable()->default(null);
            $table->string('code')->nullable()->default(null);
            $table->boolean('is_active')->default(true);
            $table->foreignId('filiar_id')->nullable()->constrained('filiars')->nullOnDelete();
            $table->foreignId('serial_id')->nullable()->constrained('serials')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('skills');
        Schema::enableForeignKeyConstraints();
    }
};
