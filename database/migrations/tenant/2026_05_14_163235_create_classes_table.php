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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->foreignId('school_year_id')->constrained('school_years')->cascadeOnDelete();
            $table->foreignId('promotion_id')->constrained('promotions');
            $table->foreignId('filiar_id')->nullable()->constrained('filiars')->nullOnDelete();
            $table->foreignId('serial_id')->nullable()->constrained('serials')->nullOnDelete();
            $table->string('name'); 
            $table->string('slug');                                     // ex: Terminale BTP 2
            $table->string('code')->nullable();                         // ex: TLE-BTP-2
            $table->enum('level', ['primaire', 'secondaire', 'superieur']);
            $table->integer('effectif_max')->default(50);
            $table->foreignId('principal_id')
                ->nullable()
                ->constrained('teachers')
                ->nullOnDelete();
            $table->foreignId('respo_1_id')                       // apprenant responsable
                ->nullable()
                ->constrained('students')
                ->nullOnDelete();
            $table->foreignId('respo_2_id')                       // apprenant responsable
                ->nullable()
                ->constrained('students')
                ->nullOnDelete();
            $table->string('localization')->nullable()->default(null);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_locked')->default(false); // on pourra empêcher l'accès aux enseignants momentanement
            $table->json('locked_for_teachers')->nullable(); // un tableau des id des enseignant sur lequels on pourra empêcher l'accès à certains enseignants momentanement
            $table->timestamps();
            $table->softDeletes();

            // Deux classes ne peuvent pas avoir le même nom dans la même année
            $table->unique(['school_year_id', 'name']);
            $table->index(['school_year_id', 'promotion_id']);
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
        Schema::dropIfExists('classes');
        Schema::enableForeignKeyConstraints();
    }
};
