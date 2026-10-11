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
        Schema::create('subject_animation_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained('school_years')->cascadeOnDelete();
            $table->foreignId('filiar_id')->nullable()->constrained('filiars')->nullOnDelete();
            $table->foreignId('serial_id')->nullable()->constrained('serials')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('day_of_week'); // 1=lundi ... 7=dimanche
            $table->time('starts_at');
            $table->time('ends_at');
            $table->string('label')->default('AP');
            $table->text('notes')->nullable('Animation Pédagogique Hebdomadaire (AP-H)');
            $table->timestamps();
            $table->unique(['school_year_id', 'subject_id']);
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
        Schema::dropIfExists('subject_animation_times');
        Schema::enableForeignKeyConstraints();
    }
};
